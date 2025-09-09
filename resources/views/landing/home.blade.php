@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => config('app.name', 'OAuth2 Passport Server')])
@endsection

@push('css')
    <style nonce="{{ $nonce }}">
        :root {
            --color-primary: rgb(79 70 229);
            --color-primary-hover: rgb(67 56 202);
            --color-primary-light: rgb(199 210 254);
            --color-secondary: rgb(99 102 241);
            --color-success: rgb(16 185 129);
            --color-success-hover: rgb(5 150 105);
            --color-danger: rgb(220 38 38);
            --color-danger-hover: rgb(185 28 28);
            --color-warning: rgb(245 158 11);
            --color-info: rgb(59 130 246);
            --color-purple: rgb(139, 92, 246);
            --color-indigo: rgb(79, 70, 229);

            --color-text-primary: rgb(243 244 246);
            --color-text-secondary: rgb(209 213 219);
            --color-text-muted: rgb(156 163 175);

            --color-bg-primary: rgb(17 24 39);
            --color-bg-secondary: rgb(31 41 55);
            --color-bg-tertiary: rgb(55 65 81);

            --color-border: rgb(75 85 99);
            --color-border-hover: rgb(107 114 128);

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);

            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
        }

        .oauth-landing {
            background: var(--color-bg-primary);
            color: var(--color-text-primary);
            min-height: 100vh;
        }

        .glass-card {
            background: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
        }

        .btn-primary {
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            background: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: var(--color-success);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-md);
        }

        .btn-success:hover {
            background: var(--color-success-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline {
            background: transparent;
            color: var(--color-primary);
            border: 1px solid var(--color-primary);
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            background: var(--color-primary);
            color: white;
        }

        .feature-card {
            background: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-primary);
        }

        .status-pill {
            background: rgba(16, 185, 129, 0.1);
            color: var(--color-success);
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
            background: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .server-info-card:hover {
            border-color: var(--color-primary);
            box-shadow: var(--shadow-md);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--color-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--color-text-secondary);
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
            background: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .platform-card:hover {
            transform: translateY(-5px);
            border-color: var(--color-primary);
            box-shadow: var(--shadow-lg);
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
            background: var(--color-bg-secondary);
            border-radius: var(--radius-md);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: var(--radius-md);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--color-border-hover);
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
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-[var(--color-text-primary)] mb-4">
                        {{ __('Centralized Authentication & Authorization Server') }}
                    </h1>
                    <p class="text-xl text-[var(--color-text-secondary)] max-w-3xl mx-auto mb-8">
                        {{ __('Secure OAuth2 + OpenID Connect server that connects applications, microservices, and platforms with centralized session, users and services management') }}
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 mb-12">
                        <a href="{{ route('documentation.index') }}" class="btn-primary inline-flex items-center">
                            <i class="mdi mdi-book-open-page-variant mr-2"></i>
                            {{ __('Documentation') }}
                        </a>
                        <a href="{{ route('passport.clients.index') }}" class="btn-success inline-flex items-center">
                            <i class="mdi mdi-rocket-launch mr-2"></i>
                            {{ __('Try Now') }}
                        </a>
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
        <div class="relative py-12 bg-[var(--color-bg-secondary)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-[var(--color-text-primary)] mb-4">
                        {{ __('One Authentication Server, Infinite Possibilities') }}
                    </h2>
                    <p class="text-lg text-[var(--color-text-secondary)] max-w-4xl mx-auto">
                        {{ __('Our OAuth2 and OpenID Connect server provides a centralized authentication solution that seamlessly connects web applications, mobile apps, microservices, and external platforms with robust security and scalability.') }}
                    </p>
                </div>

                <!-- Platform Integration Grid -->
                <div class="platform-grid">
                    <div class="platform-card">
                        <div class="platform-icon text-[var(--color-primary)]">
                            <i class="mdi mdi-web"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[var(--color-text-primary)] mb-3">
                            {{ __('Web Applications') }}</h3>
                        <p class="text-[var(--color-text-secondary)]">
                            {{ __('Connect any web application built with React, Vue, Angular, or traditional server-side frameworks.') }}
                        </p>
                    </div>

                    <div class="platform-card">
                        <div class="platform-icon text-[var(--color-success)]">
                            <i class="mdi mdi-cellphone"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[var(--color-text-primary)] mb-3">Mobile Apps</h3>
                        <p class="text-[var(--color-text-secondary)]">
                            {{ __('iOS and Android applications with secure token-based authentication and seamless user experiences.') }}
                        </p>
                    </div>

                    <div class="platform-card">
                        <div class="platform-icon text-[var(--color-info)]">
                            <i class="mdi mdi-api"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[var(--color-text-primary)] mb-3">Microservices</h3>
                        <p class="text-[var(--color-text-secondary)]">
                            {{ __('Secure service-to-service communication with JWT tokens and centralized access control.') }}
                        </p>
                    </div>
                </div>

                <!-- Server Stats -->
                <div class="server-info-grid mt-12">
                    <div class="server-info-card">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 bg-[var(--color-primary-light)] rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-server-network text-[var(--color-primary)] text-xl"></i>
                            </div>
                            <div>
                                <div class="stat-value">50+</div>
                                <div class="stat-label">{{ __('Integrated Services') }}</div>
                            </div>
                        </div>
                        <p class="text-[var(--color-text-secondary)] text-sm">
                            {{ __('From VPN and eCommerce to custom microservices and partner integrations.') }}
                        </p>
                    </div>

                    <div class="server-info-card">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-shield-check text-[var(--color-success)] text-xl"></i>
                            </div>
                            <div>
                                <div class="stat-value">{{ __('256-bit') }}</div>
                                <div class="stat-label">Encryption</div>
                            </div>
                        </div>
                        <p class="text-[var(--color-text-secondary)] text-sm">
                            {{ __('Military-grade encryption for all authentication tokens and communications.') }}
                        </p>
                    </div>

                    <div class="server-info-card">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-account-multiple text-[var(--color-secondary)] text-xl"></i>
                            </div>
                            <div>
                                <div class="stat-value">10K+</div>
                                <div class="stat-label">{{ __('Active Sessions') }}</div>
                            </div>
                        </div>
                        <p class="text-[var(--color-text-secondary)] text-sm">
                            {{ __('Centralized session management across all connected applications and services.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Integration Section -->
        <div class="py-16 bg-[var(--color-bg-primary)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-[var(--color-text-primary)] mb-4">
                        {{ __('Integrated Payment Solutions') }}
                    </h2>
                    <p class="text-lg text-[var(--color-text-secondary)] max-w-3xl mx-auto">
                        {{ __('Secure payment processing integrated directly with your authentication system') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-[var(--color-bg-secondary)] rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-blue-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-credit-card-outline text-[var(--color-info)] text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-[var(--color-text-primary)]">
                                {{ __('Stripe Integration') }}</h3>
                        </div>
                        <p class="text-[var(--color-text-secondary)] mb-4">
                            {{ __('Securely process payments through Stripe with seamless authentication flow. Customers enjoy a smooth checkout experience without leaving your application.') }}
                        </p>
                        <ul class="space-y-2 text-[var(--color-text-secondary)]">
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-[var(--color-success)] mr-2"></i>
                                {{ __('PCI Compliant payment processing') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-[var(--color-success)] mr-2"></i>
                                {{ __('Support for multiple payment methods') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-[var(--color-success)] mr-2"></i>
                                {{ __('Recurring billing and subscriptions') }}
                            </li>
                        </ul>
                    </div>

                    <div class="bg-[var(--color-bg-secondary)] rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-green-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-cash-multiple text-[var(--color-success)] text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-[var(--color-text-primary)]">
                                {{ __('P2P & Direct Payments') }}</h3>
                        </div>
                        <p class="text-[var(--color-text-secondary)] mb-4">
                            {{ __('Enable direct peer-to-peer payments and offline transactions without third-party intervention. Perfect for direct sales and personal transactions.') }}
                        </p>
                        <ul class="space-y-2 text-[var(--color-text-secondary)]">
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-[var(--color-success)] mr-2"></i>
                                {{ __('Direct bank transfers and wallet payments') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-[var(--color-success)] mr-2"></i>
                                {{ __('Offline payment verification') }}
                            </li>
                            <li class="flex items-center">
                                <i class="mdi mdi-check-circle text-[var(--color-success)] mr-2"></i>
                                {{ __('Secure transaction recording') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="py-16 bg-[var(--color-bg-primary)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Applications Card -->
                    <div class="feature-card">
                        <div
                            class="w-12 h-12 bg-[var(--color-primary-light)] rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-apps text-[var(--color-primary)] text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Applications') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.clients.index') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors">
                                <i class="mdi mdi-connection mr-2"></i>
                                <span class="text-sm">{{ __('OAuth2 and OpenID Connect') }}</span>
                            </a>
                            <a href="{{ route('transaction.admin.dashboard') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors">
                                <i class="mdi mdi-cash mr-2"></i>
                                <span class="text-sm">{{ __('Transactions') }}</span>
                            </a>
                            <a href="{{ route('partner.dashboard') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors">
                                <i class="mdi mdi-text-recognition mr-2"></i>
                                <span class="text-sm">{{ __('Partners') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Access Control Card -->
                    <div class="feature-card">
                        <div class="w-12 h-12 bg-green-900/20 rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-shield-account text-[var(--color-success)] text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Access Control') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('user.admin.groups.index') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-success)] transition-colors">
                                <i class="mdi mdi-account-group-outline mr-2"></i>
                                <span class="text-sm">{{ __('Groups') }}</span>
                            </a>
                            <a href="{{ route('user.admin.services.index') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-success)] transition-colors">
                                <i class="mdi mdi-account-group-outline mr-2"></i>
                                <span class="text-sm">{{ __('Services') }}</span>
                            </a>
                            <a href="{{ route('user.admin.roles.index') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-success)] transition-colors">
                                <i class="mdi mdi-shield-star-outline mr-2"></i>
                                <span class="text-sm">{{ __('Roles') }}</span>
                            </a>
                            <a href="{{ route('user.admin.users.index') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-success)] transition-colors">
                                <i class="mdi mdi-account-key mr-2"></i>
                                <span class="text-sm">{{ __('Users') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Integrations Card -->
                    <div class="feature-card">
                        <div class="w-12 h-12 bg-purple-900/20 rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-connection text-[var(--color-secondary)] text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Integrations') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="https://github.com/elyerr/elyerr-cloud"
                                class="flex items-center justify-between text-[var(--color-text-secondary)] hover:text-[var(--color-secondary)] transition-colors group">
                                <div class="flex items-center">
                                    <i class="mdi mdi-cloud-outline mr-2"></i>
                                    <span class="text-sm">Cloud</span>
                                </div>
                                <i
                                    class="mdi mdi-open-in-new opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i>
                            </a>
                            <a href="https://vpn.elyerr.org"
                                class="flex items-center justify-between text-[var(--color-text-secondary)] hover:text-[var(--color-secondary)] transition-colors group">
                                <div class="flex items-center">
                                    <i class="mdi mdi-shield-lock mr-2"></i>
                                    <span class="text-sm">VPN</span>
                                </div>
                                <i
                                    class="mdi mdi-open-in-new opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i>
                            </a>
                            @if (Route::has('ecommerce.dashboard'))
                                <a href="{{ route('ecommerce.dashboard') }}"
                                    class="flex items-center justify-between text-[var(--color-text-secondary)] hover:text-[var(--color-secondary)] transition-colors group">
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
                    <div class="feature-card">
                        <div class="w-12 h-12 bg-orange-900/20 rounded-lg flex items-center justify-center mb-4">
                            <i class="mdi mdi-text-box-search-outline text-[var(--color-warning)] text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Observability') }}
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.logs') }}"
                                class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-warning)] transition-colors">
                                <i class="mdi mdi-clipboard-text-outline mr-2"></i>
                                <span class="text-sm">{{ __('Audit Logs') }}</span>
                            </a>
                            @if (Route::has('passport.personal.tokens.index'))
                                <a href="{{ route('passport.personal.tokens.index') }}"
                                    class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-warning)] transition-colors">
                                    <i class="mdi mdi-key-variant mr-2"></i>
                                    <span class="text-sm">{{ __('Issued Tokens') }}</span>
                                </a>
                            @endif
                            @if (Route::has('passport.clients.index'))
                                <a href="{{ route('passport.clients.index') }}"
                                    class="flex items-center text-[var(--color-text-secondary)] hover:text-[var(--color-warning)] transition-colors">
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
        <div class="py-16 bg-[var(--color-bg-secondary)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-[var(--color-text-primary)]">
                        Why Choose Our Authentication Server?
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-[var(--color-bg-primary)] rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div
                                class="w-10 h-10 bg-[var(--color-primary-light)] rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-shield-account text-[var(--color-primary)]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[var(--color-text-primary)]">
                                    Centralized Sessions
                                </h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    Manage all user sessions from a single dashboard across web, mobile, and microservices
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[var(--color-bg-primary)] rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div class="w-10 h-10 bg-green-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-connection text-[var(--color-success)]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[var(--color-text-primary)]">
                                    Unlimited Integrations
                                </h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    Connect VPN, eCommerce, payment systems, and custom microservices without limits
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[var(--color-bg-primary)] rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div class="w-10 h-10 bg-purple-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-puzzle text-[var(--color-secondary)]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[var(--color-text-primary)]">
                                    Modular & Scalable
                                </h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    Extend functionality with modules and scale horizontally to handle millions of users
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[var(--color-bg-primary)] rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div class="w-10 h-10 bg-orange-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-currency-usd text-[var(--color-warning)]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[var(--color-text-primary)]">Payment Ready</h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    Integrated Stripe and P2P payment processing with secure authentication flows
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Section -->
        <div class="py-12 bg-[var(--color-bg-primary)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="status-pill inline-flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i> Passport • Online
                    </span>
                    <span class="status-pill inline-flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i> OpenID • Online
                    </span>
                    <span class="status-pill inline-flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i> DB • Online
                    </span>
                    <span class="status-pill inline-flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i> Payments • Online
                    </span>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer')
    @include('layouts.parts.footer')
@endsection
