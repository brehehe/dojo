<?php

namespace App\Livewire\Admin\Reports;

use App\Exports\MatchClassExport;
use App\Models\Contingent;
use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.admin')]
class AdminMatchClassReport extends Component
{
    public $contingentId;

    public $search = '';

    public function download()
    {
        $this->validate([
            'contingentId' => 'required|exists:contingents,id',
        ]);

        $contingent = Contingent::find($this->contingentId);
        $filename = 'Match_Class_'.str_replace(' ', '_', $contingent->name).'_'.date('Ymd_His').'.xlsx';

        return Excel::download(new MatchClassExport($this->contingentId), $filename);
    }

    public function getMatchGroupsProperty()
    {
        if (! $this->contingentId) {
            return collect();
        }

        $registrationIds = DB::table('registrations')
            ->where('contingent_id', $this->contingentId)
            ->where('status', 'verified')
            ->pluck('id');

        $data = DB::table('athlete_match_number')
            ->join('match_numbers', 'athlete_match_number.match_number_id', '=', 'match_numbers.id')
            ->join('athletes', 'athlete_match_number.athlete_id', '=', 'athletes.id')
            ->leftJoin('registration_athlete', function ($join) {
                $join->on('athlete_match_number.registration_id', '=', 'registration_athlete.registration_id')
                    ->on('athlete_match_number.athlete_id', '=', 'registration_athlete.athlete_id');
            })
            ->whereIn('athlete_match_number.registration_id', $registrationIds)
            ->select(
                'match_numbers.id as match_number_id',
                'match_numbers.name as match_name',
                'athletes.name as athlete_name',
                'registration_athlete.kyu as tingkat',
                'athlete_match_number.technique_ids',
                'athlete_match_number.registration_id'
            )
            ->orderBy('match_numbers.order')
            ->get();

        if ($this->search) {
            $data = $data->filter(function ($item) {
                return str_contains(strtolower($item->match_name), strtolower($this->search)) ||
                       str_contains(strtolower($item->athlete_name), strtolower($this->search));
            });
        }

        $grouped = $data->groupBy('match_number_id');

        return $grouped->map(function ($items) {
            $first = $items->first();

            $techniqueIds = [];
            if ($first->technique_ids) {
                $decoded = json_decode($first->technique_ids, true);
                if (is_array($decoded)) {
                    $techniqueIds = $decoded;
                } else {
                    $techniqueIds = explode(',', $first->technique_ids);
                }
            }
            $techniqueNames = [];
            if (! empty($techniqueIds)) {
                $uniqueTechniques = Technique::whereIn('id', $techniqueIds)->get()->keyBy('id');
                foreach ($techniqueIds as $id) {
                    if (isset($uniqueTechniques[$id])) {
                        $techniqueNames[] = $uniqueTechniques[$id]->name;
                    }
                }
            }

            return [
                'match_name' => $first->match_name,
                'athletes' => $items,
                'techniques' => $techniqueNames,
            ];
        });
    }

    public function render()
    {
        return view('livewire.admin.reports.admin-match-class-report', [
            'contingents' => Contingent::orderBy('name')->get(),
            'matchGroups' => $this->matchGroups,
        ]);
    }
}
