<?php

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Official;
use App\Models\Registration;

test('it removes empty contingents with no athletes and officials', function () {
    // 1. Contingent with Athlete
    $contingentWithAthlete = Contingent::create([
        'name' => 'Contingent with Athlete',
        'kab_kota' => 'Surabaya',
        'leader_name' => 'Leader A',
    ]);
    $regAthlete = Registration::create([
        'contingent_id' => $contingentWithAthlete->id,
        'status' => 'pending',
    ]);
    $athlete = Athlete::create([
        'name' => 'Athlete Test',
        'nik' => '1111111111111111',
        'gender' => 'Male',
        'bpjs_status' => 'Aktif',
        'birth_date' => '2000-01-01',
    ]);
    $regAthlete->athletes()->attach($athlete->id);

    // 2. Contingent with Official
    $contingentWithOfficial = Contingent::create([
        'name' => 'Contingent with Official',
        'kab_kota' => 'Malang',
        'leader_name' => 'Leader B',
    ]);
    $regOfficial = Registration::create([
        'contingent_id' => $contingentWithOfficial->id,
        'status' => 'pending',
    ]);
    $official = Official::create([
        'contingent_id' => $contingentWithOfficial->id,
        'name' => 'Official Test',
        'role' => 'Manager',
    ]);
    $regOfficial->officials()->attach($official->id, ['role' => 'Manager']);

    // 3. Empty Contingent (No Registration at all)
    $emptyContingentNoReg = Contingent::create([
        'name' => 'Empty Contingent No Reg',
        'kab_kota' => 'Gresik',
        'leader_name' => 'Leader C',
    ]);

    // 4. Empty Contingent (Has Registration, but 0 athletes & 0 officials)
    $emptyContingentWithReg = Contingent::create([
        'name' => 'Empty Contingent With Reg',
        'kab_kota' => 'Sidoarjo',
        'leader_name' => 'Leader D',
    ]);
    $regEmpty = Registration::create([
        'contingent_id' => $emptyContingentWithReg->id,
        'status' => 'pending',
    ]);

    // Test Dry Run
    $this->artisan('app:remove-empty-contingents --dry-run')
        ->assertExitCode(0)
        ->expectsOutput('=== Summary of Empty Contingents to Delete ===')
        ->expectsOutput('  * ID: '.$emptyContingentNoReg->id.' - Empty Contingent No Reg (Created: '.$emptyContingentNoReg->created_at.')')
        ->expectsOutput('  * ID: '.$emptyContingentWithReg->id.' - Empty Contingent With Reg (Created: '.$emptyContingentWithReg->created_at.')');

    // Assert that database still has all records after dry-run
    $this->assertDatabaseHas('contingents', ['id' => $contingentWithAthlete->id]);
    $this->assertDatabaseHas('contingents', ['id' => $contingentWithOfficial->id]);
    $this->assertDatabaseHas('contingents', ['id' => $emptyContingentNoReg->id]);
    $this->assertDatabaseHas('contingents', ['id' => $emptyContingentWithReg->id]);

    // Test Real Execution with Force
    $this->artisan('app:remove-empty-contingents --force')
        ->assertExitCode(0)
        ->expectsOutput('Deleting empty contingents...')
        ->expectsOutput('Successfully deleted all empty contingents!');

    // Assert that empty ones are deleted
    $this->assertDatabaseMissing('contingents', ['id' => $emptyContingentNoReg->id]);
    $this->assertDatabaseMissing('contingents', ['id' => $emptyContingentWithReg->id]);

    // Assert that non-empty ones are preserved
    $this->assertDatabaseHas('contingents', ['id' => $contingentWithAthlete->id]);
    $this->assertDatabaseHas('contingents', ['id' => $contingentWithOfficial->id]);
});
