<div class="space-y-6 animate-in fade-in duration-500">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 text-[15px] font-black uppercase tracking-[0.25em] text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Test Mode Aktif
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Testbench Penilaian Embu</h1>
            <p class="text-[15px] text-slate-800 font-semibold uppercase tracking-widest mt-0.5">Simulasi 5 Juri · Input Skor · Validasi Sistem End-to-End</p>
        </div>

        {{-- Round Toggle --}}
        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-2xl p-1.5 shadow-sm">
            <button wire:click="backToPenyisihan"
                    class="px-4 py-2 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all {{ $currentRound === 'Penyisihan' ? 'bg-slate-900 text-white shadow' : 'text-slate-800 hover:text-slate-900' }}">
                Penyisihan
            </button>
            <button wire:click="advanceToFinal"
                    class="px-4 py-2 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all {{ $currentRound === 'Final' ? 'bg-amber-500 text-white shadow' : 'text-slate-800 hover:text-slate-900' }}">
                Final
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[340px_1fr] gap-6">

        {{-- ===== LEFT: MATCH & PARTICIPANT SELECTOR ===== --}}
        <div class="space-y-4">

            {{-- Match selector --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-list-ul text-slate-800 text-[15px]"></i>
                    <p class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Pilih Nomor Embu</p>
                </div>
                <div class="p-3">
                    <select wire:model.live="selectedMatchId"
                            class="w-full appearance-none bg-slate-50 border border-slate-200 text-black text-[15px] font-bold rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all">
                        <option value="">-- Pilih Nomor Pertandingan --</option>
                        @foreach($embuMatches as $em)
                            <option value="{{ $em->id }}">{{ $em->name }} #{{ $em->id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Participant list --}}
            @if($match)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-users text-slate-800 text-[15px]"></i>
                            <p class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Peserta — {{ $currentRound }}</p>
                        </div>
                        <span class="text-[15px] font-black text-slate-800 border border-slate-200 rounded-lg px-2 py-0.5">{{ $registrations->count() }} peserta</span>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($registrations as $idx => $reg)
                            @php
                                $isActive = $activeRegistrationId == $reg['id'];
                                $score    = $reg['score'];
                                $rank     = $score?->rank;
                                $rankColor = match(true) {
                                    $rank === 1 => 'bg-amber-100 text-amber-700 border-amber-300',
                                    $rank === 2 => 'bg-slate-100 text-slate-900 border-slate-300',
                                    $rank === 3 => 'bg-orange-100 text-orange-700 border-orange-300',
                                    !is_null($rank) => 'bg-slate-50 text-slate-900 border-slate-200',
                                    default => 'bg-white text-slate-300 border-slate-100',
                                };
                            @endphp
                            <div class="px-4 py-3 flex items-center gap-3 transition-all cursor-pointer hover:bg-slate-50/70 {{ $isActive ? 'bg-emerald-50 border-l-4 border-emerald-500' : '' }}"
                                 wire:click="selectParticipant({{ $reg['id'] }})">
                                {{-- Rank badge --}}
                                <div class="w-7 h-7 rounded-lg border font-black text-[15px] flex items-center justify-center shrink-0 {{ $rankColor }}">
                                    {{ $rank ?? ($idx + 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    @foreach($reg['athletes'] as $ath)
                                        <p class="text-[15px] font-black text-slate-800 uppercase truncate leading-tight">{{ $ath->name }}</p>
                                    @endforeach
                                    <p class="text-[15px] font-bold text-slate-800 uppercase mt-0.5 truncate">{{ $reg['contingent']?->name ?? '-' }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    @if($score)
                                        <p class="text-[15px] font-black {{ $currentRound === 'Final' ? 'text-amber-600' : 'text-emerald-600' }}">{{ number_format($score->nilai_akhir, 1) }}</p>
                                        <p class="text-[15px] text-slate-800 font-bold uppercase">Nilai Akhir</p>
                                    @else
                                        <p class="text-[15px] italic text-slate-300">Belum dinilai</p>
                                    @endif
                                </div>
                                {{-- Call button --}}
                                <button wire:click.stop="callParticipant({{ $reg['id'] }})"
                                        class="flex-shrink-0 w-10 h-10 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl flex items-center justify-center transition-all active:scale-90 shadow-sm"
                                        title="Panggil ke lapangan">
                                    <i class="fas fa-bullhorn text-[15px]"></i>
                                </button>
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <i class="fas fa-users text-slate-200 text-3xl mb-3 block"></i>
                                <p class="text-[15px] font-black text-slate-300 uppercase tracking-widest">Belum ada peserta</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        {{-- ===== RIGHT: SCORING PANEL ===== --}}
        @if($activeRegistrationId && $match)
            @php
                $activeReg = $registrations->firstWhere('id', $activeRegistrationId);
            @endphp
            <div class="space-y-4">

                {{-- Active participant banner --}}
                <div class="bg-slate-900 rounded-2xl px-5 py-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-user text-white text-[15px]"></i>
                        </div>
                        <div>
                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Peserta Aktif — {{ $currentRound }}</p>
                            @if($activeReg)
                                @foreach($activeReg['athletes'] as $ath)
                                    <p class="text-[14px] font-black text-white uppercase leading-tight">{{ $ath->name }}</p>
                                @endforeach
                                <p class="text-[15px] text-emerald-400 font-bold uppercase">{{ $activeReg['contingent']?->name }}</p>
                            @endif
                        </div>
                    </div>
                    {{-- Live final score --}}
                    <div class="text-right">
                        <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Nilai Akhir (Live)</p>
                        <p class="text-3xl font-black text-emerald-400 tabular-nums">{{ number_format($finalNilaiAkhir, 1) }}</p>
                        <p class="text-[15px] text-slate-900">3 Tengah – Denda</p>
                    </div>
                </div>

                {{-- Judge tabs --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="flex border-b border-slate-100 overflow-x-auto scrollbar-hide">
                        @foreach(range(1, 5) as $j)
                            <button wire:click="$set('activeJudge', {{ $j }})"
                                    class="flex-1 flex flex-col items-center gap-0.5 px-4 py-3 text-[15px] font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap shrink-0 min-w-[80px]
                                           {{ $activeJudge === $j ? 'border-emerald-500 text-emerald-600 bg-emerald-50/50' : 'border-transparent text-slate-800 hover:text-slate-900' }}">
                                <i class="fas fa-gavel text-[15px]"></i>
                                Juri {{ $j }}
                                <span class="text-[15px] font-black {{ $activeJudge === $j ? 'text-emerald-600' : 'text-slate-900' }} tabular-nums">
                                    {{ number_format($judgeTotals[$j], 1) }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Score items for active judge --}}
                    @foreach(range(1, 5) as $j)
                        <div class="{{ $activeJudge === $j ? 'block' : 'hidden' }} p-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                                {{-- Goho --}}
                                <div>
                                    <p class="text-[15px] font-black text-red-500 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <span class="w-2 h-2 bg-red-500 rounded-sm inline-block"></span>
                                        Goho (max 10 per item)
                                    </p>
                                    <div class="space-y-2.5">
                                        @foreach(['goho_1' => 'Goho 1', 'goho_2' => 'Goho 2', 'goho_3' => 'Goho 3'] as $key => $label)
                                            <div class="flex items-center gap-3">
                                                <label class="text-[15px] font-bold text-slate-900 w-16 shrink-0">{{ $label }}</label>
                                                <div class="flex items-center gap-2 flex-1">
                                                    <button wire:click="adjustScore({{ $j }}, '{{ $key }}', -0.5)"
                                                            class="w-7 h-7 bg-slate-100 hover:bg-red-100 text-slate-900 rounded-lg text-[15px] font-black transition-all active:scale-90">−</button>
                                                    <input type="number" wire:model.blur="judgeScores.{{ $j }}.{{ $key }}"
                                                           min="0" max="10" step="0.5"
                                                           class="flex-1 text-center text-[15px] font-black border border-slate-200 rounded-xl py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400 transition-all tabular-nums">
                                                    <button wire:click="adjustScore({{ $j }}, '{{ $key }}', 0.5)"
                                                            class="w-7 h-7 bg-slate-100 hover:bg-emerald-100 text-slate-900 rounded-lg text-[15px] font-black transition-all active:scale-90">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Juho --}}
                                <div>
                                    <p class="text-[15px] font-black text-blue-500 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <span class="w-2 h-2 bg-blue-500 rounded-sm inline-block"></span>
                                        Juho (max 10 per item)
                                    </p>
                                    <div class="space-y-2.5">
                                        @foreach(['juho_1' => 'Juho 1', 'juho_2' => 'Juho 2', 'juho_3' => 'Juho 3'] as $key => $label)
                                            <div class="flex items-center gap-3">
                                                <label class="text-[15px] font-bold text-slate-900 w-16 shrink-0">{{ $label }}</label>
                                                <div class="flex items-center gap-2 flex-1">
                                                    <button wire:click="adjustScore({{ $j }}, '{{ $key }}', -0.5)"
                                                            class="w-7 h-7 bg-slate-100 hover:bg-red-100 text-slate-900 rounded-lg text-[15px] font-black transition-all active:scale-90">−</button>
                                                    <input type="number" wire:model.blur="judgeScores.{{ $j }}.{{ $key }}"
                                                           min="0" max="10" step="0.5"
                                                           class="flex-1 text-center text-[15px] font-black border border-slate-200 rounded-xl py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400 transition-all tabular-nums">
                                                    <button wire:click="adjustScore({{ $j }}, '{{ $key }}', 0.5)"
                                                            class="w-7 h-7 bg-slate-100 hover:bg-emerald-100 text-slate-900 rounded-lg text-[15px] font-black transition-all active:scale-90">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Ekspresi --}}
                                <div class="sm:col-span-2">
                                    <p class="text-[15px] font-black text-violet-500 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <span class="w-2 h-2 bg-violet-500 rounded-sm inline-block"></span>
                                        Ekspresi (max 10 per item)
                                    </p>
                                    <div class="grid grid-cols-2 gap-2.5">
                                        @foreach(['ekspresi_1' => 'Eksp. 1', 'ekspresi_2' => 'Eksp. 2', 'ekspresi_3' => 'Eksp. 3', 'ekspresi_4' => 'Eksp. 4'] as $key => $label)
                                            <div class="flex items-center gap-3">
                                                <label class="text-[15px] font-bold text-slate-900 w-16 shrink-0">{{ $label }}</label>
                                                <div class="flex items-center gap-2 flex-1">
                                                    <button wire:click="adjustScore({{ $j }}, '{{ $key }}', -0.5)"
                                                            class="w-7 h-7 bg-slate-100 hover:bg-red-100 text-slate-900 rounded-lg text-[15px] font-black transition-all active:scale-90">−</button>
                                                    <input type="number" wire:model.blur="judgeScores.{{ $j }}.{{ $key }}"
                                                           min="0" max="10" step="0.5"
                                                           class="flex-1 text-center text-[15px] font-black border border-slate-200 rounded-xl py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400 transition-all tabular-nums">
                                                    <button wire:click="adjustScore({{ $j }}, '{{ $key }}', 0.5)"
                                                            class="w-7 h-7 bg-slate-100 hover:bg-emerald-100 text-slate-900 rounded-lg text-[15px] font-black transition-all active:scale-90">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            {{-- Judge sub-total --}}
                            <div class="mt-5 flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Subtotal Juri {{ $j }}</p>
                                <p class="text-[18px] font-black text-slate-800 tabular-nums">{{ number_format($judgeTotals[$j], 1) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Denda + Score Summary --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Denda input --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                        <p class="text-[15px] font-black text-rose-500 uppercase tracking-widest mb-3"><i class="fas fa-minus-circle mr-1"></i>Denda</p>
                        <div class="flex items-center gap-2">
                            <button wire:click="decrementDenda"
                                    class="w-10 h-10 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-black transition-all active:scale-90">−</button>
                            <input type="number" wire:model.blur="denda" min="0" step="0.5"
                                   class="flex-1 text-center text-[15px] font-black border border-rose-200 rounded-xl py-2 focus:outline-none focus:ring-2 focus:ring-rose-300/40 focus:border-rose-400 transition-all text-rose-700 tabular-nums">
                            <button wire:click="incrementDenda"
                                    class="w-10 h-10 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-black transition-all active:scale-90">+</button>
                        </div>
                    </div>

                    {{-- Per-judge summary --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                        <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-3"><i class="fas fa-calculator mr-1"></i>Nilai 5 Juri</p>
                        <div class="flex justify-between gap-1">
                            @foreach(range(1, 5) as $j)
                                @php
                                    $vals = array_values($judgeTotals);
                                    sort($vals);
                                    $isDropped = $judgeTotals[$j] == $vals[0] || $judgeTotals[$j] == $vals[4];
                                @endphp
                                <div class="flex-1 text-center">
                                    <p class="text-[15px] font-black text-slate-800 uppercase">J{{ $j }}</p>
                                    <p class="text-[15px] font-black tabular-nums {{ $isDropped ? 'text-slate-300 line-through' : 'text-black' }}">{{ number_format($judgeTotals[$j], 1) }}</p>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[15px] text-slate-300 italic text-center mt-1">Nilai tertinggi & terendah dicoret</p>
                    </div>
                </div>

                {{-- Save button --}}
                <button wire:click="saveAllScores"
                        wire:loading.attr="disabled"
                        class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-[15px] uppercase tracking-widest rounded-2xl shadow-xl shadow-emerald-600/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3 disabled:opacity-60">
                    <span wire:loading.remove wire:target="saveAllScores"><i class="fas fa-save"></i> Simpan Nilai (Semua 5 Juri)</span>
                    <span wire:loading wire:target="saveAllScores"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                </button>

                {{-- Ranking panel --}}
                @if($showRankingPanel)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center gap-2">
                            <i class="fas fa-trophy text-amber-500 text-[15px]"></i>
                            <p class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Ranking — {{ $currentRound }}</p>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @foreach($registrations as $idx => $reg)
                                @if($reg['score'])
                                    <div class="px-4 py-3 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-lg font-black text-[15px] flex items-center justify-center shrink-0
                                            {{ $reg['score']->rank == 1 ? 'bg-amber-100 text-amber-700' : ($reg['score']->rank == 2 ? 'bg-slate-100 text-slate-900' : ($reg['score']->rank == 3 ? 'bg-orange-100 text-orange-700' : 'bg-slate-50 text-slate-800')) }}">
                                            {{ $reg['score']->rank }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            @foreach($reg['athletes'] as $ath)
                                                <p class="text-[15px] font-black text-slate-800 uppercase truncate">{{ $ath->name }}</p>
                                            @endforeach
                                            <p class="text-[15px] text-slate-800 font-bold uppercase">{{ $reg['contingent']?->name }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-[14px] font-black text-emerald-600 tabular-nums">{{ number_format($reg['score']->nilai_akhir, 1) }}</p>
                                            <p class="text-[15px] text-slate-800">Total: {{ number_format($reg['score']->total_score, 1) }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        @elseif($match)
            <div class="flex flex-col items-center justify-center bg-white rounded-2xl border-2 border-dashed border-slate-200 py-24 text-center">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-hand-pointer text-emerald-400 text-2xl"></i>
                </div>
                <p class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Pilih Peserta</p>
                <p class="text-[15px] text-slate-800 mt-1">Klik nama peserta di panel kiri untuk mulai penilaian</p>
            </div>
        @else
            <div class="flex flex-col items-center justify-center bg-white rounded-2xl border-2 border-dashed border-slate-200 py-24 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-sitemap text-slate-300 text-2xl"></i>
                </div>
                <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Pilih Nomor Pertandingan</p>
                <p class="text-[15px] text-slate-300 mt-1">Gunakan dropdown di panel kiri</p>
            </div>
        @endif

    </div>
</div>
