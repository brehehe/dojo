<!-- Topbar for Sidebar Layout -->
<header class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-[40]" x-data="{ mobileMenuOpen: false }">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Mobile Menu Button & Search -->
            <div class="flex items-center gap-4 flex-1">
                <!-- Mobile Menu Button (triggers sidebar in mobile view if implemented, or just shows a dropdown) -->
                <button @click="$dispatch('open-mobile-sidebar')" class="md:hidden w-10 h-10 flex items-center justify-center text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Search -->
                <div class="hidden sm:flex max-w-md w-full">
                    <div class="relative w-full group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                            <i class="fas fa-search text-[15px]"></i>
                        </span>
                        <input type="text" placeholder="Search anything..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-[15px] font-medium text-slate-700 placeholder:text-slate-400 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none">
                    </div>
                </div>
            </div>

            <!-- Utility & Profile Actions -->
            <div class="flex items-center gap-2 md:gap-6">
                <!-- Icons Utility -->
                <div class="hidden sm:flex items-center gap-1 md:gap-3">
                    {{-- Announcer Stop Button --}}
                    <button onclick="window.stopAnnouncer()" 
                            class="flex items-center gap-2 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl text-[12px] font-black uppercase tracking-widest transition-all active:scale-95 group"
                            title="Hentikan Suara Panggilan">
                        <i class="fas fa-volume-mute text-sm group-hover:animate-pulse"></i>
                        <span class="hidden lg:inline">Stop Suara</span>
                    </button>

                    <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all relative group">
                        <i class="far fa-envelope text-lg"></i>
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all relative group">
                        <i class="far fa-bell text-lg"></i>
                        <span class="absolute top-2.5 right-2.5 w-4 h-4 bg-orange-500 border-2 border-white rounded-full text-[15px] font-black flex items-center justify-center text-white shadow-sm">3</span>
                    </button>
                </div>

                <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>

                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ userDropdownOpen: false }" @click.away="userDropdownOpen = false">
                    <button @click="userDropdownOpen = !userDropdownOpen" class="flex items-center gap-3 group focus:outline-none p-1 rounded-2xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
                        <div class="hidden md:flex flex-col items-end mr-1">
                            <span class="text-[15px] font-extrabold text-slate-800 group-hover:text-orange-600 transition-colors leading-none mb-1">{{ auth()->user()->name }}</span>
                            <span class="text-[12px] text-slate-400 uppercase tracking-widest font-black leading-none">{{ auth()->user()->getRoleNames()->first() }}</span>
                        </div>
                        <div class="relative">
                            <div class="w-9 h-9 md:w-11 md:h-11 bg-orange-500 rounded-xl flex items-center justify-center text-white font-black shadow-md group-hover:scale-105 transition-transform overflow-hidden">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full shadow-sm"></div>
                        </div>
                        <i class="fas fa-chevron-down text-[15px] text-slate-400 group-hover:text-slate-600 transition-all hidden md:block" :class="userDropdownOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Profile Dropdown Menu -->
                    <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 -translate-y-2" class="absolute right-0 mt-3 w-64 bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] py-4 z-[110] border border-slate-100 overflow-hidden" x-cloak>
                        <div class="px-6 py-4 mb-2 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-600 rounded-2xl flex items-center justify-center text-white font-black shadow-lg">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[15px] font-black text-slate-800 truncate leading-none mb-1">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-[13px] text-slate-500 font-bold truncate leading-none">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                        </div>
                        <div class="px-3 pb-2 pt-2">
                            <a href="#" class="flex items-center gap-3 px-4 py-3 text-[15px] font-bold text-slate-700 hover:bg-slate-50 hover:text-orange-600 rounded-xl transition-all">
                                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                Profile Settings
                            </a>
                        </div>
                        <div class="px-4 mt-2 border-t border-slate-50 pt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-3 bg-red-50 hover:bg-red-100 text-red-600 py-3.5 rounded-2xl transition-all font-black text-[15px] uppercase tracking-widest group">
                                    <i class="fas fa-sign-out-alt opacity-50 group-hover:translate-x-1 transition-transform"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
