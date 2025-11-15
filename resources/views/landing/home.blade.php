@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => config('app.name', 'OAuth2 Passport Server')])
@endsection

@push('css')
    <style nonce="{{ $nonce }}">
        .oauth-landing {
            background: white;
            color: #374151;
            min-height: 100vh;
        }

        .glass-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .btn-primary {
            background: rgb(79 70 229);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .btn-primary:hover {
            background: rgb(67 56 202);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .btn-success {
            background: rgb(16 185 129);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .btn-success:hover {
            background: rgb(5 150 105);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .btn-outline {
            background: transparent;
            color: rgb(79 70 229);
            border: 1px solid rgb(79 70 229);
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            background: rgb(79 70 229);
            color: white;
        }

        .feature-card {
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            border-color: rgb(79 70 229);
        }

        .status-pill {
            background: rgba(16, 185, 129, 0.1);
            color: rgb(16 185 129);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Server info cards */
        .server-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .server-info-card {            
            
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .server-info-card:hover {
            border-color: rgb(79 70 229);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: rgb(79 70 229);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Integration platform section */
        .platform-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .platform-card {
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .platform-card:hover {
            transform: translateY(-5px);
            border-color: rgb(79 70 229);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .platform-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 0.5rem;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 0.5rem;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .server-info-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .platform-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('header')
    @include('layouts.parts.header')
@endsection

@section('content')
    <main class="oauth-landing">
        <!-- Hero Section -->
        <div class="relative overflow-hidden dark:bg-gray-900">
            <div class=" max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('Centralized Authentication & Authorization Server') }}
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-8">
                        {{ __('Secure OAuth2 + OpenID Connect server that connects applications, microservices, and platforms with centralized session, users and services management') }}
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 mb-12">
                        @if (Route::has('documentation.index'))
                            <a href="{{ route('documentation.index') }}" class="btn-primary inline-flex items-center">
                                <i class="mdi mdi-book-open-page-variant mr-2"></i>
                                {{ __('Documentation') }}
                            </a>
                        @endif
                        @if (config('system.demo.domain.enabled', false))
                            <a href="{{ config('system.demo.domain.url') }}" class="btn-success inline-flex items-center">
                                <i class="mdi mdi-rocket-launch mr-2"></i>
                                {{ __('Try Now') }}
                            </a>
                        @endif
                        @if (Route::has('passport.personal.tokens.index'))
                            <a href="{{ route('passport.personal.tokens.index') }}"
                                class="btn-outline inline-flex items-center">
                                <i class="mdi mdi-api mr-2"></i>
                                {{ __('API Keys') }}
                            </a>
                        @endif

                        <a href="https://www.youtube.com/@elyerr" target="_blank"
                            class="btn-outline inline-flex items-center">
                            <i class="mdi mdi-school-outline mr-2 2xl"></i>
                            {{ __('Tutorials') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Server Capabilities Section -->
        <div class="relative py-12 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('One Authentication Server, Infinite Possibilities') }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-4xl mx-auto">
                        {{ __('Our OAuth2 and OpenID Connect server provides a centralized authentication solution that seamlessly connects web applications, mobile apps, microservices, and external platforms with robust security and scalability.') }}
                    </p>
                </div>

                <!-- Platform Integration Grid -->
                <div class="platform-grid ">
                    <div class="platform-card bg-white rounded dark:bg-gray-700">
                        <div class="platform-icon text-indigo-600 dark:text-indigo-400">
                            <i class="mdi mdi-web"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                            {{ __('Web Applications') }}</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('Connect any web application built with React, Vue, Angular, or traditional server-side frameworks.') }}
                        </p>
                    </div>

                    <div class="platform-card bg-white rounded dark:bg-gray-700">
                        <div class="platform-icon text-green-600 dark:text-green-400">
                            <i class="mdi mdi-cellphone"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('Mobile Apps') }}</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('iOS and Android applications with secure token-based authentication and seamless user experiences.') }}
                        </p>
                    </div>

                    <div class="platform-card bg-white rounded dark:bg-gray-700">
                        <div class="platform-icon text-blue-600 dark:text-blue-400">
                            <i class="mdi mdi-api"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ __('Microservices') }}</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('Secure service-to-service communication with JWT tokens and centralized access control.') }}
                        </p>
                    </div>
                </div>

                <!-- Server Stats -->
                <div class="server-info-grid mt-12">
                    <div class="server-info-card bg-white dark:bg-gray-700">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-server-network text-indigo-600 dark:text-indigo-400 text-xl"></i>
                            </div>
                            <div>
                                <div class="stat-value dark:text-white">50+</div>
                                <div class="stat-label dark:text-gray-300">{{ __('Integrated Services') }}</div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            {{ __('From VPN and eCommerce to custom microservices and partner integrations.') }}
                        </p>
                    </div>

                    <div class="server-info-card bg-white dark:bg-gray-700">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-shield-check text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div>
                                <div class="stat-value dark:text-white">{{ __('256-bit') }}</div>
                                <div class="stat-label dark:text-gray-300">{{ __('Encryption') }}</div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            {{ __('Military-grade encryption for all authentication tokens and communications.') }}
                        </p>
                    </div>

                    <div class="server-info-card bg-white dark:bg-gray-700">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-account-multiple text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                            <div>
                                <div class="stat-value dark:text-white">10K+</div>
                                <div class="stat-label dark:text-gray-300">{{ __('Active Sessions') }}</div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            {{ __('Centralized session management across all connected applications and services.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Integration Section -->
        <div class="py-16 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('Integrated Payment Solutions') }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        {{ __('Secure payment processing integrated directly with your authentication system') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-14 h-14 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-credit-card-outline text-blue-600 dark:text-blue-400 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ __('Stripe Integration') }}</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            {{ __('Securely process payments through Stripe with seamless authentication flow. Customers enjoy a smooth checkout experience without leaving your application.') }}
                        </p>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-300">
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-green-600 mr-2"></i>
                                {{ __('PCI Compliant payment processing') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-green-600 mr-2"></i>
                                {{ __('Support for multiple payment methods') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-green-600 mr-2"></i>
                                {{ __('Recurring billing and subscriptions') }}
                            </li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-14 h-14 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-cash-multiple text-green-600 dark:text-green-400 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ __('P2P & Direct Payments') }}</h3>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            {{ __('Enable direct peer-to-peer payments and offline transactions without third-party intervention. Perfect for direct sales and personal transactions.') }}
                        </p>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-300">
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-green-600 mr-2"></i>
                                {{ __('Direct bank transfers and wallet payments') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-green-600 mr-2"></i>
                                {{ __('Offline payment verification') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-green-600 mr-2"></i>
                                {{ __('Secure transaction recording') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="py-16 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Applications Card -->
                    <div class="feature-card dark:bg-gray-800">
                        <div
                            class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-apps text-indigo-600 dark:text-indigo-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('Applications') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.clients.index') }}"
                                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <i class="mdi mdi-connection mr-2"></i>
                                <span class="text-sm">{{ __('OAuth2 and OpenID Connect') }}</span>
                            </a>
                            <a href="{{ route('transaction.admin.dashboard') }}"
                                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <i class="mdi mdi-cash mr-2"></i>
                                <span class="text-sm">{{ __('Transactions') }}</span>
                            </a>
                            @if (Route::has('partner.dashboard'))
                                <a href="{{ route('partner.dashboard') }}"
                                    class="flex items-center text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <i class="mdi mdi-text-recognition mr-2"></i>
                                    <span class="text-sm">{{ __('Partners') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Access Control Card -->
                    <div class="feature-card bg-white rounded dark:bg-gray-800">
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-shield-account text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('Access Control') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('user.admin.groups.index') }}"
                                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                <i class="mdi mdi-account-group-outline mr-2"></i>
                                <span class="text-sm">{{ __('Groups') }}</span>
                            </a>
                            <a href="{{ route('user.admin.services.index') }}"
                                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                <i class="mdi mdi-account-group-outline mr-2"></i>
                                <span class="text-sm">{{ __('Services') }}</span>
                            </a>
                            <a href="{{ route('user.admin.roles.index') }}"
                                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                <i class="mdi mdi-shield-star-outline mr-2"></i>
                                <span class="text-sm">{{ __('Roles') }}</span>
                            </a>
                            <a href="{{ route('user.admin.users.index') }}"
                                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                                <i class="mdi mdi-account-key mr-2"></i>
                                <span class="text-sm">{{ __('Users') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Integrations Card -->
                    <div class="feature-card bg-white rounded dark:bg-gray-800">
                        <div
                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-connection text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('Integrations') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="https://github.com/elyerr/elyerr-cloud"
                                class="flex items-center justify-between text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors group">
                                <div class="flex items-center">
                                    <i class="mdi mdi-cloud-outline mr-2"></i>
                                    <span class="text-sm">Cloud</span>
                                </div>
                                <i
                                    class="mdi mdi-open-in-new opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i>
                            </a>
                            <a href="https://vpn.elyerr.org"
                                class="flex items-center justify-between text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors group">
                                <div class="flex items-center">
                                    <i class="mdi mdi-shield-lock mr-2"></i>
                                    <span class="text-sm">{{ __('VPN') }}</span>
                                </div>
                                <i
                                    class="mdi mdi-open-in-new opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i>
                            </a>
                            @if (Route::has('ecommerce.dashboard'))
                                <a href="{{ route('ecommerce.dashboard') }}"
                                    class="flex items-center justify-between text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors group">
                                    <div class="flex items-center">
                                        <i class="mdi mdi-cart-outline mr-2"></i>
                                        <span class="text-sm">{{ __('eCommerce') }}</span>
                                    </div>
                                    <i
                                        class="mdi mdi-open-in-new opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Observability Card -->
                    <div class="feature-card bg-white rounded dark:bg-gray-800">
                        <div
                            class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-text-box-search-outline text-orange-600 dark:text-orange-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('Observability') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.logs') }}"
                                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                                <i class="mdi mdi-clipboard-text-outline mr-2"></i>
                                <span class="text-sm">{{ __('Audit Logs') }}</span>
                            </a>
                            @if (Route::has('passport.personal.tokens.index'))
                                <a href="{{ route('passport.personal.tokens.index') }}"
                                    class="flex items-center text-gray-600 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                                    <i class="mdi mdi-key-variant mr-2"></i>
                                    <span class="text-sm">{{ __('Issued Tokens') }}</span>
                                </a>
                            @endif
                            @if (Route::has('passport.clients.index'))
                                <a href="{{ route('passport.clients.index') }}"
                                    class="flex items-center text-gray-600 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                                    <i class="mdi mdi-connection mr-2"></i>
                                    <span class="text-sm">{{ __('OAuth2 Clients') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="py-16 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __('Why Choose Our Authentication Server?') }}
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div
                                class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-shield-account text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    {{ __('Centralized Sessions') }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                                    {{ __('Manage all user sessions from a single dashboard across web, mobile, and microservices') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div
                                class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-connection text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    {{ __('Unlimited Integrations') }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                                    {{ __('Connect VPN, eCommerce, payment systems, and custom microservices without limits') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div
                                class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-puzzle text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    {{ __('Modular & Scalable') }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                                    {{ __('Extend functionality with modules and scale horizontally to handle millions of users') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div
                                class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-currency-usd text-orange-600 dark:text-orange-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('Payment Ready') }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                                    {{ __('Integrated Stripe and P2P payment processing with secure authentication flows') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Section -->
        <div class="py-12 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="status-pill inline-flex items-center dark:bg-gray-700 dark:text-gray-300">
                        <i class="mdi mdi-check-circle mr-2"></i> {{ __('Passport • Online') }}
                    </span>
                    <span class="status-pill inline-flex items-center dark:bg-gray-700 dark:text-gray-300">
                        <i class="mdi mdi-check-circle mr-2"></i> {{ __('OpenID • Online') }}
                    </span>
                    <span class="status-pill inline-flex items-center dark:bg-gray-700 dark:text-gray-300">
                        <i class="mdi mdi-check-circle mr-2"></i> {{ __('DB • Online') }}
                    </span>
                    <span class="status-pill inline-flex items-center dark:bg-gray-700 dark:text-gray-300">
                        <i class="mdi mdi-check-circle mr-2"></i> {{ __('Payments • Online') }}
                    </span>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer')
    @include('layouts.parts.footer')
@endsection
