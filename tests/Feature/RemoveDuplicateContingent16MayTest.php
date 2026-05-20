<?php

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Registration;
use Carbon\Carbon;

test('it removes duplicate contingents, registrations, and athletes created on 16 May 2026', function () {
    // Set system time to mock creation
    Carbon::setTestNow('2026-05-16 12:00:00');

    // Create 16 May contingents
    $contingent16 = Contingent::create([
        'name' => 'Surabaya A (16 May)',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'Leader A',
    ]);

    // Create registration for 16 May contingent
    $reg16 = Registration::create([
        'contingent_id' => $contingent16->id,
        'status' => 'pending',
    ]);

    // Advance time to 17 May for athlete creation (as we found in db)
    Carbon::setTestNow('2026-05-17 12:00:00');

    // Create 17 May athlete associated with the 16 May contingent
    $athlete17 = Athlete::create([
        'name' => 'Athlete 17',
        'nik' => '1234567890123456',
        'gender' => 'Male',
        'bpjs_status' => 'Aktif',
        'birth_date' => '2000-01-01',
    ]);
    $athlete17->contingents()->attach($contingent16->id, ['is_primary' => true]);

    // Create a 17 May contingent that should NOT be deleted
    $contingent17 = Contingent::create([
        'name' => 'Surabaya A (17 May)',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'Leader B',
    ]);

    $reg17 = Registration::create([
        'contingent_id' => $contingent17->id,
        'status' => 'pending',
    ]);

    $athlete17_keep = Athlete::create([
        'name' => 'Athlete Keep',
        'nik' => '9876543210987654',
        'gender' => 'Male',
        'bpjs_status' => 'Aktif',
        'birth_date' => '2000-01-01',
    ]);
    $athlete17_keep->contingents()->attach($contingent17->id, ['is_primary' => true]);

    // Reset mocked time
    Carbon::setTestNow();

    // 1. Dry run execution
    $this->artisan('app:remove-duplicate-contingent16-may --dry-run')
        ->assertExitCode(0)
        ->expectsOutput('=== Summary of Data to Delete ===');

    // Assert database still has 16 May records
    $this->assertDatabaseHas('contingents', ['id' => $contingent16->id]);
    $this->assertDatabaseHas('registrations', ['id' => $reg16->id]);
    $this->assertDatabaseHas('athletes', ['id' => $athlete17->id]);

    // 2. Real execution (without forcing)
    $this->artisan('app:remove-duplicate-contingent16-may')
        ->expectsConfirmation('Are you sure you want to delete these records? This action CANNOT be undone!', 'yes')
        ->expectsOutput('Deleting records...')
        ->expectsOutput('Successfully deleted all duplicate records!')
        ->assertExitCode(0);

    // Assert 16 May records are deleted
    $this->assertDatabaseMissing('contingents', ['id' => $contingent16->id]);
    $this->assertDatabaseMissing('registrations', ['id' => $reg16->id]);
    $this->assertDatabaseMissing('athletes', ['id' => $athlete17->id]);
    $this->assertDatabaseMissing('athlete_contingent', [
        'athlete_id' => $athlete17->id,
        'contingent_id' => $contingent16->id,
    ]);

    // Assert 17 May records are NOT deleted
    $this->assertDatabaseHas('contingents', ['id' => $contingent17->id]);
    $this->assertDatabaseHas('registrations', ['id' => $reg17->id]);
    $this->assertDatabaseHas('athletes', ['id' => $athlete17_keep->id]);
});
