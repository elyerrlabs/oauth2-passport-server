<button type="button" id="openCreateModalBtn"
    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-sm">
    <i class="mdi mdi-plus-circle-outline mr-2"></i>
    {{ __('Create New Page') }}
</button>

<div id="createPageModal"
    class="fixed inset-0 z-50 {{ ($openCreateModal ?? false) || $errors->any() ? '' : 'hidden' }} overflow-y-auto"
    aria-labelledby="create-page-modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center px-4 py-6 text-center">
        <div id="modalBackdrop" class="absolute inset-0 bg-slate-900/60 transition-opacity" aria-hidden="true"></div>

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

                    <button type="button" id="closeModalBtn"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="createPageForm" method="POST" action="{{ route('admin.pages.store') }}"
                class="space-y-5 px-6 py-6">
                @csrf

                @if ($errors->any())
                    <div
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
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
                    <button type="button" id="cancelModalBtn"
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

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', () => {
            (function() {
                'use strict';

                // Elementos del DOM
                const modal = document.getElementById('createPageModal');
                const backdrop = document.getElementById('modalBackdrop');
                const closeBtn = document.getElementById('closeModalBtn');
                const cancelBtn = document.getElementById('cancelModalBtn');
                const openBtn = document.getElementById('openCreateModalBtn');
                const form = document.getElementById('createPageForm');

                // Función para cerrar el modal
                window.closeCreatePageModal = function() {
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                };

                // Función para abrir el modal
                window.openCreatePageModal = function() {
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                };

                // Abrir modal con el botón principal
                if (openBtn) {
                    openBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.openCreatePageModal();
                    });
                }

                // Cerrar con el botón X
                if (closeBtn) {
                    closeBtn.addEventListener('click', closeCreatePageModal);
                }

                // Cerrar con el botón Cancelar
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', closeCreatePageModal);
                }

                // Cerrar con el backdrop
                if (backdrop) {
                    backdrop.addEventListener('click', closeCreatePageModal);
                }

                // Cerrar con la tecla ESC
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                        closeCreatePageModal();
                    }
                });

                // Validación del formulario antes de enviar
                if (form) {
                    form.addEventListener('submit', function(event) {
                        const pageName = document.getElementById('page_name');
                        const pageSlug = document.getElementById('page_slug');
                        let hasError = false;

                        // Remover mensajes de error existentes
                        const existingErrors = form.querySelectorAll('.dynamic-error');
                        existingErrors.forEach(error => error.remove());

                        // Validar nombre
                        if (pageName && !pageName.value.trim()) {
                            showError(pageName, '{{ __('The page name is required.') }}');
                            hasError = true;
                        }

                        // Validar slug (opcional pero recomendado)
                        if (pageSlug && pageSlug.value.trim() && !isValidSlug(pageSlug.value.trim())) {
                            showError(pageSlug,
                                '{{ __('The slug must contain only letters, numbers, and hyphens.') }}'
                                );
                            hasError = true;
                        }

                        if (hasError) {
                            event.preventDefault();
                        }
                    });
                }

                // Función para mostrar errores dinámicamente
                function showError(inputElement, message) {
                    if (!inputElement) return;

                    // Crear elemento de error
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'mt-2 text-sm text-red-600 dark:text-red-400 dynamic-error';
                    errorDiv.textContent = message;

                    // Insertar después del input
                    inputElement.parentNode.appendChild(errorDiv);

                    // Agregar clase de error al input
                    inputElement.classList.add('border-red-500', 'dark:border-red-500');

                    // Remover error al empezar a escribir
                    inputElement.addEventListener('input', function() {
                        errorDiv.remove();
                        inputElement.classList.remove('border-red-500', 'dark:border-red-500');
                    }, {
                        once: true
                    });
                }

                // Función para validar slug
                function isValidSlug(slug) {
                    const slugRegex = /^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/;
                    return slugRegex.test(slug);
                }

                // Prevenir propagación de clics en el modal content
                const modalContent = modal ? modal.querySelector('.relative') : null;
                if (modalContent) {
                    modalContent.addEventListener('click', function(event) {
                        event.stopPropagation();
                    });
                }

                // Auto-generar slug desde el nombre (opcional)
                const pageNameInput = document.getElementById('page_name');
                const pageSlugInput = document.getElementById('page_slug');

                if (pageNameInput && pageSlugInput) {
                    // Generar slug automáticamente cuando el usuario escribe el nombre
                    // y el slug está vacío o fue generado automáticamente
                    let autoGenerated = true;

                    pageSlugInput.addEventListener('input', function() {
                        autoGenerated = false;
                    });

                    pageNameInput.addEventListener('input', function() {
                        if (autoGenerated && pageNameInput.value.trim()) {
                            const generatedSlug = generateSlug(pageNameInput.value.trim());
                            pageSlugInput.value = generatedSlug;
                        }
                    });
                }

                // Función para generar slug a partir de texto
                function generateSlug(text) {
                    return text
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '') // Eliminar caracteres especiales
                        .replace(/\s+/g, '-') // Reemplazar espacios con guiones
                        .replace(/--+/g, '-') // Reemplazar múltiples guiones
                        .trim();
                }
            })();
        });
    </script>
@endpush
