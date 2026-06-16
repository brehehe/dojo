<?php

use App\Livewire\Admin\NewLaporanRekapPenilaianDetail;
use App\Livewire\Admin\NewLaporanRekapPenilaianIndex;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->ageGroup = AgeGroup::create(['name' => 'Pemula', 'order' => 1]);

    // Create Embu Match Number
    $this->embuMatch = MatchNumber::create([
        'name' => 'Embu Pasangan',
        'gender' => 'Putra',
        'draft_type' => 'embu',
        'age_group_id' => $this->ageGroup->id,
    ]);

    // Create Randori Match Number
    $this->randoriMatch = MatchNumber::create([
        'name' => 'Randori Putra 50kg',
        'gender' => 'Putra',
        'draft_type' => 'randori',
        'age_group_id' => $this->ageGroup->id,
    ]);

    $this->contingent = Contingent::factory()->create(['name' => 'Surabaya A']);

    $this->registration = Registration::create(['contingent_id' => $this->contingent->id]);

    $this->athlete = Athlete::factory()->create([
        'name' => 'Kenshi A',
        'gender' => 'Male',
    ]);

    // Relate athlete to contingent using belongsToMany pivot
    $this->athlete->contingents()->attach($this->contingent->id, ['is_primary' => true]);

    // Relate athlete to match numbers
    $this->athlete->matchNumbers()->attach($this->embuMatch->id, ['registration_id' => $this->registration->id]);
    $this->athlete->matchNumbers()->attach($this->randoriMatch->id, ['registration_id' => $this->registration->id]);

    // Create drawings to satisfy whereHas('drawings') on index query
    $this->embuDrawing = DrawingMatchNumber::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'embu',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);

    $this->randoriDrawing = DrawingMatchNumber::create([
        'match_number_id' => $this->randoriMatch->id,
        'registration_id' => $this->registration->id,
        'draft_type' => 'randori',
        'round' => 'Penyisihan',
        'sequence_number' => 1,
    ]);
});

test('index component renders list of scheduled match numbers and handles filtering', function () {
    Livewire::test(NewLaporanRekapPenilaianIndex::class)
        ->assertSee('Embu Pasangan')
        ->assertSee('Randori Putra 50kg')
        ->set('filterType', 'embu')
        ->assertSee('Embu Pasangan')
        ->assertDontSee('Randori Putra 50kg')
        ->set('filterType', '')
        ->set('search', 'Pasangan')
        ->assertSee('Embu Pasangan')
        ->assertDontSee('Randori Putra 50kg');
});

