@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Settings')])
@endsection

@push('css')
    <style nonce="{{ $nonce }}">
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #CBD5E0;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #A0AEC0;
        }
    </style>
@endpush

@section('content')
    <div class="flex bg-gray-100 min-h-screen">

        <!-- SIDEBAR (FIJO) -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 shadow-md overflow-y-auto z-40 transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">

            <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="mdi mdi-cog-outline text-blue-600"></i>
                    {{ __('Settings') }}
                </h2>
                <button id="close-sidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            @php
                $routes = [
                    'admin.settings.general' => ['icon' => 'mdi-cog-outline', 'label' => __('General')],
                    'admin.settings.session' => ['icon' => 'mdi-clock-outline', 'label' => __('Session')],
                    'admin.settings.payment' => ['icon' => 'mdi-credit-card-outline', 'label' => __('Payment')],
                    'admin.settings.email' => ['icon' => 'mdi-email-outline', 'label' => __('Email')],
                    'admin.settings.routes' => ['icon' => 'mdi-routes', 'label' => __('Routes')],
                    'admin.settings.redis' => ['icon' => 'mdi-database', 'label' => __('Redis')],
                    'admin.settings.cache' => ['icon' => 'mdi-cached', 'label' => __('Cache')],
                    'admin.settings.queues' => ['icon' => 'mdi-playlist-play', 'label' => __('Queues')],
                    'admin.settings.filesystem' => ['icon' => 'mdi-folder-outline', 'label' => __('Filesystem')],
                    'admin.settings.rate_limit' => ['icon' => 'mdi-traffic-light', 'label' => __('Rate Limit')],
                    'admin.settings.security' => ['icon' => 'mdi-shield-outline', 'label' => __('Security')],
                    'admin.logs' => ['icon' => 'mdi-text-box-outline', 'label' => __('Log viewer')],
                ];
            @endphp

            <ul class="py-2">
                @foreach ($routes as $route => $data)
                    <li class="border-b border-gray-200 last:border-b-0">
                        <a href="{{ route($route) }}"
                            class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200 group
                            {{ request()->routeIs($route) ? 'bg-blue-100 border-l-4 border-blue-600 text-blue-600 font-semibold' : '' }}">
                            <i
                                class="mdi {{ $data['icon'] }} text-lg group-hover:text-blue-600 transition-colors {{ request()->routeIs($route) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                            <span class="flex-1 truncate">{{ $data['label'] }}</span>
                            @if (request()->routeIs($route))
                                <i class="mdi mdi-check-circle text-green-600"></i>
                            @else
                                <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">

            <!-- HEADER -->
            <nav class="bg-blue-600 text-white py-4 shadow flex justify-between items-center px-6 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button id="open-sidebar" class="lg:hidden text-white hover:text-blue-200 focus:outline-none">
                        <i class="mdi mdi-menu text-2xl"></i>
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-2 font-medium">
                        <i class="mdi mdi-arrow-left text-2xl"></i>
                        <span>{{ __('Back to Dashboard') }}</span>
                    </a>
                </div>
                <h1 class="text-xl font-semibold flex items-center">
                    <i class="mdi mdi-cog-outline mr-2"></i>
                    {{ __('System Settings') }}
                </h1>
            </nav>

            <!-- SETTINGS CONTENT -->
            <main class="flex-1 p-2 md:p-8 space-y-8 overflow-y-auto">

                <!-- FORM SETTINGS -->
                <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="mdi mdi-pencil-outline mr-2 text-blue-600"></i>
                            @yield('settings_title', __('Edit Settings'))
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            @yield('settings_description', __('Modify your application configuration'))
                        </p>
                    </div>

                    <form action="{{ route('admin.settings.update') }}" method="post" autocomplete="off"
                        class="p-1 md:p-6">
                        @method('put')
                        @csrf
                        <input type="hidden" name="current_route" value="{{ url()->current() }}">

                        <div>
                            @yield('form')
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                            <button type="submit"
                                class="flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors">
                                <i class="mdi mdi-content-save-all mr-2"></i>{{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CACHE SECTION -->
                <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="mdi mdi-cached mr-2 text-blue-600"></i>
                            {{ __('Cache Management') }}
                        </h2>
                    </div>

                    <form action="{{ route('admin.settings.reload') }}" method="post" autocomplete="off" class="p-6">
                        @method('put')
                        @csrf
                        <input type="hidden" name="current_route" value="{{ url()->current() }}">

                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 mr-3 text-blue-600">
                                <i class="mdi mdi-information-outline text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500">
                                {{ __('This will clear and regenerate the cache for all application settings to ensure the latest values are used across the system.') }}
                            </p>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="flex items-center px-6 py-2.5 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors">
                                <i class="mdi mdi-cached mr-2"></i>{{ __('Rebuild Settings Cache') }}
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('open-sidebar');
            const closeBtn = document.getElementById('close-sidebar');

            openBtn?.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
            closeBtn?.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));

            document.addEventListener('click', (e) => {
                if (window.innerWidth < 1024 && !sidebar.contains(e.target) && !openBtn.contains(e
                        .target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>
@endpush
