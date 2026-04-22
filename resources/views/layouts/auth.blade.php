<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Informasi Shorinji Kempo | Smart-Perkemi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-['Ubuntu'] antialiased bg-[#0f2b3d] overflow-x-hidden min-h-screen flex items-center justify-center p-6 relative">
    <!-- Animated background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-20">
        <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[60%] bg-orange-600 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[60%] h-[60%] bg-blue-600 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 w-full max-w-lg">
        {{ $slot }}
    </div>
</body>
</html>
