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
        class="create-btn group inline-flex items-center space-x-2 px-4 py-2.5 bg-transparent border border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 hover:shadow-md transform hover:-translate-y-0.5"
    >
        <svg
            class="w-5 h-5 transform group-hover:scale-110 transition-transform duration-200"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path
                fill-rule="evenodd"
                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                clip-rule="evenodd"
            />
        </svg>
        <span class="font-semibold">{{ __("Create New OAuth Client") }}</span>
    </button>

    <!-- Creation Dialog -->
    <v-modal
        v-model="dialog"
        :title="__('Create New OAuth Client')"
        panel-class="w-full lg:w-5xl"
    >
        <template #body>
            <!-- Header -->
            <div class="mb-6">
                <div class="text-gray-600 dark:text-gray-400 text-sm">
                    {{ __("Register a new OAuth 2.0 client application") }}
                </div>
            </div>

            <!-- Form Content -->
            <div class="grid grid-cols-1 gap-6 mb-4">
                <!-- Client Name -->
                <v-input
                    v-model="form.name"
                    :label="__('Name')"
                    :required="true"
                    :error="errors.name"
                    :placeholder="
                        __(
                            'Enter a descriptive name for your client application'
                        )
                    "
                    :disabled="loading"
                />

                <!-- Redirect URI -->
                <v-input
                    v-model="form.redirect"
                    :label="__('Redirect URI')"
                    :required="true"
                    :error="errors.redirect"
                    placeholder="https://yourapp.com/oauth/callback"
                    :disabled="loading"
                />

                <!-- Confidential Switch -->
                <div class="space-y-3">
                    <v-switch
                        v-model="form.confidential"
                        :label="__('Confidential Client')"
                        :error="errors.confidential"
                        :disabled="loading"
                    />

                    <!-- Switch Description -->
                    <div
                        :class="[
                            'flex items-start space-x-3 text-sm rounded-lg p-3 border transition-colors duration-200',
                            form.confidential
                                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800'
                                : 'bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                        ]"
                    >
                        <svg
                            class="w-4 h-4 text-blue-500 dark:text-blue-400 mt-0.5 flex-shrink-0"
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
            <div
                class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <button
                    @click="close"
                    :disabled="loading"
                    class="cancel-btn px-4 py-2.5 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="create"
                    :disabled="loading || !isFormValid"
                    :class="[
                        'create-confirm-btn px-4 py-2.5 text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2',
                        loading || !isFormValid
                            ? 'bg-blue-400 dark:bg-blue-500'
                            : 'bg-blue-600 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-700',
                    ]"
                >
                    <svg
                        v-if="loading"
                        class="animate-spin h-4 w-4 text-white"
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
                    <svg
                        v-else
                        class="w-4 h-4"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span class="font-medium">{{
                        loading ? __("Creating...") : __("Create Client")
                    }}</span>
                </button>
            </div>

            <!-- Credentials Display -->
            <div
                v-if="client && Object.keys(client).length"
                class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 animate-fade-in"
            >
                <!-- Security Alert -->
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6"
                >
                    <div class="flex items-center mb-3">
                        <svg
                            class="w-5 h-5 text-red-600 dark:text-red-400 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div
                            class="text-lg font-bold text-red-800 dark:text-red-300"
                        >
                            {{ __("Important Security Notice") }}
                        </div>
                    </div>

                    <p class="text-red-700 dark:text-red-300 text-sm mb-4">
                        {{
                            __(
                                "These credentials will only be shown once. Please store them securely immediately."
                            )
                        }}
                    </p>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0"
                    >
                        <div
                            class="text-red-600 dark:text-red-400 text-sm flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{
                                __("Download your credentials for safe keeping")
                            }}
                        </div>

                        <div class="flex space-x-2">
                            <button
                                v-if="client.id"
                                @click="copyToClipboard(client.id, 'Client ID')"
                                class="copy-btn px-3 py-2 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center space-x-2 text-sm"
                                :title="__('Copy Client ID to clipboard')"
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
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-2"
                                    />
                                </svg>
                                <span>{{ __("Copy ID") }}</span>
                            </button>

                            <button
                                v-if="client.secret"
                                @click="
                                    copyToClipboard(
                                        client.secret,
                                        'Client Secret'
                                    )
                                "
                                class="copy-btn px-3 py-2 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center space-x-2 text-sm"
                                :title="__('Copy Client Secret to clipboard')"
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
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-2"
                                    />
                                </svg>
                                <span>{{ __("Copy Secret") }}</span>
                            </button>

                            <button
                                @click="downloadJsonFile"
                                class="download-btn px-3 py-2 text-white bg-red-600 dark:bg-red-700 border border-transparent rounded-lg hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center space-x-2 text-sm"
                                :title="__('Download as JSON file')"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span>{{ __("Download All") }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Client Details -->
                <div>
                    <div
                        class="text-sm font-medium text-gray-900 dark:text-white mb-3"
                    >
                        {{ __("Client Details:") }}
                    </div>
                    <div
                        class="space-y-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
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
                            </div>
                        </div>
                        <div
                            v-if="client.secret"
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
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
                            </div>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
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
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Type:") }}</span
                            >
                            <span
                                :class="[
                                    'text-sm font-medium mt-1 sm:mt-0',
                                    client.confidential
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-orange-600 dark:text-orange-400',
                                ]"
                            >
                                {{
                                    client.confidential
                                        ? __("Confidential")
                                        : __("Public")
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VSwitch from "@/components/VSwitch.vue";

export default {
    components: {
        VModal,
        VSwitch,
        VInput,
    },
    emits: ["created"],

    data() {
        return {
            dialog: false,
            form: {
                name: "",
                redirect: "",
                confidential: false, // Default to true for better security
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
                confidential: false,
            };
            this.loading = false;
        },

        open() {
            this.clean();
            this.dialog = true;
        },

        async copyToClipboard(text, type = "") {
            try {
                await navigator.clipboard.writeText(text);
                this.$notify.success(
                    this.__(`:type copied to clipboard`, {
                        type: type || "Text",
                    })
                );
            } catch (e) {
                this.$notify.error(this.__("Failed to copy to clipboard"));
            }
        },

        downloadJsonFile() {
            const clientCopy = { ...this.client };
            // Remove unnecessary properties
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
                    this.$page.props.routes.clients,
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
                if (e?.response?.status == 422) {
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

.create-btn:active {
    transform: translateY(0);
    transition: transform 0.1s ease;
}
</style>
