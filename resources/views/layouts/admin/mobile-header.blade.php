<!-- Mobile Header (Slimmed down for App-like feel) -->
<header class="sticky top-0 z-[100]">
    <div class="bg-gradient-to-r from-orange-700 via-orange-600 to-orange-500 text-white shadow-md relative">
        <!-- Decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-full mx-auto px-4 relative z-10">
            <div class="flex items-center justify-between h-14 md:h-16">
                <!-- Branding Section -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="w-6 h-6 md:w-8 md:h-8 rounded-lg object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[14px] md:text-lg font-black tracking-tighter uppercase leading-none text-white drop-shadow-sm">Smart Perkemi</span>
                    </div>
                </div>

                <!-- Utility & Profile Actions -->
                <div class="flex items-center gap-2">
                    <!-- Notifications -->
                    <button class="w-9 h-9 flex items-center justify-center text-white/90 hover:text-white hover:bg-white/10 rounded-xl transition-all relative group">
                        <i class="far fa-bell text-[15px]"></i>
                        <span class="absolute top-2 right-2 w-3.5 h-3.5 bg-orange-400 border-2 border-orange-600 rounded-full text-[9px] font-black flex items-center justify-center shadow-sm">3</span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ userDropdownOpen: false }" @click.away="userDropdownOpen = false">
                        <button @click="userDropdownOpen = !userDropdownOpen" class="flex items-center group focus:outline-none p-1 rounded-2xl hover:bg-white/5 transition-all">
                            <div class="relative">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 backdrop-blur-md border border-white/20 rounded-xl flex items-center justify-center text-white text-sm font-black shadow-lg group-hover:scale-105 transition-transform overflow-hidden">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-orange-600 rounded-full shadow-sm"></div>
                            </div>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                            class="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] py-3 z-[110] border border-slate-100 overflow-hidden"
                            x-cloak>
                            <div class="px-5 py-3 mb-2 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-[14px] font-black text-slate-800 truncate leading-none mb-1">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest truncate leading-none">
                                        {{ auth()->user()->getRoleNames()->first() }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-2 pb-2">
                                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-[14px] font-bold text-slate-900 hover:bg-slate-50 hover:text-orange-600 rounded-xl transition-all">
                                    <div class="w-7 h-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    Profile Settings
                                </a>
                            </div>
                            <div class="px-3 mt-1 border-t border-slate-50 pt-3">
                                <a href="{{ route('logout') }}" class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 py-2.5 rounded-xl transition-all font-black text-[13px] uppercase tracking-widest group">
                                    <i class="fas fa-sign-out-alt opacity-50 group-hover:translate-x-1 transition-transform"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
