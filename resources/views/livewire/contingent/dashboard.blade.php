<div class="space-y-6 animate-in fade-in duration-700">
    <!-- Header Summary -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight uppercase">Dashboard Kontingen</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-500 font-bold uppercase tracking-wider italic">{{ $contingent->name }} — {{ $contingent->kab_kota }}</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <a href="/piala_walikotasby2026" class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-3 active:scale-95">
                <i class="fas fa-file-signature text-sm"></i>
                <span class="uppercase text-[15px] tracking-widest">Buka Registrasi Baru</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                <i class="fas fa-trophy text-xl"></i>
            </div>
            <div>
                <span class="text-[15px] font-black uppercase tracking-widest text-slate-400">Total Pendaftaran</span>
                <p class="text-2xl font-black text-slate-800 leading-tight">{{ $registrations->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <span class="text-[15px] font-black uppercase tracking-widest text-slate-400">Total Atlet</span>
                <p class="text-2xl font-black text-slate-800 leading-tight">{{ $registrations->sum('athletes_count') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl transition-all duration-300">
            <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                <i class="fas fa-user-tie text-xl"></i>
            </div>
            <div>
                <span class="text-[15px] font-black uppercase tracking-widest text-slate-400">Total Official</span>
                <p class="text-2xl font-black text-slate-800 leading-tight">{{ $registrations->sum('officials_count') }}</p>
            </div>
        </div>
    </div>

    <!-- Active Registrations Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Riwayat Pendaftaran Turnamen</h3>
            <span class="px-3 py-1 bg-slate-100 rounded-full text-[15px] font-black text-slate-400 tracking-tighter uppercase">Last Updates: {{ now()->format('d M Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800">
                        <th class="py-4 px-8 text-slate-400 text-[15px] font-black uppercase tracking-[0.2em]">Info Pendaftaran</th>
                        <th class="py-4 px-8 text-slate-400 text-[15px] font-black uppercase tracking-[0.2em] text-center">Partisipan</th>
                        <th class="py-4 px-8 text-slate-400 text-[15px] font-black uppercase tracking-[0.2em] text-center">Status</th>
                        <th class="py-4 px-8 text-slate-400 text-[15px] font-black uppercase tracking-[0.2em] text-right">Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($registrations as $reg)
                        <tr class="group hover:bg-slate-50/50 transition-colors cursor-pointer">
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 font-black group-hover:bg-orange-600 group-hover:text-white transition-all">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm tracking-tight">{{ $reg->referral_code }}</p>
                                        <p class="text-[15px] text-slate-400 font-semibold">{{ $reg->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs font-black text-slate-700">{{ $reg->athletes_count }} <span class="text-[15px] text-slate-400 lowercase">atlets</span></span>
                                    <span class="text-xs font-black text-slate-700">{{ $reg->officials_count }} <span class="text-[15px] text-slate-400 lowercase">officials</span></span>
                                </div>
                            </td>
                            <td class="py-5 px-8 text-center text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'verified' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'rejected' => 'bg-red-50 text-red-600 border-red-100',
                                    ][$reg->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[15px] font-black uppercase tracking-widest border {{ $statusClasses }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <p class="text-sm font-black text-slate-800 tracking-tighter">Rp {{ number_format($reg->final_amount, 0, ',', '.') }}</p>
                                <p class="text-[15px] text-slate-400 font-bold uppercase tracking-widest">{{ $reg->payment_method }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                        <i class="fas fa-file-invoice text-3xl"></i>
                                    </div>
                                    <p class="font-black text-slate-400 uppercase tracking-widest text-[15px]">Belum ada pendaftaran turnamen</p>
                                    <a href="/piala_walikotasby2026" class="text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors">Buka Formulir Sekarang &rarr;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
