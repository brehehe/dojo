<?php

namespace App\Livewire\Admin\Reports;

use App\Exports\RegistrationByNameExport;
use App\Models\Contingent;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.admin')]
class AdminRegistrationByNameReport extends Component
{
    public $contingentId;

    public $search = '';

    public $statusFilter = 'all'; // all, P, O

    public function download()
    {
        $this->validate([
            'contingentId' => 'required|exists:contingents,id',
        ]);

        $contingent = Contingent::find($this->contingentId);
        $filename = 'Registration_By_Name_'.str_replace(' ', '_', $contingent->name).'_'.date('Ymd_His').'.xlsx';

        return Excel::download(new RegistrationByNameExport($this->contingentId), $filename);
    }

    public function getParticipantsProperty()
    {
        if (! $this->contingentId) {
            return collect();
        }

        $registrationIds = DB::table('registrations')
            ->where('contingent_id', $this->contingentId)
            ->where('status', 'verified')
            ->pluck('id');

        $athletes = collect();
        if ($this->statusFilter === 'all' || $this->statusFilter === 'P') {
            $query = DB::table('registration_athlete')
                ->join('athletes', 'registration_athlete.athlete_id', '=', 'athletes.id')
                ->whereIn('registration_athlete.registration_id', $registrationIds)
                ->select(
                    'athletes.name',
                    'athletes.gender',
                    'athletes.birth_date',
                    'registration_athlete.kyu as tingkat',
                    'registration_athlete.dojo_origin as info',
                    DB::raw("'P' as status_code")
                );
            if ($this->search) {
                $query->where('athletes.name', 'ilike', '%'.$this->search.'%');
            }
            $athletes = $query->get();
        }

        $officials = collect();
        if ($this->statusFilter === 'all' || $this->statusFilter === 'O') {
            $query = DB::table('registration_official')
                ->join('officials', 'registration_official.official_id', '=', 'officials.id')
                ->whereIn('registration_official.registration_id', $registrationIds)
                ->select(
                    'officials.name',
                    DB::raw("'' as gender"),
                    DB::raw('NULL as birth_date'),
                    DB::raw("'' as tingkat"),
                    'registration_official.role as info',
                    DB::raw("'O' as status_code")
                );
            if ($this->search) {
                $query->where('officials.name', 'ilike', '%'.$this->search.'%');
            }
            $officials = $query->get();
        }

        return $officials->concat($athletes)->sortBy(function ($item) {
            $statusRank = ($item->status_code === 'O') ? '0' : '1';

            return $statusRank.$item->name;
        });
    }

    public function render()
    {
        return view('livewire.admin.reports.admin-registration-by-name-report', [
            'contingents' => Contingent::orderBy('name')->get(),
            'participants' => $this->participants,
        ]);
    }
}
