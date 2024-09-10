<?php

use App\Models\User;

//guest can't add new message
test('guest can\'t add new message', function () {
    $response = $this->postJson('/api/chat/new-message', [
        'message' => 'Hello',
        'to_user_id' => 1
    ]);

    $response->assertStatus(401);
});

//authenticated user can add new message
test('authenticated user can add new message', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson('/api/chat/new-message', [
        'message' => 'Hello',
        'to_user_id' => 1
    ]);

    $response->assertStatus(201);
});
