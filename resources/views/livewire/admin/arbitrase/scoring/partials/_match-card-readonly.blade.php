@php
    $a1 = $m['athlete1'] ?? null;
    $a2 = $m['athlete2'] ?? null;
    $hasBothAthletes = $a1 && $a2;
    $hasSomeAthlete = $a1 || $a2;

    $isDone = !empty($winnerNode);

    $isGf = $bracket === 'gf';
    
    // Style adjustments for light TV Monitor
    $borderClass = $isGf ? 'border-amber-300 shadow-amber-500/10' : ($bracket === 'ub' ? 'border-rose-200' : 'border-indigo-200');
    if ($isDone) {
        $borderClass = $isGf ? 'border-amber-400 shadow-amber-500/20' : ($bracket === 'ub' ? 'border-rose-300 shadow-rose-500/10' : 'border-indigo-300 shadow-indigo-500/10');
    }
    
    // Active styling overrides
    $isActive = $isActive ?? false;
    if ($isActive) {
        $borderClass = 'border-blue-500 shadow-lg shadow-blue-500/30 scale-105 z-20 transition-all duration-300';
    }
@endphp

<div class="relative w-48 sm:w-56 md:w-64 lg:w-72 {{ $isActive ? 'z-20' : '' }}">
    {{-- Match label badge --}}
    <div class="absolute -top-2.5 left-2 z-10 px-1.5 py-0.5 bg-white text-slate-500 text-[9px] sm:text-[10px] md:text-xs lg:text-sm font-black rounded-lg border border-slate-200 uppercase tracking-wide shadow-sm">
        @if($isGf)
            Grand Final
        @else
            {{ strtoupper($bracket) }} · R{{ $roundIdx + 1 }} M{{ $matchIdx + 1 }}
        @endif
    </div>

    {{-- Active badge --}}
    @if($isActive)
        <div class="absolute -top-2.5 right-2 z-20 px-2 py-0.5 bg-blue-500 text-white text-[9px] sm:text-[10px] md:text-xs lg:text-sm font-black rounded-lg uppercase shadow-lg shadow-blue-500/40 animate-pulse flex items-center gap-1">
            <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 bg-white rounded-full"></span> ACTIVE
        </div>
    @elseif($isDone)
        <div class="absolute -top-2.5 right-2 z-10 px-1.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[9px] sm:text-[10px] md:text-xs lg:text-sm font-black rounded-lg uppercase shadow-sm">
            Selesai
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white rounded-2xl border-2 {{ $borderClass }} shadow-md overflow-hidden transition-all">
        @if(!$hasSomeAthlete)
            {{-- Empty / TBD --}}
            <div class="px-2 py-4 sm:px-3 sm:py-6 flex flex-col items-center justify-center gap-1">
                <i class="fas fa-clock text-slate-300 text-2xl sm:text-3xl"></i>
                <span class="text-[10px] sm:text-xs md:text-sm font-bold text-slate-400 uppercase tracking-wide mt-2">Menunggu...</span>
            </div>
        @else
            {{-- Athlete 1 (Merah/AKA) --}}
            <div class="px-2.5 py-2.5 sm:px-3 sm:py-3 md:py-4 border-b border-slate-100 flex items-center gap-2 sm:gap-3
                {{ $isDone && $winnerNode === 'athlete1' ? 'bg-rose-50' : '' }}">
                <div class="w-1.5 sm:w-2 h-8 sm:h-10 md:h-12 rounded-full shrink-0
                    {{ $isDone && $winnerNode === 'athlete1' ? 'bg-rose-500 shadow-sm shadow-rose-500/50' : 'bg-rose-200' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a1)
                        <div class="text-xs sm:text-sm md:text-base font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerNode === 'athlete1' ? 'text-slate-800' : 'text-slate-600' }}">
                            {{ $a1['name'] }}
                        </div>
                        @if($a1['contingent'] ?? null)
                            <div class="text-[10px] sm:text-xs md:text-sm font-bold text-slate-400 truncate mt-0.5">{{ $a1['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-sm md:text-base text-slate-300 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerNode === 'athlete1')
                    <span class="text-[10px] sm:text-[11px] md:text-xs font-black bg-rose-100 text-rose-600 border border-rose-200 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-lg shrink-0 shadow-sm">W</span>
                @endif
            </div>

            {{-- VS --}}
            <div class="py-0.5 sm:py-1 bg-slate-50 border-y border-slate-100 flex items-center justify-center text-[10px] sm:text-xs md:text-sm font-black text-slate-300 tracking-widest uppercase shadow-inner">vs</div>

            {{-- Athlete 2 (Putih/SHIRO) --}}
            <div class="px-2.5 py-2.5 sm:px-3 sm:py-3 md:py-4 flex items-center gap-2 sm:gap-3
                {{ $isDone && $winnerNode === 'athlete2' ? 'bg-indigo-50' : '' }}">
                <div class="w-1.5 sm:w-2 h-8 sm:h-10 md:h-12 rounded-full shrink-0
                    {{ $isDone && $winnerNode === 'athlete2' ? 'bg-indigo-500 shadow-sm shadow-indigo-500/50' : 'bg-indigo-200' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a2)
                        <div class="text-xs sm:text-sm md:text-base font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerNode === 'athlete2' ? 'text-slate-800' : 'text-slate-600' }}">
                            {{ $a2['name'] }}
                        </div>
                        @if($a2['contingent'] ?? null)
                            <div class="text-[10px] sm:text-xs md:text-sm font-bold text-slate-400 truncate mt-0.5">{{ $a2['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-sm md:text-base text-slate-300 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerNode === 'athlete2')
                    <span class="text-[10px] sm:text-[11px] md:text-xs font-black bg-indigo-100 text-indigo-600 border border-indigo-200 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-lg shrink-0 shadow-sm">W</span>
                @endif
            </div>
        @endif
    </div>
</div>

