<?php

use App\Livewire\Admin\NewMatchNumberIndex;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('new match number index component can render', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(NewMatchNumberIndex::class)
        ->assertStatus(200);
});

test('new match number index displays correct eksebisi and non-eksebisi stats', function () {
    $user = User::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    // 1. Embu Eksebisi
    MatchNumber::create([
        'name' => 'Embu Pasangan Kyu kenshi eksebisi',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'age_group_id' => $ageGroup->id,
    ]);

    // 2. Embu Non-Eksebisi
    MatchNumber::create([
        'name' => 'Embu Pasangan Kyu kenshi',
        'gender' => 'Male',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'age_group_id' => $ageGroup->id,
    ]);

    // 3. Randori Eksebisi
    MatchNumber::create([
        'name' => 'Randori 50kg eksebisi',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    // 4. Randori Non-Eksebisi
    MatchNumber::create([
        'name' => 'Randori 55kg',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    Livewire::actingAs($user)
        ->test(NewMatchNumberIndex::class)
        ->assertHasNoErrors()
        ->assertViewHas('totalEksebisi', 2)
        ->assertViewHas('totalNonEksebisi', 2)
        ->assertViewHas('totalEmbuEksebisi', 1)
        ->assertViewHas('totalEmbuNonEksebisi', 1)
        ->assertViewHas('totalRandoriEksebisi', 1)
        ->assertViewHas('totalRandoriNonEksebisi', 1);
});
