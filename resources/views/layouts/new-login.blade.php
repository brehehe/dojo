<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Admin Kejuaraan Shorinji Kempo Indonesia</title>
<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        fontFamily: {
          cinzel: ['Cinzel', 'serif'],
          dm: ['DM Sans', 'sans-serif'],
        },
        colors: {
          red:       '#c0392b',
          'red-deep':'#96281b',
          'red-glow':'#e74c3c',
          gold:      '#d4a843',
          'gold-lt': '#f0c060',
          ink:       '#0f0d0b',
          paper:     '#f7f4ef',
          paper2:    '#ede9e1',
          smoke:     '#b5afa6',
        },
        boxShadow: {
          'emblem': '0 4px 20px rgba(192,57,43,.5)',
          'card':   '0 24px 64px rgba(0,0,0,.45)',
          'glow':   '0 0 40px rgba(192,57,43,.25)',
        },
        keyframes: {
          fadeUp: {
            '0%':   { opacity:'0', transform:'translateY(24px)' },
            '100%': { opacity:'1', transform:'translateY(0)' },
          },
          shimmer: {
            '0%,100%': { opacity:'.6' },
            '50%':     { opacity:'1' },
          },
          spin360: {
            '0%':   { transform:'rotate(0deg)' },
            '100%': { transform:'rotate(360deg)' },
          }
        },
        animation: {
          'fade-up':   'fadeUp .55s cubic-bezier(.4,0,.2,1) both',
          'fade-up-2': 'fadeUp .55s .1s cubic-bezier(.4,0,.2,1) both',
          'fade-up-3': 'fadeUp .55s .2s cubic-bezier(.4,0,.2,1) both',
          'fade-up-4': 'fadeUp .55s .3s cubic-bezier(.4,0,.2,1) both',
          'shimmer':   'shimmer 2.4s ease-in-out infinite',
          'spin':      'spin360 1s linear infinite',
        }
      }
    }
  }
