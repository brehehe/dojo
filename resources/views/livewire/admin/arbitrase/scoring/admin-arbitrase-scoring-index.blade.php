<div class="p-6">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Penilaian Pertandingan</h1>
                <p class="text-sm text-slate-500 font-medium">Input hasil pertandingan dan nilai juri dari lapangan.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nomor tanding..." 
                        class="pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none w-full sm:w-64 transition-all shadow-sm"
                    >
                </div>
                
                <select 
                    wire:model.live="type"
                    class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all shadow-sm"
                >
                    <option value="all">Semua Kategori</option>
                    <option value="embu">Embu (Seni)</option>
                    <option value="randori">Randori (Tanding)</option>
                </select>
            </div>
        </div>

        <!-- Matches Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($matches as $match)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 {{ $match->draft_type === 'embu' ? 'bg-indigo-50 text-indigo-600' : 'bg-rose-50 text-rose-600' }} text-[10px] font-black uppercase tracking-widest rounded-lg border border-current/10">
                                {{ strtoupper($match->draft_type) }}
                            </span>
                            @if($match->is_active)
                                <span class="flex items-center gap-1.5 px-2 py-1 bg-amber-500 text-white text-[9px] font-black uppercase tracking-widest rounded-lg animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    LIVE ON COURT
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-md">
                                    #{{ $match->id }}
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-black text-slate-800 mb-1 uppercase tracking-tight line-clamp-1">
                            {{ $match->name }}
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">
                            {{ $match->ageGroup->name ?? 'Semua Usia' }} • {{ $match->gender ?? 'Mix' }}
                        </p>
                        
                        <div class="flex items-center gap-2 mb-6 text-slate-500">
                            <i class="fas fa-users text-xs"></i>
                            <span class="text-xs font-bold">{{ $match->athletes_count ?? $match->athletes()->count() }} Peserta Terdaftar</span>
                        </div>

                        <div class="flex flex-col gap-2">
                            <button 
                                wire:click="activateMatch({{ $match->id }})"
                                class="w-full flex items-center justify-center gap-2 py-2.5 {{ $match->is_active ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                            >
                                <i class="fas fa-bullhorn text-[9px]"></i>
                                <span>{{ $match->is_active ? 'Sedang Bertanding' : 'Panggil ke Lapangan' }}</span>
                            </button>

                            <a 
                                href="{{ route('admin.arbitrase.scoring.' . $match->draft_type . '.detail', $match->id) }}"
                                class="w-full flex items-center justify-center gap-2 py-3 bg-slate-900 hover:bg-amber-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-md group-hover:shadow-amber-500/25 active:scale-95"
                            >
                                <span>Input Nilai (Admin)</span>
                                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white border-2 border-dashed border-slate-200 rounded-3xl">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Belum ada pertandingan tersedia</p>
                    <p class="text-xs text-slate-400 mt-1">Pastikan Drawing sudah di-generate.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="col-span-full mt-8">
                {{ $matches->links() }}
            </div>
        </div>
    </div>
</div>
