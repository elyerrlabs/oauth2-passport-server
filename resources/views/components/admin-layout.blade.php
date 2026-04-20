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
            class="fixed md:sticky top-0 left-0 z-40 flex flex-col h-screen transition-all duration-300 ease-in-out flex-shrink-0
                   bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800"
            style="width: 260px;">

            {{-- Logo --}}
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center space-x-2 overflow-hidden">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 dark:bg-blue-500">
                        <i class="mdi {{ $logoIcon }} text-white text-lg"></i>
                    </div>
                    <a href="{{ route('user.dashboard') }}" id="logoText" class="text-base font-semibold text-gray-900 dark:text-white whitespace-nowrap">
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

        {{-- Main Content - Con padding-left dinámico para desktop --}}
        <main id="mainWrapper"
            class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300 ease-in-out"
            style="margin-left: 260px;">

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

                        <div class="hidden md:block"> 
                        </div>
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
            <div id="mainContent" class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="p-4 md:p-6">
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
            #sidebar.collapsed {
                width: 72px !important;
            }

            #sidebar.collapsed .nav-text,
            #sidebar.collapsed #logoText {
                display: none;
            }

            #sidebar.collapsed nav a {
                justify-content: center;
                padding: 0.75rem;
            }

            #sidebar.collapsed nav a i {
                margin: 0;
            }

            /* Ajustar margen del main cuando sidebar está colapsado */
            #sidebar.collapsed+#sidebarOverlay+#mainWrapper,
            #sidebar.collapsed~#mainWrapper {
                margin-left: 72px !important;
            }

            /* Mobile styles */
            @media (max-width: 768px) {
                #sidebar {
                    transform: translateX(-100%);
                    width: 260px !important;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                }

                #sidebar.mobile-open {
                    transform: translateX(0);
                }

                #mainWrapper {
                    margin-left: 0 !important;
                    width: 100% !important;
                }

                #sidebar.collapsed.mobile-open {
                    width: 260px !important;
                }

                #sidebar.collapsed.mobile-open .nav-text,
                #sidebar.collapsed.mobile-open #logoText {
                    display: block;
                }

                #sidebar.collapsed.mobile-open nav a {
                    justify-content: flex-start;
                    padding: 0.625rem 0.75rem;
                }

                #sidebar.collapsed.mobile-open nav a i {
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
        </style>
    @endpush

    @push('js')
        <script nonce="{{ $nonce }}">
            (function() {
                const STORAGE_KEY = 'sidebarCollapsed';
                const MOBILE_BREAKPOINT = 768;

                const sidebar = document.getElementById('sidebar');
                const toggleIcon = document.getElementById('toggleIcon');
                const toggleBtn = document.getElementById('toggleSidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const mobileToggle = document.getElementById('mobileMenuToggle');
                const mainWrapper = document.getElementById('mainWrapper');

                function setSidebarState(collapsed) {
                    if (!sidebar) return;

                    sidebar.classList.toggle('collapsed', collapsed);

                    if (toggleIcon) {
                        toggleIcon.classList.toggle('mdi-chevron-left', !collapsed);
                        toggleIcon.classList.toggle('mdi-chevron-right', collapsed);
                    }

                    // Ajustar margen del main wrapper
                    if (mainWrapper && window.innerWidth >= MOBILE_BREAKPOINT) {
                        mainWrapper.style.marginLeft = collapsed ? '72px' : '260px';
                    }

                    localStorage.setItem(STORAGE_KEY, collapsed);
                }

                function closeMobileMenu() {
                    sidebar?.classList.remove('mobile-open');
                    overlay?.classList.add('hidden');
                    document.body.style.overflow = '';
                }

                function openMobileMenu() {
                    sidebar?.classList.add('mobile-open');
                    overlay?.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }

                function init() {
                    const isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
                    const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

                    if (!isMobile && isCollapsed) {
                        setSidebarState(true);
                    } else if (!isMobile && mainWrapper) {
                        mainWrapper.style.marginLeft = '260px';
                    }

                    toggleBtn?.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (window.innerWidth >= MOBILE_BREAKPOINT) {
                            setSidebarState(!sidebar.classList.contains('collapsed'));
                        }
                    });

                    mobileToggle?.addEventListener('click', (e) => {
                        e.preventDefault();
                        sidebar?.classList.contains('mobile-open') ? closeMobileMenu() : openMobileMenu();
                    });

                    overlay?.addEventListener('click', closeMobileMenu);

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && sidebar?.classList.contains('mobile-open')) {
                            closeMobileMenu();
                        }
                    });

                    // Cerrar menú móvil al hacer clic en un enlace
                    sidebar?.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', () => {
                            if (window.innerWidth < MOBILE_BREAKPOINT) {
                                closeMobileMenu();
                            }
                        });
                    });

                    // Manejar resize
                    let resizeTimer;
                    window.addEventListener('resize', () => {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(() => {
                            const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

                            if (!isMobile) {
                                closeMobileMenu();
                                const collapsed = localStorage.getItem(STORAGE_KEY) === 'true';
                                setSidebarState(collapsed);
                                if (mainWrapper) {
                                    mainWrapper.style.marginLeft = collapsed ? '72px' : '260px';
                                }
                            } else {
                                // En móvil, resetear margen
                                if (mainWrapper) {
                                    mainWrapper.style.marginLeft = '0';
                                }
                                // Resetear estado colapsado en móvil
                                sidebar?.classList.remove('collapsed');
                            }
                        }, 100);
                    });
                }

                document.readyState === 'loading' ?
                    document.addEventListener('DOMContentLoaded', init) :
                    init();
            })();
        </script>
    @endpush
</x-layout>
