<?php

namespace App\Exports;

use App\Models\DrawingMatchNumber;
use App\Models\ScheduleReferee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RefereeAssignmentExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
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

            $assignedReferees = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $shift->rundown_id)
                ->where('session_time_id', $shift->session_time_id)
                ->get();

            $dewan = $assignedReferees->whereNull('court_id')->where('judge_index', 0)->first();
            $dewanName = $dewan?->referee?->user?->name ?: '-';

            foreach ($activeCourts as $courtItem) {
                $courtRefs = $assignedReferees->where('court_id', $courtItem->court_id)->where('judge_index', '>', 0)->sortBy('judge_index');

                $rows->push([
                    'tanggal' => $shift->rundown ? Carbon::parse($shift->rundown->date)->isoFormat('dddd, D MMMM Y') : '-',
                    'sesi' => $shift->sessionTime ? $shift->sessionTime->name : '-',
                    'waktu' => $shift->sessionTime ? $shift->sessionTime->start_time->format('H:i').' - '.$shift->sessionTime->end_time->format('H:i') : '-',
                    'dewan' => $dewanName,
                    'lapangan' => $courtItem->court ? $courtItem->court->name : '-',
                    'wasit1' => $courtRefs->where('judge_index', 1)->first()?->referee?->user?->name ?: '-',
                    'wasit2' => $courtRefs->where('judge_index', 2)->first()?->referee?->user?->name ?: '-',
                    'wasit3' => $courtRefs->where('judge_index', 3)->first()?->referee?->user?->name ?: '-',
                    'wasit4' => $courtRefs->where('judge_index', 4)->first()?->referee?->user?->name ?: '-',
                    'wasit5' => $courtRefs->where('judge_index', 5)->first()?->referee?->user?->name ?: '-',
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
            'Dewan Arbitrase',
            'Lapangan',
            'Wasit 1 (Utama)',
            'Wasit 2',
            'Wasit 3',
            'Wasit 4',
            'Wasit 5',
        ];
    }

    public function map($row): array
    {
        return [
            $row['tanggal'],
            $row['sesi'],
            $row['waktu'],
            $row['dewan'],
            $row['lapangan'],
            $row['wasit1'],
            $row['wasit2'],
            $row['wasit3'],
            $row['wasit4'],
            $row['wasit5'],
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
