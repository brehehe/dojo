<div wire:poll.2s
     x-data="{
         resize() {
             let wrapper = $refs.wrapper;
             let content = $refs.content;
             if (!wrapper || !content) return;
             
             content.style.transform = 'none';
             let wHeight = wrapper.clientHeight;
             let cHeight = content.scrollHeight;
             
             if (cHeight > wHeight) {
                 let scale = wHeight / cHeight;
                 content.style.transform = `scale(${scale})`;
             }
         }
     }"
     x-init="
         $nextTick(() => resize());
         setInterval(() => resize(), 500);
     "
     @resize.window="resize()"
     x-ref="wrapper"
     class="h-screen w-full bg-slate-50 overflow-hidden font-sans select-none flex flex-col items-center">
    
    <div x-ref="content" class="w-full min-h-screen flex flex-col justify-between origin-top transition-transform duration-300">
    @if(!$court->active_match_id || !$court->activeMatch)
        <!-- IDLE STATE -->
        <div class="flex-1 flex flex-col items-center justify-center h-full p-6 text-center">
            <div class="w-32 h-32 md:w-48 md:h-48 rounded-[2rem] md:rounded-[3rem] bg-white flex items-center justify-center shadow-xl mb-8 md:mb-12 animate-bounce border border-slate-200">
                <i class="fas fa-tv text-5xl md:text-7xl text-slate-300"></i>
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-slate-800 tracking-widest uppercase mb-4 opacity-50">{{ $court->name }}</h1>
            <p class="text-xl md:text-2xl font-bold text-slate-500 uppercase tracking-widest animate-pulse">Menunggu Panggilan Pertandingan...</p>
        </div>
    @else
        <!-- ACTIVE MATCH STATE -->
        @php
            $match      = $court->activeMatch;
            $drawing    = $court->activeDrawing; // DrawingMatchNumber aktif (per slot)
            $isRandori  = ($match->draft_type === 'randori') || ($drawing?->draft_type === 'randori');

            // Konteks dari drawing aktif
            $dPool      = $drawing?->pool;
            $dSession   = $drawing?->sessionTime;
            $dRundown   = $drawing?->rundown;
            $dRound     = $drawing?->round;
            $dCourt     = $drawing?->court;
            $dContingent = $drawing?->registration?->contingent;

            // Athletes — untuk embu hanya tampilkan atlet dari registrasi yang sedang dipanggil.
            // Untuk randori: ambil semua (vs layout).
            $activeRegId = $court->active_registration_id;
            if ($isRandori) {
                $athletes = $match->athletes;
            } elseif ($activeRegId) {
                $athletes = $match->athletes->filter(
                    fn ($a) => $a->pivot->registration_id == $activeRegId
                )->values();
            } else {
                $athletes = $match->athletes;
            }
        @endphp

        <!-- Header -->
        <div class="bg-gradient-to-r {{ $isRandori ? 'from-rose-600 to-rose-900' : 'from-emerald-600 to-emerald-900' }} px-4 py-6 sm:px-6 sm:py-8 md:px-10 md:py-9 xl:px-16 xl:py-10 shadow-2xl relative overflow-hidden flex-shrink-0">
            <div class="absolute inset-0 bg-black/20"></div>
            <!-- Decorative icon -->
            <i class="fas {{ $isRandori ? 'fa-fire-alt' : 'fa-layer-group' }} absolute -right-8 -top-8 text-[6.875rem] sm:text-[8.75rem] md:text-[11.25rem] xl:text-[12.5rem] text-white opacity-10 blur-sm pointer-events-none"></i>

            <div class="relative z-10 flex flex-col items-center justify-center text-center gap-4">
                <div class="inline-flex items-center gap-2 md:gap-4 bg-white/20 backdrop-blur-md px-3 py-1.5 sm:px-4 md:px-6 md:py-2 rounded-full border border-white/20">
                    <span class="w-2 h-2 md:w-3 md:h-3 bg-white rounded-full animate-pulse"></span>
                    <h2 class="text-[11px] sm:text-sm md:text-xl font-black text-white uppercase tracking-[0.2em] md:tracking-widest">{{ $court->name }} MENGUNDANG</h2>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-6xl xl:text-[5.5rem] leading-[0.95] font-black text-white uppercase tracking-tight md:tracking-tighter drop-shadow-lg px-2 sm:px-4">
                    {{ $match->name }}
                </h1>
                <p class="text-sm sm:text-lg md:text-2xl xl:text-3xl text-white/80 font-black uppercase tracking-[0.16em] sm:tracking-[0.2em] md:tracking-[0.3em] px-2 sm:px-4">
                    Kategori {{ ucfirst($match->draft_type) }} &bull; {{ $match->ageGroup?->name ?? 'Dewasa' }}
                </p>

                {{-- Konteks meta: Pool · Babak · Sesi · Rundown · Kontingen --}}
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 mt-2">
                    @if($dPool)
                        <span class="inline-flex items-center gap-2 bg-white/20 px-3 py-1.5 sm:px-5 sm:py-2 rounded-full text-white text-xs sm:text-sm md:text-lg font-black uppercase tracking-[0.16em] md:tracking-wider border border-white/20">
                            <i class="fas fa-th text-white/60 text-[12px] sm:text-[15px]"></i>{{ $dPool->name }}
                        </span>
                    @endif
                    @if($dRound)
                        <span class="inline-flex items-center gap-2 bg-amber-400/30 px-3 py-1.5 sm:px-5 sm:py-2 rounded-full text-amber-100 text-xs sm:text-sm md:text-lg font-black uppercase tracking-[0.16em] md:tracking-wider border border-amber-300/30">
                            <i class="fas fa-flag text-amber-300 text-[12px] sm:text-[15px]"></i>{{ $dRound }}
                        </span>
                    @endif
                    @if($dSession)
                        <span class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-white/80 text-[11px] sm:text-sm md:text-base font-bold uppercase tracking-[0.14em] md:tracking-wider border border-white/10">
                            <i class="fas fa-clock text-white/50 text-[12px] sm:text-[15px]"></i>{{ $dSession->name }}
                        </span>
                    @endif
                    @if($dRundown)
                        <span class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-white/80 text-[11px] sm:text-sm md:text-base font-bold uppercase tracking-[0.14em] md:tracking-wider border border-white/10">
                            <i class="fas fa-calendar text-white/50 text-[12px] sm:text-[15px]"></i>{{ $dRundown->name ?? $dRundown->date }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Body / Kontingen Display -->
        <div class="flex-1 px-3 py-5 sm:px-4 sm:py-6 md:px-8 md:py-8 xl:px-16 flex flex-col items-center justify-center w-full">

            @if($isRandori)
                <!-- RANDORI LAYOUT (Versus Style) -->
                @if($athletes->count() >= 2)
                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto_1fr] w-full max-w-7xl gap-6 md:gap-8 items-center">

                        <!-- SUDUT MERAH (AKA) -->
                        <div class="bg-rose-600 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-rose-600/20 flex flex-col items-center text-center transform hover:scale-[1.02] transition-transform w-full">
                            <div class="px-4 py-1.5 md:px-6 md:py-2 bg-white/20 rounded-xl mb-4 md:mb-6 border border-white/30 backdrop-blur-md">
                                <span class="text-sm md:text-lg font-black text-white uppercase tracking-widest">SUDUT MERAH (AKA)</span>
                            </div>

                            {{-- Foto AKA --}}
                            <div class="w-32 h-32 md:w-48 md:h-48 xl:w-64 xl:h-64 rounded-[1.5rem] overflow-hidden border-4 border-rose-300/40 shadow-xl mb-4 md:mb-6 bg-rose-800 shrink-0">
                                @if($athletes[0]->photo_path)
                                    <img src="{{ asset('storage/' . $athletes[0]->photo_path) }}"
                                         alt="{{ $athletes[0]->name }}"
                                         class="w-full h-full object-cover object-center">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-5xl md:text-7xl xl:text-[5.625rem] font-black text-rose-300 uppercase">
                                            {{ substr($athletes[0]->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <h2 class="text-xl md:text-3xl font-black text-white uppercase leading-tight mb-3 line-clamp-2">{{ $athletes[0]->name }}</h2>
                            <div class="w-full bg-rose-800 rounded-2xl py-3 md:py-4 border border-rose-500/50">
                                <p class="text-xs md:text-sm font-bold text-rose-300 uppercase tracking-widest mb-1">Kontingen</p>
                                <p class="text-lg md:text-2xl font-black text-rose-100 uppercase truncate px-2">
                                    {{ $athletes[0]->registrations->first()?->contingent?->name ?? 'Unknown' }}</p>
                            </div>
                        </div>

                        <!-- VS -->
                        <div class="flex flex-col items-center justify-center py-4 md:py-6 relative">
                            <span class="text-4xl md:text-6xl lg:text-[5rem] font-black text-slate-300 italic block relative z-10 drop-shadow-sm">VS</span>
                            <div class="hidden lg:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[150%] h-1 bg-slate-200 z-0"></div>
                        </div>

                        <!-- SUDUT BIRU (AO) -->
                        <div class="bg-blue-600 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-600/20 flex flex-col items-center text-center transform hover:scale-[1.02] transition-transform w-full">
                            <div class="px-4 py-1.5 md:px-6 md:py-2 bg-white/20 rounded-xl mb-4 md:mb-6 border border-white/30 backdrop-blur-md">
                                <span class="text-sm md:text-lg font-black text-white uppercase tracking-widest">SUDUT BIRU (AO)</span>
                            </div>

                            {{-- Foto AO --}}
                            <div class="w-32 h-32 md:w-48 md:h-48 xl:w-64 xl:h-64 rounded-[1.5rem] overflow-hidden border-4 border-blue-300/40 shadow-xl mb-4 md:mb-6 bg-blue-800 shrink-0">
                                @if($athletes[1]->photo_path)
                                    <img src="{{ asset('storage/' . $athletes[1]->photo_path) }}"
                                         alt="{{ $athletes[1]->name }}"
                                         class="w-full h-full object-cover object-center">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-5xl md:text-7xl xl:text-[5.625rem] font-black text-blue-300 uppercase">
                                            {{ substr($athletes[1]->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <h2 class="text-xl md:text-3xl font-black text-white uppercase leading-tight mb-3 line-clamp-2">{{ $athletes[1]->name }}</h2>
                            <div class="w-full bg-blue-800 rounded-2xl py-3 md:py-4 border border-blue-500/50">
                                <p class="text-xs md:text-sm font-bold text-blue-300 uppercase tracking-widest mb-1">Kontingen</p>
                                <p class="text-lg md:text-2xl font-black text-blue-100 uppercase truncate px-2">
                                    {{ $athletes[1]->registrations->first()?->contingent?->name ?? 'Unknown' }}</p>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="text-4xl font-black text-slate-900 uppercase">Menunggu Daftar Atlet Randori...</div>
                @endif

            @else
                <!-- EMBU LAYOUT (Grid / List Style) -->
                <div class="w-full max-w-7xl mx-auto">
                    {{-- Header info kontingen & pool --}}
                    @if($dContingent || $dPool)
                        <div class="flex w-full flex-col items-stretch justify-center gap-3 sm:items-center md:flex-row md:flex-wrap md:gap-6 mb-6 sm:mb-8 md:mb-10">
                            @if($dContingent)
                                <div class="inline-flex w-full items-center justify-center gap-2 bg-emerald-50 px-4 py-3 sm:w-auto sm:gap-3 sm:px-6 md:px-8 md:py-4 rounded-2xl md:rounded-3xl border border-emerald-200 shadow-sm">
                                    <i class="fas fa-shield-alt text-xl sm:text-2xl md:text-3xl text-emerald-500"></i>
                                    <span class="text-center text-base sm:text-xl md:text-3xl font-black text-emerald-700 uppercase tracking-[0.12em] sm:tracking-wider break-words">{{ $dContingent->name }}</span>
                                </div>
                            @endif
                            @if($dPool)
                                <div class="inline-flex w-full items-center justify-center gap-2 bg-indigo-50 px-4 py-3 sm:w-auto sm:gap-3 sm:px-6 md:px-8 md:py-4 rounded-2xl md:rounded-3xl border border-indigo-200 shadow-sm">
                                    <i class="fas fa-th text-xl sm:text-2xl md:text-3xl text-indigo-500"></i>
                                    <span class="text-center text-base sm:text-xl md:text-3xl font-black text-indigo-700 uppercase tracking-[0.12em] sm:tracking-wider break-words">Pool {{ $dPool->name }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <h3 class="relative mb-6 text-center text-xl sm:text-2xl md:mb-10 md:text-4xl lg:mb-14 lg:text-5xl font-black text-slate-800 uppercase tracking-[0.18em] sm:tracking-[0.24em] md:tracking-widest">
                        <span class="relative z-10 bg-slate-50 px-4 sm:px-6">Daftar Penampil</span>
                        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-slate-200 z-0"></div>
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-4 sm:gap-5 md:gap-6 xl:grid-cols-2 xl:gap-8">
                        @foreach($athletes as $index => $ath)
                            <div class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200/80 bg-white p-4 shadow-[0_14px_36px_-20px_rgba(15,23,42,0.28)] transition duration-300 sm:p-5 md:p-6 xl:p-7">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 via-emerald-500 to-teal-500 opacity-70"></div>

                                <div class="flex flex-col gap-4 sm:gap-5 md:flex-row md:items-center md:gap-6">
                                    {{-- Nomor urut --}}
                                    <div class="flex items-center justify-between gap-3 md:flex-col md:justify-center">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-100 shadow-inner sm:h-14 sm:w-14 md:h-16 md:w-16">
                                            <span class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-400">{{ $index + 1 }}</span>
                                        </div>
                                        <span class="text-[11px] font-black uppercase tracking-[0.28em] text-slate-400 md:hidden">Urutan Tampil</span>
                                    </div>

                                    {{-- Foto Atlet --}}
                                    <div class="mx-auto h-28 w-28 overflow-hidden rounded-[1.5rem] border-4 border-slate-100 bg-slate-100 shadow-lg sm:h-36 sm:w-36 md:mx-0 md:h-40 md:w-40 lg:h-44 lg:w-44 shrink-0">
                                        @if($ath->photo_path)
                                            <img src="{{ asset('storage/' . $ath->photo_path) }}"
                                                 alt="{{ $ath->name }}"
                                                 class="h-full w-full object-cover object-center">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-500 to-emerald-700">
                                                <span class="text-4xl sm:text-5xl md:text-6xl lg:text-[4.875rem] font-black text-white uppercase">
                                                    {{ substr($ath->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info Atlet --}}
                                    <div class="min-w-0 flex-1 text-center md:text-left">
                                        <div class="flex flex-col gap-3">
                                            <h4 class="text-xl sm:text-2xl md:text-3xl lg:text-[2.2rem] font-black text-slate-800 uppercase leading-[1.05] break-words">
                                                {{ $ath->name }}
                                            </h4>

                                            <div class="inline-flex max-w-full items-center justify-center gap-2 self-center rounded-2xl border border-emerald-100 bg-emerald-50 px-3 py-2 sm:gap-3 sm:px-4 md:self-start md:px-5 md:py-3">
                                                <i class="fas fa-shield-alt shrink-0 text-base sm:text-lg md:text-2xl text-emerald-500"></i>
                                                <span class="min-w-0 break-words text-center text-sm sm:text-base md:text-lg lg:text-xl font-bold text-emerald-700 uppercase tracking-[0.12em] sm:tracking-[0.16em]">
                                                    {{ $ath->registrations->first()?->contingent?->name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    @endif

    <!-- Footer -->
    <div class="bg-white px-6 md:px-12 py-4 md:py-6 flex flex-col md:flex-row items-center justify-between gap-4 flex-shrink-0 border-t border-slate-200 shadow-[0_-4px_6px_-1px_rgb(0,0,0,0.05)]">
        <div class="flex items-center gap-4">
            <span class="text-lg md:text-xl font-black text-slate-800 tracking-widest uppercase">PERKEMI TIMING SYSTEM</span>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-3 md:gap-6 text-slate-500">
            @if(isset($dSession))
                <span class="text-sm md:text-[15px] font-bold uppercase tracking-widest">{{ $dSession->name }}</span>
            @endif
            @if(isset($dRundown))
                <span class="text-sm md:text-[15px] font-bold uppercase tracking-widest">{{ $dRundown->name ?? $dRundown->date }}</span>
            @endif
            <span class="text-sm md:text-[15px] font-black text-slate-400 uppercase tracking-widest">LIVE ARBITRASE</span>
        </div>
    </div>
    </div>
</div>
