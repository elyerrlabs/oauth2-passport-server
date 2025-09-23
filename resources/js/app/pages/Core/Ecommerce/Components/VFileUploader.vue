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
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen p-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header compacto -->
            <header class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ __("Upload Files") }}
                </h1>
                <p class="text-gray-600 text-sm">
                    {{ __("Drag & drop or select images to upload") }}
                </p>
            </header>

            <div class="bg-white rounded-xl shadow-lg p-4">
                <div
                    @dragover.prevent="dragOver = true"
                    @dragleave="dragOver = false"
                    @drop.prevent="handleDrop"
                    :class="{ 'drag-over': dragOver }"
                    class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer transition-colors mb-6"
                    @click="selectFiles"
                >
                    <div class="py-4">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3"
                        >
                            <i
                                class="fas fa-cloud-upload-alt text-blue-500 text-xl"
                            ></i>
                        </div>
                        <p class="text-gray-700 mb-2">
                            {{ __("Drag & drop your images here") }}
                        </p>
                        <button
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors"
                        >
                            {{ __("Browse Files") }}
                        </button>
                        <p class="text-gray-500 text-xs mt-2">
                            {{ __("Supports") }} JPG, WEBP, JPEG, PNG, GIF (max
                            5MB)
                        </p>
                    </div>
                    <input
                        type="file"
                        ref="fileInput"
                        @change="handleFileSelect"
                        multiple
                        accept="image/*"
                        class="hidden"
                    />
                </div>

                <div v-if="images.length > 0" class="mb-6">
                    <h2
                        class="text-lg font-semibold text-gray-800 mb-3 flex items-center"
                    >
                        <i class="fas fa-images text-blue-500 mr-2"></i>
                        {{ __("Selected Images") }} ({{ images.length }})
                    </h2>

                    <div
                        class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3"
                    >
                        <div
                            v-for="(image, index) in images"
                            :key="index"
                            class="relative group rounded-md border border-gray-200 bg-gray-50 overflow-hidden"
                        >
                            <img
                                :src="image.preview"
                                :alt="image.name"
                                class="w-full h-24 object-cover"
                            />

                            <div class="p-1.5">
                                <p
                                    class="text-xs font-medium text-gray-700 truncate"
                                >
                                    {{ truncateName(image.name) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ formatFileSize(image.size) }}
                                </p>
                            </div>

                            <div
                                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1"
                            >
                                <button
                                    @click.stop="viewImage(image.preview)"
                                    class="p-1.5 bg-blue-500 hover:bg-blue-600 rounded text-white text-xs"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    @click.stop="removeImage(index)"
                                    class="p-1.5 bg-red-500 hover:bg-red-600 rounded text-white text-xs"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="images.length > 0"
                    class="flex justify-between items-center"
                >
                    <span class="text-gray-600 text-sm">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                        {{ totalFilesSize }} total
                    </span>
                    <button
                        @click="clearAll"
                        class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm transition-colors"
                    >
                        {{ __("Clear All") }}
                    </button>
                </div>

                <div v-else class="text-center py-8">
                    <i class="fas fa-image text-gray-300 text-3xl mb-2"></i>
                    <p class="text-gray-500 text-sm">
                        {{ __("No images selected yet") }}
                    </p>
                </div>
            </div>

            <transition name="fade">
                <div
                    v-if="previewImage"
                    class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4"
                    @click="previewImage = null"
                >
                    <div class="relative">
                        <img
                            :src="previewImage"
                            class="max-w-full max-h-96 object-contain"
                            alt="Preview"
                        />
                        <button
                            class="absolute -top-10 right-0 text-white text-xl"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, watch } from "vue";

const emit = defineEmits(["update:modelValue"]);
const props = defineProps({
    error: { type: Array, default: [] },
    modelValue: { type: Array, default: [] },
});

const fileInput = ref(null);
const images = ref([]);
const dragOver = ref(false);
const previewImage = ref(null);

// Sincronizar con el valor del v-model
watch(
    () => props.modelValue,
    (newValue) => {
        if (
            JSON.stringify(newValue) !==
            JSON.stringify(images.value.map((img) => img.file))
        ) {
            images.value = newValue.map((file) => ({
                file: file,
                name: file.name,
                size: file.size,
                type: file.type,
                preview: URL.createObjectURL(file),
                progress: 0,
                uploading: false,
            }));
        }
    },
    { immediate: true }
);

const selectFiles = () => {
    fileInput.value.click();
};

const handleFileSelect = (e) => {
    processFiles(e.target.files);
    e.target.value = "";
};

const handleDrop = (e) => {
    dragOver.value = false;
    processFiles(e.dataTransfer.files);
};

const processFiles = (files) => {
    const newFiles = [];

    for (let file of files) {
        if (!file.type.match("image.*")) continue;

        if (file.size > 5 * 1024 * 1024) {
            alert(`File ${file.name} is too large. Max size is 5MB.`);
            continue;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const imageData = {
                file: file,
                name: file.name,
                size: file.size,
                type: file.type,
                preview: e.target.result,
                progress: 0,
                uploading: false,
            };

            images.value.push(imageData);
            newFiles.push(file);

            // Emitir el evento después de procesar todos los archivos
            if (newFiles.length === files.length) {
                emitUpdate();
            }
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = (index) => {
    images.value.splice(index, 1);
    emitUpdate();
};

const clearAll = () => {
    images.value = [];
    emitUpdate();
};

const viewImage = (url) => {
    previewImage.value = url;
};

const truncateName = (name) => {
    if (name.length > 15) {
        return name.substring(0, 12) + "...";
    }
    return name;
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const totalFilesSize = computed(() => {
    const totalBytes = images.value.reduce(
        (total, image) => total + image.size,
        0
    );
    return formatFileSize(totalBytes);
});

// Función para emitir la actualización del v-model
const emitUpdate = () => {
    const files = images.value.map((image) => image.file);
    emit("update:modelValue", files);
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.drag-over {
    background-color: #f0f9ff;
    border-color: #3b82f6;
}
</style>
