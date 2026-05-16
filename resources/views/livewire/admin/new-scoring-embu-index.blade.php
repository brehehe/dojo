<div>
    @push('styles')
        <style>
            .tm-page {
                padding: 24px;
                padding-bottom: 100px;
                background: var(--paper, #F7F4EF);
                min-height: 100vh;
            }

            /* HEADER */
            .tm-hdr {
                margin-bottom: 20px;
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
            }

            .tm-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 24px;
                font-weight: 700;
                margin: 0 0 8px;
                color: var(--ink, #2c3e50);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .tm-badge-title {
                display: inline-block;
                padding: 6px 16px;
                background: var(--ink);
                color: #fff;
                font-size: 13px;
                font-weight: 700;
                border-radius: 20px;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }

            /* ROUND INDICATOR */
            .round-indicator {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 16px;
                margin-top: 20px;
                margin-bottom: 20px;
            }

            /* OFFICIALS */
            .officials-stack {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-top: 40px;
                margin-bottom: 24px;
            }

            .officials-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
                margin-top: 40px;
                margin-bottom: 24px;
            }

            @media (max-width: 768px) {
                .officials-grid {
                    grid-template-columns: 1fr;
                }
            }

            .official-card {
                padding: 14px 20px;
                border-left: 4px solid var(--red);
                margin-bottom: 0 !important;
            }

            .official-label {
                font-size: 10px;
                font-weight: 800;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 6px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .official-label i {
                color: var(--red);
                font-size: 13px;
            }

            .official-val {
                font-size: 15px;
                font-weight: 700;
                color: var(--ink);
                font-family: 'Outfit', sans-serif;
            }

            .official-sub {
                font-size: 11px;
                color: var(--smoke);
                margin-top: 2px;
            }

            .official-list {
                list-style: none;
                padding: 0;
                margin: 4px 0 0 0;
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .official-list li {
                font-size: 14px;
                font-weight: 600;
                color: var(--ink);
            }

            .round-step {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 20px;
                border-radius: 30px;
                font-size: 13px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                background: #fff;
                color: var(--smoke);
                border: 1px solid var(--paper2);
            }

            .round-step.active {
                background: var(--ink);
                color: #fff;
                border-color: var(--ink);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            /* INFO BAR */
            .info-bar {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 20px;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 16px;
                margin-bottom: 20px;
            }

            .info-item {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                font-size: 9px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }

            .info-value {
                font-size: 13px;
                font-weight: 700;
                color: var(--ink);
                display: flex;
                align-items: center;
                gap: 8px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .info-value i {
                color: var(--red);
            }

            /* MAIN CARDS */
            .tm-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                overflow: hidden;
                margin-bottom: 20px;
            }

            .tm-card-head {
                padding: 16px 20px;
                border-bottom: 1px solid var(--paper2);
                background: #fdfbf7;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .tm-card-head h3 {
                font-family: 'Cinzel', serif;
                font-size: 15px;
                font-weight: 700;
                margin: 0;
                color: var(--ink);
            }

            .tm-card-body {
                padding: 20px;
            }

            /* TABLES */
            .score-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }

            .score-table th {
                padding: 12px 14px;
                background: #fdfbf7;
                font-size: 10px;
                color: var(--smoke);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: 1px solid var(--paper2);
                text-align: center;
            }

            .score-table td {
                padding: 12px 14px;
                border: 1px solid var(--paper2);
                vertical-align: middle;
                text-align: center;
            }

            .score-table .text-left {
                text-align: left;
            }

            .score-val {
                font-size: 14px;
                font-weight: 700;
                color: var(--ink);
            }

            .score-val.out {
                color: var(--smoke);
                text-decoration: line-through;
            }

            .score-final {
                font-size: 16px;
                font-weight: 700;
                color: #2980b9;
            }

            /* QUEUE CARDS */
            .queue-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 16px;
            }

            .queue-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 16px;
                display: flex;
                flex-direction: column;
                gap: 12px;
                transition: all .2s;
            }

            .queue-card:hover {
                border-color: #bbb;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .queue-card.active {
                border-color: var(--ink);
                background: #fdfbf7;
                box-shadow: 0 4px 16px rgba(44, 62, 80, 0.1);
                transform: scale(1.02);
            }

            .queue-hdr {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .queue-num {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                background: var(--paper2);
                color: var(--ink);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                font-weight: 700;
                font-family: 'Cinzel', serif;
            }

            .queue-card.active .queue-num {
                background: var(--ink);
                color: #fff;
            }

            .queue-info {
                flex: 1;
                min-width: 0;
            }

            .queue-name {
                font-size: 13px;
                font-weight: 700;
                color: var(--ink);
                text-transform: uppercase;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .queue-cont {
                font-size: 11px;
                color: var(--smoke);
                font-weight: 600;
                margin-top: 4px;
            }

            .queue-actions {
                display: flex;
                gap: 8px;
            }

            /* BUTTONS */
            .btn-gen {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 10px 16px;
                border-radius: 8px;
                border: none;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                transition: all .15s;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .btn-gen.primary {
                background: var(--ink);
                color: #fff;
            }

            .btn-gen.primary:hover {
                background: #1a252f;
                transform: translateY(-1px);
            }

            .btn-gen.ghost {
                background: #fff;
                color: var(--ink);
                border: 1px solid var(--paper2);
            }

            .btn-gen.ghost:hover {
                border-color: var(--ink);
            }

            .btn-gen.danger {
                background: var(--red);
                color: #fff;
            }

            .btn-gen.danger:hover {
                background: #a93226;
            }

            .btn-gen.success {
                background: #27ae60;
                color: #fff;
            }

            .btn-gen.success:hover {
                background: #229954;
            }

            /* MODAL */
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 100;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
            }

            .modal-content {
                background: #fff;
                border-radius: 20px;
                width: 100%;
                max-width: 600px;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            }

            .modal-hdr {
                padding: 20px 24px;
                border-bottom: 1px solid var(--paper2);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .modal-hdr h3 {
                font-family: 'Cinzel', serif;
                font-size: 16px;
                font-weight: 700;
                margin: 0;
                color: var(--ink);
            }

            .modal-close {
                background: var(--paper);
                border: none;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: var(--smoke);
            }

            .modal-close:hover {
                background: var(--red);
                color: #fff;
            }

            .modal-body {
                padding: 24px;
            }

            .score-input-grid {
                display: flex;
                flex-direction: column;
                gap: 16px;
                margin-bottom: 24px;
            }

            .score-input-group {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .score-input-label {
                font-size: 12px;
                font-weight: 700;
                color: var(--ink);
                width: 80px;
                text-transform: uppercase;
            }

            .score-input-field {
                flex: 1;
                padding: 12px;
                border-radius: 12px;
                border: 1px solid var(--paper2);
                background: var(--paper);
                font-size: 16px;
                font-weight: 700;
                text-align: center;
                color: var(--ink);
                outline: none;
            }

            .score-input-field:focus {
                border-color: var(--ink);
            }
        </style>
    @endpush

    <div class="tm-page" x-data="{ round: 1 }">
        <div style="position: fixed; bottom: 30px; right: 30px; z-index: 90;">
            <button wire:click="clearAllCourts"
                wire:confirm="PERINGATAN: Ini akan mereset status SEMUA lapangan & match yang sedang berjalan menjadi KOSONG. Lanjutkan?"
                class="btn-gen danger"
                style="padding: 12px 20px; border-radius: 12px; font-size: 12px; box-shadow: 0 8px 24px rgba(192,57,43,.3);">
                <i class="fas fa-eraser" style="margin-right: 8px;"></i>
                <span class="hidden md:inline">Reset Semua Lapangan</span>
                <span class="md:hidden">Reset</span>
            </button>
        </div>

        <div class="tm-hdr">
            <div>
                <div class="tm-badge-title">{{ strtoupper($merge->name ?? $matchNumber->name) }}</div>
                @if ($merge)
                    <div
                        style="font-size: 11px; color: var(--smoke); font-weight: 600; margin-top: 4px; font-style: italic;">
                        {{ $displayName }}
                    </div>
                @endif
                <h2>Daftar Kompilasi Nilai</h2>
            </div>
            <div style="display:flex; gap:12px;">
                <button wire:click="callOfficials" class="btn-gen primary"
                    style="background:var(--red); box-shadow:0 4px 12px rgba(192,57,43,0.2);">
                    <i class="fas fa-bullhorn"></i> Panggil Official
                </button>
                <button onclick="window.stopAnnouncer && window.stopAnnouncer()" class="btn-gen ghost"
                    style="color:var(--red); border-color:var(--red);">
                    <i class="fas fa-volume-xmark"></i> Stop Suara
                </button>
                <a href="{{ route('admin.new-scoring-index') }}" class="btn-gen ghost">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="round-indicator">
            <div class="round-step {{ $currentRound === 'Penyisihan' ? 'active' : '' }}">
                <i class="fas fa-filter"></i> 1. Penyisihan
            </div>
            <i class="fas fa-chevron-right" style="color:var(--smoke);"></i>
            <div class="round-step {{ $currentRound === 'Final' ? 'active' : '' }}">
                <i class="fas fa-trophy"></i> 2. Final
            </div>
        </div>

        @if (isset($availablePools) && $availablePools->count() > 1)
            <div style="display:flex; justify-content:center; gap:8px; margin-bottom:20px;">
                @foreach ($availablePools as $p)
                    <button wire:click="setPool({{ $p->id }})"
                        class="btn-gen {{ $selectedPoolId === $p->id ? 'primary' : 'ghost' }}">
                        {{ $p->name }}
                    </button>
                @endforeach
            </div>
        @endif

        @php
            $drawing = $firstDrawing;
            $sessionDate = $drawing?->sessionTime?->date ?? now();
            $courtOrder = $drawing?->court?->order ?? '-';
            $poolName = $drawing?->pool?->name ?? ($drawing?->metadata['pool'] ?? '-');
        @endphp
        <div class="info-bar">
            <div class="info-item">
                <span class="info-label">Hari / Tanggal</span>
                <span class="info-value"><i class="far fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($sessionDate)->translatedFormat('l, d M Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tingkat / Golongan</span>
                <span class="info-value"><i class="fas fa-layer-group"></i>
                    {{ $matchNumber->ageGroup?->name ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Pool</span>
                <span class="info-value"><i class="fas fa-door-open"></i> {{ $poolName }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Lapangan (Court)</span>
                <span class="info-value"><i class="fas fa-vector-square"></i> C{{ $courtOrder }}</span>
            </div>
        </div>

        @if (count($tiedIds) > 0)
            <div
                style="background:rgba(192,57,43,.05); border:1px solid var(--red); border-radius:16px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:16px;">
                    <i class="fas fa-exclamation-triangle" style="font-size:24px; color:var(--red);"></i>
                    <div>
                        <div style="font-size:14px; font-weight:700; color:var(--red); text-transform:uppercase;">Nilai
                            Seri Terdeteksi!</div>
                        <div style="font-size:12px; color:var(--smoke); margin-top:4px;">{{ count($tiedIds) }} peserta
                            memiliki nilai sama di ambang lolos. Lakukan tanding ulang.</div>
                    </div>
                </div>
                <button wire:click="requestTiebreak({{ json_encode($tiedIds) }})" class="btn-gen danger"><i
                        class="fas fa-redo"></i> Tanding Ulang</button>
            </div>
        @elseif($currentRound === 'Penyisihan')
            @php $hasAllScores = $registrations->every(fn($r) => $r['score'] !== null); @endphp
            @if ($hasAllScores && $registrations->count() > 0)
                <div
                    style="background:rgba(39,174,96,.05); border:1px solid #27ae60; border-radius:16px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <i class="fas fa-check-circle" style="font-size:24px; color:#27ae60;"></i>
                        <div>
                            <div style="font-size:14px; font-weight:700; color:#27ae60; text-transform:uppercase;">
                                Penyisihan Selesai!</div>
                            <div style="font-size:12px; color:var(--smoke); margin-top:4px;">Semua peserta dinilai. Siap
                                loloskan ke Final.</div>
                        </div>
                    </div>
                    <button wire:click="advanceToFinal()" class="btn-gen success"><i class="fas fa-arrow-right"></i>
                        Loloskan ke Final</button>
                </div>
            @endif
        @endif

        {{-- MAIN TABLE --}}
        <div class="tm-card">
            <div class="tm-card-head">
                <h3>Matriks Penilaian Wasit</h3>
                <div>
                    <button onclick="window.print()" class="btn-gen ghost"><i class="fas fa-print"></i> Cetak</button>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="score-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:40px;">No</th>
                            <th rowspan="2" class="text-left">Peserta & Kontingen</th>
                            <th colspan="5">Nilai Wasit</th>
                            <th rowspan="2">Nilai Awal</th>
                            <th rowspan="2">Nilai Akhir</th>
                            @if ($currentRound === 'Final')
                                <th rowspan="2">Penyisihan</th>
                                <th rowspan="2">Akumulasi</th>
                            @endif
                        </tr>
                        <tr>
                            @foreach ([1, 2, 3, 4, 5] as $w)
                                <th>W{{ $w }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $no => $item)
                            @php
                                $s = $item['score'];
                                $rawVals = $s
                                    ? [
                                        1 => (float) ($s->judge_1 ?? 0),
                                        2 => (float) ($s->judge_2 ?? 0),
                                        3 => (float) ($s->judge_3 ?? 0),
                                        4 => (float) ($s->judge_4 ?? 0),
                                        5 => (float) ($s->judge_5 ?? 0),
                                    ]
                                    : [];

                                if ($s) {
                                    $scoredJudges = array_filter($rawVals, fn($v) => $v > 0);
                                    if (count($scoredJudges) >= 2) {
                                        $tempScored = $scoredJudges;
                                        asort($tempScored);
                                        $keys = array_keys($tempScored);
                                        $minKey = $keys[0];
                                        $maxKey = $keys[count($keys) - 1];
                                    } else {
                                        $minKey = null;
                                        $maxKey = null;
                                    }
                                }

                                $calculatedTotal = 0;
                                if ($s) {
                                    $scoredCount = count(array_filter($rawVals, fn($v) => $v > 0));
                                    if ($scoredCount === 5) {
                                        $sortedVals = $rawVals;
                                        asort($sortedVals);
                                        $vals = array_values($sortedVals);
                                        $calculatedTotal = $vals[1] + $vals[2] + $vals[3];
                                    } else {
                                        $calculatedTotal = array_sum($rawVals);
                                    }
                                }
                                
                                $nilaiAwal = $s?->total_score > 0 ? $s->total_score : $calculatedTotal;
                                $denda = $s?->denda ?? 0;
                                $nilaiAkhir = $s?->nilai_akhir > 0 ? $s->nilai_akhir : max(0, $nilaiAwal - $denda);
                                $isActive = isset($activeDrawingId) && $activeDrawingId == $item['drawing_id'];
                            @endphp
                            <tr style="{{ $isActive ? 'background:#fdfbf7;' : '' }}">
                                <td>{{ $item['sequence_number'] ?? $no + 1 }}</td>
                                <td class="text-left">
                                    <div
                                        style="font-weight:700; color:var(--ink); font-size:13px; text-transform:uppercase;">
                                        @foreach ($item['athletes'] as $athlete)
                                            {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                        @endforeach
                                    </div>
                                    @if ($merge)
                                        <div
                                            style="font-size:10px; color:var(--red); font-weight:700; margin-top:2px; text-transform:uppercase; letter-spacing:0.02em;">
                                            <i class="fas fa-tag" style="margin-right:4px; font-size:9px;"></i>
                                            {{ $item['match_name'] }}
                                        </div>
                                    @endif
                                    <div style="font-size:11px; color:var(--smoke); margin-top:2px;">
                                        {{ $item['contingent']?->name ?? '-' }}</div>
                                </td>
                                @foreach ([1, 2, 3, 4, 5] as $j)
                                    @php
                                        $val = $rawVals[$j] ?? 0;
                                        $isMinMax = $s ? $j === $minKey || $j === $maxKey : false;
                                    @endphp
                                    <td>
                                        @if ($val > 0)
                                            <span
                                                class="score-val {{ $isMinMax ? 'out' : '' }}">{{ number_format($val, 1) }}</span>
                                        @else
                                            <span style="color:var(--paper2);">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td>{{ $nilaiAwal > 0 ? number_format($nilaiAwal, 1) : '-' }}</td>
                                <td>
                                    <div class="score-final">
                                        {{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 1) : '-' }}</div>
                                    @if ($denda > 0)
                                        <div style="font-size:9px; color:var(--red); font-weight:700; margin-top:2px;">
                                            -{{ $denda }} Denda</div>
                                    @endif
                                </td>
                                @if ($currentRound === 'Final')
                                    <td>{{ isset($item['penyisihan_score']) && $item['penyisihan_score'] ? number_format($item['penyisihan_score']->nilai_akhir, 1) : '-' }}
                                    </td>
                                    <td style="font-weight:700; color:#27ae60;">
                                        {{ $item['accumulated_score'] > 0 ? number_format($item['accumulated_score'], 1) : '-' }}
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" style="padding:40px; color:var(--smoke); text-align:center;">Tidak
                                    ada data peserta</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TIMER --}}
        @if ($matchNumber->active_registration_id)
            @php
                // Find the specific drawing that is active
                $activeDrawing = \App\Models\DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                    ->where('registration_id', $matchNumber->active_registration_id)
                    ->where('round', $currentRound)
                    ->first();

                $activeRegItem = $registrations->first(
                    fn($r) => $r['id'] == $matchNumber->active_registration_id &&
                        $r['match_number_id'] == ($activeDrawing->match_number_id ?? 0),
                );

                // Fallback if not found precisely
                if (!$activeRegItem) {
                    $activeRegItem = $registrations->firstWhere('id', $matchNumber->active_registration_id);
                }
            @endphp
            <div class="tm-card" style="border-color: var(--ink); box-shadow: 0 8px 24px rgba(44, 62, 80, 0.1);"
                wire:ignore x-data="{
                    time: 0,
                    running: false,
                    countdown: 0,
                    lastTickSecond: -1,
                    playedIntervals: new Set(),
                    interpolInterval: null,
                    syncInterval: null,
                    registrationId: {{ $matchNumber->active_registration_id }},
                    formatTime() {
                        let t = Math.max(0, this.time);
                        let m = Math.floor(t / 60000);
                        let s = Math.floor((t % 60000) / 1000);
                        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    },
                    formatCountdown() { return ''; },
                    async sync() {
                        let state = await $wire.getTimerState();
                        if (!state) return;
                
                        let oldCountdown = this.countdown;
                
                        if (state.status === 'running') {
                            let wasRunning = this.running;
                            this.running = true;
                            this.countdown = 0;
                            let now = Date.now();
                            let expected = (state.elapsed_ms || 0) + Math.max(0, now - state.started_at_ms);
                            if (!wasRunning || Math.abs(this.time - expected) > 1000) {
                                this.time = expected;
                            }
                        } else if (state.status === 'countdown') {
                            this.running = false;
                            let remaining = state.countdown_end_ms - Date.now();
                            this.countdown = remaining > 0 ? Math.ceil(remaining / 1000) : 0;
                            this.time = state.elapsed_ms || 0;
                            if (remaining <= 0) { $wire.startTimer(); }
                        } else {
                            this.running = false;
                            this.countdown = 0;
                            this.time = state.elapsed_ms || 0;
                        }
                
                        if (this.countdown > 0 && this.countdown !== oldCountdown) {
                            // Siap and countdown removed
                        }
                    },
                    init() {
                        this.sync();
                        this.interpolInterval = setInterval(() => {
                            if (this.running) {
                                this.time += 30;
                                let currentSecond = Math.floor(this.time / 1000);
                
                                if (currentSecond === 90 && !this.playedIntervals.has(90)) {
                                    window.playBuzzer ? window.playBuzzer('/music/freesound_community-buzzerwav-14908.mp3') : null;
                                    this.playedIntervals.add(90);
                                }
                                if (currentSecond === 120 && !this.playedIntervals.has(120)) {
                                    window.playBuzzer ? window.playBuzzer('/music/freesound_community-buzzerwav-14908.mp3') : null;
                                    this.playedIntervals.add(120);
                                }
                
                                if (currentSecond > this.lastTickSecond) {
                                    window.playTimerTick ? window.playTimerTick(1000, 0.05) : null;
                                    this.lastTickSecond = currentSecond;
                                }
                            } else {
                                this.lastTickSecond = Math.floor(this.time / 1000);
                            }
                        }, 30);
                        this.syncInterval = setInterval(() => { this.sync(); }, 1000);
                    },
                    start() {
                        if (!this.running && this.countdown === 0) {
                            window.playBuzzerDouble ? window.playBuzzerDouble('/music/freesound_community-buzzerwav-14908.mp3') : null;
                            $wire.startTimer();
                        }
                    },
                    pause() {
                        window.playBuzzerSingle ? window.playBuzzerSingle('/music/freesound_community-buzzerwav-14908.mp3') : null;
                        $wire.pauseTimer();
                    },
                    stop() { $wire.stopTimer(); },
                    finish() {
                        let capturedTime = this.time;
                        $wire.pauseTimer();
                        window.playBuzzerDouble ? window.playBuzzerDouble('/music/freesound_community-buzzerwav-14908.mp3') : null;
                        $wire.finishMatch(this.registrationId, capturedTime);
                    }
                }">
                <div class="tm-card-head"
                    style="background:var(--ink); color:#fff; display:flex; align-items:center; justify-content:space-between;">
                    <h3 style="color:#fff;"><i class="fas fa-stopwatch" style="color:#f1c40f; margin-right:8px;"></i>
                        Live Match Timer &bull; {{ $activeRegItem['athletes'][0]->name ?? 'Peserta' }}</h3>
                    <button
                        @click="window.playBuzzer ? window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3') : null"
                        class="btn-gen ghost"
                        style="padding:4px 8px; border-color:rgba(255,255,255,0.2); color:#fff; background:transparent;"
                        title="Test Suara">
                        <i class="fas fa-volume-up"></i>
                    </button>
                </div>
                <div class="tm-card-body" style="padding: 24px; text-align: center; background:#fdfbf7;">
                    <div
                        style="font-size:48px; font-weight:900; font-family:'DM Sans', monospace; color:var(--ink); letter-spacing:4px; margin-bottom:16px;">
                        <span x-show="countdown > 0" x-text="formatCountdown()" style="color:var(--red);"></span>
                        <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                    </div>
                    <div style="display:flex; gap:12px; justify-content:center;">
                        <button x-show="!running && countdown === 0" @click="start()" class="btn-gen primary"
                            style="padding:12px 24px; font-size:14px;"><i class="fas fa-play"
                                style="margin-right:6px;"></i> Mulai</button>
                        <button x-show="running" @click="pause()" class="btn-gen"
                            style="background:#f39c12; color:#fff; padding:12px 24px; font-size:14px;"><i
                                class="fas fa-pause" style="margin-right:6px;"></i> Jeda</button>
                        <button @click="stop()" class="btn-gen ghost" style="padding:12px; font-size:14px;"
                            title="Stop & Reset"><i class="fas fa-redo-alt"></i></button>
                        <button @click="finish()" class="btn-gen success"
                            style="padding:12px 24px; font-size:14px;"><i class="fas fa-flag-checkered"
                                style="margin-right:6px;"></i> Selesai (Simpan)</button>
                    </div>
                    <div
                        style="margin-top:12px; font-size:11px; color:var(--smoke); font-weight:700; text-transform:uppercase; letter-spacing:0.1em;">
                        Target Waktu: {{ $activeRegItem['is_group'] ?? false ? '1:30 - 2:00' : '1:00 - 1:30' }}
                    </div>
                </div>
            </div>
        @endif

        {{-- ACTION QUEUE --}}
        <div class="tm-card">
            <div class="tm-card-head">
                <h3>Antrian Panggilan</h3>
                <p>Klik Panggil untuk menampilkan di Monitor Court</p>
            </div>
            <div class="tm-card-body">
                <div class="queue-grid">
                    @foreach ($registrations as $no => $item)
                        @php $isActive = (isset($activeDrawingId) && $activeDrawingId == $item['drawing_id']); @endphp
                        <div class="queue-card {{ $isActive ? 'active' : '' }}">
                            <div class="queue-hdr">
                                <div class="queue-num">{{ $item['sequence_number'] ?? $no + 1 }}</div>
                                <div class="queue-info">
                                    <div class="queue-name">
                                        @foreach ($item['athletes'] as $athlete)
                                            {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                        @endforeach
                                    </div>
                                    <div class="queue-cont"><i class="fas fa-shield-alt"
                                            style="margin-right:4px;"></i>{{ $item['contingent']?->name }}</div>
                                    @if ($merge)
                                        <div
                                            style="font-size:9px; color:var(--red); font-weight:700; margin-top:2px; text-transform:uppercase;">
                                            <i class="fas fa-tag"></i> {{ $item['match_name'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="queue-actions">
                                @if ($isActive)
                                    <button wire:click="callParticipant({{ $item['drawing_id'] }})"
                                        class="btn-gen primary" style="flex:1;"><i class="fas fa-bullhorn"></i>
                                        Panggil Ulang</button>
                                    <button wire:click="dismissParticipant()" class="btn-gen danger"
                                        style="flex:1;"><i class="fas fa-times"></i> Tutup</button>
                                @else
                                    <button wire:click="callParticipant({{ $item['drawing_id'] }})"
                                        class="btn-gen primary" style="width:100%;"><i class="fas fa-bullhorn"></i>
                                        Panggil</button>
                                @endif
                            </div>
                            @if ($isActive)
                                <div style="margin-top:8px; display:flex; justify-content:center;">
                                    <button wire:click="finishMatch({{ $item['id'] }}, 0)" class="btn-gen success"
                                        style="width:100%;"><i class="fas fa-flag-checkered"></i> Selesai
                                        (Simpan)</button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RANKING LEADERBOARD --}}
        @php
            $ranked = $registrations
                ->filter(
                    fn($i) => $i['score']?->nilai_akhir > 0 ||
                        ($currentRound === 'Final' && $i['accumulated_score'] > 0),
                )
                ->sortByDesc(
                    fn($i) => $currentRound === 'Penyisihan' ? $i['score']->nilai_akhir : $i['accumulated_score'],
                )
                ->values();
        @endphp
        @if ($ranked->count() > 0)
            <div class="tm-card" style="margin-top: 20px;">
                <div class="tm-card-head" style="background:var(--ink); color:#fff; border-bottom:none;">
                    <h3 style="color:#fff;"><i class="fas fa-trophy" style="color:#f1c40f; margin-right:8px;"></i>
                        Leaderboard Peringkat</h3>
                </div>
                <div style="overflow-x:auto;">
                    <table class="score-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">Rank</th>
                                <th class="text-left">Peserta</th>
                                <th>Kontingen</th>
                                <th>{{ $currentRound === 'Final' ? 'Total Akumulasi' : 'Nilai Akhir' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranked as $rno => $item)
                                @php $rankNum = $rno + 1; @endphp
                                <tr>
                                    <td style="font-size:18px; font-weight:700;">
                                        @if ($rankNum == 1)
                                            🥇
                                        @elseif($rankNum == 2)
                                            🥈
                                        @elseif($rankNum == 3)
                                            🥉
                                        @else
                                            {{ $rankNum }}
                                        @endif
                                    </td>
                                    <td class="text-left"
                                        style="font-weight:700; color:var(--ink); text-transform:uppercase;">
                                        @foreach ($item['athletes'] as $athlete)
                                            {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                        @endforeach
                                        @if ($merge)
                                            <div
                                                style="font-size:9px; color:var(--red); font-weight:400; margin-top:2px;">
                                                <i class="fas fa-tag"></i> {{ $item['match_name'] }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item['contingent']?->name }}</td>
                                    <td style="font-size:16px; font-weight:700; color:var(--ink);">
                                        {{ number_format($currentRound === 'Final' ? $item['accumulated_score'] : optional($item['score'])->nilai_akhir ?? 0, 1) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @php
            $officials = $firstDrawing->metadata['officials'] ?? null;
        @endphp
        <div class="officials-grid">
            <div class="tm-card official-card">
                <div class="official-label">
                    <i class="fas fa-user-tie"></i> Koordinator Pertandingan
                </div>
                <div class="official-val">{{ $officials['koordinator_lapangan'] ?? '-' }}</div>
                <div class="official-sub">NIP. -</div>
            </div>
            <div class="tm-card official-card" style="border-left-color: var(--ink);">
                <div class="official-label">
                    <i class="fas fa-users"></i> Para Panitera
                </div>
                <div class="official-val">
                    @if (isset($officials['panitera']) && is_array($officials['panitera']))
                        <ul class="official-list">
                            @foreach ($officials['panitera'] as $p)
                                <li>{{ $p }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $officials['panitera'] ?? '-' }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('swal', function(event) {
                const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    timer: data.timer || 3000,
                    showConfirmButton: data.showConfirmButton || false,
                });
            });

            // 🎙️ PUBLIC ADDRESS SYSTEM (TTS)
            let isPlayingAnnouncer = false;
            let currentAudio = null;

            window.addEventListener('play-announcer', event => {
                console.log('Announcer event received:', event.detail);
                const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
                const text = formatAnnouncerText(data.text);
                console.log('Announcer text to speak:', text);

                stopAnnouncer(); // ⛔ pastikan tidak numpuk
                isPlayingAnnouncer = true;

                function playBeepAndSpeak() {
                    if (!isPlayingAnnouncer) return;

                    currentAudio = new Audio('/asset/music/nada-suara.mp3');
                    currentAudio.volume = 0.6;

                    let playPromise = currentAudio.play();

                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            currentAudio.onended = () => {
                                if (!isPlayingAnnouncer) return;
                                setTimeout(() => speak(text), 500);
                            };
                        }).catch(() => {
                            // Jika audio gagal/tidak ada, langsung bicara
                            speak(text);
                        });
                    } else {
                        // Fallback untuk browser lama
                        speak(text);
                    }
                }

                function speak(text) {
                    console.log('Attempting to speak:', text);
                    if (!isPlayingAnnouncer) {
                        console.warn('Announcer is not playing, skipping speak.');
                        return;
                    }

                    window.speechSynthesis.cancel();
                    const speech = new SpeechSynthesisUtterance(text);
                    speech.lang = 'id-ID';
                    speech.rate = 1.1;
                    speech.pitch = 1;
                    speech.volume = 1;

                    function setVoice() {
                        const voices = window.speechSynthesis.getVoices();
                        console.log('Voices available:', voices.length);
                        let voice = voices.find(v => v.name.includes('Google Bahasa Indonesia')) ||
                            voices.find(v => v.lang === 'id-ID') ||
                            voices[0];

                        if (voice) {
                            console.log('Selected voice:', voice.name);
                            speech.voice = voice;
                        }

                        speech.onstart = () => console.log('TTS started.');
                        speech.onend = () => {
                            console.log('TTS ended.');
                            stopAnnouncer();
                        };
                        speech.onerror = (e) => console.error('TTS error:', e);

                        window.speechSynthesis.speak(speech);
                    }

                    if (window.speechSynthesis.getVoices().length) {
                        setVoice();
                    } else {
                        console.log('Waiting for voices to be loaded...');
                        window.speechSynthesis.onvoiceschanged = setVoice;
                    }
                }

                playBeepAndSpeak();
            });


            // ✅ FUNCTION STOP
            window.stopAnnouncer = function() {
                isPlayingAnnouncer = false;
                window.speechSynthesis.cancel();

                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio.currentTime = 0;
                }
                console.log('Announcer stopped manually.');
            }

            // ✅ TIMER SOUND HELPERS
            window.playTimerTick = function(frequency = 1000, duration = 0.08) {
                // User requested to remove tick tick tick
                return;
            };

            window.playBuzzer = function(src) {
                try {
                    const audio = new Audio(src);
                    audio.play().catch(e => console.warn('Buzzer error:', e));
                } catch (e) {
                    console.warn('Audio error:', e);
                }
            };

            window.playBuzzerSingle = function(src) {
                window.playBuzzer(src);
            };

            window.playBuzzerDouble = function(src) {
                window.playBuzzer(src);
                setTimeout(() => window.playBuzzer(src), 800);
            };

            // Pancing AudioContext agar aktif saat ada klik pertama di halaman
            document.addEventListener('click', function() {
                if (window.sharedAudioCtx && window.sharedAudioCtx.state === 'suspended') {
                    window.sharedAudioCtx.resume();
                }
            }, {
                once: true
            });

            window.speakCountdown = function(text) {
                if (window.speechSynthesis.speaking) return;
                const speech = new SpeechSynthesisUtterance(text);
                speech.lang = 'id-ID';
                speech.rate = 1.8;
                speech.pitch = 1.2;
                window.speechSynthesis.speak(speech);
            };

            function formatAnnouncerText(text) {
                return text
                    .replace(/\./g, '. ')
                    .replace(/,/g, ', ')
                    .replace(/-/g, ' ')
                    .replace(/\s+/g, ' ')
                    .trim();
            }
        </script>
    @endpush
</div>
