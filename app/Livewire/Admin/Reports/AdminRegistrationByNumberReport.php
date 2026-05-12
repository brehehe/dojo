<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Contingent;
use App\Exports\RegistrationByNumberExport;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.admin')]
class AdminRegistrationByNumberReport extends Component
{
    public $contingentId;
    public $search = '';

    public function download()
    {
        $this->validate([
            'contingentId' => 'required|exists:contingents,id',
        ]);

        $contingent = Contingent::find($this->contingentId);
        $filename = 'Registration_By_Number_' . str_replace(' ', '_', $contingent->name) . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new RegistrationByNumberExport($this->contingentId), $filename);
    }

    public function getCategoriesProperty()
    {
        $query = MatchNumber::query()->orderBy('order');

        if ($this->search) {
            $query->where('name', 'ilike', '%' . $this->search . '%');
        }

        return $query->get()->groupBy('gender');
    }

    public function getStatsProperty()
    {
        if (!$this->contingentId) {
            return null;
        }

        $registrationIds = \Illuminate\Support\Facades\DB::table('registrations')
            ->where('contingent_id', $this->contingentId)
            ->where('status', 'verified')
            ->pluck('id');

        $totalAthletes = \Illuminate\Support\Facades\DB::table('registration_athlete')
            ->whereIn('registration_id', $registrationIds)
            ->distinct('athlete_id')
            ->count();

        $totalOfficials = \Illuminate\Support\Facades\DB::table('registration_official')
            ->whereIn('registration_id', $registrationIds)
            ->count();

        $followedMatchNumberIds = \Illuminate\Support\Facades\DB::table('athlete_match_number')
            ->whereIn('registration_id', $registrationIds)
            ->pluck('match_number_id')
            ->unique()
            ->toArray();

        return [
            'totalAthletes' => $totalAthletes,
            'totalOfficials' => $totalOfficials,
            'followedMatchNumberIds' => $followedMatchNumberIds,
            'totalFollowed' => count($followedMatchNumberIds),
        ];
    }

    public function render()
    {
        return view('livewire.admin.reports.admin-registration-by-number-report', [
            'contingents' => Contingent::orderBy('name')->get(),
            'categories' => $this->categories,
            'stats' => $this->stats,
            'ageGroups' => AgeGroup::all()->keyBy('id'),
        ]);
    }
}
