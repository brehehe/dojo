<div wire:poll.2s class="min-h-screen bg-slate-900 flex flex-col justify-between font-sans overflow-hidden select-none">

    @if(!$court->active_match_id || !$court->activeMatch)
        <!-- IDLE STATE -->
        <div class="flex-1 flex flex-col items-center justify-center h-full">
            <div class="w-48 h-48 rounded-[3rem] bg-slate-800 flex items-center justify-center shadow-2xl mb-12 animate-bounce">
                <i class="fas fa-tv text-7xl text-black"></i>
            </div>
            <h1 class="text-7xl font-black text-white tracking-widest uppercase mb-4 opacity-50">{{ $court->name }}</h1>
            <p class="text-2xl font-bold text-slate-900 uppercase tracking-widest animate-pulse">Menunggu Panggilan Pertandingan...</p>
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
            $dContingent = $drawing?->registration?->contingent;

            // Athletes — prefer athletes from match for embu (semua tampil),
            // untuk randori ambil dari drawing registration jika ada.
            $athletes   = $match->athletes;
        @endphp

        <!-- Header -->
        <div class="bg-gradient-to-r {{ $isRandori ? 'from-rose-600 to-rose-900' : 'from-emerald-600 to-emerald-900' }} px-16 py-10 shadow-2xl relative overflow-hidden flex-shrink-0">
            <div class="absolute inset-0 bg-black/20"></div>
            <!-- Decorative icon -->
            <i class="fas {{ $isRandori ? 'fa-fire-alt' : 'fa-layer-group' }} absolute -right-12 -top-12 text-[200px] text-white opacity-10 blur-sm pointer-events-none"></i>

            <div class="relative z-10 flex flex-col items-center justify-center text-center gap-4">
                <div class="inline-flex items-center gap-4 bg-white/20 backdrop-blur-md px-6 py-2 rounded-full border border-white/20">
                    <span class="w-3 h-3 bg-white rounded-full animate-pulse"></span>
                    <h2 class="text-xl font-black text-white uppercase tracking-widest">{{ $court->name }} MENGUNDANG</h2>
                </div>
                <h1 class="text-6xl md:text-7xl lg:text-[5.5rem] leading-none font-black text-white uppercase tracking-tighter drop-shadow-lg">
                    {{ $match->name }}
                </h1>
                <p class="text-3xl text-white/80 font-black uppercase tracking-[0.3em]">
                    Kategori {{ ucfirst($match->draft_type) }} &bull; {{ $match->ageGroup?->name ?? 'Dewasa' }}
                </p>

                {{-- Konteks meta: Pool · Babak · Sesi · Rundown · Kontingen --}}
                <div class="flex flex-wrap items-center justify-center gap-3 mt-2">
                    @if($dPool)
                        <span class="inline-flex items-center gap-2 bg-white/20 px-5 py-2 rounded-full text-white text-lg font-black uppercase tracking-wider border border-white/20">
                            <i class="fas fa-th text-white/60 text-[15px]"></i>Pool {{ $dPool->name }}
                        </span>
                    @endif
                    @if($dRound)
                        <span class="inline-flex items-center gap-2 bg-amber-400/30 px-5 py-2 rounded-full text-amber-100 text-lg font-black uppercase tracking-wider border border-amber-300/30">
                            <i class="fas fa-flag text-amber-300 text-[15px]"></i>{{ $dRound }}
                        </span>
                    @endif
                    @if($dSession)
                        <span class="inline-flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full text-white/80 text-base font-bold uppercase tracking-wider border border-white/10">
                            <i class="fas fa-clock text-white/50 text-[15px]"></i>{{ $dSession->name }}
                        </span>
                    @endif
                    @if($dRundown)
                        <span class="inline-flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full text-white/80 text-base font-bold uppercase tracking-wider border border-white/10">
                            <i class="fas fa-calendar text-white/50 text-[15px]"></i>{{ $dRundown->name ?? $dRundown->date }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Body / Kontingen Display -->
        <div class="flex-1 px-16 py-16 flex flex-col items-center justify-center">

            @if($isRandori)
                <!-- RANDORI LAYOUT (Versus Style) -->
                @if($athletes->count() >= 2)
                    <div class="grid grid-cols-1 md:grid-cols-3 w-full max-w-full gap-8 items-center">

                        <!-- SUDUT MERAH (AKA) -->
                        <div class="bg-rose-600 rounded-[3rem] p-12 shadow-2xl shadow-rose-900/30 flex flex-col items-center text-center transform hover:scale-[1.02] transition-transform">
                            <div class="px-6 py-2 bg-white/20 rounded-2xl mb-8 border border-white/30 backdrop-blur-md">
                                <span class="text-xl font-black text-white uppercase tracking-widest">SUDUT MERAH (AKA)</span>
                            </div>
                            <h2 class="text-5xl font-black text-white uppercase leading-tight mb-6">{{ $athletes[0]->name }}</h2>
                            <div class="w-full bg-rose-800 rounded-3xl py-6 border border-rose-500/50">
                                <p class="text-[15px] font-bold text-rose-300 uppercase tracking-widest mb-1">Kontingen</p>
                                <p class="text-4xl font-black text-rose-100 uppercase">
                                    {{ $athletes[0]->registrations->first()?->contingent?->name ?? 'Unknown' }}</p>
                            </div>
                        </div>

                        <!-- VS -->
                        <div class="flex flex-col items-center justify-center py-12 md:py-0 relative">
                            <span class="text-7xl font-black text-black italic block relative z-10">VS</span>
                            <div class="hidden md:block absolute top-1/2 left-0 right-0 h-1 bg-slate-800 z-0"></div>
                        </div>

                        <!-- SUDUT BIRU (AO) -->
                        <div class="bg-blue-600 rounded-[3rem] p-12 shadow-2xl shadow-blue-900/30 flex flex-col items-center text-center transform hover:scale-[1.02] transition-transform">
                            <div class="px-6 py-2 bg-white/20 rounded-2xl mb-8 border border-white/30 backdrop-blur-md">
                                <span class="text-xl font-black text-white uppercase tracking-widest">SUDUT BIRU (AO)</span>
                            </div>
                            <h2 class="text-5xl font-black text-white uppercase leading-tight mb-6">{{ $athletes[1]->name }}</h2>
                            <div class="w-full bg-blue-800 rounded-3xl py-6 border border-blue-500/50">
                                <p class="text-[15px] font-bold text-blue-300 uppercase tracking-widest mb-1">Kontingen</p>
                                <p class="text-4xl font-black text-blue-100 uppercase">
                                    {{ $athletes[1]->registrations->first()?->contingent?->name ?? 'Unknown' }}</p>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="text-4xl font-black text-slate-900 uppercase">Menunggu Daftar Atlet Randori...</div>
                @endif

            @else
                <!-- EMBU LAYOUT (Grid / List Style) -->
                <div class="w-full">
                    {{-- Header info kontingen & pool --}}
                    @if($dContingent || $dPool)
                        <div class="flex items-center justify-center gap-6 mb-10">
                            @if($dContingent)
                                <div class="inline-flex items-center gap-3 bg-emerald-500/15 border border-emerald-500/20 px-8 py-4 rounded-3xl">
                                    <i class="fas fa-shield-alt text-3xl text-emerald-400"></i>
                                    <span class="text-3xl font-black text-emerald-300 uppercase tracking-wider">{{ $dContingent->name }}</span>
                                </div>
                            @endif
                            @if($dPool)
                                <div class="inline-flex items-center gap-3 bg-indigo-500/15 border border-indigo-500/20 px-8 py-4 rounded-3xl">
                                    <i class="fas fa-th text-3xl text-indigo-400"></i>
                                    <span class="text-3xl font-black text-indigo-300 uppercase tracking-wider">Pool {{ $dPool->name }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <h3 class="text-center text-4xl md:text-5xl font-black text-slate-800 uppercase tracking-widest mb-16">
                        Daftar Penampil</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        @foreach($athletes as $index => $ath)
                            <div class="bg-slate-800 border-2 border-slate-700 rounded-[2rem] p-8 shadow-xl flex gap-8 items-center transform hover:scale-[1.02] transition-transform">
                                <div class="w-24 h-24 bg-slate-700 rounded-3xl flex items-center justify-center shrink-0 border border-slate-600 shadow-inner">
                                    <span class="text-5xl font-black text-slate-300">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-4xl font-black text-white uppercase mb-4 leading-tight">{{ $ath->name }}</h4>
                                    <div class="inline-flex items-center gap-3 bg-emerald-500/10 px-5 py-3 rounded-2xl border border-emerald-500/20">
                                        <i class="fas fa-shield-alt text-2xl text-emerald-400"></i>
                                        <span class="text-2xl font-bold text-emerald-400 uppercase tracking-wider">
                                            {{ $ath->registrations->first()?->contingent?->name ?? '-' }}
                                        </span>
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
    <div class="bg-black/90 px-12 py-6 flex items-center justify-between flex-shrink-0 border-t border-white/5">
        <div class="flex items-center gap-4">
            <span class="text-xl font-black text-white tracking-widest uppercase">PERKAMI TIMING SYSTEM</span>
        </div>
        <div class="flex items-center gap-6 text-slate-900">
            @if(isset($dSession))
                <span class="text-[15px] font-bold uppercase tracking-widest">{{ $dSession->name }}</span>
            @endif
            @if(isset($dRundown))
                <span class="text-[15px] font-bold uppercase tracking-widest">{{ $dRundown->name ?? $dRundown->date }}</span>
            @endif
            <span class="text-[15px] font-bold uppercase tracking-widest">LIVE ARBITRASE</span>
        </div>
    </div>
</div>