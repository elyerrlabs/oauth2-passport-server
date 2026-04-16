{{-- BOTÓN --}}
<button type="button"
    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors cursor-pointer"
    data-delete-button data-id="{{ $page->id }}" data-name="{{ $page->name }}" data-slug="{{ $page->slug }}"
    title="Delete">
    <i class="mdi mdi-delete text-lg"></i>
</button>

{{-- MODAL --}}
<div id="deletePageModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="delete-page-modal-title"
    role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center px-4 py-6 text-center">

        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-slate-900/60" data-close-modal></div>

        <div
            class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white text-left shadow-2xl dark:border-gray-700 dark:bg-gray-800">

            {{-- HEADER --}}
            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-red-600 dark:text-red-400">
                            Page manager
                        </p>
                        <h3 id="delete-page-modal-title"
                            class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                            Delete page
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            This action cannot be undone.
                        </p>
                    </div>

                    <button type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                        data-close-modal>
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- FORM --}}
            <form id="deletePageForm" method="POST" class="space-y-5 px-6 py-6">
                @csrf
                @method('DELETE')

                <div
                    class="rounded-xl border border-red-200 bg-red-50 px-4 py-4 dark:border-red-900/60 dark:bg-red-950/40">
                    <p class="text-sm font-semibold text-red-800 dark:text-red-300">
                        Warning!
                    </p>
                    <p class="text-sm text-red-700 dark:text-red-400">
                        This page will be permanently deleted.
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Page
                    </label>
                    <div
                        class="rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 dark:border-gray-600 dark:bg-gray-900">
                        <p id="deletePageName" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                        <p id="deletePageSlug" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
                    <button type="button" class="px-4 py-2.5 text-sm border rounded-xl" data-close-modal>
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2.5 text-sm bg-red-600 text-white rounded-xl hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script nonce="{{ $nonce }}">
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('deletePageModal');
        const form = document.getElementById('deletePageForm');
        const nameEl = document.getElementById('deletePageName');
        const slugEl = document.getElementById('deletePageSlug');

        const routeTemplate = @json(route('admin.pages.destroy', ':id'));

        // Abrir modal
        document.querySelectorAll('[data-delete-button]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                const slug = btn.dataset.slug;

                nameEl.textContent = name;
                slugEl.textContent = slug ? `Slug: ${slug}` : '';

                form.action = routeTemplate.replace(':id', id);

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        // Cerrar modal
        document.querySelectorAll('[data-close-modal]').forEach(el => {
            el.addEventListener('click', closeModal);
        });

        // ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
</script>
