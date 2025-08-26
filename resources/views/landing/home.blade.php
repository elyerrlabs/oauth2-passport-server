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

        .orbit {
            border: 1px solid var(--color-border);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .app-node {
            background: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 1rem;
            position: absolute;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            color: var(--color-text-primary);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 70px;
            z-index: 10;
            cursor: pointer;
        }

        .app-node:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-primary);
            z-index: 20;
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

        /* Orbital system adjustments */
        .orbital-system {
            position: relative;
            width: 100%;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .orbit {
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid var(--color-border);
        }

        .orbit-gradient {
            background: linear-gradient(135deg,
                    rgba(79, 70, 229, 0.15) 0%,
                    rgba(99, 102, 241, 0.1) 50%,
                    rgba(199, 210, 254, 0.05) 100%);
            box-shadow:
                0 0 0 1px rgba(79, 70, 229, 0.2),
                0 0 25px rgba(79, 70, 229, 0.1);
        }

        .orbit-1 {
            width: 200px;
            height: 200px;
            animation: orbit-rotate 30s linear infinite;
        }

        .orbit-2 {
            width: 280px;
            height: 280px;
            animation: orbit-rotate 40s linear infinite reverse;
        }

        .orbit-3 {
            width: 360px;
            height: 360px;
            animation: orbit-rotate 50s linear infinite;
        }

        /* Position adjustments for nodes */
        .node-cloud {
            top: 5%;
            left: 50%;
            transform: translateX(-50%);
            animation: orbit-node-1 30s linear infinite;
        }

        .node-vpn {
            top: 25%;
            right: 25%;
            animation: orbit-node-2 40s linear infinite reverse;
        }

        .node-ecommerce {
            bottom: 5%;
            left: 50%;
            transform: translateX(-50%);
            animation: orbit-node-3 50s linear infinite;
        }

        .node-app-x {
            top: 25%;
            left: 25%;
            animation: orbit-node-4 35s linear infinite reverse;
        }

        .node-microservice-a {
            top: 40%;
            right: 10%;
            animation: orbit-node-5 45s linear infinite;
        }

        .node-microservice-b {
            top: 40%;
            left: 10%;
            animation: orbit-node-6 40s linear infinite reverse;
        }

        .node-elymod {
            bottom: 25%;
            right: 25%;
            animation: orbit-node-7 35s linear infinite;
        }

        .node-oidc {
            bottom: 25%;
            left: 25%;
            animation: orbit-node-8 45s linear infinite reverse;
        }

        /* Orbital animations */
        @keyframes orbit-rotate {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes orbit-node-1 {
            0% {
                transform: translateX(-50%) rotate(0deg) translateY(-180px) rotate(0deg);
            }

            100% {
                transform: translateX(-50%) rotate(360deg) translateY(-180px) rotate(-360deg);
            }
        }

        @keyframes orbit-node-2 {
            0% {
                transform: rotate(0deg) translateX(140px) rotate(0deg);
            }

            100% {
                transform: rotate(360deg) translateX(140px) rotate(-360deg);
            }
        }

        @keyframes orbit-node-3 {
            0% {
                transform: translateX(-50%) rotate(0deg) translateY(180px) rotate(0deg);
            }

            100% {
                transform: translateX(-50%) rotate(360deg) translateY(180px) rotate(-360deg);
            }
        }

        @keyframes orbit-node-4 {
            0% {
                transform: rotate(0deg) translateX(-140px) rotate(0deg);
            }

            100% {
                transform: rotate(360deg) translateX(-140px) rotate(-360deg);
            }
        }

        @keyframes orbit-node-5 {
            0% {
                transform: rotate(45deg) translateX(140px) rotate(-45deg);
            }

            100% {
                transform: rotate(405deg) translateX(140px) rotate(-405deg);
            }
        }

        @keyframes orbit-node-6 {
            0% {
                transform: rotate(-45deg) translateX(-140px) rotate(45deg);
            }

            100% {
                transform: rotate(-405deg) translateX(-140px) rotate(405deg);
            }
        }

        @keyframes orbit-node-7 {
            0% {
                transform: rotate(135deg) translateX(140px) rotate(-135deg);
            }

            100% {
                transform: rotate(495deg) translateX(140px) rotate(-495deg);
            }
        }

        @keyframes orbit-node-8 {
            0% {
                transform: rotate(-135deg) translateX(-140px) rotate(135deg);
            }

            100% {
                transform: rotate(-495deg) translateX(-140px) rotate(495deg);
            }
        }

        /* Particle effects */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: var(--color-primary);
            border-radius: 50%;
            opacity: 0;
            animation: particle-float 8s infinite ease-in-out;
        }

        @keyframes particle-float {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
                width: 2px;
                height: 2px;
            }

            10% {
                opacity: 0.6;
            }

            90% {
                opacity: 0.2;
            }

            100% {
                transform: translateY(-40px) translateX(20px);
                opacity: 0;
                width: 4px;
                height: 4px;
            }
        }

        /* Connection lines */
        .connection-line {
            position: absolute;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--color-primary), transparent);
            transform-origin: 0 0;
            z-index: 5;
            opacity: 0.3;
        }

        .pulse-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: var(--color-success);
            border-radius: 50%;
            z-index: 15;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            70% {
                transform: scale(2.5);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .orbital-system {
                height: 300px;
            }

            .orbit-1 {
                width: 150px;
                height: 150px;
            }

            .orbit-2 {
                width: 210px;
                height: 210px;
            }

            .orbit-3 {
                width: 270px;
                height: 270px;
            }

            .app-node {
                width: 70px;
                height: 60px;
                padding: 0.5rem;
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
                        {{ __('OAuth2 Passport Server') }}
                    </h1>
                    <p class="text-xl text-[var(--color-text-secondary)] max-w-3xl mx-auto mb-8">
                        {{ __('Modern OAuth2 + OpenID Connect • Modular Architecture • Enterprise Ready') }}
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 mb-12">
                        <a href="{{ route('documentation.index') }}" class="btn-primary inline-flex items-center">
                            <i class="mdi mdi-book-open-page-variant mr-2"></i>
                            {{ __('Documentation') }}
                        </a>
                        @if (Route::has('passport.clients.index'))
                            <a href="{{ route('passport.clients.index') }}" class="btn-outline inline-flex items-center">
                                <i class="mdi mdi-shield-key mr-2"></i>
                                {{ __('OAuth2 Clients') }}
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

        <!-- Orbital System -->
        <div class="relative py-12 bg-[var(--color-bg-secondary)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="orbital-system" id="orbital-system">
                    <!-- Particle effects -->
                    <div class="particles" id="particles"></div>
                    <!-- Orbits with gradient -->
                    <div class="orbit orbit-1 orbit-gradient"></div>
                    <div class="orbit orbit-2 orbit-gradient"></div>
                    <div class="orbit orbit-3 orbit-gradient"></div>

                    <!-- Central Server -->
                    <div
                        class="glass-card absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-24 h-24 md:w-32 md:h-32 rounded-full flex flex-col items-center justify-center p-4 z-30">
                        <i
                            class="mdi mdi-server-security text-2xl md:text-3xl text-[var(--color-primary)] mb-1 md:mb-2"></i>
                        <span
                            class="text-xs md:text-sm font-semibold text-center text-[var(--color-text-primary)]">{{ __('OAuth2 Server') }}</span>
                        <span
                            class="text-[10px] md:text-xs text-[var(--color-text-secondary)]">{{ __('Central Core') }}</span>
                        <div class="pulse-dot" style="top: 15px; right: 15px;"></div>
                    </div>

                    <!-- App Nodes with real URLs -->
                    <a href="https://github.com/elyerr/elyerr-cloud" class="app-node node-cloud"
                        title="Cloud Platform - OpenID Connect">
                        <i class="mdi mdi-cloud-outline text-[var(--color-primary)] text-lg md:text-xl"></i>
                        <span class="text-xs mt-1">{{ __('Cloud') }}</span>
                        <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                    </a>

                    <a href="https://vpn.elyerr.org" class="app-node node-vpn" title="VPN Service - OAuth2">
                        <i class="mdi mdi-security-network text-[var(--color-success)] text-lg md:text-xl"></i>
                        <span class="text-xs mt-1">{{ __('VPN') }}</span>
                        <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                    </a>

                    @if (Route::has('ecommerce.dashboard'))
                        <a href="{{ route('ecommerce.dashboard') }}" class="app-node node-ecommerce"
                            title="E-commerce Platform - Module">
                            <i class="mdi mdi-shopping text-[var(--color-danger)] text-lg md:text-xl"></i>
                            <span class="text-xs mt-1">{{ __('E-commerce') }}</span>
                            <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                        </a>
                    @endif

                    <a href="#" class="app-node node-app-x" title="Application X - OpenID Connect">
                        <i class="mdi mdi-cellphone-lock text-[var(--color-warning)] text-lg md:text-xl"></i>
                        <span class="text-xs mt-1">{{ __('App X') }}</span>
                        <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                    </a>

                    <div class="app-node node-microservice-a" title="Microservice A - Internal API">
                        <i class="mdi mdi-database text-[var(--color-info)] text-lg md:text-xl"></i>
                        <span class="text-xs mt-1">{{ __('Micro A') }}</span>
                        <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                    </div>

                    <div class="app-node node-microservice-b" title="Microservice B - Internal API">
                        <i class="mdi mdi-code-braces text-[var(--color-secondary)] text-lg md:text-xl"></i>
                        <span class="text-xs mt-1">{{ __('Micro B') }}</span>
                        <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                    </div>

                    <a href="#" class="app-node node-elymod" title="Elymod Modules">
                        <i class="mdi mdi-cog text-[var(--color-purple)] text-lg md:text-xl"></i>
                        <span class="text-xs mt-1">{{ __('Elymod') }}</span>
                        <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                    </a>

                    <a href="#" class="app-node node-oidc" title="OpenID Connect Configuration">
                        <i class="mdi mdi-connection text-[var(--color-indigo)] text-lg md:text-xl"></i>
                        <span class="text-xs mt-1">{{ __('OIDC') }}</span>
                        <div class="pulse-dot" style="top: 5px; right: 5px;"></div>
                    </a>
                </div>

                <div class="text-center mt-12 max-w-4xl mx-auto">
                    <h2 class="text-2xl md:text-3xl font-bold text-[var(--color-text-primary)] mb-4">
                        {{ __('A modular, secure, and centralized authentication server') }}
                    </h2>
                    <p class="text-lg text-[var(--color-text-secondary)]">
                        {{ __('Our OAuth2 server supports both core modules and isolated Elymod modules, allowing third-party applications to be integrated without compromising system stability.') }}
                    </p>
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
                                <span class="text-sm">{{ __('OAuth2 and OpendID Connect') }}</span>
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
                                    <span class="text-sm">{{ __('Cloud') }}</span>
                                </div>
                                <i
                                    class="mdi mdi-open-in-new opacity-0 group-hover:opacity-100 transition-opacity text-xs"></i>
                            </a>
                            <a href="https://vpn.elyerr.org"
                                class="flex items-center justify-between text-[var(--color-text-secondary)] hover:text-[var(--color-secondary)] transition-colors group">
                                <div class="flex items-center">
                                    <i class="mdi mdi-shield-lock mr-2"></i>
                                    <span class="text-sm">{{ __('VPN') }}</span>
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
                                    <span class="text-sm">{{ __('Oauth2 Clients') }}</span>
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
                        {{ __('Why OAuth2 Passport Server?') }}</h2>
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
                                    {{ __('Advanced Permissions') }}</h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    {{ __('Beyond traditional role models with application and group-based controls') }}
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
                                    {{ __('Seamless Integration') }}</h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    {{ __('Connect with Cloud, VPN, eCommerce, and external microservices') }}
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
                                    {{ __('Modular Architecture') }}</h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    {{ __('Ensuring resilience and scalability with isolated modules') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[var(--color-bg-primary)] rounded-lg p-6 shadow-sm">
                        <div class="flex items-start mb-4">
                            <div class="w-10 h-10 bg-orange-900/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="mdi mdi-eye-settings text-[var(--color-warning)]"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[var(--color-text-primary)]">{{ __('Centralized Control') }}
                                </h3>
                                <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                                    {{ __('Full auditability and real-time observability across all services') }}
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
                        <i class="mdi mdi-check-circle mr-2"></i> Passport • {{ __('Online') }}
                    </span>
                    <span class="status-pill inline-flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i> OpenID • {{ __('Online') }}
                    </span>
                    <span class="status-pill inline-flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i> DB • {{ __('Online') }}
                    </span>
                    <span class="status-pill inline-flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i> Queue • {{ __('Online') }}
                    </span>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            // Add subtle animations to orbital elements
            const appNodes = document.querySelectorAll('.app-node');
            const orbitalSystem = document.getElementById('orbital-system');
            const particlesContainer = document.getElementById('particles');

            appNodes.forEach(node => {
                node.addEventListener('mouseenter', () => {
                    node.style.transform = 'scale(1.1)';
                    node.style.zIndex = '20';
                });

                node.addEventListener('mouseleave', () => {
                    node.style.transform = '';
                    node.style.zIndex = '10';
                });
            });

            // Create particles
            function createParticles() {
                for (let i = 0; i < 20; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('particle');

                    // Random position
                    const left = Math.random() * 100;
                    const top = Math.random() * 100;

                    // Random delay
                    const delay = Math.random() * 8;

                    // Random size
                    const size = Math.random() * 3 + 1;

                    // Set styles
                    particle.style.left = `${left}%`;
                    particle.style.top = `${top}%`;
                    particle.style.animationDelay = `${delay}s`;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;

                    // Random color from theme
                    const colors = [
                        'var(--color-primary)',
                        'var(--color-secondary)',
                        'var(--color-success)',
                        'var(--color-info)'
                    ];
                    const randomColor = colors[Math.floor(Math.random() * colors.length)];
                    particle.style.background = randomColor;

                    particlesContainer.appendChild(particle);
                }
            }

            // Create connection lines between nodes and center
            function createConnectionLines() {
                const centerX = orbitalSystem.offsetWidth / 2;
                const centerY = orbitalSystem.offsetHeight / 2;

                appNodes.forEach(node => {
                    const rect = node.getBoundingClientRect();
                    const parentRect = orbitalSystem.getBoundingClientRect();

                    const nodeX = rect.left - parentRect.left + rect.width / 2;
                    const nodeY = rect.top - parentRect.top + rect.height / 2;

                    // Calculate distance and angle
                    const dx = nodeX - centerX;
                    const dy = nodeY - centerY;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    const angle = Math.atan2(dy, dx) * 180 / Math.PI;

                    // Create line
                    const line = document.createElement('div');
                    line.classList.add('connection-line');
                    line.style.width = `${distance}px`;
                    line.style.left = `${centerX}px`;
                    line.style.top = `${centerY}px`;
                    line.style.transform = `rotate(${angle}deg)`;

                    orbitalSystem.appendChild(line);
                });
            }

            // Initialize effects
            createParticles();
            createConnectionLines();

            // Make particles and connections responsive
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    // Remove existing connection lines
                    document.querySelectorAll('.connection-line').forEach(line => {
                        line.remove();
                    });

                    // Recreate connection lines
                    createConnectionLines();
                }, 250);
            });
        });
    </script>
@endpush
