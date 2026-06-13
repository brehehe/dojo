<script>
    import { onMount, onDestroy } from "svelte";
    import SignaturePad from "../Components/SignaturePad.svelte";

    // Svelte 5 states
    let loading = $state(true);
    let referee = $state(null);
    let activeMatch = $state(null);
    let activeDrawing = $state(null);
    let assignedCourt = $state(null);
    let assignedSession = $state(null);
    let assignedRundown = $state(null);
    let judgeIndex = $state(null);
    let judgeLabel = $state(null);
    let activeContingentName = $state("-");
    let activeRoundLabel = $state("-");
    let activeTechniqueLabel = $state("-");
    let activeTechniqueList = $state([]);
    let activeAthleteNames = $state([]);
    let activeIsTeamCategory = $state(false);
    let isFormOpen = $state(false);
    let isTabletMode = $state(false);
    let currentActiveIdentifier = $state(null);

    let embuItems = $state({
        goho_1: 0,
        goho_2: 0,
        goho_3: 0,
        juho_1: 0,
        juho_2: 0,
        juho_3: 0,
        ekspresi_1: 0,
        ekspresi_2: 0,
        ekspresi_3: 0,
        ekspresi_4: 0,
    });
    let notes = $state("");
    let signature = $state(null);
    let isFullscreen = $state(false);
    let submitting = $state(false);

    let pollInterval;

    // Derived values (Svelte 5)
    let techniqueSubtotal = $derived(
        Number(embuItems.goho_1 || 0) +
            Number(embuItems.goho_2 || 0) +
            Number(embuItems.goho_3 || 0) +
            Number(embuItems.juho_1 || 0) +
            Number(embuItems.juho_2 || 0) +
            Number(embuItems.juho_3 || 0),
    );

    let expressionSubtotal = $derived(
        Number(embuItems.ekspresi_1 || 0) +
            Number(embuItems.ekspresi_2 || 0) +
            Number(embuItems.ekspresi_3 || 0) +
            Number(embuItems.ekspresi_4 || 0),
    );

    let totalScore = $derived(techniqueSubtotal + expressionSubtotal);

    async function fetchState() {
        try {
            const res = await fetch("/admin/referee/scoring/state");
            const data = await res.json();
            if (data.error) {
                loading = false;
                return;
            }

            referee = data.referee;
            activeDrawing = data.activeDrawing;
            assignedCourt = data.assignedCourt;
            assignedSession = data.assignedSession;
            assignedRundown = data.assignedRundown;
            judgeIndex = data.judgeIndex;
            judgeLabel = data.judgeLabel;
            activeContingentName = data.activeContingentName;
            activeRoundLabel = data.activeRoundLabel;
            activeTechniqueLabel = data.activeTechniqueLabel;
            activeTechniqueList = data.activeTechniqueList || [];
            activeAthleteNames = data.activeAthleteNames || [];
            activeIsTeamCategory = data.activeIsTeamCategory;
            isTabletMode = data.isTabletMode;

            // Handle match change or participant called transition
            if (currentActiveIdentifier !== data.currentActiveIdentifier) {
                currentActiveIdentifier = data.currentActiveIdentifier;
                activeMatch = data.activeMatch;
                isFormOpen = data.isFormOpen;

                // Sync scores & comments
                if (data.isFormOpen) {
                    for (const key in embuItems) {
                        embuItems[key] = data.embuItems?.[key] || 0;
                    }
                    notes = data.notes || "";
                    signature = data.signature || null;
                } else {
                    resetLocalForm();
                }
            } else {
                activeMatch = data.activeMatch;
                isFormOpen = data.isFormOpen;
            }

            loading = false;
        } catch (e) {
            console.error("Error fetching scoring state:", e);
            loading = false;
        }
    }

    function resetLocalForm() {
        for (const key in embuItems) {
            embuItems[key] = 0;
        }
        notes = "";
        signature = null;
    }

    async function autoSave() {
        if (!activeMatch || !referee) return;

        // Clamp inputs before saving
        const savedEmbuItems = {};
        for (const key in embuItems) {
            let val = embuItems[key];
            if (val === "" || val === null || val === undefined) {
                savedEmbuItems[key] = 0;
                continue;
            }
            if (typeof val === "string") {
                val = val.replace(",", ".");
            }
            let numeric = parseFloat(val);
            if (isNaN(numeric)) {
                savedEmbuItems[key] = 0;
            } else {
                savedEmbuItems[key] = Math.max(0, Math.min(10, numeric));
            }
        }

        try {
            await fetch("/admin/referee/scoring/save", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
                body: JSON.stringify({
                    embuItems: savedEmbuItems,
                    notes,
                }),
            });
        } catch (e) {
            console.error("Error autosaving scores:", e);
        }
    }

    async function submitScore() {
        if (submitting) return;
        if (!signature) {
            if (window.Swal) {
                window.Swal.fire({
                    icon: "warning",
                    title: "Tanda Tangan Diperlukan",
                    text: "Silakan tanda tangan terlebih dahulu pada kolom yang disediakan.",
                });
            } else {
                alert(
                    "Silakan tanda tangan terlebih dahulu pada kolom yang disediakan.",
                );
            }
            return;
        }

        submitting = true;
        try {
            const res = await fetch("/admin/referee/scoring/submit", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
                body: JSON.stringify({
                    embuItems,
                    notes,
                    signature,
                }),
            });
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            const data = await res.json();
            if (data.success) {
                if (window.Swal) {
                    await window.Swal.fire({
                        icon: "success",
                        title: "Skor Tersimpan",
                        text: data.message,
                    });
                } else {
                    alert(data.message);
                }
                fetchState();
            } else {
                if (window.Swal) {
                    window.Swal.fire({
                        icon: "error",
                        title: "Gagal Menyimpan",
                        text: data.message,
                    });
                } else {
                    alert(data.message);
                }
            }
        } catch (e) {
            console.error("Error submitting scores:", e);
            if (window.Swal) {
                window.Swal.fire({
                    icon: "error",
                    title: "Gagal Menyimpan",
                    text: "Terjadi kesalahan pada server atau koneksi internet terputus. Silakan coba lagi.",
                });
            } else {
                alert("Terjadi kesalahan pada server atau koneksi internet terputus. Silakan coba lagi.");
            }
        } finally {
            submitting = false;
        }
    }

    function handleInput(e, key) {
        let val = e.target.value;
        val = val.replace(",", ".");
        if (/^[0-9]*\.?[0-9]*$/.test(val)) {
            embuItems[key] = val;
        }
    }

    function clearZeroOnFocus(key) {
        if (Number(embuItems[key]) === 0) {
            embuItems[key] = "";
        }
    }

    function restoreZeroOnBlur(key) {
        let val = embuItems[key];
        if (val === "" || val === null || val === undefined) {
            embuItems[key] = 0;
        } else {
            if (typeof val === "string") {
                val = val.replace(",", ".");
            }
            let numeric = parseFloat(val);
            if (isNaN(numeric)) {
                embuItems[key] = 0;
            } else {
                embuItems[key] = Math.max(0, Math.min(10, numeric));
            }
        }
        autoSave();
    }

    function toggleFullscreen() {
        const element = document.documentElement;
        if (!document.fullscreenElement) {
            element
                .requestFullscreen()
                .then(() => {
                    isFullscreen = true;
                })
                .catch((err) => {
                    console.error("Error attempting fullscreen:", err);
                });
        } else {
            document.exitFullscreen().then(() => {
                isFullscreen = false;
            });
        }
    }

    function syncFullscreen() {
        isFullscreen = !!document.fullscreenElement;
    }

    onMount(() => {
        document.body.classList.add("referee-scoring-immersive");
        document.addEventListener("fullscreenchange", syncFullscreen);
        fetchState();
        pollInterval = setInterval(fetchState, 1500);
    });

    onDestroy(() => {
        document.body.classList.remove("referee-scoring-immersive");
        document.removeEventListener("fullscreenchange", syncFullscreen);
        clearInterval(pollInterval);
    });
