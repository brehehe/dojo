<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Ringkasan Admin</h1>
            <p class="text-[13px] text-slate-500 font-medium">Monitor perkembangan pendaftaran Smart-Perkemi</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white rounded-xl px-4 py-2.5 shadow-sm border border-slate-100 flex items-center gap-3">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                <span class="font-black text-slate-700 uppercase text-[10px] tracking-widest">Sistem Online</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="absolute -right-2 -top-2 w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-orange-200 text-2xl"></i>
            </div>
            <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest mb-1 relative z-10">Total Kontingen</p>
            <div class="text-3xl font-black text-slate-800 relative z-10">{{ number_format($stats['total_contingents']) }}</div>
            <div class="mt-3 text-[9px] text-slate-400 font-bold uppercase tracking-widest relative z-10">PERKEMI KAB/KOTA</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="absolute -right-2 -top-2 w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-id-card text-blue-200 text-2xl"></i>
            </div>
            <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest mb-1 relative z-10">Total Atlet</p>
            <div class="text-3xl font-black text-blue-600 relative z-10">{{ number_format($stats['total_athletes']) }}</div>
            <div class="mt-3 text-[9px] text-blue-400 font-bold uppercase tracking-widest relative z-10">TERDAFTAR DI SISTEM</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
             <div class="absolute -right-2 -top-2 w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-clock text-amber-200 text-2xl"></i>
            </div>
            <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest mb-1 relative z-10">Menunggu Verifikasi</p>
            <div class="text-3xl font-black text-amber-600 relative z-10">{{ number_format($stats['pending_verifications']) }}</div>
            <div class="mt-3 text-[9px] text-amber-400 font-bold uppercase tracking-widest relative z-10">PEMBAYARAN PENDING</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="absolute -right-2 -top-2 w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-wallet text-emerald-200 text-2xl"></i>
            </div>
            <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest mb-1 relative z-10">Total Dana Masuk</p>
            <div class="text-xl font-black text-emerald-600 relative z-10">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
            <div class="mt-3 text-[9px] text-emerald-400 font-bold uppercase tracking-widest relative z-10">STATUS CONFIRMED</div>
        </div>
    </div>

    <!-- Quick Actions / Table Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-slate-800 tracking-tight">KONTINGEN TERBARU</h3>
                <a href="{{ route('admin.contingents.index') }}" class="text-orange-600 font-black text-[10px] uppercase tracking-[0.15em] hover:text-orange-700 transition-colors">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            <th class="py-4 px-2">Kontingen</th>
                            <th class="py-4 px-2">Kab/Kota</th>
                            <th class="py-4 px-2">Status</th>
                            <th class="py-4 px-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        @foreach(App\Models\Contingent::latest()->take(5)->get() as $contingent)
                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-2 font-bold">{{ $contingent->name }}</td>
                            <td class="py-4 px-2 text-sm text-slate-500">{{ $contingent->kab_kota }}</td>
                            <td class="py-4 px-2">
                                <span @class([
                                    'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest',
                                    'bg-amber-100 text-amber-600' => $contingent->status === 'pending',
                                    'bg-emerald-100 text-emerald-600' => $contingent->status === 'confirmed',
                                    'bg-red-100 text-red-600' => $contingent->status === 'rejected',
                                ])>
                                    {{ $contingent->status }}
                                </span>
                            </td>
                            <td class="py-4 px-2 text-right">
                                <a href="{{ route('admin.contingents.detail', $contingent) }}" class="text-orange-600 hover:text-orange-700 transition-colors font-bold text-xs uppercase tracking-widest">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-[#0f2b3d] text-white rounded-2xl p-8 relative overflow-hidden group shadow-xl">
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-blue-600/20 rounded-full blur-2xl group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10 flex flex-col h-full">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-info-circle text-xl text-orange-400"></i>
                </div>
                <h3 class="text-xl font-black mb-3 tracking-tight leading-tight">BUTUH BANTUAN?</h3>
                <p class="text-white/50 text-xs mb-8 leading-relaxed font-medium">Hubungi administrator teknis untuk pengaturan user, role, atau kendala sinkronisasi data.</p>
                <div class="mt-auto">
                    <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3.5 rounded-xl font-black text-[11px] uppercase tracking-widest transition-all active:scale-[0.98] shadow-lg shadow-orange-950/20">
                        Hubungi Support
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
