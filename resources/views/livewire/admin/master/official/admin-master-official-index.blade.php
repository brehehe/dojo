<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Official</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider italic">Database Manajer &
                    Official Kontingen</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <a href="{{ route('admin.master.officials.create') }}" wire:navigate
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-user-plus text-[15px]"></i>
                <span class="uppercase text-[15px] tracking-widest">Tambah Official Baru</span>
            </a>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="md:col-span-3 bg-white rounded-xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-800">
                    <i class="fas fa-search text-[15px]"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Cari nama atau no. hp official..."
                    class="w-full pl-10 pr-4 py-2 bg-transparent border-none focus:ring-0 text-[15px] font-bold text-black placeholder:text-slate-300" />
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full">
                    <span
                        class="text-[15px] font-black uppercase tracking-widest text-slate-800 whitespace-nowrap">Show:</span>
                    <select wire:model.live="perPage"
                        class="bg-transparent border-0 text-black text-[15px] font-black focus:ring-0 cursor-pointer p-0">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full">
                    <span
                        class="text-[15px] font-black uppercase tracking-widest text-slate-800 whitespace-nowrap">Kontingen:</span>
                    <select wire:model.live="filterContingent"
                        class="bg-transparent border-0 text-black text-[15px] font-black focus:ring-0 cursor-pointer p-0 max-w-[150px] truncate">
                        <option value="">Semua</option>
                        @foreach($contingents as $contingent)
                            <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group text-white">
            <div
                class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform">
            </div>
            <span class="text-[15px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total
                Official</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black leading-none tracking-tighter">{{ $officials->total() }}</span>
                <span class="text-[15px] font-black text-white/70 uppercase tracking-widest">Personel</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-800 text-white">
                    <tr class="bg-slate-800">
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Nama Official</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Kontak</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">
                            Peran</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-right w-[1%] w-[1%]">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($officials as $official)
                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-800 font-black shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="font-bold text-slate-800 text-[15px] tracking-tight group-hover:text-orange-600 transition-colors">{{ $official->name }}</span>
                                        <span class="text-[15px] text-slate-800 font-semibold italic">ID:
                                            {{ $official->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[15px] font-black text-slate-900 border-r border-slate-200">
                                {{ $official->phone ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center border-r border-slate-200">
                                <span
                                    class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-600 text-[15px] font-black tracking-widest uppercase border border-orange-100">
                                    {{ $official->role ?? 'Official' }}
                                </span>
                            </td>
                            <td class="px-3 py-3 border-r border-slate-200 text-center align-middle">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.master.officials.edit', $official->id) }}"
                                        class="w-10 h-10 rounded-lg inline-flex items-center justify-center transition-all bg-slate-100 text-blue-600 hover:bg-blue-100 hover:text-blue-600 active:scale-95 duration-200" title="Edit">
                                        <i class="fas fa-edit text-[15px]"></i>
                                    </a>
                                    <button type="button" onclick="Swal.fire({
                                                title: 'Hapus Atlet?',
                                                text: 'Data atlet {{ $official->name }} akan dihapus secara permanen!',
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
                                                    $wire.deleteOfficial({{ $official->id }})
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
                                    <div
                                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                        <i class="fas fa-user-tie text-3xl"></i>
                                    </div>
                                    <p class="font-black text-slate-800 uppercase tracking-widest text-[15px]">Data Official
                                        Kosong</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($officials->hasPages())
            <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-100">
                {{ $officials->links('livewire.admin.pagination') }}
            </div>
        @endif
    </div>
</div>