<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Laporan — Smart Perkemi' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('/css/fonts.css');
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            @page { margin: 1cm; size: A4 portrait; }
        }
    </style>
    @livewireStyles
</head>
<body>
    {{ $slot }}
    @livewireScripts
    <script>
        function printPage() {
            window.print();
        }
    </script>
</body>
</html>
