@php
    $routes = [
        [
            'name' => __('List of pages'),
            'route' => route('admin.pages.index'),
            'icon' => 'mdi mdi-file-document-outline',
        ],
        [
            'name' => __('Layouts'),
            'route' => route('admin.layouts.schema'),
            'icon' => 'mdi mdi-file-document-outline',
        ],
    ];
@endphp

<x-admin-layout :routes="$routes">

    @push('head')
        @include('layouts.parts.title', ['title' => __('Layout manager')])
    @endpush

    <v-slot:main>

        <section
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <!-- Header -->
            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('Layout content') }}
                </h3>
            </div>

            <!-- Layout selector -->
            <div class="px-6 pt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Layout') }}
                </label>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.layouts.schema', ['layout' => 'footer']) }}"
                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-all
                        {{ request('layout', 'footer') === 'footer'
                            ? 'bg-blue-600 text-white shadow-sm shadow-blue-200 dark:shadow-none'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        <i class="mdi mdi-page-layout-footer text-lg"></i>
                        {{ __('Footer') }}
                    </a>

                    <a href="{{ route('admin.layouts.schema', ['layout' => 'header']) }}"
                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-all
                        {{ request('layout') === 'header'
                            ? 'bg-blue-600 text-white shadow-sm shadow-blue-200 dark:shadow-none'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        <i class="mdi mdi-page-layout-header text-lg"></i>
                        {{ __('Header') }}
                    </a>

                    <a href="{{ route('admin.layouts.schema', ['layout' => 'schema']) }}"
                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-all
                        {{ request('layout') === 'schema'
                            ? 'bg-blue-600 text-white shadow-sm shadow-blue-200 dark:shadow-none'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                        <i class="mdi mdi-code-braces text-lg"></i>
                        {{ __('Schema') }}
                    </a>
                </div>
            </div>

            <!-- Editor form -->
            <form method="POST" action="{{ route('admin.layouts.update') }}" class="space-y-6">
                @csrf
                @method('PUT')
                <!-- send selected layout -->
                <input type="hidden" name="layout" value="{{ request('layout', 'footer') }}">

                <div class="px-6 py-6">
                    <x-editor label="{{ __('Edit content') }}" content="{{ $content }}"
                        preview="{{ false }}" jodit="{{ false }}" name="content" lang="php" />
                </div>

                <div class="px-6 pb-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/50">
                        <i class="mdi mdi-content-save-outline mr-2 text-lg"></i>
                        {{ __('Save changes') }}
                    </button>
                </div>
            </form>

        </section>
    </v-slot:main>

</x-admin-layout>