test('detail component renders Embu in different print modes', function () {
    // Save some scores for Embu
    EmbuScore::create([
        'match_number_id' => $this->embuMatch->id,
        'registration_id' => $this->registration->id,
        'drawing_id' => $this->embuDrawing->id,
        'judge_1' => 8.5,
        'judge_2' => 8.4,
        'judge_3' => 8.6,
        'judge_4' => 8.5,
        'judge_5' => 8.5,
        'total_score' => 25.5,
        'nilai_akhir' => 25.5,
        'round_label' => 'Penyisihan',
    ]);

    // Test default rekap mode
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->embuMatch])
        ->assertSee('Berita Acara Pertandingan')
        ->assertSee('Embu Pasangan')
        ->assertSee('J1')
        ->assertSee('8.50')
        ->assertSee('Penyisihan');

    // Test juri print mode
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->embuMatch])
        ->set('printMode', 'juri')
        ->set('selectedJuri', '2')
        ->assertSee('Lembar Penilaian Juri 2')
        ->assertSee('Nilai Juri 2')
        ->assertSee('8.40');

    // Test atlet print mode
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->embuMatch])
        ->set('printMode', 'atlet')
        ->set('selectedAthleteReg', 'all')
        ->assertSee('Lembar Hasil Penilaian Atlet')
        ->assertSee('Kenshi A')
        ->assertSee('Surabaya A')
        ->assertSee('25.50');

    // Create a referee to satisfy database foreign keys
    $user = User::factory()->create(['name' => 'Wasit Agung']);
    $referee = Referee::create([
        'user_id' => $user->id,
        'certification_level' => 'Nasional',
        'city' => 'Jakarta',
    ]);

    // Create a RefereeScoreDetail with notes
    RefereeScoreDetail::create([
        'match_number_id' => $this->embuMatch->id,
        'referee_id' => $referee->id,
        'judge_index' => 1,
        'scorable_type' => DrawingMatchNumber::class,
        'scorable_id' => $this->embuDrawing->id,
        'total_calculated_score' => 8.5,
        'notes' => 'Gerakan goho sangat mantap',
        'details' => [
            'goho_1' => 8.5,
            'goho_2' => 8.4,
            'goho_3' => 8.6,
            'juho_1' => 8.5,
            'juho_2' => 8.5,
            'juho_3' => 8.5,
            'ekspresi_1' => 8.5,
            'ekspresi_2' => 8.5,
            'ekspresi_3' => 8.5,
            'ekspresi_4' => 8.5,
        ],
    ]);

    // Test catatan print mode - all juris (combined)
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->embuMatch])
        ->set('printMode', 'catatan')
        ->set('selectedJuri', 'all')
        ->assertSee('Rekap Catatan')
        ->assertSee('Nilai Juri')
        ->assertSee('Kenshi A')
        ->assertSee('Surabaya A')
        ->assertSee('Gerakan goho sangat mantap');

    // Test catatan print mode - specific juri (e.g. Juri 1)
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->embuMatch])
        ->set('printMode', 'catatan')
        ->set('selectedJuri', '1')
        ->assertSee('Laporan Catatan')
        ->assertSee('Nilai Juri 1')
        ->assertSee('Kenshi A')
        ->assertSee('Surabaya A')
        ->assertSee('Gerakan goho sangat mantap');

    // Test catatan print mode - each juri separate
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->embuMatch])
        ->set('printMode', 'catatan')
        ->set('selectedJuri', 'each')
        ->assertSee('Laporan Catatan')
        ->assertSee('Nilai Juri 1')
        ->assertSee('Nilai Juri 2')
        ->assertSee('Nilai Juri 3')
        ->assertSee('Nilai Juri 4')
        ->assertSee('Nilai Juri 5');

    // Test scorecard print mode - specific juri (e.g. Juri 1)
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->embuMatch])
        ->set('printMode', 'scorecard')
        ->set('selectedJuri', '1')
        ->set('selectedAthleteReg', 'all')
        ->assertSee('Lembar Penilaian Embu Detail')
        ->assertSee('Penguasaan Teknik (60)')
        ->assertSee('Ekspresi (40)')
        ->assertSee('Sub Total 1 (Teknik)')
        ->assertSee('Sub Total 2 (Ekspresi)')
        ->assertSee('TOTAL SKOR')
        ->assertSee('8.5')
        ->assertSee('8.4')
        ->assertSee('8.6')
        ->assertSee('Gerakan goho sangat mantap');
});

test('detail component renders Randori in different print modes', function () {
    // Save a match result for Randori
    RandoriMatchResult::create([
        'match_number_id' => $this->randoriMatch->id,
        'bracket_node' => 'ub_0_0',
        'bracket_node_index' => '0_0',
        'bracket_section' => 'ub',
        'winner_color' => 'aka',
        'score_red' => 2,
        'score_blue' => 1,
        'metadata' => json_encode([
            'aka_name' => 'Kenshi A',
            'shiro_name' => 'Kenshi B',
            'red_contingent' => 'Surabaya A',
            'blue_contingent' => 'Malang B',
            'scoringAka' => ['ippon' => 0, 'waza_ari' => 2],
            'scoringShiro' => ['ippon' => 0, 'waza_ari' => 1],
            'signatures' => [
                'arbitrase' => ['name' => 'Pak Arbitrase', 'signature' => 'data:image/png;base64,123'],
                'koordinator' => ['name' => 'Pak Koor', 'signature' => 'data:image/png;base64,456'],
                'wasit' => ['name' => 'Pak Wasit', 'signature' => 'data:image/png;base64,789'],
                'panitera' => [['name' => 'Mbak Panitera', 'signature' => 'data:image/png;base64,abc']],
            ],
        ]),
    ]);

    // Test default rekap mode
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->randoriMatch])
        ->assertSee('Berita Acara Pertandingan')
        ->assertSee('Randori Putra 50kg')
        ->assertSee('Kenshi A')
        ->assertSee('Kenshi B');

    // Test per-match print mode
    Livewire::test(NewLaporanRekapPenilaianDetail::class, ['matchNumber' => $this->randoriMatch])
        ->set('printMode', 'per-match')
        ->set('selectedMatch', 'all')
        ->assertSee('Berita Acara Pertandingan Randori')
        ->assertSee('AKA (MERAH)')
        ->assertSee('SHIRO (BIRU)')
        ->assertSee('Pak Arbitrase')
        ->assertSee('Pak Wasit')
        ->assertSee('Mbak Panitera');
});
