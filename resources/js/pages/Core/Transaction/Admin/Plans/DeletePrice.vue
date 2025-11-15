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
        class="flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
        :title="__('Delete Price')"
    >
        <i class="mdi mdi-trash-can-outline text-sm"></i>
    </button>

    <!-- Confirm Modal -->
    <v-modal
        v-model="dialog"
        :title="__('Delete Price Configuration')"
        panel-class="w-full lg:w-5xl"
    >
        <template #body>
            <div class="space-y-6">
                <!-- Warning Header -->
                <div class="flex items-start gap-4">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center"
                    >
                        <i
                            class="mdi mdi-currency-usd-off text-2xl text-red-600 dark:text-red-400"
                        ></i>
                    </div>
                    <div class="flex-1">
                        <h3
                            class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2"
                        >
                            {{ __("Delete Price Configuration") }}
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-300 leading-relaxed"
                        >
                            {{
                                __(
                                    "You are about to permanently delete this price configuration. This action will remove the pricing option from the subscription plan."
                                )
                            }}
                        </p>
                    </div>
                </div>

                <!-- Price Details Card -->
                <div
                    class="bg-white dark:bg-gray-800 border-l-4 border-red-500 dark:border-red-400 border border-gray-200 dark:border-gray-600 rounded-xl p-6 shadow-sm"
                >
                    <div
                        class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700"
                    >
                        <i
                            class="mdi mdi-cash-remove text-red-500 dark:text-red-400"
                        ></i>
                        <h4
                            class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wide"
                        >
                            {{ __("Price Configuration Details") }}
                        </h4>
                    </div>

                    <div class="space-y-4">
                        <!-- Amount -->
                        <div
                            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-cash text-green-600 dark:text-green-400 text-sm"
                                    ></i>
                                </div>
                                <div>
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 text-sm"
                                    >
                                        {{ __("Amount") }}
                                    </div>
                                    <div
                                        class="text-2xl font-bold text-red-600 dark:text-red-400"
                                    >
                                        {{ item.amount_format }}
                                        <span
                                            class="text-sm font-normal text-gray-600 dark:text-gray-400 ml-1"
                                        >
                                            {{ item.currency }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Period -->
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    class="mdi mdi-calendar-refresh text-blue-600 dark:text-blue-400 text-sm"
                                ></i>
                            </div>
                            <div class="flex-1">
                                <div
                                    class="font-medium text-gray-700 dark:text-gray-300 text-sm mb-1"
                                >
                                    {{ __("Billing Period") }}
                                </div>
                                <div
                                    class="text-gray-900 dark:text-gray-100 font-semibold capitalize"
                                >
                                    {{ item.billing_period }}
                                </div>
                            </div>
                        </div>

                        <!-- Expiration -->
                        <div
                            v-if="item.expiration"
                            class="flex items-center gap-3"
                        >
                            <div
                                class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    class="mdi mdi-clock-alert text-amber-600 dark:text-amber-400 text-sm"
                                ></i>
                            </div>
                            <div class="flex-1">
                                <div
                                    class="font-medium text-gray-700 dark:text-gray-300 text-sm mb-1"
                                >
                                    {{ __("Expiration Date") }}
                                </div>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ item.expiration }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Critical Warning -->
                <div
                    class="flex items-start gap-4 p-4 rounded-xl border-2"
                    :class="[
                        'bg-red-50 dark:bg-red-900/20',
                        'border-red-200 dark:border-red-700',
                        'text-red-800 dark:text-red-200',
                    ]"
                >
                    <i
                        class="mdi mdi-alert-octagon text-red-500 dark:text-red-400 mt-0.5 shrink-0 text-xl"
                    ></i>
                    <div class="flex-1">
                        <div
                            class="font-bold text-red-900 dark:text-red-100 mb-2"
                        >
                            {{ __("Critical: This Action Cannot Be Undone") }}
                        </div>
                        <p
                            class="text-red-700 dark:text-red-200/90 leading-relaxed"
                        >
                            {{
                                __(
                                    "This price configuration will be permanently deleted from the system. Any active subscriptions using this price may be affected."
                                )
                            }}
                        </p>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700"
                >
                    <button
                        @click="dialog = false"
                        class="px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        :class="[
                            'bg-gray-100 dark:bg-gray-700',
                            'hover:bg-gray-200 dark:hover:bg-gray-600',
                            'text-gray-700 dark:text-gray-200',
                            'border border-gray-300 dark:border-gray-600',
                            'focus:ring-gray-500 dark:focus:ring-gray-400',
                            'hover:scale-105 transform',
                        ]"
                    >
                        <i class="mdi mdi-close text-lg"></i>
                        {{ __("Cancel") }}
                    </button>

                    <button
                        @click="destroy"
                        :disabled="loading"
                        class="px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        :class="[
                            loading
                                ? 'bg-red-400 dark:bg-red-500 cursor-not-allowed'
                                : 'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600',
                            'text-white dark:text-gray-100',
                            'border border-red-600 dark:border-red-600',
                            'focus:ring-red-500 dark:focus:ring-red-400',
                            loading ? '' : 'hover:scale-105 transform',
                        ]"
                    >
                        <i
                            v-if="loading"
                            class="mdi mdi-loading mdi-spin text-lg"
                        ></i>
                        <i v-else class="mdi mdi-trash-can-outline text-lg"></i>
                        {{ loading ? __("Deleting...") : __("Delete Price") }}
                    </button>
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const emits = defineEmits(["deleted"]);
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});
const dialog = ref(false);
const loading = ref(false);

const destroy = async () => {
    if (loading.value) return;

    loading.value = true;
    const form = useForm();

    form.delete(props.item.links.destroy, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            dialog.value = false;
            emits("deleted", props.item.id);
            $notify.success(__("Price configuration deleted successfully"));
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>

<style scoped>
/* Smooth transitions */
button {
    transition: all 0.2s ease-in-out;
}

/* Loading animation */
.mdi-loading {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
