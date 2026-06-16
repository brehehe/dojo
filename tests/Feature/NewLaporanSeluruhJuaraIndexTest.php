<?php

use App\Livewire\Admin\NewLaporanSeluruhJuaraIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\TournamentResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();

    $this->ageGroup = AgeGroup::create([
        'name' => 'Pemula',
        'order' => 1,
    ]);

    // Create a match with 3 participants from 2 contingents (Should be Yellow)
    $this->matchYellow = MatchNumber::create([
        'name' => 'Embu Yellow Match',
        'gender' => 'Putra',
        'draft_type' => 'embu',
        'age_group_id' => $this->ageGroup->id,
        'order' => 1,
    ]);

    $contingentA = Contingent::factory()->create(['name' => 'Contingent A']);
    $contingentB = Contingent::factory()->create(['name' => 'Contingent B']);

    $regA = Registration::create(['contingent_id' => $contingentA->id]);
    $regB = Registration::create(['contingent_id' => $contingentB->id]);
    $regC = Registration::create(['contingent_id' => $contingentB->id]);

    $athlete1 = Athlete::factory()->create(['name' => 'Kenshi 1']);
    $athlete1->contingents()->attach($contingentA->id, ['is_primary' => true]);
    $athlete1->matchNumbers()->attach($this->matchYellow->id, ['registration_id' => $regA->id]);

    $athlete2 = Athlete::factory()->create(['name' => 'Kenshi 2']);
    $athlete2->contingents()->attach($contingentB->id, ['is_primary' => true]);
    $athlete2->matchNumbers()->attach($this->matchYellow->id, ['registration_id' => $regB->id]);

    $athlete3 = Athlete::factory()->create(['name' => 'Kenshi 3']);
    $athlete3->contingents()->attach($contingentB->id, ['is_primary' => true]);
    $athlete3->matchNumbers()->attach($this->matchYellow->id, ['registration_id' => $regC->id]);

    // Create a match with 3 participants from 1 contingent (Should be Green)
    $this->matchGreen = MatchNumber::create([
        'name' => 'Randori Green Match',
        'gender' => 'Putri',
        'draft_type' => 'randori',
        'age_group_id' => $this->ageGroup->id,
        'order' => 2,
    ]);

    $athlete4 = Athlete::factory()->create(['name' => 'Kenshi 4']);
    $athlete4->contingents()->attach($contingentA->id, ['is_primary' => true]);
    $athlete4->matchNumbers()->attach($this->matchGreen->id, ['registration_id' => $regA->id]);

    $athlete5 = Athlete::factory()->create(['name' => 'Kenshi 5']);
    $athlete5->contingents()->attach($contingentA->id, ['is_primary' => true]);
    $athlete5->matchNumbers()->attach($this->matchGreen->id, ['registration_id' => $regA->id]);

    $athlete6 = Athlete::factory()->create(['name' => 'Kenshi 6']);
    $athlete6->contingents()->attach($contingentA->id, ['is_primary' => true]);
    $athlete6->matchNumbers()->attach($this->matchGreen->id, ['registration_id' => $regA->id]);

    // Save winners for yellow match
    TournamentResult::create([
        'match_number_id' => $this->matchYellow->id,
        'draft_type' => 'embu',
        'rank' => 1,
        'registration_id' => $regA->id,
        'athlete_names' => 'Kenshi 1 & Kenshi 2',
        'contingent_name' => 'Contingent A',
    ]);
});

test('laporan seluruh juara page requires authentication', function () {
    $response = $this->get('/admin/arbitrase/new-laporan-seluruh-juara');
    $response->assertRedirect('/login');
});

test('laporan seluruh juara page renders for authenticated user', function () {
    $response = $this->actingAs($this->admin)->get('/admin/arbitrase/new-laporan-seluruh-juara');
    $response->assertSuccessful();
});

