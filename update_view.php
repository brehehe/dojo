<?php

$file = '/var/www/html/smart-perkemi/smart-perkemi.id/resources/views/livewire/admin/new-scoring-randori-index.blade.php';
$content = file_get_contents($file);

// 1. Remove the modal block completely
$modalStart = strpos($content, '{{-- ====== RESULT MODAL ====== --}}');
$modalEnd = strpos($content, "</div>\n@push('scripts')");
if ($modalStart !== false && $modalEnd !== false) {
    $content = substr($content, 0, $modalStart).substr($content, $modalEnd);
}

// 2. Replace the timer block with the new inline panel
$timerStart = strpos($content, '{{-- TIMER --}}');
$timerEnd = strpos($content, '@if(empty($ubRounds))');

if ($timerStart !== false && $timerEnd !== false) {
    $inlinePanel = <<<'HTML'
    {{-- SCORING PANEL (INLINE) --}}
    @if($activeMatch)
        <div class="bg-white rounded-3xl w-full shadow-lg relative border border-slate-100 mb-8 mt-4 overflow-hidden">
            <div class="p-5 md:p-6">
                {{-- Header --}}
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-600/20 shrink-0">
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
                    <div class="flex flex-wrap lg:flex-nowrap items-center gap-4">
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
                                                window.speakCountdown ? window.speakCountdown('Siap') : null;
                                            }
                                            
                                            // Play start buzzer at countdown 1
                                            if (this.countdown === 1) {
                                                window.playBuzzer ? window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3') : null;
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
                                                    window.playBuzzer ? window.playBuzzer('/music/freesound_community-buzzerwav-14908.mp3') : null;
                                                    this.playedIntervals.add(90);
                                                }
                                                if (currentSecond === 120 && !this.playedIntervals.has(120)) {
                                                    window.playBuzzer ? window.playBuzzer('/music/freesound_community-buzzerwav-14908.mp3') : null;
                                                    this.playedIntervals.add(120);
                                                }

                                                // Play tick only if second has actually changed
                                                if (currentSecond > this.lastTickSecond) {
                                                    window.playTimerTick ? window.playTimerTick(1000, 0.05) : null;
                                                    this.lastTickSecond = currentSecond;
                                                }
                                            } else {
                                                // Update tracker while paused so it doesn't double-tick on resume
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
                                            title: 'Selesai Pertandingan?',
                                            text: 'Pertandingan akan ditandai Selesai dan Timer ditutup.',
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
                                }" class="flex items-center gap-4 bg-amber-50/50 border border-amber-200/60 px-4 py-2 rounded-2xl w-full lg:w-auto">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-stopwatch text-amber-500"></i>
                                    <div class="text-xl font-black font-mono tracking-wider min-w-[60px]" :class="countdown > 0 ? 'text-orange-500' : 'text-amber-700'">
                                        <span x-show="countdown > 0" x-text="formatCountdown()"></span>
                                        <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 ml-auto lg:ml-0">
                                    <button x-show="!running && countdown === 0" @click="start()" class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors shadow-sm" title="Start Timer"><i class="fas fa-play text-xs"></i></button>
                                    <button x-show="running" @click="pause()" class="w-8 h-8 flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors shadow-sm" title="Pause Timer"><i class="fas fa-pause text-xs"></i></button>
                                    <button @click="stop()" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg transition-colors shadow-sm" title="Stop & Reset"><i class="fas fa-stop text-xs"></i></button>
                                    <button x-show="running || time > 0" @click="finish()" class="h-8 px-3 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors shadow-sm ml-1" title="Selesai & Tutup">
                                        <i class="fas fa-flag-checkered text-xs mr-1"></i><span class="text-[11px] font-black uppercase tracking-wider">Selesai</span>
                                    </button>
                                </div>
                        </div>

                        <div class="flex items-center gap-2 w-full lg:w-auto">
                            <button wire:click="resetDetailedScoring"
                                    wire:confirm="Reset semua nilai di panel ini ke nol?"
                                    class="flex-1 lg:flex-none h-12 px-4 rounded-2xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-[15px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-undo"></i> Reset
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
                        <table class="w-full text-[13px] border-collapse" style="min-width: 800px;">
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
                </div>
            </div>
        </div>
    @else
        <div class="tm-card mb-8">
            <div class="tm-card-head" style="background:var(--ink); color:#fff;">
                <h3 style="color:#fff;"><i class="fas fa-stopwatch" style="color:#f1c40f; margin-right:8px;"></i> Live Match Timer</h3>
            </div>
            <div class="tm-card-body" style="padding: 40px; text-align: center; background:#fdfbf7;">
                <i class="fas fa-play-circle" style="font-size:48px; color:var(--paper2); margin-bottom:16px;"></i>
                <h3 style="color:var(--ink); margin:0;">Belum ada pertandingan yang dipanggil</h3>
                <p style="color:var(--smoke); margin:8px 0 0; font-size:14px;">Silakan klik tombol <b>Panggil</b> pada salah satu match di bagan bawah untuk memulai penilaian dan mengaktifkan timer.</p>
            </div>
        </div>
    @endif

    
HTML;
    $content = substr($content, 0, $timerStart).$inlinePanel.substr($content, $timerEnd);
}

file_put_contents($file, $content);
echo 'View updated.';
