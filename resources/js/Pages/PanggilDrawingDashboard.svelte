<script>
    import { onMount } from "svelte";
    import { router } from "@inertiajs/svelte";
    import { postJson } from "../lib/api";

    // States
    let drawings = $state({
        data: [],
        total: 0,
        current_page: 1,
        last_page: 1,
    });
    let courts = $state([]);
    let sessions = $state([]);
    let rundowns = $state([]);
    let pools = $state([]);
    let contingents = $state([]);
    let rounds = $state([]);
    let ageGroups = $state([]);
    let matchNumbers = $state([]);
    let allReferees = $state([]);
    let showRefereeModal = $state(false);
    let assigningCourtId = $state(null);
    let assigningCourtName = $state("");
    let assigningRundownId = $state("");
    let assigningSessionId = $state("");
    let searchReferee = $state("");
    let selectedReferees = $state([]);

    // Filters
    let search = $state("");
    let filterCourt = $state("");
    let filterSession = $state("");
    let filterRundown = $state("");
    let filterPool = $state("");
    let filterRound = $state("");
    let filterType = $state("");
    let filterContingent = $state("");
    let filterAgeGroup = $state("");
    let filterMatchNumber = $state("");
    let filterGender = $state("");

    // Debouncing helper
    let searchTimeout;

    // Filter changes trigger refresh
    $effect(() => {
        const _ = {
            filterCourt,
            filterSession,
            filterRundown,
            filterPool,
            filterRound,
            filterType,
            filterContingent,
            filterAgeGroup,
            filterMatchNumber,
            filterGender,
        };
        fetchState();
    });

    function handleSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchState();
        }, 300);
    }

    async function fetchState(page = 1) {
        try {
            const queryParams = new URLSearchParams({
                page,
                search,
                filterCourt,
                filterSession,
                filterRundown,
                filterPool,
                filterRound,
                filterType,
                filterContingent,
                filterAgeGroup,
                filterMatchNumber,
                filterGender,
            });
            const res = await fetch(
                `/admin/api/scoring/panggil-drawing-state?${queryParams.toString()}`,
            );
            const data = await res.json();
            if (data) {
                drawings = data.drawings;
                courts = data.courts;
                sessions = data.sessions;
                rundowns = data.rundowns;
                pools = data.pools;
                contingents = data.contingents;
                rounds = data.rounds;
                ageGroups = data.ageGroups;
                matchNumbers = data.matchNumbers;
                allReferees = data.allReferees || [];
            }
        } catch (e) {
            console.error("Error fetching panggil drawing state:", e);
        }
    }

    // Actions
    async function activateMatch(drawingId) {
        if (!confirm("Panggil pertandingan/kenshi ini ke lapangan?")) return;
        try {
            const res = await postJson("/admin/api/scoring/activate-match", { drawing_id: drawingId });
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                fetchState(drawings.current_page);
            } else {
                alert(data.message || "Gagal mengaktifkan pertandingan.");
            }
        } catch (e) {
        }
    }

    function resetFilters() {
        search = "";
        filterCourt = "";
        filterSession = "";
        filterRundown = "";
        filterPool = "";
        filterRound = "";
        filterType = "";
        filterContingent = "";
        filterAgeGroup = "";
        filterMatchNumber = "";
        filterGender = "";
        fetchState(1);
    }

    function formatDate(dateStr) {
        if (!dateStr) return "-";
        try {
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            return date.toLocaleDateString("id-ID", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        } catch (e) {
            return dateStr;
        }
    }

    function getPaginationRange(current, last, delta = 1) {
        const range = [];
        const rangeWithDots = [];
        let l;

        for (let i = 1; i <= last; i++) {
            if (
                i === 1 ||
                i === last ||
                (i >= current - delta && i <= current + delta)
            ) {
                range.push(i);
            }
        }

        for (let i of range) {
            if (l) {
                if (i - l === 2) {
                    rangeWithDots.push(l + 1);
                } else if (i - l > 2) {
                    rangeWithDots.push("...");
                }
            }
            rangeWithDots.push(i);
            l = i;
        }

        return rangeWithDots;
    }

    async function clearCourt(courtId) {
        if (!confirm("Kosongkan lapangan ini?")) return;
        try {
            const res = await postJson("/admin/api/scoring/clear-court", { court_id: courtId });
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                fetchState(drawings.current_page);
            }
        } catch (e) {
        }
    }

    async function resetActiveReferees(courtId) {
        if (!confirm("Kosongkan panel wasit aktif untuk lapangan ini?")) return;
        try {
            const res = await postJson(
                "/admin/api/scoring/reset-active-referees",
                { court_id: courtId },
            );
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                fetchState(drawings.current_page);
            }
        } catch (e) {
        }
    }

    function openRefereeModal(
        courtId,
        courtName,
        activeRundownId = "",
        activeSessionId = "",
    ) {
        assigningCourtId = courtId;
        assigningCourtName = courtName;
        assigningRundownId = activeRundownId ? String(activeRundownId) : "";
        assigningSessionId = activeSessionId ? String(activeSessionId) : "";
        searchReferee = "";
        selectedReferees = [];
        showRefereeModal = true;
        loadExistingReferees();
    }

    $effect(() => {
        if (showRefereeModal && (assigningRundownId || assigningSessionId)) {
            loadExistingReferees();
        }
    });

    async function loadExistingReferees() {
        if (assigningRundownId && assigningSessionId && assigningCourtId) {
            try {
                const queryParams = new URLSearchParams({
                    rundown_id: assigningRundownId,
                    session_time_id: assigningSessionId,
                    court_id: assigningCourtId,
                });
                const res = await fetch(
                    `/api/svelte-monitor/referee/court/${assigningCourtId}/state?${queryParams.toString()}`,
                );
                const data = await res.json();
                if (data && data.referees) {
                    selectedReferees = data.referees.map((r) =>
                        String(r.referee_id),
                    );
                } else {
                    selectedReferees = [];
                }
            } catch (e) {
            }
        } else {
            selectedReferees = [];
        }
    }

    function toggleReferee(refereeId) {
        const index = selectedReferees.indexOf(String(refereeId));
        if (index > -1) {
            selectedReferees = selectedReferees.filter(
                (id) => id !== String(refereeId),
            );
        } else {
            if (selectedReferees.length < 5) {
                selectedReferees = [...selectedReferees, String(refereeId)];
            } else {
                alert("Maksimal memilih 5 wasit.");
            }
        }
    }

    async function syncFromSchedule() {
        if (!assigningRundownId || !assigningSessionId) {
            alert("Pilih Rundown dan Sesi terlebih dahulu.");
            return;
        }
        try {
            const queryParams = new URLSearchParams({
                rundown_id: assigningRundownId,
                session_time_id: assigningSessionId,
            });
            const res = await fetch(
                `/api/svelte-monitor/referee/court/${assigningCourtId}/state?${queryParams.toString()}`,
            );
            const data = await res.json();
            if (data && data.referees && data.referees.length > 0) {
                selectedReferees = data.referees.map((r) =>
                    String(r.referee_id),
                );
                alert(
                    `Berhasil menarik ${selectedReferees.length} wasit dari jadwal.`,
                );
            } else {
                alert("Jadwal wasit untuk sesi ini tidak ditemukan.");
            }
        } catch (e) {
        }
    }

    async function saveRefereeAssignment() {
        if (selectedReferees.length !== 5) {
            alert("Wajib memilih tepat 5 wasit.");
            return;
        }
        try {
            const res = await postJson(
                "/admin/api/scoring/save-referee-assignment",
                {
                        court_id: assigningCourtId,
                        rundown_id: assigningRundownId,
                        session_time_id: assigningSessionId,
                        referees: selectedReferees,
                },
            );
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                showRefereeModal = false;
                fetchState(drawings.current_page);
            } else {
                alert(data.message || "Gagal menyimpan penugasan.");
            }
        } catch (e) {
        }
    }

    async function resetCourtReferees() {
        if (!confirm("Kosongkan penugasan wasit untuk sesi ini?")) return;
        try {
            const res = await postJson("/admin/api/scoring/reset-court-referees", {
                    court_id: assigningCourtId,
                    rundown_id: assigningRundownId,
                    session_time_id: assigningSessionId,
            });
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                showRefereeModal = false;
                fetchState(drawings.current_page);
            } else {
                alert(data.message || "Gagal mengosongkan penugasan.");
            }
        } catch (e) {
        }
    }

    let filteredRefereesList = $derived.by(() => {
        if (!searchReferee) return allReferees;
        return allReferees.filter(
            (r) =>
                (r.user?.name || r.name || "")
                    .toLowerCase()
                    .includes(searchReferee.toLowerCase()) ||
                (r.license_number || "")
                    .toLowerCase()
                    .includes(searchReferee.toLowerCase()) ||
                (r.certification_level || "")
                    .toLowerCase()
                    .includes(searchReferee.toLowerCase()),
        );
    });

    onMount(() => {
        fetchState();
    });
