<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 p-4 md:p-6" x-data="{ round: 1 }">
    <div class="max-w-[1500px] mx-auto space-y-4">

        {{-- ====== HEADER ====== --}}
        <div class="text-center py-5">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tight">DAFTAR KOMPILASI NILAI</h1>
            <div class="inline-block mt-2 px-5 py-1.5 bg-indigo-600 text-white text-[15px] font-black uppercase tracking-widest rounded-full shadow-md shadow-indigo-300/50">
                {{ strtoupper($matchNumber->name) }}
            </div>
        </div>

        {{-- ====== ROUND INDICATOR ====== --}}
        <div class="flex items-center justify-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 rounded-full text-[15px] font-black uppercase tracking-widest
                {{ $currentRound === 'Penyisihan' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-300/50' : 'bg-slate-100 text-slate-800' }}">
                <i class="fas fa-filter text-[15px]"></i> 1. Penyisihan
            </div>
            <i class="fas fa-chevron-right text-slate-300 text-[15px]"></i>
            <div class="flex items-center gap-2 px-4 py-2 rounded-full text-[15px] font-black uppercase tracking-widest
                {{ $currentRound === 'Final' ? 'bg-amber-500 text-white shadow-md shadow-amber-300/50' : 'bg-slate-100 text-slate-800' }}">
                <i class="fas fa-trophy text-[15px]"></i> 2. Final
            </div>
        </div>

        {{-- ====== POOL TABS ====== --}}
        @if(isset($availablePools) && $availablePools->count() > 1)
            <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                @foreach($availablePools as $p)
                    <button wire:click="setPool({{ $p->id }})" class="px-5 py-2 rounded-full text-[15px] font-black uppercase tracking-widest transition-all
                        {{ $selectedPoolId === $p->id ? 'bg-indigo-600 text-white shadow-md shadow-indigo-300/50' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                        {{ $p->name }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- ====== INFO BAR ====== --}}
        @php
            $drawing = $firstDrawing;
            $sessionDate = $drawing?->sessionTime?->date ?? now();
            $courtOrder = $drawing?->court?->order ?? '-';
            $poolName = $drawing?->pool?->name ?? $drawing?->metadata['pool'] ?? '-';
            $round = $drawing?->round ?? '-';
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="flex items-center gap-2 border border-slate-200 rounded-xl px-3 py-2">
                    <i class="fas fa-calendar text-slate-800 text-[15px]"></i>
                    <span class="text-[15px] font-bold text-slate-800 uppercase">Hari :</span>
                    <span class="text-[15px] font-black text-black">{{ \Carbon\Carbon::parse($sessionDate)->translatedFormat('l') }}</span>
                </div>
                <div class="flex items-center gap-2 border border-slate-200 rounded-xl px-3 py-2">
                    <i class="fas fa-calendar-day text-slate-800 text-[15px]"></i>
                    <span class="text-[15px] font-bold text-slate-800 uppercase">Tanggal :</span>
                    <span class="text-[15px] font-black text-black">{{ \Carbon\Carbon::parse($sessionDate)->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2 border border-slate-200 rounded-xl px-3 py-2">
                    <i class="fas fa-layer-group text-slate-800 text-[15px]"></i>
                    <span class="text-[15px] font-bold text-slate-800 uppercase">Tingkat :</span>
                    <span class="text-[15px] font-black text-black">{{ $matchNumber->ageGroup?->name ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 border border-slate-200 rounded-xl px-3 py-2">
                    <i class="fas fa-water text-slate-800 text-[15px]"></i>
                    <span class="text-[15px] font-bold text-slate-800 uppercase">Pool :</span>
                    <span class="text-[15px] font-black text-black">{{ $poolName }}</span>
                </div>
                <div class="flex items-center gap-2 border border-slate-200 rounded-xl px-3 py-2">
                    <i class="fas fa-map-marker-alt text-slate-800 text-[15px]"></i>
                    <span class="text-[15px] font-bold text-slate-800 uppercase">Court :</span>
                    <span class="text-[15px] font-black text-black">{{ $courtOrder }}</span>
                </div>
            </div>
        </div>

        {{-- ====== LEGEND + ACTIONS BAR ====== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-4 py-3 flex flex-wrap items-center gap-x-6 gap-y-2 justify-between">
            <div class="flex flex-wrap items-center gap-4">
                @foreach(['I','II','III','IV','V'] as $wi)
                    @php
                        $wColors = ['I'=>'bg-blue-500','II'=>'bg-emerald-500','III'=>'bg-amber-500','IV'=>'bg-purple-500','V'=>'bg-rose-500'];
                    @endphp
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded-full {{ $wColors[$wi] }}"></div>
                        <span class="text-[15px] font-bold text-slate-900">Wasit {{ $wi }}</span>
                    </div>
                @endforeach
                <div class="flex items-center gap-1.5">
                    <div class="w-8 h-4 bg-rose-100 border border-rose-200 rounded text-center text-[15px] font-black text-rose-400 line-through">75</div>
                    <span class="text-[15px] font-bold text-slate-900">Dicoret (Tertinggi & Terendah)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-8 h-4 bg-emerald-50 border border-emerald-200 rounded text-center text-[15px] font-black text-emerald-600">78</div>
                    <span class="text-[15px] font-bold text-slate-900">Dihitung (3 Nilai Tengah)</span>
                </div>
                <div class="px-3 py-1 bg-amber-500 text-white rounded-lg text-[15px] font-black uppercase tracking-wide">
                    <i class="fas fa-calculator mr-1"></i> Rumus: Jumlah 3 nilai tengah
                </div>
            </div>
            <div class="flex items-center gap-3">


                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-[15px] font-black uppercase tracking-wide transition-all h-[42px]">
                    <i class="fas fa-print text-[15px]"></i> Cetak
                </button>
            </div>
        </div>

        {{-- ====== TIE ALERT + ADVANCE BAR ====== --}}
        @if(count($tiedIds) > 0)
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-[15px] font-black text-rose-700 uppercase tracking-tight">Nilai Seri Terdeteksi!</p>
                        <p class="text-[15px] text-rose-500 mt-0.5">{{ count($tiedIds) }} peserta memiliki nilai yang sama di posisi ambang batas lolos. Lakukan Tanding Ulang sebelum melanjutkan ke Final.</p>
                    </div>
                </div>
                <button
                    wire:click="requestTiebreak({{ json_encode($tiedIds) }})"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[15px] font-black uppercase tracking-wide transition-all active:scale-95 shadow-sm"
                >
                    <i class="fas fa-redo"></i> Tanding Ulang
                </button>
            </div>
        @elseif($currentRound === 'Penyisihan')
            @php $hasAllScores = $registrations->every(fn($r) => $r['score'] !== null); @endphp
            @if($hasAllScores && $registrations->count() > 0)
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-[15px] font-black text-emerald-700 uppercase tracking-tight">Penyisihan Selesai!</p>
                            <p class="text-[15px] text-emerald-600 mt-0.5">Semua peserta telah dinilai. Tidak ada nilai seri di posisi kritis. Siap loloskan ke Final.</p>
                        </div>
                    </div>
                    <button
                        wire:click="advanceToFinal()"
                        class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[15px] font-black uppercase tracking-wide transition-all active:scale-95 shadow-md shadow-emerald-500/20"
                    >
                        <i class="fas fa-arrow-right"></i> Loloskan ke Final
                    </button>
                </div>
            @endif
        @endif

        {{-- ====== MAIN TABLE ====== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-800 text-white">
                        <tr class="bg-slate-900 text-white">
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">NO.</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">NAMA PESERTA</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">KONTINGEN</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">TINGKAT</th>
                            <th colspan="5" class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">PENILAIAN WASIT</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">NILAI AWAL<br><span class="text-[15px] font-medium text-slate-800">(3 nilai tengah)</span></th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">WAKTU</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">DENDA</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">NILAI AKHIR</th>
                            @if($currentRound === 'Final')
                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center bg-indigo-900">NILAI PENYISIHAN</th>
                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center bg-emerald-900">TOTAL AKUMULASI</th>
                            @endif
                        </tr>
                        <tr class="bg-slate-800 text-slate-800 text-[15px]">
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            @foreach(['I','II','III','IV','V'] as $w)
                                @php $wColors2 = ['I'=>'bg-blue-500','II'=>'bg-emerald-500','III'=>'bg-amber-500','IV'=>'bg-purple-500','V'=>'bg-rose-500']; @endphp
                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full {{ $wColors2[$w] }} inline-block"></span> {{ $w }}
                                    </span>
                                </th>
                            @endforeach
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"></th>
                            @if($currentRound === 'Final')
                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap bg-indigo-900/50"></th>
                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap bg-emerald-900/50"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($registrations as $no => $item)
                            @php
                                $s = $item['score'];
                                $judgeVals = $s ? [
                                    1 => (float)($s->judge_1 ?? 0),
                                    2 => (float)($s->judge_2 ?? 0),
                                    3 => (float)($s->judge_3 ?? 0),
                                    4 => (float)($s->judge_4 ?? 0),
                                    5 => (float)($s->judge_5 ?? 0),
                                ] : [1=>0,2=>0,3=>0,4=>0,5=>0];

                                // Find min and max indices (one each)
                                $sortedVals = $judgeVals;
                                asort($sortedVals);
                                $sortedKeys = array_keys($sortedVals);
                                $minKey = $sortedKeys[0];
                                $maxKey = $sortedKeys[4];

                                $nilaiAwal = $s?->total_score ?? 0;
                                $denda = 0;
                                $nilaiAkhir = $nilaiAwal - $denda;

                                $isActive = $matchNumber->active_registration_id == $item['id'];
                            @endphp
                            <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                <td class="px-3 py-3 text-center font-black text-slate-900 border-r border-slate-100">{{ $item['sequence_number'] ?? ($no + 1) }}</td>
                                <td class="px-4 py-3 border-r border-slate-100">
                                    <div class="flex flex-col">
                                        <span class="font-black text-slate-800 text-[15px] uppercase tracking-tight">
                                            @foreach($item['athletes'] as $athlete)
                                                {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                            @endforeach
                                        </span>
                                        @if($isActive)
                                            <span class="text-[15px] font-black text-indigo-500 uppercase tracking-widest mt-0.5"><i class="fas fa-broadcast-tower animate-pulse mr-1"></i>TAMPIL DI WASIT</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center border-r border-slate-100 text-black font-bold">{{ $item['contingent']?->name ?? '-' }}</td>
                                <td class="px-3 py-3 text-center border-r border-slate-100 text-slate-900 font-bold">{{ $matchNumber->ageGroup?->name ?? '-' }}</td>

                                {{-- Judge Scores with strikethrough logic --}}
                                @foreach([1,2,3,4,5] as $j)
                                    @php
                                        $val = $judgeVals[$j];
                                        $isMin = ($j === $minKey && $s);
                                        $isMax = ($j === $maxKey && $s);
                                        $isCrossed = $isMin || $isMax;
                                    @endphp
                                    <td class="px-2 py-3 text-center border-r border-slate-100">
                                        @if($s && $val > 0)
                                            <span class="inline-flex items-center justify-center min-w-[44px] px-2 py-1 rounded-lg text-[15px] font-black
                                                {{ $isCrossed
                                                    ? 'bg-rose-50 text-rose-300 line-through border border-rose-100'
                                                    : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                                                {{ number_format($val, 1) }}
                                            </span>
                                        @else
                                            <span class="text-slate-200 font-bold">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="px-3 py-3 text-center border-r border-slate-100">
                                    <span class="font-black text-slate-800 text-[15px]">{{ $nilaiAwal > 0 ? number_format($nilaiAwal, 1) : '-' }}</span>
                                </td>
                                <td class="px-3 py-3 text-center border-r border-slate-100 text-slate-800 font-bold">-</td>
                                <td class="px-3 py-3 text-center border-r border-slate-100 text-slate-800 font-bold">{{ $denda }}</td>
                                <td class="px-3 py-3 text-center border-r border-slate-200">
                                    <span class="font-black text-slate-900 text-[15px]">{{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 1) : '-' }}</span>
                                </td>
                                @if($currentRound === 'Final')
                                    <td class="px-3 py-3 text-center border-r border-slate-200 bg-indigo-50/50">
                                        <span class="font-black text-indigo-700 text-[15px]">{{ isset($item['penyisihan_score']) && $item['penyisihan_score'] ? number_format($item['penyisihan_score']->nilai_akhir, 1) : '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center border-r border-slate-200 bg-emerald-50">
                                        <span class="font-black text-emerald-700 text-lg">{{ $item['accumulated_score'] > 0 ? number_format($item['accumulated_score'], 1) : '-' }}</span>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-6 py-12 text-center text-slate-800 font-medium text-[15px] border-r border-slate-200">Belum ada peserta terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ====== ACTION BUTTONS ====== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-[15px] font-black text-slate-800 uppercase tracking-tight">Panggil Peserta ke Lapangan</h3>
                <span class="text-[15px] font-bold text-slate-800 uppercase">Klik Panggil untuk menampilkan di Layar Wasit & Monitor</span>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($registrations as $no => $item)
                    @php $isActive = $matchNumber->active_registration_id == $item['id']; @endphp
                    <div class="flex flex-col gap-2 p-3 rounded-xl border {{ $isActive ? 'border-indigo-300 bg-indigo-50 shadow-md shadow-indigo-500/10' : 'border-slate-200 bg-white' }} shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full {{ $isActive ? 'bg-indigo-600' : 'bg-slate-100' }} flex items-center justify-center flex-shrink-0">
                                <span class="text-[15px] font-black {{ $isActive ? 'text-white' : 'text-slate-900' }}">{{ $item['sequence_number'] ?? ($no + 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[15px] font-black text-slate-800 truncate uppercase mt-0.5" title="@foreach($item['athletes'] as $athlete){{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}@endforeach">
                                    @foreach($item['athletes'] as $athlete){{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}@endforeach
                                </div>
                                <div class="text-[15px] text-slate-800 font-bold uppercase truncate">{{ $item['contingent']?->name }}</div>
                            </div>
                            <div class="flex gap-1.5 flex-shrink-0">
                                <button wire:click="openScoringModal({{ $item['id'] }})"
                                    class="flex items-center justify-center px-3 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-slate-900 hover:bg-slate-700 text-white shadow-sm transition-all" title="Input Skor Admin">
                                    <i class="fas fa-edit text-[15px]"></i> Skor
                                </button>
                                @if(!$isActive)
                                    <button wire:click="callParticipant({{ $item['id'] }})"
                                        class="flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide transition-all bg-slate-100 hover:bg-amber-500 hover:text-white text-slate-900" title="Panggil ke Wasit & Monitor">
                                        <i class="fas fa-bullhorn text-[15px]"></i> Panggil
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Timer Controls --}}
                        @if($isActive)
                            <div wire:ignore x-data="{
                                    time: 0,
                                    running: false,
                                    countdown: 0,
                                    lastTickSecond: -1,
                                    interpolInterval: null,
                                    syncInterval: null,
                                    registrationId: {{ $item['id'] }},
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
                                            window.speakCountdown(this.formatCountdown());
                                        }
                                    },
                                    init() {
                                        this.sync();
                                        this.interpolInterval = setInterval(() => { 
                                            if (this.running) {
                                                this.time += 30; 
                                                let currentSecond = Math.floor(this.time / 1000);
                                                
                                                // Play tick only if second has actually changed
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
                                            html: '<p>Pertandingan akan ditandai <b>Selesai</b>.<br>Denda waktu akan dihitung otomatis & nilai wasit diakumulasikan.</p>',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Ya, Selesai!',
                                            cancelButtonText: 'Batal',
                                            confirmButtonColor: '#2563eb',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $wire.finishMatch(this.registrationId, capturedTime);
                                            } else {
                                                $wire.startTimer();
                                            }
                                        });
                                    }
                                }" class="flex items-center justify-between border-t border-indigo-200/60 pt-2 mt-1">
                                <div class="flex items-center gap-2">
                                    <button @click="window.playTimerTick(1200, 0.1)" class="w-5 h-5 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-400 rounded-full transition-colors mr-1" title="Test Suara">
                                        <i class="fas fa-volume-up text-[10px]"></i>
                                    </button>
                                    <i class="fas fa-stopwatch text-indigo-500"></i>
                                    <div class="text-lg font-black font-mono tracking-wider" :class="countdown > 0 ? 'text-amber-500' : 'text-indigo-700'">
                                        <span x-show="countdown > 0" x-text="formatCountdown()"></span>
                                        <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button x-show="!running && countdown === 0" @click="start()" class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors shadow-sm" title="Start Timer"><i class="fas fa-play text-sm"></i></button>
                                    <button x-show="running" @click="pause()" class="w-8 h-8 flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors shadow-sm" title="Pause Timer"><i class="fas fa-pause text-sm"></i></button>
                                    <button @click="stop()" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg transition-colors shadow-sm" title="Stop & Reset Timer"><i class="fas fa-stop text-sm"></i></button>
                                    <button x-show="running || time > 0" @click="finish()" class="h-8 px-2 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors shadow-sm ml-1" title="Selesai & Tutup">
                                        <i class="fas fa-flag-checkered text-xs mr-1"></i> <span class="text-xs font-black uppercase tracking-wider">Selesai</span>
                                    </button>
                                    <button @click="pause(); $wire.applyTimerPenalty(time)" class="px-2.5 py-1.5 ml-1 flex items-center gap-1.5 text-[12px] uppercase tracking-wider font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors shadow-sm" title="Hitung Denda Otomatis">
                                        <i class="fas fa-calculator text-indigo-200"></i> Denda
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ====== REKAP PERINGKAT ====== --}}
        @php
            $ranked = $registrations->filter(fn($i) => ($i['score']?->nilai_akhir > 0) || ($currentRound === 'Final' && $i['accumulated_score'] > 0))
                ->sortBy(function($i) use ($currentRound) {
                    if ($currentRound === 'Penyisihan') {
                        return -$i['score']->nilai_akhir; // Highest is best
                    } else {
                        return -$i['accumulated_score']; // Highest accumulated is best
                    }
                })
                ->values();
        @endphp
        @if($ranked->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mt-8">
            <div class="px-5 py-4 bg-gradient-to-r from-amber-500 to-orange-500 flex items-center gap-3">
                <i class="fas fa-trophy text-white text-lg"></i>
                <div>
                    <h3 class="text-[15px] font-black text-white uppercase tracking-tight">REKAP PERINGKAT - {{ strtoupper($matchNumber->name) }}</h3>
                    <p class="text-amber-100 text-[15px] font-bold">{{ min(4, $ranked->count()) }} teratas mendapat highlight</p>
                </div>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">PERINGKAT</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">NO</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">NAMA PESERTA</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">KONTINGEN</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">{{ $currentRound === 'Final' ? 'TOTAL AKUMULASI' : 'NILAI AKHIR' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($ranked as $rno => $item)
                            @php
                                $rankNum = $rno + 1;
                                $medalColors = [
                                    1 => 'bg-amber-50 border-l-4 border-amber-400',
                                    2 => 'bg-slate-50 border-l-4 border-slate-400',
                                    3 => 'bg-orange-50 border-l-4 border-orange-400',
                                    4 => 'bg-blue-50 border-l-4 border-blue-400',
                                ];
                                $rowClass = $medalColors[$rankNum] ?? '';
                                $medals = [1=>'🥇',2=>'🥈',3=>'🥉',4=>'🏅'];
                            @endphp
                            <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                <td class="px-4 py-3 border-r border-slate-200">
                                    <div class="flex items-center gap-2">
                                        @if(isset($medals[$rankNum]))
                                            <span class="text-base">{{ $medals[$rankNum] }}</span>
                                        @endif
                                        <span class="font-black {{ $rankNum <= 4 ? 'text-slate-800' : 'text-slate-800' }}">{{ $rankNum }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-slate-900 border-r border-slate-200">
                                    {{ $registrations->search(fn($r) => $r['id'] == $item['id']) + 1 }}
                                </td>
                                <td class="px-4 py-3 font-black {{ $rankNum <= 4 ? 'text-slate-900' : 'text-slate-900' }} border-r border-slate-200">
                                    @foreach($item['athletes'] as $athlete){{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}@endforeach
                                </td>
                                <td class="px-4 py-3 font-bold {{ $rankNum <= 4 ? 'text-indigo-600' : 'text-slate-900' }} border-r border-slate-200">
                                    {{ $item['contingent']?->name }}
                                </td>
                                <td class="px-4 py-3 text-center font-black text-lg {{ $rankNum === 1 ? 'text-amber-500' : 'text-black' }} border-r border-slate-200">
                                    <div class="flex flex-col items-center">
                                        <span>{{ number_format($currentRound === 'Final' ? $item['accumulated_score'] : optional($item['score'])->nilai_akhir ?? 0, 1) }}</span>
                                        @if($currentRound === 'Final' && isset($item['penyisihan_score']) && $item['penyisihan_score'])
                                            <span class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">
                                                P: {{ number_format($item['penyisihan_score']->nilai_akhir, 1) }} | F: {{ isset($item['score']) && $item['score'] ? number_format($item['score']->nilai_akhir, 1) : '0.0' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ====== FOOTER INFO ====== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-user-tie text-slate-800"></i>
                    <span class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Koordinator Pertandingan</span>
                </div>
                <div class="text-[15px] font-black text-slate-800">-</div>
                <div class="text-[15px] text-slate-800 font-bold mt-1">NIP. -</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-users text-slate-800"></i>
                    <span class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Para Panitera</span>
                </div>
                <div class="text-[15px] text-slate-900 italic">-</div>
            </div>
        </div>

        {{-- ====== FOOTER NOTE ====== --}}
        <div class="text-[15px] text-slate-800 text-right pb-4">
            <span class="text-red-400">* Coret yang tidak perlu</span> &nbsp;|&nbsp;
            <span>** Nilai tertinggi dan terendah dicoret, 3 nilai tengah dijumlahkan</span>
        </div>

    </div>

    {{-- ====== SCORING MODAL ====== --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
            <div class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Input Nilai Juri</h2>
                            <p class="text-[15px] text-slate-900 font-bold uppercase tracking-widest mt-1">Metode: Sum of Middle 3</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-800 hover:text-rose-500 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 mb-10">
                        @for($i=1; $i<=5; $i++)
                            <div class="flex flex-col">
                                <label class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-2 px-1">Wasit {{ $i }}</label>
                                <input
                                    type="number"
                                    step="0.1"
                                    wire:model.live="scores.judge_{{ $i }}"
                                    class="w-full px-4 py-5 bg-slate-50 border border-slate-100 rounded-3xl text-center text-2xl font-black text-slate-800 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all"
                                >
                            </div>
                        @endfor
                    </div>

                    <div class="bg-amber-500 rounded-3xl p-6 text-white flex items-center justify-between shadow-lg shadow-amber-500/20">
                        <div>
                            <p class="text-[15px] font-black uppercase tracking-[0.2em] text-white/70">Total Skor Akhir</p>
                            <p class="text-[15px] text-white/50 italic leading-tight">*Dihitung dari 3 nilai tengah</p>
                        </div>
                        <div class="text-5xl font-black tracking-tighter">
                            @php
                                $raw = array_values($scores);
                                sort($raw);
                                $calculated = count($raw) === 5 ? ($raw[1] + $raw[2] + $raw[3]) : 0;
                            @endphp
                            {{ number_format($calculated, 1) }}
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button
                            wire:click="$set('showModal', false)"
                            class="flex-1 py-4 bg-slate-50 hover:bg-slate-100 text-slate-800 font-black uppercase tracking-widest rounded-2xl transition-all"
                        >
                            Batal
                        </button>
                        <button
                            wire:click="saveScore"
                            class="flex-[2] py-4 bg-slate-900 hover:bg-amber-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-slate-900/10 transition-all active:scale-95"
                        >
                            Simpan Nilai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
