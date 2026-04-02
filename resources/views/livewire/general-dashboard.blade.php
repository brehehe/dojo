<div class="space-y-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Portal Kenshi</h1>
            <p class="text-slate-500 font-medium">Selamat datang di Smart-Perkemi, {{ auth()->user()->name }}</p>
        </div>
        <div class="bg-white rounded-2xl px-6 py-3 shadow-sm border border-slate-100 flex items-center gap-3">
            <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
            <span class="font-bold text-slate-700 uppercase text-xs tracking-widest">Sesi Aktif</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-orange-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform opacity-30">
                <i class="fas fa-certificate text-orange-200 text-5xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2 relative z-10">Sertifikat Saya</h3>
            <p class="text-slate-500 text-sm mb-8 relative z-10">Lihat dan unduh sertifikat prestasi digital Anda.</p>
            <button class="w-full bg-slate-50 hover:bg-orange-600 hover:text-white py-4 rounded-2xl font-bold text-slate-600 transition-all active:scale-95">Lihat Semua</button>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform opacity-30">
                <i class="fas fa-calendar-alt text-blue-200 text-5xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2 relative z-10">Event Terdekat</h3>
            <p class="text-slate-500 text-sm mb-8 relative z-10">Pantau jadwal kejuaraan dan gashuku Nasional.</p>
            <button class="w-full bg-slate-50 hover:bg-blue-600 hover:text-white py-4 rounded-2xl font-bold text-slate-600 transition-all active:scale-95">Buka Kalender</button>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform opacity-30">
                <i class="fas fa-id-card text-emerald-200 text-5xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2 relative z-10">Kartu Kenshi</h3>
            <p class="text-slate-500 text-sm mb-8 relative z-10">Lihat kartu tanda anggota digital PERKEMI Anda.</p>
            <button class="w-full bg-slate-50 hover:bg-emerald-600 hover:text-white py-4 rounded-2xl font-bold text-slate-600 transition-all active:scale-95">Lihat Kartu</button>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-[#0f2b3d] rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-600/10 to-transparent"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
            <div class="w-32 h-32 shrink-0 bg-orange-600 rounded-[2rem] flex items-center justify-center shadow-lg rotate-3 group hover:rotate-0 transition-transform">
                <i class="fas fa-bullhorn text-5xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold mb-3 tracking-tight">Piala Walikota Surabaya 2026</h3>
                <p class="text-white/60 text-lg leading-relaxed mb-6">Jangan lewatkan kesempatan untuk berprestasi di tingkat Kota! Pendaftaran atlet kolektif sedang dibuka hingga 30 Mei 2026.</p>
                <a href="/piala_walikotasby2026" class="inline-flex items-center gap-3 bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-2xl font-bold transition-all no-underline active:scale-95">
                    <i class="fas fa-edit"></i> Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
