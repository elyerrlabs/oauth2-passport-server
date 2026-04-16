@extends('layouts.pages')

@push('head')
    <title>{{ config('app.name', 'Oauth2 Passport Server') }}</title>
    <meta name="description"
        content="Base template for a custom page. Edit each section to build your own landing, documentation, marketing, or internal content page.">
    <meta name="author" content="{{ config('app.name') }}">
@endpush

@push('css')
    <style nonce="{{ $nonce }}">
        :root {
            --page-bg: #f8fafc;
            --page-surface: rgba(255, 255, 255, 0.95);
            --page-surface-strong: #ffffff;
            --page-border: rgba(148, 163, 184, 0.2);
            --page-text: #0f172a;
            --page-muted: #475569;
            --page-accent: #0f766e;
            --page-accent-light: #14b8a6;
            --page-accent-soft: rgba(15, 118, 110, 0.08);
            --page-glow: rgba(15, 118, 110, 0.15);
            --page-shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --page-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --page-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --page-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .dark {
            --page-bg: #020617;
            --page-surface: rgba(30, 41, 59, 0.95);
            --page-surface-strong: #1e293b;
            --page-border: rgba(148, 163, 184, 0.12);
            --page-text: #f1f5f9;
            --page-muted: #94a3b8;
            --page-accent: #2dd4bf;
            --page-accent-light: #5eead4;
            --page-accent-soft: rgba(45, 212, 191, 0.1);
            --page-glow: rgba(45, 212, 191, 0.2);
        }

        .page-shell {
            position: relative;
            background: var(--page-bg);
            color: var(--page-text);
            min-height: calc(100vh - 200px);
        }

        .page-shell::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, var(--page-accent-soft) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, var(--page-accent-soft) 0%, transparent 50%);
            opacity: 0.6;
            pointer-events: none;
        }

        .page-grid-pattern {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(var(--page-border) 1px, transparent 1px),
                linear-gradient(90deg, var(--page-border) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(circle at center, black 40%, transparent 80%);
            pointer-events: none;
        }

        .page-hero {
            position: relative;
            overflow: hidden;
        }

        .page-hero-glow {
            position: absolute;
            top: -50%;
            left: -20%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, var(--page-accent-soft) 0%, transparent 70%);
            filter: blur(60px);
            opacity: 0.5;
            border-radius: 50%;
        }

        .page-card {
            background: var(--page-surface-strong);
            border: 1px solid var(--page-border);
            transition: all 0.3s ease;
        }

        .page-card:hover {
            transform: translateY(-4px);
            border-color: var(--page-accent-light);
            box-shadow: var(--page-shadow-xl);
        }

        .page-feature-card {
            background: linear-gradient(135deg, var(--page-surface-strong) 0%, var(--page-surface) 100%);
            border: 1px solid var(--page-border);
            position: relative;
            overflow: hidden;
        }

        .page-feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--page-accent), var(--page-accent-light));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .page-feature-card:hover::before {
            transform: scaleX(1);
        }

        .page-stat-card {
            background: linear-gradient(135deg, var(--page-accent-soft) 0%, transparent 100%);
            border: 1px solid var(--page-border);
            backdrop-filter: blur(10px);
        }

        .page-code-block {
            background: #0f172a;
            border-radius: 12px;
            padding: 1rem;
            font-family: monospace;
            font-size: 0.75rem;
            line-height: 1.5;
            color: #e2e8f0;
            overflow-x: auto;
        }

        .dark .page-code-block {
            background: #0a0f1a;
        }

        .page-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            background: var(--page-accent-soft);
            color: var(--page-accent);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        .page-icon-container {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--page-accent-soft) 0%, transparent 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }

        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--page-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--page-accent);
            border-radius: 4px;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>
@endpush

@section('header')
    @include('pages.layouts.header')
@endsection

