@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div
                class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-routes text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Route Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Control application routing and feature accessibility') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-[var(--color-bg-primary)] rounded-xl shadow-sm border border-[var(--color-border)]">
                <h3 class="text-sm font-semibold text-[var(--color-text-primary)] flex items-center">
                    <i class="mdi mdi-security mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Security Notes') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-[var(--color-text-secondary)]">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-account text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Disable registration to restrict account creation') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-api text-[var(--color-warning)] mr-2 mt-0.5"></i>
                        {{ __('API access should be limited to trusted users') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-developer-board text-[var(--color-info)] mr-2 mt-0.5"></i>
                        {{ __('Developer tools are for advanced users only') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Guest Routes Section -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-account-plus text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Guest Access') }}
                    </h3>
                </div>

                <div class="space-y-4">
                    {{-- User Registration --}}
                    <div
                        class="flex items-center justify-between p-4 bg-[var(--color-bg-secondary)] rounded-lg border border-[var(--color-border)]">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-1">
                                {{ __('User Registration') }}
                            </label>
                            <p class="text-sm text-[var(--color-text-secondary)]">
                                {{ __('Allow visitors to create new accounts through the registration page') }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[guest][register]"
                                class="w-32 px-3 py-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-bg-primary)] text-[var(--color-text-primary)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                <option value="1" {{ config('routes.guest.register') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.guest.register') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Dashboard Features Section -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-view-dashboard text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Dashboard Features') }}
                    </h3>
                </div>

                <div class="space-y-4">
                    {{-- Developers Menu --}}
                    <div
                        class="flex items-center justify-between p-4 bg-[var(--color-bg-secondary)] rounded-lg border border-[var(--color-border)]">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-1">
                                {{ __('Developers Menu') }}
                            </label>
                            <p class="text-sm text-[var(--color-text-secondary)]">
                                {{ __('Show or hide the Developers menu in the user dashboard') }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][developers]"
                                class="w-32 px-3 py-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-bg-primary)] text-[var(--color-text-primary)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                <option value="1" {{ config('routes.users.developers') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.developers') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- API Menu --}}
                    <div
                        class="flex items-center justify-between p-4 bg-[var(--color-bg-secondary)] rounded-lg border border-[var(--color-border)]">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-1">
                                {{ __('API Access Menu') }}
                            </label>
                            <p class="text-sm text-[var(--color-text-secondary)]">
                                {{ __('Show or hide the API access menu for users') }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][api]"
                                class="w-32 px-3 py-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-bg-primary)] text-[var(--color-text-primary)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                <option value="1" {{ config('routes.users.api') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.api') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Clients Menu --}}
                    <div
                        class="flex items-center justify-between p-4 bg-[var(--color-bg-secondary)] rounded-lg border border-[var(--color-border)]">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-1">
                                {{ __('Clients Section') }}
                            </label>
                            <p class="text-sm text-[var(--color-text-secondary)]">
                                {{ __('Show or hide the Clients section in the user dashboard') }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][clients]"
                                class="w-32 px-3 py-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-bg-primary)] text-[var(--color-text-primary)] focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                <option value="1" {{ config('routes.users.clients') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.clients') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Dependencies Section -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-warning-light)] rounded-lg mr-3">
                        <i class="mdi mdi-link text-[var(--color-warning)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Feature Dependencies') }}
                    </h3>
                </div>

                <div class="space-y-3 text-sm text-[var(--color-text-secondary)]">
                    <div class="flex items-start">
                        <i class="mdi mdi-information-outline mr-2 mt-0.5 text-[var(--color-info)]"></i>
                        <span>{{ __('The API menu requires the Developers menu to be enabled for full functionality') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-information-outline mr-2 mt-0.5 text-[var(--color-info)]"></i>
                        <span>{{ __('Client management features depend on both Developers and API menus being enabled') }}</span>
                    </div>
                </div>
            </div>

            <!-- Current Configuration Status -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-info-light)] rounded-lg mr-3">
                        <i class="mdi mdi-checkbox-marked-circle-outline text-[var(--color-info)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Current Configuration') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.guest.register') ? 'bg-[var(--color-success)] text-white' : 'bg-[var(--color-danger)] text-white' }}">
                            {{ config('routes.guest.register') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-[var(--color-text-secondary)]">{{ __('User Registration') }}</span>
                    </div>

                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.users.developers') ? 'bg-[var(--color-success)] text-white' : 'bg-[var(--color-danger)] text-white' }}">
                            {{ config('routes.users.developers') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-[var(--color-text-secondary)]">{{ __('Developers Menu') }}</span>
                    </div>

                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.users.api') ? 'bg-[var(--color-success)] text-white' : 'bg-[var(--color-danger)] text-white' }}">
                            {{ config('routes.users.api') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-[var(--color-text-secondary)]">{{ __('API Menu') }}</span>
                    </div>

                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.users.clients') ? 'bg-[var(--color-success)] text-white' : 'bg-[var(--color-danger)] text-white' }}">
                            {{ config('routes.users.clients') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-[var(--color-text-secondary)]">{{ __('Clients Section') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
