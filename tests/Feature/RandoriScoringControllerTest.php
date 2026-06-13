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

test('randori scoring state returns 200 with json', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
        'drawing_data' => [
            'bracket_type' => 'double_elimination',
            'upper_bracket' => ['rounds' => []],
            'lower_bracket' => ['rounds' => []],
        ],
    ]);

    $response = $this->actingAs($admin)
        ->getJson("/admin/api/scoring/randori/{$matchNumber->id}/state");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'matchNumber' => ['id', 'name'],
            'displayName',
            'drawingData',
            'timerState',
            'randoriResults',
        ]);
});

test('randori scoring state with non-existent match returns 404', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->getJson('/admin/api/scoring/randori/99999/state');

    $response->assertStatus(404);
});

test('randori submit scoring without match_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/randori/submit-scoring', [
            'bracket' => 'ub',
            'round' => 0,
            'match' => 0,
            'score_red' => 10,
            'score_blue' => 5,
            'signatures' => [
                'arbitrase' => ['name' => 'Arb', 'signature' => 'data:test'],
                'koordinator' => ['name' => 'Kor', 'signature' => 'data:test'],
                'wasit' => ['name' => 'Was', 'signature' => 'data:test'],
                'panitera' => [['name' => 'Pan', 'signature' => 'data:test']],
                'manager_red' => ['name' => 'MR', 'signature' => 'data:test'],
                'manager_white' => ['name' => 'MW', 'signature' => 'data:test'],
            ],
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['match_id']);
});

test('randori submit scoring without bracket returns 422', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/randori/submit-scoring', [
            'match_id' => $matchNumber->id,
            'round' => 0,
            'match' => 0,
            'score_red' => 10,
            'score_blue' => 5,
            'signatures' => [
                'arbitrase' => ['name' => 'Arb', 'signature' => 'data:test'],
            ],
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['bracket']);
});

test('randori submit scoring with valid data returns 200', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);

    $drawingData = [
        'bracket_type' => 'double_elimination',
        'upper_bracket' => [
            'rounds' => [
                [
                    [
                        'athlete1' => [
                            'id' => 10,
                            'name' => 'Ahmad Red',
                            'contingent' => 'Surabaya',
                            'registration_id' => null,
                            'match_number_id' => null,
                        ],
                        'athlete2' => [
                            'id' => 11,
                            'name' => 'Budi White',
                            'contingent' => 'Gresik',
                            'registration_id' => null,
                            'match_number_id' => null,
                        ],
                        'winner' => null,
                        'winner_data' => null,
                        'winner_next' => ['bracket' => 'ranked', 'rank' => 1],
                        'loser_next' => ['bracket' => 'ranked', 'rank' => 3],
                    ],
                ],
            ],
        ],
        'lower_bracket' => ['rounds' => []],
        'juara' => [],
    ];

    $matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
        'drawing_data' => $drawingData,
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

    DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $registration->id,
        'draft_type' => 'randori',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/randori/submit-scoring', [
            'match_id' => $matchNumber->id,
            'bracket' => 'ub',
            'round' => 0,
            'match' => 0,
            'score_red' => 10,
            'score_blue' => 5,
            'scoring_aka' => 'Ippon',
            'scoring_shiro' => '',
            'signatures' => [
                'arbitrase' => ['name' => 'Arbitrase Name', 'signature' => 'data:image/png;base64,abc'],
                'koordinator' => ['name' => 'Koordinator Name', 'signature' => 'data:image/png;base64,abc'],
                'wasit' => ['name' => 'Wasit Name', 'signature' => 'data:image/png;base64,abc'],
                'panitera' => [['name' => 'Panitera Name', 'signature' => 'data:image/png;base64,abc']],
                'manager_red' => ['name' => 'Manager Red', 'signature' => 'data:image/png;base64,abc'],
                'manager_white' => ['name' => 'Manager White', 'signature' => 'data:image/png;base64,abc'],
            ],
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('randori submit scoring with equal scores returns 400', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
        'drawing_data' => [
            'bracket_type' => 'double_elimination',
            'upper_bracket' => ['rounds' => [[]]],
            'lower_bracket' => ['rounds' => []],
        ],
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/randori/submit-scoring', [
            'match_id' => $matchNumber->id,
            'bracket' => 'ub',
            'round' => 0,
            'match' => 0,
            'score_red' => 5,
            'score_blue' => 5,
            'signatures' => [
                'arbitrase' => ['name' => 'Arb', 'signature' => 'data:test'],
                'koordinator' => ['name' => 'Kor', 'signature' => 'data:test'],
                'wasit' => ['name' => 'Was', 'signature' => 'data:test'],
                'panitera' => [['name' => 'Pan', 'signature' => 'data:test']],
                'manager_red' => ['name' => 'MR', 'signature' => 'data:test'],
                'manager_white' => ['name' => 'MW', 'signature' => 'data:test'],
            ],
        ]);

    $response->assertStatus(400)
        ->assertJson([
            'success' => false,
        ]);
});

test('randori confirm champion without match_id returns 422', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/randori/confirm-champion', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['match_id']);
});

test('randori confirm champion with valid match returns 200', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);

    $contingent1 = Contingent::create([
        'name' => 'Surabaya A',
        'leader_name' => 'Leader A',
        'leader_phone' => '0812345678',
        'leader_nik' => '1234567890123456',
    ]);
    $registration1 = Registration::create(['contingent_id' => $contingent1->id]);

    $contingent2 = Contingent::create([
        'name' => 'Gresik A',
        'leader_name' => 'Leader B',
        'leader_phone' => '0812345679',
        'leader_nik' => '1234567890123457',
    ]);
    $registration2 = Registration::create(['contingent_id' => $contingent2->id]);

    $matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
        'drawing_data' => [
            'bracket_type' => 'double_elimination',
            'upper_bracket' => ['rounds' => []],
            'lower_bracket' => ['rounds' => []],
            'juara' => [
                1 => [
                    'id' => 10,
                    'name' => 'Juara 1',
                    'contingent' => 'Surabaya A',
                    'registration_id' => $registration1->id,
                    'match_number_id' => $matchNumber->id ?? null,
                ],
                2 => [
                    'id' => 11,
                    'name' => 'Juara 2',
                    'contingent' => 'Gresik A',
                    'registration_id' => $registration2->id,
                    'match_number_id' => $matchNumber->id ?? null,
                ],
            ],
        ],
    ]);

    // Update drawing_data with the actual matchNumber id
    $data = $matchNumber->drawing_data;
    $data['juara'][1]['match_number_id'] = $matchNumber->id;
    $data['juara'][2]['match_number_id'] = $matchNumber->id;
    $matchNumber->update(['drawing_data' => $data]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/randori/confirm-champion', [
            'match_id' => $matchNumber->id,
        ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
        ]);
});

test('randori scoring endpoints require authentication', function () {
    $response = $this->getJson('/admin/api/scoring/randori/1/state');
    $response->assertStatus(401);

    $response = $this->postJson('/admin/api/scoring/randori/submit-scoring', []);
    $response->assertStatus(401);

    $response = $this->postJson('/admin/api/scoring/randori/confirm-champion', []);
    $response->assertStatus(401);
});
