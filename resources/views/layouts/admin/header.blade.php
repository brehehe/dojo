<!-- Header / Two-Tier Navigation -->
<header class="sticky top-0 z-[100]" x-data="{ mobileMenuOpen: false }">
    <!-- Top Tier: Branding, Search, User Utility -->
    <div class="bg-gradient-to-r from-orange-700 via-orange-600 to-orange-500 text-white shadow-lg border-b border-white/10 relative">
        <!-- Dedicated container for decorations to keep them clipped without hiding dropdowns -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-60 h-60 bg-white/5 rounded-full blur-2xl"></div>
        </div>

        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Branding Section -->
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 md:w-11 md:h-11 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105 active:scale-95 cursor-pointer">
                        <img src="{{ asset('build/logo.jpeg') }}" alt="Logo" class="w-7 h-7 md:w-9 md:h-9 rounded-lg object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm md:text-xl font-black tracking-tighter uppercase leading-none text-white drop-shadow-sm">Eco Mart</span>
                        <span class="text-[8px] md:text-[10px] text-orange-100 font-bold tracking-[0.2em] uppercase opacity-80">Smart Backend</span>
                    </div>
                </div>

                <!-- Global Search Section (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-xl px-12">
                    <div class="relative w-full group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-white/40 group-focus-within:text-white transition-colors">
                            <i class="fas fa-search text-sm"></i>
                        </span>
                        <input type="text" placeholder="Search anything..." 
                            class="w-full pl-11 pr-4 py-2.5 bg-white/10 border border-white/10 rounded-2xl text-sm font-medium placeholder:text-white/40 focus:bg-white/20 focus:border-white/20 focus:ring-4 focus:ring-white/5 transition-all outline-none backdrop-blur-sm">
                    </div>
                </div>

                <!-- Utility & Profile Actions -->
                <div class="flex items-center gap-2 md:gap-6">
                    <!-- Icons Utility -->
                    <div class="hidden sm:flex items-center gap-1 md:gap-4">
                        <button class="w-10 h-10 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-xl transition-all relative group">
                            <i class="far fa-envelope text-lg"></i>
                        </button>
                        <button class="w-10 h-10 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-xl transition-all relative group">
                            <i class="far fa-bell text-lg"></i>
                            <span class="absolute top-2.5 right-2.5 w-4 h-4 bg-orange-500 border-2 border-orange-700 rounded-full text-[8px] font-black flex items-center justify-center shadow-sm">3</span>
                        </button>
                    </div>

                    <div class="w-px h-8 bg-white/10 hidden sm:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ userDropdownOpen: false }" @click.away="userDropdownOpen = false">
                        <button @click="userDropdownOpen = !userDropdownOpen" 
                                class="flex items-center gap-3 group focus:outline-none p-1 rounded-2xl hover:bg-white/5 transition-all">
                            <div class="hidden md:flex flex-col items-end mr-1">
                                <span class="text-sm font-extrabold text-white group-hover:text-yellow-200 transition-colors leading-none mb-1">{{ auth()->user()->name }}</span>
                                <span class="text-[9px] text-white/50 uppercase tracking-widest font-black leading-none">{{ auth()->user()->getRoleNames()->first() }}</span>
                            </div>
                            <div class="relative">
                                <div class="w-9 h-9 md:w-11 md:h-11 bg-white/20 backdrop-blur-md border border-white/20 rounded-xl flex items-center justify-center text-white font-black shadow-lg group-hover:scale-105 transition-transform overflow-hidden">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-orange-600 rounded-full shadow-sm"></div>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-white/30 group-hover:text-white transition-all hidden md:block" :class="userDropdownOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div x-show="userDropdownOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="absolute right-0 mt-3 w-64 bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] py-4 z-[110] border border-slate-100 overflow-hidden"
                             x-cloak>
                            <div class="px-6 py-4 mb-2 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
                                <div class="w-12 h-12 bg-orange-600 rounded-2xl flex items-center justify-center text-white font-black shadow-lg">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black text-slate-800 truncate leading-none mb-1">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold truncate leading-none">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <div class="px-3 pb-2 pt-2">
                                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 rounded-xl transition-all">
                                    <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    Profile Settings
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 rounded-xl transition-all">
                                    <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    Account Security
                                </a>
                            </div>
                            <div class="px-4 mt-2 border-t border-slate-50 pt-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-3 bg-red-50 hover:bg-red-100 text-red-600 py-3.5 rounded-2xl transition-all font-black text-xs uppercase tracking-widest group">
                                        <i class="fas fa-sign-out-alt opacity-50 group-hover:translate-x-1 transition-transform"></i>
                                        Logout Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Toggle -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden w-10 h-10 flex items-center justify-center bg-white/10 rounded-xl text-white">
                        <i class="fas text-xl" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Tier: Horizontal Navigation (White) -->
    <div class="bg-white border-b border-slate-200 hidden md:block shadow-sm">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 h-14">
                @if(auth()->user()->hasRole('Super Admin|Admin'))
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-2.5 px-4 h-full transition-all group border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-500 hover:text-orange-600' }}">
                    <i class="fas fa-th-large text-sm opacity-50 group-hover:opacity-100"></i>
                    <span class="text-sm font-bold tracking-tight">Dashboard</span>
                </a>
                @else
                <a href="{{ route('contingent.dashboard') }}" 
                   class="flex items-center gap-2.5 px-4 h-full transition-all group border-b-2 {{ request()->routeIs('contingent.dashboard') ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-500 hover:text-orange-600' }}">
                    <i class="fas fa-th-large text-sm opacity-50 group-hover:opacity-100"></i>
                    <span class="text-sm font-bold tracking-tight">Dashboard</span>
                </a>
                @endif

                @role('Super Admin|Admin|Pendaftaran')
                <a href="{{ route('admin.registrations.index') }}" 
                   class="flex items-center gap-2.5 px-4 h-full transition-all group border-b-2 {{ request()->routeIs('admin.registrations.*') ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-500 hover:text-orange-600' }}">
                    <i class="fas fa-file-signature text-sm opacity-50 group-hover:opacity-100"></i>
                    <span class="text-sm font-bold tracking-tight">Data Registrasi</span>
                </a>

                <div class="relative h-full" x-data="{ reportDropdownOpen: false }" @mouseenter="reportDropdownOpen = true" @mouseleave="reportDropdownOpen = false">
                    <button class="flex items-center gap-2.5 px-4 h-full transition-all group border-b-2 {{ request()->routeIs('admin.match-numbers.verified', 'admin.reports.*') ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-500 hover:text-orange-600' }}">
                        <i class="fas fa-file-invoice text-sm opacity-50 group-hover:opacity-100"></i>
                        <span class="text-sm font-bold tracking-tight">Laporan</span>
                        <i class="fas fa-chevron-down text-[8px] transition-transform duration-300" :class="reportDropdownOpen ? 'rotate-180 opacity-100' : 'opacity-20'"></i>
                    </button>
                    
                    <div x-show="reportDropdownOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 w-64 bg-white border border-slate-100 rounded-2xl shadow-2xl py-3 z-[110]"
                         x-cloak>
                         <a href="{{ route('admin.match-numbers.verified') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 p-1.5 min-w-[28px]">
                                 <i class="fas fa-medal text-xs"></i>
                             </div>
                             Laporan Pertandingan
                         </a>
                         <a href="{{ route('admin.reports.registration-by-number') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 p-1.5 min-w-[28px]">
                                 <i class="fas fa-file-excel text-xs"></i>
                             </div>
                             Laporan Per Kontingen
                         </a>
                         <a href="{{ route('admin.reports.registration-by-name') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 p-1.5 min-w-[28px]">
                                 <i class="fas fa-id-card text-xs"></i>
                             </div>
                             Laporan Per Nama Peserta
                         </a>
                         <a href="{{ route('admin.reports.match-class') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 p-1.5 min-w-[28px]">
                                 <i class="fas fa-layer-group text-xs"></i>
                             </div>
                             Laporan Nomor & Kelas
                         </a>
                         <a href="{{ route('admin.reports.athlete-biodata') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 p-1.5 min-w-[28px]">
                                 <i class="fas fa-id-card-alt text-xs"></i>
                             </div>
                             Laporan Biodata Peserta
                         </a>
                    </div>
                </div>
                @endrole

                @role('Super Admin|Admin')
                <div class="relative h-full" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="flex items-center gap-2.5 px-4 h-full transition-all group border-b-2 {{ request()->routeIs('admin.master.*') ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-500 hover:text-orange-600 font-medium' }}">
                        <i class="fas fa-database text-sm opacity-50 group-hover:opacity-100"></i>
                        <span class="text-sm font-bold tracking-tight">Master Data</span>
                        <i class="fas fa-chevron-down text-[8px] transition-transform duration-300" :class="open ? 'rotate-180 opacity-100' : 'opacity-20'"></i>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 w-64 bg-white border border-slate-100 rounded-2xl shadow-2xl py-3 z-[110]"
                         x-cloak>
                         <a href="{{ route('admin.master.athletes.index') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                 <i class="fas fa-running text-xs"></i>
                             </div>
                             Master Atlet
                         </a>
                         <a href="{{ route('admin.master.officials.index') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                 <i class="fas fa-user-tie text-xs"></i>
                             </div>
                             Master Official
                         </a>
                         <a href="{{ route('admin.master.users') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                            <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                <i class="fas fa-users text-xs"></i>
                            </div>
                            User Accounts
                        </a>
                        <a href="{{ route('admin.master.roles.index') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                 <i class="fas fa-user-shield text-xs"></i>
                             </div>
                             Roles & Permissions
                         </a>
                        <a href="{{ route('admin.master.contingents.index') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                 <i class="fas fa-users text-xs"></i>
                             </div>
                             Master Kontingen
                         </a>
                        <a href="{{ route('admin.master.referees') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                            <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                <i class="fas fa-user-tie text-xs"></i>
                            </div>
                            Wasit
                        </a>
                        <a href="{{ route('admin.master.kyu-levels') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                            <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                <i class="fas fa-tags text-xs"></i>
                            </div>
                            Kyu / Dan Levels
                        </a>
                        
                        <a href="{{ route('admin.master.age-groups') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                            <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                <i class="fas fa-user-clock text-xs"></i>
                            </div>
                            Kelompok Umur
                        </a>
                        <a href="{{ route('admin.master.weight-groups') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                            <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                <i class="fas fa-weight-hanging text-xs"></i>
                            </div>
                            Kelompok Berat Badan
                        </a>
                        
                    </div>
                </div>
                @endrole

                @role('Contingent')
                <div class="relative h-full" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="flex items-center gap-2.5 px-4 h-full transition-all group border-b-2 {{ request()->routeIs('admin.master.*') ? 'border-orange-600 text-orange-600' : 'border-transparent text-slate-500 hover:text-orange-600 font-medium' }}">
                        <i class="fas fa-database text-sm opacity-50 group-hover:opacity-100"></i>
                        <span class="text-sm font-bold tracking-tight">Master Data</span>
                        <i class="fas fa-chevron-down text-[8px] transition-transform duration-300" :class="open ? 'rotate-180 opacity-100' : 'opacity-20'"></i>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 w-64 bg-white border border-slate-100 rounded-2xl shadow-2xl py-3 z-[110]"
                         x-cloak>
                         <a href="{{ route('admin.master.athletes.index') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                 <i class="fas fa-running text-xs"></i>
                             </div>
                             Master Atlet
                         </a>
                         <a href="{{ route('admin.master.officials.index') }}" class="flex items-center gap-3 px-3 py-2 text-[13px] font-bold text-slate-600 hover:bg-slate-50 hover:text-orange-600 transition-all rounded-xl mx-2">
                             <div class="rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                 <i class="fas fa-user-tie text-xs"></i>
                             </div>
                             Master Official
                         </a>
                        
                    </div>
                </div>
                @endrole

                <a href="#" class="flex items-center gap-2.5 px-4 h-full transition-all group border-b-2 border-transparent text-slate-500 hover:text-orange-600">
                    <i class="fas fa-cog text-sm opacity-50 group-hover:opacity-100"></i>
                    <span class="text-sm font-bold tracking-tight">Settings</span>
                    <i class="fas fa-chevron-down text-[8px] opacity-20"></i>
                </a>
            </nav>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-full"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-full"
         class="md:hidden bg-[#0f2b3d] border-t border-white/10 fixed inset-x-0 top-[64px] bottom-0 z-[90] flex flex-col p-6 overflow-y-auto"
         x-cloak>
        
        <div class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-4 p-5 rounded-[2rem] {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white shadow-xl shadow-orange-600/20' : 'text-slate-300 hover:bg-white/5 shadow-sm' }} transition-all">
                <i class="fas fa-th-large text-lg"></i>
                <span class="font-black uppercase tracking-wider text-xs">Dashboard</span>
            </a>

            @role('Super Admin|Admin|Pendaftaran')
            <a href="{{ route('admin.registrations.index') }}" 
               class="flex items-center gap-4 p-5 rounded-[2rem] {{ request()->routeIs('admin.registrations.*') ? 'bg-orange-600 text-white shadow-xl shadow-orange-600/20' : 'text-slate-300 hover:bg-white/5 shadow-sm' }} transition-all">
                <i class="fas fa-file-signature text-lg"></i>
                <span class="font-black uppercase tracking-wider text-xs">Data Registrasi</span>
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.match-numbers.verified', 'admin.reports.*') ? 'true' : 'false' }} }" class="space-y-2">
                <button @click="open = !open" 
                   class="w-full flex items-center justify-between p-5 rounded-[2rem] {{ request()->routeIs('admin.match-numbers.verified', 'admin.reports.*') ? 'bg-orange-600/10 text-orange-400 border border-orange-600/20' : 'text-slate-300 hover:bg-white/5 shadow-sm' }} transition-all">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-file-invoice text-lg"></i>
                        <span class="font-black uppercase tracking-wider text-xs">Laporan</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-8 space-y-2 py-2 animate-in slide-in-from-top-2 duration-300">
                    <a href="{{ route('admin.match-numbers.verified') }}" class="block p-4 rounded-2xl {{ request()->routeIs('admin.match-numbers.verified') ? 'text-orange-400 bg-white/5' : 'text-slate-400 hover:text-white' }} text-sm font-bold transition-colors">Laporan Pertandingan</a>
                    <a href="{{ route('admin.reports.registration-by-number') }}" class="block p-4 rounded-2xl {{ request()->routeIs('admin.reports.registration-by-number') ? 'text-orange-400 bg-white/5' : 'text-slate-400 hover:text-white' }} text-sm font-bold transition-colors">Laporan Per Kontingen</a>
                    <a href="{{ route('admin.reports.registration-by-name') }}" class="block p-4 rounded-2xl {{ request()->routeIs('admin.reports.registration-by-name') ? 'text-orange-400 bg-white/5' : 'text-slate-400 hover:text-white' }} text-sm font-bold transition-colors">Laporan Per Nama Peserta</a>
                    <a href="{{ request()->routeIs('admin.reports.match-class') }}" class="block p-4 rounded-2xl {{ request()->routeIs('admin.reports.match-class') ? 'text-orange-400 bg-white/5' : 'text-slate-400 hover:text-white' }} text-sm font-bold transition-colors">Laporan Nomor & Kelas</a>
                    <a href="{{ route('admin.reports.athlete-biodata') }}" class="block p-4 rounded-2xl {{ request()->routeIs('admin.reports.athlete-biodata') ? 'text-orange-400 bg-white/5' : 'text-slate-400 hover:text-white' }} text-sm font-bold transition-colors">Laporan Biodata Peserta</a>
                </div>
            </div>
            @endrole

            @role('Super Admin|Admin')
            <div x-data="{ open: false }" class="space-y-2">
                <button @click="open = !open" 
                   class="w-full flex items-center justify-between p-5 rounded-[2rem] text-slate-300 hover:bg-white/5 transition-all shadow-sm">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-database text-lg"></i>
                        <span class="font-black uppercase tracking-wider text-xs">Master Data</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-8 space-y-2 py-2 animate-in slide-in-from-top-2 duration-300">
                    <a href="{{ route('admin.master.athletes.index') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Master Atlet</a>
                    <a href="{{ route('admin.master.officials.index') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Master Official</a>
                    <a href="{{ route('admin.master.users') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">User Management</a>
                    <a href="{{ route('admin.master.roles.index') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Roles & Permissions</a>
                    <a href="{{ route('admin.master.contingents.index') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Master Kontingen</a>
                    <a href="{{ route('admin.master.referees') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Master Wasit</a>
                    <a href="{{ route('admin.master.kyu-levels') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Kyu / Dan Levels</a>
                    <a href="{{ route('admin.master.age-groups') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Kelompok Umur</a>
                    <a href="{{ route('admin.master.weight-groups') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Kelompok Berat Badan</a>
                    <a href="{{ route('admin.master.techniques') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Teknik & Jurus</a>
                    <a href="{{ route('admin.master.match-numbers') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Nomor Pertandingan</a>
                    <a href="{{ route('admin.master.rundown') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Rundown</a>
                    <a href="{{ route('admin.master.court') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Court</a>
                </div>
            </div>
            @endrole

            @role('Contingent')
            <div x-data="{ open: false }" class="space-y-2">
                <button @click="open = !open" 
                   class="w-full flex items-center justify-between p-5 rounded-[2rem] text-slate-300 hover:bg-white/5 transition-all shadow-sm">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-database text-lg"></i>
                        <span class="font-black uppercase tracking-wider text-xs">Master Data</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" class="pl-8 space-y-2 py-2 animate-in slide-in-from-top-2 duration-300">
                    <a href="{{ route('admin.master.athletes.index') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Master Atlet</a>
                    <a href="{{ route('admin.master.officials.index') }}" class="block p-4 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-colors">Master Official</a>
                </div>
            </div>
            @endrole

            <a href="#" class="flex items-center gap-4 p-5 rounded-[2rem] text-slate-300 hover:bg-white/5 transition-all shadow-sm">
                <i class="fas fa-cog text-lg"></i>
                <span class="font-black uppercase tracking-wider text-xs">Settings</span>
            </a>
        </div>

        <div class="mt-auto pt-8 border-t border-white/5">
            <div class="flex items-center gap-4 p-6 bg-white/5 rounded-[2.5rem] mb-6 shadow-inner">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-base font-black text-white truncate drop-shadow-sm">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] text-orange-200/50 uppercase tracking-[0.2em] font-black truncate">{{ auth()->user()->getRoleNames()->first() }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 bg-red-600/10 text-red-400 py-5 rounded-[2rem] font-black uppercase tracking-widest text-xs border border-red-600/10 active:scale-95 transition-all">
                    <i class="fas fa-sign-out-alt"></i> Logout System
                </button>
            </form>
        </div>
    </div>
</header>
