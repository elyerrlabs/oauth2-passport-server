@extends('layouts.pages')

@push('head')
    <title>{{ config('app.name', 'Oauth2 Passport Server') }} - Language Manager</title>
    <meta name="description" content="Manage and edit language files with an intuitive file manager interface.">
    <meta name="author" content="{{ config('app.name') }}">
@endpush

@push('css')
    <style nonce="{{ $nonce }}">
        .file-item {
            @apply flex items-center px-3 py-2 mr-1 rounded-lg cursor-pointer transition-all duration-200;
        }

        .file-item:hover {
            @apply bg-gray-100 dark:bg-gray-800;
        }

        .file-active {
            @apply bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/40 dark:to-indigo-900/40 text-blue-700 dark:text-blue-300 border-l-4 border-blue-600 dark:border-blue-400;
        }

        .folder-item {
            @apply flex items-center px-3 py-2 mr-1 rounded-lg cursor-pointer transition-all duration-200 font-medium;
        }

        .folder-item:hover {
            @apply bg-gray-100 dark:bg-gray-800;
        }

        .editor-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .dark .editor-container {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        .file-item svg,
        .folder-item svg {
            @apply flex-shrink-0;
        }

        #langTree::-webkit-scrollbar {
            width: 6px;
        }

        #langTree::-webkit-scrollbar-track {
            background: transparent;
        }

        #langTree::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .dark #langTree::-webkit-scrollbar-thumb {
            background: #334155;
        }

        .file-badge {
            @apply text-xs px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300;
        }

        /* Save button animation */
        .save-btn {
            @apply relative overflow-hidden;
        }

        .save-btn::before {
            content: '';
            @apply absolute inset-0 bg-white/20 translate-y-full transition-transform duration-200;
        }

        .save-btn:hover::before {
            @apply translate-y-0;
        }

        /* Folder children spacing */
        .folder-children {
            @apply ml-5 pl-2 border-l border-gray-200 dark:border-gray-700;
        }

        /* Delete button - solo visible en archivos .json */
        .delete-btn-json {
            @apply opacity-0 group-hover:opacity-100 transition-opacity duration-200;
        }

        .file-item:hover .delete-btn-json {
            @apply opacity-100;
        }
    </style>
@endpush

@section('header')
    @include('pages.layouts.header')
@endsection

