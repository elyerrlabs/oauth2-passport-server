@extends('settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-routes text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Route Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ __('Control application routing and feature accessibility') }}</p>
            </div>

            <div
                class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="mdi mdi-security mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Security Notes') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-account text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Disable registration to restrict account creation') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-api text-yellow-500 dark:text-yellow-400 mr-2 mt-0.5"></i>
                        {{ __('API access should be limited to trusted users') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-developer-board text-blue-500 dark:text-blue-400 mr-2 mt-0.5"></i>
                        {{ __('Developer tools are for advanced users only') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Guest Routes Section -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                        <i class="mdi mdi-account-plus text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Guest Access') }}</h3>
                </div>

                <div class="space-y-4">
                    {{-- User Registration --}}
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <label
                                class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('User Registration') }}</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Allow visitors to create new accounts through the registration page') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[guest][register]"
                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                <option value="1" {{ config('routes.guest.register') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.guest.register') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 mt-4">
                    {{-- Landing page --}}
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <label
                                class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('Landing page') }}</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Allow visitors view the landing page') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[guest][landing]"
                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                <option value="1" {{ config('routes.guest.landing', true) ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.guest.landing', true) ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Dashboard Features Section -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                        <i class="mdi mdi-view-dashboard text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Dashboard Features') }}</h3>
                </div>

                <div class="space-y-4">
                    {{-- Documentation menu --}}
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <label
                                class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('Documentation Menu') }}</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Show or hide the documentation menu') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[documentation][index]"
                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                <option value="1"
                                    {{ config('routes.documentation.index', true) == true ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0"
                                    {{ config('routes.documentation.index', true) == false ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Developers Menu --}}
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <label
                                class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('Developers Menu') }}</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Show or hide the Developers menu in the user dashboard') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][developers]"
                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                <option value="1" {{ config('routes.users.developers') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.developers') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- API Menu --}}
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <label
                                class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('API Access Menu') }}</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Show or hide the API access menu for users') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][api]"
                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                <option value="1" {{ config('routes.users.api') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.api') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Clients Menu --}}
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <label
                                class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('Clients Section') }}</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Show or hide the Clients section in the user dashboard') }}</p>
                        </div>
                        <div class="ml-4">
                            <select name="routes[users][clients]"
                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                <option value="1" {{ config('routes.users.clients') ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ !config('routes.users.clients') ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Feature Dependencies Section -->
                <div
                    class="p-5 mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg mr-3">
                            <i class="mdi mdi-link text-yellow-500 dark:text-yellow-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Feature Dependencies') }}
                        </h3>
                    </div>

                    <div class="space-y-3 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-start">
                            <i class="mdi mdi-information-outline mr-2 mt-0.5 text-blue-500 dark:text-blue-400"></i>
                            <span>{{ __('The API menu requires the Developers menu to be enabled for full functionality') }}</span>
                        </div>
                        <div class="flex items-start">
                            <i class="mdi mdi-information-outline mr-2 mt-0.5 text-blue-500 dark:text-blue-400"></i>
                            <span>{{ __('Client management features depend on both Developers and API menus being enabled') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction routes -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                        <i class="mdi mdi-view-dashboard text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Transactions routes') }}</h3>
                </div>

                <div class="space-y-4 mb-5">

                    <div class="space-y-4 mb-5">
                        {{-- Plan routes for admin --}}
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex-1">
                                <label
                                    class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('Plan routes') }}</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Disable plans routes for admin') }}</p>
                            </div>
                            <div class="ml-4">
                                <select name="module[transaction][module][routes][plans_enabled]"
                                    class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                    <option value="1"
                                        {{ config('module.transaction.module.routes.plans_enabled', true) == true ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0"
                                        {{ config('module.transaction.module.routes.plans_enabled', true) == false ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- Subscription routes for users --}}
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex-1">
                                <label
                                    class="block text-sm font-medium text-gray-800 dark:text-white mb-1">{{ __('Subscription routes') }}</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Disable subscription routes for users') }}</p>
                            </div>
                            <div class="ml-4">
                                <select name="module[transaction][module][routes][subscriptions_enabled]"
                                    class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                    <option value="1"
                                        {{ config('module.transaction.module.routes.subscriptions_enabled', true) == true ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0"
                                        {{ config('module.transaction.module.routes.subscriptions_enabled', true) == false ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
