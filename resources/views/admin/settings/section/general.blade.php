@extends('admin.settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-1 md:p-6 bg-gray-100 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">

        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 sticky top-4 space-y-4">
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white p-5 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-domain text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Organization Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Define the company information and descriptions used throughout the site.') }}
                </p>
            </div>

            <div
                class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-blue-600 dark:text-blue-400"></i>
                    {{ __('Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-600 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('This information will appear in the footer and contact pages.') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-600 dark:text-green-400 mr-2 mt-0.5"></i>
                        {{ __('Keep mission and vision concise and inspiring.') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Form -->
        <div class="w-full lg:w-3/4 space-y-6">

            <!-- Organization Name -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-office-building-outline text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Organization Name') }}</label>
                </div>
                <input type="text" name="app[org_name]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                    placeholder="{{ __('Enter the organization name') }}" value="{{ config('app.org_name') }}">
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <i class="mdi mdi-information-outline mr-1"></i>{{ __('Official name of your organization.') }}
                </p>
            </div>

            {{-- Application Name --}}
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                        <i class="mdi mdi-application-outline text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <label for="app_name"
                        class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('Application Name') }}</label>
                </div>
                <input id="app_name" type="text" name="app[name]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                    placeholder="{{ __('Enter the application name') }}" value="{{ config('app.name') }}">
                <small class="block mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('This field specifies the name of the application.') }}
                </small>
            </div>

            <!-- Tax Identifier -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-card-account-details-outline text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Tax Identifier') }}</label>
                </div>
                <input type="text" name="app[tax_id]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                    placeholder="{{ __('Enter company tax ID (RUC, CIF, EIN, etc.)') }}"
                    value="{{ config('app.tax_id') }}">
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Unique tax registration number depending on the country.') }}
                </p>
            </div>

            <!-- Phones -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-phone-outline text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Contact Phones') }}</label>
                </div>

                <!-- Primary -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-400">{{ __('Primary: Dial Code') }}</label>
                        <input type="text" name="app[phone_dial_code]"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:border-blue-600 dark:focus:border-blue-500 focus:ring-1 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                            placeholder="+51" value="{{ config('app.phone_dial_code') }}">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-400">{{ __('Primary: Number') }}</label>
                        <input type="text" name="app[phone_number]"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:border-blue-600 dark:focus:border-blue-500 focus:ring-1 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                            placeholder="987 654 321" value="{{ config('app.phone_number') }}">
                    </div>
                </div>

                <hr class="my-4 border-gray-200 dark:border-gray-600">

                <!-- Secondary -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-400">{{ __('Secondary: Dial Code') }}</label>
                        <input type="text" name="app[secondary_phone_dial_code]"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:border-blue-600 dark:focus:border-blue-500 focus:ring-1 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                            placeholder="+1" value="{{ config('app.secondary_phone_dial_code') }}">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-400">{{ __('Secondary: Number') }}</label>
                        <input type="text" name="app[secondary_phone_number]"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:border-blue-600 dark:focus:border-blue-500 focus:ring-1 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                            placeholder="555 123 4567" value="{{ config('app.secondary_phone_number') }}">
                    </div>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Separate the dial code for easier formatting.') }}
                </p>
            </div>

            <!-- Address -->
            <div
                class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-map-marker-outline text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Address') }}</label>
                </div>
                <textarea name="app[address]" rows="2"
                    class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-colors duration-300"
                    placeholder="{{ __('Enter full company address') }}">{{ config('app.address') }}</textarea>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2"><i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Used in invoices and footer.') }}</p>
            </div>
        </div>
    </div>
@endsection
