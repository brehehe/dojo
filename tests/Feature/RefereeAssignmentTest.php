<?php

use App\Livewire\Admin\NewGenerateRefereeIndex;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\ScheduleReferee;
use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('autoGenerateAllReferees correctly segregates Arbitrase and Perwasitan roles', function () {
    // 1. Create Roles
    $roleArbitrase = Role::create(['name' => 'Arbitrase']);
    $rolePerwasitan = Role::create(['name' => 'Perwasitan']);

    // 2. Create Arbitrators (1 needed)
    $userArb = User::factory()->create();
    $userArb->assignRole($roleArbitrase);
    $refArb = Referee::create(['user_id' => $userArb->id, 'certification_level' => 'Nasional']);

    // 3. Create Referees (5 needed)
    $refereeIds = [];
    for ($i = 0; $i < 5; $i++) {
        $u = User::factory()->create();
        $u->assignRole($rolePerwasitan);
        $r = Referee::create(['user_id' => $u->id, 'certification_level' => 'Daerah']);
        $refereeIds[] = $r->id;
    }

    // 4. Create Match Number, Registration, Court, Rundown, Session Time
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);
    $matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'gender' => 'Putra',
        'draft_type' => 'embu',
        'age_group_id' => $ageGroup->id,
    ]);
    $contingent = Contingent::create([
        'name' => 'Surabaya A',
        'leader_name' => 'Leader A',
        'leader_phone' => '0812345678',
        'leader_nik' => '1234567890123456',
    ]);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    // 5. Create active drawing match scheduled on this court/shift
    DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $registration->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    // 6. Execute Livewire autoGenerateAllReferees component method
    Livewire::test(NewGenerateRefereeIndex::class)
        ->call('autoGenerateAllReferees');

    // 7. Assertions
    // Verify Dewan Arbitrase was assigned for the shift (judge_index = 0, court_id = null)
    $dewan = ScheduleReferee::where('rundown_id', $rundown->id)
        ->where('session_time_id', $session->id)
        ->whereNull('court_id')
        ->where('judge_index', 0)
        ->first();

    expect($dewan)->not->toBeNull();
    expect($dewan->referee_id)->toBe($refArb->id);

    // Verify 5 Panel Referees were assigned (judge_index > 0, court_id = court->id)
    $panels = ScheduleReferee::where('rundown_id', $rundown->id)
        ->where('session_time_id', $session->id)
        ->where('court_id', $court->id)
        ->where('judge_index', '>', 0)
        ->pluck('referee_id')
        ->toArray();

    expect(count($panels))->toBe(5);
    foreach ($panels as $pId) {
        expect($refereeIds)->toContain($pId);
        expect($pId)->not->toBe($refArb->id); // Ensure no arbitrator got assigned as panel judge
    }
});
