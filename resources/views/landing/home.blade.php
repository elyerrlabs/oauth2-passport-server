@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', [
        'title' => config('app.name', 'OAuth2 Passport Server - Modern Authorization Solution'),
    ])
@endsection

@push('css')
    <link nonce="{{ $nonce }}"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style nonce="{{ $nonce }}">
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            color: white;
            font-size: 24px;
        }
    </style>
@endpush

@section('header')
    @include('layouts.parts.header')
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="gradient-bg text-white py-16 md:py-24 dark:bg-linear-to-br dark:from-gray-900 dark:to-gray-800">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ __('Modern Authorization Server') }}</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90 dark:opacity-80">
                {{ __('An OAuth2 & OpenID Connect server with microservice support, modular architecture, and enterprise-ready features.') }}
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#features"
                    class="bg-white text-purple-700 dark:bg-gray-800 dark:text-purple-300 font-bold py-3 px-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-300">
                    {{ __('Explore Features') }}
                </a>
                <a href="#links"
                    class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-full hover:bg-white hover:text-purple-700 dark:hover:bg-gray-800 dark:hover:text-purple-300 transition duration-300">
                    {{ __('Get Started') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('Powerful Features') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    {{ __('OAuth2 Passport Server is a comprehensive authorization solution with OpenID Connect support designed for modern applications.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 card-hover dark:hover:bg-gray-700 transition-colors">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('OAuth2 & OpenID Connect') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('Complete authorization server with full support for OAuth2 and OpenID Connect protocols for secure authentication and authorization.') }}
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 card-hover dark:hover:bg-gray-700 transition-colors">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('Microservices Ready') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('Easy connectivity between microservices with granular control over each section using scopes for users.') }}
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 card-hover dark:hover:bg-gray-700 transition-colors">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('Complete Management System') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('Manage groups, services, roles, users, OAuth2 applications, partners, transactions, subscriptions, and plans.') }}
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 card-hover dark:hover:bg-gray-700 transition-colors">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('Payment Integration') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('Seamless integration with Stripe and offline payments for handling subscriptions and transactions.') }}
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 card-hover dark:hover:bg-gray-700 transition-colors">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('Horizon Queue Integration') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('Integrated with Horizon for managing queues, configuration management system, and CSP support for enhanced security.') }}
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 card-hover dark:hover:bg-gray-700 transition-colors">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('Extensible Framework') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('Comes with its own mini-framework for creating modules, allowing you to extend functionality through modules or OpenID Connect.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Elymod Section -->
    <section id="elymod" class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 mb-10 lg:mb-0">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        {{ __('Elymod: Modular Framework') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ __('Elymod is a mini-framework developed to extend OAuth2 Passport Server through independent modules. It maintains the same familiarity as a Laravel framework while solving dependency conflicts in modular systems.') }}
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-purple-600 dark:text-purple-400 mt-1 mr-3"></i>
                            <span
                                class="text-gray-700 dark:text-gray-300">{{ __('Each module is completely independent with its own dependencies') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-purple-600 dark:text-purple-400 mt-1 mr-3"></i>
                            <span
                                class="text-gray-700 dark:text-gray-300">{{ __('Avoids version collision between modules or with the parent system') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-purple-600 dark:text-purple-400 mt-1 mr-3"></i>
                            <span
                                class="text-gray-700 dark:text-gray-300">{{ __('Functions as a connector that only adds features without conflicts') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-purple-600 dark:text-purple-400 mt-1 mr-3"></i>
                            <span
                                class="text-gray-700 dark:text-gray-300">{{ __('Prevents monolithic systems where modules share dependencies') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="lg:w-1/2 flex justify-center">
                    <div class="bg-white dark:bg-gray-700 p-8 rounded-2xl shadow-lg max-w-md">
                        <div class="feature-icon mb-6 mx-auto">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-center text-gray-900 dark:text-white">
                            {{ __('Independent Modules') }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-center">
                            {{ __('With Elymod, each module can install its own dependencies, avoiding collisions between different versions of a dependency across modules or with the parent system.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Laravel Runtime Section -->
    <section id="runtime" class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 order-2 lg:order-1">
                    <div
                        class="bg-linear-to-r from-purple-100 to-blue-100 dark:from-purple-900/30 dark:to-blue-900/30 p-8 rounded-2xl shadow-lg max-w-md">
                        <div class="feature-icon mb-6">
                            <i class="fab fa-laravel"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('Laravel Runtime') }}</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ __('An abstraction layer between Laravel framework and Elymod that feels familiar like working with a Laravel project. This avoids breaking dependencies since Laravel framework is not modified, only used as a bridge for some functionalities.') }}
                        </p>
                    </div>
                </div>
                <div class="lg:w-1/2 mb-10 lg:mb-0 lg:pl-12 order-1 lg:order-2">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                        {{ __('Familiar Laravel Experience') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ __('Laravel Runtime provides a seamless transition for developers already familiar with Laravel, while maintaining the integrity of dependencies and preventing conflicts.') }}
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div
                                class="bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 p-3 rounded-lg mr-4">
                                <i class="fas fa-bridge"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Abstraction Bridge') }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ __('Acts as a bridge between Laravel and Elymod without modifying the core framework') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 p-3 rounded-lg mr-4">
                                <i class="fas fa-code-branch"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Dependency Safety') }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ __('Prevents breaking changes by avoiding direct modifications to Laravel') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Links Section -->
    <section id="links" class="py-16 gradient-bg text-white dark:bg-linear-to-br dark:from-gray-900 dark:to-gray-800">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('Get Started Today') }}</h2>
                <p class="text-xl opacity-90 dark:opacity-80 max-w-2xl mx-auto">
                    {{ __('Explore OAuth2 Passport Server, Elymod, and Laravel Runtime across our platforms.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- GitHub -->
                <a href="https://github.com/elyerrlabs" target="_blank"
                    class="bg-white/10 backdrop-filter backdrop-blur-lg rounded-xl p-6 text-center card-hover hover:bg-opacity-20 transition-all">
                    <div class="text-4xl mb-4">
                        <i class="fab fa-github"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('GitHub') }}</h3>
                    <p class="opacity-80 dark:opacity-90">
                        {{ __('Explore the source code and contribute to the project') }}</p>
                    <div class="mt-4 text-sm font-medium opacity-90">github.com/elyerrlabs</div>
                </a>

                <!-- GitHub -->
                <a href="https://github.com/elyerr" target="_blank"
                    class="bg-white/10 backdrop-filter backdrop-blur-lg rounded-xl p-6 text-center card-hover hover:bg-opacity-20 transition-all">
                    <div class="text-4xl mb-4">
                        <i class="fab fa-github"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('GitHub') }}</h3>
                    <p class="opacity-80 dark:opacity-90">{{ __('Check repositories and open-source contributions') }}</p>
                    <div class="mt-4 text-sm font-medium opacity-90">github.com/elyerr</div>
                </a>

                <!-- Packagist -->
                <a href="https://packagist.org/packages/elyerr" target="_blank"
                    class="bg-white/10 backdrop-filter backdrop-blur-lg rounded-xl p-6 text-center card-hover hover:bg-opacity-20 transition-all">
                    <div class="text-4xl mb-4">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('Packagist') }}</h3>
                    <p class="opacity-80 dark:opacity-90">{{ __('Install packages via Composer for your PHP projects') }}
                    </p>
                    <div class="mt-4 text-sm font-medium opacity-90">packagist.org/packages/elyerr</div>
                </a>

                <!-- Docker -->
                <a href="https://hub.docker.com/u/elyerr" target="_blank"
                    class="bg-white/10 backdrop-filter backdrop-blur-lg rounded-xl p-6 text-center card-hover hover:bg-opacity-20 transition-all">
                    <div class="text-4xl mb-4">
                        <i class="fab fa-docker"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('Docker Hub') }}</h3>
                    <p class="opacity-80 dark:opacity-90">{{ __('Pull Docker images for easy deployment') }}</p>
                    <div class="mt-4 text-sm font-medium opacity-90">hub.docker.com/u/elyerr</div>
                </a>
            </div>

            <!-- YouTube -->
            <div class="mt-12 max-w-2xl mx-auto">
                <a href="https://www.youtube.com/@elyerr" target="_blank"
                    class="flex flex-col md:flex-row items-center bg-white/10 backdrop-filter backdrop-blur-lg rounded-xl p-6 card-hover hover:bg-opacity-20 transition-all">
                    <div class="text-4xl mb-4 md:mb-0 md:mr-6">
                        <i class="fab fa-youtube text-red-500"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-xl font-bold mb-2">{{ __('YouTube Channel') }}</h3>
                        <p class="opacity-80 dark:opacity-90">
                            {{ __('Watch tutorials, demos, and updates about OAuth2 Passport Server and related technologies') }}
                        </p>
                        <div class="mt-4 text-sm font-medium opacity-90">youtube.com/@elyerr</div>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <footer class="bg-gray-900 text-white py-8 dark:bg-black">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-6 md:mb-0">
                    <div class="feature-icon">
                        <i class="fas fa-passport"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ __('OAuth2 Passport Server') }}</h2>
                        <p class="text-xs text-gray-400">{{ __('Modern Authorization Solution') }}</p>
                    </div>
                </div>

                <div class="flex space-x-6">
                    <a href="https://github.com/elyerrlabs" target="_blank"
                        class="text-gray-400 hover:text-white transition-colors" aria-label="GitHub">
                        <i class="fab fa-github text-xl"></i>
                    </a>
                    <a href="https://gitlab.com/elyerr" target="_blank"
                        class="text-gray-400 hover:text-white transition-colors" aria-label="GitLab">
                        <i class="fab fa-gitlab text-xl"></i>
                    </a>
                    <a href="https://github.com/elyerr" target="_blank"
                        class="text-gray-400 hover:text-white transition-colors" aria-label="GitHub">
                        <i class="fab fa-github text-xl"></i>
                    </a>

                    <a href="https://packagist.org/packages/elyerr" target="_blank"
                        class="text-gray-400 hover:text-white transition-colors" aria-label="Packagist">
                        <i class="fas fa-box text-xl"></i>
                    </a>
                    <a href="https://hub.docker.com/u/elyerr" target="_blank"
                        class="text-gray-400 hover:text-white transition-colors" aria-label="Docker">
                        <i class="fab fa-docker text-xl"></i>
                    </a>
                    <a href="https://www.youtube.com/@elyerr" target="_blank"
                        class="text-gray-400 hover:text-white transition-colors" aria-label="YouTube">
                        <i class="fab fa-youtube text-xl"></i>
                    </a>
                </div>
            </div>

            <div class="border-t border-gray-800 dark:border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Elyerr.
                    {{ __('All rights reserved. OAuth2 Passport Server, Elymod, and Laravel Runtime are open-source projects.') }}
                </p>
            </div>
        </div>
    </footer>
@endsection
