<?php

namespace App\Livewire\Admin;

use App\Exports\ScheduleExport;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\Pool\Pool;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

#[Layout('layouts.premium')]
class NewTechnicalMeetingDrawingIndex extends Component
{
    public ?int $filterAgeGroupId = null;

    public ?int $filterMatchNumberId = null;

    public ?int $filterMergeId = null;

    public string $draftType = 'randori'; // randori, embu, jadwal

    public bool $isGenerating = false;

    public bool $showEditModal = false;

    public ?int $editPoolId = null;

    public ?int $editCourtId = null;

    public ?int $editSessionId = null;

    public string $editKoorName = '';

    public array $editPaniteraNames = [];

    public string $searchMatchNumber = '';

    public string $searchPanitera = '';

    public function selectMatch($id): void
    {
        $this->filterMatchNumberId = $id;
        $this->filterMergeId = null;
    }

    public function selectMerge($id): void
    {
        $this->filterMergeId = $id;
        $this->filterMatchNumberId = null;
    }

    public function mount(): void
    {
        $firstMatch = MatchNumber::where('draft_type', $this->draftType)
            ->has('athletes')
            ->orderBy('id')
            ->first();

        if ($firstMatch) {
            $this->filterAgeGroupId = $firstMatch->age_group_id;
            $this->filterMatchNumberId = $firstMatch->id;
        }
    }

    public array $matchCodeMapping = [

        // Pemula (Embu)
        'Embu Tandoku Kyu 6 (Eksibisi)' => 'P-E-TK-01-X',
        'Embu Pasangan Kyu 6 (Eksibisi)' => 'P-E-PS-04-X',
        'Embu Tandoku Kyu 5-4' => 'P-E-TK-02-P',
        'Embu Pasangan Kyu 5-4' => 'P-E-PS-03-P',
        // Remaja A (Embu)
        'Embu Tandoku Kyu 6 (Eksibisi)' => 'RA-E-TK-01-X',
        'Embu Pasangan Kyu 6 (Eksibisi)' => 'RA-E-PS-03-X',
        // Remaja B (Embu)
        'Embu Tandoku Kyu 4-3' => 'RB-E-TK-01-P',
        'Embu Tandoku Kyu 2-1' => 'RB-E-TK-02-P',
        // Randori
        'Randori 45Kg' => 'R-45',
        'Randori 50Kg' => 'R-50',
        'Randori 55Kg' => 'R-55',
        'Randori 60Kg' => 'R-60',
        'Randori 65Kg' => 'R-65',
        'Randori 70Kg' => 'R-70',
        'Randori >70Kg' => 'R-GT70',
    ];

    private function getMatchIdCode(MatchNumber $match, ?int $seq = null): string
    {
        if (! empty($match->match_id) && $seq === null) {
            return $match->match_id;
        }

        $fullName = $match->name;

        // Category
        $cat = match ($match->ageGroup->name ?? '') {
            'Pemula' => 'P',
            'Remaja A' => 'RA',
            'Remaja B' => 'RB',
            'Dewasa' => 'D',
            default => 'U',
        };

        // Type
        $type = ($match->draft_type === 'randori') ? 'R' : 'E';

        // SubType
        $subType = 'XX';
        if ($match->draft_type === 'embu') {
            if (str_contains($fullName, 'Tandoku')) {
                $subType = 'TK';
            } elseif (str_contains($fullName, 'Pasangan')) {
                $subType = 'PS';
            } elseif (str_contains($fullName, 'Beregu')) {
                $subType = 'BG';
            }
        } else {
            if (str_contains($fullName, '<')) {
                preg_match('/\d+/', $fullName, $m);
                $subType = 'LT'.($m[0] ?? 'XX');
            } elseif (str_contains($fullName, '>')) {
                preg_match('/\d+/', $fullName, $m);
                $subType = 'GT'.($m[0] ?? 'XX');
            } elseif (str_contains($fullName, '-')) {
                preg_match('/(\d+)-(\d+)/', $fullName, $m);
                $subType = ($m[1] ?? '').($m[2] ?? '');
            } else {
                preg_match('/\d+/', $fullName, $m);
                $subType = $m[0] ?? 'XX';
            }
        }

        // Order (Sequence)
        $order = str_pad($seq ?? $match->order ?? 0, 2, '0', STR_PAD_LEFT);

        // Gender
        $gender = ($match->gender === 'L') ? 'PA' : (($match->gender === 'P') ? 'PI' : 'X');

        return "{$cat}-{$type}-{$subType}-{$order}-{$gender}";
    }

    public function updatedDraftType(): void
    {
        $this->filterAgeGroupId = null;
        $this->filterMatchNumberId = null;
        $this->filterMergeId = null;
    }

    public function updatedFilterAgeGroupId(): void
    {
        $this->filterMatchNumberId = null;
        $this->filterMergeId = null;
    }

