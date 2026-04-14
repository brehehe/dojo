<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminArbitraseScoringRandoriDetail extends Component
{
    public MatchNumber $matchNumber;

    public $drawingData = [];

    public $activeMatch = null;

    public $scoreRed = 0;

    public $scoreBlue = 0;

    public $showModal = false;

    public function mount(MatchNumber $matchNumber)
    {
        try {
            $this->matchNumber = $matchNumber;
            $drawingData = $matchNumber->drawing_data ?? [];

            // Migrate legacy flat structure to hierarchical rounds structure if needed
            if (isset($drawingData['bracket']) && ! isset($drawingData['rounds'])) {
                $drawingData = $this->initializeRoundsFromBracket($drawingData);
                $this->matchNumber->update(['drawing_data' => $drawingData]);
            }

            $this->drawingData = $drawingData;
        } catch (\Exception $e) {
            logger()->error('Error mounting Randori Scoring: ' . $e->getMessage());
        }
    }

    private function initializeRoundsFromBracket(array $drawingData): array
    {
        $bracket = $drawingData['bracket'] ?? [];
        $bracketSize = $drawingData['bracket_size'] ?? 0;
        
        if ($bracketSize < 2) return $drawingData;

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

        // Subsequent rounds (TBD placeholders)
        for ($r = 1; $r < $totalRounds; $r++) {
            $matchesInRound = $bracketSize / pow(2, $r + 1);
            $roundMatches = [];
            for ($m = 0; $m < $matchesInRound; $m++) {
                $roundMatches[] = [
                    'athlete1' => null,
                    'athlete2' => null,
                ];
            }
            $rounds[] = $roundMatches;
        }

        $drawingData['rounds'] = $rounds;
        return $drawingData;
    }

    public function selectWinner($roundIndex, $matchIndex, $winnerColor)
    {
        $match = &$this->drawingData['rounds'][$roundIndex][$matchIndex];

        // Find winner athlete and red/blue athletes
        $athleteRed = $match['athlete1'] ?? null;
        $athleteBlue = $match['athlete2'] ?? null;
        $winnerAthlete = ($winnerColor === 'red') ? $athleteRed : $athleteBlue;

        if (! $winnerAthlete) {
            return;
        }

        // Record the result
        RandoriMatchResult::updateOrCreate(
            [
                'match_number_id' => $this->matchNumber->id,
                'bracket_node_index' => $roundIndex.'_'.$matchIndex,
            ],
            [
                'winner_athlete_id' => $winnerAthlete['id'],
                'winner_color' => $winnerColor,
                'score_red' => $this->scoreRed,
                'score_blue' => $this->scoreBlue,
            ]
        );

        // Propagate to next round if exists
        if (isset($this->drawingData['rounds'][$roundIndex + 1])) {
            $nextMatchIndex = floor($matchIndex / 2);
            $nextSlot = ($matchIndex % 2 === 0) ? 'athlete1' : 'athlete2';

            $this->drawingData['rounds'][$roundIndex + 1][$nextMatchIndex][$nextSlot] = $winnerAthlete;
        }

        // Save updated bracket data
        $this->matchNumber->update(['drawing_data' => $this->drawingData]);

        $this->showModal = false;
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pemenang Berhasil Dicatat',
            'text' => $winnerAthlete['name'].' maju ke babak berikutnya.',
        ]);
    }

    public function callMatch($roundIndex, $matchIndex)
    {
        $this->matchNumber->update(['active_bracket_node' => $roundIndex . '_' . $matchIndex]);
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pertandingan Dipanggil',
            'text' => 'Wasit kini dapat mengisi skor untuk pertandingan ini.',
        ]);
    }

    public function openMatchModal($roundIndex, $matchIndex)
    {
        $match = $this->drawingData['rounds'][$roundIndex][$matchIndex];

        // Don't open if one side is missing (Bye)
        if (! isset($match['athlete1']) || ! isset($match['athlete2'])) {
            // Handle Bye case if needed, but usually Bye match results in automatic winner
            return;
        }

        $this->activeMatch = ['round' => $roundIndex, 'match' => $matchIndex, 'data' => $match];

        $existing = RandoriMatchResult::where('match_number_id', $this->matchNumber->id)
            ->where('bracket_node_index', $roundIndex.'_'.$matchIndex)
            ->first();

        $this->scoreRed = $existing->score_red ?? 0;
        $this->scoreBlue = $existing->score_blue ?? 0;

        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.admin.arbitrase.scoring.admin-arbitrase-scoring-randori-detail', [
            'results' => RandoriMatchResult::where('match_number_id', $this->matchNumber->id)->get()->keyBy('bracket_node_index'),
            'drawingData' => $this->drawingData,
        ]);
    }
}
