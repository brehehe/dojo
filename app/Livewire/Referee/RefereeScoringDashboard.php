<?php

namespace App\Livewire\Referee;

use App\Models\ActiveCourtReferee;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumberMergeDetail;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Models\ScheduleReferee;
use App\Models\Technique\Technique;
use App\Services\RandoriScoringService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public $matchNumberIds = [];

    public $specificMatchId = null;

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

    public bool $isFormOpen = false;

    public $signature = null;

    public function getIsTabletModeProperty()
    {
        return Auth::user()->judge_index && Auth::user()->court_id;
    }

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
        $this->loadActiveMatch();
    }

    private function getActiveIdentifier($match, $court = null)
    {
        if (! $match) {
            return null;
        }

        if ($match->draft_type === 'embu') {
            $subId = $court?->active_drawing_id ?? $match->active_registration_id;
        } else {
            $subId = $match->active_bracket_node;
        }

        return $match->id.'_'.$subId;
    }

    public function loadActiveMatch()
    {
        $user = Auth::user();
        $newActiveMatch = null;
        $newAssignedCourt = null;
        $newAssignedSession = null;
        $newAssignedRundown = null;
        $newJudgeIndex = null;

        // ─── 1. Identify Identity & Role ───────────────────────────
        if ($user->judge_index && $user->court_id) {
            // TABLET MODE: Role and Court are fixed from account
            $newAssignedCourt = Court::find($user->court_id);
            $newJudgeIndex = $user->judge_index;

            // Resolve Acting Referee dynamically from ActiveCourtReferee (manual override) or Schedule
            if ($newAssignedCourt && $newAssignedCourt->active_match_id) {
                $newActiveMatch = $newAssignedCourt->activeMatch;

                // Priority 1: Check ActiveCourtReferee (Manual override)
                $activeAssignment = ActiveCourtReferee::where('court_id', $newAssignedCourt->id)
                    ->where('judge_index', $newJudgeIndex)
                    ->first();

                if ($activeAssignment) {
                    $this->referee = $activeAssignment->referee;
                } else {
                    // Priority 2: Fallback to Schedule (Auto detection)
                    $activeDrawing = null;
                    if ($newAssignedCourt->active_drawing_id) {
                        $activeDrawing = DrawingMatchNumber::find($newAssignedCourt->active_drawing_id);
                    }
                    if (! $activeDrawing) {
                        $activeDrawing = DrawingMatchNumber::where('match_number_id', $newAssignedCourt->active_match_id)
                            ->where('court_id', $newAssignedCourt->id)
                            ->first();
                    }

                    if ($activeDrawing) {
                        $newAssignedSession = $activeDrawing->sessionTime;
                        $newAssignedRundown = $activeDrawing->rundown;

                        $schedule = ScheduleReferee::where('court_id', $newAssignedCourt->id)
                            ->where('judge_index', $newJudgeIndex)
                            ->where('rundown_id', $activeDrawing->rundown_id)
                            ->where('session_time_id', $activeDrawing->session_time_id)
                            ->first();

                        if ($schedule) {
                            $this->referee = $schedule->referee;
                        }
                    }
                }
            }
        } else {
            // PERSONAL MODE: Referee is fixed, Court/Role are dynamic
            $this->referee = Referee::where('user_id', $user->id)->first();

            if (! $this->referee) {
                return;
            }

            // Priority 1: Check ActiveCourtReferee (Manual override)
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
                $mySchedules = ScheduleReferee::with(['court.activeMatch', 'court.activeDrawing', 'sessionTime', 'rundown'])
                    ->where('referee_id', $this->referee->id)
                    ->whereNotNull('court_id')
                    ->get();

                foreach ($mySchedules as $schedule) {
                    if (! $schedule->court_id || ! $schedule->court) {
                        continue;
                    }

                    $court = $schedule->court;
                    if (! $court->active_match_id || ! $court->activeMatch) {
                        continue;
                    }

                    // Verify the court's active drawing belongs to this schedule's session/rundown.
                    // This prevents picking the wrong session when a referee is assigned to the
                    // same court (or multiple courts) across different sessions/rundowns.
                    $activeDrawing = $court->activeDrawing;
                    if ($activeDrawing) {
                        $sessionMatch = $activeDrawing->session_time_id == $schedule->session_time_id
                            && $activeDrawing->rundown_id == $schedule->rundown_id;

                        if (! $sessionMatch) {
                            continue;
                        }
                    }

                    $newActiveMatch = $court->activeMatch;
                    $newAssignedCourt = $court;
                    $newAssignedSession = $schedule->sessionTime;
                    $newAssignedRundown = $schedule->rundown;
                    $newJudgeIndex = $schedule->judge_index;
                    break;
                }
            }
        }

        $newId = $this->getActiveIdentifier($newActiveMatch, $newAssignedCourt);

        $participantCalled = false;
        if ($newActiveMatch) {
            $participantCalled = $newActiveMatch->draft_type === 'embu'
                ? ! is_null($newActiveMatch->active_registration_id)
                : ! is_null($newActiveMatch->active_bracket_node);
        }

        if ($participantCalled) {
            $this->isFormOpen = true;
        } else {
            // If participant is not called, we only close the form if there is no active match
            // or if the active drawing ID is cleared (which Panitera does to close the form).
            $activeDrawingId = $newAssignedCourt?->active_drawing_id;

            if (! $newActiveMatch || ! $activeDrawingId) {
                $this->isFormOpen = false;
            }
        }

        if ($this->currentActiveIdentifier !== $newId) {
            $this->currentActiveIdentifier = $newId;
            $this->activeMatch = $newActiveMatch;
            $this->assignedCourt = $newAssignedCourt;
            $this->assignedSession = $newAssignedSession;
            $this->assignedRundown = $newAssignedRundown;
            $this->judgeIndex = $newJudgeIndex;

            // Handle Merge Group IDs
            $this->matchNumberIds = [];
            $this->specificMatchId = null;
            if ($this->activeMatch) {
                $this->matchNumberIds = [$this->activeMatch->id];
                if ($this->activeMatch->mergeDetail) {
                    $this->matchNumberIds = MatchNumberMergeDetail::where('match_number_merge_id', $this->activeMatch->mergeDetail->match_number_merge_id)
                        ->pluck('match_number_id')
                        ->toArray();
                }

                // Identify specific match from active drawing
                $activeDrawing = $this->assignedCourt?->activeDrawing;
                if ($activeDrawing && in_array($activeDrawing->match_number_id, $this->matchNumberIds)) {
                    $this->specificMatchId = $activeDrawing->match_number_id;
                } else {
                    $this->specificMatchId = $this->activeMatch->id;
                }
            }

            $this->syncActiveMatchMeta();

            if ($this->activeMatch) {
                $this->loadExistingDetails();
            } else {
                $this->resetForm();
            }
        }
    }

    public function closeForm(): void
    {
        $this->isFormOpen = false;
        $this->currentActiveIdentifier = null; // Force reload on next poll
        $this->loadActiveMatch(); // Refresh state
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
                ->whereIn('match_numbers.id', $this->matchNumberIds)
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
        if (! $this->referee) {
            return;
        }

        if ($this->activeMatch->draft_type === 'embu') {
            // Reload court to get the latest active_drawing_id
            $currentCourt = $this->assignedCourt ? Court::find($this->assignedCourt->id) : null;
            $drawingId = $currentCourt?->active_drawing_id;

            if ($drawingId) {
                $id = $drawingId;
            } else {
                $id = $this->activeMatch->active_registration_id;
            }
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

        $existing = RefereeScoreDetail::where('match_number_id', $this->specificMatchId ?? $this->activeMatch->id)
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
            $this->signature = $existing->signature;
            $this->dispatch('signature-loaded', ['signature' => $this->signature]);
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
        $this->signature = null;
        $this->totalScore = 0;
        $this->totalAka = 0;
        $this->totalShiro = 0;
        $this->calculateTotal();
        $this->dispatch('form-reset');
    }

    public function updated($propertyName, $value)
    {
        if (str_starts_with($propertyName, 'embuItems') || str_starts_with($propertyName, 'randoriItems')) {
            if (is_string($value)) {
                $value = str_replace(',', '.', $value);
                data_set($this, $propertyName, $value);
            }

            // Strip leading zeros for numeric inputs (e.g., "05" -> "5")
            if (is_string($value) && strlen($value) > 1 && $value[0] === '0' && is_numeric($value) && (! isset($value[1]) || $value[1] !== '.')) {
                $sanitized = ltrim($value, '0');
                if ($sanitized === '') {
                    $sanitized = '0';
                }

                data_set($this, $propertyName, $sanitized);
                $value = $sanitized;
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
                $clampedVal = $numericVal;
                if ($numericVal !== 0.0) {
                    // Only clamp non-zero values (zero = not yet filled)
                    $clampedVal = max(0.0, min(10.0, $numericVal));
                }
                $this->totalScore += $clampedVal;
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

    public function submitScore($signatureData = null)
    {
        if ($signatureData) {
            $this->signature = $signatureData;
        }

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

        if (! $this->signature) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Tanda Tangan Diperlukan',
                'text' => 'Silakan tanda tangan terlebih dahulu pada kolom yang disediakan.',
            ]);

            return;
        }

        if ($this->activeMatch->draft_type === 'embu') {
            // Reload court to get the latest active_drawing_id
            $currentCourt = $this->assignedCourt ? Court::find($this->assignedCourt->id) : null;
            $drawingId = $currentCourt?->active_drawing_id;

            if ($drawingId) {
                $id = $drawingId;
                $scorableType = DrawingMatchNumber::class;
            } else {
                $id = $this->activeMatch->active_registration_id;
                $scorableType = Registration::class;
            }

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

        if ($this->activeMatch->draft_type === 'embu') {
            foreach ($this->embuItems as $key => $val) {
                $numericVal = is_numeric($val) ? (float) $val : 0;
                if ($numericVal !== 0.0) {
                    $this->embuItems[$key] = max(0.0, min(10.0, $numericVal));
                }
            }
            $details = $this->embuItems;
        } else {
            $details = $this->randoriItems;
        }

        DB::transaction(function () use ($id, $scorableType, $bracketNode, $details, $drawingId) {
            $targetMatchId = $this->specificMatchId ?? $this->activeMatch->id;

            // 1. Save Granular Details
            RefereeScoreDetail::updateOrCreate(
                [
                    'match_number_id' => $targetMatchId,
                    'referee_id' => $this->referee->id,
                    'scorable_type' => $scorableType,
                    'scorable_id' => $id,
                ],
                [
                    'judge_index' => $this->judgeIndex,
                    'details' => $details,
                    'total_calculated_score' => $this->totalScore,
                    'notes' => $this->notes,
                    'signature' => $this->signature,
                ]
            );

            // 2. Sync to Main Table for Quick Access
            if ($this->activeMatch->draft_type === 'embu') {
                $column = 'judge_'.$this->judgeIndex;

                $registrationId = $this->activeMatch->active_registration_id;
                if (! $registrationId && $drawingId) {
                    $drawing = DrawingMatchNumber::find($drawingId);
                    $registrationId = $drawing?->registration_id;
                }

                EmbuScore::updateOrCreate(
                    [
                        'match_number_id' => $targetMatchId,
                        'registration_id' => $registrationId,
                        'drawing_id' => $drawingId ?? null,
                    ],
                    [$column => $this->totalScore]
                );
            } else {
                // Sync to RandoriJudgeScore via Service
                $service = app(RandoriScoringService::class);

                // AKA (Red)
                $service->setScore($targetMatchId, $bracketNode, $this->judgeIndex, 'ippon', 'aka', $this->randoriItems['aka']['ippon'] + $this->randoriItems['aka']['mujoken']);
                $service->setScore($targetMatchId, $bracketNode, $this->judgeIndex, 'waza_ari', 'aka', $this->randoriItems['aka']['wazaari'] + $this->randoriItems['aka']['yusei']);
                $service->setScore($targetMatchId, $bracketNode, $this->judgeIndex, 'hansoku', 'aka', $this->randoriItems['aka']['batsu5'] + $this->randoriItems['aka']['batsu10']);

                // SHIRO (White)
                $service->setScore($targetMatchId, $bracketNode, $this->judgeIndex, 'ippon', 'shiro', $this->randoriItems['shiro']['ippon'] + $this->randoriItems['shiro']['mujoken']);
                $service->setScore($targetMatchId, $bracketNode, $this->judgeIndex, 'waza_ari', 'shiro', $this->randoriItems['shiro']['wazaari'] + $this->randoriItems['shiro']['yusei']);
                $service->setScore($targetMatchId, $bracketNode, $this->judgeIndex, 'hansoku', 'shiro', $this->randoriItems['shiro']['batsu5'] + $this->randoriItems['shiro']['batsu10']);
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
