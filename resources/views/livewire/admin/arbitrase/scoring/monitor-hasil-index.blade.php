<div wire:poll class="relative mx-auto flex h-screen min-h-screen w-full max-w-[1920px] flex-col overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.08),_transparent_32%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)] p-3 font-sans text-slate-800 sm:p-4 md:p-6 lg:p-8">

    {{-- HEADER --}}
    <div class="relative z-10 mb-3 flex w-full flex-col gap-3 rounded-2xl border border-white/70 bg-white/90 p-3 shadow-[0_12px_40px_-20px_rgba(15,23,42,0.3)] backdrop-blur sm:mb-4 sm:p-4 md:mb-4 md:flex-row md:items-center md:justify-between md:gap-4 md:rounded-[1.5rem] lg:p-5">
        <div class="flex items-center gap-2.5 sm:gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 via-amber-500 to-orange-500 shadow-md shadow-amber-500/20 sm:h-12 sm:w-12 md:h-14 md:w-14 lg:h-16 lg:w-16">
                <i class="fas fa-trophy text-lg text-white drop-shadow-md sm:text-xl md:text-2xl lg:text-3xl"></i>
            </div>
            <div class="min-w-0">
                <h1 class="flex flex-wrap items-center gap-1.5 text-base font-black tracking-tight text-slate-900 sm:gap-2 sm:text-lg md:text-2xl lg:text-3xl">
                    MONITOR HASIL
                    @if($match && $match->draft_type === 'randori')
                        <span class="rounded border border-rose-200 bg-rose-50 px-1.5 py-0.5 text-[8px] uppercase tracking-[0.2em] text-rose-600 sm:text-[9px] md:rounded-lg md:px-2 md:py-0.5 md:text-xs lg:text-sm">RANDORI</span>
                    @elseif($match && $match->draft_type === 'embu')
                        <span class="rounded border border-emerald-200 bg-emerald-50 px-1.5 py-0.5 text-[8px] uppercase tracking-[0.2em] text-emerald-600 sm:text-[9px] md:rounded-lg md:px-2 md:py-0.5 md:text-xs lg:text-sm">EMBU</span>
                    @endif
                </h1>
                @if($match)
                    <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500 sm:text-xs md:text-sm lg:text-[15px]">
                        {{ $match->mergeDetail?->merge?->name ?? $match->name }}
                        @if($match->ageGroup) <span class="text-slate-300 mx-1.5">•</span> <span class="text-slate-500">{{ $match->ageGroup->name }}</span> @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3 self-center md:self-auto">
            <div wire:ignore>
                <button id="auto-scroll-btn" onclick="window.toggleAutoScroll()" 
                    class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border bg-amber-500 text-white border-amber-600 shadow-md shadow-amber-500/20 select-none cursor-pointer active:scale-95">
                    <i class="fas fa-scroll"></i>
                    Auto Scroll: ON
                </button>
            </div>

            <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 px-3 py-2 text-center sm:px-4 md:min-w-[180px] md:text-right">
                @if($court)
                    <h2 class="text-lg font-black tracking-tight text-amber-500 sm:text-xl md:text-2xl lg:text-3xl">{{ $court->name }}</h2>
                    <p class="mt-0.5 text-[8px] font-bold uppercase tracking-[0.2em] text-slate-400 sm:text-[9px] md:text-xs lg:text-xs">Update Otomatis</p>
                @else
                    <h2 class="text-md font-black text-amber-500 md:text-lg lg:text-xl">Live View</h2>
                @endif
            </div>
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
                                    $hasScore = ($row['effective_score'] && (
                                        $row['effective_score']->nilai_akhir > 0 || 
                                        $row['effective_score']->judge_1 > 0 || 
                                        $row['effective_score']->judge_2 > 0 || 
                                        $row['effective_score']->judge_3 > 0 || 
                                        $row['effective_score']->judge_4 > 0 || 
                                        $row['effective_score']->judge_5 > 0
                                    )) || (isset($row['accumulated_score']) && $row['accumulated_score'] > 0);
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
                                                        {{ number_format($row['accumulated_score'] ?? optional($row['effective_score'])->effective_score ?? 0, 1) }}
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

                            <div class="flex items-start gap-4 sm:gap-6 md:gap-8 lg:gap-12 relative z-10">
                                @foreach($ubRounds as $roundIdx => $roundMatches)
                                    <div class="flex flex-col gap-4 sm:gap-6 justify-center min-w-[192px] sm:min-w-[224px] md:min-w-[256px] lg:min-w-[288px]" style="min-height: 100%;">
                                        <div class="text-center mb-4 sticky top-0 bg-slate-50/90 py-1.5 rounded font-black text-xs sm:text-sm md:text-[15px] lg:text-base text-slate-500 tracking-widest uppercase">
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
                                    <div class="flex flex-col gap-4 sm:gap-6 justify-center min-w-[192px] sm:min-w-[224px] md:min-w-[256px] lg:min-w-[288px] ml-4 sm:ml-6 md:ml-8 lg:ml-12">
                                        <div class="text-center mb-4 sticky top-0 bg-slate-50/90 py-1.5 rounded font-black text-xs sm:text-sm md:text-[15px] lg:text-base text-amber-600 tracking-widest uppercase">
                                            Grand Final
                                        </div>
                                        @php
                                            $gfNodeKey = "gf_0_0";
                                            $gfResult = $randoriResults[$gfNodeKey] ?? null;
                                            $gfWinnerNode = $gfResult?->winner;
                                        @endphp
                                        <div class="relative group mt-auto mb-auto bg-amber-50 rounded-2xl border-2 border-amber-200 shadow-md shadow-amber-500/10 scale-105 md:scale-110 origin-left">
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

                                <div class="flex items-start gap-4 sm:gap-6 md:gap-8 lg:gap-12 relative z-10">
                                    @foreach($lbRounds as $roundIdx => $roundMatches)
                                        <div class="flex flex-col gap-4 sm:gap-6 justify-center min-w-[192px] sm:min-w-[224px] md:min-w-[256px] lg:min-w-[288px]" style="min-height: 100%;">
                                            <div class="text-center mb-4 sticky top-0 bg-slate-50/90 py-1.5 rounded font-black text-xs sm:text-sm md:text-[15px] lg:text-base text-slate-500 tracking-widest uppercase">
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

