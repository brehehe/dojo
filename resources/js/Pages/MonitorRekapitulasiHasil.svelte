<script>
    import { onMount, onDestroy } from 'svelte';
    import { createAdaptivePolling } from '../lib/adaptivePolling';
    import { conditionalJsonFetch } from '../lib/conditionalFetch';

    // Props passed from Inertia
    let { courtId } = $props();

    // Svelte 5 Reactive States
    let court = $state(null);
    let match = $state(null);
    let scores = $state([]);
    let currentRound = $state(null);
    let poolName = $state(null);
    let loaded = $state(false);

    let scrollInterval;

    let destroyed = false;
    let syncInFlight = false;
    let syncQueued = false;
    let queuedTimeout = null;
    let polling = null;
    const pollDelay = 3000;

    function scheduleQueuedSync() {
        if (destroyed) return;
        if (queuedTimeout) clearTimeout(queuedTimeout);
        queuedTimeout = setTimeout(() => {
            queuedTimeout = null;
            if (!destroyed) sync();
        }, pollDelay);
    }

    // Scrolling container refs
    let scrollContainer = $state(null);
    let scrollContent = $state(null);

    let scrollSpeed = 0.4;
    let pos = 0;
    let pauseTicks = 0;

    async function sync() {
        if (destroyed) return;
        if (syncInFlight) {
            syncQueued = true;
            return;
        }

        syncInFlight = true;
        try {
            let { data, notModified } = await conditionalJsonFetch(`/api/svelte-monitor/rekapitulasi-hasil/court/${courtId}/state`);
            if (destroyed) return;
            if (notModified) return;
            if (destroyed) return;
            if (!data) return;

            court = data.court;
            match = data.match;
            scores = data.scores || [];
            currentRound = data.currentRound;
            poolName = data.poolName;
            loaded = true;
        } catch (e) {
            console.error('Error syncing rekapitulasi hasil state:', e);
        } finally {
            syncInFlight = false;
            if (syncQueued && !destroyed) {
                syncQueued = false;
                scheduleQueuedSync();
            } else if (destroyed) {
                syncQueued = false;
            }
        }
    }

    function getJudgeStats(score) {
        let rawVals = [
            { key: 1, val: parseFloat(score.judge_1 || 0) },
            { key: 2, val: parseFloat(score.judge_2 || 0) },
            { key: 3, val: parseFloat(score.judge_3 || 0) },
            { key: 4, val: parseFloat(score.judge_4 || 0) },
            { key: 5, val: parseFloat(score.judge_5 || 0) }
        ];

        let scoredCount = rawVals.filter(v => v.val > 0);
        let minKey = null;
        let maxKey = null;

        if (scoredCount.length >= 2) {
            // Sort ascending by value
            scoredCount.sort((a, b) => a.val - b.val);
            minKey = scoredCount[0].key;
            maxKey = scoredCount[scoredCount.length - 1].key;
        }

        return { rawVals, minKey, maxKey };
    }

    function startAutoScroll() {
        scrollInterval = setInterval(() => {
            if (!scrollContainer || !scrollContent) return;

            let containerHeight = scrollContainer.offsetHeight;
            let contentHeight = scrollContent.offsetHeight;

            if (contentHeight <= containerHeight) {
                scrollContent.style.transform = 'translateY(0px)';
                return;
            }

            if (pauseTicks > 0) {
                pauseTicks--;
                return;
            }

            if (pos === 0) {
                pauseTicks = 100; // Pause for ~3 seconds at the top
                pos = 0.001;
                return;
            }

            pos += scrollSpeed;

            if (pos >= contentHeight - containerHeight) {
                pos = 0;
                pauseTicks = 100; // Pause for ~3 seconds after resetting to top
            }

            scrollContent.style.transform = `translateY(-${Math.max(0, pos)}px)`;
        }, 30);
    }

    onMount(() => {
        destroyed = false;
        sync();
        if (window.Echo) {
            window.Echo.channel(`court.${courtId}`).listen('CourtUpdated', (e) => {
                if (destroyed) return;
                polling?.markRealtimeHealthy();
                sync();
            });
        }
        polling = createAdaptivePolling({
            fetchNow: sync,
            normalInterval: pollDelay,
            healthyInterval: 20000,
            staleAfter: 20000,
            immediate: false,
        });
        polling.start();
        startAutoScroll();
    });

    onDestroy(() => {
        destroyed = true;
        syncQueued = false;
        if (window.Echo) {
            window.Echo.leave(`court.${courtId}`);
        }
        polling?.stop();
        clearInterval(scrollInterval);
        if (queuedSyncTimeout) clearTimeout(queuedSyncTimeout);
    });

    const currentYear = new Date().getFullYear();
</script>

