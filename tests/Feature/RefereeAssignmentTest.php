<?php

use App\Livewire\Admin\NewGenerateRefereeIndex;
use App\Livewire\Referee\RefereeScoringDashboard;
use App\Models\ActiveCourtReferee;
use App\Models\Athlete;
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

test('manual referee assignment restricts to exactly 5 referees', function () {
    // 1. Create Roles
    $roleArbitrase = Role::create(['name' => 'Arbitrase']);
    $rolePerwasitan = Role::create(['name' => 'Perwasitan']);

    // 2. Create Referees (6 needed to test selecting 6th)
    $referees = [];
    for ($i = 0; $i < 6; $i++) {
        $u = User::factory()->create();
        $u->assignRole($rolePerwasitan);
        $referees[] = Referee::create(['user_id' => $u->id, 'certification_level' => 'WASIT UTAMA']);
    }

    // 3. Create Court, Rundown, Session
    $court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    // 4. Test Livewire component
    $component = Livewire::test(NewGenerateRefereeIndex::class)
        ->call('openAssignModal', $rundown->id, $session->id, $court->id);

    // Toggle 4 referees
    for ($i = 0; $i < 4; $i++) {
        $component->call('toggleReferee', $referees[$i]->id);
    }

    // Attempting to save 4 referees should fail
    $component->call('saveReferees')
        ->assertHasErrors(['referees' => 'Harus memilih tepat 5 Wasit.']);

    // Toggle 5th referee
    $component->call('toggleReferee', $referees[4]->id);

    // Toggle 6th referee (should be blocked and dispatch a warning swal)
    $component->call('toggleReferee', $referees[5]->id)
        ->assertDispatched('swal', function ($eventName, $params) {
            $data = $params[0] ?? $params;

            return isset($data['title']) && $data['title'] === 'Peringatan!' && $data['icon'] === 'warning';
        });

    // Verify only 5 are selected
    expect(count($component->get('selectedReferees')))->toBe(5);

    // Save should succeed with 5 referees
    $component->call('saveReferees')
        ->assertHasNoErrors();

    // Verify 5 judges in ScheduleReferee
    $assignedCount = ScheduleReferee::where('rundown_id', $rundown->id)
        ->where('session_time_id', $session->id)
        ->where('court_id', $court->id)
        ->where('judge_index', '>', 0)
        ->count();

    expect($assignedCount)->toBe(5);
});

test('referee scoring dashboard correctly resolves referee from ActiveCourtReferee override in tablet mode', function () {
    $role = Role::firstOrCreate(['name' => 'Perwasitan']);
    $user = User::factory()->create(['judge_index' => 2, 'court_id' => 1]);
    $user->assignRole($role);
    $referee = Referee::create(['user_id' => $user->id, 'certification_level' => 'WASIT UTAMA']);

    $court = Court::create(['id' => 1, 'name' => 'Lapangan A', 'order' => 1]);
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

    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $drawing = DrawingMatchNumber::create([
        'id' => 123,
        'match_number_id' => $matchNumber->id,
        'registration_id' => $registration->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    $court->update([
        'active_match_id' => $matchNumber->id,
        'active_drawing_id' => $drawing->id,
    ]);

    ActiveCourtReferee::create([
        'court_id' => $court->id,
        'referee_id' => $referee->id,
        'judge_index' => 2,
    ]);

    $this->actingAs($user);
    $component = Livewire::test(RefereeScoringDashboard::class);

    expect($component->get('referee'))->not->toBeNull();
    expect($component->get('referee')->id)->toBe($referee->id);
    expect($component->get('judgeIndex'))->toBe(2);
});

test('personal mode resolves correct court when referee is scheduled on multiple courts', function () {
    $rolePerwasitan = Role::create(['name' => 'Perwasitan']);

    $user = User::factory()->create();
    $user->assignRole($rolePerwasitan);
    $referee = Referee::create(['user_id' => $user->id, 'certification_level' => 'Daerah']);

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);
    $matchNumber1 = MatchNumber::create(['name' => 'Embu Pasangan', 'gender' => 'Putra', 'draft_type' => 'embu', 'age_group_id' => $ageGroup->id]);
    $matchNumber2 = MatchNumber::create(['name' => 'Embu Beregu', 'gender' => 'Putri', 'draft_type' => 'embu', 'age_group_id' => $ageGroup->id]);

    $contingent = Contingent::create(['name' => 'Surabaya A', 'leader_name' => 'L', 'leader_phone' => '08123', 'leader_nik' => '1234567890123456']);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $court1 = Court::create(['name' => 'Court 1', 'order' => 1]);
    $court2 = Court::create(['name' => 'Court 2', 'order' => 2]);

    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session1 = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);
    $session2 = SessionTime::create(['name' => 'Sesi 2', 'start_time' => '10:00', 'end_time' => '12:00']);

    // Referee is assigned to Court 2 (session 1, judge 1) AND Court 1 (session 2, judge 2)
    ScheduleReferee::create(['referee_id' => $referee->id, 'court_id' => $court2->id, 'rundown_id' => $rundown->id, 'session_time_id' => $session1->id, 'judge_index' => 1]);
    ScheduleReferee::create(['referee_id' => $referee->id, 'court_id' => $court1->id, 'rundown_id' => $rundown->id, 'session_time_id' => $session2->id, 'judge_index' => 2]);

    // Active drawing on Court 1 belongs to session 2
    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber1->id,
        'registration_id' => $registration->id,
        'draft_type' => 'embu',
        'court_id' => $court1->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session2->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    // Activate Court 1 with match 1 and drawing, and call a participant
    $court1->update(['active_match_id' => $matchNumber1->id, 'active_drawing_id' => $drawing->id]);
    $matchNumber1->update(['active_registration_id' => $registration->id]);

    $this->actingAs($user);
    $component = Livewire::test(RefereeScoringDashboard::class);

    // Should resolve to Court 1, session 2, judge index 2 — NOT Court 2 session 1 judge 1
    // Should resolve to Court 1, session 2, judge index 2 — NOT Court 2 session 1 judge 1
    expect($component->get('assignedCourt'))->not->toBeNull();
    expect($component->get('assignedCourt')->id)->toBe($court1->id);
    expect($component->get('judgeIndex'))->toBe(2);
    expect($component->get('isFormOpen'))->toBeTrue();
});

