@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Two-Factor Authentication')])
@endsection

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
        <div class="w-full max-w-md">

            {{-- Icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0 3-3 3-3 3m6-3c0 3-3 3-3 3m0-10a4 4 0 00-4 4v3h8V8a4 4 0 00-4-4z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b">
                    <h1 class="text-2xl font-bold text-gray-900 text-center">
                        {{ __('Two-Factor Challenge') }}
                    </h1>
                    <p class="text-sm text-gray-600 text-center mt-1">
                        {{ __('Confirm access to your account') }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="px-8 py-6">

                    {{-- Errors --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-sm text-blue-800">
                        {{ __('Enter the authentication code from your authenticator app or use a recovery code.') }}
                    </div>

                    <form method="POST" action="{{ route('two-factor.login.store') }}">
                        @csrf

                        {{-- TOTP CODE --}}
                        <div id="totp-section">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Authentication Code') }}
                            </label>

                            <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code"
                                class="w-full px-4 py-3 border rounded-xl focus:ring focus:ring-blue-200"
                                placeholder="123456" />
                        </div>

                        {{-- RECOVERY --}}
                        <div id="recovery-section" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Recovery Code') }}
                            </label>

                            <input type="text" name="recovery_code"
                                class="w-full px-4 py-3 border rounded-xl focus:ring focus:ring-blue-200"
                                placeholder="xxxx-xxxx" />
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full mt-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition">
                            {{ __('Confirm') }}
                        </button>
                    </form>

                    {{-- Toggle --}}
                    <div class="text-center mt-6">
                        <button type="button" onclick="toggleRecovery()" class="text-sm text-blue-600 hover:underline">
                            {{ __('Use a recovery code') }}
                        </button>
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="text-center mt-4">
                        @csrf
                        <button class="text-xs text-gray-500 hover:underline">
                            {{ __('Log out') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleRecovery() {
            document.getElementById('totp-section').classList.toggle('hidden');
            document.getElementById('recovery-section').classList.toggle('hidden');
        }
    </script>
@endsection
