<?php

namespace App\Exports;

use App\Models\DrawingMatchNumber;
use App\Models\SchedulePanitera;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaniteraAssignmentExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $shifts = DrawingMatchNumber::select('rundown_id', 'session_time_id')
            ->distinct()
            ->with(['rundown', 'sessionTime'])
            ->whereNotNull('rundown_id')
            ->whereNotNull('session_time_id')
            ->orderBy('rundown_id')
            ->orderBy('session_time_id')
            ->get();

        $rows = collect();

        foreach ($shifts as $shift) {
            $activeCourts = DrawingMatchNumber::select('court_id')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->whereNotNull('court_id')
                ->distinct()
                ->with('court')
                ->orderBy('court_id')
                ->get();

            $assignedOfficers = SchedulePanitera::with('user')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->get();

            foreach ($activeCourts as $courtItem) {
                $courtOfficers = $assignedOfficers->where('court_id', $courtItem->court_id);
                $koors = $courtOfficers->where('role_type', 'koordinator')->sortBy('slot_index');
                $paniteras = $courtOfficers->where('role_type', 'panitera')->sortBy('slot_index');

                $rows->push([
                    'tanggal' => $shift->rundown ? Carbon::parse($shift->rundown->date)->isoFormat('dddd, D MMMM Y') : '-',
                    'sesi' => $shift->sessionTime ? $shift->sessionTime->name : '-',
                    'waktu' => $shift->sessionTime ? $shift->sessionTime->start_time->format('H:i').' - '.$shift->sessionTime->end_time->format('H:i') : '-',
                    'lapangan' => $courtItem->court ? $courtItem->court->name : '-',
                    'koordinator' => $koors->pluck('user.name')->join(', ') ?: '-',
                    'panitera' => $paniteras->pluck('user.name')->join(', ') ?: '-',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Sesi',
            'Waktu',
            'Lapangan',
            'Koordinator Lapangan',
            'Panitera',
        ];
    }

    public function map($row): array
    {
        return [
            $row['tanggal'],
            $row['sesi'],
            $row['waktu'],
            $row['lapangan'],
            $row['koordinator'],
            $row['panitera'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            ],
        ];
    }
}
