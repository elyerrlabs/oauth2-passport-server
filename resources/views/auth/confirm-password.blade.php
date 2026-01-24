@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Confirm Password')])
@endsection

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            {{-- Animated Icon --}}
            <div class="flex justify-center mb-8">
                <div class="relative">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-blue-600 animate-pulse" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-white/20">
                {{-- Header with Gradient --}}
                <div class="relative px-8 py-6 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <div class="absolute inset-0 bg-grid-white/10"></div>
                    <h1 class="text-2xl font-bold text-white text-center relative">
                        {{ __('Security Check Required') }}
                    </h1>
                    <p class="text-blue-100 text-sm text-center mt-2 relative">
                        {{ __('Please verify your identity to continue') }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="px-8 py-8">
                    {{-- Info Alert --}}
                    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-blue-700">
                                {{ __('For security reasons, please confirm your password to access sensitive features.') }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-6">
                        @csrf

                        {{-- Password Field --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ __('Enter Your Password') }}
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input id="password" type="password" name="password" required autofocus
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl 
                                           focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                                           placeholder:text-gray-400 transition-all duration-200
                                           @error('password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                                    placeholder="••••••••">

                                <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>

                            @error('password')
                                <div class="mt-2 flex items-center text-red-600">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 
                                       text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl 
                                       transform hover:-translate-y-0.5 transition-all duration-200 
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                       flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ __('Confirm & Continue') }}
                            </button>
                        </div>
                    </form>

                    {{-- Alternative Actions --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-4">{{ __('Not you?') }}</p>

                            <form method="POST" action="{{ route('logout') }}" class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="group px-6 py-2 border border-gray-100 text-gray-700 font-medium 
                                               rounded-lg hover:bg-gray-50 hover:border-gray-400 
                                               transition-all duration-200 inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- Security Note --}}
            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 rounded-2xl border border-blue-500">
                <div class="flex items-center justify-center gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <p class="text-xs text-blue-700 text-center">
                        {{ __('This security check is valid for 2 hours. Your data is protected with end-to-end encryption.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for toggle password --}}
    @push('scripts')
        <script nonce="{{ $nonce }}">
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eye-icon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML =
                        `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML =
                        `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                }
            }
        </script>
    @endpush
@endsection
