<?php

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('scoring index renders svelte dashboard page', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->get(route('admin.new-scoring-index'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('ScoringDashboard')
        );
});

test('scoring embu renders svelte embu page', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
        'price' => 0,
    ]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.new-scoring-embu-index', $matchNumber->id));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('ScoringEmbu')
            ->has('matchId')
        );
});

test('scoring randori renders svelte randori page', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
        'price' => 0,
    ]);
    $matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.new-scoring-randori-index', $matchNumber->id));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('ScoringRandori')
            ->has('matchId')
        );
});
