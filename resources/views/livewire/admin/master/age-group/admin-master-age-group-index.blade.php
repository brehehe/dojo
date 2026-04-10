<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Kelompok Umur</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Master Data Kelompok Umur</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-plus-circle text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em]">Tambah Kelompok Umur</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-3 bg-white rounded-lg p-1.5 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-[10px]"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari tingkatan..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border-0 rounded-md text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-orange-500/20 transition-all text-[11px] font-bold italic">
            </div>
            
            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-md min-w-fit w-full md:w-auto">
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage" class="bg-transparent border-0 text-slate-700 text-[11px] font-black focus:ring-0 cursor-pointer p-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-lg p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total Kelompok Umur</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black text-white leading-none tracking-tighter">{{ $ageGroups->total() }}</span>
                <span class="text-[9px] font-black text-white/70 uppercase tracking-widest">Kelompok Umur</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[400px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-3 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Nama Kelompok Umur</th>
                        <th class="py-3 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($ageGroups as $ageGroup)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="py-3 px-6">
                                <span class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors text-[13px]">{{ $ageGroup->name }}</span>
                            </td>
                            <td class="py-3 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                                    <button wire:click="showEditModal({{ $ageGroup->id }})"
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                title: 'Hapus Kelompok Umur?',
                                                text: 'Data {{ $ageGroup->name }} akan dihapus permanen!',
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
                                                    $wire.deleteAgeGroup({{ $ageGroup->id }})
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
                                        <p class="text-slate-400 text-[10px] font-medium italic">Belum ada kelompok umur yang terdaftar.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ageGroups->hasPages())
            <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-100">
                {{ $ageGroups->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showingageGroupModal)
        <div class="fixed inset-0 z-[200] overflow-y-auto" role="dialog" aria-modal="true" x-data x-init="$el.focus()">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"
                    wire:click="$set('showingageGroupModal', false)"></div>

                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-white/20 animate-in zoom-in-95 duration-200">
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 text-white relative">
                        <button wire:click="$set('showingageGroupModal', false)" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="space-y-1">
                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-orange-400">Kelompok Umur</span>
                            <h3 class="text-xl font-black tracking-tight">
                                {{ $ageGroupIdBeingEdited ? 'Update Kelompok Umur' : 'Kelompok Umur Baru' }}
                            </h3>
                        </div>
                    </div>

                    <div class="p-8">
                        <form wire:submit="saveAgeGroup" class="space-y-6">
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama Kelompok Umur</label>
                                <input wire:model="name" type="text" placeholder="Contoh: Pra-Pemula"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 focus:bg-white focus:border-orange-500/20 focus:ring-4 focus:ring-orange-500/5 transition-all font-bold italic text-sm">
                                @error('name') <p class="text-[9px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white py-4 rounded-xl font-black uppercase tracking-widest text-[11px] shadow-lg shadow-orange-600/20 transition-all active:scale-95">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>