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
    <body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 font-body antialiased min-h-screen py-12 px-4 shadow-inner">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2 text-zinc-500 hover:text-rose-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="font-bold text-sm uppercase tracking-widest">Kembali</span>
                </a>
                {{--<div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-600 rounded-lg flex items-center justify-center text-white">
                        <span class="text-xl font-black italic">D</span>
                    </div>
                    <span class="text-lg font-bold tracking-tighter uppercase font-title">Perkemi <span class="text-rose-500">Cup</span> 2026</span>
                </div>--}}
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
