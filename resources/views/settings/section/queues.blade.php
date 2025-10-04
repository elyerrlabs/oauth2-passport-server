@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-gray-100 rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-indigo-600 text-white p-6 rounded-2xl shadow-md">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-playlist-play text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Queue Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure job queue connections and processing') }}
                </p>
            </div>

            <div class="mt-4 p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-indigo-600"></i>
                    {{ __('Performance Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-gray-500">
                    <li class="flex items-start">
                        <i class="mdi mdi-rocket-launch-outline text-green-500 mr-2 mt-0.5"></i>
                        {{ __('Use Redis for high-performance queue processing') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-database text-yellow-500 mr-2 mt-0.5"></i>
                        {{ __('Database driver is good for development and small apps') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-amazon text-blue-500 mr-2 mt-0.5"></i>
                        {{ __('SQS is ideal for distributed cloud applications') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Default Queue Configuration -->
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-lg mr-3">
                        <i class="mdi mdi-playlist-star text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Default Queue Connection') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('Default Queue Driver') }}
                            <span class="text-gray-500">*</span>
                        </label>
                        <select id="queue_selector" name="queue[default]"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300">
                            @foreach (['sync', 'database', 'beanstalkd', 'sqs', 'redis'] as $driver)
                                <option value="{{ $driver }}"
                                    {{ config('queue.default') == $driver ? 'selected' : '' }}>
                                    {{ ucfirst($driver) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-2 text-sm text-gray-500">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Select the default queue connection for job processing') }}
                        </small>
                    </div>
                </div>

                <!-- Queue-Specific Configurations -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    {{-- Database Queue --}}
                    <div id="database_queue"
                        class="queue-config p-5 bg-gray-50 rounded-xl shadow-sm border border-gray-200 hidden">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="mdi mdi-database mr-2 text-green-500"></i>
                            {{ __('Database Queue Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['table', 'queue', 'retry_after', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['retry_after']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-gray-800 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if ($key === 'retry_after')
                                            <span class="text-gray-500">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="{{ $key === 'retry_after' ? 'number' : 'text' }}"
                                            name="queue[connections][database][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                                            value="{{ config('queue.connections.database.' . $key) }}"
                                            placeholder="{{ $key === 'table' ? 'jobs' : ($key === 'queue' ? 'default' : ($key === 'retry_after' ? '90' : '')) }}"
                                            {{ $key === 'retry_after' ? 'min="1"' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'table' ? 'table' : ($key === 'queue' ? 'playlist-play' : ($key === 'retry_after' ? 'timer-outline' : 'check-circle')) }} text-gray-500"></i>
                                        </div>
                                    </div>
                                    @if ($key === 'retry_after')
                                        <small class="block mt-1 text-sm text-gray-500">
                                            {{ __('Number of seconds before a failed job is retried') }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Beanstalkd Queue --}}
                    <div id="beanstalkd_queue"
                        class="queue-config p-5 bg-gray-50 rounded-xl shadow-sm border border-gray-200 hidden">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="mdi mdi-server mr-2 text-yellow-500"></i>
                            {{ __('Beanstalkd Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['host', 'queue', 'retry_after', 'block_for', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['retry_after', 'block_for']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-gray-800 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if (in_array($key, ['retry_after', 'block_for']))
                                            <span class="text-gray-500">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="{{ in_array($key, ['retry_after', 'block_for']) ? 'number' : 'text' }}"
                                            name="queue[connections][beanstalkd][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                                            value="{{ config('queue.connections.beanstalkd.' . $key) }}"
                                            placeholder="{{ $key === 'host' ? 'localhost' : ($key === 'queue' ? 'default' : ($key === 'retry_after' ? '90' : ($key === 'block_for' ? '0' : ''))) }}"
                                            {{ in_array($key, ['retry_after', 'block_for']) ? 'min="0"' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'host' ? 'server' : ($key === 'queue' ? 'playlist-play' : ($key === 'retry_after' ? 'timer-outline' : ($key === 'block_for' ? 'clock-outline' : 'check-circle'))) }} text-gray-500"></i>
                                        </div>
                                    </div>
                                    @if ($key === 'retry_after')
                                        <small class="block mt-1 text-sm text-gray-500">
                                            {{ __('Seconds before a failed job is retried') }}
                                        </small>
                                    @elseif($key === 'block_for')
                                        <small class="block mt-1 text-sm text-gray-500">
                                            {{ __('Seconds to block while waiting for a job (0 for non-blocking)') }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- SQS Queue --}}
                    <div id="sqs_queue"
                        class="queue-config p-5 bg-gray-50 rounded-xl shadow-sm border border-gray-200 hidden">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="mdi mdi-amazon mr-2 text-blue-500"></i>
                            {{ __('Amazon SQS Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['key', 'secret', 'prefix', 'queue', 'suffix', 'region', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['key', 'secret']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-gray-800 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if (in_array($key, ['key', 'secret']))
                                            <span class="text-gray-500">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="{{ $key === 'secret' ? 'password' : 'text' }}"
                                            name="queue[connections][sqs][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                                            value="{{ config('queue.connections.sqs.' . $key) }}"
                                            placeholder="{{ $key === 'region' ? 'us-east-1' : ($key === 'queue' ? 'default' : '') }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'key' ? 'key' : ($key === 'secret' ? 'key-variant' : ($key === 'region' ? 'earth' : ($key === 'queue' ? 'playlist-play' : 'format-letter-case'))) }} text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Redis Queue --}}
                    <div id="redis_queue"
                        class="queue-config p-5 bg-gray-50 rounded-xl shadow-sm border border-gray-200 hidden">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="mdi mdi-redis mr-2 text-red-500"></i>
                            {{ __('Redis Queue Configuration') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['connection', 'queue', 'retry_after', 'block_for', 'after_commit'] as $key)
                                <div class="{{ in_array($key, ['retry_after', 'block_for']) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-sm font-medium text-gray-800 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        @if (in_array($key, ['retry_after', 'block_for']))
                                            <span class="text-gray-500">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="{{ in_array($key, ['retry_after', 'block_for']) ? 'number' : 'text' }}"
                                            name="queue[connections][redis][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                                            value="{{ config('queue.connections.redis.' . $key) }}"
                                            placeholder="{{ $key === 'connection' ? 'default' : ($key === 'queue' ? 'default' : ($key === 'retry_after' ? '90' : ($key === 'block_for' ? '0' : ''))) }}"
                                            {{ in_array($key, ['retry_after', 'block_for']) ? 'min="0"' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="mdi mdi-{{ $key === 'connection' ? 'connection' : ($key === 'queue' ? 'playlist-play' : ($key === 'retry_after' ? 'timer-outline' : ($key === 'block_for' ? 'clock-outline' : 'check-circle'))) }} text-gray-500"></i>
                                        </div>
                                    </div>
                                    @if ($key === 'retry_after')
                                        <small class="block mt-1 text-sm text-gray-500">
                                            {{ __('Seconds before a failed job is retried') }}
                                        </small>
                                    @elseif($key === 'block_for')
                                        <small class="block mt-1 text-sm text-gray-500">
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
            <div
                class="p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-3">
                        <i class="mdi mdi-worker text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Queue Worker Settings') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('Timeout') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="queue[timeout]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                                value="{{ config('queue.timeout', 60) }}" min="1" placeholder="60">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">sec</span>
                            </div>
                        </div>
                        <small class="block mt-1 text-sm text-gray-500">
                            {{ __('Maximum seconds a job can run') }}
                        </small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('Sleep') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="queue[sleep]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                                value="{{ config('queue.sleep', 3) }}" min="0" placeholder="3">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">sec</span>
                            </div>
                        </div>
                        <small class="block mt-1 text-sm text-gray-500">
                            {{ __('Seconds to sleep when no job is available') }}
                        </small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            {{ __('Tries') }}
                        </label>
                        <div class="relative">
                            <input type="number" name="queue[tries]"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors duration-300"
                                value="{{ config('queue.tries', 1) }}" min="1" placeholder="1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-repeat text-gray-500"></i>
                            </div>
                        </div>
                        <small class="block mt-1 text-sm text-gray-500">
                            {{ __('Maximum number of job attempts') }}
                        </small>
                    </div>
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

        });
    </script>
@endpush
