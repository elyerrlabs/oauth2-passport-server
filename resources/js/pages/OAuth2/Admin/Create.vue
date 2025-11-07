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
        class="bg-transparent border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center space-x-2"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                clip-rule="evenodd"
            />
        </svg>
        <span>{{ __("Create New OAuth Client") }}</span>
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
                <div class="text-gray-600 text-sm">
                    {{ __("Register a new OAuth 2.0 client application") }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-4">
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
                />

                <v-input
                    v-model="form.redirect"
                    :label="__('Redirect URI')"
                    :required="true"
                    :error="errors.name"
                    placeholder="https://yourapp.com/oauth/callback"
                />

                <v-switch
                    v-model="form.confidential"
                    :error="errors.confidential"
                    :placeholder="
                        __(
                            'Confidential clients can keep secrets secure (server-side applications). Uncheck for public clients (SPA, mobile apps).'
                        )
                    "
                />
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200"
            >
                <button
                    @click="close"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                >
                    {{ __("Close") }}
                </button>
                <button
                    @click="create"
                    :disabled="loading"
                    class="px-4 py-2 text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
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
                    <span>{{
                        loading ? __("Creating...") : __("Create Client")
                    }}</span>
                </button>
            </div>

            <!-- Credentials Display -->
            <div
                v-if="client && Object.keys(client).length"
                class="mt-8 pt-6 border-t border-gray-200"
            >
                <!-- Security Alert -->
                <div
                    class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4"
                >
                    <div class="flex items-center mb-3">
                        <svg
                            class="w-5 h-5 text-red-600 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="text-lg font-bold text-red-800">
                            {{ __("Important Security Notice") }}
                        </div>
                    </div>

                    <p class="text-red-700 text-sm mb-4">
                        {{
                            __(
                                "These credentials will only be shown once. Please store them securely immediately."
                            )
                        }}
                    </p>

                    <div class="flex items-center justify-between">
                        <div class="text-red-600 text-xs flex items-center">
                            <svg
                                class="w-4 h-4 mr-1"
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

                        <button
                            @click="downloadJsonFile"
                            class="px-3 py-2 text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center space-x-2 text-sm"
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
                            <span>{{ __("Download Credentials") }}</span>
                        </button>
                    </div>
                </div>

                <!-- Client Details -->
                <div>
                    <div class="text-sm font-medium text-gray-900 mb-3">
                        {{ __("Client Details:") }}
                    </div>
                    <div class="space-y-2">
                        <div
                            class="flex justify-between items-center py-2 border-b border-gray-100"
                        >
                            <span class="text-sm font-medium text-gray-600">{{
                                __("Client ID:")
                            }}</span>
                            <span class="text-sm text-gray-900 font-mono">{{
                                client.id
                            }}</span>
                        </div>
                        <div
                            v-if="client.secret"
                            class="flex justify-between items-center py-2 border-b border-gray-100"
                        >
                            <span class="text-sm font-medium text-gray-600">{{
                                __("Client Secret:")
                            }}</span>
                            <span class="text-sm text-red-600 font-mono">{{
                                client.secret
                            }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600">{{
                                __("Name:")
                            }}</span>
                            <span class="text-sm text-gray-900">{{
                                client.name
                            }}</span>
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
        VInput
    },
    emits: ["created"],

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

            $notify.success(__("Credentials downloaded successfully"));
        },

        async create() {
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

                    $notify.success(__("OAuth client created successfully"));
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
