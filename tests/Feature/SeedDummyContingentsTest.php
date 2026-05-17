<?php

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Registration;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Group\AgeGroup;
use Illuminate\Support\Facades\DB;

test('it fails if surabaya a does not exist', function () {
    $this->artisan('app:seed-dummy-contingents')
        ->assertExitCode(1)
        ->expectsOutput('Contingent Surabaya A not found. Please run DummySeeder first.');
});

test('it seeds dummy data for other contingents', function () {
    // Create Surabaya A
    Contingent::create(['name' => 'Surabaya A', 'kab_kota' => 'Surabaya', 'leader_name' => 'Ketua Surabaya A']);
    
    // Create another contingent
    $contingentB = Contingent::create(['name' => 'Surabaya B', 'kab_kota' => 'Surabaya', 'leader_name' => 'Ketua Surabaya B']);
    
    // Create Age Group
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'price' => 400000, 'order' => 1]);

    // Create a match number
    MatchNumber::create([
        'name' => 'Test Match',
        'age_group_id' => $ageGroup->id,
        'gender' => 'Male',
        'draft_type' => 'embu',
        'max_athletes' => 1,
        'order' => 1,
    ]);

    $this->artisan('app:seed-dummy-contingents')
        ->assertExitCode(0)
        ->expectsOutput('Seeding dummy data for other contingents...')
        ->expectsOutput('✅ Seeding completed.');

    // Assert that a registration was created for Surabaya B
    $this->assertDatabaseHas('registrations', [
        'contingent_id' => $contingentB->id,
    ]);

    // Assert that athletes were created
    $this->assertEquals(2, Athlete::whereHas('contingents', function ($query) use ($contingentB) {
        $query->where('contingent_id', $contingentB->id);
    })->count());
});

test('it resets dummy data but keeps surabaya a', function () {
    // Create Surabaya A
    $surabayaA = Contingent::create(['name' => 'Surabaya A', 'kab_kota' => 'Surabaya', 'leader_name' => 'Ketua Surabaya A']);
    
    // Create another contingent
    $contingentB = Contingent::create(['name' => 'Surabaya B', 'kab_kota' => 'Surabaya', 'leader_name' => 'Ketua Surabaya B']);
    
    // Create Age Group
    $ageGroup = AgeGroup::create(['name' => 'Pemula', 'price' => 400000, 'order' => 1]);

    // Create a match number
    $match = MatchNumber::create([
        'name' => 'Test Match',
        'age_group_id' => $ageGroup->id,
        'gender' => 'Male',
        'draft_type' => 'embu',
        'max_athletes' => 1,
        'order' => 1,
    ]);

    // Create registration and athlete for Surabaya A (real data)
    $regA = Registration::create(['contingent_id' => $surabayaA->id, 'status' => 'pending']);
    $athleteA = Athlete::create(['name' => 'Real Athlete', 'nik' => '1234567890123456', 'bpjs_status' => 'Aktif', 'gender' => 'Male', 'birth_date' => '1990-01-01']);
    $athleteA->contingents()->attach($surabayaA->id, ['is_primary' => true]);
    $regA->athletes()->attach($athleteA->id);

    // Create registration and athlete for Surabaya B (dummy data)
    $regB = Registration::create(['contingent_id' => $contingentB->id, 'status' => 'pending']);
    $athleteB = Athlete::create(['name' => 'Dummy Athlete', 'nik' => '6543210987654321', 'bpjs_status' => 'Aktif', 'gender' => 'Male', 'birth_date' => '1990-01-01']);
    $athleteB->contingents()->attach($contingentB->id, ['is_primary' => true]);
    $regB->athletes()->attach($athleteB->id);

    // Run reset
    $this->artisan('app:seed-dummy-contingents --reset')
        ->assertExitCode(0)
        ->expectsOutput('Resetting dummy data (keeping Surabaya A)...')
        ->expectsOutput('✅ Reset completed.');

    // Assert that Surabaya A registration still exists
    $this->assertDatabaseHas('registrations', [
        'id' => $regA->id,
    ]);

    // Assert that Surabaya A athlete still exists
    $this->assertDatabaseHas('athletes', [
        'id' => $athleteA->id,
    ]);

    // Assert that Surabaya B registration is deleted
    $this->assertDatabaseMissing('registrations', [
        'id' => $regB->id,
    ]);

    // Assert that Surabaya B athlete is deleted
    $this->assertDatabaseMissing('athletes', [
        'id' => $athleteB->id,
    ]);
});
