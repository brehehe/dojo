<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrasi - Perkemi Championship 2026</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,600,800|plus-jakarta-sans:400,500,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
            .font-title { font-family: 'Outfit', sans-serif; }
            .font-body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-zinc-50 text-zinc-900 font-body antialiased min-h-screen py-12 px-4 shadow-inner">
        <div class="max-w-full mx-auto">
            <div class="mb-12 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2 text-zinc-500 hover:text-rose-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="font-bold text-sm uppercase tracking-widest">Kembali</span>
                </a>
            </div>

            <main>
                <livewire:registration-form />
            </main>

            <footer class="mt-20 text-center text-zinc-400 text-xs uppercase tracking-widest font-bold">
                Perkemi Cup Committee — Registration System v1.0
            </footer>
        </div>
    </body>
</html>
