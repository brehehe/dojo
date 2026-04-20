<?php

namespace App\Livewire\Admin\TechnicalMeeting\Embu;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Pool\Pool;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminTechnicalMeetingEmbuIndex extends Component
{
    use WithPagination;

    public ?int $selectedCourtId = null;

    public string $globalTab = 'sebelum';

    public ?int $generatingMatchId = null;

    public ?int $finalMatchId = null;
    public ?int $finalCourtId = null;
    public ?int $finalRundownId = null;
    public ?int $finalSessionTimeId = null;
    public bool $isGeneratingFinal = false;

    public function paginationView(): string
    {
        return 'livewire.admin.pagination';
    }

    // ──────────────────────────────────────────────────
    // DRAWING GENERATION
    // ──────────────────────────────────────────────────

    /**
     * Generate drawing for a specific match number.
     * Rules (THB Pasal H):
     *  ≤ 9 entries → 2 Babak (Penyisihan + Final), no pools
     *  ≥ 10 entries → Pool system:
     *   2 pools (10-11): rank 1,2,3,4 per pool → 8 finalists
     *   3 pools (12-17): rank 1,2,3 per pool   → 9 finalists
     *   4 pools (≥18):   rank 1,2 per pool     → 8 finalists
     */
    public function generateDrawing(int $matchId, string $round = 'Penyisihan'): void
    {
        $this->generatingMatchId = $matchId;

        $match = MatchNumber::findOrFail($matchId);

        // 1. Clear existing drawing records for this match and round
        DrawingMatchNumber::where('match_number_id', $matchId)
            ->where('round', $round)
            ->delete();

        // 2. Get all distinct registration_ids (each = one team/contingent entry)
        $registrations = DB::table('athlete_match_number')
            ->where('match_number_id', $matchId)
            ->select('registration_id')
            ->distinct()
            ->get()
            ->values();

        $totalEntries = $registrations->count();

        // Get contingent names per registration
        $regContingents = DB::table('registrations')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->whereIn('registrations.id', $registrations->pluck('registration_id'))
            ->pluck('contingents.name', 'registrations.id');

        // Build entry list with contingent name
        $entries = $registrations->map(function ($reg) use ($regContingents) {
            return [
                'registration_id' => $reg->registration_id,
                'contingent' => $regContingents[$reg->registration_id] ?? 'Unknown',
            ];
        })->values()->toArray();

        // Shuffle for random drawing
        shuffle($entries);

        // 3. Determine format and pool records based on THB Pasal H
        if ($totalEntries <= 9) {
            $format = '2_babak';
            $poolCount = 1;
            $qualifiers = 0; // all go straight to final
            $description = '2 Babak (Penyisihan + Final) — Nilai digabung & dirata-rata';
            $poolRecords = [
                Pool::firstOrCreate(
                    ['name' => 'POOL 1'],
                    ['order' => 1]
                ),
            ];
        } else {
            $format = 'pool';
            if ($totalEntries <= 11) {
                $poolCount = 2;
                $qualifiers = 4; // top 4 per pool
                $description = 'Sistem Pool (2 Pool) — Rank 1,2,3,4 per pool → 8 Finalis';
            } elseif ($totalEntries <= 17) {
                $poolCount = 3;
                $qualifiers = 3; // top 3 per pool
                $description = 'Sistem Pool (3 Pool) — Rank 1,2,3 per pool → 9 Finalis';
            } else {
                $poolCount = 4;
                $qualifiers = 2; // top 2 per pool
                $description = 'Sistem Pool (4 Pool) — Rank 1,2 per pool → 8 Finalis';
            }

            $poolLabels = ['A', 'B', 'C', 'D'];
            $poolRecords = [];
            for ($i = 0; $i < $poolCount; $i++) {
                $poolRecords[] = Pool::firstOrCreate([
                    'name' => 'POOL '.$poolLabels[$i],
                ], [
                    'order' => $i + 1,
                ]);
            }
        }

        // --- AUTO-SCHEDULER LOGIC (CLASH-FREE ROUND-ROBIN) ---
        $allMatchIds = MatchNumber::where('draft_type', 'embu')->orderBy('id')->pluck('id')->toArray();
        $matchIndex = array_search($matchId, $allMatchIds);
        if ($matchIndex === false) {
            $matchIndex = 0;
        }

        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date', 'asc')->get();
        $sessions = SessionTime::orderBy('start_time')->get();
        $courts = Court::orderBy('order')->get();

        $rCount = $rundowns->count() ?: 1;
        $sCount = $sessions->count() ?: 1;
        $cCount = $courts->count() ?: 1;

        // Spread logic: Courts -> Sessions -> (Pin to First Rundown)
        $courtBaseIdx = $matchIndex % $cCount;
        $sessionIdx = intval($matchIndex / $cCount) % $sCount;
        $rundownIdx = 0; // Forced to first competition date

        $rundown = $rundowns->get($rundownIdx);
        $sessionTime = $sessions->get($sessionIdx);

        $rundownDate = $rundown?->date;
        $rundownId = $rundown?->id;
        $sessionTimeId = $sessionTime?->id;

        $pools = [];
        foreach ($entries as $index => $entry) {
            $poolIdx = ($format === '2_babak') ? 0 : ($index % $poolCount);
            $pool = $poolRecords[$poolIdx];

            // Distribute courts evenly among pools within the match's designated 'base court' offset
            $court = $courts->get(($courtBaseIdx + $poolIdx) % $cCount);
            $courtId = $court ? $court->id : null;

            $poolNameKey = $pool ? $pool->name : 'PENYISIHAN';
            if (! isset($pools[$poolNameKey])) {
                $pools[$poolNameKey] = [];
            }

            $orderInPool = count($pools[$poolNameKey]) + 1;

            $pools[$poolNameKey][] = [
                'court_order' => $orderInPool,
                'registration_id' => $entry['registration_id'],
                'contingent' => $entry['contingent'],
            ];

            // Specific metadata for rules
            $metadata = [];
            if (str_contains(strtolower($match->name), 'beregu')) {
                $metadata['composition_rule'] = 'Tandoku (start) - Paired (middle) - Tandoku (end)';
            }

            DrawingMatchNumber::create([
                'match_number_id' => $matchId,
                'registration_id' => $entry['registration_id'],
                'pool_id' => $pool ? $pool->id : null,
                'court_id' => $courtId,
                'schedule_date' => $rundownDate,
                'session_time_id' => $sessionTimeId,
                'rundown_id' => $rundownId,
                'round' => $round,
                'sequence_number' => $orderInPool,
                'draft_type' => 'embu',
                'metadata' => $metadata,
            ]);
        }

        // Sync drawing_data blob for legacy display support
        $drawingData = [
            'total_entries' => $totalEntries,
            'format' => $format,
            'pool_count' => $poolCount,
            'qualifiers' => $qualifiers,
            'description' => $description,
            'pools' => $pools,
        ];

        $match->update([
            'drawing_data' => $drawingData,
            'drawing_generated_at' => now(),
        ]);

        $this->generatingMatchId = null;
        $this->dispatch('drawing-generated', matchId: $matchId);
    }

