<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessagesHistoryRequest;
use App\Http\Requests\NewMessageRequest;
use App\Models\Chat;
use App\Services\GeoIPService;

class ChatController extends Controller
{
    protected $geoIPService;

    public function __construct(GeoIPService $geoIPService)
    {
        $this->geoIPService = $geoIPService;
    }

    public function newMessage(NewMessageRequest $request)
    {
        $chat = Chat::create($request->all());
        $current_user_location = $this->getCurrentUserLocation($request);

        auth()->user()->updateLocation($current_user_location);

        return response()->json($chat, 201);
    }

    public function messagesHistory(GetMessagesHistoryRequest $request)
    {
        $user = auth()->user();
        $withUserId = $request->with_user_id;

        $messages = $user->getMessagesHistory($withUserId);
        $with_user = $messages->where('from_user_id', $withUserId)->first()->user;
        $status_count = $this->getUnreadMessagesCount($messages);
        $ids_need_update = $this->getIdsNeedUpdate($messages, $withUserId);
        $messages = $this->getMessageDetails($messages);

        $current_user_location = $this->getcurrentUserLocation($request);
        $distance = $user->getDistanceTo($with_user);
        $user->updateLocation($current_user_location);

        Chat::whereIn('id', $ids_need_update)->update(['status' => 0]);

        return response()->json([
            'messages' => $messages,
            'status_count' => $status_count,
            'distance' => $distance
        ]);
    }

    public function getCurrentUserLocation($request)
    {
        if (is_null($request->latitude) && is_null($request->longitude)) {
            return $this->geoIPService->getLocationFromIp($request->ip());
        }

        return [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
    }

    public function getUnreadMessagesCount($messages)
    {
        return $messages->where('to_user_id', auth()->id())->where('status', 1)->count();
    }

    public function getIdsNeedUpdate($messages, $withUserId)
    {
        return $messages
            ->where('to_user_id', auth()->id())
            ->where('from_user_id', $withUserId)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();
    }

    public function getMessageDetails($messages)
    {
        return $messages->map(function ($message) {
            return [
                'direction' => $message->from_user_id == auth()->id() ? 'outgoing' : 'incoming',
                'text' => $message->message,
                'status' => $message->status,
            ];
        });
    }



}
