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
            $this->resetErrorBag('referees');
        } else {
            if ($this->isDewanArbitraseMode) {
                $this->selectedReferees = [$id];
                $this->resetErrorBag('referees');
            } else {
                if (count($this->selectedReferees) >= 5) {
                    $this->dispatch('swal', [
                        'title' => 'Peringatan!',
                        'text' => 'Maksimal 5 wasit yang dapat dipilih.',
                        'icon' => 'warning',
                    ]);

                    return;
                }
                $this->selectedReferees[] = $id;
                $this->resetErrorBag('referees');
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

        // Check for duplicates in the same shift (rundown_id & session_time_id)
        $query = ScheduleReferee::where('rundown_id', $rId)
            ->where('session_time_id', $sId);

        if ($this->isDewanArbitraseMode) {
            // Exclude current Dewan Arbitrase record
            $query->where(function ($q) {
                $q->whereNotNull('court_id')
                    ->orWhere('judge_index', '>', 0);
            });
        } else {
            // Exclude current court being edited
            $query->where(function ($q) use ($cId) {
                $q->whereNull('court_id')
                    ->orWhere('court_id', '!=', $cId)
                    ->orWhere('judge_index', 0);
            });
        }

        $alreadyAssignedRefereeIds = $query->pluck('referee_id')->map(fn ($id) => (string) $id)->toArray();
        $duplicates = array_intersect($this->selectedReferees, $alreadyAssignedRefereeIds);

        if (! empty($duplicates)) {
            $duplicateNames = Referee::whereIn('id', $duplicates)->with('user')->get()->pluck('user.name')->join(', ');
            $this->addError('referees', 'Wasit berikut sudah ditugaskan pada sesi ini di lapangan lain atau Dewan Arbitrase: '.$duplicateNames);

            return;
        }

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
            if (count($this->selectedReferees) !== 5) {
                $this->addError('referees', 'Harus memilih tepat 5 Wasit.');

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
            $q->whereHas('roles', function ($r) {
                $r->whereIn('name', ['Perwasitan', 'Koordinator Lapangan']);
            });
        })->where('certification_level', 'WASIT UTAMA')->pluck('id')->toArray();

        $refereeIds = Referee::whereHas('user', function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->whereIn('name', ['Perwasitan', 'Koordinator Lapangan']);
            });
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
            // Keep track of all referees assigned in this shift to ensure uniqueness
            $assignedInShift = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->pluck('referee_id')
                ->toArray();

            // Generate Dewan Arbitrase if empty
            $existingDewan = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNull('court_id')
                ->where('judge_index', 0)
                ->first();

            if (! $existingDewan) {
                $availableArbitrase = array_values(array_diff($arbitraseIds, $assignedInShift));
                $randomDewanId = ! empty($availableArbitrase) ? collect($availableArbitrase)->random() : collect($arbitraseIds)->random();

                ScheduleReferee::create([
                    'rundown_id' => $shift->rundown_id,
                    'session_time_id' => $shift->session_time_id,
                    'court_id' => null,
                    'referee_id' => $randomDewanId,
                    'judge_index' => 0,
                ]);
                $assignedInShift[] = $randomDewanId;
            } else {
                if (! in_array($existingDewan->referee_id, $assignedInShift)) {
                    $assignedInShift[] = $existingDewan->referee_id;
                }
            }

            // Generate Court Panels
            $courtsInShift = DrawingMatchNumber::select('court_id')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNotNull('court_id')
                ->distinct()
                ->pluck('court_id');

            foreach ($courtsInShift as $courtId) {
                $existingCount = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                    ->where('session_time_id', $shift->session_time_id)
                    ->where('court_id', $courtId)
                    ->where('judge_index', '>', 0)
                    ->count();

                if ($existingCount !== 5) {
                    // Remove existing referee assignments on this court from the tracking array if any
                    $existingCourtRefs = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                        ->where('session_time_id', $shift->session_time_id)
                        ->where('court_id', $courtId)
                        ->where('judge_index', '>', 0)
                        ->pluck('referee_id')
                        ->toArray();
                    $assignedInShift = array_values(array_diff($assignedInShift, $existingCourtRefs));

                    $panelIds = [];

                    // 1. Pick a Wasit Utama if available and not already assigned in this shift
                    if (! empty($wasitUtamaIds)) {
                        $availableUtama = array_values(array_diff($wasitUtamaIds, $assignedInShift));
                        if (! empty($availableUtama)) {
                            $randomUtamaId = collect($availableUtama)->random();
                            $panelIds[] = $randomUtamaId;
                        }
                    }

                    // 2. Pick the other 4 (or 5 if there's no available Wasit Utama)
                    $neededCount = 5 - count($panelIds);
                    $availableOthers = array_values(array_diff($refereeIds, array_merge($assignedInShift, $panelIds)));
                    if (count($availableOthers) < $neededCount) {
                        // Fallback to all referee pool (excluding currently selected panel IDs)
                        $availableOthers = array_values(array_diff($refereeIds, $panelIds));
                    }
                    $randomOtherIds = collect($availableOthers)->random(min($neededCount, count($availableOthers)))->toArray();
                    $panelIds = array_merge($panelIds, $randomOtherIds);

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

                    $assignedInShift = array_values(array_unique(array_merge($assignedInShift, $panelIds)));
                    $countGenerated++;
                } else {
                    // Make sure existing court referees are added to assignedInShift
                    $existingCourtRefs = ScheduleReferee::where('rundown_id', $shift->rundown_id)
                        ->where('session_time_id', $shift->session_time_id)
                        ->where('court_id', $courtId)
                        ->where('judge_index', '>', 0)
                        ->pluck('referee_id')
                        ->toArray();
                    $assignedInShift = array_values(array_unique(array_merge($assignedInShift, $existingCourtRefs)));
                }
            }
        }

        $this->dispatch('swal', ['title' => 'Selesai!', 'text' => "Dewan Arbitrase & $countGenerated blok lapangan telah di-generate otomatis.", 'icon' => 'success']);
    }

    public function clearAllAssignments()
    {
        $uniqueShifts = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()->whereNotNull('rundown_id')->whereNotNull('session_time_id')->get();

        foreach ($uniqueShifts as $shift) {
            ScheduleReferee::where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->delete();
        }

        $this->dispatch('swal', ['title' => 'Berhasil!', 'text' => 'Semua penugasan wasit telah dihapus.', 'icon' => 'success']);
    }

    public function resetAndGenerateAllReferees()
    {
        $uniqueShifts = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()->whereNotNull('rundown_id')->whereNotNull('session_time_id')->get();

        foreach ($uniqueShifts as $shift) {
            ScheduleReferee::where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->delete();
        }

        $this->autoGenerateAllReferees();
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
                    $q->whereHas('roles', function ($r) {
                        $r->whereIn('name', ['Perwasitan', 'Koordinator Lapangan']);
                    });
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
