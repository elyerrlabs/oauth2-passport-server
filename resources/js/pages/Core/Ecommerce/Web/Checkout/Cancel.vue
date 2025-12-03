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
        <button
            @click="toggle"
            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center justify-center"
            :disabled="loading"
            :class="{ 'opacity-50 cursor-not-allowed': loading }"
        >
            <i class="fas fa-ban mr-2"></i>
            {{ __("Cancel Refund") }}
            <i v-if="loading" class="fas fa-spinner fa-spin ml-2"></i>
        </button>

        <v-modal
            :title="__('Cancel Refund Request')"
            v-model="dialog"
            panel-class="w-full lg:w-5xl"
            :loading="loading"
        >
            <template #body>
                <div class="p-6">
                    <!-- Warning Icon -->
                    <div class="text-center mb-6">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full mb-4"
                        >
                            <i
                                class="fas fa-exclamation-triangle text-2xl text-red-600 dark:text-red-400"
                            ></i>
                        </div>

                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Cancel Refund Request") }}
                        </h3>

                        <p
                            class="text-gray-600 dark:text-gray-400 text-sm mb-4"
                        >
                            {{
                                __(
                                    "Are you sure you want to cancel this refund request? This action cannot be undone."
                                )
                            }}
                        </p>
                    </div>

                    <!-- Refund Details -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6"
                    >
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Order") }}:</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    #{{ item.code }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Transaction") }}:</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-white font-mono"
                                >
                                    {{ item.transaction_code }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Refund Amount") }}:</span
                                >
                                <span
                                    class="font-bold text-red-600 dark:text-red-400"
                                >
                                    {{
                                        formatMoney(
                                            item.transaction?.refund?.amount
                                        )
                                    }}
                                    {{ item.transaction?.refund?.currency }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Current Status") }}:</span
                                >
                                <span
                                    class="font-medium px-2 py-1 rounded text-xs"
                                    :class="
                                        getStatusClass(
                                            item.transaction?.refund?.status
                                        )
                                    "
                                >
                                    {{
                                        formatRefundStatus(
                                            item.transaction?.refund?.status
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6"
                    >
                        <div class="flex items-start">
                            <i
                                class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5 mr-2"
                            ></i>
                            <div
                                class="text-sm text-yellow-800 dark:text-yellow-300"
                            >
                                <p class="font-medium mb-1">
                                    {{ __("Important") }}
                                </p>
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>
                                        {{
                                            __(
                                                "Once cancelled, you cannot reactivate this refund request"
                                            )
                                        }}
                                    </li>
                                    <li>
                                        {{
                                            __(
                                                "You can submit a new refund request if needed"
                                            )
                                        }}
                                    </li>
                                    <li>
                                        {{
                                            __(
                                                "If the refund is already processing, cancellation may not be possible"
                                            )
                                        }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            @click="confirmCancel"
                            :disabled="loading"
                            class="px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center justify-center flex-1"
                            :class="{
                                'opacity-50 cursor-not-allowed': loading,
                            }"
                        >
                            <i class="fas fa-ban mr-2"></i>
                            {{ __("Yes, Cancel Refund") }}
                            <i
                                v-if="loading"
                                class="fas fa-spinner fa-spin ml-2"
                            ></i>
                        </button>

                        <button
                            @click="toggle"
                            :disabled="loading"
                            class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center justify-center flex-1"
                        >
                            <i class="fas fa-times mr-2"></i>
                            {{ __("No, Keep Request") }}
                        </button>
                    </div>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import VModal from "@/components/VModal.vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const emits = defineEmits(["canceled"]);

const dialog = ref(false);
const loading = ref(false);
const reason = ref("");
const errors = ref({});

const toggle = () => {
    dialog.value = !dialog.value;
};

const formatMoney = (cents) => {
    if (!cents) return "0.00";
    return (cents / 100).toFixed(2);
};

const formatRefundStatus = (status) => {
    const statusMap = {
        pending: __("Pending"),
        processing: __("Processing"),
        under_review: __("Under Review"),
        approved: __("Approved"),
        waiting_for_return: __("Waiting for Return"),
        refunding: __("Refunding"),
        completed: __("Completed"),
        rejected: __("Rejected"),
        canceled: __("Canceled"),
    };
    return statusMap[status] || status;
};

const getStatusClass = (status) => {
    const classMap = {
        pending:
            "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300",
        processing:
            "bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300",
        under_review:
            "bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300",
        approved:
            "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300",
        waiting_for_return:
            "bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300",
        refunding:
            "bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300",
        completed:
            "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300",
        rejected:
            "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300",
        canceled:
            "bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300",
    };
    return (
        classMap[status] ||
        "bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300"
    );
};

const confirmCancel = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const res = await $server.put(
            props.item?.transaction?.refund?.links?.cancel
        );

        if (res.status == 200) {
            $notify.success(__("Refund request cancelled successfully!"));
            toggle();
            emits("canceled");
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};

// Validate if cancellation is possible
const canCancel = computed(() => {
    const status = props.item?.transaction?.refund?.status;
    // You can cancel from these statuses
    const cancellableStatuses = [
        "pending",
        "processing",
        "under_review",
        "approved",
    ];
    return cancellableStatuses.includes(status);
});

// Optional: Add a watcher to auto-close if status becomes uncancellable
import { watch } from "vue";
watch(
    () => props.item?.transaction?.refund?.status,
    (newStatus) => {
        if (!canCancel.value && dialog.value) {
            toggle();
            $notify.info(
                __("Refund status has changed and can no longer be cancelled")
            );
        }
    }
);
</script>

<style scoped>
/* Optional custom styles */
button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
</style>
