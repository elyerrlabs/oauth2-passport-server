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
    <div
        class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen py-3 px-4"
    >
        <div id="app" class="max-w-7xl mx-auto">
            <header class="text-center mb-10">
                <h1
                    class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-2"
                >
                    {{ __("File Gallery") }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    {{ __("Browse your images and documents") }}
                </p>
            </header>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-2">
                <!-- Gallery Controls -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4"
                >
                    <div class="flex-1">
                        <div class="relative max-w-md">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search files..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            />
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <i
                                    class="fas fa-search text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                                >{{ __("View") }}:</span
                            >
                            <button
                                @click="viewMode = 'grid'"
                                :class="{
                                    'text-blue-600 dark:text-blue-400':
                                        viewMode === 'grid',
                                    'text-gray-400 dark:text-gray-600':
                                        viewMode !== 'grid',
                                }"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            >
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button
                                @click="viewMode = 'list'"
                                :class="{
                                    'text-blue-600 dark:text-blue-400':
                                        viewMode === 'list',
                                    'text-gray-400 dark:text-gray-600':
                                        viewMode !== 'list',
                                }"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
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
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5"
                    >
                        <div
                            v-for="(file, index) in filteredFiles"
                            :key="index"
                            :class="{
                                'file-unavailable': !isImage(file.mime_type),
                            }"
                            class="grid-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden hover:shadow-lg relative"
                        >
                            <!-- Unavailable Badge -->
                            <div
                                v-if="!isImage(file.mime_type)"
                                class="absolute top-2 left-2 z-10"
                            >
                                <span
                                    class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs font-medium rounded-md"
                                    >{{ __("Unavailable") }}</span
                                >
                            </div>

                            <!-- Delete Button -->
                            <button
                                @click="deleteFile(file)"
                                class="absolute top-2 right-2 z-10 p-2 bg-white dark:bg-gray-600 rounded-full shadow-md text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                                :class="{
                                    'opacity-70': !isImage(file.mime_type),
                                }"
                            >
                                <i class="fas fa-trash"></i>
                            </button>

                            <div
                                class="h-40 overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-gray-600 cursor-pointer"
                                @click="openFile(file)"
                            >
                                <!-- Image Preview -->
                                <img
                                    v-if="isImage(file.mime_type)"
                                    :src="file.url"
                                    :alt="file.name"
                                    class="w-full h-full object-cover"
                                />

                                <!-- Unavailable Image -->
                                <div
                                    v-if="!isImage(file.mime_type)"
                                    class="p-4 text-center"
                                >
                                    <div
                                        class="w-16 h-16 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-2"
                                    >
                                        <i
                                            class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 text-2xl"
                                        ></i>
                                    </div>
                                    <p
                                        class="text-xs text-red-500 dark:text-red-400"
                                    >
                                        {{ __("Image unavailable") }}
                                    </p>
                                </div>

                                <!-- Document Preview -->
                                <div
                                    v-if="!isImage(file.mime_type)"
                                    class="p-4 text-center"
                                >
                                    <div
                                        class="w-16 h-16 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-2"
                                    >
                                        <i
                                            class="fas fa-file text-blue-500 dark:text-blue-400 text-2xl"
                                        ></i>
                                    </div>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 truncate px-2"
                                    >
                                        {{ getFileExtension(file.name) }}
                                    </p>
                                    <p
                                        v-if="!isImage(file.mime_type)"
                                        class="text-xs text-red-500 dark:text-red-400 mt-1"
                                    >
                                        {{ __("Unavailable") }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-3">
                                <p
                                    class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"
                                    :class="{
                                        'text-gray-500 dark:text-gray-400':
                                            !isImage(file.mime_type),
                                    }"
                                >
                                    {{ file.name }}
                                </p>
                                <div class="flex justify-end items-center mt-2">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                        >{{ formatFileSize(file.size) }}</span
                                    >
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
                            :class="{
                                'bg-gray-100 dark:bg-gray-700': !isImage(
                                    file.mime_type
                                ),
                            }"
                            class="flex items-center p-4 border-b border-gray-200 dark:border-gray-600 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors relative"
                        >
                            <!-- Unavailable Badge -->
                            <span
                                v-if="!isImage(file.mime_type)"
                                class="absolute top-2 left-2 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs font-medium rounded-md"
                                >Unavailable</span
                            >

                            <div
                                class="flex-shrink-0 w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mr-4 cursor-pointer"
                                @click="openFile(file)"
                            >
                                <img
                                    v-if="isImage(file.mime_type)"
                                    :src="file.url"
                                    :alt="file.name"
                                    class="w-12 h-12 object-cover rounded-lg"
                                />
                                <div
                                    v-if="!isImage(file.mime_type)"
                                    class="text-red-500 dark:text-red-400"
                                >
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>

                            <div
                                class="flex-1 min-w-0 cursor-pointer"
                                @click="openFile(file)"
                            >
                                <p
                                    class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"
                                    :class="{
                                        'text-gray-500 dark:text-gray-400':
                                            !isImage(file.mime_type),
                                    }"
                                >
                                    {{ file.name }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span
                                    class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-md"
                                    >{{ getFileExtension(file.name) }}</span
                                >
                                <button
                                    @click="deleteFile(file)"
                                    class="p-2 text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16">
                    <div
                        class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-5"
                    >
                        <i
                            class="fas fa-folder-open text-gray-300 dark:text-gray-500 text-3xl"
                        ></i>
                    </div>
                    <h3
                        class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("No files found") }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "Try adjusting your search or filter to find what you're looking for."
                            )
                        }}
                    </p>
                </div>
            </div>

            <!-- File Preview Modal -->
            <transition
                enter-active-class="transition-opacity duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="previewFile"
                    class="fixed inset-0 bg-black/70 bg-opacity-80 flex items-center justify-center z-50 p-4"
                    @click.self="previewFile = null"
                >
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl max-w-7xl w-full max-h-[90vh] overflow-hidden flex flex-col"
                    >
                        <div
                            class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600"
                        >
                            <h3
                                class="text-lg font-medium text-gray-800 dark:text-white truncate"
                            >
                                {{ previewFile.name }}
                            </h3>
                            <div class="flex items-center space-x-3">
                                <button
                                    @click="deleteFile(previewFile)"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>

                                <button
                                    class="rounded-full px-4 py-2 bg-red-400 dark:bg-red-600 text-white cursor-pointer"
                                    @click="previewFile = null"
                                >
                                    X
                                </button>
                            </div>
                        </div>

                        <div
                            class="flex-1 overflow-auto p-6 flex items-center justify-center"
                        >
                            <img
                                v-if="isImage(previewFile.mime_type)"
                                :src="previewFile.url"
                                :alt="previewFile.name"
                                class="max-w-full max-h-full object-contain"
                            />
                            <div v-else class="text-center py-10">
                                <div
                                    v-if="!isImage(previewFile.mime_type)"
                                    class="w-24 h-24 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-5"
                                >
                                    <i
                                        class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 text-3xl"
                                    ></i>
                                </div>
                                <div
                                    v-else
                                    class="w-24 h-24 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-5"
                                >
                                    <i
                                        class="fas fa-file text-blue-500 dark:text-blue-400 text-3xl"
                                    ></i>
                                </div>
                                <p
                                    class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ previewFile.name }}
                                </p>
                                <p
                                    v-if="!previewFile.available"
                                    class="text-red-500 dark:text-red-400 mb-2"
                                >
                                    {{ __("This file is unavailable") }}
                                </p>
                                <p class="text-gray-500 dark:text-gray-400">
                                    {{ formatFileSize(previewFile.size) }} •
                                    {{ getFileExtension(previewFile.name) }}
                                    file
                                </p>
                            </div>
                        </div>

                        <div
                            class="p-4 border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"
                        >
                            <div
                                class="grid grid-cols-2 md:grid-cols-2 gap-4 text-sm"
                            >
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ __("Type") }}
                                    </p>
                                    <p
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ getFileExtension(previewFile.name) }}
                                        file
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ __("Size") }}
                                    </p>
                                    <p
                                        class="font-medium text-gray-900 dark:text-white"
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
                enter-active-class="transition-opacity duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showDeleteConfirm"
                    class="fixed inset-0 bg-black/60 bg-opacity-70 flex items-center justify-center z-50 p-4"
                >
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full p-6"
                    >
                        <div class="text-center">
                            <div
                                class="w-16 h-16 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4"
                            >
                                <i
                                    class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 text-2xl"
                                ></i>
                            </div>
                            <h3
                                class="text-lg font-medium text-gray-800 dark:text-white mb-2"
                            >
                                {{ __("Delete File") }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                {{
                                    __(
                                        "Are you sure you want to delete this file? This action cannot be undone."
                                    )
                                }}
                            </p>

                            <div class="flex justify-center space-x-4">
                                <button
                                    @click="showDeleteConfirm = false"
                                    class="px-5 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg font-medium transition-colors cursor-pointer"
                                >
                                    {{ __("Cancel") }}
                                </button>
                                <button
                                    @click="confirmDelete"
                                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors cursor-pointer"
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
const sortBy = ref("name");
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

    // Apply sorting
    result.sort((a, b) => {
        if (sortBy.value === "name") {
            return a.name.localeCompare(b.name);
        } else if (sortBy.value === "date") {
            return new Date(b.date) - new Date(a.date);
        } else if (sortBy.value === "size") {
            return b.size - a.size;
        } else if (sortBy.value === "mime_type") {
            return a.type.localeCompare(b.type);
        }
        return 0;
    });

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
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
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
        } finally {
            fileToDelete.value = null;
            showDeleteConfirm.value = false;
        }
    }
};
</script>
