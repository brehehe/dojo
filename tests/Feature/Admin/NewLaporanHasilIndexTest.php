<?php

use App\Livewire\Admin\NewLaporanHasilIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\TournamentResult;
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

    $this->athlete = Athlete::factory()->create([
        'name' => 'Athlete Surabaya 1',
    ]);

    $this->athlete2 = Athlete::factory()->create([
        'name' => 'Athlete Surabaya 2',
    ]);

    $this->athlete3 = Athlete::factory()->create([
        'name' => 'Athlete Surabaya 3',
    ]);

    $this->ageGroup = AgeGroup::create([
        'name' => 'Dewasa',
        'order' => 1,
    ]);

    $this->matchNumber = MatchNumber::create([
        'name' => 'Randori Dewasa 60kg',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'age_group_id' => $this->ageGroup->id,
        'drawing_data' => [
            'type' => 'single_elimination',
            'upper_bracket' => [
                'rounds' => [
                    [
                        [
                            'athlete1' => [
                                'id' => $this->athlete->id,
                                'name' => $this->athlete->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                            'athlete2' => [
                                'id' => $this->athlete2->id,
                                'name' => $this->athlete2->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                            'winner' => 'athlete1',
                            'winner_data' => [
                                'id' => $this->athlete->id,
                                'name' => $this->athlete->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                        ],
                        [
                            'athlete1' => [
                                'id' => $this->athlete3->id,
                                'name' => $this->athlete3->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                            'athlete2' => [
                                'id' => 'BYE',
                                'name' => 'BYE',
                            ],
                            'winner' => 'athlete1',
                            'winner_data' => [
                                'id' => $this->athlete3->id,
                                'name' => $this->athlete3->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                        ],
                    ],
                    [
                        [
                            'athlete1' => [
                                'id' => $this->athlete->id,
                                'name' => $this->athlete->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                            'athlete2' => [
                                'id' => $this->athlete3->id,
                                'name' => $this->athlete3->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                            'winner' => 'athlete2',
                            'winner_data' => [
                                'id' => $this->athlete3->id,
                                'name' => $this->athlete3->name,
                                'contingent' => 'Surabaya',
                                'registration_id' => $this->registration->id,
                            ],
                        ],
                    ],
                ],
            ],
            'juara' => [],
        ],
        'drawing_generated_at' => now(),
    ]);

    // Attach athletes to the match
    $this->matchNumber->athletes()->attach($this->athlete->id, ['registration_id' => $this->registration->id]);
    $this->matchNumber->athletes()->attach($this->athlete2->id, ['registration_id' => $this->registration->id]);
    $this->matchNumber->athletes()->attach($this->athlete3->id, ['registration_id' => $this->registration->id]);
});

test('new laporan hasil component can be rendered and randori winners modal can be opened and auto filled', function () {
    Livewire::actingAs($this->admin)
        ->test(NewLaporanHasilIndex::class)
        ->assertSuccessful()
        ->call('openRandoriModal', $this->matchNumber->id, $this->matchNumber->name)
        ->assertSet('showRandoriModal', true)
        ->assertSet('randoriMatchId', $this->matchNumber->id)
        ->assertSet('juara1_id', $this->athlete3->id) // GF winner is athlete 3
        ->assertSet('juara2_id', $this->athlete->id)  // GF loser is athlete 1
        ->assertSet('juara3_id', $this->athlete2->id) // Semifinal loser is athlete 2
        ->assertSet('juara3_bersama_id', null)       // Only 3 athletes total, so only one third place
        ->call('saveRandoriResult')
        ->assertSet('showRandoriModal', false);

    // Assert that the TournamentResult is populated
    $results = TournamentResult::where('match_number_id', $this->matchNumber->id)->get()->keyBy('rank');
    expect($results)->toHaveCount(3)
        ->and($results[1]->athlete_names)->toBe($this->athlete3->name)
        ->and($results[2]->athlete_names)->toBe($this->athlete->name)
        ->and($results[4]->athlete_names)->toBe($this->athlete2->name);
});
