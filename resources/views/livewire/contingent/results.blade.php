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

        {{-- ===== EMBU RESULTS ===== --}}
        @if($filterType === 'all' || $filterType === 'embu')
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-users text-purple-500 text-xs"></i>
                    </div>
                    <h2 class="font-black text-slate-900 text-sm uppercase tracking-wider">Embu — Juara</h2>
                </div>

                @forelse($embuResults as $champion)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-3"
                         wire:key="champ-{{ $champion->id }}">

                        {{-- Rank badge --}}
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm shrink-0
                                    {{ $champion->rank === 1 ? 'bg-amber-400 text-white' : ($champion->rank === 2 ? 'bg-slate-300 text-slate-700' : ($champion->rank === 3 ? 'bg-orange-300 text-white' : 'bg-slate-100 text-slate-500')) }}">
                                    {{ $champion->rank === 1 ? '🥇' : ($champion->rank === 2 ? '🥈' : ($champion->rank === 3 ? '🥉' : '#' . $champion->rank)) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-900 text-sm leading-tight">
                                        {{ $champion->matchNumber->name ?? '-' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                        {{ $champion->matchNumber->ageGroup->name ?? '' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Score chip --}}
                            <div class="text-right shrink-0">
                                @if($champion->accumulated_score)
                                    <div class="text-base font-black text-slate-900">{{ number_format($champion->accumulated_score, 2) }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold">Total</div>
                                    <div class="text-[9px] text-slate-300 font-medium mt-1">
                                        <i class="far fa-clock mr-0.5"></i> {{ $champion->created_at->translatedFormat('d M Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Score breakdown --}}
                        @if($champion->penyisihan_score || $champion->final_score)
                            <div class="flex gap-3 mt-3 pt-3 border-t border-slate-50">
                                @if($champion->penyisihan_score)
                                    <div class="flex-1 bg-slate-50 rounded-xl p-2 text-center">
                                        <div class="text-xs font-black text-slate-700">{{ number_format($champion->penyisihan_score, 2) }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Penyisihan</div>
                                    </div>
                                @endif
                                @if($champion->final_score)
                                    <div class="flex-1 bg-slate-50 rounded-xl p-2 text-center">
                                        <div class="text-xs font-black text-slate-700">{{ number_format($champion->final_score, 2) }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Final</div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-6 text-center border border-slate-100">
                        <i class="fas fa-medal text-slate-200 text-3xl mb-2"></i>
                        <p class="text-sm font-black text-slate-300">Belum ada hasil Embu</p>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- ===== RANDORI RESULTS ===== --}}
        @if($filterType === 'all' || $filterType === 'randori')
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-fist-raised text-blue-500 text-xs"></i>
                    </div>
                    <h2 class="font-black text-slate-900 text-sm uppercase tracking-wider">Randori — Hasil Pertandingan</h2>
                </div>

                @forelse($randoriResults as $result)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 mb-3"
                         wire:key="randori-{{ $result->id }}">

                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                                {{ $result->matchNumber->name ?? '-' }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-300 bg-slate-50 px-2 py-1 rounded-lg">
                                Node {{ $result->bracket_node ?? '-' }}
                            </span>
                        </div>

                        {{-- Score vs --}}
                        <div class="flex items-center gap-3">
                            <div class="flex-1 text-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 mx-auto mb-1 flex items-center justify-center">
                                    <span class="text-[10px] font-black text-red-500">🔴</span>
                                </div>
                                <div class="text-xl font-black {{ $result->winner_color === 'red' ? 'text-red-500' : 'text-slate-300' }}">
                                    {{ $result->score_red ?? '-' }}
                                </div>
                            </div>
                            <div class="text-slate-300 font-black text-xs">VS</div>
                            <div class="flex-1 text-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 mx-auto mb-1 flex items-center justify-center">
                                    <span class="text-[10px] font-black text-blue-500">🔵</span>
                                </div>
                                <div class="text-xl font-black {{ $result->winner_color === 'blue' ? 'text-blue-500' : 'text-slate-300' }}">
                                    {{ $result->score_blue ?? '-' }}
                                </div>
                            </div>
                        </div>

                        @if($result->winner)
                            <div class="mt-3 pt-3 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-crown text-amber-400 text-xs"></i>
                                    <span class="text-xs font-black text-slate-700">{{ $result->winner->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-semibold capitalize">({{ $result->winner_color }})</span>
                                </div>
                                <div class="text-[9px] text-slate-300 font-medium italic">
                                    {{ $result->created_at->translatedFormat('d M H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-6 text-center border border-slate-100">
                        <i class="fas fa-fist-raised text-slate-200 text-3xl mb-2"></i>
                        <p class="text-sm font-black text-slate-300">Belum ada hasil Randori</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
