<div class="space-y-6 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $isEdit ? 'Update Data Kontingen' : 'Registrasi Kontingen Baru' }}</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider italic">Formulir Kelola Data Unit Kontingen & Dojo</p>
            </div>
        </div>
        <a href="{{ route('admin.master.contingents.index') }}" wire:navigate
            class="group px-4 py-2 text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
            <span class="text-[10px] font-black uppercase tracking-widest">Kembali ke List</span>
        </a>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main: Contingent Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-slate-50">
                    <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-slate-800">Identitas Kontingen</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama Kontingen / Dojo / Ranting</label>
                        <x-input wire:model="name" type="text" placeholder="Masukkan nama unit kontingen..." />
                        @error('name') <p class="text-[10px] text-red-500 mt-1 italic font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Kabupaten / Kota</label>
                        <x-input wire:model="kab_kota" type="text" placeholder="Asal Daerah" />
                        @error('kab_kota') <p class="text-[10px] text-red-500 mt-1 italic font-bold ml-1">{{ $message }}</p> @enderror
                    </div>


                    <div class="space-y-1.5 col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Alamat Lengkap Sekretariat</label>
                        <textarea wire:model="address" class="w-full bg-slate-50 border-slate-100 rounded-2xl text-sm font-bold p-4 focus:ring-orange-500/20 focus:border-orange-200 transition-all min-h-[100px]" placeholder="Alamat lengkap..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Leader Info -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-slate-50">
                    <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-slate-800">Informasi Penanggung Jawab</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama Ketua / Manajer</label>
                        <x-input wire:model="leader_name" type="text" placeholder="Pimpinan Kontingen" />
                        @error('leader_name') <p class="text-[10px] text-red-500 mt-1 italic font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">No. WhatsApp / Telepon</label>
                        <x-input wire:model="leader_phone" type="text" placeholder="08xxxxxxxxxx" />
                        @error('leader_phone') <p class="text-[10px] text-red-500 mt-1 italic font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5 col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email Resmi Kontingen</label>
                        <x-input wire:model="email" type="email" placeholder="official@domain.com" />
                        @error('email') <p class="text-[10px] text-red-500 mt-1 italic font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-slate-50">
                    <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-slate-800">Aksi Simpan</h3>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-orange-600/30 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-save shadow-sm"></i>
                        <span>{{ $isEdit ? 'Update Kontingen' : 'Daftarkan Kontingen' }}</span>
                    </button>
                    <p class="text-[10px] text-slate-400 text-center mt-4 font-bold italic px-4">Pastikan data kontingen valid untuk sinkronisasi data.</p>
                </div>
            </div>
        </div>
    </form>
</div>
