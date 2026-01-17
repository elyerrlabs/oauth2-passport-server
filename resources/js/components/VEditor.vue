<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg dark:shadow-gray-900/50 p-2 border border-gray-100 dark:border-gray-700"
    >
        <!-- Label -->
        <label
            class="text-sm md:text-lg lg:text-lg text-gray-600 dark:text-gray-300 font-medium"
        >
            {{ label }}
            <span class="text-red-500" v-if="required">*</span>
        </label>

        <!-- Tabs -->
        <div
            class="flex border-b border-gray-300 dark:border-gray-600 mb-2 space-x-2"
        >
            <button
                v-for="tab in tabs"
                :key="tab.id"
                v-show="tab.show"
                type="button"
                class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400"
                :class="{
                    'border-b-2 border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30':
                        activeTab === tab.id,
                }"
                @click="switchTab(tab.id)"
            >
                <i :class="['fas', tab.icon, 'mr-2']"></i>{{ tab.label }}
            </button>
        </div>

        <!-- Jodit Tab -->
        <div v-show="activeTab === 'editor' && jodit">
            <textarea ref="editorEl" class="min-h-[500px]">
                {{ props.modelValue }}
            </textarea>
        </div>

        <!-- HTML Tab -->
        <div v-show="activeTab === 'html' && monaco">
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="toolbar-btn bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800"
                    @click="copyHTML"
                >
                    <i class="fas fa-copy mr-1"></i>{{ __("Copy") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800"
                    @click="formatHTML"
                >
                    <i class="fas fa-indent mr-1"></i>{{ __("Format") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600"
                    @click="undo"
                >
                    <i class="fas fa-undo mr-1"></i>{{ __("Undo") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600"
                    @click="redo"
                >
                    <i class="fas fa-redo mr-1"></i>{{ __("Redo") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 hover:bg-orange-200 dark:hover:bg-orange-800"
                    @click="toggleFullscreen"
                >
                    <i class="fas fa-expand mr-1"></i>{{ __("Fullscreen") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800"
                    @click="toggleWrap"
                >
                    <i class="fas fa-align-left mr-1"></i>{{ __("Wrap") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800"
                    @click="toggleMinimap"
                >
                    <i class="fas fa-th-large mr-1"></i>{{ __("Minimap") }}
                </button>

                <select
                    v-model="language"
                    class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-xs border border-gray-300 dark:border-gray-600"
                >
                    <option value="html">HTML</option>
                    <option value="text">Text</option>
                    <option value="javascript">JavaScript</option>
                    <option value="css">CSS</option>
                    <option value="json">JSON</option>
                    <option value="php">PHP</option>
                    <option value="python">Python</option>
                </select>
            </div>

            <div
                class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
                :class="{
                    'fixed inset-0 z-50 !m-0 !rounded-none': isFullscreen,
                }"
            >
                <div
                    ref="monacoEl"
                    class="min-h-[500px]"
                    :class="{ 'h-screen': isFullscreen }"
                ></div>

                <!-- Botón para salir de pantalla completa (solo visible en fullscreen) -->
                <button
                    v-if="isFullscreen"
                    @click="toggleFullscreen"
                    class="fixed top-4 right-4 z-60 bg-red-600 hover:bg-red-700 text-white p-3 rounded-full shadow-lg transition-colors duration-200"
                    :title="__('Exit Fullscreen')"
                >
                    <i class="fas fa-compress text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Preview Tab -->
        <div v-show="activeTab === 'preview' && preview">
            <div class="mb-2 flex justify-between items-center">
                <label
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                    >{{ __("Preview") }}</label
                >
                <button
                    type="button"
                    class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 px-2 py-1 rounded transition border border-gray-300 dark:border-gray-600"
                    @click="updatePreview"
                >
                    <i class="fas fa-redo mr-1"></i>{{ __("Refresh") }}
                </button>
            </div>
            <div
                ref="previewEl"
                class="border min-h-[500px] border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800 prose dark:prose-invert max-w-none w-full overflow-auto"
            ></div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, watch } from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    error: {
        type: [Array, String],
        default: [],
    },
    label: {
        type: String,
        default: "Editor",
    },
    required: {
        type: Boolean,
        default: false,
    },
    monaco: {
        type: Boolean,
        default: true,
    },
    jodit: {
        type: Boolean,
        default: true,
    },
    preview: {
        type: Boolean,
        default: true,
    },
    lang: {
        type: String,
        default: "html",
    },
});

const emit = defineEmits(["update:modelValue"]);

const is_dark = ref(null);
const isFullscreen = ref(false);

const tabs = [
    { id: "editor", label: __("Editor"), icon: "fa-edit", show: props.jodit },
    {
        id: "html",
        label: __("HTML Editor"),
        icon: "fa-code",
        show: props.monaco,
    },
    {
        id: "preview",
        label: __("Preview"),
        icon: "fa-eye",
        show: props.preview,
    },
];

const activeTab = ref("editor");
const language = ref("");
const editorEl = ref(null);
const monacoEl = ref(null);
const previewEl = ref(null);

let joditEditor = null;
let monacoEditor = null;
let isMonacoInitialized = false;

onMounted(async () => {
    await initializeEditors();
    window.addEventListener("theme-change", handleThemeChange);
    language.value = props.lang;
});

onBeforeUnmount(() => {
    destroyEditors();
    window.removeEventListener("theme-change", handleThemeChange);
});

const handleThemeChange = async () => {
    await destroyEditors();
    await nextTick();
    await initializeEditors();
};

const destroyEditors = () => {
    if (monacoEditor) {
        monacoEditor.dispose();
        monacoEditor = null;
    }
    if (joditEditor) {
        joditEditor.destruct();
        joditEditor = null;
    }
    isMonacoInitialized = false;
};

const initializeEditors = async () => {
    const theme = localStorage.getItem("theme") ?? "light";
    is_dark.value = theme;

    if (!props.jodit && props.monaco) {
        activeTab.value = "html";
    }

    await initializeJoditEditor(theme);
    if (activeTab.value === "html") {
        await initializeMonacoEditor(theme);
    }
};

// JODIT EDITOR
const initializeJoditEditor = async (theme = "light") => {
    if (!editorEl.value) return;

    const joditTheme = theme === "dark" ? "dark" : "default";

    joditEditor = createJoditEditor(editorEl.value, {
        theme: joditTheme,
        toolbarAdaptive: true,
        minHeight: 400,
        events: {
            afterInit() {
                previewEl.value.innerHTML = props.modelValue;
            },
        },
    });

    // Synchronize Jodit → Monaco + v-model + Preview
    joditEditor.events.on("keyup", () => {
        const val = joditEditor.value;

        if (monacoEditor) {
            const pos = monacoEditor.getPosition();
            monacoEditor.setValue(val);
            monacoEditor.setPosition(pos);
        }

        previewEl.value.innerHTML = val;
        emit("update:modelValue", val);
    });

    await nextTick();
    updatePreview();
};

watch(
    () => props.modelValue,
    (newVal) => {
        if (joditEditor && joditEditor.value !== newVal) {
            joditEditor.value = newVal;
            previewEl.value.innerHTML = newVal;
        }
        if (monacoEditor && monacoEditor.getValue() !== newVal) {
            monacoEditor.setValue(newVal);
        }
    }
);

const switchTab = async (tabId) => {
    activeTab.value = tabId;

    if (tabId === "html" && !isMonacoInitialized) {
        const theme = localStorage.getItem("theme") ?? "light";
        await initializeMonacoEditor(theme);
    }

    if (tabId === "preview") updatePreview();
};

//////////// MONACO EDITOR
const initializeMonacoEditor = async (theme = "light") => {
    if (!monacoEl.value) {
        await nextTick();
        if (!monacoEl.value) return;
    }

    // Si ya existe un editor Monaco, lo destruimos primero
    if (monacoEditor) {
        monacoEditor.dispose();
        monacoEditor = null;
    }

    const monacoTheme = theme === "dark" ? "vs-dark" : "vs";

    try {
        monacoEditor = createMonacoEditor(monacoEl.value, {
            value: props.modelValue,
            language: language.value,
            theme: monacoTheme,
            automaticLayout: true,
            minimap: { enabled: true },
            scrollBeyondLastLine: false,
            fontSize: 14,
            lineNumbers: "on",
            folding: true,
            wordWrap: "on",
            formatOnType: true,
            formatOnPaste: true,
            tabSize: 2,
            insertSpaces: true,
        });

        // Agregar action personalizado para pantalla completa
        monacoEditor.addAction({
            id: "editor.action.fullscreen",
            label: "Toggle Fullscreen",
            keybindings: [monaco.KeyMod.CtrlCmd | monaco.KeyCode.Enter],
            run: () => {
                toggleFullscreen();
            },
        });

        // Sincroniza Monaco → Jodit + v-model + Preview
        monacoEditor.onDidChangeModelContent(() => {
            const value = monacoEditor.getValue();
            if (joditEditor && joditEditor.value !== value) {
                joditEditor.value = value;
            }
            previewEl.value.innerHTML = value;
            emit("update:modelValue", value);
        });

        isMonacoInitialized = true;
        console.log("Monaco editor initialized with theme:", monacoTheme);
    } catch (error) {
        console.error("Error initializing Monaco editor:", error);
    }
};

// Función para alternar pantalla completa
const toggleFullscreen = () => {
    isFullscreen.value = !isFullscreen.value;

    if (isFullscreen.value) {
        // Entrar en pantalla completa
        document.body.style.overflow = "hidden";

        // Forzar redimensionamiento de Monaco
        if (monacoEditor) {
            setTimeout(() => {
                monacoEditor.layout();
            }, 100);
        }
    } else {
        // Salir de pantalla completa
        document.body.style.overflow = "";

        // Forzar redimensionamiento de Monaco
        if (monacoEditor) {
            setTimeout(() => {
                monacoEditor.layout();
            }, 100);
        }
    }
};

// Manejar la tecla Escape para salir de pantalla completa
const handleEscapeKey = (event) => {
    if (event.key === "Escape" && isFullscreen.value) {
        toggleFullscreen();
    }
};

// Agregar event listener para la tecla Escape
watch(isFullscreen, (newValue) => {
    if (newValue) {
        document.addEventListener("keydown", handleEscapeKey);
    } else {
        document.removeEventListener("keydown", handleEscapeKey);
    }
});

watch(language, (val) => {
    if (monacoEditor) {
        monaco.editor.setModelLanguage(monacoEditor.getModel(), val);
    }
});

const formatHTML = () => {
    monacoEditor?.getAction("editor.action.formatDocument").run();
};

const copyHTML = () => {
    const htmlContent = joditEditor?.value || props.modelValue;
    navigator.clipboard
        .writeText(htmlContent)
        .then(() => window.$notify?.success(__("Copied to clipboard!")))
        .catch((err) => window.$notify?.error(err));
};

const undo = () => {
    monacoEditor?.trigger("keyboard", "undo", null);
};

const redo = () => {
    monacoEditor?.trigger("keyboard", "redo", null);
};

const toggleWrap = () => {
    if (!monacoEditor) return;
    const current = monacoEditor.getOption(monaco.editor.EditorOption.wordWrap);
    monacoEditor.updateOptions({ wordWrap: current === "on" ? "off" : "on" });
};

const toggleMinimap = () => {
    if (!monacoEditor) return;
    const current = monacoEditor.getOption(
        monaco.editor.EditorOption.minimap
    ).enabled;
    monacoEditor.updateOptions({ minimap: { enabled: !current } });
};

const updatePreview = () => {
    previewEl.value.innerHTML = joditEditor?.value || props.modelValue;
};
</script>
