<?php

namespace App\Livewire\Admin;

use App\Exports\RefereeAssignmentExport;
use App\Models\DrawingMatchNumber;
use App\Models\Referee;
use App\Models\ScheduleReferee;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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

            // Get selected referee models to check their certification levels and cities
            $selectedModels = Referee::whereIn('id', $this->selectedReferees)->get();

            // Validate same city constraint: at least 2 referees from the same city
            $cities = $selectedModels->pluck('city')->filter(fn ($c) => ! empty(trim($c)))->map(fn ($c) => strtolower(trim($c)))->toArray();
            $cityCounts = array_count_values($cities);
            $hasSameCity = false;
            foreach ($cityCounts as $count) {
                if ($count >= 2) {
                    $hasSameCity = true;
                    break;
                }
            }

            if (! $hasSameCity) {
                $this->addError('referees', 'Dalam 1 lapangan, minimal harus ada 2 wasit yang berasal dari kota yang sama.');

                return;
            }

            // Separate non-pembantu and pembantu
            $nonPembantus = $selectedModels->filter(fn ($r) => $r->certification_level !== 'WASIT PEMBANTU');
            $pembantus = $selectedModels->filter(fn ($r) => $r->certification_level === 'WASIT PEMBANTU');

            if ($nonPembantus->count() < 2) {
                $this->addError('referees', 'Posisi 1 dan 2 harus diisi oleh Wasit Utama atau Wasit (tidak boleh Wasit Pembantu).');

                return;
            }

            // Sort non-pembantus so WASIT UTAMA comes first
            $nonPembantusSorted = $nonPembantus->sortBy(fn ($r) => $r->certification_level === 'WASIT UTAMA' ? 0 : 1)->values();

            // Place first two non-pembantus in positions 1 and 2, and the rest in 3, 4, 5
            $orderedIds = [];
            $orderedIds[] = $nonPembantusSorted[0]->id;
            $orderedIds[] = $nonPembantusSorted[1]->id;

            $remaining = $nonPembantusSorted->slice(2)->concat($pembantus)->values();
            foreach ($remaining as $r) {
                $orderedIds[] = $r->id;
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

        // Get all pool referees (Perwasitan or Koordinator Lapangan)
        $allReferees = Referee::whereHas('user', function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->whereIn('name', ['Perwasitan', 'Koordinator Lapangan']);
            });
        })->get();

        if (count($arbitraseIds) < 1 || $allReferees->count() < 5) {
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

                    // Filter available referees in this shift
                    $availableRefs = $allReferees->reject(fn ($r) => in_array($r->id, $assignedInShift));

                    $selectedRefs = null;

                    // Find cities with multiple referees
                    $citiesWithMultiple = $availableRefs->filter(fn ($r) => ! empty(trim($r->city)))
                        ->groupBy(fn ($r) => strtolower(trim($r->city)))
                        ->filter(fn ($group) => $group->count() >= 2);

                    if ($citiesWithMultiple->isNotEmpty()) {
                        $citiesWithMultiple = $citiesWithMultiple->shuffle();
                        foreach ($citiesWithMultiple as $city => $refsInCity) {
                            $refsInCityArray = $refsInCity->values();
                            $pairs = [];
                            for ($i = 0; $i < $refsInCityArray->count(); $i++) {
                                for ($j = $i + 1; $j < $refsInCityArray->count(); $j++) {
                                    $pairs[] = [$refsInCityArray[$i], $refsInCityArray[$j]];
                                }
                            }
                            shuffle($pairs);

                            foreach ($pairs as $pairArray) {
                                $pair = collect($pairArray);
                                $restPool = $availableRefs->reject(fn ($r) => $pair->contains('id', $r->id));

                                // Try to ensure there is at least one female referee (gender = P)
                                $pairHasFemale = $pair->contains(fn ($r) => $r->gender === 'P');
                                $femaleNeeded = ! $pairHasFemale && $restPool->contains(fn ($r) => $r->gender === 'P');

                                $chosenRefs = collect($pair);
                                if ($femaleNeeded) {
                                    $femaleRef = $restPool->filter(fn ($r) => $r->gender === 'P')->random();
                                    $chosenRefs->push($femaleRef);
                                    $restPool = $restPool->reject(fn ($r) => $r->id === $femaleRef->id);
                                }

                                $utamas = $restPool->filter(fn ($r) => $r->certification_level === 'WASIT UTAMA');
                                $wasits = $restPool->filter(fn ($r) => $r->certification_level !== 'WASIT UTAMA' && $r->certification_level !== 'WASIT PEMBANTU');

                                $totalNonPembantus = $chosenRefs->filter(fn ($r) => $r->certification_level !== 'WASIT PEMBANTU')->count() + $utamas->count() + $wasits->count();

                                if ($totalNonPembantus >= 2 && ($chosenRefs->count() + $restPool->count() >= 5)) {
                                    // Prefer to include exactly 1 WASIT UTAMA in the panel if one is available and not already in chosenRefs
                                    $hasUtama = $chosenRefs->contains(fn ($r) => $r->certification_level === 'WASIT UTAMA');
                                    if (! $hasUtama && $utamas->isNotEmpty()) {
                                        $chosenUtama = $utamas->random();
                                        $chosenRefs->push($chosenUtama);
                                        $restPool = $restPool->reject(fn ($r) => $r->id === $chosenUtama->id);
                                    }

                                    // Ensure we have at least 2 non-pembantus
                                    $nonPembantuCount = $chosenRefs->filter(fn ($r) => $r->certification_level !== 'WASIT PEMBANTU')->count();
                                    $neededNonPembantu = max(0, 2 - $nonPembantuCount);
                                    if ($neededNonPembantu > 0) {
                                        $nonPembantuRest = $restPool->filter(fn ($r) => $r->certification_level !== 'WASIT PEMBANTU' && $r->certification_level !== 'WASIT UTAMA');
                                        if ($nonPembantuRest->count() >= $neededNonPembantu) {
                                            $addedNonPembantus = $nonPembantuRest->random($neededNonPembantu);
                                            $chosenRefs = $chosenRefs->concat($addedNonPembantus);
                                            $restPool = $restPool->reject(fn ($r) => $addedNonPembantus->contains('id', $r->id));
                                        } else {
                                            $nonPembantuRestAll = $restPool->filter(fn ($r) => $r->certification_level !== 'WASIT PEMBANTU');
                                            $addedNonPembantus = $nonPembantuRestAll->random(min($neededNonPembantu, $nonPembantuRestAll->count()));
                                            $chosenRefs = $chosenRefs->concat($addedNonPembantus);
                                            $restPool = $restPool->reject(fn ($r) => $addedNonPembantus->contains('id', $r->id));
                                        }
                                    }

                                    // Fill the rest to 5, avoiding WASIT UTAMA
                                    $stillNeeded = 5 - $chosenRefs->count();
                                    if ($stillNeeded > 0) {
                                        $otherRest = $restPool->filter(fn ($r) => $r->certification_level !== 'WASIT UTAMA');
                                        if ($otherRest->count() >= $stillNeeded) {
                                            $addedOthers = $otherRest->random($stillNeeded);
                                            $chosenRefs = $chosenRefs->concat($addedOthers);
                                        } else {
                                            $addedOthers = $restPool->random($stillNeeded);
                                            $chosenRefs = $chosenRefs->concat($addedOthers);
                                        }
                                    }

                                    $selectedRefs = $chosenRefs;
                                    break 2;
                                }
                            }
                        }
                    }

                    // Fallback 1: If same city constraint cannot be satisfied, ignore it but respect the non-pembantu and female rules
                    if (! $selectedRefs) {
                        $utamas = $availableRefs->filter(fn ($r) => $r->certification_level === 'WASIT UTAMA');
                        $wasits = $availableRefs->filter(fn ($r) => $r->certification_level !== 'WASIT UTAMA' && $r->certification_level !== 'WASIT PEMBANTU');

                        if ($utamas->count() + $wasits->count() >= 2 && $availableRefs->count() >= 5) {
                            $chosenRefs = collect();

                            // Try to get a female referee first
                            $females = $availableRefs->filter(fn ($r) => $r->gender === 'P');
                            if ($females->isNotEmpty()) {
                                $femaleRef = $females->random();
                                $chosenRefs->push($femaleRef);
                                $availableRefsFiltered = $availableRefs->reject(fn ($r) => $r->id === $femaleRef->id);
                            } else {
                                $availableRefsFiltered = $availableRefs;
                            }

                            $utamasFiltered = $availableRefsFiltered->filter(fn ($r) => $r->certification_level === 'WASIT UTAMA');
                            $wasitsFiltered = $availableRefsFiltered->filter(fn ($r) => $r->certification_level !== 'WASIT UTAMA' && $r->certification_level !== 'WASIT PEMBANTU');

                            if ($utamasFiltered->isNotEmpty()) {
                                $chosenRefs->push($utamasFiltered->random());
                            }
                            $neededNonPembantu = max(0, 2 - $chosenRefs->filter(fn ($r) => $r->certification_level !== 'WASIT PEMBANTU')->count());
                            if ($neededNonPembantu > 0 && $wasitsFiltered->count() >= $neededNonPembantu) {
                                $chosenRefs = $chosenRefs->concat($wasitsFiltered->random($neededNonPembantu));
                            }
                            $restPool = $availableRefsFiltered->reject(fn ($r) => $chosenRefs->contains('id', $r->id));
                            $restPoolSorted = $restPool->sortBy(fn ($r) => $r->certification_level === 'WASIT UTAMA' ? 1 : 0)->values();
                            $chosenRefs = $chosenRefs->concat($restPoolSorted->take(5 - $chosenRefs->count()));
                            $selectedRefs = $chosenRefs;
                        }
                    }

                    // Fallback 2: Pick randomly (with female preference)
                    if (! $selectedRefs) {
                        if ($availableRefs->count() >= 5) {
                            $females = $availableRefs->filter(fn ($r) => $r->gender === 'P');
                            if ($females->isNotEmpty()) {
                                $femaleRef = $females->random();
                                $rest = $availableRefs->reject(fn ($r) => $r->id === $femaleRef->id)->random(4);
                                $selectedRefs = collect([$femaleRef])->concat($rest);
                            } else {
                                $selectedRefs = $availableRefs->random(5);
                            }
                        } else {
                            $selectedRefs = $allReferees->random(min(5, $allReferees->count()));
                        }
                    }

                    // Order the selected refs correctly
                    $selectedRefsArray = $selectedRefs->values();
                    $nonPembantus = $selectedRefsArray->filter(fn ($r) => $r->certification_level !== 'WASIT PEMBANTU');
                    $pembantus = $selectedRefsArray->filter(fn ($r) => $r->certification_level === 'WASIT PEMBANTU');

                    // Sort non-pembantus so WASIT UTAMA comes first
                    $nonPembantusSorted = $nonPembantus->sortBy(fn ($r) => $r->certification_level === 'WASIT UTAMA' ? 0 : 1)->values();

                    $orderedIds = [];
                    if ($nonPembantusSorted->count() >= 2) {
                        $orderedIds[] = $nonPembantusSorted[0]->id;
                        $orderedIds[] = $nonPembantusSorted[1]->id;

                        $remaining = $nonPembantusSorted->slice(2)->concat($pembantus)->values();
                        foreach ($remaining as $r) {
                            $orderedIds[] = $r->id;
                        }
                    } else {
                        $orderedIds = $selectedRefsArray->pluck('id')->toArray();
                    }

                    ScheduleReferee::where('rundown_id', $shift->rundown_id)
                        ->where('session_time_id', $shift->session_time_id)
                        ->where('court_id', $courtId)
                        ->where('judge_index', '>', 0)
                        ->delete();

                    foreach ($orderedIds as $index => $refereeId) {
                        ScheduleReferee::create([
                            'rundown_id' => $shift->rundown_id,
                            'session_time_id' => $shift->session_time_id,
                            'court_id' => $courtId,
                            'referee_id' => $refereeId,
                            'judge_index' => $index + 1,
                        ]);
                    }

                    $assignedInShift = array_values(array_unique(array_merge($assignedInShift, $orderedIds)));
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

    public function export()
    {
        return Excel::download(new RefereeAssignmentExport, 'penugasan_wasit_'.now()->format('Ymd_His').'.xlsx');
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
        $referees = $refereesQuery
            ->join('users', 'referees.user_id', '=', 'users.id')
            ->orderBy('referees.certification_level')
            ->select('referees.*')
            ->get();

        return view('livewire.admin.new-generate-referee-index', [
            'paginatedShifts' => $paginatedShifts,
            'allReferees' => $referees,
        ]);
    }
}
