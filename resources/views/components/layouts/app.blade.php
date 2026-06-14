<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Perkemi Cup 2026' }}</title>
        <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
            .font-title { font-family: 'Outfit', sans-serif; }
            .font-body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
    </head>
    <body class="bg-zinc-950 text-zinc-100 font-body antialiased">
        {{ $slot }}
    </body>
</html>
