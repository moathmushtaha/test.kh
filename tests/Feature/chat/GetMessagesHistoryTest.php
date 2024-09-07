<?php

use App\Models\Chat;
use App\Models\User;

//guest can't get messages history
test('guest can\'t get messages history', function () {
    $response = $this->getJson('/api/chat/messages-history?with_user_id=1');

    $response->assertStatus(401);
});

//authenticated user can get his messages history with other users
test('authenticated user can get his messages history', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson('/api/chat/messages-history?with_user_id=1');

    $response->assertStatus(200);
});

//After get messages history, all incoming messages should be marked as read
test('after get messages history, all incoming messages should be marked as read', function () {
    $from_user_id = User::factory()->create();
    $to_user_id = User::factory()->create();

    $this->actingAs($to_user_id);

    Chat::factory()->create([
        'from_user_id' => $from_user_id,
        'to_user_id' => $to_user_id,
        'status' => 1
    ]);

    $response = $this->getJson('/api/chat/messages-history?with_user_id='.$from_user_id->id);
    $response->assertJsonFragment(['status_count' => 1]);

    $response = $this->getJson('/api/chat/messages-history?with_user_id='.$from_user_id->id);
    $response->assertJsonFragment(['status_count' => 0]);
});
