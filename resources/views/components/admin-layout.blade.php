{{-- resources/views/components/page-manager.blade.php --}}
@props([
    'title' => 'Page Manager',
    'routes' => [],
    'logoInitial' => 'P',
    'logoGradient' => 'from-blue-600 to-indigo-600',
    'header' => null,
])

<x-layout>
    {{-- Header opcional --}}
    @if ($header)
        <div id="pageManagerHeader"
            class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300">
            {{ $header }}
        </div>
    @endif

    <div id="pageManagerLayout" class="bg-gray-50 dark:bg-gray-900 flex overflow-hidden transition-colors duration-300">

        <!-- Sidebar Navigation -->
        <aside id="sidebar"
            class="relative z-40 bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 ease-in-out flex-shrink-0"
            style="width: 280px;">

            <!-- Logo & Toggle Button -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div
                        class="w-8 h-8 bg-gradient-to-r {{ $logoGradient }} rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                        <span class="text-white font-bold text-sm">{{ $logoInitial }}</span>
                    </div>
                    <h1 id="logoText"
                        class="text-xl font-bold text-gray-800 dark:text-white whitespace-nowrap transition-all duration-300">
                        {{ $title }}
                    </h1>
                </div>

                <!-- Toggle Button -->
                <button id="toggleSidebar"
                    class="p-1.5 cursor-pointer rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i id="toggleIcon" class="mdi mdi-chevron-left text-xl text-gray-600 dark:text-gray-400"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                @foreach ($routes as $item)
                    <a href="{{ $item['route'] }}"
                        class="flex items-center space-x-3 px-4 py-3 text-gray-600 dark:text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white group {{ request()->url() === $item['route'] ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-l-4 border-blue-600 dark:border-blue-500' : '' }}">
                        <span
                            class="{{ $item['icon'] }} text-xl flex-shrink-0 {{ request()->url() === $item['route'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}"></span>
                        <span class="nav-text font-medium whitespace-nowrap transition-all duration-300">
                            {{ $item['name'] }}
                        </span>
                        @if (isset($item['badge']))
                            <span class="nav-text ml-auto">
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $item['badge']['class'] ?? 'bg-blue-100 text-blue-800' }}">
                                    {{ $item['badge']['text'] }}
                                </span>
                            </span>
                        @endif
                    </a>
                @endforeach

                {{-- Slot para navegación adicional --}}
                @if (isset($navigation))
                    {{ $navigation }}
                @endif
            </nav>

            {{-- Slot para footer del sidebar --}}
            @if (isset($sidebarFooter))
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    {{ $sidebarFooter }}
                </div>
            @endif
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay"
            class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity duration-300 md:hidden">
        </div>

        <!-- Main Content Area -->
        <main id="mainContent"
            class="flex-1 min-w-0 overflow-y-auto bg-gray-50 dark:bg-gray-900 transition-all duration-300 ease-in-out">
            <div class="h-full">
                {{ $slot }}
            </div>
        </main>
    </div>

    @push('css')
        <style nonce="{{ $nonce }}">
            /* Reset de altura para ocupar todo el viewport */
            html,
            body {
                height: 100%;
                overflow: hidden;
            }

            /* Layout principal */
            #pageManagerLayout {
                height: 100vh;
                display: flex;
            }

            /* Si hay header, ajustar altura */
            #pageManagerHeader+#pageManagerLayout {
                height: calc(100vh - var(--page-manager-header-height, 0px));
            }

            /* Sidebar - altura completa */
            #sidebar {
                height: 100%;
                transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
            }

            /* Sidebar colapsado */
            #sidebar.collapsed {
                width: 80px !important;
            }

            #sidebar.collapsed .nav-text,
            #sidebar.collapsed #logoText {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }

            /* Contenido principal - altura completa */
            #mainContent {
                height: 100%;
                overflow-y: auto;
                transition: margin-left 0.3s ease-in-out;
            }

            /* Scrollbar personalizado para el contenido principal */
            #mainContent::-webkit-scrollbar {
                width: 8px;
            }

            #mainContent::-webkit-scrollbar-track {
                background: transparent;
            }

            #mainContent::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }

            #mainContent::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Dark mode scrollbar */
            .dark #mainContent::-webkit-scrollbar-thumb {
                background: #475569;
            }

            .dark #mainContent::-webkit-scrollbar-thumb:hover {
                background: #64748b;
            }

            /* Scrollbar del sidebar */
            #sidebar nav::-webkit-scrollbar {
                width: 4px;
            }

            #sidebar nav::-webkit-scrollbar-track {
                background: transparent;
            }

            #sidebar nav::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 2px;
            }

            #sidebar nav::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Estilos móviles */
            @media (max-width: 768px) {
                #sidebar {
                    position: fixed;
                    left: 0;
                    top: 0;
                    bottom: 0;
                    z-index: 40;
                    transform: translateX(-100%);
                    width: 280px !important;
                }

                #sidebar.mobile-open {
                    transform: translateX(0);
                }

                #sidebar.collapsed.mobile-open {
                    width: 280px !important;
                }

                #sidebar.collapsed.mobile-open .nav-text,
                #sidebar.collapsed.mobile-open #logoText {
                    opacity: 1;
                    width: auto;
                    overflow: visible;
                }

                #sidebarOverlay {
                    z-index: 35;
                }

                #mainContent {
                    width: 100% !important;
                    margin-left: 0 !important;
                }
            }

            /* Transiciones suaves */
            #sidebar,
            #mainContent,
            .nav-text,
            #logoText {
                transition: all 0.3s ease-in-out;
            }
        </style>
    @endpush

    @push('js')
        <script nonce="{{ $nonce }}">
            (function() {
                const root = document.documentElement;
                const header = document.getElementById('pageManagerHeader');
                const layout = document.getElementById('pageManagerLayout');
                const sidebar = document.getElementById('sidebar');
                const toggleIcon = document.getElementById('toggleIcon');
                const toggleBtn = document.getElementById('toggleSidebar');
                const overlay = document.getElementById('sidebarOverlay');

                // Configuración
                const STORAGE_KEY = 'sidebarCollapsed';
                const MOBILE_BREAKPOINT = 768;

                // Sincronizar altura del header
                function syncHeaderHeight() {
                    if (header) {
                        const height = header.offsetHeight;
                        root.style.setProperty('--page-manager-header-height', `${height}px`);
                    }
                }

                // Estado del sidebar
                function setSidebarState(collapsed) {
                    if (!sidebar) return;

                    if (collapsed) {
                        sidebar.classList.add('collapsed');
                        toggleIcon?.classList.remove('mdi-chevron-left');
                        toggleIcon?.classList.add('mdi-chevron-right');
                    } else {
                        sidebar.classList.remove('collapsed');
                        toggleIcon?.classList.remove('mdi-chevron-right');
                        toggleIcon?.classList.add('mdi-chevron-left');
                    }

                    localStorage.setItem(STORAGE_KEY, collapsed);

                    // Evento personalizado
                    window.dispatchEvent(new CustomEvent('sidebar-toggled', {
                        detail: {
                            collapsed
                        }
                    }));
                }

                // Menú móvil
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

                function toggleMobileMenu() {
                    sidebar?.classList.contains('mobile-open') ?
                        closeMobileMenu() :
                        openMobileMenu();
                }

                // Inicialización
                function init() {
                    syncHeaderHeight();

                    // Estado inicial del sidebar
                    const isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
                    const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

                    if (!isMobile && isCollapsed) {
                        setSidebarState(true);
                    }

                    // Event listeners
                    toggleBtn?.addEventListener('click', (e) => {
                        e.preventDefault();
                        const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

                        if (isMobile) {
                            toggleMobileMenu();
                        } else {
                            const isCurrentlyCollapsed = sidebar.classList.contains('collapsed');
                            setSidebarState(!isCurrentlyCollapsed);
                        }
                    });

                    // Botón de menú móvil externo (si existe)
                    const mobileToggle = document.getElementById('mobileMenuToggle');
                    mobileToggle?.addEventListener('click', (e) => {
                        e.preventDefault();
                        toggleMobileMenu();
                    });

                    // Cerrar menú al hacer clic en overlay
                    overlay?.addEventListener('click', closeMobileMenu);

                    // Cerrar menú con Escape
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && sidebar?.classList.contains('mobile-open')) {
                            closeMobileMenu();
                        }
                    });

                    // Manejar resize
                    let resizeTimer;
                    window.addEventListener('resize', () => {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(() => {
                            syncHeaderHeight();

                            const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

                            if (!isMobile) {
                                closeMobileMenu();
                                const collapsed = localStorage.getItem(STORAGE_KEY) === 'true';
                                setSidebarState(collapsed);
                            }
                        }, 100);
                    });

                    // Observer para cambios en el header
                    if (header) {
                        const observer = new ResizeObserver(() => syncHeaderHeight());
                        observer.observe(header);
                    }
                }

                // Iniciar cuando el DOM esté listo
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
            })();
        </script>
    @endpush
</x-layout>
