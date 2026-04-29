<?php

namespace App\Livewire\Referee;

use App\Models\EmbuScore;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Models\ScheduleReferee;
use App\Services\RandoriScoringService;
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
        'goho_1' => '', 'goho_2' => '', 'goho_3' => '',
        'juho_1' => '', 'juho_2' => '', 'juho_3' => '',
        'ekspresi_1' => '', 'ekspresi_2' => '', 'ekspresi_3' => '', 'ekspresi_4' => '',
    ];

    // Randori Itemized Scores
    public $randoriItems = [
        'aka' => ['mujoken' => '', 'ippon' => '', 'wazaari' => '', 'batsu5' => '', 'batsu10' => '', 'yusei' => ''],
        'shiro' => ['mujoken' => '', 'ippon' => '', 'wazaari' => '', 'batsu5' => '', 'batsu10' => '', 'yusei' => ''],
    ];

    public $notes = '';

    public $totalScore = 0;

    public $totalAka = 0;

    public $totalShiro = 0;

    public function checkParticipantCalled()
    {
        if (! $this->activeMatch) {
            return false;
        }

        return $this->activeMatch->draft_type === 'embu'
            ? ! is_null($this->activeMatch->active_registration_id)
            : ! is_null($this->activeMatch->active_bracket_node);
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
        if (! $match) {
            return null;
        }
        $subId = $match->draft_type === 'embu' ? $match->active_registration_id : $match->active_bracket_node;

        return $match->id.'_'.$subId;
    }

    public function loadActiveMatch()
    {
        if (! $this->referee) {
            return;
        }

        $newActiveMatch = null;
        $newAssignedCourt = null;
        $newAssignedSession = null;
        $newAssignedRundown = null;
        $newJudgeIndex = null;

        $mySchedules = ScheduleReferee::with(['court.activeMatch', 'sessionTime', 'rundown'])
            ->where('referee_id', $this->referee->id)
            ->get();

        foreach ($mySchedules as $schedule) {
            if (! $schedule->court_id || ! $schedule->court) {
                continue;
            }

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
        if ($this->activeMatch->draft_type === 'embu') {
            $id = $this->activeMatch->active_registration_id;
        } else {
            $bracketNode = $this->activeMatch->active_bracket_node;
            if (! $bracketNode) {
                return;
            }

            $parts = explode('_', $bracketNode);
            $bracketSection = $parts[0] ?? 'ub';
            $bracketNodeIndex = (isset($parts[1]) && isset($parts[2])) ? $parts[1].'_'.$parts[2] : '0_0';

            $randoriMatch = RandoriMatchResult::firstOrCreate(
                ['match_number_id' => $this->activeMatch->id, 'bracket_node' => $bracketNode],
                ['bracket_node_index' => $bracketNodeIndex, 'bracket_section' => $bracketSection]
            );
            $id = $randoriMatch->id;
        }

        if (! $id) {
            return;
        }

        $existing = RefereeScoreDetail::where('match_number_id', $this->activeMatch->id)
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
            'goho_1' => '', 'goho_2' => '', 'goho_3' => '',
            'juho_1' => '', 'juho_2' => '', 'juho_3' => '',
            'ekspresi_1' => '', 'ekspresi_2' => '', 'ekspresi_3' => '', 'ekspresi_4' => '',
        ];
        $this->randoriItems = [
            'aka' => ['mujoken' => '', 'ippon' => '', 'wazaari' => '', 'batsu5' => '', 'batsu10' => '', 'yusei' => ''],
            'shiro' => ['mujoken' => '', 'ippon' => '', 'wazaari' => '', 'batsu5' => '', 'batsu10' => '', 'yusei' => ''],
        ];
        $this->notes = '';
        $this->totalScore = 0;
        $this->totalAka = 0;
        $this->totalShiro = 0;
        $this->calculateTotal();
    }

    public function updated($propertyName, $value)
    {
        if (str_starts_with($propertyName, 'embuItems') || str_starts_with($propertyName, 'randoriItems')) {
            // Strip leading zeros for numeric inputs (e.g., "05" -> "5")
            if (is_string($value) && strlen($value) > 1 && $value[0] === '0' && is_numeric($value) && (! isset($value[1]) || $value[1] !== '.')) {
                $sanitized = ltrim($value, '0');
                if ($sanitized === '') {
                    $sanitized = '0';
                }

                data_set($this, $propertyName, $sanitized);
            }
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        if ($this->activeMatch && $this->activeMatch->draft_type === 'embu') {
            $this->totalScore = 0;
            // Limit Embu score fields to maximum 10 mathematically
            foreach ($this->embuItems as $key => $val) {
                $numericVal = is_numeric($val) ? (float) $val : 0;
                if ($numericVal > 10) {
                    $numericVal = 10;
                    $this->embuItems[$key] = 10;
                }
                $this->totalScore += $numericVal;
            }
        } elseif ($this->activeMatch) {
            $aka = $this->randoriItems['aka'];
            $shiro = $this->randoriItems['shiro'];

            $this->totalAka = ((is_numeric($aka['mujoken']) ? $aka['mujoken'] : 0) * 15) +
                        ((is_numeric($aka['ippon']) ? $aka['ippon'] : 0) * 10) +
                        ((is_numeric($aka['wazaari']) ? $aka['wazaari'] : 0) * 5) +
                        ((is_numeric($aka['batsu5']) ? $aka['batsu5'] : 0) * 5) +
                        ((is_numeric($aka['batsu10']) ? $aka['batsu10'] : 0) * 10) +
                        ((is_numeric($aka['yusei']) ? $aka['yusei'] : 0) * 5);

            $this->totalShiro = ((is_numeric($shiro['mujoken']) ? $shiro['mujoken'] : 0) * 15) +
                          ((is_numeric($shiro['ippon']) ? $shiro['ippon'] : 0) * 10) +
                          ((is_numeric($shiro['wazaari']) ? $shiro['wazaari'] : 0) * 5) +
                          ((is_numeric($shiro['batsu5']) ? $shiro['batsu5'] : 0) * 5) +
                          ((is_numeric($shiro['batsu10']) ? $shiro['batsu10'] : 0) * 10) +
                          ((is_numeric($shiro['yusei']) ? $shiro['yusei'] : 0) * 5);

            $this->totalScore = $this->totalAka - $this->totalShiro; // Stored as a differential
        }
    }

    public function submitScore()
    {
        if (! $this->activeMatch || ! $this->referee) {
            return;
        }

        // Verify judge index from assignment just before saving to ensure accuracy
        $schedule = ScheduleReferee::where('referee_id', $this->referee->id)
            ->where('court_id', $this->assignedCourt?->id)
            ->where('session_time_id', $this->assignedSession?->id)
            ->where('rundown_id', $this->assignedRundown?->id)
            ->first();

        if (! $schedule) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal Menyimpan',
                'text' => 'Anda tidak memiliki penugasan yang valid untuk pertandingan ini.',
            ]);

            return;
        }

        $this->judgeIndex = $schedule->judge_index;

        if (! $this->judgeIndex) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal Menyimpan',
                'text' => 'Posisi juri Anda tidak terdefinisi.',
            ]);

            return;
        }

        if ($this->activeMatch->draft_type === 'embu') {
            $id = $this->activeMatch->active_registration_id;
            $scorableType = Registration::class;
            $bracketNode = null;
        } else {
            $bracketNode = $this->activeMatch->active_bracket_node;
            if (! $bracketNode) {
                $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Belum ada peserta yang dipanggil.']);

                return;
            }

            $parts = explode('_', $bracketNode);
            $bracketSection = $parts[0] ?? 'ub';
            $bracketNodeIndex = (isset($parts[1]) && isset($parts[2])) ? $parts[1].'_'.$parts[2] : '0_0';

            $randoriMatch = RandoriMatchResult::firstOrCreate(
                ['match_number_id' => $this->activeMatch->id, 'bracket_node' => $bracketNode],
                ['bracket_node_index' => $bracketNodeIndex, 'bracket_section' => $bracketSection]
            );
            $id = $randoriMatch->id;
            $scorableType = RandoriMatchResult::class;
        }

        if (! $id) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Belum ada peserta yang dipanggil.']);

            return;
        }

        $details = $this->activeMatch->draft_type === 'embu' ? $this->embuItems : $this->randoriItems;

        \DB::transaction(function () use ($id, $scorableType, $bracketNode, $details) {
            // 1. Save Granular Details
            RefereeScoreDetail::updateOrCreate(
                [
                    'match_number_id' => $this->activeMatch->id,
                    'referee_id' => $this->referee->id,
                    'scorable_type' => $scorableType,
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
                $column = 'judge_'.$this->judgeIndex;
                EmbuScore::updateOrCreate(
                    ['match_number_id' => $this->activeMatch->id, 'registration_id' => $id],
                    [$column => $this->totalScore]
                );
            } else {
                // Sync to RandoriJudgeScore via Service
                $service = app(RandoriScoringService::class);

                // AKA (Red)
                $service->setScore($this->activeMatch->id, $bracketNode, $this->judgeIndex, 'ippon', 'aka', $this->randoriItems['aka']['ippon'] + $this->randoriItems['aka']['mujoken']);
                $service->setScore($this->activeMatch->id, $bracketNode, $this->judgeIndex, 'waza_ari', 'aka', $this->randoriItems['aka']['wazaari'] + $this->randoriItems['aka']['yusei']);
                $service->setScore($this->activeMatch->id, $bracketNode, $this->judgeIndex, 'hansoku', 'aka', $this->randoriItems['aka']['batsu5'] + $this->randoriItems['aka']['batsu10']);

                // SHIRO (White)
                $service->setScore($this->activeMatch->id, $bracketNode, $this->judgeIndex, 'ippon', 'shiro', $this->randoriItems['shiro']['ippon'] + $this->randoriItems['shiro']['mujoken']);
                $service->setScore($this->activeMatch->id, $bracketNode, $this->judgeIndex, 'waza_ari', 'shiro', $this->randoriItems['shiro']['wazaari'] + $this->randoriItems['shiro']['yusei']);
                $service->setScore($this->activeMatch->id, $bracketNode, $this->judgeIndex, 'hansoku', 'shiro', $this->randoriItems['shiro']['batsu5'] + $this->randoriItems['shiro']['batsu10']);
            }
        });

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Skor Tersimpan',
            'text' => 'Nilai Juri '.$this->judgeIndex.' telah dicatat.',
        ]);
    }

    public function render()
    {
        return view('livewire.referee.referee-scoring-dashboard');
    }
}