test('index component handles search, draft type, and color highlight filters', function () {
    Livewire::actingAs($this->admin)
        ->test(NewLaporanSeluruhJuaraIndex::class)
        ->assertSee('Embu Yellow Match')
        ->assertSee('Randori Green Match')
        // Test search filter
        ->set('search', 'Yellow')
        ->assertSee('Embu Yellow Match')
        ->assertDontSee('Randori Green Match')
        ->set('search', '')
        // Test draft type filter
        ->set('draftTypeFilter', 'randori')
        ->assertDontSee('Embu Yellow Match')
        ->assertSee('Randori Green Match')
        ->set('draftTypeFilter', '')
        // Test highlight filter - Yellow rule
        ->set('highlightFilter', 'yellow')
        ->assertSee('Embu Yellow Match')
        ->assertDontSee('Randori Green Match')
        // Test highlight filter - Green rule
        ->set('highlightFilter', 'green')
        ->assertDontSee('Embu Yellow Match')
        ->assertSee('Randori Green Match');
});

test('index component highlights rule colors properly', function () {
    $component = Livewire::actingAs($this->admin)->test(NewLaporanSeluruhJuaraIndex::class);
    $data = $component->instance()->getMatchData();

    $yellowMatchData = collect($data)->firstWhere('name', 'Embu Yellow Match');
    $greenMatchData = collect($data)->firstWhere('name', 'Randori Green Match');

    expect($yellowMatchData['color'])->toBe('yellow');
    expect($greenMatchData['color'])->toBe('green');
});

test('excel download returns binary file download response', function () {
    $component = Livewire::actingAs($this->admin)->test(NewLaporanSeluruhJuaraIndex::class);
    $response = $component->call('downloadExcel');

    $response->assertStatus(200);
});

test('index component groups merged match numbers into a single row', function () {
    // Create merge parent
    $mergeGroup = DB::table('match_number_merges')->insertGetId([
        'name' => 'Randori Putri Gabungan Kelas 60 & 65',
        'age_group_id' => $this->ageGroup->id,
        'type' => 'randori',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Create 2 match numbers
    $mnA = MatchNumber::create([
        'name' => 'Randori 60kg',
        'gender' => 'Female',
        'draft_type' => 'randori',
        'age_group_id' => $this->ageGroup->id,
        'order' => 3,
    ]);

    $mnB = MatchNumber::create([
        'name' => 'Randori 65kg',
        'gender' => 'Female',
        'draft_type' => 'randori',
        'age_group_id' => $this->ageGroup->id,
        'order' => 4,
    ]);

    // Link to merge
    DB::table('match_number_merge_details')->insert([
        ['match_number_merge_id' => $mergeGroup, 'match_number_id' => $mnA->id, 'created_at' => now(), 'updated_at' => now()],
        ['match_number_merge_id' => $mergeGroup, 'match_number_id' => $mnB->id, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // Attach participants
    $contingentC = Contingent::factory()->create(['name' => 'Contingent C']);
    $regC = Registration::create(['contingent_id' => $contingentC->id]);

    $athlete7 = Athlete::factory()->create(['name' => 'Kenshi 7', 'gender' => 'Female']);
    $athlete7->contingents()->attach($contingentC->id, ['is_primary' => true]);
    $athlete7->matchNumbers()->attach($mnA->id, ['registration_id' => $regC->id]);

    $athlete8 = Athlete::factory()->create(['name' => 'Kenshi 8', 'gender' => 'Female']);
    $athlete8->contingents()->attach($contingentC->id, ['is_primary' => true]);
    $athlete8->matchNumbers()->attach($mnB->id, ['registration_id' => $regC->id]);

    // Fetch data and assert merge grouping
    $component = Livewire::actingAs($this->admin)->test(NewLaporanSeluruhJuaraIndex::class);
    $data = $component->instance()->getMatchData();

    $mergedRow = collect($data)->first(fn ($row) => str_contains($row['name'], 'Randori Putri Gabungan Kelas 60 & 65'));

    expect($mergedRow)->not->toBeNull();
    // Combined participants from mnA and mnB should be 2
    expect($mergedRow['participant_count'])->toBe(2);
    expect($mergedRow['name'])->toContain('Randori 60kg');
    expect($mergedRow['name'])->toContain('Randori 65kg');
});
