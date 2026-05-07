<?php

namespace App\Livewire\Admin;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.premium', ['title' => 'Drawing Technical Meeting'])]
class NewTechnicalMeetingDrawingIndex extends Component
{
    public string $draftType = 'randori';

    public ?int $filterAgeGroupId = null;

    public ?int $filterMatchNumberId = null;

    public bool $isGenerating = false;

    public bool $showEditModal = false;

    public ?int $editPoolId = null;

    public ?int $editCourtId = null;

    public ?int $editSessionId = null;

    public string $editKoorName = '';

    public array $editPaniteraNames = [];

    public string $searchMatchNumber = '';

    public string $searchPanitera = '';

    public function updatedDraftType(): void
    {
        $this->filterAgeGroupId = null;
        $this->filterMatchNumberId = null;
    }

    public function updatedFilterAgeGroupId(): void
    {
        $this->filterMatchNumberId = null;
    }

    public function selectMatch(int $matchId): void
    {
        $this->filterMatchNumberId = $matchId;
    }

    // ── RANDORI ──────────────────────────────────────────────
    public function generateRandoriDrawing(): void
    {
        if (! $this->filterMatchNumberId) {
            return;
        }

        $matchId = $this->filterMatchNumberId;
        $this->isGenerating = true;
        $match = MatchNumber::findOrFail($matchId);

        // 1. Clear existing drawing records
        DrawingMatchNumber::where('match_number_id', $matchId)->delete();

        // 2. Get athletes, spread to avoid same contingent meeting early
        $athletesQuery = DB::table('athlete_match_number')
            ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->where('athlete_match_number.match_number_id', $matchId)
            ->select('athletes.id', 'athletes.name', 'athlete_match_number.registration_id', 'contingents.name as contingent_name')
            ->distinct()
            ->get();

        $totalAthletes = $athletesQuery->count();

        if ($totalAthletes === 0) {
            $this->isGenerating = false;
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Belum Ada Peserta', 'text' => 'Tidak ada atlet yang terdaftar di kelas ini.']);

            return;
        }

        $grouped = $athletesQuery->groupBy('contingent_name');
        $uniqueContingentCount = $grouped->count();

        if ($uniqueContingentCount <= 3) {
            $this->isGenerating = false;
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Minimal Kontingen', 'text' => 'Minimal harus ada 4 kontingen berbeda untuk melakukan drawing. Saat ini hanya ada '.$uniqueContingentCount.' kontingen.']);

            return;
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

        $match->update([
            'drawing_data' => $drawingData,
            'drawing_generated_at' => now(),
        ]);

        // 4. Create DrawingMatchNumber records with auto-scheduling
        $allMatchIds = MatchNumber::where('draft_type', 'randori')->orderBy('id')->pluck('id')->toArray();
        $matchIndex = array_search($matchId, $allMatchIds) ?: 0;
        $courts = Court::orderBy('order')->get();
        $sessions = SessionTime::orderBy('start_time')->get();
        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date', 'asc')->get();
        $court = $courts->get($matchIndex % max(1, $courts->count()));
        $sessionTime = $sessions->get(intdiv($matchIndex, max(1, $courts->count())) % max(1, $sessions->count()));
        $rundown = $rundowns->first();

        $localBusyKoor = [];
        $localBusyPanitera = [];
        $officials = $this->getAvailableOfficials($rundown?->id, $sessionTime?->id, $localBusyKoor, $localBusyPanitera);

        foreach ($athletes as $idx => $athlete) {
            DrawingMatchNumber::create([
                'match_number_id' => $matchId,
                'registration_id' => $athlete['registration_id'],
                'court_id' => $court?->id,
                'session_time_id' => $sessionTime?->id,
                'rundown_id' => $rundown?->id,
                'round' => 'Penyisihan',
                'sequence_number' => $idx + 1,
                'draft_type' => 'randori',
                'metadata' => [
                    'athlete_id' => $athlete['id'],
                    'officials' => $officials,
                ],
            ]);
        }

        $this->isGenerating = false;
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Bagan '.$typeLabel.' Dibuat!',
            'text' => $totalAthletes.' atlet | Slot '.$bracketSize,
        ]);
    }

    // ── EMBU ─────────────────────────────────────────────────
    public function generateEmbuDrawing(): void
    {
        if (! $this->filterMatchNumberId) {
            return;
        }

        $matchId = $this->filterMatchNumberId;
        $this->isGenerating = true;
        $match = MatchNumber::findOrFail($matchId);

        $registrations = DB::table('athlete_match_number')
            ->where('match_number_id', $matchId)
            ->select('registration_id')
            ->distinct()
            ->get()
            ->values();

        $totalEntries = $registrations->count();

        DrawingMatchNumber::where('match_number_id', $matchId)->delete();

        if ($totalEntries === 0) {
            $this->isGenerating = false;
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Belum Ada Peserta', 'text' => 'Tidak ada peserta terdaftar.']);

            return;
        }

        $regContingents = DB::table('registrations')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->whereIn('registrations.id', $registrations->pluck('registration_id'))
            ->pluck('contingents.name', 'registrations.id');

        $uniqueContingentCount = $regContingents->unique()->count();

        if ($uniqueContingentCount < 3) {
            $this->isGenerating = false;
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Minimal Kontingen',
                'text' => "Kelas ini hanya diikuti oleh {$uniqueContingentCount} kontingen. Minimal harus ada 3 kontingen agar dapat dipertandingkan.",
            ]);

            return;
        }

        $entries = $registrations->map(fn ($r) => [
            'registration_id' => $r->registration_id,
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
        $allMatchIds = MatchNumber::where('draft_type', 'embu')->orderBy('id')->pluck('id')->toArray();
        $matchIndex = array_search($matchId, $allMatchIds) ?: 0;

        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date', 'asc')->get();
        $sessions = SessionTime::orderBy('start_time')->get();
        $courts = Court::orderBy('order')->get();

        $courtBaseIdx = $matchIndex % max(1, $courts->count());
        $sessionIdx = intdiv($matchIndex, max(1, $courts->count())) % max(1, $sessions->count());
        $rundown = $rundowns->first();
        $sessionTime = $sessions->get($sessionIdx);

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

            // Distribute courts evenly among pools
            $court = $courts->get(($courtBaseIdx + $poolIdx) % max(1, $courts->count()));
            $courtId = $court ? $court->id : null;

            if (! isset($pools[$poolLabel])) {
                $pools[$poolLabel] = [];
            }

            $orderInPool = count($pools[$poolLabel]) + 1;
            $pools[$poolLabel][] = ['order' => $orderInPool, 'registration_id' => $entry['registration_id'], 'contingent' => $entry['contingent']];

            DrawingMatchNumber::create([
                'match_number_id' => $matchId,
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
                ],
            ]);
        }

        $match->update([
            'drawing_data' => ['total_entries' => $totalEntries, 'format' => $format, 'pool_count' => $poolCount, 'description' => $description, 'pools' => $pools],
            'drawing_generated_at' => now(),
        ]);

        $this->isGenerating = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Drawing Embu Dibuat!', 'text' => $totalEntries.' tim | '.$description]);
    }

    public function resetDrawing(): void
    {
        if (! $this->filterMatchNumberId) {
            return;
        }

        DrawingMatchNumber::where('match_number_id', $this->filterMatchNumberId)->delete();
        MatchNumber::findOrFail($this->filterMatchNumberId)->update(['drawing_data' => null, 'drawing_generated_at' => null]);

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
            if (isset($meta['officials']['panitera'])) {
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
            ->has('athletes')->with(['ageGroup'])->orderBy('name');

        if ($this->searchMatchNumber) {
            $matchNumbersQuery->where('name', 'like', '%'.$this->searchMatchNumber.'%');
        }

        if ($this->filterAgeGroupId) {
            $matchNumbersQuery->where('age_group_id', $this->filterAgeGroupId);
        }

        $filterMatchNumbers = $matchNumbersQuery->get();

        // Get contingent counts for each match number to display in sidebar
        $contingentCounts = DB::table('athlete_match_number')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->whereIn('athlete_match_number.match_number_id', $filterMatchNumbers->pluck('id'))
            ->select('athlete_match_number.match_number_id', DB::raw('count(distinct registrations.contingent_id) as count'))
            ->groupBy('athlete_match_number.match_number_id')
            ->pluck('count', 'match_number_id');

        $filterMatchNumbers->each(function ($mn) use ($contingentCounts) {
            $mn->contingent_count = $contingentCounts[$mn->id] ?? 0;
        });

        $selectedMatch = null;
        $matchAthletes = collect();
        $drawingEntries = collect();

        if ($this->filterMatchNumberId) {
            $selectedMatch = MatchNumber::with(['ageGroup'])->find($this->filterMatchNumberId);

            if ($selectedMatch) {
                $matchAthletes = DB::table('athlete_match_number')
                    ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                    ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                    ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                    ->where('athlete_match_number.match_number_id', $this->filterMatchNumberId)
                    ->select('athletes.id as athlete_id', 'athletes.name as athlete_name', 'contingents.name as contingent_name', 'athlete_match_number.registration_id')
                    ->distinct()->orderBy('contingents.name')->get()->groupBy('contingent_name');

                $drawingEntries = DrawingMatchNumber::where('match_number_id', $this->filterMatchNumberId)
                    ->with('registration.contingent')->orderBy('sequence_number')->get()
                    ->groupBy(function ($item) {
                        return $this->draftType === 'embu' && isset($item->metadata['pool_label'])
                            ? $item->metadata['pool_label']
                            : $item->round;
                    });
            }
        }

        $statsQuery = MatchNumber::where('draft_type', $this->draftType)->has('athletes');
        if ($this->filterAgeGroupId) {
            $statsQuery->where('age_group_id', $this->filterAgeGroupId);
        }

        $allMatches = $statsQuery->get();
        $stats = [
            'total' => $allMatches->count(),
            'drawn' => $allMatches->whereNotNull('drawing_generated_at')->count(),
            'pending' => $allMatches->whereNull('drawing_generated_at')->count(),
        ];

        return view('livewire.admin.new-technical-meeting-drawing-index', [
            'filterAgeGroups' => $filterAgeGroups,
            'filterMatchNumbers' => $filterMatchNumbers,
            'selectedMatch' => $selectedMatch,
            'matchAthletes' => $matchAthletes,
            'drawingEntries' => $drawingEntries,
            'stats' => $stats,
            'courts' => Court::orderBy('order')->get(),
            'sessionTimes' => SessionTime::orderBy('start_time')->get(),
            'koorUsers' => User::role('Koordinator Lapangan')->orderBy('name')->get(),
            'paniteraUsers' => User::role('Panitera')
                ->when($this->searchPanitera, fn ($q) => $q->where('name', 'like', '%'.$this->searchPanitera.'%'))
                ->orderBy('name')->get(),
        ]);
    }

    // ── BRACKET BUILDERS (copied from AdminTechnicalMeetingRandoriIndex) ──

    private function buildDoubleElimination(array $athletes, int $bracketSize): array
    {
        $n = count($athletes);
        $size = 2;
        while ($size < $n) {
            $size *= 2;
        }

        $bracketSize = $size;
        $mainK = (int) log($bracketSize, 2);
        $lbRoundsCount = max(0, 2 * $mainK - 2);

        $seedOrder = $this->generateSeedOrder($bracketSize);
        $seedToPos = array_flip($seedOrder);
        $slots = array_fill(0, $bracketSize, ['id' => 'BYE', 'name' => 'BYE', 'contingent' => '-']);
        for ($i = 0; $i < $n; $i++) {
            $pos = $seedToPos[$i + 1] ?? $i;
            $slots[$pos] = $athletes[$i];
        }

        $lbRounds = [];
        for ($lr = 0; $lr < $lbRoundsCount; $lr++) {
            $lbRounds[$lr] = [];
            $lbMatchCount = $lr % 2 === 0
                ? $bracketSize / (2 ** (($lr / 2) + 2))
                : $bracketSize / (2 ** ((($lr - 1) / 2) + 2));

            for ($m = 0; $m < $lbMatchCount; $m++) {
                $isLBFinal = ($lr === $lbRoundsCount - 1);
                $isLBSemi = ($lr === $lbRoundsCount - 2);

                $winnerNext = $isLBFinal
                    ? ['bracket' => 'gf', 'slot' => 'athlete2']
                    : ['bracket' => 'lb', 'round' => $lr + 1, 'match' => ($lr % 2 === 0) ? $m : (int) ($m / 2), 'slot' => ($lr % 2 === 0) ? 'athlete2' : ($m % 2 === 0 ? 'athlete1' : 'athlete2')];

                $loserNext = ['bracket' => 'eliminated'];
                if ($isLBFinal) {
                    $loserNext = ['bracket' => 'ranked', 'rank' => 3];
                } elseif ($isLBSemi) {
                    $loserNext = ['bracket' => 'ranked', 'rank' => 4];
                }

                $lbRounds[$lr][] = ['athlete1' => null, 'athlete2' => null, 'winner' => null, 'winner_data' => null, 'winner_next' => $winnerNext, 'loser_next' => $loserNext, 'is_bye' => false];
            }
        }

        $ubRounds = [];
        for ($r = 0; $r < $mainK; $r++) {
            $matchCount = $bracketSize / (2 ** ($r + 1));
            $isUBFinal = ($r === $mainK - 1);
            $round = [];

            for ($m = 0; $m < $matchCount; $m++) {
                $a1 = ($r === 0) ? $slots[$m * 2] : null;
                $a2 = ($r === 0) ? $slots[$m * 2 + 1] : null;
                $winnerNext = $isUBFinal
                    ? ['bracket' => 'gf', 'slot' => 'athlete1']
                    : ['bracket' => 'ub', 'round' => $r + 1, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'];

                if ($isUBFinal) {
                    $loserNext = $lbRoundsCount > 0 ? ['bracket' => 'lb', 'round' => $lbRoundsCount - 1, 'match' => 0, 'slot' => 'athlete1'] : ['bracket' => 'gf', 'slot' => 'athlete2'];
                } elseif ($r === 0) {
                    $loserNext = $lbRoundsCount > 0 ? ['bracket' => 'lb', 'round' => 0, 'match' => (int) ($m / 2), 'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2'] : ['bracket' => 'eliminated'];
                } else {
                    $dropMatch = $matchCount - 1 - $m;
                    $loserNext = $lbRoundsCount > 0 ? ['bracket' => 'lb', 'round' => 2 * $r - 1, 'match' => $dropMatch, 'slot' => 'athlete1'] : ['bracket' => 'eliminated'];
                }

                $round[] = ['athlete1' => $a1, 'athlete2' => $a2, 'winner' => null, 'winner_data' => null, 'winner_next' => $winnerNext, 'loser_next' => $loserNext, 'is_bye' => false, 'is_prelim' => false];
            }
            $ubRounds[] = $round;
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
