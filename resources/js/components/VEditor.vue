<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
--> 
<template>
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
        <!-- Tabs -->
        <label class="text-sm md:text-lg lg:text-lg text-gray-600 font-medium">
            {{ label }}
            <span class="text-red-500" v-if="required">*</span>
        </label>
        <!-- Tabs -->
        <div class="flex border-b border-gray-300 mb-4 space-x-2">
            <button
                v-for="tab in tabs"
                :key="tab.id"
                type="button"
                class="px-6 py-2 tab-btn rounded-t-lg transition-all duration-200 font-medium text-gray-700 hover:text-blue-600"
                :class="{
                    'border-b-2 border-blue-500 text-blue-600 bg-blue-50':
                        activeTab === tab.id,
                }"
                @click="switchTab(tab.id)"
            >
                <i :class="['fas', tab.icon, 'mr-2']"></i>{{ tab.label }}
            </button>
        </div>

        <!-- Jodit Tab -->
        <div v-show="activeTab === 'editor'">
            <textarea ref="editorEl" class="min-h-[500px]">
                {{ props.modelValue }}
            </textarea>
        </div>

        <!-- HTML Tab -->
        <div v-show="activeTab === 'html'">
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="toolbar-btn bg-blue-100 text-blue-700"
                    @click="copyHTML"
                >
                    <i class="fas fa-copy mr-1"></i>{{ __("Copy") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-green-100 text-green-700"
                    @click="formatHTML"
                >
                    <i class="fas fa-indent mr-1"></i>{{ __("Format") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-gray-100 text-gray-700"
                    @click="undo"
                >
                    <i class="fas fa-undo mr-1"></i>{{ __("Undo") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-gray-100 text-gray-700"
                    @click="redo"
                >
                    <i class="fas fa-redo mr-1"></i>{{ __("Redo") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-purple-100 text-purple-700"
                    @click="toggleWrap"
                >
                    <i class="fas fa-align-left mr-1"></i>{{ __("Wrap") }}
                </button>
                <button
                    type="button"
                    class="toolbar-btn bg-purple-100 text-purple-700"
                    @click="toggleMinimap"
                >
                    <i class="fas fa-th-large mr-1"></i>{{ __("Minimap") }}
                </button>

                <select
                    v-model="language"
                    class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs"
                >
                    <option value="html">HTML</option>
                    <option value="javascript">JavaScript</option>
                    <option value="css">CSS</option>
                    <option value="json">JSON</option>
                    <option value="php">PHP</option>
                    <option value="python">Python</option>
                </select>
            </div>

            <div
                class="border border-gray-300 rounded-lg overflow-hidden"
            >
                <div ref="monacoEl" class="min-h-[500px]"></div>
            </div>
        </div>

        <!-- Preview Tab -->
        <div v-show="activeTab === 'preview'">
            <div class="mb-2 flex justify-between items-center">
                <label class="text-sm font-medium text-gray-700">{{
                    __("Preview")
                }}</label>
                <button
                    type="button"
                    class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-2 py-1 rounded transition"
                    @click="updatePreview"
                >
                    <i class="fas fa-redo mr-1"></i>{{ __("Refresh") }}
                </button>
            </div>
            <div
                ref="previewEl"
                class="border min-h-[500px] border-gray-300 rounded-lg p-4 bg-white prose max-w-none w-full overflow-auto"
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
        type: Array,
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
});

const emit = defineEmits(["update:modelValue"]);

const tabs = [
    { id: "editor", label: __("Editor"), icon: "fa-edit" },
    { id: "html", label: __("HTML Editor"), icon: "fa-code" },
    { id: "preview", label: __("Preview"), icon: "fa-eye" },
];

const activeTab = ref("editor");
const language = ref("html");
const editorEl = ref(null);
const monacoEl = ref(null);
const previewEl = ref(null);

let joditEditor = null;
let monacoEditor = null;
let isMonacoInitialized = false;

onMounted(async () => {
    // Inicializa Jodit con el valor inicial
    joditEditor = createJoditEditor(editorEl.value, {
        events: {
            afterInit() {
                previewEl.value.innerHTML = props.modelValue;
            },
        },
    });

    // Sincroniza Jodit → Monaco + v-model + Preview
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
});

// Sincroniza cambios externos (cuando el padre cambia el valor del modelo)
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

function switchTab(tabId) {
    activeTab.value = tabId;
    if (tabId === "html" && !isMonacoInitialized) initializeMonacoEditor();
    if (tabId === "preview") updatePreview();
}

function initializeMonacoEditor() {
    monacoEditor = createMonacoEditor(monacoEl.value, {
        value: props.modelValue,
        language: language.value,
        theme: "vs-dark",
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
}

function formatHTML() {
    monacoEditor?.getAction("editor.action.formatDocument").run();
}

function copyHTML() {
    const htmlContent = joditEditor?.value || props.modelValue;
    navigator.clipboard
        .writeText(htmlContent)
        .then(() => window.$notify?.success(__("Copied to clipboard!")))
        .catch((err) => window.$notify?.error(err));
}

function undo() {
    monacoEditor?.trigger("keyboard", "undo", null);
}

function redo() {
    monacoEditor?.trigger("keyboard", "redo", null);
}

function toggleWrap() {
    const current = monacoEditor.getOption(monaco.editor.EditorOption.wordWrap);
    monacoEditor.updateOptions({ wordWrap: current === "on" ? "off" : "on" });
}

function toggleMinimap() {
    const current = monacoEditor.getOption(
        monaco.editor.EditorOption.minimap
    ).enabled;
    monacoEditor.updateOptions({ minimap: { enabled: !current } });
}

function updatePreview() {
    previewEl.value.innerHTML = joditEditor?.value || props.modelValue;
}

watch(language, (val) => {
    if (monacoEditor)
        monaco.editor.setModelLanguage(monacoEditor.getModel(), val);
});

onBeforeUnmount(() => {
    if (monacoEditor) monacoEditor.dispose();
    if (joditEditor) joditEditor.destruct();
});
</script>
