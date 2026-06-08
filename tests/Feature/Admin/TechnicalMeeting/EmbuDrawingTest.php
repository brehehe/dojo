<?php

use App\Livewire\Admin\TechnicalMeeting\Embu\AdminTechnicalMeetingEmbuIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->ageGroup = AgeGroup::create([
        'name' => 'Kategori Usia Test',
        'order' => 1,
        'price' => 0,
    ]);
});

function createMockEmbuEntries(MatchNumber $match, int $count)
{
    for ($i = 0; $i < $count; $i++) {
        $contingent = Contingent::factory()->create(['user_id' => 1]); // Assume user_id = 1 for simplicity of factory
        $registration = Registration::create([
            'contingent_id' => $contingent->id,
            'status' => 'approved',
            'payment_status' => 'paid',
        ]);

        // Embu implies 2 athletes per registration typically, but let's just create 1 for drawing verification
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

it('generates 2 babak format for 9 or less entries', function () {
    $match = MatchNumber::create([
        'name' => 'Embu Test Match',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockEmbuEntries($match, 9);

    Livewire::actingAs($this->admin)
        ->test(AdminTechnicalMeetingEmbuIndex::class)
        ->call('generateDrawing', $match->id);

    $match->refresh();

    expect($match->drawing_data)->not->toBeNull()
        ->and($match->drawing_data['format'])->toBe('2_babak')
        ->and($match->drawing_data['total_entries'])->toBe(9)
        ->and($match->drawing_data['pools'])->toHaveKey('POOL 1');

    $this->assertCount(9, $match->drawing_data['pools']['POOL 1']);
});

it('generates 2 pools for 10 to 11 entries', function () {
    $match = MatchNumber::create([
        'name' => 'Embu Test Match',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockEmbuEntries($match, 10);

    Livewire::actingAs($this->admin)
        ->test(AdminTechnicalMeetingEmbuIndex::class)
        ->call('generateDrawing', $match->id);

    $match->refresh();

    expect($match->drawing_data)->not->toBeNull()
        ->and($match->drawing_data['format'])->toBe('pool')
        ->and($match->drawing_data['pool_count'])->toBe(2)
        ->and($match->drawing_data['total_entries'])->toBe(10)
        ->and($match->drawing_data['pools'])->toHaveKeys(['POOL A', 'POOL B']);

    // Round robin distribution: Pool A gets 5, Pool B gets 5
    $this->assertCount(5, $match->drawing_data['pools']['POOL A']);
    $this->assertCount(5, $match->drawing_data['pools']['POOL B']);
});

it('generates 3 pools for 12 to 17 entries', function () {
    $match = MatchNumber::create([
        'name' => 'Embu Test Match',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockEmbuEntries($match, 15);

    Livewire::actingAs($this->admin)
        ->test(AdminTechnicalMeetingEmbuIndex::class)
        ->call('generateDrawing', $match->id);

    $match->refresh();

    expect($match->drawing_data)->not->toBeNull()
        ->and($match->drawing_data['format'])->toBe('pool')
        ->and($match->drawing_data['pool_count'])->toBe(3)
        ->and($match->drawing_data['total_entries'])->toBe(15)
        ->and($match->drawing_data['pools'])->toHaveKeys(['POOL A', 'POOL B', 'POOL C']);

    // Round robin distribution for 15: 5 per pool
    $this->assertCount(5, $match->drawing_data['pools']['POOL A']);
    $this->assertCount(5, $match->drawing_data['pools']['POOL B']);
    $this->assertCount(5, $match->drawing_data['pools']['POOL C']);
});

it('generates 4 pools for 18 or more entries', function () {
    $match = MatchNumber::create([
        'name' => 'Embu Test Match',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockEmbuEntries($match, 20);

    Livewire::actingAs($this->admin)
        ->test(AdminTechnicalMeetingEmbuIndex::class)
        ->call('generateDrawing', $match->id);

    $match->refresh();

    expect($match->drawing_data)->not->toBeNull()
        ->and($match->drawing_data['format'])->toBe('pool')
        ->and($match->drawing_data['pool_count'])->toBe(4)
        ->and($match->drawing_data['total_entries'])->toBe(20)
        ->and($match->drawing_data['pools'])->toHaveKeys(['POOL A', 'POOL B', 'POOL C', 'POOL D']);

    // Round robin distribution for 20: 5 per pool
    $this->assertCount(5, $match->drawing_data['pools']['POOL A']);
    $this->assertCount(5, $match->drawing_data['pools']['POOL B']);
    $this->assertCount(5, $match->drawing_data['pools']['POOL C']);
    $this->assertCount(5, $match->drawing_data['pools']['POOL D']);
});

it('resets drawing', function () {
    $match = MatchNumber::create([
        'name' => 'Embu Test Match',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockEmbuEntries($match, 5);

    $component = Livewire::actingAs($this->admin)
        ->test(AdminTechnicalMeetingEmbuIndex::class)
        ->call('generateDrawing', $match->id);

    $match->refresh();
    expect($match->drawing_data)->not->toBeNull();

    $component->call('resetDrawing', $match->id);

    $match->refresh();
    expect($match->drawing_data)->toBeNull()
        ->and($match->drawing_generated_at)->toBeNull();
});
