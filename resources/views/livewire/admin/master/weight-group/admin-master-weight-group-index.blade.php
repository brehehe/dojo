<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Kelompok Berat Badan</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Master Data Kelompok Berat Badan</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-plus-circle text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em]">Tambah Kelompok Berat Badan</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="md:col-span-3 bg-white rounded-lg p-1.5 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-[10px]"></i>
                </span>
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kelompok berat badan..."
                    variant="filter"
                    class="pl-10 !border-none shadow-none" />
            </div>

            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-md min-w-fit w-full md:w-auto">
                <span
                    class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage"
                    class="bg-transparent border-0 text-slate-700 text-[11px] font-black focus:ring-0 cursor-pointer p-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-lg p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div
                class="absolute -right-4 -bottom-4 w-12 h-12 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform">
            </div>
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total Kelompok Berat Badan</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black text-white leading-none tracking-tighter">{{ $weightGroups->total() }}</span>
                <span class="text-[9px] font-black text-white/70 uppercase tracking-widest">Weight Groups</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[400px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Nama Kelompok Berat Badan</th>
                        <th class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($weightGroups as $weightGroup)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="py-2 px-4">
                                <span class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors text-[13px]">{{ $weightGroup->name }}</span>
                            </td>
                            <td class="py-2 px-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                                    <button wire:click="showEditModal({{ $weightGroup->id }})"
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                title: 'Hapus Kelompok Berat Badan?',
                                                text: 'Data {{ $weightGroup->name }} akan dihapus permanen!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ea580c',
                                                cancelButtonColor: '#64748b',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal',
                                                customClass: {
                                                    popup: 'rounded-2xl',
                                                    confirmButton: 'rounded-lg font-bold uppercase tracking-widest text-[10px] px-5 py-2.5',
                                                    cancelButton: 'rounded-lg font-bold uppercase tracking-widest text-[10px] px-5 py-2.5'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.deleteWeightGroup({{ $weightGroup->id }})
                                                }
                                            })"
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all border border-transparent hover:border-red-100">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                        <i class="fas fa-layer-group text-3xl"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Data Kosong</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($weightGroups->hasPages())
            <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
                {{ $weightGroups->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showingWeightGroupModal)
        <x-modal wire:model.live="showingWeightGroupModal" title="{{ $weightGroupIdBeingEdited ? 'Update Kelompok Berat Badan' : 'Kelompok Berat Badan Baru' }}" maxWidth="md">
            <form wire:submit="saveWeightGroup" class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama Kelompok Berat Badan</label>
                    <x-input wire:model="name" type="text" placeholder="Contoh: - 40 kg" />
                    @error('name') <p class="text-[9px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p> @enderror
                </div>
            </form>

            <x-slot name="footer">
                <button wire:click="$set('showingWeightGroupModal', false)" 
                    class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all">
                    Batal
                </button>
                <button wire:click="saveWeightGroup" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-3 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-orange-600/30 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>