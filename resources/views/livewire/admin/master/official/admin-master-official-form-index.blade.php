<div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-[15px] font-black uppercase tracking-widest text-slate-800">
                <a href="{{ route('admin.master.officials.index') }}" wire:navigate class="hover:text-orange-600 transition-colors">Master Official</a>
                <i class="fas fa-chevron-right text-[15px]"></i>
                <span class="text-slate-900">{{ $isEdit ? 'Edit Data' : 'Tambah Baru' }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">
                {{ $isEdit ? 'Edit Personal Official' : 'Registrasi Official Baru' }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.master.officials.index') }}" wire:navigate
                class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-5 py-2.5 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all">
                Batal & Kembali
            </a>
            <button wire:click="save" wire:loading.attr="disabled"
                class="bg-gradient-to-br from-orange-500 to-orange-700 text-white px-8 py-2.5 rounded-xl text-[15px] font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 transition-all hover:scale-105 active:scale-95 disabled:opacity-50">
                <span wire:loading.remove>Simpan Data</span>
                <span wire:loading><i class="fas fa-circle-notch fa-spin mr-2"></i>Memproses...</span>
            </button>
        </div>
    </div>

    <!-- Form Body -->
    <div class="grid grid-cols-1 gap-8">
        <!-- Main Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-[2.5rem] p-4 md:p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                    <i class="fas fa-user-tie text-[120px]"></i>
                </div>

                    <div class="mb-4">
                        <label class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800 ml-1">Kontingen / Dojo Utama</label>
                        <div class="relative group">
                            <x-select wire:model="contingent_id" label="Kontingen / Dojo Utama" placeholder="Pilih Kontingen...">
                                @foreach($contingents as $contingent)
                                    <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                                @endforeach
                            </x-select>
                        </div>
                        @error('contingent_id') <span class="text-[15px] font-bold text-red-500 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800 ml-1">Jabatan / Peran</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-orange-500">
                                <i class="fas fa-tags text-[15px]"></i>
                            </span>
                            <x-select wire:model="role" label="Jabatan / Peran" placeholder="Pilih Jabatan / Peran">
                                <option value="Official">Official</option>
                                <option value="Manajer Tim">Manajer Tim</option>
                                <option value="Pelatih">Pelatih</option>
                                <option value="Medis">Medis</option>
                                <option value="Humas">Humas</option>
                            </x-select>
                        </div>
                        @error('role') <span class="text-[15px] font-bold text-red-500 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800 ml-1">Nama Lengkap & Gelar</label>
                            <x-input wire:model="name" label="Nama Lengkap & Gelar" placeholder="Masukkan nama lengkap official..." />
                        @error('name') <span class="text-[15px] font-bold text-red-500 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800 ml-1">Nomor WhatsApp / HP</label>
                        <x-input wire:model="phone" label="Nomor WhatsApp / HP" placeholder="Contoh: 08123456789" />
                        @error('phone') <span class="text-[15px] font-bold text-red-500 ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
