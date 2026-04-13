<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Wasit</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Registrasi dan manajemen tugas
                    perwasitan</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-user-shield text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em]">Tambah Wasit</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="md:col-span-3 bg-white rounded-xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..."
                    variant="filter" class="pl-10 !border-none shadow-none" />
            </div>

            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full md:w-auto">
                <span
                    class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage"
                    class="bg-transparent border-0 text-slate-700 text-xs font-black focus:ring-0 cursor-pointer p-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div
                class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform">
            </div>
            <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Verified
                Officials</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span
                    class="text-xl font-black text-white leading-none tracking-tighter">{{ $referees->total() }}</span>
                <span class="text-[8px] font-black text-white/70 uppercase tracking-widest">Judges</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            Profil Wasit</th>
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            Kontak & Alamat</th>
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            Sertifikasi</th>
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($referees as $referee)
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-300">
                            <td class="py-2 px-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 font-black shadow-sm group-hover:scale-110 transition-transform">
                                        {{ substr($referee->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-800 text-[13px] group-hover:text-orange-600 transition-colors">{{ $referee->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-semibold">{{ $referee->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <i class="fab fa-whatsapp text-emerald-500 text-[10px]"></i>
                                        <span class="text-xs font-bold text-slate-600">{{ $referee->phone ?? '-' }}</span>
                                    </div>
                                    <span
                                        class="text-[9px] text-slate-400 font-medium truncate max-w-[200px]">{{ $referee->address ?? 'Alamat belum diatur' }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-orange-50 text-orange-600 text-[9px] font-black tracking-widest uppercase border border-orange-100">
                                    {{ $referee->certification_level ?? 'Regular' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                                    <button wire:click="showEditModal({{ $referee->id }})"
                                        class="w-9 h-9 flex items-center justify-center text-orange-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all border border-transparent hover:border-orange-100 shadow-sm hover:shadow-md">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                    title: 'Hapus Wasit?',
                                                    text: 'Data {{ $referee->name }} akan dihapus secara permanen!',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#ea580c',
                                                    cancelButtonColor: '#64748b',
                                                    confirmButtonText: 'Ya, Hapus!',
                                                    cancelButtonText: 'Batal',
                                                    customClass: {
                                                        popup: 'rounded-[2rem]',
                                                        confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3',
                                                        cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3'
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.deleteReferee({{ $referee->id }})
                                                    }
                                                })"
                                        class="w-9 h-9 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-32 text-center text-slate-400">
                                <i class="fas fa-user-shield text-5xl mb-4 opacity-20"></i>
                                <p class="text-xs font-black uppercase tracking-widest italic">Data Belum Tersedia</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($referees->hasPages())
            <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
                {{ $referees->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showingRefereeModal)
        <x-modal wire:model.live="showingRefereeModal"
            title="{{ $refereeIdBeingEdited ? 'Update Profil Wasit' : 'Registrasi Wasit Baru' }}" maxWidth="2xl">
            <form wire:submit="saveReferee" class="space-y-8">
                <!-- Section: Account -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-4 w-1 bg-orange-600 rounded-full"></div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-800">1. Akses Login</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama
                                Lengkap</label>
                            <x-input wire:model="name" type="text" placeholder="Nama Lengkap" />
                            @error('name') <p class="text-[9px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Email
                                Login</label>
                            <x-input wire:model="email" type="email" placeholder="example@mail.com" />
                            @error('email') <p class="text-[9px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Password
                                {{ $refereeIdBeingEdited ? '(Kosongkan jika tidak diganti)' : '' }}</label>
                            <x-input wire:model="password" type="password" placeholder="••••••••" />
                            @error('password') <p class="text-[9px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Detail -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="h-4 w-1 bg-orange-600 rounded-full"></div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-800">2. Profil Wasit</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">NIK
                                (KTP)</label>
                            <x-input wire:model="nik" type="text" placeholder="16 Digit NIK" />
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">WhatsApp</label>
                            <x-input wire:model="phone" type="text" placeholder="08xxxx" />
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Sertifikasi</label>
                            <x-select wire:model="certification_level" placeholder="Pilih..." :options="[
                'Internasional' => 'Internasional',
                'Nasional A' => 'Nasional A',
                'Nasional B' => 'Nasional B',
                'Nasional C' => 'Nasional C',
                'Daerah' => 'Daerah'
            ]" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">No
                                Lisensi</label>
                            <x-input wire:model="license_number" type="text" placeholder="Nomor Resi/Lisensi" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Tempat
                                Lahir</label>
                            <x-input wire:model="birth_place" type="text" placeholder="Kota Lahir" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Tgl
                                Lahir</label>
                            <x-input wire:model="birth_date" type="date" />
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Alamat
                                Lengkap</label>
                            <x-textarea wire:model="address" rows="2" placeholder="Alamat Domisili" />
                        </div>
                    </div>
                </div>
            </form>

            <x-slot name="footer">
                <button wire:click="$set('showingRefereeModal', false)"
                    class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all">
                    Batal
                </button>
                <button wire:click="saveReferee"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-3 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-orange-600/30 transition-all active:scale-95">
                    Simpan Data Perwasitan
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>