</script>

<div class="tm-page" style="padding: 24px; background: var(--paper);">
    <!-- HEADER -->
    <div
        class="tm-hdr"
        style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;"
    >
        <div>
            <h2
                style="font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; margin: 0 0 4px;"
            >
                Dashboard Panitera
            </h2>
            <p style="font-size: 12px; color: var(--smoke); margin: 0;">
                Panggil Drawing per Lapangan — Urut Waktu & Kunci jika Sudah
                Dinilai
            </p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <button
                onclick={resetFilters}
                class="btn-gen ghost"
                style="padding: 8px 16px; border: 1px solid var(--paper2); background: #fff; border-radius: 8px; font-weight: 600; cursor: pointer;"
            >
                <i class="fas fa-rotate-left"></i> Reset Filter
            </button>
            <a
                href="/admin/new-dashboard"
                class="btn-gen ghost"
                style="padding: 8px 16px; border: 1px solid var(--paper2); background: #fff; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;"
            >
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- ACTIVE COURT CARDS -->
    <div class="tm-stats">
        {#each courts as courtCard}
            {@const ad = courtCard.active_drawing}
            {@const adMatch = courtCard.active_match}
            {@const adPool = ad?.pool}
            {@const adSession = ad?.session_time}
            {@const adContingent = ad?.registration?.contingent}
            {@const adType = ad?.draft_type || adMatch?.draft_type}
            {@const isActive = !!courtCard.active_match_id}
            <div class="tm-stat-pill">
                <div class="court-status">
                    <span class="court-title">Panel {courtCard.name}</span>
                    {#if isActive}
                        <div style="display:flex; align-items:center; gap:8px;">
                            <button
                                onclick={() => clearCourt(courtCard.id)}
                                title="Kosongkan Lapangan"
                                style="width:24px;height:24px;border-radius:50%;background:rgba(192,57,43,.1);color:var(--red);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                            >
                                <i class="fas fa-times" style="font-size:10px;"
                                ></i>
                            </button>
                            <span
                                style="width:10px;height:10px;border-radius:50%;background:#27ae60;animation:pulse 2s infinite;"
                            ></span>
                        </div>
                    {:else}
                        <span
                            style="width:10px;height:10px;border-radius:50%;background:var(--paper2);"
                        ></span>
                    {/if}
                </div>

                <div style="min-height: 50px; margin-bottom: 10px;">
                    {#if isActive && adMatch}
                        <div
                            class="court-match-name"
                            style="display:flex; align-items:center; justify-content:space-between; gap:8px;"
                        >
                            <div
                                style="display:flex; align-items:center; gap:6px; overflow:hidden; text-overflow:ellipsis; flex:1;"
                            >
                                <span
                                    class="draw-badge {adType === 'randori'
                                        ? 'randori'
                                        : 'embu'}">{adType ?? "?"}</span
                                >
                                <span
                                    style="overflow:hidden; text-overflow:ellipsis;"
                                    title={adMatch.name}>{adMatch.name}</span
                                >
                            </div>
                            <div style="display:flex; gap:4px; flex-shrink:0;">
                                <a
                                    href={`/admin/new-scoring/${adType}/${adMatch.id}?round=${ad?.round ?? ""}&pool_id=${adPool?.id ?? ""}&from=panggil-drawing`}
                                    class="btn-gen primary"
                                    style="padding:2px 6px; font-size:10px; height:22px; display:inline-flex; align-items:center; justify-content:center;"
                                    title="Input Nilai"
                                >
                                    <i
                                        class="fas fa-edit"
                                        style="font-size:10px;"
                                    ></i>
                                </a>
                                <a
                                    href={`/admin/arbitrase/scoring/monitor-hasil/match/${adMatch.id}?round=${ad?.round ?? ""}&pool_id=${adPool?.id ?? ""}`}
                                    target="_blank"
                                    class="btn-gen ghost"
                                    style="padding:2px 6px; font-size:10px; height:22px; display:inline-flex; align-items:center; justify-content:center;"
                                    title="Monitor Hasil"
                                >
                                    <i class="fas fa-tv" style="font-size:10px;"
                                    ></i>
                                </a>
                            </div>
                        </div>
                        {#if adContingent}
                            <div class="court-match-contingent">
                                <i
                                    class="fas fa-shield-alt"
                                    style="margin-right:4px;"
                                ></i>{adContingent.name}
                            </div>
                        {/if}
                        <div
                            style="display:flex; gap:4px; flex-wrap:wrap; margin-top:6px;"
                        >
                            {#if adPool}
                                <span
                                    class="draw-badge"
                                    style="background:rgba(41,128,185,.1);color:#2980b9;"
                                    >Pool {adPool.name}</span
                                >
                            {/if}
                            {#if adSession}
                                <span
                                    class="draw-badge"
                                    style="background:rgba(39,174,96,.1);color:#27ae60;"
                                    >{adSession.name}</span
                                >
                            {/if}
                        </div>
                    {:else}
                        <div
                            style="font-size:12px; color:var(--smoke); font-style:italic; padding-top:10px;"
                        >
                            KOSONG (Idle)
                        </div>
                    {/if}
                </div>

                <div class="court-ref-list">
                    <div
                        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;"
                    >
                        <span
                            style="font-size:9px; font-weight:700; color:var(--smoke); text-transform:uppercase;"
                            >Panel Wasit</span
                        >
                        {#if courtCard.current_referees && courtCard.current_referees.length > 0}
                            <button
                                onclick={() =>
                                    resetActiveReferees(courtCard.id)}
                                style="border:none;background:none;color:var(--red);font-size:9px;font-weight:700;cursor:pointer;text-transform:uppercase;"
                                >Reset</button
                            >
                        {/if}
                    </div>
                    {#if courtCard.current_referees && courtCard.current_referees.length > 0}
                        {#each courtCard.current_referees as sch}
                            <div class="court-ref-item">
                                <span
                                    style="width:16px;height:16px;border-radius:4px;background:var(--ink);color:#fff;display:flex;align-items:center;justify-content:center;font-size:8px;"
                                    >{sch.judge_index}</span
                                >
                                <span
                                    style="flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                    >{sch.referee?.name || ""}</span
                                >
                            </div>
                        {/each}
                    {:else}
                        <div
                            style="font-size:10px; color:var(--smoke); font-style:italic;"
                        >
                            Belum ada wasit
                        </div>
                    {/if}
                </div>

                <div
                    class="court-links"
                    style="grid-template-columns: repeat(2, 1fr);"
                >
                    <a
                        href={`/admin/arbitrase/scoring/monitor/${courtCard.id}`}
                        target="_blank"
                        class="court-link"
                        style="background:var(--ink);color:#fff;border-color:var(--ink);"
                        >Panggilan</a
                    >
                    <a
                        href={`/admin/arbitrase/scoring/monitor-hasil/court/${courtCard.id}`}
                        target="_blank"
                        class="court-link">Hasil</a
                    >
                    <a
                        href={`/admin/arbitrase/scoring/monitor-timer/court/${courtCard.id}`}
                        target="_blank"
                        class="court-link">Timer</a
                    >
                    <a
                        href={`/admin/arbitrase/scoring/monitor-rekapitulasi-hasil/court/${courtCard.id}`}
                        target="_blank"
                        class="court-link">Rekapitulasi</a
                    >
                    <div style="display:flex; gap:4px; grid-column: span 2;">
                        <a
                            href={`/admin/arbitrase/scoring/monitor-referee/court/${courtCard.id}`}
                            target="_blank"
                            class="court-link"
                            style="flex:1;">Wasit</a
                        >
                        <button
                            onclick={() =>
                                openRefereeModal(
                                    courtCard.id,
                                    courtCard.name,
                                    courtCard.active_drawing?.rundown_id,
                                    courtCard.active_drawing?.session_time_id,
                                )}
                            class="court-link"
                            style="padding:4px 8px;cursor:pointer;"
                            ><i class="fas fa-user-plus"></i></button
                        >
                    </div>
                </div>
            </div>
        {/each}
    </div>

    <!-- TWO-COLUMN LAYOUT -->
    <div
        class="tm-layout"
        style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; align-items: start;"
    >
        <!-- LEFT: FILTER PANEL -->
        <div
            class="tm-left"
            style="background: #fff; border: 1px solid var(--paper2); border-radius: 16px; padding: 16px; display: flex; flex-direction: column; gap: 14px;"
        >
            <div
                style="font-family: 'Cinzel', serif; font-size: 12px; font-weight: 700; border-bottom: 1px solid var(--paper2); padding-bottom: 10px; display: flex; align-items: center; gap: 8px;"
            >
                <i class="fa-solid fa-filter" style="color: var(--ink);"></i>
                Filter Pencarian
            </div>

            <div class="form-group">
                <input
                    type="text"
                    bind:value={search}
                    oninput={handleSearch}
                    style="width: 100%; padding: 8px 12px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px;"
                    placeholder="Cari atlet / kontingen..."
                />
            </div>

            <div class="form-group">
                <label
                    style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--smoke); display: block; margin-bottom: 4px;"
                    >Kategori</label
                >
                <select
                    bind:value={filterType}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Kategori</option>
                    <option value="embu">Embu</option>
                    <option value="randori">Randori</option>
                </select>
            </div>

            <div class="form-group">
                <label
                    style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--smoke); display: block; margin-bottom: 4px;"
                    >Gender</label
                >
                <select
                    bind:value={filterGender}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Gender</option>
                    <option value="Male">Laki-laki (Male)</option>
                    <option value="Female">Perempuan (Female)</option>
                    <option value="Mix">Campuran (Mix)</option>
                </select>
            </div>

            <div class="form-group">
                <label
                    style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--smoke); display: block; margin-bottom: 4px;"
                    >Kelompok Umur</label
                >
                <select
                    bind:value={filterAgeGroup}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Kategori Umur</option>
                    {#each ageGroups as ag}
                        <option value={ag.id}>{ag.name}</option>
                    {/each}
                </select>
            </div>

            <div class="form-group">
                <label
                    style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--smoke); display: block; margin-bottom: 4px;"
                    >Nomor Pertandingan</label
                >
                <select
                    bind:value={filterMatchNumber}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Nomor Match</option>
                    {#each matchNumbers as mn}
                        <option value={mn.id}
                            >{mn.name} - {mn.age_group?.name} ({mn.gender})</option
                        >
                    {/each}
                </select>
            </div>

            <div class="form-group">
                <label
                    style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--smoke); display: block; margin-bottom: 4px;"
                    >Kontingen</label
                >
                <select
                    bind:value={filterContingent}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Kontingen</option>
                    {#each contingents as contingent}
                        <option value={contingent.id}>{contingent.name}</option>
                    {/each}
                </select>
            </div>

            <div
                class="form-group"
                style="border-top: 1px dashed var(--paper2); padding-top: 12px; display: flex; flex-direction: column; gap: 10px;"
            >
                <label
                    style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--smoke);"
                    >Filter Jadwal</label
                >

                <select
                    bind:value={filterCourt}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Lapangan</option>
                    {#each courts as court}
                        <option value={court.id}>{court.name}</option>
                    {/each}
                </select>

                <select
                    bind:value={filterSession}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Sesi</option>
                    {#each sessions as session}
                        <option value={session.id}>{session.name}</option>
                    {/each}
                </select>

                <select
                    bind:value={filterRundown}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Rundown</option>
                    {#each rundowns as rundown}
                        <option value={rundown.id}
                            >{rundown.name || rundown.date}</option
                        >
                    {/each}
                </select>

                <select
                    bind:value={filterPool}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Pool</option>
                    {#each pools as pool}
                        <option value={pool.id}>{pool.name}</option>
                    {/each}
                </select>

                <select
                    bind:value={filterRound}
                    style="width: 100%; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 12px; background: #fff;"
                >
                    <option value="">Semua Babak</option>
                    {#each rounds as rnd}
                        <option value={rnd}>{rnd}</option>
                    {/each}
                </select>
            </div>
        </div>

        <!-- RIGHT: CONTENT TABLE -->
        <div class="tm-right">
            <div
                class="tm-card"
                style="background: #fff; border: 1px solid var(--paper2); border-radius: 16px; overflow: hidden;"
            >
                <div
                    class="tm-card-head"
                    style="padding: 16px 20px; border-bottom: 1px solid var(--paper2);"
                >
                    <h3
                        style="font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; margin: 0 0 2px;"
                    >
                        Jadwal Drawings Individual
                    </h3>
                    <p style="font-size: 11px; color: var(--smoke); margin: 0;">
                        {drawings.total} data ditemukan
                    </p>
                </div>

                <div class="tm-card-body" style="overflow-x: auto;">
                    <table
                        class="draw-table"
                        style="width: 100%; border-collapse: collapse;"
                    >
                        <thead>
                            <tr style="border-bottom: 2px solid var(--paper2);">
                                <th
                                    style="padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase; color: var(--smoke); font-weight: 700;"
                                    >#</th
                                >
                                <th
                                    style="padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase; color: var(--smoke); font-weight: 700;"
                                    >Waktu & Tanggal</th
                                >
                                <th
                                    style="padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase; color: var(--smoke); font-weight: 700;"
                                    >Nomor Match / Kenshi</th
                                >
                                <th
                                    style="padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase; color: var(--smoke); font-weight: 700;"
                                    >Kontingen / Pool</th
                                >
                                <th
                                    style="padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase; color: var(--smoke); font-weight: 700;"
                                    >Jadwal Lapangan</th
                                >
                                <th
                                    style="padding: 12px; text-align: center; font-size: 10px; text-transform: uppercase; color: var(--smoke); font-weight: 700; width: 150px;"
                                    >Aksi</th
                                >
                            </tr>
                        </thead>
                        <tbody>
                            {#each drawings.data as drawing}
                                {@const mn = drawing.match_number}
                                {@const pool = drawing.pool}
                                {@const court = drawing.court}
                                {@const session = drawing.session_time}
                                {@const rundown = drawing.rundown}
                                {@const isRandori =
                                    drawing.draft_type === "randori"}
                                {@const detailRoute = `/admin/new-scoring/${drawing.draft_type}/${mn?.id ?? ""}?round=${drawing.round ?? ""}&pool_id=${pool?.id ?? ""}&from=panggil-drawing`}

                                <tr
                                    style="border-bottom: 1px solid var(--paper2); background: {drawing.has_score
                                        ? '#fafafa'
                                        : '#fff'};"
                                >
                                    <td style="padding: 12px; font-weight: 700;"
                                        >{drawing.sequence_number ?? "-"}</td
                                    >
                                    <td style="padding: 12px;">
                                        <div
                                            style="font-weight: 700; color: var(--red); font-size: 13px;"
                                        >
                                            {drawing.metadata?.start_time ||
                                                "-"} - {drawing.metadata
                                                ?.end_time || "-"}
                                        </div>
                                        <div
                                            style="font-size: 11px; color: var(--smoke); margin-top: 2px;"
                                        >
                                            {formatDate(
                                                rundown?.date ||
                                                    drawing.schedule_date,
                                            )}
                                        </div>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span
                                            class="draw-badge {isRandori
                                                ? 'randori'
                                                : 'embu'}"
                                            style="font-size: 9px; font-weight: 700; text-transform: uppercase; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-bottom: 4px; background: {isRandori
                                                ? 'rgba(192,57,43,.1)'
                                                : 'rgba(52,152,219,.1)'}; color: {isRandori
                                                ? 'var(--red)'
                                                : '#2980b9'};"
                                        >
                                            {drawing.draft_type}
                                        </span>
                                        <div
                                            style="font-weight: 700; color: var(--ink); font-size: 13px;"
                                        >
                                            {mn?.name || "—"}
                                        </div>
                                        {#if drawing.metadata?.athlete_name}
                                            <div
                                                style="font-size: 12px; font-weight: 600; color: var(--ink); margin-top: 2px;"
                                            >
                                                {drawing.metadata.athlete_name}
                                            </div>
                                        {/if}
                                        <div
                                            style="font-size: 11px; color: var(--smoke);"
                                        >
                                            {drawing.metadata?.match_id_code ||
                                                mn?.match_id ||
                                                "-"}
                                        </div>
                                    </td>
                                    <td style="padding: 12px;">
                                        <div
                                            style="font-weight: 700; color: var(--ink);"
                                        >
                                            {drawing.metadata?.contingent ||
                                                drawing.registration?.contingent
                                                    ?.name ||
                                                "—"}
                                        </div>
                                        <div
                                            style="display: flex; gap: 4px; margin-top: 4px;"
                                        >
                                            {#if pool}
                                                <span
                                                    class="draw-badge"
                                                    style="background: var(--paper); border: 1px solid var(--paper2); padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600;"
                                                >
                                                    Pool {pool.name}
                                                </span>
                                            {/if}
                                            {#if drawing.round}
                                                <span
                                                    class="draw-badge"
                                                    style="background: rgba(211,84,0,.1); color: #d35400; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600;"
                                                >
                                                    {drawing.round}
                                                </span>
                                            {/if}
                                        </div>
                                    </td>
                                    <td style="padding: 12px;">
                                        {#if court}
                                            <div
                                                style="font-size: 12px; font-weight: 700; color: var(--ink);"
                                            >
                                                <i
                                                    class="fas fa-vector-square"
                                                    style="color: var(--smoke); margin-right: 4px;"
                                                ></i>
                                                {court.name}
                                            </div>
                                        {:else}
                                            <div
                                                style="font-size: 12px; color: var(--smoke); font-style: italic;"
                                            >
                                                Lapangan (-)
                                            </div>
                                        {/if}
                                        {#if session}
                                            <div
                                                style="font-size: 11px; color: var(--smoke); margin-top: 2px;"
                                            >
                                                {session.name}
                                            </div>
                                        {/if}
                                    </td>
                                    <td
                                        style="padding: 12px; text-align: center; white-space: nowrap; vertical-align: middle;"
                                    >
                                        {#if drawing.has_score}
                                            <span
                                                style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; background: rgba(39,174,96,.1); color: #27ae60; font-size: 11px; font-weight: 700;"
                                                title="Penilaian Terkunci"
                                            >
                                                <i class="fas fa-lock"></i> Terkunci
                                            </span>
                                            <a
                                                href={`/admin/arbitrase/scoring/monitor-hasil/match/${mn?.id}?round=${drawing.round ?? ""}&pool_id=${pool?.id ?? ""}`}
                                                target="_blank"
                                                class="btn-gen ghost"
                                                title="Monitor Hasil"
                                                style="padding: 6px 10px; border: 1px solid var(--paper2); border-radius: 6px; color: var(--smoke); cursor: pointer; text-decoration: none; display: inline-block; vertical-align: middle; margin-left: 6px;"
                                            >
                                                <i class="fas fa-tv"></i>
                                            </a>
                                        {:else if mn}
                                            <a
                                                href={detailRoute}
                                                class="btn-gen primary"
                                                style="padding: 6px 10px; background: var(--ink); border: none; border-radius: 6px; color: #fff; cursor: pointer; text-decoration: none; display: inline-block; vertical-align: middle; margin-right: 4px;"
                                                title="Input Nilai"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a
                                                href={`/admin/arbitrase/scoring/monitor-hasil/match/${mn.id}?round=${drawing.round ?? ""}&pool_id=${pool?.id ?? ""}`}
                                                target="_blank"
                                                class="btn-gen ghost"
                                                style="padding: 6px 10px; border: 1px solid var(--paper2); border-radius: 6px; color: var(--smoke); cursor: pointer; text-decoration: none; display: inline-block; vertical-align: middle;"
                                                title="Monitor Hasil"
                                            >
                                                <i class="fas fa-tv"></i>
                                            </a>
                                        {/if}
                                    </td>
                                </tr>
                            {:else}
                                <tr>
                                    <td
                                        colspan="6"
                                        style="text-align: center; padding: 40px 20px; color: var(--smoke);"
                                    >
                                        <i
                                            class="fas fa-clipboard-list"
                                            style="font-size: 24px; margin-bottom: 12px; color: var(--paper2); display: block;"
                                        ></i>
                                        <p
                                            style="font-size: 13px; font-weight: 600; margin: 0;"
                                        >
                                            Tidak ada data
                                        </p>
                                    </td>
                                </tr>
                            {/each}
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                {#if drawings.last_page > 1}
                    <div
                        class="pagination-container"
                        style="display: flex; justify-content: center; align-items: center; gap: 4px; padding: 16px; border-top: 1px solid var(--paper2); background: #fafafa; flex-wrap: wrap;"
                    >
                        <button
                            disabled={drawings.current_page === 1}
                            onclick={() =>
                                fetchState(drawings.current_page - 1)}
                            style="padding: 6px 12px; border: 1px solid var(--paper2); background: #fff; border-radius: 6px; cursor: pointer;"
                        >
                            <span class="d-none-mobile">Sebelumnya</span>
                            <span class="d-only-mobile">&laquo;</span>
                        </button>

                        {#each getPaginationRange(drawings.current_page, drawings.last_page) as p}
                            {#if p === "..."}
                                <span
                                    style="padding: 6px 12px; color: var(--smoke);"
                                    >...</span
                                >
                            {:else}
                                <button
                                    onclick={() => fetchState(p)}
                                    style="padding: 6px 12px; border: 1px solid {drawings.current_page ===
                                    p
                                        ? 'var(--ink)'
                                        : 'var(--paper2)'}; background: {drawings.current_page ===
                                    p
                                        ? 'var(--ink)'
                                        : '#fff'}; color: {drawings.current_page ===
                                    p
                                        ? '#fff'
                                        : 'var(--ink)'}; border-radius: 6px; cursor: pointer;"
                                    >{p}</button
                                >
                            {/if}
                        {/each}

                        <button
                            disabled={drawings.current_page ===
                                drawings.last_page}
                            onclick={() =>
                                fetchState(drawings.current_page + 1)}
                            style="padding: 6px 12px; border: 1px solid var(--paper2); background: #fff; border-radius: 6px; cursor: pointer;"
                        >
                            <span class="d-none-mobile">Selanjutnya</span>
                            <span class="d-only-mobile">&raquo;</span>
                        </button>
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>

    {#if showRefereeModal}
        <div class="modal-overlay">
            <div class="modal-content">
                <div class="modal-hdr">
                    <h3>Penugasan Wasit - {assigningCourtName}</h3>
                    <button
                        onclick={() => (showRefereeModal = false)}
                        class="modal-close"
                        title="Close Modal"
                    ><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="ref-modal-grid">
                        <div>
                            <label
                                style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; margin-bottom:4px; display:block;"
                                >Tanggal / Hari</label
                            >
                            <select
                                bind:value={assigningRundownId}
                                style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--paper2); font-size:13px; outline:none;"
                            >
                                <option value="">Pilih Tanggal</option>
                                {#each rundowns as rd}
                                    <option value={String(rd.id)}
                                        >{rd.name || rd.date}</option
                                    >
                                {/each}
                            </select>
                        </div>
                        <div>
                            <label
                                style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; margin-bottom:4px; display:block;"
                                >Sesi / Shift</label
                            >
                            <select
                                bind:value={assigningSessionId}
                                style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--paper2); font-size:13px; outline:none;"
                            >
                                <option value="">Pilih Sesi</option>
                                {#each sessions as ss}
                                    <option value={String(ss.id)}
                                        >{ss.name}</option
                                    >
                                {/each}
                            </select>
                        </div>
                    </div>

                    <!-- Search wasit list -->
                    <div style="margin-bottom: 12px;">
                        <label
                            style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; margin-bottom:4px; display:block;"
                            >Cari / Pilih Wasit (Wajib 5)</label
                        >
                        <input
                            type="text"
                            bind:value={searchReferee}
                            class="tm-filter-input"
                            placeholder="Cari wasit..."
                            style="margin-bottom:8px;"
                        />
                        <div
                            style="max-height:150px; overflow-y:auto; border:1px solid var(--paper2); border-radius:8px; padding:6px; display:flex; flex-direction:column; gap:4px; background:#fafafa;"
                        >
                            {#each filteredRefereesList as ref}
                                {@const isSelected = selectedReferees.includes(
                                    String(ref.id),
                                )}
                                <button
                                    onclick={() => toggleReferee(ref.id)}
                                    style="text-align:left; background: {isSelected
                                        ? '#e8f4fd'
                                        : '#fff'}; border: 1px solid {isSelected
                                        ? '#2980b9'
                                        : '#e0dcd3'}; border-radius:6px; padding:6px 10px; font-size:12px; font-weight:600; cursor:pointer; display:flex; justify-content:space-between; align-items:center;"
                                >
                                    <span
                                        >{ref.user?.name || ref.name || "-"}
                                        <span
                                            style="font-size:9px; color:var(--smoke);"
                                            >({ref.certification_level ||
                                                "—"})</span
                                        ></span
                                    >
                                    {#if isSelected}
                                        <span
                                            style="font-size:10px; font-weight:bold; color:#2980b9;"
                                            >#{selectedReferees.indexOf(
                                                String(ref.id),
                                            ) + 1}</span
                                        >
                                    {/if}
                                </button>
                            {:else}
                                <span
                                    style="font-size:11px; color:var(--smoke); text-align:center; padding:10px;"
                                    >Wasit tidak ditemukan</span
                                >
                            {/each}
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;"
                        >
                            <span
                                style="font-size:11px; font-weight:700; color:var(--smoke); text-transform:uppercase;"
                                >Wasit Terpilih</span
                            >
                            <button
                                onclick={syncFromSchedule}
                                class="btn-gen ghost"
                                style="padding:4px 10px; font-size:9px;"
                                ><i class="fas fa-sync-alt"></i> Tarik dari Jadwal</button
                            >
                        </div>
                        <div
                            style="background:var(--paper); border:1px solid var(--paper2); border-radius:12px; padding:16px; min-height:120px;"
                        >
                            {#if selectedReferees.length > 0}
                                <div
                                    style="display:flex; flex-direction:column; gap:8px;"
                                >
                                    {#each selectedReferees as refId, idx}
                                        {@const ref = allReferees.find(
                                            (r) => String(r.id) === refId,
                                        )}
                                        {#if ref}
                                            <div
                                                style="display:flex; align-items:center; gap:12px; background:#fff; padding:8px 12px; border-radius:8px; border:1px solid var(--paper2);"
                                            >
                                                <span
                                                    style="width:24px; height:24px; border-radius:6px; background:var(--ink); color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700;"
                                                    >{idx + 1}</span
                                                >
                                                <div>
                                                    <div
                                                        style="font-size:13px; font-weight:700; color:var(--ink);"
                                                    >
                                                        {ref.user?.name ||
                                                            ref.name ||
                                                            "-"}
                                                    </div>
                                                    <div
                                                        style="font-size:9px; font-weight:700; color:var(--smoke); text-transform:uppercase;"
                                                    >
                                                        {#if idx === 0}Wasit
                                                            Nasional (Ketua){/if}
                                                        {#if idx === 1}Wasit
                                                            Daerah 1{/if}
                                                        {#if idx === 2}Wasit
                                                            Daerah 2{/if}
                                                        {#if idx === 3}Wasit
                                                            Pembantu 1{/if}
                                                        {#if idx === 4}Wasit
                                                            Pembantu 2{/if}
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}
                                    {/each}
                                </div>
                            {:else}
                                <div
                                    style="text-align:center; padding:20px 0; color:var(--smoke);"
                                >
                                    <i
                                        class="fas fa-user-friends"
                                        style="font-size:24px; margin-bottom:8px; color:var(--paper2);"
                                    ></i>
                                    <p
                                        style="font-size:12px; font-weight:600; margin:0;"
                                    >
                                        Belum Ada Wasit Terpilih
                                    </p>
                                </div>
                            {/if}
                        </div>
                    </div>

                    <div style="display:flex; gap:8px;">
                        <button
                            onclick={() => (showRefereeModal = false)}
                            class="btn-gen ghost"
                            style="flex:1; padding:12px; justify-content:center; font-size:12px;"
                            >Batal</button
                        >
                        <button
                            onclick={saveRefereeAssignment}
                            class="btn-gen primary"
                            style="flex:2; padding:12px; justify-content:center; font-size:12px;"
                            disabled={selectedReferees.length !== 5}
                            >Simpan</button
                        >
                    </div>
                </div>
            </div>
        </div>
    {/if}

<style>
    /* STATS / COURTS */
    .tm-stats {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .tm-stat-pill {
        background: #fff;
        border: 1px solid var(--paper2, #e0dcd3);
        border-radius: 16px;
        padding: 14px;
        position: relative;
        overflow: hidden;
        transition: all 0.2s;
    }

    .tm-stat-pill:hover {
        border-color: #bbb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .court-status {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .court-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        text-transform: uppercase;
        font-family: "Cinzel", serif;
    }

    .court-match-name {
        font-size: 12px;
        font-weight: 700;
        color: #27ae60;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .court-match-contingent {
        font-size: 11px;
        color: var(--smoke);
        font-weight: 600;
    }

    .court-links {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        margin-top: 12px;
    }

    .court-link {
        background: #fdfbf7;
        border: 1px solid var(--paper2);
        border-radius: 6px;
        padding: 6px;
        text-align: center;
        font-size: 9px;
        font-weight: 700;
        color: var(--smoke);
        text-transform: uppercase;
        transition: all 0.15s;
        text-decoration: none;
    }

    .court-link:hover {
        background: var(--ink);
        color: #fff;
        border-color: var(--ink);
    }

    .court-ref-list {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed var(--paper2);
    }

    .court-ref-item {
        font-size: 10px;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .draw-badge {
        font-size: 9px;
        font-weight: 850;
        text-transform: uppercase;
        padding: 2px 6px;
        border-radius: 4px;
        background: #eee;
        color: #555;
    }

    .draw-badge.embu {
        background: rgba(155, 89, 182, 0.1);
        color: #9b59b6;
    }

    .draw-badge.randori {
        background: rgba(230, 126, 34, 0.1);
        color: #e67e22;
    }

    /* MODAL */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .modal-content {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 600px;
        max-height: 95vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-hdr {
        padding: 20px 24px;
        border-bottom: 1px solid var(--paper2);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-hdr h3 {
        font-family: "Cinzel", serif;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: var(--ink);
    }

    .modal-close {
        background: var(--paper);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--smoke);
        transition: 0.2s;
    }

    .modal-close:hover {
        background: var(--red);
        color: #fff;
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    @keyframes pulse {
        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.5;
        }
    }

    .ref-modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    @media (max-width: 500px) {
        .ref-modal-grid {
            grid-template-columns: 1fr;
        }
    }

    .tm-filter-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--paper2, #e0dcd3);
        border-radius: 9px;
        font-size: 12px;
        color: var(--ink, #2c3e50);
        background: #fff;
        outline: none;
    }

    .tm-filter-input:focus {
        border-color: var(--red, #c0392b);
    }

    @media (max-width: 640px) {
        .d-none-mobile {
            display: none !important;
        }
        :global(.pagination-container button) {
            padding: 6px 8px !important;
            font-size: 13px !important;
        }
    }
    @media (min-width: 641px) {
        .d-only-mobile {
            display: none !important;
        }
    }
</style>
