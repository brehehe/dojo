<div wire:poll
    class="min-h-screen bg-slate-900 text-white flex flex-col items-center justify-center font-sans overflow-hidden select-none relative">

    {{-- Background Accents --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-600/20 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[100px]">
        </div>
    </div>

    {{-- Top Bar with Match Context --}}
    <div class="absolute top-0 left-0 w-full p-6 md:p-8 flex justify-between items-start z-10">
        <div>
            <h1 class="text-xl md:text-2xl font-black uppercase tracking-widest text-slate-300">
                <i class="fas fa-stopwatch text-emerald-400 mr-2"></i> Monitor Waktu - {{ $court->name }}
            </h1>
            <div class="mt-2 flex flex-col gap-1">
                @if($court->active_match_id && $court->activeMatch)
                    <p class="text-sm md:text-lg font-black text-white uppercase tracking-widest">
                        {{ $court->activeMatch->name }}
                    </p>
                    <p class="text-xs md:text-sm font-bold text -emerald-400 uppercase tracking-widest">
                        {{ $court->activeMatch->draft_type }} &bull; {{ $court->activeMatch->ageGroup?->name }}
                    </p>
                @else
                    <p class="text-sm md:text-base font-bold text-slate-500 uppercase tracking-widest mt-1">IDLE - Menunggu
                        Panggilan</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Timer Component --}}
    <div wire:ignore class="relative z-10 w-full flex flex-col items-center justify-center flex-1" x-data="{
             time: 0,
             running: false,
             countdown: 0,
             async sync() {
                 let state = await $wire.getTimerState();
                 if (!state) return;
                 let wasRunning = this.running;
                 this.running = (state.status === 'running');

                 // Play buzzer when timer newly starts
                 if (this.running && !wasRunning && (!state.elapsed_ms || state.elapsed_ms < 1000)) {
                     try {
                         let audio = new Audio('/music/eritnhut1992-buzzer-or-wrong-answer-20582.mp3');
                         audio.play().catch(e => console.warn(e));
                     } catch(e) {}
                 }
                 
                 if (state.status === 'countdown') {
                     let now = Date.now();
                     let remaining = state.countdown_end_ms - now;
                     if (remaining > 0) {
                         this.countdown = Math.ceil(remaining / 1000);
                     } else {
                         this.countdown = 0;
                     }
                     this.time = state.elapsed_ms || 0;
                 } else if (state.status === 'running') {
                     this.countdown = 0;
                     let now = Date.now();
                     let serverElapsed = state.elapsed_ms || 0;
                     let runningDiff = now - state.started_at_ms;
                     
                     // Calculate expected time based on server start point
                     let expected = serverElapsed + (runningDiff > 0 ? runningDiff : 0);
                     
                     // If we are significantly out of sync, or just started, snap to it
                     if (!wasRunning || Math.abs(this.time - expected) > 1000) {
                         this.time = expected;
                     }
                 } else {
                     this.countdown = 0;
                     this.time = state.elapsed_ms || 0;
                 }
             },
             init() {
                 // Fetch absolute state from server every 1s
                 setInterval(() => {
                     this.sync();
                 }, 1000);
                 
                 // High-speed local interpolation for smooth UI
                 setInterval(() => {
                     if (this.running) {
                         this.time += 30; // 30ms interval
                     } else if (this.countdown > 0) {
                         // local countdown calculation based on system time (sync handles absolute)
                     }
                 }, 30);
             },
             formatTime() {
                 let t = Math.max(0, this.time);
                 let m = Math.floor(t / 60000);
                 let s = Math.floor((t % 60000) / 1000);
                 let ms = Math.floor((t % 1000) / 10);
                 return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}.${ms.toString().padStart(2, '0')}`;
             },
             formatCountdown() {
                 return '';
             }
         }">

        {{-- Display --}}
        <div class="font-mono text-[15vw] md:text-[12rem] lg:text-[18rem] font-black text-white leading-none tracking-tighter drop-shadow-[0_0_40px_rgba(16,185,129,0.3)] transition-colors duration-300"
            :class="{ 'text-emerald-400': running, 'text-amber-400': !running && time > 0 && countdown === 0, 'text-orange-500': countdown > 0, 'text-white': time === 0 && countdown === 0 }">
            <span x-show="countdown > 0" x-text="formatCountdown()"></span>
            <span x-show="countdown === 0" x-text="formatTime()">00:00.00</span>
        </div>
    </div>
</div>