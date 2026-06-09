<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 p-4 md:p-6" x-data="{ round: 1 }">
    {{-- ═══ FIXED RESET BUTTON ═══ --}}
    <div class="fixed bottom-8 right-4 z-[100] md:bottom-10 md:right-6">
        <button wire:click="clearAllCourts" wire:confirm="PERINGATAN: Ini akan mereset status SEMUA lapangan & match yang sedang berjalan menjadi KOSONG. Lanjutkan?"
            class="flex items-center gap-2 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white shadow-2xl shadow-rose-200 rounded-2xl text-[13px] font-black uppercase tracking-widest transition-all active:scale-95 border-2 border-white/20 backdrop-blur-sm">
            <i class="fas fa-eraser text-lg"></i>
            <span class="hidden sm:inline">Reset Semua Lapangan</span>
            <span class="sm:hidden">Reset</span>
        </button>
    </div>
    <div class="max-w-[1500px] mx-auto space-y-4">

        {{-- ====== HEADER ====== --}}
        <div class="text-center py-5">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tight">DAFTAR KOMPILASI NILAI</h1>
            <div class="inline-block mt-2 px-5 py-1.5 bg-indigo-600 text-white text-[15px] font-black uppercase tracking-widest rounded-full shadow-md shadow-indigo-300/50">
                {{ strtoupper($matchNumber->name) }}
            </div>
        </div>

        {{-- ====== ROUND INDICATOR ====== --}}
        <div class="flex flex-wrap items-center justify-center gap-4">
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
            <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                @foreach($availablePools as $p)
                    <button wire:click="setPool({{ $p->id }})" 
                        class="group relative px-6 py-2.5 rounded-2xl text-[14px] font-black uppercase tracking-widest transition-all duration-300
                        {{ $selectedPoolId === $p->id 
                            ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' 
                            : 'bg-white border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600' }}">
                        <span class="relative z-10">{{ $p->name }}</span>
                        @if($selectedPoolId === $p->id)
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 opacity-10 animate-pulse"></div>
                        @endif
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
        <div class="bg-white/70 backdrop-blur-md rounded-[2rem] border border-white shadow-xl shadow-slate-200/50 p-6">
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="group flex flex-col gap-1 p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Hari / Tanggal</span>
                    <div class="flex items-center gap-2">
                        <i class="far fa-calendar-alt text-indigo-500 text-sm"></i>
                        <span class="text-[14px] font-black text-slate-800 uppercase">{{ \Carbon\Carbon::parse($sessionDate)->translatedFormat('l, d M Y') }}</span>
                    </div>
                </div>
                <div class="group flex flex-col gap-1 p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kategori Pertandingan</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-medal text-amber-500 text-sm"></i>
                        <span class="text-[14px] font-black text-slate-800 uppercase truncate">{{ $matchNumber->name }}</span>
                    </div>
                </div>
                <div class="group flex flex-col gap-1 p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tingkat / Golongan</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-layer-group text-blue-500 text-sm"></i>
                        <span class="text-[14px] font-black text-slate-800 uppercase truncate">{{ $matchNumber->ageGroup?->name ?? '-' }}</span>
                    </div>
                </div>
                <div class="group flex flex-col gap-1 p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pool</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-door-open text-emerald-500 text-sm"></i>
                        <span class="text-[14px] font-black text-slate-800 uppercase">{{ $poolName }}</span>
                    </div>
                </div>
                <div class="group flex flex-col gap-1 p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Lapangan (Court)</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-vector-square text-rose-500 text-sm"></i>
                        <span class="text-[14px] font-black text-slate-800 uppercase">{{ $courtOrder }}</span>
                    </div>
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


            <div class="flex items-center gap-3">
                <button x-data @click="$dispatch('open-penalty-info')" class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[15px] font-black uppercase tracking-wide transition-all h-[42px]">
                    <i class="fas fa-info-circle text-[15px]"></i> Info Denda
                </button>
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
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900">
                            <th class="sticky left-0 z-20 bg-slate-900 px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center border-r border-slate-800">No</th>
                            <th class="sticky left-[72px] z-20 bg-slate-900 px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] border-r border-slate-800">Nama Peserta</th>
                            <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center border-r border-slate-800">Kontingen</th>
                            <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center border-r border-slate-800">Tingkat</th>
                            <th colspan="5" class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center border-r border-slate-800">Penilaian Wasit</th>
                            <th class="px-6 py-5 text-[11px] font-black text-slate-200 uppercase tracking-[0.2em] text-center border-r border-slate-800 bg-slate-800/50">Nilai Awal</th>
                            <th class="px-6 py-5 text-[11px] font-black text-indigo-300 uppercase tracking-[0.2em] text-center border-r border-slate-800 bg-indigo-950/30">Nilai Akhir</th>
                            <th class="px-6 py-5 text-[11px] font-black text-slate-300 uppercase tracking-[0.2em] text-center border-r border-slate-800 bg-slate-900/40">Durasi</th>
                            @if($currentRound === 'Final')
                                <th class="px-6 py-5 text-[11px] font-black text-amber-300 uppercase tracking-[0.2em] text-center border-r border-slate-800 bg-amber-950/20">Penyisihan</th>
                                <th class="px-6 py-5 text-[11px] font-black text-emerald-300 uppercase tracking-[0.2em] text-center bg-emerald-950/20">Akumulasi</th>
                            @endif
                        </tr>
                        <tr class="bg-slate-800/50 border-b border-slate-200">
                            <th colspan="4" class="px-6 py-2 border-r border-slate-700/50"></th>
                            @foreach(['I','II','III','IV','V'] as $w)
                                @php $wColors2 = ['I'=>'bg-blue-500','II'=>'bg-emerald-500','III'=>'bg-amber-500','IV'=>'bg-purple-500','V'=>'bg-rose-500']; @endphp
                                <th class="px-2 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center border-r border-slate-700/50">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $wColors2[$w] }}"></div>
                                        <span>Wasit {{ $w }}</span>
                                    </div>
                                </th>
                            @endforeach
                            <th colspan="20" class="bg-slate-900/5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
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

                                asort($judgeVals);
                                $sortedKeys = array_keys($judgeVals);
                                $minKey = $sortedKeys[0];
                                $maxKey = $sortedKeys[4];

                                $nilaiAwal = $s?->total_score ?? 0;
                                $denda = $s?->denda ?? 0;
                                $nilaiAkhir = $s?->nilai_akhir ?? ($nilaiAwal - $denda);

                                $isActive = isset($activeDrawingId) && $activeDrawingId == $item['drawing_id'];
                            @endphp
                            <tr class="group hover:bg-slate-50 transition-all duration-300 {{ $isActive ? 'bg-indigo-50/30' : '' }}">
                                <td class="sticky left-0 z-10 {{ $isActive ? 'bg-indigo-50' : ($loop->even ? 'bg-slate-50' : 'bg-white') }} px-6 py-5 text-center font-black text-slate-400 border-r border-slate-100">{{ $item['sequence_number'] ?? ($no + 1) }}</td>
                                <td class="sticky left-[72px] z-10 {{ $isActive ? 'bg-indigo-50' : ($loop->even ? 'bg-slate-50' : 'bg-white') }} px-6 py-5 border-r border-slate-100">
                                    <div class="flex flex-col">
                                        <span class="font-black text-slate-800 text-[14px] uppercase tracking-tight group-hover:text-indigo-600 transition-colors">
                                            @foreach($item['athletes'] as $athlete)
                                                {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                            @endforeach
                                        </span>
                                        @if($isActive)
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-ping"></div>
                                                <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Active Match</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center border-r border-slate-100">
                                    <span class="text-[13px] font-bold text-slate-500 uppercase tracking-wider">{{ $item['contingent']?->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-5 text-center border-r border-slate-100">
                                    <span class="text-[13px] font-bold text-slate-500 uppercase tracking-wider">{{ $matchNumber->ageGroup?->name ?? '-' }}</span>
                                </td>

                                @foreach([1,2,3,4,5] as $j)
                                    @php
                                        $val = $item['score'] ? [
                                            1 => (float)($item['score']->judge_1 ?? 0),
                                            2 => (float)($item['score']->judge_2 ?? 0),
                                            3 => (float)($item['score']->judge_3 ?? 0),
                                            4 => (float)($item['score']->judge_4 ?? 0),
                                            5 => (float)($item['score']->judge_5 ?? 0),
                                        ][$j] : 0;
                                        
                                        // Original unsorted keys to find min/max
                                        $rawVals = $item['score'] ? [
                                            1 => (float)($item['score']->judge_1 ?? 0),
                                            2 => (float)($item['score']->judge_2 ?? 0),
                                            3 => (float)($item['score']->judge_3 ?? 0),
                                            4 => (float)($item['score']->judge_4 ?? 0),
                                            5 => (float)($item['score']->judge_5 ?? 0),
                                        ] : [];
                                        
                                        if($item['score']) {
                                            asort($rawVals);
                                            $keys = array_keys($rawVals);
                                            $isMin = ($j === $keys[0]);
                                            $isMax = ($j === $keys[4]);
                                        } else {
                                            $isMin = $isMax = false;
                                        }
                                    @endphp
                                    <td class="px-2 py-5 text-center border-r border-slate-100">
                                        @if($item['score'] && $val > 0)
                                            <div class="inline-flex flex-col items-center">
                                                <span class="text-[15px] font-black {{ $isMin || $isMax ? 'text-slate-300 line-through' : 'text-slate-800' }}">
                                                    {{ number_format($val, 1) }}
                                                </span>
                                                @if($isMin || $isMax)
                                                    <span class="text-[8px] font-black text-rose-400 uppercase tracking-tighter">Out</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-200 font-bold">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="px-6 py-5 text-center border-r border-slate-100 bg-slate-50/50">
                                    <span class="font-black text-slate-800 text-[15px]">{{ $s ? number_format($nilaiAwal, 1) : '-' }}</span>
                                </td>
                                <td class="px-6 py-5 text-center border-r border-slate-100 bg-indigo-50/30">
                                    <div class="flex flex-col items-center">
                                        <span class="text-lg font-black text-indigo-700 tracking-tighter">{{ $s ? number_format($nilaiAkhir, 1) : '-' }}</span>
                                        @if($denda > 0)
                                            <span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-600 text-[9px] font-black uppercase tracking-tighter mt-1">-{{ number_format($denda, 0) }} Denda</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center border-r border-slate-100">
                                    <span class="font-black text-slate-800 text-[15px]">{{ $s && $s->waktu ? $s->waktu : '-' }}</span>
                                </td>
                                @if($currentRound === 'Final')
                                    <td class="px-6 py-5 text-center border-r border-slate-100 bg-amber-50/30">
                                        <span class="font-black text-amber-700 text-[15px]">{{ isset($item['penyisihan_score']) && $item['penyisihan_score'] ? number_format($item['penyisihan_score']->nilai_akhir, 1) : '-' }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center bg-emerald-50/30">
                                        <span class="font-black text-emerald-700 text-xl tracking-tighter">{{ $item['accumulated_score'] > 0 ? number_format($item['accumulated_score'], 1) : '-' }}</span>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                            <i class="fas fa-users-slash text-2xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold uppercase tracking-widest text-[13px]">Belum ada data peserta</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-2xl shadow-slate-200/50 overflow-hidden mt-12">
            <div class="px-8 py-6 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/20">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">Leaderboard Peringkat</h3>
                        <p class="text-indigo-200 text-[13px] font-bold uppercase tracking-[0.2em]">{{ strtoupper($matchNumber->name) }}</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <span class="px-4 py-1.5 rounded-full bg-white/10 text-white/80 text-[11px] font-black uppercase tracking-[0.2em] border border-white/10">Real-time Standing</span>
                </div>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-8 py-4 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em]">Rank</th>
                            <th class="px-4 py-4 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">No</th>
                            <th class="px-4 py-4 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Peserta</th>
                            <th class="px-4 py-4 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em]">Kontingen</th>
                            <th class="px-8 py-4 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">{{ $currentRound === 'Final' ? 'Total Akumulasi' : 'Nilai Akhir' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($ranked as $rno => $item)
                            @php
                                $rankNum = $rno + 1;
                                $isTop3 = $rankNum <= 3;
                                $medals = [
                                    1 => ['icon' => '🥇', 'color' => 'text-amber-500', 'bg' => 'bg-amber-50'],
                                    2 => ['icon' => '🥈', 'color' => 'text-slate-400', 'bg' => 'bg-slate-50'],
                                    3 => ['icon' => '🥉', 'color' => 'text-orange-400', 'bg' => 'bg-orange-50'],
                                ];
                                $medal = $medals[$rankNum] ?? null;
                            @endphp
                            <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        @if($medal)
                                            <div class="w-10 h-10 rounded-2xl {{ $medal['bg'] }} flex items-center justify-center {{ $medal['color'] }} text-xl shadow-sm">
                                                {{ $medal['icon'] }}
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 font-black">
                                                {{ $rankNum }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-center font-bold text-slate-400">
                                    {{ $registrations->search(fn($r) => $r['id'] == $item['id']) + 1 }}
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-black text-slate-800 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">
                                            @foreach($item['athletes'] as $athlete){{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}@endforeach
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    <span class="text-[13px] font-bold text-slate-500 uppercase tracking-wider">{{ $item['contingent']?->name }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-2xl font-black tracking-tighter {{ $rankNum === 1 ? 'text-indigo-600' : 'text-slate-800' }}">
                                            {{ number_format($currentRound === 'Final' ? $item['accumulated_score'] : optional($item['score'])->nilai_akhir ?? 0, 1) }}
                                        </span>
                                        @if($currentRound === 'Final' && isset($item['penyisihan_score']) && $item['penyisihan_score'])
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-500 text-[10px] font-black uppercase">P: {{ number_format($item['penyisihan_score']->nilai_akhir, 1) }}</span>
                                                <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">F: {{ isset($item['score']) && $item['score'] ? number_format($item['score']->nilai_akhir, 1) : '0.0' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">Generated at {{ now()->format('H:i:s') }}</p>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                        <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Podium Zone</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

     {{-- ====== ACTION BUTTONS ====== --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <i class="fas fa-bullhorn text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Antrian Panggilan Peserta</h3>
                    <p class="text-[13px] text-slate-500 font-bold uppercase tracking-wider">Klik Panggil untuk menampilkan di Monitor Court</p>
                </div>
            </div>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            @foreach($registrations as $no => $item)
                @php $isActive = isset($activeDrawingId) && $activeDrawingId == $item['drawing_id']; @endphp
                <div class="group relative flex flex-col gap-4 p-5 rounded-[2rem] border transition-all duration-500 
                    {{ $isActive 
                        ? 'border-indigo-500 bg-white ring-4 ring-indigo-50 shadow-2xl scale-[1.02] z-10' 
                        : 'border-slate-100 bg-slate-50/50 hover:bg-white hover:border-slate-300 hover:shadow-xl' }}">
                    
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg transition-transform duration-500 group-hover:scale-110 flex-shrink-0
                                {{ $isActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-slate-400 border border-slate-100 shadow-sm' }}">
                                {{ $item['sequence_number'] ?? ($no + 1) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-[15px] font-black text-slate-800 uppercase leading-tight truncate" title="@foreach($item['athletes'] as $athlete){{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}@endforeach">
                                    @foreach($item['athletes'] as $athlete){{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}@endforeach
                                </h4>
                                <div class="flex items-center gap-1.5 mt-1 text-[13px] font-bold text-slate-500 uppercase tracking-wider">
                                    <i class="fas fa-map-marker-alt text-[10px] text-slate-300"></i>
                                    <span class="truncate">{{ $item['contingent']?->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            @if($isActive)
                                <button wire:click="callParticipant({{ $item['id'] }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm border border-amber-100" title="Panggil Ulang">
                                    <i class="fas fa-redo text-sm"></i>
                                </button>
                                <button wire:click="dismissParticipant()" 
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-rose-100" title="Tutup / Lepas dari Wasit">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            @endif
                            <!-- <button wire:click="openScoringModal({{ $item['id'] }})"
                                class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-900 hover:border-slate-900 shadow-sm transition-all" title="Input Skor Admin">
                                <i class="fas fa-edit text-sm"></i>
                            </button> -->
                        </div>
                    </div>

                    @if(!$isActive)
                        <button wire:click="callParticipant({{ $item['drawing_id'] }})"
                            class="w-full py-3.5 rounded-2xl text-[14px] font-black uppercase tracking-[0.2em] transition-all bg-white border border-slate-200 text-slate-800 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 hover:shadow-lg hover:shadow-indigo-200">
                            <i class="fas fa-bullhorn mr-2"></i> Panggil
                        </button>
                    @endif

                    {{-- Timer Controls --}}
                    @if($isActive)
                        <div wire:ignore x-data="{
                                time: 0,
                                running: false,
                                countdown: 0,
                                lastTickSecond: -1,
                                playedIntervals: new Set(),
                                interpolInterval: null,
                                syncInterval: null,
                                drawingId: {{ $item['drawing_id'] }},
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
                                        let wasRunning = this.running;
                                        this.running = true;
                                        this.countdown = 0;
                                        this.time = state.elapsed_ms + (Date.now() - state.started_at_ms);

                                        // Play buzzer when timer newly starts
                                        if (!wasRunning && (!state.elapsed_ms || state.elapsed_ms < 1000)) {
                                            window.playBuzzer ? window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3') : null;
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

                                    // Trigger Countdown Voice
                                    if (this.countdown > 0 && this.countdown !== oldCountdown) {
                                        if (this.countdown === 2) {
                                            window.speakCountdown('Siap');
                                        }
                                    }
                                },
                                init() {
                                    this.sync();
                                    this.interpolInterval = setInterval(() => { 
                                        if (this.running) {
                                            this.time += 30; 
                                            let currentSecond = Math.floor(this.time / 1000);
                                            
                                            let isTandoku = {{ $item['is_group'] ? 'false' : 'true' }};
                                            let buzzerSound = '/music/freesound_community-buzzerwav-14908.mp3';
                                            if (isTandoku) {
                                                if ((currentSecond === 60 && !this.playedIntervals.has(60)) ||
                                                    (currentSecond === 90 && !this.playedIntervals.has(90)) ||
                                                    (currentSecond === 120 && !this.playedIntervals.has(120))) {
                                                    window.playBuzzer ? window.playBuzzer(buzzerSound) : null;
                                                    this.playedIntervals.add(currentSecond);
                                                }
                                            } else {
                                                if ((currentSecond === 90 && !this.playedIntervals.has(90)) ||
                                                    (currentSecond === 120 && !this.playedIntervals.has(120))) {
                                                    window.playBuzzer ? window.playBuzzer(buzzerSound) : null;
                                                    this.playedIntervals.add(currentSecond);
                                                }
                                            }

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
                                        customClass: {
                                            popup: 'rounded-[2rem]',
                                            confirmButton: 'rounded-2xl font-black uppercase tracking-widest px-6 py-3',
                                            cancelButton: 'rounded-2xl font-black uppercase tracking-widest px-6 py-3'
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $wire.finishMatch(this.drawingId, capturedTime);
                                        } else {
                                            $wire.startTimer();
                                        }
                                    });
                                }
                            }" class="bg-indigo-600 rounded-3xl p-5 shadow-inner">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.2em] mb-1">Live Match Timer</span>
                                    <div class="text-3xl font-black font-mono tracking-widest text-white drop-shadow-sm">
                                        <span x-show="countdown > 0" x-text="formatCountdown()" class="text-amber-400"></span>
                                        <span x-show="countdown === 0" x-text="formatTime()">00:00</span>
                                    </div>
                                </div>
                                <button @click="window.playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3')" class="w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-2xl backdrop-blur-md transition-all" title="Test Suara">
                                    <i class="fas fa-volume-up text-sm"></i>
                                </button>
                            </div>
                            <div class="flex items-center gap-2">
                                <button x-show="!running && countdown === 0" @click="start()" class="flex-1 h-12 flex items-center justify-center bg-emerald-400 hover:bg-emerald-500 text-white rounded-2xl transition-all shadow-lg shadow-emerald-900/20" title="Start Timer">
                                    <i class="fas fa-play text-sm mr-2"></i> <span class="text-[13px] font-black uppercase tracking-widest">Mulai</span>
                                </button>
                                <button x-show="running" @click="pause()" class="flex-1 h-12 flex items-center justify-center bg-amber-400 hover:bg-amber-500 text-white rounded-2xl transition-all shadow-lg shadow-amber-900/20" title="Pause Timer">
                                    <i class="fas fa-pause text-sm mr-2"></i> <span class="text-[13px] font-black uppercase tracking-widest">Jeda</span>
                                </button>
                                <button @click="stop()" class="w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-rose-500 text-white rounded-2xl transition-all backdrop-blur-md" title="Stop & Reset Timer">
                                    <i class="fas fa-redo-alt text-sm"></i>
                                </button>
                                <button @click="finish()" class="flex-[1.5] h-12 flex items-center justify-center bg-white text-indigo-700 hover:bg-indigo-50 rounded-2xl transition-all shadow-lg" title="Selesai & Tutup">
                                    <i class="fas fa-flag-checkered text-sm mr-2"></i> <span class="text-[13px] font-black uppercase tracking-widest">Selesai</span>
                                </button>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">
                                    Target Waktu: {{ $item['is_group'] ? '1:30 - 2:00' : '1:30' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        {{-- Penalty Input --}}
                        <div class="bg-rose-50 rounded-3xl p-6 border border-rose-100 shadow-sm shadow-rose-200/20">
                            <label class="text-[15px] font-black text-rose-500 uppercase tracking-widest mb-3 block px-1">
                                <i class="fas fa-minus-circle mr-1"></i> Denda Waktu
                            </label>
                            <input
                                type="number"
                                step="1"
                                wire:model.live="denda"
                                class="w-full px-4 py-4 bg-white border border-rose-200 rounded-2xl text-center text-xl font-black text-rose-600 focus:ring-4 focus:ring-rose-500/10 outline-none transition-all"
                            >
                        </div>

                        {{-- Final Total Display --}}
                        <div class="bg-slate-900 rounded-3xl p-6 text-white flex items-center justify-between shadow-xl shadow-slate-900/20 relative overflow-hidden">
                            <div class="relative z-10">
                                <p class="text-[13px] font-black uppercase tracking-[0.2em] text-slate-400">Nilai Akhir</p>
                                <p class="text-[11px] text-slate-500 italic leading-tight">(3 Tengah - Denda)</p>
                            </div>
                            <div class="text-4xl font-black tracking-tighter relative z-10 text-indigo-400">
                                @php
                                    $raw = array_values($scores);
                                    sort($raw);
                                    $calculated = count($raw) === 5 ? ($raw[1] + $raw[2] + $raw[3]) : 0;
                                    $finalVal = max(0, $calculated - (float)$denda);
                                @endphp
                                {{ number_format($finalVal, 1) }}
                            </div>
                            {{-- Decorative Background Icon --}}
                            <div class="absolute -right-4 -bottom-4 text-white/5 text-6xl">
                                <i class="fas fa-calculator"></i>
                            </div>
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

    {{-- ====== PENALTY INFO MODAL ====== --}}
    <div x-data="{ open: false }" 
         x-on:open-penalty-info.window="open = true"
         x-show="open" 
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
         style="display: none;">
        <div class="bg-white rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300" @click.away="open = false">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600">
                            <i class="fas fa-stopwatch text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Aturan Denda Waktu</h2>
                            <p class="text-[14px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Kategori Embu</p>
                        </div>
                    </div>
                    <button @click="open = false" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-800 hover:text-rose-500 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    {{-- Kategori Beregu --}}
                    <div class="p-5 rounded-3xl bg-indigo-50 border border-indigo-100">
                        <div class="flex items-center gap-2 mb-3 text-indigo-700">
                            <i class="fas fa-users"></i>
                            <span class="text-[15px] font-black uppercase tracking-widest">Beregu / Pasangan (Target: 90s - 120s)</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-2xl border border-indigo-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">50s - 89s</span>
                                <span class="text-rose-500 font-black">-5</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-indigo-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">&lt; 50s</span>
                                <span class="text-rose-500 font-black">-10</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-indigo-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">121s - 135s</span>
                                <span class="text-rose-500 font-black">-5</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-indigo-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">&gt; 135s</span>
                                <span class="text-rose-500 font-black">-10</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kategori Single --}}
                    <div class="p-5 rounded-3xl bg-emerald-50 border border-emerald-100">
                        <div class="flex items-center gap-2 mb-3 text-emerald-700">
                            <i class="fas fa-user"></i>
                            <span class="text-[15px] font-black uppercase tracking-widest">Single / Solo (Target: 90s)</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-2xl border border-emerald-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">76s - 89s</span>
                                <span class="text-rose-500 font-black">-5</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-emerald-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">&lt; 76s</span>
                                <span class="text-rose-500 font-black">-10</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-emerald-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">91s - 100s</span>
                                <span class="text-rose-500 font-black">-5</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-emerald-100 flex justify-between items-center">
                                <span class="text-slate-600 font-bold">&gt; 100s</span>
                                <span class="text-rose-500 font-black">-10</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button @click="open = false" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl transition-all active:scale-95">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
