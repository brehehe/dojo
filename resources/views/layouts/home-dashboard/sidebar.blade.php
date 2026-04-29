{{-- Home Dashboard Sidebar --}}
<aside class="hd-surface border-r hd-border flex-shrink-0 hidden md:flex flex-col transition-all duration-300 ease-in-out"
       :class="sidebarOpen ? 'w-64' : 'w-[72px]'">

    {{-- Logo --}}
    <div class="h-16 flex items-center border-b hd-border flex-shrink-0"
         :class="sidebarOpen ? 'px-4 gap-3' : 'px-0 gap-0 justify-center'">
        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
            <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-7 h-7 rounded-lg object-cover"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
            <span style="display:none" class="text-white text-xs font-black">SP</span>
        </div>
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity duration-150"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             class="overflow-hidden">
            <p class="hd-text-1 font-black text-sm tracking-tight leading-none">Smart Perkemi</p>
            <p class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest mt-0.5">Admin Panel</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-0.5 hd-scrollbar">

        @php
            $navItem = fn(string $routePattern, string $color = 'indigo') => [
                'active'  => request()->routeIs($routePattern),
                'color'   => $color,
            ];
        @endphp

        {{-- ── Helper macro: nav link classes (merged into one :class) ──
             We inline these directly per item for Alpine reactivity. --}}

        {{-- Home Dashboard --}}
        @php $isActive = request()->routeIs('admin.home-dashboard'); @endphp
        <a href="{{ route('admin.home-dashboard') }}"
           class="group flex items-center gap-3 py-2.5 rounded-xl transition-all border"
           :class="{
               'px-4':           sidebarOpen,
               'px-0 justify-center': !sidebarOpen,
               'hd-nav-active-dark':  darkMode && {{ $isActive ? 'true' : 'false' }},
               'hd-nav-active-light': !darkMode && {{ $isActive ? 'true' : 'false' }},
               'border-transparent hd-text-3 hd-hover': {{ $isActive ? 'false' : 'true' }}
           }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg transition-all
                        {{ $isActive ? 'bg-indigo-500/15 text-indigo-400' : 'hd-text-3 group-hover:text-indigo-400 group-hover:bg-indigo-500/10' }}">
                <i class="fas fa-home text-sm"></i>
            </div>
            <span class="text-sm font-semibold truncate" x-show="sidebarOpen">Home Dashboard</span>
        </a>

        @php $isActive = request()->routeIs('admin.dashboard'); @endphp
        <a href="{{ route('admin.dashboard') }}"
           class="group flex items-center gap-3 py-2.5 rounded-xl transition-all border"
           :class="{
               'px-4':           sidebarOpen,
               'px-0 justify-center': !sidebarOpen,
               'hd-nav-active-dark':  darkMode && {{ $isActive ? 'true' : 'false' }},
               'hd-nav-active-light': !darkMode && {{ $isActive ? 'true' : 'false' }},
               'border-transparent hd-text-3 hd-hover': {{ $isActive ? 'false' : 'true' }}
           }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg hd-text-3 group-hover:text-orange-400 group-hover:bg-orange-500/10 transition-all">
                <i class="fas fa-th-large text-sm"></i>
            </div>
            <span class="text-sm font-semibold truncate" x-show="sidebarOpen">Dashboard Lama</span>
        </a>

        {{-- ── PENDAFTARAN ────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Pendaftaran')
        <div class="pt-4 pb-1" x-show="sidebarOpen">
            <span class="px-1 text-[10px] font-black tracking-widest uppercase hd-section-label">Pendaftaran</span>
        </div>
        <div x-show="!sidebarOpen" class="my-2 border-t hd-border"></div>

        @php $isActive = request()->routeIs('admin.registrations.*'); @endphp
        <a href="{{ route('admin.registrations.index') }}"
           class="group flex items-center gap-3 py-2.5 rounded-xl transition-all border"
           :class="{
               'px-4':           sidebarOpen,
               'px-0 justify-center': !sidebarOpen,
               'bg-blue-600/15 border-blue-500/25 text-blue-400':  darkMode && {{ $isActive ? 'true' : 'false' }},
               'bg-blue-50 border-blue-200 text-blue-700':         !darkMode && {{ $isActive ? 'true' : 'false' }},
               'border-transparent hd-text-3 hd-hover': {{ $isActive ? 'false' : 'true' }}
           }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg transition-all
                        {{ $isActive ? 'bg-blue-500/15 text-blue-400' : 'hd-text-3 group-hover:text-blue-400 group-hover:bg-blue-500/10' }}">
                <i class="fas fa-file-signature text-sm"></i>
            </div>
            <span class="text-sm font-semibold truncate" x-show="sidebarOpen">Data Registrasi</span>
        </a>

        {{-- Laporan Dropdown --}}
        <div x-data="{ open: {{ request()->routeIs('admin.reports.*', 'admin.match-numbers.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-xl transition-all border border-transparent hd-text-3 hd-hover"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg hd-text-3 group-hover:text-violet-400 group-hover:bg-violet-500/10 transition-all">
                    <i class="fas fa-file-invoice text-sm"></i>
                </div>
                <span class="text-sm font-semibold flex-1 text-left truncate" x-show="sidebarOpen">Laporan</span>
                <i class="fas fa-chevron-down text-xs hd-text-4 transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-0.5">
                @foreach([['admin.match-numbers.verified','Laporan Pertandingan'],['admin.reports.registration-by-number','Per Kontingen'],['admin.reports.registration-by-name','Per Nama'],['admin.reports.match-class','Nomor & Kelas'],['admin.reports.athlete-biodata','Biodata Peserta']] as [$r,$l])
                <a href="{{ route($r) }}"
                   class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover hd-text-3
                          {{ request()->routeIs($r) ? '!text-violet-400' : '' }}"
                   :class="{ [darkMode ? 'bg-violet-500/10' : 'bg-violet-50 !text-violet-700']: {{ request()->routeIs($r) ? 'true' : 'false' }} }">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endrole

        {{-- ── MASTER DATA ─────────────────────────────────────────────── --}}
        @role('Super Admin|Admin')
        <div class="pt-4 pb-1" x-show="sidebarOpen">
            <span class="px-1 text-[10px] font-black tracking-widest uppercase hd-section-label">Master Data</span>
        </div>
        <div x-show="!sidebarOpen" class="my-2 border-t hd-border"></div>

        <div x-data="{ open: {{ request()->routeIs('admin.master.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-xl transition-all border border-transparent hd-text-3 hd-hover"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg hd-text-3 group-hover:text-emerald-400 group-hover:bg-emerald-500/10 transition-all">
                    <i class="fas fa-database text-sm"></i>
                </div>
                <span class="text-sm font-semibold flex-1 text-left truncate" x-show="sidebarOpen">Data Referensi</span>
                <i class="fas fa-chevron-down text-xs hd-text-4 transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-0.5">
                @foreach([['admin.master.athletes.index','Master Atlet'],['admin.master.officials.index','Master Official'],['admin.master.contingents.index','Master Kontingen'],['admin.master.referees','Master Wasit'],['admin.master.users','User Accounts'],['admin.master.roles.index','Roles & Permissions'],['admin.master.kyu-levels','Kyu / Dan Levels'],['admin.master.age-groups','Kelompok Umur'],['admin.master.techniques','Teknik & Jurus'],['admin.master.weight-groups','Kelompok Berat'],['admin.master.match-numbers','Nomor Pertandingan'],['admin.master.rundown','Rundown'],['admin.master.court','Court'],['admin.master.pool','Pool'],['admin.master.session-time','Session Time'],['admin.master.payment-method','Metode Pembayaran']] as [$r,$l])
                <a href="{{ route($r) }}"
                   class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover hd-text-3
                          {{ request()->routeIs($r) ? '!text-emerald-400' : '' }}"
                   :class="{ [darkMode ? 'bg-emerald-500/10' : 'bg-emerald-50 !text-emerald-700']: {{ request()->routeIs($r) ? 'true' : 'false' }} }">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endrole

        {{-- ── PERTANDINGAN ─────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Pertandingan')
        <div class="pt-4 pb-1" x-show="sidebarOpen">
            <span class="px-1 text-[10px] font-black tracking-widest uppercase hd-section-label">Pertandingan</span>
        </div>
        <div x-show="!sidebarOpen" class="my-2 border-t hd-border"></div>

        <div x-data="{ open: {{ request()->routeIs('admin.technical-meeting.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-xl transition-all border border-transparent hd-text-3 hd-hover"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg hd-text-3 group-hover:text-amber-400 group-hover:bg-amber-500/10 transition-all">
                    <i class="fas fa-running text-sm"></i>
                </div>
                <span class="text-sm font-semibold flex-1 text-left truncate" x-show="sidebarOpen">Technical Meeting</span>
                <i class="fas fa-chevron-down text-xs hd-text-4 transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-0.5">
                <a href="{{ route('admin.technical-meeting.embu') }}" class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover {{ request()->routeIs('admin.technical-meeting.embu') ? 'text-amber-400' : 'hd-text-3' }}">Drawing Embu</a>
                <a href="{{ route('admin.technical-meeting.randori') }}" class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover {{ request()->routeIs('admin.technical-meeting.randori') ? 'text-amber-400' : 'hd-text-3' }}">Drawing Randori</a>
            </div>
        </div>
        @endrole

        {{-- ── ARBITRASE ────────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Arbitrase')
        <div x-data="{ open: {{ request()->routeIs('admin.arbitrase.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-xl transition-all border border-transparent hd-text-3 hd-hover"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg hd-text-3 group-hover:text-rose-400 group-hover:bg-rose-500/10 transition-all">
                    <i class="fas fa-gavel text-sm"></i>
                </div>
                <span class="text-sm font-semibold flex-1 text-left truncate" x-show="sidebarOpen">Arbitrase</span>
                <i class="fas fa-chevron-down text-xs hd-text-4 transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-0.5">
                @foreach([['admin.arbitrase.scoring.index','Scoring Live'],['admin.arbitrase.generate-referee','Penugasan Wasit'],['admin.arbitrase.laporan-hasil','Laporan Hasil Juara'],['admin.arbitrase.laporan-skor','Laporan Skor'],['admin.arbitrase.rekapitulasi-randori','Rekapitulasi Randori'],['admin.arbitrase.rekapitulasi-embu','Rekapitulasi Embu']] as [$r,$l])
                <a href="{{ route($r) }}" class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover {{ request()->routeIs($r) ? 'text-rose-400' : 'hd-text-3' }}">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endrole

        {{-- ── PANITERA ─────────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Panitera')
        <div class="pt-4 pb-1" x-show="sidebarOpen">
            <span class="px-1 text-[10px] font-black tracking-widest uppercase hd-section-label">Panitera</span>
        </div>
        <div x-show="!sidebarOpen" class="my-2 border-t hd-border"></div>
        <div x-data="{ open: {{ request()->routeIs('admin.panitera.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-xl transition-all border border-transparent hd-text-3 hd-hover"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg hd-text-3 group-hover:text-cyan-400 group-hover:bg-cyan-500/10 transition-all">
                    <i class="fas fa-clipboard-check text-sm"></i>
                </div>
                <span class="text-sm font-semibold flex-1 text-left truncate" x-show="sidebarOpen">Panitera</span>
                <i class="fas fa-chevron-down text-xs hd-text-4 transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-0.5">
                <a href="{{ route('admin.panitera.scoring.index') }}" class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover {{ request()->routeIs('admin.panitera.scoring.index') ? 'text-cyan-400' : 'hd-text-3' }}">Penilaian Wasit</a>
                <a href="{{ route('admin.panitera.scoring.embu.result') }}" class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover {{ request()->routeIs('admin.panitera.scoring.embu.result') ? 'text-cyan-400' : 'hd-text-3' }}">Hasil & Juara Embu</a>
                <a href="{{ route('admin.panitera.announcer') }}" class="flex items-center gap-2 py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover {{ request()->routeIs('admin.panitera.announcer') ? 'text-cyan-400' : 'hd-text-3' }}">
                    Panggilan (PA) <span class="text-[9px] font-black bg-cyan-900/50 text-cyan-400 px-1 py-0.5 rounded uppercase">VOICE</span>
                </a>
            </div>
        </div>
        @endrole

        {{-- ── SMART WASIT ──────────────────────────────────────────────── --}}
        @role('Super Admin|Admin')
        <div class="pt-4 pb-1" x-show="sidebarOpen">
            <span class="px-1 text-[10px] font-black tracking-widest uppercase hd-section-label">Smart Wasit</span>
        </div>
        <div x-show="!sidebarOpen" class="my-2 border-t hd-border"></div>
        <div x-data="{ open: {{ request()->routeIs('admin.smart-wasit.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-xl transition-all border border-transparent hd-text-3 hd-hover"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg hd-text-3 group-hover:text-purple-400 group-hover:bg-purple-500/10 transition-all">
                    <i class="fas fa-balance-scale text-sm"></i>
                </div>
                <span class="text-sm font-semibold flex-1 text-left truncate" x-show="sidebarOpen">Smart Wasit</span>
                <i class="fas fa-chevron-down text-xs hd-text-4 transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-0.5">
                @foreach([['admin.smart-wasit.summary','Laporan Komprehensif'],['admin.smart-wasit.ranking-skw','Ranking SKW'],['admin.smart-wasit.ranking-iaw','Ranking IAW'],['admin.smart-wasit.ranking-ik','Ranking IK'],['admin.smart-wasit.perbabak','Laporan Perbabak']] as [$r,$l])
                <a href="{{ route($r) }}" class="block py-1.5 px-3 text-xs font-semibold rounded-lg transition-all hd-hover {{ request()->routeIs($r) ? 'text-purple-400' : 'hd-text-3' }}">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endrole
    </nav>

    {{-- Sidebar Footer --}}
    <div class="p-3 border-t hd-border flex-shrink-0">
        <button @click="sidebarOpen = !sidebarOpen"
                class="w-full flex items-center gap-3 py-2.5 rounded-xl hd-text-3 hd-hover transition-all text-xs font-bold"
                :class="{ 'px-4 justify-start': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </div>
            <span x-show="sidebarOpen" class="text-xs">Collapse</span>
        </button>
    </div>
</aside>
