<!-- resources/views/settings.blade.php -->
@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Settings')])
@endsection

@push('css')
    <style nonce="{{ $nonce }}">
    </style>
@endpush

@section('header')
    <nav class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white py-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="{{ route('user.dashboard') }}"
                class="flex items-center space-x-2 text-lg font-semibold hover:opacity-90 transition-opacity">
                <i class="mdi mdi-arrow-left text-2xl"></i>
                <span>{{ __('Back to Dashboard') }}</span>
            </a>
            <h1 class="text-xl font-bold flex items-center">
                <i class="mdi mdi-cog-outline mr-2"></i>
                {{ __('System Settings') }}
            </h1>
        </div>
    </nav>
@endsection

@section('content')
    <div class="flex-grow mx-auto max-w-7xl py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <button id="menu-toggle"
                    class="lg:hidden w-full px-4 py-3 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                    <i class="mdi mdi-menu text-xl mr-2"></i>
                    <span>{{ __('Settings Menu') }}</span>
                    <i class="mdi mdi-chevron-down ml-2 transition-transform duration-300" id="menu-chevron"></i>
                </button>

                <div id="sidebar-menu"
                    class="hidden lg:block bg-[var(--color-bg-primary)] border border-[var(--color-border)] rounded-2xl shadow-lg overflow-hidden mt-4 lg:mt-0 transition-all duration-300">
                    <div class="p-4 bg-[var(--color-bg-secondary)] border-b border-[var(--color-border)]">
                        <h2 class="text-lg font-semibold text-[var(--color-text-primary)] flex items-center">
                            <i class="mdi mdi-tune-variant mr-2 text-[var(--color-primary)]"></i>
                            {{ __('Configuration Sections') }}
                        </h2>
                        <p class="text-sm text-[var(--color-text-secondary)] mt-1">
                            {{ __('Manage your application settings') }}
                        </p>
                    </div>

                    <ul class="py-2">
                        @php
                            $routes = [
                                'admin.settings.general' => ['icon' => 'mdi-cog-outline', 'label' => __('General')],
                                'admin.settings.session' => ['icon' => 'mdi-clock-outline', 'label' => __('Session')],
                                'admin.settings.payment' => [
                                    'icon' => 'mdi-credit-card-outline',
                                    'label' => __('Payment'),
                                ],
                                'admin.settings.email' => ['icon' => 'mdi-email-outline', 'label' => __('Email')],
                                'admin.settings.routes' => ['icon' => 'mdi-routes', 'label' => __('Routes')],
                                'admin.settings.redis' => ['icon' => 'mdi-database', 'label' => __('Redis')],
                                'admin.settings.cache' => ['icon' => 'mdi-cached', 'label' => __('Cache')],
                                'admin.settings.queues' => ['icon' => 'mdi-playlist-play', 'label' => __('Queues')],
                                'admin.settings.filesystem' => [
                                    'icon' => 'mdi-folder-outline',
                                    'label' => __('Filesystem'),
                                ],
                                'admin.settings.rate_limit' => [
                                    'icon' => 'mdi-traffic-light',
                                    'label' => __('Rate Limit'),
                                ],
                                'admin.settings.security' => [
                                    'icon' => 'mdi-shield-outline',
                                    'label' => __('Security'),
                                ],
                                'admin.logs' => ['icon' => 'mdi-text-box-outline', 'label' => __('Log viewer')],
                            ];
                        @endphp

                        @foreach ($routes as $route => $data)
                            <li class="border-b border-[var(--color-border)] last:border-b-0">
                                <a href="{{ route($route) }}"
                                    class="flex items-center gap-3 px-5 py-4 text-[var(--color-text-primary)] hover:bg-[var(--color-bg-secondary)] transition-all duration-300 group
                                   {{ request()->routeIs($route) ? 'bg-[var(--color-primary-light)] border-l-4 border-[var(--color-primary)] text-[var(--color-primary)] font-semibold' : '' }}">
                                    <i
                                        class="mdi {{ $data['icon'] }} text-xl group-hover:text-[var(--color-primary)] transition-colors {{ request()->routeIs($route) ? 'text-[var(--color-primary)]' : 'text-[var(--color-text-secondary)]' }}"></i>
                                    <span class="flex-1">{{ $data['label'] }}</span>
                                    @if (request()->routeIs($route))
                                        <i class="mdi mdi-check-circle text-[var(--color-success)]"></i>
                                    @else
                                        <i
                                            class="mdi mdi-chevron-right text-[var(--color-text-muted)] group-hover:text-[var(--color-primary)]"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="w-full lg:w-3/4">
                <div
                    class="bg-[var(--color-bg-primary)] rounded-2xl shadow-lg border border-[var(--color-border)] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[var(--color-border)] bg-[var(--color-bg-secondary)]">
                        <h2 class="text-xl font-semibold text-[var(--color-text-primary)]">
                            <i class="mdi mdi-pencil-outline mr-2 text-[var(--color-primary)]"></i>
                            @yield('settings_title', __('Edit Settings'))
                        </h2>
                        <p class="text-sm text-[var(--color-text-secondary)] mt-1">
                            @yield('settings_description', __('Modify your application configuration'))
                        </p>
                    </div>

                    <form action="{{ route('admin.settings.update') }}" method="post" autocomplete="off" class="p-6">
                        @method('put')
                        @csrf
                        <input type="hidden" name="current_route" value="{{ url()->current() }}">

                        <div class="max-h-[100vh] overflow-y-auto custom-scrollbar pr-2 -mr-2">
                            @yield('form')
                        </div>

                        <div class="mt-8 pt-6 border-t border-[var(--color-border)] flex justify-end">
                            <button type="submit"
                                class="flex items-center px-6 py-3 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:from-[var(--color-primary-hover)] hover:to-[var(--color-primary-hover)] focus:ring-2 focus:ring-[var(--color-primary)] focus:ring-offset-2">
                                <i class="mdi mdi-content-save-all mr-2"></i>{{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="mt-8 bg-[var(--color-bg-primary)] rounded-2xl shadow-lg border border-[var(--color-border)] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[var(--color-border)] bg-[var(--color-bg-secondary)]">
                        <h2 class="text-xl font-semibold text-[var(--color-text-primary)] flex items-center">
                            <i class="mdi mdi-cached mr-2 text-[var(--color-info)]"></i>
                            {{ __('Cache Management') }}
                        </h2>
                    </div>

                    <form action="{{ route('admin.settings.reload') }}" method="post" autocomplete="off" class="p-6">
                        @method('put')
                        @csrf
                        <input type="hidden" name="current_route" value="{{ url()->current() }}">

                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 mr-3 text-[var(--color-info)]">
                                <i class="mdi mdi-information-outline text-2xl"></i>
                            </div>
                            <p class="text-sm text-[var(--color-text-secondary)]">
                                {{ __('This will clear and regenerate the cache for all application settings to ensure the latest values are used across the system.') }}
                            </p>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="flex items-center px-6 py-3 bg-gradient-to-r from-[var(--color-success)] to-[var(--color-success-hover)] text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 focus:ring-2 focus:ring-[var(--color-success)] focus:ring-offset-2">
                                <i class="mdi mdi-cached mr-2"></i>{{ __('Rebuild Settings Cache') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const menuChevron = document.getElementById('menu-chevron');

            if (menuToggle) {
                menuToggle.addEventListener('click', () => {
                    sidebarMenu.classList.toggle('hidden');
                    menuChevron.classList.toggle('mdi-chevron-down');
                    menuChevron.classList.toggle('mdi-chevron-up');
                });
            }

            // Close menu when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024) {
                    const isClickInsideMenu = sidebarMenu.contains(event.target);
                    const isClickOnToggle = menuToggle.contains(event.target);

                    if (!isClickInsideMenu && !isClickOnToggle && !sidebarMenu.classList.contains(
                        'hidden')) {
                        sidebarMenu.classList.add('hidden');
                        menuChevron.classList.add('mdi-chevron-down');
                        menuChevron.classList.remove('mdi-chevron-up');
                    }
                }
            });
        });
    </script>
@endpush
