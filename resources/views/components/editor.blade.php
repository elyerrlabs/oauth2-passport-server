<!-- resources/views/components/simple-tabs.blade.php -->
<div class="w-full">
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
    <div id="editor-tab" class="tab-content" @if (!$jodit) style="display: none;" @endif>
        <textarea id="editor" class="min-h-[500px]" name="{{ $name }}">{!! $content !!}</textarea>
    </div>

    <!-- HTML Tab -->
    <div id="html-tab" class="tab-content hidden">
        <!-- Monaco Toolbar -->
        <div class="mb-2 flex flex-wrap items-center gap-2">
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

        <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden" id="monaco-container">
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

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            let monacoEditor = null;
            let joditEditor = null;
            let isMonacoInitialized = false;
            let isFullscreen = false;

            const config = {
                jodit: {{ $jodit ? 'true' : 'false' }},
                monaco: {{ $monaco ? 'true' : 'false' }},
                preview: {{ $preview ? 'true' : 'false' }},
                lang: '{{ $lang ?? 'html' }}'
            };

            const tabs = document.querySelectorAll(".tab-btn");
            const tabContents = document.querySelectorAll(".tab-content");

            // Determinar tab activo inicial
            let initialTab = 'editor';
            if (!config.jodit && config.monaco) {
                initialTab = 'html';
            }

            // Función para cambiar de tab
            function switchTab(tabId) {
                tabContents.forEach(tc => tc.classList.add("hidden"));
                document.getElementById(tabId + "-tab").classList.remove("hidden");
                tabs.forEach(t => t.classList.remove("border-b-2", "border-blue-500",
                    "text-blue-600", "bg-blue-50", "dark:border-blue-400", "dark:text-blue-400",
                    "dark:bg-blue-900/30"));

                const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
                if (activeTab) {
                    activeTab.classList.add("border-b-2", "border-blue-500", "text-blue-600", "bg-blue-50",
                        "dark:border-blue-400", "dark:text-blue-400", "dark:bg-blue-900/30");
                }

                if (tabId === 'html' && !isMonacoInitialized) initializeMonacoEditor();
                if (tabId === 'preview') updatePreview();
            }

            // Inicializar tabs
            tabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    switchTab(this.dataset.tab);
                });
            });

            // Activar tab inicial
            const initialTabBtn = document.querySelector(`[data-tab="${initialTab}"]`);
            if (initialTabBtn) {
                initialTabBtn.click();
            } else {
                document.querySelector('[data-tab="editor"]')?.click();
            }

            // Jodit Editor
            if (config.jodit) {
                joditEditor = createJoditEditor(document.getElementById("editor"), {
                    theme: document.documentElement.classList.contains("dark") ? "dark" : "default",
                    toolbarAdaptive: true,
                    minHeight: 400,
                    events: {
                        afterInit() {
                            document.getElementById('preview').innerHTML = this.value;
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
                    document.getElementById('preview').innerHTML = val;
                });
            }

            // Monaco Editor
            function initializeMonacoEditor() {
                if (!config.monaco) return;

                const container = document.getElementById("monaco-container");
                const editorDiv = document.getElementById("monaco-editor");

                if (!editorDiv) return;

                // Si ya existe un editor Monaco, lo destruimos
                if (monacoEditor) {
                    monacoEditor.dispose();
                    monacoEditor = null;
                }

                const theme = document.documentElement.classList.contains("dark") ? "vs-dark" : "vs";
                const initialValue = joditEditor ? joditEditor.value : document.getElementById('editor')?.value ||
                    '';

                monacoEditor = createMonacoEditor(editorDiv, {
                    value: initialValue,
                    language: config.lang,
                    theme: theme,
                    automaticLayout: true,
                    minimap: {
                        enabled: true
                    },
                    scrollBeyondLastLine: false,
                    fontSize: 14,
                    lineNumbers: 'on',
                    folding: true,
                    wordWrap: 'on',
                    formatOnType: true,
                    formatOnPaste: true,
                    tabSize: 2,
                    insertSpaces: true
                });

                // Agregar action para pantalla completa
                if (monacoEditor.addAction) {
                    monacoEditor.addAction({
                        id: "editor.action.fullscreen",
                        label: "Toggle Fullscreen",
                        keybindings: [monaco.KeyMod.CtrlCmd | monaco.KeyCode.Enter],
                        run: () => toggleFullscreen()
                    });
                }

                // Sincronizar cambios
                monacoEditor.onDidChangeModelContent(() => {
                    const value = monacoEditor.getValue();
                    if (joditEditor && joditEditor.value !== value) {
                        joditEditor.value = value;
                    }
                    document.getElementById('preview').innerHTML = value;
                });

                isMonacoInitialized = true;

                // Toolbar actions
                document.getElementById('format-html')?.addEventListener('click', () =>
                    monacoEditor.getAction('editor.action.formatDocument').run());
                document.getElementById('copy-html')?.addEventListener('click', copyHTML);
                document.getElementById('undo')?.addEventListener('click', () =>
                    monacoEditor.trigger('keyboard', 'undo', null));
                document.getElementById('redo')?.addEventListener('click', () =>
                    monacoEditor.trigger('keyboard', 'redo', null));
                document.getElementById('toggle-fullscreen')?.addEventListener('click', toggleFullscreen);
                document.getElementById('toggle-wrap')?.addEventListener('click', () => {
                    const current = monacoEditor.getOption(monaco.editor.EditorOption.wordWrap);
                    monacoEditor.updateOptions({
                        wordWrap: current === 'on' ? 'off' : 'on'
                    });
                });
                document.getElementById('toggle-minimap')?.addEventListener('click', () => {
                    const current = monacoEditor.getOption(monaco.editor.EditorOption.minimap).enabled;
                    monacoEditor.updateOptions({
                        minimap: {
                            enabled: !current
                        }
                    });
                });

                // Language selector
                document.getElementById('language-selector')?.addEventListener('change', function() {
                    monaco.editor.setModelLanguage(monacoEditor.getModel(), this.value);
                });
            }

            function toggleFullscreen() {
                const container = document.getElementById('monaco-container');
                if (!container) return;

                isFullscreen = !isFullscreen;

                if (isFullscreen) {
                    container.classList.add('fixed', 'inset-0', 'z-50', '!m-0', '!rounded-none');
                    document.body.style.overflow = 'hidden';

                    // Forzar redimensionamiento
                    setTimeout(() => {
                        if (monacoEditor) monacoEditor.layout();
                    }, 100);
                } else {
                    container.classList.remove('fixed', 'inset-0', 'z-50', '!m-0', '!rounded-none');
                    document.body.style.overflow = '';

                    setTimeout(() => {
                        if (monacoEditor) monacoEditor.layout();
                    }, 100);
                }
            }

            function copyHTML() {
                const htmlContent = joditEditor ? joditEditor.value : document.getElementById('editor')?.value;
                if (htmlContent) {
                    navigator.clipboard.writeText(htmlContent).then(() => {
                        if (window.$notify) {
                            window.$notify.success("{{ __('Copied to clipboard!') }}");
                        } else {
                            alert("{{ __('Copied to clipboard!') }}");
                        }
                    }).catch(err => {
                        if (window.$notify) {
                            window.$notify.error(err);
                        } else {
                            console.error(err);
                        }
                    });
                }
            }

            // Preview
            document.getElementById('refresh-preview')?.addEventListener('click', updatePreview);

            function updatePreview() {
                const content = joditEditor ? joditEditor.value : document.getElementById('editor')?.value;
                document.getElementById('preview').innerHTML = content;
            }

            // Manejar tecla Escape para salir de pantalla completa
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isFullscreen) {
                    toggleFullscreen();
                }
            });

            // Escuchar cambios de tema
            window.addEventListener('theme-change', function() {
                if (monacoEditor) {
                    const newTheme = document.documentElement.classList.contains("dark") ? "vs-dark" : "vs";
                    monaco.editor.setTheme(newTheme);
                }
                if (joditEditor) {
                    joditEditor.destruct();
                    const newTheme = document.documentElement.classList.contains("dark") ? "dark" :
                        "default";
                    joditEditor = createJoditEditor(document.getElementById("editor"), {
                        theme: newTheme,
                        toolbarAdaptive: true,
                        minHeight: 400
                    });
                }
            });

            // Cleanup
            window.addEventListener('beforeunload', () => {
                if (monacoEditor) monacoEditor.dispose();
                if (joditEditor) joditEditor.destruct();
            });
        });
    </script>
@endpush
