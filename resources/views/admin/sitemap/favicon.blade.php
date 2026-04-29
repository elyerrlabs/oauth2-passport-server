@php
    $routes = [
        [
            'name' => 'Sitemap URLs',
            'route' => route('admin.sitemaps.index'),
            'icon' => 'mdi mdi-sitemap',
        ],
        [
            'name' => 'Robots.txt',
            'route' => route('admin.sitemaps.robot.form'),
            'icon' => 'mdi mdi-robot',
        ],
        [
            'name' => 'Favicon',
            'route' => route('admin.sitemaps.favicon.form'),
            'icon' => 'mdi mdi-upload-circle-outline',
        ],
    ];
@endphp

<x-admin-layout :routes="$routes">
    @push('head')
        <title>{{ __('Favicon Manager') }}</title>
    @endpush

    <v-slot:main>
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ __('Favicon Manager') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Manage and customize the favicon for your website.') }}
                </p>
            </div>
        </div>

        {{-- Main Container --}}
        <div class="p-6 rounded-xl bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700">
            {{-- Favicon Upload Form --}}
            <form id="faviconForm" action="{{ route('admin.sitemaps.favicon.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                {{-- File Input Section --}}
                <div class="mb-4">
                    <label for="favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Upload Favicon') }}
                    </label>

                    {{-- Styled File Input --}}
                    <div class="flex items-center gap-3">
                        <label for="favicon"
                            class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-700 
                                      border border-gray-300 dark:border-gray-600 rounded-lg 
                                      shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 
                                      cursor-pointer transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Choose File') }}
                            </span>
                        </label>
                        <span id="fileName" class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('No file chosen') }}
                        </span>
                    </div>

                    <input type="file" name="favicon" id="favicon" accept=".ico,.png,.jpg,.jpeg" class="hidden">

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        {{ __('Accepted formats: .ico, .png, .jpg, .jpeg. Recommended size: 16x16 or 32x32 pixels.') }}
                    </p>
                </div>

                {{-- Current Favicon Preview --}}
                @if ($favicon)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Current Favicon') }}
                        </label>
                        <div class="flex items-center space-x-4">
                            <img src="{{ $favicon }}" alt="Current Favicon"
                                class="w-20 h-20 rounded border border-gray-300 dark:border-gray-600">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('This is the currently active favicon.') }}
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Form Actions --}}
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 rounded-lg font-medium 
                                   transition duration-150 ease-in-out
                                   text-white bg-blue-600 hover:bg-blue-700 
                                   dark:bg-blue-500 dark:hover:bg-blue-600 
                                   focus:outline-none focus:ring-4 focus:ring-blue-300 
                                   dark:focus:ring-blue-800 cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ __('Update Favicon') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </v-slot:main>

    @push('js')
        <script nonce="{{ $nonce }}">
            $(document).ready(function() {
                // Show selected file name
                $('#favicon').on('change', function() {
                    var fileName = $(this).val().split('\\').pop();
                    if (fileName) {
                        $('#fileName').text(fileName).removeClass('text-gray-500').addClass(
                            'text-blue-600 dark:text-blue-400');
                    } else {
                        $('#fileName').text('{{ __('No file chosen') }}').removeClass(
                            'text-blue-600 dark:text-blue-400').addClass('text-gray-500');
                    }
                });

                // Form validation
                $('#faviconForm').on('submit', function(e) {
                    if (!$('#favicon').val()) {
                        e.preventDefault();
                        alert('{{ __('Please select a favicon file to upload.') }}');
                        return false;
                    }

                    if (!confirm('{{ __('Are you sure you want to update the favicon?') }}')) {
                        e.preventDefault();
                        return false;
                    }
                });
            });
        </script>
    @endpush
</x-admin-layout>
