<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Ranking Indeks Variasi (IV)</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Ranking Validitas Penilaian Wasit Terhadap Pola Standar</p>
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
    
    {{-- Info Card --}}
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-6 text-white shadow-lg shadow-orange-500/20 print:hidden">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-xl font-black uppercase tracking-tight">Apa itu Indeks Variasi (IV)?</h3>
                <p class="text-orange-50 mt-1 font-medium leading-relaxed">
                    Indeks Variasi mengukur validitas penilaian seorang wasit menggunakan korelasi Pearson. 
                    Semakin tinggi nilai IV (mendekati 1.000), pola penilaian wasit semakin sesuai dengan pola nilai rata-rata (standar).
                </p>
            </div>
            <div class="flex gap-4">
                <div class="px-4 py-2 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 text-center">
                    <div class="text-[10px] font-bold uppercase opacity-60">Sangat Tinggi</div>
                    <div class="text-lg font-black">≥ 0.800</div>
                </div>
                <div class="px-4 py-2 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 text-center">
                    <div class="text-[10px] font-bold uppercase opacity-60">Tinggi</div>
                    <div class="text-lg font-black">≥ 0.600</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Referee IV Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden print:shadow-none">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-list-ol text-slate-800"></i>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Ranking Wasit Berdasarkan IV</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 w-16">Rank</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Wasit</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Court Utama</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Sampel Skor</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-orange-50/30">Skor IV</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Interpretasi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $rank = 1; @endphp
                    @foreach($refereeAnalysis->sortByDesc('iv') as $rf)
                        @php
                            $statusColor = match($rf['iv_category']) {
                                'Sangat Baik' => 'bg-emerald-500 text-white',
                                'Baik' => 'bg-blue-500 text-white',
                                'Cukup' => 'bg-amber-500 text-white',
                                'Perlu Perbaikan' => 'bg-rose-500 text-white',
                                default => 'bg-slate-500 text-white',
                            };
                            $rankBg = match($rank) {
                                1 => 'bg-amber-100 text-amber-600',
                                2 => 'bg-slate-100 text-slate-600',
                                3 => 'bg-orange-100 text-orange-600',
                                default => 'bg-slate-50 text-slate-400',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-lg {{ $rankBg }} flex items-center justify-center font-black text-sm">
                                    {{ $rank++ }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-800 uppercase">{{ $rf['name'] }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-[9px] font-black uppercase tracking-widest">
                                    {{ $rf['primary_court'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-500">
                                {{ $rf['count'] }}
                            </td>
                            <td class="px-6 py-4 text-center bg-orange-50/10">
                                <span class="text-lg font-black text-orange-600">{{ number_format($rf['iv'], 3) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] font-bold text-slate-600 uppercase">{{ $rf['iv_interpretation'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $statusColor }}">
                                    {{ $rf['iv_category'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