<script>
    (function() {
        let autoScrollEnabled = true;
        let isPaused = false;
        let pauseTimer = null;
        let scrollDirection = 1; // 1 = down, -1 = up

        function handleUserInteraction() {
            isPaused = true;
            if (pauseTimer) clearTimeout(pauseTimer);
            pauseTimer = setTimeout(() => {
                isPaused = false;
            }, 5000); // Resume scrolling after 5 seconds of inactivity
        }

        setInterval(() => {
            if (!autoScrollEnabled || isPaused) return;

            const el = document.querySelector('.overflow-auto.custom-scrollbar');
            if (!el) return;

            if (el.scrollHeight <= el.clientHeight) return;

            if (!el.dataset.hasScrollListeners) {
                el.addEventListener('wheel', handleUserInteraction, { passive: true });
                el.addEventListener('pointerdown', handleUserInteraction, { passive: true });
                el.dataset.hasScrollListeners = 'true';
            }

            if (scrollDirection === 1) {
                el.scrollTop += 1;
                if (el.scrollTop + el.clientHeight >= el.scrollHeight - 2) {
                    isPaused = true;
                    setTimeout(() => {
                        scrollDirection = -1;
                        isPaused = false;
                    }, 3000); // Pause 3s at bottom
                }
            } else {
                el.scrollTop -= 4; // Scroll up faster
                if (el.scrollTop <= 0) {
                    el.scrollTop = 0;
                    scrollDirection = 1;
                    isPaused = true;
                    setTimeout(() => {
                        isPaused = false;
                    }, 3000); // Pause 3s at top
                }
            }
        }, 30);

        window.toggleAutoScroll = function() {
            autoScrollEnabled = !autoScrollEnabled;
            const btn = document.getElementById('auto-scroll-btn');
            if (btn) {
                if (autoScrollEnabled) {
                    btn.className = 'flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border bg-amber-500 text-white border-amber-600 shadow-md shadow-amber-500/20 select-none cursor-pointer active:scale-95';
                    btn.innerHTML = '<i class="fas fa-scroll"></i> Auto Scroll: ON';
                } else {
                    btn.className = 'flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200 select-none cursor-pointer active:scale-95';
                    btn.innerHTML = '<i class="fas fa-hand"></i> Auto Scroll: OFF';
                }
            }
        };
    })();
</script>
