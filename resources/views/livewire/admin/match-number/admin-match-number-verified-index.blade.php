<div class="space-y-6">
    <!-- Header/Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-1">Daftar Peserta Per Nomor Pertandingan</h1>
            <p class="text-[15px] text-slate-900 font-medium italic">Menampilkan seluruh atlet dari pendaftaran yang telah <span class="text-emerald-600 font-bold uppercase">Terverifikasi</span></p>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-800 group-focus-within:text-orange-500 transition-colors">
                    <i class="fas fa-search text-[15px]"></i>
                </span>
                <input wire:model.live="search" type="text" placeholder="Cari nomor pertandingan..." 
                    class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-[15px] font-bold placeholder:text-slate-300 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all outline-none w-64 shadow-sm">
            </div>
            
            <button onclick="window.print()" class="w-10 h-10 bg-white rounded-xl border border-slate-100 flex items-center justify-center text-slate-800 hover:text-orange-600 hover:border-orange-100 transition-all shadow-sm">
                <i class="fas fa-print text-[15px]"></i>
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
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-[15px] font-black rounded-full uppercase tracking-widest border border-orange-200">
                                        {{ $item['match']->ageGroup?->name }}
                                    </span>
                                    <span class="px-3 py-1 bg-slate-100 text-slate-900 text-[15px] font-black rounded-full uppercase tracking-widest border border-slate-200">
                                        {{ $item['match']->draft_type }}
                                    </span>
                                    <span class="text-[15px] text-slate-800 font-bold uppercase tracking-wider ml-2">
                                        {{ strtoupper($item['match']->gender) }}
                                    </span>
                                    <span class="text-[15px] text-slate-300 font-bold uppercase ml-2">
                                        Quota: {{ $item['match']->max_athletes > 0 ? $item['match']->max_athletes : '∞' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-1">Total Atlet</p>
                            <p class="text-2xl font-black text-slate-800 italic leading-none">
                                {{ collect($item['contingents'])->sum(fn($c) => count($c['athletes'])) }}
                            </p>
                        </div>
                    </div>

                    <!-- Contingents Section -->
                    <div class="space-y-12">
                        @foreach($item['contingents'] as $contingent)
                            <div class="space-y-6">
                                <!-- Contingent Header -->
                                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-1.5 h-6 bg-orange-500 rounded-full"></div>
                                        <h4 class="text-[15px] font-black text-black uppercase tracking-tight">{{ $contingent['name'] }}</h4>
                                        <span class="text-[15px] bg-slate-100 text-slate-900 px-2 py-0.5 rounded-md font-bold">{{ count($contingent['athletes']) }} Atlet</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                                    <!-- Left Column: Athletes (8/12) -->
                                    <div class="lg:col-span-8">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($contingent['athletes'] as $athlete)
                                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-orange-200 hover:bg-white transition-all group/athlete shadow-sm hover:shadow-md">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex-1 min-w-0">
                                                            <h5 class="text-[15px] font-black text-slate-800 uppercase truncate leading-none mb-1 group-hover/athlete:text-orange-600 transition-colors">
                                                                {{ $athlete['name'] }}
                                                            </h5>
                                                            <p class="text-[15px] font-bold text-slate-800 font-mono tracking-wider mb-2">
                                                                {{ $athlete['nik'] }}
                                                            </p>
                                                            <div class="flex items-center gap-2">
                                                                <span class="px-1.5 py-0.5 bg-slate-900 text-white text-[15px] font-black rounded uppercase tracking-wider">
                                                                    {{ strtoupper($athlete['gender']) }}
                                                                </span>
                                                                <span class="text-[15px] font-bold text-slate-800 uppercase tracking-widest">
                                                                    {{ $athlete['age'] ?? '-' }} Thn
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="text-right ml-2 shrink-0">
                                                            <span class="text-[15px] font-black border-2 border-slate-900 text-slate-900 px-3 py-1 rounded-xl uppercase tracking-wider shadow-sm group-hover/athlete:bg-slate-900 group-hover/athlete:text-white transition-all">
                                                                {{ $athlete['rank'] ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-3">
                                                        <!-- Dojo Info -->
                                                        <!-- <div class="flex items-start gap-2 p-2 bg-white rounded-xl border border-slate-100 shadow-inner">
                                                            <i class="fas fa-home text-[15px] text-orange-500 mt-0.5"></i>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-[15px] font-black text-black uppercase leading-none mb-1">{{ $athlete['dojo'] ?? 'Dojo -' }}</p>
                                                                <p class="text-[15px] font-bold text-slate-800 uppercase tracking-wider">{{ $athlete['city'] ?? 'Kota -' }}</p>
                                                            </div>
                                                        </div> -->

                                                        <!-- Physical/Level Details -->
                                                        <div class="flex items-center justify-between pt-1">
                                                            <div class="flex items-center gap-4 text-[15px] font-bold text-slate-900 uppercase">
                                                                <span class="flex items-center gap-1.5"><i class="fas fa-weight-hanging text-[15px] text-slate-300"></i> {{ $athlete['weight'] ?? '-' }} kg</span>
                                                                <span class="flex items-center gap-1.5"><i class="fas fa-certificate text-[15px] text-slate-300"></i> {{ $athlete['kyu'] ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Right Column: Techniques (4/12) -->
                                    <div class="lg:col-span-4 sticky top-32">
                                        <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group/tech">
                                            <div class="absolute top-0 right-0 w-24 h-24 bg-orange-500/5 rounded-full -mr-12 -mt-12 blur-2xl group-hover/tech:bg-orange-500/10 transition-colors"></div>
                                            
                                            <div class="relative z-10">
                                                <div class="flex items-center gap-2 mb-4">
                                                    <i class="fas fa-scroll text-orange-500 text-[15px]"></i>
                                                    <h5 class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Daftar Teknik</h5>
                                                </div>
                                                
                                                <div class="space-y-2">
                                                    @forelse($contingent['techniques'] as $index => $tech)
                                                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 group-hover/tech:border-orange-100 transition-all">
                                                            <span class="w-5 h-5 bg-white rounded-md flex items-center justify-center text-[15px] font-black text-orange-600 border border-orange-100 shadow-sm">
                                                                {{ $index + 1 }}
                                                            </span>
                                                            <span class="text-[15px] font-black text-black uppercase tracking-tight">{{ $tech }}</span>
                                                        </div>
                                                    @empty
                                                        <div class="py-4 text-center">
                                                            <p class="text-[15px] text-slate-800 italic font-bold">Tanpa Teknik Khusus</p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                <h3 class="text-[15px] font-black text-slate-800 uppercase mb-2">Tidak ada data ditemukan</h3>
                <p class="text-[15px] text-slate-800 font-medium italic">Belum ada atlet dari pendaftaran terverifikasi untuk nomor pertandingan ini.</p>
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
