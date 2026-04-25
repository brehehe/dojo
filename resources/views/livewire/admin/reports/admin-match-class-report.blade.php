<div class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="mb-6 text-center lg:text-left">
        <div class="flex flex-col lg:flex-row items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-orange-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200 transform transition-transform hover:scale-110">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Laporan Nomor & Kelas</h1>
                <p class="text-slate-900 font-bold text-[15px] uppercase tracking-widest">Match Numbers & Class Composition</p>
            </div>
        </div>
    </div>

    <!-- Filter & Preview Card -->
    <div class="max-w-full mx-auto pb-12">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 p-6 lg:p-10 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/5 rounded-full -mr-32 -mt-32 blur-3xl group-hover:bg-orange-500/10 transition-colors duration-700"></div>
            
            <div class="relative">
                <div class="grid grid-cols-1 gap-10">
                    <!-- Left: Control -->
                    <div class=" lg:border-r lg:border-slate-100 lg:pr-10">
                        <div class="mb-10">
                            <h2 class="text-xl font-black text-slate-800 mb-2 uppercase tracking-tight">Kriteria Laporan</h2>
                            <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest leading-relaxed">
                                Silahkan pilih kontingen untuk memuat daftar nomor pertandingan dan komposisi tekniknya.
                            </p>
                        </div>

                        <div class="space-y-8">
                            <!-- Contingent Selection -->
                            <div>
                                <label class="block text-[15px] font-black text-slate-800 uppercase tracking-widest mb-3 ml-1">Kontingen / Dojo</label>
                                <select wire:model.live="contingentId" 
                                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-[15px] font-black text-black focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none cursor-pointer">
                                    <option value="">-- Pilih Kontingen --</option>
                                    @foreach($contingents as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($contingentId)
                                <button wire:click="download" 
                                        class="w-full bg-slate-900 hover:bg-orange-600 text-white font-black py-5 rounded-2xl flex items-center justify-center gap-4 transition-all duration-500 shadow-xl shadow-slate-900/20 active:scale-95 group/btn">
                                    <i class="fas fa-file-excel text-orange-400 group-hover/btn:text-white transition-colors"></i>
                                    DOWNLOAD EXCEL
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Preview -->
                    <div class="">
                        @if($contingentId)
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Daftar Pertandingan</h3>
                                    <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest italic pr-12">Menampilkan {{ count($matchGroups) }} nomor pertandingan yang diikuti.</p>
                                </div>
                                <div class="relative w-full md:w-64 group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-800">
                                        <i class="fas fa-search text-[15px]"></i>
                                    </span>
                                    <input type="text" wire:model.live.debounce.300ms="search" 
                                           placeholder="Cari pertandingan/atlet..." 
                                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[15px] font-black focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                                </div>
                            </div>

                            <div class="space-y-6">
                                @forelse($matchGroups as $group)
                                    <div class="p-6 bg-white rounded-[2rem] border border-slate-100 relative group transition-all hover:shadow-xl hover:shadow-slate-100 duration-500 overflow-hidden">

                                        <!-- Match Header -->
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-100">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-orange-500 text-white rounded-2xl flex items-center justify-center shadow-lg transform group-hover:rotate-6 transition-all duration-500">
                                                    <i class="fas fa-medal text-lg"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-base font-black text-slate-800 uppercase tracking-tight leading-none mb-1">
                                                        {{ $group['match_name'] }}
                                                    </h4>
                                                    <span class="text-[13px] font-bold text-slate-400 uppercase tracking-widest">
                                                        {{ count($group['athletes']) }} Peserta
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
                                                            <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Tingkat</th>
                                                            <th class="px-4 py-3 text-[13px] font-black uppercase tracking-widest border border-slate-700">Urutan Komposisi Teknik</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $athletes   = $group['athletes'];
                                                            $techniques = $group['techniques'] ?? [];
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
                                                                        <span class="text-[14px] font-black text-slate-800 uppercase">{{ $athletes[$i]->athlete_name }}</span>
                                                                    @endif
                                                                </td>
                                                                <!-- Tingkat -->
                                                                <td class="py-3 px-4 border border-slate-200 text-center">
                                                                    @if($i < count($athletes))
                                                                        <span class="text-[13px] font-black border border-slate-800 text-slate-800 px-2.5 py-0.5 rounded-lg uppercase tracking-wide">{{ $athletes[$i]->tingkat ?: 'X Kyu' }}</span>
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
                                @empty
                                    <div class="h-[400px] flex flex-col items-center justify-center p-20 bg-slate-50 rounded-[3rem] border-4 border-dashed border-slate-100 italic text-slate-300">
                                        <i class="fas fa-layer-group text-6xl mb-6 opacity-20"></i>
                                        <p class="text-[15px] font-black uppercase tracking-widest">Data tidak ditemukan</p>
                                    </div>
                                @endforelse
                            </div>

                        @else
                            <div class="h-[600px] flex flex-col items-center justify-center p-20 bg-slate-50 rounded-[3.5rem] border-4 border-dashed border-slate-100">
                                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-xl shadow-slate-200/50 mb-8 border border-slate-100 transform transition-transform hover:scale-110">
                                    <i class="fas fa-layer-group text-4xl text-slate-200"></i>
                                </div>
                                <h3 class="text-base font-black text-slate-300 uppercase tracking-widest mb-2 text-center">Pratinjau Nomor & Kelas</h3>
                                <p class="text-[15px] text-slate-300 font-bold uppercase tracking-tight text-center max-w-xs px-10 leading-relaxed">
                                    Silahkan pilih kontingen untuk mendata nomor pertandingan dan teknik yang dimainkan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
