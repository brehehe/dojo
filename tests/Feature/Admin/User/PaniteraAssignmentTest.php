<?php

use App\Livewire\Admin\NewGeneratePaniteraIndex;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\SchedulePanitera;
use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    $adminRole = Role::findOrCreate('Admin', 'web');
    $this->admin->assignRole($adminRole);

    $this->roleKoor = Role::findOrCreate('Koordinator Lapangan', 'web');
    $this->rolePanitera = Role::findOrCreate('Panitera', 'web');

    // Create 2 Koordinators
    $this->koorUser1 = User::factory()->create(['name' => 'Joko Koor 1']);
    $this->koorUser1->assignRole($this->roleKoor);
    $this->koorUser2 = User::factory()->create(['name' => 'Joko Koor 2']);
    $this->koorUser2->assignRole($this->roleKoor);

    // Create 4 Paniteras
    $this->paniteras = [];
    for ($i = 1; $i <= 4; $i++) {
        $u = User::factory()->create(['name' => "Panitera {$i}"]);
        $u->assignRole($this->rolePanitera);
        $this->paniteras[] = $u;
    }

    // Set up dummy court and match number drawing data
    $this->ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);
    $this->matchNumber = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'gender' => 'Putra',
        'draft_type' => 'embu',
        'age_group_id' => $this->ageGroup->id,
    ]);
    $this->contingent = Contingent::create([
        'name' => 'Surabaya A',
        'leader_name' => 'Leader A',
        'leader_phone' => '0812345678',
        'leader_nik' => '1234567890123456',
    ]);
    $this->registration = Registration::create(['contingent_id' => $this->contingent->id]);

    $this->court = Court::create(['name' => 'Lapangan A', 'order' => 1]);
    $this->rundown = Rundown::create(['name' => 'Hari 1', 'date' => now()->toDateString()]);
    $this->session = SessionTime::create(['name' => 'Sesi 1', 'start_time' => '08:00', 'end_time' => '10:00']);

    // Create active drawing scheduled on court/shift
    DrawingMatchNumber::create([
        'match_number_id' => $this->matchNumber->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'embu',
        'court_id' => $this->court->id,
        'rundown_id' => $this->rundown->id,
        'session_time_id' => $this->session->id,
        'sequence_number' => 1,
        'round' => 'Penyisihan',
    ]);
});

it('allows admin to view new-generate-panitera page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.new-generate-panitera'))
        ->assertStatus(200);
});

it('allows admin to assign koordinator and panitera manually', function () {
    // 1. Assign Coordinators
    Livewire::actingAs($this->admin)
        ->test(NewGeneratePaniteraIndex::class)
        ->call('openAssignModal', $this->rundown->id, $this->session->id, $this->court->id, 'koordinator')
        ->call('toggleOfficer', (string) $this->koorUser1->id)
        ->call('toggleOfficer', (string) $this->koorUser2->id)
        ->call('saveAssignment')
        ->assertDispatched('swal');

    // 2. Assign Clerks (Panitera)
    Livewire::actingAs($this->admin)
        ->test(NewGeneratePaniteraIndex::class)
        ->call('openAssignModal', $this->rundown->id, $this->session->id, $this->court->id, 'panitera')
        ->call('toggleOfficer', (string) $this->paniteras[0]->id)
        ->call('toggleOfficer', (string) $this->paniteras[1]->id)
        ->call('saveAssignment')
        ->assertDispatched('swal');

    // Assert database has correct coordinator assignments
    $this->assertDatabaseHas('schedule_paniteras', [
        'rundown_id' => $this->rundown->id,
        'session_time_id' => $this->session->id,
        'court_id' => $this->court->id,
        'user_id' => $this->koorUser1->id,
        'role_type' => 'koordinator',
    ]);

    $this->assertDatabaseHas('schedule_paniteras', [
        'rundown_id' => $this->rundown->id,
        'session_time_id' => $this->session->id,
        'court_id' => $this->court->id,
        'user_id' => $this->koorUser2->id,
        'role_type' => 'koordinator',
    ]);

    // Assert database has correct clerk assignments
    $this->assertDatabaseHas('schedule_paniteras', [
        'rundown_id' => $this->rundown->id,
        'session_time_id' => $this->session->id,
        'court_id' => $this->court->id,
        'user_id' => $this->paniteras[0]->id,
        'role_type' => 'panitera',
    ]);

    $this->assertDatabaseHas('schedule_paniteras', [
        'rundown_id' => $this->rundown->id,
        'session_time_id' => $this->session->id,
        'court_id' => $this->court->id,
        'user_id' => $this->paniteras[1]->id,
        'role_type' => 'panitera',
    ]);
});

it('can auto generate coordinators and paniteras for active shifts', function () {
    Livewire::actingAs($this->admin)
        ->test(NewGeneratePaniteraIndex::class)
        ->call('autoGenerateAllOfficers')
        ->assertDispatched('swal');

    // Verify Koordinator was assigned (role_type = koordinator)
    $koorAssignments = SchedulePanitera::where('rundown_id', $this->rundown->id)
        ->where('session_time_id', $this->session->id)
        ->where('court_id', $this->court->id)
        ->where('role_type', 'koordinator')
        ->pluck('user_id')
        ->toArray();

    expect(count($koorAssignments))->toBeGreaterThanOrEqual(1);

    // Verify Paniteras were assigned (role_type = panitera)
    $paniteraAssignments = SchedulePanitera::where('rundown_id', $this->rundown->id)
        ->where('session_time_id', $this->session->id)
        ->where('court_id', $this->court->id)
        ->where('role_type', 'panitera')
        ->pluck('user_id')
        ->toArray();

    expect(count($paniteraAssignments))->toBeGreaterThanOrEqual(1);
    foreach ($paniteraAssignments as $userId) {
        expect(collect($this->paniteras)->pluck('id')->toArray())->toContain($userId);
    }
});
