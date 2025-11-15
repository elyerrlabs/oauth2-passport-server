@extends('settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">

        <!-- Sidebar info -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-puzzle text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Module Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ __('Enable or disable specific application modules') }}</p>
            </div>

            <div
                class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="mdi mdi-information mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Usage Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check text-green-500 mr-2 mt-0.5"></i>
                        {{ __('Disabling a module will hide all related features and menus') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-restart text-yellow-500 mr-2 mt-0.5"></i>
                        {{ __('Changes may require reloading the page to take effect') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Module form -->
        <div class="w-full lg:w-3/4 space-y-6">

            @foreach ($keys as $module => $items)
                @php
                    $nameKey = collect($items)->first(fn($k) => str_ends_with($k, '.name'));
                    $enabledKey = collect($items)->first(fn($k) => str_ends_with($k, '.module_enabled'));
                    $key = explode('.', $enabledKey);
                @endphp

                <div
                    class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg mr-3">
                            <i class="mdi mdi-puzzle-outline text-indigo-600 dark:text-indigo-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ config($nameKey) }}
                        </h3>
                    </div>

                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 transition-colors duration-300">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                                {{ __('Module Enabled') }}
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Enable or disable this module') }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <select name="module[{{ $key[1] }}][{{ $key[2] }}][{{ $key[3] }}]"
                                class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-colors duration-300">
                                <option value="1" {{ config($enabledKey, true) == true ? 'selected' : '' }}>
                                    {{ __('Enabled') }}
                                </option>
                                <option value="0" {{ config($enabledKey, true) == false ? 'selected' : '' }}>
                                    {{ __('Disabled') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