    public function resetDrawing(int $matchId): void
    {
        DrawingMatchNumber::where('match_number_id', $matchId)->delete();

        MatchNumber::findOrFail($matchId)->update([
            'drawing_data' => null,
            'drawing_generated_at' => null,
        ]);
    }

    /**
     * Generate drawing for all Embu matches that don't have drawing yet.
     * Optionally force regenerate all.
     */
    public function generateAllDrawings(bool $forceRegenerate = true, string $round = 'Penyisihan'): void
    {
        $matches = MatchNumber::where('draft_type', 'embu');

        if (! $forceRegenerate) {
            $matches->whereNull('drawing_data');
        }

        $matchIds = $matches->pluck('id');

        foreach ($matchIds as $id) {
            $this->generateDrawing($id, $round);
        }

        $this->dispatch('all-drawings-generated');
    }

    // ──────────────────────────────────────────────────
    // GENERATE FINAL
    // ──────────────────────────────────────────────────

    public function promptGenerateFinal(int $matchId): void
    {
        $this->finalMatchId = $matchId;
        $this->finalCourtId = null;
        $this->finalRundownId = null;
        $this->finalSessionTimeId = null;
        $this->isGeneratingFinal = true;
    }

    public function generateFinal(): void
    {
        if (!$this->finalMatchId) return;
        $matchId = $this->finalMatchId;
        $match = MatchNumber::findOrFail($matchId);
        
        // Ensure no previous final exists
        DrawingMatchNumber::where('match_number_id', $matchId)->where('round', 'Final')->delete();

        $drawingData = $match->drawing_data;
        if (!$drawingData) return;

        $format = $drawingData['format'] ?? '2_babak';
        $qualifiersLimit = $drawingData['qualifiers'] ?? 0;

        // Get all Penyisihan scores, only latest tiebreak, to determine qualifiers
        $penyisihanScores = EmbuScore::where('match_number_id', $matchId)
            ->where('round_label', 'Penyisihan')
            ->get();
            
        // Group by tiebreak_round to effectively just use the latest one for each group of tied participants?
        // Wait, for Embu, tiebreak_round defines the latest round scores.
        $latestTiebreak = $penyisihanScores->max('tiebreak_round');
        $validScores = $penyisihanScores->where('tiebreak_round', $latestTiebreak);

        $qualifiedRegistrations = collect();

        if ($format === '2_babak') {
            // all advance
            $qualifiedRegistrations = $validScores;
        } else {
            // Get original drawing to group by pool
            $drawings = DrawingMatchNumber::where('match_number_id', $matchId)
                ->where('round', 'Penyisihan')
                ->get()
                ->keyBy('registration_id');

            // inject pool_id to valid scores
            $validScores = $validScores->map(function($score) use ($drawings) {
                $score->pool_id = $drawings->has($score->registration_id) ? $drawings->get($score->registration_id)->pool_id : null;
                return $score;
            });

            // Group by pool and take top N
            $byPool = $validScores->groupBy('pool_id');
            foreach ($byPool as $poolId => $scoresInPool) {
                // sort from highest to lowest score
                $topScores = $scoresInPool->sortByDesc('nilai_akhir')->take($qualifiersLimit);
                $qualifiedRegistrations = $qualifiedRegistrations->concat($topScores);
            }
        }

        // Now sort the qualified ones Ascending by score (terendah ke tertinggi)
        $sortedFinalists = $qualifiedRegistrations->sortBy('nilai_akhir')->values();

        // Specific metadata
        $metadata = [];
        if (str_contains(strtolower($match->name), 'beregu')) {
            $metadata['composition_rule'] = 'Tandoku (start) - Paired (middle) - Tandoku (end)';
        }

        $sessionDate = null;
        if ($this->finalRundownId) {
            $rd = Rundown::find($this->finalRundownId);
            if ($rd) $sessionDate = $rd->date;
        }

        foreach ($sortedFinalists as $index => $finalist) {
            $order = $index + 1;
            DrawingMatchNumber::create([
                'match_number_id' => $matchId,
                'registration_id' => $finalist->registration_id,
                'pool_id' => null, // Final usually has no pool grouping (or all in one)
                'court_id' => $this->finalCourtId,
                'schedule_date' => $sessionDate,
                'session_time_id' => $this->finalSessionTimeId,
                'rundown_id' => $this->finalRundownId,
                'round' => 'Final',
                'sequence_number' => $order,
                'draft_type' => 'embu',
                'metadata' => $metadata,
            ]);
        }

        $this->isGeneratingFinal = false;
        $this->finalMatchId = null;
        $this->dispatch('drawing-final-generated', matchId: $matchId);
    }
    
