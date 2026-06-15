<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Timer - Perkemi Timing System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">
    <style>
        [x-cloak] { display: none !important; }
        /* Custom font block if needed */
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col items-center justify-center font-sans overflow-hidden select-none relative"
      x-data="{
          time: 0,
          running: false,
          interval: null,
          formatTime() {
              let m = Math.floor(this.time / 60000);
              let s = Math.floor((this.time % 60000) / 1000);
              let ms = Math.floor((this.time % 1000) / 10);
              return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}.${ms.toString().padStart(2, '0')}`;
          },
          start() {
              if (!this.running) {
                  this.running = true;
                  let startTime = Date.now() - this.time;
                  this.interval = setInterval(() => {
                      this.time = Date.now() - startTime;
                  }, 10);
              }
          },
          pause() {
              this.running = false;
              clearInterval(this.interval);
          },
          reset() {
              this.pause();
              this.time = 0;
          },
          toggle() {
              if(this.running) this.pause();
              else this.start();
          }
      }"
      @keydown.space.window="toggle()"
      @keydown.escape.window="reset()">

    {{-- Background Accents --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-600/20 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[100px]"></div>
    </div>

    {{-- Top Bar --}}
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
                    <span class="text-xs font-bold text-slate-300 mt-1"><kbd class="bg-slate-700 px-1.5 py-0.5 rounded text-white">Space</kbd> Start/Pause</span>
                </div>
            </div>
            <div class="bg-slate-800/80 backdrop-blur-md px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-3 shadow-lg">
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest leading-none">Shortcut</span>
                    <span class="text-xs font-bold text-slate-300 mt-1"><kbd class="bg-slate-700 px-1.5 py-0.5 rounded text-white">Esc</kbd> Reset</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Timer Display --}}
    <div class="relative z-10 flex flex-col items-center">
        <div class="font-mono text-[15vw] md:text-[12rem] lg:text-[18rem] font-black text-white leading-none tracking-tighter drop-shadow-[0_0_40px_rgba(16,185,129,0.3)] transition-colors duration-300"
             :class="{ 'text-emerald-400': running, 'text-amber-400': !running && time > 0, 'text-white': time === 0 }"
             x-text="formatTime()">
            00:00.00
        </div>
    </div>

    {{-- Controls --}}
    <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-10 flex items-center justify-center gap-6">
        <button x-show="!running" @click="start()" class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-emerald-500 hover:bg-emerald-400 text-white shadow-[0_0_30px_rgba(16,185,129,0.4)] flex items-center justify-center transition-all transform hover:scale-105 active:scale-95 border-4 border-emerald-400">
            <i class="fas fa-play text-3xl md:text-4xl ml-2"></i>
        </button>
        
        <button x-show="running" @click="pause()" class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-amber-500 hover:bg-amber-400 text-white shadow-[0_0_30px_rgba(245,158,11,0.4)] flex items-center justify-center transition-all transform hover:scale-105 active:scale-95 border-4 border-amber-400">
            <i class="fas fa-pause text-3xl md:text-4xl"></i>
        </button>

        <button @click="reset()" class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-slate-800 hover:bg-rose-500 text-slate-300 hover:text-white shadow-lg flex items-center justify-center transition-all transform hover:scale-105 active:scale-95 border-2 border-slate-600 hover:border-rose-400">
            <i class="fas fa-stop text-2xl md:text-3xl"></i>
        </button>
    </div>

</body>
</html>
