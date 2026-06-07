<?php

namespace App\Livewire\Admin;

use App\Models\DrawingMatchNumber;
use App\Models\SchedulePanitera;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium', ['title' => 'Penugasan Panitera & Koordinator'])]
class NewGeneratePaniteraIndex extends Component
{
    use WithPagination;

    public string $searchShift = '';

    public string $searchOfficer = '';

    public $assigningBlock = null;

    public bool $isKoordinatorMode = false;

    public array $selectedOfficers = [];

    public function paginationView(): string
    {
        return 'livewire.admin.pagination';
    }

    public function openAssignModal(int $rundownId, int $sessionId, int $courtId, string $roleType): void
    {
        $this->assigningBlock = [
            'rundown_id' => $rundownId,
            'session_time_id' => $sessionId,
            'court_id' => $courtId,
            'role_type' => $roleType,
        ];

        $this->isKoordinatorMode = ($roleType === 'koordinator');

        $existing = SchedulePanitera::where('rundown_id', $rundownId)
            ->where('session_time_id', $sessionId)
            ->where('court_id', $courtId)
            ->where('role_type', $roleType)
            ->get();

        $this->selectedOfficers = $existing->pluck('user_id')
            ->map(fn ($id) => (string) $id)
            ->toArray();

        $this->searchOfficer = '';
        $this->resetValidation();
    }

    public function closeAssignModal(): void
    {
        $this->assigningBlock = null;
        $this->selectedOfficers = [];
        $this->searchOfficer = '';
        $this->resetValidation();
    }

    public function toggleOfficer(string $id): void
    {
        $id = (string) $id;
        if (in_array($id, $this->selectedOfficers)) {
            $this->selectedOfficers = array_values(array_diff($this->selectedOfficers, [$id]));
        } else {
            $this->selectedOfficers[] = $id;
        }
    }

    public function saveAssignment(): void
    {
        if (! $this->assigningBlock) {
            return;
        }

        $rId = $this->assigningBlock['rundown_id'];
        $sId = $this->assigningBlock['session_time_id'];
        $cId = $this->assigningBlock['court_id'];
        $roleType = $this->assigningBlock['role_type'];

        DB::beginTransaction();
        try {
            SchedulePanitera::where('rundown_id', $rId)
                ->where('session_time_id', $sId)
                ->where('court_id', $cId)
                ->where('role_type', $roleType)
                ->delete();

            foreach ($this->selectedOfficers as $index => $userId) {
                SchedulePanitera::create([
                    'rundown_id' => $rId,
                    'session_time_id' => $sId,
                    'court_id' => $cId,
                    'user_id' => $userId,
                    'role_type' => $roleType,
                    'slot_index' => $index + 1,
                ]);
            }

            DB::commit();
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Petugas lapangan telah diperbarui.',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('save_error', 'Gagal menyimpan: '.$e->getMessage());

            return;
        }

        $this->closeAssignModal();
    }

    public function autoGenerateAllOfficers(): void
    {
        $koordinatorIds = User::role('Koordinator Lapangan')->pluck('id')->toArray();
        $paniteraIds = User::role('Panitera')->pluck('id')->toArray();

        if (count($koordinatorIds) < 1 || count($paniteraIds) < 1) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Master petugas minimal harus ada 1 Koordinator Lapangan dan 1 Panitera.',
                'icon' => 'error',
            ]);

            return;
        }

        $uniqueShifts = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()->whereNotNull('rundown_id')->whereNotNull('session_time_id')->get();

        $countGenerated = 0;
        foreach ($uniqueShifts as $shift) {
            $courtsInShift = DrawingMatchNumber::select('court_id')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNotNull('court_id')
                ->distinct()
                ->pluck('court_id');

            foreach ($courtsInShift as $courtId) {
                $exists = SchedulePanitera::where('rundown_id', $shift->rundown_id)
                    ->where('session_time_id', $shift->session_time_id)
                    ->where('court_id', $courtId)
                    ->exists();

                if (! $exists) {
                    DB::beginTransaction();
                    try {
                        // Select random Koordinator (1 or 2)
                        $numKoor = min(2, count($koordinatorIds));
                        $randomKoordinators = collect($koordinatorIds)->random($numKoor)->toArray();
                        foreach ($randomKoordinators as $index => $koorId) {
                            SchedulePanitera::create([
                                'rundown_id' => $shift->rundown_id,
                                'session_time_id' => $shift->session_time_id,
                                'court_id' => $courtId,
                                'user_id' => $koorId,
                                'role_type' => 'koordinator',
                                'slot_index' => $index + 1,
                            ]);
                        }

                        // Select random Panitera (3 or 4)
                        $numPaniteras = min(4, count($paniteraIds));
                        $randomPaniteras = collect($paniteraIds)->random($numPaniteras)->toArray();
                        foreach ($randomPaniteras as $index => $paniteraId) {
                            SchedulePanitera::create([
                                'rundown_id' => $shift->rundown_id,
                                'session_time_id' => $shift->session_time_id,
                                'court_id' => $courtId,
                                'user_id' => $paniteraId,
                                'role_type' => 'panitera',
                                'slot_index' => $index + 1,
                            ]);
                        }

                        DB::commit();
                        $countGenerated++;
                    } catch (\Exception $e) {
                        DB::rollBack();
                    }
                }
            }
        }

        $this->dispatch('swal', [
            'title' => 'Selesai!',
            'text' => "Penugasan otomatis berhasil untuk $countGenerated lapangan.",
            'icon' => 'success',
        ]);
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
            $shift->assigned_officers = SchedulePanitera::with('user')
                ->where('rundown_id', $shift->rundown_id)->where('session_time_id', $shift->session_time_id)->get();

            return $shift;
        });

        $operator = DB::connection()->getDriverName() === 'sqlite' ? 'like' : 'ilike';

        $officersQuery = User::query();

        if ($this->assigningBlock) {
            if ($this->isKoordinatorMode) {
                $officersQuery->role('Koordinator Lapangan');
            } else {
                $officersQuery->role('Panitera');
            }
        }

        if (! empty($this->searchOfficer)) {
            $officersQuery->where('name', $operator, '%'.$this->searchOfficer.'%');
        }

        $allOfficers = $officersQuery->orderBy('name')->get();

        return view('livewire.admin.new-generate-panitera-index', [
            'paginatedShifts' => $paginatedShifts,
            'allOfficers' => $allOfficers,
        ]);
    }
}
