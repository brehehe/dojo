<script>
    import { onMount, onDestroy } from 'svelte';
    import { router } from '@inertiajs/svelte';
    import SignaturePad from '../Components/SignaturePad.svelte';

    // Props
    let { matchId, urlRound = null, urlPoolId = null, urlFrom = null } = $props();

    const backRoute = urlFrom === 'panggil-drawing' ? '/admin/panitera/panggil-drawing' : '/admin/new-scoring';

    // State
    let matchNumber = $state(null);
    let merge = $state(null);
    let displayName = $state('');
    let drawingData = $state({
        upper_bracket: { rounds: [] },
        lower_bracket: { rounds: [] },
        grand_final: null,
        juara: {}
    });
    let officials = $state(null);
    let assignedArbitrase = $state(null);
    let assignedReferees = $state([]);
    let assignedKoordinators = $state([]);
    let assignedPaniteras = $state([]);
    let juaraMap = $state({});
    let timerState = $state({
        status: 'stopped',
        elapsed_ms: 0,
        started_at_ms: null
    });
    let courtId = $state(null);
    let randoriResults = $state({});
    let activeBracketNode = $state(null);

    // Active Match scoring form
    let activeMatch = $state(null); // { bracket, round, match, data }
    let scoreRed = $state(0);
    let scoreBlue = $state(0);
    let scoringAka = $state({
        mujoken_kachi: 0,
        ippon: 0,
        waza_ari: 0,
        hasil_batsu_5: 0,
        hasil_batsu_10: 0,
        yusei_kachi: 0
    });
    let scoringShiro = $state({
        mujoken_kachi: 0,
        ippon: 0,
        waza_ari: 0,
        hasil_batsu_5: 0,
        hasil_batsu_10: 0,
        yusei_kachi: 0
    });

    // Signatures
    let sigArbitraseName = $state('');
    let sigArbitraseData = $state(null);
    let sigKoordinatorName = $state('');
    let sigKoordinatorData = $state(null);
    let sigWasitName = $state('');
    let sigWasitData = $state(null);
    let sigPanitera = $state([{ name: '', signature: null }]);
    let sigManagerRedName = $state('');
    let sigManagerRedData = $state(null);
    let sigManagerWhiteName = $state('');
    let sigManagerWhiteData = $state(null);

    // Audio & PA state
    let isPlayingAnnouncer = $state(false);
    let currentAudio = null;
    let buzzerPool = [];
    let actionInFlight = $state(false);

    // Toast Notification State
    let toast = $state({ show: false, message: '', type: 'success' });
    let toastTimeout;
    function showToast(message, type = 'success') {
        if (toastTimeout) clearTimeout(toastTimeout);
        toast = { show: true, message, type };
        toastTimeout = setTimeout(() => {
            toast.show = false;
        }, 3000);
    }

    // Polling interval
    let pollInterval;

    // Timer local tick states
    let time = $state(0);
    let running = $state(false);
    let countdown = $state(0);
    let offset = $state(0);
    let lastTickSecond = -1;
    let playedIntervals = new Set();
    let interpolInterval;

    async function fetchState() {
        try {
            const res = await fetch(`/admin/api/scoring/randori/${matchId}/state`);
            const data = await res.json();
            if (data) {
                matchNumber = data.matchNumber;
                merge = data.merge;
                displayName = data.displayName;
                drawingData = data.drawingData;
                officials = data.officials;
                assignedArbitrase = data.assignedArbitrase;
                assignedReferees = data.assignedReferees;
                assignedKoordinators = data.assignedKoordinators;
                assignedPaniteras = data.assignedPaniteras;
                juaraMap = data.juara || {};
                timerState = data.timerState;
                courtId = data.courtId;
                randoriResults = data.randoriResults || {};
                if (!actionInFlight) {
                    activeBracketNode = data.activeBracketNode;
                }

                // Sync Timer local state with server state
                offset = (timerState.server_time_ms || Date.now()) - Date.now();
                running = (timerState.status === 'running');
                if (timerState.status !== 'countdown') {
                    countdown = 0;
                }
                if (!running && time < 500) {
                    playedIntervals.clear();
                }
            }
        } catch (e) {
            console.error('Error fetching Randori scoring state:', e);
        }
    }

    // Timer Actions
    async function startTimer() {
        if (!courtId) return;
        // Optimistic UI updates
        running = true;
        if (!timerState.elapsed_ms || timerState.elapsed_ms < 1000) {
            if (!playedIntervals.has('start')) {
                playedIntervals.add('start');
                playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
            }
        }
        try {
            const res = await fetch('/admin/api/scoring/timer-control', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ court_id: courtId, action: 'start' })
            });
            const data = await res.json();
            if (data.success) {
                timerState = data.timer_state;
                running = true;
            } else {
                running = false;
            }
        } catch (e) {
            running = false;
        }
    }

    async function pauseTimer() {
        if (!courtId) return;
        running = false;
        try {
            const res = await fetch('/admin/api/scoring/timer-control', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ court_id: courtId, action: 'pause' })
            });
            const data = await res.json();
            if (data.success) {
                timerState = data.timer_state;
                running = false;
            } else {
                running = true;
            }
        } catch (e) {
            running = true;
        }
    }

    async function stopTimer() {
        if (!courtId) return;
        running = false;
        time = 0;
        countdown = 0;
        try {
            const res = await fetch('/admin/api/scoring/timer-control', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ court_id: courtId, action: 'stop' })
            });
            const data = await res.json();
            if (data.success) {
                timerState = data.timer_state;
                time = 0;
                running = false;
                countdown = 0;
            }
        } catch (e) {
        }
    }

    async function finishTimer() {
        running = false;
        await stopTimer();
        playBuzzerDouble('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
    }

    // Call Match / Grand Final / Dismiss
    async function callMatch(nodeKey, roundIdx, matchIdx, bracket) {
        actionInFlight = true;
        const originalActiveNode = activeBracketNode;
        activeBracketNode = nodeKey; // Optimistic update
        
        // Find match details locally and set activeMatch optimistically
        let matchData = null;
        if (bracket === 'ub') {
            matchData = drawingData.upper_bracket?.rounds[roundIdx]?.[matchIdx];
        } else if (bracket === 'lb') {
            matchData = drawingData.lower_bracket?.rounds[roundIdx]?.[matchIdx];
        } else {
            matchData = drawingData.grand_final;
        }
        activeMatch = {
            bracket,
            round: roundIdx,
            match: matchIdx,
            data: matchData
        };

        try {
            const res = await fetch('/admin/api/scoring/randori/call-match', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    match_id: matchId,
                    node_key: nodeKey,
                    round_idx: roundIdx,
                    match_idx: matchIdx,
                    bracket: bracket
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                if (data.announcement_text) {
                    playAnnouncer(data.announcement_text);
                }
                activeBracketNode = nodeKey;
                actionInFlight = false;
                await fetchState();
            } else {
                activeBracketNode = originalActiveNode;
                activeMatch = null;
                actionInFlight = false;
                showToast(data.message || 'Gagal memanggil pertandingan', 'error');
            }
        } catch (e) {
            activeBracketNode = originalActiveNode;
            activeMatch = null;
            actionInFlight = false;
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    async function callGrandFinal() {
        actionInFlight = true;
        const originalActiveNode = activeBracketNode;
        activeBracketNode = 'gf_0_0'; // Optimistic update
        try {
            const res = await fetch('/admin/api/scoring/randori/call-grand-final', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    match_id: matchId
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                if (data.announcement_text) {
                    playAnnouncer(data.announcement_text);
                }
                activeBracketNode = 'gf_0_0';
                actionInFlight = false;
                await fetchState();
            } else {
                activeBracketNode = originalActiveNode;
                actionInFlight = false;
                showToast(data.message || 'Gagal memanggil Grand Final', 'error');
            }
        } catch (e) {
            activeBracketNode = originalActiveNode;
            actionInFlight = false;
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    async function dismissMatch() {
        actionInFlight = true;
        const originalActiveNode = activeBracketNode;
        activeBracketNode = null; // Optimistic update
        try {
            const res = await fetch('/admin/api/scoring/randori/dismiss-match', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ match_id: matchId })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                activeMatch = null;
                activeBracketNode = null;
                actionInFlight = false;
                await fetchState();
            } else {
                activeBracketNode = originalActiveNode;
                actionInFlight = false;
                showToast(data.message || 'Gagal menutup pertandingan', 'error');
            }
        } catch (e) {
            activeBracketNode = originalActiveNode;
            actionInFlight = false;
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    // Save Champion / Confirm
    async function confirmChampion() {
        if (!confirm('Sistem akan men-generate Juara 1 & 2 dari hasil Grand Final. Lanjutkan?')) return;
        try {
            const res = await fetch('/admin/api/scoring/randori/confirm-champion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    match_id: matchId
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                fetchState();
            } else {
                showToast(data.message || 'Gagal menyimpan juara', 'error');
            }
        } catch (e) {
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    // Call officials & TTS announcement
    async function callOfficials() {
        try {
            const res = await fetch('/admin/api/scoring/randori/call-officials', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    match_id: matchId
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                if (data.announcement_text) {
                    playAnnouncer(data.announcement_text);
                }
            } else {
                showToast(data.message || 'Gagal memanggil wasit', 'error');
            }
        } catch (e) {
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    // Repair bracket
    async function repairBracket() {
        try {
            const res = await fetch('/admin/api/scoring/randori/repair-bracket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    match_id: matchId
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                fetchState();
            } else {
                showToast(data.message || 'Gagal memperbaiki bracket', 'error');
            }
        } catch (e) {
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    // Score Calculations
    function recalculateScores() {
        scoreRed = (scoringAka.mujoken_kachi * 15) + (scoringAka.ippon * 10) + (scoringAka.waza_ari * 5) + (scoringAka.yusei_kachi * 5) - (scoringAka.hasil_batsu_5 * 5) - (scoringAka.hasil_batsu_10 * 10);
        scoreBlue = (scoringShiro.mujoken_kachi * 15) + (scoringShiro.ippon * 10) + (scoringShiro.waza_ari * 5) + (scoringShiro.yusei_kachi * 5) - (scoringShiro.hasil_batsu_5 * 5) - (scoringShiro.hasil_batsu_10 * 10);
    }

    function updateScore(side, key, delta) {
        if (side === 'aka') {
            scoringAka[key] = Math.max(0, scoringAka[key] + delta);
        } else {
            scoringShiro[key] = Math.max(0, scoringShiro[key] + delta);
        }
        recalculateScores();
    }

    // Reset score inputs
    function resetDetailedScoring() {
        scoringAka = { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
        scoringShiro = { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
        sigArbitraseName = '';
        sigArbitraseData = null;
        sigKoordinatorName = '';
        sigKoordinatorData = null;
        sigWasitName = '';
        sigWasitData = null;
        sigPanitera = [{ name: '', signature: null }];
        sigManagerRedName = '';
        sigManagerRedData = null;
        sigManagerWhiteName = '';
        sigManagerWhiteData = null;
        recalculateScores();
    }

    // Open Match modal / scoring form
    function openMatchModal(bracket, roundIdx, matchIdx) {
        const nodeKey = `${bracket}_${roundIdx}_${matchIdx}`;
        let matchData = null;
        if (bracket === 'ub') {
            matchData = drawingData.upper_bracket?.rounds[roundIdx]?.[matchIdx];
        } else if (bracket === 'lb') {
            matchData = drawingData.lower_bracket?.rounds[roundIdx]?.[matchIdx];
        } else {
            matchData = drawingData.grand_final;
        }

        activeMatch = {
            bracket,
            round: roundIdx,
            match: matchIdx,
            data: matchData
        };

        const result = randoriResults[nodeKey];
        if (result) {
            const meta = typeof result.metadata === 'string' ? JSON.parse(result.metadata) : result.metadata;
            scoringAka = meta.scoringAka || { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
            scoringShiro = meta.scoringShiro || { mujoken_kachi: 0, ippon: 0, waza_ari: 0, hasil_batsu_5: 0, hasil_batsu_10: 0, yusei_kachi: 0 };
            
            const sigs = meta.signatures || {};
            sigArbitraseName = sigs.arbitrase?.name || '';
            sigArbitraseData = sigs.arbitrase?.signature || null;
            sigKoordinatorName = sigs.koordinator?.name || '';
            sigKoordinatorData = sigs.koordinator?.signature || null;
            sigWasitName = sigs.wasit?.name || '';
            sigWasitData = sigs.wasit?.signature || null;
            sigPanitera = sigs.panitera || [{ name: '', signature: null }];
            sigManagerRedName = sigs.manager_red?.name || '';
            sigManagerRedData = sigs.manager_red?.signature || null;
            sigManagerWhiteName = sigs.manager_white?.name || '';
            sigManagerWhiteData = sigs.manager_white?.signature || null;
        } else {
            resetDetailedScoring();
        }
        recalculateScores();

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function openGrandFinalModal() {
        openMatchModal('gf', 0, 0);
    }

    // Submit scoring result
    async function submitScoring() {
        if (!sigArbitraseName || !sigArbitraseData) {
            alert('Nama dan Tanda tangan Arbitrase wajib diisi.');
            return;
        }
        if (!sigKoordinatorName || !sigKoordinatorData) {
            alert('Nama dan Tanda tangan Koordinator wajib diisi.');
            return;
        }
        if (!sigWasitName || !sigWasitData) {
            alert('Nama dan Tanda tangan Wasit wajib diisi.');
            return;
        }
        if (!sigPanitera[0]?.name || !sigPanitera[0]?.signature) {
            alert('Nama dan Tanda tangan Koordinator Panitera wajib diisi.');
            return;
        }
        if (!sigManagerRedName || !sigManagerRedData) {
            alert('Nama dan Tanda tangan Manajer Pita Merah wajib diisi.');
            return;
        }
        if (!sigManagerWhiteName || !sigManagerWhiteData) {
            alert('Nama dan Tanda tangan Manajer Pita Putih wajib diisi.');
            return;
        }

        if (scoreRed === scoreBlue) {
            alert('Poin sama (Hikiwake). Silakan tentukan pemenang lewat yusei_kachi atau mujoken_kachi.');
            return;
        }

        try {
            const res = await fetch('/admin/api/scoring/randori/submit-scoring', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    match_id: matchId,
                    bracket: activeMatch.bracket,
                    round: activeMatch.round,
                    match: activeMatch.match,
                    score_red: scoreRed,
                    score_blue: scoreBlue,
                    scoring_aka: scoringAka,
                    scoring_shiro: scoringShiro,
                    signatures: {
                        arbitrase: { name: sigArbitraseName, signature: sigArbitraseData },
                        koordinator: { name: sigKoordinatorName, signature: sigKoordinatorData },
                        wasit: { name: sigWasitName, signature: sigWasitData },
                        panitera: sigPanitera,
                        manager_red: { name: sigManagerRedName, signature: sigManagerRedData },
                        manager_white: { name: sigManagerWhiteName, signature: sigManagerWhiteData }
                    }
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                activeMatch = null;
                fetchState();
            } else {
                showToast(data.message || 'Gagal menyimpan hasil penilaian.', 'error');
            }
        } catch (e) {
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    // Panitera array operations
    function addPanitera() {
        sigPanitera = [...sigPanitera, { name: '', signature: null }];
    }

    function removePanitera(idx) {
        sigPanitera = sigPanitera.filter((_, i) => i !== idx);
    }

    // Clear all courts
    async function clearAllCourts() {
        if (!confirm('PERINGATAN: Ini akan mereset status SEMUA lapangan & match yang sedang berjalan menjadi KOSONG. Lanjutkan?')) return;
        try {
            const res = await fetch('/admin/api/scoring/clear-all-courts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.text, 'success');
                fetchState();
            } else {
                showToast(data.message || 'Gagal mereset lapangan', 'error');
            }
        } catch (e) {
            showToast('Terjadi kesalahan koneksi', 'error');
        }
    }

    // Format time helpers
    function formatTime(t) {
        let maxT = Math.max(0, t);
        let m = Math.floor(maxT / 60000);
        let s = Math.floor((maxT % 60000) / 1000);
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }

    function formatCountdown(c) {
        if (c === 5) return 'Siap';
        if (c === 4) return '3';
        if (c === 3) return '2';
        if (c === 2) return '1';
        if (c === 1) return 'Mulai';
        return c > 0 ? c.toString() : '';
    }

    // Audio sound systems
    function playBuzzer(src) {
        try {
            let audio = buzzerPool.find(a => a.paused || a.ended);
            if (!audio) {
                audio = new Audio(src);
                audio.preload = 'auto';
                buzzerPool.push(audio);
            }
            audio.currentTime = 0;
            audio.play().catch(() => {});
        } catch (e) {
            // Silent fail for audio
        }
    }

    function playBuzzerDouble(src) {
        playBuzzer(src);
        setTimeout(() => playBuzzer(src), 800);
    }

    function playAnnouncer(text) {
        stopAnnouncer();
        isPlayingAnnouncer = true;

        function playBeepAndSpeak() {
            speak(text);
        }

        function speak(rawText) {
            if (!isPlayingAnnouncer) return;

            window.speechSynthesis.cancel();
            const speechText = rawText
                .toLowerCase()
                .replace(/\./g, '. ')
                .replace(/,/g, ', ')
                .replace(/-/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();

            const speech = new SpeechSynthesisUtterance(speechText);
            speech.lang = 'id-ID';
            speech.rate = 1.1;
            speech.pitch = 1;
            speech.volume = 1;

            function setVoice() {
                const voices = window.speechSynthesis.getVoices();
                let voice = voices.find(v => v.name.includes('Google Bahasa Indonesia')) ||
                    voices.find(v => v.lang === 'id-ID') ||
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
        // Preload buzzer audio to eliminate latency
        try {
            for (let i = 0; i < 3; i++) {
                let audio = new Audio('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
                audio.preload = 'auto';
                audio.load();
                buzzerPool.push(audio);
            }
        } catch (e) {
            // Silent fail for audio preload
        }

        fetchState();

        pollInterval = setInterval(fetchState, 300);

        // 30ms Interpolation for local timer
        interpolInterval = setInterval(() => {
            if (running && timerState.started_at_ms) {
                let expected = (timerState.elapsed_ms || 0) + (Date.now() + offset - timerState.started_at_ms);
                time = Math.min(expected, 120000);
                let s = Math.floor(time / 1000);
                if (s >= 120 && !playedIntervals.has(120)) {
                    time = 120000;
                    running = false;
                    playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
                    playedIntervals.add(120);
                    pauseTimer();
                }
                if (s > lastTickSecond) {
                    lastTickSecond = s;
                }
            } else if (timerState.status === 'countdown' && timerState.countdown_end_ms) {
                let remaining = timerState.countdown_end_ms - (Date.now() + offset);
                countdown = remaining > 0 ? Math.ceil(remaining / 1000) : 0;
                time = Math.min(timerState.elapsed_ms || 0, 120000);
                if (remaining <= 0) {
                    startTimer();
                }
                lastTickSecond = Math.floor(time / 1000);
            } else {
                countdown = 0;
                time = Math.min(timerState.elapsed_ms || 0, 120000);
                lastTickSecond = Math.floor(time / 1000);
            }
        }, 30);

        document.addEventListener('click', () => {
            if (window.sharedAudioCtx && window.sharedAudioCtx.state === 'suspended') {
                window.sharedAudioCtx.resume();
            }
        }, { once: true });
    });

    onDestroy(() => {
        clearInterval(pollInterval);
        clearInterval(interpolInterval);
        stopAnnouncer();
    });

    // UB / LB rounds computed checks
    let ubRounds = $derived(drawingData.upper_bracket?.rounds || []);
    let lbRounds = $derived(drawingData.lower_bracket?.rounds || []);
    let grandFinal = $derived(drawingData.grand_final);
    let totalUB = $derived(ubRounds.length);
    let totalLB = $derived(lbRounds.length);

    let needsRepair = $derived.by(() => {
        if (ubRounds.length === 0) return false;
        // Simple heuristic matching check:
        const firstMatch = ubRounds[0]?.[0];
        return firstMatch && typeof firstMatch.winner_next === 'undefined';
    });

    const categories = [
        { label: 'PERINGATAN', desc: 'Mujoken Kachi', val: 15, key: 'mujoken_kachi' },
        { label: '1.', desc: 'Ippon', val: 10, key: 'ippon' },
        { label: '2.', desc: 'Waza Ari', val: 5, key: 'waza_ari' },
        { label: '3.', desc: 'Hasil Batsu 5', val: 5, key: 'hasil_batsu_5' },
        { label: '4.', desc: 'Hasil Batsu 10', val: 10, key: 'hasil_batsu_10' },
        { label: '5.', desc: 'Yusei Kachi', val: 5, key: 'yusei_kachi' }
    ];
</script>

<div class="tm-page">
    <div style="position: fixed; top: 20px; right: 30px; z-index: 90;">
        <button onclick={clearAllCourts}
            class="btn-gen danger"
            style="padding: 12px 20px; border-radius: 12px; font-size: 12px; box-shadow: 0 8px 24px rgba(192,57,43,.3);">
            <i class="fas fa-eraser" style="margin-right: 8px;"></i>
            <span class="hidden md:inline">Reset Semua Lapangan</span>
            <span class="md:hidden">Reset</span>
        </button>
    </div>

    <div class="tm-hdr">
        <div>
            <div class="tm-badge-title">{merge?.name || 'RANDORI'}</div>
            <h2>{matchNumber?.name || 'Randori Match'}</h2>
            {#if merge}
                <div style="font-size: 11px; color: var(--smoke); font-weight: 600; margin-top: 4px; font-style: italic;">
                    {displayName}
                </div>
            {/if}
            <p>Double Elimination — kalah 1x masih bisa juara via Loser Bracket</p>
        </div>
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            {#if Object.keys(juaraMap).length >= 2}
                <button onclick={confirmChampion}
                    class="btn-gen primary" style="background:#27ae60; box-shadow:0 4px 12px rgba(39,174,96,0.2);">
                    <i class="fas fa-medal"></i> Simpan Juara
                </button>
            {/if}
            <button onclick={callOfficials} class="btn-gen primary"
                style="background:var(--red); box-shadow:0 4px 12px rgba(192,57,43,0.2);">
                <i class="fas fa-bullhorn"></i> Panggil Official
            </button>
            <button onclick={stopAnnouncer} class="btn-gen ghost"
                style="color:var(--red); border-color:var(--red);">
                <i class="fas fa-volume-xmark"></i> Stop Suara
            </button>
            <a href={backRoute} class="btn-gen ghost" style="text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Shortcut Lapangan -->
    {#if courtId}
        <div style="background:#fff; border:1px solid var(--paper2); border-radius:16px; padding:16px 20px; margin-bottom:20px; display:flex; flex-direction:column; gap:12px;">
            <div style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; letter-spacing:0.1em; display:flex; align-items:center; gap:6px;">
                <i class="fas fa-desktop" style="color:var(--red);"></i> Monitor Lapangan (Shortcut)
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:10px;">
                <a href={`/admin/arbitrase/scoring/monitor/${courtId}`} target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-bullhorn" style="margin-right:6px;"></i> Panggilan
                </a>
                <a href={`/admin/arbitrase/scoring/monitor-hasil/match/${matchId}`} target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-tv" style="margin-right:6px;"></i> Hasil
                </a>
                <a href={`/admin/arbitrase/scoring/monitor-timer/court/${courtId}`} target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-stopwatch" style="margin-right:6px;"></i> Timer
                </a>
                <a href={`/admin/arbitrase/scoring/monitor-rekapitulasi-hasil/court/${courtId}`} target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-list-ol" style="margin-right:6px;"></i> Rekapitulasi
                </a>
                <a href={`/admin/arbitrase/scoring/monitor-referee/court/${courtId}`} target="_blank" class="btn-gen ghost" style="padding:10px; font-size:11px; justify-content:center; text-decoration:none;">
                    <i class="fas fa-user-tie" style="margin-right:6px;"></i> Wasit
                </a>
            </div>
        </div>
    {/if}

    <!-- Needs Repair Banner -->
    {#if needsRepair}
        <div style="background:#fcf3cf; border:1px solid #f1c40f; border-radius:16px; padding:16px 20px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:16px;">
                <i class="fas fa-tools" style="font-size:24px; color:#d4ac0d;"></i>
                <div>
                    <div style="font-size:14px; font-weight:700; color:#b7950b; text-transform:uppercase;">Routing Bracket Tidak Lengkap</div>
                    <div style="font-size:12px; color:#9c640c; margin-top:4px;">Beberapa match belum punya jalur winner / loser. Klik perbaiki otomatis.</div>
                </div>
            </div>
            <button onclick={repairBracket} class="btn-gen primary" style="background:#d4ac0d;"><i class="fas fa-wrench"></i> Perbaiki Bracket</button>
        </div>
    {/if}

    <!-- INLINE SCORING PANEL -->
    {#if activeMatch}
        {@const matchData = activeMatch.data}
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm overflow-hidden mb-6">
            <div class="px-5 py-4 bg-gradient-to-r from-rose-600 to-rose-500 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-gavel text-white text-[15px]"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-black text-rose-200 uppercase tracking-[0.3em]">Panel Penilaian Aktif</div>
                        <div class="text-lg font-black text-white uppercase tracking-tight leading-none">
                            Randori &bull;
                            {#if activeMatch.bracket === 'gf'}
                                Grand Final
                            {:else}
                                {activeMatch.bracket.toUpperCase()} — Babak {activeMatch.round + 1} / Match {activeMatch.match + 1}
                            {/if}
                        </div>
                    </div>
                </div>

                <!-- Live timer inline controls -->
                <div class="flex items-center gap-3 bg-white/10 border border-white/20 px-4 py-2 rounded-xl">
                    <button onclick={() => playBuzzer('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3')}
                        class="w-6 h-6 flex items-center justify-center bg-white/20 hover:bg-white/30 text-white rounded-full transition-colors"
                        title="Test Suara">
                        <i class="fas fa-volume-up text-[10px]"></i>
                    </button>
                    <i class="fas fa-stopwatch text-amber-300"></i>
                    <div class="text-xl font-black font-mono tracking-wider min-w-[60px] text-white">
                        {#if countdown > 0}
                            <span class="text-orange-300">{formatCountdown(countdown)}</span>
                        {:else}
                            <span>{formatTime(time)}</span>
                        {/if}
                    </div>
                    <div class="flex items-center gap-1">
                        {#if !running && countdown === 0}
                            <button onclick={startTimer}
                                class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors shadow-sm"
                                title="Start"><i class="fas fa-play text-xs"></i></button>
                        {:else if running}
                            <button onclick={pauseTimer}
                                class="w-8 h-8 flex items-center justify-center bg-amber-400 hover:bg-amber-500 text-white rounded-lg transition-colors shadow-sm"
                                title="Pause"><i class="fas fa-pause text-xs"></i></button>
                        {/if}
                        <button onclick={stopTimer}
                            class="w-8 h-8 flex items-center justify-center bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors shadow-sm"
                            title="Reset"><i class="fas fa-stop text-xs"></i></button>
                        {#if running || time > 0}
                            <button onclick={finishTimer}
                                class="h-8 px-3 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors shadow-sm ml-1"
                                title="Selesai">
                                <i class="fas fa-flag-checkered text-xs mr-1"></i><span class="text-[11px] font-black uppercase tracking-wider">Selesai</span>
                            </button>
                        {/if}
                    </div>
                </div>
            </div>

            <div class="p-5 md:p-6 space-y-5">
                {#if matchData && matchData.athlete1 && matchData.athlete2}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl">
                            <div class="w-3 h-8 rounded-full bg-rose-500 shrink-0"></div>
                            <div>
                                <div class="text-[11px] font-black text-rose-400 uppercase tracking-widest">Pita Merah (AKA)</div>
                                <div class="text-[15px] font-black text-slate-800 uppercase leading-tight">{matchData.athlete1.name}</div>
                                {#if matchData.athlete1.contingent}
                                    <div class="text-[13px] text-slate-500">{matchData.athlete1.contingent}</div>
                                {/if}
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="w-3 h-8 rounded-full bg-slate-600 shrink-0"></div>
                            <div>
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pita Putih (SHIRO)</div>
                                <div class="text-[15px] font-black text-slate-800 uppercase leading-tight">{matchData.athlete2.name}</div>
                                {#if matchData.athlete2.contingent}
                                    <div class="text-[13px] text-slate-500">{matchData.athlete2.contingent}</div>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/if}

                <!-- Points Matrix Grid -->
                <div class="overflow-hidden border border-slate-200 rounded-2xl shadow-sm">
                    <div class="grid grid-cols-2">
                        <div class="bg-rose-600 text-white py-2 text-center text-[13px] font-black uppercase tracking-widest border-r border-white/20">Pita Merah (AKA)</div>
                        <div class="bg-slate-800 text-white py-2 text-center text-[13px] font-black uppercase tracking-widest">Pita Putih (SHIRO)</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-[13px] border-collapse" style="min-width:780px;">
                            <thead class="bg-slate-700 text-white uppercase font-black text-[11px] tracking-widest">
                                <tr>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keputusan</th>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keterangan</th>
                                    <th class="px-3 py-2 border-r border-white/10">Poin</th>
                                    <th class="px-3 py-2 border-r border-white/10">Jml</th>
                                    <th class="px-3 py-2 border-r border-slate-500 w-24">Kontrol</th>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keputusan</th>
                                    <th class="px-3 py-2 border-r border-white/10 text-left">Keterangan</th>
                                    <th class="px-3 py-2 border-r border-white/10">Poin</th>
                                    <th class="px-3 py-2 border-r border-white/10">Jml</th>
                                    <th class="px-3 py-2 w-24">Kontrol</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-bold text-slate-800 text-center">
                                {#each categories as cat}
                                    {@const isBatsu = cat.key.includes('batsu')}
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <!-- AKA -->
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left">
                                            <span class="text-[11px] font-black uppercase tracking-widest {isBatsu ? 'text-rose-500' : 'text-slate-400'}">{cat.label}</span>
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left text-slate-700">{cat.desc}</td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-slate-400">{cat.val}</td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 font-black text-base {isBatsu ? 'text-rose-600' : 'text-slate-800'}">
                                            {isBatsu ? '-' : ''}{cat.val * scoringAka[cat.key]}
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-slate-200">
                                            <div class="inline-flex rounded-lg overflow-hidden border border-slate-200">
                                                <button type="button" onclick={() => updateScore('aka', cat.key, -1)}
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 transition-colors text-slate-500"><i class="fas fa-minus text-[10px]"></i></button>
                                                <span class="px-3 py-1.5 bg-white min-w-[32px] font-black text-slate-800 border-x border-slate-200">{scoringAka[cat.key]}</span>
                                                <button type="button" onclick={() => updateScore('aka', cat.key, 1)}
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-600 transition-colors text-slate-500"><i class="fas fa-plus text-[10px]"></i></button>
                                            </div>
                                        </td>
                                        <!-- SHIRO -->
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left">
                                            <span class="text-[11px] font-black uppercase tracking-widest {isBatsu ? 'text-rose-500' : 'text-slate-400'}">{cat.label}</span>
                                        </td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-left text-slate-700">{cat.desc}</td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 text-slate-400">{cat.val}</td>
                                        <td class="px-3 py-2.5 border-r border-slate-100 font-black text-base {isBatsu ? 'text-rose-600' : 'text-slate-800'}">
                                            {isBatsu ? '-' : ''}{cat.val * scoringShiro[cat.key]}
                                        </td>
                                        <td class="px-3 py-2.5">
                                            <div class="inline-flex rounded-lg overflow-hidden border border-slate-200">
                                                <button type="button" onclick={() => updateScore('shiro', cat.key, -1)}
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 transition-colors text-slate-500"><i class="fas fa-minus text-[10px]"></i></button>
                                                <span class="px-3 py-1.5 bg-white min-w-[32px] font-black text-slate-800 border-x border-slate-200">{scoringShiro[cat.key]}</span>
                                                <button type="button" onclick={() => updateScore('shiro', cat.key, 1)}
                                                    class="px-2.5 py-1.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-600 transition-colors text-slate-500"><i class="fas fa-plus text-[10px]"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                {/each}
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 font-black text-[15px] uppercase">
                                    <td colspan="3" class="px-3 py-3 text-right text-slate-500 text-[13px]">Total Merah</td>
                                    <td class="px-3 py-3 border-r border-slate-200">
                                        <span class="text-xl font-black text-rose-600">{scoreRed}</span>
                                    </td>
                                    <td class="border-r border-slate-200"></td>
                                    <td colspan="3" class="px-3 py-3 text-right text-slate-500 text-[13px]">Total Putih</td>
                                    <td class="px-3 py-3 border-r border-slate-200">
                                        <span class="text-xl font-black text-blue-600">{scoreBlue}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- SIGNATURE PAD FOR OFFICIALS -->
                <div class="mt-6 border-t border-slate-200 pt-6 mb-6">
                    <div class="text-[13px] font-black text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-signature text-rose-500"></i> Pengesahan & Tanda Tangan Hasil Pertandingan
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ARBITRASE -->
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Arbitrase</label>
                            <input type="text" bind:value={sigArbitraseName} class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Arbitrase...">
                            <SignaturePad bind:value={sigArbitraseData} name="Tanda Tangan Arbitrase" />
                        </div>

                        <!-- KOORDINATOR -->
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Koordinator</label>
                            <input type="text" bind:value={sigKoordinatorName} class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Koordinator Pertandingan...">
                            <SignaturePad bind:value={sigKoordinatorData} name="Tanda Tangan Koordinator" />
                        </div>

                        <!-- WASIT -->
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Wasit</label>
                            <input type="text" bind:value={sigWasitName} class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Wasit Lapangan...">
                            <SignaturePad bind:value={sigWasitData} name="Tanda Tangan Wasit" />
                        </div>

                        <!-- PANITERA -->
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Koordinator Panitera</label>
                            <input type="text" bind:value={sigPanitera[0].name} class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Koordinator Panitera...">
                            <SignaturePad bind:value={sigPanitera[0].signature} name="Tanda Tangan Koordinator Panitera" />
                        </div>

                        <!-- MANAGER AKA (RED) -->
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-rose-500 uppercase tracking-widest">Manajer Pita Merah (AKA)</label>
                            <input type="text" bind:value={sigManagerRedName} class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Manajer Aka...">
                            <SignaturePad bind:value={sigManagerRedData} name="Tanda Tangan Manajer Merah" />
                        </div>

                        <!-- MANAGER SHIRO (WHITE) -->
                        <div class="flex flex-col gap-2">
                            <label class="text-[11px] font-black text-blue-500 uppercase tracking-widest">Manajer Pita Putih (SHIRO)</label>
                            <input type="text" bind:value={sigManagerWhiteName} class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm font-bold bg-white text-slate-800 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Ketik nama Manajer Shiro...">
                            <SignaturePad bind:value={sigManagerWhiteData} name="Tanda Tangan Manajer Putih" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick={submitScoring}
                        class="flex-1 py-3.5 bg-teal-500 hover:bg-teal-600 text-white font-black text-[15px] uppercase tracking-widest rounded-xl shadow-lg shadow-teal-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="fas fa-check-double text-lg"></i> SAH — Simpan Penilaian
                    </button>
                    <button onclick={() => { if(confirm('Reset semua nilai di panel ini ke nol?')) resetDetailedScoring(); }}
                        class="sm:w-auto px-5 py-3.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 font-black text-[15px] uppercase tracking-widest transition-all active:scale-95 flex items-center justify-center gap-2 border border-rose-200">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    {:else}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-4 py-3 bg-gradient-to-r from-slate-700 to-slate-600 flex items-center gap-2">
                <i class="fas fa-stopwatch text-amber-400"></i>
                <span class="text-[15px] font-black text-white uppercase tracking-widest">Panel Penilaian</span>
            </div>
            <div class="py-16 flex flex-col items-center justify-center text-center px-6">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-play-circle text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Belum ada pertandingan yang dipanggil</h3>
                <p class="text-[15px] text-slate-500 mt-2 max-w-md">Klik tombol <span class="font-black text-slate-700">Panggil</span> pada salah satu match di bagan bawah untuk memulai penilaian dan mengaktifkan timer.</p>
            </div>
        </div>
    {/if}

    <!-- BAGAN / BRACKET VIEW -->
    {#if ubRounds.length === 0}
        <div style="text-align:center; padding:60px 20px; background:#fff; border:2px dashed var(--paper2); border-radius:24px;">
            <i class="fas fa-sitemap" style="font-size:40px; color:var(--paper2); margin-bottom:16px;"></i>
            <h3 style="font-family:'Cinzel', serif; font-size:16px; margin:0 0 8px; color:var(--ink);">Bagan Belum Dibuat</h3>
            <p style="font-size:13px; color:var(--smoke); margin:0;">Silakan generate drawing di Technical Meeting.</p>
        </div>
    {:else}
        <!-- UPPER BRACKET -->
        <div class="bracket-wrapper">
            <div class="bracket-hdr ub">
                <i class="fas fa-arrow-up" style="color:#2980b9;"></i> UPPER BRACKET — WINNER PATH
                <span style="margin-left:auto; font-size:10px; opacity:0.6;"><i class="fas fa-arrows-left-right"></i> geser</span>
            </div>
            <div class="bracket-scroll">
                {#each ubRounds as round, roundIdx}
                    {@const isUBFinal = (roundIdx === totalUB - 1)}
                    {@const isUBSemi = (roundIdx === totalUB - 2 && totalUB > 2)}
                    {@const roundLabel = isUBFinal ? 'UB FINAL' : (isUBSemi ? 'UB SEMI FINAL' : `UB R${roundIdx + 1}`)}
                    <div class="bracket-round-col">
                        <div class="bracket-round-title">{roundLabel}</div>
                        {#each round as match, matchIdx}
                            {@const nodeKey = `ub_${roundIdx}_${matchIdx}`}
                            {@const isActive = (activeBracketNode === nodeKey)}
                            {@const isDone = (match.winner !== null && typeof match.winner !== 'undefined')}
                            <div class="b-match {isActive ? 'active' : ''}">
                                <div class="b-slot {isDone && match.winner === 'athlete1' ? 'winner' : ''}">
                                    <div class="b-slot-color red"></div>
                                    <div class="b-slot-info">
                                        {#if match.athlete1}
                                            <div class="b-slot-name">{match.athlete1.name}</div>
                                            <div class="b-slot-cont">{match.athlete1.contingent}</div>
                                        {:else}
                                            <div class="b-slot-empty">Menunggu Lawan</div>
                                        {/if}
                                    </div>
                                    {#if isDone && match.winner === 'athlete1'}
                                        <i class="fas fa-check-circle b-win-icon"></i>
                                    {/if}
                                </div>
                                <div class="b-slot {isDone && match.winner === 'athlete2' ? 'winner' : ''}">
                                    <div class="b-slot-color blue"></div>
                                    <div class="b-slot-info">
                                        {#if match.athlete2}
                                            <div class="b-slot-name">{match.athlete2.name}</div>
                                            <div class="b-slot-cont">{match.athlete2.contingent}</div>
                                        {:else}
                                            <div class="b-slot-empty">Menunggu Lawan</div>
                                        {/if}
                                    </div>
                                    {#if isDone && match.winner === 'athlete2'}
                                        <i class="fas fa-check-circle b-win-icon"></i>
                                    {/if}
                                </div>
                                {#if match.athlete1 && match.athlete2 && match.athlete1.id !== 'BYE' && match.athlete2.id !== 'BYE'}
                                    <div class="b-match-actions">
                                        {#if !isActive && !isDone}
                                            <button onclick={() => callMatch(nodeKey, roundIdx, matchIdx, 'ub')} class="b-action-btn"><i class="fas fa-bullhorn"></i> Panggil</button>
                                        {:else if isActive}
                                            <button onclick={() => openMatchModal('ub', roundIdx, matchIdx)} class="b-action-btn" style="color:#2980b9;"><i class="fas fa-edit"></i> Skor</button>
                                            <button onclick={dismissMatch} class="b-action-btn" style="color:var(--red);"><i class="fas fa-times"></i> Tutup</button>
                                        {:else if isDone && !isActive}
                                            <button disabled class="b-action-btn opacity-50 cursor-not-allowed" style="color:#7f8c8d;"><i class="fas fa-check-circle"></i> Selesai</button>
                                        {/if}
                                    </div>
                                {/if}
                            </div>
                        {/each}
                    </div>
                {/each}
            </div>
        </div>

        <!-- LOWER BRACKET -->
        {#if lbRounds.length > 0}
            <div class="bracket-wrapper">
                <div class="bracket-hdr lb">
                    <i class="fas fa-arrow-down" style="color:#d35400;"></i> LOWER BRACKET — SECOND CHANCE PATH
                    <span style="margin-left:auto; font-size:10px; opacity:0.6;"><i class="fas fa-arrows-left-right"></i> geser</span>
                </div>
                <div class="bracket-scroll">
                    {#each lbRounds as round, lbRoundIdx}
                        {@const isLBFinal = (lbRoundIdx === totalLB - 1)}
                        {@const isLBSemi = (lbRoundIdx === totalLB - 2)}
                        {@const lbLabel = isLBFinal ? 'LB FINAL' : (isLBSemi ? 'LB SEMI' : `LB R${lbRoundIdx + 1}`)}
                        <div class="bracket-round-col">
                            <div class="bracket-round-title">{lbLabel}</div>
                            {#each round as match, matchIdx}
                                {@const nodeKey = `lb_${lbRoundIdx}_${matchIdx}`}
                                {@const isActive = (activeBracketNode === nodeKey)}
                                {@const isDone = (match.winner !== null && typeof match.winner !== 'undefined')}
                                <div class="b-match {isActive ? 'active' : ''}">
                                    <div class="b-slot {isDone && match.winner === 'athlete1' ? 'winner' : ''}">
                                        <div class="b-slot-color red"></div>
                                        <div class="b-slot-info">
                                            {#if match.athlete1}
                                                <div class="b-slot-name">{match.athlete1.name}</div>
                                                <div class="b-slot-cont">{match.athlete1.contingent}</div>
                                            {:else}
                                                <div class="b-slot-empty">Menunggu Lawan</div>
                                            {/if}
                                        </div>
                                        {#if isDone && match.winner === 'athlete1'}
                                            <i class="fas fa-check-circle b-win-icon"></i>
                                        {/if}
                                    </div>
                                    <div class="b-slot {isDone && match.winner === 'athlete2' ? 'winner' : ''}">
                                        <div class="b-slot-color blue"></div>
                                        <div class="b-slot-info">
                                            {#if match.athlete2}
                                                <div class="b-slot-name">{match.athlete2.name}</div>
                                                <div class="b-slot-cont">{match.athlete2.contingent}</div>
                                            {:else}
                                                <div class="b-slot-empty">Menunggu Lawan</div>
                                            {/if}
                                        </div>
                                        {#if isDone && match.winner === 'athlete2'}
                                            <i class="fas fa-check-circle b-win-icon"></i>
                                        {/if}
                                    </div>
                                    {#if match.athlete1 && match.athlete2 && match.athlete1.id !== 'BYE' && match.athlete2.id !== 'BYE'}
                                        <div class="b-match-actions">
                                            {#if !isActive && !isDone}
                                                <button onclick={() => callMatch(nodeKey, lbRoundIdx, matchIdx, 'lb')} class="b-action-btn"><i class="fas fa-bullhorn"></i> Panggil</button>
                                            {:else if isActive}
                                                <button onclick={() => callMatch(nodeKey, lbRoundIdx, matchIdx, 'lb')} class="b-action-btn"><i class="fas fa-redo"></i> Ulang</button>
                                                <button onclick={() => openMatchModal('lb', lbRoundIdx, matchIdx)} class="b-action-btn" style="color:#2980b9;"><i class="fas fa-edit"></i> Skor</button>
                                                <button onclick={dismissMatch} class="b-action-btn" style="color:var(--red);"><i class="fas fa-times"></i> Tutup</button>
                                            {:else if isDone && !isActive}
                                                <button disabled class="b-action-btn opacity-50 cursor-not-allowed" style="color:#7f8c8d;"><i class="fas fa-check-circle"></i> Selesai</button>
                                            {/if}
                                        </div>
                                    {/if}
                                </div>
                            {/each}
                        </div>
                    {/each}
                </div>
            </div>
        {/if}

        <!-- GRAND FINAL -->
        {#if grandFinal}
            {@const gfDone = (grandFinal.winner !== null && typeof grandFinal.winner !== 'undefined')}
            {@const gfActive = activeBracketNode === 'gf_0_0'}
            <div class="bracket-wrapper">
                <div class="bracket-hdr gf">
                    <i class="fas fa-trophy" style="color:#f39c12;"></i> GRAND FINAL — UB CHAMPION VS LB CHAMPION
                </div>
                <div style="padding: 32px; display:flex; justify-content:center;">
                    <div class="b-match {gfActive ? 'active' : ''}" style="width: 100%; max-width: 400px; transform: scale(1.1); box-shadow: 0 8px 24px rgba(243, 156, 18, 0.2);">
                        <div class="b-slot {gfDone && grandFinal.winner === 'athlete1' ? 'winner' : ''}" style="padding: 16px 20px;">
                            <div class="b-slot-color red"></div>
                            <div class="b-slot-info">
                                <div style="font-size:9px; color:var(--red); font-weight:700; text-transform:uppercase; margin-bottom:4px;">UB Champion</div>
                                {#if grandFinal.athlete1}
                                    <div class="b-slot-name" style="font-size:15px;">{grandFinal.athlete1.name}</div>
                                    <div class="b-slot-cont">{grandFinal.athlete1.contingent}</div>
                                {:else}
                                    <div class="b-slot-empty">Menunggu Champion</div>
                                {/if}
                            </div>
                            {#if gfDone && grandFinal.winner === 'athlete1'}
                                <i class="fas fa-trophy" style="color:#f1c40f; font-size:16px;"></i>
                            {/if}
                        </div>
                        <div style="text-align:center; padding:4px; font-weight:700; font-size:10px; color:var(--smoke); background:var(--paper);">VS</div>
                        <div class="b-slot {gfDone && grandFinal.winner === 'athlete2' ? 'winner' : ''}" style="padding: 16px 20px;">
                            <div class="b-slot-color blue"></div>
                            <div class="b-slot-info">
                                <div style="font-size:9px; color:#2980b9; font-weight:700; text-transform:uppercase; margin-bottom:4px;">LB Champion</div>
                                {#if grandFinal.athlete2}
                                    <div class="b-slot-name" style="font-size:15px;">{grandFinal.athlete2.name}</div>
                                    <div class="b-slot-cont">{grandFinal.athlete2.contingent}</div>
                                {:else}
                                    <div class="b-slot-empty">Menunggu Champion</div>
                                {/if}
                            </div>
                            {#if gfDone && grandFinal.winner === 'athlete2'}
                                <i class="fas fa-trophy" style="color:#f1c40f; font-size:16px;"></i>
                            {/if}
                        </div>
                        {#if grandFinal.athlete1 && grandFinal.athlete2}
                            <div class="b-match-actions">
                                {#if !gfActive && !gfDone}
                                    <button onclick={callGrandFinal} class="b-action-btn"><i class="fas fa-bullhorn"></i> Panggil GF</button>
                                {:else if gfActive}
                                    <button onclick={callGrandFinal} class="b-action-btn"><i class="fas fa-redo"></i> Ulang</button>
                                    <button onclick={openGrandFinalModal} class="b-action-btn" style="color:#f39c12;"><i class="fas fa-edit"></i> Skor GF</button>
                                    <button onclick={dismissMatch} class="b-action-btn" style="color:var(--red);"><i class="fas fa-times"></i> Tutup</button>
                                {:else if gfDone && !gfActive}
                                    <button disabled class="b-action-btn opacity-50 cursor-not-allowed" style="color:#7f8c8d;"><i class="fas fa-check-circle"></i> Selesai</button>
                                {/if}
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        {/if}

        <!-- FINAL CHAMPIONS LEADERBOARD -->
        {#if Object.keys(juaraMap).length > 0}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden mt-12 mb-8">
                <div class="px-6 py-4 bg-slate-900 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                            <i class="fas fa-trophy text-white text-lg"></i>
                        </div>
                        <h2 class="text-lg font-black text-white uppercase tracking-tight leading-none">Hasil Akhir Pertandingan</h2>
                    </div>
                    {#if Object.keys(juaraMap).length >= 2}
                        <button onclick={confirmChampion}
                            class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-xl shadow-lg shadow-emerald-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="fas fa-save text-[13px]"></i> Simpan Juara Ke Laporan
                        </button>
                    {/if}
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {#each [1, 2, 3, 4] as rank}
                            {@const athlete = juaraMap[rank]}
                            {@const conf = rank === 1 ? { label: 'Juara 1', icon: '🥇', bg: 'bg-amber-50', border: 'border-amber-200', text: 'text-amber-600' } :
                                          rank === 2 ? { label: 'Juara 2', icon: '🥈', bg: 'bg-slate-50', border: 'border-slate-200', text: 'text-slate-600' } :
                                          { label: 'Juara 3 Bersama', icon: '🥉', bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-600' }}
                            <div class="relative group">
                                <div class="h-full px-5 py-8 rounded-2xl border {athlete ? `${conf.border} ${conf.bg}` : 'border-slate-100 bg-white'} flex flex-col items-center text-center transition-all duration-300 {athlete ? 'shadow-md shadow-amber-500/5' : ''}">
                                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">{conf.icon}</div>
                                    <div class="text-[10px] font-black {conf.text} uppercase tracking-[0.2em] mb-3">{conf.label}</div>
                                    {#if athlete}
                                        <div class="text-base font-black text-slate-800 uppercase leading-tight mb-1">{athlete.name}</div>
                                        <div class="text-xs font-bold text-slate-500">{athlete.contingent || '—'}</div>
                                    {:else}
                                        <div class="w-12 h-1 bg-slate-100 rounded-full mb-3 mt-1"></div>
                                        <div class="text-[11px] font-bold text-slate-300 italic uppercase tracking-wider">Menunggu Hasil...</div>
                                    {/if}
                                </div>
                            </div>
                        {/each}
                    </div>
                </div>
            </div>
        {/if}
    {/if}

    <!-- OFFICIALS BOTTOM LIST -->
    <div class="officials-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="tm-card official-card" style="border-left-color: #f59e0b;">
            <div class="official-label">
                <i class="fas fa-gavel"></i> Dewan Arbitrase
            </div>
            <div class="official-val">
                {assignedArbitrase?.referee?.user?.name || 'Belum ditugaskan'}
            </div>
            <div class="official-sub">Lisensi: {assignedArbitrase?.referee?.license_number || '-'}</div>
        </div>
        
        <div class="tm-card official-card" style="border-left-color: #10b981;">
            <div class="official-label">
                <i class="fas fa-user-shield"></i> Dewan Hakim / Wasit Lapangan
            </div>
            <div class="official-val">
                {#if assignedReferees && assignedReferees.length > 0}
                    <ol class="official-list" style="list-style-type: decimal; padding-left: 16px;">
                        {#each assignedReferees as sr}
                            <li>{sr.referee?.user?.name || 'Wasit'} <span style="font-size:10px; font-weight:normal; color:var(--smoke);"> (Juri {sr.judge_index})</span></li>
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
                    <ul class="official-list" style="list-style-type: none; padding-left: 0;">
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

        <div class="tm-card official-card" style="border-left-color: var(--ink);">
            <div class="official-label">
                <i class="fas fa-users"></i> Para Panitera
            </div>
            <div class="official-val">
                {#if assignedPaniteras && assignedPaniteras.length > 0}
                    <ul class="official-list" style="list-style-type: none; padding-left: 0;">
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

    <!-- Premium Non-blocking Toast Notification -->
    {#if toast.show}
        <div class="toast-container">
            <div class="toast-item {toast.type}">
                {#if toast.type === 'success'}
                    <i class="fas fa-check-circle" style="color: #2ecc71;"></i>
                {:else}
                    <i class="fas fa-exclamation-circle" style="color: #e74c3c;"></i>
                {/if}
                <span>{toast.message}</span>
            </div>
        </div>
    {/if}
</div>

<style>
    .tm-page {
        padding: 24px;
        padding-bottom: 100px;
        background: var(--paper, #F7F4EF);
        min-height: 100vh;
        overflow-x: hidden;
        box-sizing: border-box;
        max-width: 100%;
    }

    /* HEADER */
    .tm-hdr {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 12px;
    }

    .tm-hdr h2 {
        font-family: 'Cinzel', serif;
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px;
        color: var(--ink, #2c3e50);
    }

    .tm-badge-title {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(192, 57, 43, .1);
        color: var(--red, #c0392b);
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 8px;
    }

    /* ELEGANT BRACKET */
    .bracket-wrapper {
        margin-bottom: 24px;
        background: #fff;
        border: 1px solid var(--paper2, #e0dcd3);
        border-radius: 16px;
        overflow: hidden;
    }

    .bracket-hdr {
        padding: 12px 18px;
        border-bottom: 1px solid var(--paper2, #e0dcd3);
        font-family: 'Cinzel', serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bracket-hdr.ub {
        background: rgba(41, 128, 185, .05);
    }

    .bracket-hdr.lb {
        background: rgba(211, 84, 0, .05);
    }

    .bracket-hdr.gf {
        background: rgba(243, 156, 18, .05);
    }

    .bracket-scroll {
        overflow-x: auto;
        padding: 20px;
        display: flex;
        gap: 32px;
        align-items: flex-start;
        scrollbar-width: thin;
        scrollbar-color: var(--paper2, #e0dcd3) transparent;
        -webkit-overflow-scrolling: touch;
    }

    .bracket-round-col {
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 260px;
        flex-shrink: 0;
    }

    .bracket-round-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--smoke, #7f8c8d);
        text-align: center;
        margin-bottom: 4px;
    }

    .b-match {
        background: #fff;
        border: 1px solid var(--paper2, #e0dcd3);
        border-radius: 12px;
        overflow: hidden;
        transition: all .2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .02);
        position: relative;
    }

    .b-match.active {
        border-color: var(--red, #c0392b);
        box-shadow: 0 4px 12px rgba(192, 57, 43, 0.15);
    }

    .b-slot {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        transition: background .15s;
    }

    .b-slot+.b-slot {
        border-top: 1px solid var(--paper2, #e0dcd3);
    }

    .b-slot.winner {
        background: rgba(39, 174, 96, .06);
    }

    .b-slot-color {
        width: 4px;
        height: 16px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .b-slot-color.red {
        background: var(--red, #c0392b);
    }

    .b-slot-color.blue {
        background: #2980b9;
    }

    .b-slot-info {
        flex: 1;
        min-width: 0;
    }

    .b-slot-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .b-slot-cont {
        font-size: 10px;
        color: var(--smoke, #7f8c8d);
        font-weight: 600;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .b-slot-empty {
        font-size: 12px;
        color: var(--smoke, #7f8c8d);
        font-style: italic;
    }

    .b-win-icon {
        color: #27ae60;
        font-size: 11px;
        flex-shrink: 0;
    }

    .b-match-actions {
        display: flex;
        background: var(--paper, #F7F4EF);
        border-top: 1px solid var(--paper2, #e0dcd3);
    }

    .b-action-btn {
        flex: 1;
        padding: 8px;
        text-align: center;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink, #2c3e50);
        background: none;
        border: none;
        cursor: pointer;
        border-right: 1px solid var(--paper2, #e0dcd3);
    }

    .b-action-btn:disabled {
        background: #f2f2f2 !important;
        color: #999999 !important;
        cursor: not-allowed;
    }

    .b-action-btn:last-child {
        border-right: none;
    }

    .b-action-btn:hover {
        background: #fff;
        color: var(--red, #c0392b);
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
        border-left: 4px solid var(--red, #c0392b);
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
        color: var(--red, #c0392b);
        font-size: 13px;
    }

    .official-val {
        font-size: 15px;
        font-weight: 700;
        color: var(--ink, #2c3e50);
        font-family: 'Outfit', sans-serif;
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
        font-family: 'DM Sans', sans-serif;
        transition: all .15s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-gen.primary {
        background: var(--ink, #2c3e50);
        color: #fff;
    }

    .btn-gen.primary:hover {
        background: #1a252f;
        transform: translateY(-1px);
    }

    .btn-gen.danger {
        background: var(--red, #c0392b);
        color: #fff;
    }

    .btn-gen.danger:hover {
        background: #a93226;
    }

    .btn-gen.ghost {
        background: #fff;
        color: var(--ink, #2c3e50);
        border: 1px solid var(--paper2, #e0dcd3);
    }

    .btn-gen.ghost:hover {
        border-color: var(--ink, #2c3e50);
    }

    /* Toast styles */
    .toast-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .toast-item {
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        animation: slideIn 0.2s ease-out;
    }
    .toast-item.success {
        border-left: 4px solid #2ecc71;
        background: #1a252f;
    }
    .toast-item.error {
        border-left: 4px solid #e74c3c;
        background: #1a252f;
    }
    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
