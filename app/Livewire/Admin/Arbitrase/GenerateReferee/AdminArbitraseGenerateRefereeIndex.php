<?php

namespace App\Livewire\Admin\Arbitrase\GenerateReferee;

use App\Models\DrawingMatchNumber;
use App\Models\Referee;
use App\Models\ScheduleReferee;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminArbitraseGenerateRefereeIndex extends Component
{
    use WithPagination;

    public $searchShift = '';

    public $searchReferee = '';

    // Active block being assigned [rundown_id, session_time_id, court_id]
    // if court_id is null, it means giving Dewan Arbitrase
    public $assigningBlock = null;

    public $selectedReferees = [];

    public $isDewanArbitraseMode = false;

    public function paginationView(): string
    {
        return 'livewire.admin.pagination';
    }

    public function openAssignModal($rundownId, $sessionId, $courtId = null)
    {
        $this->assigningBlock = [
            'rundown_id' => (int) $rundownId,
            'session_time_id' => (int) $sessionId,
            'court_id' => $courtId ? (int) $courtId : null,
        ];

        $this->isDewanArbitraseMode = is_null($courtId);

        $existing = ScheduleReferee::where('rundown_id', $rundownId)
            ->where('session_time_id', $sessionId);

        if ($this->isDewanArbitraseMode) {
            $existing->whereNull('court_id')->where('judge_index', 0);
        } else {
            $existing->where('court_id', $courtId)->where('judge_index', '>', 0);
        }

        $this->selectedReferees = $existing->pluck('referee_id')->map(fn ($id) => (string) $id)->toArray();
        $this->searchReferee = '';
    }

    public function closeAssignModal()
    {
        $this->assigningBlock = null;
        $this->selectedReferees = [];
        $this->searchReferee = '';
    }

    public function saveReferees()
    {
        if (! $this->assigningBlock) {
            return;
        }

        $rId = $this->assigningBlock['rundown_id'];
        $sId = $this->assigningBlock['session_time_id'];
        $cId = $this->assigningBlock['court_id'];

        if ($this->isDewanArbitraseMode) {
            if (count($this->selectedReferees) > 1) {
                $this->addError('referees', 'Pilih maksimal 1 Wasit untuk Dewan Arbitrase.');

                return;
            }
            if (count($this->selectedReferees) === 0) {
                // Clear Dewan Arbitrase
                ScheduleReferee::where('rundown_id', $rId)
                    ->where('session_time_id', $sId)
                    ->whereNull('court_id')
                    ->where('judge_index', 0)
                    ->delete();
            } else {
                ScheduleReferee::updateOrCreate(
                    [
                        'rundown_id' => $rId,
                        'session_time_id' => $sId,
                        'court_id' => null,
                        'judge_index' => 0,
                    ],
                    [
                        'referee_id' => $this->selectedReferees[0],
                    ]
                );
            }
            session()->flash('message', 'Dewan Arbitrase berhasil disimpan.');
        } else {
            if (count($this->selectedReferees) < 5) {
                $this->addError('referees', 'Minimal 5 Wasit harus dipilih untuk satu Lapangan.');

                return;
            }

            DB::beginTransaction();
            try {
                // Clear old
                ScheduleReferee::where('rundown_id', $rId)
                    ->where('session_time_id', $sId)
                    ->where('court_id', $cId)
                    ->where('judge_index', '>', 0)
                    ->delete();

                // Save new
                foreach ($this->selectedReferees as $index => $refereeId) {
                    ScheduleReferee::create([
                        'rundown_id' => $rId,
                        'session_time_id' => $sId,
                        'court_id' => $cId,
                        'referee_id' => $refereeId,
                        'judge_index' => $index + 1,
                    ]);
                }
                DB::commit();
                session()->flash('message', 'Panel wasit untuk Lapangan berhasil disimpan.');
            } catch (\Exception $e) {
                DB::rollBack();
                $this->addError('referees', 'Gagal menyimpan: '.$e->getMessage());

                return;
            }
        }

        $this->closeAssignModal();
        $this->dispatch('referees-saved');
    }

    public function autoGenerateAllReferees()
    {
        $allRefereeIds = Referee::pluck('id')->toArray();

        if (count($allRefereeIds) < 5) {
            session()->flash('error', 'Data Master Wasit tidak mencukupi (Minimal 5).');

            return;
        }

        // Generate for all active Courts configured in DrawingMatchNumber
        $activeCourts = DrawingMatchNumber::select('rundown_id', 'session_time_id', 'court_id')
            ->distinct()
            ->whereNotNull('rundown_id')
            ->whereNotNull('session_time_id')
            ->whereNotNull('court_id')
            ->get();

        $countGenerated = 0;

        foreach ($activeCourts as $courtBlock) {
            // Check if this court block already has panel
            $existing = ScheduleReferee::where('rundown_id', $courtBlock->rundown_id)
                ->where('session_time_id', $courtBlock->session_time_id)
                ->where('court_id', $courtBlock->court_id)
                ->where('judge_index', '>', 0)
                ->count();

            if ($existing < 5) {
                $randomIds = collect($allRefereeIds)->random(5)->toArray();

                // Keep Dewan Arbitrase intact if any, clear the judges
                ScheduleReferee::where('rundown_id', $courtBlock->rundown_id)
                    ->where('session_time_id', $courtBlock->session_time_id)
                    ->where('court_id', $courtBlock->court_id)
                    ->where('judge_index', '>', 0)
                    ->delete();

                foreach ($randomIds as $index => $refereeId) {
                    ScheduleReferee::create([
                        'rundown_id' => $courtBlock->rundown_id,
                        'session_time_id' => $courtBlock->session_time_id,
                        'court_id' => $courtBlock->court_id,
                        'referee_id' => $refereeId,
                        'judge_index' => $index + 1,
                    ]);
                }
                $countGenerated++;
            }
        }

        // Random Dewan Arbitrase
        $shifts = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()
            ->whereNotNull('rundown_id')
            ->get();

        foreach ($shifts as $shift) {
            $hasDewan = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNull('court_id')
                ->where('judge_index', 0)
                ->exists();

            if (! $hasDewan) {
                ScheduleReferee::create([
                    'rundown_id' => $shift->rundown_id,
                    'session_time_id' => $shift->session_time_id,
                    'court_id' => null,
                    'referee_id' => collect($allRefereeIds)->random(),
                    'judge_index' => 0,
                ]);
            }
        }

        session()->flash('message', "Otomatisasi Selesai! $countGenerated Blok Lapangan berhasil ditugaskan wasit.");
    }

    public function render()
    {
        // 1. Get Distinct Shifts
        $shiftsQuery = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()
            ->with(['rundown', 'sessionTime'])
            ->whereNotNull('rundown_id')
            ->whereNotNull('session_time_id');

        // Note: Can't easily filter by "shift name" on a distinct query without JOINs.
        // For simplicity, we just order them.
        $paginatedShifts = $shiftsQuery
            ->orderBy('rundown_id')
            ->orderBy('session_time_id')
            ->paginate(5);

        // Transform to eager load related active courts and referees for that shift
        $paginatedShifts->getCollection()->transform(function ($shift) {
            $courtsInShift = DrawingMatchNumber::select('court_id')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNotNull('court_id')
                ->distinct()
                ->with('court')
                ->orderBy('court_id')
                ->get();

            $shift->active_courts = $courtsInShift;

            $shift->assigned_referees = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->get();

            return $shift;
        });

        // Get referees for selection
        $refereesQuery = Referee::with('user');
        if (! empty($this->searchReferee)) {
            $refereesQuery->whereHas('user', function ($q) {
                $q->where('name', 'ilike', '%'.$this->searchReferee.'%');
            })->orWhere('license_number', 'ilike', '%'.$this->searchReferee.'%')
                ->orWhere('certification_level', 'ilike', '%'.$this->searchReferee.'%');
        }

        $referees = $refereesQuery->get()->sortBy([
            ['certification_level', 'asc'],
        ]);

        return view('livewire.admin.arbitrase.generate-referee.admin-arbitrase-generate-referee-index', [
            'paginatedShifts' => $paginatedShifts,
            'allReferees' => $referees,
        ]);
    }
}
