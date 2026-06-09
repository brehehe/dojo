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

    public string $newKoorName = '';

    public string $newKoorEmail = '';

    public string $newKoorPassword = 'password123';

    public bool $showAddKoorForm = false;

    public string $newPaniteraName = '';

    public string $newPaniteraEmail = '';

    public string $newPaniteraPassword = 'password123';

    public bool $showAddPaniteraForm = false;

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
    public function generateRandoriDrawing(bool $showSwal = true, ?string $onlyRound = null, ?Carbon $minFinalStartTime = null)
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

        if ($onlyRound === 'Final') {
            DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                ->where(function ($query) {
                    $query->where('round', 'Final')
                        ->orWhere('round', 'Grand Final');
                })->delete();
        } else {
            DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->delete();
        }

        $totalAthletes = 0;
        $drawingData = null;

        if ($onlyRound === 'Final') {
            $drawingData = $match->drawing_data;
            if (! $drawingData) {
                $this->isGenerating = false;

                return false;
            }
            $totalAthletes = $drawingData['total_athletes'] ?? 0;
        } else {
            // Get athletes, spread to avoid same contingent meeting early
            $athletesQuery = DB::table('athlete_match_number')
                ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                ->whereIn('athlete_match_number.match_number_id', $matchNumberIds)
                ->where('registrations.status', 'verified')
                ->where('registrations.athlete_status', 'verified')
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
                        'text' => 'Minimal harus ada 3 peserta/entri untuk melakukan drawing. Saat ini hanya ada '.$totalAthletes.' entri dari '.$uniqueContingentCount.' kontingen.',
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

            // Build Elimination structure
            if ($totalAthletes <= 4) {
                $drawingData = $this->buildDoubleElimination($athletes, $bracketSize);
                $typeLabel = 'Double Elimination';
                $drawingData['type'] = 'double_elimination';
            } else {
                $drawingData = $this->buildSingleElimination($athletes, $bracketSize);
                $typeLabel = 'Single Elimination';
                $drawingData['type'] = 'single_elimination';
            }

            // Save total_athletes count to drawing_data
            $drawingData['total_athletes'] = $totalAthletes;

            MatchNumber::whereIn('id', $matchNumberIds)->update([
                'drawing_data' => $drawingData,
                'drawing_generated_at' => now(),
            ]);
        }

        $durationPerMatch = 10;
        $latestPenyisihanEndTime = null;

        // ── PASS 1: PENYISIHAN ───────────────────────────────────
        if ($onlyRound !== 'Final') {
            $prelimMatches = [];

            if (isset($drawingData['upper_bracket']['rounds'])) {
                $totalRounds = count($drawingData['upper_bracket']['rounds']);
                foreach ($drawingData['upper_bracket']['rounds'] as $rIdx => $round) {
                    $isFinalRound = ($rIdx === $totalRounds - 1) && empty($drawingData['grand_final']);
                    if (! $isFinalRound) {
                        foreach ($round as $mIdx => $m) {
                            if (empty($m['is_bye'])) {
                                $prelimMatches[] = [
                                    'round_name' => 'Penyisihan (UB R'.($rIdx + 1).')',
                                    'match' => $m,
                                    'node_key' => 'ub_'.$rIdx.'_'.$mIdx,
                                ];
                            }
                        }
                    }
                }
            }

            if (isset($drawingData['lower_bracket']['rounds'])) {
                foreach ($drawingData['lower_bracket']['rounds'] as $rIdx => $round) {
                    foreach ($round as $mIdx => $m) {
                        if (empty($m['is_bye'])) {
                            $prelimMatches[] = [
                                'round_name' => 'Penyisihan (LB R'.($rIdx + 1).')',
                                'match' => $m,
                                'node_key' => 'lb_'.$rIdx.'_'.$mIdx,
                            ];
                        }
                    }
                }
            }

            if (! empty($prelimMatches)) {
                $matchAthletesGrouped = [];
                foreach ($prelimMatches as $item) {
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

                $slotData = $this->findBestAvailableSlot(count($prelimMatches), $durationPerMatch, null, $matchAthletesGrouped, false, 'randori');
                $court = $slotData['court'];
                $sessionTime = $slotData['session'];
                $rundown = $slotData['rundown'];
                $startTime = $slotData['startTime'];

                $localBusyKoor = [];
                $localBusyPanitera = [];
                $officials = $this->getAvailableOfficials($rundown?->id, $sessionTime?->id, $localBusyKoor, $localBusyPanitera);

                $matchSeq = 1;
                foreach ($prelimMatches as $mIdx => $scheduleItem) {
                    $m = $scheduleItem['match'];
                    $roundName = $scheduleItem['round_name'];
                    $a1 = $m['athlete1'] ?? null;
                    $a2 = $m['athlete2'] ?? null;

                    $slot = $slotData['slots'][$mIdx];
                    $rundown = $slot['rundown'];
                    $sessionTime = $slot['session'];
                    $matchStart = $slot['startTime'];
                    $matchEnd = $matchStart->copy()->addMinutes($durationPerMatch);

                    if (isset($rundown) && isset($matchEnd)) {
                        $rDate = Carbon::parse($rundown->date)->format('Y-m-d');
                        $eTime = Carbon::parse($matchEnd)->format('H:i:s');
                        $thisMatchEnd = Carbon::parse($rDate.' '.$eTime);
                        if ($latestPenyisihanEndTime === null || $thisMatchEnd->gt($latestPenyisihanEndTime)) {
                            $latestPenyisihanEndTime = $thisMatchEnd;
                        }
                    }

                    // Record for Athlete 1
                    DrawingMatchNumber::create([
                        'match_number_id' => $a1['match_number_id'] ?? $match->id,
                        'registration_id' => $a1['registration_id'] ?? null,
                        'court_id' => $court?->id,
                        'session_time_id' => $sessionTime?->id,
                        'rundown_id' => $rundown?->id,
                        'schedule_date' => $rundown?->date,
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
                        'schedule_date' => $rundown?->date,
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
                }
            }
        }

        // ── PASS 2: FINAL ────────────────────────────────────────
        if ($onlyRound !== 'Penyisihan') {
            $finalMatches = [];

            if (isset($drawingData['upper_bracket']['rounds'])) {
                $totalRounds = count($drawingData['upper_bracket']['rounds']);
                foreach ($drawingData['upper_bracket']['rounds'] as $rIdx => $round) {
                    $isFinalRound = ($rIdx === $totalRounds - 1) && empty($drawingData['grand_final']);
                    if ($isFinalRound) {
                        foreach ($round as $mIdx => $m) {
                            if (empty($m['is_bye'])) {
                                $finalMatches[] = [
                                    'round_name' => 'Final',
                                    'match' => $m,
                                    'node_key' => 'ub_'.$rIdx.'_'.$mIdx,
                                ];
                            }
                        }
                    }
                }
            }

            if (! empty($drawingData['grand_final']) && empty($drawingData['grand_final']['is_bye'])) {
                $finalMatches[] = [
                    'round_name' => 'Grand Final',
                    'match' => $drawingData['grand_final'],
                    'node_key' => 'gf_0_0',
                ];
            }

            if (! empty($finalMatches)) {
                $matchAthletesGrouped = [];
                foreach ($finalMatches as $item) {
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

                // If not passed via parameters, calculate minFinalStartTime dynamically from latest Penyisihan end time
                if ($onlyRound !== 'Final' && $latestPenyisihanEndTime !== null) {
                    $minFinalStartTime = $this->calculateMinFinalStartTime($latestPenyisihanEndTime);
                }

                $slotData = $this->findBestAvailableSlot(count($finalMatches), $durationPerMatch, $minFinalStartTime, $matchAthletesGrouped, true, 'randori');
                $court = $slotData['court'];
                $sessionTime = $slotData['session'];
                $rundown = $slotData['rundown'];
                $startTime = $slotData['startTime'];

                $localBusyKoor = [];
                $localBusyPanitera = [];
                $officials = $this->getAvailableOfficials($rundown?->id, $sessionTime?->id, $localBusyKoor, $localBusyPanitera);

                $startSeq = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                    ->max('sequence_number') ?? 0;
                $matchSeq = $startSeq + 1;

                foreach ($finalMatches as $mIdx => $scheduleItem) {
                    $m = $scheduleItem['match'];
                    $roundName = $scheduleItem['round_name'];
                    $a1 = $m['athlete1'] ?? null;
                    $a2 = $m['athlete2'] ?? null;

                    $slot = $slotData['slots'][$mIdx];
                    $rundown = $slot['rundown'];
                    $sessionTime = $slot['session'];
                    $matchStart = $slot['startTime'];
                    $matchEnd = $matchStart->copy()->addMinutes($durationPerMatch);

                    // Record for Athlete 1
                    DrawingMatchNumber::create([
                        'match_number_id' => $a1['match_number_id'] ?? $match->id,
                        'registration_id' => $a1['registration_id'] ?? null,
                        'court_id' => $court?->id,
                        'session_time_id' => $sessionTime?->id,
                        'rundown_id' => $rundown?->id,
                        'schedule_date' => $rundown?->date,
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
                        'schedule_date' => $rundown?->date,
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
                }
            }
        }

        $this->isGenerating = false;
        if ($showSwal) {
            $typeLabel = ($onlyRound === 'Final') ? 'Final' : (($onlyRound === 'Penyisihan') ? 'Penyisihan' : 'Semua');
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Bagan Randori Dibuat!',
                'text' => $totalAthletes.' atlet | Babak '.$typeLabel,
            ]);
        }

        return true;
    }

    // ── GENERATE ALL ─────────────────────────────────────────
    public function generateAllDrawings(?string $type = null): void
    {
        set_time_limit(0);
        $this->isGenerating = true;

        $targetType = $type ?: $this->draftType; // 'embu' or 'randori'

        // 1. Fetch merges
        $merges = MatchNumberMerge::where('type', $targetType)
            ->whereHas('matchNumbers', function ($q) {
                $q->whereNull('drawing_generated_at');
            })
            ->get();

        // Filter merges to only those that have athletes across their constituent match numbers
        $merges = $merges->filter(function ($merge) {
            return DB::table('athlete_match_number')
                ->whereIn('match_number_id', $merge->matchNumbers->pluck('id'))
                ->exists();
        });

        // 2. Fetch standard matches (excluding merged ones)
        $standardMatches = MatchNumber::whereNull('drawing_generated_at')
            ->where('draft_type', $targetType)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('match_number_merge_details')
                    ->whereColumn('match_number_merge_details.match_number_id', 'match_numbers.id');
            })
            ->has('athletes')
            ->get();

        // 3. Map to uniform structure
        $items = collect();

        foreach ($merges as $merge) {
            $items->push((object) [
                'is_merge' => true,
                'id' => $merge->id,
                'age_group_name' => $merge->ageGroup->name ?? '',
                'name' => $merge->name ?: 'Merged Group',
                'draft_type' => $merge->type,
                'model' => $merge,
            ]);
        }

        foreach ($standardMatches as $match) {
            $items->push((object) [
                'is_merge' => false,
                'id' => $match->id,
                'age_group_name' => $match->ageGroup->name ?? '',
                'name' => $match->name,
                'draft_type' => $match->draft_type,
                'model' => $match,
            ]);
        }

        // 4. Sort the items
        $items = $items->sort(function ($a, $b) {
            $ageOrder = [
                'Pemula' => 0,
                'Remaja A' => 1,
                'Remaja B' => 2,
                'Dewasa' => 3,
            ];
            $aAge = $ageOrder[$a->age_group_name] ?? 99;
            $bAge = $ageOrder[$b->age_group_name] ?? 99;
            if ($aAge !== $bAge) {
                return $aAge <=> $bAge;
            }

            if ($a->draft_type === 'embu') {
                $aSub = str_contains($a->name, 'Tandoku') ? 0 : (str_contains($a->name, 'Pasangan') ? 1 : (str_contains($a->name, 'Beregu') ? 2 : 3));
                $bSub = str_contains($b->name, 'Tandoku') ? 0 : (str_contains($b->name, 'Pasangan') ? 1 : (str_contains($b->name, 'Beregu') ? 2 : 3));
                if ($aSub !== $bSub) {
                    return $aSub <=> $bSub;
                }
            }

            return $a->id <=> $b->id;
        });

        $success = 0;
        $skipped = 0;

        // Pass 1: Generate Penyisihan
        foreach ($items as $item) {
            if ($item->is_merge) {
                $this->filterMergeId = $item->id;
                $this->filterMatchNumberId = null;
            } else {
                $this->filterMergeId = null;
                $this->filterMatchNumberId = $item->id;
            }

            try {
                if ($targetType === 'embu') {
                    $result = $this->generateEmbuDrawing(false, 'Penyisihan');
                } else {
                    $result = $this->generateRandoriDrawing(false, 'Penyisihan');
                }
                if ($result === false) {
                    $skipped++;
                } else {
                    $success++;
                }
            } catch (\Throwable $e) {
                $skipped++;
                logger()->error('Failed generating Penyisihan for '.($item->is_merge ? 'Merge' : 'Match').' '.$item->id.': '.$e->getMessage());
            }
        }

        // Pass 2: Generate Final
        foreach ($items as $item) {
            if ($item->is_merge) {
                $this->filterMergeId = $item->id;
                $this->filterMatchNumberId = null;
                $matchIds = $item->model->matchNumbers->pluck('id')->toArray();
            } else {
                $this->filterMergeId = null;
                $this->filterMatchNumberId = $item->id;
                $matchIds = [$item->id];
            }

            try {
                $latest = $this->getLatestPrelimEndTime($matchIds);
                $minFinalStartTime = $this->calculateMinFinalStartTime($latest);

                if ($targetType === 'embu') {
                    $this->generateEmbuDrawing(false, 'Final', $minFinalStartTime);
                } else {
                    $this->generateRandoriDrawing(false, 'Final', $minFinalStartTime);
                }
            } catch (\Throwable $e) {
                logger()->error('Failed generating Final for '.($item->is_merge ? 'Merge' : 'Match').' '.$item->id.': '.$e->getMessage());
            }
        }

        $this->filterMergeId = null;
        $this->filterMatchNumberId = null;
        $this->isGenerating = false;

        $typeLabel = $targetType === 'embu' ? 'Embu' : 'Randori';
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Generate Selesai',
            'text' => $success.' kategori '.$typeLabel.' berhasil di-generate (Penyisihan lalu Final secara otomatis).',
        ]);
    }

    private function getLatestPrelimEndTime(array $matchNumberIds): ?Carbon
    {
        $scheduledPrelims = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
            ->where(function ($q) {
                $q->where('round', 'Penyisihan')
                    ->orWhere('round', 'like', 'Penyisihan%');
            })
            ->get();

        $latestEndTime = null;
        foreach ($scheduledPrelims as $item) {
            $meta = $item->metadata ?? [];
            if (empty($meta['end_time']) || empty($item->schedule_date)) {
                continue;
            }
            $dateStr = Carbon::parse($item->schedule_date)->format('Y-m-d');
            $endTimeStr = $dateStr.' '.$meta['end_time'].':00';
            $endTime = Carbon::parse($endTimeStr);
            if ($latestEndTime === null || $endTime->gt($latestEndTime)) {
                $latestEndTime = $endTime;
            }
        }

        return $latestEndTime;
    }

    private function calculateMinFinalStartTime(?Carbon $latestPrelimEndTime): ?Carbon
    {
        if ($latestPrelimEndTime === null) {
            return null;
        }
        $earliestFinalTime = $latestPrelimEndTime->copy()->addMinutes(15);
        $minute = (int) $earliestFinalTime->format('i');
        $remainder = $minute % 10;
        if ($remainder !== 0) {
            $earliestFinalTime->addMinutes(10 - $remainder);
        }

        return $earliestFinalTime;
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
    public function generateEmbuDrawing(bool $showSwal = true, ?string $onlyRound = null, ?Carbon $minFinalStartTime = null)
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
            $match = $merge->matchNumbers->first();
            $matchId = $match->id;
        } else {
            $matchId = $this->filterMatchNumberId;
            $match = MatchNumber::findOrFail($matchId);
            $matchNumberIds = [$matchId];
        }

        // Fetch all merge match numbers to know their max_athletes
        $matchNumbers = MatchNumber::whereIn('id', $matchNumberIds)->get();

        // Get all registrations across all included match numbers
        $registrationsQuery = DB::table('athlete_match_number')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->where('registrations.status', 'verified')
            ->where('registrations.athlete_status', 'verified')
            ->whereIn('match_number_id', $matchNumberIds)
            ->select('athlete_match_number.registration_id', 'athlete_match_number.match_number_id', DB::raw('count(*) as athlete_count'))
            ->groupBy('athlete_match_number.registration_id', 'athlete_match_number.match_number_id')
            ->get();

        $allEntries = collect();
        foreach ($registrationsQuery as $reg) {
            $athleteIds = DB::table('athlete_match_number')
                ->where('match_number_id', $reg->match_number_id)
                ->where('registration_id', $reg->registration_id)
                ->orderBy('id')
                ->pluck('athlete_id');

            $matchObj = $matchNumbers->firstWhere('id', $reg->match_number_id);
            $currentMax = $matchObj->max_athletes ?? 1;

            $chunks = $athleteIds->chunk($currentMax);

            foreach ($chunks as $chunk) {
                $allEntries->push((object) [
                    'registration_id' => $reg->registration_id,
                    'match_number_id' => $reg->match_number_id,
                    'athlete_ids' => $chunk->toArray(),
                ]);
            }
        }

        $totalEntries = $allEntries->count();

        if ($onlyRound === 'Final') {
            DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->where('round', 'Final')->delete();
        } else {
            DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->delete();
        }

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
            'athlete_name' => DB::table('athletes')->whereIn('id', $r->athlete_ids)->pluck('name')->join(', ') ?: 'TBD',
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

        $durationPerMatch = 10;
        $latestPenyisihanEndTime = null;

        if ($onlyRound !== 'Final') {
            // Pass 1: Penyisihan
            $entriesByPool = [];
            for ($i = 0; $i < $poolCount; $i++) {
                $pool = $poolRecords[$i];
                $entriesByPool[$pool->name] = [
                    'pool' => $pool,
                    'entries' => [],
                ];
            }

            foreach ($entries as $index => $entry) {
                $poolIdx = ($format === '2_babak') ? 0 : ($index % $poolCount);
                $pool = $poolRecords[$poolIdx];
                $entriesByPool[$pool->name]['entries'][] = $entry;
            }

            $localBusyKoor = [];
            $localBusyPanitera = [];
            $poolOfficialsCache = [];
            $globalIndex = 0;

            foreach ($entriesByPool as $poolName => $poolData) {
                $pool = $poolData['pool'];
                $poolEntries = $poolData['entries'];
                $poolEntryCount = count($poolEntries);

                if ($poolEntryCount === 0) {
                    continue;
                }

                $poolMatchAthletesGrouped = [];
                foreach ($poolEntries as $entry) {
                    $poolMatchAthletesGrouped[] = $entry['athlete_ids'] ?? [];
                }

                $slotData = $this->findBestAvailableSlot($poolEntryCount, $durationPerMatch, null, $poolMatchAthletesGrouped, false, 'embu');

                $court = $slotData['court'];
                $sessionTime = $slotData['session'];
                $rundown = $slotData['rundown'];
                $startTime = $slotData['startTime'];
                $courtId = $court ? $court->id : null;

                if (! isset($poolOfficialsCache[$poolName])) {
                    $poolOfficialsCache[$poolName] = $this->getAvailableOfficials($rundown?->id, $sessionTime?->id, $localBusyKoor, $localBusyPanitera);
                }
                $officials = $poolOfficialsCache[$poolName];

                if (! isset($pools[$poolName])) {
                    $pools[$poolName] = [];
                }

                foreach ($poolEntries as $index => $entry) {
                    $globalIndex++;
                    $orderInPool = count($pools[$poolName]) + 1;

                    $slot = $slotData['slots'][$index];
                    $rundown = $slot['rundown'];
                    $sessionTime = $slot['session'];
                    $entryStart = $slot['startTime'];
                    $entryEnd = $entryStart->copy()->addMinutes($durationPerMatch);

                    if (isset($rundown) && isset($entryEnd)) {
                        $rDate = Carbon::parse($rundown->date)->format('Y-m-d');
                        $eTime = Carbon::parse($entryEnd)->format('H:i:s');
                        $thisPoolEnd = Carbon::parse($rDate.' '.$eTime);
                        if ($latestPenyisihanEndTime === null || $thisPoolEnd->gt($latestPenyisihanEndTime)) {
                            $latestPenyisihanEndTime = $thisPoolEnd;
                        }
                    }

                    $pools[$poolName][] = [
                        'order' => $orderInPool,
                        'registration_id' => $entry['registration_id'],
                        'contingent' => $entry['contingent'],
                    ];

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
                            'pool_label' => $poolName,
                            'officials' => $officials,
                            'match_id_code' => $this->getMatchIdCode($match, $globalIndex),
                            'start_time' => $entryStart->format('H:i'),
                            'end_time' => $entryEnd->format('H:i'),
                            'duration' => $durationPerMatch,
                            'athlete_name' => $entry['athlete_name'] ?? 'TBD',
                            'athlete_ids' => $entry['athlete_ids'] ?? [],
                            'contingent' => $entry['contingent'] ?? 'TBD',
                            'merge_id' => $this->filterMergeId,
                        ],
                    ]);
                }
            }

            MatchNumber::whereIn('id', $matchNumberIds)->update([
                'drawing_data' => ['total_entries' => $totalEntries, 'format' => $format, 'pool_count' => $poolCount, 'description' => $description, 'pools' => $pools],
                'drawing_generated_at' => now(),
            ]);
        }

        if ($onlyRound !== 'Penyisihan') {
            if ($onlyRound === 'Final') {
                $savedDrawingData = $match->drawing_data;
                $pools = $savedDrawingData['pools'] ?? [];
            }

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
                if ($onlyRound !== 'Final' && $latestPenyisihanEndTime !== null) {
                    $minFinalStartTime = $this->calculateMinFinalStartTime($latestPenyisihanEndTime);
                }

                $finalSlotData = $this->findBestAvailableSlot($totalFinalists, $durationPerMatch, $minFinalStartTime, [], true, 'embu');
                $fCourtId = $finalSlotData['court'] ? $finalSlotData['court']->id : null;
                $fStartTime = $finalSlotData['startTime'];

                for ($i = 0; $i < $totalFinalists; $i++) {
                    $slot = $finalSlotData['slots'][$i];
                    $rundown = $slot['rundown'];
                    $sessionTime = $slot['session'];
                    $fStart = $slot['startTime'];
                    $fEnd = $fStart->copy()->addMinutes($durationPerMatch);

                    DrawingMatchNumber::create([
                        'match_number_id' => $matchId,
                        'registration_id' => null, // TBD
                        'pool_id' => null, // Final pool
                        'court_id' => $fCourtId,
                        'schedule_date' => $rundown?->date,
                        'session_time_id' => $sessionTime?->id,
                        'rundown_id' => $rundown?->id,
                        'round' => 'Final',
                        'sequence_number' => $i + 1,
                        'draft_type' => 'embu',
                        'metadata' => [
                            'pool_label' => 'FINAL',
                            'officials' => [],
                            'match_id_code' => $this->getMatchIdCode($match, $totalEntries + $i + 1),
                            'start_time' => $fStart->format('H:i'),
                            'end_time' => $fEnd->format('H:i'),
                            'duration' => $durationPerMatch,
                            'contingent' => 'Lolos Final',
                            'athlete_name' => 'TBD',
                        ],
                    ]);
                }
            }
        }

        $this->isGenerating = false;
        if ($showSwal) {
            $typeLabel = ($onlyRound === 'Final') ? 'Final' : (($onlyRound === 'Penyisihan') ? 'Penyisihan' : 'Semua');
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Drawing Embu Dibuat!', 'text' => $totalEntries.' tim | '.$description]);
        }

        return true;
    }

    private function getAthleteIdsForRegistrations(array $registrationIds): array
    {
        return DB::table('registration_athlete')
            ->whereIn('registration_id', $registrationIds)
            ->pluck('athlete_id')
            ->unique()
            ->toArray();
    }

    private function findBestAvailableSlot(int $neededCount, int $durationMinutes = 10, ?Carbon $minStartTime = null, array $matchAthletesGrouped = [], bool $isFinal = false, string $draftType = 'randori'): array
    {
        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date')->orderBy('order')->get();
        $sessions = SessionTime::orderBy('start_time')->get();
        $courts = Court::orderBy('order')->get();

        // Prioritize Embu on the first day. If this is a Randori match and there are still unscheduled Embu matches,
        // we avoid scheduling it on the first day if we have other days available.
        if ($draftType === 'randori' && $rundowns->count() > 1) {
            $hasUnscheduledEmbu = MatchNumber::where('draft_type', 'embu')
                ->has('athletes')
                ->whereNull('drawing_generated_at')
                ->exists();

            if ($hasUnscheduledEmbu) {
                $firstDate = $rundowns->first()->date;
                $laterRundowns = $rundowns->filter(fn ($r) => $r->date > $firstDate);
                if ($laterRundowns->isNotEmpty()) {
                    $rundowns = $laterRundowns;
                }
            }
        }

        // If it's a final, and we have multiple days, try to jump to Day 2+ immediately to avoid same-day fatigue (only for randori)
        if ($isFinal && $draftType === 'randori' && $rundowns->count() > 1) {
            $firstDate = $rundowns->first()->date;
            $laterRundowns = $rundowns->filter(fn ($r) => $r->date > $firstDate);
            if ($laterRundowns->isNotEmpty()) {
                $rundowns = $laterRundowns;
            }
        }

        // Eager load existing drawings across all sessions/rundowns once to avoid N+1 queries.
        $existingDrawings = DrawingMatchNumber::with('registration.athletes')->get();
        $busyAthletes = [];
        foreach ($existingDrawings as $d) {
            $m = $d->metadata ?? [];
            if (! isset($m['start_time']) || ! isset($m['end_time']) || ! $d->schedule_date) {
                continue;
            }

            $rDate = Carbon::parse($d->schedule_date)->format('Y-m-d');
            $start = Carbon::parse($rDate.' '.Carbon::parse($m['start_time'])->format('H:i:s'));
            $end = Carbon::parse($rDate.' '.Carbon::parse($m['end_time'])->format('H:i:s'));
            $athletes = $d->registration ? $d->registration->athletes->pluck('id')->toArray() : [];
            foreach ($athletes as $aId) {
                $busyAthletes[$aId][] = ['start' => $start, 'end' => $end];
            }
        }

        $bestCourt = null;
        $bestSlots = null;
        $bestEndTime = null;

        foreach ($courts as $court) {
            $slots = [];

            // For this court, search through rundowns and sessions chronologically
            foreach ($rundowns as $rundown) {
                $rDate = Carbon::parse($rundown->date)->format('Y-m-d');
                foreach ($sessions as $session) {
                    $sStartStr = Carbon::parse($session->start_time)->format('H:i:s');
                    $sEndStr = Carbon::parse($session->end_time)->format('H:i:s');

                    $sessionStart = Carbon::parse($rDate.' '.$sStartStr);
                    $sessionEnd = Carbon::parse($rDate.' '.$sEndStr);

                    $currentTime = $sessionStart->copy();

                    if ($minStartTime && $currentTime->lt($minStartTime)) {
                        $currentTime = $minStartTime->copy();
                    }

                    while ($currentTime->copy()->addMinutes($durationMinutes)->lte($sessionEnd)) {
                        $slotEnd = $currentTime->copy()->addMinutes($durationMinutes);

                        // Check if this court is occupied at this specific currentTime in the DB
                        $isCourtOccupied = false;
                        $courtMatches = $existingDrawings->where('court_id', $court->id)
                            ->where('rundown_id', $rundown->id)
                            ->where('session_time_id', $session->id);
                        foreach ($courtMatches as $cm) {
                            $m = $cm->metadata ?? [];
                            if (! isset($m['start_time']) || ! isset($m['end_time'])) {
                                continue;
                            }

                            $cmStart = Carbon::parse($rDate.' '.Carbon::parse($m['start_time'])->format('H:i:s'));
                            $cmEnd = Carbon::parse($rDate.' '.Carbon::parse($m['end_time'])->format('H:i:s'));

                            if ($currentTime->lt($cmEnd) && $slotEnd->gt($cmStart)) {
                                $isCourtOccupied = true;
                                break;
                            }
                        }

                        if ($isCourtOccupied) {
                            $currentTime->addMinutes($durationMinutes);

                            continue;
                        }

                        // Check athlete clashes
                        $hasAthleteClash = false;
                        $athletesInSlot = $matchAthletesGrouped[count($slots)] ?? [];
                        foreach ($athletesInSlot as $aId) {
                            if (isset($busyAthletes[$aId])) {
                                foreach ($busyAthletes[$aId] as $interval) {
                                    if ($currentTime->lt($interval['end']) && $slotEnd->gt($interval['start'])) {
                                        $hasAthleteClash = true;
                                        break 2;
                                    }
                                }
                            }
                        }

                        if ($hasAthleteClash) {
                            $currentTime->addMinutes($durationMinutes);

                            continue;
                        }

                        // Slot is available!
                        $slots[] = [
                            'rundown' => $rundown,
                            'session' => $session,
                            'startTime' => $currentTime->copy(),
                        ];

                        if (count($slots) === $neededCount) {
                            break 3; // Found all slots for this court
                        }

                        $currentTime->addMinutes($durationMinutes);
                    }
                }
            }

            if (count($slots) === $neededCount) {
                // Calculate end time of the last slot on this day/rundown to compare
                $lastSlot = $slots[count($slots) - 1];
                $lastEnd = $lastSlot['startTime']->copy()->addMinutes($durationMinutes);

                // We want the court that completes the series earliest
                if ($bestEndTime === null || $lastEnd->lt($bestEndTime)) {
                    $bestEndTime = $lastEnd;
                    $bestSlots = $slots;
                    $bestCourt = $court;
                }
            }
        }

        // Ultimate fallback
        if (! $bestCourt) {
            $bestCourt = $courts->first() ?: Court::first();
            $bestSlots = [];
            $currentRundown = $rundowns->first() ?: Rundown::first();
            $start = $minStartTime ?? Carbon::parse(Carbon::parse($currentRundown->date)->format('Y-m-d').' 07:30:00');

            // Find session matching the start time
            $currentSession = $sessions->first(function ($s) use ($start) {
                $sStart = Carbon::parse($start->format('Y-m-d').' '.$s->start_time);
                $sEnd = Carbon::parse($start->format('Y-m-d').' '.$s->end_time);

                return $start->gte($sStart) && $start->lt($sEnd);
            }) ?: ($sessions->last() ?: SessionTime::first());

            for ($i = 0; $i < $neededCount; $i++) {
                $bestSlots[] = [
                    'rundown' => $currentRundown,
                    'session' => $currentSession,
                    'startTime' => $start->copy()->addMinutes($i * $durationMinutes),
                ];
            }
        }

        return [
            'court' => $bestCourt,
            'slots' => $bestSlots,
            'session' => $bestSlots[0]['session'],
            'rundown' => $bestSlots[0]['rundown'],
            'startTime' => $bestSlots[0]['startTime'],
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
    }

    public function openEditModal(?int $poolId = null): void
    {
        $this->editPoolId = $poolId;

        $this->showAddKoorForm = false;
        $this->showAddPaniteraForm = false;
        $this->newKoorName = '';
        $this->newKoorEmail = '';
        $this->newPaniteraName = '';
        $this->newPaniteraEmail = '';

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

    public function addKoorUser(): void
    {
        $this->validate([
            'newKoorName' => 'required|string|max:255',
            'newKoorEmail' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $this->newKoorName,
            'email' => $this->newKoorEmail,
            'password' => Hash::make($this->newKoorPassword),
        ]);
        $user->assignRole('Koordinator Lapangan');

        $this->editKoorName = $user->name;

        $this->newKoorName = '';
        $this->newKoorEmail = '';
        $this->showAddKoorForm = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Koordinator Lapangan baru berhasil ditambahkan.',
        ]);
    }

    public function addPaniteraUser(): void
    {
        $this->validate([
            'newPaniteraName' => 'required|string|max:255',
            'newPaniteraEmail' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $this->newPaniteraName,
            'email' => $this->newPaniteraEmail,
            'password' => Hash::make($this->newPaniteraPassword),
        ]);
        $user->assignRole('Panitera');

        $this->editPaniteraNames[] = $user->name;

        $this->newPaniteraName = '';
        $this->newPaniteraEmail = '';
        $this->showAddPaniteraForm = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Panitera baru berhasil ditambahkan.',
        ]);
    }

    // ── RENDER ───────────────────────────────────────────────
    public function render()
    {
        $likeOperator = DB::connection()->getDriverName() === 'sqlite' ? 'like' : 'ilike';

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
            $matchNumbersQuery->where('name', $likeOperator, '%'.$this->searchMatchNumber.'%');
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
            $mergeQuery->where('name', $likeOperator, '%'.$this->searchMatchNumber.'%');
        }
        $filterMerges = $mergeQuery->get()->map(function ($m) {
            $mergedNames = $m->matchNumbers->pluck('name')->join(', ');
            $m->display_name = ($m->name ?: 'Merged Group').' ('.$mergedNames.')';

            return $m;
        });

        // Get entry counts for each match number to display in sidebar
        $rawAthleteCounts = DB::table('athlete_match_number')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->where('registrations.status', 'verified')
            ->where('registrations.athlete_status', 'verified')
            ->whereIn('match_number_id', $filterMatchNumbers->pluck('id'))
            ->select('athlete_match_number.match_number_id', DB::raw('count(*) as count'))
            ->groupBy('athlete_match_number.match_number_id')
            ->pluck('count', 'athlete_match_number.match_number_id');

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
                $selectedMatch->display_name = ($merge->name ?: 'Merged Group').' ('.$mergedNames.')';

                $rawAthletes = DB::table('athlete_match_number')
                    ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                    ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                    ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                    ->whereIn('athlete_match_number.match_number_id', $matchNumberIds)
                    ->where('registrations.status', 'verified')
                    ->where('registrations.athlete_status', 'verified')
                    ->select(
                        'athletes.name as athlete_name',
                        'contingents.name as contingent_name',
                        'athlete_match_number.registration_id',
                        'athlete_match_number.match_number_id'
                    )
                    ->orderBy('contingents.name')
                    ->orderBy('athlete_match_number.id')
                    ->get();

                $entryData = [];
                foreach ($rawAthletes->groupBy(['registration_id', 'match_number_id']) as $regId => $byMatch) {
                    foreach ($byMatch as $matchId => $regAthletes) {
                        $matchObj = $merge->matchNumbers->firstWhere('id', $matchId);
                        $currentMax = $matchObj->max_athletes ?? 1;

                        $chunks = $regAthletes->chunk($currentMax);
                        foreach ($chunks as $chunkIndex => $chunk) {
                            $entryKey = $regId.'_'.$matchId.'_'.$chunkIndex;
                            $entryData[$entryKey] = $chunk;
                        }
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
                    ->where('registrations.status', 'verified')
                    ->where('registrations.athlete_status', 'verified')
                    ->select('athletes.name as athlete_name', 'contingents.name as contingent_name', 'athlete_match_number.registration_id')
                    ->orderBy('contingents.name')
                    ->orderBy('athlete_match_number.id')
                    ->get();

                $entryData = [];
                foreach ($rawAthletes->groupBy('registration_id') as $regId => $regAthletes) {
                    $chunks = $regAthletes->chunk($maxAthletes);
                    foreach ($chunks as $chunkIndex => $chunk) {
                        $entryKey = $regId.'_'.$chunkIndex;
                        $entryData[$entryKey] = $chunk;
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
            $allDrawings = DrawingMatchNumber::with(['matchNumber.ageGroup', 'registration.contingent', 'court', 'sessionTime', 'rundown', 'merge.ageGroup'])
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
            'paniteraUsers' => User::role('Panitera')->where('name', $likeOperator, '%'.$this->searchPanitera.'%')->orderBy('name')->get(),
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
