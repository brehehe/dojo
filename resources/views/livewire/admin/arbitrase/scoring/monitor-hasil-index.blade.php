<div wire:poll.2s class="relative mx-auto flex h-screen min-h-screen w-full max-w-[1920px] flex-col overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.08),_transparent_32%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)] p-3 font-sans text-slate-800 sm:p-4 md:p-6 lg:p-8">

    {{-- HEADER --}}
    <div class="relative z-10 mb-4 flex w-full flex-col gap-4 rounded-[1.75rem] border border-white/70 bg-white/90 p-4 shadow-[0_18px_60px_-28px_rgba(15,23,42,0.35)] backdrop-blur sm:mb-5 sm:p-5 md:mb-6 md:flex-row md:items-center md:justify-between md:gap-6 md:rounded-[2rem] md:p-6 lg:p-8">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 via-amber-500 to-orange-500 shadow-lg shadow-amber-500/25 sm:h-14 sm:w-14 md:h-16 md:w-16 lg:h-20 lg:w-20">
                <i class="fas fa-trophy text-xl text-white drop-shadow-md sm:text-2xl md:text-3xl lg:text-4xl"></i>
            </div>
            <div class="min-w-0">
                <h1 class="flex flex-wrap items-center gap-2 text-lg font-black tracking-tight text-slate-900 sm:gap-3 sm:text-xl md:text-3xl lg:text-4xl">
                    MONITOR HASIL
                    @if($match && $match->draft_type === 'randori')
                        <span class="rounded-lg border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] uppercase tracking-[0.24em] text-rose-600 sm:text-xs md:rounded-xl md:px-3 md:py-1 md:text-[15px] lg:text-lg">RANDORI</span>
                    @elseif($match && $match->draft_type === 'embu')
                        <span class="rounded-lg border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] uppercase tracking-[0.24em] text-emerald-600 sm:text-xs md:rounded-xl md:px-3 md:py-1 md:text-[15px] lg:text-lg">EMBU</span>
                    @endif
                </h1>
                @if($match)
                    <p class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-slate-500 sm:text-sm md:text-[15px] lg:text-lg">
                        {{ $match->mergeDetail?->merge?->name ?? $match->name }}
                        @if($match->ageGroup) <span class="text-slate-300 mx-2">•</span> <span class="text-slate-500">{{ $match->ageGroup->name }}</span> @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 px-4 py-3 text-center sm:px-5 md:min-w-[220px] md:text-right">
            @if($court)
                <h2 class="text-2xl font-black tracking-tight text-amber-500 sm:text-3xl md:text-4xl lg:text-5xl">{{ $court->name }}</h2>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400 sm:text-xs md:text-sm lg:text-[15px]">Update Otomatis</p>
            @else
                <h2 class="text-xl font-black text-amber-500 md:text-2xl lg:text-3xl">Live View</h2>
            @endif
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="relative flex-1 overflow-hidden rounded-[1.75rem] border border-white/70 bg-white/90 shadow-[0_18px_60px_-28px_rgba(15,23,42,0.28)] backdrop-blur md:rounded-[2rem] w-full">
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
                <div class="h-full w-full overflow-auto custom-scrollbar p-3 sm:p-4 md:p-6 lg:p-8">
                    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.24em] text-amber-500">Kategori</p>
                            <p class="mt-1 text-sm font-black uppercase tracking-[0.12em] text-slate-800 sm:text-base">{{ $match->name }}</p>
                        </div>
                        <div class="rounded-2xl border border-sky-100 bg-sky-50 px-4 py-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.24em] text-sky-500">Kelompok</p>
                            <p class="mt-1 text-sm font-black uppercase tracking-[0.12em] text-slate-800 sm:text-base">{{ $match->ageGroup?->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.24em] text-emerald-500">Peserta Tampil</p>
                            <p class="mt-1 text-sm font-black uppercase tracking-[0.12em] text-slate-800 sm:text-base">{{ $embuRanking->count() }}</p>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_10px_35px_-24px_rgba(15,23,42,0.28)]">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse text-left">
                                <thead class="bg-slate-100 text-slate-800">
                                    <tr class="text-slate-600">
                                        <th class="whitespace-nowrap border-b border-slate-200 px-3 py-3 text-center text-[10px] font-black uppercase tracking-[0.22em] sm:px-4 md:py-4 md:text-[13px]">Peringkat</th>
                                        <th class="whitespace-nowrap border-b border-slate-200 px-3 py-3 text-[10px] font-black uppercase tracking-[0.22em] sm:px-4 md:py-4 md:text-[13px]">Kontingen / Atlet</th>
                                        <th class="whitespace-nowrap border-b border-slate-200 px-3 py-3 text-center text-[10px] font-black uppercase tracking-[0.22em] sm:px-4 md:py-4 md:text-[13px]">Nilai Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($embuRanking as $index => $row)
                                @php
                                    $rank = $index + 1;
                                    // Highlight top 4
                                    $isTop = $rank <= 4;
                                    $hasScore = ($row['effective_score'] && $row['effective_score']->total_score > 0) || (isset($row['accumulated_score']) && $row['accumulated_score'] > 0);
                                @endphp
                                <tr class="{{ $loop->even ? 'bg-slate-50/70' : 'bg-white' }} transition-colors hover:bg-amber-50/40">
                                    {{-- Rank --}}
                                    <td class="border-r border-slate-100 px-3 py-3 text-center align-middle sm:px-4 md:px-5 md:py-5">
                                        @if($hasScore)
                                            <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-lg font-black sm:h-12 sm:w-12 md:h-16 md:w-16 md:rounded-2xl md:text-3xl lg:h-20 lg:w-20 lg:text-4xl {{ $rank === 1 ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/40' : ($rank === 2 ? 'bg-slate-300 text-slate-700' : ($rank === 3 ? 'bg-amber-700 text-white' : 'border border-slate-200 bg-slate-100 text-slate-500')) }}">
                                                {{ $rank }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 font-bold">-</span>
                                        @endif
                                    </td>

                                    {{-- Contingent / Athletes --}}
                                    <td class="border-r border-slate-100 px-3 py-3 align-middle sm:px-4 md:px-5 md:py-5">
                                        <div class="text-sm font-black uppercase tracking-[0.08em] text-slate-800 sm:text-lg md:text-2xl lg:text-3xl">
                                            {{ $row['contingent']->name ?? 'Tanpa Kontingen' }}
                                        </div>
                                        @if($row['athletes']->isNotEmpty())
                                            <div class="mt-1 text-xs font-medium text-slate-500 sm:text-sm md:mt-2 md:text-base lg:text-lg">
                                                {{ $row['athletes']->pluck('name')->join(' & ') }}
                                            </div>
                                        @endif
                                        @if($row['match_number_id'] != $match->id)
                                            <div class="mt-1">
                                                <span class="rounded bg-rose-50 px-2 py-0.5 text-[9px] font-black uppercase tracking-widest text-rose-500 border border-rose-100">
                                                    {{ $row['match_name'] }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Score --}}
                                    <td class="px-3 py-3 text-center align-middle sm:px-4 md:px-5 md:py-5">
                                        @if($hasScore)
                                            <div class="flex flex-col items-center gap-1.5">
                                                <div class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 md:rounded-2xl md:px-4 md:py-2">
                                                    <span class="text-lg font-black sm:text-2xl md:text-4xl lg:text-5xl {{ $isTop ? 'text-emerald-600' : 'text-slate-500' }}">
                                                        {{ number_format($row['accumulated_score'] ?? optional($row['effective_score'])->nilai_akhir ?? 0, 1) }}
                                                    </span>
                                                </div>
                                                @if(isset($row['penyisihan_score']) && $row['penyisihan_score'])
                                                    <div class="text-[10px] font-black tracking-[0.18em] text-slate-500 md:text-xs">
                                                        PENYISIHAN: {{ number_format($row['penyisihan_score']->nilai_akhir, 1) }} &nbsp;|&nbsp; FINAL: {{ $row['effective_score'] ? number_format($row['effective_score']->nilai_akhir, 1) : '0.0' }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="py-2 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400 sm:text-xs md:text-[15px] lg:text-lg">Belum Tampil</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($embuRanking->isEmpty())
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-base font-bold text-slate-500 md:text-lg">Belum ada data peserta.</td>
                                </tr>
                            @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
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

                <div class="relative h-full w-full cursor-grab overflow-auto custom-scrollbar bg-[linear-gradient(180deg,_rgba(248,250,252,0.96)_0%,_rgba(241,245,249,0.92)_100%)] p-3 select-none active:cursor-grabbing sm:p-4 md:p-6 lg:p-8" x-data="{
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

                    <div class="inline-flex min-w-max flex-col gap-10 pb-24 sm:gap-12 md:gap-16 md:pb-32">

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
