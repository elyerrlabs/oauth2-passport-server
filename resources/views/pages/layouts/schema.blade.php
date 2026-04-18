@extends('layouts.pages')

@push('head')
    <title>{{ config('app.name', 'Oauth2 Passport Server') }}</title>

    <meta name="description"
        content="Custom page template. You can modify header, content, footer, styles, and scripts. 
        Supports Blade, Vue (global), Axios ($server), TailwindCSS, and full Laravel helpers (auth, config, routes, etc). 
        Ideal for landing pages, marketing pages, documentation, or dynamic content.">

    <meta name="author" content="{{ config('app.name') }}">
@endpush

@push('css')
    <style nonce="{{ $nonce }}">
        /*
        You can write custom CSS here.
        TailwindCSS is available.
        Dark mode is supported via 'dark' classes.
        */
    </style>
@endpush

@section('header')
    {{--
        You can:
        - Use the default header
        - Replace it with custom HTML/Blade
        - Or remove it completely
    --}}
    @include('pages.layouts.header')
@endsection

@section('content')
    <!--
        MAIN CONTENT AREA

        You can use:

        ✅ Blade syntax
        ✅ Laravel helpers & facades:
           - auth()->user()
           - config('app.name')
           - route('...')
           - request()
           - session()
           - csrf_field(), @csrf
           - and any backend-accessible data

        ✅ PHP logic inside Blade
        ✅ HTML
        ✅ Vue (via global Vue)
        ✅ Axios via $server
        ✅ TailwindCSS utilities

        Example:

        @if(auth()->check())
            <p>Welcome {{ auth()->user()->name }}</p>
        @endif

        <div id="app" v-cloak>
            @{{ message }}
        </div>
    -->
@endsection

@section('footer')
    {{--
        Same logic as header:
        - Use default footer
        - Customize
        - Or remove
    --}}
    @include('pages.layouts.footer')
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", () => {

            /*
            AVAILABLE GLOBALS:

            Vue        -> Vue 3 (window.Vue)
            $server    -> Axios instance
            $notify    -> Notification helper
            __         -> Translation helper

            You can combine Laravel (Blade) + Vue + Axios freely.

            Example:

            const { createApp, ref } = Vue;

            createApp({
                setup() {
                    const message = ref("Hello from Vue 🚀");
                    return { message };
                }
            }).mount('#app');

            */

        });
    </script>
@endpush