</script>
<style>
  /* ── CSS variables per mode ── */
  :root {
    --bg:          #f7f4ef;
    --bg2:         #ede9e1;
    --surface:     rgba(255,255,255,.92);
    --surface-bd:  rgba(0,0,0,.1);
    --text:        #0f0d0b;
    --text-sub:    #5a534c;
    --text-muted:  #9a938a;
    --input-bg:    rgba(0,0,0,.04);
    --input-bd:    rgba(0,0,0,.13);
    --divider:     rgba(0,0,0,.08);
    --chip-bg:     rgba(0,0,0,.05);
    --chip-bd:     rgba(0,0,0,.1);
    --glow-orb:    rgba(192,57,43,.1);
    --noise-op:    .15;
    --scrolltrack: #ede9e1;
  }
  html.dark {
    --bg:          #0f0d0b;
    --bg2:         #1a1714;
    --surface:     rgba(255,255,255,.045);
    --surface-bd:  rgba(255,255,255,.09);
    --text:        #ffffff;
    --text-sub:    #b5afa6;
    --text-muted:  #6b6560;
    --input-bg:    rgba(255,255,255,.06);
    --input-bd:    rgba(255,255,255,.11);
    --divider:     rgba(255,255,255,.07);
    --chip-bg:     rgba(255,255,255,.06);
    --chip-bd:     rgba(255,255,255,.09);
    --glow-orb:    rgba(192,57,43,.22);
    --noise-op:    .4;
    --scrolltrack: #0f0d0b;
  }

  body {
    background: var(--bg) !important;
    transition: background .3s;
  }

  /* ── background noise texture ── */
  body::before {
    content: '';
    position: fixed; inset: 0; pointer-events: none; z-index: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
    background-size: 180px;
    opacity: var(--noise-op);
  }
  /* ── radial glow orb ── */
  body::after {
    content: '';
    position: fixed; top: -160px; right: -160px;
    width: 520px; height: 520px; pointer-events: none; z-index: 0;
    background: radial-gradient(circle, var(--glow-orb) 0%, transparent 68%);
  }

  /* ── theme-aware text helpers ── */
  .t-main  { color: var(--text); }
  .t-sub   { color: var(--text-sub); }
  .t-muted { color: var(--text-muted); }

  /* ── diagonal decorative stripe ── */
  .deco-stripe {
    position: absolute; bottom: -60px; left: -60px;
    width: 320px; height: 320px; pointer-events: none;
    background: radial-gradient(circle, rgba(212,168,67,.12) 0%, transparent 65%);
  }

  /* ── login card surface ── */
  .login-card {
    background: var(--surface);
    border-color: var(--surface-bd);
    backdrop-filter: blur(24px);
    box-shadow: 0 24px 64px rgba(0,0,0,.2);
    transition: background .3s, border-color .3s;
  }
  html.dark .login-card {
    box-shadow: 0 24px 64px rgba(0,0,0,.45);
  }

  /* ── card header/footer borders ── */
  .card-divider { border-color: var(--divider); }

  /* ── stats chip ── */
  .stat-chip {
    background: var(--input-bg);
    border-color: var(--surface-bd);
  }

  /* ── input ── */
  .kempo-input {
    background: var(--input-bg);
    border-color: var(--input-bd);
    color: var(--text);
    transition: border-color .2s, box-shadow .2s, background .3s;
  }
  .kempo-input::placeholder { color: var(--text-muted); }
  .kempo-input:focus {
    outline: none;
    border-color: #c0392b;
    box-shadow: 0 0 0 3px rgba(192,57,43,.18);
  }

  /* ── password eye btn ── */
  .eye-btn { color: var(--text-sub); transition: color .15s; }
  .eye-btn:hover { color: #c0392b; }

  /* ── icon prefix in input ── */
  .input-icon { color: var(--text-sub); }

  /* ── sso button ── */
  .sso-btn {
    border-color: var(--surface-bd);
    background: var(--input-bg);
    color: var(--text-sub);
    transition: all .2s;
  }
  .sso-btn:hover { color: var(--text); }

  /* ── submit button shimmer ── */
  .submit-btn::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.12) 50%, transparent 60%);
    transform: translateX(-100%);
    transition: transform .5s;
    border-radius: inherit;
  }
  .submit-btn:hover::after { transform: translateX(100%); }
  .submit-btn:active { transform: scale(.98); }

  /* ── gold divider ── */
  .gold-line {
    background: linear-gradient(90deg, transparent, #d4a843, transparent);
    height: 1px;
  }

  /* ── or divider ── */
  .or-line { background: var(--divider); }

  /* ── vertical gold separator ── */
  .gold-sep {
    background: linear-gradient(180deg,transparent,rgba(212,168,67,.3),rgba(212,168,67,.6),rgba(212,168,67,.3),transparent);
  }
  html:not(.dark) .gold-sep {
    background: linear-gradient(180deg,transparent,rgba(192,57,43,.2),rgba(192,57,43,.4),rgba(192,57,43,.2),transparent);
  }

  /* ── checkbox ── */
  input[type=checkbox] { accent-color: #c0392b; }

  /* ── scrollbar ── */
  ::-webkit-scrollbar { width: 4px; }
  ::-webkit-scrollbar-track { background: var(--scrolltrack); }
  ::-webkit-scrollbar-thumb { background: #c0392b; border-radius: 2px; }

  /* ── mode toggle button ── */
  #modeToggle {
    position: fixed; top: 18px; right: 18px; z-index: 200;
    width: 40px; height: 40px; border-radius: 10px;
    border: 1px solid var(--surface-bd);
    background: var(--surface);
    color: var(--text-sub);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 15px;
    backdrop-filter: blur(12px);
    transition: all .2s;
  }
  #modeToggle:hover { color: #c0392b; border-color: rgba(192,57,43,.35); }

  /* ── light mode: left panel text ── */
  html:not(.dark) .panel-title   { color: #0f0d0b; }
  html:not(.dark) .panel-desc    { color: #6b6560; }
  html:not(.dark) .panel-footer  { color: #a09890; }
  html:not(.dark) .card-h1       { color: #0f0d0b; }
  html:not(.dark) .card-p        { color: #6b6560; }
  html:not(.dark) .label-text    { color: #6b6560; }
  html:not(.dark) .section-title { color: #0f0d0b; }
</style>
@livewireStyles
</head>
<body class="font-dm min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

  <!-- ── theme toggle ── -->
  <button id="modeToggle" onclick="toggleMode()" title="Ganti tema">
    <i class="fa-solid fa-moon" id="modeIcon"></i>
  </button>

  {{ $slot }}

<script>
  // Apply saved theme immediately to avoid flash
  (function() {
    const saved = localStorage.getItem('kempo-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (saved === 'dark' || (!saved && prefersDark)) {
      document.getElementById('html-root').classList.add('dark');
    }
  })();

  function toggleMode() {
    const html = document.getElementById('html-root');
    const icon = document.getElementById('modeIcon');
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('kempo-theme', isDark ? 'dark' : 'light');
    icon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
  }

  // Set correct icon on load
  document.addEventListener('DOMContentLoaded', () => {
    const icon = document.getElementById('modeIcon');
    const isDark = document.getElementById('html-root').classList.contains('dark');
    icon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
  });
</script>

@livewireScripts
</body>
</html>
