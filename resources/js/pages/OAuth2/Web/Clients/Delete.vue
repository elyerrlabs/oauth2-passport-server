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
        <!-- Delete Button -->
        <button
            @click="open"
            class="delete-btn group relative inline-flex items-center justify-center bg-transparent text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500 font-medium py-2.5 px-4 rounded-lg border border-gray-300 dark:border-gray-600 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:focus:ring-offset-gray-900"
        >
            <div class="flex items-center space-x-2">
                <svg
                    class="w-4 h-4 transform group-hover:scale-110 transition-transform duration-200"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                </svg>
                <span class="text-sm font-medium"> {{ __("Remove") }} </span>
            </div>
            <!-- Subtle background on hover -->
            <div
                class="absolute inset-0 rounded-lg bg-red-50 dark:bg-red-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-200 -z-10"
            ></div>
        </button>

        <!-- Delete Confirmation Modal -->
        <v-modal v-model="dialog" panel-class="w-full max-w-md">
            <template #body>
                <!-- Warning Icon -->
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-8 h-8 text-red-600 dark:text-red-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2"
                >
                    {{ __("Delete OAuth Client") }}
                </h3>

                <!-- Description -->
                <p
                    class="text-gray-600 dark:text-gray-400 text-sm text-center mb-6"
                >
                    {{
                        __(
                            "Are you sure you want to delete this OAuth client? This action cannot be undone."
                        )
                    }}
                </p>

                <!-- Client Info -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-blue-600 dark:text-blue-400"
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
                        </div>
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ item.name }}
                            </h4>
                            <p
                                class="text-xs text-gray-500 dark:text-gray-400 font-mono"
                            >
                                {{ item.id }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Input -->
                <div class="mb-6">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __('Type "DELETE" to confirm:') }}
                    </label>
                    <input
                        v-model="confirmationText"
                        type="text"
                        placeholder="DELETE"
                        :class="[
                            'w-full px-3 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400',
                            confirmationText === 'DELETE'
                                ? 'border-red-300 dark:border-red-500 focus:ring-red-500 focus:border-red-500'
                                : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500',
                        ]"
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "This action is permanent and cannot be reversed."
                            )
                        }}
                    </p>
                </div>

                <!-- Warning Message -->
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 mb-6"
                >
                    <div class="flex items-start space-x-2">
                        <svg
                            class="w-4 h-4 text-red-500 dark:text-red-400 mt-0.5 flex-shrink-0"
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
                        <p class="text-sm text-red-700 dark:text-red-300">
                            {{
                                __(
                                    "All access tokens associated with this client will be immediately revoked."
                                )
                            }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <button
                        @click="close"
                        class="cancel-btn px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
                    >
                        {{ __("Cancel") }}
                    </button>
                    <button
                        @click="destroy"
                        :disabled="loading || confirmationText !== 'DELETE'"
                        :class="[
                            'delete-confirm-btn px-4 py-2.5 text-sm font-medium text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center space-x-2',
                            loading || confirmationText !== 'DELETE'
                                ? 'bg-red-400 dark:bg-red-500'
                                : 'bg-red-600 dark:bg-red-600 hover:bg-red-700 dark:hover:bg-red-700',
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
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                        </svg>
                        <span>{{
                            loading ? __("Deleting...") : __("Delete Client")
                        }}</span>
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    emits: ["deleted"],
    components: {
        VModal,
    },

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
            loading: false,
            confirmationText: "",
        };
    },

    methods: {
        open() {
            this.confirmationText = "";
            this.dialog = true;
        },

        close() {
            this.dialog = false;
            this.loading = false;
            this.confirmationText = "";
        },

        async destroy() {
            if (this.confirmationText !== "DELETE") return;

            this.loading = true;

            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.close();

                    this.$notify.success({
                        title: this.__("Deleted!"),
                        message: this.__("OAuth client deleted successfully."),
                        timeout: 3000,
                    });
                }
            } catch (e) {
                this.$notify.error({
                    title: this.__("Error"),
                    message:
                        e?.response?.data?.message ||
                        this.__("Unexpected error occurred"),
                    timeout: 5000,
                });
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
