<script>
    import { onMount } from "svelte";

    // ── State ──────────────────────────────────────────────
    let matches = $state([]);
    let selectedMatchId = $state("");
    let matchState = $state(null);
    let isLoadingMatches = $state(true);
    let isLoadingState = $state(false);
    let activeNodeKey = $state(null); // which node's detail is open

    // Derived bracket data
    let roundsUB = $derived(matchState?.matchNumber?.drawing_data?.upper_bracket?.rounds || []);
    let roundsLB = $derived(matchState?.matchNumber?.drawing_data?.lower_bracket?.rounds || []);
    let grandFinal = $derived(matchState?.matchNumber?.drawing_data?.grand_final || null);

    // Active node details
    let activeNode = $derived.by(() => {
        if (!activeNodeKey || !matchState) return null;
        const res = matchState.randoriResults?.find((r) => r.bracket_node === activeNodeKey) || null;
        return res;
    });

    let activeMeta = $derived.by(() => {
        if (!activeNode) return {};
        try {
            return (typeof activeNode.metadata === "string"
                ? JSON.parse(activeNode.metadata)
                : activeNode.metadata) || {};
        } catch {
            return {};
        }
    });

    let activeNodeData = $derived.by(() => {
        if (!activeNodeKey || !matchState) return null;
        const drawing = matchState.matchNumber?.drawing_data;
        if (!drawing) return null;

        if (activeNodeKey.startsWith("ub_")) {
            const [, ri, mi] = activeNodeKey.split("_").map(Number);
            return drawing.upper_bracket?.rounds?.[ri]?.[mi] || null;
        }
        if (activeNodeKey.startsWith("lb_")) {
            const [, ri, mi] = activeNodeKey.split("_").map(Number);
            return drawing.lower_bracket?.rounds?.[ri]?.[mi] || null;
        }
        if (activeNodeKey === "gf_0_0") {
            return drawing.grand_final || null;
        }
        return null;
    });

    const categories = [
        { label: "PERINGATAN", desc: "Mujoken Kachi", val: 15, key: "mujoken_kachi" },
        { label: "1.", desc: "Ippon", val: 10, key: "ippon" },
        { label: "2.", desc: "Waza Ari", val: 5, key: "waza_ari" },
        { label: "3.", desc: "Hasil Batsu 5", val: 5, key: "hasil_batsu_5" },
        { label: "4.", desc: "Hasil Batsu 10", val: 10, key: "hasil_batsu_10" },
        { label: "5.", desc: "Yusei Kachi", val: 5, key: "yusei_kachi" },
    ];

    // ── Load ──────────────────────────────────────────────
    onMount(async () => {
        try {
            const res = await fetch("/admin/api/scoring/correction/matches");
            if (res.ok) {
                const all = await res.json();
                matches = all.filter((m) => m.draft_type === "randori");
            }
        } finally {
            isLoadingMatches = false;
        }
    });

    async function fetchMatchState() {
        if (!selectedMatchId) { matchState = null; activeNodeKey = null; return; }
        isLoadingState = true;
        activeNodeKey = null;
        try {
            const res = await fetch(`/admin/api/scoring/correction/match-state/${selectedMatchId}`);
            if (res.ok) matchState = await res.json();
        } finally {
            isLoadingState = false;
        }
    }

    function handleMatchChange(e) {
        selectedMatchId = e.target.value;
        fetchMatchState();
    }

    function openDetail(nodeKey) {
        activeNodeKey = nodeKey;
        setTimeout(() => {
            document.getElementById("detail-panel")?.scrollIntoView({ behavior: "smooth" });
        }, 80);
    }

    function formatGender(g) {
        if (g === "Male") return "Laki-laki";
        if (g === "Female") return "Perempuan";
        if (g === "Mix") return "Campuran";
        return g || "";
    }

    function nodeLabel(key) {
        if (key.startsWith("ub_")) {
            const [, ri, mi] = key.split("_").map(Number);
            return `Upper Bracket R${ri + 1} M${mi + 1}`;
        }
        if (key.startsWith("lb_")) {
            const [, ri, mi] = key.split("_").map(Number);
            return `Lower Bracket R${ri + 1} M${mi + 1}`;
        }
        return "Grand Final";
    }

    // ── Print helpers ─────────────────────────────────────
    function printThisNode() {
        window.print();
    }
</script>

