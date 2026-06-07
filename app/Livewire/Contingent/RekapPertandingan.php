<?php

namespace App\Livewire\Contingent;

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
        $query = EmbuScore::with(['matchNumber.ageGroup', 'matchNumber.athletes', 'registration.contingent'])
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

        $scores = $query->orderBy('match_number_id')
            ->orderBy('round_label', 'desc')
            ->orderBy('nilai_akhir', 'desc')
            ->paginate(20, ['*'], 'embuPage');

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
