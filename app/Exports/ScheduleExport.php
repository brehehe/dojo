<?php

namespace App\Exports;

use App\Models\DrawingMatchNumber;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return DrawingMatchNumber::with(['matchNumber', 'registration.contingent', 'court', 'sessionTime', 'rundown'])
            ->get()
            ->groupBy(function ($d) {
                $date = $d->rundown->date ?? '9999-12-31';
                $sTime = $d->sessionTime->start_time ?? '99:99';
                $mTime = $d->metadata['start_time'] ?? '99:99';

                return $date.'|'.$sTime.'|'.$mTime.'|'.$d->court_id.'|'.$d->match_number_id.'|'.($d->metadata['pool_label'] ?? '');
            })
            ->map(function ($group) {
                return $group->values();
            })
            ->sortBy(function ($group) {
                $d = $group->first();
                $date = $d->rundown->date ?? '9999-12-31';
                $sTime = $d->sessionTime->start_time ?? '99:99';
                $mTime = $d->metadata['start_time'] ?? '99:99';

                return $date.$sTime.$mTime;
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Sesi',
            'Waktu',
            'Lapangan',
            'Nomor Pertandingan',
            'Babak/Pool',
            'Kode Partai',
            'Sudut Merah (Embu)',
            'Sudut Biru',
        ];
    }

    public function map($group): array
    {
        $first = $group->first();

        $tanggal = $first->rundown ? Carbon::parse($first->rundown->date)->isoFormat('dddd, D MMM YYYY') : '-';
        $sesi = $first->sessionTime ? $first->sessionTime->name : '-';
        $waktu = ($first->metadata['start_time'] ?? '').' - '.($first->metadata['end_time'] ?? '');
        $lapangan = $first->court ? $first->court->name : '-';

        $nomor = $first->matchNumber ? $first->matchNumber->name : '-';
        $babak = $first->metadata['pool_label'] ?? '-';
        $partai = $first->metadata['match_id_code'] ?? '-';

        $merah = '-';
        $biru = '-';

        if ($first->draft_type === 'randori') {
            $redEntry = $group->firstWhere('metadata.side', 'RED');
            $blueEntry = $group->firstWhere('metadata.side', 'BLUE');

            if ($redEntry) {
                $name = $redEntry->metadata['athlete_name'] ?? 'TBD';
                $cont = $redEntry->metadata['contingent'] ?? 'TBD';
                $merah = ($name === 'TBD') ? $cont : "{$name} ({$cont})";
            }
            if ($blueEntry) {
                $name = $blueEntry->metadata['athlete_name'] ?? 'TBD';
                $cont = $blueEntry->metadata['contingent'] ?? 'TBD';
                $biru = ($name === 'TBD') ? $cont : "{$name} ({$cont})";
            }
        } else {
            $name = $first->metadata['athlete_name'] ?? 'TBD';
            $cont = $first->metadata['contingent'] ?? 'TBD';
            $merah = ($name === 'TBD') ? $cont : "{$name} ({$cont})";
        }

        return [
            $tanggal,
            $sesi,
            $waktu,
            $lapangan,
            $nomor,
            $babak,
            $partai,
            $merah,
            $biru,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']]],
        ];
    }
}
