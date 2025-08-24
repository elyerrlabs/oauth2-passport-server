@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div
                class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-credit-card-outline text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Payment Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure payment gateways and billing preferences') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-[var(--color-bg-primary)] rounded-xl shadow-sm border border-[var(--color-border)]">
                <h3 class="text-sm font-semibold text-[var(--color-text-primary)] flex items-center">
                    <i class="mdi mdi-information-outline mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Best Practices') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-[var(--color-text-secondary)]">
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Test payment methods in sandbox mode first') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Enable automatic renewals for better user experience') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Set appropriate grace periods for payment processing') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Renewal Settings Section -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-autorenew text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Renewal Settings') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Enable Renewals --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Enable Renewals') }}
                        </label>
                        <select name="billing[renew][enable]"
                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                            <option value="1" {{ config('billing.renew.enable') ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="0" {{ !config('billing.renew.enable') ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Enable automatic renewal system (excludes manual payments)') }}
                        </small>
                    </div>

                    {{-- Hours Before Expiry --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Hours Before Expiry') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="billing[renew][hours_before]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('billing.renew.hours_before') }}" min="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-clock-outline text-[var(--color-text-secondary)]"></i>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Hours before expiration to execute renewal process') }}
                        </small>
                    </div>

                    {{-- Enable Bonus on Renewal --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Renewal Bonus') }}
                        </label>
                        <select name="billing[renew][bonus_enabled]"
                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                            <option value="1" {{ config('billing.renew.bonus_enabled') ? 'selected' : '' }}>
                                {{ __('Yes') }}
                            </option>
                            <option value="0" {{ !config('billing.renew.bonus_enabled') ? 'selected' : '' }}>
                                {{ __('No') }}
                            </option>
                        </select>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Apply plan bonus duration during renewals') }}
                        </small>
                    </div>

                    {{-- Grace Period --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Grace Period') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="billing[renew][grace_period_days]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('billing.renew.grace_period_days') }}" min="0">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-[var(--color-text-secondary)]">days</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Days after expiration when renewal is still possible') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Section -->
            <div>
                <h3 class="text-lg font-semibold text-[var(--color-text-primary)] flex items-center mb-4">
                    <i class="mdi mdi-credit-card-multiple mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Payment Methods') }}
                </h3>

                <div class="space-y-6">
                    <!-- Stripe Payment Method -->
                    <div
                        class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                                    <i
                                        class="mdi mdi-{{ config('billing.methods.stripe.icon') }} text-[var(--color-primary)] text-xl"></i>
                                </div>
                                <h4 class="text-md font-semibold text-[var(--color-text-primary)]">
                                    {{ __('Stripe Settings') }}
                                </h4>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="text-xs px-2 py-1 rounded-full {{ config('billing.methods.stripe.enable') ? 'bg-[var(--color-success)] text-white' : 'bg-[var(--color-danger)] text-white' }}">
                                    {{ config('billing.methods.stripe.enable') ? __('Enabled') : __('Disabled') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Display Name --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Display Name') }}
                                </label>
                                <input type="text" name="billing[methods][stripe][name]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('billing.methods.stripe.name') }}">
                            </div>

                            {{-- Icon --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Icon Class') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="billing[methods][stripe][icon]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                        value="{{ config('billing.methods.stripe.icon') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-iconify text-[var(--color-text-secondary)]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Enable Stripe --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Status') }}
                                </label>
                                <select name="billing[methods][stripe][enable]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                    <option value="1" {{ config('billing.methods.stripe.enable') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}
                                    </option>
                                    <option value="0"
                                        {{ !config('billing.methods.stripe.enable') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-[var(--color-border)]">
                            <h5 class="text-sm font-semibold text-[var(--color-text-primary)] mb-3 flex items-center">
                                <i class="mdi mdi-key-outline mr-2 text-[var(--color-warning)]"></i>
                                {{ __('API Credentials') }}
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Secret Key --}}
                                <div>
                                    <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                        {{ __('Secret Key') }}
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="services[stripe][secret]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('services.stripe.secret') }}" placeholder="sk_live_...">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-key-variant text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- Public Key --}}
                                <div>
                                    <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                        {{ __('Public Key') }}
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="services[stripe][key]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('services.stripe.key') }}" placeholder="pk_live_...">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-key-outline text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- Webhook Secret --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                        {{ __('Webhook Secret') }}
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="services[stripe][webhook_secret]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('services.stripe.webhook_secret') }}"
                                            placeholder="whsec_...">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-webhook text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                    <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                                        <i class="mdi mdi-information-outline mr-1"></i>
                                        {{ __('Required for processing Stripe webhook events') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Offline Payment Method -->
                    <div
                        class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                                    <i
                                        class="mdi mdi-{{ config('billing.methods.offline.icon') }} text-[var(--color-primary)] text-xl"></i>
                                </div>
                                <h4 class="text-md font-semibold text-[var(--color-text-primary)]">
                                    {{ __('Offline Payment Settings') }}
                                </h4>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="text-xs px-2 py-1 rounded-full {{ config('billing.methods.offline.enable') ? 'bg-[var(--color-success)] text-white' : 'bg-[var(--color-danger)] text-white' }}">
                                    {{ config('billing.methods.offline.enable') ? __('Enabled') : __('Disabled') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Display Name --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Display Name') }}
                                </label>
                                <input type="text" name="billing[methods][offline][name]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('billing.methods.offline.name') }}">
                            </div>

                            {{-- Icon --}}
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Icon Class') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="billing[methods][offline][icon]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                        value="{{ config('billing.methods.offline.icon') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-iconify text-[var(--color-text-secondary)]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Enable Offline --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Status') }}
                                </label>
                                <select name="billing[methods][offline][enable]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                    <option value="1"
                                        {{ config('billing.methods.offline.enable') ? 'selected' : '' }}>
                                        {{ __('Enabled') }}
                                    </option>
                                    <option value="0"
                                        {{ !config('billing.methods.offline.enable') ? 'selected' : '' }}>
                                        {{ __('Disabled') }}
                                    </option>
                                </select>
                                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility for API keys
            const togglePasswordButtons = document.querySelectorAll('.toggle-password');

            togglePasswordButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);

                    // Toggle icon
                    const icon = this.querySelector('i');
                    if (type === 'password') {
                        icon.classList.remove('mdi-eye-off');
                        icon.classList.add('mdi-eye');
                    } else {
                        icon.classList.remove('mdi-eye');
                        icon.classList.add('mdi-eye-off');
                    }
                });
            });

            // Add validation for numeric fields
            const numericInputs = document.querySelectorAll('input[type="number"]');
            numericInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 0) {
                        this.value = 0;
                        showToast('{{ __('Value cannot be negative') }}', 'warning');
                    }
                });
            });

            function showToast(message, type = 'info') {
                // Toast notification implementation
                console.log(`${type}: ${message}`);
            }
        });
    </script>
@endpush
