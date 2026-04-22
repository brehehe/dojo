@php
    $a1 = $match['athlete1'] ?? null;
    $a2 = $match['athlete2'] ?? null;
    $winnerSlot = $match['winner'] ?? null;
    $hasBothAthletes = $a1 && $a2;
    $hasSomeAthlete = $a1 || $a2;

    $schemeBorder = match($colorScheme) {
        'orange' => 'border-orange-200',
        'amber'  => 'border-amber-200',
        default  => 'border-indigo-200',
    };
    $schemeDone = match($colorScheme) {
        'orange' => 'border-orange-400 shadow-orange-500/10',
        'amber'  => 'border-amber-400 shadow-amber-500/10',
        default  => 'border-indigo-400 shadow-indigo-500/10',
    };
    $schemeActive = match($colorScheme) {
        'orange' => 'border-orange-500 ring-2 ring-orange-300 ring-offset-1',
        'amber'  => 'border-amber-500 ring-2 ring-amber-300 ring-offset-1',
        default  => 'border-indigo-500 ring-2 ring-indigo-300 ring-offset-1',
    };
    $schemeCallBtn = match($colorScheme) {
        'orange' => 'bg-white border border-orange-200 text-orange-500 hover:bg-orange-500 hover:text-white hover:border-orange-500',
        'amber'  => 'bg-white border border-amber-200 text-amber-600 hover:bg-amber-500 hover:text-white',
        default  => 'bg-white border border-slate-200 text-slate-900 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600',
    };
    $schemeCallBtnActive = match($colorScheme) {
        'orange' => 'bg-orange-500 text-white shadow-md shadow-orange-500/30',
        'amber'  => 'bg-amber-500 text-white shadow-md shadow-amber-500/30',
        default  => 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30',
    };
@endphp

<div class="relative w-64">
    {{-- Match label badge --}}
    <div class="absolute -top-3 left-3 z-10 px-1.5 py-0.5 bg-slate-100 text-slate-800 text-[15px] font-black rounded border border-slate-200 uppercase tracking-wide">
        {{ $matchLabel }}
    </div>

    {{-- LIVE badge --}}
    @if($isActive)
        <div class="absolute -top-3 right-3 z-10 px-1.5 py-0.5 bg-amber-500 text-white text-[15px] font-black rounded uppercase animate-pulse">
            <i class="fas fa-broadcast-tower mr-0.5"></i>LIVE
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white rounded-xl border-2
        {{ $isDone ? $schemeDone . ' shadow-lg' : ($isActive ? $schemeActive : $schemeBorder . ' shadow-sm') }}
        overflow-hidden transition-all">

        @if(!$hasSomeAthlete)
            {{-- Empty / TBD --}}
            <div class="px-3 py-6 flex flex-col items-center justify-center gap-1">
                <i class="fas fa-clock text-slate-200 text-xl"></i>
                <span class="text-[15px] font-bold text-slate-300 uppercase tracking-wide">Menunggu...</span>
            </div>
        @else
            {{-- Athlete 1 (Merah/AKA) --}}
            <div class="px-3 py-2.5 border-b border-slate-100 flex items-center gap-2
                {{ $isDone && $winnerSlot === 'athlete1' ? 'bg-rose-50/60' : '' }}">
                <div class="w-1 h-8 rounded-full flex-shrink-0
                    {{ $isDone && $winnerSlot === 'athlete1' ? 'bg-rose-500' : 'bg-rose-200' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a1)
                        <div class="text-[15px] font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerSlot === 'athlete1' ? 'text-rose-700' : 'text-black' }}">
                            {{ $a1['name'] }}
                        </div>
                        @if($a1['contingent'] ?? null)
                            <div class="text-[15px] text-slate-800 truncate">{{ $a1['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-[15px] text-slate-300 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerSlot === 'athlete1')
                    <span class="text-[15px] font-black bg-rose-500 text-white px-1 py-0.5 rounded flex-shrink-0">W</span>
                @endif
            </div>

            {{-- VS --}}
            <div class="py-1 bg-slate-50 flex items-center justify-center text-[15px] font-black text-slate-300 tracking-widest uppercase">vs</div>

            {{-- Athlete 2 (Putih/SHIRO) --}}
            <div class="px-3 py-2.5 flex items-center gap-2
                {{ $isDone && $winnerSlot === 'athlete2' ? 'bg-indigo-50/60' : '' }}">
                <div class="w-1 h-8 rounded-full flex-shrink-0
                    {{ $isDone && $winnerSlot === 'athlete2' ? 'bg-indigo-500' : 'bg-indigo-200' }}">
                </div>
                <div class="flex-1 min-w-0">
                    @if($a2)
                        <div class="text-[15px] font-black uppercase tracking-tight truncate
                            {{ $isDone && $winnerSlot === 'athlete2' ? 'text-indigo-700' : 'text-black' }}">
                            {{ $a2['name'] }}
                        </div>
                        @if($a2['contingent'] ?? null)
                            <div class="text-[15px] text-slate-800 truncate">{{ $a2['contingent'] }}</div>
                        @endif
                    @else
                        <div class="text-[15px] text-slate-300 italic font-bold">TBD</div>
                    @endif
                </div>
                @if($isDone && $winnerSlot === 'athlete2')
                    <span class="text-[15px] font-black bg-indigo-500 text-white px-1 py-0.5 rounded flex-shrink-0">W</span>
                @endif
            </div>

            {{-- Action footer --}}
            @if($hasBothAthletes && !$isDone)
                <div class="border-t border-slate-100 bg-slate-50/60 px-3 py-2 flex items-center justify-end gap-1.5">
                    <button
                        wire:click="callMatch('{{ $bracket }}', {{ $roundIdx }}, {{ $matchIdx }})"
                        class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide transition-all
                            {{ $isActive ? $schemeCallBtnActive : $schemeCallBtn }}"
                        title="Panggil ke layar Wasit"
                    >
                        <i class="fas fa-bullhorn text-[15px]"></i> Panggil
                    </button>
                    <button
                        wire:click="openMatchModal('{{ $bracket }}', {{ $roundIdx }}, {{ $matchIdx }})"
                        class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-[15px] font-black uppercase tracking-wide bg-slate-900 text-white hover:bg-orange-600 transition-all shadow-sm"
                        title="Input Skor"
                    >
                        <i class="fas fa-edit text-[15px]"></i> Skor
                    </button>
                </div>
            @elseif($isDone)
                <div class="border-t border-slate-100 bg-slate-50/40 px-3 py-1.5 flex items-center justify-center">
                    <span class="text-[15px] font-black text-emerald-500 uppercase tracking-widest"><i class="fas fa-check mr-1"></i>Selesai</span>
                </div>
            @endif

        @endif
    </div>
</div>
