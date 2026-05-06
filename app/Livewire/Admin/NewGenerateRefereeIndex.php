<?php

namespace App\Livewire\Admin;

use App\Models\DrawingMatchNumber;
use App\Models\Referee;
use App\Models\ScheduleReferee;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium', ['title' => 'Penugasan Wasit (Referee Assignment)'])]
class NewGenerateRefereeIndex extends Component
{
    use WithPagination;

    public $searchShift = '';

    public $searchReferee = '';

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
                ScheduleReferee::where('rundown_id', $rId)
                    ->where('session_time_id', $sId)
                    ->whereNull('court_id')
                    ->where('judge_index', 0)
                    ->delete();
            } else {
                ScheduleReferee::updateOrCreate(
                    ['rundown_id' => $rId, 'session_time_id' => $sId, 'court_id' => null, 'judge_index' => 0],
                    ['referee_id' => $this->selectedReferees[0]]
                );
            }
            $this->dispatch('swal', ['title' => 'Berhasil!', 'text' => 'Dewan Arbitrase telah diperbarui.', 'icon' => 'success']);
        } else {
            if (count($this->selectedReferees) < 5) {
                $this->addError('referees', 'Minimal 5 Wasit harus dipilih.');

                return;
            }

            DB::beginTransaction();
            try {
                ScheduleReferee::where('rundown_id', $rId)->where('session_time_id', $sId)->where('court_id', $cId)->where('judge_index', '>', 0)->delete();
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
                $this->dispatch('swal', ['title' => 'Berhasil!', 'text' => 'Panel wasit lapangan telah diperbarui.', 'icon' => 'success']);
            } catch (\Exception $e) {
                DB::rollBack();
                $this->addError('referees', 'Gagal: '.$e->getMessage());

                return;
            }
        }
        $this->closeAssignModal();
    }

    public function autoGenerateAllReferees()
    {
        $allRefereeIds = Referee::pluck('id')->toArray();
        if (count($allRefereeIds) < 6) {
            $this->dispatch('swal', ['title' => 'Gagal!', 'text' => 'Master Wasit minimal harus ada 6 orang (1 Dewan + 5 Wasit Lapangan).', 'icon' => 'error']);

            return;
        }

        $uniqueShifts = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()->whereNotNull('rundown_id')->whereNotNull('session_time_id')->get();

        $countGenerated = 0;
        foreach ($uniqueShifts as $shift) {
            // Generate Dewan Arbitrase if empty
            $existingDewan = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNull('court_id')
                ->where('judge_index', 0)
                ->exists();

            if (! $existingDewan) {
                $randomDewanId = collect($allRefereeIds)->random();
                ScheduleReferee::create([
                    'rundown_id' => $shift->rundown_id,
                    'session_time_id' => $shift->session_time_id,
                    'court_id' => null,
                    'referee_id' => $randomDewanId,
                    'judge_index' => 0,
                ]);
            }

            // Generate Court Panels
            $courtsInShift = DrawingMatchNumber::select('court_id')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNotNull('court_id')
                ->distinct()
                ->pluck('court_id');

            foreach ($courtsInShift as $courtId) {
                $existing = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                    ->where('session_time_id', $shift->session_time_id)
                    ->where('court_id', $courtId)
                    ->where('judge_index', '>', 0)
                    ->count();

                if ($existing < 5) {
                    $randomIds = collect($allRefereeIds)->random(5)->toArray();
                    ScheduleReferee::where('rundown_id', $shift->rundown_id)
                        ->where('session_time_id', $shift->session_time_id)
                        ->where('court_id', $courtId)
                        ->where('judge_index', '>', 0)
                        ->delete();

                    foreach ($randomIds as $index => $refereeId) {
                        ScheduleReferee::create([
                            'rundown_id' => $shift->rundown_id,
                            'session_time_id' => $shift->session_time_id,
                            'court_id' => $courtId,
                            'referee_id' => $refereeId,
                            'judge_index' => $index + 1,
                        ]);
                    }
                    $countGenerated++;
                }
            }
        }

        $this->dispatch('swal', ['title' => 'Selesai!', 'text' => "Dewan Arbitrase & $countGenerated blok lapangan telah di-generate otomatis.", 'icon' => 'success']);
    }

    public function render()
    {
        $shiftsQuery = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()->with(['rundown', 'sessionTime'])->whereNotNull('rundown_id')->whereNotNull('session_time_id');

        $paginatedShifts = $shiftsQuery->orderBy('rundown_id')->orderBy('session_time_id')->paginate(5);

        $paginatedShifts->getCollection()->transform(function ($shift) {
            $shift->active_courts = DrawingMatchNumber::select('court_id')
                ->where('rundown_id', $shift->rundown_id)->where('session_time_id', $shift->session_time_id)->whereNotNull('court_id')
                ->distinct()->with('court')->orderBy('court_id')->get();
            $shift->assigned_referees = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $shift->rundown_id)->where('session_time_id', $shift->session_time_id)->get();

            return $shift;
        });

        $refereesQuery = Referee::with('user');
        if (! empty($this->searchReferee)) {
            $refereesQuery->whereHas('user', function ($q) {
                $q->where('name', 'like', '%'.$this->searchReferee.'%');
            })->orWhere('license_number', 'like', '%'.$this->searchReferee.'%');
        }
        $referees = $refereesQuery->get()->sortBy([['certification_level', 'asc']]);

        return view('livewire.admin.new-generate-referee-index', [
            'paginatedShifts' => $paginatedShifts,
            'allReferees' => $referees,
        ]);
    }
}
