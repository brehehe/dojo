<?php

namespace App\Livewire\Referee;

use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')] // Using admin layout for now as it has necessary CSS/JS
class RefereeScoringDashboard extends Component
{
    public $referee;
    public $activeMatch;
    public $judgeIndex;

    // Embu Itemized Scores
    public $embuItems = [
        'goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0,
        'juho_4' => 0, 'juho_5' => 0, 'juho_6' => 0,
        'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0,
    ];

    // Randori Itemized Scores
    public $randoriItems = [
        'aka' => ['ippon' => 0, 'wazaari' => 0, 'mujoken' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
        'shiro' => ['ippon' => 0, 'wazaari' => 0, 'mujoken' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
    ];

    public $notes = '';
    public $totalScore = 0;

    public function mount()
    {
        $user = Auth::user();
        $this->referee = Referee::where('user_id', $user->id)->first();

        if (!$this->referee) {
            abort(403, 'Anda tidak terdaftar sebagai Wasit.');
        }

        $this->loadActiveMatch();
    }

    public function loadActiveMatch()
    {
        $this->activeMatch = MatchNumber::where('is_active', true)
            ->whereHas('referees', function($q) {
                $q->where('referees.id', $this->referee->id);
            })
            ->with(['referees' => function($q) {
                $q->where('referees.id', $this->referee->id);
            }])
            ->first();

        if ($this->activeMatch) {
            $this->judgeIndex = $this->activeMatch->referees->first()->pivot->judge_index;
            $this->loadExistingDetails();
        }
    }

    public function loadExistingDetails()
    {
        $id = $this->activeMatch->draft_type === 'embu' 
            ? $this->activeMatch->active_registration_id 
            : $this->activeMatch->active_bracket_node;

        if (!$id) return;

        $existing = \App\Models\RefereeScoreDetail::where('match_number_id', $this->activeMatch->id)
            ->where('referee_id', $this->referee->id)
            ->where('scorable_id', $id)
            ->first();

        if ($existing) {
            if ($this->activeMatch->draft_type === 'embu') {
                $this->embuItems = array_merge($this->embuItems, $existing->details);
            } else {
                $this->randoriItems = array_merge($this->randoriItems, $existing->details);
            }
            $this->notes = $existing->notes;
            $this->calculateTotal();
        } else {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->embuItems = array_fill_keys(array_keys($this->embuItems), 0);
        $this->randoriItems = [
            'aka' => ['ippon' => 0, 'wazaari' => 0, 'mujoken' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
            'shiro' => ['ippon' => 0, 'wazaari' => 0, 'mujoken' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
        ];
        $this->notes = '';
        $this->totalScore = 0;
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'embuItems') || str_starts_with($propertyName, 'randoriItems')) {
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        if ($this->activeMatch->draft_type === 'embu') {
            $this->totalScore = array_sum($this->embuItems);
        } else {
            // Randori scoring logic (example weights)
            $totalAka = ($this->randoriItems['aka']['ippon'] * 10) + 
                        ($this->randoriItems['aka']['wazaari'] * 5) + 
                        ($this->randoriItems['aka']['mujoken'] * 15);
            
            $totalShiro = ($this->randoriItems['shiro']['ippon'] * 10) + 
                          ($this->randoriItems['shiro']['wazaari'] * 5) + 
                          ($this->randoriItems['shiro']['mujoken'] * 15);
            
            $this->totalScore = $totalAka - $totalShiro; // Net score for calculation
        }
    }

    public function submitScore()
    {
        if (!$this->activeMatch || !$this->judgeIndex) return;

        $id = $this->activeMatch->draft_type === 'embu' 
            ? $this->activeMatch->active_registration_id 
            : $this->activeMatch->active_bracket_node;

        if (!$id) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Belum ada peserta yang dipanggil.']);
            return;
        }

        $details = $this->activeMatch->draft_type === 'embu' ? $this->embuItems : $this->randoriItems;

        \DB::transaction(function() use ($id, $details) {
            // 1. Save Granular Details
            \App\Models\RefereeScoreDetail::updateOrCreate(
                [
                    'match_number_id' => $this->activeMatch->id,
                    'referee_id' => $this->referee->id,
                    'scorable_type' => $this->activeMatch->draft_type === 'embu' ? \App\Models\Registration::class : \App\Models\RandoriMatchResult::class,
                    'scorable_id' => $id,
                ],
                [
                    'judge_index' => $this->judgeIndex,
                    'details' => $details,
                    'total_calculated_score' => $this->totalScore,
                    'notes' => $this->notes,
                ]
            );

            // 2. Sync to Main Table for Quick Access
            if ($this->activeMatch->draft_type === 'embu') {
                $column = 'judge_' . $this->judgeIndex;
                \App\Models\EmbuScore::updateOrCreate(
                    ['match_number_id' => $this->activeMatch->id, 'registration_id' => $id],
                    [$column => $this->totalScore]
                );
            }
        });

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Skor Tersimpan',
            'text' => 'Nilai Juri ' . $this->judgeIndex . ' telah dicatat.'
        ]);
    }

    public function render()
    {
        return view('livewire.referee.referee-scoring-dashboard');
    }
}
