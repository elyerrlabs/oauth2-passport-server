<x-admin-layout :routes="$routes">
    @push('head')
        <title>{{ __('Favicon Manager - SEO Gallery') }}</title>
    @endpush

    <v-slot:main>
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ __('Favicon Manager') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Manage your website favicons for better SEO indexing') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 cursor-default">
                    <i class="mdi mdi-google-chrome text-sm mr-1"></i>
                    {{ __('SEO Gallery') }}
                </span>
            </div>
        </div>

        {{-- Info Alert --}}
        <div
            class="mb-6 p-4 rounded-lg bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/30 dark:to-blue-900/30 border border-indigo-200 dark:border-indigo-800">
            <div class="flex items-start gap-3">
                <div class="shrink-0">
                    <i class="mdi mdi-information-outline text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-indigo-900 dark:text-indigo-300">
                        {{ __('SEO Gallery Purpose') }}
                    </h3>
                    <p class="text-sm text-indigo-800 dark:text-indigo-200 mt-1">
                        {{ __('This gallery is exclusively for SEO improvement purposes. Only upload public files for indexing by search engines. All uploaded favicons will be accessible via public URLs for better search engine visibility.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Container --}}
        <div class="space-y-6">
            {{-- Upload Form --}}
            <div class="p-6 rounded-xl bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    {{ __('Upload New Favicons') }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    {{ __('Add multiple favicon files at once. These will be added to the gallery below.') }}
                </p>

                <form action="{{ route('admin.sitemaps.favicon.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="images"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 cursor-pointer">
                            {{ __('Select Images (Max 10 files)') }}
                        </label>

                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-3 flex-wrap">
                                <label for="images"
                                    class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-700 
                                              border border-gray-300 dark:border-gray-600 rounded-lg 
                                              shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 
                                              cursor-pointer transition-colors duration-200">
                                    <i class="mdi mdi-cloud-upload mr-2 text-gray-500 dark:text-gray-400"></i>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Choose Files') }}
                                    </span>
                                </label>
                                <span id="fileNames" class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No files chosen') }}
                                </span>
                            </div>
                            <div id="filePreview" class="flex flex-wrap gap-2 mt-2"></div>
                        </div>

                        <input type="file" name="images[]" id="images"
                            accept=".ico,.png,.jpg,.jpeg,.gif,.webp,.svg,.bmp" multiple class="hidden">

                        <div class="mt-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="mdi mdi-information-outline text-xs"></i>
                                {{ __('Accepted formats: JPG, JPEG, PNG, GIF, WEBP, ICO, SVG, BMP. Max file size: 2MB per file. Max 10 files.') }}
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                <i class="mdi mdi-lightbulb-outline text-xs"></i>
                                {{ __('Tip: Add multiple favicon sizes (16x16, 32x32, 180x180) for better device compatibility and SEO.') }}
                            </p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div
                            class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
                            <div class="flex items-start gap-2">
                                <i class="mdi mdi-alert-circle text-red-600 dark:text-red-400 mt-0.5"></i>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-red-800 dark:text-red-200">
                                        {{ __('Validation Errors') }}
                                    </h4>
                                    <ul class="text-sm text-red-700 dark:text-red-300 mt-1 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-medium 
                                       transition duration-150 ease-in-out
                                       text-white bg-blue-600 hover:bg-blue-700 
                                       dark:bg-blue-500 dark:hover:bg-blue-600 
                                       focus:outline-none focus:ring-4 focus:ring-blue-300 
                                       dark:focus:ring-blue-800 cursor-pointer">
                            <i class="mdi mdi-cloud-upload mr-2"></i>
                            <span>{{ __('Upload Images') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Gallery Section --}}
            <div class="p-6 rounded-xl bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            <i class="mdi mdi-image-multiple mr-2"></i>
                            {{ __('Favicon Gallery') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ __('Public files ready for search engine indexing') }}
                        </p>
                    </div>
                    @if (isset($images) && count($images) > 0)
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-database-outline mr-1"></i>
                            {{ count($images) }} {{ __('items') }}
                        </span>
                    @endif
                </div>

                @if (isset($images) && count($images) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach ($images as $index => $favicon)
                            <div
                                class="group relative bg-gray-50 dark:bg-gray-700/50 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-200">
                                <div
                                    class="aspect-square flex items-center justify-center p-4 bg-white dark:bg-gray-800">
                                    <img src="{{ $favicon['url'] }}" alt="{{ $favicon['name'] }}"
                                        class="max-w-full max-h-full object-contain">
                                </div>

                                <div
                                    class="p-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate cursor-default"
                                        title="{{ $favicon['name'] }}">
                                        <i class="mdi mdi-file-image-outline text-xs mr-1"></i>
                                        {{ $favicon['name'] }}
                                    </p>
                                    <div class="flex items-center justify-between mt-2">
                                        <a href="{{ $favicon['url'] }}" target="_blank"
                                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                                            <i class="mdi mdi-open-in-new text-xs mr-1"></i>
                                            {{ __('View URL') }}
                                        </a>
                                        <form
                                            action="{{ route('admin.sitemaps.favicon.delete', ['path' => base64_encode($favicon['path'])]) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('Are you sure you want to delete') }} {{ addslashes($favicon['name']) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors cursor-pointer">
                                                <i class="mdi mdi-delete-outline text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div
                        class="mt-6 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="flex items-center gap-2">
                            <i class="mdi mdi-check-circle text-green-600 dark:text-green-400"></i>
                            <p class="text-xs text-green-800 dark:text-green-300">
                                {{ __('All favicon URLs are publicly accessible for search engine crawlers and social media bots.') }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="mdi mdi-image-off text-6xl text-gray-400 dark:text-gray-500 mb-4 block"></i>
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('No favicons uploaded yet') }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            {{ __('Upload your first favicon files using the form above.') }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __('Tip: You can add favicon images to your system from here.') }}
                            <br>
                            <a href="{{ route('admin.layouts.update', ['layout' => 'favicon']) }}"
                                class="text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                                <i class="mdi mdi-cog-outline text-xs mr-1"></i>
                                {{ __('Configure favicon layout settings →') }}
                            </a>
                        </p>
                    </div>
                @endif
            </div>

            <div
                class="p-4 rounded-lg bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-800">
                <div class="flex items-start gap-3">
                    <div class="shrink-0">
                        <i class="mdi mdi-lightbulb-on-outline text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-purple-900 dark:text-purple-300">
                            <i class="mdi mdi-information-outline text-sm mr-1"></i>
                            {{ __('How to add favicon images to your system') }}
                        </h3>
                        <p class="text-sm text-purple-800 dark:text-purple-200 mt-1">
                            {{ __('You can add favicon images to your system directly from this gallery. Once uploaded, you can configure which favicon to use in your layout settings.') }}
                            <br>
                            <a href="{{ route('admin.layouts.update', ['layout' => 'favicon']) }}"
                                class="inline-flex items-center mt-2 text-purple-700 dark:text-purple-300 hover:text-purple-900 dark:hover:text-purple-100 font-medium cursor-pointer">
                                {{ __('Go to Layout Settings') }}
                                <i class="mdi mdi-arrow-right ml-1 text-sm"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-slot:main>

    @push('js')
        <script nonce="{{ $nonce }}">
            // Solo JS para la interfaz (previsualización)
            document.getElementById('images').addEventListener('change', function(e) {
                const files = e.target.files;
                const fileNamesSpan = document.getElementById('fileNames');
                const previewContainer = document.getElementById('filePreview');

                if (files.length === 0) {
                    fileNamesSpan.textContent = '{{ __('No files chosen') }}';
                    previewContainer.innerHTML = '';
                    return;
                }

                // Mostrar nombres
                const fileNames = Array.from(files).slice(0, 3).map(f => f.name);
                let displayText = fileNames.join(', ');
                if (files.length > 3) {
                    displayText += ' + ' + (files.length - 3) + ' more';
                }
                fileNamesSpan.textContent = displayText;

                // Previsualizar miniaturas
                previewContainer.innerHTML = '';
                Array.from(files).slice(0, 5).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group cursor-pointer';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="preview" class="w-12 h-12 object-cover rounded border border-gray-300 dark:border-gray-600">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 rounded flex items-center justify-center pointer-events-none">
                                <span class="text-white text-xs">${file.name.substring(0, 10)}${file.name.length > 10 ? '...' : ''}</span>
                            </div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });
        </script>
    @endpush
</x-admin-layout>
