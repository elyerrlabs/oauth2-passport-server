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
    <v-account-layout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg transition-colors duration-200"
                        >
                            <svg
                                class="w-6 h-6 text-indigo-600 dark:text-indigo-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("API Keys Management") }}
                            </h1>
                            <p
                                class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                            >
                                {{
                                    __(
                                        "Manage your API keys for secure application integration"
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                    <v-create @created="getPersonalAccessToken" />
                </div>
            </div>

            <!-- Table Container -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-200"
            >
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                                >
                                    {{ __("Key Name") }}
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                                >
                                    {{ __("Created Date") }}
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                                >
                                    {{ __("Expiration Date") }}
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                                >
                                    {{ __("Actions") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <!-- Loading State -->
                            <tr v-if="loading">
                                <td colspan="4" class="px-6 py-8 text-center">
                                    <div
                                        class="flex justify-center items-center"
                                    >
                                        <svg
                                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600 dark:text-indigo-400"
                                            xmlns="http://www.w3.org/2000/svg"
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
                                        <span
                                            class="text-gray-500 dark:text-gray-400"
                                            >{{
                                                __("Loading API keys...")
                                            }}</span
                                        >
                                    </div>
                                </td>
                            </tr>

                            <!-- Rows -->
                            <tr
                                v-for="token in tokens"
                                :key="token.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group"
                            >
                                <!-- Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/30 rounded-lg transition-colors duration-200 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/50"
                                        >
                                            <svg
                                                class="h-5 w-5 text-indigo-600 dark:text-indigo-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900 dark:text-white"
                                            >
                                                {{ token.name }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded inline-block"
                                            >
                                                ID: {{ token.id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Created Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm text-gray-900 dark:text-white"
                                    >
                                        {{ formatDate(token.created) }}
                                    </div>
                                    <div
                                        class="flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        <svg
                                            class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400 dark:text-gray-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                        {{ formatTimeAgo(token.created) }}
                                    </div>
                                </td>

                                <!-- Expiration Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        :class="[
                                            'text-sm font-medium',
                                            getExpirationClass(
                                                token.expires
                                            ) === 'text-positive'
                                                ? 'text-green-600 dark:text-green-400'
                                                : getExpirationClass(
                                                      token.expires
                                                  ) === 'text-warning'
                                                ? 'text-amber-600 dark:text-amber-400'
                                                : 'text-red-600 dark:text-red-400',
                                        ]"
                                    >
                                        {{
                                            token.expires
                                                ? formatDate(token.expires)
                                                : __("Never")
                                        }}
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <svg
                                            :class="[
                                                'flex-shrink-0 mr-1.5 h-4 w-4',
                                                getExpirationClass(
                                                    token.expires
                                                ) === 'text-positive'
                                                    ? 'text-green-500 dark:text-green-400'
                                                    : getExpirationClass(
                                                          token.expires
                                                      ) === 'text-warning'
                                                    ? 'text-amber-500 dark:text-amber-400'
                                                    : 'text-red-500 dark:text-red-400',
                                            ]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                :d="
                                                    getExpirationIcon(
                                                        token.expires
                                                    )
                                                "
                                            />
                                        </svg>
                                        <span
                                            :class="[
                                                'text-xs font-medium',
                                                getExpirationClass(
                                                    token.expires
                                                ) === 'text-positive'
                                                    ? 'text-green-600 dark:text-green-400'
                                                    : getExpirationClass(
                                                          token.expires
                                                      ) === 'text-warning'
                                                    ? 'text-amber-600 dark:text-amber-400'
                                                    : 'text-red-600 dark:text-red-400',
                                            ]"
                                        >
                                            {{
                                                getExpirationStatus(
                                                    token.expires
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <div
                                        v-if="isExpiringSoon(token.expires)"
                                        class="text-xs text-amber-600 dark:text-amber-400 mt-1 flex items-center"
                                    >
                                        <svg
                                            class="flex-shrink-0 mr-1 h-3 w-3"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        {{ __("Expiring soon") }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <div
                                        class="flex justify-end items-center space-x-2"
                                    >
                                        <v-delete
                                            @deleted="getPersonalAccessToken"
                                            :item="token"
                                        />
                                        <button
                                            v-if="token.token"
                                            @click="
                                                copyToClipboard(token.token)
                                            "
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
                                            :title="
                                                __('Copy API key to clipboard')
                                            "
                                        >
                                            <svg
                                                class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500 dark:text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-2"
                                                />
                                            </svg>
                                            {{ __("Copy") }}
                                        </button>
                                        <button
                                            v-else
                                            @click="showTokenDetails(token)"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
                                            :title="__('View token details')"
                                        >
                                            <svg
                                                class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500 dark:text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                />
                                            </svg>
                                            {{ __("View") }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="!loading && tokens.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div
                                        class="flex flex-col items-center justify-center"
                                    >
                                        <div
                                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4"
                                        >
                                            <svg
                                                class="w-8 h-8 text-gray-400 dark:text-gray-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                                />
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-lg font-medium text-gray-900 dark:text-white mb-1"
                                        >
                                            {{ __("No API Keys Found") }}
                                        </h3>
                                        <p
                                            class="text-gray-500 dark:text-gray-400 max-w-md text-sm"
                                        >
                                            {{
                                                __(
                                                    "Create your first API key to get started with secure application integration"
                                                )
                                            }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <v-paginate
                v-model="search.page"
                :total-pages="pages.total_pages"
                @change="getPersonalAccessToken"
            />
        </div>
    </v-account-layout>
</template>

<script>
import VAccountLayout from "@/layouts/VAccountLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VDelete,
        VAccountLayout,
        VPaginate,
    },

    data() {
        return {
            tokens: [],
            loading: false,
            showDetailsDialog: false,
            selectedToken: null,
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    created() {
        this.getPersonalAccessToken();
    },

    methods: {
        getPersonalAccessToken() {
            this.loading = true;
            this.$server
                .get(this.$page.props.route, { params: this.search })
                .then((res) => {
                    this.tokens = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.search.total_pages =
                        res.data.meta.pagination.total_pages;
                })
                .catch((e) => {
                    if (e?.response?.data?.message) {
                        this.$notify.error(e.response.data.message);
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        getExpirationClass(expirationDate) {
            if (!expirationDate) return "text-positive";

            const expDate = new Date(expirationDate);
            const now = new Date();
            const sevenDaysFromNow = new Date(
                now.getTime() + 7 * 24 * 60 * 60 * 1000
            );

            if (expDate < now) return "text-negative";
            if (expDate < sevenDaysFromNow) return "text-warning";
            return "text-positive";
        },

        getExpirationIcon(expirationDate) {
            if (!expirationDate)
                return "M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z";

            const expDate = new Date(expirationDate);
            const now = new Date();

            if (expDate < now)
                return "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z";
            return "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z";
        },

        getExpirationStatus(expirationDate) {
            if (!expirationDate) return __("Never expires");

            const expDate = new Date(expirationDate);
            const now = new Date();

            if (expDate < now) return __("Expired");

            const sevenDaysFromNow = new Date(
                now.getTime() + 7 * 24 * 60 * 60 * 1000
            );
            if (expDate < sevenDaysFromNow) return __("Expires soon");

            return __("Active");
        },

        isExpiringSoon(expirationDate) {
            if (!expirationDate) return false;

            const expDate = new Date(expirationDate);
            const now = new Date();
            const sevenDaysFromNow = new Date(
                now.getTime() + 7 * 24 * 60 * 60 * 1000
            );

            return expDate > now && expDate < sevenDaysFromNow;
        },

        formatDate(dateString) {
            if (!dateString) return "N/A";
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        formatTimeAgo(dateString) {
            if (!dateString) return "";

            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 1) return __("1 day ago");
            if (diffDays < 7) return __(":count days ago", { count: diffDays });
            if (diffDays < 30)
                return __(":count weeks ago", {
                    count: Math.floor(diffDays / 7),
                });
            return __(":count months ago", {
                count: Math.floor(diffDays / 30),
            });
        },

        copyToClipboard(text) {
            navigator.clipboard
                .writeText(text)
                .then(() => {
                    this.$notify.success(
                        this.__("API key copied to clipboard")
                    );
                })
                .catch(() => {
                    this.$notify.error(this.__("Failed to copy to clipboard"));
                });
        },

        showTokenDetails(token) {
            this.selectedToken = token;
            this.showDetailsDialog = true;
        },
    },
};
</script>
