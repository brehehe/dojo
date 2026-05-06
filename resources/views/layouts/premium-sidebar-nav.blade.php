<nav class="premium-nav" id="premiumSidebarNav">
    <div class="nav-section">Utama</div>
    <a class="nav-item {{ request()->routeIs('admin.new-dashboard') ? 'active' : '' }}"
        href="{{ route('admin.new-dashboard') }}" wire:navigate><i class="fa-solid fa-gauge-high"></i> Dashboard</a>

    <div class="nav-section">Master Data</div>
    <a class="nav-item {{ request()->routeIs('admin.new-users') ? 'active' : '' }}"
        href="{{ route('admin.new-users') }}" wire:navigate><i class="fa-solid fa-users"></i> Pengguna (User)</a>
    <a class="nav-item {{ request()->routeIs('admin.new-roles*') ? 'active' : '' }}"
        href="{{ route('admin.new-roles') }}" wire:navigate><i class="fa-solid fa-shield-halved"></i> Role Akses</a>
    <a class="nav-item {{ request()->routeIs('admin.master.new-referees') ? 'active' : '' }}"
        href="{{ route('admin.master.new-referees') }}" wire:navigate><i class="fa-solid fa-gavel"></i> Data Wasit</a>
    <a class="nav-item {{ request()->routeIs('admin.new-contingents*') ? 'active' : '' }}"
        href="{{ route('admin.new-contingents') }}" wire:navigate><i class="fa-solid fa-flag"></i> Kontingen</a>
    <a class="nav-item {{ request()->routeIs('admin.new-officials*') ? 'active' : '' }}"
        href="{{ route('admin.new-officials') }}" wire:navigate><i class="fa-solid fa-user-tie"></i> Official Tim</a>
    <a class="nav-item {{ request()->routeIs('admin.new-kyu-levels') ? 'active' : '' }}"
        href="{{ route('admin.new-kyu-levels') }}" wire:navigate><i class="fa-solid fa-medal"></i> Kyu / Dan Levels</a>
    <a class="nav-item {{ request()->routeIs('admin.new-age-groups') ? 'active' : '' }}"
        href="{{ route('admin.new-age-groups') }}" wire:navigate><i class="fa-solid fa-calendar-day"></i> Kelompok
        Umur</a>
    <a class="nav-item {{ request()->routeIs('admin.new-weight-groups') ? 'active' : '' }}"
        href="{{ route('admin.new-weight-groups') }}" wire:navigate><i class="fa-solid fa-weight-scale"></i> Kelompok
        Berat Badan</a>
    <a class="nav-item {{ request()->routeIs('admin.new-techniques') ? 'active' : '' }}"
        href="{{ route('admin.new-techniques') }}" wire:navigate><i class="fa-solid fa-hand-fist"></i> Teknik &
        Jurus</a>
    <a class="nav-item {{ request()->routeIs('admin.new-match-numbers') ? 'active' : '' }}"
        href="{{ route('admin.new-match-numbers') }}" wire:navigate><i class="fa-solid fa-list-check"></i> Nomor
        Pertandingan</a>
    <a class="nav-item {{ request()->routeIs('admin.new-payment-methods') ? 'active' : '' }}"
        href="{{ route('admin.new-payment-methods') }}" wire:navigate><i class="fa-solid fa-wallet"></i> Metode
        Pembayaran</a>
    <a class="nav-item {{ request()->routeIs('admin.new-courts') ? 'active' : '' }}"
        href="{{ route('admin.new-courts') }}" wire:navigate><i class="fa-solid fa-layer-group"></i> Lapangan
        (Court)</a>
    <a class="nav-item {{ request()->routeIs('admin.new-pools') ? 'active' : '' }}"
        href="{{ route('admin.new-pools') }}" wire:navigate><i class="fa-solid fa-sitemap"></i> Bagan (Pool)</a>
    <a class="nav-item {{ request()->routeIs('admin.new-session-times') ? 'active' : '' }}"
        href="{{ route('admin.new-session-times') }}" wire:navigate><i class="fa-solid fa-clock"></i> Waktu Sesi</a>
    <a class="nav-item {{ request()->routeIs('admin.new-rundowns') ? 'active' : '' }}"
        href="{{ route('admin.new-rundowns') }}" wire:navigate><i class="fa-solid fa-calendar-alt"></i> Rundown
        Acara</a>
    <a class="nav-item {{ request()->routeIs('admin.new-athletes*') ? 'active' : '' }}"
        href="{{ route('admin.new-athletes') }}" wire:navigate><i class="fa-solid fa-users"></i> Data Atlet</a>

    <div class="nav-section">Pertandingan</div>
    <a class="nav-item {{ request()->routeIs('admin.new-registrations*') ? 'active' : '' }}"
        href="{{ route('admin.new-registrations') }}" wire:navigate><i class="fa-solid fa-file-edit"></i> Registrasi</a>
    <a class="nav-item {{ request()->routeIs('admin.new-tm-drawing') ? 'active' : '' }}"
        href="{{ route('admin.new-tm-drawing') }}" wire:navigate><i class="fa-solid fa-dice"></i> Drawing TM</a>

    <div class="nav-section">Sistem Arbitrase</div>
    <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-referees') ? 'active' : '' }}"
        href="{{ route('admin.arbitrase.new-referees') }}" wire:navigate><i class="fa-solid fa-gavel"></i> Data
        Wasit</a>
    <a class="nav-item {{ request()->routeIs('admin.new-generate-referee') ? 'active' : '' }}"
        href="{{ route('admin.new-generate-referee') }}" wire:navigate><i class="fa-solid fa-users-gear"></i> Penugasan
        Wasit</a>

    <div class="nav-section">Sistem</div>
    <a class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
        href="{{ route('admin.reports.athlete-biodata') }}" wire:navigate><i class="fa-solid fa-file-lines"></i>
        Laporan</a>
    <a class="nav-item" href="#"><i class="fa-solid fa-gear"></i> Pengaturan</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a class="nav-item" href="{{ route('logout') }}"
            onclick="event.preventDefault(); this.closest('form').submit();">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>
    </form>
</nav>

<script>
    function scrollNavToActive() {
        const nav = document.getElementById('premiumSidebarNav');
        if (nav) {
            const activeItem = nav.querySelector('.nav-item.active');
            if (activeItem) {
                // Calculate position to scroll so the active item is in the middle of the nav
                const navHeight = nav.clientHeight;
                const activeOffset = activeItem.offsetTop;
                const activeHeight = activeItem.clientHeight;

                // Set the scrollTop (only scrolls if there is overflow)
                nav.scrollTo({
                    top: activeOffset - (navHeight / 2) + (activeHeight / 2),
                    behavior: 'smooth'
                });
            }
        }
    }

    // Run on initial page load
    document.addEventListener('DOMContentLoaded', scrollNavToActive);

    // Run after Livewire navigations
    document.addEventListener('livewire:navigated', scrollNavToActive);
</script>