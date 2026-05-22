@extends('layouts.pages')

@push('head')
    @includeIf('pages.layouts.login')
@endpush

@section('content')
    <div
        class="min-h-screen bg-linear-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4 transition-colors duration-300">
        <div class="w-full max-w-md">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg mb-4">
                    <i class="mdi mdi-lock text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ config('app.name', 'OAuth2 Server') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ __('Sign in to your account') }}
                </p>
            </div>

            <!-- Demo Mode Alert (simplified) -->
            @if (config('system.demo.enabled'))
                <div class="mb-6 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center gap-2 text-sm text-blue-800 dark:text-blue-300">
                        <i class="mdi mdi-information text-lg"></i>
                        <span class="font-medium">{{ __('Demo Mode') }}</span>
                    </div>
                    <div class="mt-2 text-xs text-blue-700 dark:text-blue-400 space-y-1">
                        <div class="flex justify-between items-center">
                            <span><strong>{{ __('Email') }}:</strong> <span
                                    id="demo-email">{{ config('system.demo.email') }}</span></span>
                            <button type="button" data-copy-target="demo-email" data-copy-type="email"
                                class="copy-button cursor-pointer text-blue-600 hover:text-blue-800 dark:text-blue-400 text-xs">
                                <i class="mdi mdi-content-copy"></i> {{ __('Copy') }}
                            </button>
                        </div>
                        <div class="flex justify-between items-center">
                            <span><strong>{{ __('Password') }}:</strong> <span
                                    id="demo-password">{{ config('system.demo.password') }}</span></span>
                            <button type="button" data-copy-target="demo-password" data-copy-type="password"
                                class="copy-button cursor-pointer text-blue-600 hover:text-blue-800 dark:text-blue-400 text-xs">
                                <i class="mdi mdi-content-copy"></i> {{ __('Copy') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (config('system.demo.domain.enabled', false))
                <div class="mb-6 p-3 text-center border border-gray-300 rounded-xl">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl pb-3  bg-yellow-50/60 text-yellow-800/90 shadow-sm backdrop-blur-sm">
                        <i class="mdi mdi-eye-outline text-yellow-600 text-lg"></i>
                        <span class="text-sm font-medium text-gray-800">
                            {{ __('Demo environment active for preview purposes') }}
                        </span>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 p-4">
                        <a href="{{ config('system.demo.domain.url') }}" target="_blank"
                            class="hover:text-yellow-700 hover:bg-blue-700 cursor-pointer font-medium transition rounded-2xl bg-blue-600 text-white p-3">
                            {{ config('system.demo.domain.url') }}
                        </a>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
                <form action="{{ route('login.store') }}" method="POST" class="space-y-5" id="login-form">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Email address') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-email text-gray-400 text-lg"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 
                                          rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          dark:bg-gray-700 dark:text-white transition duration-200"
                                placeholder="name@example.com" required autofocus>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Password') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-lock text-gray-400 text-lg"></i>
                            </div>
                            <input type="password" id="password" name="password"
                                class="w-full pl-10 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 
                                          rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          dark:bg-gray-700 dark:text-white transition duration-200"
                                placeholder="••••••••" required>
                            <button type="button" id="toggle-password"
                                class="toggle-password-btn cursor-pointer absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="mdi mdi-eye text-lg"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Remember me') }}
                            </span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Captcha -->
                    <div>
                        <x-captcha />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 cursor-pointer hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg
                                   transition duration-200 transform hover:scale-[1.02] active:scale-[0.98]
                                   shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="mdi mdi-login text-lg"></i>
                        {{ __('Sign In') }}
                    </button>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center pt-2">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Don't have an account?") }}
                                <a href="{{ route('register') }}"
                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium hover:underline">
                                    {{ __('Create one') }}
                                </a>
                            </p>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Footer Links -->
            <div class="text-center mt-6">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} {{ config('app.org_name', config('app.name')) }}.
                    {{ __('All rights reserved.') }}
                </p>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordBtn = document.getElementById('toggle-password');
            if (togglePasswordBtn) {
                togglePasswordBtn.addEventListener('click', function() {
                    const passwordInput = document.getElementById('password');
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('mdi-eye');
                        icon.classList.add('mdi-eye-off');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('mdi-eye-off');
                        icon.classList.add('mdi-eye');
                    }
                });
            }

            // Copy to clipboard functionality for all copy buttons
            const copyButtons = document.querySelectorAll('.copy-button');
            copyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-copy-target');
                    const type = this.getAttribute('data-copy-type');
                    const textElement = document.getElementById(targetId);

                    if (textElement) {
                        const text = textElement.textContent;
                        navigator.clipboard.writeText(text).then(() => {
                            const message = type === 'email' ? 'Demo email copied' :
                                'Demo password copied';

                            // Check if toast system exists
                            if (typeof $notify !== 'undefined' && $notify.success) {
                                $notify.success(message);
                            } else {
                                // Create a temporary notification
                                showNotification(message);
                            }
                        }).catch(err => {
                            console.error('Failed to copy: ', err);
                            showNotification('Failed to copy to clipboard', 'error');
                        });
                    }
                });
            });

        });

        // Helper function to show notifications without inline styles
        function showNotification(message, type = 'success') {
            // Check if notification div already exists
            let notificationDiv = document.getElementById('temp-notification');

            if (!notificationDiv) {
                notificationDiv = document.createElement('div');
                notificationDiv.id = 'temp-notification';
                notificationDiv.className =
                    'fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white transition-opacity duration-300';
                document.body.appendChild(notificationDiv);
            }

            // Set color based on type
            if (type === 'success') {
                notificationDiv.className =
                    'fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white bg-green-500 transition-opacity duration-300';
            } else {
                notificationDiv.className =
                    'fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white bg-red-500 transition-opacity duration-300';
            }

            notificationDiv.textContent = message;
            notificationDiv.style.opacity = '1';

            // Auto-hide after 3 seconds
            setTimeout(() => {
                notificationDiv.style.opacity = '0';
                setTimeout(() => {
                    if (notificationDiv.parentNode) {
                        notificationDiv.parentNode.removeChild(notificationDiv);
                    }
                }, 300);
            }, 3000);
        }
    </script>
@endpush
