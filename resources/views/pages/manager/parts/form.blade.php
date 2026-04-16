<div id="createPageModal"
    class="fixed inset-0 z-50 {{ ($openCreateModal ?? false) || $errors->any() ? '' : 'hidden' }} overflow-y-auto"
    aria-labelledby="create-page-modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center px-4 py-6 text-center">
        <div class="absolute inset-0 bg-slate-900/60 transition-opacity" aria-hidden="true"
            onclick="closeCreatePageModal()"></div>

        <div
            class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white text-left shadow-2xl dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">
                            {{ __('Page manager') }}
                        </p>
                        <h3 id="create-page-modal-title"
                            class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                            {{ __('Create page') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Enter the page name to create a new page record.') }}
                        </p>
                    </div>

                    <button type="button" onclick="closeCreatePageModal()"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pages.store') }}" class="space-y-5 px-6 py-6">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
                        <p class="font-semibold">{{ __('Please correct the following errors:') }}</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="page_name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Page name') }}
                    </label>
                    <input id="page_name" name="name" type="text" value="{{ old('name') }}"
                        placeholder="{{ __('Example: About us') }}"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="page_slug" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Page Slug') }}
                    </label>
                    <input id="page_slug" name="slug" type="text" value="{{ old('slug') }}"
                        placeholder="{{ __('Example: page-2') }}"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
                    <button type="button" onclick="closeCreatePageModal()"
                        class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/50">
                        <i class="mdi mdi-plus-circle-outline mr-2 text-lg"></i>
                        {{ __('Create page') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