@section('content')
    <div class="flex h-[calc(100vh-64px)] bg-gray-50 dark:bg-gray-900">
        {{-- Sidebar --}}
        <aside class="w-80 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                            </svg>
                        </div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Languages</h2>
                    </div>

                    {{-- Create Button --}}
                    <button id="createLangBtn"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow flex-shrink-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New</span>
                    </button>
                </div>

                {{-- File count info --}}
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    <span id="fileCount">0</span> files
                </div>
            </div>

            <div id="langTree" class="flex-1 p-3 space-y-0.5 overflow-y-auto text-sm"></div>
        </aside>

        {{-- Editor --}}
        <div class="flex-1 flex flex-col editor-container">
            <div
                class="flex items-center justify-between px-5 h-14 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="currentFile" class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $file_name ?? 'No file selected' }}
                        </span>
                    </div>

                    {{-- Unsaved indicator --}}
                    <span id="unsavedIndicator" class="text-xs text-yellow-600 dark:text-yellow-400 hidden">
                        <span class="inline-block w-1.5 h-1.5 bg-yellow-600 dark:bg-yellow-400 rounded-full mr-1.5"></span>
                        Unsaved changes
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Delete Button - solo visible si es un archivo .json --}}
                    @if (isset($file_name) && str_ends_with($file_name, '.json'))
                        <button id="deleteFileBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex-shrink-0 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Delete</span>
                        </button>
                    @endif

                    {{-- Save Button --}}
                    <button id="saveFile"
                        class="save-btn group relative inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md flex-shrink-0 cursor-pointer overflow-hidden"
                        {{ isset($file_name) ? '' : 'disabled' }}>
                        <span
                            class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-200"></span>
                        <svg class="w-4 h-4 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span class="relative z-10">Save Changes</span>
                    </button>
                </div>
            </div>

            <div class="flex-1 p-5 overflow-auto">
                <form id="editorForm" method="POST" action="{{ route('admin.langs.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="file" id="currentFileInput" value="{{ $file_name ?? '' }}">
                    <x-editor label="{{ __('Keys') }}" name="content" :content="$file_content" :jodit="false"
                        :preview="false" :lang="$lang ?? 'json'" />
                </form>
            </div>
        </div>
    </div>

    {{-- Create Language Modal --}}
    <div id="createLangModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div class="flex min-h-screen items-center justify-center px-4 py-6">
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity" id="modalOverlay">
            </div>

            <div
                class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                {{-- Modal Header --}}
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Language
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add a new language locale to your application
                    </p>
                </div>

                {{-- Modal Body --}}
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Language Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="langNameInput" placeholder="e.g., es, fr, de, pt-BR"
                            class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">
                            Use two-letter ISO code (e.g., <code
                                class="bg-gray-100 dark:bg-gray-900 px-1.5 py-0.5 rounded">en</code>, <code
                                class="bg-gray-100 dark:bg-gray-900 px-1.5 py-0.5 rounded">es</code>) or with region (<code
                                class="bg-gray-100 dark:bg-gray-900 px-1.5 py-0.5 rounded">pt-BR</code>, <code
                                class="bg-gray-100 dark:bg-gray-900 px-1.5 py-0.5 rounded">en-US</code>)
                        </p>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <p class="text-xs text-blue-800 dark:text-blue-300">
                            <span class="font-medium">Examples:</span>
                            <span class="inline-flex flex-wrap gap-1 mt-1">
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">en</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">es</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">fr</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">de</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">it</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">pt</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">pt-BR</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">zh</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">ja</span>
                                <span
                                    class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded text-blue-700 dark:text-blue-300">ru</span>
                            </span>
                        </p>
                    </div>

                    {{-- Preview --}}
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">File will be created as:</p>
                        <code class="text-sm text-gray-900 dark:text-gray-200" id="filePreview">—.json</code>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end gap-2 p-5 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" id="cancelCreateLang"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="button" id="confirmCreateLang"
                        class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Language
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div class="flex min-h-screen items-center justify-center px-4 py-6">
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity"
                id="deleteModalOverlay"></div>

            <div
                class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                {{-- Modal Header --}}
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Language File
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">This action cannot be undone</p>
                </div>

                {{-- Modal Body --}}
                <div class="p-5">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <p class="text-sm text-red-800 dark:text-red-300">
                            Are you sure you want to delete <strong id="deleteFileName"></strong>?
                        </p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                            This will permanently delete the language file and all its translations.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end gap-2 p-5 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" id="cancelDeleteBtn"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="file" id="deleteFileInput" value="">
                        <button type="submit" id="confirmDeleteBtn"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        const TREE = @json($tree);
        const INITIAL_FILE = @json($file_name ?? null);
        const INDEX_URL = @json(route('admin.langs.index'));
        const STORE_URL = @json(route('admin.langs.store'));
        const DELETE_URL = @json(route('admin.langs.delete', ['file' => '__FILE__']));

        document.addEventListener("DOMContentLoaded", function() {

            $(function() {
                let currentFile = INITIAL_FILE;
                let originalContent = $('textarea[name="content"]').val() || '';
                let isDirty = false;
                let fileCount = 0;

                const $createModal = $('#createLangModal');
                const $deleteModal = $('#deleteModal');
                const $langNameInput = $('#langNameInput');
                const $unsavedIndicator = $('#unsavedIndicator');
                const $filePreview = $('#filePreview');
                const $deleteFileName = $('#deleteFileName');
                const $deleteFileInput = $('#deleteFileInput');
                const $deleteForm = $('#deleteForm');

                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                function countFiles(items) {
                    let count = 0;
                    items.forEach(item => {
                        if (item.type === 'folder' && item.children) {
                            count += countFiles(item.children);
                        } else if (item.type !== 'folder') {
                            count++;
                        }
                    });
                    return count;
                }

                function loadTree() {
                    let html = '';
                    TREE.forEach(item => {
                        html += renderItem(item);
                    });
                    $('#langTree').html(html);

                    fileCount = countFiles(TREE);
                    $('#fileCount').text(fileCount);

                    if (INITIAL_FILE) {
                        setTimeout(() => {
                            const escapedFile = INITIAL_FILE.replace(/\./g, '\\.').replace(/\//g,
                                '\\/');
                            const $file = $(`.file-node[data-file="${escapedFile}"]`);
                            if ($file.length) {
                                $file.addClass('file-active');
                                $file[0].scrollIntoView({
                                    block: 'nearest',
                                    behavior: 'smooth'
                                });
                            }
                        }, 100);
                    }
                }

                function renderItem(item, level = 0, parentPath = '') {
                    let padding = level * 20;
                    let escapedLabel = escapeHtml(item.label);
                    let currentPath = parentPath ? parentPath + '/' + item.label : item.label;

                    if (item.type === 'folder') {
                        let children = '';
                        if (item.children) {
                            item.children.forEach(child => {
                                children += renderItem(child, level + 1, currentPath);
                            });
                        }

                        return `
                        <div class="folder-container mb-0.5">
                            <div class="folder-item flex justify-around cursor-pointer" style="padding-left:${padding}px" data-expanded="true" data-path="${currentPath}">
                                <svg class="w-4 h-4 mr-2 text-yellow-500 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span class="flex-1 text-gray-700 dark:text-gray-200">${escapedLabel}</span>
                                <span class="file-badge flex-shrink-0">${item.children ? item.children.length : 0}</span>
                            </div>
                            <div class="folder-children">
                                ${children}
                            </div>
                        </div>
                    `;
                    }

                    let icon = '';
                    let iconColor = '';
                    // Determinar si es un archivo .json
                    const isJsonFile = escapedLabel.endsWith('.json');

                    if (escapedLabel.endsWith('.php')) {
                        icon =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />';
                        iconColor = 'text-purple-500 dark:text-purple-400';
                    } else if (isJsonFile) {
                        icon =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />';
                        iconColor = 'text-green-500 dark:text-green-400';
                    } else {
                        icon =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />';
                        iconColor = 'text-blue-500 dark:text-blue-400';
                    }

                    // Solo agregar botón de eliminar si es un archivo .json
                    const deleteButton = isJsonFile ? `
                    <button class="delete-file-btn delete-btn-json p-1 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200 flex-shrink-0" data-file="${currentPath}" title="Delete JSON file">
                        <svg class="w-3.5 h-3.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                ` : '';

                    return `
                    <div class="file-item flex justify-between group file-node cursor-pointer mb-0.5" data-file="${currentPath}" style="padding-left:${padding}px">
                        <svg class="w-4 h-4 mr-2 ${iconColor} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${icon}
                        </svg>
                        <span class="flex-1 truncate text-gray-700 dark:text-gray-200">${escapedLabel}</span>
                        ${deleteButton}
                    </div>
                `;
                }

                function checkDirty() {
                    const currentContent = $('textarea[name="content"]').val() || '';
                    isDirty = currentContent !== originalContent;

                    if (isDirty) {
                        $unsavedIndicator.removeClass('hidden');
                    } else {
                        $unsavedIndicator.addClass('hidden');
                    }
                }

                $(document).on('input', 'textarea[name="content"]', checkDirty);

                $(document).on('click', '.file-node', function(e) {
                    if ($(e.target).closest('.delete-file-btn').length) {
                        return;
                    }

                    if (isDirty) {
                        if (!confirm('You have unsaved changes. Are you sure you want to leave?')) {
                            return;
                        }
                    }

                    $('.file-node').removeClass('file-active');
                    $(this).addClass('file-active');

                    const fileName = $(this).data('file');
                    window.location.href = INDEX_URL + '?file=' + encodeURIComponent(fileName);
                });

                // Eliminar archivo .json - Abrir modal
                $(document).on('click', '.delete-file-btn', function(e) {
                    e.stopPropagation();
                    const fileName = $(this).data('file');
                    openDeleteModal(fileName);
                });

                $(document).on('click', '.folder-item', function() {
                    const $container = $(this).closest('.folder-container');
                    const $children = $container.find('.folder-children');
                    const $icon = $(this).find('svg');

                    $children.slideToggle(150);
                    $icon.css('transform', $children.is(':visible') ? 'rotate(0deg)' :
                        'rotate(-90deg)');
                    $(this).attr('data-expanded', $children.is(':visible'));
                });

                $('#saveFile').on('click', function() {
                    $('#editorForm').submit();
                });

                // Delete from toolbar - solo si existe el botón
                $('#deleteFileBtn').on('click', function() {
                    if (currentFile) {
                        openDeleteModal(currentFile);
                    }
                });

                function openDeleteModal(fileName) {
                    // Extraer solo el nombre del archivo sin la extensión .json para el controlador
                    const langName = fileName.replace(/\.json$/, '');
                    $deleteFileName.text(fileName);
                    $deleteFileInput.val(langName);
                    $deleteForm.attr('action', DELETE_URL.replace('__FILE__', encodeURIComponent(
                        langName)));
                    $deleteModal.removeClass('hidden');
                }

                // Cerrar modal de eliminar
                $('#cancelDeleteBtn, #deleteModalOverlay').on('click', function() {
                    $deleteModal.addClass('hidden');
                });

                // Actualizar preview del nombre del archivo
                $langNameInput.on('input', function() {
                    const name = $(this).val().trim();
                    if (name) {
                        $filePreview.text(name + '.json');
                    } else {
                        $filePreview.text('—.json');
                    }
                });

                // Modal de crear - Abrir
                $('#createLangBtn').on('click', function() {
                    $createModal.removeClass('hidden');
                    $langNameInput.val('').focus();
                    $filePreview.text('—.json');
                });

                // Modal de crear - Cerrar
                $('#cancelCreateLang, #modalOverlay').on('click', function() {
                    $createModal.addClass('hidden');
                });

                // Modal de crear - ESC key
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!$createModal.hasClass('hidden')) {
                            $createModal.addClass('hidden');
                        }
                        if (!$deleteModal.hasClass('hidden')) {
                            $deleteModal.addClass('hidden');
                        }
                    }
                });

                // Modal de crear - Confirmar
                $('#confirmCreateLang').on('click', function() {
                    let name = $langNameInput.val().trim().toLowerCase();

                    if (!name) {
                        alert('Please enter a language name');
                        return;
                    }

                    if (!/^[a-z]{2}(-[a-z]{2})?$/.test(name)) {
                        alert('Invalid format. Use "en" or "pt-BR"');
                        return;
                    }

                    const $btn = $(this);
                    const originalHtml = $btn.html();
                    $btn.html(
                        '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...'
                    );
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: STORE_URL,
                        method: 'POST',
                        data: {
                            name: name,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $createModal.addClass('hidden');
                            window.location.reload();
                        },
                        error: function(xhr) {
                            let error = 'Failed to create language';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                error = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                error = Object.values(errors).flat().join('\n');
                            }
                            alert(error);
                            $btn.html(originalHtml);
                            $btn.prop('disabled', false);
                        }
                    });
                });

                $('#editorForm').on('submit', function() {
                    setTimeout(() => {
                        originalContent = $('textarea[name="content"]').val() || '';
                        isDirty = false;
                        $unsavedIndicator.addClass('hidden');
                    }, 100);
                });

                loadTree();
            });
        });
    </script>
@endpush
