<div class="space-y-6 animate-in fade-in duration-500" x-data="{ globalTab: @entangle('globalTab') }">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Drawing Embu</h1>
            <p class="text-[15px] text-slate-800 font-semibold uppercase tracking-widest mt-1">Technical Meeting &mdash; Sistem Drawing Otomatis & Skenario THB</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex flex-wrap gap-2">
                <button wire:click="generateAllDrawings"
                        wire:confirm="Generate drawing otomatis untuk semua nomor pertandingan Embu? Sistem Auto-Scheduler akan mentribusikan Court dan Jadwal secara otomatis untuk mencegah bentrok."
                        wire:loading.attr="disabled"
                        wire:target="generateAllDrawings"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-[15px] font-black uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm shadow-red-600/20 disabled:opacity-50">
                    <span wire:loading.remove wire:target="generateAllDrawings"><i class="fas fa-magic"></i></span>
                    <span wire:loading wire:target="generateAllDrawings"><i class="fas fa-spinner fa-spin"></i></span>
                    Generate Semua
                </button>
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-700 text-white text-[15px] font-black uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm">
                    <i class="fas fa-print"></i>
                    Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- ===== THB RULES CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        {{-- Card 1 --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex gap-3 items-start">
            <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                <i class="fas fa-users-slash text-[15px]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[15px] font-black text-orange-600 uppercase tracking-widest">≤ 9 Kontingen</p>
                <p class="text-[12px] font-black text-slate-800 mt-0.5 leading-tight">2 Babak</p>
                <p class="text-[15px] text-slate-800 mt-0.5">Penyisihan + Final</p>
            </div>
        </div>
        {{-- Card 2 --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex gap-3 items-start">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                <i class="fas fa-layer-group text-[15px]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[15px] font-black text-indigo-600 uppercase tracking-widest">≥ 10 Kontingen</p>
                <p class="text-[12px] font-black text-slate-800 mt-0.5 leading-tight">Sistem Pool</p>
                <p class="text-[15px] text-slate-800 mt-0.5">Dibagi per jumlah peserta</p>
            </div>
        </div>
        {{-- Card 3 --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex gap-3 items-start">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fas fa-trophy text-[15px]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[15px] font-black text-emerald-600 uppercase tracking-widest">Kualifikasi</p>
                <p class="text-[15px] font-black text-slate-800 mt-0.5 leading-tight">8–9 Finalis / Pool</p>
                <p class="text-[15px] text-slate-800 mt-0.5">Tergantung jumlah atlet</p>
            </div>
        </div>
        {{-- Card 4 --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex gap-3 items-start">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <i class="fas fa-users text-[15px]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[15px] font-black text-rose-600 uppercase tracking-widest">Embu Beregu</p>
                <p class="text-[12px] font-black text-slate-800 mt-0.5 leading-tight">Tandoku·Paired·Tandoku</p>
                <p class="text-[15px] text-slate-800 mt-0.5">Urutan wajib sesuai THB</p>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTROL BAR ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        {{-- Tab Bar + Filter Row --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-0 border-b border-slate-100">
            {{-- Horizontal Tabs --}}
            <div class="flex overflow-x-auto scrollbar-hide">
                <button @click="globalTab = 'sebelum'"
                    class="flex items-center gap-2 px-5 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                    :class="globalTab === 'sebelum'
                        ? 'border-orange-500 text-orange-600'
                        : 'border-transparent text-slate-800 hover:text-black hover:border-slate-200'">
                    <i class="fas fa-users text-[15px]"></i>
                    <span>Pendaftar</span>
                </button>
                <button @click="globalTab = 'hasil'"
                    class="flex items-center gap-2 px-5 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                    :class="globalTab === 'hasil'
                        ? 'border-indigo-500 text-indigo-600'
                        : 'border-transparent text-slate-800 hover:text-black hover:border-slate-200'">
                    <i class="fas fa-project-diagram text-[15px]"></i>
                    <span>Drawing &amp; Pool</span>
                </button>
                <button @click="globalTab = 'nomor'"
                    class="flex items-center gap-2 px-5 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                    :class="globalTab === 'nomor'
                        ? 'border-emerald-500 text-emerald-600'
                        : 'border-transparent text-slate-800 hover:text-black hover:border-slate-200'">
                    <i class="fas fa-list-ol text-[15px]"></i>
                    <span>Nomor Match</span>
                </button>
                <button @click="globalTab = 'penilaian'"
                    class="flex items-center gap-2 px-5 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                    :class="globalTab === 'penilaian'
                        ? 'border-rose-500 text-rose-600'
                        : 'border-transparent text-slate-800 hover:text-black hover:border-slate-200'">
                    <i class="fas fa-medal text-[15px]"></i>
                    <span>Hasil Penilaian</span>
                </button>
            </div>

            {{-- Court Filter --}}
            <div class="px-4 py-3 sm:border-l border-t sm:border-t-0 border-slate-100 shrink-0">
                <div class="relative">
                    <select wire:model.live="selectedCourtId"
                            class="appearance-none bg-slate-50 border border-slate-200 text-black text-[15px] font-bold rounded-xl pl-4 pr-9 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500/30 transition-all w-full sm:w-44">
                        <option value="">Semua Court</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ $court->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-800">
                        <i class="fas fa-chevron-down text-[15px]"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== HIERARCHICAL VIEW — Pendaftar & Drawing ===== --}}
    <div x-show="globalTab !== 'nomor'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-10">

        @forelse($matchSummary as $gender => $ageGroups)

        {{-- ── Gender Section ── --}}
        <div class="space-y-6" wire:key="gender-{{ $gender }}">

            {{-- Gender Divider --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2.5 bg-slate-900 text-white px-5 py-2.5 rounded-xl shadow">
                    <i class="fas @if($gender == 'Male') fa-mars @elseif($gender == 'Female') fa-venus @else fa-venus-mars @endif text-[15px] text-orange-400"></i>
                    <span class="text-[15px] font-black uppercase tracking-widest">
                        {{ $gender == 'Male' ? 'Laki-laki' : ($gender == 'Female' ? 'Perempuan' : 'Campuran / Mix') }}
                    </span>
                </div>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            @foreach($ageGroups as $ageGroupName => $matches)

            {{-- ── Age Group ── --}}
            <div class="space-y-5" wire:key="age-{{ $gender }}-{{ $ageGroupName }}">

                {{-- Age Group Label --}}
                <div class="flex items-center gap-2.5 pl-1">
                    <div class="w-7 h-7 bg-indigo-600 text-white rounded-lg flex items-center justify-center text-[15px] font-black shrink-0">
                        {{ substr($ageGroupName, 0, 1) }}
                    </div>
                    <h3 class="text-[12px] font-black text-slate-900 uppercase tracking-wider">{{ $ageGroupName }}</h3>
                </div>

                {{-- Match Cards --}}
                @foreach($matches as $mId => $data)
                @php
                    $byContingent = collect($data['athletes'])->groupBy('contingent');
                    $totalTeams   = $byContingent->count();
                    $drawing      = $data['drawing_data'];
                    $drawingAt    = $data['drawing_at'];
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" wire:key="match-{{ $mId }}">

                    {{-- Match Card Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-2 h-2 rounded-full bg-orange-500 shrink-0"></span>
                            <h4 class="text-[15px] font-black text-slate-800 uppercase tracking-wide truncate">{{ $data['name'] }}</h4>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            <span class="text-[15px] font-bold text-slate-800 border border-slate-100 rounded-lg px-3 py-1 uppercase">
                                {{ $totalTeams }} Kontingen
                            </span>
                            @if($drawing)
                                <button wire:click="resetDrawing({{ $mId }})"
                                        wire:confirm="Reset drawing untuk nomor ini?"
                                        class="inline-flex items-center gap-1.5 text-[15px] font-black text-rose-500 border border-rose-200 hover:bg-rose-50 px-3 py-1 rounded-lg uppercase transition active:scale-95">
                                    <i class="fas fa-redo text-[15px]"></i> Reset
                                </button>
                                <button wire:click="generateDrawing({{ $mId }})"
                                        wire:loading.attr="disabled"
                                        wire:target="generateDrawing({{ $mId }})"
                                        class="inline-flex items-center gap-1.5 text-[15px] font-black text-orange-600 border border-orange-200 hover:bg-orange-50 px-3 py-1 rounded-lg uppercase transition active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-sync text-[15px]"></i> Regenerate</span>
                                    <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[15px]"></i> Loading...</span>
                                </button>
                            @else
                                <button wire:click="generateDrawing({{ $mId }})"
                                        wire:loading.attr="disabled"
                                        wire:target="generateDrawing({{ $mId }})"
                                        class="inline-flex items-center gap-1.5 text-[15px] font-black text-white bg-slate-900 hover:bg-slate-700 px-4 py-1 rounded-lg uppercase transition active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-dice text-[15px]"></i> Generate Drawing</span>
                                    <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[15px]"></i> Generating...</span>
                                </button>
                            @endif
                            <span class="text-[15px] text-slate-300 font-bold uppercase italic">#{{ $mId }}</span>
                        </div>
                    </div>

                    {{-- Tab Content: Drawing & Pool --}}
                    <div x-show="globalTab === 'hasil'" x-cloak class="p-5">
                        @if($drawing)
                            {{-- Format Info Banner --}}
                             <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-slate-900 rounded-xl px-5 py-4 mb-5">
                                 <div class="flex items-center gap-3 flex-1 min-w-0">
                                     <div class="w-10 h-10 rounded-lg bg-orange-500 text-white flex items-center justify-center shrink-0">
                                         <i class="fas @if($drawing['format'] === '2_babak') fa-layer-group @else fa-object-group @endif text-[15px]"></i>
                                     </div>
                                     <div class="min-w-0">
                                         <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Format (THB Pasal H)</p>
                                         <p class="text-[12px] font-black text-white mt-0.5 truncate">{{ $drawing['description'] }}</p>
                                     </div>
                                 </div>

                                 @php
                                     $drawings = $data['db_drawing_entries'] ?? collect();
                                     $firstEntry = $drawings->first();
                                 @endphp
                                 @if($firstEntry && ($firstEntry->schedule_date || $firstEntry->rundown || $firstEntry->sessionTime))
                                     <div class="flex items-center gap-3 flex-1 min-w-0 border-t sm:border-t-0 sm:border-l border-slate-700 pt-3 sm:pt-0 sm:pl-4">
                                         <div class="w-10 h-10 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center shrink-0">
                                             <i class="fas fa-calendar-check text-[15px]"></i>
                                         </div>
                                         <div class="min-w-0">
                                             <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Jadwal Pertandingan</p>
                                             @if($firstEntry->schedule_date || $firstEntry->rundown)
                                             <p class="text-[15px] font-black text-orange-200 mt-0.5 whitespace-nowrap">
                                                 {{ $firstEntry->schedule_date ? \Carbon\Carbon::parse($firstEntry->schedule_date)->locale('id')->isoFormat('D MMM') : 'Tgl(-)' }} • {{ $firstEntry->rundown->name ?? '-' }}
                                             </p>
                                             @endif
                                             @if($firstEntry->sessionTime)
                                             <p class="text-[15px] font-bold text-slate-900 uppercase mt-0.5">
                                                 {{ $firstEntry->sessionTime->name }} ({{ $firstEntry->sessionTime->start_time->format('H:i') }} - {{ $firstEntry->sessionTime->end_time ? $firstEntry->sessionTime->end_time->format('H:i') : 'Selesai' }})
                                             </p>
                                             @endif
                                         </div>
                                     </div>
                                 @endif

                                 <div class="flex items-center gap-4 shrink-0 border-t sm:border-t-0 sm:border-l border-slate-700 pt-3 sm:pt-0 sm:pl-4 mt-3 sm:mt-0">
                                     @if($drawing['format'] === 'pool')
                                         <div class="text-center">
                                             <p class="text-[14px] font-black text-orange-400">{{ $drawing['pool_count'] }}</p>
                                             <p class="text-[15px] text-slate-900 font-bold uppercase">Pool</p>
                                         </div>
                                         <div class="w-px h-8 bg-slate-700"></div>
                                         <div class="text-center">
                                             <p class="text-[14px] font-black text-blue-400">{{ $drawing['qualifiers'] }}</p>
                                             <p class="text-[15px] text-slate-900 font-bold uppercase">Lolos</p>
                                         </div>
                                         <div class="w-px h-8 bg-slate-700"></div>
                                     @endif
                                     <div class="text-center">
                                         <p class="text-[14px] font-black text-emerald-400">{{ $drawing['total_entries'] }}</p>
                                         <p class="text-[15px] text-slate-900 font-bold uppercase">Kontingen</p>
                                     </div>
                                 </div>
                             </div>

                            @if($drawingAt)
                                <p class="text-[15px] text-slate-800 font-semibold mb-4">
                                    <i class="fas fa-clock mr-1.5"></i>
                                    Drawing dibuat: {{ \Carbon\Carbon::parse($drawingAt)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                </p>
                            @endif

                            {{-- Pool Cards --}}
                            <div class="grid grid-cols-1 @if($drawing['pool_count'] >= 2) md:grid-cols-2 @endif gap-4">
                                @foreach($drawings->groupBy('pool_id') as $poolId => $poolEntries)
                                <div class="rounded-xl border border-slate-200 overflow-hidden">
                                    {{-- Pool Header --}}
                                     @php
                                         $firstPoolEntry = $poolEntries->first();
                                         $poolName = $firstPoolEntry->pool->name ?? ($drawing['format'] === '2_babak' ? 'PENYISIHAN' : 'Unknown');
                                         
                                         $poolColorClass = match(true) {
                                             str_contains($poolName, 'A') || $poolName === 'PENYISIHAN' => 'from-indigo-600 to-indigo-700',
                                             str_contains($poolName, 'B') => 'from-violet-600 to-violet-700',
                                             str_contains($poolName, 'C') => 'from-teal-600 to-teal-700',
                                             str_contains($poolName, 'D') => 'from-emerald-600 to-emerald-700',
                                             default => 'from-rose-600 to-rose-700',
                                         };
                                     @endphp
                                     <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-br {{ $poolColorClass }}">
                                         <div class="flex items-center gap-4">
                                             <div>
                                                 <p class="text-[15px] font-black text-white/60 uppercase tracking-widest">{{ $drawing['format'] === '2_babak' ? 'Babak' : 'Pool' }}</p>
                                                 <p class="text-[15px] font-black text-white uppercase leading-tight">{{ $poolName }}</p>
                                             </div>
                                             @if($firstPoolEntry && ($firstPoolEntry->court_id || $firstPoolEntry->session_time_id))
                                                 <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                                                 <div class="hidden sm:block">
                                                     <p class="text-[15px] font-black text-white/60 uppercase tracking-widest">{{ $firstPoolEntry->court->name ?? '-' }} • {{ $firstPoolEntry->rundown->name ?? '-' }}</p>
                                                     <p class="text-[15px] font-black text-white uppercase leading-tight">
                                                         {{ $firstPoolEntry->sessionTime->name ?? '-' }}
                                                     </p>
                                                 </div>
                                             @endif
                                         </div>
                                         <span class="text-[15px] font-black text-white/80">{{ count($poolEntries) }} Kontingen</span>
                                     </div>
                                    {{-- Pool Table --}}
                                    <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                        <thead class="bg-slate-800 text-white">
                                            <tr class="border-b border-slate-100">
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">No.</th>
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kontingen</th>
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Urutan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200">
                                            @foreach($poolEntries as $entry)
                                            <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                <td class="px-4 py-3 text-center border-r border-slate-200">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg border border-slate-200 text-[15px] font-black text-slate-900">{{ $entry->sequence_number }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-[15px] font-black text-black uppercase border-r border-slate-200">{{ $entry->registration->contingent->name ?? 'Unknown' }}</td>
                                                <td class="px-4 py-3 text-center text-[15px] font-black text-slate-800 border-r border-slate-200">{{ $entry->sequence_number }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($drawing['format'] === 'pool' && $drawing['qualifiers'] > 0)
                                        <div class="px-4 py-2.5 border-t border-slate-100">
                                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">
                                                <i class="fas fa-trophy text-amber-500 mr-1.5"></i>
                                                Rank 1–{{ $drawing['qualifiers'] }} lolos ke <strong class="text-slate-900">Final</strong>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                        @else
                            {{-- No Drawing Yet --}}
                            <div class="border-2 border-dashed border-slate-200 rounded-xl py-10 text-center">
                                <i class="fas fa-dice text-slate-300 text-3xl mb-3"></i>
                                <p class="text-[12px] font-black text-slate-800 uppercase tracking-widest">Drawing belum dibuat</p>
                                <p class="text-[15px] text-slate-800 mt-1">Klik <strong>Generate Drawing</strong> untuk membuat urutan tampil</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tab Content: Hasil Penilaian Embu --}}
                    <div x-show="globalTab === 'penilaian'" x-cloak class="p-5">
                        @php
                            $embuScores = $data['embu_scores'] ?? collect();
                            $byRound = $embuScores->groupBy('round_label');
                        @endphp
                        @if($embuScores->isEmpty())
                            <div class="border-2 border-dashed border-slate-200 rounded-xl py-12 text-center">
                                <i class="fas fa-star-half-alt text-slate-300 text-3xl mb-4"></i>
                                <p class="text-[12px] font-black text-slate-800 uppercase tracking-widest">Belum Ada Nilai</p>
                                <p class="text-[15px] text-slate-800 mt-1">Nilai akan muncul setelah Wasit menginput skor pertandingan.</p>
                            </div>
                        @else
                            @foreach($byRound as $roundLabel => $roundScores)
                                @php
                                    $isPenyisihan = strtolower($roundLabel) === 'penyisihan';
                                    $isFinal = strtolower($roundLabel) === 'final';
                                    $roundColor = $isPenyisihan ? 'from-indigo-600 to-indigo-500'
                                               : ($isFinal ? 'from-amber-500 to-yellow-500' : 'from-rose-500 to-rose-400');
                                    $roundIcon = $isPenyisihan ? 'fa-filter' : ($isFinal ? 'fa-trophy' : 'fa-redo');

                                    // Group by tiebreak round for tiebreak display
                                    $byTiebreak = $roundScores->groupBy('tiebreak_round');
                                    $latestTiebreak = $roundScores->max('tiebreak_round');

                                    // For ranking: show only latest tiebreak scores
                                    $latestScores = $roundScores->where('tiebreak_round', $latestTiebreak);

                                   // Calculate akumulasi for each
                                    $mappedScores = $latestScores->map(function($score) use ($embuScores, $isPenyisihan) {
                                        $score->akumulasi = 0;
                                        $score->penyisihan_score = 0;
                                        if (!$isPenyisihan) {
                                            $allRegScores = $embuScores->where('registration_id', $score->registration_id);
                                            $latestPenyisihan = $allRegScores->where('round_label', 'Penyisihan')->max('tiebreak_round');
                                            $pObj = $allRegScores->where('round_label', 'Penyisihan')->where('tiebreak_round', $latestPenyisihan)->first();
                                            if ($pObj) {
                                                $score->penyisihan_score = $pObj->nilai_akhir ?: $pObj->total_score;
                                            }
                                            // Akumulasi = Rata-rata dari penyisihan dan final
                                            $score->akumulasi = ($score->penyisihan_score + ($score->nilai_akhir ?: $score->total_score)) / 2;
                                        }
                                        return $score;
                                    });

                                    // Final ranking (lowest = best in Penyisihan, highest = best in Final)
                                    // Actually final ranking uses akumulasi!
                                    $sorted = $isPenyisihan
                                        ? $mappedScores->sortBy('nilai_akhir')
                                        : $mappedScores->sortByDesc('akumulasi');
                                    
                                    // Check if Penyisihan has any final drawings yet
                                    $hasFinalDrawing = \App\Models\DrawingMatchNumber::where('match_number_id', $mId)->where('round', 'Final')->exists();
                                @endphp

                                <div class="mb-6 rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                                    {{-- Round Header --}}
                                    <div class="px-4 py-3 bg-gradient-to-r {{ $roundColor }} flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="fas {{ $roundIcon }} text-white text-[15px]"></i>
                                            <span class="text-[15px] font-black text-white uppercase tracking-widest">{{ strtoupper($roundLabel) }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if($isPenyisihan)
                                                <span class="text-[15px] font-black text-white/70 uppercase bg-white/10 px-2 py-0.5 rounded">Nilai Terendah = Peringkat Terbaik</span>
                                                @if(!$hasFinalDrawing)
                                                    <button wire:click="promptGenerateFinal({{ $mId }})" class="text-[15px] font-black text-indigo-900 bg-indigo-50 hover:bg-white px-3 py-1 rounded transition">Tarik ke Final <i class="fas fa-arrow-right ml-1"></i></button>
                                                @endif
                                            @else
                                                <span class="text-[15px] font-black text-amber-900 uppercase bg-amber-200/50 px-2 py-0.5 rounded">Akumulasi Tertinggi = Juara</span>
                                                <button wire:click="tandingUlang({{ $mId }}, '{{ $roundLabel }}')" class="text-[15px] font-black text-white bg-slate-900 hover:bg-slate-800 px-3 py-1 rounded transition">Tanding Ulang (Seri)</button>
                                            @endif
                                            <span class="text-[15px] font-black text-white/80">{{ $latestScores->count() }} Peserta</span>
                                        </div>
                                    </div>

                                    {{-- Scores Table --}}
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                            <thead class="bg-slate-800 text-white">
                                                <tr class="border-b border-slate-100 bg-slate-50">
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Rank</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kontingen</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">W1</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">W2</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">W3</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">W4</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">W5</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Total</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Denda</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">{{ $isPenyisihan ? 'Nilai Akhir' : 'Skor Final' }}</th>
                                                    @if(!$isPenyisihan)
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Penyisihan</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Akumulasi</th>
                                                    @endif
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Ket. / Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-200">
                                                @foreach($sorted->values() as $rankIdx => $score)
                                                    @php
                                                        $judges = [$score->judge_1, $score->judge_2, $score->judge_3, $score->judge_4, $score->judge_5];
                                                        sort($judges);
                                                        $minVal = $judges[0];
                                                        $maxVal = $judges[4];
                                                        $isJuara = !$isPenyisihan && $rankIdx === 0;
                                                    @endphp
                                                    <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                        {{-- Rank --}}
                                                        <td class="px-3 py-3 text-center border-r border-slate-200">
                                                            @if($isJuara)
                                                                <span class="text-lg">🏆</span>
                                                            @elseif($rankIdx === 1)
                                                                <span class="text-base">🥈</span>
                                                            @elseif($rankIdx === 2)
                                                                <span class="text-base">🥉</span>
                                                            @else
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg border border-slate-200 text-[15px] font-black text-slate-900">{{ $rankIdx + 1 }}</span>
                                                            @endif
                                                        </td>
                                                        {{-- Kontingen --}}
                                                        <td class="px-3 py-3 border-r border-slate-200">
                                                            <div class="text-[15px] font-black text-slate-800 uppercase">{{ $score->registration?->contingent?->name ?? '-' }}</div>
                                                            @if($score->tiebreak_round > 0)
                                                                <span class="text-[15px] font-black text-rose-500 bg-rose-50 border border-rose-100 px-1.5 py-0.5 rounded uppercase">Tanding Ulang #{{ $score->tiebreak_round }}</span>
                                                            @endif
                                                        </td>
                                                        {{-- Judge Scores --}}
                                                        @foreach([$score->judge_1, $score->judge_2, $score->judge_3, $score->judge_4, $score->judge_5] as $ji => $judgeVal)
                                                            <td class="px-3 py-3 text-center border-r border-slate-200">
                                                                @php $sorted2 = [$score->judge_1, $score->judge_2, $score->judge_3, $score->judge_4, $score->judge_5]; sort($sorted2); @endphp
                                                                <span class="text-[15px] font-black rounded px-1
                                                                    {{ $judgeVal == $sorted2[0] || $judgeVal == $sorted2[4]
                                                                        ? 'line-through text-rose-400 bg-rose-50'
                                                                        : 'text-emerald-700 bg-emerald-50' }}">{{ number_format($judgeVal, 1) }}</span>
                                                            </td>
                                                        @endforeach
                                                        {{-- Total --}}
                                                        <td class="px-3 py-3 text-center text-[15px] font-black text-black border-r border-slate-200">{{ number_format($score->total_score, 1) }}</td>
                                                        {{-- Denda --}}
                                                        <td class="px-3 py-3 text-center text-[15px] font-bold {{ $score->denda > 0 ? 'text-rose-500' : 'text-slate-300' }}">{{ $score->denda > 0 ? '-'.number_format($score->denda, 1) : '—' }}</td>
                                                        {{-- Nilai Akhir --}}
                                                        <td class="px-3 py-3 text-center border-r border-slate-200">
                                                            <span class="text-[12px] font-black text-slate-900">{{ number_format($score->nilai_akhir ?: $score->total_score, 1) }}</span>
                                                        </td>
                                                        @if(!$isPenyisihan)
                                                            {{-- Penyisihan & Akumulasi --}}
                                                            <td class="px-3 py-3 text-center text-[15px] font-bold text-slate-800 border-r border-slate-200">
                                                                {{ number_format($score->penyisihan_score, 1) }}
                                                            </td>
                                                            <td class="px-3 py-3 text-center bg-indigo-50/50 border-r border-slate-200">
                                                                <span class="text-[12px] font-black {{ $isJuara ? 'text-amber-600' : 'text-indigo-700' }}">{{ number_format($score->akumulasi, 1) }}</span>
                                                            </td>
                                                        @endif
                                                        {{-- Keterangan / Aksi --}}
                                                        <td class="px-3 py-3 text-center border-r border-slate-200">
                                                            <div class="flex items-center justify-center gap-2">
                                                                @if(!$isPenyisihan)
                                                                    @if($score->rank)
                                                                        @php $medals = [1=>'🏆 Juara 1',2=>'🥈 Juara 2',3=>'🥉 Juara 3']; @endphp
                                                                        <span class="text-[15px] font-black text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-lg whitespace-nowrap">{{ $medals[$score->rank] ?? 'Rank '.$score->rank }}</span>
                                                                        <button wire:click="simpanJuaranya({{ $score->id }}, 0)" class="text-[15px] text-slate-800 hover:text-rose-500 ml-1"><i class="fas fa-times"></i></button>
                                                                    @else
                                                                        <div x-data="{ open: false }" class="relative">
                                                                            <button @click="open = !open" class="text-[15px] font-black text-white bg-slate-900 hover:bg-slate-700 px-2 py-1 rounded transition whitespace-nowrap"><i class="fas fa-save mr-1 border-r border-white/20 pr-1"></i> SIMPAN JUARA</button>
                                                                            <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-1 bg-white border border-slate-200 rounded-lg shadow-xl z-20 w-24 flex flex-col p-1 text-left">
                                                                                <button wire:click="simpanJuaranya({{ $score->id }}, 1); open=false;" class="text-[15px] p-1.5 font-black text-amber-600 hover:bg-amber-50 rounded">Juara 1</button>
                                                                                <button wire:click="simpanJuaranya({{ $score->id }}, 2); open=false;" class="text-[15px] p-1.5 font-black text-slate-900 hover:bg-slate-50 rounded">Juara 2</button>
                                                                                <button wire:click="simpanJuaranya({{ $score->id }}, 3); open=false;" class="text-[15px] p-1.5 font-black text-orange-600 hover:bg-orange-50 rounded">Juara 3</button>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @elseif($isPenyisihan && $rankIdx < 4)
                                                                    <span class="text-[15px] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg whitespace-nowrap">Lolos Final</span>
                                                                @else
                                                                    <span class="text-[15px] text-slate-300">—</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tiebreak History --}}
                                    @if($byTiebreak->count() > 1)
                                        <div class="px-4 py-2.5 border-t border-slate-100 bg-rose-50/40">
                                            <p class="text-[15px] font-black text-rose-500 uppercase tracking-widest">
                                                <i class="fas fa-history mr-1"></i>
                                                {{ $byTiebreak->count() - 1 }}x Tanding Ulang dilakukan untuk babak {{ $roundLabel }} ini
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>

                    {{-- Tab Content: Pendaftar --}}
                    <div x-show="globalTab === 'sebelum'" x-cloak class="p-5">
                        <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-4">
                            <i class="fas fa-users mr-1.5"></i>Detail Atlet per Kontingen
                        </p>
                        <div class="space-y-4">
                            @foreach($byContingent as $contingentName => $contingentAthletes)
                                @php
                                    $contingentNum = $loop->index + 1;
                                    $assignedPool  = null;
                                    $assignedCourt = null;
                                    if ($drawing && isset($data['db_drawing_entries']) && $data['db_drawing_entries']->isNotEmpty()) {
                                        $dmdForContingent = $data['db_drawing_entries']->first(function($dmd) use ($contingentName) {
                                            return ($dmd->registration->contingent->name ?? '') === $contingentName;
                                        });
                                        if ($dmdForContingent) {
                                            $assignedPool = $dmdForContingent->pool->name ?? '-';
                                            $assignedCourt = $dmdForContingent->sequence_number;
                                        }
                                    }
                                @endphp

                                <div class="rounded-xl border border-slate-200 overflow-hidden">
                                    {{-- Contingent Header --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-4 py-3 border-b border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[15px] font-black shrink-0">
                                                {{ $contingentNum }}
                                            </div>
                                            <span class="text-[12px] font-black text-slate-800 uppercase tracking-wider">{{ $contingentName }}</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 items-center">
                                            @if($assignedPool)
                                                <span class="text-[15px] font-black border border-slate-200 text-slate-900 px-3 py-1 rounded-lg uppercase">
                                                    {{ $drawing['format'] === '2_babak' ? 'Babak' : 'Pool' }} {{ $assignedPool }}
                                                </span>
                                                <span class="text-[15px] font-black border border-slate-200 text-slate-900 px-3 py-1 rounded-lg uppercase">
                                                    Tampil #{{ $assignedCourt }}
                                                </span>
                                            @endif
                                            <span class="text-[15px] font-bold text-slate-800">{{ $contingentAthletes->count() }} Atlet</span>
                                        </div>
                                    </div>
                                    {{-- Athletes Table --}}
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                            <thead class="bg-slate-800 text-white">
                                                <tr class="border-b border-slate-100">
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">No</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Nama Peserta</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Tingkat</th>
                                                    <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Urutan Komposisi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-200">
                                                @php $lastRegId = null; @endphp
                                                @foreach($contingentAthletes as $aIdx => $ath)
                                                    @php
                                                        $isNewTeam = ($lastRegId !== $ath->registration_id);
                                                        $lastRegId = $ath->registration_id;
                                                    @endphp
                                                    <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                        <td class="px-4 py-3 text-center text-[15px] font-bold text-slate-300 border-r border-slate-200">{{ $aIdx + 1 }}</td>
                                                        <td class="px-4 py-3 text-[15px] font-black text-black uppercase whitespace-nowrap border-r border-slate-200">{{ $ath->name }}</td>
                                                        <td class="px-4 py-3 text-center whitespace-nowrap border-r border-slate-200">
                                                            <span class="inline-block whitespace-nowrap text-[15px] font-black text-orange-600 border border-orange-200 px-2 py-0.5 rounded-lg uppercase">{{ $ath->rank }}</span>
                                                        </td>
                                                        <td class="px-4 py-3 border-r border-slate-200">
                                                            @if($isNewTeam)
                                                                <div class="flex flex-wrap gap-1.5">
                                                                    @forelse($ath->readable_techniques as $tIdx => $tName)
                                                                        <span class="text-[15px] font-black text-indigo-600 border border-indigo-100 px-2 py-0.5 rounded-lg uppercase">{{ $tIdx + 1 }}. {{ $tName }}</span>
                                                                    @empty
                                                                        <span class="text-[15px] text-slate-300 italic">—</span>
                                                                    @endforelse
                                                                </div>
                                                            @else
                                                                <span class="text-[15px] text-slate-300 italic">↳ sda.</span>
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

                </div>{{-- /match-block --}}
                @endforeach

            </div>{{-- /age-group --}}
            @endforeach

        </div>{{-- /gender-section --}}
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 py-24 text-center shadow-sm">
                <i class="fas fa-trophy text-slate-200 text-5xl mb-4"></i>
                <h3 class="text-[14px] font-black text-slate-800 uppercase tracking-widest">Belum Ada Data Drawing</h3>
                <p class="text-[12px] text-slate-800 mt-2">Pastikan nomor pertandingan bertipe Embu telah memiliki pendaftar.</p>
            </div>
        @endforelse
    </div>

    {{-- ===== NOMOR MATCH VIEW ===== --}}
    <div x-show="globalTab === 'nomor'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-800 text-white">
                        <tr class="bg-indigo-900 text-white">
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">No. Match</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Tipe</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kategori & Babak</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kontingen / Pool</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Jadwal & Sesi</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Tempat</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Urutan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php $hasAtLeastOneDrawing = false; @endphp
                        @foreach($matchSummary as $gender => $ageGroups)
                            @foreach($ageGroups as $ageName => $matches)
                                @foreach($matches as $mId => $data)
                                    @if(isset($data['db_drawing_entries']) && $data['db_drawing_entries']->isNotEmpty())
                                        @php $hasAtLeastOneDrawing = true; @endphp
                                        @foreach($data['db_drawing_entries'] as $entry)
                                            <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                <td class="px-5 py-3.5 text-center whitespace-nowrap border-r border-slate-200">
                                                    <span class="inline-block border border-slate-200 text-black px-2.5 py-1 rounded-lg text-[15px] font-black">#{{ $mId }}</span>
                                                </td>
                                                <td class="px-5 py-3.5 text-center whitespace-nowrap border-r border-slate-200">
                                                    <span class="inline-block bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-[15px] font-black uppercase shadow-sm">EMBU</span>
                                                </td>
                                                <td class="px-5 py-3.5 border-r border-slate-200">
                                                    <p class="text-[15px] font-black text-slate-800 uppercase leading-tight">{{ $data['name'] }}</p>
                                                    <div class="flex items-center gap-1.5 mt-1">
                                                        <span class="text-[15px] text-slate-800 uppercase font-black tracking-tighter">{{ $gender }} · {{ $ageName }}</span>
                                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                        <span class="text-[15px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-black uppercase tracking-tighter">{{ $entry->round ?? 'Penyisihan' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-3.5 border-r border-slate-200">
                                                    <p class="text-[15px] font-black text-black uppercase whitespace-nowrap">{{ $entry->registration->contingent->name ?? 'Unknown' }}</p>
                                                    <p class="text-[15px] text-slate-800 mt-0.5 uppercase font-bold">Pool: {{ $entry->pool->name ?? '-' }}</p>
                                                </td>
                                                <td class="px-5 py-3.5 text-center whitespace-nowrap border-r border-slate-200">
                                                    @if($entry->schedule_date || $entry->rundown || $entry->sessionTime)
                                                        @if($entry->schedule_date || $entry->rundown)
                                                        <span class="block bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-lg text-[15px] font-black uppercase">
                                                            {{ $entry->schedule_date ? \Carbon\Carbon::parse($entry->schedule_date)->locale('id')->isoFormat('D MMM') : 'Tgl(-)' }} · {{ $entry->rundown->name ?? '-' }}
                                                        </span>
                                                        @endif
                                                        @if($entry->sessionTime)
                                                            <span class="block text-[15px] text-slate-900 mt-1 uppercase font-bold">{{ $entry->sessionTime->name }} ({{ $entry->sessionTime->start_time->format('H:i') }} - {{ $entry->sessionTime->end_time ? $entry->sessionTime->end_time->format('H:i') : 'Selesai' }})</span>
                                                        @endif
                                                    @else
                                                        <span class="inline-block text-slate-300 text-[15px] italic">Belum diatur</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-3.5 border-l border-slate-100 text-center whitespace-nowrap border-r border-slate-200">
                                                    <span class="inline-block border border-indigo-100 text-indigo-600 px-2.5 py-1 rounded-lg text-[15px] font-black uppercase bg-indigo-50/50">{{ $entry->court->name ?? '—' }}</span>
                                                </td>
                                                <td class="px-5 py-3.5 text-center whitespace-nowrap border-r border-slate-200">
                                                    <span class="inline-block border border-amber-200 text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg text-[15px] font-black">#{{ $entry->sequence_number }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                        @if(!$hasAtLeastOneDrawing)
                            <tr>
                                <td colspan="7" class="py-20 text-center border-r border-slate-200">
                                    <i class="fas fa-list-ol text-slate-200 text-4xl mb-3 block"></i>
                                    <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Belum ada data drawing</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($paginatedMatches->hasPages())
        <div class="mt-4">{{ $paginatedMatches->links('livewire.admin.pagination') }}</div>
    @endif

    {{-- Generate Final Modal --}}
    @if($isGeneratingFinal && $finalMatchId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden animate-in zoom-in-95 duration-200">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-indigo-700">
                        <i class="fas fa-trophy text-[15px]"></i>
                        <h3 class="text-[15px] font-black uppercase tracking-wide">Generate Final Match</h3>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-[15px] font-black text-slate-900 uppercase tracking-widest mb-1.5">Pilih Rekap Tanggal / Rundown *</label>
                        <select wire:model="finalRundownId" required class="w-full text-[15px] font-bold text-black bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">-- Pilih Rundown / Tanggal --</option>
                            @foreach($rundowns as $rd)
                                <option value="{{ $rd->id }}">{{ \Carbon\Carbon::parse($rd->date)->locale('id')->isoFormat('D MMM YYYY') }} - {{ $rd->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[15px] font-black text-slate-900 uppercase tracking-widest mb-1.5">Pilih Sesi Waktu *</label>
                        <select wire:model="finalSessionTimeId" required class="w-full text-[15px] font-bold text-black bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">-- Pilih Sesi --</option>
                            @foreach($sessionTimes as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->start_time->format('H:i') }} - {{ $st->end_time ? $st->end_time->format('H:i') : 'Selesai' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[15px] font-black text-slate-900 uppercase tracking-widest mb-1.5">Pilih Court Khusus Final *</label>
                        <select wire:model="finalCourtId" required class="w-full text-[15px] font-bold text-black bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition">
                            <option value="">-- Pilih Court --</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}">{{ $court->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 flex gap-3 text-indigo-700 mt-2">
                        <i class="fas fa-info-circle text-[15px] shrink-0"></i>
                        <p class="text-[15px] font-semibold leading-relaxed">
                            Peserta yang lolos akan diurutkan berdasarkan skor Penyisihan.
                            Sistem akan mengatur skor <b>terendah</b> tampil <b>pertama</b>, dan skor <b>tertinggi</b> tampil <b>terakhir</b>.
                        </p>
                    </div>
                </div>
                <div class="p-5 bg-slate-50/80 border-t border-slate-100 flex gap-2 justify-end">
                    <button wire:click="$set('isGeneratingFinal', false)" class="px-4 py-2 text-[15px] font-black text-slate-900 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">Batal</button>
                    <button wire:click="generateFinal()" wire:loading.attr="disabled" class="px-5 py-2 text-[15px] font-black text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm shadow-indigo-600/20 flex items-center gap-2">
                        <span wire:loading.remove wire:target="generateFinal"><i class="fas fa-magic"></i> Setup Final</span>
                        <span wire:loading wire:target="generateFinal"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
