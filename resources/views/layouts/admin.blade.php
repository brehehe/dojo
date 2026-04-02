<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Admin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</head>
<body class="font-['Inter'] antialiased bg-slate-50 overflow-x-hidden">
    <div class="min-h-screen flex flex-col lg:flex-row" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar Container (Mobile Overlay) -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] lg:hidden transition-opacity duration-300" 
             x-show="sidebarOpen" 
             x-transition:enter="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="opacity-100" 
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             x-cloak>
        </div>

        <!-- Sidebar -->
        <aside class="w-72 bg-[#0f2b3d] text-white flex-shrink-0 fixed inset-y-0 left-0 z-[110] lg:relative lg:flex flex-col shadow-2xl transition-transform duration-300 transform"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               x-cloak>
            
            <div class="p-8 border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold tracking-tighter uppercase leading-none">Smart</span>
                        <span class="text-xs text-orange-400 font-bold tracking-[0.2em] uppercase">Perkemi</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white/50 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 overflow-y-auto p-6 space-y-2 sidebar-scroll">
                <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] px-4 mb-4">Main Menu</p>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 p-4 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fas fa-chart-pie w-5 text-center"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>

                @can('view registrations')
                <a href="{{ route('admin.contingents.index') }}" 
                   class="flex items-center gap-3 p-4 rounded-2xl transition-all {{ request()->routeIs('admin.contingents.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fas fa-clipboard-list w-5 text-center"></i>
                    <span class="font-semibold">Pendaftaran</span>
                </a>
                @endcan

                @can('manage master data')
                <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] px-4 mt-8 mb-4">Master Data</p>
                <a href="#" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span class="font-semibold">Users</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                    <i class="fas fa-tags w-5 text-center"></i>
                    <span class="font-semibold">Kategori</span>
                </a>
                @endcan
            </nav>
            
            <div class="p-6 border-t border-white/10">
                <div class="flex items-center gap-3 p-4 bg-white/5 rounded-2xl mb-4">
                    <div class="w-10 h-10 bg-orange-600/20 rounded-full flex items-center justify-center text-orange-400 font-black">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-sm font-bold truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-white/40 uppercase tracking-widest truncate">{{ auth()->user()->getRoleNames()->first() }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-600/10 hover:bg-red-600/20 text-red-400 py-3 rounded-xl border border-red-600/10 transition-colors font-bold text-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header (Mobile Toggle) -->
            <header class="bg-white border-b border-slate-200 lg:hidden px-6 py-4 flex items-center justify-between sticky top-0 z-[90]">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="font-black uppercase tracking-tight text-slate-800">Smart-Perkemi</span>
                </div>
                <button @click="sidebarOpen = true" class="text-slate-600 hover:text-orange-600 p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-10 scroll-smooth">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
