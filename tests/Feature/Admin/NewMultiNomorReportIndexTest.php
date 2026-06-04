<?php

use App\Livewire\Admin\NewMultiNomorReportIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('new multi nomor report index component can render', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(NewMultiNomorReportIndex::class)
        ->assertStatus(200);
});

test('can load data from database and analyze conflict', function () {
    $user = User::factory()->create();

    // Seed database records
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    $mn1 = MatchNumber::create([
        'name' => 'Embu Tandoku',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'age_group_id' => $ageGroup->id,
    ]);

    $mn2 = MatchNumber::create([
        'name' => 'Randori 50kg',
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

    $contingent = Contingent::factory()->create();
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $registration->athletes()->attach([
        $athleteA->id,
        $athleteB->id,
        $athleteC->id,
    ]);

    $mn1->athletes()->attach([
        $athleteA->id => ['registration_id' => $registration->id],
        $athleteB->id => ['registration_id' => $registration->id],
    ]);
    $mn2->athletes()->attach([
        $athleteA->id => ['registration_id' => $registration->id],
        $athleteC->id => ['registration_id' => $registration->id],
    ]);

    Livewire::actingAs($user)
        ->test(NewMultiNomorReportIndex::class)
        ->assertSet('totalAtlet', 3)
        ->assertSee($contingent->name)
        ->assertHasNoErrors()
        ->assertViewHas('allAthletes', function ($allAthletes) use ($contingent) {
            foreach ($allAthletes as $athlete) {
                if ($athlete['contingent'] !== $contingent->name) {
                    return false;
                }
            }

            return true;
        });
});

test('can download excel report', function () {
    $user = User::factory()->create();

    // Seed database records
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    $mn1 = MatchNumber::create([
        'name' => 'Embu Tandoku',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'age_group_id' => $ageGroup->id,
    ]);

    $mn2 = MatchNumber::create([
        'name' => 'Randori 50kg',
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

    $contingent = Contingent::factory()->create();
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $registration->athletes()->attach([
        $athleteA->id,
        $athleteB->id,
        $athleteC->id,
    ]);

    $mn1->athletes()->attach([
        $athleteA->id => ['registration_id' => $registration->id],
        $athleteB->id => ['registration_id' => $registration->id],
    ]);
    $mn2->athletes()->attach([
        $athleteA->id => ['registration_id' => $registration->id],
        $athleteC->id => ['registration_id' => $registration->id],
    ]);

    $response = Livewire::actingAs($user)
        ->test(NewMultiNomorReportIndex::class)
        ->call('downloadExcel');

    $response->assertStatus(200);
});
