<?php

namespace App\Exports;

use App\Models\Contingent;
use App\Models\Technique\Technique;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MatchClassExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct(protected int $contingentId) {}

    public function view(): View
    {
        $contingent = Contingent::findOrFail($this->contingentId);

        // Fetch verified registration IDs for the contingent
        $registrationIds = DB::table('registrations')
            ->where('contingent_id', $this->contingentId)
            ->where('status', 'verified')
            ->pluck('id');

        // Fetch all athlete participations in match numbers
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

        // Grouping: by match_number_id only (fulfill requirement: same match_number = same techniques)
        $grouped = $data->groupBy('match_number_id');

        // Resolve techniques for each group
        $finalData = $grouped->map(function ($items) {
            $first = $items->first();

            // Decipher technique_ids
            $techniqueIds = [];
            if ($first->technique_ids) {
                $decoded = json_decode($first->technique_ids, true);
                if (is_array($decoded)) {
                    $techniqueIds = $decoded;
                } else {
                    $techniqueIds = explode(',', $first->technique_ids);
                }
            }

            $list = [];
            if (! empty($techniqueIds)) {
                $uniqueTechniques = Technique::whereIn('id', $techniqueIds)->get()->keyBy('id');
                foreach ($techniqueIds as $i => $id) {
                    if (isset($uniqueTechniques[$id])) {
                        $list[] = ($i + 1).'. '.$uniqueTechniques[$id]->name;
                    }
                }
            }

            return [
                'match_name' => $first->match_name,
                'athletes' => $items->values()->all(),
                'techniques' => $list,
            ];
        });

        return view('exports.match-class-export', [
            'contingent' => $contingent,
            'matchGroups' => $finalData,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'D' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 45,
            'C' => 15,
            'D' => 60,
        ];
    }
}
