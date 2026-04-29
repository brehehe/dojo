<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Ranking Konsistensi Wasit (IK)</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Evaluasi Kestabilan Penilaian Berdasarkan Deviasi Standar</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportExcel"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button onclick="window.print()"
                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Ranking
            </button>
        </div>
    </div>
    
    <div class="bg-emerald-900 p-6 rounded-3xl shadow-xl shadow-emerald-100 relative overflow-hidden print:hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <h3 class="text-sm font-black text-emerald-300 uppercase tracking-widest mb-2">Apa itu IK?</h3>
                <p class="text-white text-sm font-bold leading-relaxed opacity-90">
                    Indeks Konsistensi (IK) mengukur seberapa stabil seorang juri dalam memberikan nilai di setiap pertandingan. Semakin rendah variasi (deviasi) nilai yang diberikan relatif terhadap rata-ratanya, semakin tinggi tingkat konsistensinya.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-center bg-white/10 px-4 py-3 rounded-2xl backdrop-blur-sm border border-white/10">
                    <div class="text-[10px] font-black text-emerald-300 uppercase">Rumus Konsistensi</div>
                    <div class="text-lg font-black text-white">1 - (σ / μ)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Referee Full Analysis Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden print:shadow-none">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i class="fas fa-sync text-slate-800"></i>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tabel Ranking Konsistensi (IK)</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Wasit</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-emerald-50/30">IK</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($refereeAnalysis->sortByDesc('ik') as $rf)
                        @php
                            $catColor = match ($rf['ik_category']) {
                                'Sangat Konsisten' => 'bg-emerald-100 text-emerald-700',
                                'Konsisten' => 'bg-blue-100 text-blue-700',
                                'Cukup Konsisten' => 'bg-amber-100 text-amber-700',
                                default => 'bg-rose-100 text-rose-700',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-800 uppercase">{{ $rf['name'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-500">
                                {{ $rf['count'] }}
                            </td>
                            <td class="px-6 py-4 text-center bg-emerald-50/10 font-black text-emerald-600 text-lg">
                                {{ number_format($rf['ik'], 3) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $catColor }}">
                                    {{ $rf['ik_category'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
