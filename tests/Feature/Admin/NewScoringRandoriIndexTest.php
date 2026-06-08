<?php

use App\Livewire\Admin\NewScoringRandoriIndex;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('new scoring randori index can select winner and propagate to grand final without error', function () {
    $user = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    // Create a match number with double elimination bracket structure in drawing_data
    $matchNumber = MatchNumber::create([
        'name' => 'Randori Male under 50kg',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'age_group_id' => $ageGroup->id,
        'drawing_data' => [
            'bracket_type' => 'double_elimination',
            'bracket_size' => 4,
            'total_athletes' => 2,
            'upper_bracket' => [
                'rounds' => [
                    [
                        [
                            'athlete1' => ['id' => 1, 'name' => 'Athlete 1', 'registration_id' => 10, 'contingent' => 'C1'],
                            'athlete2' => ['id' => 2, 'name' => 'Athlete 2', 'registration_id' => 11, 'contingent' => 'C2'],
                            'winner' => null,
                            'winner_data' => null,
                            'winner_next' => ['bracket' => 'gf', 'slot' => 'athlete1'], // points to grand final
                            'loser_next' => ['bracket' => 'eliminated'],
                        ],
                    ],
                ],
            ],
            'lower_bracket' => [
                'rounds' => [],
            ],
            'grand_final' => [
                'athlete1' => null,
                'athlete2' => null,
                'winner' => null,
                'winner_data' => null,
            ],
            'juara' => [],
        ],
    ]);

    // Test select winner
    Livewire::actingAs($user)
        ->test(NewScoringRandoriIndex::class, ['matchNumber' => $matchNumber])
        ->call('selectWinner', 'ub', 0, 0, 'athlete1')
        ->assertHasNoErrors();

    $matchNumber->refresh();
    $drawingData = $matchNumber->drawing_data;

    expect($drawingData['grand_final']['athlete1']['id'])->toBe(1)
        ->and($drawingData['upper_bracket']['rounds'][0][0]['winner'])->toBe('athlete1');
});

test('new scoring randori index timer functions can start, pause, and stop timer without error', function () {
    $user = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    $matchNumber = MatchNumber::create([
        'name' => 'Randori Male under 50kg',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'age_group_id' => $ageGroup->id,
        'drawing_data' => [
            'bracket_type' => 'double_elimination',
            'bracket_size' => 4,
            'total_athletes' => 2,
            'upper_bracket' => [
                'rounds' => [
                    [
                        [
                            'athlete1' => ['id' => 1, 'name' => 'Athlete 1', 'registration_id' => 10, 'contingent' => 'C1'],
                            'athlete2' => ['id' => 2, 'name' => 'Athlete 2', 'registration_id' => 11, 'contingent' => 'C2'],
                            'winner' => null,
                            'winner_data' => null,
                            'winner_next' => ['bracket' => 'gf', 'slot' => 'athlete1'],
                            'loser_next' => ['bracket' => 'eliminated'],
                        ],
                    ],
                ],
            ],
            'lower_bracket' => [
                'rounds' => [],
            ],
            'grand_final' => [
                'athlete1' => null,
                'athlete2' => null,
                'winner' => null,
                'winner_data' => null,
            ],
            'juara' => [],
        ],
    ]);

    Livewire::actingAs($user)
        ->test(NewScoringRandoriIndex::class, ['matchNumber' => $matchNumber])
        ->call('startTimer')
        ->assertHasNoErrors()
        ->call('pauseTimer')
        ->assertHasNoErrors()
        ->call('stopTimer')
        ->assertHasNoErrors();
});

test('new scoring randori index finishMatch runs without errors and does not dispatch swal or auto-submit', function () {
    $user = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    $matchNumber = MatchNumber::create([
        'name' => 'Randori Male under 50kg',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'age_group_id' => $ageGroup->id,
        'drawing_data' => [
            'bracket_type' => 'double_elimination',
            'bracket_size' => 4,
            'total_athletes' => 2,
            'upper_bracket' => [
                'rounds' => [
                    [
                        [
                            'athlete1' => ['id' => 1, 'name' => 'Athlete 1', 'registration_id' => 10, 'contingent' => 'C1'],
                            'athlete2' => ['id' => 2, 'name' => 'Athlete 2', 'registration_id' => 11, 'contingent' => 'C2'],
                            'winner' => null,
                            'winner_data' => null,
                            'winner_next' => ['bracket' => 'gf', 'slot' => 'athlete1'],
                            'loser_next' => ['bracket' => 'eliminated'],
                        ],
                    ],
                ],
            ],
            'lower_bracket' => [
                'rounds' => [],
            ],
            'grand_final' => [
                'athlete1' => null,
                'athlete2' => null,
                'winner' => null,
                'winner_data' => null,
            ],
            'juara' => [],
        ],
    ]);

    Livewire::actingAs($user)
        ->test(NewScoringRandoriIndex::class, ['matchNumber' => $matchNumber])
        ->call('finishMatch')
        ->assertHasNoErrors()
        ->assertNotDispatched('swal');
});