    // ──────────────────────────────────────────────────
    // TANDING ULANG & SIMPAN JUARA
    // ──────────────────────────────────────────────────

    public function tandingUlang(int $matchId, string $roundLabel): void
    {
        // Temukan peserta yang memiliki nilai_akhir sama persis di babak ini
        $scores = EmbuScore::where('match_number_id', $matchId)
            ->where('round_label', $roundLabel)
            ->get();
        // Hanya cek di babak tiebreak terakhir
        $latestTiebreak = $scores->max('tiebreak_round') ?? 0;
        $latestScores = $scores->where('tiebreak_round', $latestTiebreak);

        $counts = $latestScores->countBy(function ($score) {
            return (string)$score->nilai_akhir;
        });
        
        $tiedValues = $counts->filter(fn($val) => $val > 1)->keys()->toArray();
        if (empty($tiedValues)) return; // Tidak ada seri

        // Buat match baru tiebreak untuk mereka yang nilainya seri
        $tiedScores = $latestScores->filter(function ($score) use ($tiedValues) {
            return in_array((string)$score->nilai_akhir, $tiedValues, true);
        });

        foreach ($tiedScores as $score) {
            EmbuScore::create([
                'match_number_id' => $score->match_number_id,
                'registration_id' => $score->registration_id,
                'judge_1' => 0,
                'judge_2' => 0,
                'judge_3' => 0,
                'judge_4' => 0,
                'judge_5' => 0,
                'total_score' => 0,
                'rank' => null,
                'tiebreak_round' => $latestTiebreak + 1,
                'denda' => 0,
                'nilai_akhir' => 0,
                'round_label' => $roundLabel,
            ]);
        }
        $this->dispatch('tiebreak-generated');
    }

