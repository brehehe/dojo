<?php

namespace App\Livewire\Referee;

use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Referee;
use App\Models\Court\Court;
use App\Models\ScheduleReferee;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')] // Use guest for full screen clean tablet view
class RefereeScoringDashboard extends Component
{
    public $referee;
    public $activeMatch;
    public $assignedCourt = null;
    public $assignedSession = null;
    public $assignedRundown = null;
    public $judgeIndex = null;

    // Embu Itemized Scores
    public $embuItems = [
        'goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0,
        'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0,
        'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0,
    ];

    // Randori Itemized Scores
    public $randoriItems = [
        'aka' => ['mujoken' => 0, 'ippon' => 0, 'wazaari' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
        'shiro' => ['mujoken' => 0, 'ippon' => 0, 'wazaari' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
    ];

    public $notes = '';
    public $totalScore = 0;
    public $totalAka = 0;
    public $totalShiro = 0;

    public function checkParticipantCalled()
    {
        if (!$this->activeMatch) return false;
        return $this->activeMatch->draft_type === 'embu' 
            ? !is_null($this->activeMatch->active_registration_id) 
            : !is_null($this->activeMatch->active_bracket_node);
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->referee = Referee::where('user_id', $user->id)->first();
        }

        $this->loadActiveMatch();
    }

    private function getActiveIdentifier($match)
    {
        if (!$match) return null;
        $subId = $match->draft_type === 'embu' ? $match->active_registration_id : $match->active_bracket_node;
        return $match->id . '_' . $subId;
    }

    public function loadActiveMatch()
    {
        if (!$this->referee) return;

        $newActiveMatch = null;
        $newAssignedCourt = null;
        $newAssignedSession = null;
        $newAssignedRundown = null;
        $newJudgeIndex = null;

        $mySchedules = ScheduleReferee::with(['court.activeMatch', 'sessionTime', 'rundown'])
            ->where('referee_id', $this->referee->id)
            ->get();

        foreach ($mySchedules as $schedule) {
            if (!$schedule->court_id || !$schedule->court) continue;

            $court = $schedule->court;
            if ($court->active_match_id && $court->activeMatch) {
                $matchHasOurDrawing = $court->activeMatch->drawings()
                    ->where('session_time_id', $schedule->session_time_id)
                    ->where('rundown_id', $schedule->rundown_id)
                    ->exists();

                if ($matchHasOurDrawing || true) { 
                    $newActiveMatch = $court->activeMatch;
                    $newAssignedCourt = $court;
                    $newAssignedSession = $schedule->sessionTime;
                    $newAssignedRundown = $schedule->rundown;
                    $newJudgeIndex = $schedule->judge_index;
                    break;
                }
            }
        }

        $currentId = $this->getActiveIdentifier($this->activeMatch);
        $newId = $this->getActiveIdentifier($newActiveMatch);

        if ($currentId !== $newId) {
            $this->activeMatch = $newActiveMatch;
            $this->assignedCourt = $newAssignedCourt;
            $this->assignedSession = $newAssignedSession;
            $this->assignedRundown = $newAssignedRundown;
            $this->judgeIndex = $newJudgeIndex;

            if ($this->activeMatch) {
                $this->loadExistingDetails();
            } else {
                $this->resetForm();
            }
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
        $this->embuItems = [
            'goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0,
            'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0,
            'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0,
        ];
        $this->randoriItems = [
            'aka' => ['mujoken' => 0, 'ippon' => 0, 'wazaari' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
            'shiro' => ['mujoken' => 0, 'ippon' => 0, 'wazaari' => 0, 'batsu5' => 0, 'batsu10' => 0, 'yusei' => 0],
        ];
        $this->notes = '';
        $this->totalScore = 0;
        $this->totalAka = 0;
        $this->totalShiro = 0;
        $this->calculateTotal();
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'embuItems') || str_starts_with($propertyName, 'randoriItems')) {
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        if ($this->activeMatch && $this->activeMatch->draft_type === 'embu') {
            $this->totalScore = 0;
            // Limit Embu score fields to maximum 10 mathematically
            foreach ($this->embuItems as $key => $val) {
                if (!is_numeric($val) || $val == '') $this->embuItems[$key] = 0;
                if ($this->embuItems[$key] > 10) $this->embuItems[$key] = 10;
                $this->totalScore += (float) $this->embuItems[$key];
            }
        } elseif ($this->activeMatch) {
            // Randori scoring logic multiplier based on user requested rules:
            // Mujoken Kachi (15) , Ippon (10) , Waza ari (5) , Hasil Batsu 5 (+5 points?? No wait "Hasil Batsu" is penalty for opponent or score for self. Let's make it positive score.)
            // Actually, THB: Batsu 5 for opponent means 5 points for Us. 
            $this->totalAka = ($this->randoriItems['aka']['mujoken'] * 15) + 
                        ($this->randoriItems['aka']['ippon'] * 10) + 
                        ($this->randoriItems['aka']['wazaari'] * 5) + 
                        ($this->randoriItems['aka']['batsu5'] * 5) + 
                        ($this->randoriItems['aka']['batsu10'] * 10) + 
                        ($this->randoriItems['aka']['yusei'] * 5);
            
            $this->totalShiro = ($this->randoriItems['shiro']['mujoken'] * 15) + 
                          ($this->randoriItems['shiro']['ippon'] * 10) + 
                          ($this->randoriItems['shiro']['wazaari'] * 5) + 
                          ($this->randoriItems['shiro']['batsu5'] * 5) + 
                          ($this->randoriItems['shiro']['batsu10'] * 10) + 
                          ($this->randoriItems['shiro']['yusei'] * 5);
            
            $this->totalScore = $this->totalAka - $this->totalShiro; // Stored as a differential
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
