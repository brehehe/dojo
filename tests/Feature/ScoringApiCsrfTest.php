<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('scoring API endpoints bypass CSRF validation', function () {
    $user = User::factory()->create();

    // Send a POST request to a scoring API route without a CSRF token
    $response = $this->actingAs($user)
        ->postJson('/admin/api/scoring/embu/call-participant', [
            'drawing_id' => 1,
        ]);

    // If CSRF is working properly as exempted, it will not return 419.
    expect($response->status())->not->toBe(419);
});

test('referee scoring API endpoints bypass CSRF validation', function () {
    $user = User::factory()->create();

    // Send a POST request to a referee scoring route without a CSRF token
    $response = $this->actingAs($user)
        ->postJson('/admin/referee/scoring/save', [
            'embuItems' => [],
            'notes' => '',
        ]);

    // If CSRF is working properly as exempted, it will not return 419.
    expect($response->status())->not->toBe(419);
});
