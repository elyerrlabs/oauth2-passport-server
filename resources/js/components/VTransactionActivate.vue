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
        @click="dialog = true"
        class="inline-flex items-center px-4 py-2 border border-orange-500 text-orange-600 rounded-lg font-medium hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors duration-200"
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
                d="M13 10V3L4 14h7v7l9-11h-7z"
            />
        </svg>
        {{ __("Activate transaction") }}
    </button>

    <v-modal
        v-model="dialog"
        panel-class="w-full lg:w-6xl"
        :title="__('Activate Transaction')"
    >
        <template #body>
            <!-- Content -->
            <div class="p-6">
                <div class="flex items-start mb-4">
                    <svg
                        class="w-6 h-6 text-orange-500 mr-3 mt-0.5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                    <span class="text-gray-900 font-medium leading-relaxed">
                        {{
                            __(
                                "Are you sure you want to activate this transaction?"
                            )
                        }}
                    </span>
                </div>

                <hr class="my-4 border-gray-200" />

                <div v-if="item.description" class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 font-medium mb-1">
                        {{ __("Transaction details:") }}
                    </div>
                    <div class="text-gray-800">
                        {{ item.description }}
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-between items-center px-6 py-4 bg-gray-50 rounded-b-2xl border-t border-gray-200"
            >
                <button
                    @click="dialog = false"
                    class="px-6 py-2 text-gray-700 hover:text-gray-900 font-medium rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="activate"
                    :disabled="disable"
                    class="inline-flex items-center px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
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
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>
                    {{ __("Activate") }}
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
    emits: ["updated"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
            disable: false,
        };
    },

    methods: {
        async activate() {
            this.disable = true;

            try {
                const res = await this.$server.put(this.item.links.activate);

                if (res.status == 200) {
                    this.dialog = false;

                    $notify.success(
                        __("Transaction has been activated successfully")
                    );

                    this.$emit("updated");
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.disable = false;
                this.dialog = false;
            }
        },
    },
};
</script>

<style scoped>
.transform {
    transition: transform 0.2s ease-in-out;
}
</style>
