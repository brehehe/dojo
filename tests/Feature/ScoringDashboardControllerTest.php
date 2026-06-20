<?php

use App\Models\Court\Court;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('scoring dashboard index returns inertia response', function () {
    $this->withoutVite();

    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->get('/admin/new-scoring');

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('ScoringDashboard')
        );
});

test('scoring dashboard requires authentication', function () {
    $this->withoutVite();

    $response = $this->get('/admin/new-scoring');

    $response->assertRedirect('/login');
});

test('clear court with valid court_id returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/clear-court', [
            'court_id' => $court->id,
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonPath('text', $court->name.' sekarang idle / kosong.');
});

test('clear court resets active fields on court', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $court = Court::create([
        'name' => 'Lapangan A',
        'order' => 1,
        'active_match_id' => $matchNumber->id,
    ]);

    $this->actingAs($admin)
        ->postJson('/admin/api/scoring/clear-court', [
            'court_id' => $court->id,
        ]);

    $court->refresh();

    expect($court->active_match_id)->toBeNull()
        ->and($court->active_drawing_id)->toBeNull()
        ->and($court->active_registration_id)->toBeNull()
        ->and($court->active_bracket_node)->toBeNull();
});

test('clear court without court_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/clear-court', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['court_id']);
});

test('clear court with non-existent court_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/clear-court', [
            'court_id' => 99999,
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['court_id']);
});

test('clear court requires authentication', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->postJson('/admin/api/scoring/clear-court', [
        'court_id' => $court->id,
    ]);

    $response->assertStatus(401);
});
