@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div
                class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-lock-outline text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Session Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure session and cookie parameters for your application') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-[var(--color-bg-primary)] rounded-xl shadow-sm border border-[var(--color-border)]">
                <h3 class="text-sm font-semibold text-[var(--color-text-primary)] flex items-center">
                    <i class="mdi mdi-security mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Security Recommendations') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-[var(--color-text-secondary)]">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Enable HTTPS only in production') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Use HTTP only cookies for security') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Set appropriate session lifetime') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Session Settings Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-[var(--color-text-primary)] flex items-center mb-4">
                    <i class="mdi mdi-timer-outline mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Session Management') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Session Lifetime --}}
                    <div
                        class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                        <div class="flex items-center mb-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                                <i class="mdi mdi-timer-sand-empty text-[var(--color-primary)]"></i>
                            </div>
                            <label class="block text-sm font-semibold text-[var(--color-text-primary)]">
                                {{ __('Session Lifetime') }}
                            </label>
                        </div>
                        <div class="relative">
                            <input type="number" name="session[lifetime]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('session.lifetime') }}" min="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-[var(--color-text-secondary)]">min</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Specify minutes until session expires due to inactivity') }}
                        </small>
                    </div>

                    {{-- Expire on close --}}
                    <div
                        class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                        <div class="flex items-center mb-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                                <i class="mdi mdi-window-close text-[var(--color-primary)]"></i>
                            </div>
                            <label class="block text-sm font-semibold text-[var(--color-text-primary)]">
                                {{ __('Expire on Close') }}
                            </label>
                        </div>
                        <select name="session[expire_on_close]"
                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                            <option value="1" {{ config('session.expire_on_close') ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="0" {{ !config('session.expire_on_close') ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Immediately expire session when browser closes') }}
                        </small>
                    </div>

                    {{-- Session Encryption --}}
                    <div
                        class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                        <div class="flex items-center mb-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                                <i class="mdi mdi-encryption text-[var(--color-primary)]"></i>
                            </div>
                            <label class="block text-sm font-semibold text-[var(--color-text-primary)]">
                                {{ __('Session Encryption') }}
                            </label>
                        </div>
                        <select name="session[encrypt]"
                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                            <option value="1" {{ config('session.encrypt') ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="0" {{ !config('session.encrypt') ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Encrypt all session data before storage') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Cookie Settings Section -->
            <div>
                <h3 class="text-lg font-semibold text-[var(--color-text-primary)] flex items-center mb-4">
                    <i class="mdi mdi-cookie mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Cookie Settings') }}
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    {{-- Cookie Names --}}
                    <div
                        class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                                <i class="mdi mdi-rename-box text-[var(--color-primary)]"></i>
                            </div>
                            <h4 class="text-md font-semibold text-[var(--color-text-primary)]">
                                {{ __('Cookie Names') }}
                            </h4>
                        </div>

                        <div class="space-y-4">
                            {{-- Session cookie name --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Session Cookie Name') }}
                                </label>
                                <input type="text" name="session[cookie]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('session.cookie') }}">
                                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ __('Cookie name used to identify session instances') }}
                                </small>
                            </div>

                            {{-- CSRF cookie name --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('CSRF Token Cookie Name') }}
                                </label>
                                <input type="text" name="session[xcsrf-token]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('session.xcsrf-token') }}">
                                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ __('Cookie name used for CSRF protection tokens') }}
                                </small>
                            </div>

                            {{-- Laravel Passport cookie name --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Laravel Passport Cookie Name') }}
                                </label>
                                <input type="text" name="system[cookie_name]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    placeholder="{{ __('Enter passport cookie name') }}"
                                    value="{{ config('system.cookie_name') }}">
                                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ __('Cookie name used by Laravel Passport for authentication') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Cookie Security --}}
                    <div
                        class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                                <i class="mdi mdi-security text-[var(--color-primary)]"></i>
                            </div>
                            <h4 class="text-md font-semibold text-[var(--color-text-primary)]">
                                {{ __('Cookie Security') }}
                            </h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- HTTPS Only --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('HTTPS Only') }}
                                </label>
                                <select name="session[secure]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                    <option value="1" {{ config('session.secure') ? 'selected' : '' }}>
                                        {{ __('Yes') }}
                                    </option>
                                    <option value="0" {{ !config('session.secure') ? 'selected' : '' }}>
                                        {{ __('No') }}
                                    </option>
                                </select>
                                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ __('Only send cookies over HTTPS connections') }}
                                </small>
                            </div>

                            {{-- HTTP Only --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('HTTP Only') }}
                                </label>
                                <select name="session[http_only]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                    <option value="1" {{ config('session.http_only') ? 'selected' : '' }}>
                                        {{ __('Yes') }}
                                    </option>
                                    <option value="0" {{ !config('session.http_only') ? 'selected' : '' }}>
                                        {{ __('No') }}
                                    </option>
                                </select>
                                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ __('Prevent JavaScript access to cookies') }}
                                </small>
                            </div>

                            {{-- Partitioned Cookies --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Partitioned Cookies') }}
                                </label>
                                <select name="session[partitioned]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                    <option value="1" {{ config('session.partitioned') ? 'selected' : '' }}>
                                        {{ __('Yes') }}
                                    </option>
                                    <option value="0" {{ !config('session.partitioned') ? 'selected' : '' }}>
                                        {{ __('No') }}
                                    </option>
                                </select>
                                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ __('Tie cookie to top-level site for cross-site context') }}
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
        document.addEventListener('DOMContentLoaded', function() {
            // Add validation for session lifetime
            const lifetimeInput = document.querySelector('input[name="session[lifetime]"]');
            if (lifetimeInput) {
                lifetimeInput.addEventListener('change', function() {
                    if (this.value < 1) {
                        this.value = 120;
                        showToast('{{ __('Session lifetime must be at least 1 minute') }}', 'warning');
                    }
                });
            }

            // Function to show toast notifications
            function showToast(message, type = 'info') {
                // You can implement a toast notification system here
                console.log(`${type}: ${message}`);
            }
        });
    </script>
@endpush