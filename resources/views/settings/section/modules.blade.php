@extends('settings.setting')

@section('form')
    <div
        class="flex flex-col lg:flex-row gap-2 items-start p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm transition-colors duration-300">

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
        @foreach (config('module', []) as $type => $group)
            <div class="mb-8  ">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-4">
                    {{ strtoupper($type === 'core' ? 'System Modules' : 'Third-party Modules') }}
                </h2>

                @foreach ($group as $moduleKey => $module)
                    <div
                        class="p-5 mb-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all border border-gray-200 dark:border-gray-700">

                        <div class="flex items-center mb-4">
                            <div
                                class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg mr-3">
                                <i class="mdi mdi-puzzle-outline text-indigo-600 dark:text-indigo-400 text-xl"></i>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ config("module.{$type}.{$moduleKey}.name") ?? ucfirst($moduleKey) }}
                            </h3>


                            @if ($type === 'core')
                                <span
                                    class="ml-3 px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">
                                    CORE
                                </span>
                            @else
                                <span
                                    class="ml-3 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    THIRD
                                </span>
                            @endif
                        </div>

                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">

                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                                    {{ __('Module Enabled') }}
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Enable or disable this module') }}
                                </p>
                            </div>

                            <div class="ml-4">
                                <select name="module[{{ $type }}][{{ $moduleKey }}][module_enabled]"
                                    class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500">

                                    <option value="1"
                                        {{ config("module.{$type}.{$moduleKey}.module_enabled") ? 'selected' : '' }}>
                                        {{ __('Enabled') }}
                                    </option>
                                    <option value="0"
                                        {{ !config("module.{$type}.{$moduleKey}.module_enabled") ? 'selected' : '' }}>
                                        {{ __('Disabled') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>
@endsection
