<?php

namespace App\Exports;

use App\Models\Athlete;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AthleteExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(
        public string $search = '',
        public string $filterContingent = '',
        public string $filterGender = ''
    ) {}

    public function query()
    {
        return Athlete::query()->with(['contingents', 'categories', 'registrations'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'ilike', '%'.$this->search.'%')
                      ->orWhere('nik', 'ilike', '%'.$this->search.'%');
                });
            })
            ->when($this->filterContingent, function ($query) {
                $query->whereHas('contingents', function ($q) {
                    $q->where('contingent_id', $this->filterContingent);
                });
            })
            ->when($this->filterGender, function ($query) {
                $query->where('gender', $this->filterGender);
            });
    }

    public function headings(): array
    {
        return [
            'NIK',
            'NIK Kenshi',
            'Nama',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Golongan Darah',
            'No HP / Telepon',
            'Alamat',
            'Dojo Asal',
            'Kontingen',
            'Berat Badan (kg)',
            'Kyu / Tingkatan',
            'Kategori Lomba',
            'No BPJS',
            'Status BPJS',
        ];
    }

    public function map($athlete): array
    {
        $contingent = $athlete->contingent;
        $latestReg  = $athlete->latestRegistration();
        
        $categories = collect();
        if ($latestReg) {
            $categories = $athlete->categories()
                ->wherePivot('registration_id', $latestReg->id)
                ->get();
        }

        return [
            "'" . $athlete->nik,
            "'" . $athlete->nik_kenshi,
            $athlete->name,
            $athlete->gender === 'L' ? 'Laki-laki' : ($athlete->gender === 'P' ? 'Perempuan' : $athlete->gender),
            $athlete->birth_place,
            $athlete->birth_date ? $athlete->birth_date->format('Y-m-d') : '-',
            $athlete->blood_type,
            "'" . $athlete->phone,
            $athlete->address,
            $athlete->dojo_origin,
            $contingent ? $contingent->name : 'Tanpa Kontingen',
            $athlete->weight ?? '-',
            $athlete->kyu ?? '-',
            $categories->pluck('name')->join(', '),
            "'" . $athlete->bpjs_number,
            $athlete->bpjs_status,
        ];
    }
}
