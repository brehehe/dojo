<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piala Walikota Surabaya 2026 · SMART-PERKEMI</title>
    <meta name="description" content="Undangan Resmi Kejuaraan Shorinji Kempo Piala Walikota Surabaya 2026">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .serif { font-family: 'Cormorant Garamond', Georgia, serif; }
        body { font-family: 'Inter', sans-serif; }
        
        /* Elegant Invitation Lines */
        .inv-line { background: linear-gradient(90deg, transparent, #b45309, transparent); height: 1px; }
        .gold-gradient { background: linear-gradient(135deg, #92400e, #b45309, #d97706, #f59e0b, #d97706, #b45309, #92400e); }
        .pattern-bg {
            background-color: #fafaf9;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(180,83,9,0.03) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(180,83,9,0.03) 0%, transparent 50%);
        }
        
        /* Elegant hover */
        .elegant-hover { transition: all 0.4s cubic-bezier(0.4,0,0.2,1); }
        .elegant-hover:hover { transform: translateY(-4px); }

        /* Gold separator dots */
        .gold-sep::before, .gold-sep::after { content: '◆'; color: #b45309; margin: 0 12px; opacity: 0.5; font-size: 8px; }
    </style>
</head>
<body class="antialiased overflow-x-hidden pattern-bg text-stone-700">

    <!-- ===== MINIMAL NAV ===== -->
    <nav class="sticky top-0 z-50 bg-stone-50/90 backdrop-blur-xl border-b border-stone-200">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg overflow-hidden border border-stone-200">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="text-[15px] font-semibold text-stone-400 uppercase tracking-[0.25em]">Smart-Perkemi</div>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-6 text-stone-500 text-xs font-semibold uppercase tracking-widest">
                <a href="#undangan" class="hover:text-amber-700 transition">Undangan</a>
                <a href="#jadwal" class="hover:text-amber-700 transition">Jadwal</a>
                <a href="#pendaftaran" class="hover:text-amber-700 transition">Pendaftaran</a>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="border border-amber-700 text-amber-800 hover:bg-amber-700 hover:text-white text-xs font-semibold px-5 py-2 rounded-lg uppercase tracking-widest transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-stone-500 hover:text-amber-700 text-xs font-semibold uppercase tracking-widest transition">Masuk</a>
                    <a href="{{ route('register') }}" class="border border-amber-700 text-amber-800 hover:bg-amber-700 hover:text-white text-xs font-semibold px-5 py-2 rounded-lg uppercase tracking-widest transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ===== ELEGANT HERO (INVITATION STYLE) ===== -->
    <section id="undangan" class="py-20 md:py-32 relative">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <!-- Ornamental top -->
            <div class="flex items-center justify-center gap-4 mb-10">
                <div class="h-px flex-1 max-w-16 bg-gradient-to-r from-transparent to-amber-700/40"></div>
                <div class="text-amber-700/60 text-[15px] font-semibold uppercase tracking-[0.4em]">Undangan Resmi</div>
                <div class="h-px flex-1 max-w-16 bg-gradient-to-l from-transparent to-amber-700/40"></div>
            </div>

            <!-- Crest area -->
            <div class="flex justify-center mb-10">
                <div class="relative">
                    <div class="absolute -inset-4 rounded-full bg-amber-100 opacity-60 blur-xl"></div>
                    <div class="relative w-24 h-24 rounded-full border-2 border-amber-200 overflow-hidden shadow-lg shadow-amber-200/50">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Invitation Text -->
            <p class="serif italic text-stone-400 text-xl mb-4">Dengan segala hormat,</p>
            <p class="serif text-stone-500 text-base mb-8 leading-relaxed max-w-lg mx-auto">
                Bersama ini kami mengundang kontingen Shorinji Kempo Anda untuk turut berpartisipasi dalam
            </p>

            <!-- Decorative line -->
            <div class="inv-line mb-8 mx-auto max-w-xs"></div>

            <!-- Championship Name -->
            <h1 class="serif text-5xl md:text-7xl font-medium text-stone-800 leading-tight mb-4">
                Kejuaraan <em class="text-amber-800">Shorinji Kempo</em>
            </h1>
            <div class="serif text-3xl md:text-4xl font-light text-stone-600 mb-2">Piala Walikota Surabaya</div>
            <div class="serif italic text-amber-700 text-2xl mb-8">Tahun 2026</div>

            <!-- Decorative line -->
            <div class="inv-line mb-8 mx-auto max-w-xs"></div>

            <!-- Details Row -->
            <div class="flex flex-wrap justify-center gap-8 text-stone-500 mb-12">
                <div class="text-center">
                    <div class="text-amber-700 font-semibold text-sm uppercase tracking-wider mb-1">Tempat</div>
                    <div class="serif text-stone-600 text-base">GOR Surabaya</div>
                    <div class="text-stone-400 text-xs">Jawa Timur</div>
                </div>
                <div class="w-px bg-stone-200 hidden md:block"></div>
                <div class="text-center">
                    <div class="text-amber-700 font-semibold text-sm uppercase tracking-wider mb-1">Waktu</div>
                    <div class="serif text-stone-600 text-base">Mei 2026</div>
                    <div class="text-stone-400 text-xs">2 Hari Penuh</div>
                </div>
                <div class="w-px bg-stone-200 hidden md:block"></div>
                <div class="text-center">
                    <div class="text-amber-700 font-semibold text-sm uppercase tracking-wider mb-1">Penyelenggara</div>
                    <div class="serif text-stone-600 text-base">PERKEMI Jatim</div>
                    <div class="text-stone-400 text-xs">Resmi & Terverifikasi</div>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="elegant-hover bg-amber-800 hover:bg-amber-700 text-white font-semibold px-10 py-4 rounded-xl text-sm uppercase tracking-widest flex items-center gap-3 shadow-lg shadow-amber-900/20">
                        <i class="fas fa-tachometer-alt text-xs"></i> Masuk Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="elegant-hover bg-amber-800 hover:bg-amber-700 text-white font-semibold px-10 py-4 rounded-xl text-sm uppercase tracking-widest flex items-center gap-3 shadow-lg shadow-amber-900/20">
                        <i class="fas fa-file-signature text-xs"></i> Daftar Kontingen
                    </a>
                    <a href="{{ route('login') }}" class="elegant-hover border border-amber-700/30 text-amber-800 hover:bg-amber-50 font-semibold px-10 py-4 rounded-xl text-sm uppercase tracking-widest flex items-center gap-3 transition">
                        <i class="fas fa-sign-in-alt text-xs"></i> Masuk Akun
                    </a>
                @endauth
            </div>

            <!-- Bottom ornament -->
            <div class="flex items-center justify-center gap-4 mt-16">
                <div class="h-px flex-1 max-w-20 bg-gradient-to-r from-transparent to-amber-700/30"></div>
                <div class="text-amber-700/40 text-xs font-semibold tracking-[0.3em]">少林寺拳法</div>
                <div class="h-px flex-1 max-w-20 bg-gradient-to-l from-transparent to-amber-700/30"></div>
            </div>
        </div>
    </section>

    <!-- ===== JADWAL ===== -->
    <section id="jadwal" class="py-20 bg-stone-100 border-t border-stone-200">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-14">
                <div class="text-amber-700 text-xs font-semibold uppercase tracking-[0.3em] mb-3">Agenda Resmi</div>
                <h2 class="serif text-4xl md:text-5xl text-stone-800">Jadwal Kegiatan</h2>
                <div class="inv-line mt-6 mx-auto max-w-xs"></div>
            </div>

            <div class="space-y-4">
                @foreach([
                    ['Penutupan Pendaftaran', 'D-7 sebelum kejuaraan', 'Batas akhir pendaftaran kontingen online.', 'fa-times-circle'],
                    ['Technical Meeting', 'D-1 · 09.00 WIB', 'Rapat teknis bagi pelatih dan manajer. Wajib dihadiri.', 'fa-users'],
                    ['Registrasi & Timbang', 'D-1 · 13.00 WIB', 'Verifikasi dokumen dan timbang badan untuk kategori Randori.', 'fa-weight'],
                    ['Pembukaan & Pertandingan', 'Hari 1 · 07.00 WIB', 'Upacara pembukaan resmi, dilanjutkan pertandingan sesi 1.', 'fa-flag'],
                    ['Final & Penutupan', 'Hari 2 · 07.00 WIB', 'Babak final semua kategori dan upacara penghargaan resmi.', 'fa-trophy'],
                ] as [$title, $time, $desc, $icon])
                    <div class="bg-white border border-stone-200 hover:border-amber-200 rounded-2xl p-6 flex items-start gap-5 elegant-hover shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                            <i class="fas {{ $icon }} text-amber-700 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-1">
                                <h3 class="serif text-stone-800 font-medium text-lg">{{ $title }}</h3>
                                <span class="text-amber-700 text-xs font-semibold uppercase tracking-wider shrink-0">{{ $time }}</span>
                            </div>
                            <p class="text-stone-400 text-sm">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== PENDAFTARAN ===== -->
    <section id="pendaftaran" class="py-24">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-4 mb-8">
                <div class="h-px flex-1 max-w-20 bg-gradient-to-r from-transparent to-amber-700/30"></div>
                <div class="text-amber-700 text-xs font-semibold uppercase tracking-[0.3em]">Pendaftaran Online</div>
                <div class="h-px flex-1 max-w-20 bg-gradient-to-l from-transparent to-amber-700/30"></div>
            </div>

            <h2 class="serif text-4xl md:text-5xl text-stone-800 mb-6">Daftarkan Kontingen Anda</h2>
            <p class="text-stone-500 text-base leading-relaxed mb-10 max-w-lg mx-auto">
                Proses pendaftaran mudah dan terverifikasi otomatis. Semua dokumen diunggah secara digital melalui portal SMART-PERKEMI.
            </p>

            <!-- Steps -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 text-left">
                @foreach([
                    ['1', 'Buat Akun', 'Daftarkan akun kontingen Anda dengan email dan password.'],
                    ['2', 'Isi Formulir', 'Lengkapi data atlet, official, dan unggah dokumen persyaratan.'],
                    ['3', 'Konfirmasi', 'Bayar biaya pendaftaran dan tunggu konfirmasi dari panitia.'],
                ] as [$step, $title, $desc])
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6">
                        <div class="serif text-5xl font-light text-amber-200 leading-none mb-4">{{ $step }}</div>
                        <h3 class="text-stone-700 font-semibold text-base mb-2">{{ $title }}</h3>
                        <p class="text-stone-400 text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="elegant-hover bg-amber-800 hover:bg-amber-700 text-white font-semibold px-10 py-4 rounded-xl text-sm uppercase tracking-widest flex items-center gap-3 shadow-lg shadow-amber-900/20">
                        <i class="fas fa-tachometer-alt text-xs"></i> Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="elegant-hover bg-amber-800 hover:bg-amber-700 text-white font-semibold px-10 py-4 rounded-xl text-sm uppercase tracking-widest flex items-center gap-3 shadow-lg shadow-amber-900/20">
                        <i class="fas fa-user-plus text-xs"></i> Buat Akun Gratis
                    </a>
                    <a href="{{ route('login') }}" class="elegant-hover border border-stone-300 text-stone-600 hover:bg-stone-100 font-semibold px-10 py-4 rounded-xl text-sm uppercase tracking-widest flex items-center gap-3 transition">
                        <i class="fas fa-sign-in-alt text-xs"></i> Sudah Punya Akun
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-stone-800 py-12 px-6 border-t border-stone-700">
        <div class="max-w-4xl mx-auto text-center">
            <div class="serif italic text-amber-200/40 text-2xl mb-2">少林寺拳法</div>
            <div class="text-stone-400 text-xs font-semibold uppercase tracking-[0.3em] mb-4">Smart-Perkemi · Shorinji Kempo Indonesia</div>
            <div class="inv-line mx-auto max-w-xs mb-4" style="background: linear-gradient(90deg, transparent, rgba(180,83,9,0.3), transparent);"></div>
            <p class="text-stone-600 text-xs">© 2026 SMART-PERKEMI · Piala Walikota Surabaya 2026 · Hak Cipta Dilindungi</p>
        </div>
    </footer>

</body>
</html>
