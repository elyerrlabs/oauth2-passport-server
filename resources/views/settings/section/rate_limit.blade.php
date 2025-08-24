@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-slate-50 rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-speedometer text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Rate Limit Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure request rate limits to prevent abuse and ensure service stability') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                    <i class="mdi mdi-security mr-2 text-blue-600"></i>
                    {{ __('Best Practices') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-600">
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-check text-green-500 mr-2 mt-0.5"></i>
                        {{ __('Set appropriate limits based on user roles') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-alert-circle text-yellow-500 mr-2 mt-0.5"></i>
                        {{ __('Monitor and adjust limits based on usage patterns') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-chart-line text-blue-500 mr-2 mt-0.5"></i>
                        {{ __('Consider peak traffic times when setting limits') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Rate Limit Settings -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-lg mr-3">
                        <i class="mdi mdi-speedometer text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Rate Limit Configuration') }}
                    </h3>
                </div>

                @php
                    $rateLimits = config('rate_limit');
                @endphp

                <div class="space-y-6">
                    @foreach ($rateLimits as $module => $items)
                        <div class="p-2 bg-slate-50 rounded-lg border border-gray-200">
                            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="mdi mdi-puzzle-outline mr-2 text-blue-600"></i>
                                {{ ucfirst($module) }} {{ __('Module') }}
                            </h4>

                            <div class="grid grid-cols-1 gap-5">
                                @foreach ($items as $key => $config)
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <h5 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                            <i class="mdi mdi-cog-outline mr-2 text-gray-500"></i>
                                            {{ config("rate_limit.$module.$key.name") }}
                                        </h5>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    {{ __('Requests per Minute') }}
                                                </label>
                                                <div class="relative">
                                                    <input type="number"
                                                        name="rate_limit[{{ $module }}][{{ $key }}][limit]"
                                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                                        value="{{ $config['limit'] ?? 60 }}" min="1"
                                                        placeholder="60">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="mdi mdi-counter text-gray-400"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    {{ __('Block Time (minutes)') }}
                                                </label>
                                                <div class="relative">
                                                    <input type="number"
                                                        name="rate_limit[{{ $module }}][{{ $key }}][block_time]"
                                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                                                        value="{{ $config['block_time'] ?? 1 }}" min="1"
                                                        placeholder="1">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="mdi mdi-clock-outline text-gray-400"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Configuration Tips -->
            <div class="p-5 bg-blue-50 rounded-xl border border-blue-200">
                <h4 class="text-md font-semibold text-blue-900 mb-3 flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-blue-600"></i>
                    {{ __('Configuration Tips') }}
                </h4>
                <div class="text-sm text-blue-700 space-y-2">
                    <p>{{ __('• API endpoints typically require lower limits than web routes') }}</p>
                    <p>{{ __('• Consider increasing limits for authenticated users') }}</p>
                    <p>{{ __('• Monitor your logs to identify optimal limit values') }}</p>
                    <p>{{ __('• Test limits in staging before applying to production') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            // Add input validation for rate limits
            const numberInputs = document.querySelectorAll('input[type="number"]');

            // Add real-time validation feedback
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    let isValid = true;
                    numberInputs.forEach(input => {
                        if (!input.value || input.value < 1) {
                            isValid = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('{{ __('Please enter valid values for all rate limit fields') }}');
                    }
                });
            });

            // Add help tooltips
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-200');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-200');
                });
            });
        });
    </script>
@endpush

<style>
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
