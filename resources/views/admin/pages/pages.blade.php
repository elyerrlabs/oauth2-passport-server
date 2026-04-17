@extends('layouts.pages')

@section('header')
    <nav id="pageManagerHeader"
        class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 py-4 shadow-sm transition-colors duration-300">
        <div class="container mx-auto flex justify-between items-center px-6">
            <!-- Back Button -->
            <a href="{{ route('user.dashboard') }}"
                class="flex items-center space-x-2 text-gray-700 dark:text-gray-200 font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-300 group">
                <i class="mdi mdi-arrow-left text-2xl group-hover:-translate-x-1 transition-transform duration-300"></i>
                <span class="relative">
                    {{ __('Back to Dashboard') }}
                    <span
                        class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 dark:bg-blue-400 group-hover:w-full transition-all duration-300"></span>
                </span>
            </a>

            <!-- Theme Toggle and Mobile Menu Button -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Menu Toggle Button -->
                <button id="mobileMenuToggle"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="mdi mdi-menu text-2xl text-gray-700 dark:text-gray-200"></i>
                </button>

                <div class="hidden md:flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    <i class="mdi mdi-cog-outline text-xl"></i>
                    <span class="font-medium">{{ __('Settings') }}</span>
                </div>

                <x-theme />
            </div>
        </div>
    </nav>
@endsection

@section('content')
    @php
        $routes = [
            [
                'name' => 'List of pages',
                'route' => route('admin.pages.index'),
                'icon' => 'mdi mdi-file-document-outline',
            ],
        ];
    @endphp

    <div id="pageManagerLayout"
        class="bg-gray-50 dark:bg-gray-900 md:flex md:items-start overflow-hidden transition-colors duration-300">
        <!-- Sidebar Navigation -->
        <aside id="sidebar"
            class="fixed left-0 bottom-0 md:relative md:inset-auto z-40 bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0"
            style="width: 280px;">

            <!-- Logo & Toggle Button -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div
                        class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                        <span class="text-white font-bold text-sm">P</span>
                    </div>
                    <h1 id="logoText"
                        class="text-xl font-bold text-gray-800 dark:text-white whitespace-nowrap transition-all duration-300">
                        Page Manager
                    </h1>
                </div>

                <!-- Toggle Button -->
                <button id="toggleSidebar"
                    class="p-1.5 cursor-pointer rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i id="toggleIcon" class="mdi mdi-chevron-left text-xl text-gray-600 dark:text-gray-400"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
                @foreach ($routes as $item)
                    <a href="{{ $item['route'] }}"
                        class="flex items-center space-x-3 px-4 py-3 text-gray-600 dark:text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white group {{ request()->url() === $item['route'] ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-l-4 border-blue-600 dark:border-blue-500' : '' }}">
                        <span
                            class="{{ $item['icon'] }} text-xl flex-shrink-0 {{ request()->url() === $item['route'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}"></span>
                        <span class="nav-text font-medium whitespace-nowrap transition-all duration-300">
                            {{ $item['name'] }}
                        </span>
                    </a>
                @endforeach
            </nav>

            <!-- User Profile Section -->
            <div
                class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 mt-auto transition-colors duration-300">
                <div class="flex items-center space-x-3">
                    <div class="relative flex-shrink-0">
                        <img class="w-10 h-10 rounded-full ring-2 ring-gray-200 dark:ring-gray-700 object-cover"
                            src="https://ui-avatars.com/api/?background=3B82F6&color=fff&name={{ urlencode(auth()->user()->name . '+' . auth()->user()->last_name) }}&bold=true"
                            alt="User profile" />
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                    </div>
                    <div class="flex-1 min-w-0 transition-all duration-300">
                        <p class="nav-text text-sm font-semibold text-gray-900 dark:text-white truncate">
                            {{ auth()->user()->name }} {{ auth()->user()->last_name }}
                        </p>
                        <p class="nav-text text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="overlay"
            class="fixed left-0 right-0 bottom-0 bg-black bg-opacity-50 z-30 hidden transition-opacity duration-300 md:hidden">
        </div>

        <!-- Main Content Area -->
        <main id="mainContent" class="flex-1 min-w-0 overflow-y-auto transition-all duration-300 ease-in-out">
            <div class="p-4 sm:p-6">
                @yield('main')
            </div>
        </main>
    </div>
