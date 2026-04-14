<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piala Walikota Surabaya 2026 · SMART-PERKEMI</title>
    <meta name="description" content="Kejuaraan Resmi Shorinji Kempo Piala Walikota Surabaya 2026">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'DM Sans', sans-serif; }
        .bebas { font-family: 'Bebas Neue', sans-serif; }
        
        /* Glassmorphism Effect */
        .glass { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); }
        .glass-light { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.5); }

        /* Animated orbs */
        @keyframes float { 0%,100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.05); } }
        @keyframes pulse-slow { 0%,100% { opacity: 0.15; } 50% { opacity: 0.3; } }
        .orb { animation: float 8s ease-in-out infinite; }
        .orb-2 { animation: float 12s ease-in-out infinite reverse; }
        .orb-3 { animation: float 10s ease-in-out infinite 2s; }
        .pulse-orb { animation: pulse-slow 4s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased overflow-x-hidden" style="background: linear-gradient(135deg, #0a0e1a 0%, #0f172a 50%, #0a0e1a 100%);">

    <!-- ===== ANIMATED BACKGROUND ORBS ===== -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="orb absolute top-1/4 -left-32 w-[500px] h-[500px] rounded-full pulse-orb" style="background: radial-gradient(circle, rgba(251,146,60,0.4) 0%, transparent 70%);"></div>
        <div class="orb-2 absolute top-1/3 right-0 w-[600px] h-[600px] rounded-full pulse-orb" style="background: radial-gradient(circle, rgba(139,92,246,0.25) 0%, transparent 70%);"></div>
        <div class="orb-3 absolute bottom-0 left-1/3 w-[400px] h-[400px] rounded-full pulse-orb" style="background: radial-gradient(circle, rgba(59,130,246,0.2) 0%, transparent 70%);"></div>
    </div>

    <!-- ===== NAVBAR ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3 glass rounded-2xl px-4 py-2.5">
                <div class="w-7 h-7 rounded-lg overflow-hidden">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-white font-bold text-sm">SMART-PERKEMI</span>
            </div>
            <div class="hidden md:flex items-center gap-4">
                <div class="glass rounded-2xl px-6 py-2.5 flex items-center gap-6 text-white/50 text-xs font-semibold">
                    <a href="#hero" class="hover:text-white transition">Beranda</a>
                    <a href="#info" class="hover:text-white transition">Info</a>
                    <a href="#rundown" class="hover:text-white transition">Rundown</a>
                    <a href="#daftar" class="hover:text-white transition">Daftar</a>
                </div>
            </div>
            <div class="flex gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-orange-500/20 hover:bg-orange-500/30 border border-orange-500/30 text-orange-300 text-xs font-bold px-5 py-2.5 rounded-xl uppercase tracking-wide transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="glass text-white/60 hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-400 text-white text-xs font-bold px-5 py-2.5 rounded-xl uppercase tracking-wide transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section id="hero" class="relative min-h-screen flex items-center justify-center z-10">
        <div class="max-w-5xl mx-auto px-6 pt-32 pb-20 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 glass rounded-full px-5 py-2.5 mb-10">
                <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                <span class="text-white/60 text-xs font-semibold uppercase tracking-widest">Kejuaraan Resmi · Shorinji Kempo Indonesia</span>
            </div>

            <!-- Main Title -->
            <h1 class="bebas text-[80px] md:text-[140px] leading-none text-white mb-6 tracking-wider">
                PIALA <span style="-webkit-text-stroke: 2px rgba(251,146,60,0.6); color: transparent;">WALIKOTA</span>
            </h1>
            <div class="bebas text-4xl md:text-6xl text-orange-400 mb-8 tracking-widest">SURABAYA 2026</div>

            <!-- description -->
            <p class="text-white/40 text-lg leading-relaxed max-w-2xl mx-auto mb-14">
                Platform pendaftaran digital resmi Kejuaraan Shorinji Kempo — cepat, aman, dan terverifikasi. Daftarkan kontingen Anda sekarang.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                @auth
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 bg-orange-500 hover:bg-orange-400 text-white font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition shadow-2xl shadow-orange-500/20 hover:-translate-y-1">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                        <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group flex items-center gap-3 bg-orange-500 hover:bg-orange-400 text-white font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition shadow-2xl shadow-orange-500/20 hover:-translate-y-1">
                        <i class="fas fa-file-signature"></i> Daftar Kontingen
                        <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition"></i>
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center gap-3 glass hover:bg-white/10 text-white font-semibold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endauth
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto">
                @foreach([
                    ['500+', 'Peserta Terdaftar', 'fa-users'],
                    ['30+', 'Nomor Pertandingan', 'fa-list'],
                    ['20+', 'Kota Asal Kontingen', 'fa-map-marker-alt'],
                    ['2026', 'Edisi Kejuaraan', 'fa-award'],
                ] as [$num, $label, $icon])
                    <div class="glass rounded-2xl p-5 text-center hover:bg-white/10 transition">
                        <i class="fas {{ $icon }} text-orange-400/50 text-xs mb-3 block"></i>
                        <div class="text-2xl font-black text-white mb-1">{{ $num }}</div>
                        <div class="text-white/30 text-[10px] font-semibold uppercase tracking-widest leading-tight">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/20 animate-bounce">
            <div class="text-[10px] uppercase tracking-widest font-semibold">Scroll</div>
            <i class="fas fa-chevron-down text-xs"></i>
        </div>
    </section>

    <!-- ===== INFO CARDS ===== -->
    <section id="info" class="relative z-10 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="bebas text-5xl md:text-6xl text-white tracking-wide mb-4">INFORMASI <span class="text-orange-400">KEJUARAAN</span></h2>
                <p class="text-white/30 text-sm max-w-xl mx-auto">Semua yang perlu Anda ketahui tentang kejuaraan ini</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['fa-map-marker-alt', 'Lokasi', 'GOR Pertamina Surabaya, Jawa Timur', 'Gelanggang berstandar nasional dengan fasilitas lengkap.'],
                    ['fa-calendar-alt', 'Jadwal', 'Mei 2026', 'Technical meeting D-1, pertandingan berlangsung selama 2 hari penuh.'],
                    ['fa-users', 'Peserta', 'Terbuka Umum', 'Untuk kenshi Kyu dan Dan dari seluruh dojo di Indonesia.'],
                    ['fa-list', 'Kategori', '30+ Nomor', 'Embu Perorangan, Berpasangan, dan Randori di berbagai kelas.'],
                    ['fa-award', 'Hadiah', 'Piala + Medali', 'Piala bergilir, medali, sertifikat, dan hadiah uang pembinaan.'],
                    ['fa-certificate', 'Penyelenggara', 'PERKEMI Resmi', 'Diselenggarakan di bawah naungan PERKEMI Jawa Timur.'],
                ] as [$icon, $title, $main, $desc])
                    <div class="glass rounded-3xl p-6 hover:bg-white/10 transition group">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/20 border border-orange-500/20 flex items-center justify-center shrink-0 group-hover:bg-orange-500/30 transition">
                                <i class="fas {{ $icon }} text-orange-400 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-white/40 text-[10px] font-bold uppercase tracking-widest mb-1">{{ $title }}</div>
                                <div class="text-white font-bold text-base mb-2">{{ $main }}</div>
                                <p class="text-white/30 text-xs leading-relaxed">{{ $desc }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== RUNDOWN ===== -->
    <section id="rundown" class="relative z-10 py-24">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="bebas text-5xl md:text-6xl text-white tracking-wide mb-4">RUNDOWN <span class="text-orange-400">RESMI</span></h2>
            </div>

            <div class="space-y-4">
                @foreach([
                    ['1', 'D-7', 'Penutupan Pendaftaran', 'Batas akhir pendaftaran kontingen online. Tidak ada perpanjangan.', '00:00 WIB'],
                    ['2', 'D-1 Pagi', 'Technical Meeting', 'Rapat teknis untuk pelatih & manajer kontingen. Wajib hadir.', '09:00 WIB'],
                    ['3', 'D-1 Sore', 'Registrasi & Timbang', 'Verifikasi dokumen dan timbang badan untuk kategori Randori.', '14:00 WIB'],
                    ['4', 'Hari 1', 'Pembukaan & Sesi 1', 'Upacara pembukaan resmi dan pertandingan babak penyisihan.', '07:00 WIB'],
                    ['5', 'Hari 2', 'Final & Penutupan', 'Babak final semua kategori, upacara penghargaan, penutupan resmi.', '07:00 WIB'],
                ] as [$num, $day, $title, $desc, $time])
                    <div class="glass hover:bg-white/10 rounded-2xl p-5 flex items-center gap-6 transition group">
                        <div class="w-10 h-10 bg-orange-500/20 border border-orange-500/30 rounded-xl flex items-center justify-center shrink-0 text-orange-400 font-black text-sm group-hover:bg-orange-500 group-hover:text-white transition">
                            {{ $num }}
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center gap-2">
                                <span class="text-orange-400 text-[10px] font-black uppercase tracking-widest w-24 shrink-0">{{ $day }}</span>
                                <h3 class="text-white font-bold text-sm">{{ $title }}</h3>
                            </div>
                            <p class="text-white/30 text-xs mt-1">{{ $desc }}</p>
                        </div>
                        <div class="text-white/20 text-xs font-semibold shrink-0 hidden md:block">{{ $time }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section id="daftar" class="relative z-10 py-24">
        <div class="max-w-4xl mx-auto px-6">
            <div class="glass rounded-[40px] p-12 md:p-16 text-center" style="background: linear-gradient(135deg, rgba(251,146,60,0.15), rgba(251,146,60,0.05));">
                <div class="w-16 h-16 rounded-[20px] bg-orange-500/20 flex items-center justify-center mx-auto mb-8 overflow-hidden border border-orange-500/20">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <h2 class="bebas text-6xl md:text-8xl text-white tracking-wide mb-6">BERGABUNG<br><span class="text-orange-400">SEKARANG</span></h2>
                <p class="text-white/40 text-lg mb-10 max-w-xl mx-auto leading-relaxed">Daftarkan kontingen Anda dan jadilah bagian dari Kejuaraan Kempo bergengsi di Kota Surabaya.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-400 text-white font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-3 shadow-2xl shadow-orange-500/20">
                            <i class="fas fa-tachometer-alt"></i> Masuk Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-400 text-white font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-3 shadow-2xl shadow-orange-500/20">
                            <i class="fas fa-file-signature"></i> Daftar Kontingen
                        </a>
                        <a href="{{ route('login') }}" class="glass hover:bg-white/10 text-white font-semibold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-3">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="relative z-10 py-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg overflow-hidden border border-white/10">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-white/30 font-bold text-xs uppercase tracking-widest">SMART-PERKEMI</span>
            </div>
            <p class="text-white/20 text-xs">© 2026 SMART-PERKEMI · Shorinji Kempo Indonesia</p>
            <div class="text-white/20 text-xs font-semibold">少林寺拳法</div>
        </div>
    </footer>

</body>
</html>
