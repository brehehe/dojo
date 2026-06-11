<script>
    import { onDestroy } from 'svelte';

    // Svelte 5 State
    let time = $state(0);
    let running = $state(false);
    let interval = $state(null);
    let startTime = $state(0);

    function formatTime() {
        let m = Math.floor(time / 60000);
        let s = Math.floor((time % 60000) / 1000);
        let ms = Math.floor((time % 1000) / 10);
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}.${ms.toString().padStart(2, '0')}`;
    }

    function start() {
        if (!running) {
            running = true;
            startTime = Date.now() - time;
            interval = setInterval(() => {
                time = Date.now() - startTime;
            }, 10);
        }
    }

    function pause() {
        running = false;
        if (interval) {
            clearInterval(interval);
            interval = null;
        }
    }

    function reset() {
        pause();
        time = 0;
    }

    function toggle() {
        if (running) {
            pause();
        } else {
            start();
        }
    }

    function handleKeydown(e) {
        // Spacebar to play/pause
        if (e.code === 'Space') {
            e.preventDefault(); // Prevent page scroll
            toggle();
        }
        // Escape to reset
        if (e.code === 'Escape') {
            e.preventDefault();
            reset();
        }
    }

    onDestroy(() => {
        if (interval) {
            clearInterval(interval);
        }
    });
</script>

<svelte:window onkeydown={handleKeydown} />

<svelte:head>
    <title>Monitor Timer - Perkemi Timing System</title>
</svelte:head>

<div class="bg-slate-900 text-white min-h-screen flex flex-col items-center justify-center font-sans overflow-hidden select-none relative">
    <!-- Background Accents -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-600/20 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[100px]"></div>
    </div>

    <!-- Top Bar -->
    <div class="absolute top-0 left-0 w-full p-6 md:p-8 flex justify-between items-start z-10">
        <div>
            <h1 class="text-xl md:text-2xl font-black uppercase tracking-widest text-slate-300">
                <i class="fas fa-stopwatch text-emerald-400 mr-2"></i> Monitor Waktu
            </h1>
            <p class="text-sm md:text-base font-bold text-slate-500 uppercase tracking-widest mt-1">Perkemi Timing System</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-slate-800/80 backdrop-blur-md px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-3 shadow-lg">
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest leading-none">Shortcut</span>
                    <span class="text-xs font-bold text-slate-300 mt-1">
                        <kbd class="bg-slate-700 px-1.5 py-0.5 rounded text-white">Space</kbd> Start/Pause
                    </span>
                </div>
            </div>
            <div class="bg-slate-800/80 backdrop-blur-md px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-3 shadow-lg">
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest leading-none">Shortcut</span>
                    <span class="text-xs font-bold text-slate-300 mt-1">
                        <kbd class="bg-slate-700 px-1.5 py-0.5 rounded text-white">Esc</kbd> Reset
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Timer Display -->
    <div class="relative z-10 flex flex-col items-center">
        <div class="font-mono text-[15vw] md:text-[12rem] lg:text-[18rem] font-black text-white leading-none tracking-tighter drop-shadow-[0_0_40px_rgba(16,185,129,0.3)] transition-colors duration-300"
             class:text-emerald-400={running}
             class:text-amber-400={!running && time > 0}
             class:text-white={time === 0}>
            {formatTime()}
        </div>
    </div>

    <!-- Controls -->
    <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-10 flex items-center justify-center gap-6">
        {#if !running}
            <button onclick={start} aria-label="Mulai Timer" class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-emerald-500 hover:bg-emerald-400 text-white shadow-[0_0_30px_rgba(16,185,129,0.4)] flex items-center justify-center transition-all transform hover:scale-105 active:scale-95 border-4 border-emerald-400">
                <i class="fas fa-play text-3xl md:text-4xl ml-2"></i>
            </button>
        {:else}
            <button onclick={pause} aria-label="Jeda Timer" class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-amber-500 hover:bg-amber-400 text-white shadow-[0_0_30px_rgba(245,158,11,0.4)] flex items-center justify-center transition-all transform hover:scale-105 active:scale-95 border-4 border-amber-400">
                <i class="fas fa-pause text-3xl md:text-4xl"></i>
            </button>
        {/if}

        <button onclick={reset} aria-label="Reset Timer" class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-slate-800 hover:bg-rose-500 text-slate-300 hover:text-white shadow-lg flex items-center justify-center transition-all transform hover:scale-105 active:scale-95 border-2 border-slate-600 hover:border-rose-400">
            <i class="fas fa-stop text-2xl md:text-3xl"></i>
        </button>
    </div>
</div>
