<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piala Walikota Surabaya 2026 · SMART-PERKEMI</title>
    <meta name="description" content="Kejuaraan Resmi Shorinji Kempo Piala Walikota Surabaya 2026">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }
        .golden-line { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .card-hover { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 25px 50px -12px rgba(245,158,11,0.15); }
    </style>
</head>
<body class="antialiased bg-white overflow-x-hidden">

    <!-- ===== NAVBAR ===== -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-stone-100 shadow-sm shadow-stone-100/50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 flex items-center justify-center shadow-lg shadow-amber-500/20 overflow-hidden">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="text-xs font-black uppercase tracking-widest text-stone-800">Smart-Perkemi</div>
                    <div class="text-[10px] text-stone-400 font-medium">Shorinji Kempo Indonesia</div>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-8 text-stone-500 text-sm font-semibold">
                <a href="#kategori" class="hover:text-amber-600 transition">Kategori</a>
                <a href="#rundown" class="hover:text-amber-600 transition">Rundown</a>
                <a href="#contact" class="hover:text-amber-600 transition">Kontak</a>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-black px-5 py-2.5 rounded-xl uppercase tracking-wide transition shadow-lg shadow-amber-500/20">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-stone-500 hover:text-stone-800 text-xs font-bold transition hidden sm:block">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-black px-5 py-2.5 rounded-xl uppercase tracking-wide transition shadow-lg shadow-amber-500/20">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="relative overflow-hidden bg-stone-900 text-white min-h-[620px] flex items-center">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>

        <!-- Gold bar left -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-amber-400 to-amber-600"></div>

        <div class="max-w-7xl mx-auto px-6 py-24 grid grid-cols-1 md:grid-cols-2 gap-16 items-center relative z-10 w-full">
            <div>
                <!-- Championship Tag -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-0.5 bg-amber-500"></div>
                    <span class="text-amber-400 text-xs font-black uppercase tracking-[0.3em]">Kejuaraan Resmi 2026</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-black leading-tight mb-6 tracking-tight">
                    PIALA<br>
                    <span class="text-amber-400">WALIKOTA</span><br>
                    SURABAYA
                </h1>

                <p class="text-stone-300 text-lg leading-relaxed mb-8 max-w-md">
                    Kejuaraan Shorinji Kempo Tingkat Nasional — mempertemukan kenshi terbaik dari seluruh Indonesia di Kota Pahlawan.
                </p>

                <div class="flex flex-wrap gap-3 mb-10">
                    <span class="bg-white/10 border border-white/10 text-white/70 text-xs px-4 py-2 rounded-full font-semibold"><i class="fas fa-map-marker-alt mr-2 text-amber-400"></i>GOR Surabaya</span>
                    <span class="bg-white/10 border border-white/10 text-white/70 text-xs px-4 py-2 rounded-full font-semibold"><i class="fas fa-calendar mr-2 text-amber-400"></i>Mei 2026</span>
                    <span class="bg-white/10 border border-white/10 text-white/70 text-xs px-4 py-2 rounded-full font-semibold"><i class="fas fa-users mr-2 text-amber-400"></i>Terbuka Umum</span>
                </div>

                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-amber-500 hover:bg-amber-400 text-stone-900 font-black px-8 py-4 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-2 shadow-xl shadow-amber-500/20">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-amber-500 hover:bg-amber-400 text-stone-900 font-black px-8 py-4 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-2 shadow-xl shadow-amber-500/20">
                            <i class="fas fa-file-signature"></i> Daftar Kontingen
                        </a>
                        <a href="{{ route('login') }}" class="bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold px-8 py-4 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-2">
                            <i class="fas fa-sign-in-alt"></i> Masuk
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Visual block right -->
            <div class="hidden md:flex flex-col gap-4">
                <!-- Big medal card -->
                <div class="bg-gradient-to-br from-amber-500/20 to-amber-600/5 border border-amber-500/20 rounded-3xl p-8 text-center">
                    <div class="text-7xl font-black text-amber-400 leading-none mb-2">2026</div>
                    <div class="text-white/50 text-sm font-semibold uppercase tracking-widest">Edisi Kejuaraan</div>
                    <div class="mt-6 grid grid-cols-3 gap-3">
                        @foreach([['🥇', 'Emas'], ['🥈', 'Perak'], ['🥉', 'Perunggu']] as [$emoji, $label])
                            <div class="bg-white/5 rounded-2xl py-4">
                                <div class="text-2xl">{{ $emoji }}</div>
                                <div class="text-white/40 text-[10px] font-bold mt-1">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Info cards row -->
                <div class="grid grid-cols-2 gap-4">
                    @foreach([['500+', 'Peserta', 'fa-users', 'amber'], ['30+', 'Nomor Pertandingan', 'fa-list', 'orange'], ['20+', 'Kontingen', 'fa-flag', 'yellow'], ['', 'PERKEMI Resmi', 'fa-certificate', 'amber']] as [$num, $label, $icon, $c])
                        <div class="bg-stone-800 border border-stone-700 rounded-2xl p-4">
                            <i class="fas {{ $icon }} text-amber-400 text-sm mb-2 block"></i>
                            @if($num)
                                <div class="text-white font-black text-xl">{{ $num }}</div>
                            @endif
                            <div class="text-stone-400 text-[11px] font-semibold leading-snug">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ===== KATEGORI ===== -->
    <section id="kategori" class="py-24 bg-stone-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-6 mb-16">
                <div class="w-1 h-12 bg-amber-500 rounded-full"></div>
                <div>
                    <p class="text-amber-600 text-xs font-black uppercase tracking-widest mb-1">Nomor Pertandingan</p>
                    <h2 class="text-3xl md:text-4xl font-black text-stone-800 tracking-tight">Kategori Kejuaraan</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['fa-user', 'Embu Perorangan', 'Penampilan kata tunggal yang menunjukkan teknik, kecepatan, dan ekspresi jiwa. Dinilai berdasarkan presisi dan penghayatan.', 'amber'],
                    ['fa-user-friends', 'Embu Berpasangan', 'Penampilan kata berpasangan yang memperlihatkan keselarasan dan kerjasama antar kenshi. Teknik dipraktikkan secara berpasangan.', 'orange'],
                    ['fa-fist-raised', 'Randori', 'Pertandingan sparing teknik terkontrol antar kenshi. Mengedepankan ketepatan serangan dan pertahanan yang terukur.', 'red'],
                ] as [$icon, $name, $desc, $c])
                    <div class="card-hover bg-white border border-stone-100 rounded-3xl p-8 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mb-6">
                            <i class="fas {{ $icon }} text-amber-500"></i>
                        </div>
                        <h3 class="text-stone-800 font-black text-xl mb-3">{{ $name }}</h3>
                        <p class="text-stone-400 text-sm leading-relaxed">{{ $desc }}</p>
                        <div class="mt-6 flex items-center gap-2 text-amber-500 text-xs font-black uppercase tracking-widest">
                            <span>Lihat Kategori</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== RUNDOWN ===== -->
    <section id="rundown" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-6 mb-16">
                <div class="w-1 h-12 bg-amber-500 rounded-full"></div>
                <div>
                    <p class="text-amber-600 text-xs font-black uppercase tracking-widest mb-1">Jadwal & Agenda</p>
                    <h2 class="text-3xl md:text-4xl font-black text-stone-800 tracking-tight">Rundown Kejuaraan</h2>
                </div>
            </div>

            <div class="space-y-3">
                @foreach([
                    ['D-7', 'Penutupan Pendaftaran Online', 'Batas akhir pendaftaran kontingen secara online melalui portal SMART-PERKEMI.', '08:00 WIB', 'amber'],
                    ['D-1', 'Technical Meeting', 'Rapat teknis bagi pelatih dan manajer kontingen. Pemaparan peraturan kejuaraan.', '13:00 WIB', 'blue'],
                    ['D-1', 'Registrasi Ulang & Timbang Badan', 'Verifikasi dokumen dan timbang badan untuk kategori Randori.', '15:00–18:00 WIB', 'purple'],
                    ['Hari 1', 'Pembukaan & Pertandingan Sesi 1', 'Upacara pembukaan resmi dilanjutkan pertandingan Embu Perorangan dan Berpasangan.', '07:00 WIB', 'orange'],
                    ['Hari 2', 'Pertandingan Sesi 2 & Grand Final', 'Pertandingan lanjutan, babak final, dan upacara penghargaan seluruh kategori.', '07:00 WIB', 'orange'],
                ] as [$day, $title, $desc, $time, $c])
                    <div class="flex items-start gap-6 bg-stone-50 hover:bg-amber-50 border border-stone-100 hover:border-amber-200 rounded-2xl p-6 transition">
                        <div class="w-16 h-16 rounded-2xl bg-amber-100 border border-amber-200 flex flex-col items-center justify-center shrink-0">
                            <div class="text-amber-600 font-black text-sm leading-tight text-center">{{ $day }}</div>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                <h3 class="text-stone-800 font-black text-base">{{ $title }}</h3>
                                <span class="bg-white border border-stone-200 text-stone-500 text-[10px] font-bold px-3 py-1 rounded-full shrink-0">{{ $time }}</span>
                            </div>
                            <p class="text-stone-400 text-sm leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section id="contact" class="py-24 bg-stone-900">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="w-1 h-12 bg-amber-500 mx-auto mb-8 rounded-full"></div>
            <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-6">Siap Berkompetisi?</h2>
            <p class="text-stone-400 text-lg mb-10 max-w-xl mx-auto leading-relaxed">Daftarkan kontingen Anda sekarang dan buktikan kehebatan kenshi terbaik Anda.</p>
            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-amber-500 hover:bg-amber-400 text-stone-900 font-black px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-3">
                        <i class="fas fa-tachometer-alt"></i> Dashboard Kontingen
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-amber-500 hover:bg-amber-400 text-stone-900 font-black px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-3 shadow-2xl shadow-amber-500/20">
                        <i class="fas fa-file-signature"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide transition hover:-translate-y-1 flex items-center gap-3">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-stone-950 border-t border-stone-800 py-10 text-center">
        <div class="text-amber-500/50 font-black text-xs uppercase tracking-[0.3em] mb-2">Smart-Perkemi · 少林寺拳法</div>
        <p class="text-stone-600 text-xs">© 2026 SMART-PERKEMI · Shorinji Kempo Indonesia · Piala Walikota Surabaya</p>
    </footer>

</body>
</html>
