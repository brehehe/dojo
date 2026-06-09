<?php

use App\Livewire\Admin\NewRegistrationVerificationIndex;
use App\Livewire\Admin\NewTechnicalMeetingDrawingIndex;
use App\Livewire\Contingent\AthleteVerificationIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\Group\AgeGroup;
use App\Models\Group\WeightGroup;
use App\Models\KyuLevel;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed roles
    Role::firstOrCreate(['name' => 'Admin']);
    Role::firstOrCreate(['name' => 'Contingent']);

    // Seed basic lookup tables
    $this->kyu = KyuLevel::create(['name' => 'Kyu 5', 'order' => 1]);
    $this->weight = WeightGroup::create(['name' => '40-50 kg', 'order' => 1]);
    $this->ageGroup = AgeGroup::create(['name' => 'Pemula', 'price' => 400000, 'order' => 1]);

    // Create Match Numbers
    $this->match = MatchNumber::create([
        'name' => 'Test Randori Match',
        'gender' => 'Male',
        'draft_type' => 'randori',
        'max_athletes' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);

    // Create Admin & Contingent Users
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole('Admin');

    $this->contingentUser = User::factory()->create();
    $this->contingentUser->assignRole('Contingent');

    $this->contingent = Contingent::create([
        'user_id' => $this->contingentUser->id,
        'name' => 'Surabaya Contingent',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'John Doe',
        'leader_phone' => '0812345678',
        'email' => $this->contingentUser->email,
        'address' => 'Surabaya',
    ]);

    // Seed schedule dependencies for drawings
    Rundown::create([
        'name' => 'Hari 1',
        'type' => 'pertandingan',
        'date' => '2026-06-10',
        'order' => 1,
    ]);

    SessionTime::create([
        'name' => 'Sesi Pagi',
        'start_time' => '2026-06-10 08:00:00',
        'end_time' => '2026-06-10 12:00:00',
    ]);

    Court::create([
        'name' => 'Lapangan 1',
        'order' => 1,
    ]);
});

test('a new registration defaults to pending athlete status', function () {
    $registration = Registration::create([
        'contingent_id' => $this->contingent->id,
        'status' => 'pending',
    ]);

    expect($registration->athlete_status)->toBe('pending');
});

test('contingent user can confirm athlete data as correct', function () {
    $registration = Registration::create([
        'contingent_id' => $this->contingent->id,
        'status' => 'verified',
        'athlete_status' => 'pending',
    ]);

    Livewire::actingAs($this->contingentUser)
        ->test(AthleteVerificationIndex::class)
        ->call('confirmDataCorrect', $registration->id);

    $registration->refresh();
    expect($registration->athlete_status)->toBe('pending');
});

test('contingent user can edit athlete details and it resets verification status to pending', function () {
    $registration = Registration::create([
        'contingent_id' => $this->contingent->id,
        'status' => 'verified',
        'athlete_status' => 'verified', // already verified
    ]);

    $athlete = Athlete::create([
        'name' => 'Kenshi A',
        'nik' => '1234567890123456',
        'gender' => 'Male',
        'birth_place' => 'Surabaya',
        'birth_date' => '2010-01-01',
        'address' => 'Surabaya',
        'bpjs_number' => '12345',
        'bpjs_status' => 'Aktif',
    ]);
    $athlete->contingents()->attach($this->contingent->id, ['is_primary' => true]);
    $registration->athletes()->attach($athlete->id, [
        'weight' => 45,
        'kyu' => 'Kyu 5',
        'rank' => 'Kyu 5',
        'dojo_origin' => 'Dojo Surabaya',
        'city' => 'Surabaya',
        'match_type' => 'Tanding',
    ]);
    $athlete->matchNumbers()->attach($this->match->id, [
        'registration_id' => $registration->id,
    ]);

    Livewire::actingAs($this->contingentUser)
        ->test(AthleteVerificationIndex::class)
        ->set('selectedRegistrationId', $registration->id)
        ->call('openEditAthlete', $athlete->id)
        ->set('editName', 'Kenshi A Updated')
        ->set('editWeight', 48.5)
        ->call('saveAthlete');

    // Verification status must reset to pending
    $registration->refresh();
    expect($registration->athlete_status)->toBe('pending');

    // Athlete details updated in database
    $athlete->refresh();
    expect($athlete->name)->toBe('Kenshi A Updated');

    $pivot = $registration->athletes()->where('athlete_id', $athlete->id)->first()->pivot;
    expect((float) $pivot->weight)->toBe(48.5);
});

test('admin can toggle athlete verification status', function () {
    $registration = Registration::create([
        'contingent_id' => $this->contingent->id,
        'status' => 'verified',
        'athlete_status' => 'pending',
    ]);

    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationVerificationIndex::class)
        ->call('toggleVerification', $registration->id);

    $registration->refresh();
    expect($registration->athlete_status)->toBe('verified');

    // Toggle back to pending
    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationVerificationIndex::class)
        ->call('toggleVerification', $registration->id);

    $registration->refresh();
    expect($registration->athlete_status)->toBe('pending');
});

