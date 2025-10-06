@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-shield-account text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Security Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure application security policies and protections') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                    <i class="mdi mdi-security mr-2 text-blue-600"></i>
                    {{ __('Security Recommendations') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-600">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check text-green-500 mr-2 mt-0.5"></i>
                        {{ __('Enable CSP policies for XSS protection') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-robot-off text-yellow-500 mr-2 mt-0.5"></i>
                        {{ __('Use CAPTCHA to prevent automated attacks') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-speedometer text-blue-500 mr-2 mt-0.5"></i>
                        {{ __('Configure rate limits to prevent abuse') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Security Policies Section -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-policy text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Security Policies') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- CSP Policies --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    {{ __('Content Security Policy (CSP)') }}
                                </label>
                                <p class="text-sm text-gray-600">
                                    {{ __('Enable Content Security Policy headers for XSS protection') }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <select name="system[csp_enabled]"
                                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="1" {{ config('system.csp_enabled') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0" {{ !config('system.csp_enabled') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Disable User Creation by Command --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    {{ __('Disable User Creation by Command') }}
                                </label>
                                <p class="text-sm text-gray-600">
                                    {{ __('Prevent user creation through artisan commands') }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <select name="system[disable_create_user_by_command]"
                                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="1"
                                        {{ config('system.disable_create_user_by_command') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0"
                                        {{ !config('system.disable_create_user_by_command') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Age Verification Section -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-account-clock text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Age Verification') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Enable Age Verification --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    {{ __('Enable Age Verification') }}
                                </label>
                                <p class="text-sm text-gray-600">
                                    {{ __('Require birth date during user registration') }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <select name="system[birthday][active]"
                                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="1" {{ config('system.birthday.active') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0" {{ !config('system.birthday.active') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Minimum Age Limit --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('Minimum Age Requirement') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="system[birthday][limit]"
                                class="w-full pl-16 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                value="{{ config('system.birthday.limit') }}" min="13" max="120"
                                placeholder="18">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">years</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-gray-500">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Minimum age required for user registration') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Demo Mode Section -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-100 rounded-lg mr-3">
                        <i class="mdi mdi-monitor-eye text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Demo Mode') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Enable Demo Mode --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    {{ __('Enable Demo Mode') }}
                                </label>
                                <p class="text-sm text-gray-600">
                                    {{ __('Allow users to log in with demo credentials') }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <select name="system[demo][enabled]"
                                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="1" {{ config('system.demo.enabled') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}
                                    </option>
                                    <option value="0" {{ !config('system.demo.enabled') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Demo Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('Demo Email') }}
                        </label>
                        <input type="email" name="system[demo][email]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            value="{{ config('system.demo.email') }}" placeholder="demo@example.com">
                    </div>

                    {{-- Demo Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('Demo Password') }}
                        </label>
                        <input type="password" name="system[demo][password]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            value="{{ config('system.demo.password') }}" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <!-- CAPTCHA Configuration -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-100 rounded-lg mr-3">
                        <i class="mdi mdi-robot text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('CAPTCHA Protection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Enable CAPTCHA --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    {{ __('Enable CAPTCHA') }}
                                </label>
                                <p class="text-sm text-gray-600">
                                    {{ __('Protect forms from automated bots and spam') }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <select name="services[captcha][enabled]"
                                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="1" {{ config('services.captcha.enabled') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="0" {{ !config('services.captcha.enabled') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- CAPTCHA Driver --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('CAPTCHA Provider') }}
                        </label>
                        <select id="captcha" name="services[captcha][driver]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300">
                            <option value="turnstile"
                                {{ config('services.captcha.driver') == 'turnstile' ? 'selected' : '' }}>
                                {{ __('Cloudflare Turnstile') }}
                            </option>
                            <option value="hcaptcha"
                                {{ config('services.captcha.driver') == 'hcaptcha' ? 'selected' : '' }}>
                                {{ __('hCaptcha') }}
                            </option>
                        </select>
                        <small class="block mt-2 text-sm text-gray-500">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Select your CAPTCHA service provider') }}
                        </small>
                    </div>
                </div>

                <!-- CAPTCHA Provider Configurations -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    {{-- Cloudflare Turnstile --}}
                    <div id="turnstile"
                        class="captcha-provider p-5 bg-gray-50 rounded-xl shadow-sm border border-gray-200 {{ config('services.captcha.driver') != 'turnstile' ? 'hidden' : '' }}">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="mdi mdi-cloud mr-2 text-blue-500"></i>
                            {{ __('Cloudflare Turnstile Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-800 mb-2">
                                    {{ __('Endpoint') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="services[captcha][providers][turnstile][api]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                        value="{{ config('services.captcha.providers.turnstile.api') }}"
                                        placeholder="https://challenges.cloudflare.com/turnstile/v0/siteverify">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-web text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-800 mb-2">
                                    {{ __('Site Key') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="services[captcha][providers][turnstile][sitekey]"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                        value="{{ config('services.captcha.providers.turnstile.sitekey') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-key text-gray-400"></i>
                                    </div>
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                        <i class="mdi mdi-eye-outline text-gray-400"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-800 mb-2">
                                    {{ __('Secret Key') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="services[captcha][providers][turnstile][secret]"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                        value="{{ config('services.captcha.providers.turnstile.secret') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-key-variant text-gray-400"></i>
                                    </div>
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                        <i class="mdi mdi-eye-outline text-gray-400"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- hCaptcha --}}
                    <div id="hcaptcha"
                        class="captcha-provider p-5 bg-gray-50 rounded-xl shadow-sm border border-gray-200 {{ config('services.captcha.driver') != 'hcaptcha' ? 'hidden' : '' }}">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="mdi mdi-shield-account mr-2 text-yellow-500"></i>
                            {{ __('hCaptcha Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-800 mb-2">
                                    {{ __('Endpoint') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="services[captcha][providers][hcaptcha][api]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                        value="{{ config('services.captcha.providers.hcaptcha.api') }}"
                                        placeholder="https://hcaptcha.com/siteverify">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-web text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-800 mb-2">
                                    {{ __('Site Key') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="services[captcha][providers][hcaptcha][sitekey]"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                        value="{{ config('services.captcha.providers.hcaptcha.sitekey') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-key text-gray-400"></i>
                                    </div>
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                        <i class="mdi mdi-eye-outline text-gray-400"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-800 mb-2">
                                    {{ __('Secret Key') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="services[captcha][providers][hcaptcha][secret]"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                        value="{{ config('services.captcha.providers.hcaptcha.secret') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-key-variant text-gray-400"></i>
                                    </div>
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                        <i class="mdi mdi-eye-outline text-gray-400"></i>
                                    </button>
                                </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            const captchaSelect = document.getElementById("captcha");
            const captchaProviders = document.querySelectorAll(".captcha-provider");

            function showCaptchaProvider(selectedProvider) {
                captchaProviders.forEach(provider => {
                    provider.classList.add('hidden');
                });

                const activeProvider = document.getElementById(selectedProvider);
                if (activeProvider) {
                    activeProvider.classList.remove('hidden');
                }
            }

            // Initialize with current value
            showCaptchaProvider(captchaSelect.value);

            // Change on selection
            captchaSelect.addEventListener("change", function(e) {
                showCaptchaProvider(e.target.value);
            });

            // Toggle password visibility
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'password') {
                        icon.classList.remove('mdi-eye-off-outline');
                        icon.classList.add('mdi-eye-outline');
                    } else {
                        icon.classList.remove('mdi-eye-outline');
                        icon.classList.add('mdi-eye-off-outline');
                    }
                });
            });

            // Enable/disable age limit based on select
            const ageVerification = document.querySelector('select[name="system[birthday][active]"]');
            const ageLimit = document.querySelector('input[name="system[birthday][limit]"]');

            if (ageVerification && ageLimit) {
                function toggleAgeLimit() {
                    ageLimit.disabled = ageVerification.value === '0';
                }

                ageVerification.addEventListener('change', toggleAgeLimit);
                toggleAgeLimit(); // Initialize
            }

            // Enable/disable captcha fields based on select
            const captchaEnabled = document.querySelector('select[name="services[captcha][enabled]"]');
            const captchaFields = document.querySelectorAll('#captcha, .captcha-provider');

            if (captchaEnabled && captchaFields.length) {
                function toggleCaptchaFields() {
                    const isEnabled = captchaEnabled.value === '1';
                    captchaFields.forEach(field => {
                        if (field.tagName === 'SELECT') {
                            field.disabled = !isEnabled;
                        } else {
                            field.querySelectorAll('input').forEach(input => {
                                input.disabled = !isEnabled;
                            });
                        }
                    });
                }

                captchaEnabled.addEventListener('change', toggleCaptchaFields);
                toggleCaptchaFields(); // Initialize
            }
        });
    </script>
@endpush
