<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Contingent;
use App\Models\Referee;
use App\Models\RefereeObservation;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Apply active filters to a query builder instance.
     */
    private function applyFilters(Builder $query): Builder
    {
        $operator = DB::connection()->getDriverName() === 'sqlite' ? 'like' : 'ilike';

        if (! empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($operator, $search) {
                $q->where('observer_name', $operator, '%'.$search.'%')
                    ->orWhereHas('referee.user', function ($sub) use ($operator, $search) {
                        $sub->where('name', $operator, '%'.$search.'%');
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

        return $query;
    }

    public function render()
    {
        // Main paginated query with eager loads to prevent N+1
        $query = $this->applyFilters(
            RefereeObservation::with(['contingent', 'referee.user'])
        );

        $observations = $query->latest()->paginate($this->perPage);

        // Stats: single query using conditional aggregates instead of 4 separate COUNT/AVG queries
        $stats = $this->applyFilters(RefereeObservation::query())
            ->selectRaw("
                COUNT(*) as total_observations,
                AVG(total_score) as average_score,
                SUM(CASE WHEN category IN ('SANGAT BAIK', 'BAIK') THEN 1 ELSE 0 END) as excellent_or_good_count,
                SUM(CASE WHEN category = 'KURANG' THEN 1 ELSE 0 END) as poor_count
            ")
            ->first();

        $totalObservations = (int) $stats->total_observations;
        $averageScore = round((float) ($stats->average_score ?? 0), 1);
        $excellentOrGoodCount = (int) $stats->excellent_or_good_count;
        $poorCount = (int) $stats->poor_count;

        // Category distribution — single grouped query
        $rawCategories = $this->applyFilters(RefereeObservation::query())
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $categoryChartData = [];
        foreach (['SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG'] as $cat) {
            $categoryChartData[$cat] = 0;
            foreach ($rawCategories as $rawCat => $rawCount) {
                if (strtoupper(trim($rawCat)) === $cat) {
                    $categoryChartData[$cat] = (int) $rawCount;
                    break;
                }
            }
        }

        // Court average scores — single grouped query
        $rawCourts = $this->applyFilters(RefereeObservation::query())
            ->select('court', DB::raw('avg(total_score) as avg_score'))
            ->groupBy('court')
            ->pluck('avg_score', 'court')
            ->toArray();

        $courtChartData = [];
        foreach (['Court 1', 'Court 2', 'Court 3', 'Court 4', 'Court 5'] as $crt) {
            $courtChartData[$crt] = 0.0;
            foreach ($rawCourts as $rawCrt => $rawAvg) {
                if (trim($rawCrt) === $crt) {
                    $courtChartData[$crt] = round((float) $rawAvg, 1);
                    break;
                }
            }
        }

        // Dropdown data — sorted at DB level, not in PHP memory
        $referees = Referee::with('user')
            ->join('users', 'referees.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('referees.*')
            ->get();

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
            'averageScore' => $averageScore,
            'excellentOrGoodCount' => $excellentOrGoodCount,
            'poorCount' => $poorCount,
            'categoryChartData' => $categoryChartData,
            'courtChartData' => $courtChartData,
        ])->title('Laporan Observasi Wasit Kontingen');
    }
}
