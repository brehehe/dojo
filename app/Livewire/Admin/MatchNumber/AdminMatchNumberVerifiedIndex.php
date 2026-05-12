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
                    'registration_athlete.dojo_origin',
                    'registration_athlete.city',
                    'registration_athlete.age',
                    'athletes.birth_date',
                    'athletes.gender as athlete_gender',
                    'athlete_match_number.technique_ids'
                )
                ->get();

            if ($athletes->isEmpty()) {
                continue;
            }

            // Group athletes by contingent and entry
            $contingents = [];
            $maxAthletes = $matchNumber->max_athletes ?: 1;
            
            foreach ($athletes->groupBy('contingent_id') as $cId => $regAthletes) {
                $chunks = $regAthletes->chunk($maxAthletes);
                $hasMultiple = $chunks->count() > 1;
                
                foreach ($chunks as $chunkIndex => $chunk) {
                    $entryKey = $cId . '_' . $chunkIndex;
                    $contingentName = $chunk->first()->contingent_name . ($hasMultiple ? ' (' . ($chunkIndex + 1) . ')' : '');
                    
                    $techniques = json_decode($chunk->first()->technique_ids ?? '[]', true);
                    $techNames = array_map(fn($id) => $this->allTechniques[$id] ?? 'Unknown', $techniques);

                    $contingents[$entryKey] = [
                        'name' => $contingentName,
                        'techniques' => $techNames,
                        'athletes' => $chunk->map(fn($ath) => [
                            'name' => $ath->athlete_name,
                            'nik' => $ath->nik,
                            'kyu' => $ath->kyu,
                            'weight' => $ath->weight,
                            'rank' => $ath->rank,
                            'dojo' => $ath->dojo_origin,
                            'city' => $ath->city,
                            'age' => $ath->age,
                            'gender' => $ath->athlete_gender,
                            'birth_date' => $ath->birth_date ? date('d/m/Y', strtotime($ath->birth_date)) : null,
                        ])->toArray()
                    ];
                }
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
