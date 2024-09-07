<?php

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
