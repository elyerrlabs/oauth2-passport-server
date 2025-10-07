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
    <div class="create-client-container">
        <!-- Create Button -->
        <button
            @click="open"
            class="create-btn group relative cursor-pointer flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-4 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <svg
                class="w-6 h-6 transform group-hover:scale-110 group-hover:rotate-90 transition-all duration-200"
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
            <span class="ml-3 font-semibold text-sm hidden sm:block">
                {{ __("Create Client") }}
            </span>
        </button>

        <!-- Creation Dialog -->
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
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ __("Create New OAuth Client") }}
                                </h3>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">
                            {{
                                __(
                                    "Register a new OAuth 2.0 client application"
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
                            <input
                                v-model="form.name"
                                type="text"
                                :placeholder="
                                    __(
                                        'Enter a descriptive name for your client application'
                                    )
                                "
                                :class="[
                                    'w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200',
                                    errors.name
                                        ? 'border-red-300'
                                        : 'border-gray-300',
                                ]"
                            />
                            <v-error :error="errors.name" />
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
                            <input
                                v-model="form.redirect"
                                type="text"
                                placeholder="https://yourapp.com/oauth/callback"
                                :class="[
                                    'w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200',
                                    errors.redirect
                                        ? 'border-red-300'
                                        : 'border-gray-300',
                                ]"
                            />
                            <v-error :error="errors.redirect" />
                            <p class="mt-1 text-sm text-gray-500">
                                {{
                                    __(
                                        "The URI where users will be redirected after authorization"
                                    )
                                }}
                            </p>
                        </div>

                        <!-- Confidential Client -->
                        <div class="form-section">
                            <label
                                class="flex items-center space-x-3 cursor-pointer"
                            >
                                <input
                                    v-model="form.confidential"
                                    type="checkbox"
                                    :class="[
                                        'w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-colors duration-200',
                                        errors.confidential
                                            ? 'border-red-300'
                                            : '',
                                    ]"
                                />
                                <span class="text-sm font-medium text-gray-700">
                                    {{ __("Confidential Client") }}
                                </span>
                            </label>
                            <v-error :error="errors.confidential" />
                            <div
                                class="mt-2 flex items-start space-x-2 text-sm text-gray-500 bg-blue-50 rounded-lg p-3"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span>
                                    {{
                                        __(
                                            "Confidential clients can keep secrets secure (server-side applications). Uncheck for public clients (SPA, mobile apps)."
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <button
                            @click="close"
                            class="cancel-btn cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        >
                            {{ __("Close") }}
                        </button>
                        <button
                            @click="create"
                            :disabled="loading"
                            class="create-confirm-btn cursor-pointer px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 flex items-center space-x-2"
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
                                    ? __("Creating...")
                                    : __("Create Client")
                            }}</span>
                        </button>
                    </div>

                    <!-- Credentials Display -->
                    <div
                        v-if="client && Object.keys(client).length"
                        class="mt-8 border-t pt-6"
                    >
                        <div
                            class="bg-red-50 border border-red-200 rounded-xl p-4"
                        >
                            <div class="flex items-center mb-3">
                                <svg
                                    class="w-5 h-5 text-red-500 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                                <h4 class="text-lg font-semibold text-red-800">
                                    {{ __("Important Security Notice") }}
                                </h4>
                            </div>

                            <div class="space-y-3">
                                <p class="text-sm text-red-700">
                                    {{
                                        __(
                                            "These credentials will only be shown once. Please store them securely immediately."
                                        )
                                    }}
                                </p>

                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0"
                                >
                                    <div
                                        class="flex items-center text-sm text-red-600"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                            />
                                        </svg>
                                        {{
                                            __(
                                                "Download your credentials for safe keeping"
                                            )
                                        }}
                                    </div>

                                    <button
                                        @click="downloadJsonFile"
                                        class="download-btn inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                        :title="__('Download as JSON file')"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                            />
                                        </svg>
                                        {{ __("Download Credentials") }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Client Details -->
                        <div class="mt-4">
                            <h5
                                class="text-sm font-semibold text-gray-900 mb-3"
                            >
                                {{ __("Client Details:") }}
                            </h5>
                            <div class="space-y-2 bg-gray-50 rounded-lg p-4">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-gray-200 last:border-b-0"
                                >
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                        >{{ __("Client ID:") }}</span
                                    >
                                    <span
                                        class="text-sm text-gray-900 font-mono mt-1 sm:mt-0"
                                        >{{ client.id }}</span
                                    >
                                </div>
                                <div
                                    v-if="client.secret"
                                    class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-gray-200 last:border-b-0"
                                >
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                        >{{ __("Client Secret:") }}</span
                                    >
                                    <span
                                        class="text-sm text-red-600 font-mono mt-1 sm:mt-0"
                                        >{{ client.secret }}</span
                                    >
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-gray-200 last:border-b-0"
                                >
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                        >{{ __("Name:") }}</span
                                    >
                                    <span
                                        class="text-sm text-gray-900 mt-1 sm:mt-0"
                                        >{{ client.name }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VError from "../../../../Components/VError.vue";

export default {
    emits: ["created"],
    components: {
        VError,
    },
    data() {
        return {
            dialog: false,
            form: {
                name: "",
                redirect: "",
                confidential: false,
            },
            errors: {},
            client: {},
            loading: false,
        };
    },

    methods: {
        close() {
            this.clean();
            this.dialog = false;
        },

        clean() {
            this.client = {};
            this.errors = {};
            this.form = {
                name: "",
                redirect: "",
                confidential: false,
            };
            this.loading = false;
        },

        open() {
            this.clean();
            this.dialog = true;
        },

        downloadJsonFile() {
            const clientCopy = { ...this.client };

            delete clientCopy.links;
            delete clientCopy.revoked;
            delete clientCopy.provider;
            delete clientCopy.redirect;

            const filename = `${clientCopy.name || "client"}-credentials.json`;
            const jsonString = JSON.stringify(clientCopy, null, 2);
            const blob = new Blob([jsonString], { type: "application/json" });
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);

            this.$notify.success("Credentials downloaded successfully");
        },

        async create() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );

                if (res.status === 201) {
                    this.client = res.data.data;
                    this.$emit("created", true);

                    this.$notify.success("OAuth client created successfully");
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
