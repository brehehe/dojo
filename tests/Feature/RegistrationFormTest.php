<?php

use App\Livewire\RegistrationForm;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\Group\WeightGroup;
use App\Models\KyuLevel;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('registration form returns options from both age groups when bypass is active', function () {
    // 1. Seed dependencies
    KyuLevel::create(['name' => 'Kyu 5', 'order' => 1]);
    WeightGroup::create(['name' => '40-50 kg', 'order' => 1]);

    $remajaA = AgeGroup::create(['name' => 'Remaja A', 'order' => 1]);
    $pemula = AgeGroup::create(['name' => 'Pemula', 'order' => 2]);

    // 2. Create Match Numbers for each age group
    $matchRemaja = MatchNumber::create([
        'name' => 'Embu Beregu Remaja A',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'max_athletes' => 4,
        'age_group_id' => $remajaA->id,
    ]);

    $matchPemula = MatchNumber::create([
        'name' => 'Embu Pasangan Pemula',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'age_group_id' => $pemula->id,
    ]);

    // 3. Create User & Contingent
    $user = User::factory()->create();
    $contingent = Contingent::create([
        'official_id' => $user->id,
        'name' => 'Malang Contingent',
        'kab_kota' => 'Malang',
        'leader_name' => 'M. Djamhuri',
        'leader_phone' => '08123399479',
        'email' => $user->email,
        'address' => 'Malang',
    ]);

    // Acting as User
    $component = Livewire::actingAs($user)
        ->test(RegistrationForm::class);

    // Initial state: first athlete index is 0. Set age group to Remaja A.
    $component->set('athletes.0.age_group', $remajaA->id);

    // Scenario 1: join_other_age_group = false. Should only show Remaja A match numbers.
    $optionsNotBypassed = $component->instance()->getEventOptions($remajaA->id, 'Male', 0, 'event1');
    $optionIdsNotBypassed = collect($optionsNotBypassed)->pluck('id')->toArray();

    expect($optionIdsNotBypassed)->toContain($matchRemaja->id);
    expect($optionIdsNotBypassed)->not->toContain($matchPemula->id);

    // Scenario 2: join_other_age_group = true, but event_age_group is empty. Should still only show Remaja A match numbers.
    $component->set('athletes.0.join_other_age_group', true);
    $component->set('athletes.0.event_age_group', '');

    $optionsEmptyBypass = $component->instance()->getEventOptions($remajaA->id, 'Male', 0, 'event1');
    $optionIdsEmptyBypass = collect($optionsEmptyBypass)->pluck('id')->toArray();

    expect($optionIdsEmptyBypass)->toContain($matchRemaja->id);
    expect($optionIdsEmptyBypass)->not->toContain($matchPemula->id);

    // Scenario 3: join_other_age_group = true, event_age_group = Pemula. Should show both Remaja A and Pemula match numbers.
    $component->set('athletes.0.event_age_group', $pemula->id);

    $optionsBypassed = $component->instance()->getEventOptions($remajaA->id, 'Male', 0, 'event1');
    $optionIdsBypassed = collect($optionsBypassed)->pluck('id')->toArray();

    expect($optionIdsBypassed)->toContain($matchRemaja->id);
    expect($optionIdsBypassed)->toContain($matchPemula->id);
});

