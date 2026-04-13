<div class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="mb-12">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-orange-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200">
                <i class="fas fa-file-excel text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Laporan Per Kontingen</h1>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Registration by Number (Tahap Pertama)</p>
            </div>
        </div>
    </div>

    <!-- Filter & Preview Card -->
    <div class="max-w-full mx-auto">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 p-10 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/5 rounded-full -mr-32 -mt-32 blur-3xl group-hover:bg-orange-500/10 transition-colors duration-700"></div>
            
            <div class="relative">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Left: Control -->
                    <div class="lg:col-span-4 border-r border-slate-100 lg:pr-10">
                        <div class="mb-10">
                            <h2 class="text-xl font-black text-slate-800 mb-2 uppercase tracking-tight">Pilih Kontingen</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">
                                Silahkan pilih kontingen untuk mengunduh laporan checklist nomor pertandingan (Format Excel).
                            </p>
                        </div>

                        <div class="space-y-8">
                            <!-- Contingent Selection -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Kontingen / Dojo</label>
                                <select wire:model.live="contingentId" 
                                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-sm font-black text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none cursor-pointer">
                                    <option value="">-- Pilih Kontingen --</option>
                                    @foreach($contingents as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->kab_kota ?? 'Surabaya' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($contingentId && $stats)
                                <div class="p-6 bg-slate-900 rounded-3xl text-white shadow-xl shadow-slate-900/20">
                                    <h4 class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-4">Statistik Kontingen</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase">Total Atlet</span>
                                            <span class="text-sm font-black">{{ $stats['totalAthletes'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase">Total Ofisial</span>
                                            <span class="text-sm font-black">{{ $stats['totalOfficials'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                            <span class="text-[9px] font-bold text-orange-400 uppercase">Nomor Diikuti</span>
                                            <span class="text-sm font-black text-orange-400">{{ $stats['totalFollowed'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <button wire:click="download" 
                                        wire:loading.attr="disabled"
                                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-black py-5 rounded-2xl flex items-center justify-center gap-4 transition-all duration-500 shadow-xl shadow-orange-600/30 group/btn active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove>
                                        <i class="fas fa-file-excel text-white/50 group-hover/btn:text-white transition-colors"></i>
                                        UNDUH EXCEL
                                    </span>
                                    <span wire:loading class="flex items-center gap-3">
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                        PROSES...
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Preview -->
                    <div class="lg:col-span-8">
                        @if($contingentId && $stats)
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Checklist Preview</h3>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Daftar Kategori Lengkap</p>
                                </div>
                                <div class="relative w-full md:w-64 group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="fas fa-search text-xs"></i>
                                    </span>
                                    <input type="text" wire:model.live.debounce.300ms="search" 
                                           placeholder="Cari kategori..." 
                                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-black focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-3xl border border-slate-100 overflow-hidden">
                                <div class="max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="sticky top-0 z-10">
                                            <tr class="bg-slate-900 text-white font-black uppercase text-[9px] tracking-widest">
                                                <th class="px-6 py-4 text-center w-16">No</th>
                                                <th class="px-4 py-4">Kategori</th>
                                                <th class="px-4 py-4 text-center w-16">Ya</th>
                                                <th class="px-4 py-4 text-center w-16">No</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            @php
                                                $genderMap = ['Male' => 'Kelompok Putra', 'Female' => 'Kelompok Putri', 'Mix' => 'Kelompok Campuran'];
                                            @endphp

                                            @foreach($genderMap as $genderKey => $genderLabel)
                                                @if(isset($categories[$genderKey]))
                                                    <tr class="bg-slate-100/50">
                                                        <td colspan="4" class="px-6 py-2.5 text-[10px] font-black text-slate-800 uppercase tracking-tight border-y border-slate-100">
                                                            {{ $genderLabel }}
                                                        </td>
                                                    </tr>

                                                    @php $groupedByAge = $categories[$genderKey]->groupBy('age_group_id'); @endphp

                                                    @foreach($groupedByAge as $ageId => $matches)
                                                        <tr class="bg-slate-50/30">
                                                            <td colspan="4" class="px-8 py-2 text-[9px] font-black text-orange-600 uppercase tracking-widest italic font-serif">
                                                                {{ $ageGroups[$ageId]?->name ?? 'N/A' }}
                                                            </td>
                                                        </tr>
                                                        @foreach($matches as $index => $match)
                                                            @php $isFollowed = in_array($match->id, $stats['followedMatchNumberIds']); @endphp
                                                            <tr class="hover:bg-orange-50/30 transition-colors">
                                                                <td class="px-6 py-3 text-center text-[9px] font-bold text-slate-400 font-mono">{{ $index + 1 }}</td>
                                                                <td class="px-4 py-3">
                                                                    <p class="text-[10px] font-black text-slate-700 uppercase leading-none">{{ $match->name }}</p>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    @if($isFollowed)
                                                                        <div class="w-6 h-6 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mx-auto shadow-sm">
                                                                            <i class="fas fa-check text-[9px]"></i>
                                                                        </div>
                                                                    @else
                                                                        <div class="w-6 h-6 rounded-lg border border-dashed border-slate-200 flex items-center justify-center mx-auto"></div>
                                                                    @endif
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    @if(!$isFollowed)
                                                                        <div class="w-6 h-6 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mx-auto shadow-sm">
                                                                            <i class="fas fa-times text-[9px]"></i>
                                                                        </div>
                                                                    @else
                                                                        <div class="w-6 h-6 rounded-lg border border-dashed border-slate-200 flex items-center justify-center mx-auto"></div>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="h-full flex flex-col items-center justify-center p-20 bg-slate-50 rounded-[3rem] border-4 border-dashed border-slate-100">
                                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg mb-8 text-slate-200">
                                    <i class="fas fa-mouse-pointer text-4xl"></i>
                                </div>
                                <h3 class="text-sm font-black text-slate-300 uppercase tracking-widest mb-2 text-center">Menunggu Pilihan</h3>
                                <p class="text-[10px] text-slate-300 font-bold uppercase tracking-tight text-center max-w-xs">
                                    Pilih salah satu kontingen di samping untuk memuat pratinjau checklist laporan nomor pertandingan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
