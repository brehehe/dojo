<div class="flex items-center justify-center w-full my-auto px-4 md:px-8">

  <!-- ── content wrapper ── -->
  <div class="flex flex-col lg:flex-row items-center justify-center gap-0 lg:gap-8 w-full max-w-6xl">
    
    <!-- ── left panel (decorative) ── -->
    <div class="hidden lg:flex flex-col justify-between w-[420px] min-h-[640px] relative z-10 pr-12 animate-fade-up">

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
          Pendaftaran<br>
          <span style="color:#d4a843;">Kontingen</span><br>
          Resmi
        </h2>

        <p class="panel-desc dark:text-smoke text-sm leading-relaxed max-w-xs">
          Daftarkan kontingen Anda untuk mengelola atlet, nomor tanding, dan verifikasi administrasi kejuaraan secara terpadu.
        </p>

        <!-- stats chips -->
        <div class="flex gap-3 flex-wrap">
          <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
            <i class="fa-solid fa-envelope-circle-check text-gold text-xs"></i>
            <span class="panel-title dark:text-white text-xs font-medium">Password via Email</span>
          </div>
          <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
            <i class="fa-solid fa-shield-halved text-red text-xs"></i>
            <span class="panel-title dark:text-white text-xs font-medium">Akses Terenkripsi</span>
          </div>
          <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
            <i class="fa-solid fa-trophy text-gold text-xs"></i>
            <span class="panel-title dark:text-white text-xs font-medium">PB Perkemi</span>
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

    <!-- ── registration card ── -->
    <div class="relative z-10 w-full max-w-xl animate-fade-up-2 my-8 lg:my-0">
      <div class="login-card rounded-2xl border overflow-hidden shadow-2xl">

        @if($registrationSuccess)
          <!-- ── SUCCESS STATE ── -->
          <div class="px-6 md:px-10 py-10 text-center space-y-6 animate-fade-up">
            <!-- success icon -->
            <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center bg-emerald-500/10 border-2 border-emerald-500/30 text-emerald-500 text-3xl shadow-lg shadow-emerald-500/10">
              <i class="fa-solid fa-check"></i>
            </div>

            <div class="space-y-2">
              <h2 class="font-cinzel text-2xl font-bold dark:text-white text-stone-900">
                Pendaftaran Berhasil!
              </h2>
              <p class="text-sm dark:text-smoke text-stone-600 max-w-md mx-auto leading-relaxed">
                Akun untuk kontingen <strong class="text-gold font-bold">{{ $registeredContingent }}</strong> telah berhasil dibuat di sistem.
              </p>
            </div>

            <!-- info box -->
            <div class="p-5 rounded-xl border bg-black/5 dark:bg-white/5 border-black/10 dark:border-white/10 text-left space-y-3">
              <div class="flex items-start gap-3">
                <i class="fa-solid fa-envelope text-gold text-lg mt-0.5"></i>
                <div class="space-y-1 text-xs">
                  <p class="font-bold dark:text-white text-stone-900">
                    Kata Sandi Dikirimkan Melalui Email
                  </p>
                  <p class="dark:text-smoke text-stone-600 leading-relaxed">
                    Kami telah mengirimkan kata sandi login dan detail akun ke:
                    <br>
                    <span class="font-mono font-bold text-sm text-red-600 dark:text-red-400 mt-1 inline-block">{{ $registeredEmail }}</span>
                  </p>
                </div>
              </div>

              @if(!$emailSent)
                <div class="p-3 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-700 dark:text-amber-400 text-xs">
                  <i class="fa-solid fa-circle-info mr-1"></i>
                  Pengiriman email tertunda atau mode lokal (log) aktif. Kredensial telah dicatat di log sistem.
                </div>
              @endif

              <p class="text-[11px] dark:text-smoke/70 text-stone-500 italic pt-1">
                * Silakan periksa folder <strong>Inbox</strong> atau folder <strong>Spam</strong> email Anda untuk melihat kata sandi.
              </p>
            </div>

            <!-- action buttons -->
            <div class="pt-2 space-y-3">
              <a href="{{ route('login') }}"
                 class="submit-btn block w-full py-3.5 rounded-xl text-white font-semibold text-sm tracking-wide text-center transition-all duration-300 shadow-lg"
                 style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 4px 20px rgba(192,57,43,.4);">
                <i class="fa-solid fa-right-to-bracket text-gold mr-2"></i>
                Menuju Halaman Login
              </a>

              <a href="/" class="block text-xs panel-footer dark:text-smoke/60 text-stone-500 hover:dark:text-white hover:text-stone-800 transition-colors uppercase tracking-wider">
                <i class="fa-solid fa-arrow-left text-[9px] mr-1"></i> Kembali ke Beranda
              </a>
            </div>
          </div>

        @else
          <!-- ── REGISTRATION FORM STATE ── -->

          <!-- card header -->
          <div class="px-6 md:px-8 pt-8 pb-5 card-divider border-b">
            <!-- mobile brand (shown only on mobile) -->
            <div class="flex items-center gap-3 mb-5 lg:hidden">
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

            <h1 class="font-cinzel card-h1 dark:text-white text-xl md:text-2xl font-bold tracking-wide">Daftar Akun Kontingen</h1>
            <p class="card-p dark:text-smoke text-xs md:text-sm mt-1.5 leading-relaxed">
              Lengkapi formulir pendaftaran. Kata sandi akun akan otomatis di-generate dan dikirimkan ke email Anda.
            </p>

            <!-- Password Notice Badge -->
            <div class="mt-4 flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-700 dark:text-amber-400 text-xs">
              <i class="fa-solid fa-key text-gold text-sm flex-shrink-0"></i>
              <span>Kata sandi akan dikirim langsung ke email yang didaftarkan.</span>
            </div>
          </div>

          <!-- form -->
          <form wire:submit.prevent="register" class="px-6 md:px-8 py-6 space-y-4">

            <!-- Section 1: Data Manager / Akun -->
            <div class="space-y-4">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red"></span>
                <p class="text-[11px] font-bold tracking-widest uppercase label-text dark:text-smoke">
                  Data Penanggung Jawab / Official
                </p>
              </div>

              <!-- grid 2 cols: Name & Phone -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                <!-- name -->
                <div class="space-y-1.5">
                  <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Nama Manager / Official <span class="text-red-500 font-bold">*</span>
                  </label>
                  <div class="relative kempo-input-group">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                      <i class="fa-solid fa-user-tie"></i>
                    </span>
                    <input wire:model.blur="name" type="text" required minlength="3" placeholder="Nama Lengkap"
                      class="kempo-input w-full border rounded-xl pl-12 pr-4 py-2.5 text-sm font-dm @error('name') is-invalid @enderror">
                  </div>
                  @error('name')
                    <p class="kempo-error-msg">
                      <i class="fa-solid fa-circle-exclamation text-xs"></i>
                      <span>{{ $message }}</span>
                    </p>
                  @enderror
                </div>

                <!-- leader phone -->
                <div class="space-y-1.5">
                  <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    No. HP / WhatsApp <span class="text-red-500 font-bold">*</span>
                  </label>
                  <div class="relative kempo-input-group">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                      <i class="fa-solid fa-phone"></i>
                    </span>
                    <input wire:model.blur="leader_phone" type="tel" required minlength="8" placeholder="08123456789"
                      class="kempo-input w-full border rounded-xl pl-12 pr-4 py-2.5 text-sm font-dm @error('leader_phone') is-invalid @enderror">
                  </div>
                  @error('leader_phone')
                    <p class="kempo-error-msg">
                      <i class="fa-solid fa-circle-exclamation text-xs"></i>
                      <span>{{ $message }}</span>
                    </p>
                  @enderror
                </div>
              </div>

              <!-- email -->
              <div class="space-y-1.5">
                <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                  Alamat Email (Untuk Login & Pengiriman Password) <span class="text-red-500 font-bold">*</span>
                </label>
                <div class="relative kempo-input-group">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                    <i class="fa-solid fa-envelope"></i>
                  </span>
                  <input wire:model.blur="email" type="email" required placeholder="contoh@kempo.id"
                    class="kempo-input w-full border rounded-xl pl-12 pr-4 py-2.5 text-sm font-dm @error('email') is-invalid @enderror">
                </div>
                @error('email')
                  <p class="kempo-error-msg">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i>
                    <span>{{ $message }}</span>
                  </p>
                @enderror
              </div>
            </div>

            <!-- Section 2: Data Kontingen -->
            <div class="space-y-4 pt-2">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-gold"></span>
                <p class="text-[11px] font-bold tracking-widest uppercase label-text dark:text-smoke">
                  Profil Kontingen
                </p>
              </div>

              <!-- grid 2 cols: Contingent Name & City -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                <!-- contingent name -->
                <div class="space-y-1.5">
                  <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Nama Kontingen <span class="text-red-500 font-bold">*</span>
                  </label>
                  <div class="relative kempo-input-group">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                      <i class="fa-solid fa-flag"></i>
                    </span>
                    <input wire:model.blur="contingent_name" type="text" required minlength="3" placeholder="Contoh: Pengkab Kempo Banyuwangi"
                      class="kempo-input w-full border rounded-xl pl-12 pr-4 py-2.5 text-sm font-dm @error('contingent_name') is-invalid @enderror">
                  </div>
                  @error('contingent_name')
                    <p class="kempo-error-msg">
                      <i class="fa-solid fa-circle-exclamation text-xs"></i>
                      <span>{{ $message }}</span>
                    </p>
                  @enderror
                </div>

                <!-- city -->
                <div class="space-y-1.5">
                  <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                    Kabupaten / Kota <span class="text-red-500 font-bold">*</span>
                  </label>
                  <div class="relative kempo-input-group">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                      <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <input wire:model.blur="contingent_city" type="text" required minlength="2" placeholder="Contoh: Kota Surabaya"
                      class="kempo-input w-full border rounded-xl pl-12 pr-4 py-2.5 text-sm font-dm @error('contingent_city') is-invalid @enderror">
                  </div>
                  @error('contingent_city')
                    <p class="kempo-error-msg">
                      <i class="fa-solid fa-circle-exclamation text-xs"></i>
                      <span>{{ $message }}</span>
                    </p>
                  @enderror
                </div>
              </div>

              <!-- address -->
              <div class="space-y-1.5">
                <label class="block text-[10px] font-semibold tracking-widest uppercase label-text dark:text-smoke">
                  Alamat Kantor / Sekretariat Kontingen <span class="text-red-500 font-bold">*</span>
                </label>
                <div class="relative kempo-input-group">
                  <span class="absolute left-4 top-3 input-icon text-sm pointer-events-none flex items-center justify-center w-5">
                    <i class="fa-solid fa-map-location-dot"></i>
                  </span>
                  <textarea wire:model.blur="address" rows="2" required minlength="5" placeholder="Alamat lengkap sekretariat / dojo kontingen"
                    class="kempo-input w-full border rounded-xl pl-12 pr-4 py-2 text-sm font-dm resize-none @error('address') is-invalid @enderror"></textarea>
                </div>
                @error('address')
                  <p class="kempo-error-msg">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i>
                    <span>{{ $message }}</span>
                  </p>
                @enderror
              </div>
            </div>

            <!-- gold divider -->
            <div class="gold-line animate-fade-up-4 pt-2"></div>

            <!-- submit -->
            <button type="submit" wire:loading.attr="disabled" class="submit-btn relative w-full py-3.5 rounded-xl text-white font-semibold text-sm tracking-wide
                     transition-all duration-300 overflow-hidden animate-fade-up-4 shadow-lg"
              style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 4px 20px rgba(192,57,43,.4);">
              <span wire:loading.remove wire:target="register" class="flex items-center justify-center gap-2.5">
                <i class="fa-solid fa-paper-plane text-gold"></i>
                Daftarkan Kontingen & Kirim Akun
              </span>
              <span wire:loading wire:target="register" class="flex items-center justify-center gap-2.5">
                <i class="fa-solid fa-circle-notch animate-spin text-gold"></i>
                Memproses Pendaftaran & Mengirim Email...
              </span>
            </button>

          </form>

          <!-- card footer -->
          <div class="px-8 py-4 card-divider border-t flex items-center justify-center">
            <p class="label-text dark:text-smoke/80 text-xs tracking-wide">
              Sudah punya akun? <a href="{{ route('login') }}" class="font-bold hover:underline transition-colors" style="color:#d4a843;">Masuk di sini</a>
            </p>
          </div>

        @endif

      </div>

      <!-- back to home -->
      <a href="/" class="text-center block panel-footer dark:text-smoke/40 text-[10px] tracking-wider mt-4 uppercase animate-fade-up-4 hover:dark:text-white transition-colors">
        <i class="fa-solid fa-arrow-left text-[8px] mr-1"></i>
        Kembali ke Beranda
      </a>
    </div>

  </div>
</div>