<a class="mob-nav-item {{ request()->routeIs('referee.scoring-dashboard') ? 'active' : '' }}"
   href="{{ route('referee.scoring-dashboard') }}" wire:navigate>
    <i class="fa-solid fa-gavel"></i>
    <span>Scoring</span>
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
