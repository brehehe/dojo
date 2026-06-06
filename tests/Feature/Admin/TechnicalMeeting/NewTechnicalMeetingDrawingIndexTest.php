<?php

use App\Livewire\Admin\NewTechnicalMeetingDrawingIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->ageGroup = AgeGroup::create([
        'name' => 'Kategori Usia Test',
        'order' => 1,
        'price' => 0,
    ]);
    Role::findOrCreate('Koordinator Lapangan', 'web');
    Role::findOrCreate('Panitera', 'web');
});

function createMockRandoriEntries(MatchNumber $match, int $count)
{
    for ($i = 0; $i < $count; $i++) {
        $contingent = Contingent::factory()->create(['user_id' => 1]); // Assume user_id = 1
        $registration = Registration::create([
            'contingent_id' => $contingent->id,
            'status' => 'approved',
            'payment_status' => 'paid',
        ]);

        $athlete = Athlete::factory()->create();

        DB::table('registration_athlete')->insert([
            'registration_id' => $registration->id,
            'athlete_id' => $athlete->id,
            'kyu' => 'Kyu 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('athlete_match_number')->insert([
            'athlete_id' => $athlete->id,
            'match_number_id' => $match->id,
            'registration_id' => $registration->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

it('shows warning when participants are less than 3 in randori', function () {
    $match = MatchNumber::create([
        'name' => 'Randori Test Match',
        'draft_type' => 'randori',
        'gender' => 'L',
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);

    createMockRandoriEntries($match, 2);

    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('filterMatchNumberId', $match->id)
        ->call('generateRandoriDrawing')
        ->assertDispatched('swal');
});

it('can quickly register a new koordinator lapangan user', function () {
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('newKoorName', 'New Koor')
        ->set('newKoorEmail', 'newkoor@example.com')
        ->call('addKoorUser')
        ->assertSet('editKoorName', 'New Koor')
        ->assertSet('newKoorName', '')
        ->assertSet('newKoorEmail', '')
        ->assertSet('showAddKoorForm', false)
        ->assertDispatched('swal');

    $this->assertDatabaseHas('users', [
        'name' => 'New Koor',
        'email' => 'newkoor@example.com',
    ]);

    $user = User::where('email', 'newkoor@example.com')->first();
    expect($user->hasRole('Koordinator Lapangan'))->toBeTrue();
});

it('can quickly register a new panitera user', function () {
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('newPaniteraName', 'New Panitera')
        ->set('newPaniteraEmail', 'newpanitera@example.com')
        ->call('addPaniteraUser')
        ->assertSet('newPaniteraName', '')
        ->assertSet('newPaniteraEmail', '')
        ->assertSet('showAddPaniteraForm', false)
        ->assertDispatched('swal');

    $this->assertDatabaseHas('users', [
        'name' => 'New Panitera',
        'email' => 'newpanitera@example.com',
    ]);

    $user = User::where('email', 'newpanitera@example.com')->first();
    expect($user->hasRole('Panitera'))->toBeTrue();
});
