<div class="space-y-6 animate-in fade-in duration-500 h-full">

    {{-- ═══ HEADER ═══ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Panitera</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-800 mt-1">Panggil Drawing per Lapangan — Filter by Kontingen, Pool, Court, Sesi, Rundown & Babak</p>
        </div>
        {{-- Reset All button --}}
        <div class="flex items-center gap-3">
            {{-- TIMER MONITOR --}}
            <div x-data="{
                    time: 0,
                    running: false,
                    interval: null,
                    formatTime() {
                        let m = Math.floor(this.time / 60000);
                        let s = Math.floor((this.time % 60000) / 1000);
                        let ms = Math.floor((this.time % 1000) / 10);
                        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}.${ms.toString().padStart(2, '0')}`;
                    },
                    start() {
                        if (!this.running) {
                            this.running = true;
                            let startTime = Date.now() - this.time;
                            this.interval = setInterval(() => {
                                this.time = Date.now() - startTime;
                            }, 10);
                        }
                    },
                    pause() {
                        this.running = false;
                        clearInterval(this.interval);
                    },
                    reset() {
                        this.pause();
                        this.time = 0;
                    }
                }"
                class="flex items-center gap-3 bg-slate-900 border border-slate-700 px-3 py-1.5 rounded-xl shadow-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-stopwatch text-slate-400"></i>
                    <div class="text-xl font-black text-emerald-400 font-mono tracking-wider min-w-[100px] text-center" x-text="formatTime()">00:00.00</div>
                </div>
                <div class="flex items-center gap-1 border-l border-slate-700 pl-3">
                    <button x-show="!running" @click="start()" class="w-8 h-8 flex items-center justify-center bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-400 rounded-lg transition-colors" title="Start"><i class="fas fa-play text-sm"></i></button>
                    <button x-show="running" @click="pause()" class="w-8 h-8 flex items-center justify-center bg-amber-500/20 hover:bg-amber-500/40 text-amber-400 rounded-lg transition-colors" title="Pause"><i class="fas fa-pause text-sm"></i></button>
                    <button @click="reset()" class="w-8 h-8 flex items-center justify-center bg-rose-500/20 hover:bg-rose-500/40 text-rose-400 rounded-lg transition-colors" title="Stop & Reset"><i class="fas fa-stop text-sm"></i></button>
                </div>
            </div>

            <button wire:click="clearAllCourts"
                    wire:confirm="Reset semua lapangan ke kosong?"
                    class="flex items-center gap-2 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl text-[15px] font-black uppercase tracking-widest transition-colors">
                <i class="fas fa-eraser"></i> Reset Semua Lapangan
            </button>
        </div>
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
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <a href="{{ route('admin.arbitrase.scoring.monitor', $courtCard->id) }}" target="_blank"
                       class="w-full flex items-center justify-center py-2 bg-slate-800 hover:bg-slate-700 text-white text-[13px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm"
                       title="Monitor Panggilan">
                        <i class="fas fa-tv mr-1 text-slate-300"></i> TV Panggilan
                    </a>
                    <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.court', $courtCard->id) }}" target="_blank"
                       class="w-full flex items-center justify-center py-2 bg-amber-500 hover:bg-amber-600 text-white text-[13px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm"
                       title="Monitor Hasil Sementara">
                        <i class="fas fa-trophy mr-1 text-amber-100"></i> TV Hasil
                    </a>
                    <a href="{{ route('admin.arbitrase.scoring.monitor-timer.court', $courtCard->id) }}" target="_blank"
                       class="w-full flex items-center justify-center py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[13px] font-black uppercase tracking-widest rounded-xl transition-colors active:scale-95 shadow-sm"
                       title="Monitor Timer Lapangan">
                        <i class="fas fa-stopwatch mr-1 text-emerald-100"></i> TV Timer
                    </a>
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
                        <option value="{{ $mn->id }}">{{ $mn->name }} - {{ $mn?->ageGroup?->name }} ({{ $mn->gender }})</option>
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
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Cari match / kontingen..."
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

                                {{-- Winner Info --}}
                                @php $juara = $mn->drawing_data['juara'] ?? []; @endphp
                                @if(!empty($juara))
                                    <div class="mt-3 p-2 bg-slate-50 rounded-lg border border-slate-100 space-y-1">
                                        @if(isset($juara[1]))
                                            <div class="text-[11px] font-black text-slate-800 flex items-center gap-1 leading-none">
                                                <span class="text-[15px]">🥇</span> <span class="uppercase truncate">{{ $juara[1]['name'] }}</span>
                                            </div>
                                        @endif
                                        @if(isset($juara[2]))
                                            <div class="text-[11px] font-bold text-slate-500 flex items-center gap-1 leading-none">
                                                <span class="text-[15px]">🥈</span> <span class="uppercase truncate">{{ $juara[2]['name'] }}</span>
                                            </div>
                                        @endif
                                        @if(isset($juara[3]))
                                            <div class="text-[11px] font-bold text-orange-600 flex items-center gap-1 leading-none">
                                                <span class="text-[15px]">🥉</span> <span class="uppercase truncate">{{ $juara[3]['name'] }}</span>
                                            </div>
                                        @endif
                                    </div>
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
                                @if($session)
                                    <div class="text-[15px] font-black text-slate-800">{{ $session->name }}</div>
                                    <div class="text-[12px] font-bold text-slate-500">{{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}</div>
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
                                <div class="flex flex-col gap-2">
                                    @if($mn)
                                        <a href="{{ route($detailRoute, $mn->id) }}?round={{ $drawing->round ?? '' }}&pool_id={{ $pool?->id ?? '' }}"
                                           class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-[13px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                            <i class="fas fa-edit"></i> Input Nilai
                                        </a>
                                        <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.match', $mn->id) }}?round={{ $drawing->round ?? '' }}&pool_id={{ $pool?->id ?? '' }}" target="_blank"
                                           class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-xl text-[13px] font-black uppercase tracking-widest transition-all active:scale-95 border border-emerald-200"
                                           title="Monitor Hasil Sementara">
                                            <i class="fas fa-tv"></i> Monitor
                                        </a>
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
