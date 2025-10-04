@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-1 md:p-6 bg-gray-100 rounded-2xl shadow-sm">

        <!-- Sidebar/Info Section -->
        <div class="w-full lg:w-1/4 sticky top-4 space-y-4">

            <div class="bg-indigo-600 text-white p-5 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-cog-outline text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('General Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ __('Configure global application parameters below.') }}</p>
            </div>

            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                    <i class="mdi mdi-information-outline mr-2 text-blue-600"></i>
                    {{ __('Quick Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500">
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-600 mr-2 mt-0.5"></i>
                        {{ __('Use complete URLs including https://') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-600 mr-2 mt-0.5"></i>
                        {{ __('Changes take effect immediately after saving') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields Section -->
        <div class="w-full lg:w-3/4 space-y-6">

            {{-- Organization Name --}}
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-office-building-outline text-blue-600"></i>
                    </div>
                    <label for="org_name"
                        class="block text-sm font-semibold text-gray-900">{{ __('Organization Name') }}</label>
                </div>
                <input id="org_name" type="text" name="app[org_name]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-colors duration-300"
                    placeholder="{{ __('Enter the organization name') }}" value="{{ config('app.org_name') }}">
                <small class="block mt-2 text-sm text-gray-500">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('This field specifies the name of the organization.') }}
                </small>
            </div>

            {{-- Application Name --}}
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-application-outline text-blue-600"></i>
                    </div>
                    <label for="app_name"
                        class="block text-sm font-semibold text-gray-900">{{ __('Application Name') }}</label>
                </div>
                <input id="app_name" type="text" name="app[name]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-colors duration-300"
                    placeholder="{{ __('Enter the application name') }}" value="{{ config('app.name') }}">
                <small class="block mt-2 text-sm text-gray-500">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('This field specifies the name of the application.') }}
                </small>
            </div>

            {{-- Home Page URL 
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-home-outline text-blue-600"></i>
                    </div>
                    <label for="home_page"
                        class="block text-sm font-semibold text-gray-900">{{ __('Home Page URL') }}</label>
                </div>
                <input id="home_page" type="text" name="system[home_page]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-colors duration-300"
                    placeholder="https://example.com" value="{{ config('system.home_page') }}">
                <small class="block mt-2 text-sm text-gray-500">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('This field specifies the URL of the home page of the application.') }}
                </small>
            </div> --}}

            {{-- Legal Documents Header 
            <div class="mt-8 mb-4 border-b border-gray-200 pb-2">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="mdi mdi-file-document-outline mr-2 text-blue-600"></i>{{ __('Legal Documents') }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">{{ __('URLs for your legal policies and terms') }}</p>
            </div> --}}

            {{-- Terms URL 
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-file-document-check-outline text-blue-600"></i>
                    </div>
                    <label for="terms_url"
                        class="block text-sm font-semibold text-gray-900">{{ __('Terms and Conditions URL') }}</label>
                </div>
                <input id="terms_url" type="url" name="system[terms_url]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-colors duration-300"
                    placeholder="https://example.com/terms" value="{{ config('system.terms_url') }}">
                <small class="block mt-2 text-sm text-gray-500">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Provide the URL where users can read the full Terms and Conditions. This document outlines the rules, obligations, and rights of users when using your website or service, including acceptable behavior, limitations of liability, and other legal agreements.') }}
                </small>
            </div> --}}

            {{-- Privacy Policy URL 
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-shield-account-outline text-blue-600"></i>
                    </div>
                    <label for="privacy_url"
                        class="block text-sm font-semibold text-gray-900">{{ __('Privacy Policy URL') }}</label>
                </div>
                <input id="privacy_url" type="url" name="system[privacy_url]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-colors duration-300"
                    placeholder="https://example.com/privacy" value="{{ config('system.privacy_url') }}">
                <small class="block mt-2 text-sm text-gray-500">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Provide the URL where users can read your Privacy Policy. This document explains how personal data is collected, used, stored, and protected, including user rights, cookies usage, third-party sharing, and compliance with privacy regulations such as GDPR or CCPA.') }}
                </small>
            </div> --}}

            {{-- Cookies Policy URL 
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-cookie-outline text-blue-600"></i>
                    </div>
                    <label for="policy_cookies"
                        class="block text-sm font-semibold text-gray-900">{{ __('Cookies Policy URL') }}</label>
                </div>
                <input id="policy_cookies" type="url" name="system[policy_cookies]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-colors duration-300"
                    placeholder="https://example.com/cookies" value="{{ config('system.policy_cookies') }}">
                <small class="block mt-2 text-sm text-gray-500">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Provide the URL where users can read your Cookies Policy. This page details the types of cookies used, their purpose, and how users can manage or opt-out of cookies, ensuring transparency and compliance with relevant privacy laws.') }}
                </small>
            </div>
--}}
        </div>
    </div>
@endsection
