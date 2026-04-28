<div wire:poll.2s class="min-h-screen bg-slate-50 text-slate-800 font-sans p-4 md:p-6 lg:p-8 overflow-hidden flex flex-col relative w-full h-screen mx-auto max-w-[1920px]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row items-center justify-between bg-white rounded-2xl md:rounded-3xl p-4 md:p-6 lg:p-8 shadow-sm shrink-0 border border-slate-200 relative z-10 w-full mb-4 md:mb-6 gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20 shrink-0">
                <i class="fas fa-trophy text-white text-2xl md:text-3xl lg:text-4xl drop-shadow-md"></i>
            </div>
            <div>
                <h1 class="text-xl md:text-3xl lg:text-4xl font-black tracking-tight text-slate-900 flex items-center gap-3 flex-wrap">
                    MONITOR HASIL
                    @if($match && $match->draft_type === 'randori')
                        <span class="px-2 py-0.5 md:px-3 md:py-1 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg md:rounded-xl text-xs md:text-[15px] lg:text-lg uppercase tracking-widest">RANDORI</span>
                    @elseif($match && $match->draft_type === 'embu')
                        <span class="px-2 py-0.5 md:px-3 md:py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg md:rounded-xl text-xs md:text-[15px] lg:text-lg uppercase tracking-widest">EMBU</span>
                    @endif
                </h1>
                @if($match)
                    <p class="text-slate-600 font-bold tracking-widest text-sm md:text-[15px] lg:text-lg uppercase mt-1">
                        {{ $match->name }}
                        @if($match->ageGroup) <span class="text-slate-300 mx-2">•</span> <span class="text-slate-500">{{ $match->ageGroup->name }}</span> @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="text-center sm:text-right">
            @if($court)
                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-amber-500 tracking-tighter">{{ $court->name }}</h2>
                <p class="text-slate-400 font-bold tracking-widest uppercase text-xs md:text-sm lg:text-[15px] mt-1">Update Otomatis</p>
            @else
                <h2 class="text-xl md:text-2xl lg:text-3xl font-black text-amber-500">Live View</h2>
            @endif
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="flex-1 overflow-hidden relative border border-slate-200 bg-white rounded-2xl md:rounded-3xl shadow-sm w-full">
        @if(! $match)
            <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                <div class="w-24 h-24 md:w-32 md:h-32 lg:w-48 lg:h-48 bg-slate-50 rounded-full flex items-center justify-center shadow-inner mb-6 border border-slate-200">
                    <i class="fas fa-mug-hot text-4xl md:text-5xl lg:text-7xl text-slate-300"></i>
                </div>
                <h2 class="text-xl md:text-3xl lg:text-4xl font-black text-slate-800 uppercase tracking-widest">BELUM ADA PERTANDINGAN AKTIF</h2>
                <p class="text-slate-500 mt-2 md:mt-4 font-bold text-sm md:text-lg lg:text-xl">Silakan tunggu panggilan selanjutnya.</p>
            </div>
        @else

            @if($match->draft_type === 'embu')
                {{-- EMBU TABLE --}}
                <div class="w-full h-full overflow-auto custom-scrollbar p-4 md:p-6 lg:p-8">
                    <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <thead class="bg-slate-50 text-slate-800">
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 md:py-4 lg:py-5 text-xs md:text-[15px] font-black uppercase tracking-widest border border-slate-200 whitespace-nowrap text-center">Peringkat</th>
                                <th class="px-4 py-3 md:py-4 lg:py-5 text-xs md:text-[15px] font-black uppercase tracking-widest border border-slate-200 whitespace-nowrap">Kontingen / Atlet</th>
                                <th class="px-4 py-3 md:py-4 lg:py-5 text-xs md:text-[15px] font-black uppercase tracking-widest border border-slate-200 whitespace-nowrap text-center">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($embuRanking as $index => $row)
                                @php
                                    $rank = $index + 1;
                                    // Highlight top 4
                                    $isTop = $rank <= 4;
                                    $hasScore = ($row['effective_score'] && $row['effective_score']->total_score > 0) || (isset($row['accumulated_score']) && $row['accumulated_score'] > 0);
                                @endphp
                                <tr class="{{ $loop->even ? 'bg-slate-50' : 'bg-white' }} hover:bg-slate-100 transition-colors group">
                                    {{-- Rank --}}
                                    <td class="px-4 py-3 md:px-6 md:py-5 align-middle text-center border-r border-slate-100">
                                        @if($hasScore)
                                            <div class="inline-flex items-center justify-center w-10 h-10 md:w-16 md:h-16 lg:w-20 lg:h-20 rounded-lg md:rounded-xl lg:rounded-2xl font-black text-lg md:text-3xl lg:text-4xl {{ $rank === 1 ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/40' : ($rank === 2 ? 'bg-slate-300 text-slate-700' : ($rank === 3 ? 'bg-amber-700 text-white' : 'bg-slate-100 text-slate-500 border border-slate-200')) }}">
                                                {{ $rank }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 font-bold">-</span>
                                        @endif
                                    </td>

                                    {{-- Contingent / Athletes --}}
                                    <td class="px-4 py-3 md:px-6 md:py-5 align-middle border-r border-slate-100">
                                        <div class="text-base md:text-2xl lg:text-3xl font-black text-slate-800 uppercase tracking-tight">
                                            {{ $row['contingent']->name ?? 'Tanpa Kontingen' }}
                                        </div>
                                        @if($row['athletes']->isNotEmpty())
                                            <div class="mt-1 md:mt-2 text-sm md:text-base lg:text-lg text-slate-500 font-medium">
                                                {{ $row['athletes']->pluck('name')->join(' & ') }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Score --}}
                                    <td class="px-4 py-3 md:px-6 md:py-5 align-middle text-center border-r border-slate-100">
                                        @if($hasScore)
                                            <div class="flex flex-col items-center gap-1.5">
                                                <div class="inline-flex items-center justify-center px-3 py-1.5 md:px-4 md:py-2 bg-slate-50 border border-slate-200 rounded-lg md:rounded-xl">
                                                    <span class="text-xl md:text-4xl lg:text-5xl font-black {{ $isTop ? 'text-emerald-600' : 'text-slate-500' }}">
                                                        {{ number_format($row['accumulated_score'] ?? optional($row['effective_score'])->nilai_akhir ?? 0, 1) }}
                                                    </span>
                                                </div>
                                                @if(isset($row['penyisihan_score']) && $row['penyisihan_score'])
                                                    <div class="text-[11px] md:text-xs font-black text-slate-500 tracking-wider">
                                                        PENYISIHAN: {{ number_format($row['penyisihan_score']->nilai_akhir, 1) }} &nbsp;|&nbsp; FINAL: {{ $row['effective_score'] ? number_format($row['effective_score']->nilai_akhir, 1) : '0.0' }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs md:text-[15px] lg:text-lg text-slate-400 font-black tracking-widest uppercase py-2">Belum Tampil</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($embuRanking->isEmpty())
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500 font-bold text-base md:text-lg border-r border-slate-100">Belum ada data peserta.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            @elseif($match->draft_type === 'randori')
                {{-- RANDORI BRACKET --}}
                @php
                    $ubRounds = $drawingData['upper_bracket']['rounds'] ?? [];
                    $lbRounds = $drawingData['lower_bracket']['rounds'] ?? [];
                    $gfMatch = $drawingData['grand_final'] ?? null;
                    $totalEntries = $drawingData['total_entries'] ?? 0;
                    $hasPrelim = $drawingData['has_preliminary'] ?? false;
                @endphp

                <div class="w-full h-full overflow-auto custom-scrollbar bg-slate-50 relative select-none cursor-grab active:cursor-grabbing p-4 md:p-6 lg:p-8" x-data="{
                        startX: 0, startY: 0, scrollLeft: 0, scrollTop: 0,
                        startDrag(e) {
                            this.startX = e.pageX - this.$el.offsetLeft;
                            this.startY = e.pageY - this.$el.offsetTop;
                            this.scrollLeft = this.$el.scrollLeft;
                            this.scrollTop = this.$el.scrollTop;
                        },
                        drag(e) {
                            if (e.buttons !== 1) return;
                            e.preventDefault();
                            const x = e.pageX - this.$el.offsetLeft;
                            const y = e.pageY - this.$el.offsetTop;
                            this.$el.scrollLeft = this.scrollLeft - (x - this.startX);
                            this.$el.scrollTop = this.scrollTop - (y - this.startY);
                        }
                    }"
                    @mousedown="startDrag" @mousemove="drag">

                    <div class="inline-flex flex-col gap-12 md:gap-16 min-w-max pb-32">

                        {{-- UPPER BRACKET --}}
                        <div>
                            <div class="sticky left-0 bg-slate-50/90 backdrop-blur-sm z-20 pb-4 mb-2 flex items-center justify-between border-b border-rose-200">
                                <h3 class="text-base md:text-xl lg:text-2xl font-black text-rose-600 uppercase tracking-widest flex items-center gap-3">
                                    <i class="fas fa-sitemap"></i> Upper Bracket
                                </h3>
                            </div>

                            <div class="flex items-start gap-12 relative z-10">
                                @foreach($ubRounds as $roundIdx => $roundMatches)
                                    <div class="flex flex-col gap-6 justify-center" style="min-width: 280px; min-height: 100%;">
                                        <div class="text-center mb-6 sticky top-0 bg-slate-50/90 py-2 rounded font-black text-sm md:text-[15px] lg:text-base text-slate-500 tracking-widest uppercase">
                                            @if($roundIdx === count($ubRounds) - 1)
                                                Final UB
                                            @elseif($hasPrelim && $roundIdx === 0)
                                                Perempatan
                                            @else
                                                UB Putaran {{ $hasPrelim ? $roundIdx : $roundIdx + 1 }}
                                            @endif
                                        </div>

                                        <div class="flex flex-col justify-around flex-1" style="row-gap: {{ pow(2, $roundIdx) * 1.5 }}rem;">
                                            @foreach($roundMatches as $matchIdx => $m)
                                                @php
                                                    $nodeKey = "ub_{$roundIdx}_{$matchIdx}";
                                                    $nodeResult = $randoriResults[$nodeKey] ?? null;
                                                    $winnerNode = $nodeResult?->winner;
                                                @endphp

                                                <div class="relative group">
                                                    @include('livewire.admin.arbitrase.scoring.partials._match-card-readonly', [
                                                        'm' => $m,
                                                        'roundIdx' => $roundIdx,
                                                        'matchIdx' => $matchIdx,
                                                        'bracket' => 'ub',
                                                        'winnerNode' => $winnerNode,
                                                        'isPrelim' => $m['is_prelim'] ?? false,
                                                        'isDirect' => $m['is_direct'] ?? false,
                                                        'nodeKey' => $nodeKey,
                                                        'isActive' => $nodeKey === ($activeNodeKey ?? null),
                                                    ])
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                {{-- GRAND FINAL COLUMN --}}
                                @if($gfMatch)
                                    <div class="flex flex-col gap-6 justify-center" style="min-width: 320px; margin-left: 2rem;">
                                        <div class="text-center mb-6 sticky top-0 bg-slate-50/90 py-2 rounded font-black text-sm md:text-[15px] lg:text-base text-amber-600 tracking-widest uppercase">
                                            Grand Final
                                        </div>
                                        @php
                                            $gfNodeKey = "gf_0_0";
                                            $gfResult = $randoriResults[$gfNodeKey] ?? null;
                                            $gfWinnerNode = $gfResult?->winner;
                                        @endphp
                                        <div class="relative group mt-auto mb-auto bg-amber-50 rounded-2xl border-2 border-amber-200 shadow-md shadow-amber-500/10 scale-110 origin-left">
                                            @include('livewire.admin.arbitrase.scoring.partials._match-card-readonly', [
                                                'm' => $gfMatch,
                                                'roundIdx' => 0,
                                                'matchIdx' => 0,
                                                'bracket' => 'gf',
                                                'winnerNode' => $gfWinnerNode,
                                                'isPrelim' => false,
                                                'isDirect' => false,
                                                'nodeKey' => $gfNodeKey,
                                                'isActive' => $gfNodeKey === ($activeNodeKey ?? null),
                                            ])
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- LOWER BRACKET --}}
                        @if(count($lbRounds) > 0)
                            <div class="mt-8">
                                <div class="sticky left-0 bg-slate-50/90 backdrop-blur-sm z-20 pb-4 mb-2 flex items-center justify-between border-b border-indigo-200">
                                    <h3 class="text-base md:text-xl lg:text-2xl font-black text-indigo-600 uppercase tracking-widest flex items-center gap-3">
                                        <i class="fas fa-level-down-alt"></i> Lower Bracket
                                    </h3>
                                </div>

                                <div class="flex items-start gap-12 relative z-10">
                                    @foreach($lbRounds as $roundIdx => $roundMatches)
                                        <div class="flex flex-col gap-6 justify-center" style="min-width: 280px; min-height: 100%;">
                                            <div class="text-center mb-6 sticky top-0 bg-slate-50/90 py-2 rounded font-black text-sm md:text-[15px] lg:text-base text-slate-500 tracking-widest uppercase">
                                                @if($roundIdx === count($lbRounds) - 1)
                                                    Final LB
                                                @else
                                                    LB Putaran {{ $roundIdx + 1 }}
                                                @endif
                                            </div>

                                            <div class="flex flex-col justify-around flex-1" style="row-gap: {{ pow(2, floor($roundIdx/2)) * 1.5 }}rem;">
                                                @foreach($roundMatches as $matchIdx => $m)
                                                    @php
                                                        $nodeKey = "lb_{$roundIdx}_{$matchIdx}";
                                                        $nodeResult = $randoriResults[$nodeKey] ?? null;
                                                        $winnerNode = $nodeResult?->winner;
                                                    @endphp
                                                    <div class="relative group">
                                                        @include('livewire.admin.arbitrase.scoring.partials._match-card-readonly', [
                                                            'm' => $m,
                                                            'roundIdx' => $roundIdx,
                                                            'matchIdx' => $matchIdx,
                                                            'bracket' => 'lb',
                                                            'winnerNode' => $winnerNode,
                                                            'isPrelim' => false,
                                                            'isDirect' => false,
                                                            'nodeKey' => $nodeKey,
                                                            'isActive' => $nodeKey === ($activeNodeKey ?? null),
                                                        ])
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

        @endif
    </div>
</div>
