<!-- Bottom Navigation Bar (Mobile App Style) -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-[100] shadow-[0_-5px_20px_-5px_rgba(0,0,0,0.05)]" style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="max-w-md mx-auto sm:max-w-full px-2">
        <div class="flex items-center justify-around h-16 sm:h-20">
            @role('Contingent')
                <a href="{{ route('contingent.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ request()->routeIs('contingent.dashboard') ? 'text-orange-600' : 'text-slate-400 hover:text-orange-500' }}">
                    <i class="fas fa-home text-[22px] mb-0.5 transition-transform {{ request()->routeIs('contingent.dashboard') ? 'scale-110 drop-shadow-sm' : '' }}"></i>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest">Beranda</span>
                </a>
                
                <a href="{{ route('admin.master.athletes.index') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ request()->routeIs('admin.master.athletes.*') ? 'text-orange-600' : 'text-slate-400 hover:text-orange-500' }}">
                    <i class="fas fa-running text-[22px] mb-0.5 transition-transform {{ request()->routeIs('admin.master.athletes.*') ? 'scale-110 drop-shadow-sm' : '' }}"></i>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest">Atlet</span>
                </a>

                <a href="{{ route('admin.master.officials.index') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ request()->routeIs('admin.master.officials.*') ? 'text-orange-600' : 'text-slate-400 hover:text-orange-500' }}">
                    <i class="fas fa-user-tie text-[22px] mb-0.5 transition-transform {{ request()->routeIs('admin.master.officials.*') ? 'scale-110 drop-shadow-sm' : '' }}"></i>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest">Official</span>
                </a>
            @endrole

            @hasanyrole('Perwasitan|Wasit')
                <a href="{{ route('admin.referee.scoring') }}" class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors {{ request()->routeIs('admin.referee.*') ? 'text-orange-600' : 'text-slate-400 hover:text-orange-500' }}">
                    <i class="fas fa-tablet-alt text-[22px] mb-0.5 transition-transform {{ request()->routeIs('admin.referee.*') ? 'scale-110 drop-shadow-sm' : '' }}"></i>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest">Penilaian</span>
                </a>
                <!-- Optional: Add more Wasit specific menus here if needed in the future -->
            @endhasanyrole
        </div>
    </div>
</div>
