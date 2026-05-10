<div wire:poll.5s class="relative mx-auto flex h-screen min-h-screen w-full max-w-[1920px] flex-col overflow-hidden bg-[#f8fafc] font-sans text-slate-800">
    
    {{-- BACKGROUND DECORATION --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-blue-500/5 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-purple-500/5 blur-[120px] rounded-full"></div>
    </div>

    {{-- HEADER --}}
    <div class="relative z-10 flex w-full items-center justify-between border-b border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 shadow-lg shadow-blue-600/20">
                <i class="fas fa-list-ol text-3xl text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">
                    REKAPITULASI HASIL EMBU
                </h1>
                @if($match)
                    <p class="mt-1 text-lg font-bold text-slate-500 uppercase tracking-widest">
                        {{ $match->name }} <span class="mx-3 text-slate-300">|</span> {{ $match->ageGroup?->name ?? 'SEMUA KATEGORI' }}
                        @if($currentRound) <span class="mx-3 text-slate-300">|</span> <span class="text-blue-600">{{ $currentRound }}</span> @endif
                        @if($poolName) <span class="mx-3 text-slate-300">|</span> <span class="text-indigo-600">{{ $poolName }}</span> @endif
                    </p>
                @else
                    <p class="mt-1 text-lg font-bold text-slate-500 uppercase tracking-widest">HASIL PERTANDINGAN TERBARU</p>
                @endif
            </div>
        </div>

        <div class="text-right">
            @if($court)
                <div class="text-4xl font-black text-blue-600">{{ $court->name }}</div>
                <p class="text-sm font-bold uppercase tracking-widest text-slate-400">Update Otomatis</p>
            @else
                <div class="text-3xl font-black text-blue-600 italic">LIVE MONITOR</div>
            @endif
        </div>
    </div>

    {{-- TABLE HEADER (STAY TOP) --}}
    <div class="relative z-20 bg-slate-100 px-8 py-4 border-b border-slate-200">
        <div class="grid grid-cols-12 gap-4 text-xs font-black uppercase tracking-[0.2em] text-slate-500">
            <div class="col-span-1 text-center">Rank</div>
            <div class="col-span-4">Peserta / Kontingen</div>
            <div class="col-span-1 text-center">J1</div>
            <div class="col-span-1 text-center">J2</div>
            <div class="col-span-1 text-center">J3</div>
            <div class="col-span-1 text-center">J4</div>
            <div class="col-span-1 text-center">J5</div>
            <div class="col-span-1 text-center text-red-600">Denda</div>
            <div class="col-span-1 text-center text-blue-600">Total</div>
        </div>
    </div>

    {{-- SCROLLING CONTENT --}}
    <div class="relative z-10 flex-1 overflow-hidden" id="scroll-container" x-data="{
        scrollSpeed: 1,
        pos: 0,
        direction: 1,
        contentHeight: 0,
        containerHeight: 0,
        init() {
            this.containerHeight = $el.offsetHeight;
            this.contentHeight = this.$refs.content.offsetHeight;
            
            setInterval(() => {
                if (this.contentHeight <= this.containerHeight) return;
                
                this.pos += this.scrollSpeed * this.direction;
                
                if (this.pos >= this.contentHeight - this.containerHeight + 100) {
                    this.direction = -1;
                } else if (this.pos <= -50) {
                    this.direction = 1;
                }
                
                this.$refs.content.style.transform = `translateY(-${Math.max(0, this.pos)}px)`;
            }, 30);
        }
    }">
        <div x-ref="content" class="px-8 transition-transform duration-300 ease-linear">
            @forelse($scores as $index => $score)
                @php
                    $rank = $index + 1;
                    $rawVals = [
                        1 => (float)$score->judge_1,
                        2 => (float)$score->judge_2,
                        3 => (float)$score->judge_3,
                        4 => (float)$score->judge_4,
                        5 => (float)$score->judge_5,
                    ];
                    $scoredCount = array_filter($rawVals, fn($v) => $v > 0);
                    $minKey = null; $maxKey = null;
                    if (count($scoredCount) >= 2) {
                        $temp = $scoredCount;
                        asort($temp);
                        $keys = array_keys($temp);
                        $minKey = $keys[0];
                        $maxKey = $keys[count($keys)-1];
                    }
                @endphp
                <div class="grid grid-cols-12 gap-4 py-6 border-b border-slate-200 items-center {{ $rank <= 3 && $score->nilai_akhir > 0 ? 'bg-blue-50/50' : '' }}">
                    {{-- Rank --}}
                    <div class="col-span-1 flex justify-center">
                        @if($score->nilai_akhir > 0)
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl text-xl font-black {{ $rank === 1 ? 'bg-yellow-400 text-slate-900 shadow-sm' : ($rank === 2 ? 'bg-slate-200 text-slate-700' : ($rank === 3 ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-400')) }}">
                                {{ $rank }}
                            </div>
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl text-xl font-black bg-white text-slate-300 border border-slate-200">
                                -
                            </div>
                        @endif
                    </div>

                    {{-- Name / Contingent --}}
                    <div class="col-span-4">
                        <div class="text-xl font-black text-slate-900 uppercase truncate">
                            {{ $score->athletes->pluck('name')->join(' & ') }}
                        </div>
                        <div class="text-sm font-bold text-blue-600 uppercase tracking-widest mt-1">
                            {{ $score->registration?->contingent?->name ?? '-' }}
                        </div>
                    </div>

                    {{-- Judges --}}
                    @foreach([1,2,3,4,5] as $j)
                        @php 
                            $val = $rawVals[$j];
                            $isOut = ($j === $minKey || $j === $maxKey);
                        @endphp
                        <div class="col-span-1 text-center">
                            <span class="text-xl font-bold {{ $isOut ? 'line-through text-slate-300' : 'text-slate-800' }}">
                                {{ $val > 0 ? number_format($val, 1) : '-' }}
                            </span>
                        </div>
                    @endforeach

                    {{-- Denda --}}
                    <div class="col-span-1 text-center text-xl font-bold text-red-600">
                        {{ $score->denda > 0 ? '-' . number_format($score->denda, 1) : '0' }}
                    </div>

                    {{-- Total --}}
                    <div class="col-span-1 text-center">
                        <div class="text-2xl font-black text-blue-700">
                            {{ number_format($score->nilai_akhir, 1) }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-40 opacity-10">
                    <i class="fas fa-database text-8xl mb-6"></i>
                    <p class="text-2xl font-black uppercase tracking-widest">Belum Ada Data</p>
                </div>
            @endforelse
            
            {{-- Padding bottom to ensure last item is visible when bouncing back --}}
            <div class="h-40"></div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="relative z-20 flex w-full items-center justify-between border-t border-slate-200 bg-white p-4 shadow-inner">
        <div class="flex gap-8 text-xs font-black uppercase tracking-widest text-slate-400">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-yellow-400"></span> Juara 1
            </div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-slate-200"></span> Juara 2
            </div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-orange-100"></span> Juara 3
            </div>
        </div>
        <div class="text-xs font-bold text-slate-400">
            DOJO Digital Scoring System &copy; {{ date('Y') }}
        </div>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .line-through {
            text-decoration-thickness: 2px;
        }
    </style>
</div>
