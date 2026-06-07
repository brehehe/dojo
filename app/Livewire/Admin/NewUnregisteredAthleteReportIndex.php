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

    public string $genderFilter = '';

    public string $searchQuery = '';

    protected $queryString = [
        'genderFilter' => ['except' => ''],
        'searchQuery' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->loadData();
    }

    public function updatedGenderFilter(): void
    {
        $this->loadData();
    }

    public function updatedSearchQuery(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $likeOperator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $matchNumbersQuery = MatchNumber::with(['ageGroup', 'athletes.registrations.contingent', 'athletes.contingents'])->orderBy('id');

        if ($this->genderFilter) {
            $matchNumbersQuery->where('gender', $this->genderFilter);
        }

        if ($this->searchQuery) {
            $search = strtolower($this->searchQuery);
            $matchNumbersQuery->where(function ($q) use ($search, $likeOperator) {
                $q->where('name', $likeOperator, '%'.$search.'%')
                    ->orWhereHas('athletes', function ($sq) use ($search, $likeOperator) {
                        $sq->where('name', $likeOperator, '%'.$search.'%')
                            ->orWhereHas('contingents', function ($ssq) use ($search, $likeOperator) {
                                $ssq->where('name', $likeOperator, '%'.$search.'%');
                            });
                    });
            });
        }

        $matchNumbers = $matchNumbersQuery->get();
        $this->matchData = [];

        foreach ($matchNumbers as $mn) {
            $contingents = [];

            if ($mn->max_athletes == 1) {
                // For match numbers with max athletes = 1, display duplicate/same contingents separately
                foreach ($mn->athletes as $athlete) {
                    $contingent = $athlete->contingent;
                    $contingentName = $contingent ? $contingent->name : 'Tanpa Kontingen';

                    if ($this->searchQuery) {
                        $search = strtolower($this->searchQuery);
                        if (! str_contains(strtolower($athlete->name), $search) && ! str_contains(strtolower($contingentName), $search)) {
                            continue;
                        }
                    }

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

                    if ($this->searchQuery) {
                        $search = strtolower($this->searchQuery);
                        if (! str_contains(strtolower($athlete->name), $search) && ! str_contains(strtolower($contingentName), $search)) {
                            continue;
                        }
                    }

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

            if ($this->searchQuery && empty($contingents)) {
                if (! str_contains(strtolower($mn->name), strtolower($this->searchQuery))) {
                    continue;
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

        $athletesQuery = Athlete::with(['registrations.contingent', 'contingents', 'matchNumbers']);

        if ($this->genderFilter) {
            $athletesQuery->where('gender', $this->genderFilter);
        }

        if ($this->searchQuery) {
            $search = strtolower($this->searchQuery);
            $athletesQuery->where(function ($q) use ($search, $likeOperator) {
                $q->where('name', $likeOperator, '%'.$search.'%')
                    ->orWhereHas('contingents', function ($sq) use ($search, $likeOperator) {
                        $sq->where('name', $likeOperator, '%'.$search.'%');
                    });
            });
        }

        $allAthletes = $athletesQuery->get();
        $this->unregisteredAthletes = [];

        foreach ($allAthletes as $athlete) {
            if ($athlete->matchNumbers->isEmpty()) {
                $contingent = $athlete->contingent;
                $contingentName = $contingent ? $contingent->name : 'Tanpa Kontingen';
                $this->unregisteredAthletes[] = [
                    'name' => trim($athlete->name),
                    'contingent' => $contingentName,
                    'gender' => $athlete->gender_indo,
                ];
            }
        }

        $this->totalAthletes = $this->genderFilter
            ? Athlete::where('gender', $this->genderFilter)->count()
            : Athlete::count();
        $this->totalUnregisteredAthletes = count($this->unregisteredAthletes);
        $this->totalRegisteredAthletes = $this->totalAthletes - $this->totalUnregisteredAthletes;

        $statsQuery = DB::table('registration_athlete')
            ->select('age_group', DB::raw('count(distinct athlete_id) as total_athletes'))
            ->groupBy('age_group');

        if ($this->genderFilter) {
            $statsQuery->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('athletes')
                    ->whereColumn('athletes.id', 'registration_athlete.athlete_id')
                    ->where('gender', $this->genderFilter);
            });
        }

        $stats = $statsQuery->get()
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
