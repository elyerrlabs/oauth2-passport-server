@extends('layouts.pages')

@push('head')
    @include('layouts.parts.title', ['title' => __('Authorize Application')])
@endpush

@section('content')
    <div
        class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4 py-8 transition-colors duration-200">
        <div class="w-full max-w-2xl">
            <!-- Main Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 overflow-hidden transition-colors duration-200">

                <!-- Compact Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-linear-to-br from-blue-500 to-indigo-600 dark:from-blue-400 dark:to-indigo-500 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" />
                                    <path d="M10 4a1 1 0 011 1v5a1 1 0 01-1 1H6a1 1 0 110-2h3V5a1 1 0 011-1z" />
                                </svg>
                            </div>
                            <div>
                                <h2
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300 transition-colors duration-200">
                                    {{ config('app.name') }}</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                    {{ __('Authorization Request') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if ($client->private)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 transition-colors duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Trusted') }}
                                </span>
                            @endif
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-200 truncate max-w-30">
                                {{ Auth::user()->name ?? Auth::user()->email }}
                            </span>
                            <x-theme />
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-6">
                    <!-- App Info -->
                    <div class="flex items-start space-x-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-linear-to-br from-purple-500 to-pink-500 dark:from-purple-400 dark:to-pink-400 flex items-center justify-center shrink-0 shadow-lg shadow-purple-500/20 dark:shadow-purple-500/10">
                            <span class="text-white font-semibold text-lg">{{ substr($client->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-gray-100 transition-colors duration-200">
                                {{ $client->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-200">
                                {{ __('is requesting access to your account') }}
                            </p>
                            @if ($client->private)
                                <div
                                    class="mt-1 inline-flex items-center text-xs text-emerald-600 dark:text-emerald-400 transition-colors duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 11.586V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Verified application from your organization') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Required Permissions -->
                    @if (count($scopes) > 0)
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700 transition-colors duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <h4
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 dark:text-gray-500 transition-colors duration-200"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5zm2 2h6v2H7V7zm0 4h6v2H7v-2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Required Permissions') }}
                                    <span
                                        class="ml-2 text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full transition-colors duration-200">{{ count($scopes) }}</span>
                                </h4>
                            </div>
                            <div
                                class="space-y-2 max-h-32 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                                @foreach ($scopes as $scope)
                                    <div class="flex items-start text-sm">
                                        <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 mr-2 mt-0.5 shrink-0 transition-colors duration-200"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span
                                            class="text-gray-700 dark:text-gray-300 transition-colors duration-200">{{ __($scope->description) }}</span>
                                        <span
                                            class="ml-2 text-xs text-gray-400 dark:text-gray-500 transition-colors duration-200">·
                                            {{ __($scope->id) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Security Notice -->
                    <div
                        class="flex items-start space-x-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/30 transition-colors duration-200">
                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0 mt-0.5 transition-colors duration-200"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="text-xs text-gray-600 dark:text-gray-400 transition-colors duration-200">
                            <span
                                class="font-medium text-blue-700 dark:text-blue-400 transition-colors duration-200">{{ __('Secure authorization') }}</span>
                            <p class="mt-0.5">{{ __('You can revoke access at any time from your account settings.') }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 space-y-3">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <form method="POST" action="{{ route('passport.authorizations.deny') }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                                <input type="hidden" name="auth_token" value="{{ $authToken }}">

                                <button type="submit"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer text-sm">
                                    {{ __('Cancel') }}
                                </button>
                            </form>

                            <form method="post"
                                action="{{ route('passport.authorizations.approve') . '?nonce=' . $request->nonce }}"
                                class="flex-1">
                                @csrf
                                <input type="hidden" name="state" value="{{ $request->state }}">
                                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                                <input type="hidden" name="auth_token" value="{{ $authToken }}">

                                <button type="submit"
                                    class="w-full px-4 py-2.5 bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-600 dark:hover:to-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md cursor-pointer text-sm">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ __('Authorize Application') }}
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- App Details Toggle -->
                        <details class="group">
                            <summary
                                class="flex items-center justify-center cursor-pointer text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                                <span>{{ __('View application details') }}</span>
                                <svg class="w-4 h-4 ml-1 transition-transform duration-200 group-open:rotate-180"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </summary>
                            <div
                                class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg space-y-2 text-xs transition-colors duration-200">
                                <div class="flex justify-between">
                                    <span
                                        class="text-gray-500 dark:text-gray-400 transition-colors duration-200">{{ __('Client ID') }}</span>
                                    <span
                                        class="font-mono text-gray-700 dark:text-gray-300 transition-colors duration-200">{{ $client->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span
                                        class="text-gray-500 dark:text-gray-400 transition-colors duration-200">{{ __('Redirect URI') }}</span>
                                    <span
                                        class="text-gray-700 dark:text-gray-300 truncate max-w-50 transition-colors duration-200">
                                        @php
                                            $redirectUris = $client->redirect_uris;
                                            if (is_string($redirectUris)) {
                                                $uris = json_decode($redirectUris, true);
                                            } else {
                                                $uris = $redirectUris;
                                            }
                                            $firstUri = is_array($uris) && count($uris) > 0 ? $uris[0] : __('Not set');
                                        @endphp
                                        {{ $firstUri }}
                                    </span>
                                </div>
                                @if ($client->private)
                                    <div class="flex justify-between">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 transition-colors duration-200">{{ __('Application Type') }}</span>
                                        <span
                                            class="text-emerald-600 dark:text-emerald-400 font-medium transition-colors duration-200">{{ __('Internal') }}</span>
                                    </div>
                                @endif
                            </div>
                        </details>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="px-6 py-3 bg-gray-50/80 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 transition-colors duration-200">
                    <p class="text-center text-xs text-gray-400 dark:text-gray-500 transition-colors duration-200">
                        {{ __('Powered by') }}
                        <span class="font-medium text-gray-600 dark:text-gray-400 transition-colors duration-200">
                            {{ config('app.org_name', config('app.name')) }}
                        </span>
                        <span class="mx-1">·</span>
                        {{ __('Secure & Private') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar styling */
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 9999px;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #4b5563;
        }

        /* Smooth transitions for all interactive elements */
        button,
        a,
        .transition-colors,
        .transition-transform,
        .transition-all {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Focus states for accessibility */
        button:focus-visible,
        a:focus-visible,
        summary:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
            border-radius: 0.5rem;
        }

        .dark button:focus-visible,
        .dark a:focus-visible,
        .dark summary:focus-visible {
            outline-color: #60a5fa;
        }

        /* Improved card shadow for dark mode */
        .dark .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
        }

        /* linear enhancements for dark mode */
        .dark .bg-linear-to-br {
            filter: brightness(1.1);
        }

        /* Better border visibility in dark mode */
        .dark .border {
            border-color: rgba(55, 65, 81, 0.8);
        }

        /* Improved hover states */
        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .dark .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }

        /* Details marker customization */
        details summary::-webkit-details-marker {
            display: none;
        }

        details summary {
            list-style: none;
        }
    </style>
@endpush
