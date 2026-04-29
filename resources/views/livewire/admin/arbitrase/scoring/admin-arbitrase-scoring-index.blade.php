<div class="space-y-6 animate-in fade-in duration-500 h-full">

    {{-- ═══ HEADER ═══ --}}
    {{-- ═══ FIXED RESET BUTTON ═══ --}}
    <div class="fixed bottom-8 right-4 z-[100] md:bottom-10 md:right-6">
        <button wire:click="clearAllCourts"
            wire:confirm="PERINGATAN: Ini akan mereset status SEMUA lapangan & match yang sedang berjalan menjadi KOSONG. Lanjutkan?"
            class="flex items-center gap-2 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white shadow-2xl shadow-rose-200 rounded-2xl text-[13px] font-black uppercase tracking-widest transition-all active:scale-95 border-2 border-white/20 backdrop-blur-sm">
            <i class="fas fa-eraser text-lg"></i>
            <span class="hidden sm:inline">Reset Semua Lapangan</span>
            <span class="sm:hidden">Reset</span>
        </button>
    </div>

    {{-- ═══ HEADER ═══ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pr-32 md:pr-48">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Panitera</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-800 mt-1">Panggil Drawing per Lapangan
                — Filter by Kontingen, Pool, Court, Sesi, Rundown & Babak</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="resetFilters"
                class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-200 rounded-xl text-[15px] font-black uppercase tracking-widest transition-colors">
                <i class="fas fa-filter"></i> Reset Filter
            </button>
        </div>
    </div>

    {{-- ═══ ACTIVE COURT CARDS ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach($courts as $courtCard)
            @php
                $ad = $courtCard->activeDrawing;
                $adMatch = $courtCard->activeMatch;
                $adPool = $ad?->pool;
                $adSession = $ad?->sessionTime;
                $adRundown = $ad?->rundown;
                $adContingent = $ad?->registration?->contingent;
                $adType = $ad?->draft_type ?? $adMatch?->draft_type;
                $isActive = (bool) $courtCard->active_match_id;
            @endphp
            <div
                class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-20 h-20 bg-slate-50 rounded-full group-hover:scale-110 transition-transform">
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <span class="text-[15px] font-black text-slate-800 uppercase">{{ $courtCard->name }}</span>
                    <div class="flex items-center gap-2">
                        @if($isActive)
                            <button wire:click="clearCourt({{ $courtCard->id }})" title="Kosongkan Lapangan"
                                class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-colors">
                                <i class="fas fa-times text-[15px]"></i>
                            </button>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        @else
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-200"></span>
                        @endif
                    </div>
                </div>

                <div class="mt-3 relative z-10 space-y-1">
                    @if($isActive && $adMatch)
                        {{-- Match name & type --}}
                        <div class="flex items-center gap-1.5">
                            <span
                                class="inline-flex text-[7px] px-1.5 py-0.5 rounded font-black uppercase text-white {{ $adType === 'randori' ? 'bg-rose-500' : 'bg-emerald-500' }}">
                                {{ $adType ?? '?' }}
                            </span>
                            <span
                                class="text-[15px] font-black text-emerald-700 leading-tight truncate">{{ $adMatch->name }}</span>
                        </div>
                        {{-- Contingent --}}
                        @if($adContingent)
                            <p class="text-[15px] text-slate-900 font-bold uppercase truncate">
                                <i class="fas fa-shield-alt text-[7px] text-amber-500 mr-0.5"></i>{{ $adContingent->name }}
                            </p>
                        @endif
                        {{-- Pool + Session --}}
                        <div class="flex flex-wrap gap-1 mt-0.5">
                            @if($adPool)
                                <span
                                    class="text-[15px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded">Pool
                                    {{ $adPool->name }}</span>
                            @endif
                            @if($adSession)
                                <span
                                    class="text-[15px] font-black text-teal-700 bg-teal-50 border border-teal-100 px-1.5 py-0.5 rounded">{{ $adSession->name }}</span>
                            @endif
                            @if($adRundown)
                                <span
                                    class="text-[15px] font-black text-slate-900 bg-slate-50 border border-slate-200 px-1.5 py-0.5 rounded">{{ $adRundown->name ?? $adRundown->date }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-[15px] font-black text-slate-300 italic">KOSONG (Idle)</div>
                    @endif
                </div>

                {{-- Current Referees List --}}
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Panel Wasit</span>
                        <div class="flex items-center gap-2">
                            @if($courtCard->current_referees->count() > 0)
                                <button wire:confirm="Apakah Anda yakin ingin mengosongkan panel wasit untuk lapangan ini?" 
                                        wire:click="resetActiveReferees({{ $courtCard->id }})"
                                        class="text-[9px] font-black text-rose-500 uppercase hover:text-rose-700 transition-colors">
                                    Reset
                                </button>
                                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-tight">{{ $courtCard->current_referees->count() }} Wasit</span>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-1.5">
                        @forelse($courtCard->current_referees as $sch)
                            <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700 bg-slate-50/50 rounded-lg p-1 border border-slate-100/50">
                                <span class="w-4 h-4 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-[9px] font-black shrink-0 shadow-sm">{{ $sch->judge_index }}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-slate-700 truncate">{{ $sch->referee?->name }}</p>
                                    <p class="text-[8px] font-black text-indigo-500 uppercase tracking-tighter">
                                        @switch($sch->judge_index)
                                            @case(1) Wasit Nasional (Ketua) @break
                                            @case(2) Wasit Daerah 1 @break
                                            @case(3) Wasit Daerah 2 @break
                                            @case(4) Wasit Pembantu 1 @break
                                            @case(5) Wasit Pembantu 2 @break
                                            @default Juri {{ $sch->judge_index }}
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-[11px] text-slate-300 italic py-1">Belum ada penugasan untuk sesi ini.</div>
                        @endforelse
                    </div>
                </div>

                {{-- TV Monitor links --}}
                <div class="mt-4 grid grid-cols-2 xl:grid-cols-2 gap-2">
                    <a href="{{ route('admin.arbitrase.scoring.monitor', $courtCard->id) }}" target="_blank"
                        class="w-full flex items-center justify-center py-2 bg-slate-800 hover:bg-slate-700 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm"
                        title="Monitor Panggilan">Panggilan
                    </a>
                    <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.court', $courtCard->id) }}" target="_blank"
                        class="w-full flex items-center justify-center py-2 bg-amber-500 hover:bg-amber-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm"
                        title="Monitor Hasil Sementara">Hasil
                    </a>
                    <a href="{{ route('admin.arbitrase.scoring.monitor-timer.court', $courtCard->id) }}" target="_blank"
                        class="w-full flex items-center justify-center py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm"
                        title="Monitor Timer Lapangan">Timer
                    </a>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.arbitrase.scoring.monitor-referee.court', $courtCard->id) }}"
                            target="_blank"
                            class="flex-1 flex items-center justify-center py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm"
                            title="Monitor Daftar Wasit">Wasit
                        </a>
                        <button
                            wire:click="openRefereeModal({{ $courtCard->id }}, {{ $courtCard->activeDrawing?->rundown_id ?? 'null' }}, {{ $courtCard->activeDrawing?->session_time_id ?? 'null' }})"
                            class="w-8 h-8 flex items-center justify-center bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl transition-all flex-shrink-0"
                            title="Atur Penugasan Wasit">
                            <i class="fas fa-user-plus text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== FILTER CONTROLS ===== --}}
    <div class="bg-slate-900/5 border border-slate-200/60 rounded-2xl p-3 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            {{-- Filter: Category --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterType" placeholder="Semua Kategori" variant="filter">
                    <option value="">Semua Kategori</option>
                    <option value="embu">Embu</option>
                    <option value="randori">Randori</option>
                </x-select>
            </div>

            {{-- Filter: Gender --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterGender" placeholder="Semua Gender" variant="filter">
                    <option value="">Semua Gender</option>
                    <option value="Male">Laki-laki (Male)</option>
                    <option value="Female">Perempuan (Female)</option>
                    <option value="Mix">Campuran (Mix)</option>
                </x-select>
            </div>

            {{-- Filter: Age Group --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterAgeGroup" placeholder="Semua Kategori Umur" variant="filter">
                    <option value="">Semua Kategori Umur</option>
                    @foreach($ageGroups as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Match Number --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1 lg:col-span-2">
                <x-select wire:model.live="filterMatchNumber" placeholder="Semua Nomor Match" variant="filter">
                    <option value="">Semua Nomor Match</option>
                    @foreach($matchNumbers as $mn)
                        <option value="{{ $mn->id }}">{{ $mn->name }} - {{ $mn?->ageGroup?->name }} ({{ $mn->gender }})
                        </option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Kontingen --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterContingent" placeholder="Semua Kontingen" variant="filter">
                    <option value="">Semua Kontingen</option>
                    @foreach($contingents as $contingent)
                        <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Pool --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterPool" placeholder="Semua Pool" variant="filter">
                    <option value="">Semua Pool</option>
                    @foreach($pools as $pool)
                        <option value="{{ $pool->id }}">{{ $pool->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Round --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterRound" placeholder="Semua Babak" variant="filter">
                    <option value="">Semua Babak</option>
                    @foreach($rounds as $rnd)
                        <option value="{{ $rnd }}">{{ $rnd }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Court --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterCourt" placeholder="Semua Lapangan" variant="filter">
                    <option value="">Semua Lapangan</option>
                    @foreach($courts as $court)
                        <option value="{{ $court->id }}">{{ $court->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Session --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterSession" placeholder="Semua Sesi" variant="filter">
                    <option value="">Semua Sesi</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Rundown --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:model.live="filterRundown" placeholder="Semua Rundown" variant="filter">
                    <option value="">Semua Rundown</option>
                    @foreach($rundowns as $rundown)
                        <option value="{{ $rundown->id }}">{{ $rundown->name ?? $rundown->date }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Search --}}
            <div class="relative lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-2 h-full flex items-center">
                    <i class="fas fa-search text-slate-300 text-[15px] ml-2 mr-3"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari match / kontingen..."
                        class="w-full bg-transparent border-none text-[15px] font-bold text-black focus:ring-0 outline-none p-0">
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ MAIN TABLE ═══ --}}
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">

        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h2 class="text-[15px] font-black text-slate-800 uppercase tracking-widest">
                Daftar Panggilan <span class="text-amber-600">({{ $drawings->total() }} entri)</span>
            </h2>
            <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest">Per Atlet / Tim — Klik Panggil
                untuk aktifkan drawing</p>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            #</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Match / Kategori</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Total Peserta</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Pool</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Babak</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Lapangan</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Sesi</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Rundown</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center w-[140px]">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($drawings as $drawing)
                        @php
                            $mn = $drawing->matchNumber;
                            $pool = $drawing->pool;
                            $court = $drawing->court;
                            $session = $drawing->sessionTime;
                            $rundown = $drawing->rundown;
                            $isRandori = $drawing->draft_type === 'randori';
                            $detailRoute = $routePrefix . '.' . $drawing->draft_type . '.detail';
                        @endphp
                        <tr wire:key="drawing-{{ $drawing->id }}" class="hover:bg-amber-50/40 transition-colors">

                            {{-- Seq --}}
                            <td class="px-4 py-3 text-[15px] text-slate-800 font-bold border-r border-slate-200">
                                {{ $drawing->sequence_number ?? '-' }}
                            </td>

                            {{-- Match / Kategori --}}
                            <td class="px-4 py-3 align-top border-r border-slate-200">
                                <span
                                    class="inline-flex text-[15px] px-2 py-0.5 rounded font-black uppercase text-white shadow-sm mb-1 {{ $isRandori ? 'bg-rose-500' : 'bg-emerald-500' }}">
                                    {{ $drawing->draft_type }}
                                </span>
                                <div class="text-[12px] font-black text-slate-800 leading-tight">{{ $mn?->name ?? '—' }}
                                </div>
                                @if($mn?->ageGroup)
                                    <div class="text-[15px] text-slate-800 font-bold uppercase tracking-widest mt-0.5">
                                        {{ $mn->ageGroup->name }}
                                    </div>
                                @endif

                                {{-- Winner Info --}}
                                @php $juara = $mn->drawing_data['juara'] ?? []; @endphp
                                @if(!empty($juara))
                                    <div class="mt-3 p-2 bg-slate-50 rounded-lg border border-slate-100 space-y-1">
                                        @if(isset($juara[1]))
                                            <div class="text-[11px] font-black text-slate-800 flex items-center gap-1 leading-none">
                                                <span class="text-[15px]">🥇</span> <span
                                                    class="uppercase truncate">{{ $juara[1]['name'] }}</span>
                                            </div>
                                        @endif
                                        @if(isset($juara[2]))
                                            <div class="text-[11px] font-bold text-slate-500 flex items-center gap-1 leading-none">
                                                <span class="text-[15px]">🥈</span> <span
                                                    class="uppercase truncate">{{ $juara[2]['name'] }}</span>
                                            </div>
                                        @endif
                                        @if(isset($juara[3]))
                                            <div class="text-[11px] font-bold text-orange-600 flex items-center gap-1 leading-none">
                                                <span class="text-[15px]">🥉</span> <span
                                                    class="uppercase truncate">{{ $juara[3]['name'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Total Peserta --}}
                            <td class="px-4 py-3 align-top border-r border-slate-200">
                                <span
                                    class="inline-flex items-center justify-center bg-slate-100 text-black font-black text-[15px] px-2.5 py-1 rounded-lg border border-slate-200">
                                    <i class="fas fa-users mr-1.5 text-slate-800"></i> {{ $drawing->total_athletes }} Slot
                                    Peserta
                                </span>
                            </td>

                            {{-- Pool --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($pool)
                                    <span
                                        class="text-[15px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-lg">{{ $pool->name }}</span>
                                @else
                                    <span class="text-[15px] text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Round / Babak --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($drawing->round)
                                    <span
                                        class="text-[15px] font-black text-amber-700 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-lg">{{ $drawing->round }}</span>
                                @else
                                    <span class="text-[15px] text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Court --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($court)
                                    <div class="flex items-center gap-1.5">
                                        <span
                                            class="w-6 h-6 rounded bg-slate-800 text-white flex items-center justify-center text-[15px] font-black shrink-0">C{{ $court->order }}</span>
                                        <span class="text-[15px] font-black text-black">{{ $court->name }}</span>
                                    </div>
                                @else
                                    <span class="text-[15px] text-slate-300 italic">Belum diatur</span>
                                @endif
                            </td>

                            {{-- Session --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($session)
                                    <div class="text-[15px] font-black text-slate-800">{{ $session->name }}</div>
                                    <div class="text-[12px] font-bold text-slate-500">{{ substr($session->start_time, 0, 5) }} -
                                        {{ substr($session->end_time, 0, 5) }}
                                    </div>
                                @else
                                    <span class="text-[15px] text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Rundown --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($rundown)
                                    <div class="text-[15px] font-black text-slate-800">{{ $rundown->name }}</div>
                                    <div class="text-[15px] text-slate-800">{{ $rundown->date ?? '' }}</div>
                                @else
                                    <span class="text-[15px] text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-center align-middle border-r border-slate-200">
                                <div class="flex items-center justify-center gap-2">
                                    @if($mn)
                                        <a href="{{ route($detailRoute, $mn->id) }}?round={{ $drawing->round ?? '' }}&pool_id={{ $pool?->id ?? '' }}"
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-[13px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm"
                                            title="Input Nilai">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.match', $mn->id) }}?round={{ $drawing->round ?? '' }}&pool_id={{ $pool?->id ?? '' }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-xl text-[13px] font-black uppercase tracking-widest transition-all active:scale-95 border border-emerald-200"
                                            title="Monitor Hasil Sementara">
                                            <i class="fas fa-tv"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-14 text-center border-r border-slate-200">
                                <div
                                    class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                </div>
                                <h3 class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Tidak Ada Data
                                </h3>
                                <p class="text-[15px] text-slate-800 font-bold mt-1 max-w-sm mx-auto">
                                    Sesuaikan filter kontingen, pool, babak, lapangan, atau sesi di atas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100">
            {{ $drawings->links('livewire.admin.pagination') }}
        </div>
    </div>

    {{-- ===== MODAL: REFEREE ASSIGNMENT ===== --}}
    @if($showRefereeModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
            <div
                class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Penugasan Wasit</h2>
                            <p class="text-[13px] text-slate-500 font-bold uppercase tracking-widest mt-1">
                                {{ \App\Models\Court\Court::find($assigningCourtId)?->name ?? 'Lalu' }}
                            </p>
                        </div>
                        <button wire:click="$set('showRefereeModal', false)"
                            class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-800 hover:text-rose-500 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal /
                                Hari</label>
                            <x-select wire:model.live="assigningRundownId" placeholder="Pilih Tanggal" wire:key="select-rundown-{{ $assigningCourtId }}">
                                <option value="">Pilih Tanggal</option>
                                @foreach($rundowns as $rd)
                                    <option value="{{ $rd->id }}">{{ $rd->name ?? $rd->date }}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Sesi /
                                Shift</label>
                            <x-select wire:model.live="assigningSessionId" placeholder="Pilih Sesi" wire:key="select-session-{{ $assigningCourtId }}">
                                <option value="">Pilih Sesi</option>
                                @foreach($sessions as $ss)
                                    <option value="{{ $ss->id }}">{{ $ss->name }}</option>
                                @endforeach
                            </x-select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="text" wire:model.live.debounce.300ms="searchReferee"
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-[14px] font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all"
                                placeholder="Cari nama, sertifikasi, atau asal wasit...">
                        </div>

                        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                            <div class="max-h-[300px] overflow-y-auto custom-scrollbar p-4">
                                <div class="grid grid-cols-1 gap-2">
                                    @foreach($allReferees as $ref)
                                        <label
                                            class="flex items-center gap-4 p-3 rounded-xl border transition-all cursor-pointer 
                                                    {{ in_array((string) $ref->id, $selectedReferees) ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-slate-100 hover:border-indigo-200' }}">
                                            <input type="checkbox" wire:model.live="selectedReferees" value="{{ $ref->id }}"
                                                class="hidden">
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden shrink-0">
                                                @if($ref->photo)
                                                    <img src="{{ asset('storage/' . $ref->photo) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                        <i class="fas fa-user text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[14px] font-black text-slate-800 uppercase truncate">
                                                    {{ $ref->name }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                    {{ $ref->certification_level }} &bull; {{ $ref->province }}</p>
                                            </div>
                                            @if(in_array((string) $ref->id, $selectedReferees))
                                                <div
                                                    class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-black">
                                                    {{ array_search((string) $ref->id, $selectedReferees) + 1 }}
                                                </div>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between px-2">
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                Terpilih: {{ count($selectedReferees) }} / 5 Wasit
                            </span>
                            @if(count($selectedReferees) > 5)
                                <span
                                    class="text-[11px] font-black text-rose-500 uppercase tracking-widest animate-pulse">Maksimal
                                    5 wasit!</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-3">
                        <div class="flex gap-3">
                            <button wire:click="$set('showRefereeModal', false)"
                                class="flex-1 py-4 bg-slate-50 hover:bg-slate-100 text-slate-800 font-black uppercase tracking-widest rounded-2xl transition-all">
                                Batal
                            </button>
                            <button wire:click="saveRefereeAssignment"
                                class="flex-[2] py-4 bg-slate-900 hover:bg-indigo-600 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-slate-900/10 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ count($selectedReferees) !== 5 ? 'disabled' : '' }}>
                                Simpan Penugasan
                            </button>
                        </div>
                        
                        @if(count($selectedReferees) > 0)
                            <button 
                                wire:confirm="Apakah Anda yakin ingin menghapus seluruh penugasan wasit untuk sesi ini?"
                                wire:click="resetCourtReferees"
                                class="w-full py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 font-black uppercase tracking-widest text-[11px] rounded-xl transition-all border border-rose-100">
                                <i class="fas fa-trash-alt mr-2"></i> Kosongkan Penugasan Wasit
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>