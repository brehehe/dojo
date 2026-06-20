<?php

use App\Livewire\Admin\NewEmbuResultIndex;
use App\Models\Contingent;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuChampion;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\TournamentResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('confirmChampion limits to top 3 and saves points', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
        'price' => 0,
    ]);

    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan Yudansha',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $contingent1 = Contingent::factory()->create(['name' => 'Contingent A']);
    $contingent2 = Contingent::factory()->create(['name' => 'Contingent B']);
    $contingent3 = Contingent::factory()->create(['name' => 'Contingent C']);
    $contingent4 = Contingent::factory()->create(['name' => 'Contingent D']);

    $reg1 = Registration::create(['contingent_id' => $contingent1->id]);
    $reg2 = Registration::create(['contingent_id' => $contingent2->id]);
    $reg3 = Registration::create(['contingent_id' => $contingent3->id]);
    $reg4 = Registration::create(['contingent_id' => $contingent4->id]);

    // Create 4 final drawings
    $d1 = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg1->id,
        'round' => 'Final',
        'sequence_number' => 1,
    ]);

    $d2 = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg2->id,
        'round' => 'Final',
        'sequence_number' => 2,
    ]);

    $d3 = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg3->id,
        'round' => 'Final',
        'sequence_number' => 3,
    ]);

    $d4 = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg4->id,
        'round' => 'Final',
        'sequence_number' => 4,
    ]);

    // Create final scores (accumulated will match final score if no penyisihan)
    EmbuScore::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg1->id,
        'drawing_id' => $d1->id,
        'round_label' => 'Final',
        'nilai_akhir' => 275.0,
    ]);

    EmbuScore::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg2->id,
        'drawing_id' => $d2->id,
        'round_label' => 'Final',
        'nilai_akhir' => 270.0,
    ]);

    EmbuScore::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg3->id,
        'drawing_id' => $d3->id,
        'round_label' => 'Final',
        'nilai_akhir' => 265.0,
    ]);

    EmbuScore::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $reg4->id,
        'drawing_id' => $d4->id,
        'round_label' => 'Final',
        'nilai_akhir' => 260.0,
    ]);

    Livewire::actingAs($admin)
        ->test(NewEmbuResultIndex::class, ['selectedMatchId' => $matchNumber->id])
        ->call('confirmChampion');

    // Assert that all 4 champions were created
    $champions = EmbuChampion::where('match_number_id', $matchNumber->id)->get();
    expect($champions->count())->toEqual(4);

    // Verify ranks
    expect($champions->where('rank', 1)->first()->registration_id)->toEqual($reg1->id);
    expect($champions->where('rank', 2)->first()->registration_id)->toEqual($reg2->id);
    expect($champions->where('rank', 3)->first()->registration_id)->toEqual($reg3->id);
    expect($champions->where('rank', 4)->first()->registration_id)->toEqual($reg4->id);

    // Assert TournamentResult count is also 4
    $results = TournamentResult::where('match_number_id', $matchNumber->id)->get();
    expect($results->count())->toEqual(4);
});

test('render calculates total participants and contingent counts correctly', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
        'price' => 0,
    ]);

    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan Yudansha',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $contingentA = Contingent::factory()->create(['name' => 'Contingent A']);
    $contingentB = Contingent::factory()->create(['name' => 'Contingent B']);

    $regA1 = Registration::create(['contingent_id' => $contingentA->id]);
    $regA2 = Registration::create(['contingent_id' => $contingentA->id]);
    $regB = Registration::create(['contingent_id' => $contingentB->id]);

    // Create 3 Penyisihan drawings (2 from Contingent A, 1 from Contingent B)
    DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $regA1->id,
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);

    DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $regA2->id,
        'round' => 'Penyisihan',
        'sequence_number' => 2,
    ]);

    DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $regB->id,
        'round' => 'Penyisihan',
        'sequence_number' => 3,
    ]);

    Livewire::actingAs($admin)
        ->test(NewEmbuResultIndex::class, ['selectedMatchId' => $matchNumber->id])
        ->assertViewHas('totalParticipants', 3)
        ->assertViewHas('contingentCounts', function ($counts) use ($contingentA, $contingentB) {
            return ($counts[$contingentA->id] ?? 0) === 2 && ($counts[$contingentB->id] ?? 0) === 1;
        });
});

