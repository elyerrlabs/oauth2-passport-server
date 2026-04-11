@extends('layouts.pages')

@push('head')
    @include('layouts.parts.title', ['title' => __('Reset Password')])

    {!! config('seo.forgot-password', '') !!}
@endpush

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4 transition-colors duration-300">
        <div class="w-full max-w-md">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition group">
                    <i class="mdi mdi-arrow-left mr-1 group-hover:-translate-x-1 transition-transform"></i>
                    {{ __('Back to Login') }}
                </a>
            </div>

            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg mb-4">
                    <i class="mdi mdi-lock-reset text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Reset Password') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ __('Enter your email to receive a reset link') }}
                </p>
            </div>

            <!-- Reset Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
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
                                placeholder="name@example.com" required autofocus>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        @if (session('status'))
                            <p class="mt-1 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
                        @endif
                    </div>

                    <!-- Captcha -->
                    <div>
                        <x-captcha />
                    </div>


                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg
                                   transition duration-200 transform hover:scale-[1.02] active:scale-[0.98]
                                   shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="mdi mdi-email-send text-lg"></i>
                        {{ __('Send Reset Link') }}
                    </button>
                </form>

                <!-- Help Card (simplified) -->
                <div class="mt-6 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start gap-2">
                        <i class="mdi mdi-information text-blue-500 dark:text-blue-400 text-lg mt-0.5"></i>
                        <div>
                            <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300">
                                {{ __("Can't access your email?") }}
                            </h3>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                {{ __('Contact our support team for assistance.') }}
                            </p>
                        </div>
                    </div>
                </div>
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
@endsection
