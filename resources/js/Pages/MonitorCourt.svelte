<script>
    import { onMount, onDestroy } from 'svelte';

    // Props passed from Inertia
    let { courtId } = $props();

    // Svelte 5 Reactive States
    let court = $state(null);
    let time = $state(0);
    let running = $state(false);
    let countdown = $state(0);
    let offset = $state(0);
    let stateObj = $state(null);
    let loaded = $state(false);
    let buzzerPool = [];

    let pollInterval;
    let localTickInterval;

    async function sync() {
        try {
            let res = await fetch(`/api/svelte-monitor/court/${courtId}/state`);
            let data = await res.json();
            if (!data) return;

            court = data.court;
            let newState = data.timer_state;
            if (newState) {
                offset = newState.server_time_ms - Date.now();
                stateObj = newState;

                let wasRunning = running;
                running = (newState.status === 'running');

                // Play buzzer when timer newly starts
                if (running && !wasRunning && (!newState.elapsed_ms || newState.elapsed_ms < 1000)) {
                    playBuzzer();
                }
            }
            loaded = true;
        } catch (e) {
            console.error('Error syncing court monitor state:', e);
        }
    }

    function playBuzzer() {
        try {
            let audio = buzzerPool.find(a => a.paused || a.ended);
            if (!audio) {
                audio = new Audio('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
                audio.preload = 'auto';
                buzzerPool.push(audio);
            }
            audio.currentTime = 0;
            audio.play().catch(e => console.warn(e));
        } catch(e) {}
    }

    function formatTime() {
        let t = Math.max(0, time);
        let m = Math.floor(t / 60000);
        let s = Math.floor((t % 60000) / 1000);
        let ms = Math.floor((t % 1000) / 10);
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}.${ms.toString().padStart(2, '0')}`;
    }

    // Derived states
    let match = $derived(court?.active_match);
    let drawing = $derived(court?.active_drawing);
    let isRandori = $derived(match?.draft_type === 'randori' || drawing?.draft_type === 'randori');

    let athletes = $derived.by(() => {
        if (!court || !match) return [];
        let activeRegId = court.active_registration_id;

        if (isRandori) {
            return match.athletes || [];
        }

        if (activeRegId && drawing) {
            let athleteIds = drawing.metadata?.athlete_ids || [];
            if (athleteIds.length > 0) {
                let originalAthletes = drawing.match_number?.athletes || [];
                return originalAthletes.filter(a => athleteIds.includes(a.id));
            } else {
                let originalAthletes = drawing.match_number?.athletes || [];
                return originalAthletes.filter(a => a.pivot?.registration_id == activeRegId);
            }
        }

        if (activeRegId) {
            let matchAthletes = match.athletes || [];
            return matchAthletes.filter(a => a.pivot?.registration_id == activeRegId);
        }

        return match.athletes || [];
    });

    onMount(() => {
        // Preload buzzer audio to eliminate latency
        try {
            for (let i = 0; i < 3; i++) {
                let audio = new Audio('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
                audio.preload = 'auto';
                audio.load();
                buzzerPool.push(audio);
            }
        } catch (e) {
            console.warn('Failed to preload buzzer audio:', e);
        }

        sync();
        pollInterval = setInterval(sync, 1000); // Poll court state every 1s

        // High-speed local interpolation (30ms) for smooth timer
        localTickInterval = setInterval(() => {
            if (running && stateObj && stateObj.started_at_ms) {
                let expected = (stateObj.elapsed_ms || 0) + (Date.now() + offset - stateObj.started_at_ms);
                time = isRandori ? Math.min(expected, 120000) : expected;
            } else if (stateObj && stateObj.status === 'countdown' && stateObj.countdown_end_ms) {
                let remaining = stateObj.countdown_end_ms - (Date.now() + offset);
                if (remaining > 0) {
                    countdown = Math.ceil(remaining / 1000);
                } else {
                    countdown = 0;
                }
                let rawTime = stateObj.elapsed_ms || 0;
                time = isRandori ? Math.min(rawTime, 120000) : rawTime;
            } else if (stateObj) {
                countdown = 0;
                let rawTime = stateObj.elapsed_ms || 0;
                time = isRandori ? Math.min(rawTime, 120000) : rawTime;
            }
        }, 30);
    });

    onDestroy(() => {
        clearInterval(pollInterval);
        clearInterval(localTickInterval);
    });
</script>

<style>
    /* Scale all rem units to fit viewport perfectly on landscape screens */
    @media (min-aspect-ratio: 4/3) {
        :global(html) {
            font-size: min(100vw / 1920 * 16, 100vh / 1250 * 16) !important;
        }
    }
</style>

<div class="min-h-screen bg-slate-50 flex flex-col justify-between font-sans overflow-hidden select-none">
    {#if !loaded}
        <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">
            <p class="text-xl font-bold text-slate-500 uppercase tracking-widest animate-pulse">Memuat Data Lapangan...</p>
        </div>
    {:else if !court || !court.active_match_id || !court.active_match}
        <!-- IDLE STATE -->
        <div class="flex-1 flex flex-col items-center justify-center h-full p-6 text-center">
            <div class="w-32 h-32 md:w-48 md:h-48 rounded-[2rem] md:rounded-[3rem] bg-white flex items-center justify-center shadow-xl mb-8 md:mb-12 animate-bounce border border-slate-200">
                <i class="fas fa-tv text-5xl md:text-7xl text-slate-300"></i>
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-slate-800 tracking-widest uppercase mb-4 opacity-50">
                {court ? court.name : 'LAPANGAN'}
            </h1>
            <p class="text-xl md:text-2xl font-bold text-slate-500 uppercase tracking-widest animate-pulse">
                Menunggu Panggilan Pertandingan...
            </p>
        </div>
    {:else}
        <!-- ACTIVE MATCH STATE -->
        <!-- Header -->
        <div class="bg-gradient-to-r {isRandori ? 'from-rose-600 to-rose-900' : 'from-emerald-600 to-emerald-900'} px-4 py-6 sm:px-6 sm:py-8 md:px-10 md:py-9 xl:px-16 xl:py-10 shadow-2xl relative overflow-hidden flex-shrink-0">
            <div class="absolute inset-0 bg-black/20"></div>
            <!-- Decorative icon -->
            <i class="fas {isRandori ? 'fa-fire-alt' : 'fa-layer-group'} absolute -right-8 -top-8 text-[6.875rem] sm:text-[8.75rem] md:text-[11.25rem] xl:text-[12.5rem] text-white opacity-10 blur-sm pointer-events-none"></i>

            <div class="relative z-10 flex flex-col items-center justify-center text-center gap-4">
                <div class="inline-flex items-center gap-2 md:gap-4 bg-white/20 backdrop-blur-md px-3 py-1.5 sm:px-4 md:px-6 md:py-2 rounded-full border border-white/20">
                    <span class="w-2 h-2 md:w-3 md:h-3 bg-white rounded-full animate-pulse"></span>
                    <h2 class="text-[11px] sm:text-sm md:text-xl font-black text-white uppercase tracking-[0.2em] md:tracking-widest">
                        {court.name} MENGUNDANG
                    </h2>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-6xl xl:text-[5.5rem] leading-[0.95] font-black text-white uppercase tracking-tight md:tracking-tighter drop-shadow-lg px-2 sm:px-4">
                    {match.merge_detail?.merge?.name || match.name}
                </h1>
                <p class="text-sm sm:text-lg md:text-2xl xl:text-3xl text-white/80 font-black uppercase tracking-[0.16em] sm:tracking-[0.2em] md:tracking-[0.3em] px-2 sm:px-4">
                    {#if drawing && drawing.match_number_id != match.id}
                        <span class="text-amber-300">{drawing.match_number?.name || ''}</span> &bull;
                    {/if}
                    Kategori {match.draft_type ? match.draft_type.toUpperCase() : ''} &bull; {match.age_group?.name || 'Dewasa'}
                </p>

                <!-- Context meta: Pool · Babak · Sesi · Rundown -->
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 mt-2">
                    {#if drawing && drawing.pool}
                        <span class="inline-flex items-center gap-2 bg-white/20 px-3 py-1.5 sm:px-5 sm:py-2 rounded-full text-white text-xs sm:text-sm md:text-lg font-black uppercase tracking-[0.16em] md:tracking-wider border border-white/20">
                            <i class="fas fa-th text-white/60 text-[12px] sm:text-[15px]"></i>{drawing.pool.name}
                        </span>
                    {/if}
                    {#if drawing && drawing.round}
                        <span class="inline-flex items-center gap-2 bg-amber-400/30 px-3 py-1.5 sm:px-5 sm:py-2 rounded-full text-amber-100 text-xs sm:text-sm md:text-lg font-black uppercase tracking-[0.16em] md:tracking-wider border border-amber-300/30">
                            <i class="fas fa-flag text-amber-300 text-[12px] sm:text-[15px]"></i>{drawing.round}
                        </span>
                    {/if}
                    {#if drawing && drawing.session_time}
                        <span class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-white/80 text-[11px] sm:text-sm md:text-base font-bold uppercase tracking-[0.14em] md:tracking-wider border border-white/10">
                            <i class="fas fa-clock text-white/50 text-[12px] sm:text-[15px]"></i>{drawing.session_time.name}
                        </span>
                    {/if}
                    {#if drawing && drawing.rundown}
                        <span class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-white/80 text-[11px] sm:text-sm md:text-base font-bold uppercase tracking-[0.14em] md:tracking-wider border border-white/10">
                            <i class="fas fa-calendar text-white/50 text-[12px] sm:text-[15px]"></i>{drawing.rundown.name || drawing.rundown.date}
                        </span>
                    {/if}
                </div>
            </div>
        </div>

        <!-- Body / Contingent Display -->
        <div class="flex-1 px-3 py-5 sm:px-4 sm:py-6 md:px-8 md:py-8 xl:px-16 flex flex-col items-center justify-center w-full relative">
            <!-- TIMER DISPLAY -->
            <div class="mb-10 w-full flex flex-col items-center justify-center">
                <div class="font-mono text-[4vw] md:text-[4rem] xl:text-[8rem] font-black text-slate-800 leading-none tracking-tighter transition-colors duration-300 drop-shadow-sm"
                    class:text-emerald-500={running}
                    class:text-amber-500={!running && time > 0}
                    class:text-slate-300={time === 0}>
                    <span>{formatTime()}</span>
                </div>
            </div>

            {#if isRandori}
                <!-- RANDORI LAYOUT (Versus Style) -->
                {#if athletes.length >= 2}
                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto_1fr] w-full max-w-full gap-6 md:gap-8 items-center">
                        <!-- SUDUT MERAH (AKA) -->
                        <div class="bg-rose-600 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-rose-600/20 flex flex-col items-center text-center transform hover:scale-[1.02] transition-transform w-full">
                            <div class="px-4 py-1.5 md:px-6 md:py-2 bg-white/20 rounded-xl mb-4 md:mb-6 border border-white/30 backdrop-blur-md">
                                <span class="text-sm md:text-lg font-black text-white uppercase tracking-widest">SUDUT MERAH (AKA)</span>
                            </div>

                            <!-- Foto AKA -->
                            <div class="w-32 h-32 md:w-48 md:h-48 xl:w-64 xl:h-64 rounded-[1.5rem] overflow-hidden border-4 border-rose-300/40 shadow-xl mb-4 md:mb-6 bg-rose-800 shrink-0">
                                {#if athletes[0].photo_path}
                                    <img src={`/storage/${athletes[0].photo_path}`} alt={athletes[0].name} class="w-full h-full object-cover object-center">
                                {:else}
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-5xl md:text-7xl xl:text-[5.625rem] font-black text-rose-300 uppercase">
                                            {athletes[0].name ? athletes[0].name.charAt(0) : ''}
                                        </span>
                                    </div>
                                {/if}
                            </div>

                            <h2 class="text-xl md:text-3xl font-black text-white uppercase leading-tight mb-3 line-clamp-2">
                                {athletes[0].name}
                            </h2>
                            <div class="w-full bg-rose-800 rounded-2xl py-3 md:py-4 border border-rose-500/50">
                                <p class="text-xs md:text-sm font-bold text-rose-300 uppercase tracking-widest mb-1">Kontingen</p>
                                <p class="text-lg md:text-2xl font-black text-rose-100 uppercase truncate px-2">
                                    {athletes[0].registrations?.[0]?.contingent?.name || 'Unknown'}
                                </p>
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

                            <!-- Foto AO -->
                            <div class="w-32 h-32 md:w-48 md:h-48 xl:w-64 xl:h-64 rounded-[1.5rem] overflow-hidden border-4 border-blue-300/40 shadow-xl mb-4 md:mb-6 bg-blue-800 shrink-0">
                                {#if athletes[1].photo_path}
                                    <img src={`/storage/${athletes[1].photo_path}`} alt={athletes[1].name} class="w-full h-full object-cover object-center">
                                {:else}
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-5xl md:text-7xl xl:text-[5.625rem] font-black text-blue-300 uppercase">
                                            {athletes[1].name ? athletes[1].name.charAt(0) : ''}
                                        </span>
                                    </div>
                                {/if}
                            </div>

                            <h2 class="text-xl md:text-3xl font-black text-white uppercase leading-tight mb-3 line-clamp-2">
                                {athletes[1].name}
                            </h2>
                            <div class="w-full bg-blue-800 rounded-2xl py-3 md:py-4 border border-blue-500/50">
                                <p class="text-xs md:text-sm font-bold text-blue-300 uppercase tracking-widest mb-1">Kontingen</p>
                                <p class="text-lg md:text-2xl font-black text-blue-100 uppercase truncate px-2">
                                    {athletes[1].registrations?.[0]?.contingent?.name || 'Unknown'}
                                </p>
                            </div>
                        </div>
                    </div>
                {:else}
                    <div class="text-4xl font-black text-slate-900 uppercase">Menunggu Daftar Atlet Randori...</div>
                {/if}
            {:else}
                <!-- EMBU LAYOUT (Grid / List Style) -->
                <div class="w-full max-w-full mx-auto">
                    <!-- Header info kontingen & pool -->
                    {#if (drawing && drawing.registration?.contingent) || (drawing && drawing.pool)}
                        <div class="flex w-full flex-col items-stretch justify-center gap-3 sm:items-center md:flex-row md:flex-wrap md:gap-6 mb-6 sm:mb-8 md:mb-10">
                            {#if drawing && drawing.registration?.contingent}
                                <div class="inline-flex w-full items-center justify-center gap-2 bg-emerald-50 px-4 py-3 sm:w-auto sm:gap-3 sm:px-6 md:px-8 md:py-4 rounded-2xl md:rounded-3xl border border-emerald-200 shadow-sm">
                                    <i class="fas fa-shield-alt text-xl sm:text-2xl md:text-3xl text-emerald-500"></i>
                                    <span class="text-center text-base sm:text-xl md:text-3xl font-black text-emerald-700 uppercase tracking-[0.12em] sm:tracking-wider break-words">
                                        {drawing.registration.contingent.name}
                                    </span>
                                </div>
                            {/if}
                            {#if drawing && drawing.pool}
                                <div class="inline-flex w-full items-center justify-center gap-2 bg-indigo-50 px-4 py-3 sm:w-auto sm:gap-3 sm:px-6 md:px-8 md:py-4 rounded-2xl md:rounded-3xl border border-indigo-200 shadow-sm">
                                    <i class="fas fa-th text-xl sm:text-2xl md:text-3xl text-indigo-500"></i>
                                    <span class="text-center text-base sm:text-xl md:text-3xl font-black text-indigo-700 uppercase tracking-[0.12em] sm:tracking-wider break-words">
                                        {drawing.pool.name}
                                    </span>
                                </div>
                            {/if}
                        </div>
                    {/if}

                    <h3 class="relative mb-6 text-center text-xl sm:text-2xl md:mb-10 md:text-4xl lg:mb-14 lg:text-5xl font-black text-slate-800 uppercase tracking-[0.18em] sm:tracking-[0.24em] md:tracking-widest">
                        <span class="relative z-10 bg-slate-50 px-4 sm:px-6">Daftar Penampil</span>
                        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-slate-200 z-0"></div>
                    </h3>

                    <div class="grid grid-cols-1 gap-4 sm:gap-5 md:gap-6 xl:grid-cols-2 xl:gap-8">
                        {#each athletes as ath, index}
                            <div class="group relative overflow-hidden rounded-[1.75rem] border border-slate-200/80 bg-white p-4 shadow-[0_14px_36px_-20px_rgba(15,23,42,0.28)] transition duration-300 sm:p-5 md:p-6 xl:p-7">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 via-emerald-500 to-teal-500 opacity-70"></div>

                                <div class="flex flex-col gap-4 sm:gap-5 md:flex-row md:items-center md:gap-6">
                                    <!-- Nomor urut -->
                                    <div class="flex items-center justify-between gap-3 md:flex-col md:justify-center">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-100 shadow-inner sm:h-14 sm:w-14 md:h-16 md:w-16">
                                            <span class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-400">{index + 1}</span>
                                        </div>
                                        <span class="text-[11px] font-black uppercase tracking-[0.28em] text-slate-400 md:hidden">Urutan Tampil</span>
                                    </div>

                                    <!-- Foto Atlet -->
                                    <div class="mx-auto h-28 w-28 overflow-hidden rounded-[1.5rem] border-4 border-slate-100 bg-slate-100 shadow-lg sm:h-36 sm:w-36 md:mx-0 md:h-40 md:w-40 lg:h-44 lg:w-44 shrink-0">
                                        {#if ath.photo_path}
                                            <img src={`/storage/${ath.photo_path}`} alt={ath.name} class="h-full w-full object-cover object-center">
                                        {:else}
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-500 to-emerald-700">
                                                <span class="text-4xl sm:text-5xl md:text-6xl lg:text-[4.875rem] font-black text-white uppercase">
                                                    {ath.name ? ath.name.charAt(0) : ''}
                                                </span>
                                            </div>
                                        {/if}
                                    </div>

                                    <!-- Info Atlet -->
                                    <div class="min-w-0 flex-1 text-center md:text-left">
                                        <div class="flex flex-col gap-3">
                                            <h4 class="text-xl sm:text-2xl md:text-3xl lg:text-[2.2rem] font-black text-slate-800 uppercase leading-[1.05] break-words">
                                                {ath.name}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/each}
                    </div>
                </div>
            {/if}
        </div>
    {/if}

    <!-- Footer -->
    <div class="bg-white px-6 md:px-12 py-4 md:py-6 flex flex-col md:flex-row items-center justify-between gap-4 flex-shrink-0 border-t border-slate-200 shadow-[0_-4px_6px_-1px_rgb(0,0,0,0.05)]">
        <div class="flex items-center gap-4">
            <span class="text-lg md:text-xl font-black text-slate-800 tracking-widest uppercase">PERKEMI TIMING SYSTEM</span>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-3 md:gap-6 text-slate-500">
            {#if drawing && drawing.session_time}
                <span class="text-sm md:text-[15px] font-bold uppercase tracking-widest">{drawing.session_time.name}</span>
            {/if}
            {#if drawing && drawing.rundown}
                <span class="text-sm md:text-[15px] font-bold uppercase tracking-widest">{drawing.rundown.name || drawing.rundown.date}</span>
            {/if}
            <span class="text-sm md:text-[15px] font-black text-slate-400 uppercase tracking-widest">LIVE ARBITRASE</span>
        </div>
    </div>
</div>