    public function simpanJuaranya(int $scoreId, int $rank): void
    {
        $score = EmbuScore::find($scoreId);
        if ($score) {
            $score->update(['rank' => $rank]);
        }
    }

    // ──────────────────────────────────────────────────
    // RENDER
    // ──────────────────────────────────────────────────

    public function render()
    {
        $paginatedMatches = MatchNumber::where('draft_type', 'embu')
            ->has('athletes')
            ->with(['ageGroup'])
            ->latest()
            ->paginate(1000);

        $allTechniqueNames = Technique::pluck('name', 'id')->toArray();
        $courts = Court::orderBy('order')->get();
        // Hanya ambil rundown dengan type pertandingan
        $rundowns = Rundown::where('type', 'pertandingan')->orderBy('date')->get();
        $sessionTimes = SessionTime::orderBy('start_time')->get();

        $matchSummary = [];
        foreach ($paginatedMatches as $match) {
            $gender = $match->gender ?? 'Mix';
            $ageGroupName = $match->ageGroup->name ?? 'Unknown Age';

            // Fetch drawing results for this match
            $drawingsQuery = DrawingMatchNumber::with(['pool', 'court', 'rundown', 'sessionTime', 'registration.contingent'])
                ->where('match_number_id', $match->id);

            if ($this->selectedCourtId) {
                $drawingsQuery->where('court_id', $this->selectedCourtId);
            }

            $drawingsFromDb = $drawingsQuery->get();
            $drawing = $match->drawing_data;

            if ($drawingsFromDb->isEmpty() && $this->selectedCourtId) {
                $drawing = null;
            }

            $matchAthletes = DB::table('athlete_match_number')
                ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                ->join('registration_athlete', function ($join) {
                    $join->on('athlete_match_number.athlete_id', '=', 'registration_athlete.athlete_id')
                        ->on('athlete_match_number.registration_id', '=', 'registration_athlete.registration_id');
                })
                ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                ->where('athlete_match_number.match_number_id', $match->id)
                ->select(
                    'athletes.name',
                    'registration_athlete.kyu as rank',
                    'contingents.name as contingent',
                    'athlete_match_number.registration_id',
                    'athlete_match_number.technique_ids'
                )
                ->orderBy('athlete_match_number.registration_id')
                ->get()
                ->map(function ($ath) use ($allTechniqueNames) {
                    $techIds = $ath->technique_ids ? json_decode($ath->technique_ids, true) : [];
                    $ath->readable_techniques = array_map(function ($id) use ($allTechniqueNames) {
                        return $allTechniqueNames[$id] ?? 'Unknown #'.$id;
                    }, $techIds);

                    return $ath;
                });

            // Embu scores for each round
            $embuScores = EmbuScore::where('match_number_id', $match->id)
                ->with('registration.contingent')
                ->orderBy('round_label')
                ->orderBy('tiebreak_round')
                ->orderBy('rank')
                ->get();

            $matchSummary[$gender][$ageGroupName][$match->id] = [
                'name' => $match->name,
                'athletes' => $matchAthletes->toArray(),
                'drawing_data' => $drawing,
                'db_drawing_entries' => $drawingsFromDb,
                'drawing_at' => $match->drawing_generated_at,
                'embu_scores' => $embuScores,
            ];
        }

        return view('livewire.admin.technical-meeting.embu.admin-technical-meeting-embu-index', [
            'paginatedMatches' => $paginatedMatches,
            'matchSummary' => $matchSummary,
            'courts' => $courts,
            'rundowns' => $rundowns,
            'sessionTimes' => $sessionTimes,
        ]);
    }
}
