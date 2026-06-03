<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UnregisteredAthleteReportExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        protected array $matchData,
        protected array $unregisteredAthletes
    ) {}

    public function view(): View
    {
        return view('exports.unregistered-athlete-report-export', [
            'matchData' => $this->matchData,
            'unregisteredAthletes' => $this->unregisteredAthletes,
        ]);
    }

    public function title(): string
    {
        return 'Lap. Kontingen & Atlet Kosong';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
