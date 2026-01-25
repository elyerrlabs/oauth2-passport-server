@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Authorize')])
@endsection

@section('content')
    <div
        class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4 py-8 transition-colors duration-300">
        <div
            class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors duration-300">

            <!-- Provider Header -->
            <div class="px-8 pt-8 pb-4">
                <div class="text-center mb-6">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 mb-3">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ config('app.name') }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Authorization Service') }}</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="px-8 pb-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Signed in as') }}</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                {{ Auth::user()->name ?? Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if ($client->private)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ __('Verified') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        {{ __('Authorize this application?') }}</h1>

                    <div class="flex items-start space-x-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ substr($client->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100 text-lg">{{ $client->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                @if ($client->private)
                                    {{ __('This is a trusted application from your organization') }}
                                @else
                                    {{ __('This is a third-party application') }}
                                @endif
                            </p>
                            @if ($client->private)
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('This app meets our security standards') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <p class="text-gray-700 dark:text-gray-300 mb-6">
                        <span class="font-medium">{{ $client->name }}</span> {{ __('wants to access your account.') }}
                        {{ __('This will allow them to:') }}
                    </p>
                </div>
            </div>

            <!-- Scopes List -->
            @if (count($scopes) > 0)
                <div class="px-8 pb-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ __('Permissions requested') }}
                        </h3>
                        <ul class="space-y-3">
                            @foreach ($scopes as $scope)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3 mt-0.5 flex-shrink-0"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __($scope->description) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- App Information -->
            <div class="px-8 pb-6">
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <details class="group">
                        <summary
                            class="flex items-center justify-between cursor-pointer text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300">
                            <span>{{ __('Application details') }}</span>
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </summary>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Application ID') }}</span>
                                <span class="font-mono text-gray-900 dark:text-gray-300 text-xs">{{ $client->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Redirect URL') }}</span>
                                <span class="text-gray-900 dark:text-gray-300 truncate max-w-xs">
                                    @php
                                        $redirectUris = $client->redirect_uris;
                                        if (is_string($redirectUris)) {
                                            $uris = json_decode($redirectUris, true);
                                        } else {
                                            $uris = $redirectUris;
                                        }
                                        $firstUri =
                                            is_array($uris) && count($uris) > 0 ? $uris[0] : __('No redirect URL');
                                    @endphp
                                    {{ $firstUri }}
                                </span>
                            </div>
                            @if ($client->private)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Trust level') }}</span>
                                    <span
                                        class="text-green-600 dark:text-green-400 font-medium">{{ __('Internal Application') }}</span>
                                </div>
                            @endif
                        </div>
                    </details>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-8 pb-8">
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('By authorizing, you allow this application to access your data according to the permissions above.') }}
                    </div>

                    <div class="flex space-x-4">
                        <form method="POST" action="{{ route('passport.authorizations.deny') }}" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">

                            <button type="submit"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300 cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                        </form>

                        <form method="POST"
                            action="{{ route('passport.authorizations.approve') . '?nonce=' . $request->nonce }}"
                            class="flex-1">
                            @csrf
                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">

                            <button type="submit"
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-600 dark:hover:to-blue-700 transition-all duration-300 shadow-sm hover:shadow cursor-pointer">
                                {{ __('Authorize') }}
                            </button>
                        </form>
                    </div>

                    <!-- Footer with provider info -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('This authorization is managed by') }}
                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                @if (config('app.org_name'))
                                    {{ config('app.org_name') }}
                                @else
                                    {{ config('app.name') }}
                                @endif
                            </span>
                            <br>
                            <span class="text-xs text-gray-400 dark:text-gray-500 mt-1 block">
                                {{ __('Your security and privacy are protected') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