test('render sets yellow background class (ctg-duplicate) under yellow conditions', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
        'price' => 0,
    ]);

    $matchNumber1 = MatchNumber::create([
        'name' => 'Embu 3 Teams',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $contingentA = Contingent::factory()->create(['name' => 'Contingent A']);
    $contingentB = Contingent::factory()->create(['name' => 'Contingent B']);
    $contingentC = Contingent::factory()->create(['name' => 'Contingent C']);

    $regA = Registration::create(['contingent_id' => $contingentA->id]);
    $regB = Registration::create(['contingent_id' => $contingentB->id]);
    $regC = Registration::create(['contingent_id' => $contingentC->id]);

    // Match 1: Exactly 3 teams (all unique contingents)
    DrawingMatchNumber::create(['match_number_id' => $matchNumber1->id, 'registration_id' => $regA->id, 'round' => 'Penyisihan', 'sequence_number' => 1]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber1->id, 'registration_id' => $regB->id, 'round' => 'Penyisihan', 'sequence_number' => 2]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber1->id, 'registration_id' => $regC->id, 'round' => 'Penyisihan', 'sequence_number' => 3]);

    Livewire::actingAs($admin)
        ->test(NewEmbuResultIndex::class, ['selectedMatchId' => $matchNumber1->id])
        ->assertSee('ctg-duplicate')
        ->assertDontSee('ctg-unique');

    // Match 2: 4 teams with duplicate contingent
    $matchNumber2 = MatchNumber::create([
        'name' => 'Embu 4 Teams Duplicate',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 2,
        'age_group_id' => $ageGroup->id,
    ]);
    $regA2 = Registration::create(['contingent_id' => $contingentA->id]);

    DrawingMatchNumber::create(['match_number_id' => $matchNumber2->id, 'registration_id' => $regA->id, 'round' => 'Penyisihan', 'sequence_number' => 1]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber2->id, 'registration_id' => $regA2->id, 'round' => 'Penyisihan', 'sequence_number' => 2]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber2->id, 'registration_id' => $regB->id, 'round' => 'Penyisihan', 'sequence_number' => 3]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber2->id, 'registration_id' => $regC->id, 'round' => 'Penyisihan', 'sequence_number' => 4]);

    Livewire::actingAs($admin)
        ->test(NewEmbuResultIndex::class, ['selectedMatchId' => $matchNumber2->id])
        ->assertSee('ctg-duplicate')
        ->assertDontSee('ctg-unique');

    // Match 3: 4 teams with unique contingents
    $matchNumber3 = MatchNumber::create([
        'name' => 'Embu 4 Teams Unique',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 3,
        'age_group_id' => $ageGroup->id,
    ]);
    $contingentD = Contingent::factory()->create(['name' => 'Contingent D']);
    $regD = Registration::create(['contingent_id' => $contingentD->id]);

    DrawingMatchNumber::create(['match_number_id' => $matchNumber3->id, 'registration_id' => $regA->id, 'round' => 'Penyisihan', 'sequence_number' => 1]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber3->id, 'registration_id' => $regB->id, 'round' => 'Penyisihan', 'sequence_number' => 2]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber3->id, 'registration_id' => $regC->id, 'round' => 'Penyisihan', 'sequence_number' => 3]);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber3->id, 'registration_id' => $regD->id, 'round' => 'Penyisihan', 'sequence_number' => 4]);

    Livewire::actingAs($admin)
        ->test(NewEmbuResultIndex::class, ['selectedMatchId' => $matchNumber3->id])
        ->assertSee('ctg-unique')
        ->assertDontSee('ctg-duplicate');
});
