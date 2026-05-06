<aside class="bg-slate-900 flex-shrink-0 hidden md:flex flex-col transition-all duration-300"
    x-data="{ collapsed: false }" :class="collapsed ? 'w-20' : 'w-64'">
    <!-- Sidebar Header (Logo) -->
    <div class="h-16 md:h-20 flex items-center justify-center border-b border-white/10 px-4 bg-slate-950/50">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center shadow-lg transform transition-transform hover:scale-105 cursor-pointer">
                <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover">
            </div>
            <div class="flex flex-col" x-show="!collapsed">
                <span class="text-lg font-black tracking-tighter uppercase leading-none text-white drop-shadow-sm">Smart
                    Perkemi</span>
            </div>
        </div>
    </div>

    <!-- Sidebar Scrollable Menu -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden py-6 px-3 space-y-1 custom-scrollbar">
        <a href="{{ route('admin.new-dashboard') }}"
            class="flex items-center gap-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.new-dashboard') ? 'bg-orange-600 text-white shadow-md shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
            :class="collapsed ? 'justify-center px-0' : 'px-3'">
            <i class="fas fa-th-large w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
            <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Dashboard</span>
        </a>

        @role('Super Admin|Admin|Pendaftaran')
        <div class="pt-4 pb-1 text-center" :class="collapsed ? '' : 'text-left'">
            <span class="px-3 text-xs font-black tracking-wider text-slate-500 uppercase"
                x-show="!collapsed">Pendaftaran</span>
            <hr class="border-white/5 mt-2 mb-1" x-show="collapsed">
        </div>
        <a href="{{ route('admin.registrations.index') }}"
            class="flex items-center gap-3 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.registrations.*') ? 'bg-orange-600 text-white shadow-md shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
            :class="collapsed ? 'justify-center px-0' : 'px-3'">
            <i class="fas fa-file-signature w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
            <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Data Registrasi</span>
        </a>

        <!-- Laporan Dropdown -->
        <div x-data="{ open: {{ request()->routeIs('admin.match-numbers.verified', 'admin.reports.*') ? 'true' : 'false' }} }"
            class="mt-1">
            <button @click="open = !open"
                class="w-full flex items-center py-3 rounded-xl transition-all group {{ request()->routeIs('admin.match-numbers.verified', 'admin.reports.*') ? 'bg-white/5 text-orange-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                :class="collapsed ? 'justify-center px-0' : 'justify-between px-3'">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-invoice w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
                    <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Laporan</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"
                    x-show="!collapsed"></i>
            </button>
            <div x-show="open && !collapsed" x-collapse class="pl-9 pr-2 py-1 space-y-1">
                <a href="{{ route('admin.match-numbers.verified') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.match-numbers.verified') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Pertandingan</a>
                <a href="{{ route('admin.reports.registration-by-number') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.reports.registration-by-number') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Per Kontingen</a>
                <a href="{{ route('admin.reports.registration-by-name') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.reports.registration-by-name') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Per Nama</a>
                <a href="{{ route('admin.reports.match-class') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.reports.match-class') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Nomor & Kelas</a>
                <a href="{{ route('admin.reports.athlete-biodata') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.reports.athlete-biodata') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Biodata
                    Peserta</a>
            </div>
        </div>
        @endrole

        @role('Super Admin|Admin')
        <div class="pt-4 pb-1 text-center" :class="collapsed ? '' : 'text-left'">
            <span class="px-3 text-xs font-black tracking-wider text-slate-500 uppercase" x-show="!collapsed">Master
                Data</span>
            <hr class="border-white/5 mt-2 mb-1" x-show="collapsed">
        </div>
        <div x-data="{ open: {{ request()->routeIs('admin.master.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center py-3 rounded-xl transition-all group {{ request()->routeIs('admin.master.*') ? 'bg-white/5 text-orange-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                :class="collapsed ? 'justify-center px-0' : 'justify-between px-3'">
                <div class="flex items-center gap-3">
                    <i class="fas fa-database w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
                    <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Data Referensi</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"
                    x-show="!collapsed"></i>
            </button>
            <div x-show="open && !collapsed" x-collapse class="pl-9 pr-2 py-1 space-y-1">
                <a href="{{ route('admin.master.athletes.index') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.athletes.*') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Master
                    Atlet</a>
                <a href="{{ route('admin.master.officials.index') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.officials.*') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Master
                    Official</a>
                <a href="{{ route('admin.master.users') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.users') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">User
                    Accounts</a>
                <a href="{{ route('admin.master.roles.index') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.roles.*') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Roles
                    & Permissions</a>
                <a href="{{ route('admin.master.contingents.index') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.contingents.*') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Master
                    Kontingen</a>
                <a href="{{ route('admin.master.referees') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.referees') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Master
                    Wasit</a>
                <a href="{{ route('admin.master.kyu-levels') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.kyu-levels') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Kyu
                    / Dan Levels</a>
                <a href="{{ route('admin.master.age-groups') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.age-groups') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Kelompok
                    Umur</a>
                <a href="{{ route('admin.master.techniques') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.techniques') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Teknik
                    & Jurus</a>
                <a href="{{ route('admin.master.weight-groups') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.weight-groups') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Kelompok
                    Berat Badan</a>
                <a href="{{ route('admin.master.match-numbers') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.match-numbers') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Nomor
                    Pertandingan</a>
                <a href="{{ route('admin.master.rundown') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.rundown') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Rundown</a>
                <a href="{{ route('admin.master.court') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.court') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Court</a>
                <a href="{{ route('admin.master.pool') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.pool') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Pool</a>
                <a href="{{ route('admin.master.session-time') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.session-time') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Session
                    Time</a>
                <a href="{{ route('admin.master.payment-method') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.master.payment-method') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Metode
                    Pembayaran</a>
            </div>
        </div>
        @endrole

        @role('Super Admin|Admin|Pertandingan')
        <div class="pt-4 pb-1 text-center" :class="collapsed ? '' : 'text-left'">
            <span class="px-3 text-xs font-black tracking-wider text-slate-500 uppercase"
                x-show="!collapsed">Pertandingan</span>
            <hr class="border-white/5 mt-2 mb-1" x-show="collapsed">
        </div>
        <div x-data="{ open: {{ request()->routeIs('admin.technical-meeting.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center py-3 rounded-xl transition-all group {{ request()->routeIs('admin.technical-meeting.*') ? 'bg-white/5 text-orange-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                :class="collapsed ? 'justify-center px-0' : 'justify-between px-3'">
                <div class="flex items-center gap-3">
                    <i class="fas fa-running w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
                    <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Technical Meeting</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"
                    x-show="!collapsed"></i>
            </button>
            <div x-show="open && !collapsed" x-collapse class="pl-9 pr-2 py-1 space-y-1">
                <a href="{{ route('admin.technical-meeting.embu') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.technical-meeting.embu') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Drawing
                    Embu</a>
                <a href="{{ route('admin.technical-meeting.randori') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.technical-meeting.randori') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Drawing
                    Randori</a>
            </div>
        </div>
        @endrole

        @role('Super Admin|Admin|Arbitrase')
        <div class="pt-4 pb-1 text-center" :class="collapsed ? '' : 'text-left'">
            <span class="px-3 text-xs font-black tracking-wider text-slate-500 uppercase" x-show="!collapsed">Perwasitan
                & Arbitrase</span>
            <hr class="border-white/5 mt-2 mb-1" x-show="collapsed">
        </div>
        <div
            x-data="{ open: {{ request()->routeIs('admin.arbitrase.referees', 'admin.arbitrase.generate-referee') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center py-3 rounded-xl transition-all group {{ request()->routeIs('admin.arbitrase.referees', 'admin.arbitrase.generate-referee') ? 'bg-white/5 text-orange-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                :class="collapsed ? 'justify-center px-0' : 'justify-between px-3'">
                <div class="flex items-center gap-3">
                    <i class="fas fa-balance-scale w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
                    <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Arbitrase</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"
                    x-show="!collapsed"></i>
            </button>
            <div x-show="open && !collapsed" x-collapse class="pl-9 pr-2 py-1 space-y-1">
                <a href="{{ route('admin.arbitrase.referees') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.arbitrase.referees') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Master
                    Wasit</a>
                <a href="{{ route('admin.arbitrase.generate-referee') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.arbitrase.generate-referee') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Penugasan
                    Wasit</a>
            </div>
        </div>
        @endrole

        @role('Super Admin|Admin|Panitera')
        <div class="pt-4 pb-1 text-center" :class="collapsed ? '' : 'text-left'">
            <span class="px-3 text-xs font-black tracking-wider text-slate-500 uppercase"
                x-show="!collapsed">Panitera</span>
            <hr class="border-white/5 mt-2 mb-1" x-show="collapsed">
        </div>
        <div
            x-data="{ open: {{ request()->routeIs('admin.panitera.*', 'admin.arbitrase.laporan-hasil', 'admin.arbitrase.laporan-skor', 'admin.arbitrase.rekapitulasi-randori', 'admin.arbitrase.rekapitulasi-embu') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center py-3 rounded-xl transition-all group {{ request()->routeIs('admin.panitera.*', 'admin.arbitrase.laporan-hasil', 'admin.arbitrase.laporan-skor', 'admin.arbitrase.rekapitulasi-randori', 'admin.arbitrase.rekapitulasi-embu') ? 'bg-white/5 text-orange-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                :class="collapsed ? 'justify-center px-0' : 'justify-between px-3'">
                <div class="flex items-center gap-3">
                    <i
                        class="fas fa-clipboard-check w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
                    <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Panitera</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"
                    x-show="!collapsed"></i>
            </button>
            <div x-show="open && !collapsed" x-collapse class="pl-9 pr-2 py-1 space-y-1">
                <a href="{{ route('admin.panitera.scoring.index') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.panitera.scoring.index') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Penilaian
                    Wasit</a>
                <a href="{{ route('admin.panitera.scoring.embu.testbench') }}"
                    class="flex items-center py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.panitera.scoring.embu.testbench') ? 'text-emerald-400' : 'text-slate-500 hover:text-slate-300' }}">
                    <span class="flex-1">Testbench Embu</span>
                    <span
                        class="text-[10px] font-black bg-emerald-900/50 text-emerald-400 px-1.5 py-0.5 rounded uppercase">TEST</span>
                </a>
                <a href="{{ route('admin.panitera.scoring.embu.result') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.panitera.scoring.embu.result') ? 'text-amber-400' : 'text-slate-500 hover:text-slate-300' }}">Hasil
                    & Juara Embu</a>
                <a href="{{ route('admin.panitera.announcer') }}"
                    class="flex items-center py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.panitera.announcer') ? 'text-indigo-400' : 'text-slate-500 hover:text-slate-300' }}">
                    <span class="flex-1">Panggilan (PA)</span>
                    <span
                        class="text-[10px] font-black bg-indigo-900/50 text-indigo-400 px-1.5 py-0.5 rounded uppercase tracking-tighter">VOICE</span>
                </a>
                <a href="{{ route('admin.arbitrase.laporan-hasil') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.arbitrase.laporan-hasil') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Hasil Juara</a>
                <a href="{{ route('admin.arbitrase.laporan-skor') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.arbitrase.laporan-skor') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Skor Menyeluruh</a>
                <a href="{{ route('admin.arbitrase.rekapitulasi-randori') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.arbitrase.rekapitulasi-randori') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Rekapitulasi
                    Randori</a>
                <a href="{{ route('admin.arbitrase.rekapitulasi-embu') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.arbitrase.rekapitulasi-embu') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Rekapitulasi
                    Embu</a>
            </div>
        </div>
        @endrole

        @role('Super Admin|Admin')
        <div class="pt-4 pb-1 text-center" :class="collapsed ? '' : 'text-left'">
            <span class="px-3 text-xs font-black tracking-wider text-slate-500 uppercase" x-show="!collapsed">Smart
                Wasit</span>
            <hr class="border-white/5 mt-2 mb-1" x-show="collapsed">
        </div>
        <div x-data="{ open: {{ request()->routeIs('admin.smart-wasit.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center py-3 rounded-xl transition-all group {{ request()->routeIs('admin.smart-wasit.*') ? 'bg-white/5 text-orange-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                :class="collapsed ? 'justify-center px-0' : 'justify-between px-3'">
                <div class="flex items-center gap-3">
                    <i class="fas fa-balance-scale w-5 text-center text-[15px] opacity-70 group-hover:opacity-100"></i>
                    <span class="text-[15px] font-bold tracking-tight" x-show="!collapsed">Smart Wasit</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"
                    x-show="!collapsed"></i>
            </button>
            <div x-show="open && !collapsed" x-collapse class="pl-9 pr-2 py-1 space-y-1">
                <a href="{{ route('admin.smart-wasit.summary') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.smart-wasit.summary') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Komprehensif</a>
                <a href="{{ route('admin.smart-wasit.ranking-skw') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.smart-wasit.ranking-skw') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Ranking
                    SKW</a>
                <a href="{{ route('admin.smart-wasit.ranking-iaw') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.smart-wasit.ranking-iaw') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Ranking
                    IAW</a>
                <a href="{{ route('admin.smart-wasit.ranking-ik') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.smart-wasit.ranking-ik') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Ranking
                    IK</a>
                <a href="{{ route('admin.smart-wasit.ranking-iv') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.smart-wasit.ranking-iv') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Ranking
                    IV</a>
                <a href="{{ route('admin.smart-wasit.perbabak') }}"
                    class="block py-2 text-[14px] font-semibold transition-colors {{ request()->routeIs('admin.smart-wasit.perbabak') ? 'text-white' : 'text-slate-500 hover:text-slate-300' }}">Laporan
                    Perbabak</a>
            </div>
        </div>
        @endrole

    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-white/5">
        <button @click="collapsed = !collapsed"
            class="w-full flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-300 hover:bg-white/5 transition-colors">
            <i class="fas" :class="collapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
        </button>
    </div>
</aside>
<style>
    /* Add smooth collapse if Alpine collapse is available, otherwise normal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #334155;
        /* slate-700 */
        border-radius: 4px;
    }

    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: #475569;
        /* slate-600 */
    }
</style>