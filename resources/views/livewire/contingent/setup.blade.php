<div class="max-w-3xl mx-auto py-10 px-4 animate-fade-up">

    <!-- card surface -->
    <div class="bg-white rounded-2xl border border-[var(--paper2)] shadow-xl overflow-hidden">

        <!-- header section -->
        <div class="px-6 md:px-10 pt-12 pb-8 text-center border-b border-[var(--paper2)] relative overflow-hidden">
            <!-- decorative background -->
            <div
                class="absolute -top-10 -right-10 w-40 h-40 bg-[radial-gradient(circle,rgba(212,168,67,0.1)_0%,transparent_70%)] pointer-events-none">
            </div>

            <div class="relative inline-block mb-6">
                <div class="absolute inset-0 bg-red-600 rounded-3xl blur-2xl opacity-20 animate-pulse"></div>
                <div
                    class="relative w-20 h-20 bg-gradient-to-br from-[var(--red)] to-[var(--red-deep)] rounded-2xl flex items-center justify-center shadow-xl rotate-3">
                    <i class="fa-solid fa-id-card-clip text-[var(--gold)] text-3xl"></i>
                </div>
            </div>

            <h2 class="font-cinzel text-3xl font-bold text-[var(--ink)] tracking-wide">Lengkapi Profil</h2>
            <p class="text-[var(--red)] text-xs font-bold uppercase tracking-[0.3em] mt-3">Identitas Kontingen &
                Organisasi</p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <div class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--gold)]"></div>
                <p class="text-[10px] font-medium text-[var(--smoke)] uppercase tracking-widest">
                    Sesi Pendaftaran: {{ now()->translatedFormat('d F Y') }}
                </p>
                <div class="h-px w-8 bg-gradient-to-l from-transparent to-[var(--gold)]"></div>
            </div>
        </div>

        <!-- form section -->
        <form wire:submit.prevent="saveProfile" class="px-6 md:px-8 py-7 space-y-5">
            <div class="space-y-2 animate-fade-up-3">
                <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Nama Kontingen
                </label>
                <div class="relative">
                    <input wire:model.defer="contingent_name" type="text" placeholder="Nama Kontingen"
                        class="kempo-input w-full border rounded-xl pl-4 pr-4 py-3 text-sm font-dm">
                </div>
                @error('contingent_name')
                    <p style="color: red;" class="text-[10px] mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="space-y-2 animate-fade-up-3">
                <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Kabupaten / Kota
                </label>
                <div class="relative">
                    <input wire:model.defer="contingent_city" type="text" placeholder="Kabupaten / Kota"
                        class="kempo-input w-full border rounded-xl pl-4 pr-4 py-3 text-sm font-dm">
                </div>
                @error('contingent_city')
                    <p style="color: red;" class="text-[10px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 animate-fade-up-3">
                <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Manager / Ketua
                </label>
                <div class="relative">
                    <input wire:model.defer="leader_name" type="text" placeholder="Manager / Ketua"
                        class="kempo-input w-full border rounded-xl pl-4 pr-4 py-3 text-sm font-dm">
                </div>
                @error('leader_name')
                    <p style="color: red;" class="text-[10px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 animate-fade-up-3">
                <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Nomor HP / WA
                </label>
                <div class="relative">
                    <input wire:model.defer="leader_phone" type="number" placeholder="08xxxx"
                        class="kempo-input w-full border rounded-xl pl-4 pr-4 py-3 text-sm font-dm">
                </div>
                @error('leader_phone')
                    <p style="color: red;" class="text-[10px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 animate-fade-up-3">
                <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Alamat Kantor
                </label>
                <div class="relative">
                    <input wire:model.defer="address" type="text" placeholder="Alamat Kantor"
                        class="kempo-input w-full border rounded-xl pl-4 pr-4 py-3 text-sm font-dm">
                </div>
                @error('address')
                    <p style="color: red;" class="text-red text-[10px] mt-1">{{ $message }}</p>
                @enderror
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
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700">
                    </div>
                </button>
            </div>
        </form>

        <!-- card footer -->
        <div
            class="px-6 md:px-10 py-6 bg-[var(--paper)] border-t border-[var(--paper2)] flex items-center justify-center">
            <p
                class="text-[10px] font-medium text-[var(--smoke)] uppercase tracking-widest text-center leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1 text-[var(--gold)]"></i>
                Pastikan data yang Anda masukkan sudah benar untuk keperluan verifikasi.
            </p>
        </div>
    </div>
</div>
