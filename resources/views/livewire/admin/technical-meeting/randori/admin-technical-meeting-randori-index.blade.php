<div class="space-y-6 animate-in fade-in duration-500" x-data="{ globalTab: @entangle('globalTab') }">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Drawing Randori</h1>
            <p class="text-[15px] text-slate-800 font-semibold uppercase tracking-widest mt-1">Technical Meeting &mdash; Sistem Drawing Otomatis</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex flex-wrap gap-2">
                <button wire:click="generateAllDrawings"
                        wire:confirm="Drawing drawing otomatis untuk semua nomor Randori? Sistem Auto-Scheduler akan mentribusikan Court dan Jadwal secara otomatis untuk mencegah bentrok."
                        wire:loading.attr="disabled"
                        wire:target="generateAllDrawings"
                        class="inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white text-[15px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all active:scale-95 shadow-sm shadow-red-600/20 disabled:opacity-50">
                    <span wire:loading.remove wire:target="generateAllDrawings"><i class="fas fa-magic"></i></span>
                    <span wire:loading wire:target="generateAllDrawings"><i class="fas fa-spinner fa-spin"></i></span>
                    Drawing Semua
                </button>
                <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-700 text-white text-[15px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all active:scale-95">
                    <i class="fas fa-print"></i>
                    Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- ===== THB RULES CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
        {{-- Card 1 --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex gap-3 items-start">
            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                <i class="fas fa-sitemap text-[15px]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[15px] font-black text-red-600 uppercase tracking-widest">Sistem Gugur Tunggal</p>
                <p class="text-[12px] font-black text-slate-800 mt-0.5 leading-tight">Pohon Turnamen / Bagan</p>
                <p class="text-[15px] text-slate-800 mt-0.5">Menggunakan format 8, 16, 32, dst (THB).</p>
            </div>
        </div>
        {{-- Card 2 --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex gap-3 items-start">
             <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                <i class="fas fa-user-shield text-[15px]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[15px] font-black text-indigo-600 uppercase tracking-widest">Aturan Kontingen</p>
                <p class="text-[12px] font-black text-slate-800 mt-0.5 leading-tight">Pemisahan Otomatis</p>
                <p class="text-[15px] text-slate-800 mt-0.5">Kontingen yang sama disebar saling berjauhan.</p>
            </div>
        </div>
    </div>

    {{-- ===== TABS NAVIGATION ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-4">
        <div class="flex overflow-x-auto scrollbar-hide">
            <button @click="globalTab = 'sebelum'"
                class="flex items-center gap-2 px-6 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                :class="globalTab === 'sebelum' ? 'border-red-500 text-red-600 bg-red-50/30' : 'border-transparent text-slate-800 hover:text-black hover:bg-slate-50/50'">
                <i class="fas fa-users text-[15px]"></i>
                <span>Daftar Pendaftar Atlet</span>
            </button>
            <button @click="globalTab = 'hasil'"
                class="flex items-center gap-2 px-6 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                :class="globalTab === 'hasil' ? 'border-amber-500 text-amber-600 bg-amber-50/30' : 'border-transparent text-slate-800 hover:text-black hover:bg-slate-50/50'">
                <i class="fas fa-sitemap text-[15px]"></i>
                <span>Bagan Pertandingan</span>
            </button>
            <button @click="globalTab = 'nomor'"
                class="flex items-center gap-2 px-6 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                :class="globalTab === 'nomor' ? 'border-emerald-500 text-emerald-600 bg-emerald-50/30' : 'border-transparent text-slate-800 hover:text-black hover:bg-slate-50/50'">
                <i class="fas fa-list-ol text-[15px]"></i>
                <span>Nomor Match</span>
            </button>
            <button @click="globalTab = 'bracket'"
                class="flex items-center gap-2 px-6 py-4 text-[15px] font-black uppercase tracking-widest whitespace-nowrap border-b-2 transition-all duration-200 shrink-0"
                :class="globalTab === 'bracket' ? 'border-rose-500 text-rose-600 bg-rose-50/30' : 'border-transparent text-slate-800 hover:text-black hover:bg-slate-50/50'">
                <i class="fas fa-medal text-[15px]"></i>
                <span>Hasil Bracket</span>
            </button>
        </div>
    </div>

    {{-- ===== FILTER CONTROLS ===== --}}
    <div class="bg-slate-900/5 border border-slate-200/60 rounded-2xl p-3 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            {{-- Filter: Gender --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:key="filter-gender" wire:model.live="selectedGender" placeholder="Semua Gender" variant="filter">
                    <option value="">Semua Gender</option>
                    <option value="Male">Laki-laki (Male)</option>
                    <option value="Female">Perempuan (Female)</option>
                    <option value="Mix">Campuran (Mix)</option>
                </x-select>
            </div>

            {{-- Filter: Kategori Umur --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:key="filter-age-group" wire:model.live="selectedAgeGroupId" placeholder="Semua Kategori Umur" variant="filter">
                    <option value="">Semua Kategori Umur</option>
                    @foreach($filterAgeGroups as $ageGroup)
                        <option value="{{ $ageGroup->id }}">{{ $ageGroup->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Match --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select 
                    wire:key="filter-match-number-{{ $selectedAgeGroupId }}-{{ $selectedCourtId }}-{{ $selectedPoolId }}-{{ count($filterMatchNumbers) }}"
                    wire:model.live="selectedMatchNumberId" 
                    placeholder="Semua Match" 
                    variant="filter"
                >
                    <option value="">Semua Match</option>
                    @foreach($filterMatchNumbers as $mn)
                        <option value="{{ $mn->id }}">{{ $mn->name }} - {{ $mn?->ageGroup?->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Filter: Court --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:key="filter-court" wire:model.live="selectedCourtId" placeholder="Semua Court" variant="filter">
                    <option value="">Semua Court</option>
                    @foreach($courts as $court)
                        <option value="{{ $court->id }}">{{ $court->name }}</option>
                    @endforeach
                </x-select>
            </div>
            
            {{-- Filter: Pool --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1">
                <x-select wire:key="filter-pool" wire:model.live="selectedPoolId" placeholder="Semua Pool" variant="filter">
                    <option value="">Semua Pool</option>
                    @foreach($filterPools as $pool)
                        <option value="{{ $pool->id }}">{{ $pool->name }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>
    </div>

    {{-- ===== HIERARCHICAL VIEW ===== --}}
    <div x-show="globalTab !== 'nomor'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-10">
        @forelse($matchSummary as $gender => $ageGroups)
            {{-- Gender Section --}}
            <div class="space-y-6" wire:key="gender-{{ $gender }}">
                {{-- Gender Divider --}}
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2.5 bg-slate-900 text-white px-5 py-2.5 rounded-xl shadow">
                        <i class="fas @if($gender == 'Male') fa-mars @elseif($gender == 'Female') fa-venus @else fa-venus-mars @endif text-[15px] text-red-400"></i>
                        <span class="text-[15px] font-black uppercase tracking-widest">
                            {{ $gender == 'Male' ? 'Laki-laki' : ($gender == 'Female' ? 'Perempuan' : 'Campuran / Mix') }}
                        </span>
                    </div>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                @foreach($ageGroups as $ageGroupName => $matches)
                    {{-- Age Group --}}
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
                                $totalAthletes = count($data['athletes']);
                                $drawing       = $data['drawing_data'];
                                $drawingAt     = $data['drawing_at'];
                            @endphp
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" wire:key="match-{{ $mId }}">
                                {{-- Match Card Header --}}
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-slate-100">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                                        <h4 class="text-[15px] font-black text-slate-800 uppercase tracking-wide truncate">{{ $data['name'] }}</h4>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                                        <span class="text-[15px] font-bold text-slate-800 border border-slate-100 rounded-lg px-3 py-1 uppercase whitespace-nowrap">
                                            {{ $totalAthletes }} Atlet
                                        </span>
                                        @if($drawing)
                                            <button wire:click="resetDrawing({{ $mId }})"
                                                    wire:confirm="Reset drawing bagan untuk nomor ini?"
                                                    class="inline-flex items-center gap-1.5 text-[15px] font-black text-rose-500 border border-rose-200 hover:bg-rose-50 px-3 py-1 rounded-lg uppercase transition active:scale-95 whitespace-nowrap">
                                                <i class="fas fa-redo text-[15px]"></i> Reset
                                            </button>
                                            <button wire:click="generateDrawing({{ $mId }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="generateDrawing({{ $mId }})"
                                                    class="inline-flex items-center gap-1.5 text-[15px] font-black text-red-600 border border-red-200 hover:bg-red-50 px-3 py-1 rounded-lg uppercase transition active:scale-95 disabled:opacity-50 whitespace-nowrap">
                                                <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-sync text-[15px]"></i> Re-Drawing</span>
                                                <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[15px]"></i> Loading...</span>
                                            </button>
                                        @else
                                            <button wire:click="generateDrawing({{ $mId }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="generateDrawing({{ $mId }})"
                                                    class="inline-flex items-center gap-1.5 text-[15px] font-black text-white bg-slate-900 hover:bg-slate-700 px-4 py-1 rounded-lg uppercase transition active:scale-95 disabled:opacity-50 whitespace-nowrap">
                                                <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-sitemap text-[15px]"></i> Drawing</span>
                                                <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[15px]"></i> Drawing...</span>
                                            </button>
                                        @endif
                                        <span class="text-[15px] text-slate-300 font-bold uppercase italic whitespace-nowrap">#{{ $mId }}</span>
                                    </div>
                                </div>

                                {{-- Tab Content: Hasil Drawing Bagan --}}
                                <div x-show="globalTab === 'hasil'" x-cloak class="p-5">
                                    @if($drawing)
                                        {{-- Format Banner --}}
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-slate-900 rounded-xl px-5 py-4 mb-5">
                                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                                <div class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center shrink-0">
                                                    <i class="fas fa-sitemap text-[15px]"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Format Randori</p>
                                                    <p class="text-[12px] font-black text-white mt-0.5 truncate">Bagan Peserta {{ $drawing['bracket_size'] ?? '—' }}</p>
                                                </div>
                                            </div>

                                            @php
                                                $drawings = $data['db_drawing_entries'] ?? collect();
                                                $firstEntry = $drawings->first();
                                            @endphp
                                            @if($firstEntry && ($firstEntry->schedule_date || $firstEntry->rundown || $firstEntry->sessionTime))
                                                <div class="flex items-center gap-3 flex-1 min-w-0 border-t sm:border-t-0 sm:border-l border-slate-700 pt-3 sm:pt-0 sm:pl-4">
                                                    <div class="w-10 h-10 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                                                        <i class="fas fa-calendar-alt text-[15px]"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Jadwal Pertandingan</p>
                                                        @if($firstEntry->schedule_date || $firstEntry->rundown)
                                                        <p class="text-[15px] font-black text-indigo-300 mt-0.5 whitespace-nowrap">
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
                                                <div class="text-center">
                                                    @php $totalEntries = $drawing['total_entries'] ?? $drawing['total_athletes'] ?? 0; @endphp
                                                    <p class="text-[14px] font-black text-emerald-400">{{ $totalEntries }}</p>
                                                    <p class="text-[15px] text-slate-900 font-bold uppercase">Peserta</p>
                                                </div>
                                                <div class="w-px h-8 bg-slate-700"></div>
                                                <div class="text-center">
                                                    @php $byes = ($drawing['bracket_size'] ?? 0) - $totalEntries; @endphp
                                                    <p class="text-[14px] font-black text-blue-400">{{ $byes }}</p>
                                                    <p class="text-[15px] text-slate-900 font-bold uppercase">Byes</p>
                                                </div>
                                            </div>
                                        </div>

                                        @if($drawingAt)
                                            <p class="text-[15px] text-slate-800 font-semibold mb-4">
                                                <i class="fas fa-clock mr-1.5"></i>
                                                Drawing dibuat: {{ \Carbon\Carbon::parse($drawingAt)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                            </p>
                                        @endif

                                        {{-- Double Elimination Bagan Visualization --}}
                                        @php
                                            $ubRounds = $drawing['upper_bracket']['rounds'] ?? [];
                                            $lbRounds = $drawing['lower_bracket']['rounds'] ?? [];
                                            $gf       = $drawing['grand_final'] ?? null;
                                        @endphp

                                        {{-- ═══ UPPER BRACKET ═══ --}}
                                        <div class="mb-6 rounded-xl border border-indigo-200 overflow-hidden shadow-sm">
                                            <div class="bg-indigo-600 px-4 py-2.5">
                                                <h3 class="text-[13px] font-black text-white uppercase tracking-widest">&uarr; Upper Bracket &mdash; Winner Path</h3>
                                            </div>
                                            <div class="bg-white p-5 overflow-x-auto scrollbar-hide">
                                                <div class="flex gap-8 min-w-max items-start">
                                                    @php
                                                        $hasPrelim = $drawing['has_preliminary'] ?? true;
                                                    @endphp
                                                    @foreach($ubRounds as $rIdx => $ubRound)
                                                        @php
                                                            $isR0 = ($rIdx === 0);
                                                            if ($isR0 && $hasPrelim) {
                                                                $roundLabel   = 'UB R0 (Prelim)';
                                                                $labelColor   = 'text-indigo-400';
                                                            } elseif ($rIdx === count($ubRounds) - 1) {
                                                                $roundLabel   = 'UB Final';
                                                                $labelColor   = 'text-indigo-600';
                                                            } elseif ($rIdx === count($ubRounds) - 2) {
                                                                $roundLabel   = 'UB Semi Final';
                                                                $labelColor   = 'text-indigo-500';
                                                            } else {
                                                                $roundLabel   = 'UB R' . ($rIdx + ($hasPrelim ? 0 : 1));
                                                                $labelColor   = 'text-slate-500';
                                                            }
                                                        @endphp
                                                        <div class="flex flex-col gap-4 w-[220px]">
                                                            <p class="text-[12px] font-black uppercase tracking-widest text-center {{ $labelColor }}">
                                                                {{ $roundLabel }}
                                                            </p>

                                                            @foreach($ubRound as $mIdx => $match)
                                                                @php
                                                                    $a1         = $match['athlete1'] ?? null;
                                                                    $a2         = $match['athlete2'] ?? null;
                                                                    $winner     = $match['winner'] ?? null;
                                                                    $isPrelim   = $match['is_prelim'] ?? false;
                                                                    $isDirect   = $match['is_direct'] ?? false;
                                                                @endphp

                                                                @if($isDirect)
                                                                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm">
                                                                        <div class="w-1 h-4 rounded-full bg-emerald-400 shrink-0"></div>
                                                                        <div class="flex-1 min-w-0">
                                                                            <p class="text-[15px] font-black text-emerald-800 uppercase truncate">{{ $a1['name'] ?? '-' }}</p>
                                                                            <p class="text-[15px] text-emerald-500 uppercase truncate">{{ $a1['contingent'] ?? '' }}</p>
                                                                        </div>
                                                                        <span class="text-[7px] font-black text-emerald-500 bg-white border border-emerald-200 px-1.5 py-0.5 rounded-full uppercase whitespace-nowrap">Lolos &rarr;</span>
                                                                    </div>
                                                                @else
                                                                    <div class="bg-white border-2 {{ $winner ? 'border-indigo-300' : ($isPrelim ? 'border-orange-200' : 'border-slate-200') }} rounded-xl overflow-hidden shadow-sm">
                                                                        <div class="flex items-center gap-2 p-2.5 border-b border-slate-100 {{ $winner === 'athlete1' ? 'bg-indigo-50' : '' }}">
                                                                            <div class="w-1.5 h-5 rounded-full bg-red-500 shrink-0"></div>
                                                                            <div class="flex-1 min-w-0">
                                                                                @if($a1)
                                                                                    <p class="text-[15px] font-black text-slate-800 uppercase truncate {{ $winner === 'athlete1' ? 'text-indigo-700' : '' }}">{{ $a1['name'] }}</p>
                                                                                    <p class="text-[15px] text-slate-800 uppercase truncate">{{ $a1['contingent'] ?? '' }}</p>
                                                                                @else
                                                                                    <p class="text-[15px] text-slate-300 italic">Menunggu...</p>
                                                                                @endif
                                                                            </div>
                                                                            @if($winner === 'athlete1')<span class="text-[15px] font-black text-indigo-600 shrink-0">W</span>@endif
                                                                        </div>
                                                                        <div class="flex items-center gap-2 p-2.5 {{ $winner === 'athlete2' ? 'bg-indigo-50' : '' }}">
                                                                            <div class="w-1.5 h-5 rounded-full bg-blue-600 shrink-0"></div>
                                                                            <div class="flex-1 min-w-0">
                                                                                @if($a2)
                                                                                    <p class="text-[15px] font-black text-slate-800 uppercase truncate {{ $winner === 'athlete2' ? 'text-indigo-700' : '' }}">{{ $a2['name'] }}</p>
                                                                                    <p class="text-[15px] text-slate-800 uppercase truncate">{{ $a2['contingent'] ?? '' }}</p>
                                                                                @else
                                                                                    <p class="text-[15px] text-slate-300 italic">Menunggu...</p>
                                                                                @endif
                                                                            </div>
                                                                            @if($winner === 'athlete2')<span class="text-[15px] font-black text-indigo-600 shrink-0">W</span>@endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ═══ LOWER BRACKET ═══ --}}
                                        @if(count($lbRounds) > 0)
                                            <div class="mb-6 rounded-xl border border-orange-200 overflow-hidden shadow-sm">
                                                <div class="bg-orange-500 px-4 py-2.5">
                                                    <h3 class="text-[13px] font-black text-white uppercase tracking-widest">&darr; Loser Bracket &mdash; Second Chance Path</h3>
                                                </div>
                                                <div class="bg-white p-5 overflow-x-auto scrollbar-hide">
                                                    <div class="flex gap-8 min-w-max items-start">
                                                        @foreach($lbRounds as $lrIdx => $lbRound)
                                                            <div class="flex flex-col gap-4 w-[220px]">
                                                                <p class="text-[12px] font-black text-center uppercase tracking-widest text-orange-400">
                                                                    @if($lrIdx === count($lbRounds) - 1) LB Final
                                                                    @elseif($lrIdx === count($lbRounds) - 2) LB Semi Final
                                                                    @else LB R{{ $lrIdx + 1 }} @endif
                                                                </p>
                                                                @foreach($lbRound as $lmIdx => $lmatch)
                                                                    @php
                                                                        $la1 = $lmatch['athlete1'] ?? null;
                                                                        $la2 = $lmatch['athlete2'] ?? null;
                                                                        $lw  = $lmatch['winner'] ?? null;
                                                                    @endphp
                                                                    <div class="bg-white border-2 {{ $lw ? 'border-orange-300' : 'border-slate-200' }} rounded-xl overflow-hidden shadow-sm">
                                                                        <div class="flex items-center gap-2 p-2.5 border-b border-slate-100 {{ $lw === 'athlete1' ? 'bg-orange-50' : '' }}">
                                                                            <div class="w-1.5 h-5 rounded-full bg-red-400 shrink-0"></div>
                                                                            <div class="flex-1 min-w-0">
                                                                                @if($la1)
                                                                                    <p class="text-[15px] font-black text-black uppercase truncate {{ $lw === 'athlete1' ? 'text-orange-700' : '' }}">{{ $la1['name'] }}</p>
                                                                                    <p class="text-[15px] text-slate-800 uppercase truncate">{{ $la1['contingent'] ?? '' }}</p>
                                                                                @else
                                                                                    <p class="text-[15px] text-slate-300 italic">TBD</p>
                                                                                @endif
                                                                            </div>
                                                                            @if($lw === 'athlete1')<span class="text-[15px] font-black text-orange-600 shrink-0">W</span>@endif
                                                                        </div>
                                                                        <div class="flex items-center gap-2 p-2.5 {{ $lw === 'athlete2' ? 'bg-orange-50' : '' }}">
                                                                            <div class="w-1.5 h-5 rounded-full bg-slate-400 shrink-0"></div>
                                                                            <div class="flex-1 min-w-0">
                                                                                @if($la2)
                                                                                    <p class="text-[15px] font-black text-black uppercase truncate {{ $lw === 'athlete2' ? 'text-orange-700' : '' }}">{{ $la2['name'] }}</p>
                                                                                    <p class="text-[15px] text-slate-800 uppercase truncate">{{ $la2['contingent'] ?? '' }}</p>
                                                                                @else
                                                                                    <p class="text-[15px] text-slate-300 italic">TBD</p>
                                                                                @endif
                                                                            </div>
                                                                            @if($lw === 'athlete2')<span class="text-[15px] font-black text-orange-600 shrink-0">W</span>@endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- ═══ GRAND FINAL ═══ --}}
                                        @if($gf)
                                            <div class="mb-6 rounded-xl border border-amber-300 overflow-hidden shadow-sm">
                                                <div class="bg-amber-500 px-4 py-2.5">
                                                    <h3 class="text-[13px] font-black text-white uppercase tracking-widest">🏆 Grand Final &mdash; UB Champion vs LB Champion</h3>
                                                </div>
                                                <div class="bg-amber-50 p-6 flex flex-col md:flex-row items-center justify-center gap-6">
                                                    {{-- GF Athlete 1 (UB Winner) --}}
                                                    <div class="w-full md:w-[280px] bg-white border-2 {{ ($gf['winner'] ?? null) === 'athlete1' ? 'border-amber-400 ring-2 ring-amber-300' : 'border-slate-200' }} rounded-xl p-5 text-center shadow-sm">
                                                        <span class="text-[11px] font-black text-indigo-500 uppercase tracking-widest block mb-2">UB Champion</span>
                                                        @if($gf['athlete1'] ?? null)
                                                            <p class="text-[16px] font-black text-slate-800 uppercase">{{ $gf['athlete1']['name'] }}</p>
                                                            <p class="text-[14px] text-slate-500 mt-1 uppercase">{{ $gf['athlete1']['contingent'] ?? '' }}</p>
                                                        @else
                                                            <p class="text-[15px] text-slate-300 italic py-2">Menunggu...</p>
                                                        @endif
                                                    </div>

                                                    <div class="text-center shrink-0">
                                                        <span class="text-[14px] font-black text-amber-600 uppercase bg-amber-100 border border-amber-200 px-4 py-2 rounded-full">VS</span>
                                                    </div>

                                                    {{-- GF Athlete 2 (LB Winner) --}}
                                                    <div class="w-full md:w-[280px] bg-white border-2 {{ ($gf['winner'] ?? null) === 'athlete2' ? 'border-amber-400 ring-2 ring-amber-300' : 'border-slate-200' }} rounded-xl p-5 text-center shadow-sm">
                                                        <span class="text-[11px] font-black text-orange-500 uppercase tracking-widest block mb-2">LB Champion</span>
                                                        @if($gf['athlete2'] ?? null)
                                                            <p class="text-[16px] font-black text-slate-800 uppercase">{{ $gf['athlete2']['name'] }}</p>
                                                            <p class="text-[14px] text-slate-500 mt-1 uppercase">{{ $gf['athlete2']['contingent'] ?? '' }}</p>
                                                        @else
                                                            <p class="text-[15px] text-slate-300 italic py-2">Menunggu...</p>
                                                        @endif
                                                    </div>

                                                    @if($gf['winner'] ?? null)
                                                        <div class="w-full md:w-auto text-center mt-4 md:mt-0 md:ml-4">
                                                            <div class="inline-flex flex-col items-center bg-amber-500 border border-amber-600 px-6 py-3 rounded-xl shadow-md">
                                                                <span class="text-[10px] text-amber-100 uppercase font-black tracking-widest mb-1">Juara 1</span>
                                                                <span class="text-[16px] font-black text-white uppercase flex items-center gap-2">
                                                                    <i class="fas fa-trophy"></i> {{ $gf['winner_data']['name'] ?? '-' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif


                                        {{-- Ext Divider --}}
                                        <div class="mt-8 mb-6 border-b border-dashed border-slate-200"></div>
                                    @else
                                        {{-- No Drawing Yet --}}
                                        <div class="border-2 border-dashed border-slate-200 rounded-xl py-12 px-6 text-center">
                                            <i class="fas fa-sitemap text-slate-300 text-3xl mb-4"></i>
                                            <p class="text-[12px] font-black text-slate-800 uppercase tracking-widest">Bagan belum dibuat</p>
                                            <p class="text-[15px] text-slate-800 mt-1">Klik <strong>Drawing Bagan</strong> untuk mengundi peserta ke dalam Tree.</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Tab Content: Pendaftar --}}
                                <div x-show="globalTab === 'sebelum'" x-cloak class="p-5">
                                    <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-4">
                                        <i class="fas fa-users mr-1.5"></i>Data Pendaftar Atlet
                                    </p>
                                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                                        <div class="overflow-x-auto custom-scrollbar">
                                            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                                <thead class="bg-slate-800 text-white">
                                                    <tr class="border-b border-slate-100 bg-slate-50/50">
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">No</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Nama Peserta (Atlet)</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kontingen</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Rank</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-200">
                                                    @forelse($data['athletes'] as $idx => $ath)
                                                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                            <td class="px-4 py-3 text-center text-[15px] font-bold text-slate-800 border-r border-slate-200">{{ $idx + 1 }}</td>
                                                            <td class="px-4 py-3 text-[15px] font-black text-slate-800 uppercase whitespace-nowrap border-r border-slate-200">{{ $ath->name }}</td>
                                                            <td class="px-4 py-3 whitespace-nowrap border-r border-slate-200">
                                                                <div class="flex items-center gap-2">
                                                                    <i class="fas fa-shield-alt text-slate-800 text-[15px]"></i>
                                                                    <span class="text-[15px] font-black text-slate-900 uppercase tracking-wide">{{ $ath->contingent }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-4 py-3 text-center whitespace-nowrap border-r border-slate-200">
                                                                <span class="inline-block whitespace-nowrap px-3 py-1 bg-white text-slate-900 rounded-lg text-[15px] font-black uppercase border border-slate-200">
                                                                    {{ $ath->rank ?? '-' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="px-4 py-8 text-center text-slate-800 text-[15px] italic font-semibold border-r border-slate-200">Belum ada peserta</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tab Content: Hasil Bracket Randori --}}
                                <div x-show="globalTab === 'bracket'" x-cloak class="p-5">
                                    @php
                                        $randoriResults = $data['randori_results'] ?? collect();
                                        $drawingRaw = $data['drawing_data'] ?? null;
                                        $juaraMap = $drawingRaw['juara'] ?? [];
                                        $ubRounds = $drawingRaw['upper_bracket']['rounds'] ?? [];
                                        $lbRounds = $drawingRaw['lower_bracket']['rounds'] ?? [];
                                        $grandFinal = $drawingRaw['grand_final'] ?? null;
                                    @endphp

                                    {{-- Juara Cards (if available) --}}
                                    @if(!empty($juaraMap))
                                        <div class="mb-6">
                                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-3"><i class="fas fa-trophy mr-1.5 text-amber-500"></i>Hasil Akhir</p>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                @foreach([1=>'🥇',2=>'🥈',3=>'🥉',4=>'🏅'] as $rank => $medal)
                                                    @php $ath = $juaraMap[$rank] ?? null; @endphp
                                                    <div class="text-center p-4 rounded-2xl {{ $ath ? 'bg-amber-50 border border-amber-200 shadow-sm' : 'bg-slate-50 border border-slate-100' }}">
                                                        <div class="text-3xl mb-2">{{ $medal }}</div>
                                                        <div class="text-[15px] font-black text-slate-800 uppercase mb-1">Juara {{ $rank }}</div>
                                                        @if($ath)
                                                            <div class="text-[15px] font-black text-slate-800 uppercase leading-tight">{{ $ath['name'] }}</div>
                                                            <div class="text-[15px] text-slate-800 mt-0.5">{{ $ath['contingent'] ?? '' }}</div>
                                                        @else
                                                            <div class="text-[15px] text-slate-300 font-bold italic">Menunggu...</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="border-b border-dashed border-slate-200 mb-5"></div>
                                    @endif

                                    {{-- Grand Final --}}
                                    @if($grandFinal && (($grandFinal['athlete1'] ?? null) || ($grandFinal['athlete2'] ?? null)))
                                        <div class="mb-5">
                                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-2"><i class="fas fa-trophy text-amber-500 mr-1.5"></i>Grand Final</p>
                                            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="text-[15px] font-black text-black uppercase">{{ $grandFinal['athlete1']['name'] ?? 'TBD' }}</div>
                                                    <span class="text-[15px] font-black text-slate-800 uppercase bg-white border border-slate-200 px-2 py-0.5 rounded">vs</span>
                                                    <div class="text-[15px] font-black text-black uppercase">{{ $grandFinal['athlete2']['name'] ?? 'TBD' }}</div>
                                                </div>
                                                @if($grandFinal['winner'] ?? null)
                                                    <span class="text-[15px] font-black text-amber-700 bg-amber-100 border border-amber-300 px-3 py-1 rounded-lg">
                                                        🏆 {{ $grandFinal['winner_data']['name'] ?? '-' }}
                                                    </span>
                                                @else
                                                    <span class="text-[15px] font-black text-slate-800 uppercase italic">Belum selesai</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Progress Table: setiap match yang sudah ada hasilnya --}}
                                    @if($randoriResults->isNotEmpty())
                                        <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-3"><i class="fas fa-list mr-1.5"></i>Riwayat Pertandingan</p>
                                        <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                                            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                                <thead class="bg-slate-800 text-white">
                                                    <tr class="border-b border-slate-100 bg-slate-50">
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Jalur</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Ronde</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Merah (A1)</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Skor</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Putih (A2)</th>
                                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Pemenang</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-200">
                                                    @foreach($randoriResults as $nodeKey => $result)
                                                        @php
                                                            $parts = explode('_', $nodeKey);
                                                            $section = strtoupper($parts[0] ?? 'UB');
                                                            $rIdx = $parts[1] ?? 0;
                                                            $mIdx = $parts[2] ?? 0;

                                                            // Get match data from drawing
                                                            if ($section === 'UB') {
                                                                $matchNode = $ubRounds[$rIdx][$mIdx] ?? null;
                                                            } elseif ($section === 'LB') {
                                                                $matchNode = $lbRounds[$rIdx][$mIdx] ?? null;
                                                            } else {
                                                                $matchNode = $grandFinal;
                                                                $section = 'GF';
                                                            }
                                                            $a1Name = $matchNode['athlete1']['name'] ?? '?';
                                                            $a2Name = $matchNode['athlete2']['name'] ?? '?';
                                                            $winnerSlot = $result->winner_color;
                                                            $winnerName = $winnerSlot === 'athlete1' ? $a1Name : $a2Name;

                                                            $sectionColor = match($section) {
                                                                'UB' => 'text-indigo-600 bg-indigo-50 border-indigo-200',
                                                                'LB' => 'text-orange-600 bg-orange-50 border-orange-200',
                                                                'GF' => 'text-amber-700 bg-amber-50 border-amber-300',
                                                                default => 'text-slate-900 bg-slate-50 border-slate-200',
                                                            };
                                                        @endphp
                                                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                            <td class="px-3 py-3 border-r border-slate-200">
                                                                <span class="text-[15px] font-black px-2 py-0.5 rounded border {{ $sectionColor }} uppercase">{{ $section }}</span>
                                                            </td>
                                                            <td class="px-3 py-3 text-[15px] font-bold text-slate-900 border-r border-slate-200">
                                                                @if($section === 'GF') Grand Final @else R{{ (int)$rIdx + 1 }} M{{ (int)$mIdx + 1 }} @endif
                                                            </td>
                                                            <td class="px-3 py-3 text-[15px] font-black text-black uppercase {{ $winnerSlot === 'athlete1' ? 'text-rose-600' : '' }} border-r border-slate-200">{{ $a1Name }}</td>
                                                            <td class="px-3 py-3 text-center border-r border-slate-200">
                                                                <span class="text-[15px] font-black text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $result->score_red }} — {{ $result->score_blue }}</span>
                                                            </td>
                                                            <td class="px-3 py-3 text-[15px] font-black text-black uppercase {{ $winnerSlot === 'athlete2' ? 'text-indigo-600' : '' }} border-r border-slate-200">{{ $a2Name }}</td>
                                                            <td class="px-3 py-3 text-center border-r border-slate-200">
                                                                <span class="text-[15px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg">🏆 {{ $winnerName }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="border-2 border-dashed border-slate-200 rounded-xl py-12 text-center">
                                            <i class="fas fa-sitemap text-slate-300 text-3xl mb-4"></i>
                                            <p class="text-[12px] font-black text-slate-800 uppercase tracking-widest">Belum Ada Hasil Pertandingan</p>
                                            <p class="text-[15px] text-slate-800 mt-1">Hasil akan muncul setelah Panitera menginput skor di halaman Scoring.</p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 py-24 text-center shadow-sm">
                <i class="fas fa-sitemap text-slate-200 text-5xl mb-4"></i>
                <h3 class="text-[14px] font-black text-slate-800 uppercase tracking-widest">Belum Ada Data Drawing Randori</h3>
                <p class="text-[12px] text-slate-800 mt-2">Pastikan nomor pertandingan bertipe Randori telah memiliki pendaftar.</p>
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
                        <tr>
                            <th class="px-5 py-3.5 text-center text-[15px] font-black uppercase tracking-widest border border-slate-700">#</th>
                            <th class="px-5 py-3.5 text-center text-[15px] font-black uppercase tracking-widest border border-slate-700">Type</th>
                            <th class="px-5 py-3.5 text-left text-[15px] font-black uppercase tracking-widest border border-slate-700">Match / Kategori</th>
                            <th class="px-5 py-3.5 text-left text-[15px] font-black uppercase tracking-widest border border-slate-700">Kontingen / Bagan</th>
                            <th class="px-5 py-3.5 text-center text-[15px] font-black uppercase tracking-widest border border-slate-700">Posisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php $hasAtLeastOneDrawing = false; @endphp
                        @foreach($matchSummary as $gender => $ageGroups)
                            @foreach($ageGroups as $ageName => $matches)
                                @foreach($matches as $mId => $data)
                                    @if(isset($data['db_drawing_entries']) && $data['db_drawing_entries']->isNotEmpty())
                                        @php 
                                            $hasAtLeastOneDrawing = true; 
                                            $firstMatchEntry = $data['db_drawing_entries']->first();
                                        @endphp
                                        <tr class="bg-slate-800">
                                            <td colspan="5" class="px-5 py-2">
                                                <div class="flex items-center gap-4">
                                                    <span class="text-[12px] font-black text-white/60 uppercase tracking-widest">Update Match #{{ $mId }}:</span>
                                                    {{-- Edit Court --}}
                                                    <select wire:change="updateMatchDrawingsField({{ $mId }}, 'court_id', $event.target.value)" 
                                                            class="text-[11px] font-black bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded px-2 py-1 outline-none transition cursor-pointer">
                                                        <option value="" class="text-slate-900">No Court</option>
                                                        @foreach($courts as $c)
                                                            <option value="{{ $c->id }}" {{ $firstMatchEntry->court_id == $c->id ? 'selected' : '' }} class="text-slate-900">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    {{-- Edit Rundown --}}
                                                    <select wire:change="updateMatchDrawingsField({{ $mId }}, 'rundown_id', $event.target.value)" 
                                                            class="text-[11px] font-black bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded px-2 py-1 outline-none transition cursor-pointer">
                                                        <option value="" class="text-slate-900">No Rundown</option>
                                                        @foreach($rundowns as $r)
                                                            <option value="{{ $r->id }}" {{ $firstMatchEntry->rundown_id == $r->id ? 'selected' : '' }} class="text-slate-900">{{ $r->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach($data['db_drawing_entries'] as $entry)
                                            <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                <td class="px-5 py-3.5 text-center whitespace-nowrap border-r border-slate-200">
                                                    <span class="inline-block border border-slate-200 text-black px-2.5 py-1 rounded-lg text-[15px] font-black">#{{ $mId }}</span>
                                                </td>
                                                <td class="px-5 py-3.5 text-center whitespace-nowrap border-r border-slate-200">
                                                    <span class="inline-block bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[15px] font-black uppercase shadow-sm">RANDORI</span>
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
                                                    <p class="text-[15px] text-slate-800 mt-0.5 uppercase font-bold">{{ $entry->pool->name ?? 'BAGAN' }}</p>
                                                </td>
                                                <td class="px-5 py-3.5 text-center whitespace-nowrap border-r border-slate-200">
                                                    <span class="inline-block border border-amber-200 text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg text-[15px] font-black">Posisi {{ $entry->sequence_number + 1 }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                        @if(!$hasAtLeastOneDrawing)
                            <tr>
                                <td colspan="6" class="py-20 text-center border-r border-slate-200">
                                    <i class="fas fa-list-ol text-slate-200 text-4xl mb-3 block"></i>
                                    <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Belum ada jadwal pertandingan Randori</p>
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
        <div class="mt-4">
            {{ $paginatedMatches->links('livewire.admin.pagination') }}
        </div>
    @endif
</div>
