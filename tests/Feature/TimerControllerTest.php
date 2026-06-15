<?php

use App\Models\Court\Court;
use App\Models\User;

test('timer control with valid start action returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'court_id' => $court->id,
            'action' => 'start',
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonStructure([
            'success',
            'timer_state' => ['status', 'elapsed_ms', 'server_time_ms'],
        ]);
});

test('timer control with valid stop action returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'court_id' => $court->id,
            'action' => 'stop',
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'timer_state' => ['status' => 'stopped'],
        ]);
});

test('timer control with valid pause action returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    // First start the timer
    $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'court_id' => $court->id,
            'action' => 'start',
        ]);

    // Then pause it
    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'court_id' => $court->id,
            'action' => 'pause',
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('timer control with valid countdown action returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'court_id' => $court->id,
            'action' => 'countdown',
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'timer_state' => ['status' => 'countdown'],
        ]);
});

test('timer control without court_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'action' => 'start',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['court_id']);
});

test('timer control with invalid action returns 422', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'court_id' => $court->id,
            'action' => 'invalid_action',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['action']);
});

test('timer control with non-existent court_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/timer-control', [
            'court_id' => 99999,
            'action' => 'start',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['court_id']);
});

test('timer control requires authentication', function () {
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->postJson('/admin/api/scoring/timer-control', [
        'court_id' => $court->id,
        'action' => 'start',
    ]);

    $response->assertStatus(401);
});
