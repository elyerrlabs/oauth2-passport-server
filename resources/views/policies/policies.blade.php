@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Policies')])
@endsection

@section('header')
    <nav
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

            <!-- Theme Toggle -->
            <div class="flex items-center space-x-4">
                <!-- Optional: Current Page Title -->
                <div class="hidden md:flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    <i class="mdi mdi-cog-outline text-xl"></i>
                    <span class="font-medium">{{ __('Settings') }}</span>
                </div>

                <!-- Theme Component -->
                <x-theme />
            </div>
        </div>
    </nav>
@endsection

@section('content')
    @php
        $routes = [
            [
                'name' => __('Terms and Conditions'),
                'route' => route('admin.policies.terms-and-conditions'),
                'icon' => 'mdi mdi-file-document-outline',
            ],
            [
                'name' => __('Privacy Policy'),
                'route' => route('admin.policies.policies-of-privacy'),
                'icon' => 'mdi mdi-shield-account-outline',
            ],
            [
                'name' => __('Cookies Policy'),
                'route' => route('admin.policies.policies-of-cookies'),
                'icon' => 'mdi mdi-cookie-outline',
            ],
        ];
    @endphp

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex transition-colors duration-300">
        <!-- Sidebar Navigation -->
        <aside
            class="w-64 bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700 flex flex-col transition-colors duration-300">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-sm">P</span>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ __('Policies') }}</h1>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
                @foreach ($routes as $item)
                    <a href="{{ $item['route'] }}"
                        class="flex items-center space-x-3 px-4 py-3 text-gray-600 dark:text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white group {{ request()->url() === $item['route'] ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-l-4 border-blue-600 dark:border-blue-500' : '' }}">
                        <span
                            class="{{ $item['icon'] }} text-xl {{ request()->url() === $item['route'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}"></span>
                        <span class="font-medium">{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>

            <!-- User Profile Section -->
            <div
                class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 mt-auto transition-colors duration-300">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <img class="w-10 h-10 rounded-full ring-2 ring-gray-200 dark:ring-gray-700 object-cover"
                            src="https://ui-avatars.com/api/?background=3B82F6&color=fff&name={{ urlencode(auth()->user()->name . '+' . auth()->user()->last_name) }}&bold=true"
                            alt="User profile" />
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                            {{ auth()->user()->name }} {{ auth()->user()->last_name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 transition-colors duration-300">
            <form action="{{ route('admin.settings.update') }}" method="post" autocomplete="off">
                @method('put')
                @csrf
                <input type="hidden" name="current_route" value="{{ url()->current() }}">

                <!-- Card Container -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Policy Content') }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Edit and manage your policy documents') }}</p>
                    </div>

                    <div class="p-6 min-h-[calc(100vh-300px)]">
                        @yield('form')
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('user.dashboard') }}"
                        class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-medium">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 font-medium">
                        <i class="mdi mdi-content-save-outline mr-2 text-lg"></i>{{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </main>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar for sidebar */
        nav.overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }

        nav.overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        nav.overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        nav.overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark nav.overflow-y-auto::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .dark nav.overflow-y-auto::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark nav.overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
    </style>
@endpush
