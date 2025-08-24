@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div
                class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-database text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Redis Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure Redis database connections for optimal performance') }}
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
                        {{ __('Use different databases for cache and sessions') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-shield-key-outline text-[var(--color-warning)] mr-2 mt-0.5"></i>
                        {{ __('Secure Redis with password authentication') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-server-network text-[var(--color-info)] mr-2 mt-0.5"></i>
                        {{ __('Use Redis clusters for high availability') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Default Redis Connection -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-database-cog text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Default Redis Connection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach (['url', 'host', 'username', 'password', 'port', 'database'] as $key)
                        <div class="{{ in_array($key, ['url']) ? 'md:col-span-2' : '' }}">
                            <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                {{ ucfirst($key) }}
                                @if ($key === 'database' || $key === 'port')
                                    <span class="text-[var(--color-text-secondary)]">*</span>
                                @endif
                            </label>
                            <div class="relative">
                                <input
                                    type="{{ $key === 'password' ? 'password' : ($key === 'port' || $key === 'database' ? 'number' : 'text') }}"
                                    name="database[redis][default][{{ $key }}]"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    placeholder="{{ $key === 'port' ? '6379' : ($key === 'database' ? '0' : __('Enter Redis :name', ['name' => $key])) }}"
                                    value="{{ config('database.redis.default.' . $key, $key === 'port' ? '6379' : ($key === 'database' ? '0' : '')) }}"
                                    {{ $key === 'port' || $key === 'database' ? 'min="0"' : '' }}>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="mdi mdi-{{ $key === 'password' ? 'key-variant' : ($key === 'username' ? 'account' : ($key === 'host' ? 'server' : ($key === 'port' ? 'numeric' : ($key === 'database' ? 'database' : 'link')))) }} text-[var(--color-text-secondary)]"></i>
                                </div>
                            </div>
                            @if ($key === 'database' || $key === 'port')
                                <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                    {{ $key === 'database' ? __('Database index (0-15)') : __('Default Redis port') }}
                                </small>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 p-3 bg-[var(--color-bg-secondary)] rounded-lg border border-[var(--color-border)]">
                    <div class="flex items-center">
                        <i class="mdi mdi-information-outline text-[var(--color-info)] mr-2"></i>
                        <span class="text-sm text-[var(--color-text-secondary)]">
                            {{ __('Used for general purpose Redis operations and queuing') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Redis Cache Connection -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-info-light)] rounded-lg mr-3">
                        <i class="mdi mdi-cached text-[var(--color-info)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Redis Cache Connection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach (['url', 'host', 'username', 'password', 'port', 'database'] as $key)
                        <div class="{{ in_array($key, ['url']) ? 'md:col-span-2' : '' }}">
                            <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                {{ ucfirst($key) }}
                                @if ($key === 'database' || $key === 'port')
                                    <span class="text-[var(--color-text-secondary)]">*</span>
                                @endif
                            </label>
                            <div class="relative">
                                <input
                                    type="{{ $key === 'password' ? 'password' : ($key === 'port' || $key === 'database' ? 'number' : 'text') }}"
                                    name="database[redis][cache][{{ $key }}]"
                                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                    placeholder="{{ $key === 'port' ? '6379' : ($key === 'database' ? '1' : __('Enter Redis cache :name', ['name' => $key])) }}"
                                    value="{{ config('database.redis.cache.' . $key, $key === 'port' ? '6379' : ($key === 'database' ? '1' : '')) }}"
                                    {{ $key === 'port' || $key === 'database' ? 'min="0"' : '' }}>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="mdi mdi-{{ $key === 'password' ? 'key-variant' : ($key === 'username' ? 'account' : ($key === 'host' ? 'server' : ($key === 'port' ? 'numeric' : ($key === 'database' ? 'database' : 'link')))) }} text-[var(--color-text-secondary)]"></i>
                                </div>
                            </div>
                            @if ($key === 'database')
                                <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                    {{ __('Recommended: Use database 1 for cache to separate from default') }}
                                </small>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 p-3 bg-[var(--color-bg-secondary)] rounded-lg border border-[var(--color-border)]">
                    <div class="flex items-center">
                        <i class="mdi mdi-information-outline text-[var(--color-info)] mr-2"></i>
                        <span class="text-sm text-[var(--color-text-secondary)]">
                            {{ __('Dedicated connection for application caching. Recommended to use a separate database.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Connection Test Section -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-warning-light)] rounded-lg mr-3">
                        <i class="mdi mdi-connection text-[var(--color-warning)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Connection Test') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <button type="button" id="test-default-connection"
                            class="w-full flex items-center justify-center px-4 py-3 bg-[var(--color-primary)] text-white rounded-lg shadow-md hover:bg-[var(--color-primary-hover)] transition-colors duration-300">
                            <i class="mdi mdi-server-network mr-2"></i>
                            {{ __('Test Default Connection') }}
                        </button>
                    </div>
                    <div>
                        <button type="button" id="test-cache-connection"
                            class="w-full flex items-center justify-center px-4 py-3 bg-[var(--color-info)] text-white rounded-lg shadow-md hover:bg-[var(--color-info-hover)] transition-colors duration-300">
                            <i class="mdi mdi-cached mr-2"></i>
                            {{ __('Test Cache Connection') }}
                        </button>
                    </div>
                </div>

                <div id="connection-results" class="mt-4 hidden">
                    <div class="p-3 rounded-lg border border-[var(--color-border)]">
                        <h4 class="text-sm font-semibold text-[var(--color-text-primary)] mb-2">
                            {{ __('Test Results') }}
                        </h4>
                        <div id="test-result-content" class="text-sm"></div>
                    </div>
                </div>
            </div>

            <!-- Recommended Configuration -->
            <div
                class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-success-light)] rounded-lg mr-3">
                        <i class="mdi mdi-check-all text-[var(--color-success)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Best Practices') }}
                    </h3>
                </div>

                <div class="space-y-3 text-sm text-[var(--color-text-secondary)]">
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        <span>{{ __('Use different database indexes for default and cache connections') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        <span>{{ __('Enable Redis persistence for data durability') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
                        <span>{{ __('Use password authentication in production environments') }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="mdi mdi-check-circle-outline text-[var(--color-success)] mr-2 mt-0.5"></i>
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
                console.log(`${type}: ${message}`);
            }
        });
    </script>
@endpush
