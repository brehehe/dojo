<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Contingent;
use App\Models\Referee;
use App\Models\RefereeObservation;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.premium')]
class AdminRefereeObservationsIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public $refereeFilter = '';

    public $contingentFilter = '';

    public $courtFilter = '';

    public $dateFilter = '';

    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'refereeFilter' => ['except' => ''],
        'contingentFilter' => ['except' => ''],
        'courtFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRefereeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedContingentFilter(): void
    {
        $this->resetPage();
    }

    public function updatedCourtFilter(): void
    {
        $this->resetPage();
    }

    public function updatedDateFilter(): void
    {
        $this->resetPage();
    }

    public function deleteObservation($id): void
    {
        $observation = RefereeObservation::findOrFail($id);
        $observation->delete();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Observasi wasit telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $operator = DB::connection()->getDriverName() === 'sqlite' ? 'like' : 'ilike';

        $query = RefereeObservation::with(['contingent', 'referee.user']);

        // Apply filters
        if (! empty($this->search)) {
            $query->where(function ($q) use ($operator) {
                $q->where('observer_name', $operator, '%'.$this->search.'%')
                    ->orWhereHas('referee.user', function ($sub) use ($operator) {
                        $sub->where('name', $operator, '%'.$this->search.'%');
                    });
            });
        }

        if (! empty($this->refereeFilter)) {
            $query->where('referee_id', $this->refereeFilter);
        }

        if (! empty($this->contingentFilter)) {
            $query->where('contingent_id', $this->contingentFilter);
        }

        if (! empty($this->courtFilter)) {
            $query->where('court', $this->courtFilter);
        }

        if (! empty($this->dateFilter)) {
            $query->whereDate('observation_date', $this->dateFilter);
        }

        // Calculate statistics based on current filters
        $statsQuery = RefereeObservation::query();
        if (! empty($this->search)) {
            $statsQuery->where(function ($q) use ($operator) {
                $q->where('observer_name', $operator, '%'.$this->search.'%')
                    ->orWhereHas('referee.user', function ($sub) use ($operator) {
                        $sub->where('name', $operator, '%'.$this->search.'%');
                    });
            });
        }
        if (! empty($this->refereeFilter)) {
            $statsQuery->where('referee_id', $this->refereeFilter);
        }
        if (! empty($this->contingentFilter)) {
            $statsQuery->where('contingent_id', $this->contingentFilter);
        }
        if (! empty($this->courtFilter)) {
            $statsQuery->where('court', $this->courtFilter);
        }
        if (! empty($this->dateFilter)) {
            $statsQuery->whereDate('observation_date', $this->dateFilter);
        }

        $totalObservations = $statsQuery->count();
        $averageScore = $statsQuery->avg('total_score') ?: 0;
        $excellentOrGoodCount = (clone $statsQuery)->whereIn('category', ['SANGAT BAIK', 'BAIK'])->count();
        $poorCount = (clone $statsQuery)->where('category', 'KURANG')->count();

        // Calculate Category Distribution count for charts
        $rawCategories = (clone $statsQuery)->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $categories = ['SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG'];
        $categoryChartData = [];
        foreach ($categories as $cat) {
            $count = 0;
            foreach ($rawCategories as $rawCat => $rawCount) {
                if (strtoupper(trim($rawCat)) === $cat) {
                    $count = (int) $rawCount;
                    break;
                }
            }
            $categoryChartData[$cat] = $count;
        }

        // Calculate Court Average Scores for charts
        $rawCourts = (clone $statsQuery)->select('court', DB::raw('avg(total_score) as avg_score'))
            ->groupBy('court')
            ->pluck('avg_score', 'court')
            ->toArray();

        $courts = ['Court 1', 'Court 2', 'Court 3', 'Court 4', 'Court 5'];
        $courtChartData = [];
        foreach ($courts as $crt) {
            $avg = 0.0;
            foreach ($rawCourts as $rawCrt => $rawAvg) {
                if (trim($rawCrt) === $crt) {
                    $avg = round((float) $rawAvg, 1);
                    break;
                }
            }
            $courtChartData[$crt] = $avg;
        }

        $observations = $query->latest()->paginate($this->perPage);

        $referees = Referee::with('user')->get()->sortBy(function ($ref) {
            return $ref->name;
        });

        $contingents = Contingent::orderBy('name')->get();

        $this->dispatch('refreshRefereeObservationCharts', [
            'categoryData' => $categoryChartData,
            'courtData' => $courtChartData,
        ]);

        return view('livewire.admin.reports.admin-referee-observations-index', [
            'observations' => $observations,
            'referees' => $referees,
            'contingents' => $contingents,
            'totalObservations' => $totalObservations,
            'averageScore' => round($averageScore, 1),
            'excellentOrGoodCount' => $excellentOrGoodCount,
            'poorCount' => $poorCount,
            'categoryChartData' => $categoryChartData,
            'courtChartData' => $courtChartData,
        ])->title('Laporan Observasi Wasit Kontingen');
    }
}
