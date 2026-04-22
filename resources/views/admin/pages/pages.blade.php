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
        @include('layouts.parts.title', ['title' => __('Page manager')])
    @endpush


    <v-slot:main>
        <div class="container mx-auto px-4 py-6">
            <!-- Header Section -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Pages Management') }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage all your website pages') }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('admin.pages.generate-sitemap') }}" method="post">
                        @csrf
                        <button
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 shadow-sm">
                            <span class="mdi mdi-hammer-wrench text-lg"></span>
                            {{ __('Generate sitemap') }}
                        </button>
                    </form>
                    @include('admin.pages.form')
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6 p-4">
                <form method="GET" action="{{ route('admin.pages.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name, description or slug..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="md:w-40">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ 'Order by' }}</label>
                        <select name="order_by"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>
                                {{ __('Name') }}
                            </option>
                            <option value="is_published" {{ request('order_by') == 'is_published' ? 'selected' : '' }}>
                                {{ __('Published') }}
                            </option>
                            <option value="is_draft" {{ request('order_by') == 'is_draft' ? 'selected' : '' }}>
                                {{ __('Draft') }}
                            </option>
                        </select>
                    </div>

                    <div class="md:w-40">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ 'Order Type' }}</label>
                        <select name="order_type"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="asc" {{ request('order_type') == 'asc' ? 'selected' : '' }}>
                                {{ __('Ascending') }}
                            </option>
                            <option value="desc" {{ request('order_type') == 'desc' ? 'selected' : '' }}>
                                {{ __('Descending') }}
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <i class="mdi mdi-magnify mr-1"></i> {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.pages.index') }}"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                            <i class="mdi mdi-refresh mr-1"></i> {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Pages Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Name') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Slug') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Status') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Last Updated') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pages as $page)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">

                                    <!-- Name & Description -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $page->name }}
                                        </div>
                                    </td>

                                    <!-- Slug/Path -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                {{ !empty($page->slug) ? $page->slug : '/' }}
                                            </code>
                                        </div>
                                        @if ($page->path)
                                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                Path: {{ $page->path }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            @if ($page->is_published)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <i class="mdi mdi-check-circle mr-1 text-xs"></i> Published
                                                </span>
                                            @elseif($page->is_draft)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    <i class="mdi mdi-pencil-outline mr-1 text-xs"></i> Draft
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    <i class="mdi mdi-eye-off mr-1 text-xs"></i> Unpublished
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Last Updated -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $page->updated_at->diffForHumans() }}
                                        <div class="text-xs">{{ $page->updated_at->format('Y-m-d H:i') }}</div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.pages.edit', $page->id) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                                title="Edit">
                                                <i class="mdi mdi-pencil text-lg"></i>
                                            </a>

                                            @include('admin.pages.delete', ['page' => $page])

                                            @if ($page->is_published)
                                                <a href="{{ url($page->slug) }}" target="_blank"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors"
                                                    title="View">
                                                    <i class="mdi mdi-eye text-lg"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="mdi mdi-file-document-outline text-6xl text-gray-400 dark:text-gray-600 mb-3"></i>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No pages
                                                found
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Try adjusting your
                                                search
                                                or filter criteria</p>
                                            <button type="button" onclick="openCreatePageModal()"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                                <i class="mdi mdi-plus-circle-outline mr-2"></i>
                                                Create your first page
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($pages->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Showing {{ $pages->firstItem() ?? 0 }} to {{ $pages->lastItem() ?? 0 }} of
                                {{ $pages->total() }} results
                            </div>
                            <div class="flex justify-center">
                                {{ $pages->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>


        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="mdi mdi-alert text-red-600 dark:text-red-200 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    Delete Page
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete <strong id="deletePageName"></strong>? This
                                        action
                                        cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                        </form>
                        <button type="button" onclick="closeDeleteModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </v-slot:main>

</x-admin-layout>


@push('css')
    <style nonce="{{ $nonce }}">
        /* Custom pagination styling */
        .pagination {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            color: #4B5563;
            transition: all 0.2s;
        }

        .dark .pagination a,
        .dark .pagination span {
            color: #9CA3AF;
        }

        .pagination a:hover {
            background-color: #E5E7EB;
            color: #1F2937;
        }

        .dark .pagination a:hover {
            background-color: #374151;
            color: #F3F4F6;
        }

        .pagination .active span {
            background-color: #3B82F6;
            color: white;
        }

        .pagination .disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('js')
    <script nonce="{{ $nonce }}">
        function openCreatePageModal() {
            const modal = document.getElementById('createPageModal');
            if (!modal) return;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            const input = document.getElementById('page_name');
            if (input) {
                setTimeout(() => input.focus(), 50);
            }
        }

        function closeCreatePageModal() {
            const modal = document.getElementById('createPageModal');
            if (!modal) return;

            modal.classList.add('hidden');
            document.body.style.overflow = 'hidden';
        }

        function confirmDelete(pageId, pageName) {
            const modal = document.getElementById('deleteModal');
            const nameElement = document.getElementById('deletePageName');
            const form = document.getElementById('deleteForm');

            if (!modal || !nameElement || !form) return;

            nameElement.textContent = pageName;
            form.action = `/admin/pages/${pageId}`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (!modal) return;

            modal.classList.add('hidden');
            document.body.style.overflow = 'hidden';
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if (($openCreateModal ?? false) || $errors->any())
                openCreatePageModal();
            @endif

            document.addEventListener('keydown', function(event) {
                if (event.key !== 'Escape') {
                    return;
                }

                const createModal = document.getElementById('createPageModal');
                const deleteModal = document.getElementById('deleteModal');

                if (createModal && !createModal.classList.contains('hidden')) {
                    closeCreatePageModal();
                }

                if (deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
            });
        });
    </script>
@endpush
