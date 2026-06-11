<script>
    import { onMount, onDestroy } from 'svelte';

    let { courtId } = $props();

    let court = $state(null);
    let referees = $state([]);
    let contextRundown = $state(null);
    let contextSession = $state(null);
    let currentTime = $state(new Date());

    let pollInterval;
    let timeInterval;

    async function sync() {
        try {
            let res = await fetch(`/api/svelte-monitor/referee/court/${courtId}/state`);
            let data = await res.json();
            if (!data) return;

            court = data.court;
            referees = data.referees || [];
            contextRundown = data.contextRundown;
            contextSession = data.contextSession;
        } catch (e) {
            console.error('Error syncing referee monitor state:', e);
        }
    }

    onMount(() => {
        sync();
        pollInterval = setInterval(sync, 5000); // Poll every 5s just like wire:poll.5s

        timeInterval = setInterval(() => {
            currentTime = new Date();
        }, 1000);
    });

    onDestroy(() => {
        clearInterval(pollInterval);
        clearInterval(timeInterval);
    });

    function getJudgeLabel(index) {
        switch (index) {
            case 1: return 'Wasit Nasional (Ketua)';
            case 2: return 'Wasit Daerah 1';
            case 3: return 'Wasit Daerah 2';
            case 4: return 'Wasit Pembantu 1';
            case 5: return 'Wasit Pembantu 2';
            default: return `Wasit Juri ${index}`;
        }
    }
</script>

<div class="min-h-screen bg-slate-50 flex flex-col font-sans overflow-hidden select-none">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-700 via-indigo-800 to-slate-900 px-8 py-10 shadow-2xl relative overflow-hidden flex-shrink-0">
        <div class="absolute inset-0 bg-black/10"></div>
        <i class="fas fa-user-tie absolute -right-12 -top-12 text-[200px] text-white opacity-5 blur-sm pointer-events-none"></i>
        
        <div class="relative z-10 flex flex-col items-center justify-center text-center gap-4">
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-md px-6 py-2 rounded-full border border-white/20">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></span>
                <h2 class="text-xl font-black text-white uppercase tracking-widest">MONITOR PERWASITAN</h2>
            </div>
            <h1 class="text-5xl md:text-7xl font-black text-white uppercase tracking-tighter drop-shadow-lg">
                {court ? court.name : 'Memuat...'}
            </h1>
            <p class="text-xl md:text-2xl font-bold text-indigo-200 uppercase tracking-[0.3em]">
                {court && court.active_match ? (court.active_match.merge_detail?.merge?.name || court.active_match.name) : 'IDLE'}
            </p>
            
            <div class="flex flex-wrap items-center justify-center gap-4 mt-2">
                {#if contextSession}
                    <span class="inline-flex items-center gap-2 bg-white/10 px-5 py-2 rounded-xl text-white text-lg font-bold uppercase tracking-wider border border-white/10">
                        <i class="fas fa-clock text-indigo-300"></i> {contextSession.name}
                    </span>
                {/if}
                {#if contextRundown}
                    <span class="inline-flex items-center gap-2 bg-white/10 px-5 py-2 rounded-xl text-white text-lg font-bold uppercase tracking-wider border border-white/10">
                        <i class="fas fa-calendar text-indigo-300"></i> {contextRundown.name || contextRundown.date}
                    </span>
                {/if}
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="flex-1 p-8 md:p-12 lg:p-16 flex flex-col items-center">
        {#if referees.length > 0}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8 w-full max-w-[1600px]">
                {#each referees as schedule}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col items-center text-center transform hover:scale-[1.05] transition-all duration-500 group">
                        <div class="relative mb-8">
                            <!-- Judge Index Badge -->
                            <div class="absolute -top-4 -right-4 w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg z-10 border-4 border-white">
                                {schedule.judge_index}
                            </div>
                            
                            <!-- Photo -->
                            <div class="w-40 h-40 md:w-48 md:h-48 rounded-[2rem] overflow-hidden border-8 border-slate-50 shadow-inner bg-slate-100 group-hover:border-indigo-50 transition-colors">
                                {#if schedule.referee && schedule.referee.photo}
                                    <img src={`/storage/${schedule.referee.photo}`} 
                                         alt={schedule.referee.name}
                                         class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700">
                                {:else}
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300">
                                        <i class="fas fa-user text-6xl text-slate-400 group-hover:text-indigo-400 transition-colors"></i>
                                    </div>
                                {/if}
                            </div>
                        </div>

                        <div class="flex flex-col gap-1 w-full">
                            <span class="text-[11px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">
                                {getJudgeLabel(schedule.judge_index)}
                            </span>
                            <h3 class="text-2xl font-black text-slate-800 uppercase leading-tight line-clamp-2 min-h-[4rem] group-hover:text-indigo-700 transition-colors">
                                {#if schedule.referee && schedule.referee.name !== '-'}
                                    {schedule.referee.name}
                                {:else}
                                    Belum Ditugaskan
                                {/if}
                            </h3>
                            
                            <div class="mt-4 pt-4 border-t border-slate-50 flex flex-col gap-2">
                                <div class="bg-slate-50 rounded-xl py-2 px-4 border border-slate-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Sertifikasi</span>
                                    <span class="text-sm font-black text-slate-600 uppercase">{schedule.referee?.certification_level || '-'}</span>
                                </div>
                                <div class="bg-slate-50 rounded-xl py-2 px-4 border border-slate-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Provinsi</span>
                                    <span class="text-sm font-black text-slate-600 uppercase">{schedule.referee?.province || '-'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                {/each}
            </div>
        {:else}
            <div class="flex-1 flex flex-col items-center justify-center opacity-40">
                <div class="w-48 h-48 rounded-[3rem] bg-white flex items-center justify-center shadow-xl mb-8 animate-pulse border border-slate-200">
                    <i class="fas fa-users-slash text-7xl text-slate-300"></i>
                </div>
                <h2 class="text-4xl font-black text-slate-800 uppercase tracking-widest text-center">Data Wasit Belum Tersedia</h2>
                <p class="text-xl font-bold text-slate-500 uppercase tracking-widest mt-4">Silakan atur penugasan wasit untuk sesi ini.</p>
            </div>
        {/if}
    </div>

    <!-- Footer -->
    <div class="bg-white border-t border-slate-200 px-12 py-6 flex items-center justify-between shadow-[0_-4px_6px_-1px_rgb(0,0,0,0.05)]">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                <i class="fas fa-balance-scale"></i>
            </div>
            <span class="text-xl font-black text-slate-800 tracking-widest uppercase">Smart Perkemi Arbitrase</span>
        </div>
        <div class="flex items-center gap-8 text-slate-400 font-bold uppercase tracking-widest text-sm">
            <span>{currentTime.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
            <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
            <span>{currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })} WIB</span>
        </div>
    </div>
</div>
