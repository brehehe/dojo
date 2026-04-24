<div class="min-h-screen bg-slate-50">

    {{-- Header --}}
    <div class="bg-white border-b border-slate-100 px-5 pb-4 sticky top-0 z-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-orange-500 text-base"></i>
            </div>
            <div>
                <h1 class="text-base font-black text-slate-900 leading-tight">Jadwal Pertandingan</h1>
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

    <div class="px-4 pt-4 space-y-6">
        @forelse($schedules as $date => $daySchedules)
            {{-- Date header --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-2">
                        {{ $date !== 'Belum Dijadwalkan' ? \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') : 'Belum Dijadwalkan' }}
                    </span>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                <div class="space-y-3">
                    @foreach($daySchedules as $drawing)
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100"
                             wire:key="drawing-{{ $drawing->id }}">

                            {{-- Type badge + Seq number --}}
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    @if($drawing->draft_type === 'embu')
                                        <span class="bg-purple-100 text-purple-700 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg">
                                            <i class="fas fa-users mr-1"></i> Embu
                                        </span>
                                    @else
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg">
                                            <i class="fas fa-fist-raised mr-1"></i> Randori
                                        </span>
                                    @endif

                                    @if($drawing->round)
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-lg">
                                            {{ $drawing->round }}
                                        </span>
                                    @endif
                                </div>

                                @if($drawing->sequence_number)
                                    <span class="text-[11px] font-black text-slate-400">
                                        <i class="fas fa-hashtag text-[9px]"></i> {{ $drawing->sequence_number }}
                                    </span>
                                @endif
                            </div>

                            {{-- Match name --}}
                            <h3 class="font-black text-slate-900 text-sm mb-3 leading-snug">
                                {{ $drawing->matchNumber->name ?? '-' }}
                            </h3>

                            {{-- Info grid --}}
                            <div class="grid grid-cols-2 gap-2">
                                @if($drawing->court)
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <i class="fas fa-map-marker-alt text-[11px] text-orange-400 w-4 text-center"></i>
                                        <span class="text-[11px] font-semibold">{{ $drawing->court->name }}</span>
                                    </div>
                                @endif

                                @if($drawing->pool)
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <i class="fas fa-layer-group text-[11px] text-blue-400 w-4 text-center"></i>
                                        <span class="text-[11px] font-semibold">Pool {{ $drawing->pool->name }}</span>
                                    </div>
                                @endif

                                @if($drawing->sessionTime)
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <i class="fas fa-clock text-[11px] text-green-400 w-4 text-center"></i>
                                        <span class="text-[11px] font-semibold">{{ $drawing->sessionTime->name }}</span>
                                    </div>
                                @endif

                                @if($drawing->matchNumber->ageGroup ?? null)
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <i class="fas fa-users text-[11px] text-purple-400 w-4 text-center"></i>
                                        <span class="text-[11px] font-semibold">{{ $drawing->matchNumber->ageGroup->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-times text-slate-300 text-2xl"></i>
                </div>
                <p class="font-black text-slate-400 text-sm">Belum ada jadwal</p>
                <p class="text-[11px] text-slate-300 mt-1">Jadwal belum diumumkan untuk kontingen ini.</p>
            </div>
        @endforelse
    </div>
</div>
