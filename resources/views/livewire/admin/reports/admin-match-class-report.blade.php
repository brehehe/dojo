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
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Left: Control -->
                    <div class="lg:col-span-4 lg:border-r lg:border-slate-100 lg:pr-10">
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
                    <div class="lg:col-span-8">
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

                            <div class="space-y-12">
                                @forelse($matchGroups as $group)
                                    <div class="bg-slate-50 rounded-[2rem] border border-slate-100 p-8 hover:bg-white hover:shadow-2xl hover:shadow-slate-100 transition-all duration-500">
                                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-200/50">
                                            <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black text-[15px]">
                                                <i class="fas fa-medal"></i>
                                            </div>
                                            <h4 class="text-[15px] font-black text-slate-800 uppercase tracking-tight">{{ $group['match_name'] }}</h4>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                            <!-- Participants -->
                                            <div>
                                                <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-4">Peserta</p>
                                                <div class="space-y-3">
                                                    @foreach($group['athletes'] as $athlete)
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-[15px] font-black text-orange-600 uppercase">{{ substr($athlete->athlete_name, 0, 1) }}</div>
                                                            <div>
                                                                <p class="text-[15px] font-black text-black uppercase leading-none mb-1">{{ $athlete->athlete_name }}</p>
                                                                <p class="text-[15px] text-slate-800 font-bold uppercase">{{ $athlete->tingkat ?: 'X Kyu' }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Techniques -->
                                            <div class="bg-white/50 rounded-2xl p-6 border border-slate-100">
                                                <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-4">Urutan Komposisi</p>
                                                <div class="space-y-2">
                                                    @forelse($group['techniques'] as $tIndex => $tName)
                                                        <div class="flex items-start gap-3">
                                                            <span class="text-[15px] font-black text-orange-500 mt-0.5">{{ $tIndex + 1 }}.</span>
                                                            <p class="text-[15px] font-bold text-slate-900 uppercase leading-none">{{ $tName }}</p>
                                                        </div>
                                                    @empty
                                                        <p class="text-[15px] text-slate-300 font-bold italic uppercase">Tidak ada komposisi teknik</p>
                                                    @endforelse
                                                </div>
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
