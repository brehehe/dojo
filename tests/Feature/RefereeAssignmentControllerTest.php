<?php

use App\Models\ActiveCourtReferee;
use App\Models\Court\Court;
use App\Models\Referee;
use App\Models\Rundown\Rundown;
use App\Models\ScheduleReferee;
use App\Models\SessionTime;
use App\Models\User;

test('save referee assignment with valid data returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $refereeIds = [];
    for ($i = 0; $i < 5; $i++) {
        $user = User::factory()->create();
        $referee = Referee::create(['user_id' => $user->id, 'certification_level' => 'Daerah']);
        $refereeIds[] = $referee->id;
    }

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/save-referee-assignment', [
            'court_id' => $court->id,
            'rundown_id' => $rundown->id,
            'session_time_id' => $session->id,
            'referees' => $refereeIds,
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('save referee assignment creates schedule and active records', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $refereeIds = [];
    for ($i = 0; $i < 5; $i++) {
        $user = User::factory()->create();
        $referee = Referee::create(['user_id' => $user->id, 'certification_level' => 'Daerah']);
        $refereeIds[] = $referee->id;
    }

    $this->actingAs($admin)
        ->postJson('/admin/api/scoring/save-referee-assignment', [
            'court_id' => $court->id,
            'rundown_id' => $rundown->id,
            'session_time_id' => $session->id,
            'referees' => $refereeIds,
        ]);

    expect(ScheduleReferee::where('court_id', $court->id)
        ->where('rundown_id', $rundown->id)
        ->where('session_time_id', $session->id)
        ->count())->toBe(5);

    expect(ActiveCourtReferee::where('court_id', $court->id)->count())->toBe(5);
});

test('save referee assignment without court_id returns 422', function () {
    $admin = User::factory()->create();
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/save-referee-assignment', [
            'rundown_id' => $rundown->id,
            'session_time_id' => $session->id,
            'referees' => [1, 2, 3, 4, 5],
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['court_id']);
});

test('save referee assignment without referees returns 422', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/save-referee-assignment', [
            'court_id' => $court->id,
            'rundown_id' => $rundown->id,
            'session_time_id' => $session->id,
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['referees']);
});

test('save referee assignment with wrong number of referees returns 422', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/save-referee-assignment', [
            'court_id' => $court->id,
            'rundown_id' => $rundown->id,
            'session_time_id' => $session->id,
            'referees' => [1, 2, 3], // only 3 instead of 5
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['referees']);
});

test('reset active referees with valid court_id returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/reset-active-referees', [
            'court_id' => $court->id,
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('reset active referees without court_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/reset-active-referees', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['court_id']);
});

test('reset court referees with all required params returns 200', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/reset-court-referees', [
            'court_id' => $court->id,
            'rundown_id' => $rundown->id,
            'session_time_id' => $session->id,
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('reset court referees without required params returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/reset-court-referees', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['court_id', 'rundown_id', 'session_time_id']);
});

test('reset court referees without session_time_id returns 422', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/reset-court-referees', [
            'court_id' => $court->id,
            'rundown_id' => $rundown->id,
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['session_time_id']);
});

test('scoring endpoints require authentication', function () {
    $response = $this->postJson('/admin/api/scoring/save-referee-assignment', []);
    $response->assertStatus(401);

    $response = $this->postJson('/admin/api/scoring/reset-active-referees', []);
    $response->assertStatus(401);

    $response = $this->postJson('/admin/api/scoring/reset-court-referees', []);
    $response->assertStatus(401);
});
