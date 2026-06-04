<?php

use App\Livewire\Admin\Reports\AdminRefereeObservationsIndex;
use App\Livewire\Contingent\RefereeObservationForm;
use App\Livewire\Contingent\RefereeObservationIndex;
use App\Models\Contingent;
use App\Models\Referee;
use App\Models\RefereeObservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed Spatie roles
    Role::firstOrCreate(['name' => 'Contingent']);
    Role::firstOrCreate(['name' => 'Admin']);
    Role::firstOrCreate(['name' => 'Super Admin']);
});

test('contingent user can access referee observation index', function () {
    $user = User::factory()->create();
    $user->assignRole('Contingent');

    $contingent = Contingent::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(RefereeObservationIndex::class)
        ->assertStatus(200)
        ->assertSee('Observasi Wasit');
});

test('contingent user can render create observation form', function () {
    $user = User::factory()->create();
    $user->assignRole('Contingent');

    $contingent = Contingent::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(RefereeObservationForm::class)
        ->assertStatus(200)
        ->assertSee('Identitas Pengamat');
});

test('filling form triggers automatic calculations and saves record', function () {
    $user = User::factory()->create();
    $user->assignRole('Contingent');

    $contingent = Contingent::factory()->create(['user_id' => $user->id]);

    $refUser = User::factory()->create(['name' => 'John Referee']);
    $referee = Referee::create([
        'user_id' => $refUser->id,
        'nik' => '1234567890123456',
    ]);

    Livewire::actingAs($user)
        ->test(RefereeObservationForm::class)
        ->set('referee_id', $referee->id)
        ->set('court', 'Court 2')
        ->set('round', 'Semifinal')
        ->set('match_time', '11:45')
        ->set('p1.rows.0.waktu', '1')
        ->set('p1.rows.0.konsisten', 'ya') // 1 consistent out of 6 standard rows -> P1 score = 1/6 * 30 = 5 points
        ->set('p2.ratings.1', 5)
        ->set('p2.ratings.2', 4)
        ->set('p2.ratings.3', 5)
        ->set('p2.ratings.4', 4)
        ->set('p2.ratings.5', 5)
        ->set('p2.ratings.6', 4) // P2 total = 27/30 -> P2 score = 27/30 * 20 = 18 points
        ->set('p3.ratings.1', 5)
        ->set('p3.ratings.2', 5)
        ->set('p3.ratings.3', 5)
        ->set('p3.ratings.4', 5)
        ->set('p3.ratings.5', 5) // P3 total = 25/25 -> P3 score = 25/25 * 15 = 15 points
        ->set('p4.ratings.1', 5)
        ->set('p4.ratings.2', 5)
        ->set('p4.ratings.3', 5)
        ->set('p4.ratings.4', 5)
        ->set('p4.ratings.5', 5) // P4 total = 25/25 -> P4 score = 25/25 * 20 = 20 points
        ->set('p5.ratings.1', 5)
        ->set('p5.ratings.2', 5)
        ->set('p5.ratings.3', 5)
        ->set('p5.ratings.4', 5)
        ->set('p5.ratings.5', 5) // P5 total = 25/25 -> P5 score = 25/25 * 15 = 15 points
        ->set('etika.pernyataan', true)
        ->call('saveObservation')
        ->assertHasNoErrors();

    // Verify it is saved with calculated total score: 5 + 18 + 15 + 20 + 15 = 73
    $observation = RefereeObservation::first();
    expect($observation)->not->toBeNull()
        ->and((float) $observation->total_score)->toBe(73.00)
        ->and($observation->category)->toBe('CUKUP')
        ->and($observation->court)->toBe('Court 2')
        ->and($observation->round)->toBe('Semifinal')
        ->and($observation->match_time)->toBe('11:45');
});

test('admin can access and filter admin referee observations index', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $contingent = Contingent::factory()->create();
    $refUser = User::factory()->create(['name' => 'Jane Referee']);
    $referee = Referee::create([
        'user_id' => $refUser->id,
        'nik' => '9876543210987654',
    ]);

    RefereeObservation::create([
        'contingent_id' => $contingent->id,
        'referee_id' => $referee->id,
        'observer_name' => 'Official A',
        'observation_date' => '2026-06-04',
        'court' => 'Court 1',
        'round' => 'Penyisihan',
        'match_time' => '09:00',
        'total_score' => 85.00,
        'category' => 'BAIK',
    ]);

    Livewire::actingAs($admin)
        ->test(AdminRefereeObservationsIndex::class)
        ->assertStatus(200)
        ->assertSee('Official A')
        ->assertSee('Jane Referee')
        ->assertViewHas('categoryChartData', function ($data) {
            return isset($data['BAIK']) && $data['BAIK'] === 1 && $data['SANGAT BAIK'] === 0;
        })
        ->assertViewHas('courtChartData', function ($data) {
            return isset($data['Court 1']) && (float) $data['Court 1'] === 85.0 && (float) $data['Court 2'] === 0.0;
        })
        ->set('courtFilter', 'Court 2')
        ->assertDontSee('Official A')
        ->assertViewHas('categoryChartData', function ($data) {
            return isset($data['BAIK']) && $data['BAIK'] === 0;
        })
        ->assertViewHas('courtChartData', function ($data) {
            return isset($data['Court 1']) && (float) $data['Court 1'] === 0.0;
        });
});
