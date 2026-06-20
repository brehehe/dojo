<?php

use App\Livewire\Contingent\Standings;
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

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->contingent1 = Contingent::create([
        'name' => 'Surabaya Contingent',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'John Doe',
        'leader_phone' => '0812345678',
        'email' => 'surabaya@example.com',
        'address' => 'Surabaya',
        'user_id' => $this->user->id,
    ]);

    $this->contingent2 = Contingent::create([
        'name' => 'Gresik Contingent',
        'kab_kota' => 'Gresik',
        'leader_name' => 'Jane Doe',
        'leader_phone' => '0812345679',
        'email' => 'gresik@example.com',
        'address' => 'Gresik',
    ]);

    $this->registration1 = Registration::create([
        'contingent_id' => $this->contingent1->id,
        'status' => 'verified',
    ]);

    $this->registration2 = Registration::create([
        'contingent_id' => $this->contingent2->id,
        'status' => 'verified',
    ]);

    $this->ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    $this->embuMatch = MatchNumber::create([
        'name' => 'Embu Tandoku Pemula',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'age_group_id' => $this->ageGroup->id,
        'drawing_generated_at' => now(),
    ]);

    // Link registrations
    DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration1->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);

    DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration2->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 2,
    ]);

    // Contingent 1 (Surabaya): Penyisihan = 270.00, Final = 265.00 (Rank 1)
    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration1->id,
        'round_label' => 'Penyisihan',
        'judge_1' => 270.00,
        'rank' => 1,
    ]);

    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration1->id,
        'round_label' => 'Final',
        'judge_1' => 265.00,
        'rank' => 1,
    ]);

    // Contingent 2 (Gresik): Penyisihan = 260.00, Final = null (Rank 2)
    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration2->id,
        'round_label' => 'Penyisihan',
        'judge_1' => 260.00,
        'rank' => 2,
    ]);
});

test('contingent user can view standings displaying all scores side by side', function () {
    // Both Penyisihan, Final, and Nilai Akhir scores should be visible at the same time
    Livewire::actingAs($this->user)
        ->test(Standings::class)
        ->assertSuccessful()
        ->assertSee('Embu Tandoku Pemula')
        ->assertSee('270.00') // Surabaya Penyisihan
        ->assertSee('265.00') // Surabaya Final
        ->assertSee('535.00') // Surabaya Akhir (Accumulated)
        ->assertSee('260.00'); // Gresik Penyisihan / Akhir
});

test('standings display ordered by rank with highest score first fallback', function () {
    $standings = Livewire::actingAs($this->user)
        ->test(Standings::class)
        ->assertSuccessful()
        ->viewData('standings');

    expect($standings)->toBeArray();

    $scores = collect($standings)->first();
    $registrationIds = $scores->pluck('registration_id')->toArray();
    // Should be Surabaya (registration1) first, then Gresik (registration2)
    expect($registrationIds)->toBe([$this->registration1->id, $this->registration2->id]);
});

test('standings displays team labels for multiple entries from the same contingent', function () {
    DrawingMatchNumber::where('match_number_id', $this->embuMatch->id)->delete();

    $d1 = DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration1->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
        'metadata' => ['athlete_ids' => [1, 2], 'athlete_name' => 'Athlete A & Athlete B'],
    ]);

    $d2 = DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration1->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 2,
        'metadata' => ['athlete_ids' => [3, 4], 'athlete_name' => 'Athlete C & Athlete D'],
    ]);

    EmbuScore::where('match_number_id', $this->embuMatch->id)->delete();

    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration1->id,
        'drawing_id' => $d1->id,
        'round_label' => 'Penyisihan',
        'nilai_akhir' => 270.0,
        'rank' => 1,
    ]);

    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration1->id,
        'drawing_id' => $d2->id,
        'round_label' => 'Penyisihan',
        'nilai_akhir' => 260.0,
        'rank' => 2,
    ]);

    Livewire::actingAs($this->user)
        ->test(Standings::class)
        ->assertSuccessful()
        ->assertSee('Surabaya Contingent')
        ->assertSee('(Tim 1)')
        ->assertSee('(Tim 2)');
});
