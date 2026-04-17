@extends('admin.settings.setting')

@section('form')
    <div>
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="flex flex-wrap gap-2" aria-label="SEO Tabs">
                <button type="button" data-tab="login"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200
                           bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                           hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i class="mdi mdi-login mr-2"></i>
                    {{ __('Login') }}
                </button>
                <button type="button" data-tab="register"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200
                           bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                           hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i class="mdi mdi-account-plus mr-2"></i>
                    {{ __('Register') }}
                </button>
                <button type="button" data-tab="forgot"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200
                           bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                           hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i class="mdi mdi-lock-reset mr-2"></i>
                    {{ __('Forgot Password') }}
                </button>
                <button type="button" data-tab="plans"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200
                           bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                           hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i class="mdi mdi-view-dashboard mr-2"></i>
                    {{ __('Plans') }}
                </button>
            </nav>
        </div>

        <!-- Tabs Content -->
        <div class="space-y-6">
            <!-- Login Tab -->
            <div data-tab-content="login" class="tab-content hidden">
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="mdi mdi-information-outline text-blue-500"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Login Page SEO') }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('This content will be displayed on the login page. Add meta tags or scripts specific to authentication pages.') }}
                    </p>
                </div>
                <x-editor label="{{ __('Login page content') }}" name="seo[login]" content="{{ config('seo.login') }}"
                    preview="{{ false }}" jodit="{{ false }}" />
            </div>

            <!-- Register Tab -->
            <div data-tab-content="register" class="tab-content hidden">
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="mdi mdi-information-outline text-blue-500"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Register Page SEO') }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('This content will be displayed on the registration page. Optimize it for search engines and social sharing.') }}
                    </p>
                </div>
                <x-editor label="{{ __('Register page content') }}" name="seo[register]"
                    content="{{ config('seo.register') }}" preview="{{ false }}" jodit="{{ false }}" />
            </div>

            <!-- Forgot Password Tab -->
            <div data-tab-content="forgot" class="tab-content hidden">
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="mdi mdi-information-outline text-blue-500"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Forgot Password Page SEO') }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('This content will be displayed on the password reset page. Add relevant meta information for this page.') }}
                    </p>
                </div>
                <x-editor label="{{ __('Forgot password page content') }}" name="seo[forgot-password]"
                    content="{{ config('seo.forgot-password') }}" preview="{{ false }}"
                    jodit="{{ false }}" />
            </div>

            <!-- Plans Tab -->
            <div data-tab-content="plans" class="tab-content hidden">
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="mdi mdi-information-outline text-blue-500"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Plans Page SEO') }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('This content will be displayed on the pricing plans page. Use it to add structured data for pricing, meta tags, or tracking scripts.') }}
                    </p>
                </div>

                <!-- Additional info for Plans page -->
                <div
                    class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                    <div class="flex items-start gap-2">
                        <i class="mdi mdi-tag-multiple text-green-600 dark:text-green-400 text-lg mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-green-800 dark:text-green-300">
                                {{ __('Pricing Page Tips') }}</h4>
                            <ul class="mt-1 text-xs text-green-700 dark:text-green-400 space-y-1">
                                <li>• {{ __('Add Product schema for each pricing tier') }}</li>
                                <li>• {{ __('Include Offer schema with price and currency') }}</li>
                                <li>• {{ __('Add AggregateOffer for multiple plans') }}</li>
                                <li>• {{ __('Use comparison tables in structured data') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <x-editor label="{{ __('Plans page content') }}" name="seo[plans]" content="{{ config('seo.plans') }}"
                    preview="{{ false }}" jodit="{{ false }}" />
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <div class="flex items-start gap-3">
                <i class="mdi mdi-code-tags text-blue-600 dark:text-blue-400 text-xl mt-0.5"></i>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300">{{ __('What can I add here?') }}
                    </h4>
                    <ul class="mt-2 text-sm text-blue-700 dark:text-blue-400 space-y-1">
                        <li>• {{ __('JSON-LD structured data for rich snippets') }}</li>
                        <li>• {{ __('Open Graph meta tags for social sharing') }}</li>
                        <li>• {{ __('Twitter Card meta tags') }}</li>
                        <li>• {{ __('Canonical URLs and alternate language versions') }}</li>
                        <li>• {{ __('Tracking scripts (Google Analytics, Pixel, etc.)') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('[data-tab-content]');

            function switchTab(tabId, updateUrl = true) {
                // Hide all contents
                contents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active styles from all tabs
                tabs.forEach(tab => {
                    tab.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                    tab.classList.add('bg-gray-100', 'dark:bg-gray-800', 'text-gray-700',
                        'dark:text-gray-300');
                });

                // Show selected content
                const selectedContent = document.querySelector(`[data-tab-content="${tabId}"]`);
                if (selectedContent) {
                    selectedContent.classList.remove('hidden');
                }

                // Add active styles to selected tab
                const selectedTab = document.querySelector(`[data-tab="${tabId}"]`);
                if (selectedTab) {
                    selectedTab.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'text-gray-700',
                        'dark:text-gray-300');
                    selectedTab.classList.add('bg-blue-600', 'text-white', 'shadow-md');
                }

                // Update URL hash without scrolling
                if (updateUrl) {
                    const newUrl = new URL(window.location.href);
                    newUrl.hash = tabId;
                    window.history.pushState({
                        tab: tabId
                    }, '', newUrl);
                }

                // Save to localStorage
                localStorage.setItem('seo_active_tab', tabId);
            }

            // Add click handlers to tabs
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId, true);
                });
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(event) {
                const hash = window.location.hash.replace('#', '');
                if (hash && document.querySelector(`[data-tab="${hash}"]`)) {
                    switchTab(hash, false);
                } else {
                    // Default to landing tab
                    switchTab('login', false);
                }
            });

            // Restore tab from URL hash first, then from localStorage
            let initialTab = null;
            const urlHash = window.location.hash.replace('#', '');

            if (urlHash && document.querySelector(`[data-tab="${urlHash}"]`)) {
                initialTab = urlHash;
            } else {
                const savedTab = localStorage.getItem('seo_active_tab');
                if (savedTab && document.querySelector(`[data-tab="${savedTab}"]`)) {
                    initialTab = savedTab;
                }
            }

            // Default to landing if no tab found
            if (!initialTab) {
                initialTab = 'login';
            }

            // Switch to the initial tab without updating URL (to avoid duplicate history)
            switchTab(initialTab, false);

            // Update URL hash to match initial tab if it doesn't match
            if (window.location.hash !== `#${initialTab}`) {
                const newUrl = new URL(window.location.href);
                newUrl.hash = initialTab;
                window.history.replaceState({
                    tab: initialTab
                }, '', newUrl);
            }
        });
    </script>
@endpush

@push('css')
    <style nonce="{{ $nonce ?? '' }}">
        .tab-btn {
            transition: all 0.2s ease;
        }

        .tab-btn i {
            transition: transform 0.2s ease;
        }

        .tab-btn:hover i {
            transform: translateY(-2px);
        }

        .tab-content {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
