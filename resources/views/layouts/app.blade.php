<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    @include('layouts.parts.meta')

    <link rel="icon" href="{{ config('app.url') }}/favicon.png" type="image/png">

    <title>{{ config('app.name', 'Oauth2 Server') }}</title>

    <link nonce={{ $nonce }} rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script nonce={{ $nonce }} src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    @include('layouts.parts.translation')
    @stack('head')
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
