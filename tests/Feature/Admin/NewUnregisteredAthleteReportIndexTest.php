<?php

use App\Livewire\Admin\NewUnregisteredAthleteReportIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('new unregistered athlete report index component can render', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(NewUnregisteredAthleteReportIndex::class)
        ->assertStatus(200);
});

test('can load data from database for unregistered athlete report', function () {
    $user = User::factory()->create();

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    // Non-eksebisi embu match number
    $mn1 = MatchNumber::create([
        'name' => 'Embu Tandoku',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'age_group_id' => $ageGroup->id,
    ]);

    // Eksebisi randori match number
    $mn2 = MatchNumber::create([
        'name' => 'Randori 55Kg eksebisi',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'age_group_id' => $ageGroup->id,
    ]);

    $athleteA = Athlete::factory()->create([
        'name' => 'Athlete A',
        'gender' => 'Male',
    ]);
    $athleteB = Athlete::factory()->create([
        'name' => 'Athlete B',
        'gender' => 'Male',
    ]);
    $athleteC = Athlete::factory()->create([
        'name' => 'Athlete C',
        'gender' => 'Male',
    ]);
    $athleteD = Athlete::factory()->create([
        'name' => 'Athlete D',
        'gender' => 'Male',
    ]);

    $contingentA = Contingent::factory()->create();
    $contingentB = Contingent::factory()->create();
    $contingentC = Contingent::factory()->create();
    $contingentD = Contingent::factory()->create();

    $registrationA = Registration::create(['contingent_id' => $contingentA->id]);
    $registrationB = Registration::create(['contingent_id' => $contingentB->id]);
    $registrationC = Registration::create(['contingent_id' => $contingentC->id]);
    $registrationD = Registration::create(['contingent_id' => $contingentD->id]);

    $registrationA->athletes()->attach([
        $athleteA->id => ['age_group' => 'Pemula'],
    ]);
    $registrationB->athletes()->attach([
        $athleteB->id => ['age_group' => 'Pemula'],
    ]);
    $registrationC->athletes()->attach([
        $athleteC->id => ['age_group' => 'Pemula'],
    ]);
    $registrationD->athletes()->attach([
        $athleteD->id => ['age_group' => 'Pemula'],
    ]);

    $mn1->athletes()->attach([
        $athleteA->id => ['registration_id' => $registrationA->id],
        $athleteB->id => ['registration_id' => $registrationB->id],
    ]);

    $mn2->athletes()->attach([
        $athleteA->id => ['registration_id' => $registrationA->id],
        $athleteB->id => ['registration_id' => $registrationB->id],
        $athleteC->id => ['registration_id' => $registrationC->id],
        $athleteD->id => ['registration_id' => $registrationD->id],
    ]);

    Livewire::actingAs($user)
        ->test(NewUnregisteredAthleteReportIndex::class)
        ->assertHasNoErrors()
        ->assertSet('totalAthletes', 4)
        ->assertSet('totalRegisteredAthletes', 4)
        ->assertSet('totalUnregisteredAthletes', 0)
        ->assertSet('totalMatchesWithAthletes', 2)
        ->assertSet('totalMatchesWithoutAthletes', 0)
        ->assertSet('totalEksebisi', 4)
        ->assertSet('totalNonEksebisi', 2)
        ->assertSet('totalEmbuEksebisi', 0)
        ->assertSet('totalEmbuNonEksebisi', 2)
        ->assertSet('totalRandoriEksebisi', 4)
        ->assertSet('totalRandoriNonEksebisi', 0)
        ->assertSet('ageGroupStats', [
            'Pemula' => 4,
            'Remaja A' => 0,
            'Remaja B' => 0,
            'Dewasa' => 0,
        ])
        ->assertViewHas('matchData', function ($matchData) {
            return count($matchData) === 2
                && $matchData[0]['name'] === 'Embu Tandoku'
                && $matchData[0]['has_duplicate_contingent'] === true
                && $matchData[1]['name'] === 'Randori 55Kg eksebisi'
                && $matchData[1]['has_duplicate_contingent'] === false;
        })
        ->assertViewHas('unregisteredAthletes', function ($unregisteredAthletes) {
            return count($unregisteredAthletes) === 0;
        });
});

test('can download excel report for unregistered athlete report', function () {
    $user = User::factory()->create();

    $response = Livewire::actingAs($user)
        ->test(NewUnregisteredAthleteReportIndex::class)
        ->call('downloadExcel');

    $response->assertStatus(200);
});

test('can filter data by gender and search query', function () {
    $user = User::factory()->create();

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    $mn1 = MatchNumber::create([
        'name' => 'Embu Putra',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'age_group_id' => $ageGroup->id,
    ]);

    $mn2 = MatchNumber::create([
        'name' => 'Embu Putri',
        'gender' => 'Female',
        'draft_type' => 'embu',
        'age_group_id' => $ageGroup->id,
    ]);

    $athleteA = Athlete::factory()->create([
        'name' => 'John Doe',
        'gender' => 'Male',
    ]);
    $athleteB = Athlete::factory()->create([
        'name' => 'Jane Smith',
        'gender' => 'Female',
    ]);

    $contingent = Contingent::factory()->create();
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $registration->athletes()->attach([
        $athleteA->id => ['age_group' => 'Pemula'],
        $athleteB->id => ['age_group' => 'Pemula'],
    ]);

    // John is registered in Embu Putra, Jane is unregistered
    $mn1->athletes()->attach([
        $athleteA->id => ['registration_id' => $registration->id],
    ]);

    Livewire::actingAs($user)
        ->test(NewUnregisteredAthleteReportIndex::class)
        ->assertSet('totalAthletes', 2)
        ->assertSet('totalUnregisteredAthletes', 1)
        ->set('genderFilter', 'Female')
        ->assertSet('totalAthletes', 1)
        ->assertSet('totalUnregisteredAthletes', 1)
        ->set('searchQuery', 'Jane')
        ->assertSet('totalUnregisteredAthletes', 1)
        ->set('searchQuery', 'XYZNonExistent')
        ->assertSet('totalUnregisteredAthletes', 0);
});
