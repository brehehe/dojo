<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Wasit</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider">Registrasi dan manajemen tugas
                    perwasitan</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-user-shield text-[15px]"></i>
                <span class="uppercase text-[15px] tracking-[0.2em]">Tambah Wasit</span>
            </button>
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
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..."
                    variant="filter" class="pl-10 !border-none shadow-none" />
            </div>

            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full md:w-auto">
                <span
                    class="text-[15px] font-black uppercase tracking-widest text-slate-800 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage"
                    class="bg-transparent border-0 text-black text-[15px] font-black focus:ring-0 cursor-pointer p-0">
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
            <span class="text-[15px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Verified
                Officials</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span
                    class="text-xl font-black text-white leading-none tracking-tighter">{{ $referees->total() }}</span>
                <span class="text-[15px] font-black text-white/70 uppercase tracking-widest">Judges</span>
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
                            Profil Wasit</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Kontak & Alamat</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Sertifikasi</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-right w-[1%] w-[1%]">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($referees as $referee)
                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-900 font-black shadow-sm group-hover:scale-110 transition-transform overflow-hidden">
                                        @if($referee->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($referee->photo))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($referee->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($referee->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-800 text-[15px] group-hover:text-orange-600 transition-colors">{{ $referee->user->name }}</span>
                                        <span class="text-[15px] text-slate-800 font-semibold">{{ $referee->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <i class="fab fa-whatsapp text-emerald-500 text-[15px]"></i>
                                        <span class="text-[15px] font-bold text-slate-900">{{ $referee->phone ?? '-' }}</span>
                                    </div>
                                    <span
                                        class="text-[15px] text-slate-800 font-medium truncate max-w-[200px]">{{ $referee->address ?? 'Alamat belum diatur' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-r border-slate-200">
                                <span
                                    class="px-2.5 py-1 rounded-md bg-orange-50 text-orange-600 text-[15px] font-black tracking-widest uppercase border border-orange-100">
                                    {{ $referee->certification_level ?? 'Regular' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right border-r border-slate-200">
                                <div
                                    class="flex items-center justify-end gap-2">
                                    <button wire:click="showEditModal({{ $referee->id }})"
                                        class="w-10 h-10 flex items-center justify-center bg-slate-100 text-blue-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-[15px]"></i>
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
                                                        confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3',
                                                        cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3'
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.deleteReferee({{ $referee->id }})
                                                    }
                                                })"
                                        class="w-10 h-10 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash-alt text-[15px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-32 text-center text-slate-800 border-r border-slate-200">
                                <i class="fas fa-user-shield text-5xl mb-4 opacity-20"></i>
                                <p class="text-[15px] font-black uppercase tracking-widest italic">Data Belum Tersedia</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($referees->hasPages())
            <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
                {{ $referees->links('livewire.admin.pagination') }}
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
                        <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">1. Akses Login</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Nama
                                Lengkap</label>
                            <x-input wire:model="name" type="text" placeholder="Nama Lengkap" />
                            @error('name') <p class="text-[15px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Email
                                Login</label>
                            <x-input wire:model="email" type="email" placeholder="example@mail.com" />
                            @error('email') <p class="text-[15px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Password
                                {{ $refereeIdBeingEdited ? '(Kosongkan jika tidak diganti)' : '' }}</label>
                            <x-input wire:model="password" type="password" placeholder="••••••••" />
                            @error('password') <p class="text-[15px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Detail -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="h-4 w-1 bg-orange-600 rounded-full"></div>
                        <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">2. Profil Wasit</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">NIK
                                (KTP)</label>
                            <x-input wire:model="nik" type="text" placeholder="16 Digit NIK" />
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">WhatsApp</label>
                            <x-input wire:model="phone" type="text" placeholder="08xxxx" />
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Sertifikasi</label>
                            <x-select wire:model="certification_level" placeholder="Pilih..." :options="[
                'Internasional' => 'Internasional',
                'Nasional A' => 'Nasional A',
                'Nasional B' => 'Nasional B',
                'Nasional C' => 'Nasional C',
                'Daerah' => 'Daerah'
            ]" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">No
                                Lisensi</label>
                            <x-input wire:model="license_number" type="text" placeholder="Nomor Resi/Lisensi" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Tempat
                                Lahir</label>
                            <x-input wire:model="birth_place" type="text" placeholder="Kota Lahir" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Tgl
                                Lahir</label>
                            <x-input wire:model="birth_date" type="date" />
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Alamat
                                Lengkap</label>
                            <x-textarea wire:model="address" rows="2" placeholder="Alamat Domisili" />
                        </div>
                        
                        <!-- Section: Foto Wasit -->
                        <div class="space-y-4 pt-4 border-t border-slate-100 md:col-span-2">
                            <div class="flex items-center gap-3">
                                <div class="h-4 w-1 bg-orange-600 rounded-full"></div>
                                <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">3. Foto Wasit</h4>
                            </div>
                            <div class="flex flex-col md:flex-row items-center gap-6 bg-slate-50 p-6 rounded-2xl border border-dashed border-slate-200">
                                <div class="relative group shrink-0">
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-28 h-28 object-cover rounded-2xl border-2 border-orange-500 shadow-md">
                                        <button type="button" wire:click="removePhoto" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg transition-transform hover:scale-110 active:scale-95 z-20" title="Hapus Foto">
                                            <i class="fas fa-times text-[12px]"></i>
                                        </button>
                                    @elseif ($existingPhoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingPhoto))
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($existingPhoto) }}" class="w-28 h-28 object-cover rounded-2xl border border-slate-200 shadow-md">
                                        <button type="button" wire:click="removePhoto" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg transition-transform hover:scale-110 active:scale-95 z-20" title="Hapus Foto">
                                            <i class="fas fa-times text-[12px]"></i>
                                        </button>
                                    @else
                                        <div class="w-28 h-28 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 font-black border border-slate-200">
                                            <i class="fas fa-user-tie text-4xl"></i>
                                        </div>
                                    @endif
                                    
                                    <div wire:loading wire:target="photo" class="absolute inset-0 bg-black/50 rounded-2xl flex items-center justify-center text-white text-[12px] font-black uppercase tracking-widest">
                                        <div class="flex flex-col items-center gap-1">
                                            <i class="fas fa-circle-notch animate-spin text-lg"></i>
                                            <span>Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 space-y-2 w-full">
                                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Unggah Foto Resmi Wasit</label>
                                    <x-input wire:model="photo" type="file" class="text-[15px]" accept="image/*" />
                                    <p class="text-[13px] text-slate-500 font-medium">Format: JPG, PNG, WEBP (Maksimal 2MB). Foto ini akan digunakan untuk tanda pengenal resmi dan profil tugas.</p>
                                    @error('photo') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot name="footer">
                <button wire:click="$set('showingRefereeModal', false)"
                    class="px-6 py-3 text-[15px] font-black uppercase tracking-widest text-slate-800 hover:text-slate-900 transition-all">
                    Batal
                </button>
                <button wire:click="saveReferee"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-3 rounded-2xl font-black uppercase tracking-widest text-[15px] shadow-xl shadow-orange-600/30 transition-all active:scale-95">
                    Simpan Data Perwasitan
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>