<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('layouts.parts.meta')

    <link rel="icon" href="{{ config('app.url') }}/favicon.png" type="image/png">

    <title>{{ config('app.name', 'Oauth2 Server') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/tailwind.css') }}">
    <link nonce={{ $nonce }} rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script nonce={{ $nonce }} src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    @inertiaHead
    @stack('head')
    @stack('css')
</head>

<body>

    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
    @stack('js')
    @stack('modals')
</body>

</html>
