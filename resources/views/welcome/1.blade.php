<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piala Walikota Surabaya 2026 · SMART-PERKEMI</title>
    <meta name="description" content="Sistem Pendaftaran Resmi Kejuaraan Shorinji Kempo Piala Walikota Surabaya 2026">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .bebas { font-family: 'Bebas Neue', sans-serif; }
        .counter-num { font-variant-numeric: tabular-nums; }

        /* ====== RED + BLUE CINEMATIC THEME ====== */
        body { background: #1a0008; }

        /* Red + Blue diagonal grid pattern */
        .bg-pattern {
            background-color: #1a0008;
            background-image:
                linear-gradient(135deg, rgba(220,10,10,0.14) 25%, transparent 25%),
                linear-gradient(225deg, rgba(10,20,220,0.14) 25%, transparent 25%),
                linear-gradient(45deg,  rgba(220,10,10,0.14) 25%, transparent 25%),
                linear-gradient(315deg, rgba(10,20,220,0.14) 25%, transparent 25%);
            background-size: 40px 40px;
        }

        /* Navbar nav-link underline effect */
        .nav-link {
            position: relative;
            padding-bottom: 2px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #f97316;
            border-radius: 2px;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after { width: 100%; }

        /* Navbar glass — tinted red, semi-transparent */
        .navbar-glass {
            background: rgba(40,3,8,0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(220,20,20,0.30);
        }

        /* Glowing spotlight orb */
        @keyframes pulseCrimson {
            0%,100% { opacity: 0.85; transform: scale(1); }
            50%      { opacity: 1; transform: scale(1.05); }
        }
        .orb-crimson { animation: pulseCrimson 5s ease-in-out infinite; }

        /* ========== HERO SLIDE ANIMATIONS ========== */

        /* Tag badge floats up */
        @keyframes floatUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        /* Title sweeps in from left */
        @keyframes sweepLeft {
            from { opacity: 0; transform: translateX(-60px) skewX(-4deg); }
            to   { opacity: 1; transform: translateX(0) skewX(0); }
        }
        /* Subtitle rises with scale */
        @keyframes riseScale {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        /* Desc fades in */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        /* CTA pops up */
        @keyframes popUp {
            from { opacity: 0; transform: translateY(24px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        /* Hero image slides in from right */
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(80px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        /* Floating image gentle bob */
        @keyframes floatBob {
            0%,100% { transform: translateY(0px) rotate(0deg); }
            50%      { transform: translateY(-12px) rotate(1deg); }
        }

        /* Classes applied on active slide */
        .hero-tag   { animation: floatUp   0.6s cubic-bezier(0.34,1.56,0.64,1) both; }
        .hero-title { animation: sweepLeft 0.7s cubic-bezier(0.22,1,0.36,1) 0.10s both; }
        .hero-sub   { animation: riseScale 0.6s cubic-bezier(0.22,1,0.36,1) 0.22s both; }
        .hero-desc  { animation: fadeIn   0.6s ease                          0.35s both; }
        .hero-cta   { animation: popUp    0.5s cubic-bezier(0.34,1.56,0.64,1) 0.48s both; }
        .hero-img   { animation: slideInRight 0.75s cubic-bezier(0.22,1,0.36,1) 0.10s both; }
        .hero-img-float { animation: floatBob 6s ease-in-out infinite; }

        /* Scrolling ticker marquee */
        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .marquee-inner { animation: marquee 18s linear infinite; }
        .marquee-inner:hover { animation-play-state: paused; }

        /* Progress bar */
        .slide-progress {
            height: 3px;
            border-radius: 2px;
            transition: width 0.1s linear;
        }
    </style>
</head>
<body class="font-['Inter'] antialiased overflow-x-hidden bg-pattern">

    <!-- ===== NAVBAR ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 navbar-glass">
        <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between">

            <!-- Logo + Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden ring-2 ring-red-800/50 shadow-lg shadow-red-900/30">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="text-white font-black text-xs uppercase tracking-widest leading-tight">Shorinji Kempo</div>
                    <div class="text-red-400/70 text-[10px] font-semibold uppercase tracking-[0.2em] leading-tight">Indonesia</div>
                </div>
            </div>

            <!-- Nav Links -->
            <div class="hidden md:flex items-center gap-8 text-white/65 text-xs font-semibold uppercase tracking-widest">
                <a href="#" class="nav-link active text-white hover:text-white transition">Home</a>
                <a href="#about" class="nav-link hover:text-white transition">Tentang Kami</a>
                <a href="#rundown" class="nav-link hover:text-white transition">Rundown</a>
                <a href="{{ route('register') }}" class="nav-link hover:text-white transition">Daftar</a>
                <a href="#info" class="nav-link hover:text-white transition">Kontak</a>
            </div>

            <!-- Auth -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="bg-orange-600 hover:bg-orange-500 text-white text-[11px] font-black px-5 py-2.5 rounded-lg uppercase tracking-wider transition shadow-lg shadow-orange-900/30">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-white/60 hover:text-white text-[11px] font-bold uppercase tracking-wider transition hidden sm:block">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-orange-600 hover:bg-orange-500 text-white text-[11px] font-black px-5 py-2.5 rounded-lg uppercase tracking-wider transition shadow-lg shadow-orange-900/30">
                        Daftar Sekarang
                    </a>
                @endauth
            </div>

        </div>
    </nav>

    <!-- ===== HERO SLIDER (Split: Text Left + Image Right + Animated) ===== -->
    <section class="relative min-h-screen flex flex-col overflow-hidden" id="heroSlider"
        x-data="{
            slide: 0,
            total: 3,
            progress: 0,
            _timer: null,
            startTimer() {
                clearInterval(this._timer);
                this.progress = 0;
                let tick = 0;
                this._timer = setInterval(() => {
                    tick++;
                    this.progress = (tick / 55) * 100;
                    if (tick >= 55) {
                        this.slide = (this.slide + 1) % this.total;
                        tick = 0; this.progress = 0;
                    }
                }, 100);
            },
            goTo(i) { this.slide = i; this.startTimer(); },
            prev() { this.slide = (this.slide - 1 + this.total) % this.total; this.startTimer(); },
            next() { this.slide = (this.slide + 1) % this.total; this.startTimer(); },
            init() { this.startTimer(); }
        }" x-init="init()">

        <!-- == STATIC BACKGROUNDS == -->
        <!-- Base gradient: red left → blue right -->
        <div class="absolute inset-0 z-0"
             style="background: linear-gradient(to right, #5a0010 0%, #3d000f 25%, #1e0020 50%, #00083d 75%, #000d5a 100%);"></div>

        <!-- LEFT RED orb -->
        <div class="orb-crimson absolute inset-0 pointer-events-none"
             style="background: radial-gradient(ellipse at 10% 50%, rgba(255,20,20,0.90) 0%, rgba(220,0,0,0.60) 20%, rgba(150,0,10,0.25) 40%, transparent 60%);"></div>

        <!-- RIGHT BLUE orb -->
        <div class="orb-crimson absolute inset-0 pointer-events-none"
             style="background: radial-gradient(ellipse at 90% 50%, rgba(30,80,255,0.90) 0%, rgba(10,40,230,0.60) 20%, rgba(0,15,160,0.25) 40%, transparent 60%); animation-delay: 2.5s;"></div>

        <!-- Top arena ceiling glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1100px] h-[280px] pointer-events-none"
             style="background: radial-gradient(ellipse at 50% 0%, rgba(255,80,80,0.22) 0%, rgba(80,80,255,0.18) 55%, transparent 80%); filter: blur(18px);"></div>

        <!-- Floor glow left red + right blue -->
        <div class="absolute bottom-0 left-0 w-1/2 h-52 pointer-events-none"
             style="background: linear-gradient(to top, rgba(255,20,20,0.55), transparent); filter: blur(18px);"></div>
        <div class="absolute bottom-0 right-0 w-1/2 h-52 pointer-events-none"
             style="background: linear-gradient(to top, rgba(20,50,255,0.55), transparent); filter: blur(18px);"></div>

        <!-- Navbar area top fade -->
        <div class="absolute top-0 left-0 right-0 h-28 z-10 pointer-events-none"
             style="background: linear-gradient(to bottom, rgba(0,0,0,0.55), transparent);"></div>

        <!-- ===== SLIDE 0 ===== -->
        <div class="absolute inset-0 z-20"
             x-show="slide === 0"
             x-transition:enter="transition-opacity ease-out duration-700"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="max-w-7xl mx-auto px-6 pt-20 h-full flex flex-col lg:flex-row items-center gap-6">

                <!-- Mobile: faded background image (hidden on lg) -->
                <div class="lg:hidden absolute inset-0 pointer-events-none" style="z-index:-1;">
                    <img src="{{ asset('hero-fighters.png') }}" alt="" class="w-full h-full object-cover object-center opacity-10">
                    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(80,0,16,0.3) 0%, rgba(80,0,16,0.85) 100%);"></div>
                </div>

                <!-- LEFT: Text (full width on mobile, half on desktop) -->
                <div class="w-full lg:w-1/2 flex flex-col justify-center pb-28 lg:pb-24">
                    <div class="hero-tag mb-4 lg:mb-5 inline-flex items-center gap-2 bg-white/5 border border-orange-500/30 rounded-full px-4 py-1.5 self-start">
                        <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                        <span class="text-orange-300 text-xs font-bold uppercase tracking-widest">Kejuaraan Resmi · Shorinji Kempo</span>
                    </div>
                    <h1 class="hero-title bebas leading-[0.9] text-white mb-2 tracking-wide drop-shadow-2xl"
                        style="font-size: clamp(3rem, 12vw, 6.5rem);">SEMANGAT<br>KEMPO.</h1>
                    <h2 class="hero-sub bebas leading-[0.9] mb-4 lg:mb-6 tracking-wide"
                        style="font-size: clamp(2rem, 9vw, 4.5rem); color: #ff6030;">KEKUATAN &amp;<br>HARMONI.</h2>
                    <p class="hero-desc text-white/70 text-sm lg:text-base leading-relaxed mb-6 lg:mb-8 max-w-md">
                        Tingkatkan Potensi Anda Melalui Shorinji Kempo Indonesia. Kejuaraan bergengsi tingkat nasional di Kota Surabaya.
                    </p>
                    <div class="hero-cta flex flex-wrap gap-3 lg:gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 font-black text-sm uppercase tracking-wider text-white px-7 py-3.5 rounded-full transition hover:-translate-y-1" style="background:linear-gradient(90deg,#ea580c,#f97316);box-shadow:0 8px 32px -6px rgba(234,88,12,0.60);">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 font-black text-sm uppercase tracking-wider text-white px-7 py-3.5 rounded-full transition hover:-translate-y-1" style="background:linear-gradient(90deg,#ea580c,#f97316);box-shadow:0 8px 32px -6px rgba(234,88,12,0.60);">
                                Bergabung Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 font-bold text-sm text-white/80 hover:text-white uppercase tracking-wider px-5 py-3.5 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 transition">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- RIGHT: Hero Image — desktop only -->
                <div class="hidden lg:flex lg:w-1/2 items-center justify-center pb-24 relative">
                    <div class="absolute inset-0 rounded-3xl pointer-events-none"
                         style="background: radial-gradient(ellipse at 50% 55%, rgba(255,60,30,0.35) 0%, rgba(30,60,255,0.25) 50%, transparent 75%); filter: blur(35px);"></div>
                    <img src="{{ asset('hero-fighters.png') }}" alt="Kempo Fighters"
                         class="hero-img hero-img-float relative w-full max-w-xl object-contain"
                         style="filter: drop-shadow(0 0 50px rgba(255,40,40,0.50)) drop-shadow(0 0 40px rgba(40,80,255,0.40));">
                </div>
            </div>
        </div>

        <!-- ===== SLIDE 1 ===== -->
        <div class="absolute inset-0 z-20"
             x-show="slide === 1"
             x-transition:enter="transition-opacity ease-out duration-700"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display:none;">
            <div class="max-w-7xl mx-auto px-6 pt-20 h-full flex flex-col lg:flex-row-reverse items-center gap-6">

                <!-- Mobile: faded background image -->
                <div class="lg:hidden absolute inset-0 pointer-events-none" style="z-index:-1;">
                    <img src="{{ asset('hero-fighters.png') }}" alt="" class="w-full h-full object-cover object-center opacity-10">
                    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(0,8,60,0.3) 0%, rgba(0,8,60,0.85) 100%);"></div>
                </div>

                <div class="w-full lg:w-1/2 flex flex-col justify-center pb-28 lg:pb-24">
                    <div class="hero-tag mb-4 lg:mb-5 inline-flex items-center gap-2 bg-white/5 border border-blue-500/30 rounded-full px-4 py-1.5 self-start">
                        <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
                        <span class="text-blue-300 text-xs font-bold uppercase tracking-widest">Teknik Tingkat · Kyu &amp; Dan</span>
                    </div>
                    <h1 class="hero-title bebas leading-[0.9] text-white mb-2 tracking-wide drop-shadow-2xl"
                        style="font-size: clamp(3rem, 12vw, 6.5rem);">EMBU &amp;<br>RANDORI.</h1>
                    <h2 class="hero-sub bebas leading-[0.9] mb-4 lg:mb-6 text-blue-400 tracking-wide"
                        style="font-size: clamp(2rem, 9vw, 3.5rem);">KATEGORI<br>LENGKAP.</h2>
                    <p class="hero-desc text-white/70 text-sm lg:text-base leading-relaxed mb-6 lg:mb-8 max-w-md">
                        Tersedia nomor pertandingan Embu Berpasangan dan Randori untuk semua kelompok usia dan tingkatan.
                    </p>
                    <div class="hero-cta flex flex-wrap gap-3 lg:gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 font-black text-sm uppercase tracking-wider text-white px-7 py-3.5 rounded-full transition hover:-translate-y-1" style="background:linear-gradient(90deg,#2563eb,#3b82f6);box-shadow:0 8px 32px -6px rgba(37,99,235,0.60);">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 font-black text-sm uppercase tracking-wider text-white px-7 py-3.5 rounded-full transition hover:-translate-y-1" style="background:linear-gradient(90deg,#2563eb,#3b82f6);box-shadow:0 8px 32px -6px rgba(37,99,235,0.60);">
                                Daftar Kontingen
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 font-bold text-sm text-white/80 hover:text-white uppercase tracking-wider px-5 py-3.5 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 transition">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Image — desktop only -->
                <div class="hidden lg:flex lg:w-1/2 items-center justify-center pb-24 relative">
                    <div class="absolute inset-0 rounded-3xl pointer-events-none"
                         style="background: radial-gradient(ellipse at 50% 55%, rgba(30,60,255,0.40) 0%, rgba(255,60,30,0.20) 60%, transparent 80%); filter: blur(35px);"></div>
                    <img src="{{ asset('hero-fighters.png') }}" alt="Kempo Fighters"
                         class="hero-img hero-img-float relative w-full max-w-xl object-contain"
                         style="filter: drop-shadow(0 0 50px rgba(40,80,255,0.55)) drop-shadow(0 0 30px rgba(255,40,40,0.30));">
                </div>
            </div>
        </div>

        <!-- ===== SLIDE 2 ===== -->
        <div class="absolute inset-0 z-20"
             x-show="slide === 2"
             x-transition:enter="transition-opacity ease-out duration-700"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display:none;">
            <div class="max-w-7xl mx-auto px-6 pt-20 h-full flex flex-col items-center justify-center text-center gap-4 pb-24">
                <div class="hero-tag mb-4 inline-flex items-center gap-2 bg-white/5 border border-orange-500/30 rounded-full px-5 py-2">
                    <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                    <span class="text-orange-300 text-xs font-bold uppercase tracking-widest">Pendaftaran Online Resmi</span>
                </div>
                <h1 class="hero-title bebas text-white tracking-wide leading-none drop-shadow-2xl"
                    style="font-size: clamp(4rem, 9vw, 8rem);">DAFTAR</h1>
                <h2 class="hero-sub bebas tracking-wide leading-none mb-4"
                    style="font-size: clamp(3rem, 7vw, 6.5rem); background: linear-gradient(90deg, #ff4010, #3060ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    SEKARANG!
                </h2>
                <p class="hero-desc text-white/70 text-base leading-relaxed max-w-xl mb-8">
                    Daftarkan kontingenmu dan ikuti proses pendaftaran online yang mudah, cepat, dan terverifikasi secara resmi.
                </p>
                <div class="hero-cta flex flex-wrap justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 font-black text-sm uppercase tracking-wider text-white px-10 py-4 rounded-full transition hover:-translate-y-1" style="background:linear-gradient(90deg,#dc2626,#ea580c,#2563eb);box-shadow:0 8px 40px -6px rgba(200,50,20,0.65);">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 font-black text-sm uppercase tracking-wider text-white px-10 py-4 rounded-full transition hover:-translate-y-1" style="background:linear-gradient(90deg,#dc2626,#ea580c,#2563eb);box-shadow:0 8px 40px -6px rgba(200,50,20,0.65);">
                            <i class="fas fa-file-signature"></i> Daftar Akun Kontingen
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 font-bold text-sm text-white/80 hover:text-white uppercase tracking-wider px-6 py-4 rounded-full border border-white/15 bg-white/5 hover:bg-white/10 transition">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- ===== BOTTOM UI BAR ===== -->
        <div class="absolute bottom-0 left-0 right-0 z-30">

            <!-- Ticker / Marquee -->
            <div class="overflow-hidden py-2.5 border-t border-b border-white/10" style="background: rgba(0,0,0,0.45); backdrop-filter: blur(12px);">
                <div class="marquee-inner flex whitespace-nowrap gap-0">
                    @php
                    $items = [
                        '🥋 Piala Walikota Surabaya 2026',
                        '⚡ Pendaftaran Online Dibuka',
                        '🏆 30+ Nomor Pertandingan',
                        '📍 GOR Surabaya · Jawa Timur',
                        '🎯 Embu & Randori Kyu dan Dan',
                        '💥 500+ Peserta Terdaftar',
                        '🌟 Shorinji Kempo Indonesia',
                        '📣 Technical Meeting Wajib Hadir',
                    ];
                    $doubled = array_merge($items, $items);
                    @endphp
                    @foreach($doubled as $item)
                        <span class="text-white/70 text-xs font-semibold uppercase tracking-widest px-8">{{ $item }}</span>
                        <span class="text-orange-500/60 text-xs">◆</span>
                    @endforeach
                </div>
            </div>

            <!-- Progress + Dots + Stats -->
            <div class="" style="background: rgba(0,0,0,0.50); backdrop-filter: blur(16px);">
                <!-- Progress bar -->
                <div class="h-0.5 bg-white/10">
                    <div class="slide-progress bg-gradient-to-r from-orange-500 to-blue-500" :style="'width:' + progress + '%'"></div>
                </div>

                <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
                    <!-- Stats -->
                    <div class="hidden md:flex items-center gap-8 divide-x divide-white/10">
                        @foreach([['500+', 'Peserta'], ['30+', 'Nomor'], ['20+', 'Kota'], ['2026', 'Edisi']] as $s)
                        <div class="pl-8 first:pl-0 text-center">
                            <div class="text-white font-black text-lg counter-num">{{ $s[0] }}</div>
                            <div class="text-white/35 text-[9px] font-bold uppercase tracking-widest">{{ $s[1] }}</div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Slide dots + prev/next arrows -->
                    <div class="flex items-center gap-4 mx-auto md:mx-0">
                        <!-- Prev -->
                        <button @click="prev()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-white/70 hover:text-white">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>

                        <!-- Dots -->
                        <div class="flex gap-2">
                            <template x-for="i in total" :key="i">
                                <button @click="goTo(i-1)"
                                        class="rounded-full transition-all duration-500"
                                        :class="slide === i-1 ? 'w-8 h-2 bg-gradient-to-r from-orange-400 to-blue-400' : 'w-2 h-2 bg-white/25 hover:bg-white/50'">
                                </button>
                            </template>
                        </div>

                        <!-- Next -->
                        <button @click="next()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition text-white/70 hover:text-white">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>

                        <!-- Slide counter -->
                        <span class="text-white/30 text-xs font-mono"><span x-text="slide+1"></span> / <span x-text="total"></span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ABOUT SECTION ===== -->
    <section id="about" class="py-24" style="background: linear-gradient(160deg, #3d0010 0%, #1a0020 50%, #000838 100%);"><span style="display:none"><!-- about --></span>
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 rounded-full px-4 py-2 mb-6">
                        <i class="fas fa-trophy text-orange-400 text-xs"></i>
                        <span class="text-orange-400 text-xs font-bold uppercase tracking-widest">Tentang Kejuaraan</span>
                    </div>
                    <h2 class="bebas text-5xl md:text-6xl text-white mb-6 tracking-wide leading-tight">SHORINJI KEMPO<br><span class="text-orange-400">PIALA WALIKOTA</span></h2>
                    <p class="text-white/50 leading-relaxed mb-6">Kejuaraan Shorinji Kempo Piala Walikota Surabaya merupakan turnamen bergengsi yang mempertemukan kenshi terbaik dari seluruh Jawa Timur untuk bersaing dan menunjukkan teknik terbaik mereka.</p>
                    <p class="text-white/50 leading-relaxed">Diselenggarakan dengan standar PERKEMI Nasional, kejuaraan ini menjadi ajang pengkaderan atlet berprestasi sekaligus wadah silaturahmi antar dojo Kota / Kabupaten Jawa Timur.</p>

                    <div class="grid grid-cols-2 gap-4 mt-10">
                        @foreach([['fa-map-marker-alt', 'Lokasi', 'GOR Surabaya, Jawa Timur'], ['fa-calendar', 'Jadwal', 'Mei 2026'], ['fa-users', 'Peserta', 'Terbuka Umum (Kyu & Dan)'], ['fa-award', 'Hadiah', 'Piala + Medali + Sertifikat']] as $item)
                            <div class="bg-white/5 border border-white/5 rounded-2xl p-4">
                                <div class="w-8 h-8 rounded-lg bg-orange-500/20 flex items-center justify-center mb-3">
                                    <i class="fas {{ $item[0] }} text-orange-400 text-xs"></i>
                                </div>
                                <div class="text-white/40 text-[10px] uppercase tracking-widest font-bold">{{ $item[1] }}</div>
                                <div class="text-white text-sm font-bold mt-1">{{ $item[2] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 to-transparent rounded-[40px] blur-2xl transform scale-110"></div>
                    <div class="relative bg-gradient-to-br from-white/5 to-white/0 border border-white/10 rounded-[40px] p-10 text-center">
                        <div class="w-28 h-28 bg-orange-500 rounded-[30px] flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-orange-500/30 rotate-3">
                            <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover rounded-[30px]">
                        </div>
                        <div class="bebas text-6xl text-white mb-2 tracking-wider">SMART</div>
                        <div class="bebas text-3xl text-orange-400 tracking-widest mb-6">PERKEMI</div>
                        <div class="text-white/30 text-xs uppercase tracking-[0.3em] font-semibold">少林寺拳法 · Shorinji Kempo Indonesia</div>
                        <div class="mt-8 pt-8 border-t border-white/5 grid grid-cols-3 gap-4">
                            @foreach([['', 'Resmi PERKEMI'], ['', 'Sistem Terpadu'], ['', 'Online']] as $badge)
                                <div class="text-white/40 text-[9px] font-bold uppercase tracking-wider">✓ {{ $badge[1] }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== RUNDOWN SECTION ===== -->
    <section id="rundown" class="py-24" style="background: linear-gradient(160deg, #000838 0%, #1a0020 50%, #3d0010 100%);"><span style="display:none"><!-- rundown --></span>
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 rounded-full px-4 py-2 mb-6">
                    <i class="fas fa-calendar-alt text-orange-400 text-xs"></i>
                    <span class="text-orange-400 text-xs font-bold uppercase tracking-widest">Jadwal Kegiatan</span>
                </div>
                <h2 class="bebas text-5xl md:text-6xl text-white tracking-wide">RUNDOWN<br><span class="text-orange-400">KEJUARAAN</span></h2>
            </div>

            <div class="relative">
                <!-- Center line -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-orange-500/50 via-orange-500/20 to-transparent"></div>

                <div class="space-y-8">
                    @foreach([
                        ['D-1', 'Technical Meeting', 'Pemaparan teknik, nomor pertandingan, dan pengesahan nama atlet. Wajib dihadiri pelatih/manajer kontingen.', 'fa-users', 'orange'],
                        ['H-1', 'Registrasi Ulang & Timbang Badan', 'Verifikasi kelengkapan dokumen atlet, timbang badan, dan pengambilan nomor undian.', 'fa-weight', 'blue'],
                        ['Hari 1', 'Pertandingan Embu & Randori – Sesi 1', 'Babak penyisihan Embu Perorangan, Embu Berpasangan, dan Randori putri/putra.', 'fa-sword', 'purple'],
                        ['Hari 2', 'Pertandingan Semifinal & Final', 'Babak semifinal dan final semua kategori dilanjutkan dengan upacara penghargaan.', 'fa-trophy', 'orange'],
                    ] as $idx => [$day, $title, $desc, $icon, $color])
                        <div class="flex flex-col md:flex-row items-center gap-6 {{ $idx % 2 === 0 ? '' : 'md:flex-row-reverse' }}">
                            <div class="md:w-1/2 {{ $idx % 2 === 0 ? 'md:text-right md:pr-16' : 'md:text-left md:pl-16' }}">
                                <div class="bg-white/5 border border-white/10 rounded-3xl p-6 hover:border-orange-500/30 transition">
                                    <div class="inline-flex items-center gap-2 mb-3">
                                        <span class="bg-orange-500/20 text-orange-400 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">{{ $day }}</span>
                                    </div>
                                    <h3 class="text-white font-black text-lg mb-2">{{ $title }}</h3>
                                    <p class="text-white/40 text-sm leading-relaxed">{{ $desc }}</p>
                                </div>
                            </div>
                            <div class="md:w-0 flex-shrink-0 relative">
                                <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center shadow-lg shadow-orange-500/30 relative z-10">
                                    <i class="fas {{ $icon }} text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="md:w-1/2"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ===== INFO / CTA ===== -->
    <section id="info" class="py-24" style="background: linear-gradient(160deg, #3d0010 0%, #200030 40%, #000838 100%);"><span style="display:none"><!-- info --></span>
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="bebas text-6xl md:text-8xl text-white mb-6 tracking-wide">DAFTAR<br><span class="text-orange-400">SEKARANG</span></h2>
            <p class="text-white/40 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
                Segera daftarkan kontingen Anda sebelum kuota habis. Pendaftaran online resmi telah dibuka.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-black px-10 py-5 rounded-2xl uppercase tracking-wide text-sm transition hover:-translate-y-1 flex items-center gap-3 shadow-2xl shadow-orange-500/30">
                        <i class="fas fa-tachometer-alt"></i> Masuk Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-black px-10 py-5 rounded-2xl uppercase tracking-wide text-sm transition hover:-translate-y-1 flex items-center gap-3 shadow-2xl shadow-orange-500/30">
                        <i class="fas fa-file-signature"></i> Daftar Akun Kontingen
                    </a>
                    <a href="{{ route('login') }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold px-10 py-5 rounded-2xl uppercase tracking-wide text-sm transition hover:-translate-y-1 flex items-center gap-3">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="border-t py-10 px-6 text-center" style="background: #1a0008; border-color: rgba(220,20,20,0.25);">
        <div class="bebas text-2xl text-white/30 tracking-widest mb-2">SMART-PERKEMI</div>
        <p class="text-white/20 text-xs">© 2026 SMART-PERKEMI · Shorinji Kempo Indonesia · Piala Walikota Surabaya</p>
    </footer>

</body>
</html>
