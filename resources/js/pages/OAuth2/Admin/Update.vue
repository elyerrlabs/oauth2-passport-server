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
    <!-- Edit Button -->
    <button
        @click="open(item)"
        class="edit-btn group inline-flex items-center justify-center bg-transparent border border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 rounded-full p-2 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 hover:shadow-md transform hover:-translate-y-0.5"
        :title="__('Edit Client')"
        :disabled="loading"
    >
        <svg
            class="w-5 h-5 transform group-hover:scale-110 transition-transform duration-200"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path
                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
            />
        </svg>
    </button>

    <!-- Edit Modal -->
    <v-modal
        v-model="dialog"
        panel-class="w-full lg:w-3xl"
        :title="__('Update OAuth Client')"
    >
        <template #body>
            <!-- Header -->
            <div class="mb-6">
                <div class="text-gray-600 dark:text-gray-400 text-sm">
                    {{
                        __("Modify your OAuth 2.0 client application settings")
                    }}
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

                <!-- Client ID (Read-only) -->
                <div v-if="form.id" class="space-y-2">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        {{ __("Client ID") }}
                    </label>
                    <div class="relative">
                        <input
                            :value="form.id"
                            type="text"
                            readonly
                            :class="[
                                'w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400 cursor-not-allowed transition-colors duration-200 font-mono text-sm',
                            ]"
                        />
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                        >
                            <button
                                @click="copyToClipboard(form.id, 'Client ID')"
                                class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 p-1 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :title="__('Copy Client ID to clipboard')"
                                :disabled="loading"
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
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __("This identifier cannot be changed") }}
                    </p>
                </div>
            </div>

            <!-- Information Notice -->
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4"
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
                    <div class="text-blue-700 dark:text-blue-300 text-sm">
                        <p class="font-medium mb-1">
                            {{ __("Update Information") }}
                        </p>
                        <p>
                            {{
                                __(
                                    "Updating client details will not affect existing access tokens. Changes take effect immediately for new authorizations."
                                )
                            }}
                        </p>
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
                    class="cancel-btn px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="updateClient"
                    :disabled="loading || !isFormValid"
                    :class="[
                        'update-btn px-4 py-2.5 text-sm font-medium text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center space-x-2',
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
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span class="font-medium">{{
                        loading ? __("Updating...") : __("Update Client")
                    }}</span>
                </button>
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
                confidential: "",
            },
            form: {},
            dialog: false,
            loading: false,
        };
    },

    computed: {
        isFormValid() {
            return this.form.name?.trim() && this.form.redirect?.trim();
        },
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
            if (!this.isFormValid) return;

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

                    this.$notify.success({
                        title: this.__("Success"),
                        message: this.__("OAuth client updated successfully"),
                        timeout: 3000,
                    });
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
                        message: this.__("Failed to update client"),
                        timeout: 5000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        async copyToClipboard(text, type = "") {
            try {
                await navigator.clipboard.writeText(text);
                this.$notify.success({
                    title: this.__("Copied"),
                    message: this.__(`:type copied to clipboard`, {
                        type: type || "Text",
                    }),
                    timeout: 2000,
                });
            } catch (err) {
                this.$notify.error({
                    title: this.__("Error"),
                    message: this.__("Failed to copy to clipboard"),
                    timeout: 3000,
                });
            }
        },
    },
};
</script>

<style scoped>
.edit-btn:active {
    transform: translateY(0);
    transition: transform 0.1s ease;
}
</style>
