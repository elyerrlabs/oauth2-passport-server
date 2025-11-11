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
    <!-- Create Personal Access Client Button -->
    <button
        @click="open"
        class="personal-client-btn group inline-flex items-center space-x-2 px-4 py-2.5 bg-transparent border border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 hover:shadow-md transform hover:-translate-y-0.5"
        :title="__('Create a personal access client for API authentication')"
        :disabled="loading"
    >
        <svg
            class="w-5 h-5 transform group-hover:scale-110 transition-transform duration-200"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path
                fill-rule="evenodd"
                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                clip-rule="evenodd"
            />
        </svg>
        <span class="font-semibold">{{
            __("Create Personal Access Client")
        }}</span>
    </button>

    <!-- Dialog -->
    <v-modal
        v-model="dialog"
        :title="__('Create Personal Access Client')"
        panel-class="w-full lg:w-3xl"
    >
        <template #body>
            <!-- Header Banner -->
            <div
                class="bg-blue-600 dark:bg-blue-700 text-white rounded-t-lg -mx-6 -mt-6 px-6 py-4 transition-colors duration-200"
            >
                <div class="text-center">
                    <div class="flex items-center justify-center mb-2">
                        <div
                            class="w-10 h-10 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm opacity-90 mt-1">
                        {{
                            __(
                                "Personal access clients allow your applications to authenticate with the API using generated tokens."
                            )
                        }}
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="space-y-6 mt-4">
                <!-- Client Name Input -->
                <div class="space-y-3">
                    <v-input
                        :label="__('Client Name')"
                        v-model="form.name"
                        :error="errors.name"
                        :required="true"
                        :placeholder="
                            __('e.g., My API Client, Mobile App, etc.')
                        "
                        :disabled="loading"
                    />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "Choose a descriptive name to identify this personal access client"
                            )
                        }}
                    </p>
                </div>

                <!-- Security Notice -->
                <div
                    class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-lg p-4 border border-green-200 dark:border-green-800 transition-colors duration-200"
                >
                    <div class="flex items-start">
                        <svg
                            class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="text-sm">
                            <strong class="font-medium">{{
                                __("Security Note:")
                            }}</strong>
                            {{
                                __(
                                    "This client will be used to generate API tokens. Keep your tokens secure and never share them publicly."
                                )
                            }}
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4"
                >
                    <div class="flex items-start space-x-3">
                        <svg
                            class="w-5 h-5 text-blue-500 dark:text-blue-400 mt-0.5 flex-shrink-0"
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
                        <div class="text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-medium mb-1">
                                {{ __("About Personal Access Clients") }}
                            </p>
                            <p>
                                {{
                                    __(
                                        "Personal access clients are suitable for first-party applications where you control both the client and the resource server. They use the Password Grant or Personal Access Token flow."
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700"
            >
                <button
                    @click="close"
                    :disabled="loading"
                    class="cancel-btn px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                >
                    <svg
                        class="w-4 h-4"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>{{ __("Cancel") }}</span>
                </button>
                <button
                    @click="createPersonalAccessClient"
                    :disabled="loading || !isFormValid"
                    :class="[
                        'create-btn px-4 py-2.5 text-sm font-medium text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2',
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
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"
                        />
                        <path
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                        />
                    </svg>
                    <span class="font-medium">{{
                        loading ? __("Creating...") : __("Create Client")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";

export default {
    components: {
        VModal,
        VInput,
    },
    emits: ["created"],

    data() {
        return {
            dialog: false,
            loading: false,
            form: {
                name: "",
            },
            errors: {},
        };
    },

    computed: {
        isFormValid() {
            return this.form.name?.trim();
        },
    },

    methods: {
        open() {
            this.form.name = "";
            this.errors = {};
            this.dialog = true;
        },

        close() {
            this.dialog = false;
            this.loading = false;
            this.form.name = "";
            this.errors = {};
        },

        async createPersonalAccessClient() {
            if (!this.isFormValid) return;

            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.routes.personal,
                    this.form
                );

                if (res.status == 201) {
                    this.$notify.success({
                        title: this.__("Success"),
                        message: this.__(
                            "Personal access client created successfully"
                        ),
                        timeout: 3000,
                    });
                    this.$emit("created");
                    this.close();
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                    this.$notify.error({
                        title: this.__("Validation Error"),
                        message: this.__("Please fix the form errors"),
                        timeout: 5000,
                    });
                } else if (e?.response?.data?.message) {
                    this.$notify.error({
                        title: this.__("Error"),
                        message: e.response.data.message,
                        timeout: 5000,
                    });
                } else {
                    this.$notify.error({
                        title: this.__("Error"),
                        message: this.__(
                            "Failed to create personal access client"
                        ),
                        timeout: 5000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.personal-client-btn:active {
    transform: translateY(0);
    transition: transform 0.1s ease;
}
</style>
