<?php

namespace App\Livewire\Contingent;

use App\Models\EmbuScore;
use App\Models\RandoriMatchResult;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
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
        if (!$user->contingent()->exists()) {
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
        ]))->title('Rekap Pertandingan - ' . $this->contingent->name);
    }

    protected function getEmbuData()
    {
        $query = EmbuScore::with(['matchNumber.ageGroup', 'matchNumber.athletes', 'registration.contingent'])
            ->whereHas('registration', function($q) {
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
        // For Randori, we check randori_match_results where one of the athletes belongs to this contingent
        $query = RandoriMatchResult::with(['matchNumber.ageGroup', 'akaRegistration.contingent', 'shiroRegistration.contingent'])
            ->where(function($q) {
                $q->whereHas('akaRegistration', function($sq) {
                    $sq->where('contingent_id', $this->contingent->id);
                })->orWhereHas('shiroRegistration', function($sq) {
                    $sq->where('contingent_id', $this->contingent->id);
                });
            });

        if ($this->matchNumberFilter) {
            $query->where('match_number_id', $this->matchNumberFilter);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('akaRegistration.athletes', function($sq) {
                    $sq->where('name', 'ilike', '%'.$this->search.'%');
                })->orWhereHas('shiroRegistration.athletes', function($sq) {
                    $sq->where('name', 'ilike', '%'.$this->search.'%');
                });
            });
        }

        $results = $query->orderBy('match_number_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'randoriPage');

        return ['results' => $results];
    }
}
