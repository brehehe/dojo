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
            'Sudut Merah',
            'Kontingen (M)',
            'Sudut Biru',
            'Kontingen (B)',
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
        $merahCont = '-';
        $biru = '-';
        $biruCont = '-';

        if ($first->draft_type === 'randori') {
            $redEntry = $group->firstWhere('metadata.side', 'RED');
            $blueEntry = $group->firstWhere('metadata.side', 'BLUE');

            if ($redEntry) {
                $merah = $redEntry->metadata['athlete_name'] ?? 'TBD';
                $merahCont = $redEntry->metadata['contingent'] ?? 'TBD';
            }
            if ($blueEntry) {
                $biru = $blueEntry->metadata['athlete_name'] ?? 'TBD';
                $biruCont = $blueEntry->metadata['contingent'] ?? 'TBD';
            }
        } else {
            $merah = $first->metadata['athlete_name'] ?? 'TBD';
            $merahCont = $first->metadata['contingent'] ?? 'TBD';
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
            $merahCont,
            $biru,
            $biruCont,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']]],
        ];
    }
}
