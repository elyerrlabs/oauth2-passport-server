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
    <div>
        <button
            @click="dialog = true"
            class="flex items-center cursor-pointer px-4 py-2 justify-center text-blue-600 border border-blue-600 rounded hover:bg-blue-50 transition-colors duration-200"
        >
            <i class="mdi mdi-eye-outline text-sm"></i>
            {{ __("view") }}
        </button>

        <!-- Dialog -->
        <div
            v-if="dialog"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80"
        >
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-7xl max-h-[90vh] overflow-hidden"
            >
                <!-- Header -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <div class="flex items-center">
                        <i
                            class="mdi mdi-file-document-outline text-lg mr-2"
                        ></i>
                        <h2 class="text-xl font-semibold">
                            {{ __("Transaction Details") }}
                        </h2>
                        <div class="flex-1"></div>
                        <button
                            @click="dialog = false"
                            class="flex items-center justify-center w-8 h-8 text-white hover:bg-white hover:bg-opacity-20 rounded-full transition-colors duration-200"
                        >
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 overflow-y-auto max-h-[60vh]">
                    <!-- Main transaction information -->
                    <div class="mb-6">
                        <div
                            class="flex items-center text-blue-600 font-medium mb-3"
                        >
                            <i class="mdi mdi-information mr-2"></i>
                            {{ __("Transaction Information") }}
                        </div>

                        <div class="space-y-2">
                            <div
                                v-for="(value, key) in filteredItem"
                                :key="key"
                                class="flex py-2 border-b border-gray-100 last:border-b-0"
                            >
                                <div class="w-1/3 text-gray-600 font-medium">
                                    {{ __(formatKey(key)) }}
                                </div>
                                <div class="w-2/3 font-medium text-gray-800">
                                    {{ formatValue(value) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- JSON Response Section -->
                    <div v-if="item.response" class="mb-4">
                        <button
                            @click="toggleExpansion('response')"
                            class="flex items-center w-full text-left text-blue-600 font-medium py-2 hover:bg-gray-50 rounded-lg px-3 transition-colors duration-200"
                        >
                            <i class="mdi mdi-code-json mr-2"></i>
                            {{ __("JSON Response") }}
                            <i
                                :class="[
                                    'mdi ml-auto transition-transform duration-200',
                                    expandedSections.response
                                        ? 'mdi-chevron-up'
                                        : 'mdi-chevron-down',
                                ]"
                            ></i>
                        </button>

                        <div
                            v-if="expandedSections.response"
                            class="mt-2 bg-gray-50 rounded-lg p-4 border border-gray-200"
                        >
                            <pre
                                class="text-sm text-gray-700 whitespace-pre-wrap overflow-x-auto"
                                >{{ prettyJSON(item.response) }}</pre
                            >
                        </div>
                    </div>

                    <!-- Meta Information Section -->
                    <div v-if="item.meta" class="mb-4">
                        <button
                            @click="toggleExpansion('meta')"
                            class="flex items-center w-full text-left text-blue-600 font-medium py-2 hover:bg-gray-50 rounded-lg px-3 transition-colors duration-200"
                        >
                            <i class="mdi mdi-database-search mr-2"></i>
                            {{ __("Meta Information") }}
                            <i
                                :class="[
                                    'mdi ml-auto transition-transform duration-200',
                                    expandedSections.meta
                                        ? 'mdi-chevron-up'
                                        : 'mdi-chevron-down',
                                ]"
                            ></i>
                        </button>

                        <div
                            v-if="expandedSections.meta"
                            class="mt-2 bg-gray-50 rounded-lg p-4 border border-gray-200"
                        >
                            <pre
                                class="text-sm text-gray-700 whitespace-pre-wrap overflow-x-auto"
                                >{{ prettyJSON(item.meta) }}</pre
                            >
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-6 py-4">
                    <div class="flex justify-end">
                        <button
                            @click="dialog = false"
                            class="flex items-center px-4 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                        >
                            <i class="mdi mdi-close mr-2"></i>
                            {{ __("Close") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            dialog: false,
            expandedSections: {
                response: false,
                meta: false,
            },
        };
    },
    computed: {
        filteredItem() {
            const exclude = ["response", "meta", "links", "id"];
            return Object.keys(this.item)
                .filter((key) => !exclude.includes(key))
                .reduce((acc, key) => {
                    acc[key] = this.item[key];
                    return acc;
                }, {});
        },
    },
    methods: {
        formatKey(key) {
            return key
                .replace(/_/g, " ")
                .replace(/\b\w/g, (c) => c.toUpperCase());
        },
        formatValue(value) {
            if (value === null || value === undefined) return "N/A";
            if (typeof value === "boolean") return value ? __("Yes") : __("No");
            if (typeof value === "object") return JSON.stringify(value);
            return value;
        },
        prettyJSON(jsonData) {
            try {
                return JSON.stringify(
                    typeof jsonData === "string"
                        ? JSON.parse(jsonData)
                        : jsonData,
                    null,
                    2
                );
            } catch (e) {
                return jsonData;
            }
        },
        toggleExpansion(section) {
            this.expandedSections[section] = !this.expandedSections[section];
        },
    },
};
</script>

<style scoped>
/* Estilos adicionales para mejorar la legibilidad */
pre {
    font-family: "Courier New", Monaco, monospace;
    line-height: 1.4;
}

/* Scrollbar personalizado */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
