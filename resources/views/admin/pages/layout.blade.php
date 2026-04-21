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
            <form method="GET" action="{{ route('admin.layouts.schema') }}" id="layoutForm">
                <div class="px-6 pt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Layout') }}
                    </label>

                    <select name="layout" id="layoutSelect"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:ring-blue-900/50">

                        <option value="footer" {{ request('layout', 'footer') === 'footer' ? 'selected' : '' }}>
                            {{ __('Footer') }}
                        </option>

                        <option value="header" {{ request('layout') === 'header' ? 'selected' : '' }}>
                            {{ __('Header') }}
                        </option>

                        <option value="schema" {{ request('layout') === 'schema' ? 'selected' : '' }}>
                            {{ __('Schema') }}
                        </option>
                    </select>
                </div>
            </form>

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

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('layoutSelect');
                const form = document.getElementById('layoutForm');

                if (select && form) {
                    select.addEventListener('change', function() {
                        form.submit();
                    });
                }
            });
        </script>
    @endpush
</x-admin-layout>
