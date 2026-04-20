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

    $currentDomain = request()->getSchemeAndHttpHost();
    $sitemapUrl = $currentDomain . '/sitemap.xml';

    $templates = [
        'allowAll' => "# Allow all search engines\n# Domain: {$currentDomain}\n\nUser-agent: *\nAllow: /\n\n# Sitemap location\nSitemap: {$sitemapUrl}",
        'blockAll' => "# Block all search engines\n# Domain: {$currentDomain}\n\nUser-agent: *\nDisallow: /\n\n# Sitemap location\nSitemap: {$sitemapUrl}",
        'blockAdmin' => "# Block admin and private areas\n# Domain: {$currentDomain}\n\nUser-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /private/\nDisallow: /dashboard/\nDisallow: /api/\nDisallow: /storage/\n\n# Sitemap location\nSitemap: {$sitemapUrl}",
        'custom' => "# Custom robots.txt configuration\n# Domain: {$currentDomain}\n\nUser-agent: *\nAllow: /\nDisallow: /private/\n\n# Crawl delay (optional)\n# Crawl-delay: 10\n\n# Sitemap location\nSitemap: {$sitemapUrl}",
        'comprehensive' =>
            "# Comprehensive robots.txt configuration\n# Generated for: {$currentDomain}\n# Last updated: " .
            date('Y-m-d') .
            "\n\n# Allow all major search engines\nUser-agent: *\nAllow: /\n\n# Block sensitive directories\nDisallow: /admin/\nDisallow: /private/\nDisallow: /dashboard/\nDisallow: /api/\nDisallow: /storage/\nDisallow: /config/\nDisallow: /vendor/\nDisallow: /node_modules/\nDisallow: /.env\nDisallow: /backup/\nDisallow: /logs/\n\n# Block common files\nDisallow: /package.json\nDisallow: /composer.json\nDisallow: /yarn.lock\nDisallow: /package-lock.json\n\n# Sitemap locations\nSitemap: {$sitemapUrl}",
    ];

    $editorContent = old(
        'content',
        $content ??
            "# Default robots.txt configuration\n# Generated for: {$currentDomain}\n\nUser-agent: *\nAllow: /\n\n# Sitemap location\nSitemap: {$sitemapUrl}",
    );
@endphp

<x-admin-layout :routes="$routes">
    <v-slot:main>
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Robots.txt Manager') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Configure and customize the robots.txt file to control search engine access to your website.') }}
                </p>
            </div>
        </div>

        {{-- Container --}}
        <div class="p-6 rounded-xl bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700">

            {{-- URL Information Cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div
                    class="bg-gradient-to-r from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-blue-800 dark:text-blue-300">{{ __('Current Domain') }}
                            </div>
                            <div class="text-sm text-blue-600 dark:text-blue-400 font-mono break-all">
                                {{ $currentDomain }}</div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-r from-green-50 to-green-100/50 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-green-800 dark:text-green-300">
                                {{ __('Sitemap Location') }}</div>
                            <div class="text-sm text-green-600 dark:text-green-400 font-mono break-all">
                                {{ $sitemapUrl }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Editor Section --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Robots.txt File Content') }}
                    </label>
                    <button type="button" id="applyComprehensiveBtn" data-template="comprehensive"
                        class="px-4 py-2 cursor-pointer rounded-md font-medium transition text-white bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-800 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ __('Use Comprehensive Template') }}</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.sitemaps.robot.update') }}" id="robotForm">
                    @csrf
                    <textarea id="editor"
                        class="min-h-[500px] w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        name="content">{{ $editorContent }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            {{-- Help Information --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-blue-800 dark:text-blue-300 text-sm">
                        <p class="font-medium mb-1">{{ __('What is robots.txt?') }}</p>
                        <p>{{ __('The robots.txt file tells search engines which pages on your site they can crawl and which they cannot. Use \'User-agent\' to specify the robot and \'Disallow\' to block sections.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Quick Templates --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Quick Templates') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Allow All --}}
                    <button type="button"
                        class="template-btn p-4 cursor-pointer text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:shadow-md"
                        data-template="allowAll">
                        <div
                            class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900 dark:text-white mb-1">{{ __('Allow All') }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Allow all search engines to crawl entire site') }}</div>
                    </button>

                    {{-- Block All --}}
                    <button type="button"
                        class="template-btn p-4 cursor-pointer text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:shadow-md"
                        data-template="blockAll">
                        <div
                            class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900 dark:text-white mb-1">{{ __('Block All') }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Block all search engines from crawling the site') }}</div>
                    </button>

                    {{-- Block Admin Areas --}}
                    <button type="button"
                        class="template-btn p-4 cursor-pointer text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:shadow-md"
                        data-template="blockAdmin">
                        <div
                            class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900 dark:text-white mb-1">{{ __('Block Admin Areas') }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Block access to admin and private sections') }}</div>
                    </button>

                    {{-- Custom Configuration --}}
                    <button type="button"
                        class="template-btn p-4 cursor-pointer text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:shadow-md"
                        data-template="custom">
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900 dark:text-white mb-1">{{ __('Custom Configuration') }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Start with a basic custom configuration') }}</div>
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" id="resetDefaultBtn"
                    class="px-6 py-3 cursor-pointer rounded-lg font-medium transition text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>{{ __('Reset to Default') }}</span>
                </button>
                <button type="submit" form="robotForm"
                    class="px-6 py-3 cursor-pointer rounded-lg font-medium transition text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ __('Update Robots.txt') }}</span>
                </button>
            </div>
        </div>
    </v-slot:main>

    @push('js')
        <script nonce="{{ $nonce }}">
            (function($) {
                'use strict';

                const templates = @json($templates);
                const defaultTemplate = @json($editorContent);

                // Función para establecer el contenido del editor
                function setEditorContent(content) {
                    $('#editor').val(content);
                }

                // Función para aplicar plantilla
                function applyTemplate(templateKey) {
                    if (templates[templateKey]) {
                        setEditorContent(templates[templateKey]);
                    }
                }

                // Esperar a que el DOM esté listo
                $(document).ready(function() {
                    // Template buttons
                    $('.template-btn').on('click', function(e) {
                        e.preventDefault();
                        var template = $(this).data('template');
                        applyTemplate(template);
                    });

                    // Comprehensive button
                    $('#applyComprehensiveBtn').on('click', function(e) {
                        e.preventDefault();
                        var template = $(this).data('template');
                        applyTemplate(template);
                    });

                    // Reset button
                    $('#resetDefaultBtn').on('click', function(e) {
                        e.preventDefault();
                        setEditorContent(defaultTemplate);
                    });
                });

            })(jQuery);
        </script>
    @endpush
</x-admin-layout>