test('manual referee assignment prevents duplicate assignments in the same session', function () {
    $rolePerwasitan = Role::firstOrCreate(['name' => 'Perwasitan']);

    // Create 10 referees
    $referees = [];
    for ($i = 0; $i < 10; $i++) {
        $u = User::factory()->create();
        $u->assignRole($rolePerwasitan);
        $referees[] = Referee::create(['user_id' => $u->id, 'certification_level' => 'WASIT UTAMA']);
    }

    $court1 = Court::create(['name' => 'Court 1', 'order' => 1]);
    $court2 = Court::create(['name' => 'Court 2', 'order' => 2]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    // Pre-assign 5 referees to Court 1
    for ($i = 0; $i < 5; $i++) {
        ScheduleReferee::create([
            'rundown_id' => $rundown->id,
            'session_time_id' => $session->id,
            'court_id' => $court1->id,
            'referee_id' => $referees[$i]->id,
            'judge_index' => $i + 1,
        ]);
    }

    // Now try to assign a panel to Court 2 that contains one of the referees from Court 1 (e.g. $referees[0])
    $component = Livewire::test(NewGenerateRefereeIndex::class)
        ->call('openAssignModal', $rundown->id, $session->id, $court2->id);

    // Toggle 4 fresh referees
    for ($i = 5; $i < 9; $i++) {
        $component->call('toggleReferee', $referees[$i]->id);
    }
    // Toggle 1 duplicate referee (who is on Court 1)
    $component->call('toggleReferee', $referees[0]->id);

    // Try to save - should fail
    $component->call('saveReferees')
        ->assertHasErrors(['referees']);
});

test('autoGenerateAllReferees assigns unique referees in the same session', function () {
    $roleArbitrase = Role::firstOrCreate(['name' => 'Arbitrase']);
    $rolePerwasitan = Role::firstOrCreate(['name' => 'Perwasitan']);

    // Create 2 Arbitrators
    $userArb1 = User::factory()->create();
    $userArb1->assignRole($roleArbitrase);
    $refArb1 = Referee::create(['user_id' => $userArb1->id, 'certification_level' => 'Nasional']);

    $userArb2 = User::factory()->create();
    $userArb2->assignRole($roleArbitrase);
    $refArb2 = Referee::create(['user_id' => $userArb2->id, 'certification_level' => 'Nasional']);

    // Create 15 Referees (enough for 3 courts * 5)
    $refereeIds = [];
    for ($i = 0; $i < 15; $i++) {
        $u = User::factory()->create();
        $u->assignRole($rolePerwasitan);
        $r = Referee::create(['user_id' => $u->id, 'certification_level' => $i === 0 ? 'WASIT UTAMA' : 'Daerah']);
        $refereeIds[] = $r->id;
    }

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);
    $matchNumber = MatchNumber::create(['name' => 'Embu', 'gender' => 'Putra', 'draft_type' => 'embu', 'age_group_id' => $ageGroup->id]);
    $contingent = Contingent::create(['name' => 'Sby', 'leader_name' => 'L', 'leader_phone' => '081', 'leader_nik' => '1234567890123456']);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $court1 = Court::create(['name' => 'Court 1', 'order' => 1]);
    $court2 = Court::create(['name' => 'Court 2', 'order' => 2]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    // Schedule matches on two courts
    DrawingMatchNumber::create(['match_number_id' => $matchNumber->id, 'registration_id' => $registration->id, 'draft_type' => 'embu', 'court_id' => $court1->id, 'rundown_id' => $rundown->id, 'session_time_id' => $session->id, 'sequence_number' => 1, 'round' => 'Penyisihan']);
    DrawingMatchNumber::create(['match_number_id' => $matchNumber->id, 'registration_id' => $registration->id, 'draft_type' => 'embu', 'court_id' => $court2->id, 'rundown_id' => $rundown->id, 'session_time_id' => $session->id, 'sequence_number' => 2, 'round' => 'Penyisihan']);

    Livewire::test(NewGenerateRefereeIndex::class)
        ->call('autoGenerateAllReferees');

    // Get all assigned referees for the session
    $allAssignedRefs = ScheduleReferee::where('rundown_id', $rundown->id)
        ->where('session_time_id', $session->id)
        ->pluck('referee_id')
        ->toArray();

    // Verify there are no duplicates
    expect(count($allAssignedRefs))->toBe(count(array_unique($allAssignedRefs)));
});

test('clearAllAssignments removes all referee assignments', function () {
    $rolePerwasitan = Role::firstOrCreate(['name' => 'Perwasitan']);
    $user = User::factory()->create();
    $user->assignRole($rolePerwasitan);
    $referee = Referee::create(['user_id' => $user->id, 'certification_level' => 'Daerah']);

    $court = Court::create(['name' => 'Court 1']);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);
    $matchNumber = MatchNumber::create(['name' => 'Embu', 'gender' => 'Putra', 'draft_type' => 'embu', 'age_group_id' => $ageGroup->id]);
    $contingent = Contingent::create(['name' => 'Sby', 'leader_name' => 'L', 'leader_phone' => '081', 'leader_nik' => '1234567890123456']);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    DrawingMatchNumber::create(['match_number_id' => $matchNumber->id, 'registration_id' => $registration->id, 'draft_type' => 'embu', 'court_id' => $court->id, 'rundown_id' => $rundown->id, 'session_time_id' => $session->id, 'sequence_number' => 1]);

    // Create an assignment
    ScheduleReferee::create([
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'court_id' => $court->id,
        'referee_id' => $referee->id,
        'judge_index' => 1,
    ]);

    Livewire::test(NewGenerateRefereeIndex::class)
        ->call('clearAllAssignments');

    expect(ScheduleReferee::count())->toBe(0);
});

