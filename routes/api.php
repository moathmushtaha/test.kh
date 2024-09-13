<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sanctum/token', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    //chat routes
    Route::post('/chat/new-message', [ChatController::class, 'newMessage']);
    Route::post('/chat/messages-history', [ChatController::class, 'messagesHistory']);
});
