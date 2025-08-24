@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div
                class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-cached text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Cache Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure application caching for optimal performance') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-[var(--color-bg-primary)] rounded-xl shadow-sm border border-[var(--color-border)]">
                <h3 class="text-sm font-semibold text-[var(--color-text-primary)] flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Performance Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-[var(--color-text-secondary)]">
                    <li class="flex items-start">
                        <i class="mdi mdi-rocket-launch-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Use Redis or Memcached for production environments') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-test-tube text-[var(--color-warning)] mr-2 mt-0.5"></i>
                        {{ __('Use array driver for local development') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-key-outline text-[var(--color-info)] mr-2 mt-0.5"></i>
                        {{ __('Set appropriate expiration times for cache items') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Cache Configuration Section -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-cog-outline text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Cache Configuration') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Cache Driver --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Cache Driver') }}
                            <span class="text-[var(--color-text-secondary)]">*</span>
                        </label>
                        <select id="cache-driver" name="cache[default]"
                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                            @foreach (['apc', 'array', 'database', 'file', 'memcached', 'redis', 'dynamodb', 'octane', 'null'] as $driver)
                                <option value="{{ $driver }}"
                                    {{ config('cache.default') == $driver ? 'selected' : '' }}>
                                    {{ ucfirst($driver) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Select the cache storage driver for your application') }}
                        </small>
                    </div>

                    {{-- Expiration Time --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Expiration Time') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="cache[expires]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('cache.expires') }}" placeholder="1440" min="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-[var(--color-text-secondary)]">min</span>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Default cache item expiration time in minutes') }}
                        </small>
                    </div>

                    {{-- Cache Prefix --}}
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Cache Prefix') }}
                        </label>
                        <div class="relative">
                            <input type="text" name="cache[prefix]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('cache.prefix') }}" placeholder="laravel_cache">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-pound text-[var(--color-text-secondary)]"></i>
                            </div>
                        </div>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Prefix for all cache keys to avoid conflicts') }}
                        </small>
                    </div>
                </div>

                <!-- Driver-Specific Configurations -->
                <div class="mt-6 pt-4 border-t border-[var(--color-border)]">
                    {{-- Database Driver --}}
                    <div data-driver="database"
                        class="driver-fields p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-database mr-2 text-[var(--color-primary)]"></i>
                            {{ __('Database Cache Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Connection') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="cache[stores][database][connection]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                        value="{{ config('cache.stores.database.connection') }}" placeholder="mysql">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-connection text-[var(--color-text-secondary)]"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Table') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="cache[stores][database][table]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                        value="{{ config('cache.stores.database.table') }}" placeholder="cache">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-table text-[var(--color-text-secondary)]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Redis Driver --}}
                    <div data-driver="redis"
                        class="driver-fields p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-redis mr-2 text-[var(--color-danger)]"></i>
                            {{ __('Redis Cache Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Connection') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="cache[stores][redis][connection]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                        value="{{ config('cache.stores.redis.connection') }}" placeholder="default">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-connection text-[var(--color-text-secondary)]"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Lock Connection') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="cache[stores][redis][lock_connection]"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                        value="{{ config('cache.stores.redis.lock_connection') }}" placeholder="default">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="mdi mdi-lock-outline text-[var(--color-text-secondary)]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Memcached Driver --}}
                    <div data-driver="memcached"
                        class="driver-fields p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-server-network mr-2 text-[var(--color-info)]"></i>
                            {{ __('Memcached Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Persistent ID') }}
                                </label>
                                <input type="text" name="cache[stores][memcached][persistent_id]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.memcached.persistent_id') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('SASL Username') }}
                                </label>
                                <input type="text" name="cache[stores][memcached][sasl][username]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.memcached.sasl.username') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('SASL Password') }}
                                </label>
                                <input type="password" name="cache[stores][memcached][sasl][password]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.memcached.sasl.password') }}">
                            </div>
                            <div class="md:col-span-2">
                                <h5 class="text-sm font-semibold text-[var(--color-text-primary)] mb-3 mt-4">
                                    {{ __('Server Configuration') }}
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                            {{ __('Host') }}
                                        </label>
                                        <input type="text" name="cache[stores][memcached][servers][0][host]"
                                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ SettingItem('cache.stores.memcached.servers.0.host') }}"
                                            placeholder="127.0.0.1">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                            {{ __('Port') }}
                                        </label>
                                        <input type="number" name="cache[stores][memcached][servers][0][port]"
                                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ SettingItem('cache.stores.memcached.servers.0.port') }}"
                                            placeholder="11211" min="1" max="65535">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                            {{ __('Weight') }}
                                        </label>
                                        <input type="number" name="cache[stores][memcached][servers][0][weight]"
                                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ SettingItem('cache.stores.memcached.servers.0.weight') }}"
                                            placeholder="100" min="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DynamoDB Driver --}}
                    <div data-driver="dynamodb"
                        class="driver-fields p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-amazon mr-2 text-[var(--color-warning)]"></i>
                            {{ __('DynamoDB Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('AWS Key') }}
                                </label>
                                <input type="text" name="cache[stores][dynamodb][key]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.dynamodb.key') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('AWS Secret') }}
                                </label>
                                <input type="password" name="cache[stores][dynamodb][secret]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.dynamodb.secret') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Region') }}
                                </label>
                                <input type="text" name="cache[stores][dynamodb][region]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.dynamodb.region') }}" placeholder="us-east-1">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Table') }}
                                </label>
                                <input type="text" name="cache[stores][dynamodb][table]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.dynamodb.table') }}" placeholder="cache">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ __('Endpoint') }}
                                </label>
                                <input type="text" name="cache[stores][dynamodb][endpoint]"
                                    class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    value="{{ config('cache.stores.dynamodb.endpoint') }}"
                                    placeholder="https://dynamodb.us-east-1.amazonaws.com">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cache Actions Section -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-[var(--color-warning-light)] rounded-lg mr-3">
                        <i class="mdi mdi-playlist-remove text-[var(--color-warning)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Cache Management') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="button" id="clear-cache"
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-danger)] text-white rounded-lg shadow-md hover:bg-[var(--color-danger-hover)] transition-colors duration-300">
                        <i class="mdi mdi-trash-can-outline mr-2"></i>
                        {{ __('Clear Application Cache') }}
                    </button>
                    <button type="button" id="clear-config"
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-warning)] text-white rounded-lg shadow-md hover:bg-[var(--color-warning-hover)] transition-colors duration-300">
                        <i class="mdi mdi-cog-off-outline mr-2"></i>
                        {{ __('Clear Configuration Cache') }}
                    </button>
                    <button type="button" id="clear-route"
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-info)] text-white rounded-lg shadow-md hover:bg-[var(--color-info-hover)] transition-colors duration-300">
                        <i class="mdi mdi-routes mr-2"></i>
                        {{ __('Clear Route Cache') }}
                    </button>
                    <button type="button" id="clear-view"
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-success)] text-white rounded-lg shadow-md hover:bg-[var(--color-success-hover)] transition-colors duration-300">
                        <i class="mdi mdi-eye-off-outline mr-2"></i>
                        {{ __('Clear View Cache') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const driverSelect = document.getElementById('cache-driver');
            const driverFields = document.querySelectorAll('.driver-fields');

            function toggleDriverFields(selectedDriver) {
                driverFields.forEach(div => {
                    div.classList.toggle('hidden', div.dataset.driver !== selectedDriver);
                });
            }

            // Initialize with current value
            toggleDriverFields(driverSelect.value);

            // Change on selection
            driverSelect.addEventListener('change', (e) => {
                toggleDriverFields(e.target.value);
            });

            // Cache management actions
            const cacheButtons = ['clear-cache', 'clear-config', 'clear-route', 'clear-view'];
            cacheButtons.forEach(buttonId => {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.addEventListener('click', function() {
                        const action = this.id.replace('clear-', '');
                        clearCache(action);
                    });
                }
            });

            function clearCache(type) {
                const button = document.getElementById(`clear-${type}`);
                const originalText = button.innerHTML;
                const actionNames = {
                    'cache': 'Application Cache',
                    'config': 'Configuration Cache',
                    'route': 'Route Cache',
                    'view': 'View Cache'
                };

                button.innerHTML = '<i class="mdi mdi-loading animate-spin mr-2"></i>Clearing...';
                button.disabled = true;

                // Simulate API call (replace with actual implementation)
                setTimeout(() => {
                    const success = Math.random() > 0.2; // 80% success rate for demo

                    if (success) {
                        showToast(`{{ __(':type cleared successfully') }}`.replace(':type', actionNames[
                            type]), 'success');
                    } else {
                        showToast(`{{ __('Failed to clear :type') }}`.replace(':type', actionNames[type]),
                            'error');
                    }

                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1500);
            }

            function showToast(message, type = 'info') {
                // Toast notification implementation
                console.log(`${type}: ${message}`);
            }
        });
    </script>
@endpush
