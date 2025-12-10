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
    <v-seo-layout>
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
            <!-- Header Section -->
            <div
                class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3"
            >
                <div class="flex-1 min-w-0">
                    <h1
                        class="text-xl font-bold text-gray-900 dark:text-white truncate"
                    >
                        {{ __("Sitemap Management") }}
                    </h1>
                    <p
                        class="text-sm text-gray-600 dark:text-gray-400 mt-1 truncate"
                    >
                        {{
                            __(
                                "Manage and optimize your website sitemap for SEO"
                            )
                        }}
                    </p>
                </div>
                <button
                    @click="resetModal = true"
                    class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium whitespace-nowrap"
                >
                    {{ __("Reset Sitemap") }}
                </button>
            </div>

            <!-- Manual URL Addition -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4 sm:p-6"
            >
                <h2
                    class="text-base font-semibold text-gray-900 dark:text-white mb-3"
                >
                    {{ __("Add URL Manually") }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- URL -->
                    <div class="sm:col-span-2">
                        <v-input
                            v-model="form.url"
                            :label="__('URL')"
                            placeholder="https://example.com/page"
                            :required="true"
                            :error="form.errors.url"
                            class="text-sm"
                        />
                    </div>

                    <!-- Image URL -->
                    <div>
                        <v-input
                            v-model="form.image"
                            :label="__('Image URL')"
                            placeholder="https://example.com/image.jpg"
                            :error="form.errors.image"
                            class="text-sm"
                        />
                    </div>

                    <!-- Change Frequency -->
                    <div>
                        <v-select
                            :label="__('Change Frequency')"
                            v-model="form.changefreq"
                            :options="options"
                            :error="form.errors.changefreq"
                            class="text-sm"
                        />
                    </div>

                    <!-- Priority -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Priority") }}
                        </label>
                        <input
                            v-model="form.priority"
                            type="number"
                            min="0.1"
                            max="1"
                            step="0.1"
                            :placeholder="__('Priority (0.1 - 1.0)')"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        />
                        <p
                            class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                        >
                            {{
                                __("Higher numbers indicate greater importance")
                            }}
                        </p>
                        <v-error :error="form.errors.priority" />
                    </div>

                    <!-- Submit Button -->
                    <div class="sm:col-span-2">
                        <button
                            @click="submitUrl"
                            :disabled="form.processing || !form.url"
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 cursor-pointer text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors font-medium flex items-center justify-center gap-2 text-sm"
                        >
                            <svg
                                v-if="form.processing"
                                class="animate-spin h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            {{
                                form.processing
                                    ? __("Adding...")
                                    : __("Add URL")
                            }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Detected Routes List -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4 sm:p-6"
            >
                <div
                    class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-2 mb-4"
                >
                    <h2
                        class="text-base font-semibold text-gray-900 dark:text-white"
                    >
                        {{ __("Detected Routes") }}
                    </h2>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __("Total:") }} {{ data.length }}
                    </span>
                </div>

                <div class="space-y-3">
                    <div
                        v-for="(item, index) in data"
                        :key="index"
                        class="p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    >
                        <div
                            class="flex flex-col xs:flex-row xs:items-start xs:justify-between gap-3"
                        >
                            <div class="flex-1 min-w-0">
                                <!-- URL with number -->
                                <div
                                    class="font-medium text-blue-700 dark:text-blue-400 text-sm mb-2"
                                >
                                    <span
                                        class="text-gray-500 dark:text-gray-400 mr-1"
                                        >{{ index + 1 }}.</span
                                    >
                                    <span class="break-words">{{
                                        item.url
                                    }}</span>
                                </div>

                                <!-- Image Thumbnail -->
                                <div v-if="item.image" class="mt-2">
                                    <img
                                        :src="item.image"
                                        :alt="__('Route image')"
                                        class="w-16 h-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                                        loading="lazy"
                                    />
                                </div>

                                <!-- Additional Info -->
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mt-2 space-y-1"
                                >
                                    <div
                                        v-if="item.changefreq"
                                        class="flex items-center gap-1"
                                    >
                                        <svg
                                            class="w-3 h-3 flex-shrink-0"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        <span class="truncate"
                                            >{{ __("Change Frequency:") }}
                                            {{ item.changefreq }}</span
                                        >
                                    </div>
                                    <div
                                        v-if="item.priority"
                                        class="flex items-center gap-1"
                                    >
                                        <svg
                                            class="w-3 h-3 flex-shrink-0"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />
                                        </svg>
                                        <span
                                            >{{ __("Priority:") }}
                                            {{ item.priority }}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div
                                class="flex flex-row xs:flex-col gap-2 self-start"
                            >
                                <button
                                    v-if="!item.registered"
                                    @click="add(item)"
                                    class="px-3 py-1.5 text-xs cursor-pointer bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center gap-1 whitespace-nowrap"
                                >
                                    <svg
                                        class="w-3 h-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                        />
                                    </svg>
                                    {{ __("Add") }}
                                </button>
                                <button
                                    v-else
                                    @click="deleteRoute(item)"
                                    class="px-3 py-1.5 text-xs cursor-pointer bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center gap-1 whitespace-nowrap"
                                >
                                    <svg
                                        class="w-3 h-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                    {{ __("Delete") }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="data.length === 0" class="text-center py-6">
                        <svg
                            class="w-10 h-10 text-gray-400 dark:text-gray-500 mx-auto mb-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                            />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">
                            {{ __("No routes detected. Add URLs manually.") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Sitemap Modal -->
        <v-modal v-model="resetModal" panel-class="w-full max-w-md mx-2">
            <template #body>
                <div class="text-center p-4 sm:p-6">
                    <!-- Warning Icon -->
                    <div
                        class="mx-auto flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 mb-3"
                    >
                        <svg
                            class="h-5 w-5 text-red-600 dark:text-red-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                            />
                        </svg>
                    </div>

                    <!-- Title -->
                    <h3
                        class="text-base font-semibold text-gray-900 dark:text-white mb-2"
                    >
                        {{ __("Reset Sitemap") }}
                    </h3>

                    <!-- Warning Message -->
                    <div
                        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 mb-4 text-left"
                    >
                        <div class="flex items-start gap-2">
                            <svg
                                class="w-4 h-4 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                                />
                            </svg>
                            <div class="text-red-800 dark:text-red-300 text-xs">
                                <p class="font-medium mb-1">
                                    {{
                                        __(
                                            "Warning: This action cannot be undone"
                                        )
                                    }}
                                </p>
                                <p>
                                    {{
                                        __(
                                            "All sitemap URLs will be permanently deleted."
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Input -->
                    <div class="mb-4">
                        <label
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 text-left"
                        >
                            {{ __('Type "RESET" to confirm:') }}
                        </label>
                        <input
                            v-model="resetConfirmation"
                            type="text"
                            :placeholder="__('RESET')"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        />
                        <p
                            class="mt-1 text-xs text-gray-500 dark:text-gray-400 text-left"
                        >
                            {{
                                __(
                                    "This will remove all URLs from your sitemap."
                                )
                            }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col xs:flex-row xs:justify-end gap-2">
                        <button
                            @click="resetModal = false"
                            class="px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors w-full xs:w-auto"
                        >
                            {{ __("Cancel") }}
                        </button>
                        <button
                            @click="resetSitemap"
                            :disabled="resetConfirmation !== 'RESET'"
                            class="px-3 py-2 text-xs font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 disabled:bg-red-400 disabled:cursor-not-allowed transition-colors w-full xs:w-auto"
                        >
                            {{ __("Reset Sitemap") }}
                        </button>
                    </div>
                </div>
            </template>
        </v-modal>
    </v-seo-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VSeoLayout from "@/components/VGeneralLayout.vue";
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VError from "@/components/VError.vue";

const page = usePage();

const resetModal = ref(false);
const resetConfirmation = ref("");
const data = ref([]);

const form = useForm({
    url: "",
    image: "",
    changefreq: "weekly",
    priority: 0.5,
});

const options = [
    { id: "always", name: __("Always") },
    { id: "hourly", name: __("Hourly") },
    { id: "daily", name: __("Daily") },
    { id: "weekly", name: __("Weekly") },
    { id: "monthly", name: __("Monthly") },
    { id: "yearly", name: __("Yearly") },
    { id: "never", name: __("Never") },
];

onMounted(() => {
    data.value = page.props.data;
});

const submitUrl = () => {
    form.post(page.props.routes.index, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.changefreq = "weekly";
            form.priority = 0.5;
            $notify.success(__("Route added successfully"));
            getRoutes();
        },
    });
};

const add = (item) => {
    form.url = item.url;
    form.image = item.image;
    form.changefreq = item.changefreq;
    form.priority = item.priority;
    submitUrl();
};

const deleteRoute = (item) => {
    const destroy = useForm();
    destroy.delete(item.links.delete, {
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(__("Route deleted successfully"));
            getRoutes();
        },
    });
};

const resetSitemap = () => {
    if (resetConfirmation.value !== "RESET") return;

    const destroy = useForm();
    destroy.delete(page.props.routes.reset, {
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(__("Sitemap reset successfully"));
            resetModal.value = false;
            resetConfirmation.value = "";
            getRoutes();
        },
        onError: () => {
            $notify.error(__("Failed to reset sitemap"));
        },
    });
};

const getRoutes = () => {
    const uri = useForm();
    uri.get(page.props.routes.index, {
        preserveScroll: true,
        onSuccess: (page) => {
            data.value = page.props.data;
        },
    });
};
</script>

<style>
@media (max-width: 475px) {
    .xs\:flex-row {
        flex-direction: row !important;
    }
    .xs\:flex-col {
        flex-direction: column !important;
    }
    .xs\:items-center {
        align-items: center !important;
    }
    .xs\:justify-between {
        justify-content: space-between !important;
    }
    .xs\:justify-end {
        justify-content: flex-end !important;
    }
    .xs\:w-auto {
        width: auto !important;
    }
    .xs\:self-start {
        align-self: flex-start !important;
    }
}

@media (max-width: 640px) {
    .break-words {
        word-break: break-word;
        overflow-wrap: break-word;
    }

    button,
    [role="button"],
    input[type="submit"] {
        min-height: 44px;
        min-width: 44px;
    }
}

* {
    -webkit-tap-highlight-color: transparent;
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

.overflow-y-auto {
    -webkit-overflow-scrolling: touch;
}
</style>
