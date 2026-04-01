<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Perkemi Championship 2026 - Portal Resmi</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,600,800|plus-jakarta-sans:400,500,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
            .font-title { font-family: 'Outfit', sans-serif; }
            .font-body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .hero-gradient {
                background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.9) 100%);
            }
            .section-blur {
                filter: blur(80px);
                opacity: 0.15;
            }
        </style>
    </head>
    <body class="bg-zinc-950 text-zinc-100 font-body antialiased selection:bg-rose-600/30 overflow-x-hidden">
        <!-- Hero Section -->
        <header class="relative h-screen flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="/hero.png" class="w-full h-full object-cover scale-105" alt="Karate Action">
                <div class="absolute inset-0 hero-gradient"></div>
            </div>

            <nav class="absolute top-0 left-0 w-full p-8 flex justify-between items-center z-20">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-rose-600/20 rotate-3 transition-transform hover:rotate-0">
                        <span class="text-2xl font-black italic">D</span>
                    </div>
                    <span class="text-xl font-bold tracking-tighter uppercase font-title">Perkemi <span class="text-rose-500">Cup</span> 2026</span>
                </div>
                <div class="hidden lg:flex gap-10 text-xs font-black uppercase tracking-widest opacity-60">
                    <a href="#history" class="hover:text-rose-500 transition-colors">History</a>
                    <a href="#search" class="hover:text-rose-500 transition-colors">Cari Atlet</a>
                    <a href="#info" class="hover:text-rose-500 transition-colors">Info Event</a>
                    <a href="#gallery" class="hover:text-rose-500 transition-colors">Galeri</a>
                    <a href="#blog" class="hover:text-rose-500 transition-colors">Berita</a>
                </div>
                <a href="/register" class="glass px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-white hover:text-black transition-all">Daftar Sekarang</a>
            </nav>

            <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-rose-500/10 border border-rose-500/20 px-4 py-2 rounded-full mb-8 animate-pulse">
                    <div class="w-2 h-2 bg-rose-500 rounded-full"></div>
                    <span class="text-rose-400 text-xs font-bold uppercase tracking-widest">Pendaftaran Gelombang 1</span>
                </div>
                <h1 class="text-7xl md:text-[10rem] font-black mb-6 font-title tracking-tighter leading-[0.8] opacity-90">
                    SABLON <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 via-orange-400 to-rose-600 italic">JUARA</span>
                </h1>
                <p class="text-xl md:text-2xl text-zinc-400 max-w-2xl mx-auto mb-12 font-light leading-relaxed">
                    Event Karate Open Nasional Terbesar Tahun Ini. Tempat di mana disiplin bertemu dengan ambisi.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="/register" class="bg-rose-600 hover:bg-rose-700 text-white px-12 py-6 rounded-2xl font-black text-lg transition-all shadow-2xl shadow-rose-600/40 hover:scale-105 active:scale-95 flex items-center justify-center gap-3">
                        Join Championship
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </header>

        <!-- Search History Section -->
        <section id="search" class="relative py-32 bg-zinc-950 overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-rose-600/5 section-blur -z-10"></div>
            <div class="max-w-4xl mx-auto px-8 relative">
                <div class="text-center mb-16">
                    <span class="text-rose-500 font-bold uppercase tracking-widest mb-4 block">Archive Record</span>
                    <h2 class="text-5xl font-black font-title uppercase tracking-tight">CARI HISTORI ATLET</h2>
                    <p class="text-zinc-500 mt-4">Masukkan nama lengkap atlet untuk melihat rekor pertandingan di Perkemi Cup.</p>
                </div>
                
                <livewire:athlete-search />
            </div>
        </section>

        <!-- Info Schedule Section -->
        <section id="info" class="py-32 bg-zinc-900/50">
            <div class="max-w-7xl mx-auto px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="lg:col-span-1">
                        <span class="text-rose-500 font-bold uppercase tracking-widest mb-4 block">Informasi Event</span>
                        <h2 class="text-5xl font-black font-title leading-tight">JADWAL & <br> PERSYARATAN</h2>
                        <ul class="mt-12 space-y-6">
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-rose-600/20 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold uppercase text-sm tracking-widest mb-1">Pendaftaran</h4>
                                    <p class="text-zinc-500 text-sm">1 April - 30 Mei 2026</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-rose-600/20 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold uppercase text-sm tracking-widest mb-1">Lokasi</h4>
                                    <p class="text-zinc-500 text-sm">Istora Senayan, Jakarta</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="glass p-8 rounded-3xl border-rose-500/20">
                            <h3 class="text-xl font-bold mb-4 uppercase tracking-tight text-rose-500">Persyaratan Umum</h3>
                            <ul class="space-y-3 text-sm text-zinc-400">
                                <li class="flex gap-2"><span>-</span> Pas Foto 3x4 (Digital)</li>
                                <li class="flex gap-2"><span>-</span> Fotokopi Akte Kelahiran</li>
                                <li class="flex gap-2"><span>-</span> Sertifikat Ijazah Karate Minimum Kyu 6</li>
                                <li class="flex gap-2"><span>-</span> Surat Keterangan Sehat Dokter</li>
                            </ul>
                        </div>
                        <div class="glass p-8 rounded-3xl border-rose-500/20">
                            <h3 class="text-xl font-bold mb-4 uppercase tracking-tight text-rose-500">Peraturan Pertandingan</h3>
                            <ul class="space-y-3 text-sm text-zinc-400">
                                <li class="flex gap-2"><span>-</span> Peraturan WKF & FORKI Terbaru</li>
                                <li class="flex gap-2"><span>-</span> Sistem Gugur dengan Repechage</li>
                                <li class="flex gap-2"><span>-</span> Wajib Menggunakan Protector Standar</li>
                                <li class="flex gap-2"><span>-</span> Akreditasi Pelatih Level Nasional</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section id="gallery" class="py-32">
            <div class="max-w-7xl mx-auto px-8">
                <div class="flex justify-between items-end mb-16">
                    <div>
                        <span class="text-rose-500 font-bold uppercase tracking-widest mb-4 block">Visual Moments</span>
                        <h2 class="text-5xl font-black font-title">GALERI KEJUARAAN</h2>
                    </div>
                    <p class="text-zinc-500 max-w-sm text-right hidden md:block">Cuplikan antusiasme dan aksi dari tahun-tahun sebelumnya.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($galleries as $index => $item)
                        @if($index == 0)
                            <div class="md:col-span-2 aspect-video rounded-3xl overflow-hidden group">
                                <img src="{{ $item->image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $item->title }}">
                            </div>
                        @else
                            <div class="aspect-square bg-zinc-900 rounded-3xl overflow-hidden border border-zinc-800 p-1 group hover:border-rose-600/50 transition-all">
                                <img src="{{ $item->image_url }}" class="w-full h-full object-cover rounded-2xl transition-transform duration-700 group-hover:scale-110" alt="{{ $item->title }}">
                            </div>
                        @endif
                    @endforeach
                    
                    @if($galleries->isEmpty())
                        <div class="md:col-span-3 aspect-video bg-zinc-900 rounded-3xl flex items-center justify-center border border-zinc-800 border-dashed p-8 text-center">
                            <p class="text-zinc-500 italic">Foto galeri akan segera hadir.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Blog/News Section -->
        <section id="blog" class="py-32 bg-zinc-900">
            <div class="max-w-7xl mx-auto px-8">
                <div class="text-center mb-20">
                    <span class="text-rose-500 font-bold uppercase tracking-widest mb-4 block">Pusat Berita</span>
                    <h2 class="text-5xl font-black font-title">BLOG & UPDATE</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <!-- Blog Card -->
                        <article class="bg-zinc-950/50 rounded-3xl overflow-hidden border border-zinc-800 hover:border-rose-500/50 transition-all group">
                            <div class="h-48 bg-zinc-800">
                                <img src="{{ $post->image_url }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity" alt="{{ $post->title }}">
                            </div>
                            <div class="p-8">
                                <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest">Update {{ $post->created_at->diffForHumans() }}</span>
                                <h3 class="text-xl font-bold mt-2 mb-4 group-hover:text-rose-500 transition-colors line-clamp-2">{{ $post->title }}</h3>
                                <p class="text-zinc-500 text-sm leading-relaxed mb-6 line-clamp-3">{{ Str::limit($post->content, 120) }}</p>
                                <a href="#" class="text-xs font-black uppercase tracking-widest border-b border-rose-600 pb-1">Baca Selengkapnya</a>
                            </div>
                        </article>
                    @endforeach

                    @if($posts->isEmpty())
                        <div class="md:col-span-3 py-20 text-center">
                            <p class="text-zinc-600 italic uppercase tracking-widest font-bold">Belum ada berita terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-20 border-t border-zinc-800 text-center relative overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-rose-600/5 rounded-full blur-3xl -z-10"></div>
            <div class="flex items-center justify-center gap-3 mb-10 opacity-50">
                <div class="w-8 h-8 bg-rose-600 rounded flex items-center justify-center">
                    <span class="text-lg font-black italic">D</span>
                </div>
                <span class="text-lg font-bold tracking-tighter uppercase font-title">Perkemi <span class="text-rose-500">Cup</span> 2026</span>
            </div>
            <p class="text-zinc-600 font-medium tracking-widest text-[10px] uppercase mb-4">Developed for Martial Arts Excellence</p>
            <p class="text-zinc-500 font-medium tracking-widest text-[10px] uppercase">© 2026 Perkemi Cup Committee. All Rights Reserved.</p>
        </footer>
    </body>
</html>
