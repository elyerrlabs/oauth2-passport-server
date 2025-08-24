@extends('settings.setting')

@section('form')
    <div class="flex flex-col lg:flex-row gap-8 items-start p-6 bg-[var(--color-bg-secondary)] rounded-2xl shadow-sm">
        <!-- Header Section -->
        <div class="w-full lg:w-1/4 sticky top-4">
            <div class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <i class="mdi mdi-folder-cog text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold">{{ __('Filesystem Settings') }}</h2>
                <p class="text-sm opacity-90 mt-2">
                    {{ __('Configure storage disks and file handling') }}
                </p>
            </div>
            
            <div class="mt-4 p-4 bg-[var(--color-bg-primary)] rounded-xl shadow-sm border border-[var(--color-border)]">
                <h3 class="text-sm font-semibold text-[var(--color-text-primary)] flex items-center">
                    <i class="mdi mdi-lightbulb-on-outline mr-2 text-[var(--color-primary)]"></i>
                    {{ __('Storage Tips') }}
                </h3>
                <ul class="mt-2 space-y-2 text-xs text-[var(--color-text-secondary)]">
                    <li class="flex items-start">
                        <i class="mdi mdi-server text-[var(--color-success)] mr-2 mt-0.5"></i>
                        {{ __('Use local storage for development and testing') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-cloud text-[var(--color-info)] mr-2 mt-0.5"></i>
                        {{ __('S3 is recommended for production environments') }}
                    </li>
                    <li class="flex items-start">
                        <i class="mdi mdi-security text-[var(--color-warning)] mr-2 mt-0.5"></i>
                        {{ __('Secure your S3 credentials properly') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="w-full lg:w-3/4 space-y-6">
            <!-- Default Filesystem Configuration -->
            <div class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-star-cog text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Default Filesystem Disk') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                            {{ __('Default Disk') }}
                            <span class="text-[var(--color-text-secondary)]">*</span>
                        </label>
                        <select name="filesystems[default]" id="filesystem-select"
                            class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                            @foreach (['local', 'public', 's3'] as $driver)
                                <option value="{{ $driver }}" {{ config('filesystems.default') == $driver ? 'selected' : '' }}>
                                    {{ ucfirst($driver) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-2 text-sm text-[var(--color-text-secondary)]">
                            <i class="mdi mdi-information-outline mr-1"></i>
                            {{ __('Select the default storage disk for your application') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Disk Configurations -->
            <div class="space-y-6">
                <!-- Local Disk -->
                <div id="disk-local" class="disk-settings p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-success-light)] rounded-lg mr-3">
                            <i class="mdi mdi-server text-[var(--color-success)] text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                            {{ __('Local Disk Configuration') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach (['driver', 'root', 'throw'] as $key)
                            <div class="{{ $key === 'root' ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ ucfirst($key) }}
                                    @if($key === 'root')
                                        <span class="text-[var(--color-text-secondary)]">*</span>
                                    @endif
                                </label>
                                @if ($key == 'throw')
                                    <select name="filesystems[disks][local][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                        <option value="0" {{ !config('filesystems.disks.local.throw', false) ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                        <option value="1" {{ config('filesystems.disks.local.throw', false) ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                    </select>
                                @else
                                    <div class="relative">
                                        <input type="text" name="filesystems[disks][local][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('filesystems.disks.local.' . $key, $key == 'root' ? storage_path('app') : 'local') }}"
                                            {{ $key == 'driver' ? 'readonly' : '' }}>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-{{ $key === 'driver' ? 'cog' : 'folder' }} text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                @endif
                                @if($key === 'root')
                                    <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                        {{ __('Absolute path to the local storage directory') }}
                                    </small>
                                @elseif($key === 'throw')
                                    <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                        {{ __('Throw exceptions on write errors') }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Public Disk -->
                <div id="disk-public" class="disk-settings p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-info-light)] rounded-lg mr-3">
                            <i class="mdi mdi-folder-public text-[var(--color-info)] text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                            {{ __('Public Disk Configuration') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach (['driver', 'root', 'url', 'visibility', 'throw'] as $key)
                            <div class="{{ in_array($key, ['root', 'url']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ ucfirst($key) }}
                                    @if(in_array($key, ['root', 'url']))
                                        <span class="text-[var(--color-text-secondary)]">*</span>
                                    @endif
                                </label>
                                @if ($key == 'throw')
                                    <select name="filesystems[disks][public][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                        <option value="0" {{ !config('filesystems.disks.public.throw', false) ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                        <option value="1" {{ config('filesystems.disks.public.throw', false) ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                    </select>
                                @elseif ($key == 'visibility')
                                    <select name="filesystems[disks][public][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                        <option value="public" {{ config('filesystems.disks.public.visibility', 'public') == 'public' ? 'selected' : '' }}>
                                            {{ __('Public') }}
                                        </option>
                                        <option value="private" {{ config('filesystems.disks.public.visibility', 'public') == 'private' ? 'selected' : '' }}>
                                            {{ __('Private') }}
                                        </option>
                                    </select>
                                @else
                                    <div class="relative">
                                        <input type="text" name="filesystems[disks][public][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('filesystems.disks.public.' . $key, '') }}"
                                            {{ $key == 'driver' ? 'readonly' : '' }}
                                            placeholder="{{ $key === 'url' ? 'http://localhost/storage' : '' }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-{{ $key === 'driver' ? 'cog' : ($key === 'root' ? 'folder' : ($key === 'url' ? 'link' : 'eye')) }} text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                @endif
                                @if($key === 'url')
                                    <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                        {{ __('Base URL for public asset links') }}
                                    </small>
                                @elseif($key === 'visibility')
                                    <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                        {{ __('Default file visibility setting') }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- S3 Disk -->
                <div id="disk-s3" class="disk-settings p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-warning-light)] rounded-lg mr-3">
                            <i class="mdi mdi-amazon text-[var(--color-warning)] text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                            {{ __('Amazon S3 Configuration') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach (['driver', 'key', 'secret', 'region', 'bucket', 'url', 'endpoint', 'use_path_style_endpoint', 'throw'] as $key)
                            <div class="{{ in_array($key, ['key', 'secret', 'url', 'endpoint']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-2">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                    @if(in_array($key, ['key', 'secret', 'bucket', 'region']))
                                        <span class="text-[var(--color-text-secondary)]">*</span>
                                    @endif
                                </label>
                                @if (in_array($key, ['throw', 'use_path_style_endpoint']))
                                    <select name="filesystems[disks][s3][{{ $key }}]"
                                        class="w-full px-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300">
                                        <option value="0" {{ !config('filesystems.disks.s3.' . $key, false) ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                        <option value="1" {{ config('filesystems.disks.s3.' . $key, false) ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                    </select>
                                @else
                                    <div class="relative">
                                        <input type="{{ in_array($key, ['key', 'secret']) ? 'password' : 'text' }}"
                                            name="filesystems[disks][s3][{{ $key }}]"
                                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-[var(--color-border)] shadow-sm focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary-light)] transition-colors duration-300"
                                            value="{{ config('filesystems.disks.s3.' . $key, '') }}"
                                            {{ $key == 'driver' ? 'readonly' : '' }}
                                            placeholder="{{ $key === 'region' ? 'us-east-1' : ($key === 'bucket' ? 'your-bucket-name' : ($key === 'endpoint' ? 'https://s3.region.amazonaws.com' : '')) }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="mdi mdi-{{ $key === 'driver' ? 'cog' : ($key === 'key' ? 'key' : ($key === 'secret' ? 'key-variant' : ($key === 'region' ? 'earth' : ($key === 'bucket' ? 'bucket' : ($key === 'url' ? 'link' : ($key === 'endpoint' ? 'web' : 'alert-circle')))))) }} text-[var(--color-text-secondary)]"></i>
                                        </div>
                                    </div>
                                @endif
                                @if($key === 'use_path_style_endpoint')
                                    <small class="block mt-1 text-sm text-[var(--color-text-secondary)]">
                                        {{ __('Use path-style endpoint for legacy S3 compatibility') }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 p-3 bg-[var(--color-bg-secondary)] rounded-lg border border-[var(--color-border)]">
                        <div class="flex items-center">
                            <i class="mdi mdi-information-outline text-[var(--color-info)] mr-2"></i>
                            <span class="text-sm text-[var(--color-text-secondary)]">
                                {{ __('AWS credentials require appropriate IAM permissions for S3 access') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storage Actions -->
            <div class="p-5 bg-[var(--color-bg-primary)] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-[var(--color-border)]">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-10 h-10 bg-[var(--color-primary-light)] rounded-lg mr-3">
                        <i class="mdi mdi-tools text-[var(--color-primary)] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">
                        {{ __('Storage Management') }}
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="button" id="test-connection" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-primary)] text-white rounded-lg shadow-md hover:bg-[var(--color-primary-hover)] transition-colors duration-300">
                        <i class="mdi mdi-connection mr-2"></i>
                        {{ __('Test Storage Connection') }}
                    </button>
                    <button type="button" id="create-symlink" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-info)] text-white rounded-lg shadow-md hover:bg-[var(--color-info-hover)] transition-colors duration-300">
                        <i class="mdi mdi-link mr-2"></i>
                        {{ __('Create Storage Symlink') }}
                    </button>
                    <button type="button" id="clear-storage" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-warning)] text-white rounded-lg shadow-md hover:bg-[var(--color-warning-hover)] transition-colors duration-300">
                        <i class="mdi mdi-broom mr-2"></i>
                        {{ __('Clear Temporary Files') }}
                    </button>
                    <button type="button" id="storage-stats" 
                        class="flex items-center justify-center px-4 py-3 bg-[var(--color-success)] text-white rounded-lg shadow-md hover:bg-[var(--color-success-hover)] transition-colors duration-300">
                        <i class="mdi mdi-chart-bar mr-2"></i>
                        {{ __('View Storage Statistics') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            const selectFilesystem = document.getElementById("filesystem-select");
            const disks = ["local", "public", "s3"];

            function toggleDisks() {
                let selected = selectFilesystem.value;
                disks.forEach(disk => {
                    const diskElement = document.getElementById(`disk-${disk}`);
                    if (diskElement) {
                        diskElement.style.display = (disk === selected) ? "block" : "none";
                    }
                });
            }

            selectFilesystem.addEventListener("change", toggleDisks);
            toggleDisks();

            // Storage management actions
            const storageButtons = ['test-connection', 'create-symlink', 'clear-storage', 'storage-stats'];
            storageButtons.forEach(buttonId => {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.addEventListener('click', function() {
                        const action = this.id.replace('-', '_');
                        handleStorageAction(action);
                    });
                }
            });

            function handleStorageAction(action) {
                const button = document.getElementById(action.replace('_', '-'));
                const originalText = button.innerHTML;
                const actionNames = {
                    'test_connection': 'Storage Connection',
                    'create_symlink': 'Storage Symlink',
                    'clear_storage': 'Temporary Files',
                    'storage_stats': 'Storage Statistics'
                };

                button.innerHTML = '<i class="mdi mdi-loading animate-spin mr-2"></i>Processing...';
                button.disabled = true;

                // Simulate API call (replace with actual implementation)
                setTimeout(() => {
                    const success = Math.random() > 0.2; // 80% success rate for demo
                    
                    if (success) {
                        showToast(`{{ __(":action completed successfully") }}`.replace(':action', actionNames[action]), 'success');
                    } else {
                        showToast(`{{ __("Failed to complete :action") }}`.replace(':action', actionNames[action]), 'error');
                    }

                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1500);
            }

            function showToast(message, type = 'info') {
                // Toast notification implementation
                console.log(`${type}: ${message}`);
            }

            // Toggle password visibility for S3 credentials
            const s3SecretInput = document.querySelector('input[name="filesystems[disks][s3][secret]"]');
            if (s3SecretInput) {
                const toggleVisibility = document.createElement('button');
                toggleVisibility.type = 'button';
                toggleVisibility.className = 'absolute inset-y-0 right-0 pr-3 flex items-center';
                toggleVisibility.innerHTML = '<i class="mdi mdi-eye-outline text-[var(--color-text-secondary)]"></i>';
                toggleVisibility.addEventListener('click', function() {
                    const type = s3SecretInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    s3SecretInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? 
                        '<i class="mdi mdi-eye-outline text-[var(--color-text-secondary)]"></i>' : 
                        '<i class="mdi mdi-eye-off-outline text-[var(--color-text-secondary)]"></i>';
                });
                
                s3SecretInput.parentNode.appendChild(toggleVisibility);
            }
        });
    </script>
@endpush