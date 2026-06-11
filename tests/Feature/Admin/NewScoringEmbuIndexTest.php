<?php

use App\Livewire\Admin\NewScoringEmbuIndex;
use App\Models\Contingent;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('callParticipant dispatches swal warning if registration is null', function () {
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

    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => null, // Empty / placeholder final slot
        'round' => 'Final',
    ]);

    Livewire::actingAs($admin)
        ->test(NewScoringEmbuIndex::class, ['matchNumber' => $matchNumber])
        ->call('callParticipant', $drawing->id)
        ->assertDispatched('swal', function ($name, $params) {
            return isset($params[0]) &&
                ($params[0]['icon'] ?? null) === 'warning' &&
                str_contains($params[0]['title'] ?? '', 'Belum Tergenerate');
        });
});

test('applyTimerPenalty calculates correct denda for single and group matches', function () {
    $admin = User::factory()->create();
    $ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
        'price' => 0,
    ]);

    $contingent = Contingent::factory()->create();

    // 1. Single / Tandoku category (max_athletes = 1)
    $tandokuMatch = MatchNumber::create([
        'name' => 'Embu Tandoku kyu kenshi',
        'draft_type' => 'embu',
        'max_athletes' => 1,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    $regTandoku = Registration::create(['contingent_id' => $contingent->id]);
    $drawingTandoku = DrawingMatchNumber::create([
        'match_number_id' => $tandokuMatch->id,
        'registration_id' => $regTandoku->id,
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);

    // Test Tandoku 50 seconds -> expected denda = 5
    Livewire::actingAs($admin)
        ->test(NewScoringEmbuIndex::class, ['matchNumber' => $tandokuMatch])
        ->call('finishMatch', $drawingTandoku->id, 50 * 1000);

    $score1 = EmbuScore::where('match_number_id', $tandokuMatch->id)
        ->where('registration_id', $regTandoku->id)
        ->first();
    expect($score1)->not->toBeNull();
    expect($score1->denda)->toEqual(5);
    expect($score1->waktu)->toEqual('00:50');

    // Test Tandoku 80 seconds -> expected denda = 0
    $score1->delete();
    Livewire::actingAs($admin)
        ->test(NewScoringEmbuIndex::class, ['matchNumber' => $tandokuMatch])
        ->call('finishMatch', $drawingTandoku->id, 80 * 1000);

    $score1 = EmbuScore::where('match_number_id', $tandokuMatch->id)
        ->where('registration_id', $regTandoku->id)
        ->first();
    expect($score1)->not->toBeNull();
    expect($score1->denda)->toEqual(0);
    expect($score1->waktu)->toEqual('01:20');

    // 2. Group / Pasangan category (max_athletes = 2)
    $pasanganMatch = MatchNumber::create([
        'name' => 'Embu Pasangan Kyu kenshi',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 2,
        'age_group_id' => $ageGroup->id,
    ]);

    $regPasangan = Registration::create(['contingent_id' => $contingent->id]);
    $drawingPasangan = DrawingMatchNumber::create([
        'match_number_id' => $pasanganMatch->id,
        'registration_id' => $regPasangan->id,
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);

    // Test Pasangan 40 seconds -> expected denda = 10
    Livewire::actingAs($admin)
        ->test(NewScoringEmbuIndex::class, ['matchNumber' => $pasanganMatch])
        ->call('finishMatch', $drawingPasangan->id, 40 * 1000);

    $score2 = EmbuScore::where('match_number_id', $pasanganMatch->id)
        ->where('registration_id', $regPasangan->id)
        ->first();
    expect($score2)->not->toBeNull();
    expect($score2->denda)->toEqual(10);
    expect($score2->waktu)->toEqual('00:40');
});
