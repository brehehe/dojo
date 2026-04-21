<div wire:poll.2s class="min-h-screen bg-slate-900 text-slate-200 font-sans p-6 overflow-hidden flex flex-col relative w-full h-screen mx-auto max-w-[1920px]">

    {{-- HEADER --}}
    <div class="flex items-center justify-between bg-slate-800 rounded-2xl p-4 md:p-6 shadow-2xl shrink-0 border border-slate-700 relative z-10 w-full mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-trophy text-white text-2xl md:text-3xl drop-shadow-md"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-4xl font-black tracking-tight text-white flex items-center gap-3">
                    MONITOR HASIL
                    @if($match && $match->draft_type === 'randori')
                        <span class="px-3 py-1 bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-xl text-sm md:text-lg uppercase tracking-widest">RANDORI</span>
                    @elseif($match && $match->draft_type === 'embu')
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl text-sm md:text-lg uppercase tracking-widest">EMBU</span>
                    @endif
                </h1>
                @if($match)
                    <p class="text-amber-400 font-bold tracking-widest text-sm md:text-lg uppercase mt-1">
                        {{ $match->name }}
                        @if($match->ageGroup) <span class="text-slate-400 mx-2">•</span> <span class="text-slate-300">{{ $match->ageGroup->name }}</span> @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="text-right">
            @if($court)
                <h2 class="text-3xl md:text-5xl font-black text-amber-500 tracking-tighter">{{ $court->name }}</h2>
                <p class="text-slate-400 font-bold tracking-widest uppercase text-xs md:text-sm mt-1">Update Otomatis</p>
            @else
                <h2 class="text-2xl font-black text-amber-500">Live View</h2>
            @endif
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="flex-1 overflow-hidden relative border border-slate-700 bg-slate-800 rounded-3xl shadow-xl w-full">
        @if(! $match)
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <div class="w-32 h-32 md:w-48 md:h-48 bg-slate-800 rounded-full flex items-center justify-center shadow-inner mb-6 border border-slate-700">
                    <i class="fas fa-mug-hot text-5xl md:text-7xl text-slate-600"></i>
                </div>
                <h2 class="text-2xl md:text-4xl font-black text-slate-500 uppercase tracking-widest">BELUM ADA PERTANDINGAN AKTIF</h2>
                <p class="text-slate-600 mt-4 font-bold md:text-xl">Silakan tunggu panggilan selanjutnya.</p>
            </div>
        @else

            @if($match->draft_type === 'embu')
                {{-- EMBU TABLE --}}
                <div class="w-full h-full overflow-auto custom-scrollbar p-6">
                    <table class="w-full text-left border-collapse bg-slate-900 rounded-xl overflow-hidden shadow-2xl">
                        <thead>
                            <tr class="bg-slate-950 text-slate-400">
                                <th class="px-6 py-4 text-sm md:text-xl font-black uppercase tracking-widest text-center border-b border-slate-800 w-24">Peringkat</th>
                                <th class="px-6 py-4 text-sm md:text-xl font-black uppercase tracking-widest border-b border-slate-800">Kontingen / Atlet</th>
                                <th class="px-6 py-4 text-sm md:text-xl font-black uppercase tracking-widest text-center border-b border-slate-800 w-48">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($embuRanking as $index => $row)
                                @php
                                    $rank = $index + 1;
                                    // Highlight top 4
                                    $isTop = $rank <= 4;
                                    $hasScore = $row['effective_score'] && $row['effective_score']->total_score > 0;
                                @endphp
                                <tr class="transition-colors hover:bg-slate-800 {{ $isTop && $hasScore ? 'bg-amber-900/10' : '' }}">
                                    {{-- Rank --}}
                                    <td class="px-6 py-5 align-middle text-center">
                                        @if($hasScore)
                                            <div class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-xl font-black text-xl md:text-3xl {{ $rank === 1 ? 'bg-amber-500 text-slate-900 shadow-lg shadow-amber-500/40' : ($rank === 2 ? 'bg-slate-300 text-slate-800' : ($rank === 3 ? 'bg-amber-700 text-white' : 'bg-slate-800 text-slate-400 border border-slate-700')) }}">
                                                {{ $rank }}
                                            </div>
                                        @else
                                            <span class="text-slate-600 font-bold">-</span>
                                        @endif
                                    </td>

                                    {{-- Contingent / Athletes --}}
                                    <td class="px-6 py-5 align-middle">
                                        <div class="text-lg md:text-2xl font-black text-white uppercase tracking-tight">
                                            {{ $row['contingent']->name ?? 'Tanpa Kontingen' }}
                                        </div>
                                        @if($row['athletes']->isNotEmpty())
                                            <div class="mt-2 text-sm md:text-base text-slate-400 font-medium">
                                                {{ $row['athletes']->pluck('name')->join(' & ') }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Score --}}
                                    <td class="px-6 py-5 align-middle text-center">
                                        @if($hasScore)
                                            <div class="inline-flex items-center justify-center px-4 py-2 bg-slate-800 border border-slate-600 rounded-xl">
                                                <span class="text-2xl md:text-4xl font-black {{ $isTop ? 'text-emerald-400' : 'text-slate-300' }}">
                                                    {{ number_format($row['effective_score']->nilai_akhir, 1) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm md:text-lg text-slate-600 font-black tracking-widest uppercase py-2">Belum Tampil</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($embuRanking->isEmpty())
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500 font-bold text-lg">Belum ada data peserta.</td>
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

                <div class="w-full h-full overflow-auto custom-scrollbar bg-slate-900 border border-slate-800 relative select-none cursor-grab active:cursor-grabbing p-6" x-data="{
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

                    <div class="inline-flex flex-col gap-16 min-w-max pb-32">

                        {{-- UPPER BRACKET --}}
                        <div>
                            <div class="sticky left-0 bg-slate-900/90 backdrop-blur-sm z-20 pb-4 mb-2 flex items-center justify-between border-b border-rose-500/20">
                                <h3 class="text-lg md:text-2xl font-black text-rose-500 uppercase tracking-widest flex items-center gap-3">
                                    <i class="fas fa-sitemap"></i> Upper Bracket
                                </h3>
                            </div>

                            <div class="flex items-start gap-12 relative z-10">
                                @foreach($ubRounds as $roundIdx => $roundMatches)
                                    <div class="flex flex-col gap-6 justify-center" style="min-width: 280px; min-height: 100%;">
                                        <div class="text-center mb-6 sticky top-0 bg-slate-900/80 py-2 rounded font-black text-[11px] md:text-sm text-slate-500 tracking-widest uppercase">
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
                                                    ])
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                {{-- GRAND FINAL COLUMN --}}
                                @if($gfMatch)
                                    <div class="flex flex-col gap-6 justify-center" style="min-width: 320px; margin-left: 2rem;">
                                        <div class="text-center mb-6 sticky top-0 bg-slate-900/80 py-2 rounded font-black text-[13px] md:text-base text-amber-500 tracking-widest uppercase">
                                            Grand Final
                                        </div>
                                        @php
                                            $gfNodeKey = "gf_0_0";
                                            $gfResult = $randoriResults[$gfNodeKey] ?? null;
                                            $gfWinnerNode = $gfResult?->winner;
                                        @endphp
                                        <div class="relative group mt-auto mb-auto bg-amber-500/5 rounded-2xl border-2 border-amber-500/20 shadow-lg shadow-amber-500/10 scale-110 origin-left">
                                            @include('livewire.admin.arbitrase.scoring.partials._match-card-readonly', [
                                                'm' => $gfMatch,
                                                'roundIdx' => 0,
                                                'matchIdx' => 0,
                                                'bracket' => 'gf',
                                                'winnerNode' => $gfWinnerNode,
                                                'isPrelim' => false,
                                                'isDirect' => false,
                                            ])
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- LOWER BRACKET --}}
                        @if(count($lbRounds) > 0)
                            <div class="mt-8">
                                <div class="sticky left-0 bg-slate-900/90 backdrop-blur-sm z-20 pb-4 mb-2 flex items-center justify-between border-b border-indigo-500/20">
                                    <h3 class="text-lg md:text-2xl font-black text-indigo-400 uppercase tracking-widest flex items-center gap-3">
                                        <i class="fas fa-level-down-alt"></i> Lower Bracket
                                    </h3>
                                </div>

                                <div class="flex items-start gap-12 relative z-10">
                                    @foreach($lbRounds as $roundIdx => $roundMatches)
                                        <div class="flex flex-col gap-6 justify-center" style="min-width: 280px; min-height: 100%;">
                                            <div class="text-center mb-6 sticky top-0 bg-slate-900/80 py-2 rounded font-black text-[11px] md:text-sm text-slate-500 tracking-widest uppercase">
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
