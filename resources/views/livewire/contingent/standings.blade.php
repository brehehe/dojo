<div class="min-h-screen bg-slate-50 pb-32">

    {{-- Header --}}
    <div class="bg-white border-b border-slate-100 px-5 pt-14 pb-4 sticky top-0 z-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                <i class="fas fa-chart-bar text-indigo-500 text-base"></i>
            </div>
            <div>
                <h1 class="text-base font-black text-slate-900 leading-tight">Statistik & Klasemen</h1>
                <p class="text-[11px] text-slate-400 font-semibold">{{ $contingent->name }}</p>
            </div>
        </div>

        {{-- Sub-nav tabs: Hasil + Klasemen --}}
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
            <a href="{{ route('contingent.results') }}"
               class="flex-1 py-2 rounded-lg text-center text-[11px] font-black uppercase tracking-wider text-slate-500 transition-all">
                Rekap Hasil
            </a>
            <div class="flex-1 py-2 rounded-lg text-center text-[11px] font-black uppercase tracking-wider bg-white text-slate-900 shadow-sm transition-all">
                Klasemen
            </div>
        </div>

        {{-- Filter Embu / Randori --}}
        <div class="flex gap-2 mt-3">
            @foreach(['embu' => 'Embu', 'randori' => 'Randori'] as $val => $label)
                <button wire:click="$set('filterType', '{{ $val }}')"
                        class="flex-1 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all
                               {{ $filterType === $val ? 'bg-orange-500 text-white shadow-md shadow-orange-200' : 'bg-slate-100 text-slate-500' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="px-4 pt-5 space-y-6">

        {{-- ===== EMBU STANDINGS ===== --}}
        @if($filterType === 'embu')
            @forelse($standings as $matchNumberId => $scores)
                @php $matchNumber = $scores->first()->matchNumber; @endphp
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">

                    {{-- Match number header --}}
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-4 py-3">
                        <h3 class="font-black text-white text-sm leading-tight">{{ $matchNumber->name ?? '-' }}</h3>
                        <p class="text-purple-200 text-[10px] font-semibold mt-0.5">
                            {{ $matchNumber->ageGroup->name ?? '' }} ·
                            {{ $matchNumber->gender_indo ?? '' }}
                        </p>
                    </div>

                    {{-- Leaderboard rows --}}
                    <div class="divide-y divide-slate-50">
                        @foreach($scores->take(10) as $i => $score)
                            @php
                                $isMyContingent = in_array($score->registration_id, $registrationIds);
                            @endphp
                            <div class="flex items-center gap-3 px-4 py-3
                                        {{ $isMyContingent ? 'bg-orange-50 border-l-4 border-orange-400' : '' }}">

                                {{-- Rank --}}
                                <div class="w-8 text-center shrink-0">
                                    @if($score->rank === 1)
                                        <span class="text-lg">🥇</span>
                                    @elseif($score->rank === 2)
                                        <span class="text-lg">🥈</span>
                                    @elseif($score->rank === 3)
                                        <span class="text-lg">🥉</span>
                                    @else
                                        <span class="text-sm font-black text-slate-400">#{{ $score->rank }}</span>
                                    @endif
                                </div>

                                {{-- Contingent name --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-{{ $isMyContingent ? 'black' : 'bold' }} text-{{ $isMyContingent ? 'orange-600' : 'slate-700' }} text-sm truncate">
                                        {{ $score->registration->contingent->name ?? '-' }}
                                        @if($isMyContingent)
                                            <span class="text-[9px] text-orange-400 font-black ml-1">✦ ANDA</span>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-semibold">{{ $score->round_label ?? 'Penyisihan' }}</p>
                                </div>

                                {{-- Score --}}
                                <div class="text-right shrink-0">
                                    <div class="text-sm font-black text-slate-900">
                                        {{ $score->nilai_akhir ? number_format($score->nilai_akhir, 2) : ($score->total_score ? number_format($score->total_score, 2) : '-') }}
                                    </div>
                                    @if($score->denda && $score->denda > 0)
                                        <div class="text-[10px] text-red-400 font-bold">-{{ number_format($score->denda, 2) }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <i class="fas fa-chart-bar text-slate-300 text-2xl"></i>
                    </div>
                    <p class="font-black text-slate-400 text-sm">Belum ada klasemen Embu</p>
                    <p class="text-[11px] text-slate-300 mt-1">Klasemen akan tersedia setelah pertandingan berlangsung.</p>
                </div>
            @endforelse
        @endif

        {{-- ===== RANDORI BRACKET STANDINGS ===== --}}
        @if($filterType === 'randori')
            @forelse($standings as $matchNumber)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">

                    {{-- Match number header --}}
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-4 py-3">
                        <h3 class="font-black text-white text-sm leading-tight">{{ $matchNumber->name }}</h3>
                        <p class="text-blue-200 text-[10px] font-semibold mt-0.5">
                            {{ $matchNumber->ageGroup->name ?? '' }} · Randori
                        </p>
                    </div>

                    {{-- Participants from this contingent --}}
                    <div class="px-4 py-3">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Peserta Dari Kontingen Anda</p>
                        @php
                            $myDrawings = $matchNumber->drawings->whereIn('registration_id', $registrationIds);
                        @endphp
                        @forelse($myDrawings as $drawing)
                            <div class="flex items-center gap-2 py-2 border-b border-slate-50 last:border-0">
                                <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                                    <i class="fas fa-user text-orange-500 text-[10px]"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-black text-slate-900 truncate">
                                        {{ $drawing->registration->contingent->name ?? '-' }}
                                    </p>
                                    @if($drawing->pool)
                                        <p class="text-[10px] text-slate-400 font-semibold">Pool {{ $drawing->pool->name ?? '' }}</p>
                                    @endif
                                </div>
                                @if($drawing->round)
                                    <span class="text-[9px] font-black text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">{{ $drawing->round }}</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-slate-300 font-semibold text-center py-2">Tidak ada peserta dari kontingen ini</p>
                        @endforelse
                    </div>

                    {{-- Results summary --}}
                    @if($matchNumber->randoriResults->count() > 0)
                        <div class="border-t border-slate-100 px-4 py-3">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Hasil Pertandingan</p>
                            @foreach($matchNumber->randoriResults->take(5) as $result)
                                <div class="flex items-center justify-between py-1.5 border-b border-slate-50 last:border-0">
                                    <div class="text-[11px] text-slate-500 font-semibold">
                                        Node {{ $result->bracket_node ?? $result->bracket_node_index }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] font-black {{ $result->winner_color === 'red' ? 'text-red-500' : 'text-slate-300' }}">
                                            {{ $result->score_red ?? '-' }}
                                        </span>
                                        <span class="text-[9px] text-slate-300">vs</span>
                                        <span class="text-[11px] font-black {{ $result->winner_color === 'blue' ? 'text-blue-500' : 'text-slate-300' }}">
                                            {{ $result->score_blue ?? '-' }}
                                        </span>
                                        @if($result->winner)
                                            <span class="text-[9px] text-amber-500 font-black ml-1">
                                                <i class="fas fa-crown"></i> {{ Str::limit($result->winner->name, 10) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <i class="fas fa-sitemap text-slate-300 text-2xl"></i>
                    </div>
                    <p class="font-black text-slate-400 text-sm">Belum ada bagan Randori</p>
                    <p class="text-[11px] text-slate-300 mt-1">Bagan akan tampil setelah drawing dilakukan.</p>
                </div>
            @endforelse
        @endif
    </div>
</div>
