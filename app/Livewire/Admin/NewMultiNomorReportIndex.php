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

    public $courtCount = 3;

    public $hariCount = 2;

    public $multiAthletes = [];

    public $normalAthletes = [];

    public $totalAtlet = 0;

    public $scheduledMatches = [];

    public $activeDay = 1;

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
                    ];
                }
                $atletMap[$atlet]['count']++;
                if (! in_array($match['id'], $atletMap[$atlet]['nomorList'])) {
                    $atletMap[$atlet]['nomorList'][] = $match['id'];
                    $atletMap[$atlet]['typeList'][] = $match['type'];
                }
            }
        }

        $this->normalAthletes = [];
        $this->multiAthletes = [];
        foreach ($atletMap as $nama => $data) {
            $jumlahNomorBerbeda = count($data['nomorList']);
            $atletInfo = [
                'nama' => $nama,
                'jumlahNomor' => $jumlahNomorBerbeda,
                'totalMuncul' => $data['count'],
                'nomorList' => $data['nomorList'],
                'typeList' => $data['typeList'],
            ];

            if ($jumlahNomorBerbeda > 1) {
                $this->multiAthletes[] = $atletInfo;
            } else {
                $this->normalAthletes[] = $atletInfo;
            }
        }

        usort($this->multiAthletes, function ($a, $b) {
            return $b['jumlahNomor'] <=> $a['jumlahNomor'];
        });

        $this->totalAtlet = Athlete::count();
        $this->generateSafeSchedule();
    }

    public function generateSafeSchedule()
    {
        $courtCount = (int) $this->courtCount;
        $hariCount = (int) $this->hariCount;
        if ($courtCount < 1) {
            $courtCount = 3;
        }
        if ($hariCount < 1) {
            $hariCount = 2;
        }

        $allMatchItems = [];
        foreach ($this->matches as $m) {
            $pesertaList = array_filter(array_map('trim', explode(',', $m['pesertaText'])));
            if (count($pesertaList) < 2) {
                continue;
            }

            for ($i = 0; $i < count($pesertaList); $i++) {
                $opponent = $pesertaList[($i + 1) % count($pesertaList)];
                $allMatchItems[] = [
                    'nomorId' => $m['id'],
                    'nomorName' => $m['name'],
                    'type' => $m['type'],
                    'roundName' => 'Match '.($i + 1),
                    'athletes' => [$pesertaList[$i], $opponent],
                    'hariPrefer' => 1,
                ];
            }

            if (count($pesertaList) >= 2) {
                $allMatchItems[] = [
                    'nomorId' => $m['id'],
                    'nomorName' => $m['name'],
                    'type' => $m['type'],
                    'roundName' => 'FINAL',
                    'athletes' => [$pesertaList[0], $pesertaList[1]],
                    'hariPrefer' => 1,
                ];
            }
        }

        $startHour = 7.5;
        $endHour = 18.0;
        $slotMinutes = 10;
        $timeSlots = [];

        for ($day = 1; $day <= $hariCount; $day++) {
            $currentTime = $startHour;
            while ($currentTime + ($slotMinutes / 60) <= $endHour + 0.01) {
                $hour = floor($currentTime);
                $minute = round(($currentTime - $hour) * 60);
                $timeStr = sprintf('%02d:%02d', $hour, $minute);

                for ($c = 1; $c <= $courtCount; $c++) {
                    $courtName = 'Court '.chr(64 + $c);
                    $timeSlots[] = [
                        'day' => $day,
                        'time' => $timeStr,
                        'hourDecimal' => $currentTime,
                        'court' => $courtName,
                    ];
                }
                $currentTime += $slotMinutes / 60;
            }
        }

        $athleteLastSlot = [];
        $scheduled = [];

        foreach ($allMatchItems as $match) {
            $assigned = false;
            foreach ($timeSlots as $slot) {
                $conflict = false;
                foreach ($match['athletes'] as $athlete) {
                    if (isset($athleteLastSlot[$athlete])) {
                        $last = $athleteLastSlot[$athlete];
                        if ($last['day'] === $slot['day'] && abs($last['hourDecimal'] - $slot['hourDecimal']) < 0.5) {
                            $conflict = true;
                            break;
                        }
                    }
                }

                if (! $conflict) {
                    $scheduled[] = array_merge($match, [
                        'time' => $slot['time'],
                        'court' => $slot['court'],
                        'day' => $slot['day'],
                        'hourDecimal' => $slot['hourDecimal'],
                    ]);

                    foreach ($match['athletes'] as $athlete) {
                        $athleteLastSlot[$athlete] = [
                            'day' => $slot['day'],
                            'hourDecimal' => $slot['hourDecimal'],
                        ];
                    }
                    $assigned = true;
                    break;
                }
            }

            if (! $assigned && ! empty($timeSlots)) {
                $fallback = $timeSlots[0];
                $scheduled[] = array_merge($match, [
                    'time' => $fallback['time'],
                    'court' => $fallback['court'],
                    'day' => $fallback['day'],
                    'hourDecimal' => $fallback['hourDecimal'],
                ]);
            }
        }

        usort($scheduled, function ($a, $b) {
            if ($a['day'] !== $b['day']) {
                return $a['day'] <=> $b['day'];
            }
            $courtComp = strcmp($a['court'], $b['court']);
            if ($courtComp !== 0) {
                return $courtComp;
            }

            return strcmp($a['time'], $b['time']);
        });

        $this->scheduledMatches = $scheduled;

        $days = array_unique(array_column($this->scheduledMatches, 'day'));
        if (! empty($days) && ! in_array($this->activeDay, $days)) {
            $this->activeDay = min($days);
        }
    }

    public function setActiveDay($day)
    {
        $this->activeDay = $day;
    }

    public function downloadExcel()
    {
        $filename = 'Laporan_Multi_Nomor_'.date('Ymd_His').'.xlsx';

        return Excel::download(new MultiNomorReportExport($this->multiAthletes, $this->normalAthletes, $this->scheduledMatches, $this->courtCount, $this->hariCount), $filename);
    }

    public function render()
    {
        return view('livewire.admin.new-multi-nomor-report-index');
    }
}
