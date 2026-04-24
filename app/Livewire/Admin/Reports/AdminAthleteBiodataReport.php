<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAthleteBiodataReport extends Component
{
    public $contingent_id;

    public $athletes = [];

    public function mount()
    {
        $this->contingent_id = Contingent::first()?->id;
        $this->loadData();
    }

    public function updatedContingentId()
    {
        $this->loadData();
    }

    public function loadData()
    {
        if (! $this->contingent_id) {
            $this->athletes = [];

            return;
        }

        $this->athletes = Athlete::whereHas('contingents', function ($q) {
            $q->where('contingents.id', $this->contingent_id);
        })
            ->with(['matchNumbers' => function ($q) {
                // Only get match numbers from registrations belonging to this contingent
                $q->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('registrations')
                        ->whereColumn('registrations.id', 'athlete_match_number.registration_id')
                        ->where('registrations.contingent_id', $this->contingent_id);
                });
            }])
            ->get()
            ->map(function ($athlete) {
                $latestReg = $athlete->registrations()
                    ->where('contingent_id', $this->contingent_id)
                    ->latest()
                    ->first();

                $matchData = [];
                if ($latestReg) {
                    $matchData = DB::table('athlete_match_number')
                        ->join('match_numbers', 'athlete_match_number.match_number_id', '=', 'match_numbers.id')
                        ->where('athlete_match_number.athlete_id', $athlete->id)
                        ->where('athlete_match_number.registration_id', $latestReg->id)
                        ->select('match_numbers.name', 'athlete_match_number.technique_ids')
                        ->get()
                        ->map(function ($m) {
                            $techIds = json_decode($m->technique_ids, true) ?? [];
                            $techNames = Technique::whereIn('id', $techIds)
                                ->get()
                                ->sortBy(fn ($t) => array_search($t->id, $techIds))
                                ->pluck('name')
                                ->toArray();

                            return [
                                'name' => $m->name,
                                'techniques' => $techNames,
                            ];
                        });
                }

                return [
                    'id' => $athlete->id,
                    'nik' => $athlete->nik,
                    'name' => $athlete->name,
                    'gender' => $athlete->gender === 'Male' ? 'L' : 'P',
                    'birth_place' => $athlete->birth_place,
                    'birth_date' => $athlete->birth_date?->format('d F Y'),
                    'blood_type' => $athlete->blood_type,
                    'address' => $athlete->address,
                    'phone' => $athlete->phone,
                    'photo_path' => $athlete->photo_path,
                    'kyu' => $latestReg?->pivot?->rank ?? $athlete->kyu,
                    'status' => 'Peserta', // Defaulting to Peserta
                    'achievements' => is_array($athlete->achievement_history) ? $athlete->achievement_history : [],
                    'matches' => $matchData,
                ];
            });
    }

    public function render()
    {
        return view('livewire.admin.reports.admin-athlete-biodata-report', [
            'contingents' => Contingent::orderBy('name', 'asc')->get(),
        ]);
    }
}
