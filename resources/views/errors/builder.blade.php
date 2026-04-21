<x-layout>
    <x-slot name="head">
        <title>Builder Error - Draft Mode</title>
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Builder Error</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Draft Mode - Rendering Exception</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <x-theme />
                <x-profile />
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-5 bg-gray-50 dark:bg-gray-900 min-h-screen">

        {{-- Error Summary Banner --}}
        <div
            class="rounded-xl p-5 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/30 dark:to-orange-950/30 border border-red-200 dark:border-red-800">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-1">{{ $message }}</h2>
                    <p class="text-sm text-red-600 dark:text-red-400">
                        Thrown in <span class="font-mono">{{ class_basename($exception ?? 'Exception') }}</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Page Information Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Page Details --}}
            <div
                class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Page Information
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Name</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $page->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Slug</p>
                            <p class="text-sm font-mono text-gray-700 dark:text-gray-300">{{ $page->slug ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Status</p>
                            <div class="flex items-center gap-2">
                                @if ($page->is_published ?? false)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Published
                                    </span>
                                @endif
                                @if ($page->is_draft ?? false)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd"
                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Draft
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">ID</p>
                            <p class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $page->id ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- File Location --}}
            <div
                class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        File Location
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Error Location</p>
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                            <code class="text-sm text-gray-900 dark:text-gray-300 break-all">{{ $file }}</code>
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Line: {{ $line }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Template Path</p>
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                            <code
                                class="text-xs text-gray-700 dark:text-gray-400 break-all">{{ $page->path ?? 'N/A' }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Error Message Details --}}
        <div
            class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Error Details
                </h3>
            </div>
            <div class="p-5">
                <pre
                    class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words font-mono bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-800">{{ $message }}</pre>
            </div>
        </div>

        {{-- Stack Trace --}}
        <div x-data="{ open: true }"
            class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                <button @click="open = !open" class="w-full flex items-center justify-between group">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Stack Trace
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-normal ml-2">(Last 10 frames)</span>
                    </h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="open" x-transition>
                <div class="p-5">
                    <div class="space-y-3">
                        @foreach ($trace as $i => $item)
                            <div
                                class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-800">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-gray-200 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                        <span
                                            class="text-xs font-mono font-semibold text-gray-600 dark:text-gray-400">#{{ $i }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $item['function'] ?? 'closure' }}()
                                            </span>
                                            @if (!empty($item['class']))
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400">{{ $item['class'] }}{{ $item['type'] ?? '::' }}</span>
                                            @endif
                                        </div>
                                        @if (!empty($item['file']))
                                            <div class="bg-gray-100 dark:bg-gray-900 rounded p-2 mt-2">
                                                <code
                                                    class="text-xs text-gray-700 dark:text-gray-300 break-all">{{ $item['file'] }}</code>
                                                @if (!empty($item['line']))
                                                    <span
                                                        class="text-xs text-yellow-600 dark:text-yellow-400 ml-2">Line
                                                        {{ $item['line'] }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500 dark:text-gray-400">[internal function]</p>
                                        @endif

                                        @if (!empty($item['args']))
                                            <details class="mt-2">
                                                <summary
                                                    class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-300">
                                                    Arguments ({{ count($item['args']) }})
                                                </summary>
                                                <pre class="mt-2 text-xs bg-gray-100 dark:bg-gray-900 p-2 rounded overflow-x-auto">{{ json_encode($item['args'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </details>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Environment Info --}}
        <div
            class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Environment Information
                </h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">PHP Version</p>
                        <p class="font-mono text-gray-900 dark:text-white">{{ phpversion() }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Laravel Version</p>
                        <p class="font-mono text-gray-900 dark:text-white">{{ app()->version() }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Environment</p>
                        <p class="font-mono text-gray-900 dark:text-white">{{ app()->environment() }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Debug Mode</p>
                        <p class="font-mono text-gray-900 dark:text-white">
                            {{ config('app.debug') ? 'Enabled' : 'Disabled' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center text-xs text-gray-500 dark:text-gray-400 py-4">
            <p>This error page is only visible in draft mode. Check your template syntax and data bindings.</p>
        </div>
    </div>
</x-layout>
