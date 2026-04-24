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
                    <div class="space-y-6">
                        @foreach($item['contingents'] as $contingent)
                            <div class="p-6 bg-white rounded-[2rem] border border-slate-100 relative group transition-all hover:shadow-xl hover:shadow-slate-100 duration-500 overflow-hidden">

                                <!-- Contingent Header -->
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-orange-500 text-white rounded-2xl flex items-center justify-center shadow-lg transform group-hover:rotate-6 transition-all duration-500">
                                            <i class="fas fa-users text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-base font-black text-slate-800 uppercase tracking-tight leading-none mb-1">
                                                {{ $contingent['name'] }}
                                            </h4>
                                            <span class="text-[13px] font-bold text-slate-400 uppercase tracking-widest">
                                                {{ count($contingent['athletes']) }} Atlet Terdaftar
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table -->
                                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-left border-collapse border border-slate-200 overflow-hidden">
                                            <thead class="bg-slate-800 text-white">
                                                <tr>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[1%]">No.</th>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Nama Atlet</th>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">NIK</th>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">L/P</th>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Usia</th>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Tingkat</th>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">BB (kg)</th>
                                                    <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700">Urutan Teknik / Komposisi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $athletes   = $contingent['athletes'];
                                                    $techniques = $contingent['techniques'] ?? [];
                                                    $rowCount   = max(count($athletes), count($techniques));
                                                @endphp
                                                @for($i = 0; $i < $rowCount; $i++)
                                                    <tr class="{{ $loop->even ? 'bg-slate-50' : 'bg-white' }} hover:bg-orange-50/30 transition-colors">
                                                        <!-- No -->
                                                        <td class="py-3 px-4 border border-slate-200 text-center">
                                                            @if($i < count($athletes))
                                                                <span class="w-7 h-7 inline-flex items-center justify-center bg-slate-800 text-white rounded-lg text-[13px] font-black">{{ $i + 1 }}</span>
                                                            @endif
                                                        </td>
                                                        <!-- Nama -->
                                                        <td class="py-3 px-4 border border-slate-200">
                                                            @if($i < count($athletes))
                                                                <span class="text-[14px] font-black text-slate-800 uppercase">{{ $athletes[$i]['name'] }}</span>
                                                            @endif
                                                        </td>
                                                        <!-- NIK -->
                                                        <td class="py-3 px-4 border border-slate-200">
                                                            @if($i < count($athletes))
                                                                <span class="text-[13px] font-mono font-bold text-slate-500 tracking-wider">{{ $athletes[$i]['nik'] }}</span>
                                                            @endif
                                                        </td>
                                                        <!-- Gender -->
                                                        <td class="py-3 px-4 border border-slate-200 text-center">
                                                            @if($i < count($athletes))
                                                                <span class="px-2 py-0.5 bg-slate-900 text-white text-[11px] font-black rounded uppercase">{{ strtoupper($athletes[$i]['gender']) }}</span>
                                                            @endif
                                                        </td>
                                                        <!-- Usia -->
                                                        <td class="py-3 px-4 border border-slate-200 text-center">
                                                            @if($i < count($athletes))
                                                                <span class="text-[14px] font-bold text-slate-700">{{ $athletes[$i]['age'] ?? '-' }} Thn</span>
                                                            @endif
                                                        </td>
                                                        <!-- Tingkat/Rank -->
                                                        <td class="py-3 px-4 border border-slate-200 text-center">
                                                            @if($i < count($athletes))
                                                                <span class="text-[13px] font-black border border-slate-800 text-slate-800 px-2.5 py-0.5 rounded-lg uppercase tracking-wide">{{ $athletes[$i]['rank'] ?? 'N/A' }}</span>
                                                            @endif
                                                        </td>
                                                        <!-- Berat Badan -->
                                                        <td class="py-3 px-4 border border-slate-200 text-center">
                                                            @if($i < count($athletes))
                                                                <span class="text-[14px] font-bold text-slate-700">{{ $athletes[$i]['weight'] ?? '-' }}</span>
                                                            @endif
                                                        </td>
                                                        <!-- Teknik -->
                                                        <td class="py-3 px-4 border border-slate-200">
                                                            @if($i < count($techniques))
                                                                <div class="flex items-center gap-2">
                                                                    <span class="w-5 h-5 inline-flex items-center justify-center bg-orange-100 text-orange-600 rounded-md text-[11px] font-black border border-orange-200 shrink-0">{{ $i + 1 }}</span>
                                                                    <span class="text-[14px] font-black text-slate-800 uppercase tracking-tight">{{ $techniques[$i] }}</span>
                                                                </div>
                                                            @else
                                                                <span class="text-[13px] text-slate-300 italic">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
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
