<!-- Start Sidebar -->
<aside id="app-menu" class="app-menu">

    <!-- Sidenav Menu Brand Logo -->
    <a href="{{ route('admin.new-dashboard') }}" class="logo-box sticky top-0 flex min-h-topbar-height items-center justify-start px-6 backdrop-blur-xs">
        <!-- Light Brand Logo -->
        <div class="logo-light">
            <div class="flex items-center gap-2 logo-lg">
                <img src="/assets/logo-light-CCjoJosn.png" class="h-6" alt="Light logo">
                <span class="text-lg font-bold text-slate-800 tracking-tight leading-none">Smart <span class="text-custom-500">Perkemi</span></span>
            </div>
            <img src="/assets/logo-light-CCjoJosn.png" class="logo-sm h-6" alt="Small logo">
        </div>

        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <div class="flex items-center gap-2 logo-lg">
                <img src="/assets/logo-dark-BRT9tiBX.png" class="h-6" alt="Dark logo">
                <span class="text-lg font-bold text-white tracking-tight leading-none">Smart <span class="text-custom-500">Perkemi</span></span>
            </div>
            <img src="/assets/logo-dark-BRT9tiBX.png" class="logo-sm h-6" alt="Small logo">
        </div>
    </a>

    <!-- Sidenav Menu Toggle Button -->
    <div class="absolute top-0 end-5 flex h-topbar items-center justify">
        <button id="button-hover-toggle">
            <i class="iconify tabler--circle size-5"></i>
        </button>
    </div>

    <!-- Sidenav Menu Item Link -->
    <div class="relative min-h-0 flex-grow">
        <div class="size-full" data-simplebar>
            <ul class="side-nav p-3 hs-accordion-group">
                <li class="menu-title">
                    <span>Menu Utama</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.home-dashboard') || request()->routeIs('admin.new-dashboard') ? 'active' : '' }}" href="{{ route('admin.new-dashboard') }}">
                        <span class="menu-icon"><i data-lucide="layout-dashboard"></i></span>
                        <div class="menu-text">Dashboard</div>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Master Data</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.master.contingents.*') ? 'active' : '' }}" href="{{ route('admin.master.contingents.index') }}">
                        <span class="menu-icon"><i data-lucide="building-2"></i></span>
                        <div class="menu-text">Kontingen</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.master.athletes.*') ? 'active' : '' }}" href="{{ route('admin.master.athletes.index') }}">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <div class="menu-text">Atlet</div>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Turnamen</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}" href="{{ route('admin.registrations.index') }}">
                        <span class="menu-icon"><i data-lucide="file-edit"></i></span>
                        <div class="menu-text">Registrasi</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.technical-meeting.*') ? 'active' : '' }}" href="{{ route('admin.technical-meeting.embu') }}">
                        <span class="menu-icon"><i data-lucide="handshake"></i></span>
                        <div class="menu-text">Tech. Meeting</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.arbitrase.scoring.*') ? 'active' : '' }}" href="{{ route('admin.arbitrase.scoring.index') }}">
                        <span class="menu-icon"><i data-lucide="gavel"></i></span>
                        <div class="menu-text">Arbitrase</div>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Laporan</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.athlete-biodata') }}">
                        <span class="menu-icon"><i data-lucide="file-bar-chart"></i></span>
                        <div class="menu-text">Report Recap</div>
                    </a>
                </li>

            </ul>
        </div>
    </div>

</aside>
<!-- End Sidebar -->