<div class="relative mx-auto flex h-screen min-h-screen w-full max-w-[1920px] flex-col overflow-hidden bg-[#f8fafc] font-sans text-slate-800">
    
    <!-- BACKGROUND DECORATION -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-blue-500/5 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-purple-500/5 blur-[120px] rounded-full"></div>
    </div>

    <!-- HEADER -->
    <div class="relative z-10 flex w-full items-center justify-between border-b border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 shadow-lg shadow-blue-600/20">
                <i class="fas fa-list-ol text-3xl text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">
                    REKAPITULASI {match ? (match.merge_detail?.merge?.name || 'HASIL EMBU') : 'HASIL EMBU'}
                </h1>
                {#if match}
                    <p class="mt-1 text-lg font-bold text-slate-500 uppercase tracking-widest">
                        {match.merge_detail?.merge?.name || match.name} 
                        <span class="mx-3 text-slate-300">|</span> 
                        {match.age_group?.name || 'SEMUA KATEGORI'}
                        {#if currentRound}
                            <span class="mx-3 text-slate-300">|</span> 
                            <span class="text-blue-600">{currentRound}</span>
                        {/if}
                        {#if poolName}
                            <span class="mx-3 text-slate-300">|</span> 
                            <span class="text-indigo-600">{poolName}</span>
                        {/if}
                    </p>
                {:else}
                    <p class="mt-1 text-lg font-bold text-slate-500 uppercase tracking-widest">HASIL PERTANDINGAN TERBARU</p>
                {/if}
            </div>
        </div>

        <div class="text-right">
            {#if court}
                <div class="text-4xl font-black text-blue-600">{court.name}</div>
                <p class="text-sm font-bold uppercase tracking-widest text-slate-400">Update Otomatis</p>
            {:else}
                <div class="text-3xl font-black text-blue-600 italic">LIVE MONITOR</div>
            {/if}
        </div>
    </div>

    <!-- TABLE HEADER (STAY TOP) -->
    <div class="relative z-20 bg-slate-100 px-8 py-4 border-b border-slate-200">
        <div class="grid grid-cols-12 gap-4 text-xs font-black uppercase tracking-[0.2em] text-slate-500">
            <div class="col-span-1 text-center">Rank</div>
            <div class="col-span-4">Peserta / Kontingen</div>
            <div class="col-span-1 text-center">J1</div>
            <div class="col-span-1 text-center">J2</div>
            <div class="col-span-1 text-center">J3</div>
            <div class="col-span-1 text-center">J4</div>
            <div class="col-span-1 text-center">J5</div>
            <div class="col-span-1 text-center text-red-600">Denda</div>
            <div class="col-span-1 text-center text-blue-600">Total</div>
        </div>
    </div>

    <!-- SCROLLING CONTENT -->
    <div bind:this={scrollContainer} class="relative z-10 flex-1 overflow-hidden" id="scroll-container">
        <div bind:this={scrollContent} class="px-8 transition-transform duration-300 ease-linear">
            {#if !loaded}
                <div class="flex flex-col items-center justify-center py-40 opacity-40">
                    <p class="text-xl font-bold uppercase tracking-widest animate-pulse">Memuat data rekapitulasi...</p>
                </div>
            {:else}
                {#each scores as score, index}
                    {@const rank = index + 1}
                    {@const stats = getJudgeStats(score)}
                    {@const isTopRank = rank <= 3 && score.effective_score > 0}

                    <div class="grid grid-cols-12 gap-4 py-6 border-b border-slate-200 items-center {isTopRank ? 'bg-blue-50/50' : ''}">
                        <!-- Rank -->
                        <div class="col-span-1 flex justify-center">
                            {#if score.effective_score > 0}
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl mx-auto {rank === 1 ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/40' : (rank === 2 ? 'bg-slate-300 text-slate-700' : (rank === 3 ? 'bg-amber-700 text-white' : 'border-2 border-slate-200 bg-white text-slate-500'))} text-xl font-black">
                                    {rank}
                                </div>
                            {:else}
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl text-xl font-black bg-white text-slate-300 border border-slate-200">
                                    -
                                </div>
                            {/if}
                        </div>

                        <!-- Name / Contingent -->
                        <div class="col-span-4">
                            {#if score.athletes && score.athletes.length > 0}
                                <div class="text-xl font-black text-slate-900 uppercase truncate">
                                    {score.athletes.map(a => a.name).join(' & ')}
                                </div>
                            {/if}
                            <div class="text-sm font-bold text-blue-600 uppercase tracking-widest mt-1">
                                {score.registration?.contingent?.name || '-'}
                                {#if score.match_number_id != match.id}
                                    <span class="ml-2 rounded bg-rose-50 px-2 py-0.5 text-[10px] font-black tracking-widest text-rose-500 border border-rose-100">
                                        {score.match_name}
                                    </span>
                                {/if}
                            </div>
                        </div>

                        <!-- Judges (1-5) -->
                        {#each [1, 2, 3, 4, 5] as j}
                            {@const val = stats.rawVals[j-1].val}
                            {@const isOut = (j === stats.minKey || j === stats.maxKey)}
                            <div class="col-span-1 text-center">
                                <span class="text-xl font-bold {isOut ? 'line-through text-slate-300 decoration-2' : 'text-slate-800'}">
                                    {val > 0 ? val.toFixed(1) : '-'}
                                </span>
                            </div>
                        {/each}

                        <!-- Denda -->
                        <div class="col-span-1 text-center text-xl font-bold text-red-600">
                            {score.denda > 0 ? '-' + parseFloat(score.denda).toFixed(1) : '0'}
                        </div>

                        <!-- Total -->
                        <div class="col-span-1 text-center">
                            <div class="text-2xl font-black text-blue-700">
                                {parseFloat(score.effective_score || 0).toFixed(1)}
                            </div>
                        </div>
                    </div>
                {:else}
                    <div class="flex flex-col items-center justify-center py-40 opacity-10">
                        <i class="fas fa-database text-8xl mb-6"></i>
                        <p class="text-2xl font-black uppercase tracking-widest">Belum Ada Data</p>
                    </div>
                {/each}
            {/if}
            
            <!-- Padding bottom -->
            <div class="h-10"></div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="relative z-20 flex w-full items-center justify-between border-t border-slate-200 bg-white p-4 shadow-inner">
        <div class="flex gap-8 text-xs font-black uppercase tracking-widest text-slate-400">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-yellow-400"></span> Juara 1
            </div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-slate-200"></span> Juara 2
            </div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-orange-100"></span> Juara 3
            </div>
        </div>
        <div class="text-xs font-bold text-slate-400">
            DOJO Digital Scoring System &copy; {currentYear}
        </div>
    </div>
</div>
