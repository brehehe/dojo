<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Metode Bayar</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider">Master Data Metode Bayar</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-plus-circle text-[15px]"></i>
                <span class="uppercase text-[15px] tracking-[0.2em]">Tambah Metode Bayar</span>
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
                    variant="filter" class="pl-10 !border-none shadow-none" />
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
            <span class="text-[15px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total
                Metode Bayar</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span
                    class="text-xl font-black text-white leading-none tracking-tighter">{{ $paymentMethods->total() }}</span>
                <span class="text-[15px] font-black text-white/70 uppercase tracking-widest">Items</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <x-table>
        <x-slot:header>
            <x-table.th>Atas Nama</x-table.th>
            <x-table.th>Bank</x-table.th>
            <x-table.th>Akun Rekening</x-table.th>
            <x-table.th class="text-right w-[1%]">Aksi</x-table.th>
        </x-slot:header>

        @forelse($paymentMethods as $paymentMethod)
            <x-table.tr>
                <x-table.td>
                    <span
                        class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors text-[15px]">{{ $paymentMethod->name }}</span>
                </x-table.td>
                <x-table.td>
                    <span
                        class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors text-[15px]">{{ $paymentMethod->bank }}</span>
                </x-table.td>
                <x-table.td>
                    <span
                        class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors text-[15px]">{{ $paymentMethod->account_number }}</span>
                </x-table.td>
                <x-table.td class="text-right">
                    <div class="flex items-center justify-end gap-2 ">
                        <button wire:click="showEditModal({{ $paymentMethod->id }})"
                            class="w-10 h-10 rounded-lg inline-flex items-center justify-center transition-all bg-slate-100 text-blue-600 hover:bg-blue-100 hover:text-blue-600 active:scale-95 duration-200">
                            <i class="fas fa-edit text-[15px]"></i>
                        </button>
                        <button type="button" onclick="Swal.fire({
                                                                                            title: 'Hapus Metode Bayar?',
                                                                                            text: 'Data {{ $paymentMethod->name }} akan dihapus permanen!',
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
                                                                                                $wire.deletePaymentMethod({{ $paymentMethod->id }})
                                                                                            }
                                                                                        })"
                            class="w-10 h-10 rounded-lg inline-flex items-center justify-center transition-all bg-slate-100 text-red-600 hover:bg-red-100 hover:text-red-600 active:scale-95 duration-200">
                            <i class="fas fa-trash-alt text-[15px]"></i>
                        </button>
                    </div>
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <x-table.td colspan="4" class="py-20 text-center">
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                            <i class="fas fa-layer-group text-3xl"></i>
                        </div>
                        <div class="space-y-1">
                            <p class="font-black text-slate-800 uppercase tracking-widest text-[15px]">Data Metode Bayar
                                Kosong</p>
                        </div>
                    </div>
                </x-table.td>
            </x-table.tr>
        @endforelse

        @if($paymentMethods->hasPages())
            <x-slot:pagination>
                {{ $paymentMethods->links('livewire.admin.pagination') }}
            </x-slot:pagination>
        @endif
    </x-table>

    <!-- Modal -->
    @if($showingPaymentMethodModal)
        <x-modal wire:model.live="showingPaymentMethodModal"
            title="{{ $paymentMethodIdBeingEdited ? 'Update Metode Bayar' : 'Metode Bayar Baru' }}">
            <div class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Nama
                        Atas Nama <span class="text-red-500">*</span></label>
                    <x-input wire:model="name" type="text" placeholder="Contoh: Babak Penyisihan Hari 1" />
                    @error('name') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Nama Bank</label>
                    <x-input wire:model="bank" type="text" placeholder="Contoh: Bank BCA" />
                    @error('bank') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Akun
                        Rekening</label>
                    <x-input wire:model="account_number" type="text" placeholder="Contoh: 123456789" />
                    @error('account_number') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{
                        $message }}</p>
                    @enderror
                </div>
            </div>

            <x-slot name="footer">
                <button wire:click="$set('showingPaymentMethodModal', false)"
                    class="px-6 py-3 text-[15px] font-black uppercase tracking-widest text-slate-800 hover:text-slate-900 transition-all">
                    Batal
                </button>
                <button wire:click="savePaymentMethod"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-3 rounded-2xl font-black uppercase tracking-widest text-[15px] shadow-xl shadow-orange-600/30 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>