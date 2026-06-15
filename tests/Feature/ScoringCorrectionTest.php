<?php

use App\Models\Contingent;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('scoring correction index requires auth', function () {
    $this->withoutVite();
    $response = $this->get('/admin/new-scoring/correction');
    $response->assertRedirect('/login');
});

test('authenticated admin can access correction index', function () {
    $this->withoutVite();
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)
        ->get('/admin/new-scoring/correction');

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('ScoringCorrection')
        );
});

test('api retrieves matches list successfully', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $match = MatchNumber::create([
        'name' => 'Embu Beregu',
        'draft_type' => 'embu',
        'max_athletes' => 4,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $response = $this->actingAs($admin)
        ->getJson('/admin/api/scoring/correction/matches');

    $response->assertSuccessful()
        ->assertJsonFragment([
            'id' => $match->id,
            'name' => 'Embu Beregu',
            'draft_type' => 'embu',
        ]);
});

test('saves corrected embu scores and time', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);
    $match = MatchNumber::create([
        'name' => 'Embu Beregu',
        'draft_type' => 'embu',
        'max_athletes' => 4,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $contingent = Contingent::create([
        'name' => 'Surabaya A',
        'leader_name' => 'Leader A',
        'leader_phone' => '0812345678',
        'leader_nik' => '1234567890123456',
    ]);
    $reg = Registration::create([
        'contingent_id' => $contingent->id,
    ]);

    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $match->id,
        'registration_id' => $reg->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/correction/embu/save', [
            'match_id' => $match->id,
            'registration_id' => $reg->id,
            'drawing_id' => $drawing->id,
            'round' => 'Penyisihan',
            'waktu' => '01:45',
            'denda' => 10,
            'scores' => [
                'judge_1' => 8.5,
                'judge_2' => 8.2,
                'judge_3' => 8.3,
                'judge_4' => 8.4,
                'judge_5' => 8.6,
            ],
        ]);

    $response->assertSuccessful();

    $this->assertDatabaseHas('embu_scores', [
        'match_number_id' => $match->id,
        'registration_id' => $reg->id,
        'round_label' => 'Penyisihan',
        'denda' => 10,
        'waktu' => '01:45',
    ]);
});

test('saves corrected randori match node scores', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);

    // Create drawing data bracket mock structure
    $drawingData = [
        'bracket_type' => 'double_elimination',
        'upper_bracket' => [
            'rounds' => [
                [
                    [
                        'athlete1' => ['id' => 1, 'name' => 'Atlet Aka', 'contingent' => 'Contingent A', 'match_number_id' => 1],
                        'athlete2' => ['id' => 2, 'name' => 'Atlet Shiro', 'contingent' => 'Contingent B', 'match_number_id' => 1],
                        'winner_next' => ['bracket' => 'gf', 'slot' => 'athlete1'],
                        'loser_next' => ['bracket' => 'eliminated'],
                    ],
                ],
            ],
        ],
        'lower_bracket' => ['rounds' => []],
        'grand_final' => null,
        'juara' => [],
    ];

    $match = MatchNumber::create([
        'id' => 1,
        'name' => 'Randori Dewasa 60kg',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
        'drawing_data' => $drawingData,
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/admin/api/scoring/correction/randori/save', [
            'match_id' => $match->id,
            'bracket' => 'ub',
            'round' => 0,
            'match' => 0,
            'score_red' => 5,
            'score_blue' => 2,
            'scoring_aka' => [
                'ippon' => 2,
                'waza_ari' => 1,
            ],
            'scoring_shiro' => [
                'ippon' => 1,
                'waza_ari' => 0,
            ],
        ]);

    $response->assertSuccessful();

    $this->assertDatabaseHas('randori_match_results', [
        'match_number_id' => $match->id,
        'bracket_node' => 'ub_0_0',
        'score_red' => 5,
        'score_blue' => 2,
        'winner_color' => 'athlete1',
    ]);
});
