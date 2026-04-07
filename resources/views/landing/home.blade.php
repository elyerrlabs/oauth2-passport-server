@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', [
        'title' => config('app.name', 'OAuth2 Passport Server'),
    ])
@endsection

@section('head')
    {!! config('seo.landing-page', '') !!}
@endsection

@section('header')
    @include('layouts.parts.header')
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white dark:bg-gray-900">
        <div
            class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 opacity-50">
        </div>

        <div class="relative container mx-auto px-6 py-24 md:py-32">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 rounded-full px-4 py-1.5 mb-6">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ __('Enterprise Ready') }}</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">
                    {{ __('Modern') }}<br>
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        {{ __('Authorization Server') }}
                    </span>
                </h1>

                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    {{ __('Complete OAuth2 & OpenID Connect solution with microservice support, modular architecture, and enterprise-grade security.') }}
                </p>

                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#features"
                        class="group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold px-8 py-3.5 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        {{ __('Explore Features') }}
                        <i class="mdi mdi-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#links"
                        class="border-2 border-gray-300 dark:border-gray-600 hover:border-blue-600 dark:hover:border-blue-500 text-gray-700 dark:text-gray-300 font-semibold px-8 py-3.5 rounded-xl transition-all duration-200 hover:shadow-lg">
                        {{ __('Get Started') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-800/50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span
                    class="text-sm font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ __('Core Features') }}</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mt-2 mb-4">
                    {{ __('Everything you need') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ __('Built for developers, trusted by enterprises') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                @php
                    $features = [
                        [
                            'icon' => 'mdi-shield-lock',
                            'title' => 'OAuth2 & OIDC',
                            'desc' => 'Full support for OAuth2 and OpenID Connect protocols with all grant types.',
                        ],
                        [
                            'icon' => 'mdi-microservices',
                            'title' => 'Microservices Ready',
                            'desc' => 'Built for modern microservices architecture with granular scopes.',
                        ],
                        [
                            'icon' => 'mdi-view-dashboard',
                            'title' => 'Admin Dashboard',
                            'desc' => 'Complete management for users, roles, apps, and subscriptions.',
                        ],
                        [
                            'icon' => 'mdi-credit-card-multiple',
                            'title' => 'Payment Integration',
                            'desc' => 'Seamless Stripe integration for subscription management.',
                        ],
                        [
                            'icon' => 'mdi-puzzle-outline',
                            'title' => 'Modular System',
                            'desc' => 'Extend functionality with Elymod modules without conflicts.',
                        ],
                        [
                            'icon' => 'mdi-speedometer',
                            'title' => 'High Performance',
                            'desc' => 'Horizon queue support and CSP for maximum scalability.',
                        ],
                    ];
                @endphp
                @foreach ($features as $feature)
                    <div
                        class="group bg-white dark:bg-gray-900 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-transparent">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="mdi {{ $feature['icon'] }} text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __($feature['title']) }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ __($feature['desc']) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Elymod Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="lg:w-1/2">
                        <div
                            class="inline-flex items-center gap-2 bg-purple-100 dark:bg-purple-900/30 rounded-full px-4 py-1.5 mb-6">
                            <i class="mdi mdi-puzzle text-purple-600 dark:text-purple-400 text-sm"></i>
                            <span
                                class="text-sm font-medium text-purple-700 dark:text-purple-300">{{ __('Modular Architecture') }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ __('Elymod Framework') }}
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            {{ __('A mini-framework that extends functionality through independent modules. Each module has its own dependencies - no version conflicts, no monolithic headaches.') }}
                        </p>
                        <div class="space-y-3">
                            @foreach (['Independent modules with isolated dependencies', 'No version collisions', 'Easy installation via Composer', 'Laravel-like syntax'] as $point)
                                <div class="flex items-center gap-3">
                                    <i class="mdi mdi-check-circle text-green-500 text-lg"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __($point) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="bg-gray-900 dark:bg-gray-800 rounded-2xl p-6 shadow-xl">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="text-gray-400 text-sm ml-2">terminal</span>
                            </div>
                            <pre class="text-sm"><code class="text-green-400">composer require elyerr/elymod
                            </code>
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Laravel Runtime -->
    <section class="py-20 bg-gray-50 dark:bg-gray-800/50">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <div class="flex flex-col lg:flex-row-reverse items-center gap-12">
                    <div class="lg:w-1/2">
                        <div
                            class="inline-flex items-center gap-2 bg-red-100 dark:bg-red-900/30 rounded-full px-4 py-1.5 mb-6">
                            <i class="fab fa-laravel text-red-600 dark:text-red-400 text-sm"></i>
                            <span
                                class="text-sm font-medium text-red-700 dark:text-red-300">{{ __('Laravel Compatible') }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ __('Laravel Runtime') }}
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            {{ __('Familiar Laravel experience without framework conflicts. An abstraction layer that bridges Laravel and Elymod while keeping dependencies safe.') }}
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                <i class="mdi mdi-bridge text-blue-500 text-2xl mb-2 block"></i>
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ __('Abstraction Bridge') }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('No core modifications') }}
                                </p>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                <i class="mdi mdi-shield-check text-green-500 text-2xl mb-2 block"></i>
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ __('Dependency Safety') }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('No breaking changes') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Resources -->
    <section id="links" class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ __('Start building today') }}
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">
                {{ __('Open source. Production ready. Community driven.') }}
            </p>

            <div class="flex flex-wrap justify-center gap-3">
                <a href="https://github.com/elyerrlabs" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-xl hover:bg-gray-800 dark:hover:bg-gray-700 transition-all duration-200 hover:-translate-y-0.5">
                    <i class="mdi mdi-github text-xl"></i>
                    <span class="font-medium">GitHub</span>
                </a>
                <a href="https://packagist.org/packages/elyerr" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-xl hover:bg-gray-800 dark:hover:bg-gray-700 transition-all duration-200 hover:-translate-y-0.5">
                    <i class="mdi mdi-package-variant-closed text-xl"></i>
                    <span class="font-medium">Packagist</span>
                </a>
                <a href="https://hub.docker.com/u/elyerr" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-xl hover:bg-gray-800 dark:hover:bg-gray-700 transition-all duration-200 hover:-translate-y-0.5">
                    <i class="mdi mdi-docker text-xl"></i>
                    <span class="font-medium">Docker Hub</span>
                </a>
                <a href="https://www.youtube.com/@elyerrlabs" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 hover:-translate-y-0.5">
                    <i class="mdi mdi-youtube text-xl"></i>
                    <span class="font-medium">YouTube</span>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800/50">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-3xl mx-auto">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ __('Ready to secure your applications?') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    {{ __('Join developers using OAuth2 Passport Server for their authentication needs.') }}
                </p>
                <a href="https://github.com/elyerrlabs" target="_blank"
                    class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold hover:gap-3 transition-all">
                    {{ __('View on GitHub') }}
                    <i class="mdi mdi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="mdi mdi-passport text-white text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">OAuth2 Passport Server</span>
                </div>
                <div class="flex gap-6">
                    <a href="#features"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">{{ __('Features') }}</a>
                    <a href="#links"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">{{ __('Resources') }}</a>
                    <a href="https://github.com/elyerrlabs" target="_blank"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">{{ __('GitHub') }}</a>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-500">
                    © {{ date('Y') }} Elyerr. {{ __('AGPv3 License') }}
                </div>
            </div>
        </div>
    </footer>
@endsection
