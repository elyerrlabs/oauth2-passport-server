@extends('layouts.pages')

@push('head')
    <title>{{ config('app.name', 'Oauth2 Passport Server') }}</title>

    <meta name="description"
        content="Custom dynamic page template. Fully customizable layout with support for Blade, Vue, Axios, jQuery, TailwindCSS, and Laravel helpers. Ideal for landing pages, admin panels, documentation, and dynamic content rendering.">

    <meta name="author" content="{{ config('app.name') }}">
@endpush

@push('css')
    <style nonce="{{ $nonce }}">
        /*
        ─────────────────────────────────────────────
        🎨 CUSTOM STYLES
        ─────────────────────────────────────────────

        ✔ TailwindCSS is available
        ✔ Dark mode supported via 'dark' class
        ✔ You can safely write scoped or global styles

        Example:

        .custom-card {
            @apply bg-white dark:bg-gray-800 rounded-xl shadow p-4;
        }
        */
    </style>
@endpush

@section('header')
    {{--
        ─────────────────────────────────────────────
        🧩 HEADER SECTION
        ─────────────────────────────────────────────

        Options:
        ✔ Use default header
        ✔ Replace with custom Blade/HTML
        ✔ Remove completely
    --}}
    @include('pages.layouts.header')
@endsection

@section('content')
    <!--
    ─────────────────────────────────────────────
    🧠 MAIN CONTENT AREA
    ─────────────────────────────────────────────

    You can use:

    ✅ Blade syntax
    ✅ Laravel helpers:
       - auth()->user()
       - config()
       - route()
       - request()
       - session()
       - @csrf

    ✅ PHP logic
    ✅ HTML
    ✅ Vue 3 (global instance)
    ✅ Axios ($server)
    ✅ jQuery ($)
    ✅ TailwindCSS

    ------------------------------------------------

    🔥 EXAMPLES
    ------------------------------------------------

    Blade:

    @if(auth()->check())
        <p>Welcome {{ auth()->user()->name }}</p>
    @endif

    ------------------------------------------------

    Vue:

    <div id="app" v-cloak>
        @{{ message }}
    </div>

    ------------------------------------------------

    jQuery:

    <button id="clickMe">Click me</button>

    <script>
        $('#clickMe').on('click', function () {
            alert('Hello from jQuery');
        });
    </script>

    ------------------------------------------------
    -->
@endsection

@section('footer')
    {{--
        ─────────────────────────────────────────────
        🔻 FOOTER SECTION
        ─────────────────────────────────────────────

        Same behavior as header:
        ✔ Default
        ✔ Custom
        ✔ Optional
    --}}
    @include('pages.layouts.footer')
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", () => {

            /*
            ─────────────────────────────────────────────
            ⚙️ AVAILABLE GLOBALS
            ─────────────────────────────────────────────

            Vue        → Vue 3 (window.Vue)
            $server    → Axios instance
            $notify    → Notifications helper
            __         → Translations helper
            $ / jQuery → jQuery (if loaded via Mix/Webpack)

            ------------------------------------------------

            🔥 VUE EXAMPLE
            ------------------------------------------------

            const { createApp, ref } = Vue;

            createApp({
                setup() {
                    const message = ref("Hello from Vue 🚀");
                    return { message };
                }
            }).mount('#app');

            ------------------------------------------------

            🔥 jQuery EXAMPLE
            ------------------------------------------------

            if (window.$) {
                $('#clickMe').on('click', function () {
                    console.log('jQuery is working ✅');
                });
            }

            ------------------------------------------------
            */

        });
    </script>
@endpush