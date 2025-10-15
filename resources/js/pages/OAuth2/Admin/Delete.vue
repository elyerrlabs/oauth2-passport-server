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
    <button
        @click="openDialog"
        class="bg-transparent border border-red-600 text-red-600 rounded-full p-2 hover:bg-red-50 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
        :title="__('Delete Client')"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
            />
        </svg>
    </button>

    <!-- Confirmation Dialog -->
    <v-modal
        v-model="dialog"
        :title="__('Delete OAuth Client')"
        panel-class="w-full lg:w-3xl"
    >
        <template #body>
            <!-- Content -->
            <div class="space-y-4">
                <p class="text-gray-700">
                    {{
                        __(
                            "Are you sure you want to permanently delete the OAuth client"
                        )
                    }}
                </p>

                <!-- Client Details -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">
                            {{ __("Client Name") }}
                        </div>
                        <div class="font-bold text-blue-600 flex items-center">
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{ item.name }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">
                            {{ __("Client ID") }}
                        </div>
                        <div
                            class="font-medium text-gray-800 flex items-center"
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
                            {{ item.id }}
                        </div>
                    </div>
                </div>

                <!-- Warning Alert -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
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
                        <span class="font-medium text-red-800">
                            {{ __("This action cannot be undone") }}
                        </span>
                    </div>
                    <div class="text-sm text-red-700">
                        {{
                            __(
                                "All applications using this client will immediately lose access. Any active sessions will be terminated."
                            )
                        }}
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200"
            >
                <button
                    @click="closeDialog"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="destroy"
                    :disabled="loading"
                    class="px-4 py-2 text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
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
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>{{
                        loading ? __("Deleting...") : __("Delete Client")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    components: {
        VModal,
    },
    emits: ["deleted"],

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
        };
    },

    methods: {
        openDialog() {
            this.dialog = true;
        },

        closeDialog() {
            this.dialog = false;
            this.loading = false;
        },

        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.dialog = false;
                    $notify.error(__("Client deleted successfully"));
                }
            } catch (e) {
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
