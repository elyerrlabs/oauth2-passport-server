@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Verify My Account')])
@endsection

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md">
            {{-- Logo/Brand --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-100">
                    <h1 class="text-2xl font-bold text-gray-900 text-center flex items-center justify-center gap-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Account Verification Required') }}
                    </h1>
                </div>

                {{-- Content --}}
                <div class="px-8 py-6">
                    {{-- Status Messages --}}
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <p class="text-green-800 text-sm">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        </div>
                    @endif

                    {{-- Info Card --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm text-blue-800 mb-2">
                                    {{ __('Please check your email inbox (and spam folder) for the verification link we sent you during registration.') }}
                                </p>
                                <p class="text-xs text-blue-600">
                                    <span class="font-medium">{{ __('Didn\'t receive it?') }}</span>
                                    {{ __('You can request a new one below.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Resend Form --}}
                    <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                        @csrf
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="flex items-center my-6">
                        <div class="flex-1 border-t border-gray-200"></div>
                        <span class="px-4 text-sm text-gray-500">{{ __('or') }}</span>
                        <div class="flex-1 border-t border-gray-200"></div>
                    </div>

                    {{-- Help Text --}}
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-600 mb-2">
                            {{ __('Already verified your account?') }}
                        </p>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
                                {{ __('Try logging in again →') }}
                            </button>
                        </form>
                    </div>

                    {{-- Alternative Actions --}}
                    <div class="border-t border-gray-100 pt-6">
                        <p class="text-xs text-gray-500 text-center mb-3">{{ __('Need more help?') }}</p>
                        <div class="flex flex-col gap-3">
                            <a target="_blank" href="mailto:{{ config('mail.from.address') }}
?subject={{ urlencode('Support - Account Verification') }}
&body={{ urlencode('Hello,I am having an issue with my account verification. Thank you.') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 flex items-center justify-center gap-2 hover:bg-gray-50 py-2 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ __('Contact Support') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full border border-gray-300 text-gray-700 font-medium py-3 px-4 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <p class="text-xs text-gray-500 text-center mt-6">
                {{ __('Verification links expire in 24 hours.') }}
            </p>
        </div>
    </div>
@endsection
