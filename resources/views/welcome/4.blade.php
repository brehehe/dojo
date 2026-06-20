@php
    // Provide safe defaults when $theme is not passed (direct view call)
    $theme ??= [
        'label'        => 'Orange',
        'accent'       => '#f97316',
        'accentHover'  => '#fb923c',
        'accentDim'    => 'rgba(249,115,22,0.10)',
        'accentBorder' => 'rgba(249,115,22,0.25)',
        'accentText'   => '#ea580c',
        'orb1'         => 'rgba(251,146,60,0.25)',
        'orb2'         => 'rgba(234,88,12,0.12)',
        'orb3'         => 'rgba(253,186,116,0.10)',
        'stroke'       => 'rgba(251,146,60,0.55)',
        'bgFrom'       => '#fff7ed',
        'bgTo'         => '#fef3c7',
        'ctaGradFrom'  => 'rgba(249,115,22,0.08)',
        'ctaGradTo'    => 'rgba(249,115,22,0.02)',
    ];
    $allColors ??= [];
    $color     ??= 'orange';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piala Walikota Surabaya 2026 · SMART-PERKEMI · {{ $theme['label'] }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'DM Sans', sans-serif; }
        .bebas { font-family: 'Bebas Neue', sans-serif; }

        /* CSS custom properties injected from PHP theme */
        :root {
            --accent:         {{ $theme['accent'] }};
            --accent-hover:   {{ $theme['accentHover'] }};
            --accent-dim:     {{ $theme['accentDim'] }};
            --accent-border:  {{ $theme['accentBorder'] }};
            --accent-text:    {{ $theme['accentText'] }};
            --orb1:           {{ $theme['orb1'] }};
            --orb2:           {{ $theme['orb2'] }};
            --orb3:           {{ $theme['orb3'] }};
            --stroke:         {{ $theme['stroke'] }};
        }

        /* ====== LIGHT BACKGROUND ====== */
        .pattern-bg {
            background-color: #fafaf9;
            background-image:
                radial-gradient(circle at 25% 25%, var(--orb1) 0%, transparent 55%),
                radial-gradient(circle at 75% 75%, var(--orb2) 0%, transparent 55%);
        }

        /* ====== GLASS (light-mode) ====== */
        .glass {
            background: rgba(255,255,255,0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 2px 20px -4px rgba(0,0,0,0.06);
        }
        .glass-hover:hover {
            background: rgba(255,255,255,0.85);
        }

        /* ====== ANIMATED ORBS ====== */
        @keyframes floatOrb {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-22px) scale(1.06); }
        }
        @keyframes pulseSlow {
            0%,100% { opacity: 0.6; }
            50%      { opacity: 1; }
        }
        .orb   { animation: floatOrb 8s ease-in-out infinite; }
        .orb-2 { animation: floatOrb 12s ease-in-out infinite reverse; }
        .orb-3 { animation: floatOrb 10s ease-in-out infinite 2s; }
        .orb-pulse { animation: pulseSlow 4s ease-in-out infinite; }

        /* ====== ACCENT UTILITIES (via CSS vars — safe from Tailwind purge) ====== */
        .accent-pulse-dot   { background: var(--accent); }
        .accent-title-solid { color: var(--accent-text); }
        .accent-title-span  { -webkit-text-stroke: 2px var(--stroke); color: transparent; }
        .accent-text        { color: var(--accent-text); }
        .accent-icon        { color: var(--accent-text); }

        .accent-border-icon {
            background: var(--accent-dim);
            border: 1px solid var(--accent-border);
        }
        .accent-rundown-num {
            background: var(--accent-dim);
            border: 1px solid var(--accent-border);
            color: var(--accent-text);
        }
        .group:hover .accent-rundown-num {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }
        .btn-accent {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 10px 30px -8px color-mix(in srgb, var(--accent) 40%, transparent);
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .btn-accent:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 16px 36px -8px color-mix(in srgb, var(--accent) 45%, transparent);
        }
        .btn-ghost {
            background: rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.10);
            color: #44403c;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-ghost:hover {
            background: rgba(0,0,0,0.09);
            transform: translateY(-2px);
        }

        /* ====== CARD BORDER ====== */
        .card-light {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.07);
            box-shadow: 0 2px 16px -4px rgba(0,0,0,0.05);
            transition: box-shadow 0.25s, transform 0.25s;
        }
        .card-light:hover {
            box-shadow: 0 8px 32px -8px rgba(0,0,0,0.10);
            transform: translateY(-3px);
        }

        /* ====== SECTION DIVIDER ====== */
        .section-divider { border-color: rgba(0,0,0,0.06); }

        /* ====== BADGE / TAG ====== */
        .badge-accent {
            background: var(--accent-dim);
            border: 1px solid var(--accent-border);
            color: var(--accent-text);
        }

        /* ====== COLOR SWITCHER ====== */
        .switcher-pill {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 4px 24px -4px rgba(0,0,0,0.12);
        }
        .switcher-panel {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 12px 40px -8px rgba(0,0,0,0.14);
        }

        body { transition: background 0.5s ease; }
    </style>
