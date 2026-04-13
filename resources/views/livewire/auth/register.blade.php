<div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-[3rem] p-10 shadow-2xl shadow-black/50">
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #1a3a4f inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
    <div class="flex flex-col items-center mb-10 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center mb-4 shadow-xl rotate-3">
             <img src="{{ asset('build/logo.jpeg') }}" alt="Logo">
        </div>
        <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">Daftar Akun Baru</h2>
        <p class="text-orange-400 text-[10px] font-black uppercase tracking-[0.3em] mt-2">Registrasi Kontingen Smart-Perkemi</p>
    </div>

    <form wire:submit.prevent="register" class="space-y-4">
        <div>
            <label for="name" class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Nama Lengkap / Jabatan</label>
           <input type="text" id="name" wire:model.defer="name" placeholder="Nama Manager/Official" 
                       class="w-full pl-6 pr-8 py-3 bg-slate-950/30 border-2 border-white/10 rounded-full focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/20">
            @error('name') <span class="text-red-400 text-[10px] font-bold mt-1 block px-6">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Alamat Email</label>
            <input type="email" id="email" wire:model.defer="email" placeholder="contoh@kempo.id" 
                       class="w-full pl-6 pr-8 py-3 bg-slate-950/30 border-2 border-white/10 rounded-full focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/20">
            @error('email') <span class="text-red-400 text-[10px] font-bold mt-1 block px-6">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password" class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Kata Sandi</label>
            <input type="password" id="password" wire:model.defer="password" placeholder="••••••••" 
                       class="w-full pl-6 pr-8 py-3 bg-slate-950/30 border-2 border-white/10 rounded-full focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/20">
            @error('password') <span class="text-red-400 text-[10px] font-bold mt-1 block px-6">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-[10px] font-black text-white/50 uppercase tracking-widest mb-2 px-6">Konfirmasi Kata Sandi</label>
            <input type="password" id="password_confirmation" wire:model.defer="password_confirmation" placeholder="••••••••" 
                       class="w-full pl-6 pr-8 py-3 bg-slate-950/30 border-2 border-white/10 rounded-full focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all text-slate-100 font-semibold placeholder:text-white/20">
        </div>

        <div class="pt-4">
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-700 hover:to-amber-600 text-white py-4 rounded-full font-black text-sm uppercase tracking-widest shadow-2xl shadow-orange-600/30 transition-all active:scale-95 flex items-center justify-center gap-3">
                <span wire:loading.remove>Daftar Sekarang</span>
                <span wire:loading><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
            </button>
        </div>
    </form>
    
    <div class="mt-8 pt-8 border-t border-white/5 text-center flex flex-col gap-4">
        <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest leading-none">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-orange-400 hover:text-white transition-colors">Masuk di sini</a>
        </p>
        <a href="/" class="text-[10px] font-black text-white/20 uppercase tracking-widest hover:text-white transition-colors leading-none block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
        </a>
    </div>
</div>
