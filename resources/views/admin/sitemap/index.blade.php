@php
    $routes = [
        [
            'name' => 'Sitemap URLs',
            'route' => route('admin.sitemaps.index'),
            'icon' => 'mdi mdi-sitemap',
        ],
        [
            'name' => 'Robots.txt',
            'route' => route('admin.sitemaps.robot.form'),
            'icon' => 'mdi mdi-robot',
        ],
    ];
@endphp

<x-admin-layout :routes="$routes">
    <v-slot:main>
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Sitemap Management') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Manage and optimize your website sitemap for SEO') }}</p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="resetSitemapBtn"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ __('Reset Sitemap') }}
                </button>
            </div>
        </div>

        {{-- Manual URL Form --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Add URL Manually') }}
            </h3>

            <form method="POST" action="{{ route('admin.sitemaps.store') }}" id="addUrlForm">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- URL --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="url">
                            {{ __('URL') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="url" name="url" id="url" value="{{ old('url') }}"
                            placeholder="https://example.com/page" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('url') ? 'border-red-500 dark:border-red-500' : '' }}" />
                        @error('url')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image URL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="image">
                            {{ __('Image URL') }}
                        </label>
                        <input type="url" name="image" id="image" value="{{ old('image') }}"
                            placeholder="https://example.com/image.jpg"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('image') ? 'border-red-500 dark:border-red-500' : '' }}" />
                        @error('image')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Change Frequency --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="changefreq">
                            {{ __('Change Frequency') }}
                        </label>
                        <select name="changefreq" id="changefreq"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('changefreq') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @php
                                $freqOptions = [
                                    'always' => __('Always'),
                                    'hourly' => __('Hourly'),
                                    'daily' => __('Daily'),
                                    'weekly' => __('Weekly'),
                                    'monthly' => __('Monthly'),
                                    'yearly' => __('Yearly'),
                                    'never' => __('Never'),
                                ];
                                $selectedFreq = old('changefreq', 'weekly');
                            @endphp

                            @foreach ($freqOptions as $value => $label)
                                <option value="{{ $value }}" {{ $selectedFreq == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('changefreq')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="priority">
                            {{ __('Priority') }}
                        </label>
                        <input type="number" name="priority" id="priority" value="{{ old('priority', '0.5') }}"
                            min="0.1" max="1" step="0.1" placeholder="{{ __('Priority (0.1 - 1.0)') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('priority') ? 'border-red-500 dark:border-red-500' : '' }}" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Higher numbers indicate greater importance') }}
                        </p>
                        @error('priority')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors font-medium flex items-center justify-center gap-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            {{ __('Add URL') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Detected Routes List --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-2 mb-4">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ __('Detected Routes') }}
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('Total:') }} {{ count($data ?? []) }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($data ?? [] as $index => $item)
                    <div
                        class="p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex flex-col md:flex-row md:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                {{-- URL with number --}}
                                <div class="font-medium text-blue-700 dark:text-blue-400 text-sm mb-2">
                                    <span class="text-gray-500 dark:text-gray-400 mr-1">{{ $index + 1 }}.</span>
                                    <span class="break-words">{{ $item['url'] }}</span>
                                </div>

                                {{-- Image Thumbnail --}}
                                @if (!empty($item['image']))
                                    <div class="mt-2">
                                        <img src="{{ $item['image'] }}" alt="{{ __('Route image') }}"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                                            loading="lazy" />
                                    </div>
                                @endif

                                {{-- Additional Info --}}
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 space-y-1">
                                    @if (!empty($item['changefreq']))
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="truncate">{{ __('Change Frequency:') }}
                                                {{ $item['changefreq'] }}</span>
                                        </div>
                                    @endif

                                    @if (isset($item['priority']))
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            <span>{{ __('Priority:') }} {{ $item['priority'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-row xs:flex-col gap-2 self-start">
                                @if (empty($item['registered']))
                                    <form method="POST" action="{{ route('admin.sitemaps.store') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="url" value="{{ $item['url'] }}">
                                        <input type="hidden" name="image" value="{{ $item['image'] ?? '' }}">
                                        <input type="hidden" name="changefreq"
                                            value="{{ $item['changefreq'] ?? 'weekly' }}">
                                        <input type="hidden" name="priority"
                                            value="{{ $item['priority'] ?? 0.5 }}">
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center gap-1 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            {{ __('Add') }}
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                        action="{{ route('admin.sitemaps.delete', $item['id'] ?? 0) }}"
                                        onsubmit="return confirm('{{ __('Are you sure you want to delete this route?') }}')"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center gap-1 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">
                            {{ __('No routes detected. Add URLs manually.') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Reset Modal with Headless UI --}}
        <div x-data="{ open: false }" x-on:open-modal.window="open = true" x-on:close-modal.window="open = false"
            x-on:keydown.escape.window="open = false">
            {{-- Modal Trigger --}}
            <button id="resetSitemapTrigger" @click="open = true" class="hidden"></button>

            {{-- Modal Dialog --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

                {{-- Overlay --}}
                <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm"></div>

                {{-- Modal Panel --}}
                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div x-show="open" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        @click.away="open = false"
                        class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-auto">

                        <div class="p-6">
                            {{-- Icon --}}
                            <div
                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">
                                {{ __('Reset Sitemap') }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
                                {{ __('Are you sure you want to reset the sitemap? This action cannot be undone and all URLs will be permanently deleted.') }}
                            </p>

                            {{-- Actions --}}
                            <div class="flex flex-col sm:flex-row sm:justify-center gap-3">
                                <button type="button" @click="open = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    {{ __('Cancel') }}
                                </button>

                                <form method="POST" action="{{ route('admin.sitemaps.reset') }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                        {{ __('Reset Sitemap') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-slot:main>

    @push('js')
        <script nonce="{{ $nonce }}">
            (function() {
                const resetBtn = document.getElementById('resetSitemapBtn');
                const modalTrigger = document.getElementById('resetSitemapTrigger');

                if (resetBtn && modalTrigger) {
                    resetBtn.addEventListener('click', function() {
                        modalTrigger.click();
                    });
                }
            })();
        </script>
    @endpush
</x-admin-layout>
