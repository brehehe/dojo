<?php

namespace App\Livewire\Admin;

use App\Exports\MultiNomorReportExport;
use App\Models\Athlete;
use App\Models\MatchNumber\MatchNumber;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.premium')]
class NewMultiNomorReportIndex extends Component
{
    public $matches = [];

    public $multiAthletes = [];

    public $normalAthletes = [];

    public $allAthletes = [];

    public $totalAtlet = 0;

    public function mount()
    {
        $this->loadDataFromDb();
    }

    public function loadDataFromDb()
    {
        $matchNumbers = MatchNumber::with(['athletes'])->orderBy('id')->get();
        $this->matches = [];
        foreach ($matchNumbers as $mn) {
            $peserta = $mn->athletes->pluck('name')->toArray();
            $this->matches[] = [
                'id' => (string) ($mn->id),
                'name' => $mn->name,
                'type' => strtolower($mn->draft_type) === 'randori' ? 'Randori' : 'Embu',
                'pesertaText' => implode(', ', $peserta),
            ];
        }
        $this->analyze();
    }

    public function addMatch()
    {
        $newIndex = count($this->matches) + 1;
        $this->matches[] = [
            'id' => 'M'.$newIndex,
            'name' => 'Nama Pertandingan Baru',
            'type' => 'Embu',
            'pesertaText' => '',
        ];
    }

    public function removeMatch($idx)
    {
        if (isset($this->matches[$idx])) {
            array_splice($this->matches, $idx, 1);
        }
    }

    public function analyze()
    {
        $atletMap = [];

        foreach ($this->matches as $match) {
            $peserta = array_filter(array_map('trim', explode(',', $match['pesertaText'])));
            foreach ($peserta as $atlet) {
                if (empty($atlet)) {
                    continue;
                }
                if (! isset($atletMap[$atlet])) {
                    $atletMap[$atlet] = [
                        'count' => 0,
                        'nomorList' => [],
                        'typeList' => [],
                        'nomorNameList' => [],
                    ];
                }
                $atletMap[$atlet]['count']++;
                if (! in_array($match['id'], $atletMap[$atlet]['nomorList'])) {
                    $atletMap[$atlet]['nomorList'][] = $match['id'];
                    $atletMap[$atlet]['typeList'][] = $match['type'];
                    $atletMap[$atlet]['nomorNameList'][] = $match['name'];
                }
            }
        }

        $allDbAthletes = Athlete::pluck('name')->toArray();
        foreach ($allDbAthletes as $dbAtletName) {
            $dbAtletName = trim($dbAtletName);
            if (! isset($atletMap[$dbAtletName])) {
                $atletMap[$dbAtletName] = [
                    'count' => 0,
                    'nomorList' => [],
                    'typeList' => [],
                    'nomorNameList' => [],
                ];
            }
        }

        $athletes = Athlete::all();
        $contingentMap = [];
        foreach ($athletes as $athlete) {
            $contingentMap[strtolower(trim($athlete->name))] = $athlete->contingent?->name ?? '-';
        }

        $this->normalAthletes = [];
        $this->multiAthletes = [];
        $this->allAthletes = [];
        foreach ($atletMap as $nama => $data) {
            $jumlahNomorBerbeda = count($data['nomorList']);
            $atletInfo = [
                'nama' => $nama,
                'contingent' => $contingentMap[strtolower(trim($nama))] ?? '-',
                'jumlahNomor' => $jumlahNomorBerbeda,
                'totalMuncul' => $data['count'],
                'nomorList' => $data['nomorList'],
                'typeList' => $data['typeList'],
                'nomorNameList' => $data['nomorNameList'],
            ];

            if ($jumlahNomorBerbeda > 1) {
                $this->multiAthletes[] = $atletInfo;
            } else {
                $this->normalAthletes[] = $atletInfo;
            }
            $this->allAthletes[] = $atletInfo;
        }

        usort($this->allAthletes, function ($a, $b) {
            return $b['jumlahNomor'] <=> $a['jumlahNomor'];
        });

        $this->totalAtlet = Athlete::count();
    }

    public function downloadExcel()
    {
        $filename = 'Laporan_Multi_Nomor_'.date('Ymd_His').'.xlsx';

        return Excel::download(new MultiNomorReportExport($this->allAthletes), $filename);
    }

    public function render()
    {
        return view('livewire.admin.new-multi-nomor-report-index');
    }
}