test('resetAndGenerateAllReferees resets and generates fresh assignments', function () {
    $roleArbitrase = Role::firstOrCreate(['name' => 'Arbitrase']);
    $rolePerwasitan = Role::firstOrCreate(['name' => 'Perwasitan']);

    $userArb = User::factory()->create();
    $userArb->assignRole($roleArbitrase);
    $refArb = Referee::create(['user_id' => $userArb->id, 'certification_level' => 'Nasional']);

    $refereeIds = [];
    for ($i = 0; $i < 5; $i++) {
        $u = User::factory()->create();
        $u->assignRole($rolePerwasitan);
        $r = Referee::create(['user_id' => $u->id, 'certification_level' => 'Daerah']);
        $refereeIds[] = $r->id;
    }

    $court = Court::create(['name' => 'Court 1']);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);
    $matchNumber = MatchNumber::create(['name' => 'Embu', 'gender' => 'Putra', 'draft_type' => 'embu', 'age_group_id' => $ageGroup->id]);
    $contingent = Contingent::create(['name' => 'Sby', 'leader_name' => 'L', 'leader_phone' => '081', 'leader_nik' => '1234567890123456']);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    DrawingMatchNumber::create(['match_number_id' => $matchNumber->id, 'registration_id' => $registration->id, 'draft_type' => 'embu', 'court_id' => $court->id, 'rundown_id' => $rundown->id, 'session_time_id' => $session->id, 'sequence_number' => 1]);

    // Create an initial dummy assignment using a real referee without Perwasitan role so it's not selected during generation
    $dummyUser = User::factory()->create();
    $dummyReferee = Referee::create(['user_id' => $dummyUser->id, 'certification_level' => 'Daerah']);

    ScheduleReferee::create([
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'court_id' => $court->id,
        'referee_id' => $dummyReferee->id,
        'judge_index' => 1,
    ]);

    Livewire::test(NewGenerateRefereeIndex::class)
        ->call('resetAndGenerateAllReferees');

    // Dummy assignment should be gone, and a new unique panel of 5 referees + 1 dewan arbitrase should be generated.
    expect(ScheduleReferee::where('referee_id', $dummyReferee->id)->exists())->toBeFalse();
    expect(ScheduleReferee::count())->toBe(6); // 5 judges + 1 dewan arbitrase
});

