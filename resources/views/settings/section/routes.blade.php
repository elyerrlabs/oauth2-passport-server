@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-routes text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Route Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ __('Control application routing and feature accessibility') }}</p>
            </div>

            <div class="mt-4 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                    <i class="mdi mdi-security mr-2 text-indigo-600"></i>
                    {{ __('Security Notes') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-account text-green-500 mr-2 mt-0.5"></i>
                        {{ __('Disable registration to restrict account creation') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-api text-yellow-500 mr-2 mt-0.5"></i>
                        {{ __('API access should be limited to trusted users') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-developer-board text-blue-500 mr-2 mt-0.5"></i>
                        {{ __('Developer tools are for advanced users only') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Guest Routes Section -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-lg mr-3">
                        <i class="mdi mdi-account-plus text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('Guest Access') }}</h3>
                </div>

                <div class="space-y-4">
                    {{-- User Registration --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <label
                                class="block text-sm font-medium text-gray-800 mb-1">{{ __('User Registration') }}</label>
                            <p class="text-sm text-gray-500">
                                {{ __('Allow visitors to create new accounts through the registration page') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[guest][register]"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-lg mr-3">
                        <i class="mdi mdi-view-dashboard text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('Dashboard Features') }}</h3>
                </div>

                <div class="space-y-4">
                    {{-- Developers Menu --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-800 mb-1">{{ __('Developers Menu') }}</label>
                            <p class="text-sm text-gray-500">
                                {{ __('Show or hide the Developers menu in the user dashboard') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][developers]"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="1" {{ config('routes.users.developers') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.developers') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- API Menu --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-800 mb-1">{{ __('API Access Menu') }}</label>
                            <p class="text-sm text-gray-500">{{ __('Show or hide the API access menu for users') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][api]"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="1" {{ config('routes.users.api') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.api') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Clients Menu --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-800 mb-1">{{ __('Clients Section') }}</label>
                            <p class="text-sm text-gray-500">
                                {{ __('Show or hide the Clients section in the user dashboard') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][clients]"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-100 rounded-lg mr-3">
                        <i class="mdi mdi-link text-yellow-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('Feature Dependencies') }}</h3>
                </div>

                <div class="space-y-3 text-sm text-gray-500">
                    <div class="flex items-start">
                        <i class="mdi mdi-information-outline mr-2 mt-0.5 text-blue-500"></i>
                        <span>{{ __('The API menu requires the Developers menu to be enabled for full functionality') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-information-outline mr-2 mt-0.5 text-blue-500"></i>
                        <span>{{ __('Client management features depend on both Developers and API menus being enabled') }}</span>
                    </div>
                </div>
            </div>

            <!-- Current Configuration Status -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-checkbox-marked-circle-outline text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('Current Configuration') }}</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.guest.register') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ config('routes.guest.register') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-gray-500">{{ __('User Registration') }}</span>
                    </div>

                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.users.developers') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ config('routes.users.developers') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-gray-500">{{ __('Developers Menu') }}</span>
                    </div>

                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.users.api') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ config('routes.users.api') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-gray-500">{{ __('API Menu') }}</span>
                    </div>

                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('routes.users.clients') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ config('routes.users.clients') ? __('Enabled') : __('Disabled') }}
                        </span>
                        <span class="ml-2 text-sm text-gray-500">{{ __('Clients Section') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
