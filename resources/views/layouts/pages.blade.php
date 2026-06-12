<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>

    @includeif('pages.layouts.favicon')
    @stack('head')
    <meta name="nonce" content="{{ $nonce }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite(['resources/css/app.css', 'resources/js/pages.js'])

    @stack('css')
</head>

<body class="bg-gray-25 dark:bg-gray-800 font-sans min-h-screen transition-colors duration-300">
    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    @yield('footer')
    @includeIf('pages.layouts.privacy')

    @stack('js')
    @stack('modals')


    <script nonce="{{ $nonce }}">
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            let theme = 'light';

            if (savedTheme === 'dark' || (savedTheme === 'auto' && systemPrefersDark)) {
                theme = 'dark';
            }

            document.documentElement.classList.remove('light', 'dark');
            document.documentElement.classList.add(theme);
        })();
    </script>
</body>

</html>
