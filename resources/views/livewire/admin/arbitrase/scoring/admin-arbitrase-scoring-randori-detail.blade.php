<div class="p-4 md:p-6 space-y-6">

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
                            <div class="border-t border-amber-100 bg-amber-50 px-4 py-2.5 flex items-center justify-end gap-2">
                                <button wire:click="callGrandFinal()"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-amber-100 border border-amber-300 text-amber-700 hover:bg-amber-500 hover:text-white transition-all">
                                    <i class="fas fa-bullhorn text-[15px]"></i> Panggil
                                </button>
                                <button wire:click="openGrandFinalModal()"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[15px] font-black uppercase bg-slate-900 text-white hover:bg-amber-600 transition-all shadow-sm">
                                    <i class="fas fa-edit text-[15px]"></i> Input Skor
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ====== HASIL AKHIR ====== --}}
        @if(!empty($juaraMap))
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-gradient-to-r from-slate-800 to-slate-700 flex items-center gap-2">
                <i class="fas fa-medal text-amber-400 text-[15px]"></i>
                <span class="text-[15px] font-black text-white uppercase tracking-widest">Hasil Akhir</span>
            </div>
            <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach([1=>'🥇',2=>'🥈',3=>'🥉',4=>'🏅'] as $rank => $medal)
                    @php $athlete = $juaraMap[$rank] ?? null; @endphp
                    <div class="text-center p-4 rounded-xl {{ $athlete ? 'bg-amber-50 border border-amber-200' : 'bg-slate-50 border border-slate-100' }}">
                        <div class="text-3xl mb-2">{{ $medal }}</div>
                        <div class="text-[15px] font-black text-slate-800 uppercase mb-1">Juara {{ $rank }}</div>
                        @if($athlete)
                            <div class="text-[15px] font-black text-slate-800 uppercase leading-tight">{{ $athlete['name'] }}</div>
                            <div class="text-[15px] text-slate-800 mt-0.5">{{ $athlete['contingent'] ?? '' }}</div>
                        @else
                            <div class="text-[15px] text-slate-300 font-bold italic">Menunggu...</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    @endif {{-- end if ubRounds --}}

    {{-- ====== RESULT MODAL ====== --}}
    @if($showModal && $activeMatch)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
            <div wire:poll.2s class="bg-white rounded-3xl w-full max-w-xl shadow-2xl relative max-h-[90vh] overflow-y-auto css-scrollbar">
                <div class="p-5 md:p-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">Hasil Pertandingan</h2>
                            <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest mt-0.5">
                                {{ strtoupper($activeMatch['bracket']) }} &bull;
                                @if($activeMatch['bracket'] === 'gf') Grand Final @else R{{ $activeMatch['round'] + 1 }} M{{ $activeMatch['match'] + 1 }} @endif
                            </p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-800 hover:text-rose-500 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    {{-- Status Penilaian 5 Wasit --}}
                    <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-200">
                        <div class="text-[15px] font-black uppercase text-slate-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-satellite-dish text-teal-500 animate-pulse"></i> 
                            Status Submit 5 Wasit (Live)
                        </div>
                        <div class="flex gap-1.5">
                            @for($i = 1; $i <= 5; $i++)
                                @php 
                                    $hasScore = collect($this->scoringStatus)->has($i); 
                                    $scoreRow = $hasScore ? $this->scoringStatus[$i] : null;
                                @endphp
                                <div class="flex-1 text-center py-2 rounded-lg border {{ $hasScore ? 'bg-teal-50 border-teal-200 shadow-sm' : 'bg-white border-slate-200' }} transition-colors relative overflow-hidden group">
                                    <span class="text-[15px] font-black block mb-0.5 {{ $hasScore ? 'text-teal-700' : 'text-slate-800' }}">JURI {{ $i }}</span>
                                    
                                    @if($hasScore)
                                        @php
                                            $akaScore = ($scoreRow->ippon_aka * 10) + ($scoreRow->waza_ari_aka * 5) - ($scoreRow->hansoku_aka * 5); // Example calculation
                                            $shiroScore = ($scoreRow->ippon_shiro * 10) + ($scoreRow->waza_ari_shiro * 5) - ($scoreRow->hansoku_shiro * 5);
                                        @endphp
                                        <div class="text-[15px] font-black tracking-widest mt-1 flex items-center justify-center gap-1">
                                            <span class="text-rose-600">{{ $akaScore }}</span>
                                            <span class="text-slate-300">-</span>
                                            <span class="text-blue-600">{{ $shiroScore }}</span>
                                        </div>
                                        <div class="absolute inset-0 bg-teal-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-check-circle text-white text-lg"></i>
                                        </div>
                                    @else
                                        <i class="fas fa-clock text-base text-slate-300 mb-0.5"></i>
                                    @endif
                                </div>
                            @endfor
                        </div>
                        <div class="mt-1.5 text-[15px] font-medium text-slate-800 italic text-center">Tunggu hingga sebagian besar wasit memberikan nilai sebelum menekan "Sah". Hover kotak untuk logo centang.</div>
                    </div>

                    {{-- Athletes --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        {{-- AKA (Red / athlete1) --}}
                        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 text-center flex flex-col items-center">
                            <div class="text-[15px] font-black text-rose-500 uppercase tracking-widest mb-1 bg-rose-100 px-2 py-0.5 rounded-md">Pita Merah</div>
                            <h3 class="text-[15px] font-black text-slate-800 uppercase leading-snug mb-3 flex-1 flex items-center justify-center h-8">
                                {{ $activeMatch['data']['athlete1']['name'] ?? 'TBD' }}
                            </h3>
                            <div class="mb-3 w-full">
                                <label class="text-[15px] font-black text-rose-400 uppercase">Input Manual</label>
                                <input type="number" wire:model="scoreRed" class="w-full mt-1 px-2 py-1.5 bg-white border border-rose-200 rounded-xl text-center text-lg font-black text-slate-800 focus:border-rose-500 focus:ring-0 outline-none transition-all block">
                            </div>
                            @if($activeMatch['data']['athlete1'])
                                <button
                                    wire:click="selectWinner('{{ $activeMatch['bracket'] }}', {{ $activeMatch['round'] }}, {{ $activeMatch['match'] }}, 'athlete1')"
                                    class="w-full py-2 bg-rose-500 hover:bg-rose-600 text-white text-[15px] font-black uppercase tracking-widest rounded-xl shadow-md shadow-rose-500/20 transition-all active:scale-95"
                                >
                                    <i class="fas fa-trophy mr-1"></i> Menang
                                </button>
                            @endif
                        </div>

                        {{-- SHIRO (Blue / athlete2) --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-center flex flex-col items-center">
                            <div class="text-[15px] font-black text-blue-500 uppercase tracking-widest mb-1 bg-blue-100 px-2 py-0.5 rounded-md">Pita Putih</div>
                            <h3 class="text-[15px] font-black text-slate-800 uppercase leading-snug mb-3 flex-1 flex items-center justify-center h-8">
                                {{ $activeMatch['data']['athlete2']['name'] ?? 'TBD' }}
                            </h3>
                            <div class="mb-3 w-full">
                                <label class="text-[15px] font-black text-blue-400 uppercase">Input Manual</label>
                                <input type="number" wire:model="scoreBlue" class="w-full mt-1 px-2 py-1.5 bg-white border border-blue-200 rounded-xl text-center text-lg font-black text-slate-800 focus:border-blue-500 focus:ring-0 outline-none transition-all block">
                            </div>
                            @if($activeMatch['data']['athlete2'])
                                <button
                                    wire:click="selectWinner('{{ $activeMatch['bracket'] }}', {{ $activeMatch['round'] }}, {{ $activeMatch['match'] }}, 'athlete2')"
                                    class="w-full py-2 bg-blue-500 hover:bg-blue-600 text-white text-[15px] font-black uppercase tracking-widest rounded-xl shadow-md shadow-blue-500/20 transition-all active:scale-95"
                                >
                                    <i class="fas fa-trophy mr-1"></i> Menang
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button
                            wire:click="autoDetermineWinner('{{ $activeMatch['bracket'] }}', {{ $activeMatch['round'] }}, {{ $activeMatch['match'] }})"
                            class="w-full py-3 bg-teal-500 hover:bg-teal-600 text-white text-[15px] font-black uppercase tracking-widest rounded-xl shadow-md shadow-teal-500/20 transition-all active:scale-95 flex flex-col items-center justify-center gap-0.5"
                        >
                            <div class="flex items-center justify-center gap-1.5">
                                <i class="fas fa-calculator"></i> Sah (Tarik Akumulasi Poin)
                            </div>
                            <span class="text-[15px] font-medium opacity-80 normal-case">Murni ikut keputusan 5 Wasit</span>
                        </button>
                    </div>

                    <div class="flex items-center justify-center gap-3 mb-3">
                        <div class="h-px bg-slate-200 flex-1"></div>
                        <span class="text-[15px] font-black uppercase text-slate-800">Atau Manual</span>
                        <div class="h-px bg-slate-200 flex-1"></div>
                    </div>

                    <button wire:click="$set('showModal', false)" class="w-full py-2 bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-[15px] uppercase tracking-widest rounded-xl transition-all">
                        Tutup Panel
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