test('registration form match summary reflects changes in athlete name and rank reactively', function () {
    // 1. Seed dependencies
    KyuLevel::create(['name' => 'Kyu 5', 'order' => 1]);
    $kyu4 = KyuLevel::create(['name' => 'Kyu 4', 'order' => 2]);
    $weight = WeightGroup::create(['name' => '40-50 kg', 'order' => 1]);
    $remajaA = AgeGroup::create(['name' => 'Remaja A', 'order' => 1]);

    $matchRemaja = MatchNumber::create([
        'name' => 'Embu Beregu Remaja A',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'max_athletes' => 4,
        'age_group_id' => $remajaA->id,
    ]);

    $user = User::factory()->create();
    $contingent = Contingent::create([
        'official_id' => $user->id,
        'name' => 'Malang Contingent',
        'kab_kota' => 'Malang',
        'leader_name' => 'M. Djamhuri',
        'leader_phone' => '08123399479',
        'email' => $user->email,
        'address' => 'Malang',
    ]);

    $component = Livewire::actingAs($user)
        ->test(RegistrationForm::class);

    // Initial check: summary should be empty
    expect($component->instance()->matchSummary)->toBeEmpty();

    // Set athlete info and event
    $component->set('athletes.0.name', 'John Doe');
    $component->set('athletes.0.age_group', $remajaA->id);
    $component->set('athletes.0.rank', 'Kyu 4');
    $component->set('athletes.0.current_weight', 45);
    $component->set('athletes.0.weight_group_id', $weight->id);
    $component->set('athletes.0.event1', $matchRemaja->id);

    // Verify summary matches John Doe and Kyu 4
    $summary = $component->instance()->matchSummary;
    expect($summary)->not->toBeEmpty();

    $genderKey = 'Male';
    $ageGroupKey = 'Remaja A';

    expect($summary)->toHaveKey($genderKey);
    expect($summary[$genderKey])->toHaveKey($ageGroupKey);
    expect($summary[$genderKey][$ageGroupKey])->toHaveKey($matchRemaja->id);

    $athletesList = $summary[$genderKey][$ageGroupKey][$matchRemaja->id]['athletes'];
    expect($athletesList)->toHaveCount(1);
    expect($athletesList[0]['name'])->toBe('John Doe');
    expect($athletesList[0]['rank'])->toBe('Kyu 4');
});

test('registration form loads athlete rank from their latest registration on search or selection', function () {
    // 1. Seed dependencies
    KyuLevel::create(['name' => 'Kyu 5', 'order' => 1]);
    KyuLevel::create(['name' => 'Kyu 4', 'order' => 2]);
    $remajaA = AgeGroup::create(['name' => 'Remaja A', 'order' => 1]);

    // 2. Create Master Athlete
    $athlete = Athlete::create([
        'nik' => '1234567890123456',
        'nik_kenshi' => '54321',
        'name' => 'Rachel Bertha',
        'gender' => 'Female',
        'birth_place' => 'Jakarta',
        'birth_date' => '2010-01-01',
        'address' => 'Jakarta',
        'phone' => '08123456789',
        'bpjs_number' => '112233',
        'bpjs_status' => 'Aktif',
    ]);

    // Create User & Contingent
    $user = User::factory()->create();
    $contingent = Contingent::create([
        'official_id' => $user->id,
        'name' => 'Malang Contingent',
        'kab_kota' => 'Malang',
        'leader_name' => 'M. Djamhuri',
        'leader_phone' => '08123399479',
        'email' => $user->email,
        'address' => 'Malang',
    ]);

    // Create a previous registration and attach athlete with Kyu 5
    $reg = Registration::create([
        'contingent_id' => $contingent->id,
        'status' => 'verified',
        'total_cost' => 500000,
        'final_amount' => 500000,
    ]);

    $reg->athletes()->attach($athlete->id, [
        'weight' => 50,
        'age_group' => 'Remaja A',
        'rank' => 'Kyu 4',
        'dojo_origin' => 'Malang Dojo',
        'city' => 'Malang',
        'match_type' => 'Tanding',
    ]);

    // Test searchAthlete method
    $component = Livewire::actingAs($user)
        ->test(RegistrationForm::class);

    // Set NIK
    $component->set('athletes.0.nik', '1234567890123456');
    $component->call('searchAthlete', 0);

    // Assert that rank loaded is Kyu 4 (not default Kyu 5)
    expect($component->get('athletes.0.rank'))->toBe('Kyu 4');

    // Reset components & test updatedAthletes with athleteId selection
    $component2 = Livewire::actingAs($user)
        ->test(RegistrationForm::class);

    $component2->set('athletes.0.athlete_id', $athlete->id);

    // Assert that rank loaded is Kyu 4
    expect($component2->get('athletes.0.rank'))->toBe('Kyu 4');
});
