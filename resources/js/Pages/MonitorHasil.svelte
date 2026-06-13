<script>
    import { onMount, onDestroy } from 'svelte';

    // Props passed from Inertia
    let { courtId = null, matchId = null } = $props();

    // Svelte 5 Reactive States
    let court = $state(null);
    let match = $state(null);
    let drawingData = $state(null);
    let randoriResults = $state({});
    let embuRanking = $state([]);
    let activeNodeKey = $state(null);
    let loaded = $state(false);

    let pollInterval;

    // Computed polling URL
    const url = $derived(courtId
        ? `/api/svelte-monitor/hasil/court/${courtId}/state`
        : `/api/svelte-monitor/hasil/match/${matchId}/state`);

    async function sync() {
        try {
            let res = await fetch(url);
            let data = await res.json();
            if (!data) return;

            court = data.court;
            match = data.match;
            drawingData = data.drawingData;
            randoriResults = data.randoriResults || {};
            embuRanking = data.embuRanking || [];
            activeNodeKey = data.activeNodeKey;
            loaded = true;
        } catch (e) {
            console.error('Error syncing hasil monitor state:', e);
        }
    }

    let embuScrollEl = $state(null);
    let autoScrollEnabled = $state(true);

    let scrollInterval;
    let pauseTimer = null;
    let isPaused = false;
    let scrollDirection = 1; // 1 = down, -1 = up

    function handleUserInteraction() {
        isPaused = true;
        if (pauseTimer) clearTimeout(pauseTimer);
        pauseTimer = setTimeout(() => {
            isPaused = false;
        }, 5000); // Resume auto-scrolling after 5 seconds of inactivity
    }

    function startAutoScroll() {
        if (scrollInterval) clearInterval(scrollInterval);
        scrollInterval = setInterval(() => {
            const el = match?.draft_type === 'embu' ? embuScrollEl : scrollEl;
            
            console.log('[AutoScroll Debug]', {
                enabled: autoScrollEnabled,
                isPaused: isPaused,
                elExists: !!el,
                matchType: match?.draft_type,
                scrollTop: el ? el.scrollTop : null,
                scrollHeight: el ? el.scrollHeight : null,
                clientHeight: el ? el.clientHeight : null,
                isScrollable: el ? el.scrollHeight > el.clientHeight : false
            });

            if (!autoScrollEnabled || isPaused) return;
            if (!el) return;

            if (el.scrollHeight <= el.clientHeight) return;

            if (scrollDirection === 1) {
                el.scrollTop += 1;
                if (el.scrollTop + el.clientHeight >= el.scrollHeight - 2) {
                    isPaused = true;
                    console.log('[AutoScroll] Reached bottom, pausing 3s');
                    setTimeout(() => {
                        scrollDirection = -1;
                        isPaused = false;
                    }, 3000); // Pause 3s at the bottom
                }
            } else {
                el.scrollTop -= 4; // Scroll up faster
                if (el.scrollTop <= 0) {
                    el.scrollTop = 0;
                    scrollDirection = 1;
                    isPaused = true;
                    console.log('[AutoScroll] Reached top, pausing 3s');
                    setTimeout(() => {
                        isPaused = false;
                    }, 3000); // Pause 3s at the top
                }
            }
        }, 30);
    }

    onMount(() => {
        sync();
        pollInterval = setInterval(sync, 1000); // Poll every 1 second
        startAutoScroll();
    });

    onDestroy(() => {
        clearInterval(pollInterval);
        if (scrollInterval) clearInterval(scrollInterval);
        if (pauseTimer) clearTimeout(pauseTimer);
    });

    // Embu helper to check if scoring started
    function checkHasScore(row) {
        if (row.accumulated_score > 0) return true;
        let eff = row.effective_score;
        if (!eff) return false;
        return (
            (eff.nilai_akhir && parseFloat(eff.nilai_akhir) > 0) ||
            (eff.judge_1 && parseFloat(eff.judge_1) > 0) ||
            (eff.judge_2 && parseFloat(eff.judge_2) > 0) ||
            (eff.judge_3 && parseFloat(eff.judge_3) > 0) ||
            (eff.judge_4 && parseFloat(eff.judge_4) > 0) ||
            (eff.judge_5 && parseFloat(eff.judge_5) > 0)
        );
    }

    // Drag to scroll logic for Randori bracket
    let scrollEl = $state(null);
    let startX = 0, startY = 0, scrollLeft = 0, scrollTop = 0;

    function startDrag(e) {
        if (!scrollEl) return;
        startX = e.pageX - scrollEl.offsetLeft;
        startY = e.pageY - scrollEl.offsetTop;
        scrollLeft = scrollEl.scrollLeft;
        scrollTop = scrollEl.scrollTop;
    }

    function drag(e) {
        if (!scrollEl || e.buttons !== 1) return;
        e.preventDefault();
        const x = e.pageX - scrollEl.offsetLeft;
        const y = e.pageY - scrollEl.offsetTop;
        scrollEl.scrollLeft = scrollLeft - (x - startX);
        scrollEl.scrollTop = scrollTop - (y - startY);
    }

    // Upper and Lower Bracket computations for Randori
    let ubRounds = $derived(drawingData?.upper_bracket?.rounds || []);
    let lbRounds = $derived(drawingData?.lower_bracket?.rounds || []);
    let gfMatch = $derived(drawingData?.grand_final || null);
    let hasPrelim = $derived(drawingData?.has_preliminary || false);
