{{-- Home Dashboard Topbar --}}
<header class="hd-topbar backdrop-blur-xl border-b hd-border sticky top-0 z-40 flex-shrink-0">
    <div class="px-4 sm:px-6 flex items-center justify-between h-16">

        {{-- Left: Mobile toggle + Badge --}}
        <div class="flex items-center gap-3">
            <button @click="mobileSidebarOpen = true"
                    class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl hd-text-2 hd-hover transition-all">
                <i class="fas fa-bars text-sm"></i>
            </button>

            <div class="hidden sm:flex items-center gap-1.5 bg-indigo-500/10 border border-indigo-500/20 rounded-xl px-3 py-1.5"
                 :class="darkMode ? 'bg-indigo-500/10 border-indigo-500/20' : 'bg-indigo-50 border-indigo-200'">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse"
                      :class="darkMode ? 'bg-indigo-400' : 'bg-indigo-500'"></span>
                <span class="text-xs font-bold uppercase tracking-wider"
                      :class="darkMode ? 'text-indigo-300' : 'text-indigo-600'">Live Dashboard</span>
            </div>
        </div>

        {{-- Center: Search --}}
        <div class="hidden lg:flex flex-1 max-w-sm mx-6">
            <div class="relative w-full group">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center hd-text-3 group-focus-within:text-indigo-400 transition-colors pointer-events-none">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text"
                       placeholder="Cari menu, data, laporan..."
                       class="hd-input w-full pl-9 pr-4 py-2 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all">
            </div>
        </div>

        {{-- Right: Actions + Toggle + User --}}
        <div class="flex items-center gap-2">

            {{-- Live stats pill --}}
            <div class="hidden md:flex items-center gap-3 rounded-xl px-4 py-2 hd-card border"
                 style="box-shadow: var(--hd-shadow);">
                <div class="text-center">
                    <div class="text-xs font-black hd-text-1 leading-none">{{ \App\Models\Athlete::count() }}</div>
                    <div class="text-[9px] hd-text-3 font-bold uppercase tracking-wider mt-0.5">Atlet</div>
                </div>
                <div class="w-px h-6 hd-divider border-l"></div>
                <div class="text-center">
                    <div class="text-xs font-black hd-text-1 leading-none">{{ \App\Models\Contingent::count() }}</div>
                    <div class="text-[9px] hd-text-3 font-bold uppercase tracking-wider mt-0.5">Kontingen</div>
                </div>
                @php $pending = \App\Models\Registration::where('status','pending')->count(); @endphp
                @if($pending > 0)
                <div class="w-px h-6 hd-divider border-l"></div>
                <div class="text-center">
                    <div class="text-xs font-black text-amber-500 leading-none">{{ $pending }}</div>
                    <div class="text-[9px] hd-text-3 font-bold uppercase tracking-wider mt-0.5">Pending</div>
                </div>
                @endif
            </div>

            {{-- ─── THEME TOGGLE ──────────────────────────────── --}}
            <button @click="toggleTheme()"
                    class="hd-theme-toggle relative w-16 h-8 rounded-full transition-all flex items-center px-1 flex-shrink-0"
                    :class="darkMode ? 'bg-indigo-600/30 border-indigo-500/40' : 'bg-amber-100 border-amber-300'"
                    title="Toggle Dark/Light Mode">
                {{-- Track --}}
                <span class="absolute inset-0 rounded-full transition-all"
                      :class="darkMode ? 'bg-indigo-600/20' : 'bg-amber-400/20'"></span>
                {{-- Thumb --}}
                <span class="relative z-10 w-6 h-6 rounded-full shadow-md flex items-center justify-center transition-all duration-300 transform"
                      :class="[
                          darkMode ? 'translate-x-8 bg-indigo-500 text-white' : 'translate-x-0 bg-amber-400 text-white'
                      ]">
                    <i class="fas text-[10px]" :class="darkMode ? 'fa-moon' : 'fa-sun'"></i>
                </span>
            </button>

            {{-- Notification --}}
            <button class="relative w-9 h-9 flex items-center justify-center rounded-xl hd-text-2 hd-hover transition-all">
                <i class="fas fa-bell text-sm"></i>
                @if($pending > 0)
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-400 rounded-full"></span>
                @endif
            </button>

            <div class="w-px h-6 border-l hd-border"></div>

            {{-- User Dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open"
                        class="flex items-center gap-2.5 p-1.5 rounded-xl hd-hover transition-all border border-transparent group"
                        :class="open ? 'border-[var(--hd-border)]' : ''">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-xs font-bold hd-text-1 leading-none mb-0.5">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] hd-text-3 font-semibold uppercase tracking-wide leading-none">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-[10px] hd-text-3 hidden md:block transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute right-0 mt-2 w-64 hd-card border rounded-2xl hd-shadow py-2 z-50 overflow-hidden">

                    {{-- User Header --}}
                    <div class="px-4 py-3 border-b hd-border flex items-center gap-3">
                        <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center text-white font-black shadow-lg flex-shrink-0">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold hd-text-1 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs hd-text-3 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="w-2 h-2 bg-emerald-400 rounded-full flex-shrink-0"></div>
                    </div>

                    {{-- Links --}}
                    <div class="py-2 px-2">
                        {{-- Current theme indicator --}}
                        <div class="flex items-center justify-between px-3 py-2 mb-1 rounded-xl hd-card2 border">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs"
                                     :class="darkMode ? 'bg-indigo-500/15 text-indigo-400' : 'bg-amber-100 text-amber-600'">
                                    <i class="fas" :class="darkMode ? 'fa-moon' : 'fa-sun'"></i>
                                </div>
                                <span class="text-xs font-semibold hd-text-2" x-text="darkMode ? 'Mode Gelap' : 'Mode Terang'"></span>
                            </div>
                            <button @click="toggleTheme()"
                                    class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">
                                Ganti
                            </button>
                        </div>

                        <a href="{{ route('admin.profile') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hd-text-2 hd-hover transition-all">
                            <div class="w-7 h-7 rounded-lg bg-slate-500/10 flex items-center justify-center hd-text-3 text-xs">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            Profile Settings
                        </a>
                    </div>

                    {{-- Logout --}}
                    <div class="px-2 pb-2 pt-1 border-t hd-border">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-rose-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all font-semibold">
                                <div class="w-7 h-7 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-500 text-xs">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
