<?php

namespace App\Exports;

use App\Models\Contingent;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Group\AgeGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationByNumberExport implements FromView, ShouldAutoSize, WithStyles
{
    public function __construct(protected int $contingentId)
    {
    }

    public function view(): View
    {
        $contingent = Contingent::findOrFail($this->contingentId);
        
        // Get verified registrations ID for this contingent
        $registrationIds = DB::table('registrations')
            ->where('contingent_id', $this->contingentId)
            ->where('status', 'verified')
            ->pluck('id');

        // Statistics
        $totalAthletes = DB::table('registration_athlete')
            ->whereIn('registration_id', $registrationIds)
            ->distinct('athlete_id')
            ->count();

        $totalOfficials = DB::table('registration_official')
            ->whereIn('registration_id', $registrationIds)
            ->count();

        // Match numbers following
        $followedMatchNumberIds = DB::table('athlete_match_number')
            ->whereIn('registration_id', $registrationIds)
            ->pluck('match_number_id')
            ->unique()
            ->toArray();

        // Get all match numbers grouped by Gender and Age Group
        // Assuming genders: Male -> Kelompok Putra, Female -> Kelompok Putri, Mix -> Kelompok Campuran
        $matchNumbers = MatchNumber::orderBy('order')->get()->groupBy('gender');
        $ageGroups = AgeGroup::all()->keyBy('id');

        return view('exports.registration-by-number-export', [
            'contingent' => $contingent,
            'totalAthletes' => $totalAthletes,
            'totalOfficials' => $totalOfficials,
            'followedMatchNumberIds' => $followedMatchNumberIds,
            'matchNumbers' => $matchNumbers,
            'ageGroups' => $ageGroups,
            'totalFollowed' => count($followedMatchNumberIds),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
