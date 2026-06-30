<x-admin-layout :routes="$routes">

    @push('head')
        @include('layouts.parts.title', ['title' => __('SEO Manager')])
    @endpush

    <v-slot:main>
        <!-- Card principal -->
        <section
            class="rounded-2xl   bg-white shadow-sm dark:bg-gray-800 border border-gray-100/50 dark:border-gray-700/50 mb-8">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="mdi mdi-google-analytics text-blue-600 dark:text-blue-400 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ __('SEO Manager') }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('Manage your SEO templates') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Layout selector -->
            <div class="px-6 pt-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap items-center gap-1.5">
                    <a href="{{ route('admin.policies.schema', ['layout' => 'terms-and-conditions']) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-150
                            {{ request('layout', 'terms-and-conditions') === 'terms-and-conditions'
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800'
                                : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                        <i class="mdi mdi-login text-sm"></i>
                        {{ __('Terms and condition') }}
                    </a>

                    <a href="{{ route('admin.policies.schema', ['layout' => 'policies-of-privacy']) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-150
                            {{ request('layout', 'policies-of-privacy') === 'policies-of-privacy'
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800'
                                : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                        <i class="mdi mdi-account-plus text-sm"></i>
                        {{ __('Policies of privacy') }}
                    </a>

                    <a href="{{ route('admin.policies.schema', ['layout' => 'policies-of-cookies']) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-150
                            {{ request('layout', 'policies-of-cookies') === 'policies-of-cookies'
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800'
                                : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                        <i class="mdi mdi-lock-reset text-sm"></i>
                        {{ __('Policies of cookies') }}
                    </a>
                </div>
            </div>

            <!-- Editor form -->
            <form method="POST" action="{{ route('admin.layouts.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="layout" value="{{ request('layout', 'login') }}">

                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <i class="mdi mdi-code-tags text-gray-400 dark:text-gray-500 text-sm"></i>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Edit content') }}
                            </span>
                            <span
                                class="text-[10px] text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">
                                PHP
                            </span>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-lg shadow-sm hover:shadow transition-all duration-150">
                            <i class="mdi mdi-content-save-outline text-sm"></i>
                            {{ __('Save changes') }}
                        </button>
                    </div>

                    <x-editor label="" content="{{ $content }}" preview="{{ true }}"
                        jodit="{{ true }}" name="content" lang="php"
                        class="rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm focus:ring-1 focus:ring-blue-500" />
                </div>

                <!-- Actions -->
                <div
                    class="flex items-center justify-end px-6 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-2xl">
                    <div class="flex items-center gap-2">
                        <button type="reset"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                            <i class="mdi mdi-undo-variant text-sm"></i>
                            {{ __('Reset') }}
                        </button>

                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-lg shadow-sm hover:shadow transition-all duration-150">
                            <i class="mdi mdi-content-save-outline text-sm"></i>
                            {{ __('Save changes') }}
                        </button>
                    </div>
                </div>
            </form>

        </section>
    </v-slot:main>

</x-admin-layout>
