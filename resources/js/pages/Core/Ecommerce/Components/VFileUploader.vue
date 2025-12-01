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
        class="bg-linear-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800 min-h-screen p-4"
    >
        <div class="max-w-7xl mx-auto">
            <!-- Header compacto -->
            <header class="text-center mb-6">
                <h1
                    class="text-2xl font-bold text-gray-800 dark:text-white mb-2"
                >
                    {{ __("Upload Files") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    {{ __("Drag & drop or select files to upload") }}
                </p>
            </header>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <!-- Upload Area Compact -->
                <div
                    @dragover.prevent="dragOver = true"
                    @dragleave="dragOver = false"
                    @drop.prevent="handleDrop"
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer transition-all duration-300 mb-6"
                    :class="{
                        'bg-blue-50 dark:bg-blue-900/20 border-blue-400 dark:border-blue-500 scale-105 shadow-md':
                            dragOver,
                        'hover:border-blue-300 dark:hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50':
                            !dragOver,
                    }"
                    @click="selectFiles"
                >
                    <div
                        class="flex items-center justify-center space-x-4 py-2"
                    >
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0"
                        >
                            <i
                                class="fas fa-cloud-upload-alt text-blue-500 dark:text-blue-400 text-lg"
                            ></i>
                        </div>
                        <div class="text-left flex-1">
                            <p
                                class="text-gray-700 dark:text-gray-300 font-medium text-sm"
                            >
                                {{ __("Drop files here or click to browse") }}
                            </p>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs mt-1"
                            >
                                {{
                                    __(
                                        "Supports Images, PDF, MP4, AVI, MOV, MPEG"
                                    )
                                }}
                            </p>
                        </div>
                        <button
                            class="px-4 py-2 bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors flex-shrink-0"
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
                <div v-if="files.length > 0" class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2
                            class="text-lg font-semibold text-gray-800 dark:text-white flex items-center"
                        >
                            <i
                                class="fas fa-files text-blue-500 dark:text-blue-400 mr-2"
                            ></i>
                            {{ __("Selected Files") }}
                            <span
                                class="ml-2 text-blue-500 dark:text-blue-400 text-sm font-normal bg-blue-100 dark:bg-blue-900/30 px-2 py-1 rounded-full"
                            >
                                {{ files.length }}
                            </span>
                        </h2>

                        <div class="flex items-center space-x-3">
                            <span
                                class="text-gray-600 dark:text-gray-400 text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full"
                            >
                                <i
                                    class="fas fa-hdd text-gray-400 dark:text-gray-500 mr-1"
                                ></i>
                                {{ totalFilesSize }}
                            </span>
                            <button
                                @click="clearAll"
                                class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg text-sm transition-colors"
                            >
                                {{ __("Clear All") }}
                            </button>
                        </div>
                    </div>

                    <!-- Responsive Grid -->
                    <div
                        class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3"
                    >
                        <div
                            v-for="(file, index) in files"
                            :key="index"
                            class="relative group bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 overflow-hidden transition-all duration-300 hover:shadow-md hover:scale-102"
                            :class="[
                                errors[index]
                                    ? 'border-2 border-red-500 dark:border-red-400'
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
                                    class="w-full h-full bg-purple-50 dark:bg-purple-900/20 flex flex-col items-center justify-center p-4 relative"
                                >
                                    <i
                                        class="fas fa-film text-purple-500 dark:text-purple-400 text-2xl mb-2"
                                    ></i>
                                    <span
                                        class="text-purple-600 dark:text-purple-300 text-xs font-medium text-center"
                                    >
                                        {{ __("Video") }}
                                    </span>
                                    <div
                                        class="absolute bottom-2 left-2 right-2"
                                    >
                                        <div
                                            class="w-full bg-purple-200 dark:bg-purple-700 rounded-full h-1"
                                        >
                                            <div
                                                class="bg-purple-500 dark:bg-purple-400 h-1 rounded-full w-1/3"
                                            ></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PDF Preview -->
                                <div
                                    v-else-if="isPDF(file.type)"
                                    class="w-full h-full bg-red-50 dark:bg-red-900/20 flex flex-col items-center justify-center p-4"
                                >
                                    <i
                                        class="fas fa-file-pdf text-red-500 dark:text-red-400 text-3xl mb-2"
                                    ></i>
                                    <span
                                        class="text-red-600 dark:text-red-300 text-xs font-medium text-center"
                                    >
                                        {{ __("PDF Document") }}
                                    </span>
                                </div>

                                <!-- Default File Preview -->
                                <div
                                    v-else
                                    class="w-full h-full bg-gray-100 dark:bg-gray-600 flex flex-col items-center justify-center p-4"
                                >
                                    <i
                                        class="fas fa-file text-gray-500 dark:text-gray-400 text-3xl mb-2"
                                    ></i>
                                    <span
                                        class="text-gray-600 dark:text-gray-300 text-xs font-medium text-center"
                                    >
                                        {{ __("Document") }}
                                    </span>
                                </div>

                                <!-- Hover Overlay with Actions -->
                                <div
                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-1"
                                >
                                    <button
                                        v-if="isImage(file.type)"
                                        @click.stop="
                                            viewFile(file.preview, 'image')
                                        "
                                        class="p-1.5 bg-blue-500 hover:bg-blue-600 rounded text-white transition-colors"
                                        :title="__('View Image')"
                                    >
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button
                                        v-else-if="isVideo(file.type)"
                                        @click.stop="
                                            viewFile(file.preview, 'video')
                                        "
                                        class="p-1.5 bg-blue-500 hover:bg-blue-600 rounded text-white transition-colors"
                                        :title="__('Play Video')"
                                    >
                                        <i class="fas fa-play text-xs"></i>
                                    </button>
                                    <button
                                        v-else-if="isPDF(file.type)"
                                        @click.stop="
                                            viewFile(file.preview, 'pdf')
                                        "
                                        class="p-1.5 bg-blue-500 hover:bg-blue-600 rounded text-white transition-colors"
                                        :title="__('View PDF')"
                                    >
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button
                                        @click.stop="removeFile(index)"
                                        class="p-1.5 bg-red-500 hover:bg-red-600 rounded text-white transition-colors"
                                        :title="__('Remove File')"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>

                                <!-- File Type Badge -->
                                <div class="absolute top-1 left-1">
                                    <span
                                        class="px-1.5 py-0.5 bg-black/70 text-white text-xs rounded"
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
                                    class="absolute top-1 right-1"
                                >
                                    <span
                                        class="px-1.5 py-0.5 bg-black/70 text-white text-xs rounded"
                                    >
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ file.duration || "0:00" }}
                                    </span>
                                </div>
                            </div>

                            <!-- File Info -->
                            <div class="p-2">
                                <p
                                    class="text-xs font-medium text-gray-800 dark:text-white truncate mb-1"
                                    :title="file.name"
                                >
                                    {{ truncateName(file.name, 18) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ formatFileSize(file.size) }}
                                    </p>
                                    <i
                                        class="fas fa-check-circle text-green-500 text-xs"
                                        v-if="!errors[index]"
                                        :title="__('Valid file')"
                                    ></i>
                                    <i
                                        class="fas fa-exclamation-triangle text-red-500 text-xs"
                                        v-if="errors[index]"
                                        :title="__('File error')"
                                    ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State Compact -->
                <div v-else class="text-center py-8">
                    <i
                        class="fas fa-file text-gray-300 dark:text-gray-600 text-3xl mb-3"
                    ></i>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        {{ __("No files selected") }}
                    </p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">
                        {{ __("Your uploaded files will appear here") }}
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
                    class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4"
                    @click="previewFile = null"
                >
                    <div class="relative max-w-4xl max-h-full w-full">
                        <img
                            v-if="previewType === 'image'"
                            :src="previewFile"
                            class="max-w-full max-h-96 object-contain rounded-lg"
                            alt="Preview"
                        />
                        <video
                            v-else-if="previewType === 'video'"
                            :src="previewFile"
                            controls
                            class="max-w-full max-h-96 rounded-lg bg-black"
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
                            class="w-full h-96 rounded-lg bg-white"
                            frameborder="0"
                        ></iframe>
                        <button
                            @click="previewFile = null"
                            class="absolute -top-10 right-0 text-white text-xl hover:text-gray-300 transition-colors"
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

