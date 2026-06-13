<?php

namespace App\Livewire\Contingent;

use App\Models\Court\Court;
use App\Models\Referee;
use App\Models\RefereeObservation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class RefereeObservationForm extends Component
{
    public ?RefereeObservation $observation = null;

    public string $mode = 'create'; // create, edit, view

    public $contingent;

    // Form fields
    public $referee_id;

    public $observer_name;

    public $observation_date;

    public $court;

    public $round = 'Penyisihan';

    public $match_time;

    public $referee_number = 'I';

    public $contingent_away;

    public $contingent_home;

    public $total_score = 0.00;

    public $category = 'KURANG';

    public $kepada;

    public $dari;

    public $tanggal_laporan;

    public $kelebihan;

    public $area_perbaikan;

    public $rekomendasi;

    // Structured JSON data fields
    public array $p1 = []; // Parameter 1: Decisions

    public array $p2 = []; // Parameter 2: Communication

    public array $p3 = []; // Parameter 3: Movement

    public array $p4 = []; // Parameter 4: Match Control

    public array $p5 = []; // Parameter 5: Professionalism

    public array $event_recording = []; // Page 2: Specific events

    public array $time_sampling = []; // Page 3: 5-min intervals

    public array $rating_scale = []; // Page 4: Rating scale sheet

    public array $etika = []; // Page 6: Ethics Checklist

    public string $currentTab = 'identitas';

    public function mount(?RefereeObservation $observation = null)
    {
        $user = Auth::user();

        // Resolve contingent context
        if ($user->hasRole('Contingent')) {
            if (! $user->contingent()->exists()) {
                return redirect()->route('contingent.setup');
            }
            $this->contingent = $user->contingent;
        }

        // Determine view mode based on current route
        $routeName = request()->route()->getName();
        if (str_contains($routeName, '.show')) {
            $this->mode = 'view';
        } elseif (str_contains($routeName, '.edit')) {
            $this->mode = 'edit';
        } else {
            $this->mode = 'create';
        }

        if ($this->mode === 'create') {
            $this->observation_date = now()->format('Y-m-d');
            $this->tanggal_laporan = now()->format('Y-m-d');
            $this->observer_name = $user->name;
            $this->kepada = 'Manager Kontingen '.($this->contingent ? $this->contingent->name : '');
            $this->dari = $user->name;

            // Set default court dynamically
            $firstCourt = Court::first();
            $this->court = $firstCourt ? $firstCourt->name : 'Court 1';

            $this->initializeEmptyStructures();
        } else {
            $this->observation = $observation;

            // Authorization check
            if ($user->hasRole('Contingent') && $observation->contingent_id !== $this->contingent->id) {
                abort(403, 'Unauthorized action.');
            }

            $this->contingent = $observation->contingent;
            $this->referee_id = $observation->referee_id;
            $this->observer_name = $observation->observer_name;
            $this->observation_date = $observation->observation_date ? $observation->observation_date->format('Y-m-d') : null;
            $this->court = $observation->court;
            $this->round = $observation->round;
            $this->match_time = $observation->match_time;
            $this->referee_number = $observation->referee_number;
            $this->contingent_away = $observation->contingent_away;
            $this->contingent_home = $observation->contingent_home;
            $this->total_score = (float) $observation->total_score;
            $this->category = $observation->category;
            $this->kepada = $observation->kepada;
            $this->dari = $observation->dari;
            $this->tanggal_laporan = $observation->tanggal_laporan ? $observation->tanggal_laporan->format('Y-m-d') : null;
            $this->kelebihan = $observation->kelebihan;
            $this->area_perbaikan = $observation->area_perbaikan;
            $this->rekomendasi = $observation->rekomendasi;

            // Load structured JSON data
            $data = $observation->data ?: [];
            $this->p1 = $data['p1'] ?? [];
            $this->p2 = $data['p2'] ?? [];
            $this->p3 = $data['p3'] ?? [];
            $this->p4 = $data['p4'] ?? [];
            $this->p5 = $data['p5'] ?? [];
            $this->event_recording = $data['event_recording'] ?? [];
            $this->time_sampling = $data['time_sampling'] ?? [];
            $this->rating_scale = $data['rating_scale'] ?? [];
            $this->etika = $data['etika'] ?? [];

            // Re-sync structures in case they were partially empty
            $this->syncStructuresWithDefaults();
        }

        $this->calculateScores();
    }

    private function initializeEmptyStructures()
    {
        // P1: 6 rows
        $this->p1 = [
            'rows' => [
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'konsisten' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'konsisten' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'konsisten' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'konsisten' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'konsisten' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'konsisten' => ''],
            ],
            'jumlah_konsisten' => 0,
            'skor_konsistensi' => 0,
            'catatan' => '',
        ];

        // P2: 6 indicators rated 1-5
        $this->p2 = [
            'ratings' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0],
            'total_skor' => 0,
            'nilai_persen' => 0,
            'skor_komunikasi' => 0,
            'catatan' => '',
        ];

        // P3: 5 indicators rated 1-5
        $this->p3 = [
            'ratings' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
            'total_skor' => 0,
            'nilai_persen' => 0,
            'skor_posisi' => 0,
        ];

        // P4: 5 indicators rated 1-5
        $this->p4 = [
            'ratings' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
            'total_skor' => 0,
            'nilai_persen' => 0,
            'skor_pengendalian' => 0,
        ];

        // P5: 5 indicators rated 1-5
        $this->p5 = [
            'ratings' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
            'total_skor' => 0,
            'nilai_persen' => 0,
            'skor_profesionalisme' => 0,
        ];

        // Event Recording Sheet: 10 rows
        $this->event_recording = [
            'rows' => [
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
                ['waktu' => '', 'jenis' => 'Pelanggaran ringan', 'keputusan' => '', 'evaluasi' => ''],
            ],
            'total_kejadian' => 0,
            'total_tepat' => 0,
        ];

        // Time Sampling: 8 rows
        $this->time_sampling = [
            'rows' => [
                ['interval' => '0-5', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '5-10', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '10-15', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '15-20', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '20-25', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '25-30', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '30-35', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '35-40', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
            ],
        ];

        // Rating Scale Sheet: 12 indicators rated 1-5
        $this->rating_scale = [
            'ratings' => [
                1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0,
                7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0,
            ],
            'total_skor' => 0,
            'nilai_akhir' => 0,
        ];

        // Ethics Checklist: 7 checkpoints
        $this->etika = [
            'checks' => [
                1 => false,
                2 => false,
                3 => false,
                4 => false,
                5 => false,
                6 => false,
                7 => false,
            ],
            'pernyataan' => false,
        ];
    }

    private function syncStructuresWithDefaults()
    {
        // Safe merge helper
        $this->p1['rows'] = array_replace(
            [['waktu' => '', 'jenis' => '', 'keputusan' => '', 'konsisten' => ''], ['waktu' => '', 'jenis' => '', 'keputusan' => '', 'konsisten' => ''], ['waktu' => '', 'jenis' => '', 'keputusan' => '', 'konsisten' => ''], ['waktu' => '', 'jenis' => '', 'keputusan' => '', 'konsisten' => ''], ['waktu' => '', 'jenis' => '', 'keputusan' => '', 'konsisten' => ''], ['waktu' => '', 'jenis' => '', 'keputusan' => '', 'konsisten' => '']],
            $this->p1['rows'] ?? []
        );

        $this->p2['ratings'] = array_replace([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0], $this->p2['ratings'] ?? []);
        $this->p3['ratings'] = array_replace([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0], $this->p3['ratings'] ?? []);
        $this->p4['ratings'] = array_replace([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0], $this->p4['ratings'] ?? []);
        $this->p5['ratings'] = array_replace([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0], $this->p5['ratings'] ?? []);

        $this->event_recording['rows'] = array_replace(
            array_fill(0, 10, ['waktu' => '', 'jenis' => '', 'keputusan' => '', 'evaluasi' => '']),
            $this->event_recording['rows'] ?? []
        );

        $this->time_sampling['rows'] = array_replace(
            [
                ['interval' => '0-5', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '5-10', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '10-15', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '15-20', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '20-25', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '25-30', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '30-35', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
                ['interval' => '35-40', 'posisi' => 'Baik', 'komunikasi' => 'Jelas', 'pengendalian' => 'Baik', 'catatan' => ''],
            ],
            $this->time_sampling['rows'] ?? []
        );

        $this->rating_scale['ratings'] = array_replace(
            [
                1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0,
                7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0,
            ],
            $this->rating_scale['ratings'] ?? []
        );

        $this->etika['checks'] = array_replace(
            [1 => false, 2 => false, 3 => false, 4 => false, 5 => false, 6 => false, 7 => false],
            $this->etika['checks'] ?? []
        );
        $this->etika['pernyataan'] = $this->etika['pernyataan'] ?? false;
    }

    public function updated($propertyName)
    {
        $this->calculateScores();
    }

    public function calculateScores(): void
    {
        // ── 1. Parameter 1: Konsistensi Keputusan (Bobot 30%) ──
        $jumlahKonsisten = 0;
        $activeP1Count = 0;
        foreach ($this->p1['rows'] as $row) {
            if (! empty($row['waktu']) || ! empty($row['keputusan'])) {
                $activeP1Count++;
                if ($row['konsisten'] === 'ya') {
                    $jumlahKonsisten++;
                }
            }
        }
        $this->p1['jumlah_konsisten'] = $jumlahKonsisten;

        // Out of 6 fixed incidents (or active ones, let's use the standard 6 as total incidents or dynamic active count)
        // Standard form says: "Jumlah keputusan konsisten .... dari .... kejadian"
        // Let's use 6 as the denominator as it's the fixed table size. If they did less, it's out of active count.
        // Let's make it out of 6, or out of $activeP1Count if it's greater than 0. Let's make it out of 6.
        $denominator = 6;
        $this->p1['skor_konsistensi'] = ($denominator > 0) ? round(($jumlahKonsisten / $denominator) * 30, 2) : 0;

        // ── 2. Parameter 2: Kejelasan Komunikasi (Bobot 20%) ──
        $totalP2 = 0;
        foreach ($this->p2['ratings'] as $indicator => $val) {
            $totalP2 += (int) $val;
        }
        $this->p2['total_skor'] = $totalP2;
        $this->p2['nilai_persen'] = round(($totalP2 / 30) * 100, 2);
        $this->p2['skor_komunikasi'] = round(($totalP2 / 30) * 20, 2);

        // ── 3. Parameter 3: Posisi dan Pergerakan (Bobot 15%) ──
        $totalP3 = 0;
        foreach ($this->p3['ratings'] as $val) {
            $totalP3 += (int) $val;
        }
        $this->p3['total_skor'] = $totalP3;
        $this->p3['nilai_persen'] = round(($totalP3 / 25) * 100, 2);
        $this->p3['skor_posisi'] = round(($totalP3 / 25) * 15, 2);

        // ── 4. Parameter 4: Pengendalian Pertandingan (Bobot 20%) ──
        $totalP4 = 0;
        foreach ($this->p4['ratings'] as $val) {
            $totalP4 += (int) $val;
        }
        $this->p4['total_skor'] = $totalP4;
        $this->p4['nilai_persen'] = round(($totalP4 / 25) * 100, 2);
        $this->p4['skor_pengendalian'] = round(($totalP4 / 25) * 20, 2);

        // ── 5. Parameter 5: Sikap dan Profesionalisme (Bobot 15%) ──
        $totalP5 = 0;
        foreach ($this->p5['ratings'] as $val) {
            $totalP5 += (int) $val;
        }
        $this->p5['total_skor'] = $totalP5;
        $this->p5['nilai_persen'] = round(($totalP5 / 25) * 100, 2);
        $this->p5['skor_profesionalisme'] = round(($totalP5 / 25) * 15, 2);

        // ── Total Score ──
        $this->total_score = $this->p1['skor_konsistensi'] +
                             $this->p2['skor_komunikasi'] +
                             $this->p3['skor_posisi'] +
                             $this->p4['skor_pengendalian'] +
                             $this->p5['skor_profesionalisme'];
        $this->total_score = round($this->total_score, 2);

        // ── Kategori ──
        if ($this->total_score >= 90) {
            $this->category = 'SANGAT BAIK';
        } elseif ($this->total_score >= 75) {
            $this->category = 'BAIK';
        } elseif ($this->total_score >= 60) {
            $this->category = 'CUKUP';
        } else {
            $this->category = 'KURANG';
        }

        // ── Page 2: Event Recording statistics ──
        $totalEvents = 0;
        $totalTepat = 0;
        foreach ($this->event_recording['rows'] as $row) {
            if (! empty($row['waktu']) || ! empty($row['keputusan'])) {
                $totalEvents++;
                if ($row['evaluasi'] === 'ya') {
                    $totalTepat++;
                }
            }
        }
        $this->event_recording['total_kejadian'] = $totalEvents;
        $this->event_recording['total_tepat'] = $totalTepat;

        // ── Page 4: Rating Scale Sheet ──
        $totalScale = 0;
        foreach ($this->rating_scale['ratings'] as $val) {
            $totalScale += (int) $val;
        }
        $this->rating_scale['total_skor'] = $totalScale;
        $this->rating_scale['nilai_akhir'] = round(($totalScale / 60) * 100, 2);
    }

    public function setTab($tab): void
    {
        $this->currentTab = $tab;
    }

    public function saveObservation()
    {
        $this->validate([
            'referee_id' => 'required|exists:referees,id',
            'observer_name' => 'required|string|max:255',
            'observation_date' => 'required|date',
            'court' => 'required|string',
            'round' => 'required|string',
            'match_time' => 'required|string',
            'referee_number' => 'required|string',
            'contingent_away' => 'nullable|string|max:255',
            'contingent_home' => 'nullable|string|max:255',
            'kepada' => 'nullable|string|max:255',
            'dari' => 'nullable|string|max:255',
            'tanggal_laporan' => 'nullable|date',
            'kelebihan' => 'nullable|string',
            'area_perbaikan' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
        ]);

        $this->calculateScores();

        $dataPayload = [
            'p1' => $this->p1,
            'p2' => $this->p2,
            'p3' => $this->p3,
            'p4' => $this->p4,
            'p5' => $this->p5,
            'event_recording' => $this->event_recording,
            'time_sampling' => $this->time_sampling,
            'rating_scale' => $this->rating_scale,
            'etika' => $this->etika,
        ];

        $attributes = [
            'referee_id' => $this->referee_id,
            'observer_name' => $this->observer_name,
            'observation_date' => $this->observation_date,
            'court' => $this->court,
            'round' => $this->round,
            'match_time' => $this->match_time,
            'referee_number' => $this->referee_number,
            'contingent_away' => $this->contingent_away,
            'contingent_home' => $this->contingent_home,
            'total_score' => $this->total_score,
            'category' => $this->category,
            'kepada' => $this->kepada,
            'dari' => $this->dari,
            'tanggal_laporan' => $this->tanggal_laporan,
            'kelebihan' => $this->kelebihan,
            'area_perbaikan' => $this->area_perbaikan,
            'rekomendasi' => $this->rekomendasi,
            'data' => $dataPayload,
        ];

        if ($this->mode === 'create') {
            $attributes['contingent_id'] = $this->contingent->id;
            RefereeObservation::create($attributes);

            $this->dispatch('swal', [
                'title' => 'Tersimpan!',
                'text' => 'Observasi wasit telah berhasil dicatat.',
                'icon' => 'success',
            ]);
        } else {
            $this->observation->update($attributes);

            $this->dispatch('swal', [
                'title' => 'Diperbarui!',
                'text' => 'Observasi wasit telah berhasil diperbarui.',
                'icon' => 'success',
            ]);
        }

        return redirect()->route('contingent.observasi-wasit.index');
    }

    public function render()
    {
        $referees = Referee::with('user')
            ->join('users', 'referees.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('referees.*')
            ->get();

        $courts = Court::orderBy('order')->get();

        return view('livewire.contingent.referee-observation-form', [
            'referees' => $referees,
            'courts' => $courts,
        ])->title(($this->mode === 'create' ? 'Tambah' : ($this->mode === 'edit' ? 'Edit' : 'Lihat')).' Observasi Wasit');
    }
}
