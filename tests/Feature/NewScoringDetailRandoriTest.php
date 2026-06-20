<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('new scoring detail randori page requires authentication', function () {
    $this->withoutVite();

    $response = $this->get('/admin/panitera/scoring/randori-result');

    $response->assertRedirect('/login');
});

test('new scoring detail randori page returns inertia response for authenticated users', function () {
    $this->withoutVite();

    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->get('/admin/panitera/scoring/randori-result');

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('NewScoringDetailRandori')
        );
});
