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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Select2 & jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Inter'] antialiased bg-slate-50 overflow-x-hidden">
    <div class="min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false, userDropdownOpen: false }">

        @include('layouts.admin.header')

        <!-- Main Content Area -->
        <main class="flex-1 max-w-full mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4 md:pt-6 pb-2 md:pb-3">
            {{ $slot }}
        </main>

        @include('layouts.admin.footer')
    </div>
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