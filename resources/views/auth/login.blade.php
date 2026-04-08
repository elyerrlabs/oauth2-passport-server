@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Login')])
@endsection

@push('head')
    {!! config('seo.login', '') !!}
@endpush

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4 transition-colors duration-300">
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
                            <span><strong>{{ __('Email') }}:</strong> {{ config('system.demo.email') }}</span>
                            <button type="button" onclick="copyToClipboard('{{ config('system.demo.email') }}', 'email')"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-xs">
                                <i class="mdi mdi-content-copy"></i> {{ __('Copy') }}
                            </button>
                        </div>
                        <div class="flex justify-between items-center">
                            <span><strong>{{ __('Password') }}:</strong> {{ config('system.demo.password') }}</span>
                            <button type="button"
                                onclick="copyToClipboard('{{ config('system.demo.password') }}', 'password')"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-xs">
                                <i class="mdi mdi-content-copy"></i> {{ __('Copy') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
                <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
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
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
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
                    @if (isset($captcha) && $captcha)
                        <div>
                            <x-captcha />
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg
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

    <script nonce="{{ $nonce ?? '' }}">
        // Toggle password visibility
        document.getElementById('toggle-password')?.addEventListener('click', function() {
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

        // Copy to clipboard function
        function copyToClipboard(text, type) {
            navigator.clipboard.writeText(text).then(() => {
                // Simple notification (you can replace with your toast system)
                const message = type === 'email' ? '{{ __('Demo email copied') }}' :
                    '{{ __('Demo password copied') }}';

                // Fallback alert if no toast system
                if (typeof $notify !== 'undefined') {
                    $notify.success(message);
                } else {
                    alert(message);
                }
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        // Auto-fill demo credentials if clicked (optional)
        @if (config('system.demo.enabled'))
            function fillDemoCredentials() {
                document.getElementById('email').value = '{{ config('system.demo.email') }}';
                document.getElementById('password').value = '{{ config('system.demo.password') }}';
            }
        @endif
    </script>
@endsection
