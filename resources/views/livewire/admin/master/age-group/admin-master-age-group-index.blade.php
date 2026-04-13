<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Kelompok Umur</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Definisikan klasifikasi umur untuk kategori pertandingan</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-calendar-plus text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em]">Tambah Kategori</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-3 bg-white rounded-xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama kelompok umur..."
                    variant="filter"
                    class="pl-10 !border-none shadow-none" />
            </div>
            
            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full md:w-auto">
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage" class="bg-transparent border-0 text-slate-700 text-xs font-black focus:ring-0 cursor-pointer p-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
            <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total Kategori</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black text-white leading-none tracking-tighter">{{ $ageGroups->total() }}</span>
                <span class="text-[8px] font-black text-white/70 uppercase tracking-widest">Groups</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Nama Kelompok Umur</th>
                        <th class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Biaya Pendaftaran</th>
                        <th class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Dibuat Pada</th>
                        <th class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($ageGroups as $group)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                            <td class="py-2 px-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-orange-50 group-hover:text-orange-500 transition-all duration-300">
                                        <i class="fas fa-calendar-day text-xs"></i>
                                    </div>
                                    <span class="font-bold text-slate-800 text-sm group-hover:translate-x-1 transition-transform duration-300">{{ $group->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="font-bold text-slate-800 text-sm group-hover:translate-x-1 transition-transform duration-300">Rp{{ number_format($group->price,0,',','.') }}</span>
                            </td>
                            <td class="py-2 px-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-600">{{ $group->created_at->format('d M Y') }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase font-black tracking-widest">{{ $group->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                                    <button wire:click="showEditModal({{ $group->id }})"
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-xl transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                title: 'Hapus Kategori?',
                                                text: 'Data {{ $group->name }} akan dihapus permanen!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ea580c',
                                                cancelButtonColor: '#64748b',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal',
                                                customClass: {
                                                    popup: 'rounded-[1.5rem]',
                                                    confirmButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-6 py-3',
                                                    cancelButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-6 py-3'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.deleteAgeGroup({{ $group->id }})
                                                }
                                            })"
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-24 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200">
                                        <i class="fas fa-calendar-times text-4xl"></i>
                                    </div>
                                    <p class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Data Kosong</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ageGroups->hasPages())
            <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
                {{ $ageGroups->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showingAgeGroupModal)
        <x-modal wire:model.live="showingAgeGroupModal" title="{{ $ageGroupIdBeingEdited ? 'Update Kelompok Umur' : 'Tambah Kelompok Umur Baru' }}" maxWidth="md">
            <form wire:submit="saveAgeGroup" class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama Kelompok Umur</label>
                    <x-input wire:model="name" type="text" placeholder="Contoh: Pra-Pemula" />
                    @error('name') <p class="text-[9px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Biaya Pendaftaran</label>
                    <x-input wire:model="price" type="number" placeholder="Contoh: 100000" />
                    @error('price') <p class="text-[9px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p> @enderror
                </div>
            </form>

            <x-slot name="footer">
                <button wire:click="$set('showingAgeGroupModal', false)" 
                    class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all">
                    Batal
                </button>
                <button wire:click="saveAgeGroup" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-3 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-orange-600/30 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>