test('drawing only includes athletes with verified payments and verified athlete status', function () {
    $registration = Registration::create([
        'contingent_id' => $this->contingent->id,
        'status' => 'verified',
        'athlete_status' => 'pending', // NOT verified yet
    ]);

    $athlete = Athlete::create([
        'name' => 'Kenshi A',
        'nik' => '1234567890123456',
        'gender' => 'Male',
        'birth_place' => 'Surabaya',
        'birth_date' => '2010-01-01',
        'address' => 'Surabaya',
        'bpjs_number' => '12345',
        'bpjs_status' => 'Aktif',
    ]);
    $athlete->contingents()->attach($this->contingent->id, ['is_primary' => true]);
    $registration->athletes()->attach($athlete->id, [
        'weight' => 45,
        'kyu' => 'Kyu 5',
        'rank' => 'Kyu 5',
        'dojo_origin' => 'Dojo Surabaya',
        'city' => 'Surabaya',
        'match_type' => 'Tanding',
    ]);
    $athlete->matchNumbers()->attach($this->match->id, [
        'registration_id' => $registration->id,
    ]);

    // Test component's generate drawing method
    $drawingComponent = new NewTechnicalMeetingDrawingIndex;
    $drawingComponent->filterMatchNumberId = $this->match->id;

    // Call randori drawing generation, should return false (or skip) because registration's athlete_status is pending
    $result = $drawingComponent->generateRandoriDrawing(false);
    expect($result)->toBeFalse();

    // Verify registrations
    $registration->update(['athlete_status' => 'verified']);

    // Now drawing generation should find athletes and execute drawing (mocking minimum athletes requirement to avoid skip, but let's see)
    // Actually, drawing requires at least 3 participants. Let's add 2 more contingents to verify successful drawing generation
    $contingent2 = Contingent::create(['name' => 'Contingent 2', 'kab_kota' => 'Malang', 'leader_name' => 'Leader 2']);
    $reg2 = Registration::create(['contingent_id' => $contingent2->id, 'status' => 'verified', 'athlete_status' => 'verified']);
    $ath2 = Athlete::create(['name' => 'Athlete 2', 'nik' => '2222222222222222', 'gender' => 'Male', 'birth_date' => '2010-01-01', 'birth_place' => 'Malang', 'address' => 'Malang', 'bpjs_number' => '1', 'bpjs_status' => 'Aktif']);
    $ath2->contingents()->attach($contingent2->id, ['is_primary' => true]);
    $reg2->athletes()->attach($ath2->id, ['weight' => 45, 'kyu' => 'Kyu 5', 'rank' => 'Kyu 5', 'dojo_origin' => 'Dojo Malang', 'city' => 'Malang', 'match_type' => 'Tanding']);
    $ath2->matchNumbers()->attach($this->match->id, ['registration_id' => $reg2->id]);

    $contingent3 = Contingent::create(['name' => 'Contingent 3', 'kab_kota' => 'Gresik', 'leader_name' => 'Leader 3']);
    $reg3 = Registration::create(['contingent_id' => $contingent3->id, 'status' => 'verified', 'athlete_status' => 'verified']);
    $ath3 = Athlete::create(['name' => 'Athlete 3', 'nik' => '3333333333333333', 'gender' => 'Male', 'birth_date' => '2010-01-01', 'birth_place' => 'Gresik', 'address' => 'Gresik', 'bpjs_number' => '1', 'bpjs_status' => 'Aktif']);
    $ath3->contingents()->attach($contingent3->id, ['is_primary' => true]);
    $reg3->athletes()->attach($ath3->id, ['weight' => 45, 'kyu' => 'Kyu 5', 'rank' => 'Kyu 5', 'dojo_origin' => 'Dojo Gresik', 'city' => 'Gresik', 'match_type' => 'Tanding']);
    $ath3->matchNumbers()->attach($this->match->id, ['registration_id' => $reg3->id]);

    // Re-run with 3 verified participants
    $resultVerified = $drawingComponent->generateRandoriDrawing(false);
    expect($resultVerified)->toBeTrue();
});

test('admin can toggle selection and verify selected registrations athlete status', function () {
    $contingent1 = Contingent::create([
        'name' => 'C1',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'Leader 1',
        'leader_phone' => '0812345678',
        'email' => 'c1@example.com',
        'address' => 'Surabaya',
    ]);
    $contingent2 = Contingent::create([
        'name' => 'C2',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'Leader 2',
        'leader_phone' => '0812345678',
        'email' => 'c2@example.com',
        'address' => 'Surabaya',
    ]);

    $reg1 = Registration::create([
        'contingent_id' => $contingent1->id,
        'status' => 'verified',
        'athlete_status' => 'pending',
    ]);

    $reg2 = Registration::create([
        'contingent_id' => $contingent2->id,
        'status' => 'verified',
        'athlete_status' => 'pending',
    ]);

    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationVerificationIndex::class)
        ->set('selectAll', true)
        ->assertSet('selectedRows', [(string) $reg1->id, (string) $reg2->id])
        ->call('verifySelected');

    $reg1->refresh();
    $reg2->refresh();

    expect($reg1->athlete_status)->toBe('verified');
    expect($reg2->athlete_status)->toBe('verified');
});

test('admin can toggle selection and unverify selected registrations athlete status', function () {
    $contingent1 = Contingent::create([
        'name' => 'C1',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'Leader 1',
        'leader_phone' => '0812345678',
        'email' => 'c1@example.com',
        'address' => 'Surabaya',
    ]);
    $contingent2 = Contingent::create([
        'name' => 'C2',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'Leader 2',
        'leader_phone' => '0812345678',
        'email' => 'c2@example.com',
        'address' => 'Surabaya',
    ]);

    $reg1 = Registration::create([
        'contingent_id' => $contingent1->id,
        'status' => 'verified',
        'athlete_status' => 'verified',
    ]);

    $reg2 = Registration::create([
        'contingent_id' => $contingent2->id,
        'status' => 'verified',
        'athlete_status' => 'verified',
    ]);

    Livewire::actingAs($this->adminUser)
        ->test(NewRegistrationVerificationIndex::class)
        ->set('selectedRows', [(string) $reg1->id, (string) $reg2->id])
        ->call('unverifySelected');

    $reg1->refresh();
    $reg2->refresh();

    expect($reg1->athlete_status)->toBe('pending');
    expect($reg2->athlete_status)->toBe('pending');
});
