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

    public function toggleReferee($id)
    {
        $id = (string) $id;
        if (in_array($id, $this->selectedReferees)) {
            $this->selectedReferees = array_values(array_diff($this->selectedReferees, [$id]));
        } else {
            if ($this->isDewanArbitraseMode) {
                $this->selectedReferees = [$id];
            } else {
                $this->selectedReferees[] = $id;
            }
        }
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

            // Get selected referee models to check their certification levels
            $selectedModels = Referee::whereIn('id', $this->selectedReferees)->get();
            $wasitUtama = $selectedModels->filter(fn ($r) => $r->certification_level === 'WASIT UTAMA');
            $others = $selectedModels->filter(fn ($r) => $r->certification_level !== 'WASIT UTAMA');

            // Enforce Wasit Utama only if there are Wasit Utama in the system
            $hasSystemWasitUtama = Referee::where('certification_level', 'WASIT UTAMA')->exists();

            if ($hasSystemWasitUtama && $wasitUtama->isEmpty()) {
                $this->addError('referees', 'Minimal 1 Wasit Utama harus dipilih untuk menjadi Wasit Pertama.');

                return;
            }

            // Reorder so that a Wasit Utama is first (judge_index = 1)
            $orderedIds = [];
            if ($wasitUtama->isNotEmpty()) {
                $firstUtama = $wasitUtama->first();
                $orderedIds[] = $firstUtama->id;

                $remainingUtama = $wasitUtama->slice(1);
                foreach ($remainingUtama as $r) {
                    $orderedIds[] = $r->id;
                }
                foreach ($others as $r) {
                    $orderedIds[] = $r->id;
                }
            } else {
                $orderedIds = $this->selectedReferees;
            }

            DB::beginTransaction();
            try {
                ScheduleReferee::where('rundown_id', $rId)->where('session_time_id', $sId)->where('court_id', $cId)->where('judge_index', '>', 0)->delete();
                foreach ($orderedIds as $index => $refereeId) {
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
        $arbitraseIds = Referee::whereHas('user', function ($q) {
            $q->role('Arbitrase');
        })->pluck('id')->toArray();

        $wasitUtamaIds = Referee::whereHas('user', function ($q) {
            $q->role('Perwasitan');
        })->where('certification_level', 'WASIT UTAMA')->pluck('id')->toArray();

        $refereeIds = Referee::whereHas('user', function ($q) {
            $q->role('Perwasitan');
        })->pluck('id')->toArray();

        if (count($arbitraseIds) < 1 || count($refereeIds) < 5) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Master Wasit minimal harus ada 1 Dewan Arbitrase dan 5 Wasit Lapangan.',
                'icon' => 'error',
            ]);

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
                $randomDewanId = collect($arbitraseIds)->random();
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
                    if (! empty($wasitUtamaIds)) {
                        $randomUtamaId = collect($wasitUtamaIds)->random();
                        $otherRefereePool = array_values(array_diff($refereeIds, [$randomUtamaId]));
                        $randomOtherIds = collect($otherRefereePool)->random(4)->toArray();

                        $panelIds = array_merge([$randomUtamaId], $randomOtherIds);
                    } else {
                        $panelIds = collect($refereeIds)->random(5)->toArray();
                    }

                    ScheduleReferee::where('rundown_id', $shift->rundown_id)
                        ->where('session_time_id', $shift->session_time_id)
                        ->where('court_id', $courtId)
                        ->where('judge_index', '>', 0)
                        ->delete();
                    foreach ($panelIds as $index => $refereeId) {
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

        $operator = DB::connection()->getDriverName() === 'sqlite' ? 'like' : 'ilike';
        $refereesQuery = Referee::with('user');

        if ($this->assigningBlock) {
            if ($this->isDewanArbitraseMode) {
                $refereesQuery->whereHas('user', function ($q) {
                    $q->role('Arbitrase');
                });
            } else {
                $refereesQuery->whereHas('user', function ($q) {
                    $q->role('Perwasitan');
                });
            }
        }

        if (! empty($this->searchReferee)) {
            $refereesQuery->where(function ($sub) use ($operator) {
                $sub->whereHas('user', function ($q) use ($operator) {
                    $q->where('name', $operator, '%'.$this->searchReferee.'%');
                })->orWhere('license_number', $operator, '%'.$this->searchReferee.'%');
            });
        }
        $referees = $refereesQuery->get()->sortBy([['certification_level', 'asc']]);

        return view('livewire.admin.new-generate-referee-index', [
            'paginatedShifts' => $paginatedShifts,
            'allReferees' => $referees,
        ]);
    }
}
