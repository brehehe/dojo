<?php

namespace App\Livewire\Admin;

use App\Exports\UnregisteredAthleteReportExport;
use App\Models\Athlete;
use App\Models\MatchNumber\MatchNumber;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewUnregisteredAthleteReportIndex extends Component
{
    public $matchData = [];

    public $unregisteredAthletes = [];

    public int $totalAthletes = 0;

    public int $totalRegisteredAthletes = 0;

    public int $totalUnregisteredAthletes = 0;

    public array $ageGroupStats = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $matchNumbers = MatchNumber::with(['ageGroup', 'athletes.registrations.contingent', 'athletes.contingents'])->orderBy('id')->get();
        $this->matchData = [];

        foreach ($matchNumbers as $mn) {
            $contingents = [];

            if ($mn->max_athletes == 1) {
                // For match numbers with max athletes = 1, display duplicate/same contingents separately
                foreach ($mn->athletes as $athlete) {
                    $contingent = $athlete->contingent;
                    $contingentName = $contingent ? $contingent->name : 'Tanpa Kontingen';

                    $contingents[] = [
                        'name' => $contingentName,
                        'athletes' => [trim($athlete->name)],
                    ];
                }

                // Sort alphabetically by contingent name
                usort($contingents, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
            } else {
                // Group by contingent name normally
                $grouped = [];
                foreach ($mn->athletes as $athlete) {
                    $contingent = $athlete->contingent;
                    $contingentName = $contingent ? $contingent->name : 'Tanpa Kontingen';

                    if (! isset($grouped[$contingentName])) {
                        $grouped[$contingentName] = [];
                    }
                    $grouped[$contingentName][] = trim($athlete->name);
                }

                ksort($grouped);

                foreach ($grouped as $contingentName => $athletes) {
                    $contingents[] = [
                        'name' => $contingentName,
                        'athletes' => $athletes,
                    ];
                }
            }

            $this->matchData[] = [
                'id' => $mn->id,
                'name' => $mn->name,
                'age_group' => $mn->ageGroup ? $mn->ageGroup->name : '-',
                'contingents' => $contingents,
                'total_athletes' => $mn->athletes->count(),
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

        $this->totalAthletes = count($allAthletes);
        $this->totalUnregisteredAthletes = count($this->unregisteredAthletes);
        $this->totalRegisteredAthletes = $this->totalAthletes - $this->totalUnregisteredAthletes;

        $stats = DB::table('registration_athlete')
            ->select('age_group', DB::raw('count(distinct athlete_id) as total_athletes'))
            ->groupBy('age_group')
            ->get()
            ->pluck('total_athletes', 'age_group')
            ->toArray();

        $this->ageGroupStats = [
            'Pemula' => $stats['Pemula'] ?? 0,
            'Remaja A' => $stats['Remaja A'] ?? 0,
            'Remaja B' => $stats['Remaja B'] ?? 0,
            'Dewasa' => $stats['Dewasa'] ?? 0,
        ];
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
