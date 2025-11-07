@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-1 md:p-6 bg-gray-100 rounded-2xl shadow-sm">

        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 sticky top-4 space-y-4">
            <div class="bg-indigo-600 text-white p-5 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-domain text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Organization Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Define the company information and descriptions used throughout the site.') }}
                </p>
            </div>

            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-blue-600"></i>
                    {{ __('Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500">
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-600 mr-2 mt-0.5"></i>
                        {{ __('This information will appear in the footer and contact pages.') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-600 mr-2 mt-0.5"></i>
                        {{ __('Keep mission and vision concise and inspiring.') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Form -->
        <div class="w-full lg:w-3/4 space-y-6">

            <!-- Organization Name -->
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-office-building-outline text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Organization Name') }}</label>
                </div>
                <input type="text" name="app[org_name]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="{{ __('Enter the organization name') }}" value="{{ config('app.org_name') }}">
                <p class="text-sm text-gray-500 mt-2">
                    <i class="mdi mdi-information-outline mr-1"></i>{{ __('Official name of your organization.') }}
                </p>
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

            <!-- Tax Identifier -->
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-card-account-details-outline text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Tax Identifier') }}</label>
                </div>
                <input type="text" name="app[tax_id]"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="{{ __('Enter company tax ID (RUC, CIF, EIN, etc.)') }}"
                    value="{{ config('app.tax_id') }}">
                <p class="text-sm text-gray-500 mt-2">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Unique tax registration number depending on the country.') }}
                </p>
            </div>

            <!-- Phones -->
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-phone-outline text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Contact Phones') }}</label>
                </div>

                <!-- Primary -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Primary: Dial Code') }}</label>
                        <input type="text" name="app[phone_dial_code]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                            placeholder="+51" value="{{ config('app.phone_dial_code') }}">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Primary: Number') }}</label>
                        <input type="text" name="app[phone_number]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                            placeholder="987 654 321" value="{{ config('app.phone_number') }}">
                    </div>
                </div>

                <hr class="my-4 border-gray-200">

                <!-- Secondary -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Secondary: Dial Code') }}</label>
                        <input type="text" name="app[secondary_phone_dial_code]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                            placeholder="+1" value="{{ config('app.secondary_phone_dial_code') }}">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Secondary: Number') }}</label>
                        <input type="text" name="app[secondary_phone_number]"
                            class="w-full px-3 py-2 border  border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                            placeholder="555 123 4567" value="{{ config('app.secondary_phone_number') }}">
                    </div>
                </div>

                <p class="text-sm text-gray-500 mt-2">
                    <i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Separate the dial code for easier formatting.') }}
                </p>
            </div>

            <!-- Address -->
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-map-marker-outline text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Address') }}</label>
                </div>
                <textarea name="app[address]" rows="2"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="{{ __('Enter full company address') }}">{{ config('app.address') }}</textarea>
                <p class="text-sm text-gray-500 mt-2"><i
                        class="mdi mdi-information-outline mr-1"></i>{{ __('Used in invoices and footer.') }}</p>
            </div>

            <!-- Mission -->
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-bullseye-arrow text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Mission') }}</label>
                </div>
                <textarea name="app[mission]" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="{{ __('Describe the mission of your company') }}">{{ config('app.mission') }}</textarea>
            </div>

            <!-- Vision -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm border border-gray-200 border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-eye-outline text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Vision') }}</label>
                </div>
                <textarea name="app[vision]" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="{{ __('Describe the vision of your company') }}">{{ config('app.vision') }}</textarea>
            </div>

            <!-- Values -->
            <div class="p-5 bg-white rounded-xl shadow-sm border  border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-heart-outline text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Values') }}</label>
                </div>
                <textarea name="app[values]" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="{{ __('List company core values (e.g., Integrity, Innovation, Respect)') }}">{{ config('app.values') }}</textarea>
            </div>

            <!-- Description -->
            <div class="p-5 bg-white rounded-xl shadow-sm border  border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-text-box-outline text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Short Description') }}</label>
                </div>
                <textarea name="app[description]" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="{{ __('Brief description for footer or SEO') }}">{{ config('app.description') }}</textarea>
            </div>

            <!-- Social Links -->
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-web text-blue-600"></i>
                    </div>
                    <label class="text-sm font-semibold text-gray-900">{{ __('Social Media & Communication') }}</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Facebook -->
                    <input type="url" name="app[facebook]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                        placeholder="Facebook URL" value="{{ config('app.facebook') }}">

                    <!-- Instagram -->
                    <input type="url" name="app[instagram]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                        placeholder="Instagram URL" value="{{ config('app.instagram') }}">

                    <!-- Twitter / X -->
                    <input type="url" name="app[twitter]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                        placeholder="Twitter / X URL" value="{{ config('app.twitter') }}">

                    <!-- LinkedIn -->
                    <input type="url" name="app[linkedin]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-100 transition"
                        placeholder="LinkedIn URL" value="{{ config('app.linkedin') }}">

                    <!-- WhatsApp -->
                    <input type="url" name="app[whatsapp]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-green-600 focus:ring-1 focus:ring-green-100 transition"
                        placeholder="WhatsApp URL o número (+51...)" value="{{ config('app.whatsapp') }}">

                    <!-- Telegram -->
                    <input type="url" name="app[telegram]"
                        class="w-full px-3 py-2 border-gray-200 rounded-lg focus:border-sky-600 focus:ring-1 focus:ring-sky-100 transition"
                        placeholder="Telegram URL" value="{{ config('app.telegram') }}">

                    <!-- TikTok -->
                    <input type="url" name="app[tiktok]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-pink-600 focus:ring-1 focus:ring-pink-100 transition"
                        placeholder="TikTok URL" value="{{ config('app.tiktok') }}">

                    <!-- YouTube -->
                    <input type="url" name="app[youtube]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-red-600 focus:ring-1 focus:ring-red-100 transition"
                        placeholder="YouTube Channel URL" value="{{ config('app.youtube') }}">

                    <!-- Pinterest -->
                    <input type="url" name="app[pinterest]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-rose-600 focus:ring-1 focus:ring-rose-100 transition"
                        placeholder="Pinterest URL" value="{{ config('app.pinterest') }}">

                    <!-- Threads -->
                    <input type="url" name="app[threads]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-black focus:ring-1 focus:ring-gray-200 transition"
                        placeholder="Threads URL" value="{{ config('app.threads') }}">
                </div>

                <small class="block mt-3 text-sm text-gray-500">
                    <i class="mdi mdi-information-outline mr-1"></i>
                    {{ __('Add links to your company’s official social media and communication platforms. These can be displayed in the website footer or contact sections.') }}
                </small>
            </div>
        </div>
    </div>
@endsection
