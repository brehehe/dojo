<div class="max-w-3xl mx-auto py-10 px-4 animate-fade-up">

    <!-- card surface -->
    <div class="bg-white rounded-2xl border border-[var(--paper2)] shadow-xl overflow-hidden">
        
        <!-- header section -->
        <div class="px-6 md:px-10 pt-12 pb-8 text-center border-b border-[var(--paper2)] relative overflow-hidden">
            <!-- decorative background -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-[radial-gradient(circle,rgba(212,168,67,0.1)_0%,transparent_70%)] pointer-events-none"></div>
            
            <div class="relative inline-block mb-6">
                <div class="absolute inset-0 bg-red-600 rounded-3xl blur-2xl opacity-20 animate-pulse"></div>
                <div class="relative w-20 h-20 bg-gradient-to-br from-[var(--red)] to-[var(--red-deep)] rounded-2xl flex items-center justify-center shadow-xl rotate-3">
                    <i class="fa-solid fa-id-card-clip text-[var(--gold)] text-3xl"></i>
                </div>
            </div>

            <h2 class="font-cinzel text-3xl font-bold text-[var(--ink)] tracking-wide">Lengkapi Profil</h2>
            <p class="text-[var(--red)] text-xs font-bold uppercase tracking-[0.3em] mt-3">Identitas Kontingen & Organisasi</p>
            
            <div class="mt-6 flex items-center justify-center gap-3">
                <div class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--gold)]"></div>
                <p class="text-[10px] font-medium text-[var(--smoke)] uppercase tracking-widest">
                    Sesi Pendaftaran: {{ now()->translatedFormat('d F Y') }}
                </p>
                <div class="h-px w-8 bg-gradient-to-l from-transparent to-[var(--gold)]"></div>
            </div>
        </div>

        <!-- form section -->
        <form wire:submit.prevent="saveProfile" class="px-6 md:px-10 py-10 space-y-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- contingent name -->
                <div class="space-y-2.5 md:col-span-2">
                    <label class="block text-[11px] font-bold text-[var(--ink)] uppercase tracking-widest px-1">
                        Nama Kontingen
                    </label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--smoke)] group-focus-within:text-[var(--red)] transition-colors">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </span>
                        <input type="text" wire:model.defer="contingent_name" placeholder="Contoh: PERKEMI KOTA SURABAYA"
                            class="w-full pl-11 pr-4 py-4 bg-[var(--paper)] border border-[var(--paper2)] rounded-xl focus:border-[var(--red)] focus:ring-4 focus:ring-red-600/5 outline-none transition-all text-[var(--ink)] font-medium placeholder:text-[var(--smoke)]">
                    </div>
                    @error('contingent_name') <p class="text-red-600 text-[10px] font-bold mt-1.5 px-1 tracking-wide">{{ $message }}</p> @enderror
                </div>

                <!-- city -->
                <div class="space-y-2.5">
                    <label class="block text-[11px] font-bold text-[var(--ink)] uppercase tracking-widest px-1">
                        Kabupaten / Kota
                    </label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--smoke)] group-focus-within:text-[var(--red)] transition-colors">
                            <i class="fa-solid fa-map-location-dot text-sm"></i>
                        </span>
                        <input type="text" wire:model.defer="contingent_city" placeholder="Nama Kota"
                            class="w-full pl-11 pr-4 py-4 bg-[var(--paper)] border border-[var(--paper2)] rounded-xl focus:border-[var(--red)] focus:ring-4 focus:ring-red-600/5 outline-none transition-all text-[var(--ink)] font-medium placeholder:text-[var(--smoke)]">
                    </div>
                    @error('contingent_city') <p class="text-red-600 text-[10px] font-bold mt-1.5 px-1 tracking-wide">{{ $message }}</p> @enderror
                </div>

                <!-- leader name -->
                <div class="space-y-2.5">
                    <label class="block text-[11px] font-bold text-[var(--ink)] uppercase tracking-widest px-1">
                        Manager / Ketua
                    </label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--smoke)] group-focus-within:text-[var(--red)] transition-colors">
                            <i class="fa-solid fa-user-tie text-sm"></i>
                        </span>
                        <input type="text" wire:model.defer="leader_name" placeholder="Nama Lengkap"
                            class="w-full pl-11 pr-4 py-4 bg-[var(--paper)] border border-[var(--paper2)] rounded-xl focus:border-[var(--red)] focus:ring-4 focus:ring-red-600/5 outline-none transition-all text-[var(--ink)] font-medium placeholder:text-[var(--smoke)]">
                    </div>
                    @error('leader_name') <p class="text-red-600 text-[10px] font-bold mt-1.5 px-1 tracking-wide">{{ $message }}</p> @enderror
                </div>

                <!-- phone -->
                <div class="space-y-2.5">
                    <label class="block text-[11px] font-bold text-[var(--ink)] uppercase tracking-widest px-1">
                        Nomor HP / WA
                    </label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--smoke)] group-focus-within:text-[var(--red)] transition-colors">
                            <i class="fa-solid fa-phone-volume text-sm"></i>
                        </span>
                        <input type="tel" wire:model.defer="leader_phone" placeholder="08xxxx"
                            class="w-full pl-11 pr-4 py-4 bg-[var(--paper)] border border-[var(--paper2)] rounded-xl focus:border-[var(--red)] focus:ring-4 focus:ring-red-600/5 outline-none transition-all text-[var(--ink)] font-medium placeholder:text-[var(--smoke)]">
                    </div>
                    @error('leader_phone') <p class="text-red-600 text-[10px] font-bold mt-1.5 px-1 tracking-wide">{{ $message }}</p> @enderror
                </div>

                <!-- address -->
                <div class="space-y-2.5 md:col-span-2">
                    <label class="block text-[11px] font-bold text-[var(--ink)] uppercase tracking-widest px-1">
                        Alamat Sekretariat
                    </label>
                    <div class="relative group">
                        <span class="absolute left-4 top-4 text-[var(--smoke)] group-focus-within:text-[var(--red)] transition-colors">
                            <i class="fa-solid fa-house-chimney text-sm"></i>
                        </span>
                        <textarea wire:model.defer="address" placeholder="Alamat Lengkap Kantor/Sekretariat" rows="3"
                            class="w-full pl-11 pr-4 py-4 bg-[var(--paper)] border border-[var(--paper2)] rounded-xl focus:border-[var(--red)] focus:ring-4 focus:ring-red-600/5 outline-none transition-all text-[var(--ink)] font-medium placeholder:text-[var(--smoke)] resize-none"></textarea>
                    </div>
                    @error('address') <p class="text-red-600 text-[10px] font-bold mt-1.5 px-1 tracking-wide">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- submit action -->
            <div class="pt-6">
                <button type="submit" wire:loading.attr="disabled"
                    class="group w-full relative py-4 rounded-xl text-white font-bold text-sm tracking-widest uppercase overflow-hidden shadow-xl transition-all active:scale-[0.98]"
                    style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 8px 25px rgba(192,57,43,.3);">
                    
                    <span wire:loading.remove class="flex items-center justify-center gap-3">
                        Selesaikan Profil & Masuk
                        <i class="fa-solid fa-arrow-right-long group-hover:translate-x-1.5 transition-transform"></i>
                    </span>
                    
                    <span wire:loading class="flex items-center justify-center gap-3">
                        <i class="fa-solid fa-circle-notch animate-spin"></i>
                        Menyimpan Data...
                    </span>

                    <!-- shimmer effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>
            </div>
        </form>

        <!-- card footer -->
        <div class="px-6 md:px-10 py-6 bg-[var(--paper)] border-t border-[var(--paper2)] flex items-center justify-center">
            <p class="text-[10px] font-medium text-[var(--smoke)] uppercase tracking-widest text-center leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1 text-[var(--gold)]"></i>
                Pastikan data yang Anda masukkan sudah benar untuk keperluan verifikasi.
            </p>
        </div>
    </div>
</div>