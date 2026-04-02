<div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-[3rem] p-10 shadow-2xl shadow-black/50">
    <div class="flex flex-col items-center mb-10 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-amber-600 rounded-3xl flex items-center justify-center mb-6 shadow-xl rotate-3">
            <i class="fas fa-user-shield text-4xl text-white"></i>
        </div>
        <h2 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">Smart-Perkemi</h2>
        <p class="text-orange-400 text-[10px] font-black uppercase tracking-[0.3em] mt-2">Portal Otentikasi Admin</p>
    </div>

    <form wire:submit.prevent="login" class="space-y-6">
        <div>
            <label for="email" class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Alamat Email</label>
            <div class="relative group">
                <i class="fas fa-envelope absolute left-6 top-1/2 -translate-y-1/2 text-white/20 group-focus-within:text-orange-500 transition-colors"></i>
                <input type="email" id="email" wire:model.defer="email" placeholder="contoh@kempo.id" 
                       class="w-full pl-14 pr-8 py-4 bg-white/5 border-2 border-white/10 rounded-full focus:border-orange-500 outline-none transition-all text-white font-semibold placeholder:text-white/20">
            </div>
            @error('email') <span class="text-red-400 text-[10px] font-bold mt-2 block px-6">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password" class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Kata Sandi</label>
            <div class="relative group">
                <i class="fas fa-lock absolute left-6 top-1/2 -translate-y-1/2 text-white/20 group-focus-within:text-orange-500 transition-colors"></i>
                <input type="password" id="password" wire:model.defer="password" placeholder="••••••••" 
                       class="w-full pl-14 pr-8 py-4 bg-white/5 border-2 border-white/10 rounded-full focus:border-orange-500 outline-none transition-all text-white font-semibold placeholder:text-white/20">
            </div>
            @error('password') <span class="text-red-400 text-[10px] font-bold mt-2 block px-6">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between px-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer group">
                <div class="relative w-4 h-4 rounded border-2 border-white/20 group-hover:border-orange-500 transition-colors">
                    <input type="checkbox" wire:model="remember" class="absolute inset-0 opacity-0 cursor-pointer peer">
                    <i class="fas fa-check absolute inset-0 text-[10px] text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                </div>
                <span class="text-xs text-white/40 font-bold group-hover:text-white transition-colors">Ingat Saya</span>
            </label>
            <a href="#" class="text-xs text-orange-400/50 hover:text-orange-400 font-bold transition-colors">Lupa Password?</a>
        </div>

        <div class="pt-4">
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-700 hover:to-amber-600 text-white py-5 rounded-full font-black text-sm uppercase tracking-widest shadow-2xl shadow-orange-600/30 transition-all active:scale-95 flex items-center justify-center gap-3">
                <span wire:loading.remove>Masuk ke Panel</span>
                <span wire:loading><i class="fas fa-spinner fa-spin"></i> Memuat...</span>
            </button>
        </div>
    </form>
    
    <div class="mt-10 pt-10 border-t border-white/5 text-center">
        <a href="/" class="text-[10px] font-black text-white/30 uppercase tracking-widest hover:text-white transition-colors leading-none block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
        </a>
    </div>
</div>
