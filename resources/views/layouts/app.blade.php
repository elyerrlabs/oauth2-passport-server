<!DOCTYPE html class="light">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    @includeif('pages.layouts.favicon')
    @stack('head')
    <meta name="nonce" content="{{ $nonce }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.parts.translation')
    @includeIf('pages.layouts.fonts')    
    <x-inertia::head />
    @stack('css')
</head>

<body class="bg-white dark:bg-gray-800 font-sans min-h-screen transition-colors duration-200">

    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    @yield('footer')

    @includeIf('pages.layouts.privacy')
    @stack('js')
    @stack('modals')
</body>

</html>
