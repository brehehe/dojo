<?php

use App\Livewire\Admin\NewLaporanSkorIndex;
use App\Models\Athlete;
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
    $this->admin = User::factory()->create();

    $this->contingent = Contingent::create([
        'name' => 'Surabaya Contingent',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'John Doe',
        'leader_phone' => '0812345678',
        'email' => 'surabaya@example.com',
        'address' => 'Surabaya',
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

    // Create athletes using factory
    $this->athleteA = Athlete::factory()->create(['name' => 'Athlete A', 'gender' => 'Male', 'birth_date' => '2010-01-01']);
    $this->athleteB = Athlete::factory()->create(['name' => 'Athlete B', 'gender' => 'Male', 'birth_date' => '2010-01-01']);
    $this->athleteC = Athlete::factory()->create(['name' => 'Athlete C', 'gender' => 'Male', 'birth_date' => '2010-01-01']);
    $this->athleteD = Athlete::factory()->create(['name' => 'Athlete D', 'gender' => 'Male', 'birth_date' => '2010-01-01']);

    // Attach athletes to registration
    $this->registration->athletes()->attach([$this->athleteA->id, $this->athleteB->id, $this->athleteC->id, $this->athleteD->id]);

    // Create two drawings for the same registration in Penyisihan (two distinct teams)
    $this->d1 = DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
        'metadata' => ['athlete_ids' => [$this->athleteA->id, $this->athleteB->id], 'athlete_name' => 'Athlete A & Athlete B'],
    ]);

    $this->d2 = DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 2,
        'metadata' => ['athlete_ids' => [$this->athleteC->id, $this->athleteD->id], 'athlete_name' => 'Athlete C & Athlete D'],
    ]);

    // Create scores for both drawings in Penyisihan
    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'drawing_id' => $this->d1->id,
        'round_label' => 'Penyisihan',
        'judge_1' => 90.0,
        'judge_2' => 90.0,
        'judge_3' => 90.0,
        'nilai_akhir' => 270.0,
        'rank' => 1,
    ]);

    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'drawing_id' => $this->d2->id,
        'round_label' => 'Penyisihan',
        'judge_1' => 85.0,
        'judge_2' => 90.0,
        'judge_3' => 85.0,
        'nilai_akhir' => 260.0,
        'rank' => 2,
    ]);
});

test('laporan skor index lists each team separately and displays their scores correctly', function () {
    Livewire::actingAs($this->admin)
        ->test(NewLaporanSkorIndex::class)
        ->assertSuccessful()
        ->assertSee('Surabaya Contingent')
        ->assertSee('Athlete A & Athlete B')
        ->assertSee('Athlete C & Athlete D')
        ->assertSee('270.00')
        ->assertSee('260.00');
});
