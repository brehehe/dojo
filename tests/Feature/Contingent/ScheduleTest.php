<?php

use App\Livewire\Contingent\Schedule;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\DrawingMatchNumber;
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

    $this->athlete = Athlete::create([
        'name' => 'Kenshi A',
        'nik' => '1234567890123456',
        'gender' => 'Male',
        'birth_place' => 'Surabaya',
        'birth_date' => '2010-01-01',
        'address' => 'Surabaya',
        'bpjs_number' => '12345',
        'bpjs_status' => 'Aktif',
    ]);

    $this->registration->athletes()->attach($this->athlete->id, [
        'weight' => 45,
        'kyu' => 'Kyu 5',
        'rank' => 'Kyu 5',
        'age_group' => 'Pemula',
        'dojo_origin' => 'Dojo Surabaya',
        'city' => 'Surabaya',
        'match_type' => 'Tanding',
    ]);

    $this->ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    // Randori match with bracket data
    $this->randoriMatch = MatchNumber::create([
        'name' => 'Test Randori',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'age_group_id' => $this->ageGroup->id,
        'drawing_generated_at' => now(),
        'drawing_data' => [
            'upper_bracket' => [
                'rounds' => [
                    [
                        [
                            'athlete1' => [
                                'name' => 'Kenshi A',
                                'contingent' => 'Surabaya Contingent',
                            ],
                            'athlete2' => [
                                'name' => 'Kenshi B',
                                'contingent' => 'Malang Contingent',
                            ],
                            'winner' => 'athlete1',
                        ],
                    ],
                ],
            ],
        ],
    ]);

    $this->athlete->matchNumbers()->attach($this->randoriMatch->id, [
        'registration_id' => $this->registration->id,
    ]);

    // Drawing record for schedule
    $this->drawing = DrawingMatchNumber::create([
        'match_number_id' => $this->randoriMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'randori',
        'schedule_date' => '2026-06-15',
        'sequence_number' => 1,
        'round' => 'Penyisihan',
        'metadata' => [
            'athlete_name' => 'Kenshi A',
            'contingent' => 'Surabaya Contingent',
            'blue_athlete_name' => 'Kenshi B',
            'blue_contingent' => 'Malang Contingent',
            'start_time' => '08:00',
        ],
    ]);
});

test('contingent user can view their schedule list', function () {
    Livewire::actingAs($this->user)
        ->test(Schedule::class)
        ->assertStatus(200)
        ->assertSee('Test Randori')
        ->assertSee('Kenshi A')
        ->assertSee('VS')
        ->assertSee('Kenshi B');
});

test('contingent user can switch tabs to view brackets', function () {
    Livewire::actingAs($this->user)
        ->test(Schedule::class)
        ->set('activeTab', 'bracket')
        ->assertStatus(200)
        ->assertSee('Bagan Pemenang (Upper Bracket)')
        ->assertSee('Kenshi A')
        ->assertSee('Malang Contingent');
});
