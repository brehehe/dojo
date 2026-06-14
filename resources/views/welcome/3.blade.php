<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piala Walikota Surabaya 2026 · SMART-PERKEMI</title>
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Space Grotesk', sans-serif; }
        .bebas { font-family: 'Bebas Neue', sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #f97316, #fb923c, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .split-card { clip-path: polygon(0 0, 100% 0, 100% 85%, 95% 100%, 0 100%); }
    </style>
</head>
<body class="antialiased overflow-x-hidden bg-slate-900">

    <!-- ===== NAVBAR ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between bg-white/5 backdrop-blur-2xl border border-white/10 rounded-2xl px-6 py-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl overflow-hidden border border-white/10">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-white font-bold text-sm">SMART-PERKEMI</span>
            </div>
            <div class="hidden md:flex items-center gap-6 text-white/50 text-xs font-semibold">
                <a href="#about" class="hover:text-white transition">Tentang</a>
                <a href="#countdown" class="hover:text-white transition">Hitung Mundur</a>
                <a href="#kategoris" class="hover:text-white transition">Kategori</a>
                <a href="#rundown" class="hover:text-white transition">Rundown</a>
            </div>
            <div class="flex gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-orange-500 text-white text-xs font-bold px-4 py-2 rounded-xl uppercase tracking-wide">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-white/60 hover:text-white text-xs font-bold px-4 py-2 transition hidden sm:block">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl uppercase tracking-wide transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ===== SPLIT HERO ===== -->
    <section class="min-h-screen flex flex-col md:flex-row relative overflow-hidden">
        <!-- LEFT: Dark Info -->
        <div class="relative z-10 flex-1 bg-slate-900 flex flex-col justify-center px-10 md:px-20 pt-32 pb-16">
            <div class="w-20 h-1 bg-orange-500 mb-8 rounded-full"></div>
            <h1 class="bebas text-[80px] md:text-[110px] leading-none text-white mb-4 tracking-wider">
                PIALA<br>WALIKOTA
            </h1>
            <div class="bebas text-5xl text-orange-400 mb-8 tracking-widest">SURABAYA 2026</div>

            <p class="text-slate-400 text-base leading-relaxed max-w-sm mb-10">
                Kejuaraan Shorinji Kempo tingkat nasional yang mempertemukan kenshi-kenshi berprestasi dari seluruh Indonesia di Kota Pahlawan.
            </p>

            <div class="flex flex-col gap-3 max-w-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-4 rounded-2xl text-sm uppercase tracking-wide transition flex items-center justify-center gap-3">
                        <i class="fas fa-tachometer-alt"></i> Masuk Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-4 rounded-2xl text-sm uppercase tracking-wide transition flex items-center gap-3 shadow-2xl shadow-orange-500/20">
                        <i class="fas fa-file-signature"></i> Daftar Kontingen Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold px-8 py-4 rounded-2xl text-sm uppercase tracking-wide transition flex items-center gap-3">
                        <i class="fas fa-sign-in-alt"></i> Login Akun Kontingen
                    </a>
                @endauth
            </div>
        </div>

        <!-- RIGHT: Colored Panel -->
        <div class="relative flex-1 bg-gradient-to-br from-orange-600 to-red-700 flex flex-col justify-center px-10 md:px-16 py-16 md:pt-32">
            <!-- Pattern overlay -->
            <div class="absolute inset-0 opacity-10"
                 style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 60px); background-size: 60px 60px;"></div>

            <div class="relative z-10">
                <div class="inline-block bg-white/20 border border-white/30 text-white text-[15px] font-black uppercase tracking-[0.3em] px-4 py-2 rounded-full mb-8">
                    Pendaftaran Resmi Dibuka ✓
                </div>

                <!-- Countdown -->
                <div class="mb-10" id="countdown" x-data="{
                    days: 0, hours: 0, mins: 0, secs: 0,
                    target: new Date('2026-05-01T00:00:00'),
                    init() {
                        setInterval(() => {
                            const now = new Date();
                            const diff = this.target - now;
                            if (diff > 0) {
                                this.days = Math.floor(diff / (1000*60*60*24));
                                this.hours = Math.floor((diff % (1000*60*60*24)) / (1000*60*60));
                                this.mins = Math.floor((diff % (1000*60*60)) / (1000*60));
                                this.secs = Math.floor((diff % (1000*60)) / 1000);
                            }
                        }, 1000);
                    }
                }" x-init="init()">
                    <div class="text-white/80 text-xs font-bold uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                        <i class="fas fa-clock"></i> Hitung Mundur Kejuaraan
                    </div>
                    <div class="grid grid-cols-4 gap-3">
                        <template x-for="[val, lbl] in [[days,'Hari'],[hours,'Jam'],[mins,'Menit'],[secs,'Detik']]">
                            <div class="bg-white/20 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                                <div class="text-3xl font-black text-white" x-text="String(val).padStart(2,'0')">00</div>
                                <div class="text-white/60 text-[15px] font-bold uppercase tracking-wider mt-1" x-text="lbl"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Quick info -->
                <div class="space-y-3">
                    @foreach([
                        ['fa-map-marker-alt', 'Lokasi', 'GOR Surabaya, Jawa Timur'],
                        ['fa-calendar-alt', 'Jadwal', 'Mei 2026'],
                        ['fa-users', 'Peserta', '500+ Kenshi dari seluruh Indonesia'],
                        ['fa-list', 'Nomor', '30+ Nomor Pertandingan'],
                    ] as [$icon, $label, $val])
                        <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl px-4 py-3">
                            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas {{ $icon }} text-white text-xs"></i>
                            </div>
                            <div>
                                <div class="text-white/50 text-[15px] font-bold uppercase tracking-widest">{{ $label }}</div>
                                <div class="text-white font-semibold text-sm">{{ $val }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ===== KATEGORIS ===== -->
    <section id="kategoris" class="py-24 bg-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="text-orange-400 text-xs font-black uppercase tracking-[0.3em] mb-4">Nomor Pertandingan</div>
                <h2 class="bebas text-5xl md:text-6xl text-white tracking-wide">KATEGORI <span class="text-orange-400">KEJUARAAN</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['fa-user', 'Embu Perorangan', 'Kata tunggal dengan penilaian presisi teknik, ekspresi, dan penghayatan jiwa Shorinji Kempo.', ['Kyu Pemula', 'Kyu Muda', 'Kyu Madya', 'Dan']],
                    ['fa-user-friends', 'Embu Berpasangan', 'Kata berpasangan dengan teknik bersama. Dinilai keselarasan dan kerjasama antara dua kenshi.', ['Pasangan Putra', 'Pasangan Putri', 'Pasangan Campuran']],
                    ['fa-fist-raised', 'Randori', 'Pertandingan sparing terkontrol antar kenshi. Serangan dan pertahanan dengan teknik terukur.', ['Kelas Berat Ringan', 'Kelas Berat Sedang', 'Kelas Berat Besar']],
                ] as [$icon, $name, $desc, $classes])
                    <div class="group bg-slate-900/50 border border-white/5 hover:border-orange-500/30 rounded-3xl p-8 transition duration-300 hover:bg-slate-900">
                        <div class="w-14 h-14 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center mb-6 group-hover:bg-orange-500 group-hover:border-orange-500 transition">
                            <i class="fas {{ $icon }} text-orange-400 group-hover:text-white transition"></i>
                        </div>
                        <h3 class="text-white font-bold text-xl mb-3">{{ $name }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">{{ $desc }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($classes as $class)
                                <span class="bg-white/5 text-white/40 text-[15px] px-3 py-1 rounded-full font-semibold">{{ $class }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== RUNDOWN ===== -->
    <section id="rundown" class="py-24 bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="text-orange-400 text-xs font-black uppercase tracking-[0.3em] mb-4">Jadwal Resmi</div>
                <h2 class="bebas text-5xl md:text-6xl text-white tracking-wide">RUNDOWN <span class="text-orange-400">KEGIATAN</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach([
                    ['fa-file-alt', 'D-7', 'Tutup Pendaftaran', 'Batas akhir pendaftaran kontingen secara online.', 'orange'],
                    ['fa-users', 'D-1', 'Technical Meeting', 'Rapat teknis peraturan kejuaraan untuk pelatih & manajer.', 'blue'],
                    ['fa-weight', 'D-1 Sore', 'Timbang Badan', 'Registrasi ulang + timbang badan untuk Randori.', 'purple'],
                    ['fa-trophy', 'D-Day', 'Hari Pertandingan', 'Upacara pembukaan, pertandingan, hingga penutupan.', 'green'],
                ] as [$icon, $day, $title, $desc, $c])
                    <div class="relative bg-slate-800/50 border border-white/5 rounded-3xl p-6 hover:border-orange-500/20 transition">
                        <div class="absolute -top-4 left-6 inline-flex items-center gap-2 bg-orange-500 text-white text-[15px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                            {{ $day }}
                        </div>
                        <div class="pt-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center mb-4">
                                <i class="fas {{ $icon }} text-orange-400 text-sm"></i>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2">{{ $title }}</h3>
                            <p class="text-slate-400 text-xs leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="py-24 bg-gradient-to-br from-orange-600 to-red-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 60px);"></div>
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="bebas text-6xl md:text-8xl text-white tracking-wider mb-6">SIAP BERTANDING?</h2>
            <p class="text-orange-100 text-lg mb-10 max-w-xl mx-auto">Daftarkan kontingen sekarang dan tunjukkan kemampuan terbaik para kenshi Anda di Kota Surabaya!</p>
            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-orange-600 font-black px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:bg-orange-50 flex items-center gap-3">
                        <i class="fas fa-tachometer-alt"></i> Dashboard Kontingen
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-orange-600 hover:bg-orange-50 font-black px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition flex items-center gap-3 shadow-2xl">
                        <i class="fas fa-file-signature"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="bg-orange-700/50 hover:bg-orange-700 border border-white/20 text-white font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition flex items-center gap-3">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-slate-950 border-t border-white/5 py-10 text-center">
        <div class="bebas text-2xl text-white/30 tracking-widest mb-2">SMART-PERKEMI</div>
        <p class="text-slate-600 text-xs">© 2026 SMART-PERKEMI · Shorinji Kempo Indonesia</p>
    </footer>

</body>
</html>
