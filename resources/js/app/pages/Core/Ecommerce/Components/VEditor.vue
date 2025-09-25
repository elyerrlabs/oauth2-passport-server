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
        <div class="flex border-b border-gray-200 mb-4">
            <button
                class="px-4 py-2 font-medium"
                :class="
                    activeTab === 'editor'
                        ? 'border-b-2 border-blue-500 text-blue-600'
                        : 'text-gray-600'
                "
                @click="activeTab = 'editor'"
            >
                {{ __("Editor") }}
            </button>
            <button
                class="px-4 py-2 font-medium"
                :class="
                    activeTab === 'html'
                        ? 'border-b-2 border-blue-500 text-blue-600'
                        : 'text-gray-600'
                "
                @click="activeTab = 'html'"
            >
                HTML
            </button>
            <button
                class="px-4 py-2 font-medium"
                :class="
                    activeTab === 'preview'
                        ? 'border-b-2 border-blue-500 text-blue-600'
                        : 'text-gray-600'
                "
                @click="activeTab = 'preview'"
            >
                {{ __("Preview") }}
            </button>
        </div>

        <!-- Editor Tab -->
        <div v-show="activeTab === 'editor'">
            <div :id="editorId" class="min-h-[300px]"></div>
        </div>

        <!-- HTML Tab -->
        <div v-show="activeTab === 'html'">
            <textarea
                v-model="htmlContent"
                class="w-full p-4 border border-gray-200 rounded-lg font-mono min-h-[300px] resize-vertical"
            ></textarea>
        </div>

        <!-- Preview Tab -->
        <div
            v-show="activeTab === 'preview'"
            class="preview-content ql-editor border border-gray-200 rounded-lg p-4 min-h-[300px]"
            v-html="previewContent"
        ></div>
        <v-error :error="error" />
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import Quill from "quill";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import VError from "./VError.vue";

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

const activeTab = ref("editor");
const htmlContent = ref(props.modelValue);
const previewContent = ref(props.modelValue);
const quill = ref(null);

const randomIdentifier = (length = 8) => {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    return Array.from({ length }, () =>
        chars.charAt(Math.floor(Math.random() * chars.length))
    ).join("");
};

const editorId = `editor-${randomIdentifier(10)}`;

// Inicializar Quill
onMounted(() => {
    quill.value = new Quill(`#${editorId}`, {
        theme: "snow",
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline", "strike"],
                [{ script: "sub" }, { script: "super" }],
                [{ indent: "-1" }, { indent: "+1" }],
                [{ direction: "rtl" }],
                [{ size: ["small", false, "large", "huge"] }],
                [{ color: [] }, { background: [] }],
                [{ font: [] }],
                [{ align: [] }],
                ["blockquote", "code-block"],
                ["link"],
                ["clean"],
            ],
        },
        placeholder: __("Start writing your content here..."),
    });

    quill.value.root.innerHTML = htmlContent.value;

    quill.value.on("text-change", () => {
        htmlContent.value = quill.value.root.innerHTML;
        previewContent.value = quill.value.root.innerHTML;
        emit("update:modelValue", htmlContent.value);
    });
});

watch(htmlContent, (newVal) => {
    if (quill.value && quill.value.root.innerHTML !== newVal) {
        quill.value.root.innerHTML = newVal;
    }
    previewContent.value = newVal;
    emit("update:modelValue", newVal);
});

watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal !== htmlContent.value) {
            htmlContent.value = newVal;
            previewContent.value = newVal;
            if (quill.value) quill.value.root.innerHTML = newVal;
        }
    }
);
</script>
