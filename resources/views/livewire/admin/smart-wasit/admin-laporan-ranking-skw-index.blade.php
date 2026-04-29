<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Ranking Skor Kompetensi Wasit (SKW)</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Evaluasi Menyeluruh Performa Wasit Berdasarkan SKW</p>
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
    
    {{-- Formula Explanation (Legend) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 print:hidden">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-bullseye text-xs"></i>
                </div>
                <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-wider">IAW (Akurasi)</h4>
            </div>
            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                <span class="font-bold text-slate-700">(μ Wasit / μ Referensi) × 100%</span>. Kedekatan nilai terhadap rata-rata juri.
            </p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-sync text-xs"></i>
                </div>
                <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-wider">IK (Konsistensi)</h4>
            </div>
            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                <span class="font-bold text-slate-700">1 - (σ / μ)</span>. Kestabilan penilaian. Semakin mendekati 100%, semakin konsisten.
            </p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                    <i class="fas fa-check-double text-xs"></i>
                </div>
                <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-wider">IV (Validitas)</h4>
            </div>
            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                <span class="font-bold text-slate-700">Pearson Correlation</span>. Kevalidan penilaian terhadap pola nilai standar.
            </p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-slate-900">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white">
                    <i class="fas fa-star text-xs"></i>
                </div>
                <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-wider">SKW (Kompetensi)</h4>
            </div>
            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                <span class="font-bold text-slate-700">Weighted Score</span>. Bobot: Akurasi 40%, Konsistensi 35%, Validitas 25%.
            </p>
        </div>
    </div>

    {{-- Referee Full Analysis Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden print:shadow-none">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-trophy text-slate-800"></i>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Ranking Wasit Berdasarkan SKW</h3>
            </div>
            <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> A: Sangat Baik</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> B: Baik</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> C: Cukup</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-500"></span> D: Bimbingan</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> E: Kurang</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 w-16">Rank</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Wasit</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Court</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-indigo-50/30">IAW (%)</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-emerald-50/30">IK</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-orange-50/30">IV</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-slate-900 text-white font-black">SKW</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $rank = 1; @endphp
                    @foreach($refereeAnalysis->sortByDesc('skw') as $rf)
                        @php
                            $gradeColor = match($rf['grade']) {
                                'A' => 'bg-emerald-500 text-white',
                                'B' => 'bg-blue-500 text-white',
                                'C' => 'bg-amber-500 text-white',
                                'D' => 'bg-orange-500 text-white',
                                'E' => 'bg-rose-500 text-white',
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
                            <td class="px-6 py-4 text-center bg-indigo-50/10 font-black text-indigo-600">
                                {{ number_format($rf['iaw'], 2) }}%
                            </td>
                            <td class="px-6 py-4 text-center bg-emerald-50/10 font-black text-emerald-600">
                                {{ number_format($rf['ik'], 3) }}
                            </td>
                            <td class="px-6 py-4 text-center bg-orange-50/10 font-black text-orange-600">
                                {{ number_format($rf['iv'], 3) }}
                            </td>
                            <td class="px-6 py-4 text-center bg-slate-800 font-black text-white text-lg">
                                {{ number_format($rf['skw'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $gradeColor }}">
                                        {{ $rf['grade'] }}
                                    </span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">{{ $rf['grade_label'] }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
