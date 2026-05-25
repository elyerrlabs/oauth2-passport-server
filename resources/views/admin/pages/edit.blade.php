<x-admin-layout :routes="$routes">

    @push('head')
        @include('layouts.parts.title', ['title' => __('Edit page')])
    @endpush

    <v-slot:main>

        @php
            $publishedAtValue = old('published_at');

            if (!$publishedAtValue && !empty($page->published_at)) {
                try {
                    $publishedAtValue = \Illuminate\Support\Carbon::parse($page->published_at)->format('Y-m-d\TH:i');
                } catch (\Throwable $e) {
                    $publishedAtValue = null;
                }
            }
        @endphp

        <div class="container mx-auto px-4 py-6">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        {{ __('Page manager') }}
                    </p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __('Edit page') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Update the main configuration and content of this page.') }}
                    </p>
                </div>

                <div class="flex justify-around gap-2">

                    <a href="{{ route('admin.pages.show', $page->id) }}" target="_blank"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:border-blue-400 dark:hover:text-blue-400">
                        <i class="mdi mdi-arrow-right mr-2 text-lg"></i>
                        {{ __('Preview') }}
                    </a>

                    <button type="button" id="resetButton"
                        class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm font-medium text-red-600 shadow-sm transition hover:bg-red-50 hover:border-red-500 dark:border-red-700 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:border-red-500">
                        <i class="mdi mdi-refresh mr-2 text-lg"></i>
                        {{ __('Reset') }}
                    </button>

                    <form id="resetForm" action="{{ route('admin.pages.reset', ['page' => $page->id]) }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>

                    <a href="{{ route('admin.pages.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:border-blue-400 dark:hover:text-blue-400">
                        <i class="mdi mdi-arrow-left mr-2 text-lg"></i>
                        {{ __('Back to pages') }}
                    </a>
                </div>

            </div>

            <form method="POST" action="{{ route('admin.pages.update', $page->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Page settings') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Edit the basic attributes used to organize and publish the page.') }}
                        </p>
                    </div>

                    <div class="grid gap-6 px-6 py-6 md:grid-cols-2">
                        <div>
                            <label for="name"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Name') }}
                            </label>
                            <input id="name" name="name" type="text" value="{{ old('name', $page->name) }}"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_published"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Is published') }}
                            </label>
                            <select id="is_published" name="is_published"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="0"
                                    {{ (string) old('is_published', (int) $page->is_published) === '0' ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="1"
                                    {{ (string) old('is_published', (int) $page->is_published) === '1' ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="is_draft"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Is draft') }}
                            </label>
                            <select id="is_draft" name="is_draft"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="0"
                                    {{ (string) old('is_draft', (int) $page->is_draft) === '0' ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="1"
                                    {{ (string) old('is_draft', (int) $page->is_draft) === '1' ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="index"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Index page') }}
                            </label>
                            <select id="index" name="index"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="0"
                                    {{ (string) old('index', (int) $page->index) === '0' ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="1"
                                    {{ (string) old('index', (int) $page->index) === '1' ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Page content') }}
                        </h3>
                    </div>

                    <div class="px-6 py-6">
                        <x-editor label="{{ __('Edit content') }}" content="{{ $page->content }}"
                            preview="{{ false }}" jodit="{{ false }}" name="content" lang="php" />
                    </div>
                </section>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.pages.index') }}"
                        class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/50">
                        <i class="mdi mdi-content-save-outline mr-2 text-lg"></i>
                        {{ __('Save changes') }}
                    </button>
                </div>
            </form>
        </div>
    </v-slot:main>
</x-admin-layout>

<script nonce="{{ $nonce }}">
    $(document).ready(function() {
        $('#resetButton').on('click', function(e) {
            e.preventDefault();

            var confirmMessage =
                'WARNING: This will reset the template to the production version. All current changes will be lost. Are you sure?';

            if (confirm(confirmMessage)) {
                $('#resetForm').submit();
            }
        });
    });
</script>
