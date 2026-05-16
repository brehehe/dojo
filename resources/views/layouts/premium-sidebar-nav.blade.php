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
            padding: 18px 24px 10px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: transparent;
            text-align: left;
        }

        .nav-section-trigger:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-section-trigger .chevron {
            font-size: 8px;
            color: rgba(255, 255, 255, 0.25);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-section-trigger.active .chevron {
            transform: rotate(90deg);
            color: var(--gold);
        }

        .nav-section-trigger .label {
            font-size: 10px;
            color: var(--smoke);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            font-weight: 700;
            opacity: 0.6;
        }

        .nav-section-trigger.active .label {
            color: #fff;
            opacity: 1;
        }

        .nav-section {
            padding: 24px 24px 10px;
            font-size: 10px;
            color: var(--gold);
            letter-spacing: .22em;
            text-transform: uppercase;
            font-weight: 700;
            opacity: 0.8;
        }

        .collapsible-content {
            overflow: hidden;
            transition: all 0.3s ease-out;
        }
    </style>

    @unless (auth()->user()->hasRole('Contingent') || auth()->user()->hasRole('Wasit') || auth()->user()->hasRole('Perwasitan'))
        <a class="nav-item {{ request()->routeIs('admin.new-dashboard') ? 'active' : '' }}"
            href="{{ route('admin.new-dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
    @endunless

    {{-- Pendaftaran --}}
    @hasanyrole('Super Admin|Admin|Pendaftaran')
        <div x-data="{ open: @json(request()->routeIs('admin.new-registrations*')) }">
            <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                <span class="label">Pendaftaran</span>
                <i class="fa-solid fa-chevron-right chevron"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="collapsible-content">
                <a class="nav-item {{ request()->routeIs('admin.new-registrations*') ? 'active' : '' }}"
                    href="{{ route('admin.new-registrations') }}"><i class="fa-solid fa-file-edit"></i> Registrasi</a>
            </div>
        </div>
    @endhasanyrole

    {{-- Pertandingan --}}
    @hasanyrole('Super Admin|Admin|Pertandingan|Koordinator Lapangan')
        <div x-data="{ open: @json(request()->routeIs('admin.new-tm-drawing')) }">
            <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                <span class="label">Pertandingan</span>
                <i class="fa-solid fa-chevron-right chevron"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="collapsible-content">
                <a class="nav-item {{ request()->routeIs('admin.master.match-number-merges') ? 'active' : '' }}"
                    href="{{ route('admin.master.match-number-merges') }}"><i class="fa-solid fa-object-group"></i>
                    Merge Nomer Pertandingan</a>
                <a class="nav-item {{ request()->routeIs('admin.new-tm-drawing') ? 'active' : '' }}"
                    href="{{ route('admin.new-tm-drawing') }}"><i class="fa-solid fa-dice"></i> Drawing TM</a>
            </div>
        </div>
    @endhasanyrole

    {{-- Sistem Arbitrase --}}
    @hasanyrole('Super Admin|Admin|Arbitrase|Perwasitan')
        <div x-data="{ open: @json(request()->routeIs('admin.arbitrase.new-referees') || request()->routeIs('admin.new-generate-referee')) }">
            <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                <span class="label">Sistem Arbitrase</span>
                <i class="fa-solid fa-chevron-right chevron"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="collapsible-content">
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-referees') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-referees') }}"><i class="fa-solid fa-gavel"></i> Data
                    Wasit</a>
                <a class="nav-item {{ request()->routeIs('admin.new-generate-referee') ? 'active' : '' }}"
                    href="{{ route('admin.new-generate-referee') }}"><i class="fa-solid fa-users-gear"></i> Penugasan
                    Wasit</a>
            </div>
        </div>
    @endhasanyrole

    {{-- Sistem Panitera --}}
    @hasanyrole('Super Admin|Admin|Panitera|Koordinator Lapangan|Court')
        <div x-data="{ open: @json(request()->routeIs('admin.new-scoring-*') || request()->routeIs('admin.panitera.scoring.embu.result')) }">
            <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                <span class="label">Sistem Panitera</span>
                <i class="fa-solid fa-chevron-right chevron"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="collapsible-content">
                <a class="nav-item {{ request()->routeIs('admin.new-scoring-*') ? 'active' : '' }}"
                    href="{{ route('admin.new-scoring-index') }}"><i class="fa-solid fa-star"></i> Penilaian
                    (Scoring)</a>
                <a class="nav-item {{ request()->routeIs('admin.panitera.scoring.embu.result') ? 'active' : '' }}"
                    href="{{ route('admin.panitera.scoring.embu.result') }}"><i class="fa-solid fa-trophy"></i> Hasil
                    Embu</a>
            </div>
        </div>
    @endhasanyrole

    @hasanyrole('Wasit|Perwasitan')
        <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i
                class="fa-solid fa-house"></i> Dashboard</a>
        <a class="nav-item {{ request()->routeIs('admin.referee.scoring') ? 'active' : '' }}"
            href="{{ route('admin.referee.scoring') }}"><i class="fa-solid fa-gauge-high"></i> Penilain Wasit</a>
    @endhasanyrole

    {{-- Smart Wasit --}}
    @hasanyrole('Super Admin|Admin')
        <div x-data="{ open: @json(request()->routeIs('admin.smart-wasit.*') || request()->routeIs('admin.referee.*')) }">
            <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                <span class="label">Sistem Wasit</span>
                <i class="fa-solid fa-chevron-right chevron"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="collapsible-content">
                @hasrole('Wasit')
                    <a class="nav-item {{ request()->routeIs('admin.referee.scoring') ? 'active' : '' }}"
                        href="{{ route('admin.referee.scoring') }}"><i class="fa-solid fa-gavel"></i> Scoring</a>
                @endhasrole
                <a class="nav-item {{ request()->routeIs('admin.smart-wasit.summary') ? 'active' : '' }}"
                    href="{{ route('admin.smart-wasit.summary') }}"><i class="fa-solid fa-square-poll-vertical"></i>
                    Summary</a>
                <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-skw') ? 'active' : '' }}"
                    href="{{ route('admin.smart-wasit.ranking-skw') }}"><i class="fa-solid fa-ranking-star"></i> Ranking
                    SKW</a>
                <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-iaw') ? 'active' : '' }}"
                    href="{{ route('admin.smart-wasit.ranking-iaw') }}"><i class="fa-solid fa-bullseye"></i> Ranking
                    IAW</a>
                <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-ik') ? 'active' : '' }}"
                    href="{{ route('admin.smart-wasit.ranking-ik') }}"><i class="fa-solid fa-arrows-rotate"></i> Ranking
                    IK</a>
                <a class="nav-item {{ request()->routeIs('admin.smart-wasit.ranking-iv') ? 'active' : '' }}"
                    href="{{ route('admin.smart-wasit.ranking-iv') }}"><i class="fa-solid fa-check-double"></i> Ranking
                    IV</a>
                <a class="nav-item {{ request()->routeIs('admin.smart-wasit.perbabak') ? 'active' : '' }}"
                    href="{{ route('admin.smart-wasit.perbabak') }}"><i class="fa-solid fa-list-check"></i> Per Babak</a>
            </div>
        </div>
    @endhasanyrole


    {{-- Laporan --}}
    @hasanyrole('Super Admin|Admin|Arbitrase|Perwasitan|Pertandingan|Koordinator Lapangan|Court|Panitera')
        <div x-data="{ open: @json(request()->routeIs('admin.arbitrase.new-*') || request()->routeIs('admin.arbitrase.new-rekapitulasi-*')) }">
            <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                <span class="label">Laporan</span>
                <i class="fa-solid fa-chevron-right chevron"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="collapsible-content">
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-hasil') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-laporan-hasil') }}"><i class="fa-solid fa-medal"></i> Hasil
                    Juara</a>
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-skor') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-laporan-skor') }}"><i class="fa-solid fa-file-invoice"></i> Skor
                    Menyeluruh</a>
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-rekapitulasi-embu') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-rekapitulasi-embu') }}"><i class="fa-solid fa-chart-bar"></i>
                    Rekap Embu</a>
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-rekapitulasi-randori') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-rekapitulasi-randori') }}"><i
                        class="fa-solid fa-fist-raised"></i> Rekap Randori</a>
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-wasit') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-laporan-wasit') }}"><i class="fa-solid fa-gavel"></i> Penilaian
                    Wasit</a>
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-wasit-juri') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-laporan-wasit-juri') }}"><i class="fa-solid fa-chart-simple"></i>
                    Analisis Per Juri</a>
                <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-rekapitulasi-embu-index') ? 'active' : '' }}"
                    href="{{ route('admin.arbitrase.new-rekapitulasi-embu-index') }}"><i
                        class="fa-solid fa-chart-line"></i> Rekapitulasi Embu</a>
            </div>
        </div>
    @endhasanyrole

    {{-- Kontingen --}}
    @hasanyrole('Super Admin|Admin|Contingent')
        @if (auth()->user()->hasRole('Contingent') &&
                !auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
            <div class="nav-section">Menu Utama</div>
            <a class="nav-item {{ request()->routeIs('contingent.dashboard') ? 'active' : '' }}"
                href="{{ route('contingent.dashboard') }}"><i class="fa-solid fa-house-user"></i> Dashboard</a>
            <a class="nav-item {{ request()->routeIs('contingent.schedule') ? 'active' : '' }}"
                href="{{ route('contingent.schedule') }}"><i class="fa-solid fa-calendar-alt"></i> Jadwal</a>
            <a class="nav-item {{ request()->routeIs('contingent.results') ? 'active' : '' }}"
                href="{{ route('contingent.results') }}"><i class="fa-solid fa-poll"></i> Hasil</a>
            <a class="nav-item {{ request()->routeIs('contingent.athletes') ? 'active' : '' }}"
                href="{{ route('contingent.athletes') }}"><i class="fa-solid fa-users"></i> Atlet</a>
            <a class="nav-item {{ request()->routeIs('contingent.officials') ? 'active' : '' }}"
                href="{{ route('contingent.officials') }}"><i class="fa-solid fa-user-tie"></i> Official</a>
            <div class="nav-section">Pendaftaran</div>
            <a class="nav-item {{ request()->routeIs('contingent.registration-history') ? 'active' : '' }}"
                href="{{ route('contingent.registration-history') }}"><i class="fa-solid fa-gavel"></i> History
                Pendaftaran</a>
            <div class="nav-section">Laporan</div>
            <a class="nav-item {{ request()->routeIs('contingent.laporan-wasit') ? 'active' : '' }}"
                href="{{ route('contingent.laporan-wasit') }}"><i class="fa-solid fa-gavel"></i> Laporan Wasit</a>
            <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-wasit-juri') ? 'active' : '' }}"
                href="{{ route('admin.arbitrase.new-laporan-wasit-juri') }}"><i class="fa-solid fa-chart-simple"></i>
                Analisis Per Juri</a>
            <a class="nav-item {{ request()->routeIs('contingent.rekap-pertandingan') ? 'active' : '' }}"
                href="{{ route('contingent.rekap-pertandingan') }}"><i class="fa-solid fa-file-invoice"></i> Rekap Embu &
                Randori</a>
        @else
            <div x-data="{ open: @json(request()->routeIs('contingent.*')) }">
                <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                    <span class="label">Menu Kontingen</span>
                    <i class="fa-solid fa-chevron-right chevron"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="collapsible-content">
                    <a class="nav-item {{ request()->routeIs('contingent.dashboard') ? 'active' : '' }}"
                        href="{{ route('contingent.dashboard') }}"><i class="fa-solid fa-house-user"></i> Dashboard</a>
                    <a class="nav-item {{ request()->routeIs('contingent.schedule') ? 'active' : '' }}"
                        href="{{ route('contingent.schedule') }}"><i class="fa-solid fa-calendar-alt"></i> Jadwal</a>
                    <a class="nav-item {{ request()->routeIs('contingent.results') ? 'active' : '' }}"
                        href="{{ route('contingent.results') }}"><i class="fa-solid fa-poll"></i> Hasil</a>
                    <a class="nav-item {{ request()->routeIs('contingent.athletes') ? 'active' : '' }}"
                        href="{{ route('contingent.athletes') }}"><i class="fa-solid fa-users"></i> Atlet</a>
                    <a class="nav-item {{ request()->routeIs('contingent.officials') ? 'active' : '' }}"
                        href="{{ route('contingent.officials') }}"><i class="fa-solid fa-user-tie"></i> Official</a>
                    <div x-data="{ open: @json(request()->routeIs('contingent.registration-history*')) }">
                        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                            <span class="label">Pendaftaran</span>
                            <i class="fa-solid fa-chevron-right chevron"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
                            <a class="nav-item {{ request()->routeIs('contingent.registration-history*') ? 'active' : '' }}"
                                href="{{ route('contingent.registration-history') }}"><i class="fa-solid fa-history"></i>
                                History Registrasi</a>
                        </div>
                    </div>
                    <a class="nav-item {{ request()->routeIs('contingent.laporan-wasit') ? 'active' : '' }}"
                        href="{{ route('contingent.laporan-wasit') }}"><i class="fa-solid fa-gavel"></i> Laporan
                        Wasit</a>
                    <a class="nav-item {{ request()->routeIs('admin.arbitrase.new-laporan-wasit-juri') ? 'active' : '' }}"
                        href="{{ route('admin.arbitrase.new-laporan-wasit-juri') }}"><i
                            class="fa-solid fa-chart-simple"></i> Analisis Per Juri</a>
                    <a class="nav-item {{ request()->routeIs('contingent.rekap-pertandingan') ? 'active' : '' }}"
                        href="{{ route('contingent.rekap-pertandingan') }}"><i class="fa-solid fa-file-invoice"></i>
                        Rekap Embu & Randori</a>
                </div>
            </div>
        @endif
    @endhasanyrole

    {{-- Master Data --}}
    @hasanyrole('Super Admin|Admin')
        <div x-data="{ open: @json(request()->routeIs('admin.new-users') ||
                request()->routeIs('admin.new-roles*') ||
                request()->routeIs('admin.master.new-referees') ||
                request()->routeIs('admin.new-contingents*') ||
                request()->routeIs('admin.new-officials*') ||
                request()->routeIs('admin.new-kyu-levels') ||
                request()->routeIs('admin.new-age-groups') ||
                request()->routeIs('admin.new-weight-groups') ||
                request()->routeIs('admin.new-techniques') ||
                request()->routeIs('admin.new-match-numbers') ||
                request()->routeIs('admin.master.match-number-merges') ||
                request()->routeIs('admin.new-payment-methods') ||
                request()->routeIs('admin.new-courts') ||
                request()->routeIs('admin.new-pools') ||
                request()->routeIs('admin.new-session-times') ||
                request()->routeIs('admin.new-rundowns') ||
                request()->routeIs('admin.new-athletes*')) }">
            <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
                <span class="label">Master Data</span>
                <i class="fa-solid fa-chevron-right chevron"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="collapsible-content">
                <a class="nav-item {{ request()->routeIs('admin.new-users') ? 'active' : '' }}"
                    href="{{ route('admin.new-users') }}"><i class="fa-solid fa-users"></i> Pengguna (User)</a>
                <a class="nav-item {{ request()->routeIs('admin.new-roles*') ? 'active' : '' }}"
                    href="{{ route('admin.new-roles') }}"><i class="fa-solid fa-shield-halved"></i> Role Akses</a>
                <a class="nav-item {{ request()->routeIs('admin.master.new-referees') ? 'active' : '' }}"
                    href="{{ route('admin.master.new-referees') }}"><i class="fa-solid fa-gavel"></i> Data Wasit</a>
                <a class="nav-item {{ request()->routeIs('admin.new-contingents*') ? 'active' : '' }}"
                    href="{{ route('admin.new-contingents') }}"><i class="fa-solid fa-flag"></i> Kontingen</a>
                <a class="nav-item {{ request()->routeIs('admin.new-officials*') ? 'active' : '' }}"
                    href="{{ route('admin.new-officials') }}"><i class="fa-solid fa-user-tie"></i> Official Tim</a>
                <a class="nav-item {{ request()->routeIs('admin.new-kyu-levels') ? 'active' : '' }}"
                    href="{{ route('admin.new-kyu-levels') }}"><i class="fa-solid fa-medal"></i> Kyu / Dan Levels</a>
                <a class="nav-item {{ request()->routeIs('admin.new-age-groups') ? 'active' : '' }}"
                    href="{{ route('admin.new-age-groups') }}"><i class="fa-solid fa-calendar-day"></i> Kelompok
                    Umur</a>
                <a class="nav-item {{ request()->routeIs('admin.new-weight-groups') ? 'active' : '' }}"
                    href="{{ route('admin.new-weight-groups') }}"><i class="fa-solid fa-weight-scale"></i> Kelompok
                    Berat Badan</a>
                <a class="nav-item {{ request()->routeIs('admin.new-techniques') ? 'active' : '' }}"
                    href="{{ route('admin.new-techniques') }}"><i class="fa-solid fa-hand-fist"></i> Teknik &
                    Jurus</a>
                <a class="nav-item {{ request()->routeIs('admin.new-match-numbers') ? 'active' : '' }}"
                    href="{{ route('admin.new-match-numbers') }}"><i class="fa-solid fa-list-check"></i> Nomor
                    Pertandingan</a>
                <a class="nav-item {{ request()->routeIs('admin.new-payment-methods') ? 'active' : '' }}"
                    href="{{ route('admin.new-payment-methods') }}"><i class="fa-solid fa-wallet"></i> Metode
                    Pembayaran</a>
                <a class="nav-item {{ request()->routeIs('admin.new-courts') ? 'active' : '' }}"
                    href="{{ route('admin.new-courts') }}"><i class="fa-solid fa-layer-group"></i> Lapangan
                    (Court)</a>
                <a class="nav-item {{ request()->routeIs('admin.new-pools') ? 'active' : '' }}"
                    href="{{ route('admin.new-pools') }}"><i class="fa-solid fa-sitemap"></i> Bagan (Pool)</a>
                <a class="nav-item {{ request()->routeIs('admin.new-session-times') ? 'active' : '' }}"
                    href="{{ route('admin.new-session-times') }}"><i class="fa-solid fa-clock"></i> Waktu Sesi</a>
                <a class="nav-item {{ request()->routeIs('admin.new-rundowns') ? 'active' : '' }}"
                    href="{{ route('admin.new-rundowns') }}"><i class="fa-solid fa-calendar-alt"></i> Rundown
                    Acara</a>
                <a class="nav-item {{ request()->routeIs('admin.new-athletes*') ? 'active' : '' }}"
                    href="{{ route('admin.new-athletes') }}"><i class="fa-solid fa-users"></i> Data Atlet</a>
            </div>
        </div>
    @endhasanyrole

    {{-- Sistem --}}
    {{-- <div x-data="{ open: @json(request()->routeIs('admin.reports.*')) }">
        <button @click="open = !open" :class="{ 'active': open }" class="nav-section-trigger">
            <span class="label">Sistem</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="collapsible-content">
            <a class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                href="{{ route('admin.reports.athlete-biodata') }}"><i class="fa-solid fa-file-lines"></i>
                Laporan</a>
            <a class="nav-item" href="#"><i class="fa-solid fa-gear"></i> Pengaturan</a>
        </div>
    </div> --}}

    <div class="dd-divider" style="margin: 20px 24px 10px; border-top: 1px solid rgba(255,255,255,0.1);"></div>

    <a class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
        href="{{ route('admin.profile') }}"><i class="fa-solid fa-user-circle"></i> Profil Saya</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a class="nav-item logout" href="{{ route('logout') }}"
            onclick="event.preventDefault(); this.closest('form').submit();" style="color: var(--red-glow);">
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
