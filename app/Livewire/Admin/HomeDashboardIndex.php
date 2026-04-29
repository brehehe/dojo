<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class HomeDashboardIndex extends Component
{
    use WithPagination;

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
        return Contingent::latest()->take(8)->get();
    }

    public function getLatestRegistrations()
    {
        return Registration::with('contingent')->latest()->paginate(5);
    }

    public function render()
    {
        return view('livewire.admin.home-dashboard-index', [
            'stats' => $this->getStats(),
            'monthlyAthletes' => $this->getMonthlyAthletes(),
            'statusBreakdown' => $this->getRegistrationStatusBreakdown(),
            'latestContingents' => $this->getLatestContingents(),
            'latestRegistrations' => $this->getLatestRegistrations(),
        ])->layout('layouts.home-dashboard');
    }
}
