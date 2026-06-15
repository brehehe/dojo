<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TV Monitor Cerdas | Smart-Perkemi</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        body { margin: 0; padding: 0; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-900 m-0 p-0">
    <div class="relative w-full min-h-screen">
        {{ $slot }}
    </div>

    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
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
