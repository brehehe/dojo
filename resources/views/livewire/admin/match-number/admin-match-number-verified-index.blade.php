<div class="space-y-6">
    <!-- Header/Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-1">Daftar Peserta Per Nomor Pertandingan</h1>
            <p class="text-xs text-slate-500 font-medium italic">Menampilkan seluruh atlet dari pendaftaran yang telah <span class="text-emerald-600 font-bold uppercase">Terverifikasi</span></p>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input wire:model.live="search" type="text" placeholder="Cari nomor pertandingan..." 
                    class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-xs font-bold placeholder:text-slate-300 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all outline-none w-64 shadow-sm">
            </div>
            
            <button onclick="window.print()" class="w-10 h-10 bg-white rounded-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:border-orange-100 transition-all shadow-sm">
                <i class="fas fa-print text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Grouped Content -->
    <div class="space-y-10">
        @forelse($groupedData as $item)
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                <!-- Decorative Background -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/5 rounded-full -mr-32 -mt-32 blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="relative z-10">
                    <!-- Match Header -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-slate-900 text-white rounded-[1.25rem] flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                <i class="fas fa-medal text-xl text-orange-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight leading-none mb-2">
                                    {{ $item['match']->name }}
                                </h3>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-[9px] font-black rounded-full uppercase tracking-widest border border-orange-200">
                                        {{ $item['match']->ageGroup?->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                        {{ $item['match']->gender === 'male' ? 'PUTRA' : 'PUTRI' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Atlet</p>
                            <p class="text-2xl font-black text-slate-800 italic leading-none">
                                {{ collect($item['contingents'])->sum(fn($c) => count($c['athletes'])) }}
                            </p>
                        </div>
                    </div>

                    <!-- Contingents Section -->
                    <div class="grid grid-cols-1 gap-8">
                        @foreach($item['contingents'] as $contingent)
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-orange-500 rounded-full"></div>
                                    <h4 class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ $contingent['name'] }}</h4>
                                    <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold">{{ count($contingent['athletes']) }} Atlet</span>
                                    
                                    <!-- Techniques at Contingent Level -->
                                    <div class="flex flex-wrap gap-1.5 ml-4">
                                        @forelse($contingent['techniques'] as $tech)
                                            <span class="px-2 py-1 bg-white text-orange-600 text-[8px] font-black rounded-lg uppercase border border-orange-100 shadow-sm">
                                                {{ $tech }}
                                            </span>
                                        @empty
                                            <span class="text-[8px] text-slate-400 italic">Tanpa Teknik Khusus</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pl-4">
                                    @foreach($contingent['athletes'] as $athlete)
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-orange-200 hover:bg-white transition-all group/athlete shadow-sm hover:shadow-md">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="text-xs font-black text-slate-800 uppercase truncate leading-none mb-1 group-hover/athlete:text-orange-600 transition-colors">
                                                        {{ $athlete['name'] }}
                                                    </h5>
                                                    <p class="text-[9px] font-bold text-slate-400 font-mono tracking-wider">
                                                        {{ $athlete['nik'] }}
                                                    </p>
                                                </div>
                                                <div class="text-right ml-2 shrink-0">
                                                    <span class="text-[9px] font-black bg-slate-900 text-white px-2 py-1 rounded-lg uppercase tracking-wider shadow-sm">
                                                        {{ $athlete['rank'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Details -->
                                            <div class="flex items-center gap-3 text-[9px] font-bold text-slate-500 uppercase">
                                                <span class="flex items-center gap-1"><i class="fas fa-weight-hanging text-[8px]"></i> {{ $athlete['weight'] ?? '-' }} kg</span>
                                                <span class="flex items-center gap-1"><i class="fas fa-certificate text-[8px]"></i> {{ $athlete['kyu'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-100 shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-6">
                    <i class="fas fa-layer-group text-3xl"></i>
                </div>
                <h3 class="text-sm font-black text-slate-800 uppercase mb-2">Tidak ada data ditemukan</h3>
                <p class="text-xs text-slate-400 font-medium italic">Belum ada atlet dari pendaftaran terverifikasi untuk nomor pertandingan ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            header, nav, .sticky, button, input { display: none !important; }
            .bg-slate-50 { background-color: transparent !important; }
            .shadow-sm, .shadow-lg, .shadow-xl { shadow: none !important; }
            .border { border: 1px solid #e2e8f0 !important; }
            .rounded-[2.5rem], .rounded-[1.25rem], .rounded-2xl { border-radius: 0.5rem !important; }
            .p-8 { padding: 1rem !important; }
            .space-y-10 > * + * { margin-top: 2rem !important; }
            body { font-size: 10pt; }
            .grid { display: block !important; }
            .grid-cols-1, .grid-cols-2, .grid-cols-3 { grid-template-columns: none !important; }
            .pl-4 { padding-left: 0 !important; }
            .p-4 { padding: 0.5rem 0 !important; border: none !important; border-bottom: 1px dashed #eee !important; }
            .flex-row { flex-direction: row !important; }
        }
    </style>
</div>
