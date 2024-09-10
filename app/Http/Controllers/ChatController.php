<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessagesHistoryRequest;
use App\Http\Requests\NewMessageRequest;
use App\Models\Chat;

class ChatController extends Controller
{
    /**
     * Create a new message
     *
     * @param NewMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newMessage(NewMessageRequest $request)
    {
        $chat = Chat::create($request->all());

        return response()->json($chat, 201);
    }

    /**
     * Get all messages history between the authenticated user and other users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function MessagesHistory(GetMessagesHistoryRequest $request)
    {
        $userId = auth()->id();
        $withUserId = $request->with_user_id;

        // Fetch messages between the authenticated user and the specified user
        $messages = Chat::where(function ($query) use ($withUserId, $userId, $request) {
            $query->where('from_user_id', $userId)
                ->where('to_user_id', $withUserId);
        })->orWhere(function ($query) use ($withUserId, $userId, $request) {
            $query->where('from_user_id', $withUserId)
                ->where('to_user_id', $userId);
        })->orderBy('created_at', 'asc')
            ->get();

        // Count unread messages for the current user
        $status_count = $messages->where('to_user_id',auth()->id())->where('status', 1)->count();

        // Get messages list
        $messages = $messages->map(function ($message) use ($userId) {
            return [
                'direction' => $message->from_user_id == $userId ? 'outgoing' : 'incoming',
                'text' => $message->message,
                'status' => $message->status,
            ];
        });

        // Update the status of incoming messages to read
        Chat::where('to_user_id', auth()->id())
            ->where('from_user_id', $withUserId)
            ->where('status', 1)
            ->update(['status' => 0]);

        return response()->json([
            'messages' => $messages,
            'status_count' => $status_count
        ]);
    }

}
