<?php

use App\Livewire\Admin\NewTechnicalMeetingDrawingIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
            'status' => 'verified',
            'payment_status' => 'paid',
            'athlete_status' => 'verified',
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

it('generates Embu drawing with parallel pool scheduling and 15 min break for final', function () {
    // 1. Setup Court, Rundown, SessionTime
    $court1 = Court::create(['name' => 'Court 1', 'order' => 1]);
    $court2 = Court::create(['name' => 'Court 2', 'order' => 2]);
    $court3 = Court::create(['name' => 'Court 3', 'order' => 3]);

    $rundown = Rundown::create([
        'name' => 'Hari 1',
        'date' => '2026-06-08',
        'type' => 'pertandingan',
        'order' => 1,
    ]);

    $session = SessionTime::create([
        'name' => 'Sesi Pagi',
        'start_time' => '07:30:00',
        'end_time' => '12:00:00',
    ]);

    // Create match number (Embu)
    $match = MatchNumber::create([
        'name' => 'Embu Pasangan Kyu Kenshi',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);

    // Create 12 entries
    createMockRandoriEntries($match, 12);

    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('filterMatchNumberId', $match->id)
        ->call('generateEmbuDrawing')
        ->assertDispatched('swal');

    // Verify drawing data
    $match->refresh();
    expect($match->drawing_data)->not->toBeNull();
    expect($match->drawing_data['pool_count'])->toBe(3);

    // Verify drawing match numbers scheduled in parallel
    $drawings = DrawingMatchNumber::where('match_number_id', $match->id)->get();

    // There should be 12 preliminary matches + 9 finalists (3 pools * 3 qualifiers) = 21 matches
    expect($drawings->count())->toBe(21);

    $prelims = $drawings->where('round', 'Penyisihan');
    expect($prelims->count())->toBe(12);

    // Verify court distribution of pools (POOL A, POOL B, POOL C)
    $poolCourts = $prelims->groupBy('metadata.pool_label')->map(function ($items) {
        return $items->pluck('court_id')->unique()->values()->all();
    });

    // Each pool should be on its own court, demonstrating parallel scheduling!
    expect($poolCourts['POOL A'])->not->toBe($poolCourts['POOL B']);
    expect($poolCourts['POOL A'])->not->toBe($poolCourts['POOL C']);

    // Check start times for Penyisihan. Because they run in parallel, all pools should start at 07:30.
    $poolStartTimes = $prelims->groupBy('metadata.pool_label')->map(function ($items) {
        return $items->first()->metadata['start_time'];
    });

    expect($poolStartTimes['POOL A'])->toBe('07:30');
    expect($poolStartTimes['POOL B'])->toBe('07:30');
    expect($poolStartTimes['POOL C'])->toBe('07:30');

    // Each pool has 4 entries (40 mins). Start times for each pool: 07:30, 07:40, 07:50, 08:00.
    // Latest end time is 08:10.
    // The Final should start 15 minutes after 08:10, which is 08:25. Due to 10-minute grid alignment, the next slot is 08:30!
    $finals = $drawings->where('round', 'Final');
    expect($finals->count())->toBe(9);

    $firstFinalStart = $finals->first()->metadata['start_time'];
    expect($firstFinalStart)->toBe('08:30');
});

it('generates all drawings scheduling preliminaries first then finals across categories', function () {
    // 1. Setup Court, Rundown, SessionTime
    $court1 = Court::create(['name' => 'Court 1', 'order' => 1]);
    $court2 = Court::create(['name' => 'Court 2', 'order' => 2]);
    $court3 = Court::create(['name' => 'Court 3', 'order' => 3]);

    $rundown = Rundown::create([
        'name' => 'Hari 1',
        'date' => '2026-06-08',
        'type' => 'pertandingan',
        'order' => 1,
    ]);

    $session = SessionTime::create([
        'name' => 'Sesi Pagi',
        'start_time' => '07:30:00',
        'end_time' => '12:00:00',
    ]);

    // Create 1 Embu match number and 1 Randori match number
    $embuMatch = MatchNumber::create([
        'name' => 'Embu Pasangan Kyu Kenshi',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockRandoriEntries($embuMatch, 10); // 10 entries -> 2 pools (POOL A: 5, POOL B: 5). Prelims duration: 5 * 10 = 50 min. Ends at 07:30 + 50min = 08:20.

    $randoriMatch = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'gender' => 'L',
        'order' => 2,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockRandoriEntries($randoriMatch, 4); // 4 entries -> double elimination. Prelims duration: 5 matches of 10 min = 50 min. Ends at 07:30 + 50min = 08:20.

    // Generate all
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->call('generateAllDrawings')
        ->assertDispatched('swal');

    // Fetch all scheduled drawings
    $drawings = DrawingMatchNumber::all();

    // Check all preliminaries end times
    $prelims = $drawings->filter(fn ($d) => str_starts_with($d->round, 'Penyisihan'));
    $finals = $drawings->filter(fn ($d) => $d->round === 'Final' || $d->round === 'Grand Final');

    expect($prelims->isNotEmpty())->toBeTrue();
    expect($finals->isNotEmpty())->toBeTrue();

    $embuPrelims = $prelims->where('draft_type', 'embu');
    $randoriPrelims = $prelims->where('draft_type', 'randori');
    expect($embuPrelims->isNotEmpty())->toBeTrue();
    expect($randoriPrelims->isNotEmpty())->toBeTrue();

    // Verify Embu prelims start at 07:30
    expect($embuPrelims->pluck('metadata.start_time')->contains('07:30'))->toBeTrue();

    // Find the latest prelim end time
    $latestPrelimEnd = null;
    foreach ($prelims as $p) {
        $pDate = Carbon::parse($p->schedule_date)->format('Y-m-d');
        $end = Carbon::parse($pDate.' '.$p->metadata['end_time']);
        if ($latestPrelimEnd === null || $end->gt($latestPrelimEnd)) {
            $latestPrelimEnd = $end;
        }
    }

    // Every final match should start at or after latest prelim end + 15 min aligned to 10 min boundary
    $expectedMinFinalStart = $latestPrelimEnd->copy()->addMinutes(15);
    $minute = (int) $expectedMinFinalStart->format('i');
    $remainder = $minute % 10;
    if ($remainder !== 0) {
        $expectedMinFinalStart->addMinutes(10 - $remainder);
    }

    foreach ($finals as $f) {
        $fDate = Carbon::parse($f->schedule_date)->format('Y-m-d');
        $finalStart = Carbon::parse($fDate.' '.$f->metadata['start_time']);
        expect($finalStart->gte($expectedMinFinalStart))->toBeTrue();
    }
});

it('prioritizes Embu on the first day and puts Randori on Day 1 only if Embu is fully scheduled', function () {
    // 1. Setup Court, 2 Rundowns (Day 1 & Day 2), SessionTime
    $court = Court::create(['name' => 'Court 1', 'order' => 1]);

    $day1 = Rundown::create([
        'name' => 'Hari 1',
        'date' => '2026-06-15',
        'type' => 'pertandingan',
        'order' => 1,
    ]);

    $day2 = Rundown::create([
        'name' => 'Hari 2',
        'date' => '2026-06-16',
        'type' => 'pertandingan',
        'order' => 2,
    ]);

    $session = SessionTime::create([
        'name' => 'Sesi Pagi',
        'start_time' => '07:30:00',
        'end_time' => '12:00:00',
    ]);

    // Create 1 Embu match and 1 Randori match
    $embuMatch = MatchNumber::create([
        'name' => 'Embu Test Match',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockRandoriEntries($embuMatch, 4);

    $randoriMatch = MatchNumber::create([
        'name' => 'Randori Test Match',
        'draft_type' => 'randori',
        'gender' => 'L',
        'order' => 2,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockRandoriEntries($randoriMatch, 4);

    // Scenario A: Embu is NOT scheduled yet. Generate Randori drawing.
    // It should be scheduled on Day 2, not Day 1, because Embu is unscheduled.
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('filterMatchNumberId', $randoriMatch->id)
        ->call('generateRandoriDrawing')
        ->assertDispatched('swal');

    $randoriDrawings = DrawingMatchNumber::where('match_number_id', $randoriMatch->id)->get();
    expect($randoriDrawings->isNotEmpty())->toBeTrue();
    foreach ($randoriDrawings as $d) {
        expect($d->rundown_id)->toBe($day2->id); // Must be Day 2!
    }

    // Reset all drawings to test Scenario B
    DrawingMatchNumber::truncate();
    $randoriMatch->update(['drawing_data' => null, 'drawing_generated_at' => null]);
    $embuMatch->update(['drawing_data' => null, 'drawing_generated_at' => null]);

    // Scenario B: First schedule the Embu match. It should go to Day 1.
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('filterMatchNumberId', $embuMatch->id)
        ->call('generateEmbuDrawing')
        ->assertDispatched('swal');

    $embuDrawings = DrawingMatchNumber::where('match_number_id', $embuMatch->id)->get();
    expect($embuDrawings->isNotEmpty())->toBeTrue();
    // Embu prelims should be on Day 1
    $embuPrelims = $embuDrawings->where('round', 'Penyisihan');
    foreach ($embuPrelims as $d) {
        expect($d->rundown_id)->toBe($day1->id);
    }

    // Now all Embu matches are scheduled. Generate Randori drawing.
    // Since Embu is fully scheduled, Randori should utilize the leftover slots on Day 1 (if there is space).
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('filterMatchNumberId', $randoriMatch->id)
        ->call('generateRandoriDrawing')
        ->assertDispatched('swal');

    $newRandoriDrawings = DrawingMatchNumber::where('match_number_id', $randoriMatch->id)->get();
    expect($newRandoriDrawings->isNotEmpty())->toBeTrue();

    // Check if the first prelim match of Randori is on Day 1 (since Embu left free slots after ~40 mins)
    $randoriPrelims = $newRandoriDrawings->filter(fn ($d) => str_starts_with($d->round, 'Penyisihan'));
    expect($randoriPrelims->first()->rundown_id)->toBe($day1->id);
});

it('schedules Embu finals on the same day as its preliminaries, while Randori finals are pushed to Day 2', function () {
    // 1. Setup Court, 2 Rundowns (Day 1 & Day 2), SessionTime
    $court = Court::create(['name' => 'Court 1', 'order' => 1]);

    $day1 = Rundown::create([
        'name' => 'Hari 1',
        'date' => '2026-06-15',
        'type' => 'pertandingan',
        'order' => 1,
    ]);

    $day2 = Rundown::create([
        'name' => 'Hari 2',
        'date' => '2026-06-16',
        'type' => 'pertandingan',
        'order' => 2,
    ]);

    $session = SessionTime::create([
        'name' => 'Sesi Pagi',
        'start_time' => '07:30:00',
        'end_time' => '12:00:00',
    ]);

    // Create 1 Embu match and 1 Randori match
    $embuMatch = MatchNumber::create([
        'name' => 'Embu Test Match',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockRandoriEntries($embuMatch, 4);

    $randoriMatch = MatchNumber::create([
        'name' => 'Randori Test Match',
        'draft_type' => 'randori',
        'gender' => 'L',
        'order' => 2,
        'age_group_id' => $this->ageGroup->id,
    ]);
    createMockRandoriEntries($randoriMatch, 4);

    // First generate Embu drawing. Since it has Embu type, both its prelims and finals should be scheduled on Day 1.
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('filterMatchNumberId', $embuMatch->id)
        ->call('generateEmbuDrawing')
        ->assertDispatched('swal');

    $embuDrawings = DrawingMatchNumber::where('match_number_id', $embuMatch->id)->get();
    expect($embuDrawings->isNotEmpty())->toBeTrue();

    // Prelims should be on Day 1
    $embuPrelims = $embuDrawings->where('round', 'Penyisihan');
    foreach ($embuPrelims as $d) {
        expect($d->rundown_id)->toBe($day1->id);
    }

    // Finals should ALSO be on Day 1
    $embuFinals = $embuDrawings->where('round', 'Final');
    expect($embuFinals->isNotEmpty())->toBeTrue();
    foreach ($embuFinals as $d) {
        expect($d->rundown_id)->toBe($day1->id);
    }

    // Now generate Randori drawing.
    // Randori prelims should go to Day 1 (if there is space left), but Randori finals must be pushed to Day 2!
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->set('filterMatchNumberId', $randoriMatch->id)
        ->call('generateRandoriDrawing')
        ->assertDispatched('swal');

    $randoriDrawings = DrawingMatchNumber::where('match_number_id', $randoriMatch->id)->get();
    expect($randoriDrawings->isNotEmpty())->toBeTrue();

    $randoriPrelims = $randoriDrawings->filter(fn ($d) => str_starts_with($d->round, 'Penyisihan'));
    foreach ($randoriPrelims as $d) {
        expect($d->rundown_id)->toBe($day1->id);
    }

    $randoriFinals = $randoriDrawings->filter(fn ($d) => $d->round === 'Final' || $d->round === 'Grand Final');
    expect($randoriFinals->isNotEmpty())->toBeTrue();
    foreach ($randoriFinals as $d) {
        expect($d->rundown_id)->toBe($day2->id); // Pushed to Day 2!
    }
});

it('generates all drawings following the priority sequence and packs them compactly without empty slots', function () {
    // 1. Setup Court, 2 Rundowns (Day 1 & Day 2), SessionTime
    $court = Court::create(['name' => 'Court 1', 'order' => 1]);

    $day1 = Rundown::create([
        'name' => 'Hari 1',
        'date' => '2026-06-15',
        'type' => 'pertandingan',
        'order' => 1,
    ]);

    $day2 = Rundown::create([
        'name' => 'Hari 2',
        'date' => '2026-06-16',
        'type' => 'pertandingan',
        'order' => 2,
    ]);

    $session = SessionTime::create([
        'name' => 'Sesi Pagi',
        'start_time' => '07:30:00',
        'end_time' => '12:00:00', // 270 mins total capacity on Day 1
    ]);

    // Create age groups: Pemula, Remaja A, Dewasa
    $pemulaGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1, 'price' => 0]);
    $remajaAGroup = AgeGroup::create(['name' => 'Remaja A', 'order' => 2, 'price' => 0]);
    $dewasaGroup = AgeGroup::create(['name' => 'Dewasa', 'order' => 3, 'price' => 0]);

    // 1. Embu Pemula Pasangan (should be priority 2 inside Embu Pemula)
    $embuPemulaPasangan = MatchNumber::create([
        'name' => 'Embu Pasangan Pemula',
        'draft_type' => 'embu',
        'max_athletes' => 2,
        'order' => 1,
        'age_group_id' => $pemulaGroup->id,
    ]);
    createMockRandoriEntries($embuPemulaPasangan, 4); // 4 entries -> 1 pool = 40 mins. Final: 4 finalists = 40 mins. Total 80 mins.

    // 2. Embu Pemula Tandoku (should be priority 1 inside Embu Pemula)
    $embuPemulaTandoku = MatchNumber::create([
        'name' => 'Embu Tandoku Pemula',
        'draft_type' => 'embu',
        'max_athletes' => 1,
        'order' => 2,
        'age_group_id' => $pemulaGroup->id,
    ]);
    createMockRandoriEntries($embuPemulaTandoku, 4); // 4 entries -> 1 pool = 40 mins. Final: 4 finalists = 40 mins. Total 80 mins.

    // 3. Embu Remaja A Tandoku (should be after Pemula)
    $embuRemajaATandoku = MatchNumber::create([
        'name' => 'Embu Tandoku Remaja A',
        'draft_type' => 'embu',
        'max_athletes' => 1,
        'order' => 3,
        'age_group_id' => $remajaAGroup->id,
    ]);
    createMockRandoriEntries($embuRemajaATandoku, 3); // 3 entries -> 1 pool = 30 mins. Final: 3 finalists = 30 mins. Total 60 mins.

    // 4. Randori Dewasa
    $randoriDewasa = MatchNumber::create([
        'name' => 'Randori Dewasa',
        'draft_type' => 'randori',
        'gender' => 'L',
        'order' => 4,
        'age_group_id' => $dewasaGroup->id,
    ]);
    createMockRandoriEntries($randoriDewasa, 4); // 4 entries -> double elimination. Prelims: 5 matches = 50 mins. Finals: 1 match = 10 mins. Total 60 mins.

    // Run generateAllDrawings
    Livewire::actingAs($this->admin)
        ->test(NewTechnicalMeetingDrawingIndex::class)
        ->call('generateAllDrawings')
        ->assertDispatched('swal');

    $embuPemulaTandokuPrelim = DrawingMatchNumber::where('match_number_id', $embuPemulaTandoku->id)->where('round', 'Penyisihan')->first();
    expect($embuPemulaTandokuPrelim->metadata['start_time'])->toBe('07:30');
    expect($embuPemulaTandokuPrelim->rundown_id)->toBe($day1->id);

    $embuPemulaPasanganPrelim = DrawingMatchNumber::where('match_number_id', $embuPemulaPasangan->id)->where('round', 'Penyisihan')->first();
    expect($embuPemulaPasanganPrelim->metadata['start_time'])->toBe('08:10');
    expect($embuPemulaPasanganPrelim->rundown_id)->toBe($day1->id);

    $embuRemajaATandokuPrelim = DrawingMatchNumber::where('match_number_id', $embuRemajaATandoku->id)->where('round', 'Penyisihan')->first();
    expect($embuRemajaATandokuPrelim->metadata['start_time'])->toBe('08:50');
    expect($embuRemajaATandokuPrelim->rundown_id)->toBe($day1->id);

    $embuPemulaTandokuFinal = DrawingMatchNumber::where('match_number_id', $embuPemulaTandoku->id)->where('round', 'Final')->first();
    expect($embuPemulaTandokuFinal->metadata['start_time'])->toBe('09:40');
    expect($embuPemulaTandokuFinal->rundown_id)->toBe($day1->id);

    $embuPemulaPasanganFinal = DrawingMatchNumber::where('match_number_id', $embuPemulaPasangan->id)->where('round', 'Final')->first();
    expect($embuPemulaPasanganFinal->metadata['start_time'])->toBe('10:20');
    expect($embuPemulaPasanganFinal->rundown_id)->toBe($day1->id);

    $embuRemajaATandokuFinal = DrawingMatchNumber::where('match_number_id', $embuRemajaATandoku->id)->where('round', 'Final')->first();
    expect($embuRemajaATandokuFinal->metadata['start_time'])->toBe('11:00');
    expect($embuRemajaATandokuFinal->rundown_id)->toBe($day1->id);

    $randoriDewasaPrelim = DrawingMatchNumber::where('match_number_id', $randoriDewasa->id)->where('round', 'like', 'Penyisihan%')->first();
    expect($randoriDewasaPrelim->rundown_id)->toBe($day2->id); // Pushed to Day 2!
});
