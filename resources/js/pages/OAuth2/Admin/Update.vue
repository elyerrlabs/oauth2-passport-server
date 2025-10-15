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
        @click="open(item)"
        class="bg-transparent border border-blue-600 text-blue-600 rounded-full p-2 hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        :title="__('Edit Client')"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
            />
        </svg>
    </button>

    <v-modal
        v-model="dialog"
        panel-class="w-full lg:w-3xl"
        :title="__('Update OAuth Client')"
    >
        <template #body>
            <!-- Header -->
            <div class="mb-6">
                <div class="text-gray-600 text-sm">
                    {{
                        __("Modify your OAuth 2.0 client application settings")
                    }}
                </div>
            </div>

            <!-- Form Content -->
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
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="updateClient"
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

                    $notify.error(__("OAuth client updated successfully"));
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                    $notify.error(__("Please fix the form errors"));
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

                $notify.error(__("Client ID copied to clipboard"));
            } catch (err) {
                $notify.error(__("Failed to copy to clipboard"));
            }
        },
    },
};
</script>
