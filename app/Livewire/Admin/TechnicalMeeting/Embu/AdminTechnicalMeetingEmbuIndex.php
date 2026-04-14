<?php

namespace App\Livewire\Admin\TechnicalMeeting\Embu;

use App\Models\MatchNumber\MatchNumber;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminTechnicalMeetingEmbuIndex extends Component
{
    use WithPagination;

    public ?int $generatingMatchId = null;

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
    public function generateDrawing(int $matchId): void
    {
        $this->generatingMatchId = $matchId;

        $match = MatchNumber::findOrFail($matchId);

        // Get all distinct registration_ids (each = one team/contingent entry)
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

        // Determine format
        if ($totalEntries <= 9) {
            $format = '2_babak';
            $poolCount = 1;
            $qualifiers = 0; // all go straight to final
            $description = '2 Babak (Penyisihan + Final) — Nilai digabung & dirata-rata';
        } elseif ($totalEntries <= 11) {
            $format = 'pool';
            $poolCount = 2;
            $qualifiers = 4; // top 4 per pool
            $description = 'Sistem Pool (2 Pool) — Rank 1,2,3,4 per pool → 8 Finalis';
        } elseif ($totalEntries <= 17) {
            $format = 'pool';
            $poolCount = 3;
            $qualifiers = 3; // top 3 per pool
            $description = 'Sistem Pool (3 Pool) — Rank 1,2,3 per pool → 9 Finalis';
        } else {
            $format = 'pool';
            $poolCount = 4;
            $qualifiers = 2; // top 2 per pool
            $description = 'Sistem Pool (4 Pool) — Rank 1,2 per pool → 8 Finalis';
        }

        // Distribute entries into pools (snake/round-robin distribution for balance)
        $poolLabels = ['A', 'B', 'C', 'D'];
        $pools = [];

        foreach ($entries as $courtOrder => $entry) {
            if ($format === '2_babak') {
                $poolKey = 'PENYISIHAN';
            } else {
                // Round-robin: entry 0→Pool A, 1→Pool B, 2→Pool C, 3→Pool D, 4→Pool A ...
                $poolKey = $poolLabels[$courtOrder % $poolCount];
            }

            if (! isset($pools[$poolKey])) {
                $pools[$poolKey] = [];
            }

            $pools[$poolKey][] = [
                'court_order' => count($pools[$poolKey]) + 1,
                'registration_id' => $entry['registration_id'],
                'contingent' => $entry['contingent'],
            ];
        }

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
        MatchNumber::findOrFail($matchId)->update([
            'drawing_data' => null,
            'drawing_generated_at' => null,
        ]);
    }

    /**
     * Generate drawing for all Embu matches that don't have drawing yet.
     * Optionally force regenerate all.
     */
    public function generateAllDrawings(bool $forceRegenerate = false): void
    {
        $matches = MatchNumber::where('draft_type', 'embu');

        if (! $forceRegenerate) {
            $matches->whereNull('drawing_data');
        }

        $matchIds = $matches->pluck('id');

        foreach ($matchIds as $id) {
            $this->generateDrawing($id);
        }

        $this->dispatch('all-drawings-generated');
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

        $matchSummary = [];
        foreach ($paginatedMatches as $match) {
            $gender = $match->gender ?? 'Mix';
            $ageGroupName = $match->ageGroup->name ?? 'Unknown Age';

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

            $matchSummary[$gender][$ageGroupName][$match->id] = [
                'name' => $match->name,
                'athletes' => $matchAthletes->toArray(),
                'drawing_data' => $match->drawing_data,
                'drawing_at' => $match->drawing_generated_at,
            ];
        }

        return view('livewire.admin.technical-meeting.embu.admin-technical-meeting-embu-index', [
            'paginatedMatches' => $paginatedMatches,
            'matchSummary' => $matchSummary,
        ]);
    }
}
