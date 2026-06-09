<!DOCTYPE html>
<html lang="id">
@php
  $user = Auth::user();
  $isMobileRole = $user && $user->hasAnyRole(['Contingent', 'Perwasitan','Wasit']);
  $isReferee    = $user && $user->hasRole(['Perwasitan','Wasit']);
@endphp

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Admin Dashboard' }} | Smart Perkemi</title>
  <link rel="shortcut icon" href="/assets/favicon-CK1QI2Xs.ico">

  <!-- Fonts & Icons -->
  <link
    href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    /* ══════════════════════════════════════════════════════
   TOKENS
══════════════════════════════════════════════════════ */
    :root {
      --red: #c0392b;
      --red-deep: #96281b;
      --red-glow: #e74c3c;
      --gold: #d4a843;
      --gold-lt: #f0c060;
      --ink: #0f0d0b;
      --paper: #f7f4ef;
      --paper2: #ede9e1;
      --smoke: #b5afa6;
      --sidebar-w: 260px;
      --header-h: 64px;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--paper);
      color: var(--ink);
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
      margin: 0;
    }

    /* ══════════════════════════════════════════════════════
   OVERLAY (mobile)
══════════════════════════════════════════════════════ */
    #overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 90;
      backdrop-filter: blur(2px);
      transition: opacity .25s;
    }

    #overlay.show {
      display: block;
    }

    /* ══════════════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════════════ */
    aside {
      width: var(--sidebar-w);
      background: var(--ink);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 100;
      overflow: hidden;
      transition: transform .28s cubic-bezier(.4, 0, .2, 1);
    }

    aside::before {
      content: '';
      position: absolute;
      top: -60px;
      right: -60px;
      width: 220px;
      height: 220px;
      background: radial-gradient(circle, rgba(192, 57, 43, .35) 0%, transparent 70%);
      pointer-events: none;
    }

    .brand {
      padding: 28px 24px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, .06);
      position: relative;
      display: flex;
      flex-direction: column;
    }

    .brand-top {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .brand-emblem {
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--red), var(--red-deep));
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Cinzel', serif;
      color: var(--gold);
      font-size: 18px;
      font-weight: 700;
      box-shadow: 0 4px 20px rgba(192, 57, 43, .5);
      flex-shrink: 0;
    }

    .brand-text h1 {
      font-family: 'Cinzel', serif;
      font-size: 11.5px;
      font-weight: 700;
      color: #fff;
      letter-spacing: .04em;
      line-height: 1.4;
      text-transform: uppercase;
    }

    .brand-text p {
      font-size: 9.5px;
      color: var(--smoke);
      margin-top: 3px;
      letter-spacing: .12em;
      text-transform: uppercase;
    }

    .sidebar-close {
      display: none;
      margin-left: auto;
      background: none;
      border: none;
      color: rgba(255, 255, 255, .4);
      font-size: 18px;
      cursor: pointer;
      padding: 4px;
    }

    nav.premium-nav {
      flex: 1;
      padding: 16px 0;
      overflow-y: auto;
      /* Firefox */
      scrollbar-width: thin;
      scrollbar-color: rgba(192, 57, 43, .4) transparent;
    }

    /* Chrome / Safari / Edge */
    nav.premium-nav::-webkit-scrollbar {
      width: 4px;
    }

    nav.premium-nav::-webkit-scrollbar-track {
      background: transparent;
    }

    nav.premium-nav::-webkit-scrollbar-thumb {
      background: rgba(192, 57, 43, .35);
      border-radius: 10px;
      transition: background .2s;
    }

    nav.premium-nav::-webkit-scrollbar-thumb:hover {
      background: rgba(192, 57, 43, .7);
    }

    .nav-section {
      padding: 12px 24px 6px;
      font-size: 9px;
      color: rgba(255, 255, 255, .3);
      letter-spacing: .18em;
      text-transform: uppercase;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 24px;
      color: rgba(255, 255, 255, .55);
      font-size: 13.5px;
      font-weight: 400;
      cursor: pointer;
      transition: all .2s;
      border-left: 3px solid transparent;
      text-decoration: none;
    }

    .nav-item i {
      width: 18px;
      font-size: 13px;
    }

    .nav-item:hover {
      color: #fff;
      background: rgba(255, 255, 255, .05);
    }

    .nav-item.active {
      color: var(--gold-lt);
      background: rgba(192, 57, 43, .18);
      border-left-color: var(--red-glow);
      font-weight: 500;
    }

    .nav-badge {
      margin-left: auto;
      background: var(--red);
      color: #fff;
      font-size: 10px;
      padding: 2px 7px;
      border-radius: 30px;
      font-weight: 600;
    }

    .sidebar-foot {
      padding: 18px 24px;
      border-top: 1px solid rgba(255, 255, 255, .06);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .avatar-premium {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--red), var(--gold));
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      flex-shrink: 0;
    }

    .sidebar-foot .info h4 {
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      margin: 0;
    }

    .sidebar-foot .info p {
      color: var(--smoke);
      font-size: 11px;
      margin: 0;
    }


    /* ══════════════════════════════════════════════════════
   GLOBAL TABLE-SECTION & TOOLBAR (shared by all master pages)
══════════════════════════════════════════════════════ */
    .table-section-prem {
      background: #fff;
      border-radius: 16px;
      border: 1px solid var(--paper2);
      overflow: hidden;
      margin-bottom: 24px;
    }

    .table-toolbar-prem {
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid var(--paper2);
      flex-wrap: wrap;
    }

    .table-toolbar-prem h3 {
      font-family: 'Cinzel', serif;
      font-size: 13px;
      font-weight: 700;
      color: var(--ink);
      flex: 1;
      min-width: 100px;
      margin: 0;
    }

    /* ── Filter Tabs ── */
    .filter-tabs-prem {
      display: flex;
      gap: 3px;
      background: var(--paper);
      border-radius: 9px;
      padding: 3px;
      flex-shrink: 0;
    }

    .filter-tab-prem {
      padding: 5px 13px;
      border-radius: 7px;
      border: none;
      background: none;
      font-size: 11.5px;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      color: var(--smoke);
      font-weight: 500;
      transition: all .15s;
      white-space: nowrap;
    }

    .filter-tab-prem.active {
      background: var(--ink);
      color: #fff;
      font-weight: 600;
    }

    /* ── Search ── */
    .search-field-prem {
      display: flex;
      align-items: center;
      gap: 7px;
      background: var(--paper);
      border: 1px solid var(--paper2);
      border-radius: 9px;
      padding: 7px 12px;
      flex: 1;
      max-width: 280px;
      min-width: 160px;
    }

    .search-field-prem i { color: var(--smoke); font-size: 11px; }

    .search-field-prem input {
      background: none;
      border: none;
      outline: none;
      flex: 1;
      font-size: 12.5px;
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
    }

    .search-field-prem input::placeholder { color: var(--smoke); }

    /* ── Per Page Select ── */
    .perpage-select-prem {
      padding: 7px 10px;
      border: 1px solid var(--paper2);
      border-radius: 9px;
      font-size: 12px;
      background: #fff;
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      outline: none;
      flex-shrink: 0;
    }

    /* ══════════════════════════════════════════════════════
   MAIN
══════════════════════════════════════════════════════ */
    main.premium-main {
      margin-left: var(--sidebar-w);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      min-width: 0; /* fixes flex item blowout */
      overflow-x: hidden;
      transition: margin-left .28s cubic-bezier(.4, 0, .2, 1);
    }

    /* ══════════════════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════════════════ */
    header.premium-header {
      background: rgba(247, 244, 239, .96);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--paper2);
      padding: 0 28px;
      height: var(--header-h);
      display: flex;
      align-items: center;
      gap: 14px;
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .hamburger {
      display: none;
      background: none;
      border: none;
      font-size: 18px;
      color: var(--ink);
      cursor: pointer;
      padding: 6px;
      border-radius: 8px;
      transition: background .15s;
    }

    .hamburger:hover {
      background: var(--paper2);
    }

    .page-title-premium {
      font-family: 'Cinzel', serif;
      font-size: 14px;
      font-weight: 700;
      color: var(--ink);
      flex: 1;
    }

    .page-title-premium span {
      color: var(--red);
    }

    .search-wrap-premium {
      display: flex;
      align-items: center;
      background: var(--paper2);
      border: 1px solid rgba(0, 0, 0, .08);
      border-radius: 10px;
      padding: 8px 14px;
      gap: 8px;
    }

    .search-wrap-premium i {
      color: var(--smoke);
      font-size: 12px;
    }

    .search-wrap-premium input {
      background: none;
      border: none;
      outline: none;
      font-size: 13px;
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
      width: 180px;
    }

    .top-btn {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      border: 1px solid rgba(0, 0, 0, .08);
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #666;
      font-size: 14px;
      position: relative;
      transition: all .2s;
      flex-shrink: 0;
    }

    .top-btn:hover {
      background: var(--paper2);
    }

    /* ── Profile Dropdown ── */
    .profile-wrap {
      position: relative;
      flex-shrink: 0;
    }

    .profile-trigger {
      display: flex;
      align-items: center;
      gap: 9px;
      cursor: pointer;
      padding: 4px 10px 4px 4px;
      border-radius: 12px;
      border: 1px solid transparent;
      transition: all .2s;
      user-select: none;
    }

    .profile-trigger:hover {
      background: var(--paper2);
      border-color: rgba(0, 0, 0, .07);
    }

    .profile-trigger-info {
      display: flex;
      flex-direction: column;
    }

    .profile-trigger-info span:first-child {
      font-size: 12.5px;
      font-weight: 600;
      color: var(--ink);
      line-height: 1.2;
    }

    .profile-trigger-info span:last-child {
      font-size: 10.5px;
      color: var(--smoke);
    }

    .profile-trigger-chevron {
      font-size: 10px;
      color: var(--smoke);
      transition: transform .2s;
      margin-left: 2px;
    }

    .profile-wrap.open .profile-trigger-chevron {
      transform: rotate(180deg);
    }

    .profile-dropdown {
      position: absolute;
      top: calc(100% + 10px);
      right: 0;
      width: 220px;
      background: #fff;
      border: 1px solid var(--paper2);
      border-radius: 14px;
      box-shadow: 0 12px 40px rgba(0, 0, 0, .12);
      overflow: hidden;
      z-index: 200;
    }

    .profile-wrap.open .profile-dropdown {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(0);
    }

    .profile-dropdown-head {
      padding: 14px 16px;
      border-bottom: 1px solid var(--paper2);
      display: flex;
      align-items: center;
      gap: 11px;
    }

    .profile-dropdown-head .dd-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      flex-shrink: 0;
      background: linear-gradient(135deg, var(--red), var(--gold));
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 14px;
      font-weight: 700;
    }

    .profile-dropdown-head .dd-info h4 {
      font-size: 13px;
      font-weight: 600;
      color: var(--ink);
      margin: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 130px;
    }

    .profile-dropdown-head .dd-info p {
      font-size: 10.5px;
      color: var(--smoke);
      margin: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 130px;
    }

    .profile-dropdown-menu {
      padding: 6px 0;
    }

    .dd-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      font-size: 13px;
      color: #555;
      text-decoration: none;
      cursor: pointer;
      transition: background .12s;
      width: 100%;
      border: none;
      background: none;
      text-align: left;
      font-family: 'DM Sans', sans-serif;
    }

    .dd-item i {
      width: 16px;
      font-size: 12px;
      color: #888;
    }

    .dd-item:hover {
      background: var(--paper);
      color: var(--ink);
    }

    .dd-item:hover i {
      color: var(--red);
    }

    .dd-divider {
      height: 1px;
      background: var(--paper2);
      margin: 4px 0;
    }

    .dd-item.logout {
      color: var(--red);
    }

    .dd-item.logout i {
      color: var(--red);
    }

    .dd-item.logout:hover {
      background: rgba(192, 57, 43, .07);
    }

    @media (max-width: 1024px) {
      aside {
        transform: translateX(-100%);
      }

      aside.open {
        transform: translateX(0);
      }

      main.premium-main {
        margin-left: 0;
        overflow-x: hidden;
        max-width: 100vw;
      }

      .hamburger {
        display: flex;
      }

      .sidebar-close {
        display: block;
      }

      .search-wrap-premium {
        display: none;
      }
    }

    /* ══════════════════════════════════════════════════════
   MOBILE ROLE — Bottom Nav (Contingent & Referee)
══════════════════════════════════════════════════════ */

    /* Only apply mobile-specific layout overrides on actual mobile screens */
    @media (max-width: 1024px) {
      .mob-body-role main.premium-main {
        margin-left: 0;
        padding-bottom: calc(66px + env(safe-area-inset-bottom, 0px));
      }

      .mob-body-role header.premium-header .hamburger,
      .mob-body-role header.premium-header .search-wrap-premium,
      .mob-body-role header.premium-header .top-btn {
        display: none !important;
      }

      .mob-body-role .page-title-premium {
        font-size: 13px;
      }
    }

    /* ── TOPBAR role badge (mobile roles) ── */
    .mob-role-badge {
      padding: 4px 11px;
      border-radius: 20px;
      font-size: 9.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      flex-shrink: 0;
    }
    .mob-role-badge.contingent { background: rgba(52,152,219,.15); color: #5dade2; }
    .mob-role-badge.referee    { background: rgba(192,57,43,.15);  color: #e74c3c; }

    /* ── BOTTOM NAV ── */
    .mob-bottomnav {
      display: none;
      position: fixed;
      bottom: 0; left: 0; right: 0;
      height: 66px;
      padding-bottom: env(safe-area-inset-bottom, 0px);
      background: var(--ink);
      border-top: 1px solid rgba(255,255,255,.06);
      align-items: stretch;
      z-index: 200;
    }

    @media (max-width: 1024px) {
      .mob-body-role .mob-bottomnav { display: flex; }
    }

    .mob-nav-btn {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 4px;
      text-decoration: none;
      color: rgba(255,255,255,.38);
      font-size: 9.5px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .07em;
      transition: color .15s;
      padding: 8px 4px 6px;
      cursor: pointer;
      border: none;
      background: none;
      font-family: 'DM Sans', sans-serif;
      position: relative;
    }

    .mob-nav-btn i { font-size: 18px; line-height: 1; }
    .mob-nav-btn:hover { color: rgba(255,255,255,.65); }

    .mob-nav-btn.active {
      color: var(--gold-lt);
    }
    .mob-nav-btn.active::before {
      content: '';
      position: absolute;
      top: 0; left: 50%;
      transform: translateX(-50%);
      width: 28px; height: 2px;
      background: var(--red-glow);
      border-radius: 0 0 4px 4px;
    }
    .mob-nav-btn.danger { color: rgba(231,76,60,.45); }
    .mob-nav-btn.danger:hover { color: var(--red-glow); }
  </style>
  @stack('styles')
</head>

<body class="{{ $isMobileRole ? 'mob-body-role' : '' }}">

  <div id="overlay" onclick="closeSidebar()"></div>

  {{-- ── Sidebar: always rendered, role-visibility handled inside ── --}}
  <aside id="sidebar">
    <div class="brand">
      <div class="brand-top">
        <div class="brand-emblem">拳</div>
        <div class="brand-text">
          <h1>Smart Perkemi</h1>
          <p>Admin Panel · 2026</p>
        </div>
        <button class="sidebar-close" onclick="closeSidebar()"><i class="fa-solid fa-xmark"></i></button>
      </div>
    </div>

    @include('layouts.premium-sidebar-nav')

    {{--<div class="sidebar-foot">
      <div class="avatar-premium">{{ substr(Auth::user()->name, 0, 2) }}</div>
      <div class="info">
        <h4>{{ Auth::user()->name }}</h4>
        <p>Administrator</p>
      </div>
    </div>--}}
  </aside>

  <main class="premium-main">
    <header class="premium-header">
      {{-- Hamburger: only for admin sidebar --}}
      @if(!$isMobileRole)
      <button class="hamburger" onclick="openSidebar()">
        <i class="fa-solid fa-bars"></i>
      </button>
      @endif

      <span class="page-title-premium">{{ $title ?? 'Dashboard' }}
        @if(!$isMobileRole)<span>/ Overview</span>@endif
      </span>

      {{-- Mobile Role Badge (visible only for non-admin) --}}
      @if($isMobileRole)
        @if($isReferee)
          <span class="mob-role-badge referee"><i class="fa-solid fa-gavel" style="margin-right:4px;"></i>Wasit</span>
        @else
          <span class="mob-role-badge contingent"><i class="fa-solid fa-flag" style="margin-right:4px;"></i>Kontingen</span>
        @endif
      @endif

      @if(!$isMobileRole)
      <div class="search-wrap-premium">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Cari peserta, kontingen…">
      </div>
      <button class="top-btn"><i class="fa-solid fa-bell"></i></button>
      @endif

      <!-- Profile Dropdown -->
      <div class="profile-wrap" x-data="{ open: false }" @click.outside="open = false">
        <div class="profile-trigger" @click="open = !open" :class="{ 'open': open }">
          <div class="avatar-premium" style="width:34px;height:34px;font-size:13px;border-radius:9px;flex-shrink:0;">
            {{ substr(Auth::user()->name, 0, 2) }}
          </div>
          <div class="profile-trigger-info" style="display:none;" id="profileTriggerInfo">
            <span>{{ Str::limit(Auth::user()->name, 16) }}</span>
            <span>{{ $isMobileRole ? ($isReferee ? 'Wasit Juri' : 'Kontingen') : 'Administrator' }}</span>
          </div>
          <i class="fa-solid fa-chevron-down profile-trigger-chevron" id="profileChevron" style="display:none;"
            :class="{ 'rotate-180': open }" style="transition: transform .2s;"></i>

        </div>
        <div class="profile-dropdown" x-show="open" x-transition:enter="transition ease-out duration-150"
          x-transition:enter-start="opacity-0 translate-y-[-6px]" x-transition:enter-end="opacity-100 translate-y-0"
          x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
          x-transition:leave-end="opacity-0 translate-y-[-6px]" style="display:none;">
          <div class="profile-dropdown-head">
            <div class="dd-avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
            <div class="dd-info">
              <h4>{{ Auth::user()->name }}</h4>
              <p>{{ Auth::user()->email }}</p>
            </div>
          </div>
          <div class="profile-dropdown-menu">
            <a href="/admin/profile" class="dd-item">
              <i class="fa-solid fa-user"></i> Profil Saya
            </a>
            <div class="dd-divider"></div>
            <a href="{{ route('logout') }}" class="dd-item logout">
              <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </a>
          </div>
        </div>
      </div>
    </header>

    {{ $slot }}
  </main>

  {{-- ── Mobile Bottom Nav (Contingent & Referee only) ── --}}
  @if($isMobileRole)
  <nav class="mob-bottomnav">
    @if($isReferee)
      {{-- Referee Nav --}}
      <a class="mob-nav-btn {{ request()->routeIs('admin.referee.scoring') ? 'active' : '' }}"
         href="{{ route('admin.referee.scoring') }}" wire:navigate>
        <i class="fa-solid fa-gavel"></i>
        <span>Scoring</span>
      </a>
      <button type="button" class="mob-nav-btn" onclick="openSidebar()">
        <i class="fa-solid fa-bars"></i>
        <span>Menu</span>
      </button>
    @else
      {{-- Contingent Nav --}}
      <a class="mob-nav-btn {{ request()->routeIs('contingent.dashboard') ? 'active' : '' }}"
         href="{{ route('contingent.dashboard') }}" wire:navigate>
        <i class="fa-solid fa-gauge-high"></i>
        <span>Dashboard</span>
      </a>
      <a class="mob-nav-btn {{ request()->routeIs('contingent.schedule') ? 'active' : '' }}"
         href="{{ route('contingent.schedule') }}" wire:navigate>
        <i class="fa-solid fa-calendar-days"></i>
        <span>Jadwal</span>
      </a>
      <a class="mob-nav-btn {{ request()->routeIs('contingent.results') ? 'active' : '' }}"
         href="{{ route('contingent.results') }}" wire:navigate>
        <i class="fa-solid fa-trophy"></i>
        <span>Hasil</span>
      </a>
      <a class="mob-nav-btn {{ request()->routeIs('contingent.standings') ? 'active' : '' }}"
         href="{{ route('contingent.standings') }}" wire:navigate>
        <i class="fa-solid fa-ranking-star"></i>
        <span>Klasemen</span>
      </a>
      <button type="button" class="mob-nav-btn" onclick="openSidebar()">
        <i class="fa-solid fa-bars"></i>
        <span>Menu</span>
      </button>
    @endif
  </nav>
  @endif

  <script>
    function openSidebar() {
      document.getElementById('sidebar').classList.add('open');
      document.getElementById('overlay').classList.add('show');
    }
    function closeSidebar() {
      document.getElementById('sidebar').classList.remove('open');
      document.getElementById('overlay').classList.remove('show');
    }
    // Show extra info on wider screens
    function updateProfileTrigger() {
      const info = document.getElementById('profileTriggerInfo');
      const chevron = document.getElementById('profileChevron');
      const show = window.innerWidth >= 640;
      if (info) info.style.display = show ? 'flex' : 'none';
      if (chevron) chevron.style.display = show ? 'block' : 'none';
    }
    window.addEventListener('resize', () => {
      if (window.innerWidth > 1024) closeSidebar();
      updateProfileTrigger();
    });
    document.addEventListener('DOMContentLoaded', updateProfileTrigger);
  </script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    window.addEventListener('swal', function (event) {
      const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
      Swal.fire({
        title: data.title,
        text: data.text,
        icon: data.icon,
        timer: data.timer || 3000,
        showConfirmButton: data.showConfirmButton || false,
      });
    });
  </script>
  @livewireScripts
  @stack('scripts')
</body>

</html>