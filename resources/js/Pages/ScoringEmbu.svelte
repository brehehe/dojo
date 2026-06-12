<script>
    import { onMount, onDestroy } from "svelte";
    import { router } from "@inertiajs/svelte";

    let { matchId, urlRound = null, urlPoolId = null, urlFrom = null } = $props();

    const backRoute = urlFrom === 'panggil-drawing' ? '/admin/panitera/panggil-drawing' : '/admin/new-scoring';

    // State
    let matchNumber = $state(null);
    let merge = $state(null);
    let displayName = $state("");
    let currentRound = $state(urlRound || "Penyisihan");
    let selectedPoolId = $state(urlPoolId ? Number(urlPoolId) : null);
    let registrations = $state([]);
    let firstDrawing = $state(null);
    let availablePools = $state([]);
    let tiedIds = $state([]);
    let activeDrawingId = $state(null);
    let assignedArbitrase = $state(null);
    let assignedReferees = $state([]);
    let assignedKoordinators = $state([]);
    let assignedPaniteras = $state([]);
    let timerState = $state({
        status: "stopped",
        elapsed_ms: 0,
        started_at_ms: null,
        countdown_end_ms: null,
    });
    let courtId = $state(null);

    // Audio & Announcer state
    let isPlayingAnnouncer = $state(false);
    let currentAudio = null;

    // Modal wasit override
    let showModal = $state(false);
    let activeRegistrationIdForModal = $state(null);
    let activeRegistrationNameForModal = $state("");
    let modalScores = $state({
        judge_1: 0,
        judge_2: 0,
        judge_3: 0,
        judge_4: 0,
        judge_5: 0,
    });
    let modalDenda = $state(0);

    // Polling interval
    let pollInterval;

    // Timer interpolation state
    let time = $state(0);
    let running = $state(false);
    let countdown = $state(0);
    let offset = $state(0);
    let lastTickSecond = -1;
    let playedIntervals = new Set();
    let interpolInterval;

    // Derived state
    let sessionDate = $derived(
        firstDrawing?.session_time?.date ||
            new Date().toISOString().split("T")[0],
    );
    let courtOrder = $derived(firstDrawing?.court?.order || "-");
    let poolName = $derived(
        firstDrawing?.pool?.name || firstDrawing?.metadata?.pool || "-",
    );

    async function fetchState() {
        try {
            const queryParams = new URLSearchParams();
            if (currentRound) queryParams.append("round", currentRound);
            if (selectedPoolId) queryParams.append("pool_id", selectedPoolId);

            const res = await fetch(
                `/admin/api/scoring/embu/${matchId}/state?${queryParams.toString()}`,
            );
            const data = await res.json();
            if (data) {
                matchNumber = data.matchNumber;
                merge = data.merge;
                displayName = data.displayName;
                currentRound = data.currentRound;
                selectedPoolId = data.selectedPoolId;
                registrations = data.registrations;
                firstDrawing = data.firstDrawing;
                availablePools = data.availablePools;
                tiedIds = data.tiedIds;
                activeDrawingId = data.activeDrawingId;
                assignedArbitrase = data.assignedArbitrase;
                assignedReferees = data.assignedReferees;
                assignedKoordinators = data.assignedKoordinators;
                assignedPaniteras = data.assignedPaniteras;
                timerState = data.timerState;
                courtId = data.courtId;

                // Sync Timer local state with server state
                offset = (timerState.server_time_ms || Date.now()) - Date.now();
                running = timerState.status === "running";
                if (timerState.status !== "countdown") {
                    countdown = 0;
                }
                if (!running && time < 500) {
                    playedIntervals.clear();
                }
            }
        } catch (e) {
            console.error("Error fetching Embu scoring state:", e);
        }
    }

    // Timer controls
    async function startTimer() {
        if (!courtId) return;
        try {
            const res = await fetch("/admin/api/scoring/timer-control", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify({ court_id: courtId, action: "start" }),
            });
            const data = await res.json();
            if (data.success) {
                timerState = data.timer_state;
                running = true;
                if (time < 1000) {
                    playBuzzer(
                        "/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3",
                    );
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function pauseTimer() {
        if (!courtId) return;
        try {
            const res = await fetch("/admin/api/scoring/timer-control", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify({ court_id: courtId, action: "pause" }),
            });
            const data = await res.json();
            if (data.success) {
                timerState = data.timer_state;
                running = false;
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function stopTimer() {
        if (!courtId) return;
        try {
            const res = await fetch("/admin/api/scoring/timer-control", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify({ court_id: courtId, action: "stop" }),
            });
            const data = await res.json();
            if (data.success) {
                timerState = data.timer_state;
                time = 0;
                running = false;
                countdown = 0;
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function finishMatch(drawingId) {
        let capturedTime = time;
        running = false;
        playBuzzerDouble(
            "/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3",
        );
        try {
            // First pause the timer on server
            await fetch("/admin/api/scoring/timer-control", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify({ court_id: courtId, action: "pause" }),
            });

            // Finish the match on server
            const res = await fetch("/admin/api/scoring/embu/finish-match", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify({
                    drawing_id: drawingId,
                    time_ms: capturedTime,
                    round: currentRound,
                }),
            });
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                fetchState();
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Call participant
    async function callParticipant(drawingId) {
        try {
            const res = await fetch(
                "/admin/api/scoring/embu/call-participant",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify({ drawing_id: drawingId }),
                },
            );
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                if (data.announcement_text) {
                    playAnnouncer(data.announcement_text);
                }
                fetchState();
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Dismiss participant
    async function dismissParticipant() {
        try {
            const res = await fetch(
                "/admin/api/scoring/embu/dismiss-participant",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify({
                        match_id: matchId,
                        court_id: courtId,
                    }),
                },
            );
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                fetchState();
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Call officials
    async function callOfficials() {
        try {
            const res = await fetch("/admin/api/scoring/embu/call-officials", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify({
                    match_id: matchId,
                    round: currentRound,
                    pool_id: selectedPoolId,
                }),
            });
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                if (data.announcement_text) {
                    playAnnouncer(data.announcement_text);
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Clear all courts
    async function clearAllCourts() {
        if (
            !confirm(
                "PERINGATAN: Ini akan mereset status SEMUA lapangan & match yang sedang berjalan menjadi KOSONG. Lanjutkan?",
            )
        )
            return;
        try {
            const res = await fetch("/admin/api/scoring/clear-all-courts", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
            });
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                fetchState();
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Tanding Ulang / request tiebreak
    async function requestTiebreak() {
        if (!confirm("Lakukan tanding ulang untuk nilai yang seri ini?"))
            return;
        try {
            const res = await fetch(
                "/admin/api/scoring/embu/request-tiebreak",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify({
                        match_id: matchId,
                        registration_ids: tiedIds,
                        round: currentRound,
                    }),
                },
            );
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                fetchState();
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Advance to Final
    async function advanceToFinal() {
        if (!confirm("Siap loloskan ke Final babak ini?")) return;
        try {
            const res = await fetch(
                "/admin/api/scoring/embu/advance-to-final",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify({
                        match_id: matchId,
                    }),
                },
            );
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                currentRound = "Final";
                fetchState();
            } else {
                alert(data.message || "Gagal loloskan ke final.");
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Change Pool
    function setPool(poolId) {
        selectedPoolId = poolId;
        fetchState();
    }

    // Open Scoring override modal
    function openScoringModal(item) {
        activeRegistrationIdForModal = item.id;
        activeRegistrationNameForModal = item.athletes
            .map((a) => a.name)
            .join(" & ");
        modalScores = {
            judge_1: item.score?.judge_1 || 0,
            judge_2: item.score?.judge_2 || 0,
            judge_3: item.score?.judge_3 || 0,
            judge_4: item.score?.judge_4 || 0,
            judge_5: item.score?.judge_5 || 0,
        };
        modalDenda = item.score?.denda || 0;
        showModal = true;
    }

    // Save Scoring override
    async function saveScore() {
        try {
            const res = await fetch("/admin/api/scoring/embu/save-score", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify({
                    match_id: matchId,
                    registration_id: activeRegistrationIdForModal,
                    round: currentRound,
                    scores: modalScores,
                    denda: modalDenda,
                }),
            });
            const data = await res.json();
            if (data.success) {
                alert(data.text);
                showModal = false;
                fetchState();
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Format time helpers
    function formatTime(t) {
        let maxT = Math.max(0, t);
        let m = Math.floor(maxT / 60000);
        let s = Math.floor((maxT % 60000) / 1000);
        return `${m.toString().padStart(2, "0")}:${s.toString().padStart(2, "0")}`;
    }

    function formatCountdown(c) {
        if (c === 5) return "Siap";
        if (c === 4) return "3";
        if (c === 3) return "2";
        if (c === 2) return "1";
        if (c === 1) return "Mulai";
        return c > 0 ? c.toString() : "";
    }

    // Audio functions
    function playBuzzer(src) {
        try {
            const audio = new Audio(src);
            audio.play().catch((e) => console.warn("Buzzer error:", e));
        } catch (e) {
            console.warn("Audio error:", e);
        }
    }

    function playBuzzerDouble(src) {
        playBuzzer(src);
        setTimeout(() => playBuzzer(src), 800);
    }

    function playAnnouncer(text) {
        console.log("Announcer requested:", text);
        stopAnnouncer();
        isPlayingAnnouncer = true;

        function playBeepAndSpeak() {
            if (!isPlayingAnnouncer) return;

            currentAudio = new Audio("/asset/music/nada-suara.mp3");
            currentAudio.volume = 0.6;

            let playPromise = currentAudio.play();

            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        currentAudio.onended = () => {
                            if (!isPlayingAnnouncer) return;
                            setTimeout(() => speak(text), 500);
                        };
                    })
                    .catch(() => {
                        speak(text);
                    });
            } else {
                speak(text);
            }
        }

        function speak(rawText) {
            if (!isPlayingAnnouncer) return;

            window.speechSynthesis.cancel();
            const speechText = rawText
                .toLowerCase()
                .replace(/\./g, ". ")
                .replace(/,/g, ", ")
                .replace(/-/g, " ")
                .replace(/\s+/g, " ")
                .trim();

            const speech = new SpeechSynthesisUtterance(speechText);
            speech.lang = "id-ID";
            speech.rate = 1.1;
            speech.pitch = 1;
            speech.volume = 1;

            function setVoice() {
                const voices = window.speechSynthesis.getVoices();
                let voice =
                    voices.find((v) =>
                        v.name.includes("Google Bahasa Indonesia"),
                    ) ||
                    voices.find((v) => v.lang === "id-ID") ||
                    voices[0];

                if (voice) {
                    speech.voice = voice;
                }

                speech.onend = () => {
                    stopAnnouncer();
                };

                window.speechSynthesis.speak(speech);
            }

            if (window.speechSynthesis.getVoices().length) {
                setVoice();
            } else {
                window.speechSynthesis.onvoiceschanged = setVoice;
            }
        }

        playBeepAndSpeak();
    }

    function stopAnnouncer() {
        isPlayingAnnouncer = false;
        window.speechSynthesis.cancel();
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
        }
    }

    // Lifecycle
    onMount(() => {
        fetchState();

        // 300ms Polling for state
        pollInterval = setInterval(fetchState, 300);

        // 30ms Interpolation for local timer
        interpolInterval = setInterval(() => {
            if (running && timerState.started_at_ms) {
                let expected =
                    (timerState.elapsed_ms || 0) +
                    (Date.now() + offset - timerState.started_at_ms);
                time = expected;
                let currentSecond = Math.floor(time / 1000);

                // Get active registration info to see if Tandoku
                const activeReg = activeRegItem;
                let isTandoku = activeReg ? !activeReg.is_group : true;
                let buzzerSound =
                    "/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3";

                if (isTandoku) {
                    if (
                        (currentSecond === 60 && !playedIntervals.has(60)) ||
                        (currentSecond === 90 && !playedIntervals.has(90)) ||
                        (currentSecond === 120 && !playedIntervals.has(120))
                    ) {
                        playBuzzer(buzzerSound);
                        playedIntervals.add(currentSecond);
                    }
                } else {
                    if (
                        (currentSecond === 90 && !playedIntervals.has(90)) ||
                        (currentSecond === 120 && !playedIntervals.has(120))
                    ) {
                        playBuzzer(buzzerSound);
                        playedIntervals.add(currentSecond);
                    }
                }

                if (currentSecond > lastTickSecond) {
                    lastTickSecond = currentSecond;
                }
            } else if (
                timerState.status === "countdown" &&
                timerState.countdown_end_ms
            ) {
                let remaining =
                    timerState.countdown_end_ms - (Date.now() + offset);
                countdown = remaining > 0 ? Math.ceil(remaining / 1000) : 0;
                time = timerState.elapsed_ms || 0;
                if (remaining <= 0) {
                    // Trigger timer start
                    startTimer();
                }
                lastTickSecond = Math.floor(time / 1000);
            } else {
                countdown = 0;
                time = timerState.elapsed_ms || 0;
                lastTickSecond = Math.floor(time / 1000);
            }
        }, 30);

        document.addEventListener(
            "click",
            () => {
                if (
                    window.sharedAudioCtx &&
                    window.sharedAudioCtx.state === "suspended"
                ) {
                    window.sharedAudioCtx.resume();
                }
            },
            { once: true },
        );
    });

    onDestroy(() => {
        clearInterval(pollInterval);
        clearInterval(interpolInterval);
        stopAnnouncer();
    });

    // Leaderboard state
    let ranked = $derived.by(() => {
        return registrations
            .filter(
                (i) =>
                    i.score?.nilai_akhir > 0 ||
                    (currentRound === "Final" && i.accumulated_score > 0),
            )
            .sort((a, b) => {
                const scoreA =
                    currentRound === "Penyisihan"
                        ? a.score?.nilai_akhir || 0
                        : a.accumulated_score || 0;
                const scoreB =
                    currentRound === "Penyisihan"
                        ? b.score?.nilai_akhir || 0
                        : b.accumulated_score || 0;
                return scoreB - scoreA;
            });
    });

    // All checked scores in Penyisihan
    let hasAllScores = $derived.by(() => {
        return (
            registrations.length > 0 &&
            registrations.every((r) => r.score !== null)
        );
    });

    // Active registration item details
    let activeRegItem = $derived(
        activeDrawingId && registrations
            ? registrations.find(
                  (r) => Number(r.drawing_id) === Number(activeDrawingId),
              ) || null
            : null,
    );
</script>

<div class="tm-page">
    <div style="position: fixed; top: 20px; right: 30px; z-index: 90;">
        <button
            onclick={clearAllCourts}
            class="btn-gen danger"
            style="padding: 12px 20px; border-radius: 12px; font-size: 12px; box-shadow: 0 8px 24px rgba(192,57,43,.3);"
        >
            <i class="fas fa-eraser" style="margin-right: 8px;"></i>
            <span class="hidden md:inline">Reset Semua Lapangan</span>
            <span class="md:hidden">Reset</span>
        </button>
    </div>

    <div class="tm-hdr">
        <div>
            <div class="tm-badge-title">
                {merge?.name || matchNumber?.name || "EMBU"}
            </div>
            {#if merge}
                <div
                    style="font-size: 11px; color: var(--smoke); font-weight: 600; margin-top: 4px; font-style: italic;"
                >
                    {displayName}
                </div>
            {/if}
            <h2>Daftar Kompilasi Nilai</h2>
        </div>
        <div style="display:flex; gap:12px;">
            <button
                onclick={callOfficials}
                class="btn-gen primary"
                style="background:var(--red); box-shadow:0 4px 12px rgba(192,57,43,0.2);"
            >
                <i class="fas fa-bullhorn"></i> Panggil Official
            </button>
            <button
                onclick={stopAnnouncer}
                class="btn-gen ghost"
                style="color:var(--red); border-color:var(--red);"
            >
                <i class="fas fa-volume-xmark"></i> Stop Suara
            </button>
            <a href={backRoute} class="btn-gen ghost">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Court monitors links shortcut -->
    {#if courtId}
        <div
            style="background:#fff; border:1px solid var(--paper2); border-radius:16px; padding:16px 20px; margin-bottom:20px; display:flex; flex-direction:column; gap:12px;"
        >
            <div
                style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; letter-spacing:0.1em; display:flex; align-items:center; gap:6px;"
            >
                <i class="fas fa-desktop" style="color:var(--red);"></i> Monitor
                Lapangan (Shortcut)
            </div>
            <div
                style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:10px;"
            >
                <a
                    href={`/admin/arbitrase/scoring/monitor/${courtId}`}
                    target="_blank"
                    class="btn-gen ghost"
                    style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;"
                >
                    <i class="fas fa-bullhorn" style="margin-right:6px;"></i> Panggilan
                </a>
                <a
                    href={`/admin/arbitrase/scoring/monitor-hasil/match/${matchId}?round=${encodeURIComponent(currentRound || "")}&pool_id=${selectedPoolId || ""}`}
                    target="_blank"
                    class="btn-gen ghost"
                    style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;"
                >
                    <i class="fas fa-tv" style="margin-right:6px;"></i> Hasil
                </a>
                <a
                    href={`/admin/arbitrase/scoring/monitor-timer/court/${courtId}`}
                    target="_blank"
                    class="btn-gen ghost"
                    style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;"
                >
                    <i class="fas fa-stopwatch" style="margin-right:6px;"></i> Timer
                </a>
                <a
                    href={`/admin/arbitrase/scoring/monitor-rekapitulasi-hasil/court/${courtId}`}
                    target="_blank"
                    class="btn-gen ghost"
                    style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;"
                >
                    <i class="fas fa-list-ol" style="margin-right:6px;"></i> Rekapitulasi
                </a>
                <a
                    href={`/admin/arbitrase/scoring/monitor-referee/court/${courtId}`}
                    target="_blank"
                    class="btn-gen ghost"
                    style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;"
                >
                    <i class="fas fa-user-tie" style="margin-right:6px;"></i> Wasit
                </a>
            </div>
        </div>
    {/if}

    <div class="round-indicator">
        <div class="round-step {currentRound === 'Penyisihan' ? 'active' : ''}">
            <i class="fas fa-filter"></i> 1. Penyisihan
        </div>
        <i class="fas fa-chevron-right" style="color:var(--smoke);"></i>
        <div class="round-step {currentRound === 'Final' ? 'active' : ''}">
            <i class="fas fa-trophy"></i> 2. Final
        </div>
    </div>

    {#if availablePools && availablePools.length > 1}
        <div
            style="display:flex; justify-content:center; gap:8px; margin-bottom:20px;"
        >
            {#each availablePools as p}
                <button
                    onclick={() => setPool(p.id)}
                    class="btn-gen {selectedPoolId === p.id
                        ? 'primary'
                        : 'ghost'}"
                >
                    {p.name}
                </button>
            {/each}
        </div>
    {/if}

    <div class="info-bar">
        <div class="info-item">
            <span class="info-label">Hari / Tanggal</span>
            <span class="info-value"
                ><i class="far fa-calendar-alt"></i>
                {sessionDate}</span
            >
        </div>
        <div class="info-item">
            <span class="info-label">Tingkat / Golongan</span>
            <span class="info-value"
                ><i class="fas fa-layer-group"></i>
                {matchNumber?.age_group?.name || "-"}</span
            >
        </div>
        <div class="info-item">
            <span class="info-label">Pool</span>
            <span class="info-value"
                ><i class="fas fa-door-open"></i> {poolName}</span
            >
        </div>
        <div class="info-item">
            <span class="info-label">Lapangan (Court)</span>
            <span class="info-value"
                ><i class="fas fa-vector-square"></i> C{courtOrder}</span
            >
        </div>
    </div>

    <!-- Tiebreak / Advance banner -->
    {#if tiedIds && tiedIds.length > 0}
        <div
            style="background:rgba(192,57,43,.05); border:1px solid var(--red); border-radius:16px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;"
        >
            <div style="display:flex; align-items:center; gap:16px;">
                <i
                    class="fas fa-exclamation-triangle"
                    style="font-size:24px; color:var(--red);"
                ></i>
                <div>
                    <div
                        style="font-size:14px; font-weight:700; color:var(--red); text-transform:uppercase;"
                    >
                        Nilai Seri Terdeteksi!
                    </div>
                    <div
                        style="font-size:12px; color:var(--smoke); margin-top:4px;"
                    >
                        {tiedIds.length} peserta memiliki nilai sama di ambang lolos.
                        Lakukan tanding ulang.
                    </div>
                </div>
            </div>
            <button onclick={requestTiebreak} class="btn-gen danger"
                ><i class="fas fa-redo"></i> Tanding Ulang</button
            >
        </div>
    {:else if hasAllScores && currentRound === "Penyisihan"}
        <div
            style="background:rgba(39,174,96,.05); border:1px solid #27ae60; border-radius:16px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;"
        >
            <div style="display:flex; align-items:center; gap:16px;">
                <i
                    class="fas fa-check-circle"
                    style="font-size:24px; color:#27ae60;"
                ></i>
                <div>
                    <div
                        style="font-size:14px; font-weight:700; color:#27ae60; text-transform:uppercase;"
                    >
                        Penyisihan Selesai!
                    </div>
                    <div
                        style="font-size:12px; color:var(--smoke); margin-top:4px;"
                    >
                        Semua peserta dinilai. Siap loloskan ke Final.
                    </div>
                </div>
            </div>
            <button onclick={advanceToFinal} class="btn-gen success"
                ><i class="fas fa-arrow-right"></i> Loloskan ke Final</button
            >
        </div>
    {/if}

    <!-- MATRIX TABLE -->
    <div class="tm-card">
        <div class="tm-card-head">
            <h3>Matriks Penilaian Wasit</h3>
            <div>
                <button onclick={() => window.print()} class="btn-gen ghost"
                    ><i class="fas fa-print"></i> Cetak</button
                >
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="score-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:40px;">No</th>
                        <th rowspan="2" class="text-left"
                            >Peserta & Kontingen</th
                        >
                        <th colspan="5">Nilai Wasit</th>
                        <th rowspan="2">Nilai Awal</th>
                        <th rowspan="2">Nilai Akhir</th>
                        <th rowspan="2">Durasi</th>
                        {#if currentRound === "Final"}
                            <th rowspan="2">Penyisihan</th>
                            <th rowspan="2">Akumulasi</th>
                        {/if}
                        <th rowspan="2" style="width:80px;">Override</th>
                    </tr>
                    <tr>
                        {#each [1, 2, 3, 4, 5] as w}
                            <th>W{w}</th>
                        {/each}
                    </tr>
                </thead>
                <tbody>
                    {#each registrations as item, no}
                        {@const s = item.score}
                        {@const rawVals = s
                            ? [
                                  s.judge_1 || 0,
                                  s.judge_2 || 0,
                                  s.judge_3 || 0,
                                  s.judge_4 || 0,
                                  s.judge_5 || 0,
                              ]
                            : [0, 0, 0, 0, 0]}

                        {@const activeJudges = rawVals.filter(v => v > 0)}
                        {@const scoredCount = activeJudges.length}
                        {@const sortedVals = [...rawVals].sort((a, b) => a - b)}
                        {@const minVal = s ? (scoredCount === 5 ? sortedVals[0] : 0) : 0}
                        {@const maxVal = s ? (scoredCount === 5 ? sortedVals[4] : 0) : 0}

                        {@const calculatedNilaiAwal = s ? (
                            scoredCount === 5 ? (sortedVals[1] + sortedVals[2] + sortedVals[3]) : rawVals.reduce((a, b) => a + b, 0)
                        ) : 0}
                        {@const nilaiAwal = s ? (s.total_score > 0 ? s.total_score : calculatedNilaiAwal) : 0}
                        {@const denda = s ? s.denda : 0}
                        {@const nilaiAkhir = s ? (s.nilai_akhir > 0 ? s.nilai_akhir : Math.max(0, calculatedNilaiAwal - denda)) : 0}
                        {@const isActive = !!(
                            activeDrawingId &&
                            Number(activeDrawingId) === Number(item.drawing_id)
                        )}
                        <tr style={isActive ? "background:#fdfbf7;" : ""}>
                            <td>{item.sequence_number || no + 1}</td>
                            <td class="text-left">
                                <div
                                    style="font-weight:700; color:var(--ink); font-size:13px; text-transform:uppercase;"
                                >
                                    {item.athletes
                                        .map((a) => a.name)
                                        .join(" & ")}
                                </div>
                                {#if merge}
                                    <div
                                        style="font-size:10px; color:var(--red); font-weight:700; margin-top:2px; text-transform:uppercase; letter-spacing:0.02em;"
                                    >
                                        <i
                                            class="fas fa-tag"
                                            style="margin-right:4px; font-size:9px;"
                                        ></i>
                                        {item.match_name}
                                    </div>
                                {/if}
                                <div
                                    style="font-size:11px; color:var(--smoke); margin-top:2px;"
                                >
                                    {item.contingent?.name || "-"}
                                </div>
                            </td>
                            {#each [0, 1, 2, 3, 4] as idx}
                                {@const val = rawVals[idx]}
                                {@const isOut =
                                    s && (val === minVal || val === maxVal)}
                                <td>
                                    {#if val > 0}
                                        <span
                                            class="score-val {isOut
                                                ? 'out'
                                                : ''}">{val.toFixed(1)}</span
                                        >
                                    {:else}
                                        <span style="color:var(--paper2);"
                                            >-</span
                                        >
                                    {/if}
                                </td>
                            {/each}
                            <td>{s ? nilaiAwal.toFixed(1) : "-"}</td>
                            <td>
                                <div class="score-final">
                                    {s ? nilaiAkhir.toFixed(1) : "-"}
                                </div>
                                {#if denda > 0}
                                    <div
                                        style="font-size:9px; color:var(--red); font-weight:700; margin-top:2px;"
                                    >
                                        -{denda} Denda
                                    </div>
                                {/if}
                            </td>
                            <td>
                                <div style="font-weight:700; color:var(--ink);">
                                    {s?.waktu || "-"}
                                </div>
                            </td>
                            {#if currentRound === "Final"}
                                <td
                                    >{item.penyisihan_score
                                        ? item.penyisihan_score.nilai_akhir.toFixed(
                                              1,
                                          )
                                        : "-"}</td
                                >
                                <td style="font-weight:700; color:#27ae60;">
                                    {item.accumulated_score > 0
                                        ? item.accumulated_score.toFixed(1)
                                        : "-"}
                                </td>
                            {/if}
                            <td>
                                <button
                                    onclick={() => openScoringModal(item)}
                                    class="btn-gen ghost"
                                    style="padding:4px 8px; font-size:10px;"
                                >
                                    <i class="fas fa-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                    {:else}
                        <tr>
                            <td
                                colspan="15"
                                style="padding:40px; color:var(--smoke); text-align:center;"
                                >Tidak ada data peserta</td
                            >
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    </div>

    <!-- LIVE TIMER CARD -->
    {#if activeRegItem}
        <div
            class="tm-card"
            style="border-color: var(--ink); box-shadow: 0 8px 24px rgba(44, 62, 80, 0.1);"
        >
            <div
                class="tm-card-head"
                style="background:var(--ink); color:#fff; display:flex; align-items:center; justify-content:space-between;"
            >
                <h3 style="color:#fff;">
                    <i
                        class="fas fa-stopwatch"
                        style="color:#f1c40f; margin-right:8px;"
                    ></i>
                    Live Match Timer &bull; {activeRegItem.athletes[0]?.name ||
                        "Peserta"}
                </h3>
                <button
                    onclick={() =>
                        playBuzzer(
                            "/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3",
                        )}
                    class="btn-gen ghost"
                    style="padding:4px 8px; border-color:rgba(255,255,255,0.2); color:#fff; background:transparent;"
                    title="Test Suara"
                >
                    <i class="fas fa-volume-up"></i>
                </button>
            </div>
            <div
                class="tm-card-body"
                style="padding: 24px; text-align: center; background:#fdfbf7;"
            >
                <div
                    style="font-size:48px; font-weight:900; font-family:'DM Sans', monospace; color:var(--ink); letter-spacing:4px; margin-bottom:16px;"
                >
                    {#if countdown > 0}
                        <span style="color:var(--red);"
                            >{formatCountdown(countdown)}</span
                        >
                    {:else}
                        <span>{formatTime(time)}</span>
                    {/if}
                </div>
                <div style="display:flex; gap:12px; justify-content:center;">
                    {#if !running && countdown === 0}
                        <button
                            onclick={startTimer}
                            class="btn-gen primary"
                            style="padding:12px 24px; font-size:14px;"
                        >
                            <i class="fas fa-play" style="margin-right:6px;"
                            ></i> Mulai
                        </button>
                    {:else if running}
                        <button
                            onclick={pauseTimer}
                            class="btn-gen"
                            style="background:#f39c12; color:#fff; padding:12px 24px; font-size:14px;"
                        >
                            <i class="fas fa-pause" style="margin-right:6px;"
                            ></i> Jeda
                        </button>
                    {/if}
                    <button
                        onclick={stopTimer}
                        class="btn-gen ghost"
                        style="padding:12px; font-size:14px;"
                        title="Stop & Reset"
                    >
                        <i class="fas fa-redo-alt"></i>
                    </button>
                    <button
                        onclick={() =>
                            finishMatch(
                                activeDrawingId || activeRegItem.drawing_id,
                            )}
                        class="btn-gen success"
                        style="padding:12px 24px; font-size:14px;"
                    >
                        <i
                            class="fas fa-flag-checkered"
                            style="margin-right:6px;"
                        ></i> Selesai (Simpan)
                    </button>
                </div>
                <div
                    style="margin-top:12px; font-size:11px; color:var(--smoke); font-weight:700; text-transform:uppercase; letter-spacing:0.1em;"
                >
                    Target Waktu: {activeRegItem.is_group
                        ? "1:30 - 2:00"
                        : "1:30"}
                </div>
            </div>
        </div>
    {/if}

    <!-- ANTRIAN PANGGILAN -->
    <div class="tm-card">
        <div class="tm-card-head">
            <h3>Antrian Panggilan</h3>
            <p>Klik Panggil untuk menampilkan di Monitor Court</p>
        </div>
        <div class="tm-card-body">
            <div class="queue-grid">
                {#each registrations as item, no}
                    {@const isActive = !!(
                        activeDrawingId &&
                        Number(activeDrawingId) === Number(item.drawing_id)
                    )}
                    <div class="queue-card {isActive ? 'active' : ''}">
                        <div class="queue-hdr">
                            <div class="queue-num">
                                {item.sequence_number || no + 1}
                            </div>
                            <div class="queue-info">
                                <div class="queue-name">
                                    {item.athletes
                                        .map((a) => a.name)
                                        .join(" & ")}
                                </div>
                                <div class="queue-cont">
                                    <i
                                        class="fas fa-shield-alt"
                                        style="margin-right:4px;"
                                    ></i>{item.contingent?.name || "-"}
                                </div>
                                {#if merge}
                                    <div
                                        style="font-size:9px; color:var(--red); font-weight:700; margin-top:2px; text-transform:uppercase;"
                                    >
                                        <i class="fas fa-tag"></i>
                                        {item.match_name}
                                    </div>
                                {/if}
                            </div>
                        </div>
                        <div class="queue-actions">
                            {#if isActive}
                                <button
                                    onclick={() =>
                                        callParticipant(item.drawing_id)}
                                    class="btn-gen primary"
                                    style="flex:1;"
                                    ><i class="fas fa-bullhorn"></i> Panggil Ulang</button
                                >
                                <button
                                    onclick={dismissParticipant}
                                    class="btn-gen danger"
                                    style="flex:1;"
                                    ><i class="fas fa-times"></i> Tutup</button
                                >
                            {:else}
                                <button
                                    onclick={() =>
                                        callParticipant(item.drawing_id)}
                                    class="btn-gen primary"
                                    style="width:100%;"
                                    disabled={!!item.score}
                                >
                                    <i class="fas fa-bullhorn"></i>
                                    {#if item.score}
                                        Sudah Dinilai
                                    {:else}
                                        Panggil
                                    {/if}
                                </button>
                            {/if}
                        </div>
                        {#if isActive}
                            <div
                                style="margin-top:8px; display:flex; gap:8px; justify-content:center;"
                            >
                                <button
                                    onclick={dismissParticipant}
                                    class="btn-gen danger"
                                    style="flex:1;"
                                    ><i class="fas fa-rectangle-xmark"></i> Tutup
                                    Form Wasit</button
                                >
                            </div>
                        {/if}
                    </div>
                {/each}
            </div>
        </div>
    </div>

    <!-- LEADERBOARD -->
    {#if ranked.length > 0}
        <div class="tm-card" style="margin-top: 20px;">
            <div
                class="tm-card-head"
                style="background:var(--ink); color:#fff; border-bottom:none;"
            >
                <h3 style="color:#fff;">
                    <i
                        class="fas fa-trophy"
                        style="color:#f1c40f; margin-right:8px;"
                    ></i> Leaderboard Peringkat
                </h3>
            </div>
            <div style="overflow-x:auto;">
                <table class="score-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">Rank</th>
                            <th class="text-left">Peserta</th>
                            <th>Kontingen</th>
                            <th
                                >{currentRound === "Final"
                                    ? "Total Akumulasi"
                                    : "Nilai Akhir"}</th
                            >
                        </tr>
                    </thead>
                    <tbody>
                        {#each ranked as item, rno}
                            {@const rankNum = rno + 1}
                            <tr>
                                <td style="font-size:18px; font-weight:700;">
                                    {#if rankNum === 1}🥇{:else if rankNum === 2}🥈{:else if rankNum === 3}🥉{:else}{rankNum}{/if}
                                </td>
                                <td
                                    class="text-left"
                                    style="font-weight:700; color:var(--ink); text-transform:uppercase;"
                                >
                                    {item.athletes
                                        .map((a) => a.name)
                                        .join(" & ")}
                                    {#if merge}
                                        <div
                                            style="font-size:9px; color:var(--red); font-weight:400; margin-top:2px;"
                                        >
                                            <i class="fas fa-tag"></i>
                                            {item.match_name}
                                        </div>
                                    {/if}
                                </td>
                                <td>{item.contingent?.name || "-"}</td>
                                <td
                                    style="font-size:16px; font-weight:700; color:var(--ink);"
                                >
                                    {currentRound === "Final"
                                        ? item.accumulated_score.toFixed(1)
                                        : (
                                              item.score?.nilai_akhir || 0
                                          ).toFixed(1)}
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        </div>
    {/if}

    <!-- OFFICIALS PANELS -->
    <div
        class="officials-grid"
        style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));"
    >
        <div class="tm-card official-card" style="border-left-color: #f59e0b;">
            <div class="official-label">
                <i class="fas fa-gavel"></i> Dewan Arbitrase
            </div>
            <div class="official-val">
                {assignedArbitrase?.referee?.user?.name || "Belum ditugaskan"}
            </div>
            <div class="official-sub">
                Lisensi: {assignedArbitrase?.referee?.license_number || "-"}
            </div>
        </div>

        <div class="tm-card official-card" style="border-left-color: #10b981;">
            <div class="official-label">
                <i class="fas fa-user-shield"></i> Dewan Hakim / Wasit Lapangan
            </div>
            <div class="official-val">
                {#if assignedReferees && assignedReferees.length > 0}
                    <ol
                        class="official-list"
                        style="list-style-type: decimal; padding-left: 16px;"
                    >
                        {#each assignedReferees as sr}
                            <li>
                                {sr.referee?.user?.name || "Wasit"}
                                <span
                                    style="font-size:10px; font-weight:normal; color:var(--smoke);"
                                >
                                    (Juri {sr.judge_index})</span
                                >
                            </li>
                        {/each}
                    </ol>
                {:else}
                    Belum ditugaskan
                {/if}
            </div>
        </div>

        <div class="tm-card official-card">
            <div class="official-label">
                <i class="fas fa-user-tie"></i> Koordinator Pertandingan
            </div>
            <div class="official-val">
                {#if assignedKoordinators && assignedKoordinators.length > 0}
                    <ul
                        class="official-list"
                        style="list-style-type: none; padding-left: 0;"
                    >
                        {#each assignedKoordinators as ak}
                            <li>{ak.user?.name}</li>
                        {/each}
                    </ul>
                {:else}
                    -
                {/if}
            </div>
            <div class="official-sub">NIP. -</div>
        </div>

        <div
            class="tm-card official-card"
            style="border-left-color: var(--ink);"
        >
            <div class="official-label">
                <i class="fas fa-users"></i> Para Panitera
            </div>
            <div class="official-val">
                {#if assignedPaniteras && assignedPaniteras.length > 0}
                    <ul
                        class="official-list"
                        style="list-style-type: none; padding-left: 0;"
                    >
                        {#each assignedPaniteras as ap}
                            <li>{ap.user?.name}</li>
                        {/each}
                    </ul>
                {:else}
                    -
                {/if}
            </div>
        </div>
    </div>

    <!-- OVERRIDE MODAL -->
    {#if showModal}
        <div class="modal-overlay">
            <div class="modal-content">
                <div class="modal-hdr">
                    <h3>Input Nilai Juri (Admin Override)</h3>
                    <button
                        onclick={() => (showModal = false)}
                        class="modal-close"><i class="fas fa-times"></i></button
                    >
                </div>
                <div class="modal-body">
                    <p
                        style="font-size:12px; font-weight:700; color:var(--smoke); margin-bottom:16px;"
                    >
                        PESERTA: {activeRegistrationNameForModal}
                    </p>
                    <div class="score-input-grid">
                        {#each [1, 2, 3, 4, 5] as num}
                            <div class="score-input-group">
                                <span class="score-input-label">Juri {num}</span
                                >
                                <input
                                    type="number"
                                    step="0.1"
                                    bind:value={modalScores[`judge_${num}`]}
                                    class="score-input-field"
                                    placeholder="0.0"
                                />
                            </div>
                        {/each}
                        <div
                            class="score-input-group"
                            style="margin-top:8px; border-top:1px solid var(--paper2); padding-top:16px;"
                        >
                            <span
                                class="score-input-label"
                                style="color:var(--red);">Denda</span
                            >
                            <input
                                type="number"
                                step="1"
                                bind:value={modalDenda}
                                class="score-input-field"
                                placeholder="0"
                            />
                        </div>
                    </div>

                    <div style="display:flex; gap:8px;">
                        <button
                            onclick={() => (showModal = false)}
                            class="btn-gen ghost"
                            style="flex:1; padding:12px; justify-content:center; font-size:12px;"
                            >Batal</button
                        >
                        <button
                            onclick={saveScore}
                            class="btn-gen primary"
                            style="flex:2; padding:12px; justify-content:center; font-size:12px;"
                            >Simpan Nilai</button
                        >
                    </div>
                </div>
            </div>
        </div>
    {/if}
</div>

<style>
    .tm-page {
        padding: 24px;
        padding-bottom: 100px;
        background: var(--paper, #f7f4ef);
        min-height: 100vh;
    }

    /* HEADER */
    .tm-hdr {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .tm-hdr h2 {
        font-family: "Cinzel", serif;
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px;
        color: var(--ink, #2c3e50);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .tm-badge-title {
        display: inline-block;
        padding: 6px 16px;
        background: var(--ink, #2c3e50);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 8px;
    }

    /* ROUND INDICATOR */
    .round-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .round-step {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #fff;
        color: var(--smoke, #7f8c8d);
        border: 1px solid var(--paper2, #e0dcd3);
    }

    .round-step.active {
        background: var(--ink, #2c3e50);
        color: #fff;
        border-color: var(--ink, #2c3e50);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* OFFICIALS */
    .officials-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 40px;
        margin-bottom: 24px;
    }

    .official-card {
        padding: 14px 20px;
        border-left: 4px solid var(--red, #e74c3c);
        margin-bottom: 0 !important;
    }

    .official-label {
        font-size: 10px;
        font-weight: 800;
        color: var(--smoke, #7f8c8d);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .official-label i {
        color: var(--red, #e74c3c);
        font-size: 13px;
    }

    .official-val {
        font-size: 15px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        font-family: "Outfit", sans-serif;
    }

    .official-sub {
        font-size: 11px;
        color: var(--smoke, #7f8c8d);
        margin-top: 2px;
    }

    .official-list {
        list-style: none;
        padding: 0;
        margin: 4px 0 0 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .official-list li {
        font-size: 14px;
        font-weight: 600;
        color: var(--ink, #2c3e50);
    }

    /* INFO BAR */
    .info-bar {
        background: #fff;
        border: 1px solid var(--paper2, #e0dcd3);
        border-radius: 16px;
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 9px;
        font-weight: 700;
        color: var(--smoke, #7f8c8d);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .info-value {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .info-value i {
        color: var(--red, #e74c3c);
    }

    /* MAIN CARDS */
    .tm-card {
        background: #fff;
        border: 1px solid var(--paper2, #e0dcd3);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .tm-card-head {
        padding: 16px 20px;
        border-bottom: 1px solid var(--paper2, #e0dcd3);
        background: #fdfbf7;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tm-card-head h3 {
        font-family: "Cinzel", serif;
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        color: var(--ink, #2c3e50);
    }

    .tm-card-body {
        padding: 20px;
    }

    /* TABLES */
    .score-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .score-table th {
        padding: 12px 14px;
        background: #fdfbf7;
        font-size: 10px;
        color: var(--smoke, #7f8c8d);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid var(--paper2, #e0dcd3);
        text-align: center;
    }

    .score-table td {
        padding: 12px 14px;
        border: 1px solid var(--paper2, #e0dcd3);
        vertical-align: middle;
        text-align: center;
    }

    .score-table .text-left {
        text-align: left;
    }

    .score-val {
        font-size: 14px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
    }

    .score-val.out {
        color: var(--smoke, #7f8c8d);
        text-decoration: line-through;
    }

    .score-final {
        font-size: 16px;
        font-weight: 700;
        color: #2980b9;
    }

    /* QUEUE CARDS */
    .queue-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .queue-card {
        background: #fff;
        border: 1px solid var(--paper2, #e0dcd3);
        border-radius: 16px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: all 0.2s;
    }

    .queue-card:hover {
        border-color: #bbb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .queue-card.active {
        border-color: var(--ink, #2c3e50);
        background: #fdfbf7;
        box-shadow: 0 4px 16px rgba(44, 62, 80, 0.1);
        transform: scale(1.02);
    }

    .queue-hdr {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .queue-num {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--paper2, #e0dcd3);
        color: var(--ink, #2c3e50);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        font-family: "Cinzel", serif;
    }

    .queue-card.active .queue-num {
        background: var(--ink, #2c3e50);
        color: #fff;
    }

    .queue-info {
        flex: 1;
        min-width: 0;
    }

    .queue-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .queue-cont {
        font-size: 11px;
        color: var(--smoke, #7f8c8d);
        font-weight: 600;
        margin-top: 4px;
    }

    .queue-actions {
        display: flex;
        gap: 8px;
    }

    /* BUTTONS */
    .btn-gen {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        font-family: "DM Sans", sans-serif;
        transition: all 0.15s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-gen:disabled {
        background: #bdc3c7 !important;
        color: #7f8c8d !important;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-gen.primary {
        background: var(--ink, #2c3e50);
        color: #fff;
    }

    .btn-gen.primary:hover {
        background: #1a252f;
        transform: translateY(-1px);
    }

    .btn-gen.ghost {
        background: #fff;
        color: var(--ink, #2c3e50);
        border: 1px solid var(--paper2, #e0dcd3);
    }

    .btn-gen.ghost:hover {
        border-color: var(--ink, #2c3e50);
    }

    .btn-gen.danger {
        background: var(--red, #e74c3c);
        color: #fff;
    }

    .btn-gen.danger:hover {
        background: #a93226;
    }

    .btn-gen.success {
        background: #27ae60;
        color: #fff;
    }

    .btn-gen.success:hover {
        background: #229954;
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
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-hdr {
        padding: 20px 24px;
        border-bottom: 1px solid var(--paper2, #e0dcd3);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-hdr h3 {
        font-family: "Cinzel", serif;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: var(--ink, #2c3e50);
    }

    .modal-close {
        background: var(--paper, #f7f4ef);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--smoke, #7f8c8d);
    }

    .modal-close:hover {
        background: var(--red, #e74c3c);
        color: #fff;
    }

    .modal-body {
        padding: 24px;
    }

    .score-input-grid {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 24px;
    }

    .score-input-group {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .score-input-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        width: 80px;
        text-transform: uppercase;
    }

    .score-input-field {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid var(--paper2, #e0dcd3);
        background: var(--paper, #f7f4ef);
        font-size: 16px;
        font-weight: 700;
        text-align: center;
        color: var(--ink, #2c3e50);
        outline: none;
    }

    .score-input-field:focus {
        border-color: var(--ink, #2c3e50);
    }
</style>
