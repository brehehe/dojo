<?php

namespace App\Livewire\Admin\MatchNumber;

use App\Models\MatchNumber\MatchNumber;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminMatchNumberVerifiedIndex extends Component
{
    public $search = '';
    public $allTechniques;

    public function mount()
    {
        $this->allTechniques = Technique::pluck('name', 'id')->toArray();
    }

    public function getGroupedDataProperty()
    {
        // Fetch all match numbers that have verified athletes
        $matchNumbers = MatchNumber::with(['ageGroup'])
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();

        $data = [];

        foreach ($matchNumbers as $matchNumber) {
            // Get athletes for this match number from verified registrations
            $athletes = DB::table('athlete_match_number')
                ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                ->join('registration_athlete', function($join) {
                    $join->on('athlete_match_number.athlete_id', '=', 'registration_athlete.athlete_id')
                         ->on('athlete_match_number.registration_id', '=', 'registration_athlete.registration_id');
                })
                ->where('athlete_match_number.match_number_id', $matchNumber->id)
                ->where('registrations.status', 'verified')
                ->select(
                    'athletes.id as athlete_id',
                    'athletes.name as athlete_name',
                    'athletes.nik',
                    'contingents.id as contingent_id',
                    'contingents.name as contingent_name',
                    'registration_athlete.kyu',
                    'registration_athlete.weight',
                    'registration_athlete.rank',
                    'athlete_match_number.technique_ids'
                )
                ->get();

            if ($athletes->isEmpty()) {
                continue;
            }

            // Group athletes by contingent
            $contingents = [];
            foreach ($athletes as $athlete) {
                $cId = $athlete->contingent_id;
                if (!isset($contingents[$cId])) {
                    $techniques = json_decode($athlete->technique_ids ?? '[]', true);
                    $techNames = array_map(fn($id) => $this->allTechniques[$id] ?? 'Unknown', $techniques);

                    $contingents[$cId] = [
                        'name' => $athlete->contingent_name,
                        'techniques' => $techNames,
                        'athletes' => []
                    ];
                }

                $contingents[$cId]['athletes'][] = [
                    'name' => $athlete->athlete_name,
                    'nik' => $athlete->nik,
                    'kyu' => $athlete->kyu,
                    'weight' => $athlete->weight,
                    'rank' => $athlete->rank,
                ];
            }

            $data[] = [
                'match' => $matchNumber,
                'contingents' => $contingents
            ];
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.admin.match-number.admin-match-number-verified-index', [
            'groupedData' => $this->groupedData,
        ]);
    }
}
