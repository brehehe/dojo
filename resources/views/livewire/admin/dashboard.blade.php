<div class="space-y-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Ringkasan Admin</h1>
            <p class="text-slate-500">Monitor perkembangan pendaftaran Smart-Perkemi</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white rounded-2xl px-6 py-3 shadow-sm border border-slate-100 flex items-center gap-3">
                <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="font-bold text-slate-700 uppercase text-xs tracking-widest">Sistem Online</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-orange-200 text-3xl"></i>
            </div>
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Total Kontingen</p>
            <div class="text-4xl font-extrabold text-slate-800">{{ number_format($stats['total_contingents']) }}</div>
            <div class="mt-4 text-xs text-slate-400 font-bold uppercase tracking-widest">PERKEMI KAB/KOTA</div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-id-card text-blue-200 text-3xl"></i>
            </div>
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Total Atlet</p>
            <div class="text-4xl font-extrabold text-blue-600">{{ number_format($stats['total_athletes']) }}</div>
            <div class="mt-4 text-xs text-blue-400 font-bold uppercase tracking-widest">TERDAFTAR DI SISTEM</div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-clock text-amber-200 text-3xl"></i>
            </div>
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Menunggu Verifikasi</p>
            <div class="text-4xl font-extrabold text-amber-600">{{ number_format($stats['pending_verifications']) }}</div>
            <div class="mt-4 text-xs text-amber-400 font-bold uppercase tracking-widest">PEMBAYARAN PENDING</div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-wallet text-emerald-200 text-3xl"></i>
            </div>
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Total Dana Masuk</p>
            <div class="text-2xl font-extrabold text-emerald-600">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
            <div class="mt-4 text-xs text-emerald-400 font-bold uppercase tracking-widest">STATUS CONFIRMED</div>
        </div>
    </div>

    <!-- Quick Actions / Table Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-md border border-slate-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">KONTINGEN TERBARU</h3>
                <a href="{{ route('admin.contingents.index') }}" class="text-orange-600 font-bold text-xs uppercase tracking-widest hover:underline">Lihat Semua</a>
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

        <div class="bg-[#0f2b3d] text-white rounded-[2.5rem] p-10 relative overflow-hidden group shadow-2xl">
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-orange-600/20 rounded-full blur-2xl group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10 flex flex-col h-full">
                <i class="fas fa-info-circle text-3xl text-orange-400 mb-6"></i>
                <h3 class="text-2xl font-extrabold mb-4 tracking-tight leading-tight">BUTUH BANTUAN SISTEM?</h3>
                <p class="text-white/60 text-sm mb-10 leading-relaxed">Silakan hubungi administrator teknis untuk pengaturan user, role, atau jika terjadi kendala pada sinkronisasi data.</p>
                <div class="mt-auto">
                    <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-4 rounded-2xl font-bold transition-all active:scale-95 shadow-xl shadow-orange-950">
                        Hubungi Support
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