@section('content')
    <div class="page-shell">
        <div class="page-grid-pattern"></div>

        <div class="content-wrapper">
            <!-- Hero Section -->
            <section class="page-hero relative overflow-hidden pt-24 pb-16">
                <div class="page-hero-glow"></div>

                <div class="container relative z-10 mx-auto px-6">
                    <div class="mx-auto max-w-4xl text-center">
                        <div class="animate-fadeInUp mb-6 inline-flex">
                            <span class="page-badge">
                                <i class="mdi mdi-rocket text-sm"></i>
                                Dynamic Page Builder
                            </span>
                        </div>

                        <h1
                            class="animate-fadeInUp mb-6 text-5xl font-bold tracking-tight text-slate-900 dark:text-white md:text-6xl lg:text-7xl">
                            Build Anything
                            <span class="bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent"> Without
                                Limits</span>
                        </h1>

                        <p
                            class="animate-fadeInUp mx-auto mb-8 max-w-2xl text-lg leading-relaxed text-slate-600 dark:text-slate-300">
                            A powerful template that gives you complete control. Add database models, APIs, Vue components,
                            custom logic, and real-time editing — all without deploying code.
                        </p>

                        <div class="animate-fadeInUp flex flex-wrap justify-center gap-4">
                            <a href="#features"
                                class="inline-flex items-center rounded-lg bg-teal-600 px-6 py-3 font-semibold text-white transition hover:bg-teal-700">
                                Explore Features
                                <i class="mdi mdi-arrow-right ml-2"></i>
                            </a>
                            <a href="#sections"
                                class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:border-teal-500 hover:text-teal-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                See Examples
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="relative py-16">
                <div class="container relative z-10 mx-auto px-6">
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="page-stat-card reveal-on-scroll rounded-2xl p-6 text-center">
                            <div
                                class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900">
                                <i class="mdi mdi-puzzle text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">7+</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Ready Sections</div>
                        </div>

                        <div class="page-stat-card reveal-on-scroll rounded-2xl p-6 text-center">
                            <div
                                class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900">
                                <i class="mdi mdi-database text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">100%</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Database Ready</div>
                        </div>

                        <div class="page-stat-card reveal-on-scroll rounded-2xl p-6 text-center">
                            <div
                                class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900">
                                <i class="mdi mdi-code-tags text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">No Deploy</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Instant Changes</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="relative py-20">
                <div class="container relative z-10 mx-auto px-6">
                    <div class="mb-12 text-center">
                        <span class="page-badge mb-4">Core Capabilities</span>
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white md:text-4xl">
                            Everything You Need to Build
                        </h2>
                        <p class="mx-auto mt-4 max-w-2xl text-slate-600 dark:text-slate-400">
                            Access all the power of Laravel, Vue, and modern web development
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Database Models -->
                        <div class="page-feature-card reveal-on-scroll rounded-xl p-6">
                            <div class="page-icon-container mb-4">
                                <i class="mdi mdi-database-outline text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">Database Models</h3>
                            <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
                                Query any existing model directly. Display users, products, posts, or any data from your
                                database.
                            </p>
                            <div class="page-code-block mt-3">
                                <code class="text-xs">
                                    {{ '@php $users = App\Models\User::latest()->take(5)->get(); @endphp' }}
                                </code>
                            </div>
                        </div>

                        <!-- API Integration -->
                        <div class="page-feature-card reveal-on-scroll rounded-xl p-6">
                            <div class="page-icon-container mb-4">
                                <i class="mdi mdi-api text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">API Integration</h3>
                            <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
                                Consume internal or external APIs. Fetch real-time data from REST or GraphQL endpoints.
                            </p>
                            <div class="page-code-block mt-3">
                                <code class="text-xs">
                                    fetch('/api/posts').then(res => res.json())
                                </code>
                            </div>
                        </div>

                        <!-- Vue Components -->
                        <div class="page-feature-card reveal-on-scroll rounded-xl p-6">
                            <div class="page-icon-container mb-4">
                                <i class="mdi mdi-vuejs text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">Vue.js Components</h3>
                            <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
                                Build interactive UI with Vue. Create reactive components that update in real-time.
                            </p>
                            <div class="page-code-block mt-3">
                                <code class="text-xs">
                                    {{ "<div id='app'>   message </div>" }}
                                </code>
                            </div>
                        </div>

                        <!-- Custom Logic -->
                        <div class="page-feature-card reveal-on-scroll rounded-xl p-6">
                            <div class="page-icon-container mb-4">
                                <i class="mdi mdi-sitemap text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">Custom Logic</h3>
                            <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
                                Write PHP logic directly. Use conditionals, loops, functions, and existing helpers.
                            </p>
                            <div class="page-code-block mt-3">
                                <code class="text-xs">
                                    {{ '@if (auth()->check()) Welcome back! @endif' }}
                                </code>
                            </div>
                        </div>

                        <!-- Real-time Editing -->
                        <div class="page-feature-card reveal-on-scroll rounded-xl p-6">
                            <div class="page-icon-container mb-4">
                                <i class="mdi mdi-pencil-ruler text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">Real-time Editing</h3>
                            <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
                                Changes reflect immediately. No deployment, no commit, no waiting for builds.
                            </p>
                            <div class="page-code-block mt-3">
                                <code class="text-xs">
                                    Edit → Save → See changes instantly
                                </code>
                            </div>
                        </div>

                        <!-- Variables & Functions -->
                        <div class="page-feature-card reveal-on-scroll rounded-xl p-6">
                            <div class="page-icon-container mb-4">
                                <i class="mdi mdi-function-variant text-2xl text-teal-600 dark:text-teal-400"></i>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">Variables & Functions</h3>
                            <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">
                                Use system variables, create custom functions, and reuse logic across pages.
                            </p>
                            <div class="page-code-block mt-3">
                                <code class="text-xs">
                                    $total = array_sum($items);
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Live Demo -->
            <section class="relative py-16 bg-white/50 dark:bg-slate-900/50">
                <div class="container relative z-10 mx-auto px-6">
                    <div class="page-card reveal-on-scroll rounded-3xl p-8 md:p-12">
                        <div class="mb-8 text-center">
                            <span class="page-badge mb-4">Live Example</span>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white md:text-4xl">
                                Current User Information
                            </h2>
                            <p class="mx-auto mt-4 max-w-2xl text-slate-600 dark:text-slate-400">
                                Using auth() helper to display real-time user data
                            </p>
                        </div>

                        <div class="max-w-2xl mx-auto">
                            @auth
                                <div
                                    class="flex items-center gap-6 p-6 rounded-2xl bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-950/30 dark:to-cyan-950/30 border border-teal-200 dark:border-teal-800">
                                    <div
                                        class="w-20 h-20 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg">
                                        <span class="text-2xl font-bold text-white">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}
                                        </h3>
                                        <p class="text-slate-600 dark:text-slate-400">{{ auth()->user()->email }}</p>
                                        <p class="text-sm text-teal-600 dark:text-teal-400 mt-2">
                                            <i class="mdi mdi-clock-outline"></i> Member since
                                            {{ auth()->user()->created_at->format('F Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                    <code class="text-xs text-slate-700 dark:text-slate-300">
                                        auth()->user()->name = {{ auth()->user()->name }}<br>
                                        auth()->user()->email = {{ auth()->user()->email }}
                                    </code>
                                </div>
                            @else
                                <div class="text-center p-8">
                                    <i class="mdi mdi-account-off text-5xl text-slate-400 mb-4"></i>
                                    <p class="text-slate-600 dark:text-slate-400">No user is currently logged in</p>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </section>

            <!-- Examples Section -->
            <section id="examples" class="relative py-20">
                <div class="container relative z-10 mx-auto px-6">
                    <div class="page-card reveal-on-scroll rounded-3xl p-8 md:p-12">
                        <div class="mb-8 text-center">
                            <span class="page-badge mb-4">Inspiration</span>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white md:text-4xl">
                                What You Can Build
                            </h2>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                            <div class="text-center">
                                <div
                                    class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900">
                                    <i class="mdi mdi-rocket text-xl text-teal-600 dark:text-teal-400"></i>
                                </div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">Landing Pages</h3>
                            </div>

                            <div class="text-center">
                                <div
                                    class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                    <i class="mdi mdi-file-document text-xl text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">Documentation</h3>
                            </div>

                            <div class="text-center">
                                <div
                                    class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                                    <i class="mdi mdi-scale-balance text-xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">Legal Pages</h3>
                            </div>

                            <div class="text-center">
                                <div
                                    class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                    <i class="mdi mdi-chart-donut text-xl text-green-600 dark:text-green-400"></i>
                                </div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">Dashboards</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Resources Section -->
            <section id="resources" class="relative py-20">
                <div class="container relative z-10 mx-auto px-6">
                    <div class="grid gap-8 lg:grid-cols-2">
                        <div class="reveal-on-scroll">
                            <div class="page-card rounded-2xl p-8">
                                <div
                                    class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900">
                                    <i class="mdi mdi-lightbulb text-2xl text-teal-600 dark:text-teal-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Quick Tips</h3>
                                <ul class="space-y-3 text-slate-600 dark:text-slate-400">
                                    <li>Edit any section by modifying the Blade code directly</li>
                                    <li>Add database queries using existing Laravel models</li>
                                    <li>Include Vue components for interactive elements</li>
                                    <li>Use CSS variables for consistent theming</li>
                                </ul>
                            </div>
                        </div>

                        <div class="reveal-on-scroll">
                            <div class="page-card rounded-2xl p-8">
                                <div
                                    class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                                    <i class="mdi mdi-code-json text-2xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Available Sections</h3>
                                <div class="space-y-2 text-slate-600 dark:text-slate-400">
                                    <p>✅ push('head') - Meta tags and custom styles</p>
                                    <p>✅ section('header') - Navigation and branding</p>
                                    <p>✅ section('content') - Main page content</p>
                                    <p>✅ section('footer') - Footer and copyright</p>
                                    <p>✅ push('js') - JavaScript and Vue components</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('footer')
    @include('pages.layouts.footer')
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        const revealElements = document.querySelectorAll('.reveal-on-scroll');

        const revealOnScroll = function() {
            revealElements.forEach(function(element) {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.classList.add('revealed');
                }
            });
        };

        revealOnScroll();
        window.addEventListener('scroll', revealOnScroll);

        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endpush
