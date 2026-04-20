<div class="space-y-6 animate-in fade-in duration-500">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col gap-4">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-[0.25em] text-orange-600 bg-orange-50 border border-orange-200 px-3 py-1 rounded-full">
                        <i class="fas fa-trophy text-orange-500"></i>
                        Manajemen Hasil Embu
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Hasil & Juara Embu</h1>
                <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-widest mt-0.5">Penyisihan · Final · Tanding Ulang · Konfirmasi Juara</p>
            </div>

            {{-- Filters --}}
            <div class="flex flex-col gap-2 min-w-[280px]">
                {{-- Age Group filter pills --}}
                <div class="flex flex-wrap gap-1.5">
                    <button wire:click="$set('selectedAgeGroupId', null)"
                            class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border
                                   {{ is_null($selectedAgeGroupId) ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300' }}">
                        Semua
                    </button>
                    @foreach($ageGroups as $ag)
                        <button wire:click="$set('selectedAgeGroupId', {{ $ag->id }})"
                                class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border
                                       {{ $selectedAgeGroupId == $ag->id ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-slate-500 border-slate-200 hover:border-orange-300 hover:text-orange-600' }}">
                            {{ $ag->name }}
                        </button>
                    @endforeach
                </div>

                {{-- Match selector --}}
                <select wire:model.live="selectedMatchId"
                        class="w-full appearance-none bg-white border border-slate-200 text-slate-700 text-[12px] font-bold rounded-2xl px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 transition-all">
                    <option value="">-- Pilih Nomor Embu --</option>
                    @foreach($embuMatches as $em)
                        <option value="{{ $em->id }}">{{ $em->name }}{{ $em->ageGroup ? ' · '.$em->ageGroup->name : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($selectedMatchId)

        {{-- ===== CHAMPIONS (if confirmed) ===== --}}
        @if($champions->isNotEmpty())
            <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl p-6 shadow-xl shadow-amber-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-crown text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-white/70 uppercase tracking-widest">Hasil Akhir Dikonfirmasi</p>
                            <p class="text-[15px] font-black text-white uppercase tracking-tight">Juara Embu</p>
                        </div>
                    </div>
                    <button wire:click="$set('showChampionModal', true)"
                            class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        <i class="fas fa-redo mr-1"></i> Update
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($champions->take(3) as $champ)
                        @php
                            $rankIcon = match($champ->rank) { 1 => '🥇', 2 => '🥈', 3 => '🥉', default => '#'.$champ->rank };
                            $athletes = $champ->registration?->athletes ?? collect();
                        @endphp
                        <div class="flex items-center gap-3 bg-white/15 rounded-2xl px-4 py-3">
                            <span class="text-xl">{{ $rankIcon }}</span>
                            <div class="flex-1 min-w-0">
                                @foreach($athletes as $ath)
                                    <p class="text-[12px] font-black text-white uppercase leading-none">{{ $ath->name }}</p>
                                @endforeach
                                <p class="text-[9px] text-white/60 font-bold uppercase mt-0.5">{{ $champ->registration?->contingent?->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[14px] font-black text-white tabular-nums">{{ number_format($champ->accumulated_score, 1) }}</p>
                                <p class="text-[8px] text-white/60">Akumulasi</p>
                            </div>
                        </div>
                    @endforeach
                    @if($champions->count() > 3)
                        <p class="text-[9px] text-white/50 text-center font-bold">+{{ $champions->count() - 3 }} peserta lainnya</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- ===== PENYISIHAN RANKING ===== --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list-ol text-slate-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Penyisihan</p>
                            <p class="text-[9px] text-slate-400 font-bold">Nilai terendah = terbaik</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if(!empty($tiedPenyisihanIds))
                            <button wire:click="openTiebreakModal('Penyisihan', {{ json_encode($tiedPenyisihanIds) }})"
                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-[9px] font-black uppercase tracking-widest rounded-xl transition-all border border-rose-200">
                                <i class="fas fa-equals mr-1"></i> Tanding Ulang ({{ count($tiedPenyisihanIds) }})
                            </button>
                        @endif
                        @if(!$finalExists)
                            <button wire:click="openGenerateFinalModal"
                                    class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm">
                                <i class="fas fa-arrow-right mr-1"></i> Generate Final
                            </button>
                        @else
                            <button wire:click="openGenerateFinalModal"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl transition-all">
                                <i class="fas fa-sync mr-1"></i> Re-generate
                            </button>
                        @endif
                    </div>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($penyisihanRanking as $idx => $reg)
                        @php
                            $score = $reg['effective_score'];
                            $isTied = in_array($reg['id'], $tiedPenyisihanIds);
                            $qualifies = $idx < $finalQuota;
                        @endphp
                        <div class="px-5 py-3 flex items-center gap-3 {{ $qualifies ? '' : 'opacity-50' }} {{ $isTied ? 'bg-rose-50' : '' }}">
                            <div class="w-7 h-7 rounded-lg font-black text-[10px] flex items-center justify-center shrink-0 border
                                {{ $idx === 0 ? 'bg-amber-100 text-amber-700 border-amber-300' : ($idx === 1 ? 'bg-slate-100 text-slate-600 border-slate-300' : ($idx === 2 ? 'bg-orange-100 text-orange-700 border-orange-300' : 'bg-slate-50 text-slate-400 border-slate-100')) }}">
                                {{ $idx + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                @foreach($reg['athletes'] as $ath)
                                    <p class="text-[11px] font-black text-slate-800 uppercase leading-none truncate">{{ $ath->name }}</p>
                                @endforeach
                                <p class="text-[9px] text-slate-400 font-bold mt-0.5 truncate">{{ $reg['contingent']?->name }}</p>
                            </div>
                            <div class="text-right shrink-0 flex items-center gap-2">
                                @if($isTied)
                                    <span class="text-[8px] font-black bg-rose-100 text-rose-600 px-1.5 py-0.5 rounded-md uppercase">SERI</span>
                                @endif
                                @if($score)
                                    <div>
                                        <p class="text-[13px] font-black text-slate-700 tabular-nums">{{ number_format($score->nilai_akhir, 1) }}</p>
                                        @if($reg['tiebreak_score'])
                                            <p class="text-[8px] text-rose-500 font-bold">TB: {{ number_format($reg['tiebreak_score']->nilai_akhir, 1) }}</p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-[9px] italic text-slate-300">-</p>
                                @endif
                                @if($qualifies && $finalExists)
                                    <span class="w-5 h-5 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-emerald-600 text-[8px]"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <i class="fas fa-list text-slate-200 text-3xl mb-3 block"></i>
                            <p class="text-[11px] font-black text-slate-300 uppercase tracking-widest">Belum ada nilai Penyisihan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ===== FINAL RANKING ===== --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trophy text-amber-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Final</p>
                            <p class="text-[9px] text-slate-400 font-bold">Akumulasi Penyisihan + Final</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if(!empty($tiedFinalIds))
                            <button wire:click="openTiebreakModal('Final', {{ json_encode($tiedFinalIds) }})"
                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-[9px] font-black uppercase tracking-widest rounded-xl transition-all border border-rose-200">
                                <i class="fas fa-equals mr-1"></i> Tanding Ulang
                            </button>
                        @endif
                        @if($finalExists && $finalRanking->whereNotNull('accumulated')->isNotEmpty())
                            <button wire:click="$set('showChampionModal', true)"
                                    class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm">
                                <i class="fas fa-crown mr-1"></i> Konfirmasi Juara
                            </button>
                        @endif
                    </div>
                </div>

                @if(!$finalExists)
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-lock text-slate-300 text-xl"></i>
                        </div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Babak Final Belum Dibuka</p>
                        <p class="text-[10px] text-slate-300 mt-1">Generate Final dari panel Penyisihan</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-50">
                        @forelse($finalRanking as $idx => $reg)
                            @php
                                $isTiedFinal = in_array($reg['id'], $tiedFinalIds);
                            @endphp
                            <div class="px-5 py-3 flex items-center gap-3 {{ $isTiedFinal ? 'bg-rose-50' : '' }}">
                                <div class="w-7 h-7 rounded-lg font-black text-[10px] flex items-center justify-center shrink-0 border
                                    {{ $idx === 0 ? 'bg-amber-100 text-amber-700 border-amber-300' : ($idx === 1 ? 'bg-slate-100 text-slate-600 border-slate-300' : ($idx === 2 ? 'bg-orange-100 text-orange-700 border-orange-300' : 'bg-slate-50 text-slate-400 border-slate-100')) }}">
                                    {{ $idx + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    @foreach($reg['athletes'] as $ath)
                                        <p class="text-[11px] font-black text-slate-800 uppercase leading-none truncate">{{ $ath->name }}</p>
                                    @endforeach
                                    <p class="text-[9px] text-slate-400 font-bold mt-0.5 truncate">{{ $reg['contingent']?->name }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    @if($isTiedFinal)
                                        <span class="text-[8px] font-black bg-rose-100 text-rose-600 px-1.5 py-0.5 rounded-md uppercase block mb-1">SERI</span>
                                    @endif
                                    <div class="flex gap-3 items-center justify-end text-[10px] text-slate-400 font-bold">
                                        <span>P: {{ number_format($reg['penyisihan_score']?->nilai_akhir ?? 0, 1) }}</span>
                                        <span>+</span>
                                        <span>F: {{ $reg['final_score'] ? number_format($reg['final_score']->nilai_akhir, 1) : '–' }}</span>
                                    </div>
                                    @if($reg['accumulated'] !== null)
                                        <p class="text-[15px] font-black text-amber-600 tabular-nums">{{ number_format($reg['accumulated'], 1) }}</p>
                                    @else
                                        <p class="text-[9px] italic text-slate-300">Belum dinilai</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center">
                                <i class="fas fa-trophy text-slate-200 text-3xl mb-3 block"></i>
                                <p class="text-[11px] font-black text-slate-300 uppercase tracking-widest">Peserta Final belum diinput nilainya</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>

        </div>

        {{-- ===== MODAL: GENERATE FINAL ===== --}}
        @if($showGenerateFinalModal)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="$set('showGenerateFinalModal', false)">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 animate-in zoom-in-95 duration-200">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-right text-orange-500"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Jadwal Babak</p>
                            <p class="text-[15px] font-black text-slate-800">Generate Final</p>
                        </div>
                    </div>

                    <div class="space-y-3 mb-5">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Kuota Lolos ke Final</label>
                            <input type="number" wire:model="finalQuota" min="2" max="20"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-orange-400/30">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Court</label>
                                <select wire:model="finalCourtId" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-orange-400/30">
                                    <option value="">– Pilih –</option>
                                    @foreach($courts as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Pool</label>
                                <select wire:model="finalPoolId" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-orange-400/30">
                                    <option value="">– Pilih –</option>
                                    @foreach($pools as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Sesi</label>
                                <select wire:model="finalSessionTimeId" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-orange-400/30">
                                    <option value="">– Pilih –</option>
                                    @foreach($sessionTimes as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Rundown</label>
                                <select wire:model="finalRundownId" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-orange-400/30">
                                    <option value="">– Pilih –</option>
                                    @foreach($rundowns as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Tanggal Jadwal</label>
                            <input type="date" wire:model="finalScheduleDate"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-orange-400/30">
                        </div>
                    </div>

                    @if(!empty($tiedPenyisihanIds))
                        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 mb-4">
                            <p class="text-[10px] font-black text-rose-700 uppercase"><i class="fas fa-exclamation-triangle mr-1"></i> Ada nilai seri di batas kuota!</p>
                            <p class="text-[9px] text-rose-600 mt-0.5">{{ count($tiedPenyisihanIds) }} peserta perlu tanding ulang sebelum generate final.</p>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        <button wire:click="$set('showGenerateFinalModal', false)"
                                class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-2xl transition-all">Batal</button>
                        <button wire:click="generateFinal" wire:loading.attr="disabled"
                                class="flex-1 py-3 bg-orange-600 hover:bg-orange-700 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-orange-600/20 disabled:opacity-60">
                            <span wire:loading.remove wire:target="generateFinal"><i class="fas fa-check mr-1"></i> Generate Final</span>
                            <span wire:loading wire:target="generateFinal"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== MODAL: TIEBREAK SCHEDULE ===== --}}
        @if($showTiebreakModal)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="$set('showTiebreakModal', false)">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 animate-in zoom-in-95 duration-200">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-redo text-rose-500"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Jadwal Tanding Ulang</p>
                            <p class="text-[15px] font-black text-slate-800">{{ $tiebreakRound }} — {{ count($tiebreakRegistrationIds) }} Peserta Seri</p>
                        </div>
                    </div>

                    <div class="space-y-3 mb-5">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Court</label>
                                <select wire:model="tiebreakCourtId" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-rose-400/30">
                                    <option value="">– Pilih –</option>
                                    @foreach($courts as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Sesi</label>
                                <select wire:model="tiebreakSessionTimeId" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-rose-400/30">
                                    <option value="">– Pilih –</option>
                                    @foreach($sessionTimes as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Rundown</label>
                                <select wire:model="tiebreakRundownId" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-rose-400/30">
                                    <option value="">– Pilih –</option>
                                    @foreach($rundowns as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Tanggal</label>
                                <input type="date" wire:model="tiebreakScheduleDate"
                                       class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-rose-400/30">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="$set('showTiebreakModal', false)"
                                class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-2xl transition-all">Batal</button>
                        <button wire:click="generateTiebreakSchedule" wire:loading.attr="disabled"
                                class="flex-1 py-3 bg-rose-600 hover:bg-rose-700 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-rose-600/20 disabled:opacity-60">
                            <span wire:loading.remove wire:target="generateTiebreakSchedule"><i class="fas fa-calendar-plus mr-1"></i> Buat Jadwal</span>
                            <span wire:loading wire:target="generateTiebreakSchedule"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== MODAL: CONFIRM CHAMPION ===== --}}
        @if($showChampionModal)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="$set('showChampionModal', false)">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 animate-in zoom-in-95 duration-200">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-crown text-amber-500 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-amber-500 uppercase tracking-widest">Konfirmasi Hasil Akhir</p>
                            <p class="text-[15px] font-black text-slate-800">Simpan Juara ke Database</p>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 mb-4">
                        <p class="text-[10px] font-black text-amber-800 uppercase"><i class="fas fa-info-circle mr-1"></i> Aksi ini akan menyimpan / mengupdate data juara</p>
                        <p class="text-[9px] text-amber-600 mt-0.5">Data juara sebelumnya (jika ada) akan diganti dengan hasil terbaru.</p>
                    </div>

                    @if(!empty($tiedFinalIds))
                        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 mb-4">
                            <p class="text-[10px] font-black text-rose-700 uppercase"><i class="fas fa-exclamation-triangle mr-1"></i> Masih ada nilai seri!</p>
                            <p class="text-[9px] text-rose-600 mt-0.5">{{ count($tiedFinalIds) }} peserta memiliki nilai akumulasi sama. Selesaikan tanding ulang terlebih dahulu.</p>
                        </div>
                    @endif

                    <div class="space-y-2 mb-5 max-h-64 overflow-y-auto">
                        @foreach($finalRanking->whereNotNull('accumulated')->take(5) as $idx => $reg)
                            <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-3 py-2.5">
                                <span class="text-lg">{{ match($idx) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => '#'.($idx+1) } }}</span>
                                <div class="flex-1 min-w-0">
                                    @foreach($reg['athletes'] as $ath)
                                        <p class="text-[11px] font-black text-slate-700 uppercase truncate">{{ $ath->name }}</p>
                                    @endforeach
                                </div>
                                <p class="text-[13px] font-black text-amber-600 tabular-nums">{{ number_format($reg['accumulated'], 1) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="$set('showChampionModal', false)"
                                class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-2xl transition-all">Batal</button>
                        <button wire:click="confirmChampion" wire:loading.attr="disabled"
                                class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-amber-500/20 disabled:opacity-60">
                            <span wire:loading.remove wire:target="confirmChampion"><i class="fas fa-crown mr-1"></i> Konfirmasi Juara</span>
                            <span wire:loading wire:target="confirmChampion"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

    @else
        <div class="flex flex-col items-center justify-center bg-white rounded-3xl border-2 border-dashed border-slate-200 py-24 text-center">
            <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-trophy text-orange-300 text-2xl"></i>
            </div>
            <p class="text-[13px] font-black text-slate-400 uppercase tracking-widest">Pilih Nomor Embu</p>
            <p class="text-[11px] text-slate-300 mt-1">Gunakan dropdown di atas untuk memulai</p>
        </div>
    @endif

</div>
