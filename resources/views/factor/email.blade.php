@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Two-Factor Authentication')])
@endsection

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Two-Factor Authentication') }}</h1>
                <p class="text-gray-600 mt-2 text-sm">
                    {{ __('We\'ve sent a verification code to your email address. Please enter it below to continue.') }}
                </p>
            </div>

            <!-- Verification Form -->
            <form action="{{ route('user.2fa.login') }}" method="post" class="space-y-6">
                @csrf

                <!-- Code Input -->
                <div>
                    <label for="token" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Verification Code') }}
                    </label>
                    <div class="relative">
                        <input type="text" id="token" name="token" inputmode="numeric" pattern="[0-9]*"
                            autocomplete="one-time-code"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 text-center text-xl tracking-widest font-semibold"
                            placeholder="••••••" value="{{ old('token') }}" autofocus required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    @if ($errors->has('token'))
                        <div class="mt-2 text-sm text-red-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $errors->first('token') }}
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center justify-center">
                    <!-- SVG icon -->
                    {{ __('Verify Code') }}
                </button>

                <!-- Warning Message -->
                @if (session('warning'))
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-yellow-800 text-sm font-medium">{{ session('warning') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-green-800 text-sm font-medium">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif
            </form>

            <!-- Additional Options -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-3">{{ __('Didn\'t receive the code?') }}</p>

                    <!-- Alternative Action -->
                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-gray-800 text-sm inline-flex items-center transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Return to login') }}
                    </a>
                </div>
            </div>

            <!-- Security Note -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 mt-0.5 mr-2 flex-shrink-0"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <p class="text-xs text-gray-600">
                        {{ __(
                            'For your security, we require two-factor authentication. The verification code will expire in :minute minutes.',
                            [
                                'minute' => config('system.code_2fa_email_expires'),
                            ],
                        ) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const tokenInput = document.getElementById('token');

            if (tokenInput) {
                tokenInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/\D/g, '');
                    if (this.value.length > 6) {
                        this.value = this.value.slice(0, 6);
                    }
                    if (this.value.length === 6) {
                        this.form.submit();
                    }
                });

                tokenInput.focus();
            }
        });
    </script>
@endpush
