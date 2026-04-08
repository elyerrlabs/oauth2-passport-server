@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Register')])
@endsection


@push('head')
    {!! config('seo.register', '') !!}
@endpush

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4 transition-colors duration-300">
        <div class="w-full max-w-2xl">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg mb-4">
                    <i class="mdi mdi-account-plus text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Create an Account') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ __('Join our secure platform') }}
                </p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('First Name') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-account text-gray-400 text-lg"></i>
                                </div>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 
                                              rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="John" required autofocus>
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Last Name') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-account-outline text-gray-400 text-lg"></i>
                                </div>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 
                                              rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="Doe" required>
                            </div>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Email Address') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="mdi mdi-email text-gray-400 text-lg"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 
                                          rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          dark:bg-gray-700 dark:text-white transition duration-200"
                                placeholder="john@example.com" required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birthday (optional) -->
                    @if (config('system.birthday.active', false))
                        <div>
                            <label for="birthday" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Birthday') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-cake text-gray-400 text-lg"></i>
                                </div>
                                <input type="date" id="birthday" name="birthday" value="{{ old('birthday') }}"
                                    class="w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 
                                              rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white transition duration-200">
                            </div>
                            @error('birthday')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
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
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <i class="mdi mdi-eye text-lg"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Confirm Password') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="mdi mdi-lock-check text-gray-400 text-lg"></i>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full pl-10 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 
                                              rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white transition duration-200"
                                    placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <i class="mdi mdi-eye text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="accept_terms" id="accept_terms" value="1"
                                class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('I accept the') }}
                                <a href="{{ config('system.terms_url') ?? route('legal.terms-and-conditions') }}"
                                    target="_blank"
                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline">
                                    {{ __('Terms and Conditions') }}
                                </a>
                                {{ __('and') }}
                                <a href="{{ config('system.privacy_url') ?? route('legal.policies-of-privacy') }}"
                                    target="_blank"
                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline">
                                    {{ __('Privacy Policy') }}
                                </a>
                            </span>
                        </label>
                        @error('accept_terms')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="accept_cookies" id="accept_cookies" value="1"
                                class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('I accept the') }}
                                <a href="{{ config('system.policy_cookies') ?? route('legal.policies-of-cookies') }}"
                                    target="_blank"
                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline">
                                    {{ __('Cookies Policy') }}
                                </a>
                            </span>
                        </label>
                        @error('accept_cookies')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                        <i class="mdi mdi-account-plus text-lg"></i>
                        {{ __('Create Account') }}
                    </button>

                    <!-- Login Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}"
                                class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium hover:underline">
                                {{ __('Sign In') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} {{ config('app.org_name', config('app.name')) }}.
                    {{ __('All rights reserved.') }}
                </p>
            </div>
        </div>
    </div>

    <script nonce="{{ $nonce ?? '' }}">
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const button = event.currentTarget;
            const icon = button.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('mdi-eye');
                icon.classList.add('mdi-eye-off');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('mdi-eye-off');
                icon.classList.add('mdi-eye');
            }
        }
    </script>
@endsection
