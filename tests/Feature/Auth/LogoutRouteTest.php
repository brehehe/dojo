<?php

use App\Models\User;

test('guest accessing logout is redirected to login', function () {
    $response = $this->get('/logout');

    $response->assertRedirect('/login');
});

test('authenticated user accessing logout is logged out and redirected to login', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->assertAuthenticatedAs($user);

    $response = $this->get('/logout');

    $response->assertRedirect('/login');

    $this->assertGuest();
});
