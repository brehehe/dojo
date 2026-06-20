<script>
    import { onMount } from "svelte";

    let matches = $state([]);
    let selectedMatchId = $state("");
    let matchData = $state(null);
    let isLoadingMatches = $state(true);
    let isLoadingData = $state(false);
    let printNodeKey = $state(null); // null = print all, string = print single node

    const categories = [
        { label: "PERINGATAN", desc: "Mujoken Kachi", val: 15, key: "mujoken_kachi" },
        { label: "1.", desc: "Ippon", val: 10, key: "ippon" },
        { label: "2.", desc: "Waza Ari", val: 5, key: "waza_ari" },
        { label: "3.", desc: "Hasil Batsu 5", val: 5, key: "hasil_batsu_5" },
        { label: "4.", desc: "Hasil Batsu 10", val: 10, key: "hasil_batsu_10" },
        { label: "5.", desc: "Yusei Kachi", val: 5, key: "yusei_kachi" },
    ];

    /** @returns {Array<{nodeKey:string, label:string, nodeData:any, result:any|null}>} */
    function getAllNodes(md) {
        if (!md) return [];
        const drawing = md.matchNumber?.drawing_data;
        if (!drawing) return [];
        const nodes = [];

        (drawing.upper_bracket?.rounds || []).forEach((round, rIdx) => {
            round.forEach((mNode, mIdx) => {
                const nodeKey = `ub_${rIdx}_${mIdx}`;
                nodes.push({
                    nodeKey,
                    label: `Upper Bracket R${rIdx + 1} M${mIdx + 1}`,
                    nodeData: mNode,
                    result: md.randoriResults?.find((r) => r.bracket_node === nodeKey) || null,
                });
            });
        });

        (drawing.lower_bracket?.rounds || []).forEach((round, rIdx) => {
            round.forEach((mNode, mIdx) => {
                const nodeKey = `lb_${rIdx}_${mIdx}`;
                nodes.push({
                    nodeKey,
                    label: `Lower Bracket R${rIdx + 1} M${mIdx + 1}`,
                    nodeData: mNode,
                    result: md.randoriResults?.find((r) => r.bracket_node === nodeKey) || null,
                });
            });
        });

        if (drawing.grand_final) {
            const nodeKey = "gf_0_0";
            nodes.push({
                nodeKey,
                label: "Grand Final",
                nodeData: drawing.grand_final,
                result: md.randoriResults?.find((r) => r.bracket_node === nodeKey) || null,
            });
        }

        return nodes;
    }

    /** Parse metadata from result safely */
    function getMeta(result) {
        if (!result) return {};
        try {
            return (typeof result.metadata === "string" ? JSON.parse(result.metadata) : result.metadata) || {};
        } catch {
            return {};
        }
    }

    function calcScore(scoring) {
        if (!scoring) return 0;
        return Math.max(
            0,
            (scoring.mujoken_kachi || 0) * 15 +
                (scoring.ippon || 0) * 10 +
                (scoring.waza_ari || 0) * 5 +
                (scoring.yusei_kachi || 0) * 5 -
                (scoring.hasil_batsu_5 || 0) * 5 -
                (scoring.hasil_batsu_10 || 0) * 10
        );
    }

    let allNodes = $derived(getAllNodes(matchData));
    let displayNodes = $derived(
        printNodeKey
            ? allNodes.filter((n) => n.nodeKey === printNodeKey)
            : allNodes.filter((n) => n.result !== null)
    );

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

    async function loadMatchData() {
        if (!selectedMatchId) {
            matchData = null;
            return;
        }
        isLoadingData = true;
        printNodeKey = null;
        try {
            const res = await fetch(`/admin/api/scoring/correction/match-state/${selectedMatchId}`);
            if (res.ok) {
                matchData = await res.json();
            }
        } finally {
            isLoadingData = false;
        }
    }

    function handleMatchChange(e) {
        selectedMatchId = e.target.value;
        loadMatchData();
    }

    function printAll() {
        printNodeKey = null;
        setTimeout(() => window.print(), 100);
    }

    function printNode(key) {
        printNodeKey = key;
        setTimeout(() => {
            window.print();
            printNodeKey = null;
        }, 150);
    }

    function formatGender(g) {
        if (!g) return "";
        if (g === "Male") return "Laki-laki";
        if (g === "Female") return "Perempuan";
        if (g === "Mix") return "Campuran";
        return g;
    }
