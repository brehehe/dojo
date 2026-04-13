<?php

namespace App\Exports;

use App\Models\Contingent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationByNameExport implements FromView, ShouldAutoSize, WithStyles
{
    public function __construct(protected int $contingentId)
    {
    }

    public function view(): View
    {
        $contingent = Contingent::findOrFail($this->contingentId);
        
        $registrationIds = DB::table('registrations')
            ->where('contingent_id', $this->contingentId)
            ->where('status', 'verified')
            ->pluck('id');

        // Fetch Athletes (Status P)
        $athletes = DB::table('registration_athlete')
            ->join('athletes', 'registration_athlete.athlete_id', '=', 'athletes.id')
            ->whereIn('registration_athlete.registration_id', $registrationIds)
            ->select(
                'athletes.name',
                'athletes.gender',
                'athletes.birth_date',
                'registration_athlete.kyu as tingkat',
                'registration_athlete.dojo_origin as info',
                DB::raw("'P' as status_code"),
                DB::raw("'Peserta' as status_label")
            )
            ->get();

        // Fetch Officials (Status O)
        $officials = DB::table('registration_official')
            ->join('officials', 'registration_official.official_id', '=', 'officials.id')
            ->whereIn('registration_official.registration_id', $registrationIds)
            ->select(
                'officials.name',
                DB::raw("'' as gender"),
                DB::raw("NULL as birth_date"),
                DB::raw("'' as tingkat"),
                'registration_official.role as info',
                DB::raw("'O' as status_code"),
                DB::raw("'Official' as status_label")
            )
            ->get();

        // Merge and Sort: Officials first (O), then Athletes (P), then by Name
        $participants = $officials->concat($athletes)->sortBy(function ($item) {
            $statusRank = ($item->status_code === 'O') ? '0' : '1';
            return $statusRank . $item->name;
        });

        return view('exports.registration-by-name-export', [
            'contingent' => $contingent,
            'participants' => $participants,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
