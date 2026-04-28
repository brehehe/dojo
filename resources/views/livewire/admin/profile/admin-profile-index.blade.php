<div class="max-w-full mx-auto space-y-8 animate-in fade-in duration-500">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pengaturan Akun</h1>
        <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Kelola informasi profil dan
            keamanan Anda</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Profile Info Section --}}
        <div class="md:col-span-1">
            <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Profil</h2>
            <p class="text-[13px] text-slate-500 mt-2">Perbarui nama dan alamat email akun Anda.</p>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <form wire:submit="updateProfile" class="p-6 space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Nama
                            Lengkap</label>
                        <input type="text" wire:model="name"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all"
                            placeholder="Masukkan nama lengkap...">
                        @error('name') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Alamat
                            Email</label>
                        <input type="email" wire:model="email"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all"
                            placeholder="Masukkan email...">
                        @error('email') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Referee Fields --}}
                    @if($is_referee)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                            <div class="md:col-span-2">
                                <h3 class="text-[11px] font-black text-orange-600 uppercase tracking-widest">Detail Wasit
                                    Juri</h3>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">NIK</label>
                                <input type="text" wire:model="referee_data.nik"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">No.
                                    Telepon</label>
                                <input type="text" wire:model="referee_data.phone"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Provinsi</label>
                                <input type="text" wire:model="referee_data.province"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kota/Kabupaten</label>
                                <input type="text" wire:model="referee_data.city"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Tingkat
                                    Sertifikasi</label>
                                <input type="text" wire:model="referee_data.certification_level"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                        </div>
                    @endif

                    {{-- Contingent Fields --}}
                    @if($is_contingent)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                            <div class="md:col-span-2">
                                <h3 class="text-[11px] font-black text-blue-600 uppercase tracking-widest">Detail Kontingen
                                </h3>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Nama
                                    Ketua</label>
                                <input type="text" wire:model="contingent_data.leader_name"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">No. HP
                                    Ketua</label>
                                <input type="text" wire:model="contingent_data.leader_phone"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kota /
                                    Kabupaten (Cabang)</label>
                                <input type="text" wire:model="contingent_data.kab_kota"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Alamat
                                    Lengkap</label>
                                <textarea wire:model="contingent_data.address"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-sm transition-all shadow-sm active:scale-95 flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Divider --}}
        <div class="md:col-span-3 border-t border-slate-100"></div>

        {{-- Password Section --}}
        <div class="md:col-span-1">
            <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Ubah Password</h2>
            <p class="text-[13px] text-slate-500 mt-2">Pastikan akun Anda menggunakan password yang panjang dan acak
                untuk tetap aman.</p>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <form wire:submit="updatePassword" class="p-6 space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Password
                            Saat Ini</label>
                        <input type="password" wire:model="current_password"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                        @error('current_password') <span
                        class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Password
                            Baru</label>
                        <input type="password" wire:model="new_password"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                        @error('new_password') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Konfirmasi
                            Password Baru</label>
                        <input type="password" wire:model="new_password_confirmation"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm font-medium transition-all">
                        @error('new_password_confirmation') <span
                        class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-orange-200 active:scale-95 flex items-center gap-2">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>