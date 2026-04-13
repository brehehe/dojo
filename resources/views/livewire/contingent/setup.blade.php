<div
    class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-[3rem] p-10 shadow-2xl shadow-black/50 max-w-full mx-auto">
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active,
        textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #1a3a4f inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
    <div class="flex flex-col items-center mb-12 text-center">
        <div class="relative mb-6">
            <div class="absolute inset-0 bg-blue-600 rounded-3xl blur-2xl opacity-40 animate-pulse"></div>
            <div
                class="relative w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl rotate-3 transition-transform hover:rotate-0 duration-500">
                <i class="fas fa-id-card text-white text-4xl"></i>
            </div>
        </div>
        <h2 class="text-4xl font-black text-white tracking-tighter uppercase leading-none drop-shadow-sm">Lengkapi
            Profil</h2>
        <p class="text-blue-400 text-[11px] font-black uppercase tracking-[0.4em] mt-3 opacity-80">Identitas Kontingen &
            Organisasi</p>
    </div>

    <form wire:submit.prevent="saveProfile" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
            <div>
                <label class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Nama
                    Kontingen</label>
               <input type="text" wire:model.defer="contingent_name" placeholder="Contoh: PERKEMI KOTA SURABAYA"
                        class="w-full pl-4 pr-8 py-5 bg-slate-950/40 border-2 border-white/10 rounded-full focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/30 shadow-inner">
                @error('contingent_name') <span
                class="text-red-400 text-[10px] font-bold mt-2 block px-6">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Kabupaten
                    / Kota</label>
<input type="text" wire:model.defer="contingent_city" placeholder="Pilih Kota"
                        class="w-full pl-4 pr-8 py-5 bg-slate-950/40 border-2 border-white/10 rounded-full focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/30 shadow-inner">
                @error('contingent_city') <span
                class="text-red-400 text-[10px] font-bold mt-2 block px-6">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Nama
                    Manager / Ketua</label>
                <input type="text" wire:model.defer="leader_name" placeholder="Nama Lengkap"
                        class="w-full pl-4 pr-8 py-5 bg-slate-950/40 border-2 border-white/10 rounded-full focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/30 shadow-inner">
                @error('leader_name') <span
                class="text-red-400 text-[10px] font-bold mt-2 block px-6">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Nomor HP /
                    WA</label>
                <input type="tel" wire:model.defer="leader_phone" placeholder="08xxxx"
                        class="w-full pl-4 pr-8 py-5 bg-slate-950/40 border-2 border-white/10 rounded-full focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/30 shadow-inner">
                @error('leader_phone') <span
                class="text-red-400 text-[10px] font-bold mt-2 block px-6">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Alamat
                    Sekretariat</label>
                <textarea wire:model.defer="address" placeholder="Alamat Lengkap Kantor/Sekretariat" rows="3"
                        class="w-full pl-4 pr-8 py-5 bg-slate-950/40 border-2 border-white/10 rounded-[2.5rem] focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/40 resize-none shadow-inner"></textarea>
                @error('address') <span class="text-red-400 text-[10px] font-bold mt-2 block px-6">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="pt-8">
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-700 text-white py-6 rounded-full font-black text-sm uppercase tracking-widest shadow-2xl shadow-blue-600/40 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                <span wire:loading.remove class="flex items-center gap-3">
                    Selesaikan Profil & Masuk Dashboard
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </span>
                <span wire:loading><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
            </button>
        </div>
    </form>
</div>