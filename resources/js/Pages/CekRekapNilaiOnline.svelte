<script>
    import { onMount, onDestroy } from 'svelte';
    import { createAdaptivePolling } from '../lib/adaptivePolling';
    import { conditionalJsonFetch } from '../lib/conditionalFetch';

    // Svelte 5 Reactive States
    let courts = $state([]);
    let matchNumbers = $state([]);
    let drawings = $state([]);
    let embuScores = $state([]);
    let randoriResults = $state([]);
    let loaded = $state(false);

    // Filters State
    let search = $state('');
    let selectedCourt = $state('');
    let selectedMatch = $state('');
    let selectedRound = $state('');
    let selectedPool = $state('');

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

    async function sync() {
        if (destroyed) return;
        if (syncInFlight) {
            syncQueued = true;
            return;
        }

        syncInFlight = true;
        try {
            let { data, notModified } = await conditionalJsonFetch('/api/cek-rekap-nilai-online/state');
            if (destroyed) return;
            if (notModified) return;
            if (!data) return;

            courts = data.courts || [];
            matchNumbers = data.matchNumbers || [];
            drawings = data.drawings || [];
            embuScores = data.embuScores || [];
            randoriResults = data.randoriResults || [];
            loaded = true;
        } catch (e) {
            console.error('Error syncing online recap state:', e);
        } finally {
            syncInFlight = false;
            if (syncQueued && !destroyed) {
                syncQueued = false;
                scheduleQueuedSync();
            }
        }
    }

    onMount(() => {
        destroyed = false;
        sync();

        // Listen on Reverb for live updates
        if (window.Echo) {
            window.Echo.channel('tournament-updates')
                .listen('.CourtUpdated', () => {
                    if (destroyed) return;
                    sync();
                })
                .listen('.MatchUpdated', () => {
                    if (destroyed) return;
                    sync();
                });
        }

        polling = createAdaptivePolling({
            fetchNow: sync,
            normalInterval: pollDelay,
            healthyInterval: 15000,
            staleAfter: 15000,
            immediate: false,
        });
        polling.start();
    });

    onDestroy(() => {
        destroyed = true;
        syncQueued = false;
        if (window.Echo) {
            window.Echo.leave('tournament-updates');
        }
        polling?.stop();
        if (queuedTimeout) clearTimeout(queuedTimeout);
    });

    // Helper: calculate judge high/low outliers to cross out
    function getJudgeStats(score) {
        if (!score) return null;
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

        if (scoredCount.length === 5) {
            // Sort ascending by value to find min and max index
            let sorted = [...scoredCount].sort((a, b) => a.val - b.val);
            minKey = sorted[0].key;
            maxKey = sorted[sorted.length - 1].key;
        }

        return { rawVals, minKey, maxKey };
    }

    // Helper: resolve scores for a drawing
    function getDrawingScores(drawing) {
        return embuScores.filter(s => 
            s.match_number_id === drawing.match_number_id && 
            s.registration_id === drawing.registration_id &&
            (s.drawing_id === drawing.id || (!s.drawing_id && !drawing.sequence_number))
        );
    }

    // Helper: calculate rankings within the same match, round, and pool
    function getRank(drawing, score) {
        if (!score || score.nilai_akhir <= 0) return '-';
        
        // Find all scores in the same match, round, and pool
        let siblings = drawings
            .filter(d => 
                d.match_number_id === drawing.match_number_id && 
                d.round === drawing.round && 
                d.pool_id === drawing.pool_id
            )
            .flatMap(d => getDrawingScores(d))
            .filter(s => s.nilai_akhir > 0)
            // Deduplicate scores in case of duplicates
            .filter((value, index, self) => 
                self.findIndex(t => t.id === value.id) === index
            )
            .sort((a, b) => b.nilai_akhir - a.nilai_akhir);

        let rank = siblings.findIndex(s => s.id === score.id) + 1;
        return rank > 0 ? rank : '-';
    }

    // Filter drawings reactively
    let filteredDrawings = $derived(
        drawings.filter(d => {
            // Search text
            if (search.trim() !== '') {
                let sTerm = search.toLowerCase();
                let athleteNames = d.athletes?.map(a => a.name.toLowerCase()).join(' ') || '';
                let contingentName = d.registration?.contingent?.name?.toLowerCase() || '';
                if (!athleteNames.includes(sTerm) && !contingentName.includes(sTerm)) {
                    return false;
                }
            }

            // Court
            if (selectedCourt !== '' && String(d.court_id) !== String(selectedCourt)) {
                return false;
            }

            // Match Number
            if (selectedMatch !== '' && String(d.match_number_id) !== String(selectedMatch)) {
                return false;
            }

            // Round
            if (selectedRound !== '' && d.round !== selectedRound) {
                return false;
            }

            // Pool
            if (selectedPool !== '' && String(d.pool_id) !== String(selectedPool)) {
                return false;
            }

            return true;
        })
    );

    // Get unique pool list from loaded drawings for filter dropdown
    let pools = $derived(
        [...new Set(drawings.map(d => d.pool).filter(Boolean).map(p => JSON.stringify({id: p.id, name: p.name})))]
            .map(p => JSON.parse(p))
            .sort((a, b) => a.name.localeCompare(b.name))
    );

    const currentYear = new Date().getFullYear();
