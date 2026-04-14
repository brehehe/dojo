<?php

namespace App\Livewire\Admin\TechnicalMeeting\Embu;

use App\Models\MatchNumber\MatchNumber;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminTechnicalMeetingEmbuIndex extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'livewire.admin.pagination';
    }

    public function render()
    {
        // 1. Fetch paginated match numbers of type EMBU
        $paginatedMatches = MatchNumber::where('draft_type', 'embu')
            ->with(['ageGroup'])
            ->latest()
            ->paginate(5);


        // 2. Load all technique names
        $allTechniqueNames = Technique::pluck('name', 'id')->toArray();

        // 3. Transform into hierarchical summary
        $matchSummary = [];
        foreach ($paginatedMatches as $match) {
            $gender = $match->gender ?? 'Mix';
            $ageGroupName = $match->ageGroup->name ?? 'Unknown Age';

            // Fetch athletes with their specific registration context (Rank/Kyu)
            $matchAthletes = DB::table('athlete_match_number')
                ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
                ->join('registration_athlete', function ($join) {
                    $join->on('athlete_match_number.athlete_id', '=', 'registration_athlete.athlete_id')
                        ->on('athlete_match_number.registration_id', '=', 'registration_athlete.registration_id');
                })
                ->join('registrations', 'athlete_match_number.registration_id', '=', 'registrations.id')
                ->join('contingents', 'registrations.contingent_id', '=', 'contingents.id')
                ->where('athlete_match_number.match_number_id', $match->id)
                ->select(
                    'athletes.name',
                    'registration_athlete.kyu as rank',
                    'contingents.name as contingent',
                    'athlete_match_number.registration_id',
                    'athlete_match_number.technique_ids'
                )
                ->orderBy('athlete_match_number.registration_id') // Group teams together
                ->get()
                ->map(function($ath) use ($allTechniqueNames) {
                    $techIds = $ath->technique_ids ? json_decode($ath->technique_ids, true) : [];
                    $ath->readable_techniques = array_map(function($id) use ($allTechniqueNames) {
                        return $allTechniqueNames[$id] ?? 'Unknown #' . $id;
                    }, $techIds);
                    return $ath;
                });

            $matchSummary[$gender][$ageGroupName][$match->id] = [
                'name' => $match->name,
                'athletes' => $matchAthletes->toArray(),
            ];
        }

        return view('livewire.admin.technical-meeting.embu.admin-technical-meeting-embu-index', [
            'paginatedMatches' => $paginatedMatches,
            'matchSummary' => $matchSummary
        ]);
    }
}