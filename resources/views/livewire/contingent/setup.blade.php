<div class="flex items-center justify-center w-full my-auto px-4 md:px-8">

  <!-- ── content wrapper ── -->
  <div class="flex flex-col lg:flex-row items-center justify-center gap-0 lg:gap-8 w-full max-w-6xl">
    
    <!-- ── left panel (decorative) ── -->
    <div class="hidden lg:flex flex-col justify-between w-[420px] min-h-[620px] relative z-10 pr-12 animate-fade-up">

      <!-- brand mark -->
      <div class="flex items-center gap-4">
        <div
          class="w-11 h-11 rounded-xl flex items-center justify-center font-cinzel text-gold font-bold text-xl flex-shrink-0"
          style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 4px 20px rgba(192,57,43,.5);">
          SK
        </div>
        <div>
          <p class="font-cinzel panel-title dark:text-white text-xs font-bold tracking-widest uppercase leading-tight">
            Shorinji Kempo</p>
          <p class="panel-desc dark:text-smoke text-[9px] tracking-[.16em] uppercase mt-0.5">Indonesia · Portal Kontingen</p>
        </div>
      </div>

      <!-- hero text -->
      <div class="space-y-6 animate-fade-up-2">
        <h2 class="font-cinzel panel-title dark:text-white text-4xl font-bold leading-tight tracking-wide">
          Lengkapi<br>
          <span style="color:#d4a843;">Profil</span><br>
          Kontingen
        </h2>

        <p class="panel-desc dark:text-smoke text-sm leading-relaxed max-w-xs">
          Lengkapi identitas resmi kontingen Anda untuk pendaftaran atlet, penentuan nomor tanding, dan verifikasi administrasi kejuaraan.
        </p>

        <!-- stats chips -->
        <div class="flex gap-3 flex-wrap">
          <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
            <i class="fa-solid fa-id-card-clip text-red text-xs"></i>
            <span class="panel-title dark:text-white text-xs font-medium">Verifikasi Resmi</span>
          </div>
          <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
            <i class="fa-solid fa-shield-halved text-gold text-xs"></i>
            <span class="panel-title dark:text-white text-xs font-medium">PB Perkemi</span>
          </div>
          <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
            <i class="fa-solid fa-calendar-check text-red text-xs"></i>
            <span class="panel-title dark:text-white text-xs font-medium">Musim {{ date('Y') }}</span>
          </div>
        </div>
      </div>

      <!-- footer note -->
      <p class="panel-footer dark:text-smoke/50 text-[10px] tracking-wider uppercase animate-fade-up-3">
        © {{ date('Y') }} Pengurus Besar Shorinji Kempo Indonesia
      </p>

      <div class="deco-stripe"></div>
    </div>

    <!-- ── vertical gold divider ── -->
    <div class="hidden lg:block w-px self-stretch mx-4 my-8 relative z-10 animate-fade-up gold-sep"></div>

    <!-- ── setup card ── -->
    <div class="relative z-10 w-full max-w-lg animate-fade-up-2 my-8 lg:my-0">
      <div class="login-card rounded-2xl border overflow-hidden">

        <!-- card header -->
        <div class="px-6 md:px-8 pt-8 pb-6 card-divider border-b">
          <!-- mobile brand (shown only on mobile) -->
          <div class="flex items-center gap-3 mb-6 lg:hidden">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center font-cinzel text-gold font-bold text-sm"
              style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 4px 14px rgba(192,57,43,.45);">
              SK
            </div>
            <div>
              <p class="font-cinzel section-title dark:text-white text-[10px] font-bold tracking-widest uppercase">
                Shorinji Kempo Indonesia</p>
              <p class="label-text dark:text-smoke text-[9px] tracking-[.14em] uppercase mt-0.5">Portal Kontingen</p>
            </div>
          </div>

          <h1 class="font-cinzel card-h1 dark:text-white text-xl md:text-2xl font-bold tracking-wide">Profil Kontingen</h1>
          <p class="card-p dark:text-smoke text-xs md:text-sm mt-1.5">Lengkapi identitas kontingen untuk mengaktifkan akses pendaftaran.</p>

          @auth
            <div class="mt-3.5 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs">
              <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
              <span>Akun Terhubung: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</span>
            </div>
          @else
            <div class="mt-3.5 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-700 dark:text-amber-400 text-xs">
              <i class="fa-solid fa-circle-info text-amber-500 text-[11px]"></i>
              <span>Pratinjau / Pengisian Data Kontingen Baru</span>
            </div>
          @endauth
        </div>

        <!-- form -->
        <form wire:submit.prevent="saveProfile" class="px-6 md:px-8 py-7 space-y-4">

          <!-- contingent name -->
          <div class="space-y-1.5 animate-fade-up-3">
            <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
              Nama Kontingen
            </label>
            <div class="relative kempo-input-group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                <i class="fa-solid fa-flag"></i>
              </span>
              <input wire:model.defer="contingent_name" type="text" placeholder="Contoh: Pengkab Kempo Banyuwangi"
                class="kempo-input w-full border rounded-xl pl-12 pr-4 py-3 text-sm font-dm">
            </div>
            @error('contingent_name') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- city -->
          <div class="space-y-1.5 animate-fade-up-3">
            <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
              Kabupaten / Kota
            </label>
            <div class="relative kempo-input-group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                <i class="fa-solid fa-location-dot"></i>
              </span>
              <input wire:model.defer="contingent_city" type="text" placeholder="Contoh: Kota Surabaya / Kab. Banyuwangi"
                class="kempo-input w-full border rounded-xl pl-12 pr-4 py-3 text-sm font-dm">
            </div>
            @error('contingent_city') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- grid 2 cols: leader name & phone -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- leader name -->
            <div class="space-y-1.5 animate-fade-up-3">
              <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                Manager / Ketua Kontingen
              </label>
              <div class="relative kempo-input-group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                  <i class="fa-solid fa-user-tie"></i>
                </span>
                <input wire:model.defer="leader_name" type="text" placeholder="Nama Manager"
                  class="kempo-input w-full border rounded-xl pl-12 pr-4 py-3 text-sm font-dm">
              </div>
              @error('leader_name') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- leader phone -->
            <div class="space-y-1.5 animate-fade-up-3">
              <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                Nomor HP / WhatsApp
              </label>
              <div class="relative kempo-input-group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                  <i class="fa-solid fa-phone"></i>
                </span>
                <input wire:model.defer="leader_phone" type="tel" placeholder="08xxxxxxxxxx"
                  class="kempo-input w-full border rounded-xl pl-12 pr-4 py-3 text-sm font-dm">
              </div>
              @error('leader_phone') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
          </div>

          <!-- office address -->
          <div class="space-y-1.5 animate-fade-up-3">
            <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
              Alamat Kantor / Sekretariat
            </label>
            <div class="relative kempo-input-group">
              <span class="absolute left-4 top-3.5 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                <i class="fa-solid fa-map-location-dot"></i>
              </span>
              <textarea wire:model.defer="address" rows="2" placeholder="Alamat lengkap kantor / dojo sekretariat kontingen"
                class="kempo-input w-full border rounded-xl pl-12 pr-4 py-3 text-sm font-dm resize-none"></textarea>
            </div>
            @error('address') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- gold divider -->
          <div class="gold-line animate-fade-up-4 pt-1"></div>

          <!-- submit -->
          <button type="submit" wire:loading.attr="disabled" class="submit-btn relative w-full py-3.5 rounded-xl text-white font-semibold text-sm tracking-wide
                   transition-all duration-300 overflow-hidden animate-fade-up-4"
            style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 4px 20px rgba(192,57,43,.4);">
            <span wire:loading.remove wire:target="saveProfile" class="flex items-center justify-center gap-2.5">
              <i class="fa-solid fa-floppy-disk text-gold"></i>
              Simpan Profil Kontingen
            </span>
            <span wire:loading wire:target="saveProfile" class="flex items-center justify-center gap-2.5">
              <i class="fa-solid fa-circle-notch animate-spin text-gold"></i>
              Menyimpan Data...
            </span>
          </button>

        </form>

        <!-- card footer -->
        <div class="px-8 py-4 card-divider border-t flex items-center justify-between">
          <p class="label-text dark:text-smoke/50 text-[10px] tracking-wide">
            Butuh bantuan? Hubungi <span class="t-sub dark:text-smoke">admin@perkemi.or.id</span>
          </p>
          <div class="flex items-center gap-1.5 animate-shimmer">
            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
            <span class="text-[10px] text-emerald-500 dark:text-emerald-400/80">Sistem Aktif</span>
          </div>
        </div>

      </div>

      <!-- back to home or login -->
      <div class="flex items-center justify-center gap-4 mt-5 animate-fade-up-4 text-[10px] uppercase tracking-wider panel-footer dark:text-smoke/50">
        <a href="/" class="hover:text-stone-900 dark:hover:text-white transition-colors">
          <i class="fa-solid fa-arrow-left text-[8px] mr-1"></i> Beranda
        </a>
        <span>·</span>
        <a href="{{ route('register') }}" class="hover:text-stone-900 dark:hover:text-white transition-colors">
          Daftar Akun Baru
        </a>
        <span>·</span>
        <a href="{{ route('login') }}" class="hover:text-stone-900 dark:hover:text-white transition-colors">
          Masuk Akun
        </a>
      </div>

    </div>

  </div>
</div>
