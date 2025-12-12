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
            class="flex items-center cursor-pointer px-4 py-2.5 justify-center text-blue-600 dark:text-blue-400 border border-blue-600 dark:border-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 font-medium text-sm shadow-sm hover:shadow-md"
        >
            <i class="mdi mdi-eye-outline text-sm mr-2"></i>
            {{ __("View Details") }}
        </button>

        <!-- Dialog -->
        <div
            v-if="dialog"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
        >
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-7xl max-h-[90vh] overflow-hidden border border-gray-200 dark:border-gray-700"
            >
                <!-- Header -->
                <div
                    class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 text-white px-6 py-4"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center"
                            >
                                <i
                                    class="mdi mdi-file-document-outline text-lg"
                                ></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">
                                    {{ __("Transaction Details") }}
                                </h2>
                                <p class="text-blue-100 text-sm mt-1">
                                    {{
                                        __(
                                            "Complete transaction information and metadata"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="dialog = false"
                            class="flex items-center justify-center w-10 h-10 text-white hover:bg-white/20 rounded-xl transition-all duration-200 hover:scale-110"
                        >
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 overflow-y-auto max-h-[60vh]">
                    <!-- Transaction Overview -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Status Card -->
                        <div
                            class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-700 rounded-xl p-4"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-receipt text-white text-xl"
                                    ></i>
                                </div>
                                <div>
                                    <div
                                        class="text-sm text-blue-600 dark:text-blue-400 font-medium"
                                    >
                                        {{ __("Transaction ID") }}
                                    </div>
                                    <div
                                        class="text-lg font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ item.code || item.id }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Card -->
                        <div
                            class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-700 rounded-xl p-4"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-green-500 dark:bg-green-600 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-cash text-white text-xl"
                                    ></i>
                                </div>
                                <div>
                                    <div
                                        class="text-sm text-green-600 dark:text-green-400 font-medium"
                                    >
                                        {{ __("Amount") }}
                                    </div>
                                    <div
                                        class="text-lg font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ item.total }} {{ item.currency }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Card -->
                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-700 rounded-xl p-4"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-purple-500 dark:bg-purple-600 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-check-circle text-white text-xl"
                                    ></i>
                                </div>
                                <div>
                                    <div
                                        class="text-sm text-purple-600 dark:text-purple-400 font-medium"
                                    >
                                        {{ __("Status") }}
                                    </div>
                                    <div
                                        class="text-lg font-bold text-gray-900 dark:text-white capitalize"
                                    >
                                        {{ item.status }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main transaction information -->
                    <div class="mb-8">
                        <div
                            class="flex items-center space-x-2 text-blue-600 dark:text-blue-400 font-semibold mb-4"
                        >
                            <div
                                class="w-1.5 h-6 bg-blue-600 dark:bg-blue-400 rounded-full"
                            ></div>
                            <i class="mdi mdi-information-outline text-lg"></i>
                            <h3 class="text-lg">
                                {{ __("Transaction Information") }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                v-for="(value, key) in filteredItem"
                                :key="key"
                                class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors duration-200"
                            >
                                <div class="flex flex-col space-y-1">
                                    <div
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide"
                                    >
                                        {{ __(formatKey(key)) }}
                                    </div>
                                    <div
                                        class="text-sm font-semibold text-gray-900 dark:text-white break-words"
                                    >
                                        {{ formatValue(value) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable Sections -->
                    <div class="space-y-4">
                        <!-- JSON Response Section -->
                        <div
                            v-if="item.response"
                            class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                        >
                            <button
                                @click="toggleExpansion('response')"
                                class="flex items-center w-full text-left p-4 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200"
                            >
                                <div class="flex items-center space-x-3 flex-1">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-code-json text-blue-600 dark:text-blue-400 text-lg"
                                        ></i>
                                    </div>
                                    <div>
                                        <div
                                            class="font-semibold text-gray-900 dark:text-white"
                                        >
                                            {{ __("JSON Response") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{ __("Raw API response data") }}
                                        </div>
                                    </div>
                                </div>
                                <i
                                    :class="[
                                        'mdi text-xl text-gray-400 dark:text-gray-500 transition-transform duration-300',
                                        expandedSections.response
                                            ? 'mdi-chevron-up'
                                            : 'mdi-chevron-down',
                                    ]"
                                ></i>
                            </button>

                            <div
                                v-if="expandedSections.response"
                                class="border-t border-gray-200 dark:border-gray-700"
                            >
                                <div class="p-4 bg-gray-900 rounded-b-xl">
                                    <pre
                                        class="text-sm text-green-400 whitespace-pre-wrap overflow-x-auto font-mono"
                                        >{{ prettyJSON(item.response) }}</pre
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Meta Information Section -->
                        <div
                            v-if="item.meta"
                            class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                        >
                            <button
                                @click="toggleExpansion('meta')"
                                class="flex items-center w-full text-left p-4 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200"
                            >
                                <div class="flex items-center space-x-3 flex-1">
                                    <div
                                        class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-database-search text-purple-600 dark:text-purple-400 text-lg"
                                        ></i>
                                    </div>
                                    <div>
                                        <div
                                            class="font-semibold text-gray-900 dark:text-white"
                                        >
                                            {{ __("Meta Information") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Additional metadata and system information"
                                                )
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <i
                                    :class="[
                                        'mdi text-xl text-gray-400 dark:text-gray-500 transition-transform duration-300',
                                        expandedSections.meta
                                            ? 'mdi-chevron-up'
                                            : 'mdi-chevron-down',
                                    ]"
                                ></i>
                            </button>

                            <div
                                v-if="expandedSections.meta"
                                class="border-t border-gray-200 dark:border-gray-700"
                            >
                                <div class="p-4 bg-gray-900 rounded-b-xl">
                                    <pre
                                        class="text-sm text-amber-400 whitespace-pre-wrap overflow-x-auto font-mono"
                                        >{{ prettyJSON(item.meta) }}</pre
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-700/50"
                >
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Transaction created") }}:
                            {{ item.created || __("N/A") }}
                        </div>
                        <div class="flex space-x-3">
                            <button
                                @click="copyTransactionData"
                                class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 font-medium"
                            >
                                <i class="mdi mdi-content-copy mr-2"></i>
                                {{ __("Copy Data") }}
                            </button>
                            <button
                                @click="dialog = false"
                                class="flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-all duration-200 font-medium shadow-sm hover:shadow-md"
                            >
                                <i class="mdi mdi-check mr-2"></i>
                                {{ __("Close") }}
                            </button>
                        </div>
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
            const exclude = [
                "response",
                "meta",
                "links",
                "id",
                "code",
                "total",
                "currency",
                "status",
            ];
            return Object.keys(this.item)
                .filter(
                    (key) =>
                        !exclude.includes(key) &&
                        this.item[key] !== null &&
                        this.item[key] !== undefined &&
                        this.item[key] !== ""
                )
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
            if (typeof value === "object") {
                try {
                    return JSON.stringify(value, null, 2);
                } catch {
                    return String(value);
                }
            }
            if (typeof value === "string" && value.length > 100) {
                return value.substring(0, 100) + "...";
            }
            return value;
        },
        prettyJSON(jsonData) {
            try {
                const parsed =
                    typeof jsonData === "string"
                        ? JSON.parse(jsonData)
                        : jsonData;
                return JSON.stringify(parsed, null, 2);
            } catch (e) {
                return String(jsonData);
            }
        },
        toggleExpansion(section) {
            this.expandedSections[section] = !this.expandedSections[section];
        },
        copyTransactionData() {
            const dataToCopy = {
                transaction: this.filteredItem,
                response: this.item.response,
                meta: this.item.meta,
            };

            navigator.clipboard
                .writeText(JSON.stringify(dataToCopy, null, 2))
                .then(() => {
                    $notify.success(__("Transaction data copied to clipboard"));
                })
                .catch(() => {
                    $notify.error(__("Failed to copy data"));
                });
        },
    },
};
</script>
