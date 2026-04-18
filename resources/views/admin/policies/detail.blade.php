@extends('layouts.pages')

@push('head')
    @include('layouts.parts.title', ['title' => $title])
@endpush

@section('header')
    @include('pages.layouts.header')
@endsection

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex justify-center items-start py-12 transition-colors duration-300">

        <!-- Main Content Area -->
        <main class="w-full max-w-4xl px-4 sm:px-6">
            <!-- Policies Navigation -->
            <nav class="mb-6" aria-label="Policies navigation">
                <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                    <a href="{{ route('legal.policies-of-cookies') }}"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ Route::currentRouteName() === 'legal.policies-of-cookies'
                                  ? 'bg-blue-600 text-white shadow-md'
                                  : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        {{ __('Cookie Policy') }}
                    </a>
                    <a href="{{ route('legal.policies-of-privacy') }}"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ Route::currentRouteName() === 'legal.policies-of-privacy'
                                  ? 'bg-blue-600 text-white shadow-md'
                                  : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        {{ __('Privacy Policy') }}
                    </a>
                    <a href="{{ route('legal.terms-and-conditions') }}"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ Route::currentRouteName() === 'legal.terms-and-conditions'
                                  ? 'bg-blue-600 text-white shadow-md'
                                  : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        {{ __('Terms & Conditions') }}
                    </a>
                </div>
            </nav>

            <!-- Breadcrumb -->
            <nav class="mb-4 text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <span class="mx-2">/</span>
                    </li>
                    <li>
                        <span class="text-gray-500 dark:text-gray-500">Legal</span>
                    </li>
                    <li>
                        <span class="mx-2">/</span>
                    </li>
                    <li class="text-gray-900 dark:text-gray-200 font-medium">
                        {{ $title ?? 'Policy' }}
                    </li>
                </ol>
            </nav>

            <!-- Content Card -->
            <article
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-2xl">

                <!-- Header with icon based on policy type -->
                <header class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-8 pt-8 pb-4">
                    <div class="flex items-start gap-4">
                        <!-- MDI Icon based on policy type -->
                        <div class="flex-shrink-0">
                            @switch(Route::currentRouteName())
                                @case('legal.policies-of-cookies')
                                    <i class="mdi mdi-cookie text-3xl text-blue-600 dark:text-blue-400"></i>
                                @break

                                @case('legal.policies-of-privacy')
                                    <i class="mdi mdi-shield-lock text-3xl text-blue-600 dark:text-blue-400"></i>
                                @break

                                @case('legal.terms-and-conditions')
                                    <i class="mdi mdi-file-document text-3xl text-blue-600 dark:text-blue-400"></i>
                                @break
                            @endswitch
                        </div>
                        <div class="flex-1">
                            <h1 id="policy-title" class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $title ?? 'Policy' }}
                            </h1>
                            @if ($lastUpdated ?? false)
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Last updated: {{ $lastUpdated }}
                                </p>
                            @endif
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="min-h-[450px] p-6 sm:p-8 md:p-10">
                    <div
                        class="prose prose-gray max-w-none 
                                dark:prose-invert 
                                prose-headings:font-semibold 
                                prose-h1:text-3xl prose-h1:mt-0
                                prose-h2:text-2xl prose-h2:mt-8 prose-h2:mb-4
                                prose-h3:text-xl prose-h3:mt-6
                                prose-p:text-gray-700 dark:prose-p:text-gray-300
                                prose-a:text-blue-600 dark:prose-a:text-blue-400
                                prose-a:no-underline hover:prose-a:underline
                                prose-strong:text-gray-900 dark:prose-strong:text-gray-100
                                prose-ul:list-disc prose-ul:pl-6
                                prose-ol:list-decimal prose-ol:pl-6
                                prose-li:my-1
                                prose-blockquote:border-l-4 prose-blockquote:border-blue-500 prose-blockquote:pl-4 prose-blockquote:italic">
                        {!! $content !!}
                    </div>
                </div>

                <!-- Footer with actions -->
                <footer class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-8 py-4">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Official legal document
                        </p>
                        <div class="flex gap-3">
                            <button onclick="window.print()"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 
                                           bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 
                                           rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                                aria-label="Print policy">
                                <i class="mdi mdi-printer text-base"></i>
                                Print
                            </button>
                            <button onclick="copyCurrentUrl()"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 
                                           bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 
                                           rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                                aria-label="Copy link">
                                <i class="mdi mdi-link-variant text-base"></i>
                                Copy link
                            </button>
                        </div>
                    </div>
                </footer>
            </article>
        </main>
    </div>

    <script>
        function copyCurrentUrl() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = event.currentTarget;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="mdi mdi-check text-base"></i> Copied!';
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                }, 2000);
            });
        }
    </script>
@endsection
