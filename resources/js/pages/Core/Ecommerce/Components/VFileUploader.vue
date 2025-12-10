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
    <div class="bg-gray-50 dark:bg-gray-900 p-3">
        <div class="max-w-7xl mx-auto">
            <!-- Header compacto -->
            <header class="text-center mb-4">
                <h1
                    class="text-xl font-bold text-gray-800 dark:text-white mb-1"
                >
                    {{ __("Upload Files") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-xs">
                    {{ __("Drag & drop or select files to upload") }}
                </p>
            </header>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <!-- Upload Area Compact -->
                <div
                    @dragover.prevent="dragOver = true"
                    @dragleave="dragOver = false"
                    @drop.prevent="handleDrop"
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-3 text-center cursor-pointer transition-colors mb-4"
                    :class="{
                        'bg-blue-50 dark:bg-blue-900/20 border-blue-400 dark:border-blue-500':
                            dragOver,
                        'hover:border-blue-300 dark:hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50':
                            !dragOver,
                    }"
                    @click="selectFiles"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-3"
                    >
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto sm:mx-0"
                        >
                            <i
                                class="fas fa-cloud-upload-alt text-blue-500 dark:text-blue-400"
                            ></i>
                        </div>
                        <div class="text-center sm:text-left flex-1">
                            <p
                                class="text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm"
                            >
                                {{ __("Drop files here or click to browse") }}
                            </p>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs mt-0.5"
                            >
                                {{ __("Images, PDF, Videos") }}
                            </p>
                        </div>
                        <button
                            class="px-3 py-1.5 bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 text-white rounded text-xs sm:text-sm font-medium transition-colors"
                        >
                            {{ __("Browse") }}
                        </button>
                    </div>
                    <input
                        type="file"
                        ref="fileInput"
                        @change="handleFileSelect"
                        multiple
                        accept="image/*,.pdf,.mp4,.avi,.mov,.mpeg,.mpg"
                        class="hidden"
                    />
                </div>

                <!-- Files Grid -->
                <div v-if="files.length > 0" class="mb-4">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3"
                    >
                        <div class="flex items-center">
                            <h2
                                class="text-base font-semibold text-gray-800 dark:text-white flex items-center"
                            >
                                <i
                                    class="fas fa-files text-blue-500 dark:text-blue-400 mr-2"
                                ></i>
                                {{ __("Selected") }}
                                <span
                                    class="ml-2 text-blue-500 dark:text-blue-400 text-xs bg-blue-100 dark:bg-blue-900/30 px-1.5 rounded"
                                >
                                    {{ files.length }}
                                </span>
                            </h2>
                        </div>

                        <div class="flex items-center gap-2">
                            <span
                                class="text-gray-600 dark:text-gray-400 text-xs bg-gray-100 dark:bg-gray-700 px-2 rounded"
                            >
                                <i
                                    class="fas fa-hdd text-gray-400 dark:text-gray-500 mr-1"
                                ></i>
                                {{ totalFilesSize }}
                            </span>
                            <button
                                @click="clearAll"
                                class="px-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded text-xs transition-colors"
                            >
                                {{ __("Clear All") }}
                            </button>
                        </div>
                    </div>

                    <!-- Responsive Grid -->
                    <div
                        class="grid grid-cols-2 xs:grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2"
                    >
                        <div
                            v-for="(file, index) in files"
                            :key="index"
                            class="relative group bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600 overflow-hidden transition-colors"
                            :class="[
                                errors[index]
                                    ? 'border-red-500 dark:border-red-400'
                                    : 'hover:border-blue-300 dark:hover:border-blue-500',
                            ]"
                        >
                            <!-- File Preview -->
                            <div class="aspect-square relative">
                                <!-- Image Preview -->
                                <img
                                    v-if="isImage(file.type)"
                                    :src="file.preview"
                                    :alt="file.name"
                                    class="w-full h-full object-cover"
                                />

                                <!-- Video Preview -->
                                <div
                                    v-else-if="isVideo(file.type)"
                                    class="w-full h-full bg-purple-50 dark:bg-purple-900/20 flex flex-col items-center justify-center p-2"
                                >
                                    <i
                                        class="fas fa-film text-purple-500 dark:text-purple-400 text-lg mb-1"
                                    ></i>
                                    <span
                                        class="text-purple-600 dark:text-purple-300 text-xs text-center"
                                    >
                                        {{ __("Video") }}
                                    </span>
                                </div>

                                <!-- PDF Preview -->
                                <div
                                    v-else-if="isPDF(file.type)"
                                    class="w-full h-full bg-red-50 dark:bg-red-900/20 flex flex-col items-center justify-center p-2"
                                >
                                    <i
                                        class="fas fa-file-pdf text-red-500 dark:text-red-400 text-xl mb-1"
                                    ></i>
                                    <span
                                        class="text-red-600 dark:text-red-300 text-xs text-center"
                                    >
                                        {{ __("PDF") }}
                                    </span>
                                </div>

                                <!-- Default File Preview -->
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-100 dark:bg-gray-600 flex flex-col items-center justify-center p-2"
                                >
                                    <i
                                        class="fas fa-file text-gray-500 dark:text-gray-400 text-xl mb-1"
                                    ></i>
                                    <span
                                        class="text-gray-600 dark:text-gray-300 text-xs text-center"
                                    >
                                        {{ __("File") }}
                                    </span>
                                </div>

                                <!-- Hover Overlay with Actions -->
                                <div
                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-1"
                                >
                                    <button
                                        v-if="
                                            isImage(file.type) ||
                                            isVideo(file.type) ||
                                            isPDF(file.type)
                                        "
                                        @click.stop="
                                            viewFile(
                                                file.preview,
                                                isImage(file.type)
                                                    ? 'image'
                                                    : isVideo(file.type)
                                                    ? 'video'
                                                    : 'pdf'
                                            )
                                        "
                                        class="p-1 bg-blue-500 hover:bg-blue-600 rounded text-white transition-colors"
                                        :title="__('View')"
                                    >
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button
                                        @click.stop="removeFile(index)"
                                        class="p-1 bg-red-500 hover:bg-red-600 rounded text-white transition-colors"
                                        :title="__('Remove')"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>

                                <!-- File Type Badge -->
                                <div class="absolute top-0.5 left-0.5">
                                    <span
                                        class="px-1 bg-black/70 text-white text-2xs rounded"
                                        :class="{
                                            'bg-blue-600': isImage(file.type),
                                            'bg-purple-600': isVideo(file.type),
                                            'bg-red-600': isPDF(file.type),
                                            'bg-gray-600':
                                                !isImage(file.type) &&
                                                !isVideo(file.type) &&
                                                !isPDF(file.type),
                                        }"
                                    >
                                        {{ getFileExtension(file.name) }}
                                    </span>
                                </div>

                                <!-- Video Duration Badge -->
                                <div
                                    v-if="isVideo(file.type)"
                                    class="absolute top-0.5 right-0.5"
                                >
                                    <span
                                        class="px-1 bg-black/70 text-white text-2xs rounded"
                                    >
                                        {{ file.duration || "0:00" }}
                                    </span>
                                </div>
                            </div>

                            <!-- File Info -->
                            <div class="p-1.5">
                                <p
                                    class="text-2xs xs:text-xs font-medium text-gray-800 dark:text-white truncate mb-0.5"
                                    :title="file.name"
                                >
                                    {{
                                        truncateName(
                                            file.name,
                                            isMobile ? 12 : 15
                                        )
                                    }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <p
                                        class="text-2xs xs:text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ formatFileSize(file.size) }}
                                    </p>
                                    <i
                                        class="fas fa-check-circle text-green-500 text-2xs"
                                        v-if="!errors[index]"
                                        :title="__('Valid')"
                                    ></i>
                                    <i
                                        class="fas fa-exclamation-triangle text-red-500 text-2xs"
                                        v-if="errors[index]"
                                        :title="__('Error')"
                                    ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State Compact -->
                <div v-else class="text-center py-6">
                    <i
                        class="fas fa-file text-gray-300 dark:text-gray-600 text-2xl mb-2"
                    ></i>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">
                        {{ __("No files selected") }}
                    </p>
                    <p class="text-gray-400 dark:text-gray-500 text-2xs mt-0.5">
                        {{ __("Uploaded files will appear here") }}
                    </p>
                </div>
            </div>
            <v-error :error="errors" />

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
                    @click="previewFile = null"
                >
                    <div class="relative max-w-4xl max-h-full w-full">
                        <img
                            v-if="previewType === 'image'"
                            :src="previewFile"
                            class="max-w-full max-h-80 object-contain rounded"
                            alt="Preview"
                        />
                        <video
                            v-else-if="previewType === 'video'"
                            :src="previewFile"
                            controls
                            class="max-w-full max-h-80 rounded bg-black"
                        >
                            {{
                                __(
                                    "Your browser does not support the video tag."
                                )
                            }}
                        </video>
                        <iframe
                            v-else-if="previewType === 'pdf'"
                            :src="previewFile"
                            class="w-full h-80 rounded bg-white"
                            frameborder="0"
                        ></iframe>
                        <button
                            @click="previewFile = null"
                            class="absolute -top-8 right-0 text-white text-lg hover:text-gray-300 transition-colors"
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
import VError from "@/components/VError.vue";
import { ref, computed, onMounted, watch } from "vue";

const emit = defineEmits(["update:modelValue"]);
const props = defineProps({
    error: { type: Array, default: [] },
    modelValue: { type: Array, default: [] },
});

const fileInput = ref(null);
const files = ref([]);
const dragOver = ref(false);
const previewFile = ref(null);
const previewType = ref(null);
const errors = ref([]);
const isMobile = ref(false);

const checkMobile = () => {
    isMobile.value = window.innerWidth < 640;
};

onMounted(() => {
    checkMobile();
    window.addEventListener("resize", checkMobile);
});

const supportedVideoFormats = [
    "video/mp4",
    "video/avi",
    "video/quicktime",
    "video/mpeg",
    "video/x-msvideo",
    "video/x-m4v",
];

const supportedFormats = [
    "image/jpeg",
    "image/jpg",
    "image/png",
    "image/gif",
    "image/webp",
    "image/svg+xml",
    "application/pdf",
    ...supportedVideoFormats,
];

watch(
    () => props.error,
    (value) => {
        errors.value = value;
    }
);

watch(
    () => props.modelValue,
    (newValue) => {
        if (
            JSON.stringify(newValue) !==
            JSON.stringify(files.value.map((file) => file.file))
        ) {
            files.value = newValue.map((file) => ({
                file: file,
                name: file.name,
                size: file.size,
                type: file.type,
                preview:
                    isImage(file.type) || isVideo(file.type)
                        ? URL.createObjectURL(file)
                        : null,
                duration: isVideo(file.type) ? "0:00" : null,
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

const processFiles = async (fileList) => {
    const newFiles = [];

    for (let file of fileList) {
        if (!isSupportedFileType(file.type)) {
            continue;
        }

        const fileData = {
            file: file,
            name: file.name,
            size: file.size,
            type: file.type,
            preview:
                isImage(file.type) || isVideo(file.type)
                    ? URL.createObjectURL(file)
                    : null,
            duration: isVideo(file.type) ? "0:00" : null,
            progress: 0,
            uploading: false,
        };

        if (isVideo(file.type)) {
            try {
                const duration = await getVideoDuration(file);
                fileData.duration = formatDuration(duration);
            } catch {
                fileData.duration = "0:00";
            }
        }

        files.value.push(fileData);
        newFiles.push(file);
    }

    if (newFiles.length > 0) {
        emitUpdate();
    }
};

const getVideoDuration = (file) => {
    return new Promise((resolve, reject) => {
        const video = document.createElement("video");
        video.preload = "metadata";

        video.onloadedmetadata = function () {
            URL.revokeObjectURL(video.src);
            resolve(video.duration);
        };

        video.onerror = function () {
            URL.revokeObjectURL(video.src);
            reject(new Error("Could not load video metadata"));
        };

        video.src = URL.createObjectURL(file);
    });
};

const formatDuration = (seconds) => {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds.toString().padStart(2, "0")}`;
};

const removeFile = (index) => {
    files.value.splice(index, 1);
    errors.value = Object.fromEntries(
        Object.entries(errors.value).filter(([key]) => key != index)
    );
    emitUpdate();
};

const clearAll = () => {
    files.value = [];
    emitUpdate();
};

const viewFile = (url, type) => {
    previewFile.value = url;
    previewType.value = type;
};

const isImage = (type) => type && type.startsWith("image/");
const isVideo = (type) => type && type.startsWith("video/");
const isPDF = (type) => type === "application/pdf";
const isSupportedFileType = (type) =>
    supportedFormats.some(
        (format) =>
            type.startsWith(format) ||
            format.startsWith(type) ||
            supportedFormats.includes(type)
    );

const truncateName = (name, length = 15) =>
    name.length > length ? name.substring(0, length - 3) + "..." : name;
const getFileExtension = (filename) => filename.split(".").pop().toUpperCase();
const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + " " + sizes[i];
};

const totalFilesSize = computed(() => {
    const totalBytes = files.value.reduce(
        (total, file) => total + file.size,
        0
    );
    return formatFileSize(totalBytes);
});

const emitUpdate = () => {
    const fileObjects = files.value.map((file) => file.file);
    emit("update:modelValue", fileObjects);
};
</script>

<style scoped>
.text-2xs {
    font-size: 0.625rem; /* 10px */
    line-height: 0.875rem; /* 14px */
}

@media (max-width: 375px) {
    .xs\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (min-width: 475px) {
    .xs\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 475px) {
    .text-2xs {
        font-size: 0.5rem; /* 8px */
        line-height: 0.75rem; /* 12px */
    }

    .px-1 {
        padding-left: 0.15rem;
        padding-right: 0.15rem;
    }
}
</style>
