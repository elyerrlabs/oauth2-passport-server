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
        @click="open"
        class="bg-transparent border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center space-x-2"
        :title="__('Create a personal access client for API authentication')"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                clip-rule="evenodd"
            />
        </svg>
        <span>{{ __("Create Personal Access Client") }}</span>
    </button>

    <!-- Dialog -->
    <v-modal
        v-model="dialog"
        :title="__('Create Personal Access Client')"
        panel-class="w-full lg:w-3xl"
    >
        <template #body>
            <!-- Header -->
            <div
                class="bg-blue-600 text-white rounded-t-lg -mx-6 -mt-6 px-6 py-4"
            >
                <div class="text-center">
                    <div class="text-sm opacity-90 mt-1">
                        {{
                            __(
                                "Personal access clients allow your applications to authenticate with the API using generated tokens."
                            )
                        }}
                    </div>
                </div>
            </div>

            <!-- Client Name Input -->
            <div class="m-4 p-4">
                <v-input
                    :label="__('Client Name')"
                    v-model="form.name"
                    :error="errors.name"
                />
            </div>

            <!-- Security Notice -->
            <div
                class="bg-green-50 text-green-700 rounded-lg p-4 border border-green-200"
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

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200"
            >
                <button
                    @click="dialog = false"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                >
                    <svg
                        class="w-4 h-4 inline mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="createPersonalAccessClient"
                    :disabled="loading"
                    class="px-4 py-2 text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                >
                    <svg
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
                    <span>{{ __("Create Client") }}</span>
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
        VInput,
        VSwitch,
    },
    emits: ["created"],

    data() {
        return {
            dialog: false,
            loading: false,
            form: {
                name: null,
            },
            errors: {},
        };
    },

    methods: {
        open() {
            this.form.name = null;
            this.errors = {};
            this.dialog = true;
        },

        async createPersonalAccessClient() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.routes.personal,
                    this.form
                );

                if (res.status == 201) {
                    // Replace with your notification system
                    $notify.success(
                        __("Personal access client created successfully")
                    );
                    this.$emit("created");
                    this.dialog = false;
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
