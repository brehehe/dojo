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
    $this->contingent = Contingent::create([
        'name' => 'Surabaya Contingent',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'John Doe',
        'leader_phone' => '0812345678',
        'email' => 'surabaya@example.com',
        'address' => 'Surabaya',
        'user_id' => $this->user->id,
    ]);

    $this->registration = Registration::create([
        'contingent_id' => $this->contingent->id,
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

    // Create drawing linking registration to the match number
    DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);

    // Create scores
    $this->scorePenyisihan1 = EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'round_label' => 'Penyisihan',
        'judge_1' => 260.00,
        'rank' => 2,
    ]);

    $this->scorePenyisihan2 = EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'round_label' => 'Penyisihan',
        'judge_1' => 270.00,
        'rank' => 1,
    ]);

    $this->scoreFinal = EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'round_label' => 'Final',
        'judge_1' => 265.00,
        'rank' => 1,
    ]);
});

test('contingent user can view standings and filter by round', function () {
    // 1. Default view: 'Penyisihan' - Penyisihan scores (270.00, 260.00) should be visible, Final score (265.00) should not
    Livewire::actingAs($this->user)
        ->test(Standings::class)
        ->assertStatus(200)
        ->assertSee('Embu Tandoku Pemula')
        ->assertSee('270.00')
        ->assertSee('260.00')
        ->assertDontSee('265.00');

    // 2. Filter by Final - Final score (265.00) should be visible, Penyisihan scores (270.00, 260.00) should not
    Livewire::actingAs($this->user)
        ->test(Standings::class)
        ->set('roundFilter', 'Final')
        ->assertStatus(200)
        ->assertSee('265.00')
        ->assertDontSee('270.00')
        ->assertDontSee('260.00');
});

test('standings display highest score first (nilai tertinggi)', function () {
    // When roundFilter is 'Penyisihan', sorting should order scores as 270.00 -> 260.00
    Livewire::actingAs($this->user)
        ->test(Standings::class)
        ->set('roundFilter', 'Penyisihan')
        ->assertStatus(200)
        ->viewData('standings')
        ->each(function ($scores) {
            $sortedScores = $scores->pluck('nilai_akhir')->toArray();
            expect($sortedScores)->toBe([270.00, 260.00]);
        });
});
