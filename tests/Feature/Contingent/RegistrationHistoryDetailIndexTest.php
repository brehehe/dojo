<?php

use App\Livewire\Contingent\RegistrationHistoryDetailIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\Group\WeightGroup;
use App\Models\KyuLevel;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\Technique\Technique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->kyu = KyuLevel::create(['name' => 'Kyu 5', 'order' => 1]);
    $this->weight = WeightGroup::create(['name' => '40-50 kg', 'order' => 1]);
    $this->ageGroup1 = AgeGroup::create(['name' => 'Pemula', 'price' => 400000, 'order' => 1]);

    $this->match = MatchNumber::create([
        'name' => 'Test Randori Match',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'age_group_id' => $this->ageGroup1->id,
    ]);

    // Link the user to the contingent
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
        'status' => 'pending',
        'unique_code' => 123,
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

    $this->athlete->matchNumbers()->attach($this->match->id, [
        'registration_id' => $this->registration->id,
    ]);
});

test('contingent user can view registration detail', function () {
    Livewire::actingAs($this->user)
        ->test(RegistrationHistoryDetailIndex::class, ['registration' => $this->registration->id])
        ->assertStatus(200)
        ->assertSee('Surabaya Contingent')
        ->assertSee('Kenshi A');
});

test('contingent user can edit techniques for a match number', function () {
    $tech1 = Technique::create(['name' => 'Keri']);
    $tech2 = Technique::create(['name' => 'Tsuki']);

    $pivotId = DB::table('athlete_match_number')
        ->where('registration_id', $this->registration->id)
        ->where('match_number_id', $this->match->id)
        ->value('id');

    Livewire::actingAs($this->user)
        ->test(RegistrationHistoryDetailIndex::class, ['registration' => $this->registration->id])
        ->call('openEditTechniques', [$pivotId])
        ->set('newTechniqueId', $tech1->id)
        ->call('addTechnique')
        ->set('newTechniqueId', $tech2->id)
        ->call('addTechnique')
        ->call('moveTechniqueDown', 0)
        ->call('saveTechniques')
        ->assertHasNoErrors();

    $pivot = DB::table('athlete_match_number')
        ->where('registration_id', $this->registration->id)
        ->where('match_number_id', $this->match->id)
        ->first();

    $techIds = json_decode($pivot->technique_ids, true);
    expect($techIds)->toBe([$tech2->id, $tech1->id]);
});

test('contingent user can update athlete team number', function () {
    $pivotId = DB::table('athlete_match_number')
        ->where('registration_id', $this->registration->id)
        ->where('match_number_id', $this->match->id)
        ->value('id');

    Livewire::actingAs($this->user)
        ->test(RegistrationHistoryDetailIndex::class, ['registration' => $this->registration->id])
        ->call('updateAthleteTeam', $pivotId, 2)
        ->assertHasNoErrors();

    $pivot = DB::table('athlete_match_number')
        ->where('id', $pivotId)
        ->first();

    expect($pivot->team_number)->toBe(2);
});
