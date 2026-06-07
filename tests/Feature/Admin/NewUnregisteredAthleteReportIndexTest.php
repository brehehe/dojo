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

    $mn1 = MatchNumber::create([
        'name' => 'Embu Tandoku',
        'gender' => 'Male',
        'draft_type' => 'embu',
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

    $contingent = Contingent::factory()->create();
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $registration->athletes()->attach([
        $athleteA->id => ['age_group' => 'Pemula'],
        $athleteB->id => ['age_group' => 'Pemula'],
    ]);

    $mn1->athletes()->attach([
        $athleteA->id => ['registration_id' => $registration->id],
    ]);

    Livewire::actingAs($user)
        ->test(NewUnregisteredAthleteReportIndex::class)
        ->assertHasNoErrors()
        ->assertSet('totalAthletes', 2)
        ->assertSet('totalRegisteredAthletes', 1)
        ->assertSet('totalUnregisteredAthletes', 1)
        ->assertSet('ageGroupStats', [
            'Pemula' => 2,
            'Remaja A' => 0,
            'Remaja B' => 0,
            'Dewasa' => 0,
        ])
        ->assertViewHas('matchData', function ($matchData) {
            return count($matchData) === 1 && $matchData[0]['name'] === 'Embu Tandoku' && $matchData[0]['total_athletes'] === 1;
        })
        ->assertViewHas('unregisteredAthletes', function ($unregisteredAthletes) {
            return count($unregisteredAthletes) === 1 && $unregisteredAthletes[0]['name'] === 'Athlete B';
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
