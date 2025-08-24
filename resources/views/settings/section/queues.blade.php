@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-playlist-play text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Queue Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure job queue connections and processing') }}
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
                        {{ __('Use Redis for high-performance queue processing') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-database text-[var(--color-warning)] mr-2 mt-0.5"></i>
                        {{ __('Database driver is good for development and small apps') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-amazon text-[var(--color-info)] mr-2 mt-0.5"></i>
                        {{ __('SQS is ideal for distributed cloud applications') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Default Queue Configuration -->
            <div class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-playlist-star text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Default Queue Connection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Default Queue Driver') }}
                            <span class="text-[var(--color-text-secondary)]">*</span>
                        </label>
                        <select id="queue_selector" name="queue[default]"
                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                            @foreach (['sync', 'database', 'beanstalkd', 'sqs', 'redis'] as $driver)
                                <option value="{{ $driver }}" {{ config('queue.default') == $driver ? 'selected' : '' }}>
                                    {{ ucfirst($driver) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Select the default queue connection for job processing') }}
                        </small>
                    </div>
                </div>

                <!-- Queue-Specific Configurations -->
                <div class="mt-6 pt-4 border-t border-[var(--color-border)]">
                    {{-- Database Queue --}}
                    <div id="database_queue" class="queue-config p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-database mr-2 text-[var(--color-success)]"></i>
                            {{ __('Database Queue Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['table', 'queue', 'retry_after', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['retry_after']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if($key === 'retry_after')
                                            <span class="text-[var(--color-text-secondary)]">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="{{ $key === 'retry_after' ? 'number' : 'text' }}"
                                            name="queue[connections][database][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('queue.connections.database.' . $key) }}"
                                            placeholder="{{ $key === 'table' ? 'jobs' : ($key === 'queue' ? 'default' : ($key === 'retry_after' ? '90' : '')) }}"
                                            {{ $key === 'retry_after' ? 'min="1"' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-{{ $key === 'table' ? 'table' : ($key === 'queue' ? 'playlist-play' : ($key === 'retry_after' ? 'timer-outline' : 'check-circle')) }} text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                    @if($key === 'retry_after')
                                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                            {{ __('Number of seconds before a failed job is retried') }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Beanstalkd Queue --}}
                    <div id="beanstalkd_queue" class="queue-config p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-server mr-2 text-[var(--color-warning)]"></i>
                            {{ __('Beanstalkd Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['host', 'queue', 'retry_after', 'block_for', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['retry_after', 'block_for']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if(in_array($key, ['retry_after', 'block_for']))
                                            <span class="text-[var(--color-text-secondary)]">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="{{ in_array($key, ['retry_after', 'block_for']) ? 'number' : 'text' }}"
                                            name="queue[connections][beanstalkd][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('queue.connections.beanstalkd.' . $key) }}"
                                            placeholder="{{ $key === 'host' ? 'localhost' : ($key === 'queue' ? 'default' : ($key === 'retry_after' ? '90' : ($key === 'block_for' ? '0' : ''))) }}"
                                            {{ in_array($key, ['retry_after', 'block_for']) ? 'min="0"' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-{{ $key === 'host' ? 'server' : ($key === 'queue' ? 'playlist-play' : ($key === 'retry_after' ? 'timer-outline' : ($key === 'block_for' ? 'clock-outline' : 'check-circle'))) }} text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                    @if($key === 'retry_after')
                                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                            {{ __('Seconds before a failed job is retried') }}
                                        </small>
                                    @elseif($key === 'block_for')
                                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                            {{ __('Seconds to block while waiting for a job (0 for non-blocking)') }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- SQS Queue --}}
                    <div id="sqs_queue" class="queue-config p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-amazon mr-2 text-[var(--color-info)]"></i>
                            {{ __('Amazon SQS Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['key', 'secret', 'prefix', 'queue', 'suffix', 'region', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['key', 'secret']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if(in_array($key, ['key', 'secret']))
                                            <span class="text-[var(--color-text-secondary)]">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="{{ $key === 'secret' ? 'password' : 'text' }}"
                                            name="queue[connections][sqs][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('queue.connections.sqs.' . $key) }}"
                                            placeholder="{{ $key === 'region' ? 'us-east-1' : ($key === 'queue' ? 'default' : '') }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-{{ $key === 'key' ? 'key' : ($key === 'secret' ? 'key-variant' : ($key === 'region' ? 'earth' : ($key === 'queue' ? 'playlist-play' : 'format-letter-case'))) }} text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Redis Queue --}}
                    <div id="redis_queue" class="queue-config p-5 bg-[var(--color-bg-secondary)] rounded-xl shadow-sm border border-[var(--color-border)] hidden">
                        <h4 class="text-md font-semibold text-[var(--color-text-primary)] mb-4 flex items-center">
                            <i class="mdi mdi-redis mr-2 text-[var(--color-danger)]"></i>
                            {{ __('Redis Queue Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['connection', 'queue', 'retry_after', 'block_for', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['retry_after', 'block_for']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if(in_array($key, ['retry_after', 'block_for']))
                                            <span class="text-[var(--color-text-secondary)]">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="{{ in_array($key, ['retry_after', 'block_for']) ? 'number' : 'text' }}"
                                            name="queue[connections][redis][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('queue.connections.redis.' . $key) }}"
                                            placeholder="{{ $key === 'connection' ? 'default' : ($key === 'queue' ? 'default' : ($key === 'retry_after' ? '90' : ($key === 'block_for' ? '0' : ''))) }}"
                                            {{ in_array($key, ['retry_after', 'block_for']) ? 'min="0"' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-{{ $key === 'connection' ? 'connection' : ($key === 'queue' ? 'playlist-play' : ($key === 'retry_after' ? 'timer-outline' : ($key === 'block_for' ? 'clock-outline' : 'check-circle'))) }} text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                    @if($key === 'retry_after')
                                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                            {{ __('Seconds before a failed job is retried') }}
                                        </small>
                                    @elseif($key === 'block_for')
                                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                            {{ __('Seconds to block while waiting for a job (0 for non-blocking)') }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queue Worker Settings -->
            <div class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-info-light)] rounded-lg mr-3">
                        <i class="mdi mdi-worker text-[var(--color-info)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Queue Worker Settings') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Timeout') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="queue[timeout]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('queue.timeout', 60) }}"
                                min="1"
                                placeholder="60">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-[var(--color-text-secondary)]">sec</span>
                            </div>
                        </div>
                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                            {{ __('Maximum seconds a job can run') }}
                        </small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Sleep') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="queue[sleep]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('queue.sleep', 3) }}"
                                min="0"
                                placeholder="3">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-[var(--color-text-secondary)]">sec</span>
                            </div>
                        </div>
                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                            {{ __('Seconds to sleep when no job is available') }}
                        </small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Tries') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="queue[tries]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                value="{{ config('queue.tries', 1) }}"
                                min="1"
                                placeholder="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-repeat text-[var(--color-text-secondary)]"></i>
                            </div>
                        </div>
                        <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                            {{ __('Maximum number of job attempts') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Queue Actions -->
            <div class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-warning-light)] rounded-lg mr-3">
                        <i class="mdi mdi-play-circle-outline text-[var(--color-warning)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Queue Management') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="button" id="restart-worker" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-primary)] text-white rounded-lg shadow-md hover:bg-[var(--color-primary-hover)] transition-colors duration-300">
                        <i class="mdi mdi-restart mr-2"></i>
                        {{ __('Restart Queue Worker') }}
                    </button>
                    <button type="button" id="clear-failed" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-danger)] text-white rounded-lg shadow-md hover:bg-[var(--color-danger-hover)] transition-colors duration-300">
                        <i class="mdi mdi-trash-can-outline mr-2"></i>
                        {{ __('Clear Failed Jobs') }}
                    </button>
                    <button type="button" id="retry-failed" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-warning)] text-white rounded-lg shadow-md hover:bg-[var(--color-warning-hover)] transition-colors duration-300">
                        <i class="mdi mdi-repeat mr-2"></i>
                        {{ __('Retry Failed Jobs') }}
                    </button>
                    <button type="button" id="monitor-queue" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-info)] text-white rounded-lg shadow-md hover:bg-[var(--color-info-hover)] transition-colors duration-300">
                        <i class="mdi mdi-monitor-dashboard mr-2"></i>
                        {{ __('Monitor Queue Status') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('queue_selector');
            const configs = document.querySelectorAll('.queue-config');

            function updateVisibility() {
                configs.forEach(config => config.classList.add('hidden'));
                const selectedQueue = selector.value + '_queue';
                const activeConfig = document.getElementById(selectedQueue);
                if (activeConfig) activeConfig.classList.remove('hidden');
            }

            // Initialize with current value
            updateVisibility();

            // Change on selection
            selector.addEventListener('change', updateVisibility);

            // Queue management actions
            const queueButtons = ['restart-worker', 'clear-failed', 'retry-failed', 'monitor-queue'];
            queueButtons.forEach(buttonId => {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.addEventListener('click', function() {
                        const action = this.id.replace('-', '_');
                        handleQueueAction(action);
                    });
                }
            });

            function handleQueueAction(action) {
                const button = document.getElementById(action.replace('_', '-'));
                const originalText = button.innerHTML;
                const actionNames = {
                    'restart_worker': 'Queue Worker',
                    'clear_failed': 'Failed Jobs',
                    'retry_failed': 'Failed Jobs',
                    'monitor_queue': 'Queue Monitoring'
                };

                button.innerHTML = '<i class="mdi mdi-loading animate-spin mr-2"></i>Processing...';
                button.disabled = true;

                // Simulate API call (replace with actual implementation)
                setTimeout(() => {
                    const success = Math.random() > 0.2; // 80% success rate for demo
                    
                    if (success) {
                        showToast(`{{ __(":action completed successfully") }}`.replace(':action', actionNames[action]), 'success');
                    } else {
                        showToast(`{{ __("Failed to complete :action") }}`.replace(':action', actionNames[action]), 'error');
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