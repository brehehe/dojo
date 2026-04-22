<div class="space-y-8 pb-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-900 rounded-2xl flex items-center justify-center shadow-inner">
                <i class="fas fa-chart-pie text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-2xl font-black text-black tracking-tight uppercase">Ringkasan Admin</h1>
                <p class="text-[15px] text-slate-900 font-medium">Monitor perkembangan pendaftaran Smart-Perkemi</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="bg-slate-50 rounded-xl px-4 py-2.5 border border-slate-100 flex items-center gap-3">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                <span class="font-black text-black uppercase text-[15px] tracking-widest">Sistem Aktif</span>
            </div>
        </div>
    </div>

    <!-- HORIZONTAL MENU -->
    <div>
        <h2 class="text-[15px] font-black uppercase text-slate-900 tracking-widest mb-3 px-2 flex items-center gap-2">
            <i class="fas fa-compass"></i> Navigasi Utama
        </h2>
        <div class="flex overflow-x-auto gap-4 pb-4 hidescrollbar">
            <!-- Pendaftaran -->
            <a href="{{ route('admin.registrations.index') }}" class="flex-shrink-0 flex items-center gap-4 bg-gradient-to-br from-blue-500 to-indigo-600 text-white px-6 py-5 rounded-[1.5rem] shadow-lg shadow-blue-500/20 hover:-translate-y-1 transition-all duration-300 min-w-[260px] group border border-blue-400/50">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:-rotate-12 transition-transform duration-300">
                    <i class="fas fa-file-signature text-xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-[15px] uppercase tracking-widest leading-none mb-1">Verifikasi</h3>
                    <p class="text-[15px] text-blue-50 font-bold uppercase tracking-widest">Pendaftaran Peserta</p>
                </div>
            </a>
            
            <!-- Kontingen & Atlet (Master) -->
            <a href="{{ route('admin.master.contingents.index') }}" class="flex-shrink-0 flex items-center gap-4 bg-gradient-to-br from-emerald-500 to-teal-600 text-white px-6 py-5 rounded-[1.5rem] shadow-lg shadow-emerald-500/20 hover:-translate-y-1 transition-all duration-300 min-w-[260px] group border border-emerald-400/50">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:-rotate-12 transition-transform duration-300">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-[15px] uppercase tracking-widest leading-none mb-1">Master Data</h3>
                    <p class="text-[15px] text-emerald-50 font-bold uppercase tracking-widest">Kontingen & Atlet</p>
                </div>
            </a>

            <!-- Technical Meeting (Undian) -->
            <a href="{{ route('admin.technical-meeting.embu') }}" class="flex-shrink-0 flex items-center gap-4 bg-gradient-to-br from-amber-500 to-orange-600 text-white px-6 py-5 rounded-[1.5rem] shadow-lg shadow-amber-500/20 hover:-translate-y-1 transition-all duration-300 min-w-[260px] group border border-amber-400/50">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:-rotate-12 transition-transform duration-300">
                    <i class="fas fa-random text-xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-[15px] uppercase tracking-widest leading-none mb-1">T. Meeting</h3>
                    <p class="text-[15px] text-amber-50 font-bold uppercase tracking-widest">Pengundian Bagan</p>
                </div>
            </a>

            <!-- Arbitrase (Pertandingan) -->
            <a href="{{ route('admin.arbitrase.scoring.index') }}" class="flex-shrink-0 flex items-center gap-4 bg-gradient-to-br from-rose-500 to-pink-600 text-white px-6 py-5 rounded-[1.5rem] shadow-lg shadow-rose-500/20 hover:-translate-y-1 transition-all duration-300 min-w-[260px] group border border-rose-400/50">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm group-hover:-rotate-12 transition-transform duration-300">
                    <i class="fas fa-gavel text-xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-[15px] uppercase tracking-widest leading-none mb-1">Arbitrase</h3>
                    <p class="text-[15px] text-rose-50 font-bold uppercase tracking-widest">Scoring Wasit & Live</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div>
        <h2 class="text-[15px] font-black uppercase text-slate-900 tracking-widest mb-3 px-2 flex items-center gap-2">
            <i class="fas fa-chart-bar"></i> Statistik Utama
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-users text-orange-200 text-3xl"></i>
                </div>
                <p class="text-slate-900 text-[15px] font-black uppercase tracking-widest mb-2 relative z-10">Total Kontingen</p>
                <div class="text-4xl font-black text-black relative z-10">
                    {{ number_format($stats['total_contingents']) }}
                </div>
                <div class="mt-4 text-[15px] font-bold uppercase tracking-widest relative z-10 inline-flex items-center gap-1.5 px-2 py-1 bg-slate-50 text-slate-900 rounded-lg border border-slate-100">
                    <i class="fas fa-map-marker-alt text-orange-600"></i> PERKEMI KAB/KOTA
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-id-card text-blue-200 text-3xl"></i>
                </div>
                <p class="text-slate-900 text-[15px] font-black uppercase tracking-widest mb-2 relative z-10">Total Atlet</p>
                <div class="text-4xl font-black text-blue-900 relative z-10">{{ number_format($stats['total_athletes']) }}</div>
                <div class="mt-4 text-[15px] font-bold uppercase tracking-widest relative z-10 inline-flex items-center gap-1.5 px-2 py-1 bg-blue-50 text-blue-900 rounded-lg border border-blue-100">
                    <i class="fas fa-check-circle"></i> TERDAFTAR DI SISTEM
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock text-amber-200 text-3xl"></i>
                </div>
                <p class="text-slate-900 text-[15px] font-black uppercase tracking-widest mb-2 relative z-10">Menunggu Verifikasi</p>
                <div class="text-4xl font-black text-amber-600 relative z-10">
                    {{ number_format($stats['pending_verifications']) }}
                </div>
                <div class="mt-4 text-[15px] font-bold uppercase tracking-widest relative z-10 inline-flex items-center gap-1.5 px-2 py-1 bg-amber-50 text-amber-900 rounded-lg border border-amber-100">
                    <i class="fas fa-hourglass-half"></i> PEMBAYARAN PENDING
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-wallet text-emerald-200 text-3xl"></i>
                </div>
                <p class="text-slate-900 text-[15px] font-black uppercase tracking-widest mb-2 relative z-10">Total Dana Masuk</p>
                <div class="text-2xl font-black text-emerald-800 relative z-10 mt-1 mb-2.5">
                    Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}
                </div>
                <div class="mt-4 text-[15px] font-bold uppercase tracking-widest relative z-10 inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-50 text-emerald-900 rounded-lg border border-emerald-100">
                    <i class="fas fa-shield-check"></i> STATUS CONFIRMED
                </div>
            </div>
        </div>
    </div>

    <!-- Kontingen Terbaru Table -->
    <div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <h3 class="text-[15px] font-black text-black uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-list-alt text-orange-600"></i> Kontingen Terbaru
                </h3>
                <a href="{{ route('admin.master.contingents.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 hover:bg-slate-100 text-orange-800 rounded-xl font-black text-[15px] uppercase tracking-widest transition-colors border border-slate-100">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kontingen</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kab/Kota</th>
                            <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center" width="50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach(App\Models\Contingent::latest()->take(5)->get() as $contingent)
                            <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                <td class="px-4 py-4 text-[15px] font-black text-slate-800 border-r border-slate-200 align-top uppercase">
                                    {{ $contingent->name }}
                                </td>
                                <td class="px-4 py-4 text-[15px] font-black text-slate-800 border-r border-slate-200 align-top uppercase">
                                    {{ $contingent->kab_kota }}
                                </td>
                                <td class="px-3 py-3 border-r border-slate-200 text-center align-middle">
                                    <a href="{{ route('admin.master.contingents.detail', $contingent) }}"
                                        class="w-10 h-10 rounded-lg inline-flex items-center justify-center transition-all bg-slate-100 text-slate-900 hover:bg-orange-100 hover:text-orange-600 active:scale-95 duration-200" title="Detail">
                                        <i class="fas fa-eye text-[15px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('styles')


<style>
    .hidescrollbar::-webkit-scrollbar {
        display: none;
    }
    .hidescrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush