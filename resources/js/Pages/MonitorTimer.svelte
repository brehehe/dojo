<script>
    import { onMount, onDestroy } from 'svelte';
    import { createAdaptivePolling } from '../lib/adaptivePolling';
    import { conditionalJsonFetch } from '../lib/conditionalFetch';

    // Props passed from Inertia
    let { courtId } = $props();

    // Svelte 5 Reactive States
    let court = $state(null);
    let time = $state(0);
    let running = $state(false);
    let countdown = $state(0);
    let offset = $state(0);
    let stateObj = $state({ status: 'stopped', elapsed_ms: 0, started_at_ms: null, countdown_end_ms: null });
    let playedIntervals = $state(new Set());
    let buzzerPool = [];

    let localTickInterval;

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
            let { data, notModified } = await conditionalJsonFetch(`/api/svelte-monitor/timer/court/${courtId}/state`);
            if (destroyed) return;
            if (notModified) return;
            if (destroyed) return;
            if (!data) return;

            court = data.court;
            let newState = data.timer_state;
            if (!newState) return;

            offset = newState.server_time_ms - Date.now();
            stateObj = newState;

            let wasRunning = running;
            running = (newState.status === 'running');

            // Play buzzer when timer newly starts
            if (running && !wasRunning && (!newState.elapsed_ms || newState.elapsed_ms < 1000)) {
                if (!playedIntervals.has('start')) {
                    playedIntervals.add('start');
                    playBuzzer();
                }
            }

            if (newState.status !== 'countdown') {
                countdown = 0;
            }

            if (!running && time < 500) {
                playedIntervals.clear();
            }
        } catch (e) {
            console.error('Error syncing timer state:', e);
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

    function playBuzzer() {
        try {
            let audio = buzzerPool.find(a => a.paused || a.ended);
            if (!audio) {
                audio = new Audio('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
                audio.preload = 'auto';
                buzzerPool.push(audio);
            }
            audio.currentTime = 0;
            audio.play().catch(() => {});
        } catch(e) {}
    }

    function formatTime() {
        let t = Math.max(0, time);
        let m = Math.floor(t / 60000);
        let s = Math.floor((t % 60000) / 1000);
        let ms = Math.floor((t % 1000) / 10);
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}.${ms.toString().padStart(2, '0')}`;
    }

    function formatCountdown() {
        if (countdown === 5) return 'Siap';
        if (countdown === 4) return '3';
        if (countdown === 3) return '2';
        if (countdown === 2) return '1';
        if (countdown === 1) return 'Mulai';
        return countdown > 0 ? countdown.toString() : '';
    }

    onMount(() => {
        destroyed = false;
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

        // Initial sync
        sync();

        // Listen to CourtUpdated event on court.{courtId} channel
        if (window.Echo) {
            window.Echo.channel(`court.${courtId}`).listen('CourtUpdated', (e) => {
                if (destroyed) return;
                polling?.markRealtimeHealthy();
                if (e.timer_state) {
                    offset = e.timer_state.server_time_ms - Date.now();
                    stateObj = e.timer_state;

                    let wasRunning = running;
                    running = (e.timer_state.status === 'running');

                    // Play buzzer when timer newly starts
                    if (running && !wasRunning && (!e.timer_state.elapsed_ms || e.timer_state.elapsed_ms < 1000)) {
                        if (!playedIntervals.has('start')) {
                            playedIntervals.add('start');
                            playBuzzer();
                        }
                    }

                    if (e.timer_state.status !== 'countdown') {
                        countdown = 0;
                    }

                    if (!running && time < 500) {
                        playedIntervals.clear();
                    }
                } else {
                    sync();
                }
            });
        }

        polling = createAdaptivePolling({
            fetchNow: sync,
            normalInterval: pollDelay,
            healthyInterval: 10000,
            staleAfter: 10000,
            immediate: false,
        });
        polling.start();

        // High-speed local interpolation (30ms) for smooth layout
        localTickInterval = setInterval(() => {
            let isRandori = court && court.active_match && (court.active_match.draft_type === 'randori' || court.active_match.name.toLowerCase().includes('randori'));

            if (running && stateObj.started_at_ms) {
                let expected = (stateObj.elapsed_ms || 0) + (Date.now() + offset - stateObj.started_at_ms);
                time = isRandori ? Math.min(expected, 120000) : expected;

                let currentSecond = Math.floor(time / 1000);
                let isPemula = court && court.active_match && (court.active_match.age_group_id === 1 || (court.active_match.age_group && court.active_match.age_group.name.toLowerCase() === 'pemula'));
                let isTandoku = court && court.active_match && (court.active_match.name.toLowerCase().includes('tandoku') || court.active_match.max_athletes == 1);
                let isShortDuration = isPemula || isTandoku;

                if (isRandori) {
                    if (time >= 120000 && !playedIntervals.has(120)) {
                        time = 120000;
                        running = false;
                        playedIntervals.add(120);
                        playBuzzer();
                    }
                } else {
                    if (isShortDuration) {
                        if ((currentSecond === 60 && !playedIntervals.has(60)) ||
                            (currentSecond === 90 && !playedIntervals.has(90)) ||
                            (currentSecond === 120 && !playedIntervals.has(120))) {
                            playedIntervals.add(currentSecond);
                            playBuzzer();
                        }
                    } else {
                        if ((currentSecond === 90 && !playedIntervals.has(90)) ||
                            (currentSecond === 120 && !playedIntervals.has(120))) {
                            playedIntervals.add(currentSecond);
                            playBuzzer();
                        }
                    }
                }
            } else if (stateObj.status === 'countdown' && stateObj.countdown_end_ms) {
                let remaining = stateObj.countdown_end_ms - (Date.now() + offset);
                if (remaining > 0) {
                    countdown = Math.ceil(remaining / 1000);
                } else {
                    countdown = 0;
                }
                let rawTime = stateObj.elapsed_ms || 0;
                time = isRandori ? Math.min(rawTime, 120000) : rawTime;
            } else {
                countdown = 0;
                let rawTime = stateObj.elapsed_ms || 0;
                time = isRandori ? Math.min(rawTime, 120000) : rawTime;
            }
        }, 30);
    });

    onDestroy(() => {
        destroyed = true;
        syncQueued = false;
        if (window.Echo) {
            window.Echo.leave(`court.${courtId}`);
        }
        polling?.stop();
        clearInterval(localTickInterval);
        if (queuedSyncTimeout) clearTimeout(queuedSyncTimeout);
    });
</script>

<div class="min-h-screen bg-slate-900 text-white flex flex-col items-center justify-center font-sans overflow-hidden select-none relative">
    <!-- Background Accents -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-600/20 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[100px]"></div>
    </div>

    <!-- Top Bar with Match Context -->
    <div class="absolute top-0 left-0 w-full p-6 md:p-8 flex justify-between items-start z-10">
        <div>
            <h1 class="text-xl md:text-2xl font-black uppercase tracking-widest text-slate-300">
                <i class="fas fa-stopwatch text-emerald-400 mr-2"></i> Monitor Waktu - {court ? court.name : 'Memuat...'}
            </h1>
            <div class="mt-2 flex flex-col gap-1">
                {#if court && court.active_match_id && court.active_match}
                    <p class="text-sm md:text-lg font-black text-white uppercase tracking-widest">
                        {court.active_match.merge_detail?.merge?.name || court.active_match.name}
                    </p>
                    <p class="text-xs md:text-sm font-bold text-emerald-400 uppercase tracking-widest">
                        {#if court.active_drawing && court.active_drawing.match_number_id != court.active_match.id}
                            <span class="text-amber-300">{court.active_drawing.match_number?.name || ''}</span> &bull;
                        {/if}
                        {court.active_match.draft_type} &bull; {court.active_match.age_group?.name || ''}
                    </p>
                {:else}
                    <p class="text-sm md:text-base font-bold text-slate-500 uppercase tracking-widest mt-1">IDLE - Menunggu Panggilan</p>
                {/if}
            </div>
        </div>
    </div>

    <!-- Display -->
    <div class="relative z-10 w-full flex flex-col items-center justify-center flex-1">
        <div class="font-mono text-[15vw] md:text-[12rem] lg:text-[18rem] font-black text-white leading-none tracking-tighter drop-shadow-[0_0_40px_rgba(16,185,129,0.3)] transition-colors duration-300"
            class:text-emerald-400={running}
            class:text-amber-400={!running && time > 0 && countdown === 0}
            class:text-orange-500={countdown > 0}
            class:text-white={time === 0 && countdown === 0}>
            {#if countdown > 0}
                <span>{formatCountdown()}</span>
            {:else}
                <span>{formatTime()}</span>
            {/if}
        </div>
    </div>
</div>
