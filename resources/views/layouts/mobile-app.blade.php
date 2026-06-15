<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>{{ $title ?? 'Smart Perkemi' }}</title>
  <link rel="shortcut icon" href="/assets/favicon-CK1QI2Xs.ico">

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    /* ══════════════════════════════════════════════════════
   TOKENS (same as premium layout)
══════════════════════════════════════════════════════ */
    :root {
      --red: #c0392b;
      --red-deep: #96281b;
      --red-glow: #e74c3c;
      --gold: #d4a843;
      --gold-lt: #f0c060;
      --ink: #0f0d0b;
      --paper: #f7f4ef;
      --paper2: #ede9e1;
      --smoke: #b5afa6;
      --topbar-h: 60px;
      --bottombar-h: 66px;
      --safe-bottom: env(safe-area-inset-bottom, 0px);
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--paper);
      color: var(--ink);
      min-height: 100vh;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;
    }

    /* ══════════════════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════════════════ */
    .mob-topbar {
      position: fixed;
      top: 0; left: 0; right: 0;
      height: var(--topbar-h);
      background: var(--ink);
      display: flex;
      align-items: center;
      padding: 0 16px;
      gap: 12px;
      z-index: 100;
      border-bottom: 1px solid rgba(255,255,255,.06);
    }

    .mob-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      flex: 1;
    }

    .mob-brand-emblem {
      width: 34px;
      height: 34px;
      background: linear-gradient(135deg, var(--red), var(--red-deep));
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Cinzel', serif;
      color: var(--gold);
      font-size: 15px;
      font-weight: 700;
      flex-shrink: 0;
    }

    .mob-brand-text {
      font-family: 'Cinzel', serif;
      font-size: 11px;
      font-weight: 700;
      color: #fff;
      letter-spacing: .05em;
      text-transform: uppercase;
      line-height: 1.3;
    }

    .mob-brand-text small {
      display: block;
      font-size: 8.5px;
      color: var(--smoke);
      font-weight: 400;
      letter-spacing: .12em;
    }

    .mob-topbar-right {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .mob-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--red), var(--gold));
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Cinzel', serif;
      color: #fff;
      font-size: 12px;
      font-weight: 700;
      flex-shrink: 0;
    }

    .mob-role-badge {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 9.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
    }

    .mob-role-badge.contingent { background: rgba(52,152,219,.2); color: #5dade2; }
    .mob-role-badge.referee    { background: rgba(192,57,43,.2);  color: #e74c3c; }

    /* ══════════════════════════════════════════════════════
   MAIN CONTENT
══════════════════════════════════════════════════════ */
    .mob-main {
      padding-top: var(--topbar-h);
      padding-bottom: calc(var(--bottombar-h) + var(--safe-bottom));
      min-height: 100vh;
    }

    /* ══════════════════════════════════════════════════════
   BOTTOM NAV
══════════════════════════════════════════════════════ */
    .mob-bottombar {
      position: fixed;
      bottom: 0; left: 0; right: 0;
      height: calc(var(--bottombar-h) + var(--safe-bottom));
      padding-bottom: var(--safe-bottom);
      background: var(--ink);
      border-top: 1px solid rgba(255,255,255,.06);
      display: flex;
      align-items: stretch;
      z-index: 100;
    }

    .mob-nav-item {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 4px;
      text-decoration: none;
      color: rgba(255,255,255,.4);
      font-size: 9.5px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .08em;
      transition: color .15s;
      padding: 8px 4px 6px;
      cursor: pointer;
      border: none;
      background: none;
      font-family: 'DM Sans', sans-serif;
    }

    .mob-nav-item i { font-size: 18px; line-height: 1; }

    .mob-nav-item:hover { color: rgba(255,255,255,.7); }

    .mob-nav-item.active {
      color: var(--gold-lt);
      position: relative;
    }

    .mob-nav-item.active::before {
      content: '';
      position: absolute;
      top: 0; left: 50%;
      transform: translateX(-50%);
      width: 32px; height: 2px;
      background: var(--red-glow);
      border-radius: 0 0 4px 4px;
    }

    .mob-nav-item.active i { color: var(--gold-lt); }

    /* Logout danger item */
    .mob-nav-item.danger { color: rgba(231,76,60,.5); }
    .mob-nav-item.danger:hover { color: var(--red-glow); }

    /* ══════════════════════════════════════════════════════
   SHARED CONTENT TOKENS
══════════════════════════════════════════════════════ */
    .mob-page { padding: 16px; }

    .mob-section-title {
      font-family: 'Cinzel', serif;
      font-size: 16px;
      font-weight: 700;
      color: var(--ink);
      margin: 0 0 4px;
    }

    .mob-section-sub {
      font-size: 11.5px;
      color: var(--smoke);
      margin: 0 0 16px;
    }

    /* ══════════════════════════════════════════════════════
   SWEETALERT
══════════════════════════════════════════════════════ */
  </style>

  @stack('styles')
</head>

<body>

  <!-- TOPBAR -->
  <header class="mob-topbar">
    <div class="mob-brand">
      <div class="mob-brand-emblem">拳</div>
      <div class="mob-brand-text">
        Smart Perkemi
        <small>{{ $subtitle ?? 'Portal Member' }}</small>
      </div>
    </div>
    <div class="mob-topbar-right">
      <span class="mob-role-badge {{ $roleClass ?? '' }}">{{ $roleLabel ?? '' }}</span>
      <div class="mob-avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="mob-main">
    {{ $slot }}
  </main>

  <!-- BOTTOM NAV -->
  <nav class="mob-bottombar">
    @include($navInclude ?? 'layouts.mobile-nav-contingent')
  </nav>

  <!-- SweetAlert2 -->
  <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script>
    window.addEventListener('swal', function (event) {
      const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
      Swal.fire({
        title: data.title,
        text: data.text,
        icon: data.icon,
        timer: data.timer || 3000,
        showConfirmButton: data.showConfirmButton || false,
      });
    });
  </script>
  @livewireScripts
  @stack('scripts')
</body>

</html>
