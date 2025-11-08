@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-50 rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 text-white p-6 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-database text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Redis Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure Redis database connections for optimal performance') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-indigo-600"></i>
                    {{ __('Performance Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500">
                    <li class="flex items-start">
                        <i class="mdi mdi-rocket-launch-outline text-green-500 mr-2 mt-0.5"></i>
                        {{ __('Use different databases for cache and sessions') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-key-outline text-yellow-500 mr-2 mt-0.5"></i>
                        {{ __('Secure Redis with password authentication') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-server-network text-blue-500 mr-2 mt-0.5"></i>
                        {{ __('Use Redis clusters for high availability') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Default Redis Connection -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-lg mr-3">
                        <i class="mdi mdi-database-cog text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Default Redis Connection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- URL -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-800 mb-2">URL</label>
                        <input type="text" name="database[redis][default][url]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                            placeholder="Enter Redis URL" value="{{ config('database.redis.default.url', '') }}">
                    </div>

                    <!-- Host -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Host</label>
                        <input type="text" name="database[redis][default][host]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                            placeholder="Enter Redis host" value="{{ config('database.redis.default.host', '127.0.0.1') }}">
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Username</label>
                        <input type="text" name="database[redis][default][username]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                            placeholder="Enter Redis username" value="{{ config('database.redis.default.username', '') }}">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Password</label>
                        <input type="password" name="database[redis][default][password]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                            placeholder="Enter Redis password" value="{{ config('database.redis.default.password', '') }}">
                    </div>

                    <!-- Port -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Port <span
                                class="text-gray-500">*</span></label>
                        <input type="number" name="database[redis][default][port]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                            placeholder="6379" min="0" value="{{ config('database.redis.default.port', 6379) }}">
                    </div>

                    <!-- Database -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Database <span
                                class="text-gray-500">*</span></label>
                        <input type="number" name="database[redis][default][database]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                            placeholder="0" min="0" max="15"
                            value="{{ config('database.redis.default.database', 0) }}">
                        <small class="block mt-1 text-sm text-gray-500">Database index (0-15)</small>
                    </div>
                </div>
            </div>

            <!-- Redis Cache Connection -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-cached text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Redis Cache Connection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- URL -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-800 mb-2">URL</label>
                        <input type="text" name="database[redis][cache][url]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            placeholder="Enter Redis cache URL" value="{{ config('database.redis.cache.url', '') }}">
                    </div>

                    <!-- Host -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Host</label>
                        <input type="text" name="database[redis][cache][host]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            placeholder="Enter Redis cache host"
                            value="{{ config('database.redis.cache.host', '127.0.0.1') }}">
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Username</label>
                        <input type="text" name="database[redis][cache][username]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            placeholder="Enter Redis cache username"
                            value="{{ config('database.redis.cache.username', '') }}">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Password</label>
                        <input type="password" name="database[redis][cache][password]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            placeholder="Enter Redis cache password"
                            value="{{ config('database.redis.cache.password', '') }}">
                    </div>

                    <!-- Port -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Port <span
                                class="text-gray-500">*</span></label>
                        <input type="number" name="database[redis][cache][port]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            placeholder="6379" min="0" value="{{ config('database.redis.cache.port', 6379) }}">
                    </div>

                    <!-- Database -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Database <span
                                class="text-gray-500">*</span></label>
                        <input type="number" name="database[redis][cache][database]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-colors duration-300"
                            placeholder="1" min="0" max="15"
                            value="{{ config('database.redis.cache.database', 1) }}">
                        <small class="block mt-1 text-sm text-gray-500">
                            {{ __('Recommended: Use database 1 for cache to separate from default') }}
                        </small>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <i class="mdi mdi-information-outline text-blue-500 mr-2"></i>
                        <span class="text-sm text-gray-500">
                            {{ __('Dedicated connection for application caching. Recommended to use a separate database.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Redis Horizon Connection -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg mr-3">
                        <i class="mdi mdi-chart-timeline-variant text-purple-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Redis Horizon Connection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- URL -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-800 mb-2">URL</label>
                        <input type="text" name="database[redis][horizon][url]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-colors duration-300"
                            placeholder="Enter Redis Horizon URL" value="{{ config('database.redis.horizon.url', '') }}">
                    </div>

                    <!-- Host -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Host</label>
                        <input type="text" name="database[redis][horizon][host]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-colors duration-300"
                            placeholder="Enter Redis Horizon host"
                            value="{{ config('database.redis.horizon.host', '127.0.0.1') }}">
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Username</label>
                        <input type="text" name="database[redis][horizon][username]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-colors duration-300"
                            placeholder="Enter Redis Horizon username"
                            value="{{ config('database.redis.horizon.username', '') }}">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Password</label>
                        <input type="password" name="database[redis][horizon][password]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-colors duration-300"
                            placeholder="Enter Redis Horizon password"
                            value="{{ config('database.redis.horizon.password', '') }}">
                    </div>

                    <!-- Port -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Port <span
                                class="text-gray-500">*</span></label>
                        <input type="number" name="database[redis][horizon][port]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-colors duration-300"
                            placeholder="6379" min="0" value="{{ config('database.redis.horizon.port', 6379) }}">
                    </div>

                    <!-- Database -->
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">Database <span
                                class="text-gray-500">*</span></label>
                        <input type="number" name="database[redis][horizon][database]"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-colors duration-300"
                            placeholder="0" min="0" max="15"
                            value="{{ config('database.redis.horizon.database', 0) }}">
                        <small class="block mt-1 text-sm text-gray-500">
                            {{ __('Recommended: Use database 0 for Horizon (same as default) or separate if needed.') }}
                        </small>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <i class="mdi mdi-information-outline text-purple-500 mr-2"></i>
                        <span class="text-sm text-gray-500">
                            {{ __('Dedicated Redis connection for Laravel Horizon. You can use the same as default or assign a specific one for better isolation.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Recommended Configuration -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg mr-3">
                        <i class="mdi mdi-check-all text-green-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Best Practices') }}
                    </h3>
                </div>

                <div class="space-y-3 text-sm text-gray-500">
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 mr-2 mt-0.5"></i>
                        <span>{{ __('Use different database indexes for default and cache connections') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 mr-2 mt-0.5"></i>
                        <span>{{ __('Enable Redis persistence for data durability') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 mr-2 mt-0.5"></i>
                        <span>{{ __('Use password authentication in production environments') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-green-500 mr-2 mt-0.5"></i>
                        <span>{{ __('Consider using Redis clusters for high availability setups') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            // Connection test functionality
            const testDefaultBtn = document.getElementById('test-default-connection');
            const testCacheBtn = document.getElementById('test-cache-connection');
            const connectionResults = document.getElementById('connection-results');
            const resultContent = document.getElementById('test-result-content');

            if (testDefaultBtn && testCacheBtn) {
                testDefaultBtn.addEventListener('click', function() {
                    testConnection('default');
                });

                testCacheBtn.addEventListener('click', function() {
                    testConnection('cache');
                });
            }

            function testConnection(type) {
                // Show loading state
                const button = type === 'default' ? testDefaultBtn : testCacheBtn;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="mdi mdi-loading animate-spin mr-2"></i>Testing...';
                button.disabled = true;

                // Simulate connection test (replace with actual API call)
                setTimeout(() => {
                    const success = Math.random() > 0.3; // 70% success rate for demo

                    if (success) {
                        resultContent.innerHTML = `
                            <div class="flex items-center text-[var(--color-success)]">
                                <i class="mdi mdi-check-circle-outline mr-2"></i>
                                <span>${type === 'default' ? 'Default' : 'Cache'} connection successful!</span>
                            </div>
                            <div class="mt-2 text-xs text-[var(--color-text-secondary)]">
                                Connection established successfully to Redis server.
                            </div>
                        `;
                    } else {
                        resultContent.innerHTML = `
                            <div class="flex items-center text-[var(--color-danger)]">
                                <i class="mdi mdi-close-circle-outline mr-2"></i>
                                <span>${type === 'default' ? 'Default' : 'Cache'} connection failed!</span>
                            </div>
                            <div class="mt-2 text-xs text-[var(--color-text-secondary)]">
                                Unable to connect to Redis server. Please check your configuration.
                            </div>
                        `;
                    }

                    connectionResults.classList.remove('hidden');

                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1500);
            }

            // Input validation
            const numberInputs = document.querySelectorAll('input[type="number"]');
            numberInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 0) {
                        this.value = 0;
                        showToast('{{ __('Value cannot be negative') }}', 'warning');
                    }

                    if (this.name.includes('database') && this.value > 15) {
                        this.value = 15;
                        showToast('{{ __('Redis database index must be between 0-15') }}',
                            'warning');
                    }
                });
            });

            function showToast(message, type = 'info') {
                // Toast notification implementation
                $notify.error(`${type}: ${message}`);
            }
        });
    </script>
@endpush