<svelte:head>
    <title>Rekap Penilaian Randori — Smart Perkemi</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap");

        body { font-family: "Inter", sans-serif; }

        /* ── Print: hide chrome, show white detail panel ──────── */
        @media print {
            @page { size: A4; margin: 10mm 12mm; }

            html, body {
                background: white !important;
                color: #111 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print { display: none !important; }
            .print-only { display: block !important; }

            #detail-panel {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .print-page-header {
                border-bottom: 2px solid #111;
                padding-bottom: 6px;
                margin-bottom: 10px;
            }

            .athlete-col-red  { background: #fff0f0 !important; border: 1.5px solid #dc2626 !important; }
            .athlete-col-white { background: #f8fafc !important; border: 1.5px solid #64748b !important; }

            .score-table { width: 100%; border-collapse: collapse; font-size: 8pt; }
            .score-table th, .score-table td { border: 1px solid #cbd5e1; padding: 3px 6px; }
            .score-table thead th { background: #f1f5f9; font-weight: 900; text-align: center; text-transform: uppercase; font-size: 7.5pt; }
            .score-table .col-red-hd { background: #fee2e2 !important; color: #991b1b; }
            .score-table .col-white-hd { background: #f0f4ff !important; color: #1e40af; }
            .score-table .total-row { background: #f8fafc; font-weight: 900; }

            .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
            .sig-box  { border: 1px solid #94a3b8; border-radius: 6px; padding: 8px 10px; }
            .sig-label { font-size: 7.5pt; font-weight: 900; text-transform: uppercase; letter-spacing: .04em; color: #374151; margin-bottom: 2px; }
            .sig-name  { font-size: 9pt; font-weight: 700; margin-bottom: 4px; }
            .sig-img   { max-height: 54px; max-width: 100%; object-fit: contain; display: block; }
            .sig-line  { font-size: 7pt; color: #64748b; }
            .sig-blank { height: 36px; border-bottom: 1px dashed #94a3b8; margin-top: 4px; }
        }

        @media screen {
            .print-only { display: none !important; }
        }
    </style>
</svelte:head>

<!-- ════════════════════════════════════════════════════════
     SCREEN LAYOUT (white background)
     ════════════════════════════════════════════════════════ -->
<div class="min-h-screen bg-gray-50" style="font-family:'Inter',sans-serif;">

    <!-- Top bar -->
    <div class="no-print bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-sm font-black text-gray-900 uppercase tracking-wide">Rekap Penilaian Randori</h1>
                    <p class="text-[11px] text-gray-400">Read-only · Pilih pertandingan &amp; cetak hasil</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {#if activeNodeKey && activeNode}
                    <button
                        onclick={printThisNode}
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors shadow-sm"
                    >
                        <i class="fas fa-print"></i> Cetak PDF Babak Ini
                    </button>
                {/if}
                <a
                    href="/admin/new-dashboard"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors border border-gray-200"
                >
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 space-y-5">

        <!-- Match Selector -->
        <div class="no-print bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <label for="match-sel" class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">
                Pilih Nomor Pertandingan
            </label>
            {#if isLoadingMatches}
                <p class="text-sm text-gray-400">Memuat daftar...</p>
            {:else}
                <select
                    id="match-sel"
                    value={selectedMatchId}
                    onchange={handleMatchChange}
                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 px-4 py-3 rounded-xl text-sm font-bold outline-none cursor-pointer focus:border-red-400 transition-colors"
                >
                    <option value="">-- Pilih Pertandingan --</option>
                    {#each matches as m}
                        <option value={String(m.id)}>
                            {m.name}{m.age_group?.name ? " — " + m.age_group.name : ""}
                            {#if m.gender} ({formatGender(m.gender)}){/if}
                        </option>
                    {/each}
                </select>
            {/if}
        </div>

        <!-- Loading -->
        {#if isLoadingState}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-16 text-center">
                <i class="fas fa-spinner fa-spin fa-2x text-red-500 mb-3"></i>
                <p class="text-sm font-bold text-gray-400">Memuat data pertandingan...</p>
            </div>

        {:else if matchState}

            <!-- Match Info Banner -->
            <div class="no-print bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-wrap justify-between items-center gap-3">
                <div>
                    <span class="inline-block bg-red-100 text-red-700 text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">RANDORI</span>
                    <h2 class="text-lg font-black text-gray-900 mt-1 uppercase">{matchState.matchNumber.name}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {#if matchState.matchNumber.age_group}Usia: {matchState.matchNumber.age_group.name} &nbsp;·&nbsp;{/if}
                        {#if matchState.matchNumber.gender}Gender: {formatGender(matchState.matchNumber.gender)} &nbsp;·&nbsp;{/if}
                        {matchState.randoriResults?.length || 0} babak selesai
                    </p>
                </div>
            </div>

            <!-- Bracket Table -->
            <div class="no-print bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-sitemap text-red-500"></i>
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-700">Bagan Pertandingan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 uppercase font-black tracking-wider border-b border-gray-200 text-[11px]">
                                <th class="px-4 py-3">Babak</th>
                                <th class="px-4 py-3 text-red-500">Pita Merah (AKA)</th>
                                <th class="px-4 py-3 text-gray-500">Pita Putih (SHIRO)</th>
                                <th class="px-4 py-3 text-center">Skor</th>
                                <th class="px-4 py-3">Pemenang</th>
                                <th class="px-4 py-3 text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">

                            <!-- UB Rounds -->
                            {#each roundsUB as round, rIdx}
                                {#each round as mNode, mIdx}
                                    {@const nodeKey = `ub_${rIdx}_${mIdx}`}
                                    {@const res = matchState.randoriResults?.find(r => r.bracket_node === nodeKey)}
                                    <tr class="hover:bg-gray-50 transition-colors {activeNodeKey === nodeKey ? 'bg-red-50' : ''}">
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-black text-gray-500 uppercase">UB R{rIdx+1} M{mIdx+1}</span>
                                        </td>
                                        <td class="px-4 py-3 font-bold text-gray-800">{#if mNode.athlete1?.name}{mNode.athlete1.name}{:else}<span class="text-gray-300 italic">Menunggu</span>{/if}</td>
                                        <td class="px-4 py-3 font-bold text-gray-600">{#if mNode.athlete2?.name}{mNode.athlete2.name}{:else}<span class="text-gray-300 italic">Menunggu</span>{/if}</td>
                                        <td class="px-4 py-3 text-center font-black font-mono">
                                            {#if res}
                                                <span class="text-red-600">{res.score_red}</span>
                                                <span class="text-gray-300 mx-1">—</span>
                                                <span class="text-gray-600">{res.score_blue}</span>
                                            {:else}
                                                <span class="text-gray-300">—</span>
                                            {/if}
                                        </td>
                                        <td class="px-4 py-3">
                                            {#if res}
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {res.winner_color === 'athlete1' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'}">
                                                    {res.winner_color === "athlete1" ? "Merah" : "Putih"}
                                                </span>
                                            {:else}
                                                <span class="text-gray-300 text-[10px]">Belum tanding</span>
                                            {/if}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {#if res}
                                                <button
                                                    onclick={() => openDetail(nodeKey)}
                                                    class="px-3 py-1.5 {activeNodeKey === nodeKey ? 'bg-red-600 text-white' : 'bg-gray-100 hover:bg-red-50 hover:text-red-700 text-gray-600'} font-black rounded-lg transition-colors text-[10px] uppercase tracking-wide border {activeNodeKey === nodeKey ? 'border-red-600' : 'border-gray-200'}"
                                                >
                                                    {activeNodeKey === nodeKey ? "● Aktif" : "Lihat"}
                                                </button>
                                            {:else}
                                                <span class="text-gray-300 text-[10px]">—</span>
                                            {/if}
                                        </td>
                                    </tr>
                                {/each}
                            {/each}

                            <!-- LB Rounds -->
                            {#each roundsLB as round, rIdx}
                                {#each round as mNode, mIdx}
                                    {@const nodeKey = `lb_${rIdx}_${mIdx}`}
                                    {@const res = matchState.randoriResults?.find(r => r.bracket_node === nodeKey)}
                                    <tr class="hover:bg-gray-50 transition-colors {activeNodeKey === nodeKey ? 'bg-red-50' : ''}">
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] font-black text-gray-500 uppercase">LB R{rIdx+1} M{mIdx+1}</span>
                                        </td>
                                        <td class="px-4 py-3 font-bold text-gray-800">{mNode.athlete1?.name || "—"}</td>
                                        <td class="px-4 py-3 font-bold text-gray-600">{mNode.athlete2?.name || "—"}</td>
                                        <td class="px-4 py-3 text-center font-black font-mono">
                                            {#if res}
                                                <span class="text-red-600">{res.score_red}</span>
                                                <span class="text-gray-300 mx-1">—</span>
                                                <span class="text-gray-600">{res.score_blue}</span>
                                            {:else}
                                                <span class="text-gray-300">—</span>
                                            {/if}
                                        </td>
                                        <td class="px-4 py-3">
                                            {#if res}
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {res.winner_color === 'athlete1' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'}">
                                                    {res.winner_color === "athlete1" ? "Merah" : "Putih"}
                                                </span>
                                            {:else}
                                                <span class="text-gray-300 text-[10px]">Belum tanding</span>
                                            {/if}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {#if res}
                                                <button
                                                    onclick={() => openDetail(nodeKey)}
                                                    class="px-3 py-1.5 {activeNodeKey === nodeKey ? 'bg-red-600 text-white' : 'bg-gray-100 hover:bg-red-50 hover:text-red-700 text-gray-600'} font-black rounded-lg transition-colors text-[10px] uppercase tracking-wide border {activeNodeKey === nodeKey ? 'border-red-600' : 'border-gray-200'}"
                                                >
                                                    {activeNodeKey === nodeKey ? "● Aktif" : "Lihat"}
                                                </button>
                                            {:else}
                                                <span class="text-gray-300 text-[10px]">—</span>
                                            {/if}
                                        </td>
                                    </tr>
                                {/each}
                            {/each}

                            <!-- Grand Final -->
                            {#if grandFinal}
                                {@const nodeKey = "gf_0_0"}
                                {@const res = matchState.randoriResults?.find(r => r.bracket_node === nodeKey)}
                                <tr class="hover:bg-amber-50 transition-colors {activeNodeKey === nodeKey ? 'bg-amber-50' : 'bg-amber-50/30'}">
                                    <td class="px-4 py-3">
                                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-wider">Grand Final</span>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-800">{grandFinal.athlete1?.name || "Menunggu"}</td>
                                    <td class="px-4 py-3 font-bold text-gray-600">{grandFinal.athlete2?.name || "Menunggu"}</td>
                                    <td class="px-4 py-3 text-center font-black font-mono">
                                        {#if res}
                                            <span class="text-red-600">{res.score_red}</span>
                                            <span class="text-gray-300 mx-1">—</span>
                                            <span class="text-gray-600">{res.score_blue}</span>
                                        {:else}
                                            <span class="text-gray-300">—</span>
                                        {/if}
                                    </td>
                                    <td class="px-4 py-3">
                                        {#if res}
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {res.winner_color === 'athlete1' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'}">
                                                {res.winner_color === "athlete1" ? "Merah" : "Putih"}
                                            </span>
                                        {:else}
                                            <span class="text-gray-300 text-[10px]">Menunggu</span>
                                        {/if}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {#if res}
                                            <button
                                                onclick={() => openDetail(nodeKey)}
                                                class="px-3 py-1.5 {activeNodeKey === nodeKey ? 'bg-amber-500 text-white' : 'bg-amber-50 hover:bg-amber-100 text-amber-700'} font-black rounded-lg transition-colors text-[10px] uppercase tracking-wide border {activeNodeKey === nodeKey ? 'border-amber-500' : 'border-amber-200'}"
                                            >
                                                {activeNodeKey === nodeKey ? "● Aktif" : "Lihat"}
                                            </button>
                                        {:else}
                                            <span class="text-gray-300 text-[10px]">—</span>
                                        {/if}
                                    </td>
                                </tr>
                            {/if}

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── DETAIL PANEL (read-only) ── -->
            {#if activeNodeKey && activeNodeData && activeNode}
                {@const meta = activeMeta}
                {@const aka = meta.scoringAka || {}}
                {@const shiro = meta.scoringShiro || {}}
                {@const sigs = meta.signatures || {}}
                {@const scoreRed = activeNode.score_red ?? 0}
                {@const scoreBlue = activeNode.score_blue ?? 0}
                {@const isRedWinner = activeNode.winner_color === "athlete1"}

                <div id="detail-panel" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                    <!-- Panel Header (screen only) -->
                    <div class="no-print px-5 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-check text-red-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-wide text-gray-900">
                                    Detail Penilaian — {nodeLabel(activeNodeKey)}
                                </h3>
                                <p class="text-[10px] font-mono text-gray-400">NODE: {activeNodeKey}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                onclick={printThisNode}
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors shadow"
                            >
                                <i class="fas fa-print"></i> Cetak PDF
                            </button>
                            <button
                                onclick={() => activeNodeKey = null}
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition-colors border border-gray-200"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>

                    <div class="p-5 md:p-7 space-y-6">

                        <!-- ── PRINT HEADER (only visible on print) ── -->
                        <div class="print-only print-page-header">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                                <div>
                                    <div style="font-size:13pt;font-weight:900;text-transform:uppercase;letter-spacing:.04em;">
                                        Rekap Penilaian Randori
                                    </div>
                                    <div style="font-size:8.5pt;color:#555;margin-top:2px;">
                                        {matchState.matchNumber.name}
                                        {#if matchState.matchNumber.age_group} — {matchState.matchNumber.age_group.name}{/if}
                                        {#if matchState.matchNumber.gender} ({formatGender(matchState.matchNumber.gender)}){/if}
                                    </div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:9pt;font-weight:900;text-transform:uppercase;">{nodeLabel(activeNodeKey)}</div>
                                    <div style="font-size:7pt;color:#888;font-family:monospace;">NODE: {activeNodeKey}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Athletes side by side -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="athlete-col-red flex items-center gap-3 px-4 py-3.5 bg-red-50 border border-red-200 rounded-xl">
                                <div class="w-2.5 h-9 rounded-full bg-red-500 shrink-0"></div>
                                <div>
                                    <div class="text-[10px] font-black text-red-500 uppercase tracking-widest">Pita Merah (AKA)</div>
                                    <div class="text-sm font-black text-gray-900 uppercase leading-tight">{activeNodeData.athlete1?.name || "BYE"}</div>
                                    {#if activeNodeData.athlete1?.contingent}
                                        <div class="text-xs text-gray-400">{activeNodeData.athlete1.contingent}</div>
                                    {/if}
                                </div>
                            </div>
                            <div class="athlete-col-white flex items-center gap-3 px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="w-2.5 h-9 rounded-full bg-gray-400 shrink-0"></div>
                                <div>
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pita Putih (SHIRO)</div>
                                    <div class="text-sm font-black text-gray-900 uppercase leading-tight">{activeNodeData.athlete2?.name || "BYE"}</div>
                                    {#if activeNodeData.athlete2?.contingent}
                                        <div class="text-xs text-gray-400">{activeNodeData.athlete2.contingent}</div>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <!-- Scoring Table (READ-ONLY) -->
                        <div class="overflow-hidden border border-gray-200 rounded-xl shadow-sm">
                            <div class="grid grid-cols-2">
                                <div class="bg-red-600 text-white py-2 text-center text-[11px] font-black uppercase tracking-widest border-r border-gray-200">Pita Merah (AKA)</div>
                                <div class="bg-gray-600 text-white py-2 text-center text-[11px] font-black uppercase tracking-widest">Pita Putih (SHIRO)</div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="score-table w-full text-xs border-collapse" style="min-width:620px;">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black tracking-wider border-b border-gray-200">
                                            <th class="col-red-hd px-3 py-2.5 border border-gray-200 text-left">Keputusan</th>
                                            <th class="col-red-hd px-3 py-2.5 border border-gray-200 text-center">Poin</th>
                                            <th class="col-red-hd px-3 py-2.5 border border-gray-200 text-center">Jml</th>
                                            <th class="col-red-hd px-3 py-2.5 border border-gray-200 text-center">Total</th>
                                            <th class="col-white-hd px-3 py-2.5 border border-gray-200 text-left border-l-2 border-l-gray-300">Keputusan</th>
                                            <th class="col-white-hd px-3 py-2.5 border border-gray-200 text-center">Poin</th>
                                            <th class="col-white-hd px-3 py-2.5 border border-gray-200 text-center">Jml</th>
                                            <th class="col-white-hd px-3 py-2.5 border border-gray-200 text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        {#each categories as cat}
                                            {@const isBatsu = cat.key.includes("batsu")}
                                            {@const akaVal = aka[cat.key] || 0}
                                            {@const shiroVal = shiro[cat.key] || 0}
                                            <tr class="hover:bg-gray-50">
                                                <!-- AKA -->
                                                <td class="px-3 py-3 border border-gray-100 text-left">
                                                    <span class="text-[10px] font-black uppercase {isBatsu ? 'text-red-500' : 'text-gray-400'}">{cat.label}</span>
                                                    <span class="text-gray-700 ml-1">{cat.desc}</span>
                                                </td>
                                                <td class="px-3 py-3 border border-gray-100 text-center text-gray-400">{cat.val}</td>
                                                <td class="px-3 py-3 border border-gray-100 text-center font-black text-gray-800">{akaVal}</td>
                                                <td class="px-3 py-3 border border-gray-100 text-center font-black {isBatsu ? 'text-red-500' : 'text-gray-900'}">
                                                    {isBatsu ? "-" : ""}{cat.val * akaVal}
                                                </td>
                                                <!-- SHIRO -->
                                                <td class="px-3 py-3 border border-gray-100 text-left border-l-2 border-l-gray-300">
                                                    <span class="text-[10px] font-black uppercase {isBatsu ? 'text-red-500' : 'text-gray-400'}">{cat.label}</span>
                                                    <span class="text-gray-700 ml-1">{cat.desc}</span>
                                                </td>
                                                <td class="px-3 py-3 border border-gray-100 text-center text-gray-400">{cat.val}</td>
                                                <td class="px-3 py-3 border border-gray-100 text-center font-black text-gray-800">{shiroVal}</td>
                                                <td class="px-3 py-3 border border-gray-100 text-center font-black {isBatsu ? 'text-red-500' : 'text-gray-900'}">
                                                    {isBatsu ? "-" : ""}{cat.val * shiroVal}
                                                </td>
                                            </tr>
                                        {/each}
                                        <!-- Totals row -->
                                        <tr class="total-row bg-gray-50 border-t-2 border-gray-200">
                                            <td colspan="3" class="px-3 py-3.5 border border-gray-200 text-right font-black text-gray-500 text-xs">TOTAL MERAH (AKA)</td>
                                            <td class="px-3 py-3.5 border border-gray-200 text-center">
                                                <span class="text-2xl font-black text-red-600">{scoreRed}</span>
                                            </td>
                                            <td colspan="3" class="px-3 py-3.5 border border-gray-200 text-right font-black text-gray-500 text-xs border-l-2 border-l-gray-300">TOTAL PUTIH (SHIRO)</td>
                                            <td class="px-3 py-3.5 border border-gray-200 text-center">
                                                <span class="text-2xl font-black text-gray-600">{scoreBlue}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Winner Banner -->
                        <div class="flex items-center justify-center py-4 rounded-xl border-2 font-black text-sm uppercase tracking-widest {isRedWinner ? 'bg-red-50 border-red-300 text-red-700' : 'bg-slate-50 border-slate-300 text-slate-700'}">
                            <i class="fas fa-trophy mr-2 text-amber-500"></i>
                            PEMENANG: PITA {isRedWinner ? "MERAH" : "PUTIH"}
                            &nbsp;—&nbsp;
                            {isRedWinner ? activeNodeData.athlete1?.name : activeNodeData.athlete2?.name}
                        </div>

                        <!-- Signatures (READ-ONLY display) -->
                        <div class="border-t-2 border-gray-100 pt-6">
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-signature text-red-500"></i>
                                <h4 class="text-sm font-black uppercase tracking-wider text-gray-700">Tanda Tangan &amp; Pengesahan</h4>
                            </div>
                            <div class="sig-grid grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <!-- Koordinator -->
                                <div class="sig-box border border-gray-200 rounded-xl p-4 bg-gray-50">
                                    <div class="sig-label text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Koordinator Lapangan</div>
                                    <div class="sig-name text-sm font-black text-gray-900 mb-2">
                                        {#if sigs.koordinator?.name}{sigs.koordinator.name}{:else}<span class="text-gray-300 italic font-normal">Belum diisi</span>{/if}
                                    </div>
                                    {#if sigs.koordinator?.signature}
                                        <img class="sig-img h-14 object-contain" src={sigs.koordinator.signature} alt="TTD Koordinator" />
                                    {:else}
                                        <div class="sig-blank h-10 border-b border-dashed border-gray-300"></div>
                                    {/if}
                                    <div class="sig-line text-[10px] text-gray-400 mt-1">Tanda Tangan</div>
                                </div>

                                <!-- Wasit -->
                                <div class="sig-box border border-gray-200 rounded-xl p-4 bg-gray-50">
                                    <div class="sig-label text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Wasit Utama</div>
                                    <div class="sig-name text-sm font-black text-gray-900 mb-2">
                                        {#if sigs.wasit?.name}{sigs.wasit.name}{:else}<span class="text-gray-300 italic font-normal">Belum diisi</span>{/if}
                                    </div>
                                    {#if sigs.wasit?.signature}
                                        <img class="sig-img h-14 object-contain" src={sigs.wasit.signature} alt="TTD Wasit" />
                                    {:else}
                                        <div class="sig-blank h-10 border-b border-dashed border-gray-300"></div>
                                    {/if}
                                    <div class="sig-line text-[10px] text-gray-400 mt-1">Tanda Tangan</div>
                                </div>

                                <!-- Panitera -->
                                {#if sigs.panitera}
                                    {#each (Array.isArray(sigs.panitera) ? sigs.panitera : [sigs.panitera]) as pan, pi}
                                        <div class="sig-box border border-gray-200 rounded-xl p-4 bg-gray-50">
                                            <div class="sig-label text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                                Koordinator Panitera{pi > 0 ? " " + (pi + 1) : ""}
                                            </div>
                                            <div class="sig-name text-sm font-black text-gray-900 mb-2">
                                                {#if pan?.name}{pan.name}{:else}<span class="text-gray-300 italic font-normal">Belum diisi</span>{/if}
                                            </div>
                                            {#if pan?.signature}
                                                <img class="sig-img h-14 object-contain" src={pan.signature} alt="TTD Panitera" />
                                            {:else}
                                                <div class="sig-blank h-10 border-b border-dashed border-gray-300"></div>
                                            {/if}
                                            <div class="sig-line text-[10px] text-gray-400 mt-1">Tanda Tangan</div>
                                        </div>
                                    {/each}
                                {:else}
                                    <div class="sig-box border border-gray-200 rounded-xl p-4 bg-gray-50">
                                        <div class="sig-label text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Koordinator Panitera</div>
                                        <div class="sig-blank h-10 border-b border-dashed border-gray-300"></div>
                                        <div class="sig-line text-[10px] text-gray-400 mt-1">Tanda Tangan</div>
                                    </div>
                                {/if}

                                <!-- Manager Red -->
                                <div class="sig-box border border-red-200 rounded-xl p-4 bg-red-50">
                                    <div class="sig-label text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Manajer Pita Merah (AKA)</div>
                                    <div class="sig-name text-sm font-black text-gray-900 mb-2">
                                        {#if sigs.manager_red?.name}{sigs.manager_red.name}{:else}<span class="text-gray-300 italic font-normal">Belum diisi</span>{/if}
                                    </div>
                                    {#if sigs.manager_red?.signature}
                                        <img class="sig-img h-14 object-contain" src={sigs.manager_red.signature} alt="TTD Manajer Merah" />
                                    {:else}
                                        <div class="sig-blank h-10 border-b border-dashed border-red-200"></div>
                                    {/if}
                                    <div class="sig-line text-[10px] text-gray-400 mt-1">Tanda Tangan</div>
                                </div>

                                <!-- Manager White -->
                                <div class="sig-box border border-gray-200 rounded-xl p-4 bg-gray-50">
                                    <div class="sig-label text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Manajer Pita Putih (SHIRO)</div>
                                    <div class="sig-name text-sm font-black text-gray-900 mb-2">
                                        {#if sigs.manager_white?.name}{sigs.manager_white.name}{:else}<span class="text-gray-300 italic font-normal">Belum diisi</span>{/if}
                                    </div>
                                    {#if sigs.manager_white?.signature}
                                        <img class="sig-img h-14 object-contain" src={sigs.manager_white.signature} alt="TTD Manajer Putih" />
                                    {:else}
                                        <div class="sig-blank h-10 border-b border-dashed border-gray-300"></div>
                                    {/if}
                                    <div class="sig-line text-[10px] text-gray-400 mt-1">Tanda Tangan</div>
                                </div>

                            </div><!-- /sig-grid -->
                        </div><!-- /signatures -->

                    </div><!-- /p-5 -->
                </div><!-- /detail-panel -->
            {/if}

        {:else if !isLoadingState && selectedMatchId === ""}
            <!-- Empty state -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-16 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-trophy text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-sm font-black text-gray-700 uppercase tracking-widest">Belum Ada Pertandingan Yang Dipilih</h3>
                <p class="text-xs text-gray-400 mt-2 max-w-sm">Pilih salah satu nomor pertandingan Randori di atas untuk memuat bagan dan melihat hasil penilaian.</p>
            </div>
        {/if}

    </div><!-- /max-w container -->
</div><!-- /min-h-screen -->