// Formatos de video soportados
const supportedVideoFormats = [
    "video/mp4",
    "video/avi",
    "video/quicktime",
    "video/mpeg",
    "video/x-msvideo",
    "video/x-m4v",
];

// Formatos de archivo soportados
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
        // Validar tipos de archivo permitidos
        if (!isSupportedFileType(file.type)) {
            alert(
                `File type ${file.type} is not supported. Supported types: images, PDF, and videos (MP4, AVI, MOV, MPEG).`
            );
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

        // Si es video, intentar obtener duración
        if (isVideo(file.type)) {
            await getVideoDuration(file)
                .then((duration) => {
                    fileData.duration = formatDuration(duration);
                })
                .catch(() => {
                    fileData.duration = "0:00";
                });
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

const isImage = (type) => {
    return type && type.startsWith("image/");
};

const isVideo = (type) => {
    return type && type.startsWith("video/");
};

const isPDF = (type) => {
    return type === "application/pdf";
};

const isSupportedFileType = (type) => {
    return supportedFormats.some(
        (format) =>
            type.startsWith(format) ||
            format.startsWith(type) ||
            supportedFormats.includes(type)
    );
};

const truncateName = (name, length = 15) => {
    if (name.length > length) {
        return name.substring(0, length - 3) + "...";
    }
    return name;
};

const getFileExtension = (filename) => {
    return filename.split(".").pop().toUpperCase();
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const totalFilesSize = computed(() => {
    const totalBytes = files.value.reduce(
        (total, file) => total + file.size,
        0
    );
    return formatFileSize(totalBytes);
});

// Función para emitir la actualización del v-model
const emitUpdate = () => {
    const fileObjects = files.value.map((file) => file.file);
    emit("update:modelValue", fileObjects);
};
</script>

<style scoped>
/* Responsive breakpoints for the grid */
@media (max-width: 475px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Smooth scaling */
.hover\:scale-102:hover {
    transform: scale(1.02);
}
</style>
