<div class="space-y-6 animate-in fade-in duration-500 h-full">

    {{-- ═══ HEADER ═══ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Panitera</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-800 mt-1">Panggil Drawing per Lapangan — Filter by Kontingen, Pool, Court, Sesi, Rundown & Babak</p>
        </div>
        {{-- Reset All button --}}
        <button wire:click="clearAllCourts"
                wire:confirm="Reset semua lapangan ke kosong?"
                class="flex items-center gap-2 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl text-[15px] font-black uppercase tracking-widest transition-colors">
            <i class="fas fa-eraser"></i> Reset Semua Lapangan
        </button>
    </div>

    {{-- ═══ ACTIVE COURT CARDS ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach($courts as $courtCard)
            @php
                $ad          = $courtCard->activeDrawing;
                $adMatch     = $courtCard->activeMatch;
                $adPool      = $ad?->pool;
                $adSession   = $ad?->sessionTime;
                $adRundown   = $ad?->rundown;
                $adContingent = $ad?->registration?->contingent;
                $adType      = $ad?->draft_type ?? $adMatch?->draft_type;
                $isActive    = (bool) $courtCard->active_match_id;
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-slate-50 rounded-full group-hover:scale-110 transition-transform"></div>
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
                            <span class="inline-flex text-[7px] px-1.5 py-0.5 rounded font-black uppercase text-white {{ $adType === 'randori' ? 'bg-rose-500' : 'bg-emerald-500' }}">
                                {{ $adType ?? '?' }}
                            </span>
                            <span class="text-[15px] font-black text-emerald-700 leading-tight truncate">{{ $adMatch->name }}</span>
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
                                <span class="text-[15px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded">Pool {{ $adPool->name }}</span>
                            @endif
                            @if($adSession)
                                <span class="text-[15px] font-black text-teal-700 bg-teal-50 border border-teal-100 px-1.5 py-0.5 rounded">{{ $adSession->name }}</span>
                            @endif
                            @if($adRundown)
                                <span class="text-[15px] font-black text-slate-900 bg-slate-50 border border-slate-200 px-1.5 py-0.5 rounded">{{ $adRundown->name ?? $adRundown->date }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-[15px] font-black text-slate-300 italic">KOSONG (Idle)</div>
                    @endif
                </div>

                {{-- TV Monitor links --}}
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.arbitrase.scoring.monitor', $courtCard->id) }}" target="_blank"
                       class="w-full flex items-center justify-center py-2 bg-slate-800 hover:bg-slate-700 text-white text-[15px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm">
                        <i class="fas fa-tv mr-1 text-slate-300"></i> TV Panggilan
                    </a>
                    <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.court', $courtCard->id) }}" target="_blank"
                       class="w-full flex items-center justify-center py-2 bg-amber-500 hover:bg-amber-600 text-white text-[15px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm">
                        <i class="fas fa-trophy mr-1 text-amber-100"></i> TV Hasil
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ═══ FILTER BAR ═══ --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-3">Filter Panggilan</p>
        <div class="flex flex-wrap gap-3">

            {{-- Kontingen --}}
            <select wire:model.live="filterContingent"
                    class="bg-white border border-slate-200 text-[15px] font-bold text-black px-3 py-2 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400 outline-none min-w-[160px]">
                <option value="">Semua Kontingen</option>
                @foreach($contingents as $contingent)
                    <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                @endforeach
            </select>

            {{-- Pool --}}
            <select wire:model.live="filterPool"
                    class="bg-white border border-slate-200 text-[15px] font-bold text-black px-3 py-2 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400 outline-none min-w-[130px]">
                <option value="">Semua Pool</option>
                @foreach($pools as $pool)
                    <option value="{{ $pool->id }}">{{ $pool->name }}</option>
                @endforeach
            </select>

            {{-- Round / Babak --}}
            <select wire:model.live="filterRound"
                    class="bg-white border border-slate-200 text-[15px] font-bold text-black px-3 py-2 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400 outline-none min-w-[140px]">
                <option value="">Semua Babak</option>
                @foreach($rounds as $rnd)
                    <option value="{{ $rnd }}">{{ $rnd }}</option>
                @endforeach
            </select>

            {{-- Court --}}
            <select wire:model.live="filterCourt"
                    class="bg-white border border-slate-200 text-[15px] font-bold text-black px-3 py-2 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400 outline-none min-w-[140px]">
                <option value="">Semua Lapangan</option>
                @foreach($courts as $court)
                    <option value="{{ $court->id }}">{{ $court->name }}</option>
                @endforeach
            </select>

            {{-- Session Time --}}
            <select wire:model.live="filterSession"
                    class="bg-white border border-slate-200 text-[15px] font-bold text-black px-3 py-2 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400 outline-none min-w-[140px]">
                <option value="">Semua Sesi</option>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->name }}</option>
                @endforeach
            </select>

            {{-- Rundown --}}
            <select wire:model.live="filterRundown"
                    class="bg-white border border-slate-200 text-[15px] font-bold text-black px-3 py-2 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400 outline-none min-w-[140px]">
                <option value="">Semua Rundown</option>
                @foreach($rundowns as $rundown)
                    <option value="{{ $rundown->id }}">{{ $rundown->name ?? $rundown->date }}</option>
                @endforeach
            </select>

            {{-- Draft Type --}}
            <select wire:model.live="filterType"
                    class="bg-white border border-slate-200 text-[15px] font-bold text-black px-3 py-2 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-400 outline-none min-w-[130px]">
                <option value="">Semua Kategori</option>
                <option value="embu">Embu</option>
                <option value="randori">Randori</option>
            </select>

            {{-- Search --}}
            <div class="relative flex-1 min-w-[180px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-[15px]"></i>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari match / kontingen..."
                       class="w-full bg-white border border-slate-200 rounded-xl pl-8 pr-3 py-2 text-[15px] font-bold text-black focus:outline-none focus:border-amber-400">
            </div>
        </div>
    </div>

    {{-- ═══ MAIN TABLE ═══ --}}
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">

        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h2 class="text-[15px] font-black text-slate-800 uppercase tracking-widest">
                Daftar Panggilan <span class="text-amber-600">({{ $drawings->total() }} entri)</span>
            </h2>
            <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest">Per Atlet / Tim — Klik Panggil untuk aktifkan drawing</p>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">#</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Match / Kategori</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Total Peserta</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Pool</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Babak</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Lapangan</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Sesi</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Rundown</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center w-[140px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($drawings as $drawing)
                        @php
                            $mn          = $drawing->matchNumber;
                            $pool        = $drawing->pool;
                            $court       = $drawing->court;
                            $session     = $drawing->sessionTime;
                            $rundown     = $drawing->rundown;
                            $isRandori   = $drawing->draft_type === 'randori';
                            $detailRoute = $routePrefix . '.' . $drawing->draft_type . '.detail';
                        @endphp
                        <tr wire:key="drawing-{{ $drawing->id }}" class="hover:bg-amber-50/40 transition-colors">

                            {{-- Seq --}}
                            <td class="px-4 py-3 text-[15px] text-slate-800 font-bold border-r border-slate-200">
                                {{ $drawing->sequence_number ?? '-' }}
                            </td>

                            {{-- Match / Kategori --}}
                            <td class="px-4 py-3 align-top border-r border-slate-200">
                                <span class="inline-flex text-[15px] px-2 py-0.5 rounded font-black uppercase text-white shadow-sm mb-1 {{ $isRandori ? 'bg-rose-500' : 'bg-emerald-500' }}">
                                    {{ $drawing->draft_type }}
                                </span>
                                <div class="text-[12px] font-black text-slate-800 leading-tight">{{ $mn?->name ?? '—' }}</div>
                                @if($mn?->ageGroup)
                                    <div class="text-[15px] text-slate-800 font-bold uppercase tracking-widest mt-0.5">{{ $mn->ageGroup->name }}</div>
                                @endif
                            </td>

                            {{-- Total Peserta --}}
                            <td class="px-4 py-3 align-top border-r border-slate-200">
                                <span class="inline-flex items-center justify-center bg-slate-100 text-black font-black text-[15px] px-2.5 py-1 rounded-lg border border-slate-200">
                                    <i class="fas fa-users mr-1.5 text-slate-800"></i> {{ $drawing->total_athletes }} Slot Peserta
                                </span>
                            </td>

                            {{-- Pool --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($pool)
                                    <span class="text-[15px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-lg">{{ $pool->name }}</span>
                                @else
                                    <span class="text-[15px] text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Round / Babak --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($drawing->round)
                                    <span class="text-[15px] font-black text-amber-700 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-lg">{{ $drawing->round }}</span>
                                @else
                                    <span class="text-[15px] text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Court --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($court)
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-6 h-6 rounded bg-slate-800 text-white flex items-center justify-center text-[15px] font-black shrink-0">C{{ $court->order }}</span>
                                        <span class="text-[15px] font-black text-black">{{ $court->name }}</span>
                                    </div>
                                @else
                                    <span class="text-[15px] text-slate-300 italic">Belum diatur</span>
                                @endif
                            </td>

                            {{-- Session --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                <span class="text-[15px] text-slate-900 font-bold">{{ $session?->name ?? '—' }}</span>
                            </td>

                            {{-- Rundown --}}
                            <td class="px-4 py-3 border-r border-slate-200">
                                @if($rundown)
                                    <div class="text-[15px] font-bold text-slate-900">{{ $rundown->name ?? '—' }}</div>
                                    <div class="text-[15px] text-slate-800">{{ $rundown->date ?? '' }}</div>
                                @else
                                    <span class="text-[15px] text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-center align-middle border-r border-slate-200">
                                <div class="flex flex-col gap-1.5 items-center">
                                    @if($court)
                                        {{-- Panggil berdasarkan drawing ID — court, match, reg, pool, session, rundown, draft_type sudah ada di drawing --}}
                                        <button wire:click="activateMatch({{ $drawing->id }})"
                                                class="inline-flex items-center justify-center gap-1 w-full px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-[15px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-sm shadow-amber-500/20">
                                            <i class="fas fa-bullhorn text-[15px]"></i> Panggil
                                        </button>
                                    @else
                                        <span class="text-[15px] text-slate-300 italic">No Court</span>
                                    @endif
                                    @if($mn)
                                        <div class="grid grid-cols-2 gap-1 w-full mt-1">
                                            <a href="{{ route($detailRoute, $mn->id) }}"
                                               class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-[15px] font-black uppercase tracking-widest transition-all active:scale-95">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.match', $mn->id) }}" target="_blank"
                                               class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[15px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm"
                                               title="Monitor Hasil Sementara">
                                                <i class="fas fa-tv text-[15px]"></i> Hasil
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-14 text-center border-r border-slate-200">
                                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                </div>
                                <h3 class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Tidak Ada Data</h3>
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

</div>
