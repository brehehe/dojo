<a class="mob-nav-item {{ request()->routeIs('contingent.dashboard') ? 'active' : '' }}"
   href="{{ route('contingent.dashboard') }}" wire:navigate>
    <i class="fa-solid fa-gauge-high"></i>
    <span>Dashboard</span>
</a>

<a class="mob-nav-item {{ request()->routeIs('contingent.schedule') ? 'active' : '' }}"
   href="{{ route('contingent.schedule') }}" wire:navigate>
    <i class="fa-solid fa-calendar-days"></i>
    <span>Jadwal</span>
</a>

<a class="mob-nav-item {{ request()->routeIs('contingent.results') ? 'active' : '' }}"
   href="{{ route('contingent.results') }}" wire:navigate>
    <i class="fa-solid fa-trophy"></i>
    <span>Hasil</span>
</a>

<a class="mob-nav-item {{ request()->routeIs('contingent.standings') ? 'active' : '' }}"
   href="{{ route('contingent.standings') }}" wire:navigate>
    <i class="fa-solid fa-ranking-star"></i>
    <span>Klasemen</span>
</a>

<a class="mob-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
   href="{{ route('admin.profile') }}" wire:navigate>
    <i class="fa-solid fa-user-circle"></i>
    <span>Profil</span>
</a>

<a class="mob-nav-item danger" href="{{ route('logout') }}">
    <i class="fa-solid fa-right-from-bracket"></i>
    <span>Keluar</span>
</a>