</script>

{#snippet matchCard(m, bracket, roundIdx, matchIdx, nodeKey)}
    {@const a1 = m.athlete1}
    {@const a2 = m.athlete2}
    {@const hasBothAthletes = a1 && a2}
    {@const hasSomeAthlete = a1 || a2}
    {@const nodeResult = randoriResults[nodeKey]}
    {@const winnerNode = nodeResult?.winner}
    {@const isDone = !!winnerNode}
    {@const isGf = bracket === 'gf'}
    {@const isActive = nodeKey === activeNodeKey}

    <!-- Card styling overrides -->
    {@const borderClass = isActive
        ? 'border-blue-500 shadow-lg shadow-blue-500/30 scale-105 z-20 transition-all duration-300'
        : isDone
            ? (isGf ? 'border-amber-400 shadow-amber-500/20' : (bracket === 'ub' ? 'border-rose-300 shadow-rose-500/10' : 'border-indigo-300 shadow-indigo-500/10'))
            : (isGf ? 'border-amber-300 shadow-amber-500/10' : (bracket === 'ub' ? 'border-rose-200' : 'border-indigo-200'))}

    <div class="relative w-48 sm:w-56 md:w-64 lg:w-72 {isActive ? 'z-20' : ''}">
        <!-- Match label badge -->
        <div class="absolute -top-2.5 left-2 z-10 px-1.5 py-0.5 bg-white text-slate-500 text-[9px] sm:text-[10px] md:text-xs lg:text-sm font-black rounded-lg border border-slate-200 uppercase tracking-wide shadow-sm">
            {#if isGf}
                Grand Final
            {:else}
                {bracket.toUpperCase()} &bull; R{roundIdx + 1} M{matchIdx + 1}
            {/if}
        </div>

        <!-- Active badge / Selesai badge -->
        {#if isActive}
            <div class="absolute -top-2.5 right-2 z-20 px-2 py-0.5 bg-blue-500 text-white text-[9px] sm:text-[10px] md:text-xs lg:text-sm font-black rounded-lg uppercase shadow-lg shadow-blue-500/40 animate-pulse flex items-center gap-1">
                <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 bg-white rounded-full"></span> ACTIVE
            </div>
        {:else if isDone}
            <div class="absolute -top-2.5 right-2 z-10 px-1.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[9px] sm:text-[10px] md:text-xs lg:text-sm font-black rounded-lg uppercase shadow-sm">
                Selesai
            </div>
        {/if}

        <!-- Card Container -->
        <div class="bg-white rounded-2xl border-2 {borderClass} shadow-md overflow-hidden transition-all">
            {#if !hasSomeAthlete}
                <!-- Empty / TBD -->
                <div class="px-2 py-4 sm:px-3 sm:py-6 flex flex-col items-center justify-center gap-1">
                    <i class="fas fa-clock text-slate-300 text-2xl sm:text-3xl"></i>
                    <span class="text-[10px] sm:text-xs md:text-sm font-bold text-slate-400 uppercase tracking-wide mt-2">Menunggu...</span>
                </div>
            {:else}
                <!-- Athlete 1 (Merah/AKA) -->
                <div class="px-2.5 py-2.5 sm:px-3 sm:py-3 md:py-4 border-b border-slate-100 flex items-center gap-2 sm:gap-3 {isDone && winnerNode === 'athlete1' ? 'bg-rose-50' : ''}">
                    <div class="w-1.5 sm:w-2 h-8 sm:h-10 md:h-12 rounded-full shrink-0 {isDone && winnerNode === 'athlete1' ? 'bg-rose-500 shadow-sm shadow-rose-500/50' : 'bg-rose-200'}"></div>
                    <div class="flex-1 min-w-0">
                        {#if a1}
                            <div class="text-xs sm:text-sm md:text-base font-black uppercase tracking-tight truncate {isDone && winnerNode === 'athlete1' ? 'text-slate-800' : 'text-slate-600'}">
                                {a1.name}
                            </div>
                            {#if a1.contingent}
                                <div class="text-[10px] sm:text-xs md:text-sm font-bold text-slate-400 truncate mt-0.5">{a1.contingent}</div>
                            {/if}
                        {:else}
                            <div class="text-sm md:text-base text-slate-300 italic font-bold">TBD</div>
                        {/if}
                    </div>
                    {#if isDone && winnerNode === 'athlete1'}
                        <span class="text-[10px] sm:text-[11px] md:text-xs font-black bg-rose-100 text-rose-600 border border-rose-200 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-lg shrink-0 shadow-sm">W</span>
                    {/if}
                </div>

                <!-- VS divider -->
                <div class="py-0.5 sm:py-1 bg-slate-50 border-y border-slate-100 flex items-center justify-center text-[10px] sm:text-xs md:text-sm font-black text-slate-300 tracking-widest uppercase shadow-inner">vs</div>

                <!-- Athlete 2 (Putih/SHIRO) -->
                <div class="px-2.5 py-2.5 sm:px-3 sm:py-3 md:py-4 flex items-center gap-2 sm:gap-3 {isDone && winnerNode === 'athlete2' ? 'bg-indigo-50' : ''}">
                    <div class="w-1.5 sm:w-2 h-8 sm:h-10 md:h-12 rounded-full shrink-0 {isDone && winnerNode === 'athlete2' ? 'bg-indigo-500 shadow-sm shadow-indigo-500/50' : 'bg-indigo-200'}"></div>
                    <div class="flex-1 min-w-0">
                        {#if a2}
                            <div class="text-xs sm:text-sm md:text-base font-black uppercase tracking-tight truncate {isDone && winnerNode === 'athlete2' ? 'text-slate-800' : 'text-slate-600'}">
                                {a2.name}
                            </div>
                            {#if a2.contingent}
                                <div class="text-[10px] sm:text-xs md:text-sm font-bold text-slate-400 truncate mt-0.5">{a2.contingent}</div>
                            {/if}
                        {:else}
                            <div class="text-sm md:text-base text-slate-300 italic font-bold">TBD</div>
                        {/if}
                    </div>
                    {#if isDone && winnerNode === 'athlete2'}
                        <span class="text-[10px] sm:text-[11px] md:text-xs font-black bg-indigo-100 text-indigo-600 border border-indigo-200 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-lg shrink-0 shadow-sm">W</span>
                    {/if}
                </div>
            {/if}
        </div>
    </div>
{/snippet}

<div class="relative mx-auto flex h-screen min-h-screen w-full max-w-[1920px] flex-col overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.08),_transparent_32%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)] p-3 font-sans text-slate-800 sm:p-4 md:p-6 lg:p-8">

    <!-- HEADER -->
    <div class="relative z-10 mb-3 flex w-full flex-col gap-3 rounded-2xl border border-white/70 bg-white/90 p-3 shadow-[0_12px_40px_-20px_rgba(15,23,42,0.3)] backdrop-blur sm:mb-4 sm:p-4 md:mb-4 md:flex-row md:items-center md:justify-between md:gap-4 md:rounded-[1.5rem] lg:p-5">
        <div class="flex items-center gap-2.5 sm:gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 via-amber-500 to-orange-500 shadow-md shadow-amber-500/20 sm:h-12 sm:w-12 md:h-14 md:w-14 lg:h-16 lg:w-16">
                <i class="fas fa-trophy text-lg text-white drop-shadow-md sm:text-xl md:text-2xl lg:text-3xl"></i>
            </div>
            <div class="min-w-0">
                <h1 class="flex flex-wrap items-center gap-1.5 text-base font-black tracking-tight text-slate-900 sm:gap-2 sm:text-lg md:text-2xl lg:text-3xl">
                    MONITOR HASIL
                    {#if match && match.draft_type === 'randori'}
                        <span class="rounded border border-rose-200 bg-rose-50 px-1.5 py-0.5 text-[8px] uppercase tracking-[0.2em] text-rose-600 sm:text-[9px] md:rounded-lg md:px-2 md:py-0.5 md:text-xs lg:text-sm">RANDORI</span>
                    {:else if match && match.draft_type === 'embu'}
                        <span class="rounded border border-emerald-200 bg-emerald-50 px-1.5 py-0.5 text-[8px] uppercase tracking-[0.2em] text-emerald-600 sm:text-[9px] md:rounded-lg md:px-2 md:py-0.5 md:text-xs lg:text-sm">EMBU</span>
                    {/if}
                </h1>
                {#if match}
                    <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500 sm:text-xs md:text-sm lg:text-[15px]">
                        {match.merge_detail?.merge?.name || match.name}
                        {#if match.age_group}
                            <span class="text-slate-300 mx-1.5">•</span>
                            <span class="text-slate-500">{match.age_group.name}</span>
                        {/if}
                    </p>
                {/if}
            </div>
        </div>

        <div class="flex items-center gap-3 self-center md:self-auto">
            <button onclick={() => autoScrollEnabled = !autoScrollEnabled} 
                class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all border select-none cursor-pointer active:scale-95 {autoScrollEnabled ? 'bg-amber-500 text-white border-amber-600 shadow-md shadow-amber-500/20' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200'}">
                <i class="fas {autoScrollEnabled ? 'fa-scroll' : 'fa-hand'}"></i>
                Auto Scroll: {autoScrollEnabled ? 'ON' : 'OFF'}
            </button>

            <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 px-3 py-2 text-center sm:px-4 md:min-w-[180px] md:text-right">
                {#if court}
                    <h2 class="text-lg font-black tracking-tight text-amber-500 sm:text-xl md:text-2xl lg:text-3xl">{court.name}</h2>
                    <p class="mt-0.5 text-[8px] font-bold uppercase tracking-[0.2em] text-slate-400 sm:text-[9px] md:text-xs lg:text-xs">Update Otomatis</p>
                {:else}
                    <h2 class="text-md font-black text-amber-500 md:text-lg lg:text-xl">Live View</h2>
                {/if}
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="relative flex-1 overflow-hidden rounded-[1.75rem] border border-white/70 bg-white/90 shadow-[0_18px_60px_-28px_rgba(15,23,42,0.28)] backdrop-blur md:rounded-[2rem] w-full">
        {#if !loaded}
            <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                <p class="text-slate-500 font-bold text-sm md:text-lg lg:text-xl animate-pulse">Memuat data...</p>
            </div>
        {:else if !match}
            <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                <div class="w-24 h-24 md:w-32 md:h-32 lg:w-48 lg:h-48 bg-slate-50 rounded-full flex items-center justify-center shadow-inner mb-6 border border-slate-200">
                    <i class="fas fa-mug-hot text-4xl md:text-5xl lg:text-7xl text-slate-300"></i>
                </div>
                <h2 class="text-xl md:text-3xl lg:text-4xl font-black text-slate-800 uppercase tracking-widest">BELUM ADA PERTANDINGAN AKTIF</h2>
                <p class="text-slate-500 mt-2 md:mt-4 font-bold text-sm md:text-lg lg:text-xl">Silakan tunggu panggilan selanjutnya.</p>
            </div>
        {:else if match.draft_type === 'embu'}
            <!-- EMBU TABLE -->
            <div 
                bind:this={embuScrollEl}
                onwheel={handleUserInteraction}
                onpointerdown={handleUserInteraction}
                role="region"
                aria-label="Embu Scrollable Container"
                class="h-full w-full overflow-auto custom-scrollbar p-3 sm:p-4 md:p-6 lg:p-8"
            >
                <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-amber-500">Kategori</p>
                        <p class="mt-1 text-sm font-black uppercase tracking-[0.12em] text-slate-800 sm:text-base">{match.name}</p>
                    </div>
                    <div class="rounded-2xl border border-sky-100 bg-sky-50 px-4 py-3">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-sky-500">Kelompok</p>
                        <p class="mt-1 text-sm font-black uppercase tracking-[0.12em] text-slate-800 sm:text-base">{match.age_group?.name || '-'}</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-emerald-500">Peserta Tampil</p>
                        <p class="mt-1 text-sm font-black uppercase tracking-[0.12em] text-slate-800 sm:text-base">{embuRanking.length}</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_10px_35px_-24px_rgba(15,23,42,0.28)]">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse text-left">
                            <thead class="bg-slate-100 text-slate-800">
                                <tr class="text-slate-600">
                                    <th class="whitespace-nowrap border-b border-slate-200 px-3 py-3 text-center text-[10px] font-black uppercase tracking-[0.22em] sm:px-4 md:py-4 md:text-[13px]">Peringkat</th>
                                    <th class="whitespace-nowrap border-b border-slate-200 px-3 py-3 text-[10px] font-black uppercase tracking-[0.22em] sm:px-4 md:py-4 md:text-[13px]">Kontingen / Atlet</th>
                                    <th class="whitespace-nowrap border-b border-slate-200 px-3 py-3 text-center text-[10px] font-black uppercase tracking-[0.22em] sm:px-4 md:py-4 md:text-[13px]">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                {#each embuRanking as row, index}
                                    {@const rank = index + 1}
                                    {@const isTop = rank <= 4}
                                    {@const hasScore = checkHasScore(row)}

                                    <tr class="transition-colors hover:bg-amber-50/40 {index % 2 === 1 ? 'bg-slate-50/70' : 'bg-white'}">
                                        <!-- Rank -->
                                        <td class="border-r border-slate-100 px-3 py-3 text-center align-middle sm:px-4 md:px-5 md:py-5">
                                            {#if hasScore}
                                                <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-lg font-black sm:h-12 sm:w-12 md:h-16 md:w-16 md:rounded-2xl md:text-3xl lg:h-20 lg:w-20 lg:text-4xl {rank === 1 ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/40' : (rank === 2 ? 'bg-slate-300 text-slate-700' : (rank === 3 ? 'bg-amber-700 text-white' : 'border border-slate-200 bg-slate-100 text-slate-500'))}">
                                                    {rank}
                                                </div>
                                            {:else}
                                                <span class="text-slate-400 font-bold">-</span>
                                            {/if}
                                        </td>

                                        <!-- Contingent / Athletes -->
                                        <td class="border-r border-slate-100 px-3 py-3 align-middle sm:px-4 md:px-5 md:py-5">
                                            <div class="text-sm font-black uppercase tracking-[0.08em] text-slate-800 sm:text-lg md:text-2xl lg:text-3xl">
                                                {row.contingent?.name || 'Tanpa Kontingen'}
                                            </div>
                                            {#if row.athletes && row.athletes.length > 0}
                                                <div class="mt-1 text-xs font-medium text-slate-500 sm:text-sm md:mt-2 md:text-base lg:text-lg">
                                                    {row.athletes.map(a => a.name).join(' & ')}
                                                </div>
                                            {/if}
                                            {#if row.match_number_id != match.id}
                                                <div class="mt-1">
                                                    <span class="rounded bg-rose-50 px-2 py-0.5 text-[9px] font-black uppercase tracking-widest text-rose-500 border border-rose-100">
                                                        {row.match_name}
                                                    </span>
                                                </div>
                                            {/if}
                                        </td>

                                        <!-- Score -->
                                        <td class="px-3 py-3 text-center align-middle sm:px-4 md:px-5 md:py-5">
                                            {#if hasScore}
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <div class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 md:rounded-2xl md:px-4 md:py-2">
                                                        <span class="text-lg font-black sm:text-2xl md:text-4xl lg:text-5xl {isTop ? 'text-emerald-600' : 'text-slate-500'}">
                                                            {Number(row.accumulated_score || row.effective_score?.effective_score || 0).toFixed(1)}
                                                        </span>
                                                    </div>
                                                    {#if row.penyisihan_score}
                                                        <div class="text-[10px] font-black tracking-[0.18em] text-slate-500 md:text-xs">
                                                            PENYISIHAN: {Number(row.penyisihan_score.nilai_akhir).toFixed(1)} &nbsp;|&nbsp; FINAL: {row.effective_score ? Number(row.effective_score.nilai_akhir).toFixed(1) : '0.0'}
                                                        </div>
                                                    {/if}
                                                </div>
                                            {:else}
                                                <span class="py-2 text-[10px] font-black uppercase tracking-[0.22em] text-slate-400 sm:text-xs md:text-[15px] lg:text-lg">Belum Tampil</span>
                                            {/if}
                                        </td>
                                    </tr>
                                {/each}
                                {#if embuRanking.length === 0}
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-base font-bold text-slate-500 md:text-lg">Belum ada data peserta.</td>
                                    </tr>
                                {/if}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        {:else if match.draft_type === 'randori'}
            <!-- RANDORI BRACKET -->
            <div
                bind:this={scrollEl}
                onmousedown={(e) => { startDrag(e); handleUserInteraction(); }}
                onmousemove={drag}
                onwheel={handleUserInteraction}
                role="region"
                aria-label="Tournament Bracket Grid"
                class="relative h-full w-full cursor-grab overflow-auto custom-scrollbar bg-[linear-gradient(180deg,_rgba(248,250,252,0.96)_0%,_rgba(241,245,249,0.92)_100%)] p-3 select-none active:cursor-grabbing sm:p-4 md:p-6 lg:p-8"
            >
                <div class="inline-flex min-w-max flex-col gap-10 pb-24 sm:gap-12 md:gap-16 md:pb-32">
                    <!-- UPPER BRACKET -->
                    <div>
                        <div class="sticky left-0 bg-slate-50/90 backdrop-blur-sm z-20 pb-4 mb-2 flex items-center justify-between border-b border-rose-200">
                            <h3 class="text-base md:text-xl lg:text-2xl font-black text-rose-600 uppercase tracking-widest flex items-center gap-3">
                                <i class="fas fa-sitemap"></i> Upper Bracket
                            </h3>
                        </div>

                        <div class="flex items-start gap-4 sm:gap-6 md:gap-8 lg:gap-12 relative z-10">
                            {#each ubRounds as roundMatches, roundIdx}
                                <div class="flex flex-col gap-4 sm:gap-6 justify-center min-w-[192px] sm:min-w-[224px] md:min-w-[256px] lg:min-w-[288px]" style="min-height: 100%;">
                                    <div class="text-center mb-4 sticky top-0 bg-slate-50/90 py-1.5 rounded font-black text-xs sm:text-sm md:text-[15px] lg:text-base text-slate-500 tracking-widest uppercase">
                                        {#if roundIdx === ubRounds.length - 1}
                                            Final UB
                                        {:else}
                                            UB Putaran {roundIdx + 1}
                                        {/if}
                                    </div>

                                    <div class="flex flex-col justify-around flex-1" style="row-gap: {Math.pow(2, roundIdx) * 1.5}rem;">
                                        {#each roundMatches as m, matchIdx}
                                            {@const nodeKey = `ub_${roundIdx}_${matchIdx}`}
                                            {@render matchCard(m, 'ub', roundIdx, matchIdx, nodeKey)}
                                        {/each}
                                    </div>
                                </div>
                            {/each}

                            <!-- GRAND FINAL COLUMN -->
                            {#if gfMatch}
                                <div class="flex flex-col gap-4 sm:gap-6 justify-center min-w-[192px] sm:min-w-[224px] md:min-w-[256px] lg:min-w-[288px] ml-4 sm:ml-6 md:ml-8 lg:ml-12">
                                    <div class="text-center mb-4 sticky top-0 bg-slate-50/90 py-1.5 rounded font-black text-xs sm:text-sm md:text-[15px] lg:text-base text-amber-600 tracking-widest uppercase">
                                        Grand Final
                                    </div>
                                    <div class="relative group mt-auto mb-auto bg-amber-50 rounded-2xl border-2 border-amber-200 shadow-md shadow-amber-500/10 scale-105 md:scale-110 origin-left">
                                        {@render matchCard(gfMatch, 'gf', 0, 0, 'gf_0_0')}
                                    </div>
                                </div>
                            {/if}
                        </div>
                    </div>

                    <!-- LOWER BRACKET -->
                    {#if lbRounds.length > 0}
                        <div class="mt-8">
                            <div class="sticky left-0 bg-slate-50/90 backdrop-blur-sm z-20 pb-4 mb-2 flex items-center justify-between border-b border-indigo-200">
                                <h3 class="text-base md:text-xl lg:text-2xl font-black text-indigo-600 uppercase tracking-widest flex items-center gap-3">
                                    <i class="fas fa-level-down-alt"></i> Lower Bracket
                                </h3>
                            </div>

                            <div class="flex items-start gap-4 sm:gap-6 md:gap-8 lg:gap-12 relative z-10">
                                {#each lbRounds as roundMatches, roundIdx}
                                    <div class="flex flex-col gap-4 sm:gap-6 justify-center min-w-[192px] sm:min-w-[224px] md:min-w-[256px] lg:min-w-[288px]" style="min-height: 100%;">
                                        <div class="text-center mb-4 sticky top-0 bg-slate-50/90 py-1.5 rounded font-black text-xs sm:text-sm md:text-[15px] lg:text-base text-slate-500 tracking-widest uppercase">
                                            {#if roundIdx === lbRounds.length - 1}
                                                Final LB
                                            {:else}
                                                LB Putaran {roundIdx + 1}
                                            {/if}
                                        </div>

                                        <div class="flex flex-col justify-around flex-1" style="row-gap: {Math.pow(2, Math.floor(roundIdx / 2)) * 1.5}rem;">
                                            {#each roundMatches as m, matchIdx}
                                                {@const nodeKey = `lb_${roundIdx}_${matchIdx}`}
                                                {@render matchCard(m, 'lb', roundIdx, matchIdx, nodeKey)}
                                            {/each}
                                        </div>
                                    </div>
                                {/each}
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
        {/if}
    </div>
</div>
