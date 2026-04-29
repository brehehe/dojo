{{-- Home Dashboard Sidebar (Luwes Style) --}}
<aside class="bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 flex-shrink-0 hidden md:flex flex-col transition-all duration-300 ease-in-out z-50 shadow-sm"
       :class="sidebarOpen ? 'w-64' : 'w-[72px]'">

    {{-- Logo --}}
    <div class="h-16 flex items-center border-b border-slate-100 dark:border-slate-800 flex-shrink-0"
         :class="sidebarOpen ? 'px-4 gap-3' : 'px-0 gap-0 justify-center'">
        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 bg-blue-50 dark:bg-blue-500/10 text-blue-500">
            <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-8 h-8 rounded-xl object-cover shadow-sm"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
            <span style="display:none" class="text-blue-600 dark:text-blue-400 text-sm font-black">SP</span>
        </div>
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             class="overflow-hidden">
            <p class="text-slate-800 dark:text-white font-black text-sm tracking-tight leading-none">Smart Perkemi</p>
            <p class="text-blue-500 text-[10px] font-bold uppercase tracking-widest mt-0.5">Admin Panel</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-1">

        {{-- Home Dashboard --}}
        @php $isActive = request()->routeIs('admin.home-dashboard'); @endphp
        <a href="{{ route('admin.home-dashboard') }}"
           class="group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300"
           :class="{
               'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen,
               'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 font-bold': {{ $isActive ? 'true' : 'false' }},
               'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium': {{ $isActive ? 'false' : 'true' }}
           }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                <i class="fas fa-home text-[15px]"></i>
            </div>
            <span class="text-sm truncate" x-show="sidebarOpen">Home Dashboard</span>
        </a>

        @php $isActive = request()->routeIs('admin.dashboard'); @endphp
        <a href="{{ route('admin.dashboard') }}"
           class="group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300"
           :class="{
               'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen,
               'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 font-bold': {{ $isActive ? 'true' : 'false' }},
               'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium': {{ $isActive ? 'false' : 'true' }}
           }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                <i class="fas fa-th-large text-[15px]"></i>
            </div>
            <span class="text-sm truncate" x-show="sidebarOpen">Dashboard Lama</span>
        </a>

        {{-- ── PENDAFTARAN ────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Pendaftaran')
        <div class="pt-5 pb-2" x-show="sidebarOpen">
            <span class="px-3 text-[10px] font-black tracking-widest uppercase text-slate-400 dark:text-slate-500">Pendaftaran</span>
        </div>
        <div x-show="!sidebarOpen" class="my-3 border-t border-slate-100 dark:border-slate-800 mx-2"></div>

        @php $isActive = request()->routeIs('admin.registrations.*'); @endphp
        <a href="{{ route('admin.registrations.index') }}"
           class="group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300"
           :class="{
               'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen,
               'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold': {{ $isActive ? 'true' : 'false' }},
               'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium': {{ $isActive ? 'false' : 'true' }}
           }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                <i class="fas fa-file-signature text-[15px]"></i>
            </div>
            <span class="text-sm truncate" x-show="sidebarOpen">Data Registrasi</span>
        </a>

        {{-- Laporan Dropdown --}}
        <div x-data="{ open: {{ request()->routeIs('admin.reports.*', 'admin.match-numbers.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                    <i class="fas fa-file-invoice text-[15px]"></i>
                </div>
                <span class="text-sm flex-1 text-left truncate" x-show="sidebarOpen">Laporan</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                @foreach([['admin.match-numbers.verified','Pertandingan'],['admin.reports.registration-by-number','Per Kontingen'],['admin.reports.registration-by-name','Per Nama'],['admin.reports.match-class','Nomor & Kelas'],['admin.reports.athlete-biodata','Biodata']] as [$r,$l])
                <a href="{{ route($r) }}"
                   class="block py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs($r) ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">
                   {{ $l }}
                </a>
                @endforeach
            </div>
        </div>
        @endrole

        {{-- ── MASTER DATA ─────────────────────────────────────────────── --}}
        @role('Super Admin|Admin')
        <div class="pt-5 pb-2" x-show="sidebarOpen">
            <span class="px-3 text-[10px] font-black tracking-widest uppercase text-slate-400 dark:text-slate-500">Master Data</span>
        </div>
        <div x-show="!sidebarOpen" class="my-3 border-t border-slate-100 dark:border-slate-800 mx-2"></div>

        <div x-data="{ open: {{ request()->routeIs('admin.master.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                    <i class="fas fa-database text-[15px]"></i>
                </div>
                <span class="text-sm flex-1 text-left truncate" x-show="sidebarOpen">Data Referensi</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                @foreach([['admin.master.athletes.index','Master Atlet'],['admin.master.officials.index','Master Official'],['admin.master.contingents.index','Master Kontingen'],['admin.master.referees','Master Wasit'],['admin.master.users','User Accounts'],['admin.master.roles.index','Roles & Permissions'],['admin.master.kyu-levels','Kyu / Dan Levels'],['admin.master.age-groups','Kelompok Umur'],['admin.master.techniques','Teknik & Jurus'],['admin.master.weight-groups','Kelompok Berat'],['admin.master.match-numbers','Nomor Pertandingan'],['admin.master.rundown','Rundown'],['admin.master.court','Court'],['admin.master.pool','Pool'],['admin.master.session-time','Session Time'],['admin.master.payment-method','Metode Pembayaran']] as [$r,$l])
                <a href="{{ route($r) }}"
                   class="block py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs($r) ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">
                   {{ $l }}
                </a>
                @endforeach
            </div>
        </div>
        @endrole

        {{-- ── PERTANDINGAN ─────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Pertandingan')
        <div class="pt-5 pb-2" x-show="sidebarOpen">
            <span class="px-3 text-[10px] font-black tracking-widest uppercase text-slate-400 dark:text-slate-500">Pertandingan</span>
        </div>
        <div x-show="!sidebarOpen" class="my-3 border-t border-slate-100 dark:border-slate-800 mx-2"></div>

        <div x-data="{ open: {{ request()->routeIs('admin.technical-meeting.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                    <i class="fas fa-running text-[15px]"></i>
                </div>
                <span class="text-sm flex-1 text-left truncate" x-show="sidebarOpen">T. Meeting</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                @foreach([['admin.technical-meeting.embu','Drawing Embu'],['admin.technical-meeting.randori','Drawing Randori']] as [$r,$l])
                <a href="{{ route($r) }}" class="block py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs($r) ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endrole

        {{-- ── ARBITRASE ────────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Arbitrase')
        <div class="pt-5 pb-2" x-show="sidebarOpen">
            <span class="px-3 text-[10px] font-black tracking-widest uppercase text-slate-400 dark:text-slate-500">Arbitrase</span>
        </div>
        <div x-show="!sidebarOpen" class="my-3 border-t border-slate-100 dark:border-slate-800 mx-2"></div>
        <div x-data="{ open: {{ request()->routeIs('admin.arbitrase.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                    <i class="fas fa-gavel text-[15px]"></i>
                </div>
                <span class="text-sm flex-1 text-left truncate" x-show="sidebarOpen">Arbitrase</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                @foreach([['admin.arbitrase.scoring.index','Scoring Live'],['admin.arbitrase.generate-referee','Penugasan Wasit'],['admin.arbitrase.laporan-hasil','Laporan Hasil'],['admin.arbitrase.laporan-skor','Laporan Skor'],['admin.arbitrase.rekapitulasi-randori','Rekap Randori'],['admin.arbitrase.rekapitulasi-embu','Rekap Embu']] as [$r,$l])
                <a href="{{ route($r) }}" class="block py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs($r) ? 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endrole

        {{-- ── PANITERA ─────────────────────────────────────────────────── --}}
        @role('Super Admin|Admin|Panitera')
        <div class="pt-5 pb-2" x-show="sidebarOpen">
            <span class="px-3 text-[10px] font-black tracking-widest uppercase text-slate-400 dark:text-slate-500">Panitera</span>
        </div>
        <div x-show="!sidebarOpen" class="my-3 border-t border-slate-100 dark:border-slate-800 mx-2"></div>
        <div x-data="{ open: {{ request()->routeIs('admin.panitera.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                    <i class="fas fa-clipboard-check text-[15px]"></i>
                </div>
                <span class="text-sm flex-1 text-left truncate" x-show="sidebarOpen">Panitera</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                <a href="{{ route('admin.panitera.scoring.index') }}" class="block py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs('admin.panitera.scoring.index') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">Penilaian Wasit</a>
                <a href="{{ route('admin.panitera.scoring.embu.result') }}" class="block py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs('admin.panitera.scoring.embu.result') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">Hasil Embu</a>
                <a href="{{ route('admin.panitera.announcer') }}" class="flex items-center gap-2 py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs('admin.panitera.announcer') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">
                    Panggilan <span class="text-[9px] font-black bg-cyan-100 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 px-1 py-0.5 rounded">VOICE</span>
                </a>
            </div>
        </div>
        @endrole

        {{-- ── SMART WASIT ──────────────────────────────────────────────── --}}
        @role('Super Admin|Admin')
        <div class="pt-5 pb-2" x-show="sidebarOpen">
            <span class="px-3 text-[10px] font-black tracking-widest uppercase text-slate-400 dark:text-slate-500">Smart Wasit</span>
        </div>
        <div x-show="!sidebarOpen" class="my-3 border-t border-slate-100 dark:border-slate-800 mx-2"></div>
        <div x-data="{ open: {{ request()->routeIs('admin.smart-wasit.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full group flex items-center gap-3 py-2.5 rounded-2xl transition-all duration-300 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 font-medium"
                    :class="{ 'px-4': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl transition-transform group-hover:scale-110">
                    <i class="fas fa-balance-scale text-[15px]"></i>
                </div>
                <span class="text-sm flex-1 text-left truncate" x-show="sidebarOpen">Smart Wasit</span>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                @foreach([['admin.smart-wasit.summary','Laporan Komprehensif'],['admin.smart-wasit.ranking-skw','Ranking SKW'],['admin.smart-wasit.ranking-iaw','Ranking IAW'],['admin.smart-wasit.ranking-ik','Ranking IK'],['admin.smart-wasit.perbabak','Laporan Perbabak']] as [$r,$l])
                <a href="{{ route($r) }}" class="block py-2 px-3 text-xs rounded-xl transition-all duration-300 {{ request()->routeIs($r) ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }}">{{ $l }}</a>
                @endforeach
            </div>
        </div>
        @endrole
    </nav>

    {{-- Sidebar Footer --}}
    <div class="p-3 border-t border-slate-100 dark:border-slate-800 flex-shrink-0">
        <button @click="sidebarOpen = !sidebarOpen"
                class="w-full flex items-center gap-3 py-2.5 rounded-2xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-slate-200 transition-all duration-300 text-xs font-bold"
                :class="{ 'px-4 justify-start': sidebarOpen, 'px-0 justify-center': !sidebarOpen }">
            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </div>
            <span x-show="sidebarOpen" class="text-xs">Collapse Panel</span>
        </button>
    </div>
</aside>
