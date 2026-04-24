<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Perkemi') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Select2 & jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Ubuntu'] antialiased bg-slate-50 overflow-x-hidden">
    @hasanyrole('Wasit|Contingent')
        <!-- Header / Mobile Layout for Perwasitan & Contingent -->
        <div class="min-h-screen flex flex-col pb-20" x-data="{ userDropdownOpen: false }">
            @include('layouts.admin.mobile-header')

            <!-- Main Content Area -->
            <main class="flex-1 max-w-full mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4 md:pt-6 pb-2 md:pb-3">
                {{ $slot }}
            </main>

            @include('layouts.admin.bottombar')
        </div>
    @else
        <!-- Sidebar Layout for Admin and other management roles -->
        <div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ mobileMenuOpen: false }" @open-mobile-sidebar.window="mobileMenuOpen = true">
            <!-- Sidebar -->
            @include('layouts.admin.sidebar')

            <!-- Mobile Sidebar Backdrop & Menu (Optional, we can just use the existing sidebar conditionally hidden) -->
            <div x-show="mobileMenuOpen" class="fixed inset-0 z-50 flex md:hidden" x-cloak>
                <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex w-full max-w-xs flex-1 flex-col bg-slate-900 pt-5 pb-4">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="mobileMenuOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <i class="fas fa-times text-white text-xl"></i>
                        </button>
                    </div>
                    <!-- Reuse the sidebar content for mobile by including it again but without the hidden class, or we can just make the main sidebar responsive. Let's make main sidebar responsive via alpine if we need. Since we included sidebar above, let's keep it simple: the main sidebar is hidden on mobile, so we include it here without hidden md:flex -->
                    <div class="flex-1 overflow-y-auto">
                        @include('layouts.admin.sidebar')
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                @include('layouts.admin.topbar')

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 md:p-6 custom-scrollbar">
                    {{ $slot }}
                </main>

                <div class="mt-auto">
                    @include('layouts.admin.footer')
                </div>
            </div>
        </div>
    @endhasanyrole
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('swal', function (event) {
            const data = event.detail[0];
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                timer: data.timer || 3000,
                showConfirmButton: data.showConfirmButton || false,
            });
        });
    </script>
</body>

</html>