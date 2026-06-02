<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MultiNomorReportExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        protected array $multiAthletes,
        protected array $normalAthletes,
        protected array $scheduledMatches,
        protected int $courtCount,
        protected int $hariCount
    ) {}

    public function view(): View
    {
        return view('exports.multi-nomor-report-export', [
            'multiAthletes' => $this->multiAthletes,
            'normalAthletes' => $this->normalAthletes,
            'scheduledMatches' => $this->scheduledMatches,
            'courtCount' => $this->courtCount,
            'hariCount' => $this->hariCount,
        ]);
    }

    public function title(): string
    {
        return 'Deteksi & Jadwal Anti Bentrok';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
