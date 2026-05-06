<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\TournamentResult;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class NewDashboardIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getStats(): array
    {
        $totalAthletes = Athlete::count();
        $totalContingents = Contingent::count();
        $totalRegistrations = Registration::count();
        $verifiedCount = Registration::where('status', 'verified')->count();
        $pendingCount = Registration::where('status', 'pending')->count();
        $totalAmount = Registration::where('status', 'verified')->sum('final_amount');

        $verificationRate = $totalRegistrations > 0
            ? round(($verifiedCount / $totalRegistrations) * 100, 1)
            : 0;

        return [
            'total_athletes' => $totalAthletes,
            'total_contingents' => $totalContingents,
            'total_registrations' => $totalRegistrations,
            'verified_count' => $verifiedCount,
            'pending_count' => $pendingCount,
            'total_amount' => $totalAmount,
            'verification_rate' => $verificationRate,
        ];
    }

    public function getMonthlyAthletes(): array
    {
        $months = [];
        $counts = [];

        $data = Athlete::selectRaw("TO_CHAR(created_at, 'Mon') as month_label, EXTRACT(MONTH FROM created_at) as month, EXTRACT(YEAR FROM created_at) as year, count(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw("TO_CHAR(created_at, 'Mon'), EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)")
            ->orderByRaw('EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)')
            ->get();

        foreach ($data as $row) {
            $months[] = $row->month_label;
            $counts[] = (int) $row->total;
        }

        return ['labels' => $months, 'data' => $counts];
    }

    public function getRegistrationStatusBreakdown(): array
    {
        $data = Registration::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        return [
            'verified' => $data['verified'] ?? 0,
            'pending' => $data['pending'] ?? 0,
            'rejected' => $data['rejected'] ?? 0,
        ];
    }

    public function getLatestContingents(): Collection
    {
        return Contingent::withCount('athletes')->latest()->take(8)->get();
    }

    public function getLatestRegistrations()
    {
        return Registration::with('contingent')
            ->when($this->search, function ($query) {
                $query->where('registration_number', 'ilike', '%'.$this->search.'%')
                    ->orWhereHas('contingent', function ($q) {
                        $q->where('name', 'ilike', '%'.$this->search.'%');
                    });
            })
            ->latest()
            ->paginate(5);
    }

    public function getMedalStats(): array
    {
        return [
            'gold' => TournamentResult::where('rank', 1)->count(),
            'silver' => TournamentResult::where('rank', 2)->count(),
            'bronze' => TournamentResult::whereIn('rank', [3, 4])->count(),
        ];
    }

    public function getMedalDistribution(): array
    {
        $contingents = Contingent::withCount(['tournamentResults as gold_count' => function ($query) {
            $query->where('rank', 1);
        }])
            ->withCount(['tournamentResults as silver_count' => function ($query) {
                $query->where('rank', 2);
            }])
            ->withCount(['tournamentResults as bronze_count' => function ($query) {
                $query->whereIn('rank', [3, 4]);
            }])
            ->orderByRaw('gold_count DESC, silver_count DESC, bronze_count DESC')
            ->take(7)
            ->get();

        $labels = $contingents->pluck('name')->toArray();
        $goldData = $contingents->pluck('gold_count')->toArray();
        $silverData = $contingents->pluck('silver_count')->toArray();
        $bronzeData = $contingents->pluck('bronze_count')->toArray();

        return [
            'labels' => $labels,
            'gold' => $goldData,
            'silver' => $silverData,
            'bronze' => $bronzeData,
            'contingents' => $contingents,
        ];
    }

    public function getTodaySchedules(): Collection
    {
        return Rundown::whereDate('date', now())->orderBy('order')->take(6)->get();
    }

    public function getLatestActivities(): array
    {
        // Simple mock of recent activities based on registrations and results
        $activities = [];

        $registrations = Registration::with('contingent')->latest()->take(3)->get();
        foreach ($registrations as $reg) {
            $activities[] = [
                'icon' => 'fa-user-plus',
                'color' => '#27ae60',
                'bg' => 'rgba(39,174,96,.12)',
                'title' => 'Registrasi Baru — '.($reg->contingent->name ?? 'Kontingen'),
                'desc' => ($reg->contingent->kab_kota ?? '-').' · '.$reg->created_at->diffForHumans(),
            ];
        }

        $results = TournamentResult::latest()->take(3)->get();
        foreach ($results as $res) {
            $activities[] = [
                'icon' => 'fa-medal',
                'color' => '#b8860b',
                'bg' => 'rgba(212,168,67,.12)',
                'title' => 'Hasil Pertandingan — Rank '.$res->rank,
                'desc' => $res->contingent_name.' · '.$res->created_at->diffForHumans(),
            ];
        }

        return $activities;
    }

    public function render()
    {
        return view('livewire.admin.new-dashboard-index', [
            'stats' => $this->getStats(),
            'monthlyAthletes' => $this->getMonthlyAthletes(),
            'statusBreakdown' => $this->getRegistrationStatusBreakdown(),
            'latestContingents' => $this->getLatestContingents(),
            'latestRegistrations' => $this->getLatestRegistrations(),
            'medalStats' => $this->getMedalStats(),
            'medalDistribution' => $this->getMedalDistribution(),
            'todaySchedules' => $this->getTodaySchedules(),
            'latestActivities' => $this->getLatestActivities(),
        ])->layout('layouts.premium', ['title' => 'Admin Dashboard']);
    }
}
