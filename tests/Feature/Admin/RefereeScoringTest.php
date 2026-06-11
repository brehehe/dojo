<?php

use App\Models\ActiveCourtReferee;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('referee scoring dashboard index renders svelte page', function () {
    $user = User::factory()->create();
    $referee = Referee::create([
        'user_id' => $user->id,
        'nik' => '1234567890',
        'phone' => '08123456789',
    ]);

    $response = $this->actingAs($user)
        ->get(route('admin.referee.scoring'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('RefereeScoring')
        );
});

test('referee scoring state returns correct json structure', function () {
    $user = User::factory()->create();
    $referee = Referee::create([
        'user_id' => $user->id,
        'nik' => '1234567890',
        'phone' => '08123456789',
    ]);

    $response = $this->actingAs($user)
        ->get(route('admin.referee.scoring.state'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'referee',
            'activeMatch',
            'activeDrawing',
            'assignedCourt',
            'assignedSession',
            'assignedRundown',
            'judgeIndex',
            'judgeLabel',
            'activeContingentName',
            'activeRoundLabel',
            'activeTechniqueLabel',
            'activeTechniqueList',
            'activeAthleteNames',
            'activeIsTeamCategory',
            'isFormOpen',
            'embuItems',
            'notes',
            'totalScore',
            'signature',
            'isTabletMode',
            'currentActiveIdentifier',
        ]);
});

test('referee scoring save updates score state successfully', function () {
    $user = User::factory()->create();
    $referee = Referee::create([
        'user_id' => $user->id,
        'nik' => '1234567890',
        'phone' => '08123456789',
    ]);

    $contingent = Contingent::factory()->create();
    $registration = Registration::create([
        'contingent_id' => $contingent->id,
        'status' => 'paid',
    ]);

    $court = Court::create(['name' => 'Court A', 'active_match_id' => null]);
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);

    $match = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
        'active_registration_id' => $registration->id,
    ]);

    $court->update([
        'active_match_id' => $match->id,
        'active_drawing_id' => null,
    ]);

    $activeRef = ActiveCourtReferee::create([
        'court_id' => $court->id,
        'referee_id' => $referee->id,
        'judge_index' => 1,
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('admin.referee.scoring.save'), [
            'embuItems' => [
                'goho_1' => '8.5',
                'goho_2' => '9,0',
                'goho_3' => '8.0',
                'juho_1' => '8.5',
                'juho_2' => '8.0',
                'juho_3' => '8.0',
                'ekspresi_1' => '8.0',
                'ekspresi_2' => '8.0',
                'ekspresi_3' => '8.0',
                'ekspresi_4' => '8.0',
            ],
            'notes' => 'Good performance',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'totalScore' => 82.0,
        ]);
});

test('referee scoring submit saves signature successfully', function () {
    $user = User::factory()->create();
    $referee = Referee::create([
        'user_id' => $user->id,
        'nik' => '1234567890',
        'phone' => '08123456789',
    ]);

    $contingent = Contingent::factory()->create();
    $registration = Registration::create([
        'contingent_id' => $contingent->id,
        'status' => 'paid',
    ]);

    $court = Court::create(['name' => 'Court A', 'active_match_id' => null]);
    $ageGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 1, 'price' => 0]);

    $match = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $ageGroup->id,
        'active_registration_id' => $registration->id,
    ]);

    $court->update([
        'active_match_id' => $match->id,
        'active_drawing_id' => null,
    ]);

    $activeRef = ActiveCourtReferee::create([
        'court_id' => $court->id,
        'referee_id' => $referee->id,
        'judge_index' => 1,
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('admin.referee.scoring.submit'), [
            'embuItems' => [
                'goho_1' => '8.5',
                'goho_2' => '9.0',
                'goho_3' => '8.0',
                'juho_1' => '8.5',
                'juho_2' => '8.0',
                'juho_3' => '8.0',
                'ekspresi_1' => '8.0',
                'ekspresi_2' => '8.0',
                'ekspresi_3' => '8.0',
                'ekspresi_4' => '8.0',
            ],
            'notes' => 'Finalized score',
            'signature' => 'data:image/png;base64,mockSignatureBytes',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Nilai Juri 1 telah disimpan.',
        ]);
});