</script>

<div class="relative mx-auto flex min-h-screen w-full flex-col bg-[#f8fafc] font-sans text-slate-800">
    <!-- BACKGROUND DECORATION -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-blue-500/5 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-indigo-500/5 blur-[120px] rounded-full"></div>
    </div>

    <!-- HEADER -->
    <header class="relative z-10 border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 shadow-lg shadow-blue-600/20 text-white">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">
                        REKAP NILAI ONLINE
                    </h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">
                        Live Monitoring Perolehan Nilai & Hasil Pertandingan
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700 border border-emerald-200 uppercase tracking-widest animate-pulse">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Live Sync
                </span>
                <button onclick={sync} aria-label="Refresh Data" title="Refresh Data" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 shadow-sm transition-all">
                    <i class="fa-solid fa-rotate"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- FILTER BAR -->
    <section class="relative z-10 border-b border-slate-200 bg-slate-50/50">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                <!-- Search -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input 
                        type="text" 
                        bind:value={search} 
                        placeholder="Cari Kontingen / Atlet..." 
                        class="w-full rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-4 text-sm font-semibold text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:outline-none transition-all"
                    />
                </div>

                <!-- Court Select -->
                <div>
                    <select 
                        bind:value={selectedCourt} 
                        class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm font-bold text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none transition-all"
                    >
                        <option value="">Semua Lapangan</option>
                        {#each courts as c}
                            <option value={c.id}>{c.name}</option>
                        {/each}
                    </select>
                </div>

                <!-- Match Select -->
                <div>
                    <select 
                        bind:value={selectedMatch} 
                        class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm font-bold text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none transition-all truncate"
                    >
                        <option value="">Semua Kategori</option>
                        {#each matchNumbers as mn}
                            <option value={mn.id}>{mn.name} ({mn.gender === 'male' ? 'PA' : 'PI'})</option>
                        {/each}
                    </select>
                </div>

                <!-- Round Select -->
                <div>
                    <select 
                        bind:value={selectedRound} 
                        class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm font-bold text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none transition-all"
                    >
                        <option value="">Semua Babak</option>
                        <option value="Penyisihan">Penyisihan</option>
                        <option value="Final">Final</option>
                    </select>
                </div>

                <!-- Pool Select -->
                <div>
                    <select 
                        bind:value={selectedPool} 
                        class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm font-bold text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none transition-all"
                    >
                        <option value="">Semua Pool</option>
                        {#each pools as p}
                            <option value={p.id}>Pool {p.name}</option>
                        {/each}
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT BODY -->
    <main class="relative z-10 flex-1 mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {#if !loaded}
            <div class="flex flex-col items-center justify-center py-40 text-slate-400">
                <i class="fa-solid fa-spinner animate-spin text-5xl mb-4 text-blue-600"></i>
                <p class="text-lg font-bold uppercase tracking-widest animate-pulse">Memuat data rekapitulasi...</p>
            </div>
        {:else if filteredDrawings.length === 0}
            <div class="flex flex-col items-center justify-center py-32 text-slate-400 bg-white border border-slate-200 rounded-3xl shadow-sm">
                <i class="fa-solid fa-database text-6xl mb-4 text-slate-300"></i>
                <p class="text-xl font-black uppercase tracking-wider text-slate-600">Tidak Ada Data</p>
                <p class="text-sm font-semibold text-slate-400 mt-1">Coba sesuaikan filter pencarian Anda.</p>
            </div>
        {:else}
            <div class="flex flex-col gap-6">
                <!-- Group drawings by Match Category -->
                {#each [...new Set(filteredDrawings.map(d => d.match_number_id))] as matchId}
                    {@const matchDrawings = filteredDrawings.filter(d => d.match_number_id === matchId)}
                    {@const match = matchNumbers.find(m => m.id === matchId)}

                    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden transition-all hover:shadow-md">
                        <!-- Group Header -->
                        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h3 class="text-lg font-black text-slate-900 uppercase">
                                    {match?.name || 'Kategori Tidak Diketahui'}
                                </h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                    Kelompok Umur: {match?.age_group?.name || '-'} • Gender: {match?.gender === 'male' ? 'LAKI-LAKI' : 'PEREMPUAN'}
                                </p>
                            </div>
                            <span class="inline-flex self-start rounded-full bg-blue-50 px-2.5 py-1 text-xs font-black tracking-widest text-blue-700 border border-blue-100 uppercase">
                                {match?.draft_type === 'embu' ? 'EMBU (KATA)' : 'RANDORI (SPARRING)'}
                            </span>
                        </div>

                        <!-- Table / Cards List -->
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-left">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                        <th class="px-6 py-3.5 text-center w-16">Rank</th>
                                        <th class="px-4 py-3.5 text-center w-20">Lap.</th>
                                        <th class="px-4 py-3.5 text-center w-28">Babak</th>
                                        <th class="px-4 py-3.5 text-center w-20">Pool</th>
                                        <th class="px-6 py-3.5">Peserta / Kontingen</th>
                                        <th class="px-4 py-3.5 text-center">Juri 1</th>
                                        <th class="px-4 py-3.5 text-center">Juri 2</th>
                                        <th class="px-4 py-3.5 text-center">Juri 3</th>
                                        <th class="px-4 py-3.5 text-center">Juri 4</th>
                                        <th class="px-4 py-3.5 text-center">Juri 5</th>
                                        <th class="px-4 py-3.5 text-center text-rose-600">Denda</th>
                                        <th class="px-6 py-3.5 text-center text-blue-600 w-24">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    {#each matchDrawings as drawing}
                                        {@const scores = getDrawingScores(drawing)}
                                        
                                        {#if scores.length === 0}
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-5 text-center text-slate-300 font-black">-</td>
                                                <td class="px-4 py-5 text-center">
                                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-black text-slate-600 border border-slate-200">
                                                        {drawing.court?.name || '—'}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-5 text-center">
                                                    <span class="inline-flex items-center rounded-lg bg-indigo-50/50 px-2.5 py-0.5 text-xs font-bold text-indigo-600 border border-indigo-100 uppercase tracking-widest">
                                                        {drawing.round}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-5 text-center text-sm font-bold text-slate-500">
                                                    {drawing.pool?.name || '—'}
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="text-sm font-black text-slate-900 uppercase">
                                                        {drawing.athletes?.map(a => a.name).join(' & ') || '—'}
                                                    </div>
                                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                                        {drawing.registration?.contingent?.name || '—'}
                                                    </div>
                                                </td>
                                                <td colspan="7" class="px-6 py-5 text-center text-slate-400 font-semibold italic text-xs">
                                                    Belum Tampil / Nilai Belum Masuk
                                                </td>
                                            </tr>
                                        {:else}
                                            {#each scores as s, sIdx}
                                                {@const stats = getJudgeStats(s)}
                                                {@const rank = getRank(drawing, s)}
                                                {@const isTopRank = typeof rank === 'number' && rank <= 3}
                                                {@const isDuplicate = scores.length > 1}

                                                <tr class="hover:bg-slate-50/50 transition-colors {isTopRank ? 'bg-blue-50/20' : ''} {isDuplicate ? 'bg-rose-50/40' : ''}">
                                                    <!-- Rank -->
                                                    <td class="px-6 py-5 text-center">
                                                        {#if typeof rank === 'number'}
                                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-sm font-black 
                                                                {rank === 1 ? 'bg-amber-400 text-white shadow-md shadow-amber-400/20' : ''}
                                                                {rank === 2 ? 'bg-slate-300 text-slate-700' : ''}
                                                                {rank === 3 ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : ''}
                                                                {rank > 3 ? 'bg-slate-100 text-slate-500 border border-slate-200' : ''}"
                                                            >
                                                                {rank}
                                                            </span>
                                                        {:else}
                                                            <span class="text-slate-300 font-black">-</span>
                                                        {/if}
                                                    </td>

                                                    <!-- Court -->
                                                    <td class="px-4 py-5 text-center">
                                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-black text-slate-600 border border-slate-200">
                                                            {drawing.court?.name || '—'}
                                                        </span>
                                                    </td>

                                                    <!-- Round -->
                                                    <td class="px-4 py-5 text-center">
                                                        <div class="flex flex-col items-center gap-1">
                                                            <span class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-600 border border-indigo-100 uppercase tracking-widest">
                                                                {drawing.round}
                                                            </span>
                                                            {#if isDuplicate}
                                                                <span class="inline-flex rounded bg-rose-100 px-1.5 py-0.5 text-[8px] font-black tracking-widest text-rose-600 uppercase">
                                                                    Duplikat #{sIdx + 1}
                                                                </span>
                                                            {/if}
                                                        </div>
                                                    </td>

                                                    <!-- Pool -->
                                                    <td class="px-4 py-5 text-center text-sm font-bold text-slate-500">
                                                        {drawing.pool?.name || '—'}
                                                    </td>

                                                    <!-- Competitors -->
                                                    <td class="px-6 py-5">
                                                        <div class="text-sm font-black text-slate-900 uppercase">
                                                            {drawing.athletes?.map(a => a.name).join(' & ') || '—'}
                                                        </div>
                                                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                                            {drawing.registration?.contingent?.name || '—'}
                                                        </div>
                                                    </td>

                                                    <!-- Judges scores -->
                                                    {#each [1, 2, 3, 4, 5] as j}
                                                        {@const val = stats ? stats.rawVals[j-1].val : parseFloat(s[`judge_${j}`] || 0)}
                                                        {@const isOut = stats && (j === stats.minKey || j === stats.maxKey)}
                                                        <td class="px-4 py-5 text-center">
                                                            <span class="text-sm font-bold {isOut ? 'line-through text-slate-300 decoration-2' : 'text-slate-800'}">
                                                                {val > 0 ? val.toFixed(1) : '-'}
                                                            </span>
                                                        </td>
                                                    {/each}

                                                    <!-- Denda -->
                                                    <td class="px-4 py-5 text-center text-sm font-bold text-rose-500">
                                                        {s.denda > 0 ? '-' + parseFloat(s.denda).toFixed(1) : '0'}
                                                    </td>

                                                    <!-- Final Score -->
                                                    <td class="px-6 py-5 text-center">
                                                        <div class="text-lg font-black text-blue-600">
                                                            {parseFloat(s.nilai_akhir || 0).toFixed(1)}
                                                        </div>
                                                        {#if s.waktu && s.waktu !== '00:00'}
                                                            <div class="text-[10px] font-bold text-slate-400 mt-0.5">
                                                                <i class="fa-regular fa-clock mr-1"></i>{s.waktu}
                                                            </div>
                                                        {/if}
                                                    </td>
                                                </tr>
                                            {/each}
                                        {/if}
                                    {/each}
                                </tbody>
                            </table>
                        </div>
                    </div>
                {/each}
            </div>
        {/if}
    </main>

    <!-- FOOTER -->
    <footer class="relative z-10 border-t border-slate-200 bg-white p-5 shadow-inner mt-auto">
        <div class="mx-auto max-w-7xl flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap justify-center gap-6 text-xs font-black uppercase tracking-widest text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-amber-400 shadow-sm shadow-amber-400/50"></span> Juara 1
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-slate-300"></span> Juara 2
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-amber-600 shadow-sm shadow-amber-600/50"></span> Juara 3
                </div>
            </div>
            <div class="text-[10px] font-bold text-slate-400 tracking-wider">
                DOJO Digital Scoring System &copy; {currentYear}
            </div>
        </div>
    </footer>
</div>
