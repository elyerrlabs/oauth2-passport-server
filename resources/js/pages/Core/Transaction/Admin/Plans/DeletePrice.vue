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
    <!-- Delete Price Button -->
    <button
        @click="dialog = true"
        class="flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110"
        :title="__('Delete Price')"
    >
        <i class="mdi mdi-close text-sm"></i>
    </button>

    <!-- Confirm Modal -->
    <v-modal v-model="dialog" :title="__('Delete Price')">
        <template #body>
            <div class="flex items-center gap-3 text-red-600">
                <i class="mdi mdi-currency-usd-off text-2xl"></i>
                <span class="text-xl font-bold">{{ __("Delete Price") }}</span>
            </div>
            <div class="space-y-5">
                <!-- Warning Message -->
                <div class="flex items-start gap-4">
                    <i
                        class="mdi mdi-alert text-3xl text-red-500 mt-1 flex-shrink-0"
                    ></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ __("Confirm Deletion") }}
                        </h3>
                        <p class="text-gray-600">
                            {{
                                __(
                                    "You are about to delete the following price option. This action cannot be undone."
                                )
                            }}
                        </p>
                    </div>
                </div>

                <!-- Price Details -->
                <div
                    class="bg-gray-50 border-l-4 border-red-500 border rounded-xl p-4"
                >
                    <h4
                        class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide"
                    >
                        {{ __("Price Details") }}
                    </h4>

                    <div class="space-y-3">
                        <!-- Amount -->
                        <div class="flex items-center gap-3">
                            <i class="mdi mdi-cash text-gray-500"></i>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-700">
                                    {{ __("Amount:") }}
                                </span>
                                <span
                                    class="ml-2 text-red-600 font-bold text-lg"
                                >
                                    {{ item.amount_format }}
                                </span>
                                <span class="ml-1 text-gray-600 text-sm"
                                    >({{ item.currency }})</span
                                >
                            </div>
                        </div>

                        <!-- Billing Period -->
                        <div class="flex items-center gap-3">
                            <i
                                class="mdi mdi-calendar-refresh text-gray-500"
                            ></i>
                            <div>
                                <span class="font-medium text-gray-700">
                                    {{ __("Billing Period:") }}
                                </span>
                                <span class="ml-2 text-gray-900 capitalize">{{
                                    item.billing_period
                                }}</span>
                            </div>
                        </div>

                        <!-- Expiration -->
                        <div
                            v-if="item.expiration"
                            class="flex items-center gap-3"
                        >
                            <i class="mdi mdi-clock-alert text-gray-500"></i>
                            <div>
                                <span class="font-medium text-gray-700">
                                    {{ __("Expiration:") }}
                                </span>
                                <span class="ml-2 text-gray-900">
                                    {{ item.expiration }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Warning -->
                <div
                    class="flex items-center gap-2 text-sm text-gray-600 bg-yellow-50 border border-yellow-200 rounded-lg p-3"
                >
                    <i class="mdi mdi-information text-yellow-500"></i>
                    <span>
                        {{
                            __(
                                "This price will be permanently removed from the system."
                            )
                        }}
                    </span>
                </div>

                <!-- Footer Buttons -->
                <div class="flex justify-end gap-3 pt-4">
                    <button
                        @click="dialog = false"
                        class="px-4 py-2.5 rounded-xl font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 transition-all"
                    >
                        <i class="mdi mdi-close mr-1"></i>{{ __("Cancel") }}
                    </button>

                    <button
                        @click="destroy"
                        class="px-4 py-2.5 rounded-xl font-semibold bg-red-600 hover:bg-red-700 text-white transition-all flex items-center gap-2"
                        :disabled="loading"
                    >
                        <i class="mdi mdi-delete"></i>
                        <span v-if="!loading">{{ __("Delete Price") }}</span>
                        <span v-else>{{ __("Deleting...") }}</span>
                    </button>
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    components: { VModal },
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
        };
    },

    methods: {
        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);
                if (res.status === 200) {
                    this.dialog = false;
                    this.$emit("deleted", true);
                    $notify.success(__("Price deleted successfully."));
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e?.response?.data?.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
