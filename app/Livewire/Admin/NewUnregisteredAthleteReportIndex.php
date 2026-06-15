<?php

namespace App\Livewire\Admin;

use App\Exports\UnregisteredAthleteReportExport;
use App\Models\Athlete;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewUnregisteredAthleteReportIndex extends Component
{
    public $matchData = [];

    /** @var array<int, array<string, mixed>> Daftar merge group yang aktif */
    public $mergeGroups = [];

    /** @var array<int> ID match_number yang sudah masuk merge */
    public $mergedMatchIds = [];

    public $unregisteredAthletes = [];

    public int $totalAthletes = 0;

    public int $totalRegisteredAthletes = 0;

    public int $totalUnregisteredAthletes = 0;

    public int $totalMatchesWithAthletes = 0;

    public int $totalMatchesWithoutAthletes = 0;

    /** Total semua nomor pertandingan: individual + yang ada di dalam merge */
    public int $totalMatchNumbers = 0;

    /** Total merge groups yang aktif */
    public int $totalMergeGroups = 0;

    public int $totalEksebisi = 0;

    public int $totalNonEksebisi = 0;

    public int $totalEmbuEksebisi = 0;

    public int $totalEmbuNonEksebisi = 0;

    public int $totalRandoriEksebisi = 0;

    public int $totalRandoriNonEksebisi = 0;

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
        $this->mergeGroups = [];
        $this->mergedMatchIds = [];
        $this->totalMatchesWithAthletes = 0;
        $this->totalMatchesWithoutAthletes = 0;
        $this->totalMatchNumbers = 0;
        $this->totalMergeGroups = 0;

        // Load semua merge groups beserta kontingen tiap nomor di dalamnya
        $allMerges = MatchNumberMerge::with(['matchNumbers.athletes.contingents', 'matchNumbers.ageGroup', 'ageGroup'])->get();
        foreach ($allMerges as $merge) {
            $mergeMatchIds = $merge->matchNumbers->pluck('id')->toArray();

            // Filter match numbers in this merge sesuai filter gender
            $mergeMatchNumbers = $merge->matchNumbers->filter(function ($mn) {
                if ($this->genderFilter && $mn->gender !== $this->genderFilter) {
                    return false;
                }

                return true;
            });

            if ($mergeMatchNumbers->isEmpty()) {
                continue;
            }

            // Kumpulkan IDs yang sudah di-merge
            foreach ($mergeMatchIds as $id) {
                $this->mergedMatchIds[] = $id;
            }

            // Build match_numbers with contingent data
            $mergeMatchNumbersData = $mergeMatchNumbers->map(function ($mn) {
                // Group athletes by contingent (same logic as individual matches)
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

                $contingents = [];
                foreach ($grouped as $contingentName => $athletes) {
                    $contingents[] = ['name' => $contingentName, 'athletes' => $athletes];
                }

                return [
                    'id' => $mn->id,
                    'name' => $mn->name,
                    'gender' => $mn->gender ?? '-',
                    'total_athletes' => $mn->athletes->count(),
                    'contingents' => $contingents,
                ];
            })->values()->toArray();

            $this->mergeGroups[] = [
                'id' => $merge->id,
                'name' => $merge->name,
                'type' => $merge->type,
                'age_group' => $merge->ageGroup?->name ?? '-',
                'match_numbers' => $mergeMatchNumbersData,
            ];
        }

        foreach ($matchNumbers as $mn) {
            // Skip jika nomor ini sudah masuk dalam sebuah merge group
            if (in_array($mn->id, $this->mergedMatchIds)) {
                continue;
            }

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

            $uniqueContingents = collect($mn->athletes)->map(function ($athlete) {
                return $athlete->contingent ? $athlete->contingent->name : 'Tanpa Kontingen';
            })->unique();
            $distinctContingentsCount = $uniqueContingents->count();

            // Hitung frekuensi kontingen untuk mengecek duplikat (2 atau lebih atlet dari kontingen yang sama)
            $contingentNames = collect($mn->athletes)->map(function ($athlete) {
                return $athlete->contingent ? $athlete->contingent->name : 'Tanpa Kontingen';
            });
            $contingentCounts = $contingentNames->groupBy(fn ($name) => $name)->map->count();
            $hasDuplicate = $contingentCounts->contains(fn ($count) => $count >= 2);

            // Warna kuning jika jumlah kontingen <= 3 ATAU ada kontingen yang duplikat
            $hasDuplicateContingent = ($distinctContingentsCount <= 3) || $hasDuplicate;

            $this->matchData[] = [
                'id' => $mn->id,
                'name' => $mn->name,
                'age_group' => $mn->ageGroup ? $mn->ageGroup->name : '-',
                'gender' => $mn->gender ?? '-',
                'contingents' => $contingents,
                'total_athletes' => $mn->athletes->count(),
                'has_duplicate_contingent' => $hasDuplicateContingent,
            ];

            if ($mn->athletes->count() > 0) {
                $this->totalMatchesWithAthletes++;
            } else {
                $this->totalMatchesWithoutAthletes++;
            }
        }

        // Hitung total: individual matchData + semua nomor di dalam merge groups
        $mergeMatchCount = collect($this->mergeGroups)->sum(fn ($mg) => count($mg['match_numbers']));
        $this->totalMatchNumbers = count($this->matchData) + $mergeMatchCount;
        $this->totalMergeGroups = count($this->mergeGroups);

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

        $notLikeOperator = DB::connection()->getDriverName() === 'pgsql' ? 'not ilike' : 'not like';

        $eksebisiQuery = Athlete::whereHas('matchNumbers', function ($query) use ($likeOperator) {
            $query->where('name', $likeOperator, '%eksebisi%');
        });
        $nonEksebisiQuery = Athlete::whereHas('matchNumbers', function ($query) use ($notLikeOperator) {
            $query->where('name', $notLikeOperator, '%eksebisi%');
        });
        $embuEksebisiQuery = Athlete::whereHas('matchNumbers', function ($query) use ($likeOperator) {
            $query->where('draft_type', 'embu')->where('name', $likeOperator, '%eksebisi%');
        });
        $embuNonEksebisiQuery = Athlete::whereHas('matchNumbers', function ($query) use ($notLikeOperator) {
            $query->where('draft_type', 'embu')->where('name', $notLikeOperator, '%eksebisi%');
        });
        $randoriEksebisiQuery = Athlete::whereHas('matchNumbers', function ($query) use ($likeOperator) {
            $query->where('draft_type', 'randori')->where('name', $likeOperator, '%eksebisi%');
        });
        $randoriNonEksebisiQuery = Athlete::whereHas('matchNumbers', function ($query) use ($notLikeOperator) {
            $query->where('draft_type', 'randori')->where('name', $notLikeOperator, '%eksebisi%');
        });

        if ($this->genderFilter) {
            $eksebisiQuery->where('gender', $this->genderFilter);
            $nonEksebisiQuery->where('gender', $this->genderFilter);
            $embuEksebisiQuery->where('gender', $this->genderFilter);
            $embuNonEksebisiQuery->where('gender', $this->genderFilter);
            $randoriEksebisiQuery->where('gender', $this->genderFilter);
            $randoriNonEksebisiQuery->where('gender', $this->genderFilter);
        }

        $this->totalEksebisi = $eksebisiQuery->count();
        $this->totalNonEksebisi = $nonEksebisiQuery->count();
        $this->totalEmbuEksebisi = $embuEksebisiQuery->count();
        $this->totalEmbuNonEksebisi = $embuNonEksebisiQuery->count();
        $this->totalRandoriEksebisi = $randoriEksebisiQuery->count();
        $this->totalRandoriNonEksebisi = $randoriNonEksebisiQuery->count();

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
