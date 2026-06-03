<?php

namespace App\Livewire\Admin;

use App\Exports\UnregisteredAthleteReportExport;
use App\Models\Athlete;
use App\Models\MatchNumber\MatchNumber;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewUnregisteredAthleteReportIndex extends Component
{
    public $matchData = [];
    public $unregisteredAthletes = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $matchNumbers = MatchNumber::with(['ageGroup', 'athletes.registrations.contingent', 'athletes.contingents'])->orderBy('id')->get();
        $this->matchData = [];
        
        foreach ($matchNumbers as $mn) {
            $contingents = [];
            foreach ($mn->athletes as $athlete) {
                $contingent = $athlete->contingent;
                $contingentName = $contingent ? $contingent->name : 'Tanpa Kontingen';
                
                if (!isset($contingents[$contingentName])) {
                    $contingents[$contingentName] = [];
                }
                $contingents[$contingentName][] = $athlete->name;
            }
            
            ksort($contingents);
            
            $this->matchData[] = [
                'id' => $mn->id,
                'name' => $mn->name,
                'age_group' => $mn->ageGroup ? $mn->ageGroup->name : '-',
                'contingents' => $contingents,
            ];
        }
        
        $allAthletes = Athlete::with(['registrations.contingent', 'contingents', 'matchNumbers'])->get();
        $this->unregisteredAthletes = [];
        
        foreach ($allAthletes as $athlete) {
            if ($athlete->matchNumbers->isEmpty()) {
                $contingent = $athlete->contingent;
                $contingentName = $contingent ? $contingent->name : 'Tanpa Kontingen';
                $this->unregisteredAthletes[] = [
                    'name' => trim($athlete->name),
                    'contingent' => $contingentName,
                ];
            }
        }
    }

    public function downloadExcel()
    {
        $filename = 'Laporan_Kontingen_dan_Atlet_Kosong_'.date('Ymd_His').'.xlsx';
        return Excel::download(new UnregisteredAthleteReportExport($this->matchData, $this->unregisteredAthletes), $filename);
    }
    public function render()
    {
        return view('livewire.admin.new-unregistered-athlete-report-index');
    }
}
