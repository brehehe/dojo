@php
    $a1 = $m['athlete1'] ?? null;
    $a2 = $m['athlete2'] ?? null;
    $hasBothAthletes = $a1 && $a2;
    $hasSomeAthlete = $a1 || $a2;

    $isDone = !empty($winnerNode);

    $isGf = $bracket === 'gf';
    
    // Style adjustments for dark TV Monitor
    $borderClass = $isGf ? 'border-amber-500/50 shadow-amber-500/20' : ($bracket === 'ub' ? 'border-rose-500/30' : 'border-indigo-500/30');
    if ($isDone) {
        $borderClass = $isGf ? 'border-amber-500 shadow-amber-500/40' : ($bracket === 'ub' ? 'border-rose-500 shadow-rose-500/20' : 'border-indigo-500 shadow-indigo-500/20');
    }
@endphp

<div class="relative w-64 md:w-72">
    {{-- Match label badge --}}
    <div class="absolute -top-3 left-3 z-10 px-1.5 py-0.5 bg-slate-800 text-slate-400 text-[9px] md:text-[10px] font-black rounded border border-slate-700 uppercase tracking-wide">
        @if($isGf)
            Grand Final
        @else
            {{ strtoupper($bracket) }} · R{{ $roundIdx + 1 }} M{{ $matchIdx + 1 }}
        @endif
    </div>

    {{-- Is Done badge --}}
    @if($isDone)
        <div class="absolute -top-3 right-3 z-10 px-1.5 py-0.5 bg-emerald-500 text-white text-[9px] md:text-[10px] font-black rounded uppercase">
            Selesai
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-slate-800 rounded-xl border-2 {{ $borderClass }} shadow-lg overflow-hidden transition-all">
        @if(!$hasSomeAthlete)
            {{-- Empty / TBD --}}
            <div class="px-3 py-6 flex flex-col items-center justify-center gap-1">
                <i class="fas fa-clock text-slate-600 text-2xl"></i>
                <span class="text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-wide">Menunggu...</span>
            </div>
        @else
            {{-- Athlete 1 (Merah/AKA) --}}
            <div class="px-3 py-3 border-b border-slate-700 flex items-center gap-2
                {{ $isDone && $winnerNode === 'athlete1' ? 'bg-rose-900/30' : '' }}">
                <div class="w-1.5 h-10 rounded-full flex-shrink-0
                    {{ $isDone && $winnerNode === 'athlete1' ? 'bg-rose-500 shadow-lg shadow-rose-500' : 'bg-rose-500/30' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a1)
                        <div class="text-[11px] md:text-sm font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerNode === 'athlete1' ? 'text-white' : 'text-slate-300' }}">
                            {{ $a1['name'] }}
                        </div>
                        @if($a1['contingent'] ?? null)
                            <div class="text-[9px] md:text-[10px] text-slate-500 truncate">{{ $a1['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-[11px] md:text-sm text-slate-600 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerNode === 'athlete1')
                    <span class="text-[10px] font-black bg-rose-500 text-white px-1.5 py-0.5 rounded flex-shrink-0 shadow-md">W</span>
                @endif
            </div>

            {{-- VS --}}
            <div class="py-1 bg-slate-900 flex items-center justify-center text-[9px] font-black text-slate-600 tracking-widest uppercase">vs</div>

            {{-- Athlete 2 (Putih/SHIRO) --}}
            <div class="px-3 py-3 flex items-center gap-2
                {{ $isDone && $winnerNode === 'athlete2' ? 'bg-indigo-900/30' : '' }}">
                <div class="w-1.5 h-10 rounded-full flex-shrink-0
                    {{ $isDone && $winnerNode === 'athlete2' ? 'bg-indigo-500 shadow-lg shadow-indigo-500' : 'bg-indigo-500/30' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a2)
                        <div class="text-[11px] md:text-sm font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerNode === 'athlete2' ? 'text-white' : 'text-slate-300' }}">
                            {{ $a2['name'] }}
                        </div>
                        @if($a2['contingent'] ?? null)
                            <div class="text-[9px] md:text-[10px] text-slate-500 truncate">{{ $a2['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-[11px] md:text-sm text-slate-600 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerNode === 'athlete2')
                    <span class="text-[10px] font-black bg-indigo-500 text-white px-1.5 py-0.5 rounded flex-shrink-0 shadow-md">W</span>
                @endif
            </div>
        @endif
    </div>
</div>
