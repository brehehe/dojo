<?php

use App\Livewire\Admin\Arbitrase\Laporan\AdminLaporanHasilIndex;
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

    // Create two drawings for the same registration in Penyisihan
    $this->d1 = DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
        'metadata' => ['athlete_ids' => [1, 2], 'athlete_name' => 'Athlete A & Athlete B'],
    ]);

    $this->d2 = DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 2,
        'metadata' => ['athlete_ids' => [3, 4], 'athlete_name' => 'Athlete C & Athlete D'],
    ]);

    // Create scores for both drawings in Penyisihan
    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'drawing_id' => $this->d1->id,
        'round_label' => 'Penyisihan',
        'nilai_akhir' => 270.0,
        'rank' => 1,
    ]);

    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'drawing_id' => $this->d2->id,
        'round_label' => 'Penyisihan',
        'nilai_akhir' => 260.0,
        'rank' => 2,
    ]);
});

test('laporan hasil computes and displays team suffixes correctly', function () {
    Livewire::actingAs($this->admin)
        ->test(AdminLaporanHasilIndex::class)
        ->assertSuccessful()
        ->assertSee('Surabaya Contingent')
        ->assertSee('Athlete A & Athlete B (Tim 1)')
        ->assertSee('Athlete C & Athlete D (Tim 2)');
});
