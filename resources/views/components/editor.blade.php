<!-- resources/views/components/editor.blade.php -->
<div>
    <div id="{{ $uid }}" class="w-full">
        <!-- Label -->
        @if ($label)
            <label class="text-sm md:text-lg lg:text-lg text-gray-600 dark:text-gray-300 font-medium mb-2 block">
                {{ $label }}
                @if ($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif

        <!-- Tabs -->
        <div class="flex border-b border-gray-300 dark:border-gray-600 mb-4 space-x-2">
            @if ($jodit)
                <button type="button"
                    class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400"
                    data-tab="editor">
                    <i class="fas fa-edit mr-2"></i>{{ __('Editor') }}
                </button>
            @endif

            @if ($monaco)
                <button type="button"
                    class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400"
                    data-tab="html">
                    <i class="fas fa-code mr-2"></i>{{ __('HTML Editor') }}
                </button>
            @endif

            @if ($preview)
                <button type="button"
                    class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400"
                    data-tab="preview">
                    <i class="fas fa-eye mr-2"></i>{{ __('Preview') }}
                </button>
            @endif
        </div>

        <!-- Editor Tab -->
        <div id="editor-tab" class="tab-content">
            <textarea id="editor" class="min-h-[500px]" name="{{ $name }}">{!! $content !!}</textarea>
        </div>

        <!-- HTML Tab -->
        <div id="html-tab" class="tab-content hidden">
            <!-- Monaco Toolbar -->
            <div class="mb-2 flex flex-wrap items-center gap-2 toolbar-container">
                <button type="button" id="copy-html"
                    class="toolbar-btn bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 rounded px-2 py-1 text-xs transition"
                    title="Copy HTML">
                    <i class="fas fa-copy mr-1"></i>{{ __('Copy') }}
                </button>
                <button type="button" id="format-html"
                    class="toolbar-btn bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800 rounded px-2 py-1 text-xs transition"
                    title="Format Code">
                    <i class="fas fa-indent mr-1"></i>{{ __('Format') }}
                </button>
                <button type="button" id="undo"
                    class="toolbar-btn bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded px-2 py-1 text-xs transition"
                    title="Undo">
                    <i class="fas fa-undo mr-1"></i>{{ __('Undo') }}
                </button>
                <button type="button" id="redo"
                    class="toolbar-btn bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded px-2 py-1 text-xs transition"
                    title="Redo">
                    <i class="fas fa-redo mr-1"></i>{{ __('Redo') }}
                </button>
                <button type="button" id="toggle-fullscreen"
                    class="toolbar-btn bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 hover:bg-orange-200 dark:hover:bg-orange-800 rounded px-2 py-1 text-xs transition"
                    title="Fullscreen">
                    <i class="fas fa-expand mr-1"></i>{{ __('Fullscreen') }}
                </button>
                <button type="button" id="toggle-wrap"
                    class="toolbar-btn bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800 rounded px-2 py-1 text-xs transition"
                    title="Toggle Word Wrap">
                    <i class="fas fa-align-left mr-1"></i>{{ __('Wrap') }}
                </button>
                <button type="button" id="toggle-minimap"
                    class="toolbar-btn bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800 rounded px-2 py-1 text-xs transition"
                    title="Toggle Minimap">
                    <i class="fas fa-th-large mr-1"></i>{{ __('Minimap') }}
                </button>

                <!-- Language selector -->
                <select id="language-selector"
                    class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-xs border border-gray-300 dark:border-gray-600">
                    <option value="html" selected>HTML</option>
                    <option value="text">Text</option>
                    <option value="javascript">JavaScript</option>
                    <option value="css">CSS</option>
                    <option value="json">JSON</option>
                    <option value="php">PHP</option>
                    <option value="python">Python</option>
                </select>
            </div>

            <div class="border border-gray-300 dark:border-gray-600 rounded-lg editor-container" id="monaco-container">
                <div id="monaco-editor" class="min-h-[500px]"></div>
            </div>
        </div>

        <!-- Preview Tab -->
        <div id="preview-tab" class="tab-content hidden">
            <div class="mb-2 flex justify-between items-center">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Preview') }}</label>
                <button type="button" id="refresh-preview"
                    class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 px-2 py-1 rounded transition border border-gray-300 dark:border-gray-600">
                    <i class="fas fa-redo mr-1"></i>{{ __('Refresh') }}
                </button>
            </div>
            <div id="preview"
                class="border min-h-[500px] border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800 prose dark:prose-invert max-w-none w-full overflow-auto">
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $nonce }}">
    document.addEventListener("DOMContentLoaded", function() {

        const root = document.getElementById("{{ $uid }}");

        if (!root) return;

        let monacoEditor = null;
        let joditEditor = null;
        let isMonacoInitialized = false;
        let isFullscreen = false;
        let originalStyles = {};

        const config = {
            jodit: {{ $jodit ? 'true' : 'false' }},
            monaco: {{ $monaco ? 'true' : 'false' }},
            preview: {{ $preview ? 'true' : 'false' }},
            lang: '{{ $lang ?? 'html' }}'
        };

        // Tabs
        const tabs = root.querySelectorAll(".tab-btn");
        const tabContents = root.querySelectorAll(".tab-content");

        const editorEl = root.querySelector("#editor");
        const previewEl = root.querySelector("#preview");
        const monacoContainer = root.querySelector("#monaco-container");
        const monacoEl = root.querySelector("#monaco-editor");
        const toolbar = root.querySelector(".toolbar-container");

        // Toolbar
        const btnFormat = root.querySelector('#format-html');
        const btnCopy = root.querySelector('#copy-html');
        const btnUndo = root.querySelector('#undo');
        const btnRedo = root.querySelector('#redo');
        const btnFullscreen = root.querySelector('#toggle-fullscreen');
        const btnWrap = root.querySelector('#toggle-wrap');
        const btnMinimap = root.querySelector('#toggle-minimap');
        const languageSelector = root.querySelector('#language-selector');
        const refreshPreviewBtn = root.querySelector('#refresh-preview');

        let initialTab = 'editor';
        if (!config.jodit && config.monaco) {
            initialTab = 'html';
        }

        function switchTab(tabId) {
            tabContents.forEach(tc => tc.classList.add("hidden"));
            root.querySelector(`#${tabId}-tab`)?.classList.remove("hidden");

            tabs.forEach(t => t.classList.remove(
                "border-b-2", "border-blue-500", "text-blue-600", "bg-blue-50",
                "dark:border-blue-400", "dark:text-blue-400", "dark:bg-blue-900/30"
            ));

            const activeTab = root.querySelector(`[data-tab="${tabId}"]`);
            activeTab?.classList.add(
                "border-b-2", "border-blue-500", "text-blue-600", "bg-blue-50",
                "dark:border-blue-400", "dark:text-blue-400", "dark:bg-blue-900/30"
            );

            if (tabId === 'html' && !isMonacoInitialized) initializeMonacoEditor();
            if (tabId === 'preview') updatePreview();

            // Re-layout Monaco if needed
            if (tabId === 'html' && monacoEditor) {
                setTimeout(() => monacoEditor.layout(), 100);
            }
        }

        tabs.forEach(tab => {
            tab.addEventListener("click", function() {
                switchTab(this.dataset.tab);
            });
        });

        root.querySelector(`[data-tab="${initialTab}"]`)?.click();

        // ---------------- JODIT ----------------
        if (config.jodit && editorEl) {
            joditEditor = createJoditEditor(editorEl, {
                theme: document.documentElement.classList.contains("dark") ? "dark" : "default",
                toolbarAdaptive: true,
                minHeight: 400,
                events: {
                    afterInit() {
                        if (previewEl) previewEl.innerHTML = this.value;
                    }
                }
            });

            joditEditor.events.on('keyup', function() {
                const val = joditEditor.value;

                if (monacoEditor) {
                    const pos = monacoEditor.getPosition();
                    monacoEditor.setValue(val);
                    monacoEditor.setPosition(pos);
                }

                if (previewEl) previewEl.innerHTML = val;
            });
        }

        // ---------------- FULLSCREEN FUNCTIONS ----------------
        function enterFullscreen() {
            if (!monacoContainer) return;

            // Save original styles
            originalStyles = {
                position: monacoContainer.style.position,
                top: monacoContainer.style.top,
                left: monacoContainer.style.left,
                right: monacoContainer.style.right,
                bottom: monacoContainer.style.bottom,
                width: monacoContainer.style.width,
                height: monacoContainer.style.height,
                zIndex: monacoContainer.style.zIndex,
                borderRadius: monacoContainer.style.borderRadius,
                overflow: document.body.style.overflow,
                toolbarPosition: toolbar.style.position,
                toolbarTop: toolbar.style.top,
                toolbarLeft: toolbar.style.left,
                toolbarRight: toolbar.style.right,
                toolbarZIndex: toolbar.style.zIndex,
                toolbarBackground: toolbar.style.background
            };

            // Apply fullscreen styles to container
            monacoContainer.style.position = 'fixed';
            monacoContainer.style.top = '0';
            monacoContainer.style.left = '0';
            monacoContainer.style.right = '0';
            monacoContainer.style.bottom = '0';
            monacoContainer.style.width = '100vw';
            monacoContainer.style.height = '100vh';
            monacoContainer.style.zIndex = '9998';
            monacoContainer.style.borderRadius = '0';
            monacoContainer.style.display = 'flex';
            monacoContainer.style.flexDirection = 'column';

            // Make toolbar fixed and visible in fullscreen
            if (toolbar) {
                toolbar.style.position = 'fixed';
                toolbar.style.top = '10px';
                toolbar.style.left = '10px';
                toolbar.style.right = '10px';
                toolbar.style.zIndex = '9999';
                toolbar.style.background = document.documentElement.classList.contains("dark") ? '#1f2937' :
                    '#ffffff';
                toolbar.style.padding = '10px';
                toolbar.style.borderRadius = '8px';
                toolbar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                toolbar.style.border = '1px solid ' + (document.documentElement.classList.contains("dark") ?
                    '#374151' : '#e5e7eb');
            }

            // Adjust monaco editor to take remaining space
            if (monacoEl) {
                monacoEl.style.flex = '1';
                monacoEl.style.height = 'calc(100vh - 80px)';
                monacoEl.style.minHeight = 'calc(100vh - 80px)';
            }

            document.body.style.overflow = 'hidden';

            // Add fullscreen class for any additional styling
            monacoContainer.classList.add('fullscreen-mode');

            isFullscreen = true;

            // Update button icon
            const icon = btnFullscreen.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-expand');
                icon.classList.add('fa-compress');
            }
            btnFullscreen.title = 'Exit Fullscreen';

            // Force Monaco to re-layout
            setTimeout(() => {
                if (monacoEditor) {
                    monacoEditor.layout();
                }
            }, 50);
        }

        function exitFullscreen() {
            if (!monacoContainer) return;

            // Restore original styles for container
            monacoContainer.style.position = originalStyles.position || '';
            monacoContainer.style.top = originalStyles.top || '';
            monacoContainer.style.left = originalStyles.left || '';
            monacoContainer.style.right = originalStyles.right || '';
            monacoContainer.style.bottom = originalStyles.bottom || '';
            monacoContainer.style.width = originalStyles.width || '';
            monacoContainer.style.height = originalStyles.height || '';
            monacoContainer.style.zIndex = originalStyles.zIndex || '';
            monacoContainer.style.borderRadius = originalStyles.borderRadius || '0.5rem';
            monacoContainer.style.display = originalStyles.display || '';
            monacoContainer.style.flexDirection = originalStyles.flexDirection || '';

            // Restore toolbar styles
            if (toolbar) {
                toolbar.style.position = originalStyles.toolbarPosition || '';
                toolbar.style.top = originalStyles.toolbarTop || '';
                toolbar.style.left = originalStyles.toolbarLeft || '';
                toolbar.style.right = originalStyles.toolbarRight || '';
                toolbar.style.zIndex = originalStyles.toolbarZIndex || '';
                toolbar.style.background = originalStyles.toolbarBackground || '';
                toolbar.style.padding = '';
                toolbar.style.borderRadius = '';
                toolbar.style.boxShadow = '';
                toolbar.style.border = '';
            }

            // Restore monaco editor styles
            if (monacoEl) {
                monacoEl.style.flex = '';
                monacoEl.style.height = '';
                monacoEl.style.minHeight = '';
            }

            document.body.style.overflow = originalStyles.overflow || '';

            // Remove fullscreen class
            monacoContainer.classList.remove('fullscreen-mode');

            isFullscreen = false;

            // Update button icon
            const icon = btnFullscreen.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-compress');
                icon.classList.add('fa-expand');
            }
            btnFullscreen.title = 'Fullscreen';

            // Force Monaco to re-layout
            setTimeout(() => {
                if (monacoEditor) {
                    monacoEditor.layout();
                }
            }, 50);
        }

        function toggleFullscreen() {
            if (isFullscreen) {
                exitFullscreen();
            } else {
                enterFullscreen();
            }
        }

        // ---------------- MONACO ----------------
        function initializeMonacoEditor() {
            if (!config.monaco || !monacoEl) return;

            if (monacoEditor) {
                monacoEditor.dispose();
                monacoEditor = null;
            }

            const theme = document.documentElement.classList.contains("dark") ? "vs-dark" : "vs";
            const initialValue = joditEditor ? joditEditor.value : editorEl?.value || '';

            monacoEditor = createMonacoEditor(monacoEl, {
                value: initialValue,
                language: config.lang,
                theme,
                automaticLayout: true,
                minimap: {
                    enabled: true
                },
                wordWrap: 'on'
            });

            monacoEditor.onDidChangeModelContent(() => {
                const value = monacoEditor.getValue();

                if (joditEditor && joditEditor.value !== value) {
                    joditEditor.value = value;
                } else if (editorEl) {
                    editorEl.value = value;
                }

                if (previewEl) previewEl.innerHTML = value;
            });

            isMonacoInitialized = true;

            // Toolbar
            btnFormat?.addEventListener('click', () =>
                monacoEditor.getAction('editor.action.formatDocument').run());

            btnCopy?.addEventListener('click', copyHTML);

            btnUndo?.addEventListener('click', () =>
                monacoEditor.trigger('keyboard', 'undo', null));

            btnRedo?.addEventListener('click', () =>
                monacoEditor.trigger('keyboard', 'redo', null));

            btnFullscreen?.addEventListener('click', toggleFullscreen);

            btnWrap?.addEventListener('click', () => {
                const current = monacoEditor.getOption(monaco.editor.EditorOption.wordWrap);
                monacoEditor.updateOptions({
                    wordWrap: current === 'on' ? 'off' : 'on'
                });
            });

            btnMinimap?.addEventListener('click', () => {
                const current = monacoEditor.getOption(monaco.editor.EditorOption.minimap).enabled;
                monacoEditor.updateOptions({
                    minimap: {
                        enabled: !current
                    }
                });
            });

            languageSelector?.addEventListener('change', function() {
                monaco.editor.setModelLanguage(monacoEditor.getModel(), this.value);
            });

            // Listen for escape key to exit fullscreen
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isFullscreen) {
                    exitFullscreen();
                }
            });
        }

        function copyHTML() {
            const htmlContent = joditEditor ? joditEditor.value : editorEl?.value;

            if (!htmlContent) return;

            navigator.clipboard.writeText(htmlContent).then(() => {
                // Optional: Show temporary success message
                const originalText = btnCopy.innerHTML;
                btnCopy.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                setTimeout(() => {
                    btnCopy.innerHTML = originalText;
                }, 2000);
            });
        }

        function updatePreview() {
            const content = joditEditor ? joditEditor.value : editorEl?.value;
            if (previewEl) previewEl.innerHTML = content;
        }

        refreshPreviewBtn?.addEventListener('click', updatePreview);

        // Handle window resize to ensure Monaco adjusts properly
        window.addEventListener('resize', function() {
            if (monacoEditor) {
                setTimeout(() => monacoEditor.layout(), 100);
            }
        });

    });
</script>

<style nonce="{{ $nonce }}">
    /* Fullscreen styles */
    .editor-container.fullscreen-mode {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9998 !important;
        border-radius: 0 !important;
        margin: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .editor-container.fullscreen-mode #monaco-editor {
        flex: 1 !important;
        height: calc(100vh - 80px) !important;
        min-height: calc(100vh - 80px) !important;
    }

    /* Ensure Monaco editor takes full height in fullscreen */
    .editor-container {
        position: relative;
        transition: all 0.3s ease;
    }

    #monaco-editor {
        transition: height 0.3s ease;
    }

    /* Toolbar styles in fullscreen */
    .toolbar-container {
        transition: all 0.3s ease;
    }

    /* Improve button visibility */
    .toolbar-btn {
        cursor: pointer;
        user-select: none;
    }

    .toolbar-btn:active {
        transform: scale(0.95);
    }
</style>
