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
        $messages = Chat::where(function ($query) use ($request) {
            $query->where('from_user_id', auth()->id())
                ->where('to_user_id', $request->with_user_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('from_user_id', $request->with_user_id)
                ->where('to_user_id', auth()->id());
        })->orderBy('created_at', 'asc')
            ->get();

        //count the number of unread messages
        $status_count = $messages->where('status', 1)->count();

        //get messages list
        $messages = $messages->map(function ($message) {
            return [
                'direction' => $message->from_user_id == auth()->id() ? 'outgoing' : 'incoming',
                'text' => $message->message,
                'status' => $message->status,
            ];
        });

        return response()->json([
            'messages' => $messages,
            'status_count' => $status_count
        ]);
    }

}
