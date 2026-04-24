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
            background: rgba(15, 2, 5, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(220, 38, 38, 0.2);
        }

        /* Glowing spotlight orb */
        @keyframes pulseCrimson {
            0%, 100% { opacity: 0.7; transform: scale(1) translate(0, 0); }
            50%      { opacity: 0.9; transform: scale(1.1) translate(2%, -2%); }
        }
        .orb-crimson { animation: pulseCrimson 8s ease-in-out infinite; }

        /* ========== HERO SLIDE ANIMATIONS ========== */
        @keyframes floatUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes sweepLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes riseScale {
            from { opacity: 0; transform: scale(0.9); }
            to   { opacity: 1; transform: scale(1); }
        }

        .hero-tag   { animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .hero-title { animation: sweepLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
        .hero-sub   { animation: riseScale 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }
        .hero-desc  { animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both; }
        .hero-cta   { animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both; }

        /* Scrolling ticker marquee */
        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .marquee-inner { animation: marquee 30s linear infinite; }
        @media (max-width: 640px) {
            .marquee-inner { animation-duration: 20s; }
        }

        /* Progress bar */
        .slide-progress {
            height: 100%;
            transition: width 0.1s linear;
        }

        /* Responsive adjustments */
        @media (max-height: 700px) {
            .hero-content { padding-top: 5rem !important; padding-bottom: 8rem !important; }
            .hero-title { font-size: clamp(2.5rem, 8vh, 5rem) !important; }
            .hero-sub { font-size: clamp(1.8rem, 6vh, 4rem) !important; }
        }
    </style>
</head>
<body class="font-['Inter'] antialiased overflow-x-hidden bg-pattern">

    <!-- ===== NAVBAR ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 navbar-glass" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">

            <!-- Logo + Brand -->
            <a href="/" class="flex items-center gap-2 sm:gap-3 group">
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl overflow-hidden ring-2 ring-red-600/30 shadow-lg shadow-red-950/50 group-hover:ring-red-500/50 transition-all duration-300">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-black text-[11px] sm:text-[13px] uppercase tracking-[0.2em] leading-none mb-0.5">Shorinji Kempo</span>
                    <span class="text-red-500 text-[13px] sm:text-[16px] font-bold uppercase tracking-[0.3em] leading-none">Indonesia</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden lg:flex items-center gap-10">
                <a href="#" class="nav-link text-[11px] font-bold uppercase tracking-[0.2em] text-white/70 hover:text-white transition-colors duration-300">Home</a>
                <a href="#about" class="nav-link text-[11px] font-bold uppercase tracking-[0.2em] text-white/70 hover:text-white transition-colors duration-300">Tentang Kami</a>
                <a href="#rundown" class="nav-link text-[11px] font-bold uppercase tracking-[0.2em] text-white/70 hover:text-white transition-colors duration-300">Rundown</a>
                <a href="{{ route('register') }}" class="nav-link text-[11px] font-bold uppercase tracking-[0.2em] text-white/70 hover:text-white transition-colors duration-300">Daftar</a>
                <a href="#info" class="nav-link text-[11px] font-bold uppercase tracking-[0.2em] text-white/70 hover:text-white transition-colors duration-300">Kontak</a>
            </div>

            <!-- Auth + Mobile Toggle -->
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="hidden sm:flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="bg-white/10 hover:bg-white text-white hover:text-black text-[11px] font-bold px-5 py-2.5 rounded-full uppercase tracking-widest transition-all duration-300 border border-white/20">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-white/70 hover:text-white text-[11px] font-bold uppercase tracking-widest transition-colors duration-300">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-orange-600 hover:bg-orange-500 text-white text-[11px] font-black px-6 py-2.5 rounded-full uppercase tracking-widest transition-all duration-300 shadow-lg shadow-orange-950/50">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden w-10 h-10 flex items-center justify-center text-white/80 hover:text-white transition-colors">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars-staggered'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden absolute top-full left-0 right-0 bg-black/95 backdrop-blur-xl border-b border-white/10 py-8 px-6 flex flex-col gap-6 items-center text-center"
             @click.away="mobileMenu = false">
            <a href="#" @click="mobileMenu = false" class="text-white/60 hover:text-orange-500 text-sm font-bold uppercase tracking-widest transition-colors">Home</a>
            <a href="#about" @click="mobileMenu = false" class="text-white/60 hover:text-orange-500 text-sm font-bold uppercase tracking-widest transition-colors">Tentang Kami</a>
            <a href="#rundown" @click="mobileMenu = false" class="text-white/60 hover:text-orange-500 text-sm font-bold uppercase tracking-widest transition-colors">Rundown</a>
            <a href="{{ route('register') }}" @click="mobileMenu = false" class="text-white/60 hover:text-orange-500 text-sm font-bold uppercase tracking-widest transition-colors">Daftar</a>
            <a href="#info" @click="mobileMenu = false" class="text-white/60 hover:text-orange-500 text-sm font-bold uppercase tracking-widest transition-colors">Kontak</a>
            <div class="w-full h-px bg-white/5 my-2"></div>
            @auth
                <a href="{{ route('dashboard') }}" class="w-full bg-orange-600 text-white py-4 rounded-xl font-black uppercase tracking-widest">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-white/60 font-bold uppercase tracking-widest">Login</a>
                <a href="{{ route('register') }}" class="w-full bg-orange-600 text-white py-4 rounded-xl font-black uppercase tracking-widest">Daftar Sekarang</a>
            @endauth
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
             x-transition:enter="transition-opacity ease-out duration-1000"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition-opacity ease-in duration-800"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="relative h-full w-full flex items-center justify-center">
                <!-- Background Image Layer -->
                <div class="absolute inset-0 z-0 overflow-hidden">
                    <img src="{{ asset('hero-fighters.png') }}" alt="" class="w-full h-full object-cover opacity-10 scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1a0008] via-transparent to-transparent"></div>
                </div>

                <!-- Content Container -->
                <div class="relative z-10 max-w-7xl mx-auto px-6 w-full flex flex-col items-center text-center hero-content">
                    <div class="hero-tag mb-6 inline-flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-full px-5 py-2">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                        </span>
                        <span class="text-white/80 text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em]">Kejuaraan Resmi · SMART-PERKEMI</span>
                    </div>
                    
                    <h1 class="hero-title bebas leading-[0.85] text-white tracking-tight"
                        style="font-size: clamp(3.5rem, 15vw, 10rem);">
                        SEMANGAT<br><span class="text-orange-600">KEMPO.</span>
                    </h1>
                    
                    <p class="hero-desc text-white/60 text-sm sm:text-base md:text-lg leading-relaxed mt-8 max-w-2xl mx-auto font-medium">
                        Tingkatkan Potensi Anda Melalui Shorinji Kempo Indonesia. Kejuaraan bergengsi tingkat nasional di Kota Surabaya 2026.
                    </p>
                    
                    <div class="hero-cta mt-10 flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                        @auth
                            <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center justify-center gap-3 font-black text-xs sm:text-sm uppercase tracking-widest text-white px-10 py-5 rounded-full transition-all duration-500 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 group-hover:scale-105 transition-transform duration-500"></div>
                                <span class="relative flex items-center gap-2">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center gap-3 font-black text-xs sm:text-sm uppercase tracking-widest text-white px-10 py-5 rounded-full transition-all duration-500 overflow-hidden shadow-2xl shadow-orange-950/50">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 group-hover:scale-105 transition-transform duration-500"></div>
                                <span class="relative">Daftar Kontingen</span>
                            </a>
                            <a href="{{ route('login') }}" class="group inline-flex items-center justify-center gap-3 font-bold text-xs sm:text-sm text-white/70 hover:text-white uppercase tracking-widest px-10 py-5 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-300 backdrop-blur-sm">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SLIDE 1 ===== -->
        <div class="absolute inset-0 z-20"
             x-show="slide === 1"
             x-transition:enter="transition-opacity ease-out duration-1000"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition-opacity ease-in duration-800"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display:none;">
            
            <div class="relative h-full w-full flex items-center justify-center">
                <!-- Background Image Layer -->
                <div class="absolute inset-0 z-0 overflow-hidden">
                    <img src="{{ asset('hero-fighters.png') }}" alt="" class="w-full h-full object-cover opacity-10 scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#00083d] via-transparent to-transparent"></div>
                </div>

                <!-- Content Container -->
                <div class="relative z-10 max-w-7xl mx-auto px-6 w-full flex flex-col items-center text-center hero-content">
                    <div class="hero-tag mb-6 inline-flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-full px-5 py-2">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        <span class="text-white/80 text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em]">Teknik & Kategori · Kyu & Dan</span>
                    </div>
                    
                    <h1 class="hero-title bebas leading-[0.85] text-white tracking-tight"
                        style="font-size: clamp(3.5rem, 15vw, 10rem);">
                        EMBU &<br><span class="text-blue-500">RANDORI.</span>
                    </h1>
                    
                    <p class="hero-desc text-white/60 text-sm sm:text-base md:text-lg leading-relaxed mt-8 max-w-2xl mx-auto font-medium">
                        Tersedia nomor pertandingan Embu Berpasangan dan Randori untuk semua kelompok usia dan tingkatan sabuk.
                    </p>
                    
                    <div class="hero-cta mt-10 flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                        @auth
                            <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center justify-center gap-3 font-black text-xs sm:text-sm uppercase tracking-widest text-white px-10 py-5 rounded-full transition-all duration-500 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-blue-600 group-hover:scale-105 transition-transform duration-500"></div>
                                <span class="relative flex items-center gap-2">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center gap-3 font-black text-xs sm:text-sm uppercase tracking-widest text-white px-10 py-5 rounded-full transition-all duration-500 overflow-hidden shadow-2xl shadow-blue-950/50">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-blue-600 group-hover:scale-105 transition-transform duration-500"></div>
                                <span class="relative">Pendaftaran Online</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SLIDE 2 ===== -->
        <div class="absolute inset-0 z-20"
             x-show="slide === 2"
             x-transition:enter="transition-opacity ease-out duration-1000"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition-opacity ease-in duration-800"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display:none;">
            
            <div class="relative h-full w-full flex items-center justify-center">
                <!-- Content Container -->
                <div class="relative z-10 max-w-7xl mx-auto px-6 w-full flex flex-col items-center text-center hero-content">
                    <div class="hero-tag mb-6 inline-flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-full px-5 py-2">
                        <span class="text-orange-400 text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em]">Pendaftaran Dibuka Sekarang</span>
                    </div>
                    
                    <h1 class="hero-title bebas leading-[0.85] text-white tracking-tight"
                        style="font-size: clamp(4rem, 18vw, 12rem);">
                        DAFTAR<br><span style="background: linear-gradient(90deg, #ff4d00, #0070ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">SEKARANG!</span>
                    </h1>
                    
                    <p class="hero-desc text-white/60 text-sm sm:text-base md:text-lg leading-relaxed mt-8 max-w-xl mx-auto font-medium">
                        Segera daftarkan kontingenmu dan jadilah saksi sejarah kejuaraan Shorinji Kempo terbesar tahun ini.
                    </p>
                    
                    <div class="hero-cta mt-10 flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center gap-3 font-black text-xs sm:text-sm uppercase tracking-widest text-white px-12 py-6 rounded-full transition-all duration-500 overflow-hidden shadow-[0_0_50px_-12px_rgba(234,88,12,0.5)]">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 via-orange-500 to-blue-600 group-hover:scale-105 transition-transform duration-500"></div>
                            <span class="relative">MULAI PENDAFTARAN</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== BOTTOM UI BAR ===== -->
        <div class="absolute bottom-0 left-0 right-0 z-30">

            <!-- Ticker / Marquee -->
            <div class="overflow-hidden py-3 border-t border-white/10 bg-black/40 backdrop-blur-md">
                <div class="marquee-inner flex whitespace-nowrap items-center">
                    @php
                    $items = [
                        '🥋 PIALA WALIKOTA SURABAYA 2026',
                        '⚡ PENDAFTARAN ONLINE DIBUKA',
                        '🏆 30+ NOMOR PERTANDINGAN',
                        '📍 GOR SURABAYA · JAWA TIMUR',
                        '🎯 EMBU & RANDORI KYU DAN DAN',
                        '💥 500+ PESERTA TERDAFTAR',
                        '🌟 SHORINJI KEMPO INDONESIA',
                        '📣 TECHNICAL MEETING WAJIB HADIR',
                    ];
                    @endphp
                    @foreach(array_merge($items, $items) as $item)
                        <div class="flex items-center gap-4 px-6">
                            <span class="text-white/60 text-[9px] sm:text-[11px] font-bold uppercase tracking-[0.2em]">{{ $item }}</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-600/50"></span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Stats + Controls -->
            <div class="bg-black/60 backdrop-blur-2xl border-t border-white/5">
                <div class="max-w-7xl mx-auto px-4 sm:px-8 py-5 flex items-center justify-between gap-8">
                    <!-- Stats -->
                    <div class="hidden lg:flex items-center gap-12">
                        @foreach([['500+', 'Peserta'], ['30+', 'Nomor'], ['20+', 'Kota'], ['2026', 'Edisi']] as $s)
                        <div class="flex flex-col">
                            <span class="text-white font-black text-xl leading-none mb-1">{{ $s[0] }}</span>
                            <span class="text-white/30 text-[10px] font-bold uppercase tracking-widest">{{ $s[1] }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Slide Navigation -->
                    <div class="flex items-center gap-6 mx-auto lg:mx-0">
                        <!-- Arrows -->
                        <div class="flex items-center gap-2">
                            <button @click="prev()" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-white hover:bg-white/10 transition-all duration-300 group">
                                <i class="fas fa-chevron-left text-xs group-active:scale-90 transition-transform"></i>
                            </button>
                            <button @click="next()" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-white hover:bg-white/10 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs group-active:scale-90 transition-transform"></i>
                            </button>
                        </div>

                        <!-- Dots + Progress -->
                        <div class="flex items-center gap-4">
                            <div class="flex gap-2.5">
                                <template x-for="i in total" :key="i">
                                    <button @click="goTo(i-1)" 
                                            class="h-1.5 rounded-full transition-all duration-500 relative overflow-hidden"
                                            :class="slide === i-1 ? 'w-10 bg-white/20' : 'w-2 bg-white/10'">
                                        <div x-show="slide === i-1" 
                                             class="absolute inset-0 bg-gradient-to-r from-orange-500 to-blue-500 transition-all duration-100"
                                             :style="'width:' + progress + '%'"></div>
                                    </button>
                                </template>
                            </div>
                            <div class="text-white/20 text-[11px] font-mono tracking-tighter">
                                <span class="text-white/60" x-text="'0' + (slide + 1)"></span> / <span x-text="'0' + total"></span>
                            </div>
                        </div>
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
                                <div class="text-white/40 text-[15px] uppercase tracking-widest font-bold">{{ $item[1] }}</div>
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
                                <div class="text-white/40 text-[15px] font-bold uppercase tracking-wider">✓ {{ $badge[1] }}</div>
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
                                        <span class="bg-orange-500/20 text-orange-400 text-[15px] font-black uppercase tracking-widest px-3 py-1 rounded-full">{{ $day }}</span>
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
