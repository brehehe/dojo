<?php

namespace App\Livewire\Admin\Master\Contingent;

use App\Models\Contingent;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterContingentIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteContingent($id)
    {
        $contingent = Contingent::withCount(['registrations as athletes_count' => function ($q) {
            $q->select(DB::raw('count(*)'))->join('registration_athlete', 'registrations.id', '=', 'registration_athlete.registration_id');
        }])->findOrFail($id);

        if ($contingent->athletes_count > 0) {
            $this->dispatch('swal', title: 'Gagal!', text: 'Kontingen ini masih memiliki atlet atau official terdaftar.', icon: 'error');

            return;
        }

        $contingent->delete();
        $this->dispatch('swal', title: 'Berhasil!', text: 'Kontingen berhasil dihapus.', icon: 'success');
    }

    public function render()
    {
        $contingents = Contingent::addSelect([
            'athletes_count' => Registration::whereColumn('contingent_id', 'contingents.id')
                ->join('registration_athlete', 'registrations.id', '=', 'registration_athlete.registration_id')
                ->selectRaw('count(*)'),
            'officials_count' => Registration::whereColumn('contingent_id', 'contingents.id')
                ->join('registration_official', 'registrations.id', '=', 'registration_official.registration_id')
                ->selectRaw('count(*)'),
        ])
            ->with(['registrations' => function ($q) {
                $q->latest();
            }])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'ilike', '%'.$this->search.'%')
                        ->orWhere('leader_name', 'ilike', '%'.$this->search.'%')
                        ->orWhere('kab_kota', 'ilike', '%'.$this->search.'%')
                        ->orWhereHas('registrations', function ($rq) {
                            $rq->where('referral_code', 'ilike', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->whereHas('registrations', function ($q) {
                    $q->where('status', $this->statusFilter);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.master.contingent.admin-master-contingent-index', [
            'contingents' => $contingents,
        ]);
    }
}
