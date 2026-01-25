@extends('settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-email-outline text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Email Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ __('Configure email services and delivery settings') }}</p>
            </div>

            <div
                class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="mdi mdi-lightbulb-outline mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Quick Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Test your email configuration before saving') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Use environment variables for sensitive credentials') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Set appropriate expiration times for security codes') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Mailer Configuration Section -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                        <i class="mdi mdi-email-send text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Mailer Configuration') }}</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Default Mailer --}}
                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('Default Mailer') }}</label>
                        <select name="mail[default]" id="mail_selector"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300">
                            @foreach (['smtp', 'ses', 'mailgun', 'postmark', 'sendmail', 'log', 'array', 'failover'] as $driver)
                                <option value="{{ $driver }}"
                                    {{ config('mail.default') == $driver ? 'selected' : '' }}>
                                    {{ ucfirst($driver) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Select the default email delivery service') }}
                        </small>
                    </div>

                    {{-- From Address --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('From Email') }}</label>
                        <div class="relative">
                            <input type="email" name="mail[from][address]" value="{{ config('mail.from.address') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300"
                                placeholder="noreply@example.com">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-email-outline text-gray-400 dark:text-gray-500"></i>
                            </div>
                        </div>
                    </div>

                    {{-- From Name --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('From Name') }}</label>
                        <div class="relative">
                            <input type="text" name="mail[from][name]" value="{{ config('mail.from.name') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300"
                                placeholder="{{ __('Your Company Name') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-account-outline text-gray-400 dark:text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mailer Specific Configurations -->
                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                    {{-- SMTP --}}
                    <div id="smtp"
                        class="mail-config p-5 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 hidden">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <i class="mdi mdi-server mr-2 text-indigo-600 dark:text-indigo-400"></i>
                            {{ __('SMTP Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['host', 'port', 'encryption', 'username', 'password', 'timeout', 'local_domain'] as $key)
                                <div class="{{ in_array($key, ['timeout', 'local_domain']) ? 'md:col-span-2' : '' }}">
                                    <label
                                        class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                    <div class="relative">
                                        <input type="{{ $key === 'password' ? 'password' : 'text' }}"
                                            name="mail[mailers][smtp][{{ $key }}]"
                                            value="{{ config('mail.mailers.smtp.' . $key) }}"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300"
                                            placeholder="{{ $key === 'port' ? '587' : '' }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'password' ? 'key-variant' : ($key === 'username' ? 'account' : ($key === 'host' ? 'server' : ($key === 'port' ? 'numeric' : 'lock'))) }} text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mailgun --}}
                    <div id="mailgun"
                        class="mail-config p-5 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 hidden">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <i class="mdi mdi-email-fast-outline mr-2 text-indigo-600 dark:text-indigo-400"></i>
                            {{ __('Mailgun Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['domain', 'secret', 'endpoint', 'scheme'] as $key)
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ ucfirst($key) }}</label>
                                    <div class="relative">
                                        <input type="text" name="services[mailgun][{{ $key }}]"
                                            value="{{ config('services.mailgun.' . $key, '') }}"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'secret' ? 'key-variant' : ($key === 'domain' ? 'web' : 'settings') }} text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- SES --}}
                    <div id="ses"
                        class="mail-config p-5 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 hidden">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <i class="mdi mdi-amazon mr-2 text-indigo-600 dark:text-indigo-400"></i>
                            {{ __('Amazon SES Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['key', 'secret', 'region'] as $key)
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ ucfirst($key) }}</label>
                                    <div class="relative">
                                        <input type="{{ $key === 'secret' ? 'password' : 'text' }}"
                                            name="services[ses][{{ $key }}]"
                                            value="{{ config('services.ses.' . $key, '') }}"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'secret' ? 'key-variant' : ($key === 'key' ? 'key' : 'earth') }} text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Postmark --}}
                    <div id="postmark"
                        class="mail-config p-5 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 hidden">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <i class="mdi mdi-email-check-outline mr-2 text-indigo-600 dark:text-indigo-400"></i>
                            {{ __('Postmark Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('Token') }}</label>
                                <div class="relative">
                                    <input type="password" name="services[postmark][token]"
                                        value="{{ config('services.postmark.token', '') }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-key-variant text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expiration Settings -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                        <i class="mdi mdi-timer-sand text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Expiration Settings') }}</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Account Verification Time --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('Account Verification Time') }}</label>
                        <div class="relative">
                            <input type="number" name="system[verify_account_time]"
                                value="{{ config('system.verify_account_time', 5) }}"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300"
                                min="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 dark:text-gray-500">min</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Time until account verification link expires') }}
                        </small>
                    </div> 

                    {{-- Password Reset Expiration --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('Password Reset Expiration') }}</label>
                        <div class="relative">
                            <input type="number" name="auth[passwords][users][expire]"
                                value="{{ config('auth.passwords.users.expire', 10) }}"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300"
                                min="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 dark:text-gray-500">min</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Time until password reset link expires') }}
                        </small>
                    </div>

                    {{-- Password Reset Throttle --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('Password Reset Throttle') }}</label>
                        <div class="relative">
                            <input type="number" name="auth[passwords][users][throttle]"
                                value="{{ config('auth.passwords.users.throttle', 10) }}"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-colors duration-300"
                                min="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 dark:text-gray-500">min</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Minimum time between password reset requests') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('mail_selector');
            const configs = document.querySelectorAll('.mail-config');

            function updateVisibility() {
                configs.forEach(config => config.classList.add('hidden'));
                const selectedMailer = document.getElementById(selector.value);
                if (selectedMailer) selectedMailer.classList.remove('hidden');
            }

            selector.addEventListener('change', updateVisibility);
            updateVisibility();

            const timeInputs = document.querySelectorAll('input[type="number"]');
            timeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 1) {
                        this.value = 1;
                        showToast('{{ __('Time must be at least 1 minute') }}', 'warning');
                    }
                });
            });

            function showToast(message, type = 'info') {
                $notify.error(`${type}: ${message}`);
            }
        });
    </script>
@endpush