</script>

<svelte:head>
    <title>Cetak Hasil Randori — Smart Perkemi</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap");

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", sans-serif;
            background: #0f172a;
            color: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        /* ──────── Screen Layout ──────── */
        .screen-shell {
            min-height: 100vh;
            background: #0f172a;
            padding: 2rem 1rem;
        }

        .screen-card {
            background: #1e293b;
            border-radius: 1rem;
            border: 1px solid #334155;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .score-badge-red {
            background: #7f1d1d;
            color: #fca5a5;
            border: 1px solid #ef4444;
        }
        .score-badge-white {
            background: #1e293b;
            color: #f8fafc;
            border: 1px solid #475569;
        }

        /* ──────── Print Styles ──────── */
        @media print {
            @page {
                size: A4;
                margin: 10mm 12mm;
            }

            html,
            body {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .print-page {
                background: white;
                color: #111;
                page-break-after: always;
                break-after: page;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 0 0 0 !important;
            }
            .print-page:last-child {
                page-break-after: avoid;
                break-after: avoid;
            }

            .print-header {
                border-bottom: 2.5px solid #111;
                padding-bottom: 8px;
                margin-bottom: 10px;
            }

            .print-title {
                font-size: 14pt;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #111 !important;
            }

            .print-subtitle {
                font-size: 9pt;
                color: #444 !important;
            }

            .athlete-card {
                border: 1.5px solid #111;
                border-radius: 6px;
                padding: 8px 12px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .athlete-dot {
                width: 10px;
                height: 32px;
                border-radius: 9999px;
                flex-shrink: 0;
            }
            .dot-red {
                background: #dc2626;
            }
            .dot-white {
                background: #64748b;
            }
            .athlete-label {
                font-size: 8pt;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .athlete-name {
                font-size: 11pt;
                font-weight: 900;
                text-transform: uppercase;
                line-height: 1.2;
            }
            .athlete-cont {
                font-size: 8pt;
                color: #555;
            }

            .score-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8pt;
                margin-top: 8px;
            }
            .score-table th {
                background: #f1f5f9;
                border: 1px solid #cbd5e1;
                padding: 4px 6px;
                text-align: center;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }
            .score-table td {
                border: 1px solid #cbd5e1;
                padding: 4px 6px;
                text-align: center;
            }
            .header-red {
                background: #fee2e2 !important;
                color: #991b1b !important;
            }
            .header-white {
                background: #f8fafc !important;
                color: #1e293b !important;
            }
            .batsu-row td {
                color: #dc2626;
            }
            .total-row {
                background: #f8fafc;
                font-weight: 900;
                font-size: 10pt;
            }
            .winner-row {
                background: #fef9c3;
                font-weight: 900;
                font-size: 10pt;
                text-align: center;
                padding: 5px;
            }

            /* Signature grid */
            .sig-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                margin-top: 12px;
            }
            .sig-box {
                border: 1px solid #94a3b8;
                border-radius: 6px;
                padding: 8px 10px;
            }
            .sig-label {
                font-size: 8pt;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                margin-bottom: 2px;
                color: #374151;
            }
            .sig-name {
                font-size: 9pt;
                font-weight: 700;
                margin-bottom: 4px;
            }
            .sig-img {
                max-height: 56px;
                max-width: 100%;
                object-fit: contain;
                display: block;
            }
            .sig-empty {
                height: 40px;
                border-bottom: 1px dashed #94a3b8;
                margin-top: 4px;
            }
            .sig-line {
                margin-top: 4px;
                font-size: 7.5pt;
                color: #64748b;
            }

            .node-divider {
                border: none;
                border-top: 2.5px solid #111;
                margin: 8px 0;
            }
        }
    </style>
</svelte:head>

<!-- ============================================================
     SCREEN LAYOUT — hidden on print
     ============================================================ -->
<div class="screen-shell no-print">
    <div style="max-width:960px;margin:0 auto;">
        <!-- Header -->
        <div class="screen-card" style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:1.4rem;font-weight:900;text-transform:uppercase;letter-spacing:.04em;color:#f1f5f9;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#f97316;">&#x1F4CB;</span>
                    Cetak Hasil Penilaian Randori
                </h1>
                <p style="font-size:.75rem;color:#94a3b8;margin-top:.25rem;">
                    Pilih nomor pertandingan, lalu cetak rekap nilai &amp; tanda tangan per babak
                </p>
            </div>
            <a
                href="/admin/panitera/scoring"
                style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:#334155;color:#f1f5f9;border-radius:.6rem;font-size:.7rem;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:.05em;border:1px solid #475569;"
            >
                ← Kembali ke Scoring
            </a>
        </div>

        <!-- Match Selector -->
        <div class="screen-card">
            <label for="sel-match" style="display:block;font-size:.65rem;font-weight:900;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">
                Pilih Nomor Pertandingan Randori
            </label>
            {#if isLoadingMatches}
                <p style="font-size:.8rem;color:#64748b;">Memuat daftar...</p>
            {:else}
                <select
                    id="sel-match"
                    value={selectedMatchId}
                    onchange={handleMatchChange}
                    style="width:100%;background:#0f172a;border:1px solid #334155;color:#f1f5f9;padding:.75rem 1rem;border-radius:.6rem;font-size:.85rem;font-weight:700;outline:none;"
                >
                    <option value="">-- Pilih Pertandingan --</option>
                    {#each matches as m}
                        <option value={String(m.id)}>
                            {m.name}{m.age_group?.name ? " — " + m.age_group.name : ""}{m.gender ? " (" + formatGender(m.gender) + ")" : ""}
                        </option>
                    {/each}
                </select>
            {/if}
        </div>

        <!-- Loading -->
        {#if isLoadingData}
            <div class="screen-card" style="text-align:center;padding:3rem;">
                <p style="color:#94a3b8;font-size:.9rem;font-weight:700;">⏳ Memuat data pertandingan...</p>
            </div>

        {:else if matchData}
            <!-- Match Info Banner -->
            <div class="screen-card" style="background:linear-gradient(135deg,#1a0505 0%,#1e293b 100%);border-color:#7f1d1d;">
                <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;">
                    <div>
                        <span style="background:#b91c1c;color:#fff;font-size:.6rem;font-weight:900;padding:.2rem .7rem;border-radius:999px;text-transform:uppercase;letter-spacing:.06em;">RANDORI</span>
                        <h2 style="font-size:1.2rem;font-weight:900;color:#fff;margin:.4rem 0 .2rem;text-transform:uppercase;">
                            {matchData.matchNumber.name}
                        </h2>
                        <p style="font-size:.7rem;color:#94a3b8;">
                            {#if matchData.matchNumber.age_group}Usia: {matchData.matchNumber.age_group.name} &nbsp;|&nbsp;{/if}
                            {#if matchData.matchNumber.gender}Gender: {formatGender(matchData.matchNumber.gender)} &nbsp;|&nbsp;{/if}
                            {allNodes.filter(n => n.result).length} babak selesai dari {allNodes.length} babak
                        </p>
                    </div>
                    <button
                        onclick={printAll}
                        style="display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1.4rem;background:#16a34a;color:#fff;border:none;border-radius:.7rem;font-size:.75rem;font-weight:900;text-transform:uppercase;letter-spacing:.05em;cursor:pointer;"
                    >
                        🖨️ Cetak Semua Babak
                    </button>
                </div>
            </div>

            <!-- Node List -->
            {#each allNodes as node}
                {@const meta = getMeta(node.result)}
                {@const aka = meta.scoringAka || {}}
                {@const shiro = meta.scoringShiro || {}}
                {@const sigs = meta.signatures || {}}
                {@const totalRed = node.result ? (node.result.score_red ?? calcScore(aka)) : 0}
                {@const totalWhite = node.result ? (node.result.score_blue ?? calcScore(shiro)) : 0}
                <div class="screen-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem;margin-bottom:.75rem;">
                        <div>
                            <div style="font-size:.6rem;font-weight:900;color:#f97316;text-transform:uppercase;letter-spacing:.07em;margin-bottom:.2rem;">
                                {node.label}
                            </div>
                            <div style="font-size:.7rem;font-family:monospace;color:#64748b;">{node.nodeKey}</div>
                        </div>
                        {#if node.result}
                            <div style="display:flex;align-items:center;gap:.6rem;">
                                <span style="font-size:1.2rem;font-weight:900;color:#ef4444;">{totalRed}</span>
                                <span style="font-size:.85rem;color:#475569;font-weight:700;">vs</span>
                                <span style="font-size:1.2rem;font-weight:900;color:#94a3b8;">{totalWhite}</span>
                                <span style="padding:.2rem .7rem;border-radius:999px;font-size:.6rem;font-weight:900;text-transform:uppercase;letter-spacing:.06em;
                                    {node.result.winner_color === 'athlete1' ? 'background:#7f1d1d;color:#fca5a5;' : 'background:#1e3a5f;color:#93c5fd;'}">
                                    Menang: {node.result.winner_color === "athlete1" ? "Merah" : "Putih"}
                                </span>
                                <button
                                    onclick={() => printNode(node.nodeKey)}
                                    style="padding:.4rem .9rem;background:#334155;color:#f1f5f9;border:1px solid #475569;border-radius:.5rem;font-size:.65rem;font-weight:900;text-transform:uppercase;cursor:pointer;"
                                >
                                    🖨️ Cetak
                                </button>
                            </div>
                        {:else}
                            <span style="font-size:.7rem;color:#64748b;font-style:italic;">
                                {node.nodeData.athlete1 && node.nodeData.athlete2 ? "Belum ada hasil" : "Menunggu peserta"}
                            </span>
                        {/if}
                    </div>

                    <!-- Athletes -->
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:.75rem;">
                        <div style="display:flex;align-items:center;gap:.6rem;background:#450a0a;border:1px solid #7f1d1d;border-radius:.6rem;padding:.6rem .8rem;">
                            <div style="width:8px;height:28px;border-radius:999px;background:#ef4444;flex-shrink:0;"></div>
                            <div>
                                <div style="font-size:.55rem;font-weight:900;color:#f87171;text-transform:uppercase;letter-spacing:.07em;">Pita Merah (AKA)</div>
                                <div style="font-size:.85rem;font-weight:900;color:#fff;text-transform:uppercase;">{node.nodeData.athlete1?.name || "BYE"}</div>
                                {#if node.nodeData.athlete1?.contingent}<div style="font-size:.65rem;color:#94a3b8;">{node.nodeData.athlete1.contingent}</div>{/if}
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:.6rem;background:#1e293b;border:1px solid #334155;border-radius:.6rem;padding:.6rem .8rem;">
                            <div style="width:8px;height:28px;border-radius:999px;background:#94a3b8;flex-shrink:0;"></div>
                            <div>
                                <div style="font-size:.55rem;font-weight:900;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Pita Putih (SHIRO)</div>
                                <div style="font-size:.85rem;font-weight:900;color:#fff;text-transform:uppercase;">{node.nodeData.athlete2?.name || "BYE"}</div>
                                {#if node.nodeData.athlete2?.contingent}<div style="font-size:.65rem;color:#94a3b8;">{node.nodeData.athlete2.contingent}</div>{/if}
                            </div>
                        </div>
                    </div>

                    <!-- Scoring detail (only if result exists) -->
                    {#if node.result}
                        <div style="overflow-x:auto;border:1px solid #334155;border-radius:.6rem;font-size:.7rem;">
                            <table style="width:100%;border-collapse:collapse;min-width:640px;">
                                <thead>
                                    <tr>
                                        <th colspan="4" style="background:#7f1d1d;color:#fecaca;padding:.4rem .6rem;text-align:center;font-size:.6rem;font-weight:900;text-transform:uppercase;letter-spacing:.05em;">
                                            PITA MERAH (AKA)
                                        </th>
                                        <th colspan="4" style="background:#1e293b;color:#e2e8f0;padding:.4rem .6rem;text-align:center;font-size:.6rem;font-weight:900;text-transform:uppercase;letter-spacing:.05em;border-left:1px solid #334155;">
                                            PITA PUTIH (SHIRO)
                                        </th>
                                    </tr>
                                    <tr style="background:#0f172a;color:#64748b;font-size:.58rem;text-transform:uppercase;font-weight:900;">
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;text-align:left;">Keputusan</th>
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;">Poin</th>
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;">Jml</th>
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;">Total</th>
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;text-align:left;">Keputusan</th>
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;">Poin</th>
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;">Jml</th>
                                        <th style="padding:.35rem .5rem;border:1px solid #334155;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {#each categories as cat}
                                        {@const isBatsu = cat.key.includes("batsu")}
                                        {@const akaVal = aka[cat.key] || 0}
                                        {@const shiroVal = shiro[cat.key] || 0}
                                        <tr style="border-bottom:1px solid #334155;{isBatsu ? 'color:#f87171;' : ''}">
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:left;">
                                                <span style="font-size:.6rem;font-weight:900;color:{isBatsu ? '#f87171' : '#94a3b8'};">{cat.label}</span>
                                                <span style="margin-left:.3rem;">{cat.desc}</span>
                                            </td>
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:center;color:#64748b;">{cat.val}</td>
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:center;font-weight:900;">{akaVal}</td>
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:center;font-weight:900;color:{isBatsu ? '#f87171' : '#f1f5f9'};">
                                                {isBatsu ? "-" : ""}{cat.val * akaVal}
                                            </td>
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:left;border-left:2px solid #334155;">
                                                <span style="font-size:.6rem;font-weight:900;color:{isBatsu ? '#f87171' : '#94a3b8'};">{cat.label}</span>
                                                <span style="margin-left:.3rem;">{cat.desc}</span>
                                            </td>
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:center;color:#64748b;">{cat.val}</td>
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:center;font-weight:900;">{shiroVal}</td>
                                            <td style="padding:.3rem .5rem;border:1px solid #334155;text-align:center;font-weight:900;color:{isBatsu ? '#f87171' : '#f1f5f9'};">
                                                {isBatsu ? "-" : ""}{cat.val * shiroVal}
                                            </td>
                                        </tr>
                                    {/each}
                                    <tr style="background:#0f172a;font-weight:900;font-size:.8rem;">
                                        <td colspan="3" style="padding:.4rem .5rem;border:1px solid #334155;text-align:right;color:#94a3b8;">TOTAL MERAH</td>
                                        <td style="padding:.4rem .5rem;border:1px solid #334155;text-align:center;font-size:1rem;color:#ef4444;">{totalRed}</td>
                                        <td colspan="3" style="padding:.4rem .5rem;border:1px solid #334155;text-align:right;color:#94a3b8;border-left:2px solid #334155;">TOTAL PUTIH</td>
                                        <td style="padding:.4rem .5rem;border:1px solid #334155;text-align:center;font-size:1rem;color:#94a3b8;">{totalWhite}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Signatures preview (screen) -->
                        {#if sigs.koordinator || sigs.wasit || sigs.manager_red || sigs.manager_white}
                            <div style="margin-top:.75rem;padding:.75rem;background:#0f172a;border:1px solid #334155;border-radius:.6rem;">
                                <div style="font-size:.6rem;font-weight:900;color:#64748b;text-transform:uppercase;letter-spacing:.07em;margin-bottom:.5rem;">Tanda Tangan Tersimpan</div>
                                <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                                    {#each [
                                        { key: "koordinator", label: "Koordinator", color: "#f97316" },
                                        { key: "wasit", label: "Wasit Utama", color: "#8b5cf6" },
                                        { key: "manager_red", label: "Manajer Merah", color: "#ef4444" },
                                        { key: "manager_white", label: "Manajer Putih", color: "#94a3b8" },
                                    ] as s}
                                        {@const sigObj = sigs[s.key]}
                                        {#if sigObj?.name}
                                            <div style="background:#1e293b;border:1px solid #334155;border-radius:.5rem;padding:.4rem .7rem;font-size:.65rem;">
                                                <div style="font-weight:900;color:{s.color};text-transform:uppercase;font-size:.55rem;">{s.label}</div>
                                                <div style="color:#f1f5f9;font-weight:700;">{sigObj.name}</div>
                                                {#if sigObj.signature}
                                                    <img src={sigObj.signature} alt="TTD" style="height:32px;margin-top:3px;object-fit:contain;" />
                                                {:else}
                                                    <div style="color:#64748b;font-size:.6rem;font-style:italic;">— Tanda tangan belum diisi —</div>
                                                {/if}
                                            </div>
                                        {/if}
                                    {/each}
                                    {#if sigs.panitera}
                                        {#each (Array.isArray(sigs.panitera) ? sigs.panitera : [sigs.panitera]) as pan, i}
                                            {#if pan?.name}
                                                <div style="background:#1e293b;border:1px solid #334155;border-radius:.5rem;padding:.4rem .7rem;font-size:.65rem;">
                                                    <div style="font-weight:900;color:#22d3ee;text-transform:uppercase;font-size:.55rem;">Panitera {i > 0 ? i+1 : ""}</div>
                                                    <div style="color:#f1f5f9;font-weight:700;">{pan.name}</div>
                                                    {#if pan.signature}
                                                        <img src={pan.signature} alt="TTD" style="height:32px;margin-top:3px;object-fit:contain;" />
                                                    {:else}
                                                        <div style="color:#64748b;font-size:.6rem;font-style:italic;">— Tanda tangan belum diisi —</div>
                                                    {/if}
                                                </div>
                                            {/if}
                                        {/each}
                                    {/if}
                                </div>
                            </div>
                        {/if}
                    {/if}
                </div>
            {/each}

            {#if allNodes.length === 0}
                <div class="screen-card" style="text-align:center;padding:2rem;color:#64748b;">
                    Belum ada data bracket untuk pertandingan ini.
                </div>
            {:else if allNodes.filter(n => n.result).length === 0}
                <div class="screen-card" style="text-align:center;padding:2rem;color:#64748b;">
                    Belum ada babak yang selesai.
                </div>
            {/if}
        {/if}
    </div>
</div>

<!-- ============================================================
     PRINT LAYOUT — shown only when printing
     ============================================================ -->
{#if matchData}
    <div style="display:none;" class="print-area">
        {#each displayNodes as node}
            {@const meta = getMeta(node.result)}
            {@const aka = meta.scoringAka || {}}
            {@const shiro = meta.scoringShiro || {}}
            {@const sigs = meta.signatures || {}}
            {@const totalRed = node.result ? (node.result.score_red ?? calcScore(aka)) : 0}
            {@const totalWhite = node.result ? (node.result.score_blue ?? calcScore(shiro)) : 0}
            {@const winner = node.result?.winner_color === "athlete1" ? "MERAH" : "PUTIH"}

            <div class="print-page">
                <!-- Document Header -->
                <div class="print-header">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                        <div>
                            <div class="print-title">Rekap Penilaian Randori</div>
                            <div class="print-subtitle">
                                {matchData.matchNumber.name}
                                {#if matchData.matchNumber.age_group} — {matchData.matchNumber.age_group.name}{/if}
                                {#if matchData.matchNumber.gender} ({formatGender(matchData.matchNumber.gender)}){/if}
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:9pt;font-weight:900;text-transform:uppercase;letter-spacing:.04em;">{node.label}</div>
                            <div style="font-size:7pt;color:#64748b;font-family:monospace;">NODE: {node.nodeKey}</div>
                        </div>
                    </div>
                </div>

                <!-- Athletes -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
                    <div class="athlete-card">
                        <div class="athlete-dot dot-red"></div>
                        <div>
                            <div class="athlete-label" style="color:#dc2626;">PITA MERAH (AKA)</div>
                            <div class="athlete-name">{node.nodeData.athlete1?.name || "BYE"}</div>
                            {#if node.nodeData.athlete1?.contingent}
                                <div class="athlete-cont">{node.nodeData.athlete1.contingent}</div>
                            {/if}
                        </div>
                    </div>
                    <div class="athlete-card">
                        <div class="athlete-dot dot-white"></div>
                        <div>
                            <div class="athlete-label" style="color:#475569;">PITA PUTIH (SHIRO)</div>
                            <div class="athlete-name">{node.nodeData.athlete2?.name || "BYE"}</div>
                            {#if node.nodeData.athlete2?.contingent}
                                <div class="athlete-cont">{node.nodeData.athlete2.contingent}</div>
                            {/if}
                        </div>
                    </div>
                </div>

                <!-- Score Table -->
                <table class="score-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="header-red">PITA MERAH (AKA)</th>
                            <th colspan="4" class="header-white" style="border-left:2px solid #94a3b8;">PITA PUTIH (SHIRO)</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Keputusan</th>
                            <th>Poin</th>
                            <th>Jml</th>
                            <th>Total</th>
                            <th style="text-align:left;border-left:2px solid #94a3b8;">Keputusan</th>
                            <th>Poin</th>
                            <th>Jml</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {#each categories as cat}
                            {@const isBatsu = cat.key.includes("batsu")}
                            {@const akaVal = aka[cat.key] || 0}
                            {@const shiroVal = shiro[cat.key] || 0}
                            <tr class={isBatsu ? "batsu-row" : ""}>
                                <td style="text-align:left;"><strong>{cat.label}</strong> {cat.desc}</td>
                                <td>{cat.val}</td>
                                <td><strong>{akaVal}</strong></td>
                                <td><strong>{isBatsu ? "-" : ""}{cat.val * akaVal}</strong></td>
                                <td style="text-align:left;border-left:2px solid #94a3b8;"><strong>{cat.label}</strong> {cat.desc}</td>
                                <td>{cat.val}</td>
                                <td><strong>{shiroVal}</strong></td>
                                <td><strong>{isBatsu ? "-" : ""}{cat.val * shiroVal}</strong></td>
                            </tr>
                        {/each}
                        <tr class="total-row">
                            <td colspan="3" style="text-align:right;padding:5px 6px;">TOTAL SKOR MERAH (AKA)</td>
                            <td style="font-size:13pt;color:#dc2626;padding:5px 6px;">{totalRed}</td>
                            <td colspan="3" style="text-align:right;border-left:2px solid #94a3b8;padding:5px 6px;">TOTAL SKOR PUTIH (SHIRO)</td>
                            <td style="font-size:13pt;color:#374151;padding:5px 6px;">{totalWhite}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Winner Banner -->
                {#if node.result}
                    <div class="winner-row" style="margin-top:6px;border-radius:4px;">
                        🏆 PEMENANG: PITA <strong style="color:{node.result.winner_color === 'athlete1' ? '#dc2626' : '#374151'};">{winner}</strong>
                        — {node.result.winner_color === "athlete1" ? node.nodeData.athlete1?.name : node.nodeData.athlete2?.name}
                    </div>
                {/if}

                <!-- Signatures -->
                <div style="margin-top:12px;border-top:1.5px solid #111;padding-top:10px;">
                    <div style="font-size:9pt;font-weight:900;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">
                        Pengesahan &amp; Tanda Tangan
                    </div>
                    <div class="sig-grid">
                        <!-- Koordinator -->
                        <div class="sig-box">
                            <div class="sig-label">Koordinator Lapangan</div>
                            <div class="sig-name">{sigs.koordinator?.name || "________________________"}</div>
                            {#if sigs.koordinator?.signature}
                                <img class="sig-img" src={sigs.koordinator.signature} alt="TTD Koordinator" />
                            {:else}
                                <div class="sig-empty"></div>
                            {/if}
                            <div class="sig-line">Tanda Tangan</div>
                        </div>

                        <!-- Wasit -->
                        <div class="sig-box">
                            <div class="sig-label">Wasit Utama</div>
                            <div class="sig-name">{sigs.wasit?.name || "________________________"}</div>
                            {#if sigs.wasit?.signature}
                                <img class="sig-img" src={sigs.wasit.signature} alt="TTD Wasit" />
                            {:else}
                                <div class="sig-empty"></div>
                            {/if}
                            <div class="sig-line">Tanda Tangan</div>
                        </div>

                        <!-- Panitera -->
                        {#if sigs.panitera}
                            {#each (Array.isArray(sigs.panitera) ? sigs.panitera : [sigs.panitera]) as pan, pi}
                                <div class="sig-box">
                                    <div class="sig-label">Koordinator Panitera{pi > 0 ? " " + (pi + 1) : ""}</div>
                                    <div class="sig-name">{pan?.name || "________________________"}</div>
                                    {#if pan?.signature}
                                        <img class="sig-img" src={pan.signature} alt="TTD Panitera" />
                                    {:else}
                                        <div class="sig-empty"></div>
                                    {/if}
                                    <div class="sig-line">Tanda Tangan</div>
                                </div>
                            {/each}
                        {:else}
                            <div class="sig-box">
                                <div class="sig-label">Koordinator Panitera</div>
                                <div class="sig-name">________________________</div>
                                <div class="sig-empty"></div>
                                <div class="sig-line">Tanda Tangan</div>
                            </div>
                        {/if}

                        <!-- Manager Red -->
                        <div class="sig-box" style="border-color:#dc2626;">
                            <div class="sig-label" style="color:#dc2626;">Manajer Pita Merah (AKA)</div>
                            <div class="sig-name">{sigs.manager_red?.name || "________________________"}</div>
                            {#if sigs.manager_red?.signature}
                                <img class="sig-img" src={sigs.manager_red.signature} alt="TTD Manajer Merah" />
                            {:else}
                                <div class="sig-empty"></div>
                            {/if}
                            <div class="sig-line">Tanda Tangan</div>
                        </div>

                        <!-- Manager White -->
                        <div class="sig-box">
                            <div class="sig-label" style="color:#475569;">Manajer Pita Putih (SHIRO)</div>
                            <div class="sig-name">{sigs.manager_white?.name || "________________________"}</div>
                            {#if sigs.manager_white?.signature}
                                <img class="sig-img" src={sigs.manager_white.signature} alt="TTD Manajer Putih" />
                            {:else}
                                <div class="sig-empty"></div>
                            {/if}
                            <div class="sig-line">Tanda Tangan</div>
                        </div>
                    </div>
                </div>
            </div>
        {/each}
    </div>
{/if}

<style>
    @media print {
        .screen-shell {
            display: none !important;
        }
        .print-area {
            display: block !important;
        }
    }
    @media screen {
        .print-area {
            display: none !important;
        }
    }
</style>