    // ── RANDORI ──────────────────────────────────────────────
    public function generateRandoriDrawing(bool $showSwal = true)
    {
        if (! $this->filterMatchNumberId && ! $this->filterMergeId) {
            return false;
        }

        $this->isGenerating = true;
        $matchNumberIds = [];
        $match = null;
        if ($this->filterMergeId) {
            $merge = MatchNumberMerge::with('matchNumbers')->findOrFail($this->filterMergeId);
            $matchNumberIds = $merge->matchNumbers->pluck('id')->toArray();
            $match = $merge->matchNumbers->first();
        } else {
            $matchId = $this->filterMatchNumberId;
            $match = MatchNumber::findOrFail($matchId);
            $matchNumberIds = [$matchId];
        }

        // 1. Clear existing drawing records
        DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->delete();

        // 2. Get athletes, spread to avoid same contingent meeting early
        $athletesQuery = DB::table('athlete_match_number')
            ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->whereIn('athlete_match_number.match_number_id', $matchNumberIds)
            ->select('athletes.id', 'athletes.name', 'athlete_match_number.registration_id', 'contingents.name as contingent_name', 'athlete_match_number.match_number_id')
            ->distinct()
            ->get();

        $totalAthletes = $athletesQuery->count();

        if ($totalAthletes === 0) {
            $this->isGenerating = false;
            if ($showSwal) {
                $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Belum Ada Peserta', 'text' => 'Tidak ada atlet yang terdaftar di kategori ini.']);
            }

            return false;
        }

        $grouped = $athletesQuery->groupBy('contingent_name');
        $uniqueContingentCount = $grouped->count();

        if ($uniqueContingentCount < 3 && $totalAthletes < 3) {

            // optional logging / collect skipped
            logger()->warning('Drawing skipped karena peserta minim', [
                'match_id' => $match->id,
                'total_athletes' => $totalAthletes,
                'unique_contingent' => $uniqueContingentCount,
            ]);

            $this->isGenerating = false;

            if ($showSwal) {
                $this->dispatch('swal', [
                    'icon' => 'warning',
                    'title' => 'Peserta Minim',
                    'text' => 'Minimal harus ada 3 peserta/entri untuk melakukan drawing. Saat ini hanya ada '.$totalAthletes.' entri dari '.$uniqueContingentCount.' kontingen.'
                ]);
            }

            return false;
        }

        $spreadAthletes = [];
        $maxPerContingent = $grouped->max(fn ($c) => $c->count());
        for ($i = 0; $i < $maxPerContingent; $i++) {
            foreach ($grouped as $members) {
                if ($members->count() > $i) {
                    $spreadAthletes[] = $members[$i];
                }
            }
        }

        $athletes = array_map(fn ($a) => [
            'id' => $a->id,
            'name' => $a->name,
            'contingent' => $a->contingent_name,
            'registration_id' => $a->registration_id,
            'match_number_id' => $a->match_number_id,
        ], $spreadAthletes);

        // Bracket size = next power of 2
        $bracketSize = 1;
        while ($bracketSize < $totalAthletes) {
            $bracketSize *= 2;
        }

        // 3. Build Elimination structure
        if ($totalAthletes <= 4) {
            $drawingData = $this->buildDoubleElimination($athletes, $bracketSize);
            $typeLabel = 'Double Elimination';
            $drawingData['type'] = 'double_elimination';
        } else {
            $drawingData = $this->buildSingleElimination($athletes, $bracketSize);
            $typeLabel = 'Single Elimination';
            $drawingData['type'] = 'single_elimination';
        }

        MatchNumber::whereIn('id', $matchNumberIds)->update([
            'drawing_data' => $drawingData,
            'drawing_generated_at' => now(),
        ]);

        // 4. Schedule based on ALL bracket matches
        $allMatchesToSchedule = [];

        if (isset($drawingData['upper_bracket']['rounds'])) {
            foreach ($drawingData['upper_bracket']['rounds'] as $rIdx => $round) {
                foreach ($round as $mIdx => $m) {
                    if (empty($m['is_bye'])) {
                        $allMatchesToSchedule[] = [
                            'round_name' => 'Penyisihan (UB R'.($rIdx + 1).')',
                            'match' => $m,
                            'node_key' => 'ub_'.$rIdx.'_'.$mIdx,
                        ];
                    }
                }
            }
        }

        if (isset($drawingData['lower_bracket']['rounds'])) {
            foreach ($drawingData['lower_bracket']['rounds'] as $rIdx => $round) {
                foreach ($round as $mIdx => $m) {
                    if (empty($m['is_bye'])) {
                        $allMatchesToSchedule[] = [
                            'round_name' => 'Penyisihan (LB R'.($rIdx + 1).')',
                            'match' => $m,
                            'node_key' => 'lb_'.$rIdx.'_'.$mIdx,
                        ];
                    }
                }
            }
        }

        if (! empty($drawingData['grand_final']) && empty($drawingData['grand_final']['is_bye'])) {
            $allMatchesToSchedule[] = [
                'round_name' => 'Grand Final',
                'match' => $drawingData['grand_final'],
                'node_key' => 'gf_0_0',
            ];
        }

        $scheduledMatchesCount = count($allMatchesToSchedule);

        $durationPerMatch = 10;

        // Group athletes by match for granular clash detection
        $matchAthletesGrouped = [];
        foreach ($allMatchesToSchedule as $item) {
            $m = $item['match'];
            $rIds = [];
            if (isset($m['athlete1']['registration_id'])) {
                $rIds[] = $m['athlete1']['registration_id'];
            }
            if (isset($m['athlete2']['registration_id'])) {
                $rIds[] = $m['athlete2']['registration_id'];
            }
            $matchAthletesGrouped[] = $this->getAthleteIdsForRegistrations($rIds);
        }

        $slotData = $this->findBestAvailableSlot(max(1, $scheduledMatchesCount), $durationPerMatch, null, $matchAthletesGrouped);
        $court = $slotData['court'];
        $sessionTime = $slotData['session'];
        $rundown = $slotData['rundown'];
        $startTime = $slotData['startTime'];

        $localBusyKoor = [];
        $localBusyPanitera = [];
        $officials = $this->getAvailableOfficials($rundown?->id, $sessionTime?->id, $localBusyKoor, $localBusyPanitera);

        $matchSeq = 1;
        $mIdx = 0;

        foreach ($allMatchesToSchedule as $scheduleItem) {
            $m = $scheduleItem['match'];
            $roundName = $scheduleItem['round_name'];
            $a1 = $m['athlete1'] ?? null;
            $a2 = $m['athlete2'] ?? null;

            $matchStart = $startTime->copy()->addMinutes($mIdx * $durationPerMatch);
            $matchEnd = $matchStart->copy()->addMinutes($durationPerMatch);

            // Record for Athlete 1
            DrawingMatchNumber::create([
                'match_number_id' => $a1['match_number_id'] ?? $match->id,
                'registration_id' => $a1['registration_id'] ?? null,
                'court_id' => $court?->id,
                'session_time_id' => $sessionTime?->id,
                'rundown_id' => $rundown?->id,
                'round' => $roundName,
                'sequence_number' => $matchSeq,
                'draft_type' => 'randori',
                'metadata' => [
                    'athlete_id' => $a1['id'] ?? null,
                    'athlete_name' => $a1['name'] ?? 'TBD',
                    'contingent' => $a1['contingent'] ?? 'TBD',
                    'officials' => $officials,
                    'match_id_code' => $this->getMatchIdCode($match, $matchSeq),
                    'start_time' => $matchStart->format('H:i'),
                    'end_time' => $matchEnd->format('H:i'),
                    'duration' => $durationPerMatch,
                    'side' => 'RED',
                    'pool_label' => $roundName,
                    'merge_id' => $this->filterMergeId,
                    'node_key' => $scheduleItem['node_key'] ?? null,
                ],
            ]);

            // Record for Athlete 2
            DrawingMatchNumber::create([
                'match_number_id' => $a2['match_number_id'] ?? $match->id,
                'registration_id' => $a2['registration_id'] ?? null,
                'court_id' => $court?->id,
                'session_time_id' => $sessionTime?->id,
                'rundown_id' => $rundown?->id,
                'round' => $roundName,
                'sequence_number' => $matchSeq,
                'draft_type' => 'randori',
                'metadata' => [
                    'athlete_id' => $a2['id'] ?? null,
                    'athlete_name' => $a2['name'] ?? 'TBD',
                    'contingent' => $a2['contingent'] ?? 'TBD',
                    'officials' => $officials,
                    'match_id_code' => $this->getMatchIdCode($match, $matchSeq),
                    'start_time' => $matchStart->format('H:i'),
                    'end_time' => $matchEnd->format('H:i'),
                    'duration' => $durationPerMatch,
                    'side' => 'BLUE',
                    'pool_label' => $roundName,
                    'merge_id' => $this->filterMergeId,
                    'node_key' => $scheduleItem['node_key'] ?? null,
                ],
            ]);

            $matchSeq++;
            $mIdx++;
        }
        $this->isGenerating = false;
        if ($showSwal) {
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Bagan '.$typeLabel.' Dibuat!',
                'text' => $totalAthletes.' atlet | '.$scheduledMatchesCount.' Jadwal Match.',
            ]);
        }
    }

    // ── GENERATE ALL ─────────────────────────────────────────
    public function generateAllDrawings(): void
    {
        set_time_limit(0);
        $this->isGenerating = true;

        $matches = MatchNumber::whereNull('drawing_generated_at')
            ->has('athletes')
            ->get();

        $success = 0;
        $skipped = 0;

        foreach ($matches as $match) {
            $this->filterMatchNumberId = $match->id;

            try {
                if ($match->draft_type === 'randori') {
                    $result = $this->generateRandoriDrawing(false);
                } else {
                    $result = $this->generateEmbuDrawing(false);
                }

                if ($result === false) {
                    $skipped++;
                } else {
                    $success++;
                }

            } catch (\Throwable $e) {
                $skipped++;
                logger()->error($e->getMessage());
            }
        }

        $this->filterMatchNumberId = null;
        $this->isGenerating = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Generate Selesai',
            'text' => $success.' berhasil, '.$skipped.' dilewati.',
        ]);
    }

    public function resetAllDrawings(): void
    {
        set_time_limit(0);
        
        DrawingMatchNumber::query()->delete();
        MatchNumber::whereNotNull('drawing_generated_at')
            ->update(['drawing_data' => null, 'drawing_generated_at' => null]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Reset Selesai',
            'text' => 'Semua data drawing telah direset.',
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new ScheduleExport, 'Jadwal_Pertandingan_Kempo.xlsx');
    }

    // ── EMBU ─────────────────────────────────────────────────
    public function generateEmbuDrawing(bool $showSwal = true)
    {
        if (! $this->filterMatchNumberId && ! $this->filterMergeId) {
            return false; 
        }

        $this->isGenerating = true;
        $matchNumberIds = [];
        $match = null;
        $merge = null;

        if ($this->filterMergeId) {
            $merge = MatchNumberMerge::with('matchNumbers')->findOrFail($this->filterMergeId);
            $matchNumberIds = $merge->matchNumbers->pluck('id')->toArray();
            $match = $merge->matchNumbers->first(); // Use first match as template for settings
            $matchId = $match->id;
        } else {
            $matchId = $this->filterMatchNumberId;
            $match = MatchNumber::findOrFail($matchId);
            $matchNumberIds = [$matchId];
        }

        $maxAthletes = $match->max_athletes ?: 1;

        // Get all registrations across all included match numbers
        $registrationsQuery = DB::table('athlete_match_number')
            ->whereIn('match_number_id', $matchNumberIds)
            ->select('registration_id', 'match_number_id', DB::raw('count(*) as athlete_count'))
            ->groupBy('registration_id', 'match_number_id')
            ->get();

        $allEntries = collect();
        foreach ($registrationsQuery as $reg) {
            $athleteIds = DB::table('athlete_match_number')
                ->where('match_number_id', $reg->match_number_id)
                ->where('registration_id', $reg->registration_id)
                ->orderBy('id')
                ->pluck('athlete_id');

            $chunks = $athleteIds->chunk($maxAthletes);

            foreach ($chunks as $chunk) {
                $allEntries->push((object) [
                    'registration_id' => $reg->registration_id,
                    'match_number_id' => $reg->match_number_id,
                    'athlete_ids' => $chunk->toArray(),
                ]);
            }
        }

        $totalEntries = $allEntries->count();

        // Delete existing drawings for all affected match numbers
        DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->delete();

        if ($totalEntries === 0) {
            $this->isGenerating = false;
            if ($showSwal) {
                $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Belum Ada Peserta', 'text' => 'Tidak ada peserta terdaftar.']);
            }

            return false;
        }

        $regContingents = DB::table('registrations')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->whereIn('registrations.id', $allEntries->pluck('registration_id')->unique())
            ->pluck('contingents.name', 'registrations.id');

        $uniqueContingentCount = $regContingents->unique()->count();

        if ($uniqueContingentCount < 3 && $totalEntries < 3) {
            $this->isGenerating = false;
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Peserta Minim',
                'text' => "Kategori ini hanya diikuti oleh {$totalEntries} peserta dari {$uniqueContingentCount} kontingen. Minimal harus ada 3 peserta agar dapat dipertandingkan.",
            ]);

            return false;
        }

        $entries = $allEntries->map(fn ($r) => [
            'registration_id' => $r->registration_id,
            'match_number_id' => $r->match_number_id,
            'athlete_ids' => $r->athlete_ids,
            'contingent' => $regContingents[$r->registration_id] ?? 'Unknown',
        ])->values()->toArray();

        shuffle($entries);

        if ($totalEntries <= 9) {
            $format = '2_babak';
            $poolCount = 1;
            $description = '2 Babak (Penyisihan + Final)';
        } elseif ($totalEntries <= 11) {
            $format = 'pool';
            $poolCount = 2;
            $description = 'Sistem Pool (2 Pool)';
        } elseif ($totalEntries <= 17) {
            $format = 'pool';
            $poolCount = 3;
            $description = 'Sistem Pool (3 Pool)';
        } else {
            $format = 'pool';
            $poolCount = 4;
            $description = 'Sistem Pool (4 Pool)';
        }

        $pools = [];
        $poolLabels = ['A', 'B', 'C', 'D'];
        $poolRecords = [];
        for ($i = 0; $i < $poolCount; $i++) {
            $poolRecords[] = Pool::firstOrCreate([
                'name' => ($format === '2_babak') ? 'POOL 1' : 'POOL '.$poolLabels[$i],
            ], [
                'order' => $i + 1,
            ]);
        }

        // --- AUTO-SCHEDULER LOGIC ---
        $durationPerMatch = 10;

        // Group athletes for granular clash detection
        $matchAthletesGrouped = [];
        foreach ($entries as $entry) {
            $matchAthletesGrouped[] = $this->getAthleteIdsForRegistrations([$entry['registration_id']]);
        }

        $slotData = $this->findBestAvailableSlot($totalEntries, $durationPerMatch, null, $matchAthletesGrouped);

        $court = $slotData['court'];
        $sessionTime = $slotData['session'];
        $rundown = $slotData['rundown'];
        $startTime = $slotData['startTime'];
        $courtId = $court ? $court->id : null;

        $localBusyKoor = [];
        $localBusyPanitera = [];
        $poolOfficialsCache = [];

        foreach ($entries as $index => $entry) {
            $poolIdx = ($format === '2_babak') ? 0 : ($index % $poolCount);
            $pool = $poolRecords[$poolIdx];
            $poolLabel = $pool->name;

            if (! isset($poolOfficialsCache[$poolLabel])) {
                $poolOfficialsCache[$poolLabel] = $this->getAvailableOfficials($rundown?->id, $sessionTime?->id, $localBusyKoor, $localBusyPanitera);
            }
            $officials = $poolOfficialsCache[$poolLabel];

            if (! isset($pools[$poolLabel])) {
                $pools[$poolLabel] = [];
            }

            $orderInPool = count($pools[$poolLabel]) + 1;

            $entryStart = $startTime->copy()->addMinutes($index * $durationPerMatch);
            $entryEnd = $entryStart->copy()->addMinutes($durationPerMatch);

            $pools[$poolLabel][] = ['order' => $orderInPool, 'registration_id' => $entry['registration_id'], 'contingent' => $entry['contingent']];

            DrawingMatchNumber::create([
                'match_number_id' => $entry['match_number_id'],
                'registration_id' => $entry['registration_id'],
                'pool_id' => $pool->id,
                'court_id' => $courtId,
                'schedule_date' => $rundown?->date,
                'session_time_id' => $sessionTime?->id,
                'rundown_id' => $rundown?->id,
                'round' => 'Penyisihan',
                'sequence_number' => $orderInPool,
                'draft_type' => 'embu',
                'metadata' => [
                    'pool_label' => $poolLabel,
                    'officials' => $officials,
                    'match_id_code' => $this->getMatchIdCode($match, $index + 1),
                    'start_time' => $entryStart->format('H:i'),
                    'end_time' => $entryEnd->format('H:i'),
                    'duration' => $durationPerMatch,
                    'athlete_name' => $entry['athlete_name'] ?? 'TBD',
                    'athlete_ids' => $entry['athlete_ids'] ?? [],
                    'contingent' => $entry['contingent'] ?? 'TBD',
                    'merge_id' => $this->filterMergeId, // Optional: track merge
                ],
            ]);
        }

        // Update drawing_generated_at for all match numbers
        MatchNumber::whereIn('id', $matchNumberIds)->update(['drawing_generated_at' => now()]);

        // --- GENERATE FINAL SLOTS (PLACEHOLDERS) ---
        $qualifiersPerPool = 0;
        if ($poolCount === 1) {
            $qualifiersPerPool = $totalEntries;
        } elseif ($poolCount === 2) {
            $qualifiersPerPool = 4;
        } elseif ($poolCount === 3) {
            $qualifiersPerPool = 3;
        } else {
            $qualifiersPerPool = 2;
        }

        $totalFinalists = ($poolCount === 1) ? $totalEntries : ($poolCount * $qualifiersPerPool);

        if ($totalFinalists > 0) {
            $minFinalStartTime = null;
            if (isset($rundown) && isset($entryEnd)) {
                $rDate = Carbon::parse($rundown->date)->format('Y-m-d');
                $eTime = Carbon::parse($entryEnd)->format('H:i:s');
                $minFinalStartTime = Carbon::parse($rDate.' '.$eTime)->addHours(2); // At least 2 hours break
            }

            // For finals, we don't know athletes yet, but we want it to be later (Day 2 if possible)
            $finalSlotData = $this->findBestAvailableSlot($totalFinalists, $durationPerMatch, $minFinalStartTime, [], true);
            $fCourtId = $finalSlotData['court'] ? $finalSlotData['court']->id : null;
            $fStartTime = $finalSlotData['startTime'];

            for ($i = 0; $i < $totalFinalists; $i++) {
                $fStart = $fStartTime->copy()->addMinutes($i * $durationPerMatch);
                $fEnd = $fStart->copy()->addMinutes($durationPerMatch);

                DrawingMatchNumber::create([
                    'match_number_id' => $matchId,
                    'registration_id' => null, // TBD
                    'pool_id' => null, // Final pool
                    'court_id' => $fCourtId,
                    'schedule_date' => $finalSlotData['rundown']?->date,
                    'session_time_id' => $finalSlotData['session']?->id,
                    'rundown_id' => $finalSlotData['rundown']?->id,
                    'round' => 'Final',
                    'sequence_number' => $i + 1,
                    'draft_type' => 'embu',
                    'metadata' => [
                        'pool_label' => 'FINAL',
                        'officials' => [],
                        'match_id_code' => $this->getMatchIdCode($match, $totalEntries + $i + 1), // Offset by penyisihan entries
                        'start_time' => $fStart->format('H:i'),
                        'end_time' => $fEnd->format('H:i'),
                        'duration' => $durationPerMatch,
                        'contingent' => 'Lolos Final',
                        'athlete_name' => 'TBD',
                    ],
                ]);
            }
        }

        MatchNumber::whereIn('id', $matchNumberIds)->update([
            'drawing_data' => ['total_entries' => $totalEntries, 'format' => $format, 'pool_count' => $poolCount, 'description' => $description, 'pools' => $pools],
            'drawing_generated_at' => now(),
        ]);

        $this->isGenerating = false;
        if ($showSwal) {
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Drawing Embu Dibuat!', 'text' => $totalEntries.' tim | '.$description]);
        }
    }

    private function getAthleteIdsForRegistrations(array $registrationIds): array
    {
        return DB::table('registration_athlete')
            ->whereIn('registration_id', $registrationIds)
            ->pluck('athlete_id')
            ->unique()
            ->toArray();
    }

    private function findBestAvailableSlot(int $neededCount, int $durationMinutes = 10, ?Carbon $minStartTime = null, array $matchAthletesGrouped = [], bool $isFinal = false): array
    {
        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date')->orderBy('order')->get();
        $sessions = SessionTime::orderBy('start_time')->get();
        $courts = Court::orderBy('order')->get();

        // If it's a final, and we have multiple days, try to jump to Day 2+ immediately to avoid same-day fatigue
        if ($isFinal && $rundowns->count() > 1) {
            $firstDate = $rundowns->first()->date;
            $laterRundowns = $rundowns->filter(fn ($r) => $r->date > $firstDate);
            if ($laterRundowns->isNotEmpty()) {
                $rundowns = $laterRundowns;
            }
        }

        foreach ($rundowns as $rundown) {
            foreach ($sessions as $session) {
                $rDate = Carbon::parse($rundown->date)->format('Y-m-d');
                $sStartStr = Carbon::parse($session->start_time)->format('H:i:s');
                $sEndStr = Carbon::parse($session->end_time)->format('H:i:s');

                $sessionStart = Carbon::parse($rDate.' '.$sStartStr);
                $sessionEnd = Carbon::parse($rDate.' '.$sEndStr);

                // Fetch ALL busy intervals for this session once to avoid N+1 inside the court loop
                $busyAthletes = [];
                $existingDrawingInSession = DrawingMatchNumber::where('rundown_id', $rundown->id)
                    ->where('session_time_id', $session->id)
                    ->with('registration.athletes')
                    ->get();

                foreach ($existingDrawingInSession as $d) {
                    $m = $d->metadata ?? [];
                    if (!isset($m['start_time']) || !isset($m['end_time'])) continue;
                    
                    $start = Carbon::parse($m['start_time']);
                    $end = Carbon::parse($m['end_time']);
                    $athletes = $d->registration ? $d->registration->athletes->pluck('id')->toArray() : [];
                    foreach ($athletes as $aId) {
                        $busyAthletes[$aId][] = ['start' => $start, 'end' => $end];
                    }
                }

                // Iterate through every possible start time in 10-minute increments
                $currentTime = $sessionStart->copy();
                while ($currentTime->copy()->addMinutes($neededCount * $durationMinutes)->lte($sessionEnd)) {

                    if ($minStartTime && $currentTime->lt($minStartTime)) {
                        $currentTime->addMinutes($durationMinutes);

                        continue;
                    }

                    // Check which court is available at this EXACT currentTime
                    foreach ($courts as $court) {
                        // Check if this court is busy for ANY part of the needed block
                        $endTimeBlock = $currentTime->copy()->addMinutes($neededCount * $durationMinutes);

                        $isCourtOccupied = false;
                        $courtMatches = $existingDrawingInSession->where('court_id', $court->id);
                        foreach ($courtMatches as $cm) {
                            $m = $cm->metadata ?? [];
                            if (!isset($m['start_time']) || !isset($m['end_time'])) continue;

                            $cmStart = Carbon::parse($rDate.' '.Carbon::parse($m['start_time'])->format('H:i:s'));
                            $cmEnd = Carbon::parse($rDate.' '.Carbon::parse($m['end_time'])->format('H:i:s'));

                            if ($currentTime->lt($cmEnd) && $endTimeBlock->gt($cmStart)) {
                                $isCourtOccupied = true;
                                break;
                            }
                        }

                        if ($isCourtOccupied) {
                            continue; // Try next court at same currentTime
                        }

                        // Court is available! Now check Granular Athlete Clashes
                        $hasAthleteClash = false;
                        for ($i = 0; $i < $neededCount; $i++) {
                            $slotStart = $currentTime->copy()->addMinutes($i * $durationMinutes);
                            $slotEnd = $slotStart->copy()->addMinutes($durationMinutes);

                            $athletesInSlot = $matchAthletesGrouped[$i] ?? [];
                            foreach ($athletesInSlot as $aId) {
                                if (isset($busyAthletes[$aId])) {
                                    foreach ($busyAthletes[$aId] as $interval) {
                                        $iStart = Carbon::parse($rDate.' '.$interval['start']->format('H:i:s'));
                                        $iEnd = Carbon::parse($rDate.' '.$interval['end']->format('H:i:s'));

                                        if ($slotStart->lt($iEnd) && $slotEnd->gt($iStart)) {
                                            $hasAthleteClash = true;
                                            break 2; // Next court or next time
                                        }
                                    }
                                }
                            }
                        }

                        if ($hasAthleteClash) {
                            continue; // Try next court (though likely same clash) or next time
                        }

                        // FOUND IT! Earliest time, first available court, no athlete clashes.
                        return [
                            'rundown' => $rundown,
                            'session' => $session,
                            'court' => $court,
                            'startTime' => $currentTime,
                        ];
                    }

                    $currentTime->addMinutes($durationMinutes);
                }
            }
        }

        // Ultimate fallback
        return [
            'rundown' => $rundowns->first(),
            'session' => $sessions->first(),
            'court' => $courts->first(),
            'startTime' => $minStartTime ?? Carbon::parse(Carbon::parse($rundowns->first()->date)->format('Y-m-d').' '.Carbon::parse($sessions->first()->start_time)->format('H:i:s')),
        ];
    }

    public function resetDrawing(): void
    {
        if (! $this->filterMatchNumberId && ! $this->filterMergeId) {
            return;
        }

        $matchNumberIds = [];
        if ($this->filterMergeId) {
            $merge = MatchNumberMerge::with('matchNumbers')->findOrFail($this->filterMergeId);
            $matchNumberIds = $merge->matchNumbers->pluck('id')->toArray();
        } else {
            $matchNumberIds = [$this->filterMatchNumberId];
        }

        DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->delete();
        MatchNumber::whereIn('id', $matchNumberIds)->update(['drawing_data' => null, 'drawing_generated_at' => null]);

        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Drawing Direset', 'text' => 'Data drawing berhasil dihapus.', 'timer' => 2000]);
    }

    private function getAvailableOfficials($rundownId, $sessionTimeId, &$localBusyKoor, &$localBusyPanitera)
    {
        $this->ensureDummyOfficials();

        $busyMetadata = DrawingMatchNumber::where('rundown_id', $rundownId)
            ->where('session_time_id', $sessionTimeId)
            ->pluck('metadata');

        $dbKoordinatorNames = [];
        $dbPaniteraNames = [];

        foreach ($busyMetadata as $meta) {
            if (isset($meta['officials']['koordinator_lapangan'])) {
                $dbKoordinatorNames[] = $meta['officials']['koordinator_lapangan'];
            }
            if (isset($meta['officials']['panitera']) && is_array($meta['officials']['panitera'])) {
                $dbPaniteraNames = array_merge($dbPaniteraNames, $meta['officials']['panitera']);
            }
        }

        $busyKoordinatorNames = array_unique(array_merge($dbKoordinatorNames, $localBusyKoor));
        $busyPaniteraNames = array_unique(array_merge($dbPaniteraNames, $localBusyPanitera));

        $koordinator = User::role('Koordinator Lapangan')
            ->whereNotIn('name', $busyKoordinatorNames)
            ->inRandomOrder()
            ->first();

        if (! $koordinator) {
            // Fallback if all are busy
            $koordinator = User::role('Koordinator Lapangan')->inRandomOrder()->first();
        }

        $paniteras = User::role('Panitera')
            ->whereNotIn('name', $busyPaniteraNames)
            ->inRandomOrder()
            ->take(4)
            ->pluck('name')
            ->toArray();

        if (count($paniteras) < 4) {
            // Fallback to fill missing
            $extra = User::role('Panitera')
                ->whereNotIn('name', $paniteras)
                ->inRandomOrder()
                ->take(4 - count($paniteras))
                ->pluck('name')
                ->toArray();
            $paniteras = array_merge($paniteras, $extra);
        }

        if ($koordinator) {
            $localBusyKoor[] = $koordinator->name;
        }
        $localBusyPanitera = array_merge($localBusyPanitera, $paniteras);

        return [
            'koordinator_lapangan' => $koordinator->name ?? 'Dummy Koordinator',
            'panitera' => $paniteras,
        ];
    }

    private function ensureDummyOfficials(): void
    {
        // Ensure roles exist
        $roles = ['Koordinator Lapangan', 'Panitera'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Ensure at least 1 Koordinator Lapangan
        if (User::role('Koordinator Lapangan')->count() < 1) {
            $koor = User::factory()->create([
                'name' => 'Koor. Lapangan Dummy',
                'email' => 'koor_lapangan_'.uniqid().'@example.com',
                'password' => Hash::make('password'),
            ]);
            $koor->assignRole('Koordinator Lapangan');
        }

        // Ensure at least 4 Panitera
        $currentPanitera = User::role('Panitera')->count();
        if ($currentPanitera < 4) {
            for ($i = 0; $i < (4 - $currentPanitera); $i++) {
                $panitera = User::factory()->create([
                    'name' => 'Panitera Dummy '.($currentPanitera + $i + 1),
                    'email' => 'panitera_'.uniqid().'@example.com',
                    'password' => Hash::make('password'),
                ]);
                $panitera->assignRole('Panitera');
            }
        }
    }

    public function openEditModal(?int $poolId = null): void
    {
        $this->editPoolId = $poolId;

        $query = DrawingMatchNumber::where('match_number_id', $this->filterMatchNumberId);
        if ($poolId) {
            $query->where('pool_id', $poolId);
        }

        $first = $query->first();
        if ($first) {
            $this->editCourtId = $first->court_id;
            $this->editSessionId = $first->session_time_id;
            $this->editKoorName = $first->metadata['officials']['koordinator_lapangan'] ?? '';
            $this->editPaniteraNames = $first->metadata['officials']['panitera'] ?? [];
            $this->showEditModal = true;
        }
    }

    public function saveAssignments(): void
    {
        $query = DrawingMatchNumber::where('match_number_id', $this->filterMatchNumberId);
        if ($this->editPoolId) {
            $query->where('pool_id', $this->editPoolId);
        }

        $records = $query->get();

        foreach ($records as $record) {
            $meta = $record->metadata;
            $meta['officials'] = [
                'koordinator_lapangan' => $this->editKoorName,
                'panitera' => $this->editPaniteraNames,
            ];

            $record->update([
                'court_id' => $this->editCourtId,
                'session_time_id' => $this->editSessionId,
                'metadata' => $meta,
            ]);
        }

        $this->showEditModal = false;
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Tersimpan',
            'text' => 'Penugasan berhasil diperbarui secara manual.',
        ]);
    }

    // ── RENDER ───────────────────────────────────────────────
    public function render()
    {
        $ageGroupIds = MatchNumber::where('draft_type', $this->draftType)
            ->has('athletes')->pluck('age_group_id')->unique()->filter();

        $filterAgeGroups = AgeGroup::whereIn('id', $ageGroupIds)->orderBy('order')->get();

        $matchNumbersQuery = MatchNumber::where('draft_type', $this->draftType)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('match_number_merge_details')
                    ->whereColumn('match_number_merge_details.match_number_id', 'match_numbers.id');
            })
            ->has('athletes')->with(['ageGroup'])->orderBy('name', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id');

        if ($this->searchMatchNumber) {
            $matchNumbersQuery->where('name', 'ilike', '%'.$this->searchMatchNumber.'%');
        }

        if ($this->filterAgeGroupId) {
            $matchNumbersQuery->where('age_group_id', $this->filterAgeGroupId);
        }

        $filterMatchNumbers = $matchNumbersQuery->get();

        // Get Match Number Merges
        $mergeQuery = MatchNumberMerge::with('matchNumbers')
            ->where('type', $this->draftType);
        if ($this->filterAgeGroupId) {
            $mergeQuery->where('age_group_id', $this->filterAgeGroupId);
        }
        if ($this->searchMatchNumber) {
            $mergeQuery->where('name', 'ilike', '%'.$this->searchMatchNumber.'%');
        }
        $filterMerges = $mergeQuery->get()->map(function($m) {
            $mergedNames = $m->matchNumbers->pluck('name')->join(', ');
            $m->display_name = ($m->name ?: 'Merged Group') . " (" . $mergedNames . ")";
            return $m;
        });

        // Get entry counts for each match number to display in sidebar
        $rawAthleteCounts = DB::table('athlete_match_number')
            ->whereIn('match_number_id', $filterMatchNumbers->pluck('id'))
            ->select('match_number_id', DB::raw('count(*) as count'))
            ->groupBy('match_number_id')
            ->pluck('count', 'match_number_id');

        $contingentCounts = [];
        foreach ($filterMatchNumbers as $match) {
            $count = $rawAthleteCounts[$match->id] ?? 0;
            if ($match->draft_type === 'embu') {
                $contingentCounts[$match->id] = ceil($count / ($match->max_athletes ?: 1));
            } else {
                $contingentCounts[$match->id] = $count;
            }
        }

        $selectedMatch = null;
        $matchAthletes = collect();
        $drawingEntries = collect();

        if ($this->filterMergeId) {
            $merge = MatchNumberMerge::with('matchNumbers')->find($this->filterMergeId);
            if ($merge) {
                $selectedMatch = $merge;
                $matchNumberIds = $merge->matchNumbers->pluck('id')->toArray();
                $maxAthletes = $merge->matchNumbers->first()->max_athletes ?? 1;

                // Calculate consolidated display name
                $mergedNames = $merge->matchNumbers->pluck('name')->join(', ');
                $selectedMatch->display_name = ($merge->name ?: 'Merged Group') . " (" . $mergedNames . ")";

                $rawAthletes = DB::table('athlete_match_number')
                    ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                    ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                    ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                    ->whereIn('athlete_match_number.match_number_id', $matchNumberIds)
                    ->select('athletes.name as athlete_name', 'contingents.name as contingent_name', 'athlete_match_number.registration_id')
                    ->orderBy('contingents.name')
                    ->orderBy('athlete_match_number.id')
                    ->get();

                $entryData = [];
                foreach ($rawAthletes->groupBy('registration_id') as $regId => $regAthletes) {
                    $chunks = $regAthletes->chunk($maxAthletes);
                    $hasMultiple = $chunks->count() > 1;
                    foreach ($chunks as $chunkIndex => $chunk) {
                        $displayName = $chunk->first()->contingent_name.($hasMultiple ? ' ('.($chunkIndex + 1).')' : '');
                        $entryData[$displayName] = $chunk;
                    }
                }
                $matchAthletes = collect($entryData);

                $drawingEntries = DrawingMatchNumber::with(['registration.contingent', 'court', 'sessionTime'])
                    ->whereIn('match_number_id', $matchNumberIds)
                    ->orderBy('sequence_number')
                    ->get()
                    ->groupBy(fn ($e) => $e->metadata['pool_label'] ?? 'Drawing Result');
            }
        } elseif ($this->filterMatchNumberId) {
            $selectedMatch = MatchNumber::with(['ageGroup'])->find($this->filterMatchNumberId);
            if ($selectedMatch) {
                $selectedMatch->display_name = $selectedMatch->name;
                $maxAthletes = $selectedMatch->max_athletes ?: 1;
                $rawAthletes = DB::table('athlete_match_number')
                    ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                    ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                    ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                    ->where('athlete_match_number.match_number_id', $this->filterMatchNumberId)
                    ->select('athletes.name as athlete_name', 'contingents.name as contingent_name', 'athlete_match_number.registration_id')
                    ->orderBy('contingents.name')
                    ->orderBy('athlete_match_number.id')
                    ->get();

                $entryData = [];
                foreach ($rawAthletes->groupBy('registration_id') as $regId => $regAthletes) {
                    $chunks = $regAthletes->chunk($maxAthletes);
                    $hasMultiple = $chunks->count() > 1;
                    foreach ($chunks as $chunkIndex => $chunk) {
                        $displayName = $chunk->first()->contingent_name.($hasMultiple ? ' ('.($chunkIndex + 1).')' : '');
                        $entryData[$displayName] = $chunk;
                    }
                }
                $matchAthletes = collect($entryData);

                $drawingEntriesQuery = DrawingMatchNumber::with(['registration.contingent', 'court', 'sessionTime'])
                    ->where('match_number_id', $this->filterMatchNumberId)
                    ->orderBy('sequence_number')
                    ->get();

                if ($selectedMatch->draft_type === 'randori') {
                    $drawingEntries = collect(['Jadwal Pertandingan' => $drawingEntriesQuery]);
                } else {
                    $drawingEntries = $drawingEntriesQuery->groupBy(fn ($e) => $e->metadata['pool_label'] ?? 'Drawing Result');
                }
            }
        }

        // Schedule View Data - Grouped by Rundown -> Session -> Time
        $scheduleByRundown = [];
        $scheduleStats = [
            'total' => 0,
            'embu' => 0,
            'randori' => 0,
        ];
        if ($this->draftType === 'jadwal') {
            $allDrawings = DrawingMatchNumber::with(['matchNumber', 'registration.contingent', 'court', 'sessionTime', 'rundown', 'merge'])
                ->get()
                ->sortBy(function ($d) {
                    $date = $d->rundown->date ?? '9999-12-31';
                    $sTime = $d->sessionTime->start_time ?? '99:99';
                    $mTime = $d->metadata['start_time'] ?? '99:99';

                    return $date.$sTime.$mTime;
                });

            foreach ($allDrawings as $draw) {
                $rId = $draw->rundown_id ?? 0;
                $sId = $draw->session_time_id ?? 0;
                $time = $draw->metadata['start_time'] ?? 'Unknown';

                if (! isset($scheduleByRundown[$rId])) {
                    $scheduleByRundown[$rId] = [
                        'model' => $draw->rundown,
                        'sessions' => [],
                    ];
                }

                if (! isset($scheduleByRundown[$rId]['sessions'][$sId])) {
                    $scheduleByRundown[$rId]['sessions'][$sId] = [
                        'model' => $draw->sessionTime,
                        'times' => [],
                    ];
                }

                if (! isset($scheduleByRundown[$rId]['sessions'][$sId]['times'][$time])) {
                    $scheduleByRundown[$rId]['sessions'][$sId]['times'][$time] = [
                        'duration' => $draw->metadata['duration'] ?? 10,
                        'courts' => [],
                    ];
                }
                if (! isset($scheduleByRundown[$rId]['sessions'][$sId]['times'][$time]['courts'][$draw->court_id])) {
                    $scheduleByRundown[$rId]['sessions'][$sId]['times'][$time]['courts'][$draw->court_id] = [];
                }
                $scheduleByRundown[$rId]['sessions'][$sId]['times'][$time]['courts'][$draw->court_id][] = $draw;
            }

            foreach ($scheduleByRundown as $rId => $rData) {
                foreach ($rData['sessions'] as $sId => $sData) {
                    foreach ($sData['times'] as $tId => $tData) {
                        foreach ($tData['courts'] as $cId => $entries) {
                            $scheduleStats['total']++;
                            if (isset($entries[0]) && $entries[0]->draft_type === 'randori') {
                                $scheduleStats['randori']++;
                            } else {
                                $scheduleStats['embu']++;
                            }
                        }
                    }
                }
            }
        }

        return view('livewire.admin.new-technical-meeting-drawing-index', [
            'filterAgeGroups' => $filterAgeGroups,
            'filterMatchNumbers' => $filterMatchNumbers,
            'filterMerges' => $filterMerges,
            'selectedMatch' => $selectedMatch,
            'matchAthletes' => $matchAthletes,
            'drawingEntries' => $drawingEntries,
            'contingentCounts' => $contingentCounts,
            'courts' => Court::orderBy('order')->get(),
            'sessionTimes' => SessionTime::orderBy('start_time')->get(),
            'koorUsers' => User::role('Koordinator Lapangan')->orderBy('name')->get(),
            'paniteraUsers' => User::role('Panitera')->where('name', 'ilike', '%'.$this->searchPanitera.'%')->orderBy('name')->get(),
            'stats' => [
                'total' => MatchNumber::where('draft_type', $this->draftType)->has('athletes')->count(),
                'drawn' => MatchNumber::where('draft_type', $this->draftType)->has('athletes')->whereNotNull('drawing_generated_at')->count(),
                'pending' => MatchNumber::where('draft_type', $this->draftType)->has('athletes')->whereNull('drawing_generated_at')->count(),
            ],
            'scheduleByRundown' => $scheduleByRundown,
            'scheduleStats' => $scheduleStats,
        ]);
    }

    private function buildDoubleElimination(array $athletes, int $bracketSize): array
    {
        $n = count($athletes);
        $size = 2;
        while ($size < $n) {
            $size *= 2;
        }
        $bracketSize = $size;
        $mainK = (int) log($bracketSize, 2);

        $seedOrder = $this->generateSeedOrder($bracketSize);
        $seedToPos = array_flip($seedOrder);

        $slots = array_fill(0, $bracketSize, ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-']);
        for ($i = 0; $i < $n; $i++) {
            $pos = $seedToPos[$i + 1] ?? $i;
            $slots[$pos] = $athletes[$i];
        }

        $ubRounds = [];
        $lbRounds = [];

        // UB Rounds
        for ($r = 0; $r < $mainK; $r++) {
            $matchesInRound = $bracketSize / (2 ** ($r + 1));
            $round = [];
            for ($m = 0; $m < $matchesInRound; $m++) {
                $isFinal = ($r === $mainK - 1);

                $winnerNext = $isFinal
                    ? ['bracket' => 'gf', 'slot' => 'athlete1']
                    : ['bracket' => 'ub', 'round' => $r + 1, 'match' => intdiv($m, 2), 'slot' => ($m % 2 === 0) ? 'athlete1' : 'athlete2'];

                if ($r === 0) {
                    $loserNext = ['bracket' => 'lb', 'round' => 0, 'match' => intdiv($m, 2), 'slot' => ($m % 2 === 0) ? 'athlete1' : 'athlete2'];
                } else {
                    $loserNext = ['bracket' => 'lb', 'round' => 2 * $r - 1, 'match' => $m, 'slot' => 'athlete2'];
                }

                $round[] = [
                    'athlete1' => $r === 0 ? $slots[$m * 2] : null,
                    'athlete2' => $r === 0 ? $slots[$m * 2 + 1] : null,
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => $winnerNext,
                    'loser_next' => $loserNext,
                    'is_bye' => false,
                    'is_prelim' => false,
                ];
            }
            $ubRounds[] = $round;
        }

        // LB Rounds
        for ($r = 0; $r < ($mainK - 1) * 2; $r++) {
            $roundIdx = intdiv($r, 2);
            $matchesInRound = $bracketSize / (2 ** ($roundIdx + 2));
            $round = [];
            for ($m = 0; $m < $matchesInRound; $m++) {
                $isLast = ($r === ($mainK - 1) * 2 - 1);

                if ($isLast) {
                    $winnerNext = ['bracket' => 'gf', 'slot' => 'athlete2'];
                } elseif ($r === ($mainK - 1) * 2 - 2) {
                    // This is the LB Semi-Final (round before the absolute last LB round)
                    // Winner goes to LB Final, slot athlete1 (to meet UB Final loser who drops to athlete2)
                    $winnerNext = ['bracket' => 'lb', 'round' => $r + 1, 'match' => 0, 'slot' => 'athlete1'];
                } elseif ($r % 2 === 0) {
                    // Even round: winner goes to next round (odd), same match index, slot 1
                    $winnerNext = ['bracket' => 'lb', 'round' => $r + 1, 'match' => $m, 'slot' => 'athlete1'];
                } else {
                    // Odd round: winner goes to next round (even), half match index
                    $winnerNext = ['bracket' => 'lb', 'round' => $r + 1, 'match' => intdiv($m, 2), 'slot' => ($m % 2 === 0) ? 'athlete1' : 'athlete2'];
                }

                $round[] = [
                    'athlete1' => null,
                    'athlete2' => null,
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => $winnerNext,
                    'loser_next' => ['bracket' => 'eliminated'],
                    'is_bye' => false,
                    'is_prelim' => false,
                ];
            }
            $lbRounds[] = $round;
        }

        $this->propagateBracketByes($ubRounds, $lbRounds);

        return [
            'bracket_type' => 'double_elimination',
            'bracket_size' => $bracketSize,
            'total_athletes' => $n,
            'has_preliminary' => false,
            'upper_bracket' => ['rounds' => $ubRounds],
            'lower_bracket' => ['rounds' => $lbRounds],
            'grand_final' => ['athlete1' => null, 'athlete2' => null, 'winner' => null, 'winner_data' => null],
            'juara' => [],
        ];
    }

    private function propagateBracketByes(array &$ubRounds, array &$lbRounds): void
    {
        foreach ($ubRounds as $rIdx => &$round) {
            foreach ($round as $mIdx => &$match) {
                $a1IsBye = isset($match['athlete1']['id']) && $match['athlete1']['id'] === 'BYE';
                $a2IsBye = isset($match['athlete2']['id']) && $match['athlete2']['id'] === 'BYE';

                if ($a1IsBye && $a2IsBye) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'none';
                    $match['winner_data'] = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } elseif ($a1IsBye && $match['athlete2'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete2';
                    $match['winner_data'] = $match['athlete2'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } elseif ($a2IsBye && $match['athlete1'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete1';
                    $match['winner_data'] = $match['athlete1'];
                    $loserData = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                } else {
                    continue;
                }

                if ($match['winner_next']['bracket'] === 'ub') {
                    $wn = $match['winner_next'];
                    $ubRounds[$wn['round']][$wn['match']][$wn['slot']] = $match['winner_data'];
                }

                if ($match['loser_next']['bracket'] === 'lb') {
                    $ln = $match['loser_next'];
                    $lbRounds[$ln['round']][$ln['match']][$ln['slot']] = $loserData;
                }
            }
        }

        $changed = true;
        while ($changed) {
            $changed = false;
            foreach ($lbRounds as $rIdx => &$round) {
                foreach ($round as $mIdx => &$match) {
                    if ($match['is_bye'] || $match['winner'] !== null) {
                        continue;
                    }

                    $a1IsBye = isset($match['athlete1']['id']) && $match['athlete1']['id'] === 'BYE';
                    $a2IsBye = isset($match['athlete2']['id']) && $match['athlete2']['id'] === 'BYE';

                    if ($a1IsBye && $a2IsBye) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'none';
                        $match['winner_data'] = ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-'];
                        $changed = true;
                    } elseif ($a1IsBye && $match['athlete2'] !== null) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'athlete2';
                        $match['winner_data'] = $match['athlete2'];
                        $changed = true;
                    } elseif ($a2IsBye && $match['athlete1'] !== null) {
                        $match['is_bye'] = true;
                        $match['winner'] = 'athlete1';
                        $match['winner_data'] = $match['athlete1'];
                        $changed = true;
                    }

                    if ($match['is_bye'] && $match['winner'] !== null && $match['winner_next']['bracket'] === 'lb') {
                        $wn = $match['winner_next'];
                        $lbRounds[$wn['round']][$wn['match']][$wn['slot']] = $match['winner_data'];
                    }
                }
            }
        }
    }

    private function buildSingleElimination(array $athletes, int $bracketSize): array
    {
        $n = count($athletes);
        $size = 2;
        while ($size < $n) {
            $size *= 2;
        }

        $bracketSize = $size;
        $mainK = (int) log($bracketSize, 2);
        $seedOrder = $this->generateSeedOrder($bracketSize);
        $seedToPos = array_flip($seedOrder);

        $slots = array_fill(0, $bracketSize, ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-']);
        for ($i = 0; $i < $n; $i++) {
            $pos = $seedToPos[$i + 1] ?? $i;
            $slots[$pos] = $athletes[$i];
        }

        $ubRounds = [];
        for ($r = 0; $r < $mainK; $r++) {
            $matchesInRound = $bracketSize / (2 ** ($r + 1));
            $round = [];
            for ($m = 0; $m < $matchesInRound; $m++) {
                $isFinal = ($r === $mainK - 1);
                $isSemi = ($r === $mainK - 2);

                $winnerNext = $isFinal
                    ? ['bracket' => 'ranked', 'rank' => 1]
                    : ['bracket' => 'ub', 'round' => $r + 1, 'match' => intdiv($m, 2), 'slot' => ($m % 2 === 0) ? 'athlete1' : 'athlete2'];

                $loserNext = $isFinal
                    ? ['bracket' => 'ranked', 'rank' => 2]
                    : ($isSemi ? ['bracket' => 'ranked', 'rank' => ($m % 2 === 0) ? 3 : 4] : ['bracket' => 'eliminated']);

                $round[] = ['athlete1' => $r === 0 ? $slots[$m * 2] : null, 'athlete2' => $r === 0 ? $slots[$m * 2 + 1] : null, 'winner' => null, 'winner_data' => null, 'winner_next' => $winnerNext, 'loser_next' => $loserNext, 'is_bye' => false];
            }
            $ubRounds[] = $round;
        }

        foreach ($ubRounds as $rIdx => &$round) {
            foreach ($round as $mIdx => &$match) {
                $a1Bye = isset($match['athlete1']['id']) && $match['athlete1']['id'] === 'BYE';
                $a2Bye = isset($match['athlete2']['id']) && $match['athlete2']['id'] === 'BYE';
                if ($a1Bye && $match['athlete2'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete2';
                    $match['winner_data'] = $match['athlete2'];
                } elseif ($a2Bye && $match['athlete1'] !== null) {
                    $match['is_bye'] = true;
                    $match['winner'] = 'athlete1';
                    $match['winner_data'] = $match['athlete1'];
                }

                if ($match['is_bye'] && isset($match['winner_next']['bracket']) && $match['winner_next']['bracket'] === 'ub') {
                    $wn = $match['winner_next'];
                    $ubRounds[$wn['round']][$wn['match']][$wn['slot']] = $match['winner_data'];
                }
            }
        }

        return ['bracket_size' => $bracketSize, 'upper_bracket' => ['rounds' => $ubRounds], 'lower_bracket' => ['rounds' => []], 'grand_final' => null, 'juara' => []];
    }

    private function generateSeedOrder(int $bracketSize): array
    {
        $order = [1, 2];
        $size = (int) log($bracketSize, 2);
        for ($r = 0; $r < $size - 1; $r++) {
            $newOrder = [];
            $target = count($order) * 2 + 1;
            foreach ($order as $seed) {
                $newOrder[] = $seed;
                $newOrder[] = $target - $seed;
            }
            $order = $newOrder;
        }

        return array_combine(array_keys($order), $order);
    }
}