</head>
<body class="antialiased overflow-x-hidden pattern-bg text-stone-800">

    <!-- ===== ANIMATED BACKGROUND ORBS ===== -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="orb orb-pulse absolute top-1/4 -left-32 w-[500px] h-[500px] rounded-full"
             style="background: radial-gradient(circle, {{ $theme['orb1'] }} 0%, transparent 70%);"></div>
        <div class="orb-2 orb-pulse absolute top-1/3 right-0 w-[600px] h-[600px] rounded-full"
             style="background: radial-gradient(circle, {{ $theme['orb2'] }} 0%, transparent 70%);"></div>
        <div class="orb-3 orb-pulse absolute bottom-0 left-1/3 w-[400px] h-[400px] rounded-full"
             style="background: radial-gradient(circle, {{ $theme['orb3'] }} 0%, transparent 70%);"></div>
    </div>

    <!-- ===== NAVBAR ===== -->
    <nav class="sticky top-0 z-50 px-6 py-4 border-b section-divider" style="background: rgba(250,250,249,0.85); backdrop-filter: blur(20px);">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl overflow-hidden border border-stone-200 shadow-sm">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-stone-800 font-black text-sm tracking-tight">SMART-PERKEMI</span>
            </div>

            <!-- Links -->
            <div class="hidden md:flex items-center gap-7 text-stone-500 text-xs font-semibold">
                <a href="#hero"    class="hover:text-stone-800 transition">Beranda</a>
                <a href="#info"    class="hover:text-stone-800 transition">Info</a>
                <a href="#rundown" class="hover:text-stone-800 transition">Rundown</a>
                <a href="#daftar"  class="hover:text-stone-800 transition">Daftar</a>
            </div>

            <!-- Auth -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="btn-accent text-xs font-black px-5 py-2.5 rounded-xl uppercase tracking-wide">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="btn-ghost text-xs font-bold px-4 py-2.5 rounded-xl">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="btn-accent text-xs font-black px-5 py-2.5 rounded-xl uppercase tracking-wide">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section id="hero" class="relative min-h-screen flex items-center justify-center z-10">
        <div class="max-w-5xl mx-auto px-6 pt-20 pb-20 text-center">

            <!-- Badge -->
            <div class="inline-flex items-center gap-2 badge-accent rounded-full px-5 py-2.5 mb-10">
                <div class="w-2 h-2 rounded-full animate-pulse accent-pulse-dot"></div>
                <span class="text-xs font-semibold uppercase tracking-widest accent-text">
                    Kejuaraan Resmi · Shorinji Kempo Indonesia
                </span>
            </div>

            <!-- Title -->
            <h1 class="bebas text-[80px] md:text-[130px] leading-none text-stone-900 mb-4 tracking-wider">
                PIALA <span class="accent-title-span">WALIKOTA</span>
            </h1>
            <div class="bebas text-4xl md:text-6xl mb-8 tracking-widest accent-text">SURABAYA 2026</div>

            <!-- Description -->
            <p class="text-stone-500 text-lg leading-relaxed max-w-2xl mx-auto mb-14">
                Platform pendaftaran digital resmi Kejuaraan Shorinji Kempo — cepat, aman, dan terverifikasi.
                Daftarkan kontingen Anda sekarang.
            </p>

            <!-- CTA -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="group btn-accent flex items-center gap-3 font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                        <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="group btn-accent flex items-center gap-3 font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide">
                        <i class="fas fa-file-signature"></i> Daftar Kontingen
                        <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition"></i>
                    </a>
                    <a href="{{ route('login') }}"
                       class="btn-ghost flex items-center gap-3 font-semibold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endauth
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto">
                @foreach([
                    ['500+', 'Peserta Terdaftar',    'fa-users'],
                    ['30+',  'Nomor Pertandingan',   'fa-list'],
                    ['20+',  'Kota Asal Kontingen',  'fa-map-marker-alt'],
                    ['2026', 'Edisi Kejuaraan',      'fa-award'],
                ] as [$num, $label, $icon])
                    <div class="card-light rounded-2xl p-5 text-center">
                        <i class="fas {{ $icon }} accent-icon text-sm mb-3 block opacity-70"></i>
                        <div class="text-2xl font-black text-stone-800 mb-1">{{ $num }}</div>
                        <div class="text-stone-400 text-[15px] font-semibold uppercase tracking-widest leading-tight">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-stone-300 animate-bounce">
            <div class="text-[15px] uppercase tracking-widest font-semibold">Scroll</div>
            <i class="fas fa-chevron-down text-xs"></i>
        </div>
    </section>

    <!-- ===== INFO CARDS ===== -->
    <section id="info" class="relative z-10 py-24 border-t section-divider">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="bebas text-5xl md:text-6xl text-stone-800 tracking-wide mb-4">
                    INFORMASI <span class="accent-title-solid">KEJUARAAN</span>
                </h2>
                <p class="text-stone-400 text-sm max-w-xl mx-auto">Semua yang perlu Anda ketahui tentang kejuaraan ini</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach([
                    ['fa-map-marker-alt', 'Lokasi',       'GOR Pertamina Surabaya',  'Gelanggang berstandar nasional dengan fasilitas lengkap.'],
                    ['fa-calendar-alt',  'Jadwal',        'Mei 2026',                'Technical meeting D-1, pertandingan berlangsung selama 2 hari.'],
                    ['fa-users',         'Peserta',       'Terbuka Umum',            'Untuk kenshi Kyu dan Dan dari seluruh dojo di Indonesia.'],
                    ['fa-list',          'Kategori',      '30+ Nomor',               'Embu Perorangan, Berpasangan, dan Randori di berbagai kelas.'],
                    ['fa-award',         'Hadiah',        'Piala + Medali',          'Piala bergilir, medali, sertifikat, dan hadiah uang pembinaan.'],
                    ['fa-certificate',   'Penyelenggara', 'PERKEMI Resmi',           'Diselenggarakan di bawah naungan PERKEMI Jawa Timur.'],
                ] as [$icon, $title, $main, $desc])
                    <div class="card-light rounded-2xl p-6 group">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl accent-border-icon flex items-center justify-center shrink-0">
                                <i class="fas {{ $icon }} accent-icon text-sm"></i>
                            </div>
                            <div>
                                <div class="text-stone-400 text-[15px] font-bold uppercase tracking-widest mb-1">{{ $title }}</div>
                                <div class="text-stone-800 font-bold text-base mb-1">{{ $main }}</div>
                                <p class="text-stone-400 text-xs leading-relaxed">{{ $desc }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== RUNDOWN ===== -->
    <section id="rundown" class="relative z-10 py-24 border-t section-divider">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="bebas text-5xl md:text-6xl text-stone-800 tracking-wide mb-4">
                    RUNDOWN <span class="accent-title-solid">RESMI</span>
                </h2>
            </div>

            <div class="space-y-3">
                @foreach([
                    ['1', 'D-7',    'Penutupan Pendaftaran', 'Batas akhir pendaftaran kontingen online. Tidak ada perpanjangan.',    '00:00 WIB'],
                    ['2', 'D-1',    'Technical Meeting',     'Rapat teknis untuk pelatih & manajer kontingen. Wajib hadir.',         '09:00 WIB'],
                    ['3', 'D-1',    'Registrasi & Timbang',  'Verifikasi dokumen dan timbang badan untuk kategori Randori.',         '14:00 WIB'],
                    ['4', 'Hari 1', 'Pembukaan & Sesi 1',   'Upacara pembukaan resmi dan pertandingan babak penyisihan.',           '07:00 WIB'],
                    ['5', 'Hari 2', 'Final & Penutupan',    'Babak final semua kategori, upacara penghargaan, penutupan resmi.',    '07:00 WIB'],
                ] as [$num, $day, $title, $desc, $time])
                    <div class="card-light rounded-2xl p-5 flex items-center gap-5 group">
                        <div class="w-10 h-10 accent-rundown-num rounded-xl flex items-center justify-center shrink-0 font-black text-sm transition">
                            {{ $num }}
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center gap-2">
                                <span class="accent-text text-[15px] font-black uppercase tracking-widest w-20 shrink-0">{{ $day }}</span>
                                <h3 class="text-stone-800 font-bold text-sm">{{ $title }}</h3>
                            </div>
                            <p class="text-stone-400 text-xs mt-1">{{ $desc }}</p>
                        </div>
                        <div class="text-stone-300 text-xs font-semibold shrink-0 hidden md:block">{{ $time }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section id="daftar" class="relative z-10 py-24 border-t section-divider">
        <div class="max-w-4xl mx-auto px-6">
            <div class="card-light rounded-[40px] p-12 md:p-16 text-center"
                 style="background: linear-gradient(135deg, {{ $theme['ctaGradFrom'] }}, {{ $theme['ctaGradTo'] }}, #fff);">
                <div class="w-16 h-16 rounded-[20px] flex items-center justify-center mx-auto mb-8 overflow-hidden accent-border-icon">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <h2 class="bebas text-6xl md:text-8xl text-stone-900 tracking-wide mb-6">
                    BERGABUNG<br><span class="accent-title-solid">SEKARANG</span>
                </h2>
                <p class="text-stone-500 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
                    Daftarkan kontingen Anda dan jadilah bagian dari Kejuaraan Kempo bergengsi di Kota Surabaya.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="btn-accent font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide flex items-center gap-3">
                            <i class="fas fa-tachometer-alt"></i> Masuk Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="btn-accent font-bold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide flex items-center gap-3">
                            <i class="fas fa-file-signature"></i> Daftar Kontingen
                        </a>
                        <a href="{{ route('login') }}"
                           class="btn-ghost font-semibold px-10 py-5 rounded-2xl text-sm uppercase tracking-wide flex items-center gap-3">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- ===== COLOR SWITCHER (floating) ===== -->
    @if(count($allColors) > 0)
        <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50"
             x-data="{ open: false }" @click.outside="open = false">

            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="absolute bottom-14 left-1/2 -translate-x-1/2 switcher-panel rounded-2xl p-4 flex flex-wrap justify-center gap-3"
                 style="min-width: 290px;">
                @foreach($allColors as $c)
                    <a href="{{ route('welcome.4.color', $c['slug']) }}"
                       title="{{ $c['label'] }}"
                       class="group flex flex-col items-center gap-1.5 transition">
                        <div class="w-8 h-8 rounded-full shadow-sm transition group-hover:scale-110"
                             style="background-color: {{ $c['hex'] }}; {{ $c['slug'] === $color ? 'outline: 2.5px solid '.$c['hex'].'; outline-offset: 3px;' : 'border: 2px solid rgba(0,0,0,0.08);' }}">
                        </div>
                        <span class="text-stone-400 text-[15px] font-semibold uppercase tracking-wider group-hover:text-stone-700 transition leading-none">
                            {{ $c['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Toggle button -->
            <button @click="open = !open"
                    class="switcher-pill rounded-full px-5 py-2.5 flex items-center gap-3 text-stone-700 text-xs font-bold uppercase tracking-widest transition hover:shadow-lg">
                <div class="w-3 h-3 rounded-full shadow-sm" style="background: {{ $theme['accent'] }};"></div>
                <span>{{ $theme['label'] }}</span>
                <i class="fas fa-palette text-xs text-stone-400"></i>
            </button>
        </div>
    @endif

    <!-- ===== FOOTER ===== -->
    <footer class="relative z-10 py-10 border-t section-divider" style="background: rgba(250,250,249,0.8);">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg overflow-hidden border border-stone-200">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-stone-500 font-bold text-xs uppercase tracking-widest">SMART-PERKEMI</span>
            </div>
            <p class="text-stone-400 text-xs">© 2026 SMART-PERKEMI · Shorinji Kempo Indonesia</p>
            <div class="text-stone-300 text-xs font-semibold">少林寺拳法</div>
        </div>
    </footer>

</body>
</html>
