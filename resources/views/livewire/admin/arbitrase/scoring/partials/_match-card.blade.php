@php
    $a1 = $match['athlete1'] ?? null;
    $a2 = $match['athlete2'] ?? null;
    $winnerSlot = $match['winner'] ?? null;
    $hasBothAthletes = $a1 && $a2;
    $hasSomeAthlete = $a1 || $a2;

    $schemeBorder = match($colorScheme) {
        'orange' => 'border-orange-200',
        'amber'  => 'border-amber-200',
        default  => 'border-indigo-200',
    };
    $schemeDone = match($colorScheme) {
        'orange' => 'border-orange-400 shadow-orange-500/10',
        'amber'  => 'border-amber-400 shadow-amber-500/10',
        default  => 'border-indigo-400 shadow-indigo-500/10',
    };
    $schemeActive = match($colorScheme) {
        'orange' => 'border-orange-500 ring-2 ring-orange-300 ring-offset-1',
        'amber'  => 'border-amber-500 ring-2 ring-amber-300 ring-offset-1',
        default  => 'border-indigo-500 ring-2 ring-indigo-300 ring-offset-1',
    };
    $schemeCallBtn = match($colorScheme) {
        'orange' => 'bg-white border border-orange-200 text-orange-500 hover:bg-orange-500 hover:text-white hover:border-orange-500',
        'amber'  => 'bg-white border border-amber-200 text-amber-600 hover:bg-amber-500 hover:text-white',
        default  => 'bg-white border border-slate-200 text-slate-900 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600',
    };
    $schemeCallBtnActive = match($colorScheme) {
        'orange' => 'bg-orange-500 text-white shadow-md shadow-orange-500/30',
        'amber'  => 'bg-amber-500 text-white shadow-md shadow-amber-500/30',
        default  => 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30',
    };
@endphp

