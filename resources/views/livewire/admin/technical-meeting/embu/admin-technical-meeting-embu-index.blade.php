<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Drawing Embu</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Technical Meeting / Drawing</p>
            </div>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row gap-2">
            <!-- Generate All Button -->
            <button wire:click="generateAllDrawings"
                    wire:confirm="Yakin ingin melakukan generate drawing otomatis untuk semua nomor Embu yang belum memiliki drawing?"
                    wire:loading.attr="disabled"
                    wire:target="generateAllDrawings"
                    class="w-full md:w-auto group bg-orange-600 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 hover:bg-orange-700 transition-all flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50">
                <span wire:loading.remove wire:target="generateAllDrawings"><i class="fas fa-magic text-xs"></i></span>
                <span wire:loading wire:target="generateAllDrawings"><i class="fas fa-spinner fa-spin text-xs"></i></span>
                <span class="uppercase text-[9px] tracking-[0.2em] whitespace-nowrap">Generate Semua</span>
            </button>
            <button onclick="window.print()" class="w-full md:w-auto group bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-slate-900/10 hover:bg-slate-700 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-print text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em] whitespace-nowrap">Cetak Drawing</span>
            </button>
        </div>
    </div>

    <!-- THB Rules Info Box -->
    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-6 py-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-400 text-white flex items-center justify-center shrink-0 mt-0.5">
                <i class="fas fa-scroll text-xs"></i>
            </div>
            <div class="flex-1">
                <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest mb-3">📜 Peraturan Pertandingan (THB Pasal H)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-lg bg-amber-200 text-amber-700 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">≤9</span>
                        <p class="text-[10px] text-amber-700 font-semibold leading-relaxed"><strong>2 Babak</strong> (Penyisihan + Final) → Nilai digabung &amp; dirata-rata</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-lg bg-amber-200 text-amber-700 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5">≥10</span>
                        <p class="text-[10px] text-amber-700 font-semibold leading-relaxed"><strong>Sistem Pool</strong></p>
                    </div>
                    <div class="flex items-start gap-2 md:col-start-2 md:-mt-2">
                        <div class="space-y-1 text-[10px] text-amber-600 font-medium leading-relaxed">
                            <p>• <strong>2 Pool</strong> (10–11 peserta): Rank 1,2,3,4 per pool → <strong>8 Finalis</strong></p>
                            <p>• <strong>3 Pool</strong> (12–17 peserta): Rank 1,2,3 per pool → <strong>9 Finalis</strong></p>
                            <p>• <strong>4 Pool</strong> (≥18 peserta): Rank 1,2 per pool → <strong>8 Finalis</strong></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 md:col-span-2">
                        <span class="w-5 h-5 rounded-lg bg-amber-200 text-amber-700 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5"><i class="fas fa-users"></i></span>
                        <p class="text-[10px] text-amber-700 font-semibold leading-relaxed"><strong>Embu Beregu:</strong> Komposisi Tandoku di awal &amp; akhir, Paired di tengah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div x-data="{ globalTab: 'sebelum' }" class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100 min-h-[600px]">
        
        <!-- Global Tab Header -->
        <div class="flex gap-4 mb-8 border-b-2 border-slate-100 px-1">
            <button @click="globalTab = 'sebelum'" 
                class="px-4 py-3 text-[11px] uppercase font-black tracking-widest border-b-4 -mb-[3px] transition-all duration-200"
                :class="globalTab === 'sebelum' ? 'border-orange-500 text-orange-600' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-200'">
                <i class="fas fa-users mr-2"></i> Daftar Pendaftar Atlet
            </button>
            <button @click="globalTab = 'hasil'" 
                class="px-4 py-3 text-[11px] uppercase font-black tracking-widest border-b-4 -mb-[3px] transition-all duration-200"
                :class="globalTab === 'hasil' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-200'">
                <i class="fas fa-project-diagram mr-2"></i> Hasil Drawing & Pool
            </button>
        </div>

        @forelse($matchSummary as $gender => $ageGroups)
            <div class="gender-block mb-12" wire:key="gender-{{ $gender }}">
                <!-- GENDER HEADER -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-[2px] flex-1 bg-slate-100"></div>
                    <div class="flex items-center gap-2 bg-slate-900 text-white px-8 py-3 rounded-full shadow-xl">
                        <i class="fas @if($gender == 'Male') fa-mars @elseif($gender == 'Female') fa-venus @else fa-venus-mars @endif text-xs text-orange-400"></i>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em]">{{ $gender == 'Male' ? 'LAKI-LAKI' : ($gender == 'Female' ? 'PEREMPUAN' : 'CAMPURAN / MIX') }}</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100"></div>
                </div>

                <div class="space-y-12 pl-4 md:pl-10 border-l-4 border-slate-50">
                    @foreach($ageGroups as $ageGroupName => $matches)
                        <div class="age-group-block" wire:key="age-{{ $gender }}-{{ $ageGroupName }}">
                            <!-- AGE GROUP HEADER -->
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-[20px] flex items-center justify-center font-black shadow-sm border border-indigo-100/50">
                                    {{ substr($ageGroupName, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-slate-800 uppercase tracking-tight leading-none">{{ $ageGroupName }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">KATEGORI KELOMPOK USIA</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-12">
                                @foreach($matches as $mId => $data)
                                    <div class="match-block animate-in slide-in-from-bottom-4 duration-500" wire:key="match-{{ $mId }}">

                                        <!-- Match Title Row -->
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 px-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-orange-500 shadow-sm shadow-orange-500/20"></div>
                                                <h4 class="text-[13px] font-black text-slate-700 uppercase tracking-wide">{{ $data['name'] }}</h4>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @php
                                                    $byContingent = collect($data['athletes'])->groupBy('contingent');
                                                    $totalTeams   = $byContingent->count();
                                                    $drawing      = $data['drawing_data'];
                                                    $drawingAt    = $data['drawing_at'];
                                                @endphp

                                                <!-- Team count badge -->
                                                <span class="text-[9px] font-black text-slate-400 bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 uppercase tracking-widest">
                                                    {{ $totalTeams }} Kontingen
                                                </span>

                                                @if($drawing)
                                                    <!-- Reset button -->
                                                    <button wire:click="resetDrawing({{ $mId }})"
                                                            wire:confirm="Reset drawing untuk nomor ini? Data drawing akan dihapus."
                                                            class="inline-flex items-center gap-1.5 text-[9px] font-black text-rose-500 border border-rose-200 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg uppercase tracking-widest transition active:scale-95">
                                                        <i class="fas fa-redo text-[8px]"></i> Reset
                                                    </button>
                                                    <!-- Regenerate -->
                                                    <button wire:click="generateDrawing({{ $mId }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="generateDrawing({{ $mId }})"
                                                            class="inline-flex items-center gap-1.5 text-[9px] font-black text-orange-600 border border-orange-200 bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg uppercase tracking-widest transition active:scale-95 disabled:opacity-50">
                                                        <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-sync text-[8px]"></i> Regenerate</span>
                                                        <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[8px]"></i> Generating...</span>
                                                    </button>
                                                @else
                                                    <!-- Generate button -->
                                                    <button wire:click="generateDrawing({{ $mId }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="generateDrawing({{ $mId }})"
                                                            class="inline-flex items-center gap-1.5 text-[9px] font-black text-white bg-slate-900 hover:bg-slate-700 px-4 py-2 rounded-lg uppercase tracking-widest transition active:scale-95 shadow-sm disabled:opacity-50">
                                                        <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-dice text-[8px]"></i> Generate Drawing</span>
                                                        <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[8px]"></i> Generating...</span>
                                                    </button>
                                                @endif

                                                <span class="text-[10px] font-black text-slate-300 uppercase italic tracking-widest">Entry #{{ $mId }}</span>
                                            </div>
                                        </div>

                                        <!-- Tabs Wrapper -->
                                        <div class="mt-4">
                                            <!-- Tab Content: Hasil Generate -->
                                            <div x-show="globalTab === 'hasil'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                                        @if($drawing)
                                            {{-- ===== DRAWING RESULT ===== --}}

                                            <!-- Format Banner -->
                                            <div class="mb-5 flex flex-col sm:flex-row sm:items-center gap-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl px-5 py-4">
                                                <div class="flex items-center gap-3 flex-1">
                                                    <div class="w-8 h-8 rounded-xl bg-orange-500 flex items-center justify-center shrink-0">
                                                        <i class="fas @if($drawing['format'] === '2_babak') fa-layer-group @else fa-object-group @endif text-white text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Format Pertandingan (THB Pasal H)</p>
                                                        <p class="text-[11px] font-black text-white mt-0.5">{{ $drawing['description'] }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-4 shrink-0">
                                                    @if($drawing['format'] === 'pool')
                                                        <div class="text-center">
                                                            <p class="text-[10px] font-black text-orange-400">{{ $drawing['pool_count'] }}</p>
                                                            <p class="text-[8px] text-slate-400 font-bold uppercase">Pool</p>
                                                        </div>
                                                        <div class="w-px h-6 bg-slate-700"></div>
                                                        <div class="text-center">
                                                            <p class="text-[10px] font-black text-blue-400">{{ $drawing['qualifiers'] }}</p>
                                                            <p class="text-[8px] text-slate-400 font-bold uppercase">Lolos/Pool</p>
                                                        </div>
                                                        <div class="w-px h-6 bg-slate-700"></div>
                                                    @endif
                                                    <div class="text-center">
                                                        <p class="text-[10px] font-black text-emerald-400">{{ $drawing['total_entries'] }}</p>
                                                        <p class="text-[8px] text-slate-400 font-bold uppercase">Kontingen</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Generated at info -->
                                            @if($drawingAt)
                                                <p class="text-[9px] text-slate-300 font-bold uppercase tracking-widest mb-4 px-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Drawing dibuat: {{ \Carbon\Carbon::parse($drawingAt)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </p>
                                            @endif

                                            <!-- Pools Grid -->
                                            <div class="grid grid-cols-1 @if($drawing['pool_count'] >= 2) md:grid-cols-2 @endif @if($drawing['pool_count'] >= 4) xl:grid-cols-2 @endif gap-5">
                                                @foreach($drawing['pools'] as $poolName => $poolEntries)
                                                    <div class="rounded-[20px] border-2 border-slate-100 overflow-hidden">

                                                        <!-- Pool Header -->
                                                        <div class="flex items-center justify-between px-5 py-3
                                                            @if($poolName === 'A' || $poolName === 'PENYISIHAN') bg-indigo-600
                                                            @elseif($poolName === 'B') bg-violet-600
                                                            @elseif($poolName === 'C') bg-teal-600
                                                            @else bg-rose-600
                                                            @endif">
                                                            <div class="flex items-center gap-2">
                                                                <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center">
                                                                    <i class="fas fa-layer-group text-white text-[10px]"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="text-[9px] font-black text-white/60 uppercase tracking-widest">
                                                                        {{ $drawing['format'] === '2_babak' ? 'Babak' : 'Pool' }}
                                                                    </p>
                                                                    <p class="text-[13px] font-black text-white uppercase leading-none">{{ $poolName }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="text-[10px] font-black text-white">{{ count($poolEntries) }}</p>
                                                                <p class="text-[8px] text-white/60 font-bold uppercase tracking-widest">Kontingen</p>
                                                            </div>
                                                        </div>

                                                        <!-- Pool Entries Table -->
                                                        <div class="bg-white overflow-x-auto">
                                                            <table class="w-full text-left border-collapse">
                                                                <thead>
                                                                    <tr class="border-b border-slate-50 bg-slate-50/60">
                                                                        <th class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-wider text-center w-12">Court</th>
                                                                        <th class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-wider">Kontingen</th>
                                                                        <th class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-wider text-center w-24">Urutan Tampil</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-slate-50">
                                                                    @foreach($poolEntries as $entry)
                                                                        <tr class="hover:bg-slate-50/40 transition-colors">
                                                                            <td class="px-4 py-3.5 text-center">
                                                                                <span class="w-7 h-7 rounded-xl
                                                                                    @if($poolName === 'A' || $poolName === 'PENYISIHAN') bg-indigo-100 text-indigo-600
                                                                                    @elseif($poolName === 'B') bg-violet-100 text-violet-600
                                                                                    @elseif($poolName === 'C') bg-teal-100 text-teal-600
                                                                                    @else bg-rose-100 text-rose-600
                                                                                    @endif
                                                                                    inline-flex items-center justify-center text-[11px] font-black">
                                                                                    {{ $entry['court_order'] }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="px-4 py-3.5">
                                                                                <div class="flex items-center gap-2">
                                                                                    <i class="fas fa-shield-alt text-slate-300 text-[10px]"></i>
                                                                                    <span class="text-[11px] font-black text-slate-700 uppercase tracking-tight">{{ $entry['contingent'] }}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="px-4 py-3.5 text-center">
                                                                                <span class="inline-flex items-center gap-1 text-[9px] font-black text-slate-500 bg-slate-100 rounded-lg px-2.5 py-1 uppercase tracking-wider">
                                                                                    <i class="fas fa-sort-numeric-up text-[8px]"></i>
                                                                                    Tampil ke-{{ $entry['court_order'] }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        @if($drawing['format'] === 'pool' && $drawing['qualifiers'] > 0)
                                                            <div class="px-5 py-2.5 bg-slate-50 border-t border-slate-100">
                                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                                                    <i class="fas fa-trophy text-amber-400 mr-1"></i>
                                                                    Rank 1–{{ $drawing['qualifiers'] }} lolos ke <strong class="text-slate-600">Final</strong>
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Divider -->
                                            <div class="mt-8 mb-6 h-px bg-slate-100"></div>

                                        @else
                                            {{-- ===== NO DRAWING YET ===== --}}
                                            <div class="bg-slate-50 rounded-2xl py-8 px-6 text-center border border-dashed border-slate-200 mb-6">
                                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100 shadow-sm">
                                                    <i class="fas fa-dice text-slate-300 text-lg"></i>
                                                </div>
                                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Drawing belum dibuat</p>
                                                <p class="text-[10px] text-slate-300 font-medium mt-1">Klik <strong>Generate Drawing</strong> untuk membuat urutan tampil otomatis</p>
                                            </div>
                                            @endif
                                            </div>

                                            <!-- Tab Content: Sebelum Generate (Detail Atlet) -->
                                            <div x-show="globalTab === 'sebelum'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                                                {{-- ===== ATHLETE DETAIL TABLE (grouped per contingent) ===== --}}
                                                <div class="mt-2">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">
                                                <i class="fas fa-users mr-1"></i> Detail Atlet per Kontingen
                                            </p>

                                            @php
                                                $byContingent = collect($data['athletes'])->groupBy('contingent');
                                            @endphp

                                            <div class="space-y-4">
                                                @foreach($byContingent as $contingentName => $contingentAthletes)
                                                    @php $contingentNum = $loop->index + 1; @endphp

                                                    <!-- Find pool assignment for this contingent -->
                                                    @php
                                                        $assignedPool  = null;
                                                        $assignedCourt = null;
                                                        if ($drawing) {
                                                            foreach ($drawing['pools'] as $pName => $pEntries) {
                                                                foreach ($pEntries as $pEntry) {
                                                                    if ($pEntry['contingent'] === $contingentName) {
                                                                        $assignedPool  = $pName;
                                                                        $assignedCourt = $pEntry['court_order'];
                                                                        break 2;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    <div class="rounded-[20px] border-2 border-slate-100 overflow-hidden shadow-sm">
                                                        <!-- Contingent Header -->
                                                        <div class="flex items-center gap-3 px-5 py-3 bg-slate-50 border-b border-slate-100">
                                                            <div class="w-7 h-7 rounded-xl bg-slate-900 text-white flex items-center justify-center text-[10px] font-black shrink-0">
                                                                {{ $contingentNum }}
                                                            </div>
                                                            <div class="flex items-center gap-2 flex-1">
                                                                <i class="fas fa-shield-alt text-orange-500 text-xs"></i>
                                                                <span class="text-[11px] font-black text-slate-700 uppercase tracking-[0.15em]">{{ $contingentName }}</span>
                                                            </div>

                                                            @if($assignedPool)
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg
                                                                        @if($assignedPool === 'A' || $assignedPool === 'PENYISIHAN') bg-indigo-100 text-indigo-600
                                                                        @elseif($assignedPool === 'B') bg-violet-100 text-violet-600
                                                                        @elseif($assignedPool === 'C') bg-teal-100 text-teal-600
                                                                        @else bg-rose-100 text-rose-600
                                                                        @endif">
                                                                        {{ $drawing['format'] === '2_babak' ? 'Babak' : 'Pool' }} {{ $assignedPool }}
                                                                    </span>
                                                                    <span class="text-[9px] font-black text-slate-400 bg-white border border-slate-200 rounded-lg px-2.5 py-1 uppercase tracking-widest">
                                                                        Tampil ke-{{ $assignedCourt }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ $contingentAthletes->count() }} Atlet</span>
                                                        </div>

                                                        <!-- Athletes Table -->
                                                        <div class="bg-white overflow-x-auto">
                                                            <table class="w-full text-left border-collapse">
                                                                <thead>
                                                                    <tr class="border-b border-slate-50">
                                                                        <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider text-center w-12">No</th>
                                                                        <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider">Nama Peserta (Atlet)</th>
                                                                        <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider text-center w-32">Tingkat (Rank)</th>
                                                                        <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider min-w-[280px]">Urutan Jurus / Teknik</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-slate-50">
                                                                    @php $lastRegId = null; @endphp
                                                                    @foreach($contingentAthletes as $aIdx => $ath)
                                                                        @php
                                                                            $isNewTeam = ($lastRegId !== $ath->registration_id);
                                                                            $lastRegId = $ath->registration_id;
                                                                        @endphp
                                                                        <tr class="hover:bg-slate-50/40 transition-colors">
                                                                            <td class="px-5 py-4 text-center">
                                                                                <span class="text-[11px] font-black text-slate-300">{{ $aIdx + 1 }}</span>
                                                                            </td>
                                                                            <td class="px-5 py-4">
                                                                                <span class="text-[12px] font-black text-slate-700 uppercase tracking-tight">{{ $ath->name }}</span>
                                                                            </td>
                                                                            <td class="px-5 py-4 text-center">
                                                                                <span class="inline-block px-3 py-1 bg-orange-50 text-orange-600 rounded-lg text-[10px] font-black uppercase tracking-tight border border-orange-100/50">
                                                                                    {{ $ath->rank }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="px-5 py-4">
                                                                                @if($isNewTeam)
                                                                                    <div class="flex flex-wrap gap-2">
                                                                                        @forelse($ath->readable_techniques as $tIdx => $tName)
                                                                                            <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-1.5">
                                                                                                <span class="text-[10px] font-black text-indigo-400">{{ $tIdx + 1 }}</span>
                                                                                                <span class="text-[10px] font-black text-indigo-700 uppercase tracking-tight whitespace-nowrap">{{ $tName }}</span>
                                                                                            </div>
                                                                                        @empty
                                                                                            <span class="text-[10px] text-slate-300 font-bold uppercase italic tracking-widest">Belum ada teknik dipilih</span>
                                                                                        @endforelse
                                                                                    </div>
                                                                                @else
                                                                                    <div class="flex items-center gap-2">
                                                                                        <i class="fas fa-level-up-alt rotate-90 text-slate-200 text-xs"></i>
                                                                                        <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest italic">Sama seperti di atas</span>
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Tabs Wrapper -->
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-32 text-center">
                <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-trophy text-4xl"></i>
                </div>
                <h3 class="text-base font-black text-slate-400 uppercase tracking-[0.3em]">Belum Ada Data Drawing</h3>
                <p class="text-[11px] text-slate-300 font-medium mt-3">Silakan pastikan nomor pertandingan bertipe 'Embu' telah memiliki pendaftar.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($paginatedMatches->hasPages())
            <div class="mt-12 px-8 py-6 bg-slate-50 border-t border-slate-100 rounded-[28px]">
                {{ $paginatedMatches->links() }}
            </div>
        @endif
    </div>
</div>
