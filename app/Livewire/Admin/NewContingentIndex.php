<?php

namespace App\Livewire\Admin;

use App\Models\Contingent;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewContingentIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteContingent($id)
    {
        $contingent = Contingent::withCount(['registrations as athletes_count' => function ($q) {
            $q->select(DB::raw('count(*)'))->join('registration_athlete', 'registrations.id', '=', 'registration_athlete.registration_id');
        }])->findOrFail($id);

        if ($contingent->athletes_count > 0) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Kontingen ini masih memiliki atlet atau official terdaftar.',
                'icon' => 'error',
            ]);

            return;
        }

        $contingent->delete();
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Kontingen berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = Contingent::addSelect([
            'athletes_count' => Registration::whereColumn('contingent_id', 'contingents.id')
                ->join('registration_athlete', 'registrations.id', '=', 'registration_athlete.registration_id')
                ->selectRaw('count(*)'),
            'officials_count' => Registration::whereColumn('contingent_id', 'contingents.id')
                ->join('registration_official', 'registrations.id', '=', 'registration_official.registration_id')
                ->selectRaw('count(*)'),
        ])->with(['registrations' => function ($q) {
            $q->latest();
        }]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('leader_name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('kab_kota', 'ilike', '%'.$this->search.'%')
                    ->orWhereHas('registrations', function ($rq) {
                        $rq->where('referral_code', 'ilike', '%'.$this->search.'%');
                    });
            });
        }

        if ($this->statusFilter) {
            $query->whereHas('registrations', function ($q) {
                $q->where('status', $this->statusFilter);
            });
        }

        $contingents = $query->latest()->paginate($this->perPage === 'all' ? Contingent::count() : $this->perPage);

        return view('livewire.admin.new-contingent-index', [
            'contingents' => $contingents,
        ])->layout('layouts.premium', ['title' => 'Master Kontingen']);
    }
}
