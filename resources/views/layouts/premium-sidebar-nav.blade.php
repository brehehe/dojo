<nav class="premium-nav" id="premiumSidebarNav" x-data="{ 
    openedSection: null,
    toggle(section) {
        this.openedSection = this.openedSection === section ? null : section;
    }
}">
    <style>
        .nav-section-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 14px 24px 8px;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            background: transparent;
            text-align: left;
        }
        .nav-section-trigger:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        .nav-section-trigger .chevron {
            font-size: 8px;
            color: rgba(255, 255, 255, 0.2);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-section-trigger.active .chevron {
            transform: rotate(90deg);
            color: var(--gold);
        }
        .nav-section-trigger .label {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.3);
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 600;
        }
        .nav-section-trigger.active .label {
            color: rgba(255, 255, 255, 0.6);
        }
        .collapsible-content {
            overflow: hidden;
            transition: all 0.3s ease-out;
        }
    </style>

    {{-- Utama --}}
    <div x-data="{ open: true }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Utama</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.new-dashboard') ? 'active' : '' }}"
                href="{{ route('admin.new-dashboard') }}" wire:navigate><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        </div>
    </div>

    {{-- Laporan --}}
    <div x-data="{ open: @json(request()->routeIs('admin.arbitrase.new-*') || request()->routeIs('admin.arbitrase.new-rekapitulasi-*')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Laporan</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-hasil') ? 'active' : '' }}"
                href="{{ route('admin.arbitrase.new-laporan-hasil') }}" wire:navigate><i class="fa-solid fa-medal"></i> Hasil Juara</a>
            <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-skor') ? 'active' : '' }}"
                href="{{ route('admin.arbitrase.new-laporan-skor') }}" wire:navigate><i class="fa-solid fa-file-invoice"></i> Skor Menyeluruh</a>
            <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-rekapitulasi-embu') ? 'active' : '' }}"
                href="{{ route('admin.arbitrase.new-rekapitulasi-embu') }}" wire:navigate><i class="fa-solid fa-chart-bar"></i> Rekap Embu</a>
            <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-rekapitulasi-randori') ? 'active' : '' }}"
                href="{{ route('admin.arbitrase.new-rekapitulasi-randori') }}" wire:navigate><i class="fa-solid fa-fist-raised"></i> Rekap Randori</a>
        </div>
    </div>

    {{-- Smart Wasit --}}
    <div x-data="{ open: @json(request()->routeIs('admin.smart-wasit.*')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Smart Wasit</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.smart-wasit.summary') ? 'active' : '' }}"
                href="{{ route('admin.smart-wasit.summary') }}" wire:navigate><i class="fa-solid fa-square-poll-vertical"></i> Summary</a>
            <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-skw') ? 'active' : '' }}"
                href="{{ route('admin.smart-wasit.ranking-skw') }}" wire:navigate><i class="fa-solid fa-ranking-star"></i> Ranking SKW</a>
            <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-iaw') ? 'active' : '' }}"
                href="{{ route('admin.smart-wasit.ranking-iaw') }}" wire:navigate><i class="fa-solid fa-bullseye"></i> Ranking IAW</a>
            <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-ik') ? 'active' : '' }}"
                href="{{ route('admin.smart-wasit.ranking-ik') }}" wire:navigate><i class="fa-solid fa-arrows-rotate"></i> Ranking IK</a>
            <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-iv') ? 'active' : '' }}"
                href="{{ route('admin.smart-wasit.ranking-iv') }}" wire:navigate><i class="fa-solid fa-check-double"></i> Ranking IV</a>
            <a class="nav-item {{ request()->routeIs('admin.smart-wasit.perbabak') ? 'active' : '' }}"
                href="{{ route('admin.smart-wasit.perbabak') }}" wire:navigate><i class="fa-solid fa-list-check"></i> Per Babak</a>
        </div>
    </div>

    {{-- Master Data --}}
    <div x-data="{ open: @json(request()->routeIs('admin.new-users') || request()->routeIs('admin.new-roles*') || request()->routeIs('admin.master.new-referees') || request()->routeIs('admin.new-contingents*') || request()->routeIs('admin.new-officials*') || request()->routeIs('admin.new-kyu-levels') || request()->routeIs('admin.new-age-groups') || request()->routeIs('admin.new-weight-groups') || request()->routeIs('admin.new-techniques') || request()->routeIs('admin.new-match-numbers') || request()->routeIs('admin.new-payment-methods') || request()->routeIs('admin.new-courts') || request()->routeIs('admin.new-pools') || request()->routeIs('admin.new-session-times') || request()->routeIs('admin.new-rundowns') || request()->routeIs('admin.new-athletes*')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Master Data</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
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
        </div>
    </div>

    {{-- Pertandingan --}}
    <div x-data="{ open: @json(request()->routeIs('admin.new-registrations*') || request()->routeIs('admin.new-tm-drawing')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Pertandingan</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.new-registrations*') ? 'active' : '' }}"
                href="{{ route('admin.new-registrations') }}" wire:navigate><i class="fa-solid fa-file-edit"></i> Registrasi</a>
            <a class="nav-item {{ request()->routeIs('admin.new-tm-drawing') ? 'active' : '' }}"
                href="{{ route('admin.new-tm-drawing') }}" wire:navigate><i class="fa-solid fa-dice"></i> Drawing TM</a>
        </div>
    </div>

    {{-- Sistem Arbitrase --}}
    <div x-data="{ open: @json(request()->routeIs('admin.arbitrase.new-referees') || request()->routeIs('admin.new-generate-referee')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Sistem Arbitrase</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-referees') ? 'active' : '' }}"
                href="{{ route('admin.arbitrase.new-referees') }}" wire:navigate><i class="fa-solid fa-gavel"></i> Data
                Wasit</a>
            <a class="nav-item {{ request()->routeIs('admin.new-generate-referee') ? 'active' : '' }}"
                href="{{ route('admin.new-generate-referee') }}" wire:navigate><i class="fa-solid fa-users-gear"></i> Penugasan
                Wasit</a>
        </div>
    </div>

    {{-- Sistem Panitera --}}
    <div x-data="{ open: @json(request()->routeIs('admin.new-scoring-*') || request()->routeIs('admin.panitera.scoring.embu.result')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Sistem Panitera</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.new-scoring-*') ? 'active' : '' }}"
                href="{{ route('admin.new-scoring-index') }}" wire:navigate><i class="fa-solid fa-star"></i> Penilaian
                (Scoring)</a>
            <a class="nav-item {{ request()->routeIs('admin.panitera.scoring.embu.result') ? 'active' : '' }}"
                href="{{ route('admin.panitera.scoring.embu.result') }}" wire:navigate><i class="fa-solid fa-trophy"></i> Hasil
                Embu</a>
        </div>
    </div>

    {{-- Sistem --}}
    <div x-data="{ open: @json(request()->routeIs('admin.reports.*')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Sistem</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                href="{{ route('admin.reports.athlete-biodata') }}" wire:navigate><i class="fa-solid fa-file-lines"></i>
                Laporan</a>
            <a class="nav-item" href="#"><i class="fa-solid fa-gear"></i> Pengaturan</a>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
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