test('referee scoring dashboard correctly resolves active athlete names for Embu match', function () {
    $rolePerwasitan = Role::firstOrCreate(['name' => 'Perwasitan']);

    $user = User::factory()->create();
    $user->assignRole($rolePerwasitan);
    $referee = Referee::create(['user_id' => $user->id, 'certification_level' => 'Daerah']);

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);
    $matchNumber = MatchNumber::create(['name' => 'Embu Pasangan', 'gender' => 'Putra', 'draft_type' => 'embu', 'age_group_id' => $ageGroup->id]);

    $contingent = Contingent::create(['name' => 'Surabaya A', 'leader_name' => 'L', 'leader_phone' => '08123', 'leader_nik' => '1234567890123456']);
    $registration = Registration::create(['contingent_id' => $contingent->id]);

    $athlete1 = Athlete::create([
        'name' => 'Kenshi Embu A',
        'nik' => '1111111111111111',
        'gender' => 'Male',
        'birth_place' => 'Surabaya',
        'birth_date' => '2010-01-01',
        'address' => 'Surabaya',
        'bpjs_number' => '12345',
        'bpjs_status' => 'Aktif',
    ]);
    $athlete2 = Athlete::create([
        'name' => 'Kenshi Embu B',
        'nik' => '2222222222222222',
        'gender' => 'Male',
        'birth_place' => 'Surabaya',
        'birth_date' => '2010-01-01',
        'address' => 'Surabaya',
        'bpjs_number' => '12346',
        'bpjs_status' => 'Aktif',
    ]);

    $registration->athletes()->attach([$athlete1->id, $athlete2->id], [
        'weight' => 45,
        'kyu' => 'Kyu 5',
        'rank' => 'Kyu 5',
        'age_group' => 'Pemula',
        'dojo_origin' => 'Dojo Surabaya',
        'city' => 'Surabaya',
        'match_type' => 'Tanding',
    ]);

    $athlete1->matchNumbers()->attach($matchNumber->id, ['registration_id' => $registration->id]);
    $athlete2->matchNumbers()->attach($matchNumber->id, ['registration_id' => $registration->id]);

    $court = Court::create(['name' => 'Court 1', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    ScheduleReferee::create([
        'referee_id' => $referee->id,
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'judge_index' => 1,
    ]);

    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'registration_id' => $registration->id,
        'draft_type' => 'embu',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    $court->update([
        'active_match_id' => $matchNumber->id,
        'active_drawing_id' => $drawing->id,
    ]);
    $matchNumber->update(['active_registration_id' => $registration->id]);

    $this->actingAs($user);
    $component = Livewire::test(RefereeScoringDashboard::class);

    expect($component->get('activeAthleteNames'))->toBe(['Kenshi Embu A', 'Kenshi Embu B']);
});

test('referee scoring dashboard correctly resolves active athlete names for Randori match', function () {
    $rolePerwasitan = Role::firstOrCreate(['name' => 'Perwasitan']);

    $user = User::factory()->create();
    $user->assignRole($rolePerwasitan);
    $referee = Referee::create(['user_id' => $user->id, 'certification_level' => 'Daerah']);

    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    // Create drawing data with custom bracket structure
    $drawingData = [
        'upper_bracket' => [
            'rounds' => [
                [
                    [
                        'athlete1' => [
                            'id' => 10,
                            'name' => 'Ahmad Red',
                            'contingent' => 'Surabaya',
                        ],
                        'athlete2' => [
                            'id' => 11,
                            'name' => 'Budi White',
                            'contingent' => 'Gresik',
                        ],
                    ],
                ],
            ],
        ],
    ];

    $matchNumber = MatchNumber::create([
        'name' => 'Randori Kelas A',
        'gender' => 'Putra',
        'draft_type' => 'randori',
        'age_group_id' => $ageGroup->id,
        'drawing_data' => $drawingData,
        'active_bracket_node' => 'ub_0_0',
    ]);

    $court = Court::create(['name' => 'Court 1', 'order' => 1]);
    $rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    ScheduleReferee::create([
        'referee_id' => $referee->id,
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'judge_index' => 1,
    ]);

    $drawing = DrawingMatchNumber::create([
        'match_number_id' => $matchNumber->id,
        'draft_type' => 'randori',
        'court_id' => $court->id,
        'rundown_id' => $rundown->id,
        'session_time_id' => $session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);

    $court->update([
        'active_match_id' => $matchNumber->id,
        'active_drawing_id' => $drawing->id,
        'active_bracket_node' => 'ub_0_0',
    ]);

    $this->actingAs($user);
    $component = Livewire::test(RefereeScoringDashboard::class);

    expect($component->get('activeAthleteNames'))->toBe(['Ahmad Red (Merah)', 'Budi White (Putih)']);
});
