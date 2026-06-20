<?php

use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\User;

test('embu scoring state returns 200 with json', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $response = $this->actingAs($admin)
        ->getJson("/admin/api/scoring/embu/{$matchNumber->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'matchNumber' => ['id', 'name'],
            'displayName',
            'currentRound',
            'registrations',
            'timerState',
        ]);
});

test('embu scoring state with non-existent match returns 404', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->getJson('/admin/api/scoring/embu/99999/state');

    $response->assertStatus(404);
});

test('embu save score with valid data returns 200', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $contingent = Contingent::create([
        'name' => 'Surabaya A',
        'leader_name' => 'Leader A',
        'leader_phone' => '0812345678',
        'leader_nik' => '1234567890123456',
    ]);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $registration->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/save-score', [
            'match_id' => $matchNumber->id,
            'registration_id' => $registration->id,
            'round' => 'Penyisihan',
            'drawing_id' => $drawing->id,
            'scores' => [
                'judge_1' => 7.0,
                'judge_2' => 7.5,
                'judge_3' => 8.0,
                'judge_4' => 7.0,
                'judge_5' => 7.5,
            ],
            'denda' => 0,
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('embu save score without match_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/save-score', [
            'registration_id' => 1,
            'scores' => ['judge_1' => 7.0],
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['match_id']);
});

test('embu save score without scores returns 422', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $contingent = Contingent::create([
        'name' => 'Surabaya A',
        'leader_name' => 'Leader',
        'leader_phone' => '0812345678',
        'leader_nik' => '1234567890123456',
    ]);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/save-score', [
            'match_id' => $matchNumber->id,
            'registration_id' => $registration->id,
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['scores']);
});

test('embu call participant without drawing_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/call-participant', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['drawing_id']);
});

test('embu call participant with valid drawing_id returns 200', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $contingent = Contingent::create([
        'name' => 'Surabaya A',
        'leader_name' => 'Leader A',
        'leader_phone' => '0812345678',
        'leader_nik' => '1234567890123456',
    ]);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $registration->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/call-participant', [
            'drawing_id' => $drawing->id,
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('embu scoring endpoints require authentication', function () {
    $response = $this->getJson('/admin/api/scoring/embu/1/state');
    $response->assertStatus(401);

    $response = $this->postJson('/admin/api/scoring/embu/save-score', []);
    $response->assertStatus(401);

    $response = $this->postJson('/admin/api/scoring/embu/call-participant', []);
    $response->assertStatus(401);
});

test('embu finish match calculates denda correctly based on Pemula and Tandoku rules', function () {
    $admin = User::factory()->create();
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    // Case 1: Pemula Group Match (should follow 60-90s rules: 70 seconds -> 0 penalty)
    $pemulaGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1, 'price' => 0]);
    $pemulaMatch = MatchNumber::create([
        'name' => 'Embu Pasangan Pemula',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $pemulaGroup->id,
    ]);
    $contingent1 = Contingent::create(['name' => 'Sby A', 'leader_name' => 'L1', 'leader_phone' => '123', 'leader_nik' => '1234567890123456']);
    $reg1 = Registration::create(['contingent_id' => $contingent1->id]);
    $drawing1 = DrawingMatchNumber::create([
        'match_number_id' => $pemulaMatch->id,
        'registration_id' => $reg1->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    $response1 = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/finish-match', [
            'drawing_id' => $drawing1->id,
            'time_ms' => 70000, // 70 seconds
            'round' => 'Penyisihan',
        ]);
    $response1->assertSuccessful()
        ->assertJson([
            'success' => true,
            'denda' => 0,
        ]);

    // Case 2: Dewasa Group Match (should follow 90-120s rules: 70 seconds -> 10 penalty)
    $dewasaGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 2, 'price' => 0]);
    $dewasaMatch = MatchNumber::create([
        'name' => 'Embu Pasangan Dewasa',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 2,
        'age_group_id' => $dewasaGroup->id,
    ]);
    $contingent2 = Contingent::create(['name' => 'Sby B', 'leader_name' => 'L2', 'leader_phone' => '456', 'leader_nik' => '1234567890123457']);
    $reg2 = Registration::create(['contingent_id' => $contingent2->id]);
    $drawing2 = DrawingMatchNumber::create([
        'match_number_id' => $dewasaMatch->id,
        'registration_id' => $reg2->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 2,
        'round' => 'Penyisihan',
    ]);

    $response2 = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/finish-match', [
            'drawing_id' => $drawing2->id,
            'time_ms' => 70000, // 70 seconds
            'round' => 'Penyisihan',
        ]);
    $response2->assertSuccessful()
        ->assertJson([
            'success' => true,
            'denda' => 10,
        ]);

    // Case 3: Dewasa Tandoku Match (should follow 60-90s rules: 70 seconds -> 0 penalty)
    $tandokuMatch = MatchNumber::create([
        'name' => 'Embu Tandoku Dewasa',
        'draft_type' => 'embu',
        'max_athletes' => 1,
        'order' => 3,
        'age_group_id' => $dewasaGroup->id,
    ]);
    $contingent3 = Contingent::create(['name' => 'Sby C', 'leader_name' => 'L3', 'leader_phone' => '789', 'leader_nik' => '1234567890123458']);
    $reg3 = Registration::create(['contingent_id' => $contingent3->id]);
    $drawing3 = DrawingMatchNumber::create([
        'match_number_id' => $tandokuMatch->id,
        'registration_id' => $reg3->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 3,
        'round' => 'Penyisihan',
    ]);

    $response3 = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/embu/finish-match', [
            'drawing_id' => $drawing3->id,
            'time_ms' => 70000, // 70 seconds
            'round' => 'Penyisihan',
        ]);
    $response3->assertSuccessful()
        ->assertJson([
            'success' => true,
            'denda' => 0,
        ]);
});
