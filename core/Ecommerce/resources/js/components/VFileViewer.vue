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
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-3 px-3">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="text-center mb-6">
                <h1
                    class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white mb-1"
                >
                    {{ __("File Gallery") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    {{ __("Browse your images and documents") }}
                </p>
            </header>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
                <!-- Gallery Controls -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3"
                >
                    <!-- Search -->
                    <div class="flex-1 w-full">
                        <div class="relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                :placeholder="__('Search files...')"
                                class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"
                            />
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <i
                                    class="fas fa-search text-gray-400 dark:text-gray-500 text-sm"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <!-- View Toggle -->
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <span
                            class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap"
                        >
                            {{ __("View") }}:
                        </span>
                        <div
                            class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1"
                        >
                            <button
                                @click="viewMode = 'grid'"
                                :class="{
                                    'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm':
                                        viewMode === 'grid',
                                    'text-gray-500 dark:text-gray-400':
                                        viewMode !== 'grid',
                                }"
                                class="px-3 py-1.5 rounded text-sm transition-colors"
                            >
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button
                                @click="viewMode = 'list'"
                                :class="{
                                    'bg-white dark:bg-gray-600 text-blue-600 dark:text-blue-400 shadow-sm':
                                        viewMode === 'list',
                                    'text-gray-500 dark:text-gray-400':
                                        viewMode !== 'list',
                                }"
                                class="px-3 py-1.5 rounded text-sm transition-colors"
                            >
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Gallery Content -->
                <div v-if="filteredFiles.length > 0">
                    <!-- Grid View -->
                    <div
                        v-if="viewMode === 'grid'"
                        class="grid grid-cols-2 xs:grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"
                    >
                        <div
                            v-for="(file, index) in filteredFiles"
                            :key="index"
                            class="bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden hover:shadow-sm transition-shadow relative"
                        >
                            <!-- Unavailable Badge -->
                            <div
                                v-if="!isImage(file.mime_type)"
                                class="absolute top-1 left-1 z-10"
                            >
                                <span
                                    class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs rounded"
                                >
                                    {{ __("File") }}
                                </span>
                            </div>

                            <!-- Delete Button -->
                            <button
                                @click.stop="deleteFile(file)"
                                class="absolute top-1 right-1 z-10 p-1.5 bg-white/90 dark:bg-gray-600/90 rounded text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                            >
                                <i class="fas fa-trash text-xs"></i>
                            </button>

                            <div
                                class="h-32 overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-gray-600 cursor-pointer"
                                @click="openFile(file)"
                            >
                                <!-- Image Preview -->
                                <img
                                    v-if="isImage(file.mime_type)"
                                    :src="file?.links?.show"
                                    :alt="file.name"
                                    class="w-full h-full object-cover"
                                />

                                <!-- File Preview -->
                                <div v-else class="p-3 text-center">
                                    <div
                                        class="w-12 h-12 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-2"
                                    >
                                        <i
                                            class="fas fa-file text-blue-500 dark:text-blue-400 text-lg"
                                        ></i>
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 truncate"
                                    >
                                        {{ getFileExtension(file.name) }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-2">
                                <p
                                    class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate mb-1"
                                >
                                    {{ file.name }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ formatFileSize(file.size) }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-400 dark:text-gray-500"
                                    >
                                        {{ getFileExtension(file.name) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- List View -->
                    <div
                        v-if="viewMode === 'list'"
                        class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden"
                    >
                        <div
                            v-for="(file, index) in filteredFiles"
                            :key="index"
                            class="flex items-center p-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-gray-600 rounded flex items-center justify-center mr-3 cursor-pointer"
                                @click="openFile(file)"
                            >
                                <img
                                    v-if="isImage(file.mime_type)"
                                    :src="file?.links?.show"
                                    :alt="file.name"
                                    class="w-10 h-10 object-cover rounded"
                                />
                                <i
                                    v-else
                                    class="fas fa-file text-gray-500 dark:text-gray-400"
                                ></i>
                            </div>

                            <div
                                class="flex-1 min-w-0 cursor-pointer"
                                @click="openFile(file)"
                            >
                                <p
                                    class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"
                                >
                                    {{ file.name }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ formatFileSize(file.size) }} •
                                    {{ getFileExtension(file.name) }}
                                </p>
                            </div>

                            <button
                                @click.stop="deleteFile(file)"
                                class="p-2 text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                            >
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-8">
                    <div
                        class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3"
                    >
                        <i
                            class="fas fa-folder-open text-gray-300 dark:text-gray-500 text-xl"
                        ></i>
                    </div>
                    <h3
                        class="text-base font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("No files found") }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        {{ __("Try adjusting your search") }}
                    </p>
                </div>
            </div>

            <!-- File Preview Modal -->
            <transition
                enter-active-class="transition-opacity duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="previewFile"
                    class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-3"
                    @click.self="previewFile = null"
                >
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col"
                    >
                        <div
                            class="flex items-center justify-between p-3 border-b border-gray-200 dark:border-gray-600"
                        >
                            <h3
                                class="text-base font-medium text-gray-800 dark:text-white truncate"
                            >
                                {{ previewFile.name }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <button
                                    @click="deleteFile(previewFile)"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                >
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                <button
                                    @click="previewFile = null"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                                >
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div
                            class="flex-1 overflow-auto p-4 flex items-center justify-center"
                        >
                            <img
                                v-if="isImage(previewFile.mime_type)"
                                :src="previewFile?.links?.show"
                                :alt="previewFile.name"
                                class="max-w-full max-h-64 object-contain"
                            />
                            <div v-else class="text-center py-6">
                                <div
                                    class="w-16 h-16 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-3"
                                >
                                    <i
                                        class="fas fa-file text-blue-500 dark:text-blue-400 text-2xl"
                                    ></i>
                                </div>
                                <p
                                    class="text-base font-medium text-gray-700 dark:text-gray-300 mb-1"
                                >
                                    {{ previewFile.name }}
                                </p>
                                <p
                                    class="text-gray-500 dark:text-gray-400 text-sm"
                                >
                                    {{ formatFileSize(previewFile.size) }} •
                                    {{ getFileExtension(previewFile.name) }}
                                    {{ __("file") }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="p-3 border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"
                        >
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 text-xs"
                                    >
                                        {{ __("Type") }}
                                    </p>
                                    <p
                                        class="font-medium text-gray-900 dark:text-white text-sm"
                                    >
                                        {{ getFileExtension(previewFile.name) }}
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 text-xs"
                                    >
                                        {{ __("Size") }}
                                    </p>
                                    <p
                                        class="font-medium text-gray-900 dark:text-white text-sm"
                                    >
                                        {{ formatFileSize(previewFile.size) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Delete Confirmation Modal -->
            <transition
                enter-active-class="transition-opacity duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showDeleteConfirm"
                    class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-3"
                >
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-4"
                    >
                        <div class="text-center">
                            <div
                                class="w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-3"
                            >
                                <i
                                    class="fas fa-exclamation-triangle text-red-500 dark:text-red-400"
                                ></i>
                            </div>
                            <h3
                                class="text-base font-medium text-gray-800 dark:text-white mb-2"
                            >
                                {{ __("Delete File") }}
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-sm mb-4"
                            >
                                {{
                                    __(
                                        "Are you sure you want to delete this file? This action cannot be undone."
                                    )
                                }}
                            </p>

                            <div class="flex justify-center gap-3">
                                <button
                                    @click="showDeleteConfirm = false"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded text-sm font-medium transition-colors"
                                >
                                    {{ __("Cancel") }}
                                </button>
                                <button
                                    @click="confirmDelete"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-medium transition-colors"
                                >
                                    {{ __("Delete") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps({
    modelValue: {
        type: Array,
        default: [],
    },
});

const viewMode = ref("grid");
const searchQuery = ref("");
const previewFile = ref(null);
const showDeleteConfirm = ref(false);
const fileToDelete = ref(null);

const files = computed({
    get: () => props.modelValue,
    set: (val) => emit("update:modelValue", val),
});

const filteredFiles = computed(() => {
    let result = [...files.value];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(
            (file) =>
                file.name.toLowerCase().includes(query) ||
                getFileExtension(file.name).toLowerCase().includes(query)
        );
    }

    // Sort by name by default
    result.sort((a, b) => a.name.localeCompare(b.name));

    return result;
});

const isImage = (fileType) => {
    return fileType && fileType.startsWith("image/");
};

const getFileExtension = (fileName) => {
    return fileName.split(".").pop().toUpperCase();
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + " " + sizes[i];
};

const openFile = (file) => {
    previewFile.value = file;
};

const deleteFile = (file) => {
    fileToDelete.value = file;
    showDeleteConfirm.value = true;
};

const confirmDelete = async () => {
    if (fileToDelete.value) {
        try {
            const res = await $server.delete(fileToDelete.value.links.delete);
            if (res.status == 200) {
                $notify.success(__("File deleted successfully"));
                files.value = files.value.filter(
                    (file) => file !== fileToDelete.value
                );
                if (
                    previewFile.value &&
                    previewFile.value === fileToDelete.value
                ) {
                    previewFile.value = null;
                }
            }
        } catch (e) {
            console.error("Error deleting file:", e);
        } finally {
            fileToDelete.value = null;
            showDeleteConfirm.value = false;
        }
    }
};
</script>

<style scoped>
/* Grid responsive adicional para pantallas muy pequeñas */
@media (max-width: 475px) {
    .xs\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* Ajustes específicos para pantallas extra pequeñas */
@media (max-width: 375px) {
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .h-32 {
        height: 6rem;
    }

    .p-2 {
        padding: 0.4rem;
    }

    .text-xs {
        font-size: 0.65rem;
        line-height: 0.9rem;
    }
}
</style>
