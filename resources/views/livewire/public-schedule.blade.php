<div class="min-h-screen bg-white text-slate-800 font-sans pb-16">
    @push('styles')
        <style>
            /* Override guest/body layout dark background */
            body {
                background-color: #ffffff !important;
                color: #0f0d0b !important;
            }
        </style>
    @endpush

    {{-- HERO / HEADER --}}
    <header class="relative bg-slate-50 border-b border-slate-200 py-10 px-6 sm:px-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-6 relative z-10">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-8 h-8 rounded bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center font-black text-amber-400 text-sm shadow-lg shadow-red-950/20">拳</span>
                    <span class="text-xs uppercase tracking-widest font-black text-slate-500">Smart Perkemi</span>
                </div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase font-serif">Jadwal Pertandingan</h1>
                <p class="text-sm text-slate-600 mt-1">Daftar lengkap jadwal tanding, lapangan, pool & sesi kenshi</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="export" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md">
                    <i class="fas fa-file-excel text-sm"></i> Export Excel
                </button>
            </div>
        </div>
    </header>

    {{-- FILTER CONTROLS --}}
    <section class="max-w-7xl mx-auto mt-8 px-4 sm:px-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search --}}
                <div class="relative">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">Cari Pertandingan</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2.5">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kenshi, kontingen, match..."
                            class="bg-transparent border-none text-sm font-semibold text-slate-800 focus:ring-0 outline-none p-0 w-full placeholder-slate-400">
                    </div>
                </div>

                {{-- Category tabs --}}
                <div>
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">Kategori</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-1 flex gap-1 h-[42px] items-center">
                        <button wire:click="$set('filterType', 'all')" class="flex-1 text-center py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition-all {{ $filterType === 'all' ? 'bg-red-700 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">Semua</button>
                        <button wire:click="$set('filterType', 'embu')" class="flex-1 text-center py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition-all {{ $filterType === 'embu' ? 'bg-red-700 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">Embu</button>
                        <button wire:click="$set('filterType', 'randori')" class="flex-1 text-center py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition-all {{ $filterType === 'randori' ? 'bg-red-700 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">Randori</button>
                    </div>
                </div>

                {{-- Court dropdown --}}
                <div>
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2 block">Lapangan</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-2 h-[42px] flex items-center">
                        <select wire:model.live="filterCourt" class="bg-transparent border-none text-xs font-bold text-slate-800 focus:ring-0 outline-none py-1.5 w-full cursor-pointer">
                            <option value="" class="text-slate-800">Semua Lapangan</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}" class="text-slate-800">{{ $court->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SCHEDULE LIST --}}
    <main class="max-w-7xl mx-auto mt-8 px-4 sm:px-6">
        @forelse($schedules as $date => $daySchedules)
            <div class="mb-10">
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="text-sm font-black text-slate-500 uppercase tracking-widest font-serif whitespace-nowrap">
                        <i class="far fa-calendar-alt text-red-600 mr-2"></i>
                        {{ $date !== 'Belum Dijadwalkan' ? \Carbon\Carbon::parse($date)->translatedFormat('d F Y') : 'Belum Dijadwalkan' }}
                    </h2>
                    <div class="w-full h-px bg-slate-200"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($daySchedules as $drawing)
                        @php
                            $isRandori = $drawing->draft_type === 'randori';
                        @endphp
                        <div class="bg-white border border-slate-200 hover:border-slate-350 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                            {{-- Top Meta Info --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex text-[9px] px-2.5 py-0.5 rounded font-black uppercase tracking-wider {{ $isRandori ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                                    {{ $drawing->draft_type }}
                                </span>
                                
                                @if($drawing->round)
                                    <span class="inline-flex text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-100">
                                        {{ $drawing->round }}
                                    </span>
                                @endif

                                <span class="text-[10px] font-black text-slate-500 font-mono tracking-tight bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                                    {{ $drawing->metadata['match_id_code'] ?? '-' }}
                                </span>
                            </div>

                            {{-- Match Category Name --}}
                            <h3 class="text-base font-black text-slate-900 font-serif leading-snug tracking-tight mb-4 min-h-[44px]">
                                {{ $drawing->matchNumber->name ?? 'Pertandingan' }}
                            </h3>

                            {{-- Participant Details --}}
                            <div class="mb-4 p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                @if($isRandori)
                                    {{-- Randori Sides --}}
                                    @php
                                        $redAthlete = $drawing->metadata['athlete_name'] ?? 'TBD';
                                        $redCont = $drawing->metadata['contingent'] ?? 'TBD';
                                        $blueAthlete = $drawing->metadata['blue_athlete_name'] ?? 'TBD';
                                        $blueCont = $drawing->metadata['blue_contingent'] ?? 'TBD';
                                    @endphp
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-black text-red-600 truncate">{{ $redAthlete }}</div>
                                            <div class="text-[9px] font-bold text-slate-500 uppercase truncate">{{ $redCont }}</div>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 bg-white border border-slate-200 px-1.5 py-0.5 rounded">VS</span>
                                        <div class="flex-1 min-w-0 text-right">
                                            <div class="text-xs font-black text-blue-600 truncate">{{ $blueAthlete }}</div>
                                            <div class="text-[9px] font-bold text-slate-500 uppercase truncate">{{ $blueCont }}</div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Embu Athletes list --}}
                                    @php
                                        $embAthlete = $drawing->metadata['athlete_name'] ?? 'TBD';
                                        $embCont = $drawing->metadata['contingent'] ?? 'TBD';
                                    @endphp
                                    <div>
                                        <div class="text-xs font-black text-slate-800">{{ $embAthlete }}</div>
                                        <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">{{ $embCont }}</div>
                                    </div>
                                @endif
                            </div>

                            {{-- Bottom Grid Details --}}
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 pt-3 border-t border-slate-100">
                                <div class="flex items-center gap-2 text-slate-500">
                                    <i class="fa-solid fa-map-pin text-[10px] text-red-600 shrink-0"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Court:</span>
                                    <span class="text-xs font-black text-slate-800 ml-auto">{{ $drawing->court->name ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-500">
                                    <i class="fa-solid fa-users-viewfinder text-[10px] text-amber-600 shrink-0"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pool:</span>
                                    <span class="text-xs font-black text-slate-800 ml-auto">{{ $drawing->metadata['pool_label'] ?? ($drawing->pool->name ?? '-') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-500 col-span-2">
                                    <i class="fa-solid fa-clock text-[10px] text-slate-400 shrink-0"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sesi:</span>
                                    <span class="text-xs font-black text-slate-800 ml-auto">
                                        {{ $drawing->sessionTime->name ?? '-' }} 
                                        @if(isset($drawing->metadata['start_time']))
                                            <span class="text-red-600">({{ $drawing->metadata['start_time'] }})</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-slate-50 border border-slate-200 rounded-3xl py-20 text-center max-w-xl mx-auto shadow-sm">
                <i class="fa-solid fa-calendar-xmark text-4xl text-slate-300 mb-4 block"></i>
                <h3 class="text-lg font-black text-slate-600 uppercase tracking-widest font-serif">Jadwal Tidak Ditemukan</h3>
                <p class="text-sm text-slate-500 mt-2 px-6">Tidak ada data jadwal pertandingan yang sesuai dengan filter pencarian Anda.</p>
            </div>
        @endforelse
    </main>
</div>
