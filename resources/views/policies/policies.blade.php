@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Policies')])
@endsection

@section('header')
    <nav class="bg-gray-25 dark:bg-gray-800 dark:border-b-gray-500 border-bottom text-white py-4 shadow-lg transition-colors duration-300">
        <div class="container mx-auto flex justify-between items-center px-6">
            <!-- Back Button -->
            <a href="{{ route('user.dashboard') }}"
                class="flex items-center space-x-2 text-lg font-semibold hover:opacity-90 transition-all duration-300 
                       hover:scale-105 active:scale-95 group">
                <i class="mdi mdi-arrow-left text-2xl group-hover:-translate-x-1 transition-transform duration-300"></i>
                <span class="relative">
                    {{ __('Back to Dashboard') }}
                    <span
                        class="absolute bottom-0 left-0 w-0 h-0.5 bg-white group-hover:w-full transition-all duration-300"></span>
                </span>
            </a>

            <!-- Theme Toggle -->
            <div class="flex items-center space-x-4">
                <!-- Optional: Current Page Title -->
                <div class="hidden md:flex items-center space-x-2 text-blue-100">
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
                'icon' => 'mdi mdi-file-sign',
            ],
            [
                'name' => __('Privacy Policy'),
                'route' => route('admin.policies.policies-of-privacy'),
                'icon' => 'mdi mdi-file-sign',
            ],
            [
                'name' => __('Cookies Policy'),
                'route' => route('admin.policies.policies-of-cookies'),
                'icon' => 'mdi mdi-file-sign',
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
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">D</span>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ __('Dashboard') }}</h1>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
                @foreach ($routes as $item)
                    <a href="{{ $item['route'] }}"
                        class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 hover:bg-blue-100 dark:hover:bg-blue-900/30 hover:text-blue-800 dark:hover:text-blue-300 {{ request()->url() === $item['route'] ? 'bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 text-blue-800 dark:text-blue-300' : '' }}">
                        <span class="{{ $item['icon'] }}"></span>
                        <span class="font-medium">{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>

            <!-- User Profile Section -->
            <div
                class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 mt-auto transition-colors duration-300">
                <div class="flex items-center space-x-3">
                    <img class="w-8 h-8 rounded-full"
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?...&q=80" alt="User profile" />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ auth()->user()->name }} {{ auth()->user()->last_name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 transition-colors duration-300">
            <form action="{{ route('admin.settings.update') }}" method="post" autocomplete="off" class="p-6">
                @method('put')
                @csrf
                <input type="hidden" name="current_route" value="{{ url()->current() }}">

                <div class="p-2 min-h-screen">
                    @yield('form')
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button type="submit"
                        class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        <i class="mdi mdi-content-save-all mr-2"></i>{{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </main>
    </div>
@endsection
