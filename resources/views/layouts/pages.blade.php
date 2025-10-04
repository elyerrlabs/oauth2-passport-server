<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.parts.meta')

    @yield('title', config('app.name', 'OAuth2 Server'))

    @include('layouts.parts.favicon')

    @vite(['resources/js/pages.js'])
    
    @stack('head')
    @stack('css')
</head>

<body>

    @yield('header')

    @include('layouts.parts.alerts')

    @yield('content')

    @yield('footer')
    <x-privacy />
    @stack('js')
    @stack('modals')

</body>

</html>
