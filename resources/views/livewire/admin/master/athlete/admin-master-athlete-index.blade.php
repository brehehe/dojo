<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Atlet</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider italic">Registrasi dan Database Atlet Championship</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <a href="{{ route('admin.master.athletes.create') }}" wire:navigate
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-user-plus text-[15px]"></i>
                <span class="uppercase text-[15px] tracking-widest">Tambah Atlet Baru</span>
            </a>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="space-y-2">

        {{-- Stat chip (mobile) + full card (md+) --}}
        <div class="flex items-center justify-between gap-3 md:hidden">
            <p class="text-[13px] text-slate-400 font-bold uppercase tracking-widest">Daftar Atlet</p>
            <div class="flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-full px-4 py-1.5 shadow-md shadow-orange-200">
                <i class="fas fa-users text-[11px]"></i>
                <span class="text-[13px] font-black tracking-wider">{{ $athletes->total() }} Atlets</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- Search + Filters --}}
            <div class="md:col-span-3 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">

                {{-- Search bar --}}
                <div class="relative border-b border-slate-100">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <x-input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Cari nama atlet atau NIK..."
                        variant="filter"
                        class="pl-10 !border-none shadow-none !rounded-none" />
                </div>

                {{-- Filters row --}}
                <div class="flex items-center divide-x divide-slate-100">
                    <div class="flex items-center gap-2 px-4 py-2.5 flex-1 min-w-0">
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show</span>
                        <select wire:model.live="perPage"
                            class="bg-transparent border-0 text-slate-800 text-[13px] font-black focus:ring-0 cursor-pointer p-0 w-full">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 px-4 py-2.5 flex-[3] min-w-0">
                        <i class="fas fa-filter text-[11px] text-slate-400 shrink-0"></i>
                        <select wire:model.live="filterContingent"
                            class="bg-transparent border-0 text-slate-800 text-[13px] font-black focus:ring-0 cursor-pointer p-0 w-full truncate">
                            <option value="">Semua Kontingen</option>
                            @foreach($contingents as $contingent)
                                <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Stat card (md+) --}}
            <div class="hidden md:flex bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-4 shadow-lg flex-col justify-center border border-white/10 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
                <span class="text-[13px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total Atlet</span>
                <div class="flex items-baseline gap-2 relative z-10">
                    <span class="text-2xl font-black text-white leading-none tracking-tighter">{{ $athletes->total() }}</span>
                    <span class="text-[12px] font-black text-white/60 uppercase tracking-widest">Atlets</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-800 text-white">
                    <tr class="bg-slate-800">
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Profil Atlet</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kontingen</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Data Fisik</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kategori Lomba</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[1%] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($athletes as $athlete)
                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-800 font-black shadow-sm group-hover:scale-110 transition-transform">
                                        {{ substr($athlete->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-slate-800 text-[15px] tracking-tight group-hover:text-orange-600 transition-colors">{{ $athlete->name }}</span>
                                        <span class="text-[15px] text-slate-800 font-semibold italic">NIK: {{ $athlete->nik ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-r border-slate-200">
                                @php
                                    $latestReg = $athlete->latestRegistration();
                                    $contingent = $athlete->contingent;
                                @endphp
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $contingent ? 'bg-orange-500' : 'bg-slate-300' }}"></div>
                                        <span class="text-[15px] font-bold text-slate-900">{{ $contingent->name ?? 'Tanpa Kontingen' }}</span>
                                    </div>
                                    @if($contingent)
                                        <span class="text-[15px] text-slate-800 font-bold uppercase tracking-widest">{{ $contingent->kab_kota ?? '-' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center border-r border-slate-200">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[15px] font-black text-black">{{ $athlete->weight ?? '-' }} <span class="text-[15px] text-slate-800 font-bold">KG</span></span>
                                    <span class="text-[15px] text-slate-800 font-black uppercase tracking-widest">{{ $athlete->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        // Show categories from latest registration
                                        $categories = $athlete->categories()->wherePivot('registration_id', $latestReg?->id)->get();
                                    @endphp
                                    @forelse($categories as $category)
                                        <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-600 text-[15px] font-black tracking-widest uppercase border border-orange-100">
                                            {{ $category->name }}
                                        </span>
                                    @empty
                                        <span class="text-[15px] text-slate-300 font-bold italic">Belum terdaftar</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-6 border-r border-slate-200 text-center align-middle">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.master.athletes.detail', $athlete->id) }}"
                                        class="w-10 h-10 rounded-lg inline-flex items-center justify-center transition-all bg-slate-100 text-orange-600 hover:bg-orange-100 hover:text-orange-600 active:scale-95 duration-200" title="Detail">
                                        <i class="fas fa-eye text-[15px]"></i>
                                    </a>
                                    <a href="{{ route('admin.master.athletes.edit', $athlete->id) }}" wire:navigate
                                        class="w-10 h-10 rounded-lg inline-flex items-center justify-center transition-all bg-slate-100 text-blue-600 hover:bg-blue-100 hover:text-blue-600 active:scale-95 duration-200">
                                        <i class="fas fa-edit text-[15px]"></i>
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
                                                    confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3',
                                                    cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.deleteAthlete({{ $athlete->id }})
                                                }
                                            })"
                                        class="w-10 h-10 rounded-lg inline-flex items-center justify-center transition-all bg-slate-100 text-red-600 hover:bg-red-100 hover:text-red-600 active:scale-95 duration-200">
                                        <i class="fas fa-trash-alt text-[15px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center border-r border-slate-200">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                        <i class="fas fa-user-friends text-3xl"></i>
                                    </div>
                                    <p class="font-black text-slate-800 uppercase tracking-widest text-[15px]">Data Atlet Kosong</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($athletes->hasPages())
            <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-100">
                {{ $athletes->links('livewire.admin.pagination') }}
            </div>
        @endif
    </div>
</div>
