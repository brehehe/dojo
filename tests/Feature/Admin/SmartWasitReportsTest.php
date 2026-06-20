<?php

use App\Livewire\Admin\SmartWasit\NewLaporanPerbabakIndex;
use App\Livewire\Admin\SmartWasit\NewLaporanSmartWasitSummaryIndex;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed admin role if needed
    Role::firstOrCreate(['name' => 'Super Admin']);
});

test('summary and perbabak reports render and load polymorphic score records successfully', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    // Create a referee
    $refUser = User::factory()->create(['name' => 'Test Referee']);
    $referee = Referee::create([
        'user_id' => $refUser->id,
        'nik' => '1234567890',
        'phone' => '08123456789',
    ]);

    // Create support models
    $contingent = Contingent::factory()->create();
    $ageGroup = AgeGroup::create(['name' => 'Remaja', 'order' => 1, 'price' => 0]);
    $court = Court::create(['name' => 'Court 1']);

    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan Remaja',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
    ]);

    // Create a registration
    $registration = Registration::create([
        'contingent_id' => $contingent->id,
        'status' => 'paid',
    ]);

    // Create a drawing match number
    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $registration->id,
        'court_id' => $court->id,
        'round' => 'Penyisihan',
        'sequence_number' => 1,
        'draft_type' => 'embu',
        'metadata' => [
            'athlete_name' => 'Athlete Name',
            'athlete_ids' => [1],
        ],
    ]);

    // 1. Create a RefereeScoreDetail pointing to DrawingMatchNumber
    RefereeScoreDetail::create([
        'referee_id' => $referee->id,
        'match_number_id' => $matchNumber->id,
        'scorable_id' => $drawing->id,
        'scorable_type' => DrawingMatchNumber::class,
        'judge_index' => 1,
        'details' => [
            'goho_1' => '8.0',
            'juho_1' => '8.0',
            'ekspresi_1' => '8.0',
        ],
        'total_calculated_score' => 80.0,
    ]);

    // 2. Create a RefereeScoreDetail pointing to Registration
    RefereeScoreDetail::create([
        'referee_id' => $referee->id,
        'match_number_id' => $matchNumber->id,
        'scorable_id' => $registration->id,
        'scorable_type' => Registration::class,
        'judge_index' => 2,
        'details' => [
            'goho_1' => '8.5',
            'juho_1' => '8.5',
            'ekspresi_1' => '8.0',
        ],
        'total_calculated_score' => 84.0,
    ]);

    // Test NewLaporanSmartWasitSummaryIndex
    Livewire::actingAs($admin)
        ->test(NewLaporanSmartWasitSummaryIndex::class)
        ->assertStatus(200)
        ->assertSee('Laporan Smart Wasit')
        ->assertSee('Test Referee') // Check referee is listed
        ->set('tab', 'detail')
        ->assertSee($contingent->name); // Check detailed assessments are loaded

    // Test NewLaporanPerbabakIndex
    Livewire::actingAs($admin)
        ->test(NewLaporanPerbabakIndex::class)
        ->assertStatus(200)
        ->assertSee('Laporan Perbabak')
        ->assertSee('Test Referee');
});