@endsection

@push('css')
    <style nonce="{{ $nonce }}">
        body {
            overflow: hidden;
        }

        #pageManagerHeader {
            position: sticky;
            top: 0;
            z-index: 50;
        }

        #pageManagerLayout {
            height: calc(100vh - var(--page-manager-header-height, 73px));
        }

        /* Sidebar transitions */
        #sidebar {
            top: var(--page-manager-header-height, 73px);
            height: calc(100vh - var(--page-manager-header-height, 73px));
            transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        @media (min-width: 769px) {
            #sidebar {
                position: sticky;
                top: var(--page-manager-header-height, 73px);
                left: auto;
                inset: auto;
                z-index: 20;
            }
        }

        /* Hide text when sidebar is collapsed */
        #sidebar.collapsed {
            width: 80px !important;
        }

        #sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        #sidebar.collapsed #logoText {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        #mainContent {
            flex: 1 1 auto;
            width: 100%;
            min-width: 0;
            height: 100%;
            transition: padding 0.3s ease-in-out;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            #pageManagerLayout {
                display: block;
            }

            #overlay {
                top: var(--page-manager-header-height, 73px);
            }

            #sidebar {
                transform: translateX(-100%);
                z-index: 40;
                width: 280px !important;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            #sidebar.collapsed.mobile-open {
                width: 280px !important;
            }

            #sidebar.collapsed.mobile-open .nav-text {
                opacity: 1;
                width: auto;
                overflow: visible;
            }

            #sidebar.collapsed.mobile-open #logoText {
                opacity: 1;
                width: auto;
                overflow: visible;
            }

            #mainContent {
                width: 100%;
            }
        }

        /* Scrollbar styling */
        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endpush

@push('js')
    <script nonce="{{ $nonce }}">
        (function() {
            // Get sidebar state from localStorage
            const root = document.documentElement;
            const header = document.getElementById('pageManagerHeader');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            const toggleBtn = document.getElementById('toggleSidebar');
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const overlay = document.getElementById('overlay');

            function syncHeaderHeight() {
                if (!header) return;
                root.style.setProperty('--page-manager-header-height', `${header.offsetHeight}px`);
            }

            // Function to update sidebar state
            function setSidebarState(collapsed) {
                if (collapsed) {
                    sidebar.classList.add('collapsed');
                    if (toggleIcon) {
                        toggleIcon.classList.remove('mdi-chevron-left');
                        toggleIcon.classList.add('mdi-chevron-right');
                    }
                } else {
                    sidebar.classList.remove('collapsed');
                    if (toggleIcon) {
                        toggleIcon.classList.remove('mdi-chevron-right');
                        toggleIcon.classList.add('mdi-chevron-left');
                    }
                }
                localStorage.setItem('sidebarCollapsed', collapsed);
            }

            // Apply initial state
            syncHeaderHeight();

            if (isCollapsed && window.innerWidth > 768) {
                setSidebarState(true);
            }

            // Toggle sidebar on desktop
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isCurrentlyCollapsed = sidebar.classList.contains('collapsed');
                    setSidebarState(!isCurrentlyCollapsed);
                });
            }

            // Mobile menu toggle
            function closeMobileMenu() {
                sidebar.classList.remove('mobile-open');
                if (overlay) overlay.classList.add('hidden');
                document.body.style.overflow = 'hidden';
            }

            function openMobileMenu() {
                sidebar.classList.add('mobile-open');
                if (overlay) overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            if (mobileToggle) {
                mobileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (sidebar.classList.contains('mobile-open')) {
                        closeMobileMenu();
                    } else {
                        openMobileMenu();
                    }
                });
            }

            // Close mobile menu when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    closeMobileMenu();
                });
            }

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    syncHeaderHeight();
                    if (window.innerWidth > 768) {
                        closeMobileMenu();
                        const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                        if (collapsed) {
                            setSidebarState(true);
                        } else {
                            setSidebarState(false);
                        }
                    }
                }, 250);
            });

            // Close mobile menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('mobile-open')) {
                    closeMobileMenu();
                }
            });
        })();
    </script>
@endpush
