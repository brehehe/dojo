<div class="min-h-screen bg-slate-50 pb-32">

    {{-- Header --}}
    <div class="bg-white border-b border-slate-100 px-5 pb-4 sticky top-0 z-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                <i class="fas fa-trophy text-amber-500 text-base"></i>
            </div>
            <div>
                <h1 class="text-base font-black text-slate-900 leading-tight">Rekapitulasi Hasil</h1>
                <p class="text-[11px] text-slate-400 font-semibold">{{ $contingent->name }}</p>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-2">
            @foreach(['all' => 'Semua', 'embu' => 'Embu', 'randori' => 'Randori'] as $val => $label)
                <button wire:click="$set('filterType', '{{ $val }}')"
                        class="flex-1 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all
                               {{ $filterType === $val ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'bg-slate-100 text-slate-500' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="px-4 pt-5 space-y-8">

    <div class="px-4 pt-5 space-y-8">
        <div>
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-medal text-amber-500 text-xs"></i>
                </div>
                <h2 class="font-black text-slate-900 text-sm uppercase tracking-wider">Hasil Kejuaraan — {{ $filterType === 'all' ? 'Semua' : ucfirst($filterType) }}</h2>
            </div>

            @forelse($results as $champion)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-3"
                     wire:key="champ-{{ $champion->id }}">

                    {{-- Rank badge & Info --}}
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm shrink-0
                                {{ $champion->rank === 1 ? 'bg-amber-400 text-white shadow-sm shadow-amber-100' : ($champion->rank === 2 ? 'bg-slate-300 text-slate-700' : ($champion->rank === 3 ? 'bg-orange-300 text-white' : 'bg-slate-100 text-slate-500')) }}">
                                {{ $champion->rank === 1 ? '🥇' : ($champion->rank === 2 ? '🥈' : ($champion->rank === 3 ? '🥉' : '#' . $champion->rank)) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-[13px] leading-tight mb-0.5">
                                    {{ $champion->matchNumber->name ?? '-' }}
                                </p>
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded text-[9px] font-bold uppercase tracking-wider">
                                        {{ $champion->draft_type }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-semibold">
                                        {{ $champion->matchNumber->ageGroup->name ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Score display (only for Embu) --}}
                        @if($champion->accumulated_score > 0)
                            <div class="text-right shrink-0">
                                <div class="text-[15px] font-black text-slate-900">{{ number_format($champion->accumulated_score, 2) }}</div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Skor</div>
                            </div>
                        @endif
                    </div>

                    {{-- Athlete Names --}}
                    <div class="mt-3 pt-3 border-t border-slate-50">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-user-circle text-slate-300 text-xs mt-0.5"></i>
                            <p class="text-[11px] font-bold text-slate-600 leading-snug">
                                {{ $champion->athlete_names }}
                            </p>
                        </div>
                    </div>

                    {{-- Footer Info --}}
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-[9px] text-slate-300 font-medium">
                            <i class="far fa-clock mr-0.5"></i> {{ $champion->created_at->translatedFormat('d M Y') }}
                        </div>
                        @if($champion->confirmed_at)
                            <div class="text-[9px] text-green-500 font-black uppercase tracking-widest flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Hasil Resmi
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 text-center border border-slate-100">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-slate-200 text-3xl"></i>
                    </div>
                    <p class="text-sm font-black text-slate-300">Belum ada hasil kejuaraan yang tercatat.</p>
                </div>
            @endforelse
        </div>
    </div>
    </div>
</div>
