<div class="flex items-center justify-center w-full">

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
        <p class="panel-desc dark:text-smoke text-[9px] tracking-[.16em] uppercase mt-0.5">Indonesia · Admin Portal</p>
      </div>
    </div>

    <!-- hero text -->
    <div class="space-y-6 animate-fade-up-2">
      <h2 class="font-cinzel panel-title dark:text-white text-4xl font-bold leading-tight tracking-wide">
        Selamat<br>
        <span style="color:#d4a843;">Datang</span><br>
        Kembali
      </h2>

      <p class="panel-desc dark:text-smoke text-sm leading-relaxed max-w-xs">
        Portal administrasi resmi Kejuaraan Nasional Shorinji Kempo Indonesia. Kelola peserta, jadwal, dan data
        kejuaraan secara efisien.
      </p>

      <!-- stats chips -->
      <div class="flex gap-3 flex-wrap">
        <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
          <i class="fa-solid fa-users text-red text-xs"></i>
          <span class="panel-title dark:text-white text-xs font-medium">{{ number_format($totalAthletes) }}
            Peserta</span>
        </div>
        <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
          <i class="fa-solid fa-trophy text-gold text-xs"></i>
          <span class="panel-title dark:text-white text-xs font-medium">{{ $totalProvinces }} Wilayah</span>
        </div>
        <div class="stat-chip flex items-center gap-2 border rounded-xl px-4 py-2.5">
          <i class="fa-solid fa-calendar-check text-red text-xs"></i>
          <span class="panel-title dark:text-white text-xs font-medium">Aktif {{ date('Y') }}</span>
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

  <!-- ── login card ── -->
  <div class="relative z-10 w-full max-w-md animate-fade-up-2">
    <div class="login-card rounded-2xl border overflow-hidden">

      <!-- card header -->
      <div class="px-8 pt-8 pb-6 card-divider border-b">
        <!-- mobile brand (shown only on mobile) -->
        <div class="flex items-center gap-3 mb-6 lg:hidden">
          <div class="w-9 h-9 rounded-lg flex items-center justify-center font-cinzel text-gold font-bold text-sm"
            style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 4px 14px rgba(192,57,43,.45);">
            SK
          </div>
          <div>
            <p class="font-cinzel section-title dark:text-white text-[10px] font-bold tracking-widest uppercase">
              Shorinji Kempo Indonesia</p>
            <p class="label-text dark:text-smoke text-[9px] tracking-[.14em] uppercase mt-0.5">Admin Portal</p>
          </div>
        </div>

        <h1 class="font-cinzel card-h1 dark:text-white text-2xl font-bold tracking-wide">Masuk ke Akun</h1>
        <p class="card-p dark:text-smoke text-sm mt-1.5">Gunakan kredensial administrator Anda untuk melanjutkan.</p>
      </div>

      <!-- form -->
      <form wire:submit.prevent="login" class="px-8 py-7 space-y-5">

        <!-- email -->
        <div class="space-y-2 animate-fade-up-3">
          <label class="block text-xs font-semibold tracking-widest uppercase label-text dark:text-smoke">
            Username / Email
          </label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none">
              <i class="fa-solid fa-user"></i>
            </span>
            <input wire:model="email" type="text" placeholder="admin@perkemi.or.id"
              class="kempo-input w-full border rounded-xl pl-11 pr-4 py-3.5 text-sm font-dm">
          </div>
          @error('email') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- password -->
        <div class="space-y-2 animate-fade-up-3">
          <label class="block text-xs font-semibold tracking-widest uppercase label-text dark:text-smoke">
            Kata Sandi
          </label>
          <div class="relative" x-data="{ show: false }">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 input-icon text-sm pointer-events-none">
              <i class="fa-solid fa-lock"></i>
            </span>
            <input wire:model="password" :type="show ? 'text' : 'password'" placeholder="••••••••"
              class="kempo-input w-full border rounded-xl pl-11 pr-12 py-3.5 text-sm font-dm">
            <button type="button" @click="show = !show"
              class="eye-btn absolute right-4 top-1/2 -translate-y-1/2 text-sm">
              <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
          </div>
          @error('password') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- remember + forgot -->
        <div class="flex items-center justify-between animate-fade-up-3">
          <label class="flex items-center gap-2.5 cursor-pointer select-none">
            <input wire:model="remember" type="checkbox" class="w-3.5 h-3.5 rounded">
            <span class="label-text dark:text-smoke text-xs">Ingat saya</span>
          </label>
        </div>

        <!-- gold divider -->
        <div class="gold-line animate-fade-up-4"></div>

        <!-- submit -->
        <button type="submit" wire:loading.attr="disabled" class="submit-btn relative w-full py-3.5 rounded-xl text-white font-semibold text-sm tracking-wide
                 transition-all duration-300 overflow-hidden animate-fade-up-4"
          style="background:linear-gradient(135deg,#c0392b,#96281b);box-shadow:0 4px 20px rgba(192,57,43,.4);">
          <span wire:loading.remove wire:target="login" class="flex items-center justify-center gap-2.5">
            <i class="fa-solid fa-right-to-bracket text-gold"></i>
            Masuk ke Dashboard
          </span>
          <span wire:loading wire:target="login" class="flex items-center justify-center gap-2.5">
            <i class="fa-solid fa-circle-notch animate-spin text-gold"></i>
            Memverifikasi...
          </span>
        </button>

        <!-- sso divider -->
        <div class="flex items-center gap-3 animate-fade-up-4">
          <div class="flex-1 h-px or-line"></div>
          <span class="label-text dark:text-smoke/50 text-[10px] tracking-wider uppercase">atau</span>
          <div class="flex-1 h-px or-line"></div>
        </div>

        <!-- SSO button -->
        <button type="button" class="sso-btn w-full py-3 rounded-xl border text-sm font-medium flex items-center justify-center gap-3
                 transition-all duration-200 animate-fade-up-4">
          <i class="fa-solid fa-shield-halved text-gold text-xs"></i>
          Masuk dengan SSO Perkemi
        </button>

      </form>

      <!-- card footer -->
      <div class="px-8 py-5 card-divider border-t flex items-center justify-between">
        <p class="label-text dark:text-smoke/50 text-[10px] tracking-wide">
          Butuh akses? Hubungi <span class="t-sub dark:text-smoke">admin@perkemi.or.id</span>
        </p>
        <div class="flex items-center gap-1.5 animate-shimmer">
          <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
          <span class="text-[10px] text-emerald-500 dark:text-emerald-400/80">Sistem Aktif</span>
        </div>
      </div>

    </div>

    <!-- security notice -->
    <p class="text-center panel-footer dark:text-smoke/40 text-[10px] tracking-wider mt-5 uppercase animate-fade-up-4">
      <i class="fa-solid fa-lock text-[8px] mr-1"></i>
      Koneksi aman · TLS 1.3 · Akses terbatas
    </p>
  </div>

</div>