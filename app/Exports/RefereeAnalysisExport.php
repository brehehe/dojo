<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RefereeAnalysisExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;

    protected $type;

    public function __construct($data, $type = 'SKW')
    {
        $this->data = collect($data);
        $this->type = $type;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Ranking '.$this->type;
    }

    public function headings(): array
    {
        if ($this->type === 'SKW') {
            return [
                'Rank',
                'Wasit',
                'Court',
                'Jumlah Penilaian',
                'IAW (%)',
                'IK',
                'IV',
                'SKW',
                'Grade',
                'Keterangan',
            ];
        }

        if ($this->type === 'IAW') {
            return [
                'Wasit',
                'Jumlah',
                'Rata-rata',
                'Referensi',
                'IAW (%)',
                'Kategori',
            ];
        }

        if ($this->type === 'IK') {
            return [
                'Wasit',
                'Jumlah',
                'Max',
                'Min',
                'Std Dev',
                'IK',
                'Kategori',
            ];
        }

        return [];
    }

    public function map($row): array
    {
        if ($this->type === 'SKW') {
            static $rank = 0;
            $rank++;

            return [
                $rank,
                $row['name'],
                $row['primary_court'],
                $row['count'],
                number_format($row['iaw'], 2).'%',
                number_format($row['ik'], 3),
                number_format($row['iv'], 3),
                number_format($row['skw'], 2),
                $row['grade'],
                $row['grade_label'],
            ];
        }

        if ($this->type === 'IAW') {
            return [
                $row['name'],
                $row['count'],
                number_format($row['avg_total'], 2),
                number_format($row['avg_ref'], 2),
                number_format($row['iaw'], 2).'%',
                $row['iaw_category'],
            ];
        }

        if ($this->type === 'IK') {
            return [
                $row['name'],
                $row['count'],
                number_format($row['max'], 1),
                number_format($row['min'], 1),
                number_format($row['std_dev'], 3),
                number_format($row['ik'], 3),
                $row['ik_category'],
            ];
        }

        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
