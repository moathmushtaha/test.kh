<?php

namespace App\Http\Controllers;

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
}
