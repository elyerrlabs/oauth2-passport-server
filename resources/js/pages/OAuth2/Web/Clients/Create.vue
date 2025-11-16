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
    <!-- Create Button -->
    <button
        @click="open"
        class="create-btn group relative cursor-pointer flex items-center justify-center bg-linear-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 dark:from-blue-600 dark:to-blue-700 dark:hover:from-blue-700 dark:hover:to-blue-800 text-white p-2 lg:p-4 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
    >
        <svg
            class="w-8 h-8 transform group-hover:scale-110 group-hover:rotate-90 transition-all duration-200"
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
        <span class="ml-3 font-semibold text-sm md:text-lg ">
            {{ __("Create Client") }}
        </span>

        <!-- Ripple Effect -->
        <span class="absolute inset-0 overflow-hidden rounded-2xl">
            <span
                class="ripple absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"
            ></span>
        </span>
    </button>

    <!-- Creation Dialog -->
    <v-modal v-model="dialog" panel-class="w-full lg:w-5xl">
        <template #body>
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <div class="shrink-0">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center transition-colors duration-200"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600 dark:text-blue-400"
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
                        <h3
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ __("Create New OAuth Client") }}
                        </h3>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    {{ __("Register a new OAuth 2.0 client application") }}
                </p>
            </div>

            <!-- Form Content -->
            <div class="space-y-6">
                <!-- Client Name -->
                <div class="form-section">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400 mr-2"
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
                            <span class="text-red-500 ml-1">*</span>
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
                            'w-full px-3 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400',
                            errors.name
                                ? 'border-red-300 dark:border-red-500'
                                : 'border-gray-300 dark:border-gray-600',
                        ]"
                    />
                    <v-error :error="errors.name" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __("This will help you identify the client later") }}
                    </p>
                </div>

                <!-- Redirect URI -->
                <div class="form-section">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400 mr-2"
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
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                    </label>
                    <input
                        v-model="form.redirect"
                        type="text"
                        placeholder="https://yourapp.com/oauth/callback"
                        :class="[
                            'w-full px-3 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400',
                            errors.redirect
                                ? 'border-red-300 dark:border-red-500'
                                : 'border-gray-300 dark:border-gray-600',
                        ]"
                    />
                    <v-error :error="errors.redirect" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "The URI where users will be redirected after authorization"
                            )
                        }}
                    </p>
                </div>

                <!-- Confidential Client -->
                <div class="form-section">
                    <div class="flex items-center justify-between">
                        <label
                            class="flex items-center space-x-3 cursor-pointer group"
                        >
                            <div class="relative">
                                <input
                                    v-model="form.confidential"
                                    type="checkbox"
                                    :class="[
                                        'w-4 h-4 text-blue-600 dark:text-blue-500 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-400 transition-all duration-200 bg-white dark:bg-gray-700',
                                        errors.confidential
                                            ? 'border-red-300 dark:border-red-500'
                                            : '',
                                    ]"
                                />
                                <div
                                    class="absolute inset-0 rounded border-2 border-transparent group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors duration-200 pointer-events-none"
                                ></div>
                            </div>
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Confidential Client") }}
                            </span>
                        </label>

                        <!-- Toggle Badge -->
                        <span
                            :class="[
                                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                form.confidential
                                    ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
                            ]"
                        >
                            <i
                                :class="[
                                    'mdi mr-1 text-xs',
                                    form.confidential
                                        ? 'mdi-lock-outline'
                                        : 'mdi-lock-open-outline',
                                ]"
                            ></i>
                            {{
                                form.confidential
                                    ? __("Confidential")
                                    : __("Public")
                            }}
                        </span>
                    </div>

                    <v-error :error="errors.confidential" />
                    <div
                        :class="[
                            'mt-3 flex items-start space-x-3 text-sm rounded-lg p-3 border transition-colors duration-200',
                            form.confidential
                                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800'
                                : 'bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                        ]"
                    >
                        <svg
                            class="w-4 h-4 text-blue-500 dark:text-blue-400 mt-0.5 shrink-0"
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
                                form.confidential
                                    ? __(
                                          "Confidential clients can keep secrets secure (recommended for server-side applications)."
                                      )
                                    : __(
                                          "Public clients cannot keep secrets secure (suitable for SPA and mobile apps)."
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
                    class="cancel-btn cursor-pointer px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="create"
                    :disabled="loading || !isFormValid"
                    :class="[
                        'create-confirm-btn cursor-pointer px-4 py-2.5 text-sm font-medium text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center space-x-2',
                        loading || !isFormValid
                            ? 'bg-blue-400 dark:bg-blue-500'
                            : 'bg-blue-600 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-700',
                    ]"
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
                        loading ? __("Creating...") : __("Create Client")
                    }}</span>
                </button>
            </div>

            <!-- Credentials Display -->
            <div
                v-if="client && Object.keys(client).length"
                class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6"
            >
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 animate-fade-in"
                >
                    <div class="flex items-center mb-3">
                        <svg
                            class="w-5 h-5 text-red-500 dark:text-red-400 mr-2"
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
                        <h4
                            class="text-lg font-semibold text-red-800 dark:text-red-300"
                        >
                            {{ __("Important Security Notice") }}
                        </h4>
                    </div>

                    <div class="space-y-3">
                        <p class="text-sm text-red-700 dark:text-red-300">
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
                                class="flex items-center text-sm text-red-600 dark:text-red-400"
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
                                class="download-btn inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
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
                        class="text-sm font-semibold text-gray-900 dark:text-white mb-3"
                    >
                        {{ __("Client Details:") }}
                    </h5>
                    <div
                        class="space-y-2 bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Client ID:") }}</span
                            >
                            <div
                                class="flex items-center space-x-2 mt-1 sm:mt-0"
                            >
                                <span
                                    class="text-sm text-gray-900 dark:text-white font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"
                                    >{{ client.id }}</span
                                >
                                <button
                                    @click="copyToClipboard(client.id)"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                    :title="__('Copy to clipboard')"
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
                        <div
                            v-if="client.secret"
                            class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Client Secret:") }}</span
                            >
                            <div
                                class="flex items-center space-x-2 mt-1 sm:mt-0"
                            >
                                <span
                                    class="text-sm text-red-600 dark:text-red-400 font-mono bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded border border-red-100 dark:border-red-800"
                                    >{{ client.secret }}</span
                                >
                                <button
                                    @click="copyToClipboard(client.secret)"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                    :title="__('Copy to clipboard')"
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
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Name:") }}</span
                            >
                            <span
                                class="text-sm text-gray-900 dark:text-white mt-1 sm:mt-0"
                                >{{ client.name }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VError from "@/components/VError.vue";
import VModal from "@/components/VModal.vue";

export default {
    emits: ["created"],
    components: {
        VError,
        VModal,
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

    computed: {
        isFormValid() {
            return this.form.name.trim() && this.form.redirect.trim();
        },
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
                confidential: true,
            };
            this.loading = false;
        },

        open() {
            this.clean();
            this.dialog = true;
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$notify.success(this.__("Copied to clipboard"));
            } catch (err) {
                console.error("Failed to copy text: ", err);
                this.$notify.error(this.__("Failed to copy to clipboard"));
            }
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

            this.$notify.success(
                this.__("Credentials downloaded successfully")
            );
        },

        async create() {
            if (!this.isFormValid) return;

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

                    this.$notify.success(
                        this.__("OAuth client created successfully")
                    );
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

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ripple {
    border-radius: inherit;
}

.create-btn:active .ripple {
    animation: ripple 0.6s ease-out;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
</style>