</script>

<div class="ref-scoring-shell">
    {#if loading}
        <div class="ref-wait">
            <div class="ref-wait-icon">
                <i class="fa-solid fa-spinner fa-spin"></i>
            </div>
            <h3>Memuat Halaman...</h3>
            <p>Menyiapkan data penilaian juri.</p>
        </div>
    {:else}
        <!-- Fullscreen Button -->
        <button
            type="button"
            class="ref-fullscreen-btn"
            onclick={toggleFullscreen}
        >
            <i class="fa-solid {isFullscreen ? 'fa-compress' : 'fa-expand'}"
            ></i>
            <span>{isFullscreen ? "Keluar Fullscreen" : "Fullscreen"}</span>
        </button>

        <!-- Status Bar -->
        <div class="ref-statusbar">
            <div class="ref-judge-info">
                <div class="ref-judge-icon">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <div>
                    <h3 class="ref-judge-name">
                        {#if isTabletMode}
                            {referee?.user?.name || "Belum Ditugaskan"}
                        {:else}
                            {referee?.user?.name || referee?.name || "Wasit"}
                        {/if}
                    </h3>
                    <p class="ref-judge-sub">
                        {#if isTabletMode}
                            <span style="color:var(--red); font-weight:700;"
                                >Acting as {judgeLabel || "Wasit"}</span
                            >
                        {:else}
                            Wasit Juri Aktif
                        {/if}
                    </p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a href="/dashboard" class="ref-btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                {#if judgeIndex}
                    <div class="ref-judge-badge">{judgeLabel}</div>
                {/if}
            </div>
        </div>

        <!-- Sync Badge -->
        <div class="ref-sync">
            <i class="fa-solid fa-satellite-dish fa-spin-pulse"></i>
            Sinkronisasi Otomatis Aktif
        </div>

        <!-- Warning banner -->
        {#if !referee}
            <div
                class="ref-warning"
                style="background: {isTabletMode
                    ? 'rgba(52,152,219,.08)'
                    : 'rgba(245,158,11,.08)'}; border-color: {isTabletMode
                    ? 'rgba(52,152,219,.25)'
                    : 'rgba(245,158,11,.25)'};"
            >
                <i
                    class="fa-solid {isTabletMode
                        ? 'fa-info-circle'
                        : 'fa-triangle-exclamation'}"
                    style="color: {isTabletMode ? '#2980b9' : '#d97706'};"
                ></i>
                <div>
                    <p
                        class="ref-warning-title"
                        style="color: {isTabletMode ? '#24364b' : '#92400e'};"
                    >
                        {isTabletMode
                            ? "Menunggu Penugasan Wasit"
                            : "Akun Bukan Wasit Terdaftar"}
                    </p>
                    <p
                        class="ref-warning-text"
                        style="color: {isTabletMode ? '#2c3e50' : '#78350f'};"
                    >
                        {#if isTabletMode}
                            Tablet ini sudah siap digunakan. Silakan tunggu
                            Panitia/Admin menugaskan Wasit untuk shift ini di
                            Court {assignedCourt
                                ? assignedCourt.name || assignedCourt.id
                                : ""}.
                        {:else}
                            Akun Anda tidak terdaftar sebagai wasit. Scoring
                            tidak dapat dikirim. Pastikan login menggunakan akun
                            wasit yang benar.
                        {/if}
                    </p>
                </div>
            </div>
        {/if}

        {#if isFormOpen}
            <!-- Match Header -->
            <div class="ref-match-hdr">
                <div class="ref-live-badge">
                    <span class="ref-live-dot"></span> LIVE ON COURT
                </div>
                <p class="ref-match-type">
                    {activeMatch.draft_type
                        ? activeMatch.draft_type.toUpperCase()
                        : ""}
                </p>
                <h3 class="ref-match-name">{activeMatch.name}</h3>
                {#if activeAthleteNames && activeAthleteNames.length > 0}
                    <div
                        class="ref-match-athletes"
                        style="margin-top: 8px; margin-bottom: 8px; padding: 6px 12px; background: rgba(255, 255, 255, 0.08); border-radius: 8px;"
                    >
                        <p
                            style="font-size: 12px; color: rgba(255, 255, 255, 0.6); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;"
                        >
                            Atlet Bertanding:
                        </p>
                        <p
                            style="font-size: 16px; color: #fff; font-weight: 600; margin: 2px 0 0;"
                        >
                            {#if activeMatch.draft_type === "embu"}
                                {activeAthleteNames.join(" & ")}
                            {:else}
                                {activeAthleteNames.join(" vs ")}
                            {/if}
                        </p>
                    </div>
                {/if}
                <p class="ref-match-sub">
                    Berikan penilaian terbaik Anda secara objektif.
                </p>
            </div>

            <!-- Info Chips -->
            <div class="ref-info-chips">
                <div class="ref-info-chip">
                    <div class="ref-info-chip-icon red">
                        <i class="fa-solid fa-flag"></i>
                    </div>
                    <div class="ref-info-chip-body">
                        <p class="ref-info-chip-label">Kontingen</p>
                        <p class="ref-info-chip-value">
                            {activeContingentName || "-"}
                        </p>
                    </div>
                </div>
                <div class="ref-info-chip">
                    <div class="ref-info-chip-icon blue">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="ref-info-chip-body">
                        <p class="ref-info-chip-label">Court / Lapangan</p>
                        <p class="ref-info-chip-value">
                            {assignedCourt ? assignedCourt.name : "-"}
                        </p>
                    </div>
                </div>
                <div class="ref-info-chip">
                    <div class="ref-info-chip-icon green">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div class="ref-info-chip-body">
                        <p class="ref-info-chip-label">Pool / Kelas</p>
                        <p class="ref-info-chip-value">
                            {activeDrawing?.pool?.name ||
                                activeMatch?.age_group?.name ||
                                activeMatch?.ageGroup?.name ||
                                "-"}
                        </p>
                    </div>
                </div>
                <div class="ref-info-chip">
                    <div class="ref-info-chip-icon gold">
                        <i class="fa-solid fa-hand-fist"></i>
                    </div>
                    <div class="ref-info-chip-body">
                        <p class="ref-info-chip-label">Babak</p>
                        <p class="ref-info-chip-value">
                            {activeRoundLabel || "-"}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Technique Panel -->
            <div class="ref-technique-panel">
                <div class="ref-technique-hdr">
                    <div class="ref-technique-icon">
                        <i class="fa-solid fa-hand-fist"></i>
                    </div>
                    <div>
                        <p class="ref-technique-label">Teknik</p>
                        <p class="ref-technique-title">
                            Komposisi / Teknik yang Diuji
                        </p>
                    </div>
                </div>
                {#if activeTechniqueList && activeTechniqueList.length > 0}
                    <ol class="ref-technique-list">
                        {#each activeTechniqueList as technique}
                            <li>{technique}</li>
                        {/each}
                    </ol>
                {:else}
                    <p class="ref-technique-text">
                        {activeTechniqueLabel || "-"}
                    </p>
                {/if}
            </div>

            {#if activeMatch.draft_type === "embu"}
                <!-- Form Panel -->
                {#if referee}
                    <div class="ref-form-panel">
                        <div class="ref-score-table">
                            <div class="ref-score-table-wrap">
                                <table class="ref-score-table-grid">
                                    <thead>
                                        <tr>
                                            <th>Aspek</th>
                                            <th>Deskripsi</th>
                                            <th>Bobot</th>
                                            <th>No</th>
                                            <th>Nilai</th>
                                            <th>Standar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- ASPECT: Penguasaan Teknik (60) -->
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-aspect"
                                                rowspan="6"
                                            >
                                                Penguasaan Teknik (60)
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-desc-cell"
                                                rowspan="3"
                                            >
                                                <div style="line-height: 1.6;">
                                                    <strong
                                                        style="color: var(--ink); font-size: 14px;"
                                                        >GOHO</strong
                                                    >
                                                    <span
                                                        style="color: var(--ink); font-size: 14px;"
                                                        >: Serangan, bertahan,
                                                        serangan balasan, lima
                                                        unsur serangan dan
                                                        lain-lain</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-weight"
                                                rowspan="6"
                                            >
                                                <div>
                                                    60
                                                    <span
                                                        class="ref-score-weight-note"
                                                        >(Masing² 10)</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >1</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.goho_1}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "goho_1",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "goho_1",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "goho_1",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >2</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.goho_2}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "goho_2",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "goho_2",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "goho_2",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >3</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.goho_3}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "goho_3",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "goho_3",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "goho_3",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-desc-cell"
                                                rowspan="3"
                                            >
                                                <div style="line-height: 1.6;">
                                                    <strong
                                                        style="color: var(--ink); font-size: 14px;"
                                                        >JUHO</strong
                                                    >
                                                    <span
                                                        style="color: var(--ink); font-size: 14px;"
                                                        >: Shuha, nukiwaza,
                                                        gyaku waza, nage waza,
                                                        katame waza dan
                                                        lain-lain</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >4</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.juho_1}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "juho_1",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "juho_1",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "juho_1",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >5</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.juho_2}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "juho_2",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "juho_2",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "juho_2",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >6</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.juho_3}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "juho_3",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "juho_3",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "juho_3",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>

                                        <tr class="ref-score-table-subtotal">
                                            <td
                                                class="ref-score-cell ref-score-subtotal-label"
                                                colspan="5">Sub Total-1</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-subtotal-value"
                                                >{techniqueSubtotal.toFixed(
                                                    1,
                                                )}</td
                                            >
                                        </tr>

                                        <!-- ASPECT: Ekspresi (40) -->
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-aspect"
                                                rowspan="4"
                                            >
                                                Ekspresi (40)
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-desc-cell"
                                            >
                                                <div
                                                    class="ref-score-desc"
                                                    style="color: var(--ink); font-weight: 500; font-size: 14px; margin-top: 0;"
                                                >
                                                    1. Rangkaian, Irama, Harmoni
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-weight"
                                                rowspan="4"
                                            >
                                                <div>
                                                    40
                                                    <span
                                                        class="ref-score-weight-note"
                                                        >(Masing² 10)</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >1</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.ekspresi_1}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "ekspresi_1",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "ekspresi_1",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "ekspresi_1",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-desc-cell"
                                            >
                                                <div
                                                    class="ref-score-desc"
                                                    style="color: var(--ink); font-weight: 500; font-size: 14px; margin-top: 0;"
                                                >
                                                    2. Tai gamae, Kuda-kuda,
                                                    Keindahan
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >2</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.ekspresi_2}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "ekspresi_2",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "ekspresi_2",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "ekspresi_2",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-desc-cell"
                                            >
                                                <div
                                                    class="ref-score-desc"
                                                    style="color: var(--ink); font-weight: 500; font-size: 14px; margin-top: 0;"
                                                >
                                                    3. Semangat, Disiplin
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >3</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.ekspresi_3}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "ekspresi_3",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "ekspresi_3",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "ekspresi_3",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>
                                        <tr>
                                            <td
                                                class="ref-score-cell ref-score-desc-cell"
                                            >
                                                <div
                                                    class="ref-score-desc"
                                                    style="color: var(--ink); font-weight: 500; font-size: 14px; margin-top: 0;"
                                                >
                                                    4. Nafas, Pandangan mata,
                                                    Zanshin
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-no"
                                                >4</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-input-cell"
                                            >
                                                <div
                                                    class="ref-score-input-wrap"
                                                >
                                                    <input
                                                        type="text"
                                                        inputmode="decimal"
                                                        value={embuItems.ekspresi_4}
                                                        onfocus={() =>
                                                            clearZeroOnFocus(
                                                                "ekspresi_4",
                                                            )}
                                                        onblur={() =>
                                                            restoreZeroOnBlur(
                                                                "ekspresi_4",
                                                            )}
                                                        oninput={(e) =>
                                                            handleInput(
                                                                e,
                                                                "ekspresi_4",
                                                            )}
                                                        class="ref-score-input"
                                                        placeholder="0.0"
                                                    />
                                                    <span
                                                        class="ref-score-range-hint"
                                                        >Isi 0.0 – 10.0</span
                                                    >
                                                </div>
                                            </td>
                                            <td
                                                class="ref-score-cell ref-score-standard"
                                                >8</td
                                            >
                                        </tr>

                                        <tr class="ref-score-table-subtotal">
                                            <td
                                                class="ref-score-cell ref-score-subtotal-label"
                                                colspan="5">Sub Total-2</td
                                            >
                                            <td
                                                class="ref-score-cell ref-score-subtotal-value"
                                                >{expressionSubtotal.toFixed(
                                                    1,
                                                )}</td
                                            >
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total Banner -->
                        <div class="ref-total-banner">
                            <div class="ref-total-left">
                                <div class="ref-total-label">Total Skor</div>
                                <div class="ref-total-sub">
                                    Sub Total-1 + Sub Total-2 (10 aspek)
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div class="ref-total-val">
                                    {totalScore.toFixed(1)}
                                </div>
                                {#if totalScore > 0}
                                    <div class="ref-avg-val">
                                        Rata-rata: {(totalScore / 10).toFixed(
                                            2,
                                        )}
                                    </div>
                                {/if}
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="ref-notes-wrap">
                            <div class="ref-notes-hdr">
                                <i
                                    class="fa-solid fa-pen-nib"
                                    style="font-size:10px; margin-right:4px;"
                                ></i>
                                Catatan Wasit (Opsional)
                            </div>
                            <textarea
                                bind:value={notes}
                                onblur={autoSave}
                                class="ref-notes-textarea"
                                rows="3"
                                placeholder="Ketik catatan di sini..."
                            ></textarea>
                        </div>

                        <!-- Signature Pad -->
                        <div style="margin-bottom: 20px;">
                            <SignaturePad
                                bind:value={signature}
                                name="Tanda Tangan Digital Wasit (Wajib)"
                            />
                        </div>

                        <!-- Action Button -->
                        <div class="ref-actions">
                            <button
                                type="button"
                                onclick={submitScore}
                                class="ref-btn-submit"
                                disabled={submitting}
                            >
                                {#if submitting}
                                    <i class="fa-solid fa-spinner animate-spin"></i> Menyimpan...
                                {:else}
                                    <i class="fa-solid fa-paper-plane"></i> Simpan Penilaian
                                {/if}
                            </button>
                        </div>
                    </div>
                {:else}
                    <div class="ref-form-readonly">
                        <div class="ref-card-hdr">
                            <h2>
                                Penilaian {activeMatch ? activeMatch.name : "—"}
                            </h2>
                            <div class="ref-match-info">
                                <div class="ref-info-row">
                                    <span class="ref-info-label">Arena</span>
                                    <span class="ref-info-value"
                                        >{assignedCourt
                                            ? assignedCourt.name
                                            : "—"}</span
                                    >
                                </div>
                                <div class="ref-info-row">
                                    <span class="ref-info-label">Wasit</span>
                                    <span class="ref-info-value"
                                        >{referee?.user?.name || "-"}</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="ref-readonly-overlay">
                            <div>
                                <i class="fa-solid fa-user-slash"></i>
                                <h3>Wasit Tidak Tersedia</h3>
                                <p>
                                    Anda tidak terdaftar sebagai wasit untuk
                                    pertandingan ini atau wasit belum diatur.
                                </p>
                            </div>
                        </div>
                    </div>
                {/if}
            {:else}
                <!-- RANDORI INFO -->
                <div class="ref-randori-info">
                    <i class="fa-solid fa-circle-info"></i>
                    <h3>Kategori Randori Aktif</h3>
                    <p>
                        Penilaian kategori Randori dilakukan langsung oleh <strong
                            >Panitera</strong
                        > di meja pertandingan. Anda tidak perlu memasukkan skor
                        melalui panel ini.
                    </p>
                </div>
            {/if}
        {:else if activeMatch}
            <!-- Waiting — peserta belum dipanggil -->
            <div class="ref-wait">
                <div class="ref-wait-icon">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
                <h3>Persiapan: {activeMatch.name}</h3>
                <p>
                    Mohon tunggu, Panitera akan segera memanggil atlet ke
                    lapangan untuk Anda nilai.
                </p>
            </div>
        {:else}
            <!-- No active match -->
            <div class="ref-wait">
                <div class="ref-wait-icon">
                    <i class="fa-solid fa-broadcast-tower"></i>
                </div>
                <h3>Menunggu Pertandingan</h3>
                <p>
                    Belum ada pertandingan yang dipanggil ke lapangan oleh
                    Panitera.
                </p>
            </div>
        {/if}
    {/if}
</div>

<style>
    /* ══════════════════════════════════════════════════════
                       REFEREE SCORING DASHBOARD — Mobile Premium Styles
                    ══════════════════════════════════════════════════════ */

    :global(body.referee-scoring-immersive) {
        overflow: auto;
        background: var(--paper) !important;
        font-family: "DM Sans", sans-serif;
    }

    :global(body.referee-scoring-immersive .premium-header),
    :global(body.referee-scoring-immersive .mob-bottomnav) {
        display: none !important;
    }

    :global(body.referee-scoring-immersive main.premium-main) {
        height: 100dvh;
        min-height: 100dvh;
        padding-bottom: 0 !important;
        overflow: hidden;
    }

    :global(body.referee-scoring-immersive .ref-scoring-shell) {
        height: 100dvh;
        min-height: 100dvh;
        padding-bottom: 32px;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior-y: contain;
        touch-action: pan-y;
    }

    .ref-scoring-shell {
        position: relative;
    }

    .ref-fullscreen-btn {
        position: fixed;
        top: 50px;
        left: 30px;
        z-index: 80;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        border-radius: 999px;
        background: rgba(15, 13, 11, 0.92);
        color: #fff;
        padding: 12px 16px;
        font-family: "DM Sans", sans-serif;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.02em;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        cursor: pointer;
    }

    .ref-fullscreen-btn i {
        font-size: 14px;
    }

    /* ── STATUS BAR ── */
    .ref-statusbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px 10px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ref-judge-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ref-judge-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(192, 57, 43, 0.1);
        color: var(--red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .ref-judge-name {
        font-size: 19px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1px;
    }

    .ref-judge-sub {
        font-size: 14px;
        color: var(--smoke);
        margin: 0;
    }

    .ref-judge-badge {
        padding: 6px 13px;
        background: var(--ink);
        color: var(--gold-lt);
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
        font-family: "Cinzel", serif;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    /* ── BACK BUTTON ── */
    .ref-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: rgba(36, 54, 75, 0.05);
        border: 1px solid rgba(36, 54, 75, 0.15);
        border-radius: 999px;
        color: var(--ink);
        font-family: "DM Sans", sans-serif;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s;
    }

    .ref-btn-back:hover {
        background: rgba(36, 54, 75, 0.1);
        border-color: rgba(36, 54, 75, 0.25);
    }

    /* ── SYNC INDICATOR ── */
    .ref-sync {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 0 12px;
        font-size: 13px;
        color: var(--smoke);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .ref-sync i {
        color: #27ae60;
    }

    /* ── WARNING BANNER ── */
    .ref-warning {
        margin: 0 16px 12px;
        padding: 12px 14px;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.25);
        border-radius: 12px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .ref-warning i {
        color: #d97706;
        font-size: 16px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .ref-warning-title {
        font-size: 18px;
        font-weight: 700;
        color: #92400e;
        margin: 0 0 2px;
    }

    .ref-warning-text {
        font-size: 15px;
        color: #78350f;
        margin: 0;
        line-height: 1.5;
    }

    /* ── MATCH HEADER ── */
    .ref-match-hdr {
        background: var(--ink);
        margin: 0 16px 12px;
        border-radius: 16px;
        padding: 16px 18px 14px;
        position: relative;
        overflow: hidden;
    }

    .ref-match-hdr::after {
        content: "";
        position: absolute;
        right: -20px;
        bottom: -20px;
        width: 100px;
        height: 100px;
        background: rgba(192, 57, 43, 0.15);
        border-radius: 50%;
    }

    .ref-live-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        background: var(--red);
        color: #fff;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.1em;
        margin-bottom: 8px;
    }

    .ref-live-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #fff;
        animation: blink 0.8s ease-in-out infinite alternate;
    }

    @keyframes blink {
        from {
            opacity: 1;
        }
        to {
            opacity: 0.3;
        }
    }

    .ref-match-type {
        font-size: 12.5px;
        color: var(--smoke);
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 4px;
    }

    .ref-match-name {
        font-family: "Cinzel", serif;
        font-size: 27px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 2px;
    }

    .ref-match-sub {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.4);
        margin: 0;
    }

    /* ── MATCH INFO CHIPS ── */
    .ref-info-chips {
        padding: 0 16px 12px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .ref-info-chip {
        background: #fff;
        border: 1px solid var(--paper2);
        border-radius: 12px;
        padding: 10px 12px;
        display: flex;
        align-items: flex-start;
        gap: 9px;
    }

    .ref-info-chip-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .ref-info-chip-icon.red {
        background: rgba(192, 57, 43, 0.1);
        color: var(--red);
    }

    .ref-info-chip-icon.blue {
        background: rgba(52, 152, 219, 0.1);
        color: #2980b9;
    }

    .ref-info-chip-icon.green {
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
    }

    .ref-info-chip-icon.gold {
        background: rgba(212, 168, 67, 0.12);
        color: #b8860b;
    }

    .ref-info-chip-body {
        min-width: 0;
    }

    .ref-info-chip-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--smoke);
        margin: 0 0 2px;
    }

    .ref-info-chip-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
        font-family: "DM Sans", sans-serif;
    }

    /* ── TECHNIQUE PANEL ── */
    .ref-technique-panel {
        margin: 0 16px 12px;
        background: #fff;
        border: 1px solid var(--paper2);
        border-radius: 14px;
        padding: 14px 16px;
        position: sticky;
        top: 12px;
        z-index: 50;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }

    .ref-technique-hdr {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .ref-technique-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(212, 168, 67, 0.12);
        color: #b8860b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .ref-technique-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--smoke);
        margin: 0 0 2px;
    }

    .ref-technique-title {
        font-size: 19px;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .ref-technique-text {
        font-size: 18px;
        line-height: 1.7;
        color: var(--ink);
        margin: 0;
    }

    .ref-technique-list {
        margin: 0;
        padding-left: 18px;
        color: var(--ink);
        list-style: decimal;
        list-style-position: outside;
    }

    .ref-technique-list li {
        font-size: 18px;
        line-height: 1.7;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    /* ── WAIT STATE ── */
    .ref-wait {
        margin: 24px 16px 16px;
        padding: 32px 24px;
        text-align: center;
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--paper2);
    }

    .ref-wait-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--paper);
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: var(--smoke);
        animation: pulse-slow 2.5s ease-in-out infinite;
    }

    @keyframes pulse-slow {
        0%,
        100% {
            opacity: 1;
        }
        50% {
            opacity: 0.4;
        }
    }

    .ref-wait h3 {
        font-family: "Cinzel", serif;
        font-size: 20px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 6px;
    }

    .ref-wait p {
        font-size: 15px;
        color: var(--smoke);
        margin: 0;
        line-height: 1.6;
        max-width: 260px;
        margin-inline: auto;
    }

    /* ── SCORE FORM PANEL ── */
    .ref-form-panel {
        padding: 0 16px 16px;
    }

    .ref-score-table {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--paper2);
        margin-bottom: 12px;
        overflow: hidden;
    }

    .ref-score-table-wrap {
        overflow-x: auto;
    }

    .ref-score-table-grid {
        width: 100%;
        min-width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .ref-score-table-grid th:nth-child(1) {
        width: 5%;
    }
    .ref-score-table-grid th:nth-child(2) {
        width: 15%;
    }
    .ref-score-table-grid th:nth-child(3) {
        width: 10%;
    }
    .ref-score-table-grid th:nth-child(4) {
        width: 5%;
    }
    .ref-score-table-grid th:nth-child(5) {
        width: 8%;
    }
    .ref-score-table-grid th:nth-child(6) {
        width: 8%;
    }

    .ref-score-table-grid th,
    .ref-score-table-grid td {
        border-right: 1px solid var(--paper2);
        border-bottom: 1px solid var(--paper2);
        vertical-align: middle;
    }

    .ref-score-table-grid tr > *:last-child {
        border-right: none;
    }

    .ref-score-table-grid thead th {
        background: var(--paper);
        padding: 12px 10px;
        font-size: 11px;
        font-weight: 800;
        color: #24364b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        text-align: center;
    }

    .ref-score-table-grid thead th:nth-child(2) {
        text-align: left;
    }

    .ref-score-cell {
        padding: 10px 8px;
        min-width: 0;
    }

    @media (max-width: 1024px) {
        .ref-score-cell {
            padding: 6px 4px;
        }
        .ref-score-table-grid thead th {
            padding: 10px 6px;
            font-size: 10px;
        }
    }

    .ref-score-aspect {
        text-align: center;
        font-size: 18px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.35;
        background: rgba(247, 244, 239, 0.65);
        writing-mode: vertical-rl;
        white-space: nowrap;
    }

    .ref-score-desc {
        font-size: 15px;
        color: var(--smoke);
        margin-top: 4px;
        line-height: 1.45;
    }

    .ref-score-weight,
    .ref-score-no,
    .ref-score-standard {
        font-size: 20px;
        font-weight: 700;
        color: var(--ink);
        text-align: center;
    }

    .ref-score-weight-note {
        display: block;
        margin-top: 4px;
        font-size: 10px;
        font-weight: 700;
        color: var(--smoke);
    }

    .ref-score-input-cell {
        text-align: center;
    }

    /* ── SCORE INPUT (range 8–10) ── */
    .ref-score-input-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        width: 100%;
    }

    .ref-score-input {
        width: 100%;
        max-width: 120px;
        padding: 6px 2px;
        border: 1.5px solid var(--paper2);
        border-radius: 10px;
        font-family: "Cinzel", serif;
        font-size: 30px;
        font-weight: 700;
        text-align: center;
        color: var(--ink);
        background: var(--paper);
        outline: none;
        transition:
            border 0.15s,
            box-shadow 0.15s;
        height: 50px;
    }

    .ref-score-input:focus {
        border-color: var(--red);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
    }

    .ref-score-range-hint {
        font-size: 13px;
        color: var(--smoke);
        letter-spacing: 0.05em;
    }

    @media (max-width: 1024px) {
        .ref-score-aspect {
            font-size: 15px;
        }
        .ref-score-desc {
            font-size: 13px;
            margin-top: 2px;
            line-height: 1.3;
        }
        .ref-score-weight,
        .ref-score-no,
        .ref-score-standard {
            font-size: 16px;
        }
        .ref-score-input {
            font-size: 22px;
            max-width: 90px;
            padding: 8px 4px;
        }
        .ref-score-range-hint {
            font-size: 11px;
        }
    }

    .ref-score-table-subtotal {
        background: rgba(247, 244, 239, 0.55);
    }

    .ref-score-subtotal-label {
        text-align: right;
        font-size: 17px;
        font-weight: 800;
        color: #24364b;
        padding-right: 20px;
    }

    .ref-score-subtotal-value {
        font-family: "Cinzel", serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--ink);
        text-align: center;
    }

    /* ── TOTAL BANNER ── */
    .ref-total-banner {
        background: var(--ink);
        border-radius: 14px;
        margin-bottom: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ref-total-label {
        font-size: 14px;
        color: var(--smoke);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .ref-total-sub {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.3);
        font-style: italic;
        margin-top: 2px;
    }

    .ref-total-val {
        font-family: "Cinzel", serif;
        font-size: 54px;
        font-weight: 700;
        color: var(--gold-lt);
        line-height: 1;
    }

    .ref-avg-val {
        font-size: 15px;
        color: var(--smoke);
        text-align: right;
        margin-top: 4px;
        font-family: "DM Sans", sans-serif;
    }

    /* ── NOTES ── */
    .ref-notes-wrap {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--paper2);
        margin-bottom: 14px;
        overflow: hidden;
    }

    .ref-notes-hdr {
        padding: 10px 14px;
        border-bottom: 1px solid var(--paper2);
        font-size: 13px;
        font-weight: 700;
        color: var(--smoke);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        background: var(--paper);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ref-notes-textarea {
        width: 100%;
        border: none;
        outline: none;
        padding: 12px 14px;
        font-family: "DM Sans", sans-serif;
        font-size: 18px;
        color: var(--ink);
        background: transparent;
        resize: none;
        min-height: 80px;
        box-sizing: border-box;
    }

    /* ── ACTION BUTTONS ── */
    .ref-actions {
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .ref-btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--red);
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        font-family: "DM Sans", sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 16px rgba(192, 57, 43, 0.35);
        transition: all 0.2s;
    }

    .ref-btn-submit:hover {
        background: var(--red-deep);
    }

    .ref-btn-submit:active {
        transform: scale(0.98);
    }

    .ref-btn-submit:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    /* ── RANDORI INFO ── */
    .ref-randori-info {
        margin: 24px 16px 16px;
        padding: 24px;
        text-align: center;
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--paper2);
    }

    .ref-randori-info i {
        font-size: 28px;
        color: #2980b9;
        margin-bottom: 12px;
        display: block;
    }

    .ref-randori-info h3 {
        font-family: "Cinzel", serif;
        font-size: 19px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 6px;
    }

    .ref-randori-info p {
        font-size: 15px;
        color: var(--smoke);
        margin: 0;
        line-height: 1.6;
        max-width: 280px;
        margin-inline: auto;
    }

    @media (max-width: 640px) {
        .ref-statusbar {
            padding: 18px 14px 10px;
        }

        .ref-warning,
        .ref-match-hdr,
        .ref-detail-panel,
        .ref-technique-panel,
        .ref-randori-info,
        .ref-wait {
            margin-inline: 12px;
        }

        .ref-form-panel {
            padding-inline: 12px;
        }

        .ref-match-hdr {
            padding: 14px 14px 12px;
        }

        .ref-match-name {
            font-size: 23px;
            line-height: 1.35;
            max-width: calc(100% - 28px);
        }

        .ref-technique-panel {
            padding: 12px 14px;
        }

        .ref-technique-list {
            padding-left: 20px;
        }

        .ref-notes-hdr {
            line-height: 1.4;
        }

        .ref-score-input-wrap {
            align-items: center;
            max-width: 128px;
            margin-inline: auto;
        }

        .ref-score-input {
            min-height: 58px;
            font-size: 28px;
        }

        .ref-score-range-hint {
            text-align: center;
        }

        .ref-total-banner {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
        }

        .ref-total-val,
        .ref-avg-val {
            text-align: left;
        }

        .ref-btn-submit {
            min-height: 48px;
        }

        .ref-randori-info,
        .ref-wait {
            padding: 20px 16px;
        }
    }

    @media (min-width: 641px) and (max-width: 960px) {
        .ref-score-table-grid {
            min-width: 820px;
        }

        .ref-technique-panel {
            padding: 14px 15px;
        }
    }

    /* ── REFEREE UNAVAILABLE / READONLY ── */
    .ref-form-readonly {
        margin: 0 16px 16px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--paper2);
        overflow: hidden;
    }

    .ref-card-hdr {
        padding: 20px;
        background: var(--paper);
        border-bottom: 1px solid var(--paper2);
    }

    .ref-card-hdr h2 {
        font-family: "Cinzel", serif;
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 12px;
    }

    .ref-match-info {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .ref-info-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
    }

    .ref-info-label {
        color: var(--smoke);
        font-weight: 500;
    }

    .ref-info-value {
        color: var(--ink);
        font-weight: 600;
        text-align: right;
    }

    .ref-readonly-overlay {
        padding: 32px 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .ref-readonly-overlay i {
        font-size: 32px;
        color: #e74c3c;
        margin-bottom: 12px;
        display: block;
    }

    .ref-readonly-overlay h3 {
        font-family: "Cinzel", serif;
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 6px;
    }

    .ref-readonly-overlay p {
        font-size: 13px;
        color: var(--smoke);
        margin: 0;
        line-height: 1.5;
        max-width: 280px;
        margin-inline: auto;
    }
</style>
