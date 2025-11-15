@extends('settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">

        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white p-6 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-indigo-700 dark:bg-indigo-800 rounded-xl mb-4">
                    <i class="mdi mdi-credit-card-outline text-2xl"></i>
                </div>
                <h2 class="text-xl font-semibold">{{ __('Payment Settings') }}</h2>
                <p class="text-sm text-indigo-100 mt-2">
                    {{ __('Configure payment gateways and billing preferences') }}
                </p>
            </div>

            <div
                class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="mdi mdi-information-outline mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Best Practices') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Test payment methods in sandbox mode first') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Enable automatic renewals for better user experience') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Set appropriate grace periods for payment processing') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full lg:w-3/4 space-y-6">

            <!-- Renewal Settings -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                        <i class="mdi mdi-autorenew text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('Renewal Settings') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Enable Renewals -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Enable Renewals') }}</label>
                        <select name="billing[renew][enable]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            <option value="1" {{ config('billing.renew.enable') ? 'selected' : '' }}>
                                {{ __('Yes') }}</option>
                            <option value="0" {{ !config('billing.renew.enable') ? 'selected' : '' }}>
                                {{ __('No') }}</option>
                        </select>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Enable automatic renewal system (excludes manual payments)') }}
                        </small>
                    </div>

                    <!-- Hours Before Expiry -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Hours Before Expiry') }}</label>
                        <div class="relative">
                            <input type="number" name="billing[renew][hours_before]"
                                value="{{ config('billing.renew.hours_before') }}" min="1"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-clock-outline text-gray-400 dark:text-gray-500"></i>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Hours before expiration to execute renewal process') }}
                        </small>
                    </div>

                    <!-- Renewal Bonus -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Renewal Bonus') }}</label>
                        <select name="billing[renew][bonus_enabled]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            <option value="1" {{ config('billing.renew.bonus_enabled') ? 'selected' : '' }}>
                                {{ __('Yes') }}</option>
                            <option value="0" {{ !config('billing.renew.bonus_enabled') ? 'selected' : '' }}>
                                {{ __('No') }}</option>
                        </select>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Apply plan bonus duration during renewals') }}
                        </small>
                    </div>

                    <!-- Grace Period -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Grace Period') }}</label>
                        <div class="relative">
                            <input type="number" name="billing[renew][grace_period_days]"
                                value="{{ config('billing.renew.grace_period_days') }}" min="0"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 dark:text-gray-500">days</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Days after expiration when renewal is still possible') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="mdi mdi-credit-card-multiple mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Payment Methods') }}
                </h3>

                <div class="space-y-6">

                    <!-- Stripe -->
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                                    <i
                                        class="mdi mdi-{{ config('billing.methods.stripe.icon') }} text-indigo-600 dark:text-indigo-400 text-xl"></i>
                                </div>
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white">{{ __('Stripe Settings') }}
                                </h4>
                            </div>
                            <span
                                class="text-xs px-2 py-1 rounded-full {{ config('billing.methods.stripe.enable') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ config('billing.methods.stripe.enable') ? __('Enabled') : __('Disabled') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Display Name -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Display Name') }}</label>
                                <input type="text" name="billing[methods][stripe][name]"
                                    value="{{ config('billing.methods.stripe.name') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            </div>

                            <!-- Icon -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Icon Class') }}</label>
                                <input type="text" name="billing[methods][stripe][icon]"
                                    value="{{ config('billing.methods.stripe.icon') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            </div>

                            <!-- Status -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                                <select name="billing[methods][stripe][enable]"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                                    <option value="1" {{ config('billing.methods.stripe.enable') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0"
                                        {{ !config('billing.methods.stripe.enable') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <h5 class="text-sm font-semibold text-gray-800 dark:text-white mb-3 flex items-center">
                                <i class="mdi mdi-key-outline mr-2 text-yellow-500 dark:text-yellow-400"></i>
                                {{ __('API Credentials') }}
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Secret Key -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Secret Key') }}</label>
                                    <input type="password" name="services[stripe][secret]"
                                        value="{{ config('services.stripe.secret') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300"
                                        placeholder="sk_live_...">
                                </div>

                                <!-- Public Key -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Public Key') }}</label>
                                    <input type="password" name="services[stripe][key]"
                                        value="{{ config('services.stripe.key') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300"
                                        placeholder="pk_live_...">
                                </div>

                                <!-- Webhook Secret -->
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Webhook Secret') }}</label>
                                    <input type="password" name="services[stripe][webhook_secret]"
                                        value="{{ config('services.stripe.webhook_secret') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300"
                                        placeholder="whsec_...">
                                    <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        <i class="mdi mdi-information-outline mr-1"></i>
                                        {{ __('Required for processing Stripe webhook events') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Offline -->
                    <div
                        class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                                    <i
                                        class="mdi mdi-{{ config('billing.methods.offline.icon') }} text-indigo-600 dark:text-indigo-400 text-xl"></i>
                                </div>
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white">
                                    {{ __('Offline Payment Settings') }}</h4>
                            </div>
                            <span
                                class="text-xs px-2 py-1 rounded-full {{ config('billing.methods.offline.enable') ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ config('billing.methods.offline.enable') ? __('Enabled') : __('Disabled') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Display Name') }}</label>
                                <input type="text" name="billing[methods][offline][name]"
                                    value="{{ config('billing.methods.offline.name') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Icon Class') }}</label>
                                <input type="text" name="billing[methods][offline][icon]"
                                    value="{{ config('billing.methods.offline.icon') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                                <select name="billing[methods][offline][enable]"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors duration-300">
                                    <option value="1"
                                        {{ config('billing.methods.offline.enable') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0"
                                        {{ !config('billing.methods.offline.enable') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                                <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <i class="mdi mdi-information-outline mr-1"></i>
                                    {{ __('Allow manual/offline payment processing') }}
                                </small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 0) this.value = 0;
                });
            });
        });
    </script>
@endpush