<div class="relative w-64">
    {{-- Match label badge --}}
    <div class="absolute -top-3 left-3 z-10 px-1.5 py-0.5 bg-slate-100 text-slate-800 text-[15px] font-black rounded border border-slate-200 uppercase tracking-wide">
        {{ $matchLabel }}
    </div>

    {{-- LIVE badge --}}
    @if($isActive)
        <div class="absolute -top-3 right-3 z-10 px-1.5 py-0.5 bg-amber-500 text-white text-[15px] font-black rounded uppercase animate-pulse">
            <i class="fas fa-broadcast-tower mr-0.5"></i>LIVE
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white rounded-xl border-2
        {{ $isDone ? $schemeDone . ' shadow-lg' : ($isActive ? $schemeActive : $schemeBorder . ' shadow-sm') }}
        overflow-hidden transition-all">

        @if(!$hasSomeAthlete)
            {{-- Empty / TBD --}}
            <div class="px-3 py-6 flex flex-col items-center justify-center gap-1">
                <i class="fas fa-clock text-slate-200 text-xl"></i>
                <span class="text-[15px] font-bold text-slate-300 uppercase tracking-wide">Menunggu...</span>
            </div>
        @else
            {{-- Athlete 1 (Merah/AKA) --}}
            <div class="px-3 py-2.5 border-b border-slate-100 flex items-center gap-2
                {{ $isDone && $winnerSlot === 'athlete1' ? 'bg-rose-50/60' : '' }}">
                <div class="w-1 h-8 rounded-full flex-shrink-0
                    {{ $isDone && $winnerSlot === 'athlete1' ? 'bg-rose-500' : 'bg-rose-200' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a1)
                        <div class="text-[15px] font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerSlot === 'athlete1' ? 'text-rose-700' : 'text-black' }}">
                            {{ $a1['name'] }}
                        </div>
                        @if($a1['contingent'] ?? null)
                            <div class="text-[15px] text-slate-800 truncate">{{ $a1['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-[15px] text-slate-300 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerSlot === 'athlete1')
                    <span class="text-[15px] font-black bg-rose-500 text-white px-1 py-0.5 rounded flex-shrink-0">W</span>
                @endif
            </div>

            {{-- VS --}}
            <div class="py-1 bg-slate-50 flex items-center justify-center text-[15px] font-black text-slate-300 tracking-widest uppercase">vs</div>

            {{-- Athlete 2 (Putih/SHIRO) --}}
            <div class="px-3 py-2.5 flex items-center gap-2
                {{ $isDone && $winnerSlot === 'athlete2' ? 'bg-indigo-50/60' : '' }}">
                <div class="w-1 h-8 rounded-full flex-shrink-0
                    {{ $isDone && $winnerSlot === 'athlete2' ? 'bg-indigo-500' : 'bg-indigo-200' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a2)
                        <div class="text-[15px] font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerSlot === 'athlete2' ? 'text-indigo-700' : 'text-black' }}">
                            {{ $a2['name'] }}
                        </div>
                        @if($a2['contingent'] ?? null)
                            <div class="text-[15px] text-slate-800 truncate">{{ $a2['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-[15px] text-slate-300 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerSlot === 'athlete2')
                    <span class="text-[15px] font-black bg-indigo-500 text-white px-1 py-0.5 rounded flex-shrink-0">W</span>
                @endif
            </div>

            {{-- Action footer --}}
            @if($match['is_bye'] ?? false)
                <div class="border-t border-emerald-100 bg-emerald-50/40 px-3 py-1.5 flex items-center justify-center">
                    <span class="text-[15px] font-black text-emerald-500 uppercase tracking-widest"><i class="fas fa-forward mr-1"></i>Lolos Otomatis</span>
                </div>
            @elseif($hasBothAthletes && !$isDone)
                <div class="border-t border-slate-100 bg-slate-50/60 px-3 py-2 flex flex-col gap-2">
                    <div class="flex items-center justify-end gap-1.5">
                        @if(!$isActive)
                            <button
                                wire:click="callMatch('{{ $bracket }}', {{ $roundIdx }}, {{ $matchIdx }})"
                                class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide transition-all {{ $schemeCallBtn }}"
                                title="Panggil ke layar Wasit"
                            >
                                <i class="fas fa-bullhorn text-[15px]"></i> Panggil
                            </button>
                        @else
                            <div class="flex items-center gap-1.5">
                                <button
                                    wire:click="callMatch('{{ $bracket }}', {{ $roundIdx }}, {{ $matchIdx }})"
                                    class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-amber-100 text-amber-700 hover:bg-amber-500 hover:text-white transition-all border border-amber-200"
                                    title="Panggil Ulang"
                                >
                                    <i class="fas fa-redo text-[15px]"></i>
                                </button>
                                <button
                                    wire:click="dismissMatch()"
                                    class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all border border-rose-100"
                                    title="Tutup Panggilan"
                                >
                                    <i class="fas fa-times text-[15px]"></i>
                                </button>
                                <button
                                    wire:click="openMatchModal('{{ $bracket }}', {{ $roundIdx }}, {{ $matchIdx }})"
                                    class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-slate-900 text-white hover:bg-orange-600 transition-all shadow-sm"
                                    title="Input Skor"
                                >
                                    <i class="fas fa-edit text-[15px]"></i> Skor
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($isActive)
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
                                formatCountdown() {
                                    if (this.countdown === 2) return 'Siap';
                                    return '';
                                },
                                async sync() {
                                    let state = await $wire.getTimerState();
                                    if (!state) return;
                                    
                                    let oldCountdown = this.countdown;

                                    if (state.status === 'running') {
                                        this.running = true;
                                        this.countdown = 0;
                                        this.time = state.elapsed_ms + (Date.now() - state.started_at_ms);
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

                                    // Trigger Countdown Voice
                                    if (this.countdown > 0 && this.countdown !== oldCountdown) {
                                        if (this.countdown === 2) {
                                            window.speakCountdown('Siap');
                                        }
                                        
                                        // Play start buzzer at countdown 1
                                        if (this.countdown === 1) {
                                            window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
                                        }
                                    }
                                },
                                init() {
                                    this.sync();
                                    this.interpolInterval = setInterval(() => { 
                                        if (this.running) {
                                            this.time += 30; 
                                            let currentSecond = Math.floor(this.time / 1000);
                                            
                                            // Interval buzzers: 90s and 120s
                                            if (currentSecond === 90 && !this.playedIntervals.has(90)) {
                                                window.playBuzzer('/music/freesound_community-buzzerwav-14908.mp3');
                                                this.playedIntervals.add(90);
                                            }
                                            if (currentSecond === 120 && !this.playedIntervals.has(120)) {
                                                window.playBuzzer('/music/freesound_community-buzzerwav-14908.mp3');
                                                this.playedIntervals.add(120);
                                            }

                                            if (currentSecond > this.lastTickSecond) {
                                                window.playTimerTick(1000, 0.05);
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
                            }" class="flex items-center justify-between border-t border-orange-200/60 pt-2">
                            <div class="flex items-center gap-1.5">
                                <button @click="window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3')" class="w-5 h-5 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-400 rounded-full transition-colors mr-1" title="Test Suara">
                                    <i class="fas fa-volume-up text-[10px]"></i>
                                </button>
                                <i class="fas fa-stopwatch text-orange-500"></i>
                                <div class="text-[15px] font-black font-mono tracking-wider" :class="countdown > 0 ? 'text-amber-500' : 'text-orange-700'">
                                    <span x-show="countdown > 0" x-text="formatCountdown()"></span>
                                    <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <button x-show="!running && countdown === 0" @click="start()" class="w-7 h-7 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-md transition-colors" title="Start Timer"><i class="fas fa-play text-[10px]"></i></button>
                                <button x-show="running" @click="pause()" class="w-7 h-7 flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors" title="Pause Timer"><i class="fas fa-pause text-[10px]"></i></button>
                                <button @click="stop()" class="w-7 h-7 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-md transition-colors" title="Stop & Reset"><i class="fas fa-stop text-[10px]"></i></button>
                                <button x-show="running || time > 0" @click="finish()" class="h-7 px-2 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors ml-1" title="Selesai & Tutup">
                                    <i class="fas fa-flag-checkered text-[9px] mr-1"></i><span class="text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @elseif($isDone)
                <div class="border-t border-slate-100 bg-slate-50/40 px-3 py-1.5 flex items-center justify-center">
                    <span class="text-[15px] font-black text-emerald-500 uppercase tracking-widest"><i class="fas fa-check mr-1"></i>Selesai</span>
                </div>
            @endif

        @endif
    </div>
</div>
