<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('layouts.parts.meta')

    <link rel="icon" href="{{ config('app.url') }}/favicon.png" type="image/png">

    <title>{{ config('app.name', 'Oauth2 Server') }}</title>

    @vite(['resources/js/app.js', 'resources/scss/app.scss'])
    <link nonce={{ $nonce }} rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script nonce={{ $nonce }} src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    @include('layouts.parts.translation')
    @inertiaHead
    @stack('head')
    @stack('css')
</head>

<body>

    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    <x-privacy />
    <script nonce="{{ $nonce }}">
        window.translation = @json(setLanguage()->getData())
        console.log(window.translation);
    </script>
    @stack('js')
    @stack('modals')
</body>

</html>
