<?php

namespace App\Livewire\Contingent;

use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class RekapPertandingan extends Component
{
    use WithPagination;

    public string $tab = 'embu';

    public string $search = '';

    public string $matchNumberFilter = '';

    public $contingent;

    protected $queryString = [
        'tab' => ['except' => 'embu'],
        'search' => ['except' => ''],
        'matchNumberFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $user = Auth::user();
        if (! $user->contingent()->exists()) {
            return redirect()->route('contingent.setup');
        }
        $this->contingent = $user->contingent;
    }

    public function updated($property)
    {
        if ($property !== 'tab') {
            $this->resetPage();
        }
    }

    public function render()
    {
        if ($this->tab === 'embu') {
            $data = $this->getEmbuData();
        } else {
            $data = $this->getRandoriData();
        }

        return view('livewire.contingent.rekap-pertandingan', array_merge($data, [
            'matchNumbers' => MatchNumber::where('draft_type', $this->tab)->orderBy('name')->get(),
        ]))->title('Rekap Pertandingan - '.$this->contingent->name);
    }

    protected function getEmbuData()
    {
        $query = EmbuScore::with(['matchNumber.ageGroup', 'registration'])
            ->whereHas('registration', function ($q) {
                $q->where('contingent_id', $this->contingent->id);
            });

        if ($this->matchNumberFilter) {
            $query->where('match_number_id', $this->matchNumberFilter);
        }

        if ($this->search) {
            $query->whereHas('registration.athletes', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%');
            });
        }

        $allScores = $query
            ->orderBy('match_number_id')
            ->orderBy('drawing_id')
            ->orderBy('round_label', 'desc')
            ->get();

        // Load the drawing metadata for each score to get per-team athlete names.
        $drawingIds = $allScores->pluck('drawing_id')->filter()->unique()->values();
        $drawings = DrawingMatchNumber::whereIn('id', $drawingIds)
            ->get(['id', 'metadata', 'sequence_number'])
            ->keyBy('id');

        // Attach athlete_label and drawing sequence; skip orphaned scores (drawing deleted).
        $allScores->each(function (EmbuScore $s) use ($drawings) {
            $drawing = $drawings->get($s->drawing_id);
            $s->athlete_label = $drawing ? trim($drawing->metadata['athlete_name'] ?? '') : null;
            $s->drawing_sequence = $drawing ? $drawing->sequence_number : 0;
        });

        // Remove scores whose drawing no longer exists in DB or has no athlete name.
        $allScores = $allScores->filter(
            fn (EmbuScore $s) => $s->athlete_label !== null && $s->athlete_label !== ''
        );

        // Group: match_number_id → unique athlete_label (= one row per team, all rounds).
        // Within each team group, deduplicate by round_label keeping the best nilai_akhir.
        $deduplicated = $allScores
            ->groupBy(fn (EmbuScore $s) => $s->match_number_id.'__'.$s->athlete_label)
            ->flatMap(function ($teamScores) {
                // Keep one score per round_label for this team (best nilai_akhir).
                return $teamScores
                    ->groupBy('round_label')
                    ->map(fn ($roundScores) => $roundScores->sortByDesc('nilai_akhir')->first())
                    ->values();
            })
            ->sortBy([
                ['match_number_id', 'asc'],
                ['drawing_sequence', 'asc'],
                ['round_label', 'asc'],
            ])
            ->values();

        $page = request()->get('embuPage', 1);
        $perPage = 40;
        $scores = new LengthAwarePaginator(
            $deduplicated->forPage($page, $perPage),
            $deduplicated->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'embuPage']
        );

        return ['scores' => $scores];
    }

    protected function getRandoriData()
    {
        // For Randori, we check randori_match_results and filter by contingent name in drawing_data
        $query = RandoriMatchResult::with(['matchNumber.ageGroup'])
            ->whereHas('matchNumber', function ($q) {
                $q->where('draft_type', 'randori');
            });

        if ($this->matchNumberFilter) {
            $query->where('match_number_id', $this->matchNumberFilter);
        }

        $allResults = $query->latest()->get();
        $contingentName = $this->contingent->name;

        $filteredResults = $allResults->filter(function ($res) use ($contingentName) {
            $drawingData = $res->matchNumber->drawing_data ?? [];
            $nodeParts = explode('_', $res->bracket_node);
            $bracket = $nodeParts[0];
            $roundIdx = (int) ($nodeParts[1] ?? 0);
            $matchIdx = (int) ($nodeParts[2] ?? 0);

            $matchInfo = null;
            if ($bracket === 'ub') {
                $matchInfo = $drawingData['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'lb') {
                $matchInfo = $drawingData['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'gf') {
                $matchInfo = $drawingData['grand_final'] ?? null;
            }

            if (! $matchInfo) {
                return false;
            }

            $akaContingent = $matchInfo['athlete1']['contingent'] ?? '';
            $shiroContingent = $matchInfo['athlete2']['contingent'] ?? '';

            // Filter by search if exists
            if ($this->search) {
                $akaName = $matchInfo['athlete1']['name'] ?? '';
                $shiroName = $matchInfo['athlete2']['name'] ?? '';
                if (stripos($akaName, $this->search) === false && stripos($shiroName, $this->search) === false) {
                    return false;
                }
            }

            if ($akaContingent === $contingentName || $shiroContingent === $contingentName) {
                // Attach processed info to the result object
                $res->processed_aka = [
                    'name' => $matchInfo['athlete1']['name'] ?? '-',
                    'contingent' => $akaContingent,
                    'is_winner' => $res->winner_color === 'athlete1',
                    'is_mine' => $akaContingent === $contingentName,
                ];
                $res->processed_shiro = [
                    'name' => $matchInfo['athlete2']['name'] ?? '-',
                    'contingent' => $shiroContingent,
                    'is_winner' => $res->winner_color === 'athlete2',
                    'is_mine' => $shiroContingent === $contingentName,
                ];
                $res->round_label = $this->getRoundLabel($res->bracket_node, $res->matchNumber);
                $res->pool_name = $matchInfo['pool'] ?? ($matchInfo['pool_id'] ?? 'A');

                return true;
            }

            return false;
        });

        // Paginate the collection manually
        $page = request()->get('randoriPage', 1);
        $perPage = 20;
        $results = new LengthAwarePaginator(
            $filteredResults->forPage($page, $perPage),
            $filteredResults->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'randoriPage']
        );

        return ['results' => $results];
    }

    private function getRoundLabel(string $nodeKey, $matchNumber): string
    {
        $parts = explode('_', $nodeKey);
        $bracket = $parts[0];
        $roundIdx = (int) ($parts[1] ?? 0);

        if ($bracket === 'gf') {
            return 'Final';
        }

        $totalRounds = count($matchNumber->drawing_data['upper_bracket']['rounds'] ?? []);

        if ($bracket === 'ub') {
            $diff = $totalRounds - 1 - $roundIdx;

            return match ($diff) {
                0 => 'Semi Final',
                1 => 'Perempat Final',
                2 => 'Babak 16 Besar',
                default => 'Penyisihan',
            };
        }

        return 'Repechage';
    }
}
