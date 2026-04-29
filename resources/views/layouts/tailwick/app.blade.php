<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Dashboard' }} | {{ config('app.name', 'Smart Perkemi') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Smart Perkemi Admin Dashboard" name="description">
    <meta content="Themesdesign" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/favicon-CK1QI2Xs.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (function () {
            const html = document.documentElement;
            const storageKey = "__TAILWICK_CONFIG__";
            const savedConfig = sessionStorage.getItem(storageKey);

            // Default config
            const defaultConfig = {
                dir: "ltr",
                theme: "light",
                sidenav: {
                    color: "light",
                    size: "default",
                },
            };

            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light";
            }

            const htmlConfig = {
                dir: html.getAttribute("dir") || defaultConfig.dir,
                theme: html.getAttribute("data-theme") === 'system'
                    ? getSystemTheme()
                    : html.getAttribute("data-theme") || (defaultConfig.theme === 'system' ? getSystemTheme() : defaultConfig.theme),
                sidenav: {
                    color: html.getAttribute("data-sidenav-color") || defaultConfig.sidenav.color,
                    size: html.getAttribute("data-sidenav-size") || defaultConfig.sidenav.size,
                },
            };

            window.defaultConfig = structuredClone(htmlConfig);
            let config = savedConfig ? JSON.parse(savedConfig) : htmlConfig;
            window.config = config;

            html.setAttribute("dir", config.dir);
            html.setAttribute("data-theme", config.theme);
            html.setAttribute("data-sidenav-color", config.sidenav.color);

            if (config.sidenav.size) {
                let size = config.sidenav.size;
                if (window.innerWidth <= 1140) {
                    size = "offcanvas";
                }
                html.setAttribute("data-sidenav-size", size);
            }
        })();
    </script>

    <!-- Styles and Scripts -->
    <script type="module" crossorigin src="/assets/index-DsDs3XAz.js"></script>
    <link rel="modulepreload" crossorigin href="/assets/app-BxTRRtUp.js">
    <link rel="modulepreload" crossorigin href="/assets/apexcharts.esm-DPbJ6jlt.js">
    <link rel="modulepreload" crossorigin href="/assets/flatpickr-DxeCcIwz.js">
    <link rel="stylesheet" crossorigin href="/assets/app-0ZOPNGSF.css">

    @livewireStyles
    @stack('styles')
</head>

<body>

    <div class="wrapper">

        @include('layouts.tailwick.sidebar')

        <!-- Start Page Content here -->
        <div class="page-content">

            @include('layouts.tailwick.header')

            <div>
                {{ $slot }}
            </div>

            @include('layouts.tailwick.footer')

        </div>
        <!-- End Page Content -->

    </div>

    <!-- App JS -->
    <script type="module" src="/assets/app-BxTRRtUp.js"></script>

    @livewireScripts
    @stack('scripts')

</body>

</html>