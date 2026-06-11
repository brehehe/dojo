@push('styles')
    <style>
        .tm-page {
            padding: 24px;
            padding-bottom: 100px;
            background: var(--paper, #F7F4EF);
            min-height: 100vh;
            overflow-x: hidden;
            box-sizing: border-box;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            .tm-page {
                padding: 14px;
                padding-bottom: 100px;
            }
        }

        /* HEADER */
        .tm-hdr {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
        }

        .tm-hdr h2 {
            font-family: 'Cinzel', serif;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 8px;
            color: var(--ink, #2c3e50);
        }

        @media (max-width: 768px) {
            .tm-hdr h2 {
                font-size: 18px;
            }

            .tm-hdr>div:last-child {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .tm-hdr>div:last-child>* {
                flex: 1;
                min-width: 120px;
                justify-content: center;
            }
        }

        .tm-badge-title {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(192, 57, 43, .1);
            color: var(--red, #c0392b);
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }

        /* ELEGANT BRACKET */
        .bracket-wrapper {
            margin-bottom: 24px;
            background: #fff;
            border: 1px solid var(--paper2, #e0dcd3);
            border-radius: 16px;
            overflow: hidden;
        }

        .bracket-hdr {
            padding: 12px 18px;
            border-bottom: 1px solid var(--paper2, #e0dcd3);
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--ink, #2c3e50);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bracket-hdr.ub {
            background: rgba(41, 128, 185, .05);
        }

        .bracket-hdr.lb {
            background: rgba(211, 84, 0, .05);
        }

        .bracket-hdr.gf {
            background: rgba(243, 156, 18, .05);
        }

        .bracket-scroll {
            overflow-x: auto;
            padding: 20px;
            display: flex;
            gap: 32px;
            align-items: flex-start;
            scrollbar-width: thin;
            scrollbar-color: var(--paper2, #e0dcd3) transparent;
            -webkit-overflow-scrolling: touch;
        }

        .bracket-scroll::-webkit-scrollbar {
            height: 4px;
        }

        .bracket-scroll::-webkit-scrollbar-thumb {
            background: var(--paper2, #e0dcd3);
            border-radius: 4px;
        }

        .bracket-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .bracket-round-col {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 260px;
            flex-shrink: 0;
        }

        @media (max-width: 1200px) {
            .bracket-round-col {
                width: 230px;
            }

            .bracket-scroll {
                gap: 24px;
            }

            .tm-page {
                padding: 20px;
                padding-bottom: 100px;
            }
        }

        @media (max-width: 768px) {
            .bracket-round-col {
                width: 200px !important;
            }

            .bracket-scroll {
                gap: 20px;
                padding: 14px;
            }
        }

        .bracket-round-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--smoke, #7f8c8d);
            text-align: center;
            margin-bottom: 4px;
        }

        .b-match {
            background: #fff;
            border: 1px solid var(--paper2, #e0dcd3);
            border-radius: 12px;
            overflow: hidden;
            transition: all .2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .02);
            position: relative;
        }

        .b-match.active {
            border-color: var(--red);
            box-shadow: 0 4px 12px rgba(192, 57, 43, 0.15);
        }

        .b-slot {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            transition: background .15s;
        }

        .b-slot+.b-slot {
            border-top: 1px solid var(--paper2, #e0dcd3);
        }

        .b-slot.winner {
            background: rgba(39, 174, 96, .06);
        }

        .b-slot-color {
            width: 4px;
            height: 16px;
            border-radius: 4px;
            flex-shrink: 0;
        }

        /* OFFICIALS */
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

        @media (max-width: 1024px) {
            .b-slot-color {
                width: 4px;
                height: 16px;
                border-radius: 4px;
                flex-shrink: 0;
            }
        }

        .b-slot-color.red {
            background: var(--red, #c0392b);
        }

        .b-slot-color.blue {
            background: #2980b9;
        }

        .b-slot-info {
            flex: 1;
            min-width: 0;
        }

        .b-slot-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ink, #2c3e50);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .b-slot-cont {
            font-size: 10.5px;
            color: var(--smoke, #7f8c8d);
            font-weight: 600;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .b-slot-empty {
            font-size: 12px;
            color: var(--smoke, #7f8c8d);
            font-style: italic;
        }

        .b-win-icon {
            color: #27ae60;
            font-size: 11px;
            flex-shrink: 0;
        }

        .b-match-actions {
            display: flex;
            background: var(--paper);
            border-top: 1px solid var(--paper2);
        }

        .b-action-btn {
            flex: 1;
            padding: 8px;
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--ink);
            background: none;
            border: none;
            cursor: pointer;
            border-right: 1px solid var(--paper2);
        }

        .b-action-btn:last-child {
            border-right: none;
        }

        .b-action-btn:hover {
            background: #fff;
            color: var(--red);
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
            background: var(--ink, #2c3e50);
            color: #fff;
        }

        .btn-gen.primary:hover {
            background: #1a252f;
            transform: translateY(-1px);
        }

        .btn-gen.danger {
            background: var(--red, #c0392b);
            color: #fff;
        }

        .btn-gen.danger:hover {
            background: #a93226;
        }

        .btn-gen.ghost {
            background: #fff;
            color: var(--ink);
            border: 1px solid var(--paper2);
        }

        .btn-gen.ghost:hover {
            border-color: var(--ink);
        }

        /* CARDS */
        .tm-card {
            background: #fff;
            border: 1px solid var(--paper2);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 24px;
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

        .score-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .score-table th {
            padding: 12px;
            background: #fdfbf7;
            font-size: 10px;
            color: var(--smoke);
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid var(--paper2);
            text-align: center;
        }

        .score-table td {
            padding: 12px;
            border: 1px solid var(--paper2);
            vertical-align: middle;
            text-align: center;
        }

        .score-btn {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1px solid var(--paper2);
            background: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .score-btn:hover {
            background: var(--paper);
            border-color: var(--ink);
        }
    </style>
@endpush

<div class="tm-page">
    <div style="position: fixed; top: 30px; right: 30px; z-index: 90;">
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
            <div class="tm-badge-title">{{ strtoupper($merge->name ?? 'RANDORI') }}</div>
            <h2>{{ $matchNumber->name }}</h2>
            @if ($merge)
                <div
                    style="font-size: 11px; color: var(--smoke); font-weight: 600; margin-top: 4px; font-style: italic;">
                    {{ $displayName }}
                </div>
            @endif
            <p>Double Elimination — kalah 1x masih bisa juara via Loser Bracket</p>
        </div>
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <button wire:click="confirmChampion"
                wire:confirm="Sistem akan men-generate Juara 1 & 2 dari hasil Grand Final. Lanjutkan?"
                class="btn-gen primary" style="background:#27ae60; box-shadow:0 4px 12px rgba(39,174,96,0.2);">
                <i class="fas fa-medal"></i> Simpan Juara
            </button>
            <button wire:click="callOfficials" class="btn-gen primary"
                style="background:var(--red); box-shadow:0 4px 12px rgba(192,57,43,0.2);">
                <i class="fas fa-bullhorn"></i> Panggil Official
            </button>
            <button onclick="window.stopAnnouncer && window.stopAnnouncer()" class="btn-gen ghost"
                style="color:var(--red); border-color:var(--red);">
                <i class="fas fa-volume-xmark"></i> Stop Suara
            </button>
            <a href="{{ route('admin.new-scoring-index') }}" class="btn-gen ghost" style="text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @php
        $courtId = $this->getCourtId();
    @endphp
    @if($courtId)
        <div style="background:#fff; border:1px solid var(--paper2); border-radius:16px; padding:16px 20px; margin-bottom:20px; display:flex; flex-direction:column; gap:12px;">
            <div style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; letter-spacing:0.1em; display:flex; align-items:center; gap:6px;">
                <i class="fas fa-desktop" style="color:var(--red);"></i> Monitor Lapangan (Shortcut)
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:10px;">
                <a href="{{ route('admin.arbitrase.scoring.monitor', $courtId) }}" target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-bullhorn" style="margin-right:6px;"></i> Panggilan
                </a>
                <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.match', $matchNumber->id) }}" target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-tv" style="margin-right:6px;"></i> Hasil
                </a>
                <a href="{{ route('admin.arbitrase.scoring.monitor-timer.court', $courtId) }}" target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-stopwatch" style="margin-right:6px;"></i> Timer
                </a>
                <a href="{{ route('admin.arbitrase.scoring.monitor-rekapitulasi-hasil.court', $courtId) }}" target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-list-ol" style="margin-right:6px;"></i> Rekapitulasi
                </a>
                <a href="{{ route('admin.arbitrase.scoring.monitor-referee.court', $courtId) }}" target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-user-tie" style="margin-right:6px;"></i> Wasit
                </a>
            </div>
        </div>
    @endif

    @if (!empty($needsRepair))
        <div
            style="background:#fcf3cf; border:1px solid #f1c40f; border-radius:16px; padding:16px 20px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:16px;">
                <i class="fas fa-tools" style="font-size:24px; color:#d4ac0d;"></i>
                <div>
                    <div style="font-size:14px; font-weight:700; color:#b7950b; text-transform:uppercase;">Routing
                        Bracket Tidak Lengkap</div>
                    <div style="font-size:12px; color:#9c640c; margin-top:4px;">Beberapa match belum punya jalur winner
                        / loser. Klik perbaiki otomatis.</div>
                </div>
            </div>
            <button wire:click="repairBracket" class="btn-gen primary" style="background:#d4ac0d;"><i
                    class="fas fa-wrench"></i> Perbaiki Bracket</button>
        </div>
    @endif

    @php
        $ubRounds = $drawingData['upper_bracket']['rounds'] ?? [];
        $lbRounds = $drawingData['lower_bracket']['rounds'] ?? [];
        $grandFinal = $drawingData['grand_final'] ?? null;
        $juaraMap = $juara ?? [];
    @endphp

    {{-- ====== SCORING PANEL (INLINE) ====== --}}
    @if ($activeMatch)
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm overflow-hidden">

            {{-- Gradient Header --}}
            <div
                class="px-5 py-4 bg-gradient-to-r from-rose-600 to-rose-500 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-gavel text-white text-[15px]"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-black text-rose-200 uppercase tracking-[0.3em]">Panel Penilaian
                            Aktif</div>
                        <div class="text-lg font-black text-white uppercase tracking-tight leading-none">
                            Randori &bull;
                            @if ($activeMatch['bracket'] === 'gf')
                                Grand Final
                            @else
                                {{ strtoupper($activeMatch['bracket']) }} — Babak {{ $activeMatch['round'] + 1 }} /
                                Match {{ $activeMatch['match'] + 1 }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Timer (inline, wire:ignore) --}}
                <div wire:ignore x-data="{
                    time: 0,
                    running: false,
                    countdown: 0,
                    lastTickSecond: -1,
                    playedIntervals: new Set(),
                    interpolInterval: null,
                    syncInterval: null,
                    formatTime() {
                        let t = Math.max(0, this.time);
                        let m = Math.floor(t / 60000);
                        let s = Math.floor((t % 60000) / 1000);
                        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    },
                    formatCountdown() { return ''; },
                    async sync() {
                        let res = await fetch(`/api/court/{{ $courtId }}/timer-state`);
                        let state = await res.json();
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
                                let s = Math.floor(this.time / 1000);
                                if (s >= 120 && !this.playedIntervals.has(120)) {
                                    this.time = 120000;
                                    this.running = false;
                                    window.playBuzzer ? window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3') : null;
                                    this.playedIntervals.add(120);
                                    $wire.pauseTimer();
                                }
                                if (s > this.lastTickSecond) {
                                    window.playTimerTick ? window.playTimerTick(1000, 0.05) : null;
                                    this.lastTickSecond = s;
                                }
                            } else { this.lastTickSecond = Math.floor(this.time / 1000); }
                        }, 30);
                        this.syncInterval = setInterval(() => { this.sync(); }, 300);
                    },
                    start() {
                        if (!this.running && this.countdown === 0) {
                            this.running = true;
                            $wire.startTimer();
                        }
                    },
                    pause() {
                        this.running = false;
                        $wire.pauseTimer();
                    },
                    stop() {
                        this.time = 0;
                        this.running = false;
                        $wire.stopTimer();
                    },
                    finish() {
                        this.time = 0;
                        this.running = false;
                        $wire.stopTimer();
                        window.playBuzzerDouble ? window.playBuzzerDouble('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3') : null;
                        $wire.finishMatch();
                    }
                }"
                    class="flex items-center gap-3 bg-white/10 border border-white/20 px-4 py-2 rounded-xl">
                    <button
                        @click="window.playBuzzer ? window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3') : null"
                        class="w-6 h-6 flex items-center justify-center bg-white/20 hover:bg-white/30 text-white rounded-full transition-colors"
                        title="Test Suara">
                        <i class="fas fa-volume-up text-[10px]"></i>
                    </button>
                    <i class="fas fa-stopwatch text-amber-300"></i>
                    <div class="text-xl font-black font-mono tracking-wider min-w-[60px]"
                        :class="countdown > 0 ? 'text-orange-300' : 'text-white'">
                        <span x-show="countdown > 0" x-text="formatCountdown()"></span>
                        <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <button x-show="!running && countdown === 0" @click="start()"
                            class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors shadow-sm"
                            title="Start"><i class="fas fa-play text-xs"></i></button>
                        <button x-show="running" @click="pause()"
                            class="w-8 h-8 flex items-center justify-center bg-amber-400 hover:bg-amber-500 text-white rounded-lg transition-colors shadow-sm"
                            title="Pause"><i class="fas fa-pause text-xs"></i></button>
                        <button @click="stop()"
                            class="w-8 h-8 flex items-center justify-center bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors shadow-sm"
                            title="Reset"><i class="fas fa-stop text-xs"></i></button>
                        <button x-show="running || time > 0" @click="finish()"
                            class="h-8 px-3 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors shadow-sm ml-1"
                            title="Selesai">
                            <i class="fas fa-flag-checkered text-xs mr-1"></i><span
                                class="text-[11px] font-black uppercase tracking-wider">Selesai</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-5 md:p-6 space-y-5">

                {{-- Athlete Info Bar --}}
                @php
                    $matchData = $activeMatch['data'] ?? null;
                @endphp
                @if ($matchData && ($matchData['athlete1'] ?? null) && ($matchData['athlete2'] ?? null))
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl">
                            <div class="w-3 h-8 rounded-full bg-rose-500 shrink-0"></div>
                            <div>
                                <div class="text-[11px] font-black text-rose-400 uppercase tracking-widest">Pita Merah
                                    (AKA)</div>
                                <div class="text-[15px] font-black text-slate-800 uppercase leading-tight">
                                    {{ $matchData['athlete1']['name'] ?? '—' }}</div>
                                @if ($matchData['athlete1']['contingent'] ?? null)
                                    <div class="text-[13px] text-slate-500">{{ $matchData['athlete1']['contingent'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="w-3 h-8 rounded-full bg-slate-600 shrink-0"></div>
                            <div>
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pita Putih
                                    (SHIRO)</div>
                                <div class="text-[15px] font-black text-slate-800 uppercase leading-tight">
                                    {{ $matchData['athlete2']['name'] ?? '—' }}</div>
                                @if ($matchData['athlete2']['contingent'] ?? null)
                                    <div class="text-[13px] text-slate-500">{{ $matchData['athlete2']['contingent'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Scoring Table --}}
                <div class="overflow-hidden border border-slate-200 rounded-2xl shadow-sm">
                    <div class="grid grid-cols-2">
                        <div
                            class="bg-rose-600 text-white py-2 text-center text-[13px] font-black uppercase tracking-widest border-r border-white/20">
                            Pita Merah (AKA)</div>
                        <div
                            class="bg-slate-800 text-white py-2 text-center text-[13px] font-black uppercase tracking-widest">
                            Pita Putih (SHIRO)</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-[13px] border-collapse" style="min-width:780px;">
                            <thead class="bg-slate-700 text-white uppercase font-black text-[11px] tracking-widest">
                                <tr>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keputusan</th>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keterangan</th>
                                    <th class="px-3 py-2 border-r border-white/10">Poin</th>
                                    <th class="px-3 py-2 border-r border-white/10">Jml</th>
                                    <th class="px-3 py-2 border-r border-slate-500 w-24">Kontrol</th>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keputusan</th>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keterangan</th>
                                    <th class="px-3 py-2 border-r border-white/10">Poin</th>
                                    <th class="px-3 py-2 border-r border-white/10">Jml</th>
                                    <th class="px-3 py-2 w-24">Kontrol</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-bold text-slate-800 text-center">
                                @php
                                    $categories = [
                                        [
                                            'label' => 'PERINGATAN',
                                            'desc' => 'Mujoken Kachi',
                                            'val' => 15,
                                            'key' => 'mujoken_kachi',
                                        ],
                                        ['label' => '1.', 'desc' => 'Ippon', 'val' => 10, 'key' => 'ippon'],
                                        ['label' => '2.', 'desc' => 'Waza Ari', 'val' => 5, 'key' => 'waza_ari'],
                                        [
                                            'label' => '3.',
                                            'desc' => 'Hasil Batsu 5',
                                            'val' => 5,
                                            'key' => 'hasil_batsu_5',
                                        ],
                                        [
                                            'label' => '4.',
                                            'desc' => 'Hasil Batsu 10',
                                            'val' => 10,
                                            'key' => 'hasil_batsu_10',
                                        ],
                                        ['label' => '5.', 'desc' => 'Yusei Kachi', 'val' => 5, 'key' => 'yusei_kachi'],
                                    ];
                                @endphp
                                @foreach ($categories as $cat)
                                    @php $isBatsu = str_contains($cat['key'], 'batsu'); @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        {{-- AKA --}}
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left">
                                            <span
                                                class="text-[11px] font-black uppercase tracking-widest {{ $isBatsu ? 'text-rose-500' : 'text-slate-400' }}">{{ $cat['label'] }}</span>
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left text-slate-700">
                                            {{ $cat['desc'] }}</td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-slate-400">
                                            {{ $cat['val'] }}</td>
                                        <td
                                            class="px-3 py-2.5 border-r border-slate-100 font-black text-base {{ $isBatsu ? 'text-rose-600' : 'text-slate-800' }}">
                                            {{ $isBatsu ? '-' : '' }}{{ $cat['val'] * $scoringAka[$cat['key']] }}
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-slate-200">
                                            <div
                                                class="inline-flex rounded-lg overflow-hidden border border-slate-200">
                                                <button type="button"
                                                    wire:click="updateScore('aka', '{{ $cat['key'] }}', -1)"
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 transition-colors text-slate-500"><i
                                                        class="fas fa-minus text-[10px]"></i></button>
                                                <span
                                                    class="px-3 py-1.5 bg-white min-w-[32px] font-black text-slate-800 border-x border-slate-200">{{ $scoringAka[$cat['key']] }}</span>
                                                <button type="button"
                                                    wire:click="updateScore('aka', '{{ $cat['key'] }}', 1)"
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-600 transition-colors text-slate-500"><i
                                                        class="fas fa-plus text-[10px]"></i></button>
                                            </div>
                                        </td>
                                        {{-- SHIRO --}}
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left">
                                            <span
                                                class="text-[11px] font-black uppercase tracking-widest {{ $isBatsu ? 'text-rose-500' : 'text-slate-400' }}">{{ $cat['label'] }}</span>
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left text-slate-700">
                                            {{ $cat['desc'] }}</td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-slate-400">
                                            {{ $cat['val'] }}</td>
                                        <td
                                            class="px-3 py-2.5 border-r border-slate-100 font-black text-base {{ $isBatsu ? 'text-rose-600' : 'text-slate-800' }}">
                                            {{ $isBatsu ? '-' : '' }}{{ $cat['val'] * $scoringShiro[$cat['key']] }}
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <div
                                                class="inline-flex rounded-lg overflow-hidden border border-slate-200">
                                                <button type="button"
                                                    wire:click="updateScore('shiro', '{{ $cat['key'] }}', -1)"
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 transition-colors text-slate-500"><i
                                                        class="fas fa-minus text-[10px]"></i></button>
                                                <span
                                                    class="px-3 py-1.5 bg-white min-w-[32px] font-black text-slate-800 border-x border-slate-200">{{ $scoringShiro[$cat['key']] }}</span>
                                                <button type="button"
                                                    wire:click="updateScore('shiro', '{{ $cat['key'] }}', 1)"
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-600 transition-colors text-slate-500"><i
                                                        class="fas fa-plus text-[10px]"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 font-black text-[15px] uppercase">
                                    <td colspan="3" class="px-3 py-3 text-right text-slate-500 text-[13px]">Total
                                        Merah</td>
                                    <td class="px-3 py-3 border-r border-slate-200">
                                        <span class="text-xl font-black text-rose-600">{{ $scoreRed }}</span>
                                    </td>
                                    <td class="border-r border-slate-200"></td>
                                    <td colspan="3" class="px-3 py-3 text-right text-slate-500 text-[13px]">Total
                                        Putih</td>
                                    <td class="px-3 py-3 border-r border-slate-200">
                                        <span class="text-xl font-black text-blue-600">{{ $scoreBlue }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- TANDA TANGAN PENILAI & OFFICIALS --}}
                <div class="mt-6 border-t border-slate-200 pt-6 mb-6">
                    <div class="text-[13px] font-black text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-signature text-rose-500"></i> Pengesahan & Tanda Tangan Hasil Pertandingan
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- ROW 1 LEFT: ARBITRASE --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Arbitrase</label>
                            <input type="text" wire:model="sigArbitraseName" list="assigned-arbitrase-list" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Arbitrase...">
                            <datalist id="assigned-arbitrase-list">
                                @if ($assignedArbitrase)
                                    <option value="{{ $assignedArbitrase->referee?->user?->name }}">
                                @endif
                            </datalist>

                            <div x-data="{
                                signature: @entangle('sigArbitraseData'),
                                isDrawing: false,
                                ctx: null,
                                canvas: null,
                                init() {
                                    this.canvas = this.$refs.canvas;
                                    this.ctx = this.canvas.getContext('2d');
                                    const preventDefault = (e) => { if (e.target === this.canvas) e.preventDefault(); };
                                    this.canvas.addEventListener('touchstart', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchmove', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchend', preventDefault, { passive: false });
                                    this.resizeCanvas();
                                    if (this.signature) this.loadSignature(this.signature);
                                    window.addEventListener('resize', () => this.resizeCanvas());
                                    this.$watch('signature', (value) => {
                                        if (!value) this.clearCanvas();
                                        else if (value !== this.canvas.toDataURL()) this.loadSignature(value);
                                    });
                                },
                                resizeCanvas() {
                                    if (!this.canvas || this.canvas.offsetWidth === 0) return;
                                    const temp = this.canvas.toDataURL();
                                    const rect = this.canvas.getBoundingClientRect();
                                    this.canvas.width = rect.width * (window.devicePixelRatio || 1);
                                    this.canvas.height = rect.height * (window.devicePixelRatio || 1);
                                    this.ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                                    this.ctx.strokeStyle = '#000000';
                                    this.ctx.lineWidth = 2.5;
                                    this.ctx.lineCap = 'round';
                                    this.ctx.lineJoin = 'round';
                                    if (temp && temp !== 'data:,') {
                                        const img = new Image();
                                        img.onload = () => { this.ctx.drawImage(img, 0, 0, rect.width, rect.height); };
                                        img.src = temp;
                                    }
                                },
                                getMousePos(e) {
                                    const rect = this.canvas.getBoundingClientRect();
                                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                    return { x: clientX - rect.left, y: clientY - rect.top };
                                },
                                startDrawing(e) {
                                    this.isDrawing = true;
                                    const pos = this.getMousePos(e);
                                    this.ctx.beginPath();
                                    this.ctx.moveTo(pos.x, pos.y);
                                },
                                draw(e) {
                                    if (!this.isDrawing) return;
                                    const pos = this.getMousePos(e);
                                    this.ctx.lineTo(pos.x, pos.y);
                                    this.ctx.stroke();
                                },
                                stopDrawing() {
                                    if (!this.isDrawing) return;
                                    this.isDrawing = false;
                                    this.save();
                                },
                                clearCanvas() {
                                    if (!this.canvas) return;
                                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                    this.signature = null;
                                },
                                save() {
                                    const dataUrl = this.canvas.toDataURL('image/png');
                                    const blank = document.createElement('canvas');
                                    blank.width = this.canvas.width;
                                    blank.height = this.canvas.height;
                                    if (dataUrl === blank.toDataURL('image/png')) {
                                        this.signature = null;
                                    } else {
                                        this.signature = dataUrl;
                                    }
                                },
                                loadSignature(dataUrl) {
                                    const img = new Image();
                                    img.onload = () => {
                                        const rect = this.canvas.getBoundingClientRect();
                                        this.ctx.clearRect(0, 0, rect.width, rect.height);
                                        this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                                    };
                                    img.src = dataUrl;
                                }
                            }" class="flex flex-col gap-2">
                                <div class="flex justify-between items-center bg-slate-100 px-3 py-1.5 rounded-t-lg border border-slate-200 border-b-0">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Tanda Tangan Arbitrase</span>
                                    <button type="button" @click="clearCanvas()" class="text-[10px] font-black text-red-500 hover:text-red-700 uppercase flex items-center gap-1">
                                        <i class="fas fa-eraser"></i> Hapus
                                    </button>
                                </div>
                                <div class="border border-dashed border-slate-300 rounded-b-lg bg-white relative overflow-hidden" style="height: 240px;" wire:ignore>
                                    <canvas x-ref="canvas" 
                                            class="absolute inset-0 w-full h-full cursor-crosshair"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart="startDrawing($event)"
                                            @touchmove="draw($event)"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 1 RIGHT: KOORDINATOR --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Koordinator</label>
                            <input type="text" wire:model="sigKoordinatorName" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Koordinator...">

                            <div x-data="{
                                signature: @entangle('sigKoordinatorData'),
                                isDrawing: false,
                                ctx: null,
                                canvas: null,
                                init() {
                                    this.canvas = this.$refs.canvas;
                                    this.ctx = this.canvas.getContext('2d');
                                    const preventDefault = (e) => { if (e.target === this.canvas) e.preventDefault(); };
                                    this.canvas.addEventListener('touchstart', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchmove', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchend', preventDefault, { passive: false });
                                    this.resizeCanvas();
                                    if (this.signature) this.loadSignature(this.signature);
                                    window.addEventListener('resize', () => this.resizeCanvas());
                                    this.$watch('signature', (value) => {
                                        if (!value) this.clearCanvas();
                                        else if (value !== this.canvas.toDataURL()) this.loadSignature(value);
                                    });
                                },
                                resizeCanvas() {
                                    if (!this.canvas || this.canvas.offsetWidth === 0) return;
                                    const temp = this.canvas.toDataURL();
                                    const rect = this.canvas.getBoundingClientRect();
                                    this.canvas.width = rect.width * (window.devicePixelRatio || 1);
                                    this.canvas.height = rect.height * (window.devicePixelRatio || 1);
                                    this.ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                                    this.ctx.strokeStyle = '#000000';
                                    this.ctx.lineWidth = 2.5;
                                    this.ctx.lineCap = 'round';
                                    this.ctx.lineJoin = 'round';
                                    if (temp && temp !== 'data:,') {
                                        const img = new Image();
                                        img.onload = () => { this.ctx.drawImage(img, 0, 0, rect.width, rect.height); };
                                        img.src = temp;
                                    }
                                },
                                getMousePos(e) {
                                    const rect = this.canvas.getBoundingClientRect();
                                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                    return { x: clientX - rect.left, y: clientY - rect.top };
                                },
                                startDrawing(e) {
                                    this.isDrawing = true;
                                    const pos = this.getMousePos(e);
                                    this.ctx.beginPath();
                                    this.ctx.moveTo(pos.x, pos.y);
                                },
                                draw(e) {
                                    if (!this.isDrawing) return;
                                    const pos = this.getMousePos(e);
                                    this.ctx.lineTo(pos.x, pos.y);
                                    this.ctx.stroke();
                                },
                                stopDrawing() {
                                    if (!this.isDrawing) return;
                                    this.isDrawing = false;
                                    this.save();
                                },
                                clearCanvas() {
                                    if (!this.canvas) return;
                                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                    this.signature = null;
                                },
                                save() {
                                    const dataUrl = this.canvas.toDataURL('image/png');
                                    const blank = document.createElement('canvas');
                                    blank.width = this.canvas.width;
                                    blank.height = this.canvas.height;
                                    if (dataUrl === blank.toDataURL('image/png')) {
                                        this.signature = null;
                                    } else {
                                        this.signature = dataUrl;
                                    }
                                },
                                loadSignature(dataUrl) {
                                    const img = new Image();
                                    img.onload = () => {
                                        const rect = this.canvas.getBoundingClientRect();
                                        this.ctx.clearRect(0, 0, rect.width, rect.height);
                                        this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                                    };
                                    img.src = dataUrl;
                                }
                            }" class="flex flex-col gap-2">
                                <div class="flex justify-between items-center bg-slate-100 px-3 py-1.5 rounded-t-lg border border-slate-200 border-b-0">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Tanda Tangan Koordinator</span>
                                    <button type="button" @click="clearCanvas()" class="text-[10px] font-black text-red-500 hover:text-red-700 uppercase flex items-center gap-1">
                                        <i class="fas fa-eraser"></i> Hapus
                                    </button>
                                </div>
                                <div class="border border-dashed border-slate-300 rounded-b-lg bg-white relative overflow-hidden" style="height: 240px;" wire:ignore>
                                    <canvas x-ref="canvas" 
                                            class="absolute inset-0 w-full h-full cursor-crosshair"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart="startDrawing($event)"
                                            @touchmove="draw($event)"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 2 LEFT: WASIT --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Wasit</label>
                            <input type="text" wire:model="sigWasitName" list="assigned-wasit-list" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Wasit...">
                            <datalist id="assigned-wasit-list">
                                @foreach ($assignedReferees as $sr)
                                    <option value="{{ $sr->referee?->user?->name }}">
                                @endforeach
                            </datalist>

                            <div x-data="{
                                signature: @entangle('sigWasitData'),
                                isDrawing: false,
                                ctx: null,
                                canvas: null,
                                init() {
                                    this.canvas = this.$refs.canvas;
                                    this.ctx = this.canvas.getContext('2d');
                                    const preventDefault = (e) => { if (e.target === this.canvas) e.preventDefault(); };
                                    this.canvas.addEventListener('touchstart', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchmove', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchend', preventDefault, { passive: false });
                                    this.resizeCanvas();
                                    if (this.signature) this.loadSignature(this.signature);
                                    window.addEventListener('resize', () => this.resizeCanvas());
                                    this.$watch('signature', (value) => {
                                        if (!value) this.clearCanvas();
                                        else if (value !== this.canvas.toDataURL()) this.loadSignature(value);
                                    });
                                },
                                resizeCanvas() {
                                    if (!this.canvas || this.canvas.offsetWidth === 0) return;
                                    const temp = this.canvas.toDataURL();
                                    const rect = this.canvas.getBoundingClientRect();
                                    this.canvas.width = rect.width * (window.devicePixelRatio || 1);
                                    this.canvas.height = rect.height * (window.devicePixelRatio || 1);
                                    this.ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                                    this.ctx.strokeStyle = '#000000';
                                    this.ctx.lineWidth = 2.5;
                                    this.ctx.lineCap = 'round';
                                    this.ctx.lineJoin = 'round';
                                    if (temp && temp !== 'data:,') {
                                        const img = new Image();
                                        img.onload = () => { this.ctx.drawImage(img, 0, 0, rect.width, rect.height); };
                                        img.src = temp;
                                    }
                                },
                                getMousePos(e) {
                                    const rect = this.canvas.getBoundingClientRect();
                                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                    return { x: clientX - rect.left, y: clientY - rect.top };
                                },
                                startDrawing(e) {
                                    this.isDrawing = true;
                                    const pos = this.getMousePos(e);
                                    this.ctx.beginPath();
                                    this.ctx.moveTo(pos.x, pos.y);
                                },
                                draw(e) {
                                    if (!this.isDrawing) return;
                                    const pos = this.getMousePos(e);
                                    this.ctx.lineTo(pos.x, pos.y);
                                    this.ctx.stroke();
                                },
                                stopDrawing() {
                                    if (!this.isDrawing) return;
                                    this.isDrawing = false;
                                    this.save();
                                },
                                clearCanvas() {
                                    if (!this.canvas) return;
                                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                    this.signature = null;
                                },
                                save() {
                                    const dataUrl = this.canvas.toDataURL('image/png');
                                    const blank = document.createElement('canvas');
                                    blank.width = this.canvas.width;
                                    blank.height = this.canvas.height;
                                    if (dataUrl === blank.toDataURL('image/png')) {
                                        this.signature = null;
                                    } else {
                                        this.signature = dataUrl;
                                    }
                                },
                                loadSignature(dataUrl) {
                                    const img = new Image();
                                    img.onload = () => {
                                        const rect = this.canvas.getBoundingClientRect();
                                        this.ctx.clearRect(0, 0, rect.width, rect.height);
                                        this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                                    };
                                    img.src = dataUrl;
                                }
                            }" class="flex flex-col gap-2">
                                <div class="flex justify-between items-center bg-slate-100 px-3 py-1.5 rounded-t-lg border border-slate-200 border-b-0">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Tanda Tangan Wasit</span>
                                    <button type="button" @click="clearCanvas()" class="text-[10px] font-black text-red-500 hover:text-red-700 uppercase flex items-center gap-1">
                                        <i class="fas fa-eraser"></i> Hapus
                                    </button>
                                </div>
                                <div class="border border-dashed border-slate-300 rounded-b-lg bg-white relative overflow-hidden" style="height: 240px;" wire:ignore>
                                    <canvas x-ref="canvas" 
                                            class="absolute inset-0 w-full h-full cursor-crosshair"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart="startDrawing($event)"
                                            @touchmove="draw($event)"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 2 RIGHT: PANITERA (CAN BE MULTIPLE) --}}
                        <div x-data="{ paniteras: @entangle('sigPanitera') }" class="flex flex-col gap-3">
                            <div class="flex justify-between items-center">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Panitera</label>
                                <button type="button" wire:click="addPanitera" class="px-2.5 py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1 transition-colors">
                                    <i class="fas fa-plus"></i> Tambah Panitera
                                </button>
                            </div>
                            @foreach ($sigPanitera as $idx => $p)
                                <div wire:key="panitera-slot-{{ $idx }}-{{ $p['id'] ?? $idx }}" class="flex flex-col gap-2 p-3 bg-slate-100/50 rounded-xl border border-slate-200">
                                    <div class="flex justify-between items-center">
                                        <div class="text-[10px] font-black text-slate-600 uppercase tracking-wider">Panitera #{{ $idx + 1 }}</div>
                                        @if (count($sigPanitera) > 1)
                                            <button type="button" wire:click="removePanitera({{ $idx }})" class="text-[9px] font-black text-red-500 hover:text-red-700 uppercase flex items-center gap-1 transition-colors">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                    <input type="text" wire:model="sigPanitera.{{ $idx }}.name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Panitera...">

                                    <div x-data="{
                                        isDrawing: false,
                                        ctx: null,
                                        canvas: null,
                                        init() {
                                            this.canvas = this.$refs.canvas;
                                            this.ctx = this.canvas.getContext('2d');
                                            const preventDefault = (e) => { if (e.target === this.canvas) e.preventDefault(); };
                                            this.canvas.addEventListener('touchstart', preventDefault, { passive: false });
                                            this.canvas.addEventListener('touchmove', preventDefault, { passive: false });
                                            this.canvas.addEventListener('touchend', preventDefault, { passive: false });
                                            this.resizeCanvas();
                                            if (this.paniteras[{{ $idx }}] && this.paniteras[{{ $idx }}].signature) {
                                                this.loadSignature(this.paniteras[{{ $idx }}].signature);
                                            }
                                            window.addEventListener('resize', () => this.resizeCanvas());
                                            this.$watch('paniteras', (newVal) => {
                                                if (newVal && newVal[{{ $idx }}]) {
                                                    const sig = newVal[{{ $idx }}].signature;
                                                    if (!sig) {
                                                        this.clearCanvasOnly();
                                                    } else if (sig !== this.canvas.toDataURL()) {
                                                        this.loadSignature(sig);
                                                    }
                                                }
                                            }, { deep: true });
                                        },
                                        resizeCanvas() {
                                            if (!this.canvas || this.canvas.offsetWidth === 0) return;
                                            const temp = this.canvas.toDataURL();
                                            const rect = this.canvas.getBoundingClientRect();
                                            this.canvas.width = rect.width * (window.devicePixelRatio || 1);
                                            this.canvas.height = rect.height * (window.devicePixelRatio || 1);
                                            this.ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                                            this.ctx.strokeStyle = '#000000';
                                            this.ctx.lineWidth = 2.5;
                                            this.ctx.lineCap = 'round';
                                            this.ctx.lineJoin = 'round';
                                            if (temp && temp !== 'data:,') {
                                                const img = new Image();
                                                img.onload = () => { this.ctx.drawImage(img, 0, 0, rect.width, rect.height); };
                                                img.src = temp;
                                            }
                                        },
                                        getMousePos(e) {
                                            const rect = this.canvas.getBoundingClientRect();
                                            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                            return { x: clientX - rect.left, y: clientY - rect.top };
                                        },
                                        startDrawing(e) {
                                            this.isDrawing = true;
                                            const pos = this.getMousePos(e);
                                            this.ctx.beginPath();
                                            this.ctx.moveTo(pos.x, pos.y);
                                        },
                                        draw(e) {
                                            if (!this.isDrawing) return;
                                            const pos = this.getMousePos(e);
                                            this.ctx.lineTo(pos.x, pos.y);
                                            this.ctx.stroke();
                                        },
                                        stopDrawing() {
                                            if (!this.isDrawing) return;
                                            this.isDrawing = false;
                                            this.save();
                                        },
                                        clearCanvasOnly() {
                                            if (!this.canvas) return;
                                            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                        },
                                        clearCanvas() {
                                            this.clearCanvasOnly();
                                            if (this.paniteras[{{ $idx }}]) {
                                                this.paniteras[{{ $idx }}].signature = null;
                                            }
                                        },
                                        save() {
                                            const dataUrl = this.canvas.toDataURL('image/png');
                                            const blank = document.createElement('canvas');
                                            blank.width = this.canvas.width;
                                            blank.height = this.canvas.height;
                                            if (this.paniteras[{{ $idx }}]) {
                                                if (dataUrl === blank.toDataURL('image/png')) {
                                                    this.paniteras[{{ $idx }}].signature = null;
                                                } else {
                                                    this.paniteras[{{ $idx }}].signature = dataUrl;
                                                }
                                            }
                                        },
                                        loadSignature(dataUrl) {
                                            const img = new Image();
                                            img.onload = () => {
                                                const rect = this.canvas.getBoundingClientRect();
                                                this.ctx.clearRect(0, 0, rect.width, rect.height);
                                                this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                                            };
                                            img.src = dataUrl;
                                        }
                                    }" class="flex flex-col gap-2">
                                        <div class="flex justify-between items-center bg-white px-3 py-1 border border-slate-200 border-b-0 rounded-t-lg">
                                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-wider">Tanda Tangan Panitera</span>
                                            <button type="button" @click="clearCanvas()" class="text-[9px] font-black text-red-500 hover:text-red-700 uppercase flex items-center gap-1">
                                                <i class="fas fa-eraser"></i> Hapus
                                            </button>
                                        </div>
                                        <div class="border border-dashed border-slate-300 rounded-b-lg bg-white relative overflow-hidden" style="height: 190px;" wire:ignore>
                                            <canvas x-ref="canvas" 
                                                    class="absolute inset-0 w-full h-full cursor-crosshair"
                                                    @mousedown="startDrawing($event)"
                                                    @mousemove="draw($event)"
                                                    @mouseup="stopDrawing()"
                                                    @mouseleave="stopDrawing()"
                                                    @touchstart="startDrawing($event)"
                                                    @touchmove="draw($event)"
                                                    @touchend="stopDrawing()">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- ROW 3 LEFT: MANAJER PITA MERAH --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-rose-500 uppercase tracking-widest">Manajer Pita Merah (AKA)</label>
                            <div class="text-[11px] font-bold text-slate-600 mb-1">
                                Atlet: {{ $activeMatch['data']['athlete1']['name'] ?? '—' }} ({{ $activeMatch['data']['athlete1']['contingent'] ?? '—' }})
                            </div>
                            <input type="text" wire:model="sigManagerRedName" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Manajer Pita Merah...">

                            <div x-data="{
                                signature: @entangle('sigManagerRedData'),
                                isDrawing: false,
                                ctx: null,
                                canvas: null,
                                init() {
                                    this.canvas = this.$refs.canvas;
                                    this.ctx = this.canvas.getContext('2d');
                                    const preventDefault = (e) => { if (e.target === this.canvas) e.preventDefault(); };
                                    this.canvas.addEventListener('touchstart', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchmove', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchend', preventDefault, { passive: false });
                                    this.resizeCanvas();
                                    if (this.signature) this.loadSignature(this.signature);
                                    window.addEventListener('resize', () => this.resizeCanvas());
                                    this.$watch('signature', (value) => {
                                        if (!value) this.clearCanvas();
                                        else if (value !== this.canvas.toDataURL()) this.loadSignature(value);
                                    });
                                },
                                resizeCanvas() {
                                    if (!this.canvas || this.canvas.offsetWidth === 0) return;
                                    const temp = this.canvas.toDataURL();
                                    const rect = this.canvas.getBoundingClientRect();
                                    this.canvas.width = rect.width * (window.devicePixelRatio || 1);
                                    this.canvas.height = rect.height * (window.devicePixelRatio || 1);
                                    this.ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                                    this.ctx.strokeStyle = '#000000';
                                    this.ctx.lineWidth = 2.5;
                                    this.ctx.lineCap = 'round';
                                    this.ctx.lineJoin = 'round';
                                    if (temp && temp !== 'data:,') {
                                        const img = new Image();
                                        img.onload = () => { this.ctx.drawImage(img, 0, 0, rect.width, rect.height); };
                                        img.src = temp;
                                    }
                                },
                                getMousePos(e) {
                                    const rect = this.canvas.getBoundingClientRect();
                                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                    return { x: clientX - rect.left, y: clientY - rect.top };
                                },
                                startDrawing(e) {
                                    this.isDrawing = true;
                                    const pos = this.getMousePos(e);
                                    this.ctx.beginPath();
                                    this.ctx.moveTo(pos.x, pos.y);
                                },
                                draw(e) {
                                    if (!this.isDrawing) return;
                                    const pos = this.getMousePos(e);
                                    this.ctx.lineTo(pos.x, pos.y);
                                    this.ctx.stroke();
                                },
                                stopDrawing() {
                                    if (!this.isDrawing) return;
                                    this.isDrawing = false;
                                    this.save();
                                },
                                clearCanvas() {
                                    if (!this.canvas) return;
                                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                    this.signature = null;
                                },
                                save() {
                                    const dataUrl = this.canvas.toDataURL('image/png');
                                    const blank = document.createElement('canvas');
                                    blank.width = this.canvas.width;
                                    blank.height = this.canvas.height;
                                    if (dataUrl === blank.toDataURL('image/png')) {
                                        this.signature = null;
                                    } else {
                                        this.signature = dataUrl;
                                    }
                                },
                                loadSignature(dataUrl) {
                                    const img = new Image();
                                    img.onload = () => {
                                        const rect = this.canvas.getBoundingClientRect();
                                        this.ctx.clearRect(0, 0, rect.width, rect.height);
                                        this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                                    };
                                    img.src = dataUrl;
                                }
                            }" class="flex flex-col gap-2">
                                <div class="flex justify-between items-center bg-rose-50 px-3 py-1.5 rounded-t-lg border border-rose-200 border-b-0">
                                    <span class="text-[10px] font-black text-rose-600 uppercase tracking-wider">Tanda Tangan Manajer Merah</span>
                                    <button type="button" @click="clearCanvas()" class="text-[10px] font-black text-rose-600 hover:text-rose-800 uppercase flex items-center gap-1">
                                        <i class="fas fa-eraser"></i> Hapus
                                    </button>
                                </div>
                                <div class="border border-dashed border-rose-200 rounded-b-lg bg-white relative overflow-hidden" style="height: 240px;" wire:ignore>
                                    <canvas x-ref="canvas" 
                                            class="absolute inset-0 w-full h-full cursor-crosshair"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart="startDrawing($event)"
                                            @touchmove="draw($event)"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 3 RIGHT: MANAJER PITA PUTIH --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-blue-500 uppercase tracking-widest">Manajer Pita Putih (SHIRO)</label>
                            <div class="text-[11px] font-bold text-slate-600 mb-1">
                                Atlet: {{ $activeMatch['data']['athlete2']['name'] ?? '—' }} ({{ $activeMatch['data']['athlete2']['contingent'] ?? '—' }})
                            </div>
                            <input type="text" wire:model="sigManagerWhiteName" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Manajer Pita Putih...">

                            <div x-data="{
                                signature: @entangle('sigManagerWhiteData'),
                                isDrawing: false,
                                ctx: null,
                                canvas: null,
                                init() {
                                    this.canvas = this.$refs.canvas;
                                    this.ctx = this.canvas.getContext('2d');
                                    const preventDefault = (e) => { if (e.target === this.canvas) e.preventDefault(); };
                                    this.canvas.addEventListener('touchstart', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchmove', preventDefault, { passive: false });
                                    this.canvas.addEventListener('touchend', preventDefault, { passive: false });
                                    this.resizeCanvas();
                                    if (this.signature) this.loadSignature(this.signature);
                                    window.addEventListener('resize', () => this.resizeCanvas());
                                    this.$watch('signature', (value) => {
                                        if (!value) this.clearCanvas();
                                        else if (value !== this.canvas.toDataURL()) this.loadSignature(value);
                                    });
                                },
                                resizeCanvas() {
                                    if (!this.canvas || this.canvas.offsetWidth === 0) return;
                                    const temp = this.canvas.toDataURL();
                                    const rect = this.canvas.getBoundingClientRect();
                                    this.canvas.width = rect.width * (window.devicePixelRatio || 1);
                                    this.canvas.height = rect.height * (window.devicePixelRatio || 1);
                                    this.ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                                    this.ctx.strokeStyle = '#000000';
                                    this.ctx.lineWidth = 2.5;
                                    this.ctx.lineCap = 'round';
                                    this.ctx.lineJoin = 'round';
                                    if (temp && temp !== 'data:,') {
                                        const img = new Image();
                                        img.onload = () => { this.ctx.drawImage(img, 0, 0, rect.width, rect.height); };
                                        img.src = temp;
                                    }
                                },
                                getMousePos(e) {
                                    const rect = this.canvas.getBoundingClientRect();
                                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                    return { x: clientX - rect.left, y: clientY - rect.top };
                                },
                                startDrawing(e) {
                                    this.isDrawing = true;
                                    const pos = this.getMousePos(e);
                                    this.ctx.beginPath();
                                    this.ctx.moveTo(pos.x, pos.y);
                                },
                                draw(e) {
                                    if (!this.isDrawing) return;
                                    const pos = this.getMousePos(e);
                                    this.ctx.lineTo(pos.x, pos.y);
                                    this.ctx.stroke();
                                },
                                stopDrawing() {
                                    if (!this.isDrawing) return;
                                    this.isDrawing = false;
                                    this.save();
                                },
                                clearCanvas() {
                                    if (!this.canvas) return;
                                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                    this.signature = null;
                                },
                                save() {
                                    const dataUrl = this.canvas.toDataURL('image/png');
                                    const blank = document.createElement('canvas');
                                    blank.width = this.canvas.width;
                                    blank.height = this.canvas.height;
                                    if (dataUrl === blank.toDataURL('image/png')) {
                                        this.signature = null;
                                    } else {
                                        this.signature = dataUrl;
                                    }
                                },
                                loadSignature(dataUrl) {
                                    const img = new Image();
                                    img.onload = () => {
                                        const rect = this.canvas.getBoundingClientRect();
                                        this.ctx.clearRect(0, 0, rect.width, rect.height);
                                        this.ctx.drawImage(img, 0, 0, rect.width, rect.height);
                                    };
                                    img.src = dataUrl;
                                }
                            }" class="flex flex-col gap-2">
                                <div class="flex justify-between items-center bg-blue-50 px-3 py-1.5 rounded-t-lg border border-blue-200 border-b-0">
                                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-wider">Tanda Tangan Manajer Putih</span>
                                    <button type="button" @click="clearCanvas()" class="text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase flex items-center gap-1">
                                        <i class="fas fa-eraser"></i> Hapus
                                    </button>
                                </div>
                                <div class="border border-dashed border-blue-200 rounded-b-lg bg-white relative overflow-hidden" style="height: 240px;" wire:ignore>
                                    <canvas x-ref="canvas" 
                                            class="absolute inset-0 w-full h-full cursor-crosshair"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart="startDrawing($event)"
                                            @touchmove="draw($event)"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="submitScoring"
                        class="flex-1 py-3.5 bg-teal-500 hover:bg-teal-600 text-white font-black text-[15px] uppercase tracking-widest rounded-xl shadow-lg shadow-teal-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="fas fa-check-double text-lg"></i> SAH — Simpan Penilaian
                    </button>
                    <button wire:click="resetDetailedScoring" wire:confirm="Reset semua nilai di panel ini ke nol?"
                        class="sm:w-auto px-5 py-3.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 font-black text-[15px] uppercase tracking-widest transition-all active:scale-95 flex items-center justify-center gap-2 border border-rose-200">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>

            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-slate-700 to-slate-600 flex items-center gap-2">
                <i class="fas fa-stopwatch text-amber-400"></i>
                <span class="text-[15px] font-black text-white uppercase tracking-widest">Panel Penilaian</span>
            </div>
            <div class="py-16 flex flex-col items-center justify-center text-center px-6">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-play-circle text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Belum ada pertandingan yang
                    dipanggil</h3>
                <p class="text-[15px] text-slate-500 mt-2 max-w-md">Klik tombol <span
                        class="font-black text-slate-700">Panggil</span> pada salah satu match di bagan bawah untuk
                    memulai penilaian dan mengaktifkan timer.</p>
            </div>
        </div>
    @endif

    @if (empty($ubRounds))
        <div
            style="text-align:center; padding:60px 20px; background:#fff; border:2px dashed var(--paper2); border-radius:24px;">
            <i class="fas fa-sitemap" style="font-size:40px; color:var(--paper2); margin-bottom:16px;"></i>
            <h3 style="font-family:'Cinzel', serif; font-size:16px; margin:0 0 8px; color:var(--ink);">Bagan Belum
                Dibuat</h3>
            <p style="font-size:13px; color:var(--smoke); margin:0;">Silakan generate drawing di Technical Meeting.</p>
        </div>
    @else
        {{-- UPPER BRACKET --}}
        <div class="bracket-wrapper">
            <div class="bracket-hdr ub">
                <i class="fas fa-arrow-up" style="color:#2980b9;"></i> UPPER BRACKET — WINNER PATH
                <span style="margin-left:auto; font-size:10px; opacity:0.6;"><i class="fas fa-arrows-left-right"></i>
                    geser</span>
            </div>
            <div class="bracket-scroll">
                @foreach ($ubRounds as $roundIdx => $matches)
                    @php
                        $totalUB = count($ubRounds);
                        if ($roundIdx === $totalUB - 1) {
                            $roundLabel = 'UB FINAL';
                        } elseif ($roundIdx === $totalUB - 2 && $totalUB > 2) {
                            $roundLabel = 'UB SEMI FINAL';
                        } else {
                            $roundLabel = 'UB R' . ($roundIdx + 1);
                        }
                    @endphp
                    <div class="bracket-round-col">
                        <div class="bracket-round-title">{{ $roundLabel }}</div>
                        @foreach ($matches as $matchIdx => $match)
                            @php
                                $nodeKey = 'ub_' . $roundIdx . '_' . $matchIdx;
                                $isActive = $matchNumber->active_bracket_node === $nodeKey;
                                $isDone = ($match['winner'] ?? null) !== null;
                            @endphp
                            <div class="b-match {{ $isActive ? 'active' : '' }}">
                                <div class="b-slot {{ $isDone && $match['winner'] === 'athlete1' ? 'winner' : '' }}">
                                    <div class="b-slot-color red"></div>
                                    <div class="b-slot-info">
                                        @if ($match['athlete1'] ?? null)
                                            <div class="b-slot-name">{{ $match['athlete1']['name'] }}</div>
                                            <div class="b-slot-cont">{{ $match['athlete1']['contingent'] }}</div>
                                        @else
                                            <div class="b-slot-empty">Menunggu Lawan</div>
                                        @endif
                                    </div>
                                    @if ($isDone && $match['winner'] === 'athlete1')
                                        <i class="fas fa-check-circle b-win-icon"></i>
                                    @endif
                                </div>
                                <div class="b-slot {{ $isDone && $match['winner'] === 'athlete2' ? 'winner' : '' }}">
                                    <div class="b-slot-color blue"></div>
                                    <div class="b-slot-info">
                                        @if ($match['athlete2'] ?? null)
                                            <div class="b-slot-name">{{ $match['athlete2']['name'] }}</div>
                                            <div class="b-slot-cont">{{ $match['athlete2']['contingent'] }}</div>
                                        @else
                                            <div class="b-slot-empty">Menunggu Lawan</div>
                                        @endif
                                    </div>
                                    @if ($isDone && $match['winner'] === 'athlete2')
                                        <i class="fas fa-check-circle b-win-icon"></i>
                                    @endif
                                </div>
                                @if (isset($match['athlete1']) && isset($match['athlete2']))
                                    <div class="b-match-actions">
                                        @if (!$isActive && !$isDone)
                                            <button
                                                wire:click="callMatch('{{ $nodeKey }}', {{ $roundIdx }}, {{ $matchIdx }}, 'ub')"
                                                class="b-action-btn"><i class="fas fa-bullhorn"></i> Panggil</button>
                                        @elseif($isActive)
                                            <button
                                                wire:click="openMatchModal('ub', {{ $roundIdx }}, {{ $matchIdx }})"
                                                class="b-action-btn" style="color:#2980b9;"><i
                                                    class="fas fa-edit"></i> Skor</button>
                                            <button wire:click="dismissMatch()" class="b-action-btn"
                                                style="color:var(--red);"><i class="fas fa-times"></i> Tutup</button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        {{-- LOWER BRACKET --}}
        @if (count($lbRounds) > 0)
            <div class="bracket-wrapper">
                <div class="bracket-hdr lb">
                    <i class="fas fa-arrow-down" style="color:#d35400;"></i> LOWER BRACKET — SECOND CHANCE PATH
                    <span style="margin-left:auto; font-size:10px; opacity:0.6;"><i
                            class="fas fa-arrows-left-right"></i> geser</span>
                </div>
                <div class="bracket-scroll">
                    @foreach ($lbRounds as $lbRoundIdx => $matches)
                        @php
                            $totalLB = count($lbRounds);
                            if ($lbRoundIdx === $totalLB - 1) {
                                $lbLabel = 'LB FINAL';
                            } elseif ($lbRoundIdx === $totalLB - 2) {
                                $lbLabel = 'LB SEMI';
                            } else {
                                $lbLabel = 'LB R' . ($lbRoundIdx + 1);
                            }
                        @endphp
                        <div class="bracket-round-col">
                            <div class="bracket-round-title">{{ $lbLabel }}</div>
                            @foreach ($matches as $matchIdx => $match)
                                @php
                                    $nodeKey = 'lb_' . $lbRoundIdx . '_' . $matchIdx;
                                    $isActive = $matchNumber->active_bracket_node === $nodeKey;
                                    $isDone = ($match['winner'] ?? null) !== null;
                                @endphp
                                <div class="b-match {{ $isActive ? 'active' : '' }}">
                                    <div
                                        class="b-slot {{ $isDone && $match['winner'] === 'athlete1' ? 'winner' : '' }}">
                                        <div class="b-slot-color red"></div>
                                        <div class="b-slot-info">
                                            @if ($match['athlete1'] ?? null)
                                                <div class="b-slot-name">{{ $match['athlete1']['name'] }}</div>
                                                <div class="b-slot-cont">{{ $match['athlete1']['contingent'] }}</div>
                                            @else
                                                <div class="b-slot-empty">Menunggu Lawan</div>
                                            @endif
                                        </div>
                                        @if ($isDone && $match['winner'] === 'athlete1')
                                            <i class="fas fa-check-circle b-win-icon"></i>
                                        @endif
                                    </div>
                                    <div
                                        class="b-slot {{ $isDone && $match['winner'] === 'athlete2' ? 'winner' : '' }}">
                                        <div class="b-slot-color blue"></div>
                                        <div class="b-slot-info">
                                            @if ($match['athlete2'] ?? null)
                                                <div class="b-slot-name">{{ $match['athlete2']['name'] }}</div>
                                                <div class="b-slot-cont">{{ $match['athlete2']['contingent'] }}</div>
                                            @else
                                                <div class="b-slot-empty">Menunggu Lawan</div>
                                            @endif
                                        </div>
                                        @if ($isDone && $match['winner'] === 'athlete2')
                                            <i class="fas fa-check-circle b-win-icon"></i>
                                        @endif
                                    </div>
                                    @if (isset($match['athlete1']) && isset($match['athlete2']))
                                        <div class="b-match-actions">
                                            @if (!$isActive && !$isDone)
                                                <button
                                                    wire:click="callMatch('{{ $nodeKey }}', {{ $lbRoundIdx }}, {{ $matchIdx }}, 'lb')"
                                                    class="b-action-btn"><i class="fas fa-bullhorn"></i>
                                                    Panggil</button>
                                            @elseif($isActive)
                                                <button
                                                    wire:click="callMatch('{{ $nodeKey }}', {{ $lbRoundIdx }}, {{ $matchIdx }}, 'lb')"
                                                    class="b-action-btn"><i class="fas fa-redo"></i> Ulang</button>
                                                <button
                                                    wire:click="openMatchModal('lb', {{ $lbRoundIdx }}, {{ $matchIdx }})"
                                                    class="b-action-btn" style="color:#2980b9;"><i
                                                        class="fas fa-edit"></i> Skor</button>
                                                <button wire:click="dismissMatch()" class="b-action-btn"
                                                    style="color:var(--red);"><i class="fas fa-times"></i>
                                                    Tutup</button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- GRAND FINAL --}}
        @if ($grandFinal)
            <div class="bracket-wrapper">
                <div class="bracket-hdr gf">
                    <i class="fas fa-trophy" style="color:#f39c12;"></i> GRAND FINAL — UB CHAMPION VS LB CHAMPION
                </div>
                <div style="padding: 32px; display:flex; justify-content:center;">
                    @php
                        $gfDone = ($grandFinal['winner'] ?? null) !== null;
                        $gfActive = str_starts_with($matchNumber->active_bracket_node ?? '', 'gf_');
                    @endphp
                    <div class="b-match {{ $gfActive ? 'active' : '' }}"
                        style="width: 100%; max-width: 400px; transform: scale(1.1); box-shadow: 0 8px 24px rgba(243, 156, 18, 0.2);">
                        <div class="b-slot {{ $gfDone && $grandFinal['winner'] === 'athlete1' ? 'winner' : '' }}"
                            style="padding: 16px 20px;">
                            <div class="b-slot-color red"></div>
                            <div class="b-slot-info">
                                <div
                                    style="font-size:9px; color:var(--red); font-weight:700; text-transform:uppercase; margin-bottom:4px;">
                                    UB Champion</div>
                                @if ($grandFinal['athlete1'] ?? null)
                                    <div class="b-slot-name" style="font-size:15px;">
                                        {{ $grandFinal['athlete1']['name'] }}</div>
                                    <div class="b-slot-cont">{{ $grandFinal['athlete1']['contingent'] }}</div>
                                @else
                                    <div class="b-slot-empty">Menunggu Champion</div>
                                @endif
                            </div>
                            @if ($gfDone && $grandFinal['winner'] === 'athlete1')
                                <i class="fas fa-trophy" style="color:#f1c40f; font-size:16px;"></i>
                            @endif
                        </div>
                        <div
                            style="text-align:center; padding:4px; font-weight:700; font-size:10px; color:var(--smoke); background:var(--paper);">
                            VS</div>
                        <div class="b-slot {{ $gfDone && $grandFinal['winner'] === 'athlete2' ? 'winner' : '' }}"
                            style="padding: 16px 20px;">
                            <div class="b-slot-color blue"></div>
                            <div class="b-slot-info">
                                <div
                                    style="font-size:9px; color:#2980b9; font-weight:700; text-transform:uppercase; margin-bottom:4px;">
                                    LB Champion</div>
                                @if ($grandFinal['athlete2'] ?? null)
                                    <div class="b-slot-name" style="font-size:15px;">
                                        {{ $grandFinal['athlete2']['name'] }}</div>
                                    <div class="b-slot-cont">{{ $grandFinal['athlete2']['contingent'] }}</div>
                                @else
                                    <div class="b-slot-empty">Menunggu Champion</div>
                                @endif
                            </div>
                            @if ($gfDone && $grandFinal['winner'] === 'athlete2')
                                <i class="fas fa-trophy" style="color:#f1c40f; font-size:16px;"></i>
                            @endif
                        </div>
                        @if (isset($grandFinal['athlete1']) && isset($grandFinal['athlete2']) && !$gfDone)
                            <div class="b-match-actions">
                                @if (!$gfActive && !$gfDone)
                                    <button wire:click="callGrandFinal()" class="b-action-btn"><i
                                            class="fas fa-bullhorn"></i> Panggil GF</button>
                                @elseif($gfActive)
                                    <button wire:click="callGrandFinal()" class="b-action-btn"><i
                                            class="fas fa-redo"></i> Ulang</button>
                                    <button wire:click="openGrandFinalModal()" class="b-action-btn"
                                        style="color:#f39c12;"><i class="fas fa-edit"></i> Skor GF</button>
                                    <button wire:click="dismissMatch()" class="b-action-btn"
                                        style="color:var(--red);"><i class="fas fa-times"></i> Tutup</button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- WINNERS / JUARA --}}
        @if (!empty($juaraMap))
            {{-- ====== HASIL AKHIR PERTANDINGAN ====== --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden mt-12 mb-8">
                <div class="px-6 py-4 bg-slate-900 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                            <i class="fas fa-trophy text-white text-lg"></i>
                        </div>
                        <h2 class="text-lg font-black text-white uppercase tracking-tight leading-none">Hasil Akhir
                            Pertandingan</h2>
                    </div>

                    @if (count($juaraMap) >= 2)
                        <button wire:click="confirmChampion"
                            class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-xl shadow-lg shadow-emerald-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="fas fa-save text-[13px]"></i> Simpan Juara Ke Laporan
                        </button>
                    @endif
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @php
                            $medalConfig = [
                                1 => [
                                    'label' => 'Juara 1',
                                    'icon' => '🥇',
                                    'bg' => 'bg-amber-50',
                                    'border' => 'border-amber-200',
                                    'text' => 'text-amber-600',
                                ],
                                2 => [
                                    'label' => 'Juara 2',
                                    'icon' => '🥈',
                                    'bg' => 'bg-slate-50',
                                    'border' => 'border-slate-200',
                                    'text' => 'text-slate-600',
                                ],
                                3 => [
                                    'label' => 'Juara 3 Bersama',
                                    'icon' => '🥉',
                                    'bg' => 'bg-orange-50',
                                    'border' => 'border-orange-200',
                                    'text' => 'text-orange-600',
                                ],
                                4 => [
                                    'label' => 'Juara 3 Bersama',
                                    'icon' => '🥉',
                                    'bg' => 'bg-orange-50',
                                    'border' => 'border-orange-200',
                                    'text' => 'text-orange-600',
                                ],
                            ];
                        @endphp

                        @foreach ($medalConfig as $rank => $conf)
                            @php $athlete = $juaraMap[$rank] ?? null; @endphp
                            <div class="relative group">
                                <div
                                    class="h-full px-5 py-8 rounded-2xl border {{ $athlete ? $conf['border'] . ' ' . $conf['bg'] : 'border-slate-100 bg-white' }} flex flex-col items-center text-center transition-all duration-300 {{ $athlete ? 'shadow-md shadow-amber-500/5' : '' }}">
                                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                        {{ $conf['icon'] }}</div>
                                    <div
                                        class="text-[10px] font-black {{ $conf['text'] }} uppercase tracking-[0.2em] mb-3">
                                        {{ $conf['label'] }}</div>

                                    @if ($athlete)
                                        <div class="text-base font-black text-slate-800 uppercase leading-tight mb-1">
                                            {{ $athlete['name'] }}</div>
                                        <div class="text-xs font-bold text-slate-500">
                                            {{ $athlete['contingent'] ?? '—' }}</div>
                                    @else
                                        <div class="w-12 h-1 bg-slate-100 rounded-full mb-3 mt-1"></div>
                                        <div
                                            class="text-[11px] font-bold text-slate-300 italic uppercase tracking-wider">
                                            Menunggu Hasil...</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    @endif
    <div class="officials-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="tm-card official-card" style="border-left-color: #f59e0b;">
            <div class="official-label">
                <i class="fas fa-gavel"></i> Dewan Arbitrase
            </div>
            <div class="official-val">
                {{ $assignedArbitrase?->referee?->user?->name ?? 'Belum ditugaskan' }}
            </div>
            <div class="official-sub">Lisensi: {{ $assignedArbitrase?->referee?->license_number ?? '-' }}</div>
        </div>
        
        <div class="tm-card official-card" style="border-left-color: #10b981;">
            <div class="official-label">
                <i class="fas fa-user-shield"></i> Dewan Hakim / Wasit Lapangan
            </div>
            <div class="official-val">
                @if ($assignedReferees->isNotEmpty())
                    <ol class="official-list" style="list-style-type: decimal; padding-left: 16px;">
                        @foreach ($assignedReferees as $sr)
                            <li>{{ $sr->referee?->user?->name }} <span style="font-size:10px; font-weight:normal; color:var(--smoke);"> (Juri {{ $sr->judge_index }})</span></li>
                        @endforeach
                    </ol>
                @else
                    Belum ditugaskan
                @endif
            </div>
        </div>

        <div class="tm-card official-card">
            <div class="official-label">
                <i class="fas fa-user-tie"></i> Koordinator Pertandingan
            </div>
            <div class="official-val">
                @if ($assignedKoordinators->isNotEmpty())
                    <ul class="official-list" style="list-style-type: none; padding-left: 0;">
                        @foreach ($assignedKoordinators as $ak)
                            <li>{{ $ak->user->name }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ $officials['koordinator_lapangan'] ?? '-' }}
                @endif
            </div>
            <div class="official-sub">NIP. -</div>
        </div>
        <div class="tm-card official-card" style="border-left-color: var(--ink);">
            <div class="official-label">
                <i class="fas fa-users"></i> Para Panitera
            </div>
            <div class="official-val">
                @if ($assignedPaniteras->isNotEmpty())
                    <ul class="official-list" style="list-style-type: none; padding-left: 0;">
                        @foreach ($assignedPaniteras as $ap)
                            <li>{{ $ap->user->name }}</li>
                        @endforeach
                    </ul>
                @else
                    @if (isset($officials['panitera']) && is_array($officials['panitera']))
                        <ul class="official-list">
                            @foreach ($officials['panitera'] as $p)
                                <li>{{ $p }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $officials['panitera'] ?? '-' }}
                    @endif
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

        window.addEventListener('scroll-top', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
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
                .toLowerCase()
                .replace(/\./g, '. ')
                .replace(/,/g, ', ')
                .replace(/-/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }
    </script>
@endpush
