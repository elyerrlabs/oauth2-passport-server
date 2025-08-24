<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.parts.meta')

    @yield('title', config('app.name', 'OAuth2 Server'))

    @include('layouts.parts.favicon')

    <link rel="stylesheet" href="{{ mix('/css/pages.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/tailwind.css') }}">

    <!-- Notyf-->
    <link nonce={{ $nonce }} rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script nonce={{ $nonce }} src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    @stack('head')
    @stack('css')
</head>

<body>

    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    @include('layouts.parts.footer')

    <script src="{{ mix('/js/pages.js') }}"></script>
    @stack('js')
    @stack('modals')
</body>

</html>
