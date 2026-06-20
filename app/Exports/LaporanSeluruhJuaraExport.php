<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanSeluruhJuaraExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(protected array $matchData) {}

    public function view(): View
    {
        return view('exports.laporan-seluruh-juara-export', [
            'matchData' => $this->matchData,
        ]);
    }

    public function title(): string
    {
        return 'Rekap Laporan Seluruh Juara';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
