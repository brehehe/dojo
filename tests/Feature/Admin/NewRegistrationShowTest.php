<?php

use App\Livewire\Admin\NewRegistrationShow;
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
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed basic lookup tables
    $this->kyu = KyuLevel::create(['name' => 'Kyu 5', 'order' => 1]);
    $this->weight = WeightGroup::create(['name' => '40-50 kg', 'order' => 1]);
    $this->ageGroup1 = AgeGroup::create(['name' => 'Pemula', 'price' => 400000, 'order' => 1]);
    $this->ageGroup2 = AgeGroup::create(['name' => 'Remaja', 'price' => 500000, 'order' => 2]);

    // Create Match Numbers
    $this->match = MatchNumber::create([
        'name' => 'Test Randori Match',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'age_group_id' => $this->ageGroup1->id,
    ]);

    // Create Admin User
    $this->adminUser = User::factory()->create();

    // Create Contingent & Registration
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
        'status' => 'pending',
        'unique_code' => 123,
    ]);

    // Create and attach athlete
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

    // Set initial fee sum: contingent (2500000) + Pemula (400000) = 2900000
    // plus unique code (123) = 2900123
    $this->registration->update([
        'total_cost' => 2900000,
        'final_amount' => 2900123,
    ]);
});

test('admin can view registration detail', function () {
    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationShow::class, ['registration' => $this->registration->id])
        ->assertStatus(200)
        ->assertSee('Surabaya Contingent')
        ->assertSee('Kenshi A');
});

test('admin can edit athlete details and recalculate fees', function () {
    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationShow::class, ['registration' => $this->registration->id])
        ->call('openEditAthlete', $this->athlete->id)
        ->set('editName', 'Kenshi A Edited')
        ->set('editAgeGroup', 'Remaja') // Changing age group changes fee from 400000 to 500000
        ->call('saveAthlete');

    $this->athlete->refresh();
    expect($this->athlete->name)->toBe('Kenshi A Edited');

    $this->registration->refresh();
    // New total fee: contingent (2500000) + Remaja (500000) = 3000000
    // plus unique code (123) = 3000123
    expect($this->registration->total_cost)->toBe(3000000);
    expect($this->registration->final_amount)->toBe(3000123);
    expect($this->registration->athlete_status)->toBe('pending');
});

test('admin can delete athlete from registration and recalculate fees', function () {
    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationShow::class, ['registration' => $this->registration->id])
        ->call('deleteAthlete', $this->athlete->id);

    // Verify database relations are detached/deleted
    $this->assertDatabaseMissing('registration_athlete', [
        'registration_id' => $this->registration->id,
        'athlete_id' => $this->athlete->id,
    ]);

    $this->assertDatabaseMissing('athlete_match_number', [
        'registration_id' => $this->registration->id,
        'athlete_id' => $this->athlete->id,
    ]);

    $this->registration->refresh();
    // New total fee: contingent (2500000) + 0 athletes = 2500000
    // plus unique code (123) = 2500123
    expect($this->registration->total_cost)->toBe(2500000);
    expect($this->registration->final_amount)->toBe(2500123);
    expect($this->registration->athlete_status)->toBe('pending');
});

test('admin can add a new athlete and recalculate fees', function () {
    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationShow::class, ['registration' => $this->registration->id])
        ->call('openAddAthlete')
        ->set('editName', 'Kenshi B New')
        ->set('editNik', '9876543210987654')
        ->set('editNikKenshi', 'new/kenshi')
        ->set('editGender', 'Male')
        ->set('editWeight', 52.5)
        ->set('editRank', 'Kyu 5')
        ->set('editAgeGroup', 'Pemula') // Pemula fee: 400000
        ->set('editDojo', 'Dojo Surabaya')
        ->set('editBpjsNumber', '121212')
        ->set('editBpjsStatus', 'Aktif')
        ->set('editEvent1', $this->match->id)
        ->call('saveAthlete')
        ->assertHasNoErrors();

    // Verify athlete is created
    $newAthlete = Athlete::where('nik', '9876543210987654')->first();
    expect($newAthlete)->not->toBeNull();
    expect($newAthlete->name)->toBe('Kenshi B New');

    // Verify registration has two athletes
    expect($this->registration->athletes()->count())->toBe(2);

    $this->registration->refresh();
    // New total fee: contingent (2500000) + 2x Pemula (800000) = 3300000
    // plus unique code (123) = 3300123
    expect($this->registration->total_cost)->toBe(3300000);
    expect($this->registration->final_amount)->toBe(3300123);
    expect($this->registration->athlete_status)->toBe('pending');
});

test('admin can edit techniques for a match number', function () {
    // Seed a couple of Techniques
    $tech1 = Technique::create(['name' => 'Keri']);
    $tech2 = Technique::create(['name' => 'Tsuki']);

    $pivotId = DB::table('athlete_match_number')
        ->where('registration_id', $this->registration->id)
        ->where('match_number_id', $this->match->id)
        ->value('id');

    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationShow::class, ['registration' => $this->registration->id])
        ->call('openEditTechniques', [$pivotId])
        ->set('newTechniqueId', $tech1->id)
        ->call('addTechnique')
        ->set('newTechniqueId', $tech2->id)
        ->call('addTechnique')
        ->call('moveTechniqueDown', 0) // Tsuki, Keri
        ->call('saveTechniques')
        ->assertHasNoErrors();

    // Check pivot table
    $pivot = DB::table('athlete_match_number')
        ->where('registration_id', $this->registration->id)
        ->where('match_number_id', $this->match->id)
        ->first();

    $techIds = json_decode($pivot->technique_ids, true);
    expect($techIds)->toBe([$tech2->id, $tech1->id]);
});
