<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piala Walikota Surabaya 2026 · SMART-PERKEMI</title>
    <meta name="description" content="Sistem Pendaftaran Resmi Kejuaraan Shorinji Kempo Piala Walikota Surabaya 2026">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .bebas { font-family: 'Bebas Neue', sans-serif; }
        .slide-enter { animation: slideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .hero-slide { transition: opacity 0.7s ease, transform 0.7s ease; }
        .counter-num { font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="font-['Inter'] antialiased overflow-x-hidden bg-[#060a10]">

    <!-- ===== NAVBAR ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-orange-500 flex items-center justify-center">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <span class="text-white font-black text-sm uppercase tracking-widest">Smart-Perkemi</span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-white/60 text-xs font-semibold uppercase tracking-widest">
                <a href="#about" class="hover:text-orange-400 transition">Tentang</a>
                <a href="#rundown" class="hover:text-orange-400 transition">Rundown</a>
                <a href="#info" class="hover:text-orange-400 transition">Info</a>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-[11px] font-black px-5 py-2.5 rounded-full uppercase tracking-wider transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-white/70 hover:text-white text-[11px] font-bold uppercase tracking-wider transition hidden sm:block">Login</a>
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-[11px] font-black px-5 py-2.5 rounded-full uppercase tracking-wider transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ===== HERO SLIDER ===== -->
    <section class="relative min-h-screen flex items-center overflow-hidden" id="heroSlider" x-data="{
        slide: 0,
        slides: [
            {
                title: 'PIALA WALIKOTA',
                subtitle: 'SURABAYA 2026',
                tag: 'Kejuaraan Resmi · Shorinji Kempo',
                desc: 'Event kejuaraan bergengsi tingkat nasional yang diselenggarakan di Kota Surabaya, memperebutkan Piala Bergilir Walikota.',
                color: 'from-orange-600/40 to-red-900/60',
                accent: 'bg-orange-500',
            },
            {
                title: 'EMBU & RANDORI',
                subtitle: 'KATEGORI LENGKAP',
                tag: 'Teknik Tingkat · Kyu & Dan',
                desc: 'Tersedia berbagai nomor pertandingan Embu (Berpasangan) dan Randori untuk semua kelompok usia dan tingkatan.',
                color: 'from-blue-600/40 to-indigo-900/60',
                accent: 'bg-blue-500',
            },
            {
                title: 'DAFTAR SEKARANG',
                subtitle: 'BATAS PENDAFTARAN',
                tag: 'Pendaftaran Online Resmi',
                desc: 'Daftarkan kontingenmu dan ikuti proses pendaftaran online yang mudah, cepat, dan terverifikasi secara resmi.',
                color: 'from-emerald-600/40 to-teal-900/60',
                accent: 'bg-emerald-500',
            }
        ],
        init() {
            setInterval(() => { this.slide = (this.slide + 1) % this.slides.length; }, 5000);
        }
    }" x-init="init()">

        <!-- BG overlays -->
        <div class="absolute inset-0 bg-gradient-to-b from-[#060a10] via-transparent to-[#060a10] z-10 pointer-events-none"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.025%22%3E%3Ccircle cx=%2230%22 cy=%2230%22 r=%221.5%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50 z-0"></div>

        <!-- Slide Color Layer -->
        <template x-for="(s, i) in slides" :key="i">
            <div class="absolute inset-0 transition-opacity duration-700 bg-gradient-to-br"
                 :class="[s.color, slide === i ? 'opacity-100' : 'opacity-0']"></div>
        </template>

        <!-- Orb decorations -->
        <div class="absolute top-1/4 -right-32 w-[600px] h-[600px] rounded-full blur-[120px] bg-orange-500/10 pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-[400px] h-[400px] rounded-full blur-[100px] bg-blue-500/10 pointer-events-none"></div>

        <!-- Main Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-6 pt-24 pb-20 w-full">
            <div class="max-w-3xl">
                <template x-for="(s, i) in slides" :key="'content-'+i">
                    <div x-show="slide === i" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-8">
                        <!-- Tag -->
                        <div class="mb-6 inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-2">
                            <div class="w-1.5 h-1.5 rounded-full animate-pulse" :class="s.accent"></div>
                            <span class="text-white/60 text-xs font-semibold uppercase tracking-widest" x-text="s.tag"></span>
                        </div>

                        <!-- Title -->
                        <h1 class="bebas text-7xl md:text-[9rem] leading-none text-white mb-2 tracking-wider" x-text="s.title"></h1>
                        <h2 class="bebas text-4xl md:text-6xl leading-none mb-8" :class="['text-orange-400', 'text-blue-400', 'text-emerald-400'][i]" x-text="s.subtitle"></h2>

                        <!-- Desc -->
                        <p class="text-white/50 text-lg leading-relaxed max-w-xl mb-10" x-text="s.desc"></p>

                        <!-- CTAs -->
                        <div class="flex flex-wrap gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-black px-8 py-4 rounded-2xl uppercase tracking-wide text-sm transition hover:-translate-y-1 flex items-center gap-3 shadow-2xl shadow-orange-500/20">
                                    <i class="fas fa-tachometer-alt"></i> Masuk Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-black px-8 py-4 rounded-2xl uppercase tracking-wide text-sm transition hover:-translate-y-1 flex items-center gap-3 shadow-2xl shadow-orange-500/20">
                                    <i class="fas fa-file-signature"></i> Daftar Kontingen
                                </a>
                                <a href="{{ route('login') }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold px-8 py-4 rounded-2xl uppercase tracking-wide text-sm transition hover:-translate-y-1 flex items-center gap-3">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            @endauth
                        </div>
                    </div>
                </template>
            </div>

            <!-- Slide indicators -->
            <div class="absolute bottom-10 left-6 flex gap-2 z-30">
                <template x-for="(s, i) in slides" :key="'dot-'+i">
                    <button @click="slide = i" class="h-1 rounded-full transition-all duration-500"
                            :class="slide === i ? 'w-10 bg-orange-400' : 'w-3 bg-white/20'"></button>
                </template>
            </div>
        </div>

        <!-- Stats bar -->
        <div class="absolute bottom-0 left-0 right-0 z-20 bg-white/5 backdrop-blur-xl border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6 py-5 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-0 md:divide-x md:divide-white/5">
                @foreach([['500+', 'Peserta Terdaftar'], ['30+', 'Nomor Pertandingan'], ['20+', 'Kota Kontingen'], ['2026', 'Edisi Kejuaraan']] as $stat)
                    <div class="md:px-8 text-center">
                        <div class="text-2xl font-black text-white counter-num">{{ $stat[0] }}</div>
                        <div class="text-white/40 text-[10px] font-semibold uppercase tracking-widest mt-1">{{ $stat[1] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== ABOUT SECTION ===== -->
    <section id="about" class="py-24 bg-[#060a10]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 rounded-full px-4 py-2 mb-6">
                        <i class="fas fa-trophy text-orange-400 text-xs"></i>
                        <span class="text-orange-400 text-xs font-bold uppercase tracking-widest">Tentang Kejuaraan</span>
                    </div>
                    <h2 class="bebas text-5xl md:text-6xl text-white mb-6 tracking-wide leading-tight">SHORINJI KEMPO<br><span class="text-orange-400">PIALA WALIKOTA</span></h2>
                    <p class="text-white/50 leading-relaxed mb-6">Kejuaraan Shorinji Kempo Piala Walikota Surabaya merupakan turnamen bergengsi yang mempertemukan kenshi terbaik dari seluruh penjuru nusantara untuk bersaing dan menunjukkan teknik terbaik mereka.</p>
                    <p class="text-white/50 leading-relaxed">Diselenggarakan dengan standar PERKEMI Nasional, kejuaraan ini menjadi ajang pengkaderan atlet berprestasi sekaligus wadah silaturahmi antar dojo seluruh Indonesia.</p>

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
    <section id="rundown" class="py-24 bg-[#08111a]">
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
    <section id="info" class="py-24 bg-[#060a10]">
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
    <footer class="bg-[#030609] border-t border-white/5 py-10 px-6 text-center">
        <div class="bebas text-2xl text-white/30 tracking-widest mb-2">SMART-PERKEMI</div>
        <p class="text-white/20 text-xs">© 2026 SMART-PERKEMI · Shorinji Kempo Indonesia · Piala Walikota Surabaya</p>
    </footer>

</body>
</html>
