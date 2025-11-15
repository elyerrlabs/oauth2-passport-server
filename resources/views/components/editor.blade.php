<!-- resources/views/components/simple-tabs.blade.php -->
<div class="w-full">
    <!-- Tabs -->
    <div class="flex border-b border-gray-300 mb-4 space-x-2">
        <button type="button"
            class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 hover:text-blue-600"
            data-tab="editor">
            <i class="fas fa-edit mr-2"></i>{{ __('Editor') }}
        </button>
        <button type="button"
            class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 hover:text-blue-600"
            data-tab="html">
            <i class="fas fa-code mr-2"></i>{{ __('HTML Editor') }}
        </button>
        <button type="button"
            class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 hover:text-blue-600"
            data-tab="preview">
            <i class="fas fa-eye mr-2"></i>{{ __('Preview') }}
        </button>
    </div>

    <!-- Editor Tab -->
    <div id="editor-tab" class="tab-content">
        <textarea id="editor" class="min-h-[500px]" name="{{ $name }}">{!! $content !!}</textarea>
    </div>

    <!-- HTML Tab -->
    <div id="html-tab" class="tab-content hidden">
        <!-- Monaco Toolbar -->
        <div class="mb-2 flex flex-wrap items-center gap-2">
            <button type="button" id="copy-html"
                class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs" title="Copy HTML"><i
                    class="fas fa-copy mr-1"></i>{{ __('Copy') }}</button>
            <button type="button" id="format-html"
                class="px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 text-xs" title="Format Code"><i
                    class="fas fa-indent mr-1"></i>{{ __('Format') }}</button>
            <button type="button" id="undo"
                class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs" title="Undo"><i
                    class="fas fa-undo mr-1"></i>{{ __('Undo') }}</button>
            <button type="button" id="redo"
                class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs" title="Redo"><i
                    class="fas fa-redo mr-1"></i>{{ __('Redo') }}</button>
            <button type="button" id="toggle-wrap"
                class="px-2 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 text-xs"
                title="Toggle Word Wrap"><i class="fas fa-align-left mr-1"></i>{{ __('Wrap') }}</button>
            <button type="button" id="toggle-minimap"
                class="px-2 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 text-xs"
                title="Toggle Minimap"><i class="fas fa-th-large mr-1"></i>{{ __('Minimap') }}</button>

            <!-- Language selector -->
            <select id="language-selector"
                class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs">
                <option value="html" selected>HTML</option>
                <option value="javascript">JavaScript</option>
                <option value="css">CSS</option>
                <option value="json">JSON</option>
                <option value="php">PHP</option>
                <option value="python">Python</option>
            </select>
        </div>
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <div id="monaco-editor" class="min-h-[500px]"></div>
        </div>
    </div>

    <!-- Preview Tab -->
    <div id="preview-tab" class="tab-content hidden">
        <div class="mb-2 flex justify-between items-center">
            <label class="text-sm font-medium text-gray-700">{{ __('Preview') }}</label>
            <button type="button" id="refresh-preview"
                class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-2 py-1 rounded transition">
                <i class="fas fa-redo mr-1"></i>{{ __('Refresh') }}
            </button>
        </div>
        <div id="preview"
            class="border min-h-[500px] border-gray-300 rounded-lg p-4 bg-white prose max-w-none w-full overflow-auto">
        </div>
    </div>
</div>

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            let monacoEditor = null;
            let joditEditor = null;
            let isMonacoInitialized = false;

            const tabs = document.querySelectorAll(".tab-btn");
            const tabContents = document.querySelectorAll(".tab-content");

            tabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    const target = this.dataset.tab;
                    tabContents.forEach(tc => tc.classList.add("hidden"));
                    document.getElementById(target + "-tab").classList.remove("hidden");
                    tabs.forEach(t => t.classList.remove("border-b-2", "border-blue-500",
                        "text-blue-600", "bg-blue-50"));
                    this.classList.add("border-b-2", "border-blue-500", "text-blue-600",
                        "bg-blue-50");

                    if (target === 'html' && !isMonacoInitialized) initializeMonacoEditor();
                    if (target === 'preview') updatePreview();
                });
            });
            document.querySelector('[data-tab="editor"]').click();

            // Jodit Editor
            joditEditor = createJoditEditor(document.getElementById("editor"), {
                theme: document.documentElement.classList.contains("dark") ?
                    "dark" :
                    "default",
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

            // Monaco
            function initializeMonacoEditor() {

                monacoEditor = createMonacoEditor(document.getElementById("monaco-editor"), {
                    value: joditEditor.value,
                    language: 'html',
                    theme: 'vs-dark',
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

                monacoEditor.onDidChangeModelContent(() => {
                    const value = monacoEditor.getValue();
                    joditEditor.value = value;
                    document.getElementById('preview').innerHTML = value;
                });

                isMonacoInitialized = true;

                // Toolbar actions
                document.getElementById('format-html').addEventListener('click', () => monacoEditor.getAction(
                    'editor.action.formatDocument').run());
                document.getElementById('copy-html').addEventListener('click', copyHTML);
                document.getElementById('undo').addEventListener('click', () => monacoEditor.trigger('keyboard',
                    'undo', null));
                document.getElementById('redo').addEventListener('click', () => monacoEditor.trigger('keyboard',
                    'redo', null));
                document.getElementById('toggle-wrap').addEventListener('click', () => {
                    monacoEditor.updateOptions({
                        wordWrap: monacoEditor.getOption(monaco.editor.EditorOption.wordWrap) ===
                            'on' ? 'off' : 'on'
                    });
                });
                document.getElementById('toggle-minimap').addEventListener('click', () => {
                    monacoEditor.updateOptions({
                        minimap: {
                            enabled: !monacoEditor.getOption(monaco.editor.EditorOption.minimap)
                                .enabled
                        }
                    });
                });

                // Language selector
                document.getElementById('language-selector').addEventListener('change', function() {
                    monaco.editor.setModelLanguage(monacoEditor.getModel(), this.value);
                });
            }

            function copyHTML() {
                const htmlContent = joditEditor.value;
                navigator.clipboard.writeText(htmlContent).then(() => $notify.success(
                        "{{ __('Copied to clipboard!') }}"))
                    .catch(err => $notify.error(err));
            }

            // Preview
            document.getElementById('refresh-preview').addEventListener('click', updatePreview);

            function updatePreview() {
                document.getElementById('preview').innerHTML = joditEditor.value;
            }

            // Cleanup
            window.addEventListener('beforeunload', () => {
                if (monacoEditor) monacoEditor.dispose();
                if (joditEditor) joditEditor.destruct();
            });
        });
    </script>
@endpush
