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
    <div class="update-client-container">
        <!-- Edit Button -->
        <button
            @click="open(item)"
            class="edit-btn group relative inline-flex items-center justify-center bg-white text-blue-600 border border-blue-200 hover:border-blue-300 hover:bg-blue-50 font-medium py-2.5 px-4 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white"
        >
            <!-- Animated icon container -->
            <div class="relative flex items-center justify-center w-5 h-5 mr-2">
                <svg
                    class="w-5 h-5 transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-200"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                </svg>

                <!-- Pulse effect on hover -->
                <div
                    class="absolute inset-0 rounded-full bg-blue-100 opacity-0 group-hover:opacity-100 scale-150 group-hover:scale-100 transition-all duration-300 -z-10"
                ></div>
            </div>

            <!-- Button text -->
            <span class="text-sm font-semibold tracking-wide">
                {{ __("Edit") }}
            </span>

            <!-- Hover border animation -->
            <div
                class="absolute inset-0 rounded-xl border border-transparent group-hover:border-blue-200 transition-colors duration-200 -z-10"
            ></div>
        </button>

        <!-- Update Dialog -->
        <div v-if="dialog" class="fixed inset-0 z-50 overflow-y-auto">
            <div
                class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-black/80"></div>

                <!-- Dialog panel -->
                <div
                    class="relative inline-block w-full max-w-2xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:p-6"
                >
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ __("Update OAuth Client") }}
                                </h3>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">
                            {{
                                __(
                                    "Modify your OAuth 2.0 client application settings"
                                )
                            }}
                        </p>
                    </div>

                    <!-- Form Content -->
                    <div class="space-y-6">
                        <!-- Client Name -->
                        <div class="form-section">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-gray-500 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                        />
                                    </svg>
                                    {{ __("Client Name") }}
                                </div>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                        />
                                    </svg>
                                </div>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    :placeholder="
                                        __(
                                            'Enter a descriptive name for your client application'
                                        )
                                    "
                                    :class="[
                                        'w-full pl-10 pr-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200',
                                        errors.name
                                            ? 'border-red-300'
                                            : 'border-gray-300',
                                    ]"
                                />
                            </div>
                            <v-error
                                :error="errors.name"
                                class="mt-1"
                            ></v-error>
                            <p class="mt-1 text-sm text-gray-500">
                                {{
                                    __(
                                        "This will help you identify the client later"
                                    )
                                }}
                            </p>
                        </div>

                        <!-- Redirect URI -->
                        <div class="form-section">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-gray-500 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                        />
                                    </svg>
                                    {{ __("Redirect URI") }}
                                </div>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                        />
                                    </svg>
                                </div>
                                <input
                                    v-model="form.redirect"
                                    type="text"
                                    placeholder="https://yourapp.com/oauth/callback"
                                    :class="[
                                        'w-full pl-10 pr-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200',
                                        errors.redirect
                                            ? 'border-red-300'
                                            : 'border-gray-300',
                                    ]"
                                />
                            </div>
                            <v-error
                                :error="errors.redirect"
                                class="mt-1"
                            ></v-error>
                            <p class="mt-1 text-sm text-gray-500">
                                {{
                                    __(
                                        "The URI where users will be redirected after authorization"
                                    )
                                }}
                            </p>
                        </div>

                        <!-- Client ID Display (Read-only) -->
                        <div class="form-section" v-if="form.id">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-gray-500 mr-2"
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
                                    {{ __("Client ID") }}
                                </div>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
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
                                <input
                                    :value="form.id"
                                    type="text"
                                    readonly
                                    :class="[
                                        'w-full pl-10 pr-12 py-2 border border-gray-300 bg-gray-50 rounded-lg text-gray-600 cursor-not-allowed',
                                    ]"
                                />
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                                >
                                    <button
                                        @click="copyToClipboard(form.id)"
                                        class="text-gray-400 hover:text-blue-600 transition-colors duration-200 p-1 rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        :title="__('Copy Client ID')"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __("This identifier cannot be changed") }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <button
                            @click="close"
                            class="cancel-btn px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        >
                            {{ __("Cancel") }}
                        </button>
                        <button
                            @click="updateClient"
                            :disabled="loading"
                            class="update-btn px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 flex items-center space-x-2"
                        >
                            <svg
                                v-if="loading"
                                class="w-4 h-4 animate-spin"
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
                            <svg
                                v-else
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span>{{
                                loading
                                    ? __("Updating...")
                                    : __("Update Client")
                            }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["updated"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            errors: {
                name: "",
                redirect: "",
            },
            form: {},
            dialog: false,
            loading: false,
        };
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
            this.loading = false;
        },

        open(item) {
            this.form = { ...item };
            this.errors = {};
            this.dialog = true;
        },

        async updateClient() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.$emit("updated", true);
                    this.dialog = false;

                     $notify.success("OAuth client updated successfully");
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                     $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                 $notify.success("Client ID copied to clipboard");
            } catch (err) {
                 $notify.error("Failed to copy to clipboard");
            }
        },
    },
};
</script>
