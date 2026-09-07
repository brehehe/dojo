<?php

use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanRekapitulasiRandori;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('can render rekapitulasi randori component even with missing mujoken_kachi in metadata', function () {
    $user = User::factory()->create();

    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
    ]);

    $matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa Putra 60kg',
        'gender' => 'Putra',
        'draft_type' => 'randori',
        'age_group_id' => $ageGroup->id,
        'drawing_data' => [
            'upper_bracket' => [
                'rounds' => [
                    [
                        [
                            'pool' => 'A',
                            'athlete1' => [
                                'name' => 'Aka Athlete',
                                'contingent' => 'Contingent A',
                            ],
                            'athlete2' => [
                                'name' => 'Shiro Athlete',
                                'contingent' => 'Contingent B',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]);

    RandoriMatchResult::create([
        'match_number_id' => $matchNumber->id,
        'bracket_node' => 'ub_0_0',
        'bracket_node_index' => 0,
        'bracket_section' => 'upper_bracket',
        'score_red' => 10,
        'score_blue' => 0,
        'winner_color' => 'athlete1',
        'metadata' => [
            'scoringAka' => [
                'ippon' => 1,
                // 'mujoken_kachi' is intentionally omitted to verify null coalescing
            ],
            'scoringShiro' => [
                // 'mujoken_kachi' is intentionally omitted
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('admin.arbitrase.new-rekapitulasi-randori'))
        ->assertOk();

    Livewire::actingAs($user)
        ->test(AdminLaporanRekapitulasiRandori::class)
        ->assertOk()
        ->assertSee('Randori Dewasa Putra 60kg')
        ->assertSee('Aka Athlete')
        ->assertSee('Shiro Athlete');
});
