<script>
    import { onMount } from "svelte";
    import { router } from "@inertiajs/svelte";
    import { postJson } from "../lib/api";

    let { urlMatchId = null } = $props();

    // State
    let matches = $state([]);
    let selectedMatchId = $state(urlMatchId ? String(urlMatchId) : "");
    let matchState = $state(null);
    let roundsUB = $derived(matchState?.matchNumber?.drawing_data?.upper_bracket?.rounds || []);
    let roundsLB = $derived(matchState?.matchNumber?.drawing_data?.lower_bracket?.rounds || []);
    let grandFinal = $derived(matchState?.matchNumber?.drawing_data?.grand_final || null);
    let isLoadingMatches = $state(true);
    let isLoadingState = $state(false);
    let isSaving = $state(false);

    // Toast notifications
    let toast = $state({ show: false, message: "", type: "success" });
    let toastTimeout;
    function showToast(message, type = "success") {
        if (toastTimeout) clearTimeout(toastTimeout);
        toast = { show: true, message, type };
        toastTimeout = setTimeout(() => {
            toast.show = false;
        }, 3500);
    }

    // Modal state for Embu
    let showEmbuModal = $state(false);
    let activeDrawing = $state(null);
    let embuScores = $state({
        judge_1: 0,
        judge_2: 0,
        judge_3: 0,
        judge_4: 0,
        judge_5: 0
    });
    let embuDenda = $state(0);
    let embuWaktu = $state("00:00");
    let autoDenda = $state(true);
    let selectedTiebreakRound = $state(0);

    // Modal state for Randori
    let showRandoriModal = $state(false);
    let activeRandoriNode = $state(null); // { key, bracket, round, match, nodeData }
    let randoriScoreRed = $state(0);
    let randoriScoreBlue = $state(0);
    let randoriScoringAka = $state({
        mujoken_kachi: 0,
        ippon: 0,
        waza_ari: 0,
        hasil_batsu_5: 0,
        hasil_batsu_10: 0,
        yusei_kachi: 0
    });
    let randoriScoringShiro = $state({
        mujoken_kachi: 0,
        ippon: 0,
        waza_ari: 0,
        hasil_batsu_5: 0,
        hasil_batsu_10: 0,
        yusei_kachi: 0
    });

    onMount(async () => {
        try {
            const res = await fetch("/admin/api/scoring/correction/matches");
            if (res.ok) {
                matches = await res.json();
            } else {
                showToast("Gagal memuat daftar pertandingan", "error");
            }
        } catch (e) {
            showToast("Gagal memuat pertandingan", "error");
        } finally {
            isLoadingMatches = false;
        }

        if (selectedMatchId) {
            fetchMatchState();
        }
    });

    async function fetchMatchState() {
        if (!selectedMatchId) {
            matchState = null;
            return;
        }
        isLoadingState = true;
        try {
            const res = await fetch(`/admin/api/scoring/correction/match-state/${selectedMatchId}`);
            if (res.ok) {
                matchState = await res.json();
            } else {
                showToast("Gagal memuat status pertandingan", "error");
            }
        } catch (e) {
            showToast("Koneksi gagal saat memuat status", "error");
        } finally {
            isLoadingState = false;
        }
    }

    function handleMatchChange(e) {
        selectedMatchId = e.target.value;
        fetchMatchState();
    }

    // Embu Correction
    function openEmbuEdit(drawing) {
        activeDrawing = drawing;
        const existingScore = matchState.embuScores.find(s => 
            s.drawing_id === drawing.id || 
            (s.registration_id === drawing.registration_id && s.match_number_id === drawing.match_number_id)
        );
        embuScores = {
            judge_1: existingScore?.judge_1 || 0,
            judge_2: existingScore?.judge_2 || 0,
            judge_3: existingScore?.judge_3 || 0,
            judge_4: existingScore?.judge_4 || 0,
            judge_5: existingScore?.judge_5 || 0
        };
        embuDenda = existingScore?.denda || 0;
        embuWaktu = existingScore?.waktu || "00:00";
        selectedTiebreakRound = existingScore?.tiebreak_round || 0;
        autoDenda = true;
        showEmbuModal = true;
    }

    // Auto-calculate denda when waktu changes
    $effect(() => {
        if (!autoDenda || !showEmbuModal || !activeDrawing) return;
        const waktuStr = embuWaktu;
        const maxAthletes = matchState?.matchNumber?.max_athletes || 2;
        
        const parts = waktuStr.split(":");
        let seconds = 0;
        if (parts.length === 2) {
            seconds = parseInt(parts[0]) * 60 + parseInt(parts[1]);
        } else if (parts.length === 1 && !isNaN(parts[0])) {
            seconds = parseInt(parts[0]);
        }
        
        if (seconds <= 0) {
            embuDenda = 0;
            return;
        }

        let calculatedDenda = 0;
        if (maxAthletes > 2) {
            // Group category rules
            if (seconds >= 80 && seconds <= 89) {
                calculatedDenda = 5;
            } else if (seconds <= 79) {
                calculatedDenda = 10;
            } else if (seconds >= 121 && seconds <= 130) {
                calculatedDenda = 5;
            } else if (seconds >= 131) {
                calculatedDenda = 10;
            }
        } else {
            // Single/Pair category rules
            if (seconds >= 50 && seconds <= 59) {
                calculatedDenda = 5;
            } else if (seconds <= 49) {
                calculatedDenda = 10;
            } else if (seconds >= 91 && seconds <= 100) {
                calculatedDenda = 5;
            } else if (seconds >= 101) {
                calculatedDenda = 10;
            }
        }
        embuDenda = calculatedDenda;
    });

    async function saveEmbuScore() {
        isSaving = true;
        try {
            const res = await postJson("/admin/api/scoring/correction/embu/save", {
                match_id: matchState.matchNumber.id,
                registration_id: activeDrawing.registration_id,
                drawing_id: activeDrawing.id,
                round: activeDrawing.round,
                scores: embuScores,
                denda: embuDenda,
                waktu: embuWaktu,
                tiebreak_round: selectedTiebreakRound
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, "success");
                showEmbuModal = false;
                fetchMatchState();
            } else {
                showToast(data.message || "Gagal menyimpan nilai", "error");
            }
        } catch (e) {
            showToast("Terjadi kesalahan koneksi", "error");
        } finally {
            isSaving = false;
        }
    }

    // Randori Correction
    function openRandoriEdit(nodeKey, bracket, round, match, nodeData) {
        activeRandoriNode = { key: nodeKey, bracket, round, match, nodeData };
        const existingResult = matchState.randoriResults.find(r => r.bracket_node === nodeKey);
        if (existingResult) {
            randoriScoreRed = existingResult.score_red || 0;
            randoriScoreBlue = existingResult.score_blue || 0;
            const meta = (typeof existingResult.metadata === 'string' ? JSON.parse(existingResult.metadata) : existingResult.metadata) || {};
            randoriScoringAka = meta.scoringAka || { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
            randoriScoringShiro = meta.scoringShiro || { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
        } else {
            randoriScoreRed = 0;
            randoriScoreBlue = 0;
            randoriScoringAka = { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
            randoriScoringShiro = { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
        }
        showRandoriModal = true;
    }

    // Auto-sum randori score when criteria points change
    $effect(() => {
        if (!showRandoriModal) return;
        
        // Aka
        let sumAka = 
            (randoriScoringAka.ippon * 2) + 
            (randoriScoringAka.waza_ari * 1) + 
            (randoriScoringAka.mujoken_kachi ? 99 : 0) +
            (randoriScoringAka.yusei_kachi ? 1 : 0);
        randoriScoreRed = sumAka;

        // Shiro
        let sumShiro = 
            (randoriScoringShiro.ippon * 2) + 
            (randoriScoringShiro.waza_ari * 1) + 
            (randoriScoringShiro.mujoken_kachi ? 99 : 0) +
            (randoriScoringShiro.yusei_kachi ? 1 : 0);
        randoriScoreBlue = sumShiro;
    });

    async function saveRandoriScore() {
        if (randoriScoreRed === randoriScoreBlue) {
            alert("Skor sama! Silakan tentukan pemenang dengan menambahkan poin Yusei Kachi (1 poin) atau Mujoken Kachi (99 poin).");
            return;
        }
        isSaving = true;
        try {
            const res = await postJson("/admin/api/scoring/correction/randori/save", {
                match_id: matchState.matchNumber.id,
                bracket: activeRandoriNode.bracket,
                round: activeRandoriNode.round,
                match: activeRandoriNode.match,
                score_red: randoriScoreRed,
                score_blue: randoriScoreBlue,
                scoring_aka: randoriScoringAka,
                scoring_shiro: randoriScoringShiro
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, "success");
                showRandoriModal = false;
                fetchMatchState();
            } else {
                showToast(data.message || "Gagal menyimpan nilai", "error");
            }
        } catch (e) {
            showToast("Terjadi kesalahan koneksi", "error");
        } finally {
            isSaving = false;
        }
    }

    // Utility helper
    function getRegistrationScore(drawing) {
        if (!matchState) return null;
        return matchState.embuScores.find(s => 
            s.drawing_id === drawing.id || 
            (s.registration_id === drawing.registration_id && s.match_number_id === drawing.match_number_id)
        );
    }
</script>

<div class="tm-page">
    <div class="tm-hdr">
        <div>
            <h2>
                <i class="fa-solid fa-gavel" style="color:var(--ink);margin-right:8px;"></i>
                Koreksi Nilai & Denda
            </h2>
            <p>Modifikasi skor, waktu, dan denda hasil pertandingan resmi panitera.</p>
        </div>
        <button onclick={() => router.visit("/admin/new-scoring")} class="btn-gen ghost">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Scoring
        </button>
    </div>

    <!-- Selection Dropdown -->
    <div class="selection-card">
        <label for="match-select" class="field-label">PILIH NOMOR PERTANDINGAN</label>
        {#if isLoadingMatches}
            <div class="loading-inline">
                <i class="fas fa-spinner fa-spin"></i> Memuat daftar pertandingan...
            </div>
        {:else}
            <select 
                id="match-select" 
                class="select-field" 
                value={selectedMatchId} 
                onchange={handleMatchChange}
            >
                <option value="">-- Pilih Pertandingan --</option>
                {#each matches as m}
                    <option value={String(m.id)}>
                        [{m.draft_type.toUpperCase()}] {m.name}
                        {#if m.age_group?.name}
                            - {m.age_group.name}
                        {/if}
                        {#if m.gender}
                            ({m.gender === 'Male' ? 'Laki-laki' : m.gender === 'Female' ? 'Perempuan' : m.gender === 'Mix' ? 'Campuran' : m.gender})
                        {/if}
                    </option>
                {/each}
            </select>
        {/if}
    </div>

    <!-- Match State Data -->
    {#if isLoadingState}
        <div class="loading-state-card">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p>Mengambil data skor pertandingan...</p>
        </div>
    {:else if matchState}
        <div class="match-info-banner">
            <span class="badge {matchState.matchNumber.draft_type}">
                {matchState.matchNumber.draft_type.toUpperCase()}
            </span>
            <h2>{matchState.matchNumber.name}</h2>
            <p>
                ID Pertandingan: {matchState.matchNumber.id} 
                | Maksimal Atlet: {matchState.matchNumber.max_athletes}
                {#if matchState.matchNumber.age_group}
                    | Kategori Umur: {matchState.matchNumber.age_group.name}
                {/if}
                {#if matchState.matchNumber.gender}
                    | Gender: {matchState.matchNumber.gender === 'Male' ? 'Laki-laki' : matchState.matchNumber.gender === 'Female' ? 'Perempuan' : matchState.matchNumber.gender === 'Mix' ? 'Campuran' : matchState.matchNumber.gender}
                {/if}
            </p>
        </div>

        {#if matchState.matchNumber.draft_type === 'embu'}
            <!-- EMBU SCORE CORRECTION -->
            <div class="scores-card">
                <h3>Daftar Peserta & Nilai Embu</h3>
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Babak</th>
                                <th>Pool</th>
                                <th>Kontingen / Kenshi</th>
                                <th>Juri (1-5)</th>
                                <th>Waktu</th>
                                <th>Denda</th>
                                <th>Total</th>
                                <th>Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {#each matchState.drawings as drawing, idx}
                                {@const s = getRegistrationScore(drawing)}
                                <tr>
                                    <td>{idx + 1}</td>
                                    <td>
                                        <span class="badge round-label">{drawing.round}</span>
                                    </td>
                                    <td>{drawing.pool?.name || '—'}</td>
                                    <td>
                                        <div class="contingent-name">{drawing.registration?.contingent?.name || '—'}</div>
                                        <div class="athletes-list">
                                            {drawing.athletes?.map(a => a.name).join(' & ') || '—'}
                                        </div>
                                    </td>
                                    <td class="monospace font-bold">
                                        {#if s}
                                            {s.judge_1.toFixed(1)} / {s.judge_2.toFixed(1)} / {s.judge_3.toFixed(1)} / {s.judge_4.toFixed(1)} / {s.judge_5.toFixed(1)}
                                        {:else}
                                            —
                                        {/if}
                                    </td>
                                    <td class="monospace">{s?.waktu || '—'}</td>
                                    <td class="text-danger font-bold">-{s?.denda || 0}</td>
                                    <td class="monospace">{s?.total_score?.toFixed(1) || '—'}</td>
                                    <td class="monospace font-bold text-success" style="font-size: 15px;">
                                        {s?.nilai_akhir?.toFixed(1) || '—'}
                                    </td>
                                    <td>
                                        <button onclick={() => openEmbuEdit(drawing)} class="btn-action">
                                            <i class="fa-solid fa-pen-to-square"></i> Koreksi
                                        </button>
                                    </td>
                                </tr>
                            {:else}
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Belum ada peserta terdaftar.</td>
                                </tr>
                            {/each}
                        </tbody>
                    </table>
                </div>
            </div>
        {:else if matchState.matchNumber.draft_type === 'randori'}
            <!-- RANDORI SCORE CORRECTION -->
            <div class="scores-card">
                <h3>Daftar Match Node Bracket Randori</h3>
                <p class="section-hint">Mengubah pemenang di bracket akan otomatis merutekan ulang kontestan di babak selanjutnya jika Anda menyimpan.</p>
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Node ID</th>
                                <th>Babak / Posisi</th>
                                <th>Sudut Merah (Aka)</th>
                                <th>Sudut Putih (Shiro)</th>
                                <th>Skor Akhir</th>
                                <th>Pemenang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Gather all match nodes from bracket schema -->


                            {#each roundsUB as round, rIdx}
                                {#each round as mNode, mIdx}
                                    {@const nodeKey = `ub_${rIdx}_${mIdx}`}
                                    {@const res = matchState.randoriResults.find(r => r.bracket_node === nodeKey)}
                                    <tr>
                                        <td class="monospace">{nodeKey}</td>
                                        <td>Upper Bracket Round {rIdx + 1}</td>
                                        <td class="text-danger font-bold">{mNode.athlete1?.name || 'Menunggu'}</td>
                                        <td class="font-bold">{mNode.athlete2?.name || 'Menunggu'}</td>
                                        <td class="monospace font-bold">
                                            {#if res}
                                                {res.score_red} - {res.score_blue}
                                            {:else}
                                                Belum Tanding
                                            {/if}
                                        </td>
                                        <td>
                                            {#if res}
                                                <span class="badge {res.winner_color === 'athlete1' ? 'red' : 'white'}">
                                                    {res.winner_color === 'athlete1' ? 'Merah' : 'Putih'}
                                                </span>
                                            {:else}
                                                —
                                            {/if}
                                        </td>
                                        <td>
                                            {#if mNode.athlete1 && mNode.athlete2}
                                                <button onclick={() => openRandoriEdit(nodeKey, 'ub', rIdx, mIdx, mNode)} class="btn-action">
                                                    <i class="fa-solid fa-pen-to-square"></i> Koreksi
                                                </button>
                                            {:else}
                                                <button disabled class="btn-action disabled">Kunci</button>
                                            {/if}
                                        </td>
                                    </tr>
                                {/each}
                            {/each}

                            {#each roundsLB as round, rIdx}
                                {#each round as mNode, mIdx}
                                    {@const nodeKey = `lb_${rIdx}_${mIdx}`}
                                    {@const res = matchState.randoriResults.find(r => r.bracket_node === nodeKey)}
                                    <tr>
                                        <td class="monospace">{nodeKey}</td>
                                        <td>Lower Bracket Round {rIdx + 1}</td>
                                        <td class="text-danger font-bold">{mNode.athlete1?.name || 'Menunggu'}</td>
                                        <td class="font-bold">{mNode.athlete2?.name || 'Menunggu'}</td>
                                        <td class="monospace font-bold">
                                            {#if res}
                                                {res.score_red} - {res.score_blue}
                                            {:else}
                                                Belum Tanding
                                            {/if}
                                        </td>
                                        <td>
                                            {#if res}
                                                <span class="badge {res.winner_color === 'athlete1' ? 'red' : 'white'}">
                                                    {res.winner_color === 'athlete1' ? 'Merah' : 'Putih'}
                                                </span>
                                            {:else}
                                                —
                                            {/if}
                                        </td>
                                        <td>
                                            {#if mNode.athlete1 && mNode.athlete2}
                                                <button onclick={() => openRandoriEdit(nodeKey, 'lb', rIdx, mIdx, mNode)} class="btn-action">
                                                    <i class="fa-solid fa-pen-to-square"></i> Koreksi
                                                </button>
                                            {:else}
                                                <button disabled class="btn-action disabled">Kunci</button>
                                            {/if}
                                        </td>
                                    </tr>
                                {/each}
                            {/each}

                            {#if grandFinal}
                                {@const nodeKey = 'gf_0_0'}
                                {@const res = matchState.randoriResults.find(r => r.bracket_node === nodeKey)}
                                <tr>
                                    <td class="monospace">{nodeKey}</td>
                                    <td class="font-bold text-warning">GRAND FINAL</td>
                                    <td class="text-danger font-bold">{grandFinal.athlete1?.name || 'Menunggu'}</td>
                                    <td class="font-bold">{grandFinal.athlete2?.name || 'Menunggu'}</td>
                                    <td class="monospace font-bold">
                                        {#if res}
                                            {res.score_red} - {res.score_blue}
                                        {:else}
                                            Belum Tanding
                                        {/if}
                                    </td>
                                    <td>
                                        {#if res}
                                            <span class="badge {res.winner_color === 'athlete1' ? 'red' : 'white'}">
                                                {res.winner_color === 'athlete1' ? 'Merah' : 'Putih'}
                                            </span>
                                        {:else}
                                            —
                                        {/if}
                                    </td>
                                    <td>
                                        {#if grandFinal.athlete1 && grandFinal.athlete2}
                                            <button onclick={() => openRandoriEdit(nodeKey, 'gf', 0, 0, grandFinal)} class="btn-action">
                                                <i class="fa-solid fa-pen-to-square"></i> Koreksi
                                            </button>
                                        {:else}
                                            <button disabled class="btn-action disabled">Kunci</button>
                                        {/if}
                                    </td>
                                </tr>
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        {/if}
    {:else}
        <div class="empty-state-card">
            <i class="fa-solid fa-edit fa-4x text-muted"></i>
            <h3>Silakan pilih nomor pertandingan di atas</h3>
            <p>Gunakan opsi dropdown untuk memuat data penilaian atlet.</p>
        </div>
    {/if}
</div>

<!-- EMBU MODAL -->
{#if showEmbuModal}
    <div class="modal-overlay">
        <div class="modal-content">
            <div class="modal-hdr">
                <h3>Koreksi Nilai Embu</h3>
                <button onclick={() => (showEmbuModal = false)} class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="participant-banner">
                    <div class="contingent-label">KONTINGEN: {activeDrawing.registration?.contingent?.name}</div>
                    <div class="kenshi-label">Kenshi: {activeDrawing.athletes?.map(a => a.name).join(' & ') || '—'}</div>
                </div>

                <div class="form-grid">
                    {#each [1, 2, 3, 4, 5] as num}
                        <div class="form-group">
                            <label for="juri-{num}" class="form-label">JURI {num}</label>
                            <input 
                                id="juri-{num}"
                                type="number" 
                                step="0.1" 
                                min="0" 
                                max="10" 
                                bind:value={embuScores[`judge_${num}`]} 
                                class="form-input" 
                                placeholder="0.0"
                            />
                        </div>
                    {/each}

                    <div class="form-group">
                        <label for="waktu-field" class="form-label">DURASI (MM:SS)</label>
                        <input 
                            id="waktu-field"
                            type="text" 
                            bind:value={embuWaktu} 
                            class="form-input" 
                            placeholder="01:30"
                        />
                    </div>

                    <div class="form-group col-span-2">
                        <div class="toggle-container">
                            <label class="switch">
                                <input type="checkbox" bind:checked={autoDenda} />
                                <span class="slider round"></span>
                            </label>
                            <span class="toggle-text">Sesuaikan Denda Otomatis (Auto-Calculate)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="denda-field" class="form-label text-danger font-bold">DENDA PENALTY</label>
                        <input 
                            id="denda-field"
                            type="number" 
                            step="1" 
                            bind:value={embuDenda} 
                            class="form-input text-danger font-bold" 
                            disabled={autoDenda}
                            placeholder="0"
                        />
                    </div>

                    <div class="form-group">
                        <label for="tiebreak-select" class="form-label">TIPE PENILAIAN</label>
                        <select id="tiebreak-select" class="form-input" bind:value={selectedTiebreakRound}>
                            <option value={0}>Skor Utama (Main Score)</option>
                            <option value={1}>Tiebreak ke-1 (Rereplay)</option>
                            <option value={2}>Tiebreak ke-2</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button onclick={() => (showEmbuModal = false)} class="btn-cancel">Batal</button>
                    <button onclick={saveEmbuScore} disabled={isSaving} class="btn-save">
                        {#if isSaving}
                            <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                        {:else}
                            <i class="fas fa-check"></i> Simpan Koreksi
                        {/if}
                    </button>
                </div>
            </div>
        </div>
    </div>
{/if}

<!-- RANDORI MODAL -->
{#if showRandoriModal}
    <div class="modal-overlay">
        <div class="modal-content large">
            <div class="modal-hdr">
                <h3>Koreksi Skor Randori</h3>
                <button onclick={() => (showRandoriModal = false)} class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="node-banner">
                    <h4>NODE: {activeRandoriNode.key}</h4>
                    <p>{activeRandoriNode.nodeData.athlete1?.name} (Aka) vs {activeRandoriNode.nodeData.athlete2?.name} (Shiro)</p>
                </div>

                <div class="randori-scoring-layout">
                    <!-- SUDUT MERAH -->
                    <div class="sudut-box aka">
                        <div class="sudut-header">AKA (SUDUT MERAH)</div>
                        <div class="sudut-body">
                            <div class="athlete-name-label">{activeRandoriNode.nodeData.athlete1?.name}</div>
                            <div class="form-group">
                                <label class="form-label">Ippon (2 Poin)</label>
                                <input type="number" min="0" bind:value={randoriScoringAka.ippon} class="form-input" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Waza-ari (1 Poin)</label>
                                <input type="number" min="0" bind:value={randoriScoringAka.waza_ari} class="form-input" />
                            </div>
                            <div class="form-group">
                                <div class="toggle-container">
                                    <label class="switch">
                                        <input type="checkbox" bind:checked={randoriScoringAka.yusei_kachi} />
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="toggle-text">Yusei Kachi (Keunggulan)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="toggle-container">
                                    <label class="switch">
                                        <input type="checkbox" bind:checked={randoriScoringAka.mujoken_kachi} />
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="toggle-text">Mujoken Kachi (Mutlak)</span>
                                </div>
                            </div>
                            <div class="total-points text-danger">Total: {randoriScoreRed}</div>
                        </div>
                    </div>

                    <!-- SUDUT PUTIH -->
                    <div class="sudut-box shiro">
                        <div class="sudut-header">SHIRO (SUDUT PUTIH)</div>
                        <div class="sudut-body">
                            <div class="athlete-name-label">{activeRandoriNode.nodeData.athlete2?.name}</div>
                            <div class="form-group">
                                <label class="form-label">Ippon (2 Poin)</label>
                                <input type="number" min="0" bind:value={randoriScoringShiro.ippon} class="form-input" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Waza-ari (1 Poin)</label>
                                <input type="number" min="0" bind:value={randoriScoringShiro.waza_ari} class="form-input" />
                            </div>
                            <div class="form-group">
                                <div class="toggle-container">
                                    <label class="switch">
                                        <input type="checkbox" bind:checked={randoriScoringShiro.yusei_kachi} />
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="toggle-text">Yusei Kachi (Keunggulan)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="toggle-container">
                                    <label class="switch">
                                        <input type="checkbox" bind:checked={randoriScoringShiro.mujoken_kachi} />
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="toggle-text">Mujoken Kachi (Mutlak)</span>
                                </div>
                            </div>
                            <div class="total-points">Total: {randoriScoreBlue}</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button onclick={() => (showRandoriModal = false)} class="btn-cancel">Batal</button>
                    <button onclick={saveRandoriScore} disabled={isSaving} class="btn-save">
                        {#if isSaving}
                            <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                        {:else}
                            <i class="fas fa-check"></i> Simpan Koreksi Randori
                        {/if}
                    </button>
                </div>
            </div>
        </div>
    </div>
{/if}

<!-- TOAST -->
{#if toast.show}
    <div class="toast-notification {toast.type}">
        <i class="fas {toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>{toast.message}</span>
    </div>
{/if}

<style>
    .tm-page {
        padding: 24px;
        background: var(--paper, #f7f4ef);
        min-height: 100vh;
    }

    .tm-hdr {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .tm-hdr h2 {
        font-family: "Cinzel", serif;
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 4px;
        color: var(--ink, #0f0d0b);
        text-transform: uppercase;
        display: flex;
        align-items: center;
    }

    .tm-hdr p {
        font-size: 12px;
        color: var(--smoke, #b5afa6);
        margin: 0;
    }

    .btn-gen {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        border: none;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        font-family: "DM Sans", sans-serif;
        transition: all 0.15s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
    }

    .btn-gen.primary {
        background: var(--ink, #0f0d0b);
        color: #fff;
    }

    .btn-gen.primary:hover {
        background: #1a252f;
        transform: translateY(-1px);
    }

    .btn-gen.danger {
        background: var(--red, #c0392b);
        color: #fff;
        box-shadow: 0 4px 12px rgba(192, 57, 43, 0.25);
    }

    .btn-gen.danger:hover {
        background: #a93226;
        transform: translateY(-1px);
    }

    .btn-gen.ghost {
        background: #fff;
        color: var(--smoke, #b5afa6);
        border: 1px solid var(--paper2, #ede9e1);
    }

    .btn-gen.ghost:hover {
        border-color: var(--ink);
        color: var(--ink);
    }

    .selection-card {
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid var(--paper2, #ede9e1);
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .field-label {
        font-size: 10px;
        font-weight: 800;
        color: var(--smoke, #b5afa6);
        letter-spacing: 0.1em;
        display: block;
        margin-bottom: 8px;
    }

    .select-field {
        width: 100%;
        background: var(--paper, #f7f4ef);
        border: 1px solid var(--paper2, #ede9e1);
        color: var(--ink, #0f0d0b);
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }

    .select-field:focus {
        border-color: var(--ink);
    }

    .loading-inline {
        color: var(--ink);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .loading-state-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 16px;
        color: var(--ink);
        gap: 16px;
        border: 1px solid var(--paper2);
    }

    .empty-state-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 80px 20px;
        background: #fff;
        border: 1px dashed var(--paper2);
        border-radius: 16px;
        text-align: center;
    }

    .empty-state-card h3 {
        color: var(--ink);
        margin: 16px 0 8px 0;
        font-size: 18px;
        font-family: "Cinzel", serif;
    }

    .empty-state-card p {
        color: var(--smoke);
        font-size: 13px;
        margin: 0;
    }

    .match-info-banner {
        background: #fff;
        border-left: 4px solid var(--red, #c0392b);
        border-top: 1px solid var(--paper2);
        border-right: 1px solid var(--paper2);
        border-bottom: 1px solid var(--paper2);
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .match-info-banner h2 {
        font-family: "Cinzel", serif;
        color: var(--ink);
        margin: 8px 0 4px 0;
        font-size: 20px;
        font-weight: 700;
    }

    .match-info-banner p {
        margin: 0;
        font-size: 12px;
        color: var(--smoke);
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 20px;
        letter-spacing: 0.05em;
    }

    .badge.embu {
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
    }

    .badge.randori {
        background: rgba(192, 57, 43, 0.1);
        color: var(--red);
    }

    .badge.round-label {
        background: var(--paper);
        color: var(--ink);
        border: 1px solid var(--paper2);
    }

    .badge.red {
        background: var(--red);
        color: #fff;
    }

    .badge.white {
        background: #fff;
        color: var(--ink);
        border: 1px solid var(--paper2);
    }

    .scores-card {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--paper2, #ede9e1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .scores-card h3 {
        font-family: "Cinzel", serif;
        margin: 0 0 16px 0;
        color: var(--ink);
        font-size: 16px;
        font-weight: 700;
        border-bottom: 1px solid var(--paper2);
        padding-bottom: 12px;
    }

    .section-hint {
        font-size: 11px;
        color: var(--smoke);
        margin: -10px 0 16px 0;
        font-style: italic;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .premium-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .premium-table th {
        background: #fdfbf7;
        color: var(--smoke);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 12px 14px;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--paper2);
    }

    .premium-table td {
        padding: 12px 14px;
        font-size: 12.5px;
        border-bottom: 1px solid var(--paper2);
        color: var(--ink);
    }

    .contingent-name {
        font-weight: 700;
        color: var(--ink);
    }

    .athletes-list {
        font-size: 11px;
        color: var(--smoke);
        margin-top: 2px;
    }

    .monospace {
        font-family: 'Courier New', Courier, monospace;
    }

    .font-bold {
        font-weight: 700;
    }

    .text-danger {
        color: var(--red);
    }

    .text-success {
        color: #27ae60;
    }

    .btn-action {
        background: #fff;
        border: 1px solid var(--paper2);
        color: var(--smoke);
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        border-color: var(--ink);
        color: var(--ink);
    }

    .btn-action.disabled {
        background: var(--paper);
        border-color: var(--paper2);
        color: var(--smoke);
        cursor: not-allowed;
    }

    /* MODAL STYLES */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: #fff;
        width: 100%;
        max-width: 480px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }

    .modal-content.large {
        max-width: 800px;
    }

    @keyframes scaleUp {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-hdr {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fdfbf7;
        padding: 20px 24px;
        border-bottom: 1px solid var(--paper2);
    }

    .modal-hdr h3 {
        margin: 0;
        color: var(--ink);
        font-family: "Cinzel", serif;
        font-size: 16px;
        font-weight: 700;
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
    }

    .participant-banner, .node-banner {
        background: var(--paper);
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid var(--paper2);
    }

    .contingent-label {
        font-size: 10px;
        font-weight: 800;
        color: var(--smoke);
    }

    .kenshi-label {
        color: var(--ink);
        font-size: 14px;
        font-weight: 700;
        margin-top: 4px;
    }

    .node-banner h4 {
        margin: 0;
        color: var(--red);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.15em;
    }

    .node-banner p {
        margin: 4px 0 0 0;
        color: var(--ink);
        font-size: 16px;
        font-weight: 700;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .col-span-2 {
        grid-column: span 2;
    }

    .form-label {
        font-size: 10px;
        font-weight: 800;
        color: var(--smoke);
        margin-bottom: 6px;
        letter-spacing: 0.05em;
    }

    .form-input {
        background: var(--paper);
        border: 1px solid var(--paper2);
        color: var(--ink);
        padding: 10px 14px;
        border-radius: 12px;
        outline: none;
        font-size: 14px;
        font-weight: 700;
        transition: border-color 0.2s ease;
        text-align: center;
    }

    select.form-input {
        text-align: left;
        text-align-last: center;
    }

    .form-input:focus {
        border-color: var(--ink);
    }

    .form-input:disabled {
        background: var(--paper2);
        color: var(--smoke);
        cursor: not-allowed;
    }

    .toggle-container {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 8px 0;
    }

    .toggle-text {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
    }

    /* Switch styling */
    .switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--paper);
        transition: .4s;
        border: 1px solid var(--paper2);
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 12px;
        width: 12px;
        left: 3px;
        bottom: 3px;
        background-color: var(--smoke);
        transition: .4s;
    }

    input:checked + .slider {
        background-color: rgba(39, 174, 96, 0.15);
        border-color: #27ae60;
    }

    input:checked + .slider:before {
        transform: translateX(18px);
        background-color: #27ae60;
    }

    .slider.round {
        border-radius: 20px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        border-top: 1px solid var(--paper2);
        padding-top: 16px;
    }

    .btn-cancel {
        background: #fff;
        border: 1px solid var(--paper2);
        color: var(--smoke);
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-cancel:hover {
        border-color: var(--ink);
        color: var(--ink);
    }

    .btn-save {
        background: var(--ink);
        border: 1px solid var(--ink);
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-save:hover {
        background: #1a252f;
        border-color: #1a252f;
        transform: translateY(-1px);
    }

    .btn-save:disabled {
        background: var(--paper2);
        border-color: var(--paper2);
        color: var(--smoke);
        cursor: not-allowed;
        transform: none;
    }

    /* RANDORI OVERVIEW IN MODAL */
    .randori-scoring-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .sudut-box {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--paper2);
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .sudut-box.aka {
        border-color: var(--red);
    }

    .sudut-box.shiro {
        border-color: var(--smoke);
    }

    .sudut-header {
        padding: 10px 16px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-align: center;
    }

    .sudut-box.aka .sudut-header {
        background: rgba(192, 57, 43, 0.1);
        color: var(--red);
    }

    .sudut-box.shiro .sudut-header {
        background: var(--paper);
        color: var(--ink);
    }

    .sudut-body {
        padding: 20px;
    }

    .athlete-name-label {
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 16px;
        text-align: center;
        color: var(--ink);
    }

    .total-points {
        margin-top: 16px;
        font-size: 18px;
        font-weight: 800;
        text-align: center;
        padding-top: 12px;
        border-top: 1px dashed var(--paper2);
    }

    .total-points.text-danger {
        color: var(--red);
    }

    /* TOAST STYLE */
    .toast-notification {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #fff;
        border-left: 4px solid var(--ink);
        border-top: 1px solid var(--paper2);
        border-right: 1px solid var(--paper2);
        border-bottom: 1px solid var(--paper2);
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1100;
        color: var(--ink);
        font-weight: 600;
        font-size: 13px;
        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .toast-notification.success {
        border-left-color: #27ae60;
    }

    .toast-notification.success i {
        color: #27ae60;
    }

    .toast-notification.error {
        border-left-color: var(--red);
    }

    .toast-notification.error i {
        color: var(--red);
    }

    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
