<div class="min-h-screen bg-slate-50 pb-20">
    {{-- Header Section --}}
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Public Address System</h1>
                        <p class="text-slate-500 text-sm font-medium">Sistem Pemanggilan Peserta Otomatis</p>
                    </div>
                </div>

                {{-- Custom Announcement Input --}}
                <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 w-full md:w-96">
                    <input type="text" wire:model.defer="customMessage" placeholder="Ketik pesan khusus di sini..." 
                           class="flex-1 bg-transparent border-none focus:ring-0 text-sm font-bold text-slate-700 px-3">
                    <button wire:click="playCustom" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-indigo-100 flex items-center gap-2">
                        <i class="fas fa-play"></i>
                        Panggil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Filters Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="md:col-span-2">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-500 text-slate-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Cari Kontingen, Atlet, atau Nomor Pertandingan..." 
                           class="w-full pl-11 pr-4 py-4 bg-white border border-slate-200 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-bold text-slate-700">
                </div>
            </div>
            <div>
                <select wire:model.live="filterCourt" 
                        class="w-full py-4 bg-white border border-slate-200 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm font-bold text-slate-700">
                    <option value="">Semua Lapangan</option>
                    @foreach($courts as $court)
                        <option value="{{ $court->id }}">{{ $court->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Participant List --}}
        <div class="grid grid-cols-1 gap-4">
            @forelse($drawings as $drawing)
                <div class="bg-white border border-slate-200 rounded-3xl p-5 hover:border-indigo-300 transition-all hover:shadow-xl hover:shadow-indigo-500/5 group">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5 flex-1">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100 group-hover:bg-indigo-50 transition-colors">
                                <span class="text-2xl">🥋</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-black uppercase tracking-widest border border-slate-200">
                                        {{ $drawing->matchNumber->ageGroup->name ?? '—' }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[10px] font-black uppercase tracking-widest border border-indigo-100">
                                        {{ $drawing->matchNumber->name }}
                                    </span>
                                    @if($drawing->pool)
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 rounded text-[10px] font-black uppercase tracking-widest border border-amber-100">
                                            POOL {{ $drawing->pool->name }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-black text-slate-900 uppercase truncate mb-0.5">
                                    {{ $drawing->registration->contingent->name ?? '—' }}
                                </h3>
                                <p class="text-sm text-slate-500 font-bold truncate">
                                    {{ $drawing->registration->athletes->pluck('name')->implode(', ') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 shrink-0">
                            <div class="text-right hidden md:block">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Lokasi</p>
                                <p class="text-sm font-black text-slate-700 uppercase">
                                    {{ $drawing->court->name ?? 'Lap. Belum Diatur' }}
                                </p>
                            </div>

                            <button wire:click="callParticipant({{ $drawing->id }})" 
                                    class="flex-1 md:flex-none h-12 px-6 bg-slate-900 hover:bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-indigo-500/25 flex items-center justify-center gap-3 active:scale-95">
                                <i class="fas fa-volume-up text-sm"></i>
                                Panggil Peserta
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border-2 border-dashed border-slate-200 rounded-[2rem] p-20 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                        <i class="fas fa-users-slash text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-slate-500 font-medium">Gunakan filter atau pencarian lain untuk menemukan peserta.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $drawings->links() }}
        </div>
    </div>
</div>
