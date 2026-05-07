<?php

namespace App\Livewire\Referee;

use App\Models\ActiveCourtReferee;
use App\Models\EmbuScore;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Models\ScheduleReferee;
use App\Models\Technique\Technique;
use App\Services\RandoriScoringService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class RefereeScoringDashboard extends Component
{
    public $referee;

    public $activeMatch;

    public ?string $currentActiveIdentifier = null;

    public $assignedCourt = null;

    public $assignedSession = null;

    public $assignedRundown = null;

    public $judgeIndex = null;

    public $activeContingentName = '-';

    public $activeRoundLabel = '-';

    public $activeTechniqueLabel = '-';

    public array $activeTechniqueList = [];

    public array $activeAthleteNames = [];

    public bool $activeIsTeamCategory = false;

    // Embu Itemized Scores
    public $embuItems = [
        'goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0,
        'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0,
        'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0,
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

        // Priority 1: Check ActiveCourtReferee (Manual override from Scoring Dashboard)
        $activeAssignment = ActiveCourtReferee::with(['court.activeMatch'])
            ->where('referee_id', $this->referee->id)
            ->first();

        if ($activeAssignment && $activeAssignment->court?->active_match_id) {
            $newActiveMatch = $activeAssignment->court->activeMatch;
            $newAssignedCourt = $activeAssignment->court;
            $newJudgeIndex = $activeAssignment->judge_index;
        }

        // Priority 2: Fallback to Schedule (Auto detection)
        if (! $newActiveMatch) {
            $mySchedules = ScheduleReferee::with(['court.activeMatch', 'sessionTime', 'rundown'])
                ->where('referee_id', $this->referee->id)
                ->get();

            foreach ($mySchedules as $schedule) {
                if (! $schedule->court_id || ! $schedule->court) {
                    continue;
                }

                $court = $schedule->court;
                if ($court->active_match_id && $court->activeMatch) {
                    $newActiveMatch = $court->activeMatch;
                    $newAssignedCourt = $court;
                    $newAssignedSession = $schedule->sessionTime;
                    $newAssignedRundown = $schedule->rundown;
                    $newJudgeIndex = $schedule->judge_index;
                    break;
                }
            }
        }

        $newId = $this->getActiveIdentifier($newActiveMatch);

        if ($this->currentActiveIdentifier !== $newId) {
            $this->currentActiveIdentifier = $newId;
            $this->activeMatch = $newActiveMatch;
            $this->assignedCourt = $newAssignedCourt;
            $this->assignedSession = $newAssignedSession;
            $this->assignedRundown = $newAssignedRundown;
            $this->judgeIndex = $newJudgeIndex;
            $this->syncActiveMatchMeta();

            if ($this->activeMatch) {
                $this->loadExistingDetails();
            } else {
                $this->resetForm();
            }
        }
    }

    public function getJudgeLabelProperty()
    {
        return match ($this->judgeIndex) {
            1 => 'Wasit Nasional (Ketua)',
            2 => 'Wasit Daerah 1',
            3 => 'Wasit Daerah 2',
            4 => 'Wasit Pembantu 1',
            5 => 'Wasit Pembantu 2',
            default => 'Juri '.$this->judgeIndex
        };
    }

    private function syncActiveMatchMeta(): void
    {
        $this->activeContingentName = '-';
        $this->activeRoundLabel = $this->activeMatch?->round ?? ($this->assignedRundown?->name ?? '-');
        $this->activeTechniqueLabel = '-';
        $this->activeTechniqueList = [];
        $this->activeAthleteNames = [];
        $this->activeIsTeamCategory = false;

        if (! $this->activeMatch?->active_registration_id) {
            return;
        }

        $registration = Registration::with([
            'contingent',
            'athletes.matchNumbers' => fn ($query) => $query
                ->whereKey($this->activeMatch->id)
                ->wherePivot('registration_id', $this->activeMatch->active_registration_id),
        ])->find($this->activeMatch->active_registration_id);

        if (! $registration) {
            return;
        }

        $this->activeContingentName = $registration->contingent?->name ?? '-';
        $activeAthletes = $registration->athletes
            ->filter(fn ($athlete) => $athlete->matchNumbers->isNotEmpty())
            ->values();

        $this->activeAthleteNames = $activeAthletes
            ->pluck('name')
            ->filter()
            ->values()
            ->all();
        $this->activeIsTeamCategory = count($this->activeAthleteNames) > 2;

        $selectedTechniqueIds = $activeAthletes
            ->flatMap(fn ($athlete) => $athlete->matchNumbers->pluck('pivot.technique_ids'))
            ->filter()
            ->first();

        if (! $selectedTechniqueIds) {
            return;
        }

        $decodedTechniqueIds = json_decode($selectedTechniqueIds, true);

        $techniqueIds = collect(is_array($decodedTechniqueIds) ? $decodedTechniqueIds : explode(',', (string) $selectedTechniqueIds))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        if ($techniqueIds->isEmpty()) {
            return;
        }

        $techniqueNames = Technique::whereIn('id', $techniqueIds)
            ->get()
            ->keyBy('id');

        $selectedTechniqueNames = $techniqueIds
            ->map(fn ($id) => $techniqueNames->get($id)?->name)
            ->filter()
            ->values()
            ->all();

        if ($selectedTechniqueNames !== []) {
            $this->activeTechniqueLabel = implode(', ', $selectedTechniqueNames);
            $this->activeTechniqueList = $selectedTechniqueNames;
        }
    }

    public function getActiveContestantLabelProperty(): string
    {
        if ($this->activeContingentName === '-' && $this->activeAthleteNames === []) {
            return '-';
        }

        if ($this->activeIsTeamCategory || $this->activeAthleteNames === []) {
            return $this->activeContingentName;
        }

        return sprintf(
            '%s (%s)',
            $this->activeContingentName,
            implode(' & ', $this->activeAthleteNames)
        );
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
            'goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0,
            'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0,
            'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0,
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
            // Embu score range: 8.0 – 10.0
            foreach ($this->embuItems as $key => $val) {
                $numericVal = is_numeric($val) ? (float) $val : 0;
                if ($numericVal !== 0.0) {
                    // Only clamp non-zero values (zero = not yet filled)
                    $numericVal = max(8.0, min(10.0, $numericVal));
                    $this->embuItems[$key] = $numericVal;
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

        // 1. Check Manual Assignment Override
        $activeAssignment = ActiveCourtReferee::where('referee_id', $this->referee->id)
            ->where('court_id', $this->assignedCourt?->id)
            ->first();

        $validAssignment = null;
        if ($activeAssignment) {
            $validAssignment = $activeAssignment;
        } else {
            // 2. Fallback to Schedule
            $validAssignment = ScheduleReferee::where('referee_id', $this->referee->id)
                ->where('court_id', $this->assignedCourt?->id)
                ->where('session_time_id', $this->assignedSession?->id)
                ->where('rundown_id', $this->assignedRundown?->id)
                ->first();
        }

        if (! $validAssignment) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal Menyimpan',
                'text' => 'Anda tidak memiliki penugasan yang valid untuk pertandingan ini.',
            ]);

            return;
        }

        $this->judgeIndex = $validAssignment->judge_index;

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
