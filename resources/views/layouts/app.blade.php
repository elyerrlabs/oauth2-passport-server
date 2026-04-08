<!DOCTYPE html class="dark">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    @yield('title')

    @include('layouts.parts.favicon')
    @stack('head')

    <link nonce={{ $nonce }} rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script nonce={{ $nonce }} src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    @include('layouts.parts.translation')
    @inertiaHead
    @stack('css')
</head>

<body class="bg-white dark:bg-gray-800 font-sans min-h-screen transition-colors duration-200">

    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    @yield('footer')

    <x-privacy />
    @stack('js')
    @stack('modals')
</body>

</html>
