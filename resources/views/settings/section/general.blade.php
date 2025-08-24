@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div
                class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-cog-outline text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('General Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure global application parameters below.') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-[var(--color-bg-primary)] rounded-xl shadow-sm border border-[var(--color-border)]">
                <h3 class="text-sm font-semibold text-[var(--color-text-primary)] flex items-center">
                    <i class="mdi mdi-information-outline mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Quick Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-[var(--color-text-secondary)]">
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Use complete URLs including https://') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Changes take effect immediately after saving') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            {{-- Organization name --}}
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-office-building-outline text-[var(--color-primary)]"></i>
                    </div>
                    <label for="org_name" class="block text-sm font-semibold text-[var(--color-text-primary)]">
                        {{ __('Organization Name') }}
                    </label>
                </div>
                <input id="org_name" type="text" name="app[org_name]"
                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                    placeholder="{{ __('Enter the organization name') }}" value="{{ config('app.org_name') }}">
                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __('This field specifies the name of the organization.') }}
                </small>
            </div>

            {{-- App name --}}
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-application-outline text-[var(--color-primary)]"></i>
                    </div>
                    <label for="app_name" class="block text-sm font-semibold text-[var(--color-text-primary)]">
                        {{ __('Application Name') }}
                    </label>
                </div>
                <input id="app_name" type="text" name="app[name]"
                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                    placeholder="{{ __('Enter the application name') }}" value="{{ config('app.name') }}">
                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __('This field specifies the name of the application.') }}
                </small>
            </div>

            {{-- Home Page --}}
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-home-outline text-[var(--color-primary)]"></i>
                    </div>
                    <label for="home_page" class="block text-sm font-semibold text-[var(--color-text-primary)]">
                        {{ __('Home Page URL') }}
                    </label>
                </div>
                <input id="home_page" type="text" name="system[home_page]"
                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                    placeholder="https://example.com" value="{{ config('system.home_page') }}">
                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __('This field specifies the URL of the home page of the application.') }}
                </small>
            </div>

            {{-- Legal Documents Section Header --}}
            <div class="mt-8 mb-4 border-b border-[var(--color-border)] pb-2">
                <h3 class="text-lg font-semibold text-[var(--color-text-primary)] flex items-center">
                    <i class="mdi mdi-file-document-outline mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Legal Documents') }}
                </h3>
                <p class="text-sm text-[var(--color-text-secondary)] mt-1">
                    {{ __('URLs for your legal policies and terms') }}
                </p>
            </div>

            {{-- Terms URL --}}
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-file-document-check-outline text-[var(--color-primary)]"></i>
                    </div>
                    <label for="terms_url" class="block text-sm font-semibold text-[var(--color-text-primary)]">
                        {{ __('Terms and Conditions URL') }}
                    </label>
                </div>
                <input id="terms_url" type="url" name="system[terms_url]"
                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                    placeholder="https://example.com/terms" value="{{ config('system.terms_url') }}">
                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __('This field specifies the URL where users can read the Terms and Conditions.') }}
                </small>
            </div>

            {{-- Privacy URL --}}
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-shield-account-outline text-[var(--color-primary)]"></i>
                    </div>
                    <label for="privacy_url" class="block text-sm font-semibold text-[var(--color-text-primary)]">
                        {{ __('Privacy Policy URL') }}
                    </label>
                </div>
                <input id="privacy_url" type="url" name="system[privacy_url]"
                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                    placeholder="https://example.com/privacy" value="{{ config('system.privacy_url') }}">
                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __('This field specifies the URL where users can read the Privacy Policy.') }}
                </small>
            </div>

            {{-- Cookies Policy --}}
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-cookie-outline text-[var(--color-primary)]"></i>
                    </div>
                    <label for="policy_cookies" class="block text-sm font-semibold text-[var(--color-text-primary)]">
                        {{ __('Cookies Policy URL') }}
                    </label>
                </div>
                <input id="policy_cookies" type="url" name="system[policy_cookies]"
                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                    placeholder="https://example.com/cookies" value="{{ config('system.policy_cookies') }}">
                <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __('This field specifies the URL where users can read the Cookies Policy.') }}
                </small>
            </div>
        </div>
    </div>
@endsection
