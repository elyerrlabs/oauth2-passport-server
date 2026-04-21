{{-- resources/views/components/page-manager.blade.php --}}
@props([
    'title' => null,
    'subtitle' => null,
    'routes' => [],
    'logoIcon' => 'mdi-view-dashboard',
    'showUserMenu' => true,
    'showThemeToggle' => true,
])

@php
    $appName = $title ?? config('app.name', 'OAuth2 Server');
@endphp

<x-layout>
    <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-950">

        {{-- Sidebar --}}
        <aside id="sidebar"
            class="sidebar fixed md:sticky top-0 left-0 z-40 flex flex-col h-screen transition-all duration-300 ease-in-out flex-shrink-0
                   bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 w-[260px]">

            {{-- Logo --}}
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center space-x-2 overflow-hidden">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 dark:bg-blue-500">
                        <i class="mdi {{ $logoIcon }} text-white text-lg"></i>
                    </div>
                    <a href="{{ route('user.dashboard') }}" id="logoText"
                        class="text-base font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                        {{ $appName }}
                    </a>
                </div>

                <button id="toggleSidebar"
                    class="hidden md:flex p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i id="toggleIcon" class="mdi mdi-chevron-left text-gray-600 dark:text-gray-400"></i>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @foreach ($routes as $item)
                    @php
                        $isActive = request()->url() === $item['route'] || request()->is(trim($item['route'], '/'));
                    @endphp

                    <a href="{{ $item['route'] }}"
                        @if (isset($item['external']) && $item['external']) target="_blank" rel="noopener noreferrer" @endif
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors
                               {{ $isActive
                                   ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300'
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <i
                            class="{{ $item['icon'] }} text-lg w-5 flex-shrink-0 {{ $isActive ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"></i>
                        <span class="nav-text ml-3 whitespace-nowrap">{{ $item['name'] }}</span>
                        @if (isset($item['badge']))
                            <span class="nav-text ml-auto">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium rounded-full {{ $item['badge']['class'] ?? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                    {{ $item['badge']['text'] }}
                                </span>
                            </span>
                        @endif
                    </a>
                @endforeach

                @if (isset($navigation))
                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-800">
                        {{ $navigation }}
                    </div>
                @endif
            </nav>

            {{-- Sidebar Footer --}}
            @if (isset($sidebarFooter))
                <div class="p-4 border-t border-gray-200 dark:border-gray-800">
                    {{ $sidebarFooter }}
                </div>
            @endif
        </aside>

        {{-- Mobile Overlay --}}
        <div id="sidebarOverlay"
            class="fixed inset-0 bg-black/50 dark:bg-black/70 z-30 hidden md:hidden backdrop-blur-sm"></div>

        {{-- Main Content --}}
        <main id="mainWrapper"
            class="main-wrapper flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50 dark:bg-gray-950 transition-all duration-300 ease-in-out">

            {{-- Header --}}
            <header
                class="flex-shrink-0 flex items-center h-16 px-4 md:px-6 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between w-full">

                    {{-- Left --}}
                    <div class="flex items-center space-x-3">
                        <button id="mobileMenuToggle"
                            class="md:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <i class="mdi mdi-menu text-xl text-gray-700 dark:text-gray-300"></i>
                        </button>

                        @if ($subtitle)
                            <div class="hidden md:block">
                                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appName }}
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                            </div>
                        @else
                            <div class="hidden md:block">
                                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appName }}
                                </h1>
                            </div>
                        @endif
                    </div>

                    {{-- Right --}}
                    <div class="flex items-center space-x-2">
                        @if (isset($headerActions))
                            {{ $headerActions }}
                        @endif

                        @if ($showThemeToggle)
                            <x-theme />
                        @endif

                        @if ($showUserMenu)
                            <x-profile />
                        @endif
                    </div>
                </div>
            </header>

            {{-- Page Header --}}
            @if (isset($pageHeader))
                <div
                    class="flex-shrink-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-4 md:px-6 py-4">
                    {{ $pageHeader }}
                </div>
            @endif

            {{-- Content Area - Único con scroll --}}
            <div id="mainContent" class="flex-1 overflow-y-auto">
                <div class="p-4 md:p-6 h-full">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @push('css')
        <style nonce="{{ $nonce }}">
            html,
            body {
                height: 100%;
                overflow: hidden;
            }

            /* Sidebar colapsado */
            .sidebar.collapsed {
                width: 72px !important;
            }

            .sidebar.collapsed .nav-text,
            .sidebar.collapsed #logoText {
                display: none;
            }

            .sidebar.collapsed nav a {
                justify-content: center;
                padding: 0.75rem;
            }

            .sidebar.collapsed nav a i {
                margin: 0;
            }

            /* Mobile styles */
            @media (max-width: 768px) {
                .sidebar {
                    position: fixed;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    transform: translateX(-100%);
                    width: 260px !important;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                }

                .sidebar.mobile-open {
                    transform: translateX(0);
                }

                .main-wrapper {
                    margin-left: 0 !important;
                    width: 100% !important;
                }

                .sidebar.collapsed.mobile-open {
                    width: 260px !important;
                }

                .sidebar.collapsed.mobile-open .nav-text,
                .sidebar.collapsed.mobile-open #logoText {
                    display: block;
                }

                .sidebar.collapsed.mobile-open nav a {
                    justify-content: flex-start;
                    padding: 0.625rem 0.75rem;
                }

                .sidebar.collapsed.mobile-open nav a i {
                    margin-right: 0.75rem;
                }
            }

            /* Scrollbar */
            #mainContent::-webkit-scrollbar,
            nav::-webkit-scrollbar {
                width: 6px;
            }

            #mainContent::-webkit-scrollbar-track,
            nav::-webkit-scrollbar-track {
                background: transparent;
            }

            #mainContent::-webkit-scrollbar-thumb,
            nav::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 3px;
            }

            .dark #mainContent::-webkit-scrollbar-thumb,
            .dark nav::-webkit-scrollbar-thumb {
                background: #374151;
            }

            #mainContent::-webkit-scrollbar-thumb:hover,
            nav::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }

            .dark #mainContent::-webkit-scrollbar-thumb:hover,
            .dark nav::-webkit-scrollbar-thumb:hover {
                background: #4b5563;
            }

            /* Flex para ocupar todo el espacio */
            .flex-1 {
                flex: 1 1 0%;
                min-height: 0;
            }
        </style>
    @endpush

    @push('js')
        <script nonce="{{ $nonce }}">
            (function($) {
                'use strict';

                var STORAGE_KEY = 'sidebarCollapsed';
                var MOBILE_BREAKPOINT = 768;

                var $sidebar = $('.sidebar');
                var $toggleIcon = $('#toggleIcon');
                var $toggleBtn = $('#toggleSidebar');
                var $overlay = $('#sidebarOverlay');
                var $mobileToggle = $('#mobileMenuToggle');
                var $mainWrapper = $('.main-wrapper');

                function setSidebarState(collapsed) {
                    if (!$sidebar.length) return;

                    $sidebar.toggleClass('collapsed', collapsed);

                    if ($toggleIcon.length) {
                        $toggleIcon.toggleClass('mdi-chevron-left', !collapsed);
                        $toggleIcon.toggleClass('mdi-chevron-right', collapsed);
                    }

                    localStorage.setItem(STORAGE_KEY, collapsed);
                }

                function closeMobileMenu() {
                    $sidebar.removeClass('mobile-open');
                    $overlay.addClass('hidden');
                    $('body').css('overflow', '');
                }

                function openMobileMenu() {
                    $sidebar.addClass('mobile-open');
                    $overlay.removeClass('hidden');
                    $('body').css('overflow', 'hidden');
                }

                function adjustLayout() {
                    var isMobile = $(window).width() < MOBILE_BREAKPOINT;
                    var isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';

                    if (!isMobile) {
                        closeMobileMenu();
                        setSidebarState(isCollapsed);
                    } else {
                        $sidebar.removeClass('collapsed');
                        if ($toggleIcon.length) {
                            $toggleIcon.removeClass('mdi-chevron-right').addClass('mdi-chevron-left');
                        }
                    }
                }

                function init() {
                    adjustLayout();

                    $toggleBtn.on('click', function(e) {
                        e.preventDefault();
                        if ($(window).width() >= MOBILE_BREAKPOINT) {
                            setSidebarState(!$sidebar.hasClass('collapsed'));
                        }
                    });

                    $mobileToggle.on('click', function(e) {
                        e.preventDefault();
                        if ($sidebar.hasClass('mobile-open')) {
                            closeMobileMenu();
                        } else {
                            openMobileMenu();
                        }
                    });

                    $overlay.on('click', closeMobileMenu);

                    $(document).on('keydown', function(e) {
                        if (e.key === 'Escape' && $sidebar.hasClass('mobile-open')) {
                            closeMobileMenu();
                        }
                    });

                    $sidebar.find('a').on('click', function() {
                        if ($(window).width() < MOBILE_BREAKPOINT) {
                            closeMobileMenu();
                        }
                    });

                    var resizeTimer;
                    $(window).on('resize', function() {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(adjustLayout, 100);
                    });
                }

                $(document).ready(init);

            })(jQuery);
        </script>
    @endpush
</x-layout>
