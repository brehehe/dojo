<?php

namespace App\Livewire\Admin\TechnicalMeeting\Randori;

use App\Models\MatchNumber\MatchNumber;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminTechnicalMeetingRandoriIndex extends Component
{
    use WithPagination;

    public ?int $generatingMatchId = null;

    public function paginationView(): string
    {
        return 'livewire.admin.pagination';
    }

    // ──────────────────────────────────────────────────
    // DRAWING GENERATION (BRACKET / BAGAN)
    // ──────────────────────────────────────────────────

    public function generateDrawing(int $matchId): void
    {
        $this->generatingMatchId = $matchId;
        $match = MatchNumber::findOrFail($matchId);

        // Get all unique athletes in this match (unlike Embu, Randori is usually individual, so registration_id maps to 1 athlete)
        $athletesQuery = DB::table('athlete_match_number')
            ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
            ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
            ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
            ->where('athlete_match_number.match_number_id', $matchId)
            ->select(
                'athletes.id',
                'athletes.name',
                'contingents.name as contingent_name'
            )
            ->distinct()
            ->get();

        $totalEntries = $athletesQuery->count();

        if ($totalEntries === 0) {
            $this->generatingMatchId = null;

            return;
        }

        // Bracket size must be a power of 2
        $bracketSize = 1;
        while ($bracketSize < $totalEntries) {
            $bracketSize *= 2;
        }

        // Shuffle athletes but try to spread out same contingents
        $grouped = $athletesQuery->groupBy('contingent_name');
        $spreadAthletes = [];
        // Round robin pop to interleave contingents
        $maxPerContingent = $grouped->max(fn ($c) => $c->count());
        for ($i = 0; $i < $maxPerContingent; $i++) {
            foreach ($grouped as $contingent => $members) {
                if ($members->count() > $i) {
                    $spreadAthletes[] = $members[$i];
                }
            }
        }

        // Now we have $spreadAthletes of length $totalEntries
        // We will insert them into bracket nodes based on Seed order to keep them apart in the visual tree
        $seedOrder = $this->generateSeedingArray($bracketSize);

        $bracket = array_fill(0, $bracketSize, null); // null represents Bye

        // Fill the bracket
        for ($i = 0; $i < $totalEntries; $i++) {
            $position = $seedOrder[$i];
            $bracket[$position] = [
                'id' => $spreadAthletes[$i]->id,
                'name' => $spreadAthletes[$i]->name,
                'contingent' => $spreadAthletes[$i]->contingent_name,
            ];
        }

        $drawingData = [
            'format' => 'bagan',
            'total_entries' => $totalEntries,
            'bracket_size' => $bracketSize,
            'bracket' => $bracket, // original bracket for ref
        ];

        // Generate hierarchical rounds structure for scoring/progression
        $rounds = [];
        $totalRounds = log($bracketSize, 2);
        
        // Round 1 (Initial matches from bracket)
        $initialMatches = [];
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $initialMatches[] = [
                'athlete1' => $bracket[$i] ?? null,
                'athlete2' => $bracket[$i + 1] ?? null,
            ];
        }
        $rounds[] = $initialMatches;

        // Subsequent TBD rounds
        for ($r = 1; $r < $totalRounds; $r++) {
            $matchesInRound = $bracketSize / pow(2, $r + 1);
            $roundMatches = [];
            for ($m = 0; $m < $matchesInRound; $m++) {
                $roundMatches[] = ['athlete1' => null, 'athlete2' => null];
            }
            $rounds[] = $roundMatches;
        }
        $drawingData['rounds'] = $rounds;

        $match->update([
            'drawing_data' => $drawingData,
            'drawing_generated_at' => now(),
        ]);

        $this->generatingMatchId = null;
        $this->dispatch('drawing-generated', matchId: $matchId);
    }

    public function generateAllDrawings(bool $forceRegenerate = false): void
    {
        $matches = MatchNumber::where('draft_type', 'randori');
        if (! $forceRegenerate) {
            $matches->whereNull('drawing_data');
        }

        $matchIds = $matches->pluck('id');
        foreach ($matchIds as $id) {
            $this->generateDrawing($id);
        }

        $this->dispatch('all-drawings-generated');
    }

    public function resetDrawing(int $matchId): void
    {
        MatchNumber::findOrFail($matchId)->update([
            'drawing_data' => null,
            'drawing_generated_at' => null,
        ]);
    }

    /**
     * Generate standard tournament seeding array to space out athletes.
     * E.g., for 8: [0, 7, 3, 4, 1, 6, 2, 5]
     */
    private function generateSeedingArray(int $size): array
    {
        if ($size === 1) {
            return [0];
        }
        if ($size === 2) {
            return [0, 1];
        }

        $order = [0, 1];
        $currentSize = 2;

        while ($currentSize < $size) {
            $nextOrder = [];
            foreach ($order as $val) {
                // For each element X, we pair it with ($currentSize * 2 - 1 - X)
                $nextOrder[] = $val;
                $nextOrder[] = ($currentSize * 2) - 1 - $val;
            }
            $order = $nextOrder;
            $currentSize *= 2;
        }

        return $order;
    }

    // ──────────────────────────────────────────────────
    // RENDER
    // ──────────────────────────────────────────────────

    public function render()
    {
        $paginatedMatches = MatchNumber::where('draft_type', 'randori')
            ->has('athletes')
            ->with(['ageGroup'])
            ->latest()
            ->paginate(1000);

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
                    'contingents.name as contingent'
                )
                ->distinct()
                ->orderBy('contingents.name')
                ->get();

            $matchSummary[$gender][$ageGroupName][$match->id] = [
                'name' => $match->name,
                'athletes' => $matchAthletes->toArray(),
                'drawing_data' => $match->drawing_data,
                'drawing_at' => $match->drawing_generated_at,
            ];
        }

        return view('livewire.admin.technical-meeting.randori.admin-technical-meeting-randori-index', [
            'paginatedMatches' => $paginatedMatches,
            'matchSummary' => $matchSummary,
        ]);
    }
}
