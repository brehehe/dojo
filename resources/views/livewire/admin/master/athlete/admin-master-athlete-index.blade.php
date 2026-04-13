<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Atlet</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider italic">Registrasi dan Database Atlet Championship</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <a href="{{ route('admin.master.athletes.create') }}" wire:navigate
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-user-plus text-xs"></i>
                <span class="uppercase text-[10px] tracking-widest">Tambah Atlet Baru</span>
            </a>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-3 bg-white rounded-xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atlet atau NIK..."
                    variant="filter"
                    class="pl-10 !border-none shadow-none" />
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show:</span>
                    <select wire:model.live="perPage" class="bg-transparent border-0 text-slate-700 text-xs font-black focus:ring-0 cursor-pointer p-0">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Kontingen:</span>
                    <select wire:model.live="filterContingent" class="bg-transparent border-0 text-slate-700 text-xs font-black focus:ring-0 cursor-pointer p-0 max-w-[150px] truncate">
                        <option value="">Semua</option>
                        @foreach($contingents as $contingent)
                            <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
            <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total Atlet Terdaftar</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black text-white leading-none tracking-tighter">{{ $athletes->total() }}</span>
                <span class="text-[8px] font-black text-white/70 uppercase tracking-widest">Atlets</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Profil Atlet</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Kontingen</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-center">Data Fisik</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Kategori Lomba</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($athletes as $athlete)
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-300">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 font-black shadow-sm group-hover:scale-110 transition-transform">
                                        {{ substr($athlete->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-slate-800 text-sm tracking-tight group-hover:text-orange-600 transition-colors">{{ $athlete->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-semibold italic">NIK: {{ $athlete->nik ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $latestReg = $athlete->latestRegistration();
                                    $contingent = $athlete->contingent;
                                @endphp
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $contingent ? 'bg-orange-500' : 'bg-slate-300' }}"></div>
                                        <span class="text-[11px] font-bold text-slate-600">{{ $contingent->name ?? 'Tanpa Kontingen' }}</span>
                                    </div>
                                    @if($contingent)
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $contingent->kab_kota ?? '-' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-black text-slate-700">{{ $athlete->weight ?? '-' }} <span class="text-[9px] text-slate-400 font-bold">KG</span></span>
                                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $athlete->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        // Show categories from latest registration
                                        $categories = $athlete->categories()->wherePivot('registration_id', $latestReg?->id)->get();
                                    @endphp
                                    @forelse($categories as $category)
                                        <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-600 text-[8px] font-black tracking-widest uppercase border border-orange-100">
                                            {{ $category->name }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-slate-300 font-bold italic">Belum terdaftar</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('admin.master.athletes.detail', $athlete->id) }}" wire:navigate
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all border border-transparent hover:border-blue-100">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.master.athletes.edit', $athlete->id) }}" wire:navigate
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button" onclick="Swal.fire({
                                                title: 'Hapus Atlet?',
                                                text: 'Data atlet {{ $athlete->name }} akan dihapus secara permanen!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ea580c',
                                                cancelButtonColor: '#64748b',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal',
                                                customClass: {
                                                    popup: 'rounded-2xl',
                                                    confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-[10px] px-6 py-3',
                                                    cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-[10px] px-6 py-3'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.deleteAthlete({{ $athlete->id }})
                                                }
                                            })"
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all border border-transparent hover:border-red-100">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                        <i class="fas fa-user-friends text-3xl"></i>
                                    </div>
                                    <p class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Data Atlet Kosong</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($athletes->hasPages())
            <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-100">
                {{ $athletes->links() }}
            </div>
        @endif
    </div>
</div>
