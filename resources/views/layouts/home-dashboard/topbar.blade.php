{{-- Home Dashboard Topbar (Luwes Style) --}}
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-100 dark:border-slate-800 sticky top-0 z-40 flex-shrink-0 shadow-sm transition-colors duration-300">
    <div class="px-4 sm:px-6 flex items-center justify-between h-16">

        {{-- Left: Mobile toggle + Badge --}}
        <div class="flex items-center gap-3">
            <button @click="mobileSidebarOpen = true"
                    class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800/50 transition-colors">
                <i class="fas fa-bars"></i>
            </button>

            <div class="hidden sm:flex items-center gap-2 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-full px-4 py-1.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 tracking-wide">Live Dashboard</span>
            </div>
        </div>

        {{-- Center: Search (Elegant style) --}}
        <div class="hidden lg:flex flex-1 max-w-md mx-8">
            <div class="relative w-full group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors pointer-events-none">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text"
                       placeholder="Cari menu atau laporan..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 text-slate-800 dark:text-slate-200 placeholder-slate-400 rounded-full text-sm outline-none border border-transparent focus:bg-white dark:focus:bg-slate-900 focus:border-blue-200 dark:focus:border-blue-500/30 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm">
            </div>
        </div>

        {{-- Right: Actions + Toggle + User --}}
        <div class="flex items-center gap-3">

            {{-- Live stats pill (Minimalist) --}}
            <div class="hidden md:flex items-center gap-4 bg-white dark:bg-slate-900 rounded-full px-5 py-1.5 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="text-center">
                    <div class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-none">{{ \App\Models\Athlete::count() }}</div>
                    <div class="text-[9px] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Atlet</div>
                </div>
                <div class="w-px h-6 bg-slate-200 dark:bg-slate-700"></div>
                <div class="text-center">
                    <div class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-none">{{ \App\Models\Contingent::count() }}</div>
                    <div class="text-[9px] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Kontingen</div>
                </div>
                @php $pending = \App\Models\Registration::where('status','pending')->count(); @endphp
                @if($pending > 0)
                <div class="w-px h-6 bg-slate-200 dark:bg-slate-700"></div>
                <div class="text-center">
                    <div class="text-sm font-bold text-amber-500 leading-none">{{ $pending }}</div>
                    <div class="text-[9px] text-amber-500/70 font-semibold uppercase tracking-wider mt-0.5">Pending</div>
                </div>
                @endif
            </div>

            {{-- THEME TOGGLE (Soft pill) --}}
            <button @click="toggleTheme()"
                    class="relative w-14 h-7 rounded-full transition-colors duration-300 flex items-center px-1 flex-shrink-0 shadow-inner"
                    :class="darkMode ? 'bg-slate-700 border border-slate-600' : 'bg-slate-200 border border-slate-300'"
                    title="Toggle Dark/Light Mode">
                <span class="relative z-10 w-5 h-5 rounded-full shadow-sm flex items-center justify-center transition-transform duration-300 transform bg-white dark:bg-slate-800"
                      :class="[ darkMode ? 'translate-x-7 text-indigo-400' : 'translate-x-0 text-amber-500' ]">
                    <i class="fas text-[10px]" :class="darkMode ? 'fa-moon' : 'fa-sun'"></i>
                </span>
            </button>

            {{-- Notification --}}
            <button class="relative w-10 h-10 flex items-center justify-center rounded-full text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800/50 transition-colors">
                <i class="fas fa-bell"></i>
                @if($pending > 0)
                <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                @endif
            </button>

            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>

            {{-- User Dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open"
                        class="flex items-center gap-3 p-1.5 rounded-full hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group border border-transparent"
                        :class="open ? 'border-slate-200 dark:border-slate-700' : ''">
                    <div class="w-8 h-8 bg-blue-50 dark:bg-blue-500/10 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 text-xs font-bold group-hover:scale-105 transition-transform">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div class="hidden md:block text-left mr-2">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-none mb-0.5">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium leading-none">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 hidden md:block transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown Menu (Soft Dropdown) --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                     class="absolute right-0 mt-3 w-64 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-xl py-2 z-50 overflow-hidden">

                    {{-- User Header --}}
                    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-4 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold flex-shrink-0 text-lg">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    {{-- Links --}}
                    <div class="py-2 px-3 space-y-1">
                        <a href="{{ route('admin.profile') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            Pengaturan Profil
                        </a>
                    </div>

                    {{-- Logout --}}
                    <div class="px-3 pb-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-500">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
