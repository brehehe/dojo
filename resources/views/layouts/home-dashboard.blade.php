<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — {{ config('app.name', 'Smart Perkemi') }}</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─── THEME VARIABLES ─────────────────────────────────────── */
        .hd-root {
            --hd-bg:           #0f1117;
            --hd-surface:      #13151f;
            --hd-card:         #1a1d2e;
            --hd-card2:        #1e2235;
            --hd-border:       rgba(255,255,255,0.06);
            --hd-border-md:    rgba(255,255,255,0.10);
            --hd-text-1:       #f1f5f9;
            --hd-text-2:       #94a3b8;
            --hd-text-3:       #475569;
            --hd-text-4:       #334155;
            --hd-hover:        rgba(255,255,255,0.05);
            --hd-hover-md:     rgba(255,255,255,0.09);
            --hd-scrollbar:    rgba(255,255,255,0.10);
            --hd-input-bg:     rgba(255,255,255,0.05);
            --hd-input-border: rgba(255,255,255,0.10);
            --hd-divider:      rgba(255,255,255,0.05);
            --hd-shadow:       0 4px 24px rgba(0,0,0,0.4);
            --hd-topbar-bg:    rgba(19,21,31,0.85);
            --hd-section-label: #334155;
        }

        /* ─── LIGHT THEME OVERRIDE ────────────────────────────────── */
        .hd-root.hd-light {
            --hd-bg:           #f1f5f9;
            --hd-surface:      #ffffff;
            --hd-card:         #ffffff;
            --hd-card2:        #f8fafc;
            --hd-border:       #e2e8f0;
            --hd-border-md:    #cbd5e1;
            --hd-text-1:       #0f172a;
            --hd-text-2:       #475569;
            --hd-text-3:       #94a3b8;
            --hd-text-4:       #cbd5e1;
            --hd-hover:        rgba(0,0,0,0.04);
            --hd-hover-md:     rgba(0,0,0,0.07);
            --hd-scrollbar:    rgba(0,0,0,0.12);
            --hd-input-bg:     rgba(0,0,0,0.04);
            --hd-input-border: #e2e8f0;
            --hd-divider:      #e2e8f0;
            --hd-shadow:       0 4px 24px rgba(0,0,0,0.08);
            --hd-topbar-bg:    rgba(255,255,255,0.92);
            --hd-section-label: #94a3b8;
        }

        /* ─── BASE UTILITIES ─────────────────────────────────────── */
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        .hd-bg       { background-color: var(--hd-bg); }
        .hd-surface  { background-color: var(--hd-surface); border-color: var(--hd-border); }
        .hd-card     { background-color: var(--hd-card); border-color: var(--hd-border); }
        .hd-card2    { background-color: var(--hd-card2); border-color: var(--hd-border); }
        .hd-border   { border-color: var(--hd-border); }
        .hd-border-md{ border-color: var(--hd-border-md); }
        .hd-divider  { border-color: var(--hd-divider); }
        .hd-text-1   { color: var(--hd-text-1); }
        .hd-text-2   { color: var(--hd-text-2); }
        .hd-text-3   { color: var(--hd-text-3); }
        .hd-text-4   { color: var(--hd-text-4); }
        .hd-hover:hover { background-color: var(--hd-hover); }
        .hd-shadow   { box-shadow: var(--hd-shadow); }
        .hd-section-label { color: var(--hd-section-label); }
        .hd-topbar   { background-color: var(--hd-topbar-bg); border-color: var(--hd-border); }
        .hd-input    { background-color: var(--hd-input-bg); border-color: var(--hd-input-border); color: var(--hd-text-2); }
        .hd-input::placeholder { color: var(--hd-text-3); }

        /* Scrollbar */
        .hd-scrollbar::-webkit-scrollbar { width: 4px; }
        .hd-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .hd-scrollbar::-webkit-scrollbar-thumb { background: var(--hd-scrollbar); border-radius: 4px; }

        /* Transition for all theme-sensitive elements */
        .hd-root *, .hd-root *::before, .hd-root *::after {
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.2s ease, box-shadow 0.25s ease;
        }
        /* Exclude transitions that shouldn't animate */
        .hd-root a, .hd-root button { transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.15s ease, opacity 0.15s ease; }

        /* Nav item active state - different per theme */
        .hd-nav-active-dark  { background-color: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.25); color: #a5b4fc; }
        .hd-nav-active-light { background-color: #eef2ff; border-color: #c7d2fe; color: #4338ca; }
        .hd-nav-sub-active-dark  { background-color: rgba(99,102,241,0.12); color: #a5b4fc; }
        .hd-nav-sub-active-light { background-color: #eef2ff; color: #4338ca; }

        /* Theme toggle button */
        .hd-theme-toggle {
            background-color: var(--hd-hover);
            border: 1px solid var(--hd-border);
            color: var(--hd-text-2);
        }
        .hd-theme-toggle:hover {
            background-color: var(--hd-hover-md);
            color: var(--hd-text-1);
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased overflow-x-hidden">
    <div
        class="hd-root hd-bg min-h-screen"
        x-data="{
            darkMode: localStorage.getItem('hd-theme') !== 'light',
            sidebarOpen: true,
            mobileSidebarOpen: false,
            toggleTheme() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('hd-theme', this.darkMode ? 'dark' : 'light');
                document.dispatchEvent(new CustomEvent('hd-theme-changed', { detail: { dark: this.darkMode } }));
            }
        }"
        x-init="
            $el.classList.toggle('hd-light', !darkMode);
            document.documentElement.classList.toggle('dark', darkMode);
            $watch('darkMode', val => {
                $el.classList.toggle('hd-light', !val);
                document.documentElement.classList.toggle('dark', val);
            });
        "
    >
        <div class="flex h-screen overflow-hidden">

            {{-- Desktop Sidebar --}}
            @include('layouts.home-dashboard.sidebar')

            {{-- Mobile Sidebar Backdrop --}}
            <div x-show="mobileSidebarOpen" x-cloak class="fixed inset-0 z-50 flex md:hidden">
                <div @click="mobileSidebarOpen = false"
                     x-transition:enter="transition-opacity ease-linear duration-200"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-200"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div x-transition:enter="transition ease-in-out duration-250 transform"
                     x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-200 transform"
                     x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                     class="hd-surface border-r hd-border relative w-72 flex-shrink-0">
                    @include('layouts.home-dashboard.sidebar')
                </div>
            </div>

            {{-- Main Area --}}
            <div class="flex-1 flex flex-col overflow-hidden min-w-0">
                @include('layouts.home-dashboard.topbar')
                <main class="flex-1 overflow-y-auto overflow-x-hidden hd-bg p-4 md:p-6 hd-scrollbar">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        window.addEventListener('swal', function (event) {
            const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            Swal.fire({ title: data.title, text: data.text, icon: data.icon, timer: data.timer || 3000, showConfirmButton: data.showConfirmButton || false });
        });
    </script>
    @stack('scripts')
</body>
</html>
