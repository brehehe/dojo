<div class="p-4 md:p-6 space-y-6">
    {{-- ═══ FIXED RESET BUTTON ═══ --}}
    <div class="fixed bottom-8 right-4 z-[100] md:bottom-10 md:right-6">
        <button wire:click="clearAllCourts" wire:confirm="PERINGATAN: Ini akan mereset status SEMUA lapangan & match yang sedang berjalan menjadi KOSONG. Lanjutkan?"
            class="flex items-center gap-2 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white shadow-2xl shadow-rose-200 rounded-2xl text-[13px] font-black uppercase tracking-widest transition-all active:scale-95 border-2 border-white/20 backdrop-blur-sm">
            <i class="fas fa-eraser text-lg"></i>
            <span class="hidden sm:inline">Reset Semua Lapangan</span>
            <span class="sm:hidden">Reset</span>
        </button>
    </div>

    {{-- ====== HEADER ====== --}}
    <div>
        <nav class="flex mb-3 text-[15px] font-bold uppercase tracking-widest text-slate-800 gap-2 items-center">
            <a href="{{ route('admin.arbitrase.scoring.index') }}" class="hover:text-amber-500 transition-colors">Scoring</a>
            <i class="fas fa-chevron-right text-[15px]"></i>
            <span class="text-slate-800">Randori Bracket</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[15px] font-black uppercase tracking-widest rounded border border-rose-100">RANDORI</span>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-800 uppercase tracking-tighter">{{ $matchNumber->name }}</h1>
                </div>
                <p class="text-[15px] text-slate-900 font-medium italic">Double Elimination — kalah 1x masih bisa juara via Loser Bracket</p>
            </div>
            <div class="flex flex-col items-end gap-3">


                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500"></div>
                        <span class="text-[15px] font-black text-indigo-600 uppercase">Upper Bracket (UB)</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-400"></div>
                        <span class="text-[15px] font-black text-orange-600 uppercase">Loser Bracket (LB)</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        <span class="text-[15px] font-black text-amber-600 uppercase">Grand Final</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $ubRounds = $drawingData['upper_bracket']['rounds'] ?? [];
        $lbRounds = $drawingData['lower_bracket']['rounds'] ?? [];
        $grandFinal = $drawingData['grand_final'] ?? null;
        $juaraMap = $juara ?? [];

        $ubRoundLabels = ['UB Penyisihan', 'UB Perempat Final', 'UB Semi Final', 'UB Final'];
        $lbRoundLabels = ['LB R1', 'LB R2', 'LB R3', 'LB R4', 'LB Semi', 'LB Final'];
    @endphp

    {{-- ====== REPAIR BANNER ====== --}}
    @if(!empty($needsRepair))
        <div class="bg-amber-50 border border-amber-300 rounded-2xl px-5 py-4 flex items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-tools text-amber-600 text-[15px]"></i>
                </div>
                <div>
                    <p class="text-[15px] font-black text-amber-800 uppercase tracking-widest">Routing Bracket Tidak Lengkap</p>
                    <p class="text-[15px] text-amber-600 font-medium mt-0.5">Beberapa match belum punya jalur winner / loser. Klik tombol di bawah untuk memperbaiki otomatis dan me-replay semua hasil yang ada.</p>
                </div>
            </div>
            <button wire:click="repairBracket"
                    wire:loading.attr="disabled"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-[15px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm active:scale-95">
                <span wire:loading.remove wire:target="repairBracket"><i class="fas fa-wrench"></i> Perbaiki Bracket</span>
                <span wire:loading wire:target="repairBracket"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
            </button>
        </div>
    @endif

    @if(empty($ubRounds))
        <div class="flex flex-col items-center justify-center py-20 bg-white border-2 border-dashed border-slate-200 rounded-[2.5rem]">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-sitemap text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Bagan Belum Dibuat</h3>
            <p class="text-[15px] text-slate-900 mt-2">Klik "Generate Drawing" di halaman Technical Meeting untuk membuat bagan.</p>
        </div>
    @else

        {{-- ====== UPPER BRACKET ====== --}}
        <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 flex items-center gap-2">
                <i class="fas fa-arrow-up text-white text-[15px]"></i>
                <span class="text-[15px] font-black text-white uppercase tracking-widest">Upper Bracket — Winner Path</span>
            </div>
            <div class="overflow-x-auto p-4 pb-6">
                <div class="flex gap-12 items-start min-w-max">
                    @foreach($ubRounds as $roundIdx => $matches)
                        @php
                            $totalUB = count($ubRounds);
                            if ($roundIdx === $totalUB - 1) { $roundLabel = 'UB FINAL'; }
                            elseif ($roundIdx === $totalUB - 2 && $totalUB > 2) { $roundLabel = 'UB SEMI FINAL'; }
                            else { $roundLabel = 'UB R' . ($roundIdx + 1); }
                        @endphp
                        <div class="flex flex-col gap-6">
                            <div class="text-center mb-2">
                                <span class="text-[15px] font-black text-indigo-400 uppercase tracking-[0.3em]">{{ $roundLabel }}</span>
                            </div>
                            @foreach($matches as $matchIdx => $match)
                                @php
                                    $nodeKey = 'ub_' . $roundIdx . '_' . $matchIdx;
                                    $result = $results[$nodeKey] ?? null;
                                    $isActive = $matchNumber->active_bracket_node === $nodeKey;
                                    $isDone = ($match['winner'] ?? null) !== null;
                                @endphp
                                @include('livewire.admin.arbitrase.scoring.partials._match-card', [
                                    'match' => $match,
                                    'bracket' => 'ub',
                                    'roundIdx' => $roundIdx,
                                    'matchIdx' => $matchIdx,
                                    'nodeKey' => $nodeKey,
                                    'isActive' => $isActive,
                                    'isDone' => $isDone,
                                    'colorScheme' => 'indigo',
                                    'matchLabel' => 'UB M' . ($matchIdx + 1),
                                ])
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ====== LOWER BRACKET ====== --}}
        @if(count($lbRounds) > 0)
        <div class="bg-white rounded-2xl border border-orange-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-orange-500 to-amber-500 flex items-center gap-2">
                <i class="fas fa-arrow-down text-white text-[15px]"></i>
                <span class="text-[15px] font-black text-white uppercase tracking-widest">Loser Bracket — Second Chance Path</span>
            </div>
            <div class="overflow-x-auto p-4 pb-6">
                <div class="flex gap-12 items-start min-w-max">
                    @foreach($lbRounds as $lbRoundIdx => $matches)
                        @php
                            $totalLB = count($lbRounds);
                            if ($lbRoundIdx === $totalLB - 1) { $lbLabel = 'LB FINAL'; }
                            elseif ($lbRoundIdx === $totalLB - 2) { $lbLabel = 'LB SEMI'; }
                            else { $lbLabel = 'LB R' . ($lbRoundIdx + 1); }
                        @endphp
                        <div class="flex flex-col gap-6">
                            <div class="text-center mb-2">
                                <span class="text-[15px] font-black text-orange-400 uppercase tracking-[0.3em]">{{ $lbLabel }}</span>
                            </div>
                            @foreach($matches as $matchIdx => $match)
                                @php
                                    $nodeKey = 'lb_' . $lbRoundIdx . '_' . $matchIdx;
                                    $isActive = $matchNumber->active_bracket_node === $nodeKey;
                                    $isDone = ($match['winner'] ?? null) !== null;
                                @endphp
                                @include('livewire.admin.arbitrase.scoring.partials._match-card', [
                                    'match' => $match,
                                    'bracket' => 'lb',
                                    'roundIdx' => $lbRoundIdx,
                                    'matchIdx' => $matchIdx,
                                    'nodeKey' => $nodeKey,
                                    'isActive' => $isActive,
                                    'isDone' => $isDone,
                                    'colorScheme' => 'orange',
                                    'matchLabel' => 'LB M' . ($matchIdx + 1),
                                ])
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ====== GRAND FINAL ====== --}}
        @if($grandFinal)
        <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-amber-500 to-yellow-500 flex items-center gap-2">
                <i class="fas fa-trophy text-white text-[15px]"></i>
                <span class="text-[15px] font-black text-white uppercase tracking-widest">Grand Final — UB Champion vs LB Champion</span>
            </div>
            <div class="p-6">
                <div class="max-w-lg mx-auto">
                    @php
                        $gfDone = ($grandFinal['winner'] ?? null) !== null;
                        $gfActive = str_starts_with($matchNumber->active_bracket_node ?? '', 'gf_');
                    @endphp
                    <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-2 {{ $gfDone ? 'border-amber-400' : 'border-amber-200' }} rounded-2xl overflow-hidden {{ $gfActive ? 'ring-4 ring-amber-300 ring-offset-2' : '' }}">
                        {{-- Athlete 1 (UB Champion) --}}
                        <div class="px-4 py-3 border-b border-amber-100 flex items-center gap-3 {{ $gfDone && $grandFinal['winner'] === 'athlete1' ? 'bg-amber-100/60' : '' }}">
                            <div class="w-1.5 h-10 rounded-full {{ $gfDone && $grandFinal['winner'] === 'athlete1' ? 'bg-amber-500' : 'bg-slate-200' }} flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="text-[15px] font-black text-amber-500 uppercase tracking-widest">UB Champion</div>
                                <div class="text-[15px] font-black text-slate-800 uppercase">{{ $grandFinal['athlete1']['name'] ?? '—' }}</div>
                                @if($grandFinal['athlete1']['contingent'] ?? null)
                                    <div class="text-[15px] text-slate-800">{{ $grandFinal['athlete1']['contingent'] }}</div>
                                @endif
                            </div>
                            @if($gfDone && $grandFinal['winner'] === 'athlete1')
                                <span class="text-[15px] font-black bg-amber-500 text-white px-2 py-1 rounded-lg">🏆 JUARA 1</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-center py-1.5 bg-amber-100/40 text-[15px] font-black text-amber-400 tracking-widest uppercase">VS</div>
                        {{-- Athlete 2 (LB Champion) --}}
                        <div class="px-4 py-3 flex items-center gap-3 {{ $gfDone && $grandFinal['winner'] === 'athlete2' ? 'bg-amber-100/60' : '' }}">
                            <div class="w-1.5 h-10 rounded-full {{ $gfDone && $grandFinal['winner'] === 'athlete2' ? 'bg-amber-500' : 'bg-slate-200' }} flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="text-[15px] font-black text-orange-500 uppercase tracking-widest">LB Champion</div>
                                <div class="text-[15px] font-black text-slate-800 uppercase">{{ $grandFinal['athlete2']['name'] ?? '—' }}</div>
                                @if($grandFinal['athlete2']['contingent'] ?? null)
                                    <div class="text-[15px] text-slate-800">{{ $grandFinal['athlete2']['contingent'] }}</div>
                                @endif
                            </div>
                            @if($gfDone && $grandFinal['winner'] === 'athlete2')
                                <span class="text-[15px] font-black bg-amber-500 text-white px-2 py-1 rounded-lg">🏆 JUARA 1</span>
                            @endif
                        </div>
                        {{-- Footer --}}
                        @if(!$gfDone && $grandFinal['athlete1'] && $grandFinal['athlete2'])
                            <div class="border-t border-amber-100 bg-amber-50 px-4 py-2.5 flex flex-col gap-2">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$gfActive)
                                        <button wire:click="callGrandFinal()"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-amber-100 border border-amber-300 text-amber-700 hover:bg-amber-500 hover:text-white transition-all">
                                            <i class="fas fa-bullhorn text-[15px]"></i> Panggil
                                        </button>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <button wire:click="callGrandFinal()"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-amber-100 border border-amber-300 text-amber-700 hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                                                title="Panggil Ulang">
                                                <i class="fas fa-redo text-[15px]"></i>
                                            </button>
                                            <button wire:click="dismissMatch()"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                                title="Tutup Panggilan">
                                                <i class="fas fa-times text-[15px]"></i>
                                            </button>
                                            <button wire:click="openGrandFinalModal()"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[15px] font-black uppercase bg-slate-900 text-white hover:bg-amber-600 transition-all shadow-sm">
                                                <i class="fas fa-edit text-[15px]"></i> Input Skor
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                
                                        <div wire:ignore x-data="{
                                            time: 0,
                                            running: false,
                                            countdown: 0,
                                            offset: 0,
                                            lastTickSecond: -1,
                                            interpolInterval: null,
                                            syncInterval: null,
                                            starting: false,
                                            state: { status: 'stopped', elapsed_ms: 0, started_at_ms: null, countdown_end_ms: null },
                                            formatTime() {
                                                let t = Math.max(0, this.time);
                                                let m = Math.floor(t / 60000);
                                                let s = Math.floor((t % 60000) / 1000);
                                                return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                                            },
                                            formatCountdown() {
                                                if (this.countdown === 5) return 'Siap';
                                                if (this.countdown === 4) return '3';
                                                if (this.countdown === 3) return '2';
                                                if (this.countdown === 2) return '1';
                                                if (this.countdown === 1) return 'Mulai';
                                                return this.countdown > 0 ? this.countdown.toString() : '';
                                            },
                                            async sync() {
                                                let state = await $wire.getTimerState();
                                                if (!state) return;
                                                
                                                let oldCountdown = this.countdown;
                                                this.offset = state.server_time_ms - Date.now();
                                                this.state = state;
                                                
                                                this.running = (state.status === 'running');
                                                
                                                if (state.status === 'countdown') {
                                                    let remaining = state.countdown_end_ms - (Date.now() + this.offset);
                                                    this.countdown = remaining > 0 ? Math.ceil(remaining / 1000) : 0;
                                                } else {
                                                    this.countdown = 0;
                                                }

                                                // Trigger Countdown Voice
                                                if (this.countdown > 0 && this.countdown !== oldCountdown) {
                                                    window.speakCountdown ? window.speakCountdown(this.formatCountdown()) : null;
                                                }
                                            },
                                            init() {
                                                this.sync();
                                                this.interpolInterval = setInterval(() => { 
                                                    if (this.running && this.state.started_at_ms) {
                                                        let expected = (this.state.elapsed_ms || 0) + (Date.now() + this.offset - this.state.started_at_ms);
                                                        this.time = expected;
                                                        let currentSecond = Math.floor(this.time / 1000);
                                                        
                                                        // Play tick only if second has actually changed
                                                        if (currentSecond > this.lastTickSecond) {
                                                            window.playTimerTick ? window.playTimerTick(1000, 0.05) : null;
                                                            this.lastTickSecond = currentSecond;
                                                        }
                                                    } else if (this.state.status === 'countdown' && this.state.countdown_end_ms) {
                                                        let remaining = this.state.countdown_end_ms - (Date.now() + this.offset);
                                                        this.countdown = remaining > 0 ? Math.ceil(remaining / 1000) : 0;
                                                        this.time = this.state.elapsed_ms || 0;
                                                        if (remaining <= 0 && !this.starting) {
                                                            this.starting = true;
                                                            $wire.startTimer().then(() => { this.starting = false; });
                                                        }
                                                        this.lastTickSecond = Math.floor(this.time / 1000);
                                                    } else {
                                                        this.countdown = 0;
                                                        this.time = this.state.elapsed_ms || 0;
                                                        this.lastTickSecond = Math.floor(this.time / 1000);
                                                    }
                                                }, 30);
                                                this.syncInterval = setInterval(() => { this.sync(); }, 300);
                                            },
                                            start() {
                                                if (!this.running && this.countdown === 0) {
                                                    $wire.startCountdown();
                                                }
                                            },
                                            pause() { $wire.pauseTimer(); },
                                            stop() { $wire.stopTimer(); },
                                            finish() {
                                                let capturedTime = this.time;
                                                $wire.pauseTimer();
                                                Swal.fire({
                                                    title: 'Apakah anda yakin?',
                                                    html: '<p>Grand Final akan ditandai <b>Selesai</b>.<br>Panggilan akan ditutup / dinonaktifkan.</p>',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Ya, Selesai!',
                                                    cancelButtonText: 'Batal',
                                                    confirmButtonColor: '#2563eb',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.finishMatch();
                                                    } else {
                                                        $wire.startTimer();
                                                    }
                                                });
                                            }
                                        }" class="flex items-center justify-between border-t border-amber-200/60 pt-2">
                                        <div class="flex items-center gap-1.5">
                                            <button @click="window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3')" class="w-5 h-5 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-400 rounded-full transition-colors mr-1" title="Test Suara">
                                                <i class="fas fa-volume-up text-[10px]"></i>
                                            </button>
                                            <i class="fas fa-stopwatch text-amber-500"></i>
                                            <div class="text-[15px] font-black font-mono tracking-wider" :class="countdown > 0 ? 'text-orange-500' : 'text-amber-700'">
                                                <span x-show="countdown > 0" x-text="formatCountdown()"></span>
                                                <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button x-show="!running && countdown === 0" @click="start()" class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-md transition-colors shadow-sm" title="Start Timer"><i class="fas fa-play text-xs"></i></button>
                                            <button x-show="running" @click="pause()" class="w-8 h-8 flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors shadow-sm" title="Pause Timer"><i class="fas fa-pause text-xs"></i></button>
                                            <button @click="stop()" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-md transition-colors shadow-sm" title="Stop & Reset"><i class="fas fa-stop text-xs"></i></button>
                                            <button x-show="running || time > 0" @click="finish()" class="h-8 px-2 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors shadow-sm ml-1" title="Selesai & Tutup">
                                                <i class="fas fa-flag-checkered text-xs mr-1"></i><span class="text-xs font-black uppercase tracking-wider">Selesai</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(!empty($juaraMap))
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-slate-800 to-slate-700 flex items-center gap-2">
                <i class="fas fa-medal text-amber-400 text-[18px]"></i>
                <span class="text-lg font-black text-white uppercase tracking-widest">Hasil Akhir Pertandingan</span>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    @foreach([1=>'🥇',2=>'🥈',3=>'🥉',4=>'🏅'] as $rank => $medal)
                        @php 
                            $athlete = $juaraMap[$rank] ?? null; 
                            // Selalu tampilkan Juara 3 Bersama untuk peringkat 3 dan 4
                            $displayRank = ($rank == 3 || $rank == 4) ? '3 Bersama' : $rank;
                            $displayMedal = ($rank == 4) ? '🥉' : $medal;
                        @endphp
                        <div class="text-center p-6 rounded-2xl {{ $athlete ? 'bg-amber-50 border border-amber-200 ring-4 ring-amber-500/5' : 'bg-slate-50 border border-slate-100' }} transition-all hover:scale-[1.02]">
                            <div class="text-4xl mb-3">{{ $displayMedal }}</div>
                            <div class="text-[13px] font-black text-slate-400 uppercase tracking-widest mb-1">Juara {{ $displayRank }}</div>
                            @if($athlete)
                                <div class="text-lg font-black text-slate-800 uppercase leading-tight">{{ $athlete['name'] }}</div>
                                <div class="text-[15px] font-bold text-slate-500 mt-1 uppercase">{{ $athlete['contingent'] ?? '' }}</div>
                            @else
                                <div class="text-[15px] text-slate-300 font-bold italic">Menunggu...</div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Signature & Personnel Section --}}
                <div class="mt-8 pt-8 border-t border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Left Column --}}
                        <div class="space-y-6">
                            <div>
                                <label class="flex items-center gap-2 text-[15px] font-black text-slate-800 uppercase mb-2">
                                    <i class="fas fa-gavel text-slate-400"></i> Wasit Utama :
                                </label>
                                <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-[15px] font-bold text-slate-700">
                                    {{ $matchNumber->main_referee ?? '................................................' }}
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="text-[15px] font-black text-slate-800 uppercase mb-2 block">Manager Kontingen (Merah) :</label>
                                    <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-[15px] font-bold text-slate-700 italic">
                                        (Tanda Tangan)
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[15px] font-black text-slate-800 uppercase mb-2 block">Manager Kontingen (Putih) :</label>
                                    <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-[15px] font-bold text-slate-700 italic">
                                        (Tanda Tangan)
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="grid grid-cols-1 gap-6">
                            <div class="bg-indigo-50/50 border border-indigo-100 rounded-[2rem] p-6">
                                <label class="flex items-center gap-2 text-[15px] font-black text-indigo-900 uppercase mb-3">
                                    <i class="fas fa-user-tie text-indigo-400"></i> Koordinator Pertandingan
                                </label>
                                <div class="space-y-1">
                                    <p class="text-lg font-black text-slate-800 uppercase">{{ $matchNumber->coordinator_name ?? '................................' }}</p>
                                    <p class="text-[15px] font-bold text-slate-500 uppercase">NIP. {{ $matchNumber->coordinator_nip ?? '................................' }}</p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-indigo-100/50 flex items-center gap-2">
                                    <i class="fas fa-users text-indigo-300"></i>
                                    <span class="text-[13px] font-black text-indigo-800 uppercase">Para Panitera :</span>
                                </div>
                                <ul class="mt-2 space-y-1">
                                    <li class="text-[15px] font-bold text-slate-600">1. ................................</li>
                                    <li class="text-[15px] font-bold text-slate-600">2. ................................</li>
                                    <li class="text-[15px] font-bold text-slate-600">3. ................................</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @endif {{-- end if ubRounds --}}

    {{-- ====== RESULT MODAL ====== --}}
    @if($showModal && $activeMatch)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
            <div class="bg-white rounded-3xl w-full max-w-7xl shadow-2xl relative max-h-[90vh] overflow-y-auto css-scrollbar">
                <div class="p-5 md:p-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-600/20">
                                <i class="fas fa-gavel text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight leading-none">Panel Penilaian Randori</h2>
                                <p class="text-[15px] text-slate-500 font-bold uppercase tracking-widest mt-1.5">
                                    {{ strtoupper($activeMatch['bracket']) }} &bull;
                                    @if($activeMatch['bracket'] === 'gf') Grand Final @else BABAK {{ $activeMatch['round'] + 1 }} &bull; MATCH {{ $activeMatch['match'] + 1 }} @endif
                                </p>
                            </div>
                        </div>
                                             <div wire:ignore x-data="{
                                        time: 0,
                                        running: false,
                                        countdown: 0,
                                        offset: 0,
                                        lastTickSecond: -1,
                                        playedIntervals: new Set(),
                                        interpolInterval: null,
                                        syncInterval: null,
                                        starting: false,
                                        state: { status: 'stopped', elapsed_ms: 0, started_at_ms: null, countdown_end_ms: null },
                                        formatTime() {
                                            let t = Math.max(0, this.time);
                                            let m = Math.floor(t / 60000);
                                            let s = Math.floor((t % 60000) / 1000);
                                            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                                        },
                                        formatCountdown() {
                                            if (this.countdown === 5) return 'Siap';
                                            if (this.countdown === 4) return '3';
                                            if (this.countdown === 3) return '2';
                                            if (this.countdown === 2) return '1';
                                            if (this.countdown === 1) return 'Mulai';
                                            return this.countdown > 0 ? this.countdown.toString() : '';
                                        },
                                        async sync() {
                                            let state = await $wire.getTimerState();
                                            if (!state) return;
                                            
                                            let oldCountdown = this.countdown;
                                            this.offset = state.server_time_ms - Date.now();
                                            this.state = state;
                                            
                                            this.running = (state.status === 'running');
                                            
                                            if (state.status === 'countdown') {
                                                let remaining = state.countdown_end_ms - (Date.now() + this.offset);
                                                this.countdown = remaining > 0 ? Math.ceil(remaining / 1000) : 0;
                                            } else {
                                                this.countdown = 0;
                                            }

                                            // Trigger Countdown Voice
                                            if (this.countdown > 0 && this.countdown !== oldCountdown) {
                                                window.speakCountdown ? window.speakCountdown(this.formatCountdown()) : null;
                                            }
                                        },
                                        init() {
                                            this.sync();
                                            this.interpolInterval = setInterval(() => { 
                                                if (this.running && this.state.started_at_ms) {
                                                    let expected = (this.state.elapsed_ms || 0) + (Date.now() + this.offset - this.state.started_at_ms);
                                                    this.time = expected;
                                                    let currentSecond = Math.floor(this.time / 1000);
                                                    
                                                    // Interval buzzers: 120s
                                                    if (currentSecond === 120 && !this.playedIntervals.has(120)) {
                                                        window.playBuzzer ? window.playBuzzer('/music/freesound_community-buzzerwav-14908.mp3') : null;
                                                        this.playedIntervals.add(120);
                                                        $wire.pauseTimer();
                                                    }

                                                    // Play tick only if second has actually changed
                                                    if (currentSecond > this.lastTickSecond) {
                                                        window.playTimerTick ? window.playTimerTick(1000, 0.05) : null;
                                                        this.lastTickSecond = currentSecond;
                                                    }
                                                } else if (this.state.status === 'countdown' && this.state.countdown_end_ms) {
                                                    let remaining = this.state.countdown_end_ms - (Date.now() + this.offset);
                                                    this.countdown = remaining > 0 ? Math.ceil(remaining / 1000) : 0;
                                                    this.time = this.state.elapsed_ms || 0;
                                                    if (remaining <= 0 && !this.starting) {
                                                        this.starting = true;
                                                        $wire.startTimer().then(() => { this.starting = false; });
                                                    }
                                                    this.lastTickSecond = Math.floor(this.time / 1000);
                                                } else {
                                                    this.countdown = 0;
                                                    this.time = this.state.elapsed_ms || 0;
                                                    this.lastTickSecond = Math.floor(this.time / 1000);
                                                }
                                            }, 30);
                                            this.syncInterval = setInterval(() => { this.sync(); }, 300);
                                        },
                                        start() {
                                            if (!this.running && this.countdown === 0) {
                                                $wire.startCountdown();
                                            }
                                        },
                                        pause() { $wire.pauseTimer(); },
                                        stop() { $wire.stopTimer(); },
                                        finish() {
                                            let capturedTime = this.time;
                                            $wire.pauseTimer();
                                            Swal.fire({
                                                title: 'Apakah anda yakin?',
                                                html: '<p>Pertandingan akan ditandai <b>Selesai</b>.<br>Panggilan akan ditutup / dinonaktifkan.</p>',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Ya, Selesai!',
                                                cancelButtonText: 'Batal',
                                                confirmButtonColor: '#2563eb',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.finishMatch();
                                                } else {
                                                    $wire.startTimer();
                                                }
                                            });
                                        }
                                    }" class="flex items-center gap-4 bg-amber-50/50 border border-amber-200/60 px-4 py-2 rounded-2xl">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-stopwatch text-amber-500"></i>
                                        <div class="text-xl font-black font-mono tracking-wider min-w-[60px]" :class="countdown > 0 ? 'text-orange-500' : 'text-amber-700'">
                                            <span x-show="countdown > 0" x-text="formatCountdown()"></span>
                                            <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button x-show="!running && countdown === 0" @click="start()" class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors shadow-sm" title="Start Timer"><i class="fas fa-play text-xs"></i></button>
                                        <button x-show="running" @click="pause()" class="w-8 h-8 flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors shadow-sm" title="Pause Timer"><i class="fas fa-pause text-xs"></i></button>
                                        <button @click="stop()" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg transition-colors shadow-sm" title="Stop & Reset"><i class="fas fa-stop text-xs"></i></button>
                                        <button x-show="running || time > 0" @click="finish()" class="h-8 px-3 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors shadow-sm ml-1" title="Selesai & Tutup">
                                            <i class="fas fa-flag-checkered text-xs mr-1"></i><span class="text-[11px] font-black uppercase tracking-wider">Selesai</span>
                                        </button>
                                    </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button wire:click="resetDetailedScoring"
                                        wire:confirm="Reset semua nilai di panel ini ke nol?"
                                        class="h-12 px-4 rounded-2xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-[15px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2">
                                    <i class="fas fa-undo"></i> Reset Nilai
                                </button>
                                <button wire:click="$set('showModal', false)" class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition-all active:scale-95">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Advanced Scoring Table --}}
                    <div class="overflow-hidden border-2 border-slate-100 rounded-3xl mb-8 shadow-sm">
                        <div class="grid grid-cols-2">
                            <div class="bg-rose-700 text-white py-2 text-center text-[15px] font-black uppercase tracking-widest border-r border-white/20">Pita Merah (AKA)</div>
                            <div class="bg-slate-800 text-white py-2 text-center text-[15px] font-black uppercase tracking-widest">Pita Putih (SHIRO)</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-[13px] border-collapse">
                                <thead class="bg-slate-700 text-white uppercase font-black tracking-tighter">
                                    <tr>
                                        <th class="px-4 py-2 border-r border-white/10">Keputusan</th>
                                        <th class="px-4 py-2 border-r border-white/10">Keterangan</th>
                                        <th class="px-4 py-2 border-r border-white/10">Nilai</th>
                                        <th class="px-4 py-2 border-r border-white/10">Jml</th>
                                        <th class="px-4 py-2 border-r border-slate-200"></th>
                                        <th class="px-4 py-2 border-r border-white/10">Keputusan</th>
                                        <th class="px-4 py-2 border-r border-white/10">Keterangan</th>
                                        <th class="px-4 py-2 border-r border-white/10">Nilai</th>
                                        <th class="px-4 py-2 border-r border-white/10">Jml</th>
                                        <th class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-bold text-slate-800 text-center">
                                    @php
                                        $categories = [
                                            ['label' => 'PERINGATAN', 'desc' => 'Mujoken Kachi', 'val' => 15, 'key' => 'mujoken_kachi'],
                                            ['label' => '1.', 'desc' => 'Ippon', 'val' => 10, 'key' => 'ippon'],
                                            ['label' => '2.', 'desc' => 'Waza Ari', 'val' => 5, 'key' => 'waza_ari'],
                                            ['label' => '3.', 'desc' => 'Hasil Batsu 5', 'val' => 5, 'key' => 'hasil_batsu_5'],
                                            ['label' => '4.', 'desc' => 'Hasil Batsu 10', 'val' => 10, 'key' => 'hasil_batsu_10'],
                                            ['label' => '5.', 'desc' => 'Yusei Kachi', 'val' => 5, 'key' => 'yusei_kachi'],
                                        ];
                                    @endphp
                                    @foreach($categories as $cat)
                                        <tr>
                                            {{-- AKA --}}
                                            <td class="px-4 py-2 border-r border-slate-100 uppercase tracking-tighter">{{ $cat['label'] }}</td>
                                            <td class="px-4 py-2 border-r border-slate-100 text-left">{{ $cat['desc'] }}</td>
                                            <td class="px-4 py-2 border-r border-slate-100">{{ $cat['val'] }}</td>
                                            <td class="px-4 py-2 border-r border-slate-100 font-black {{ str_contains($cat['key'], 'batsu') ? 'text-rose-600' : '' }}">
                                                {{ str_contains($cat['key'], 'batsu') ? '-' : '' }}{{ $cat['val'] * $scoringAka[$cat['key']] }}
                                            </td>
                                            <td class="px-4 py-2 border-r border-slate-200 whitespace-nowrap">
                                                <div class="inline-flex rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                                    <button type="button" wire:click="updateScore('aka', '{{ $cat['key'] }}', -1)" class="px-2 py-1 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 transition-colors"><i class="fas fa-minus text-[11px]"></i></button>
                                                    <span class="px-3 py-1 bg-white min-w-[30px] font-black text-slate-800">{{ $scoringAka[$cat['key']] }}</span>
                                                    <button type="button" wire:click="updateScore('aka', '{{ $cat['key'] }}', 1)" class="px-2 py-1 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-600 transition-colors"><i class="fas fa-plus text-[11px]"></i></button>
                                                </div>
                                            </td>
                                            {{-- SHIRO --}}
                                            <td class="px-4 py-2 border-r border-slate-100 uppercase tracking-tighter">{{ $cat['label'] }}</td>
                                            <td class="px-4 py-2 border-r border-slate-100 text-left">{{ $cat['desc'] }}</td>
                                            <td class="px-4 py-2 border-r border-slate-100">{{ $cat['val'] }}</td>
                                            <td class="px-4 py-2 border-r border-slate-100 font-black {{ str_contains($cat['key'], 'batsu') ? 'text-rose-600' : '' }}">
                                                {{ str_contains($cat['key'], 'batsu') ? '-' : '' }}{{ $cat['val'] * $scoringShiro[$cat['key']] }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <div class="inline-flex rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                                    <button type="button" wire:click="updateScore('shiro', '{{ $cat['key'] }}', -1)" class="px-2 py-1 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 transition-colors"><i class="fas fa-minus text-[11px]"></i></button>
                                                    <span class="px-3 py-1 bg-white min-w-[30px] font-black text-slate-800">{{ $scoringShiro[$cat['key']] }}</span>
                                                    <button type="button" wire:click="updateScore('shiro', '{{ $cat['key'] }}', 1)" class="px-2 py-1 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-600 transition-colors"><i class="fas fa-plus text-[11px]"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-slate-50 text-[15px] font-black uppercase">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right">Total Merah</td>
                                        <td class="px-2 py-3 border-r border-slate-200 text-rose-600">{{ $scoreRed }}</td>
                                        <td class="border-r border-slate-200"></td>
                                        <td colspan="3" class="px-4 py-3 text-right">Total Putih</td>
                                        <td class="px-2 py-3 text-blue-600">{{ $scoreBlue }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button
                            wire:click="submitScoring"
                            class="w-full py-4 bg-teal-500 hover:bg-teal-600 text-white text-[15px] font-black uppercase tracking-widest rounded-3xl shadow-lg shadow-teal-500/20 transition-all active:scale-95 flex flex-col items-center justify-center gap-0.5"
                        >
                            <div class="flex items-center justify-center gap-2">
                                <i class="fas fa-check-double text-lg"></i> SAH (SIMPAN PENILAIAN)
                            </div>
                            <span class="text-[13px] font-bold opacity-80 normal-case">Tentukan pemenang berdasarkan akumulasi poin di atas</span>
                        </button>

                        <button wire:click="$set('showModal', false)" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-black text-[15px] uppercase tracking-widest rounded-2xl transition-all">
                            TUTUP PANEL
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
