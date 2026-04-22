<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Teknik & Jurus</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider">Master Data Teknik & Jurus</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-plus-circle text-[15px]"></i>
                <span class="uppercase text-[15px] tracking-[0.2em]">Tambah Teknik & Jurus</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="md:col-span-3 bg-white rounded-lg p-1.5 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-800">
                    <i class="fas fa-search text-[15px]"></i>
                </span>
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari tingkatan..."
                    variant="filter"
                    class="pl-10 !border-none shadow-none" />
            </div>

            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-md min-w-fit w-full md:w-auto">
                <span
                    class="text-[15px] font-black uppercase tracking-widest text-slate-800 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage"
                    class="bg-transparent border-0 text-black text-[15px] font-black focus:ring-0 cursor-pointer p-0">
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
            <span class="text-[15px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total Teknik & Jurus</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black text-white leading-none tracking-tighter">{{ $techniques->total() }}</span>
                <span class="text-[15px] font-black text-white/70 uppercase tracking-widest">Items</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-800 text-white">
                    <tr class="bg-slate-800">
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Nama Teknik & Jurus</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-right w-[1%] w-[1%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($techniques as $technique)
                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                            <td class="py-4 px-6 border-r border-slate-200">
                                <span class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors text-[15px]">{{ $technique->name }}</span>
                            </td>
                            <td class="py-4 px-6 text-right border-r border-slate-200">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="showEditModal({{ $technique->id }})"
                                        class="w-10 h-10 flex items-center justify-center bg-slate-100 text-blue-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-[15px]"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                title: 'Hapus Teknik & Jurus?',
                                                text: 'Data {{ $technique->name }} akan dihapus permanen!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ea580c',
                                                cancelButtonColor: '#64748b',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal',
                                                customClass: {
                                                    popup: 'rounded-2xl',
                                                    confirmButton: 'rounded-lg font-bold uppercase tracking-widest text-[15px] px-5 py-2.5',
                                                    cancelButton: 'rounded-lg font-bold uppercase tracking-widest text-[15px] px-5 py-2.5'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.deleteTechnique({{ $technique->id }})
                                                }
                                            })"
                                        class="w-10 h-10 flex items-center justify-center bg-slate-100 text-red-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100">
                                        <i class="fas fa-trash-alt text-[15px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-20 text-center border-r border-slate-200">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                        <i class="fas fa-layer-group text-3xl"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="font-black text-slate-800 uppercase tracking-widest text-[15px]">Data Kosong</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($techniques->hasPages())
            <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
                {{ $techniques->links('livewire.admin.pagination') }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showingTechniqueModal)
        <x-modal wire:model.live="showingTechniqueModal" title="{{ $techniqueIdBeingEdited ? 'Update Teknik & Jurus' : 'Teknik & Jurus Baru' }}">
            <form wire:submit="saveTechnique" class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Nama Teknik & Jurus</label>
                    <x-input wire:model="name" type="text" placeholder="Contoh: Jurus 1" />
                    @error('name') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p> @enderror
                </div>
            </form>

            <x-slot name="footer">
                <button wire:click="$set('showingTechniqueModal', false)" 
                    class="px-6 py-3 text-[15px] font-black uppercase tracking-widest text-slate-800 hover:text-slate-900 transition-all">
                    Batal
                </button>
                <button wire:click="saveTechnique" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-3 rounded-2xl font-black uppercase tracking-widest text-[15px] shadow-xl shadow-orange-600/30 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>