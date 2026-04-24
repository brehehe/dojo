<!-- Bottom Navigation Bar (Mobile App Style with Floating Center Button) -->
@php
    $centerIcon = 'fa-th-large';
    $centerRoute = '#';
    $isCenterActive = false;

    if (auth()->user()->hasRole('Contingent')) {
        $centerIcon = 'fa-running';
        $centerRoute = route('admin.master.athletes.index');
        $isCenterActive = request()->routeIs('admin.master.athletes.*');
    } elseif (auth()->user()->hasAnyRole(['Perwasitan', 'Wasit'])) {
        $centerIcon = 'fa-tablet-alt';
        $centerRoute = route('admin.referee.scoring');
        $isCenterActive = request()->routeIs('admin.referee.*');
    }
@endphp

<div class="fixed bottom-0 left-0 right-0 z-[100]" style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="relative mx-3 mb-3">

        {{-- White pill bar --}}
        <nav class="bg-white rounded-[2rem] shadow-[0_-2px_20px_rgba(0,0,0,0.08),0_4px_30px_rgba(0,0,0,0.07)] border border-slate-100 flex items-center h-[62px] px-4">

            @role('Contingent')
                {{-- Left 2 items --}}
                <a href="{{ route('contingent.dashboard') }}"
                   class="flex-1 flex flex-col items-center justify-center gap-1 py-1 transition-all group {{ request()->routeIs('contingent.dashboard') ? 'text-slate-900' : 'text-slate-400 hover:text-slate-600' }}">
                    <i class="fas fa-home text-[20px] transition-transform {{ request()->routeIs('contingent.dashboard') ? 'scale-110' : '' }}"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Beranda</span>
                </a>

                <a href="{{ route('admin.master.officials.index') }}"
                   class="flex-1 flex flex-col items-center justify-center gap-1 py-1 transition-all group {{ request()->routeIs('admin.master.officials.*') ? 'text-slate-900' : 'text-slate-400 hover:text-slate-600' }}">
                    <i class="fas fa-user-tie text-[20px] transition-transform {{ request()->routeIs('admin.master.officials.*') ? 'scale-110' : '' }}"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Official</span>
                </a>

                {{-- Center spacer for floating button --}}
                <div class="w-[68px] shrink-0"></div>

                {{-- Right 2 items --}}
                <a href="{{ route('contingent.schedule') }}"
                   class="flex-1 flex flex-col items-center justify-center gap-1 py-1 transition-all group {{ request()->routeIs('contingent.schedule') ? 'text-orange-500' : 'text-slate-400 hover:text-slate-600' }}">
                    <i class="fas fa-calendar-alt text-[20px] transition-transform {{ request()->routeIs('contingent.schedule') ? 'scale-110' : '' }}"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Jadwal</span>
                </a>

                <a href="{{ route('contingent.results') }}"
                   class="flex-1 flex flex-col items-center justify-center gap-1 py-1 transition-all group {{ request()->routeIs('contingent.results', 'contingent.standings') ? 'text-orange-500' : 'text-slate-400 hover:text-slate-600' }}">
                    <i class="fas fa-trophy text-[20px] transition-transform {{ request()->routeIs('contingent.results', 'contingent.standings') ? 'scale-110' : '' }}"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Hasil</span>
                </a>
            @endrole

            @hasanyrole('Perwasitan|Wasit')
                <a href="#"
                   class="flex-1 flex flex-col items-center justify-center gap-1 py-1 transition-all text-slate-400 hover:text-slate-600">
                    <i class="fas fa-clipboard-list text-[20px]"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Jadwal</span>
                </a>

                {{-- Center spacer for floating button --}}
                <div class="w-[68px] shrink-0"></div>

                <a href="#"
                   class="flex-1 flex flex-col items-center justify-center gap-1 py-1 transition-all text-slate-400 hover:text-slate-600">
                    <i class="fas fa-user-circle text-[20px]"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Profil</span>
                </a>
            @endhasanyrole
        </nav>

        {{-- Floating Center Button (sits above bar center) --}}
        <div class="absolute left-1/2 -translate-x-1/2 bottom-[10px] z-20">
            {{-- White ring (notch illusion) --}}
            <div class="w-[76px] h-[76px] rounded-full bg-slate-50 flex items-center justify-center">
                <a href="{{ $centerRoute }}"
                   class="w-[62px] h-[62px] rounded-full flex items-center justify-center shadow-[0_6px_24px_rgba(0,0,0,0.22)] transition-all active:scale-95 hover:scale-105 {{ $isCenterActive ? 'bg-orange-500' : 'bg-slate-900' }}">
                    <i class="fas {{ $centerIcon }} text-white text-[22px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>
