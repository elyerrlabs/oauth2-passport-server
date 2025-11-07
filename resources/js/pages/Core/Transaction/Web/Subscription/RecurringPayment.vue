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
        class="w-full mt-2 px-4 py-2 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 border"
        :class="buttonClasses"
    >
        <span class="flex items-center justify-center space-x-2">
            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    v-if="item?.is_recurring"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
                <path
                    v-else
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                />
            </svg>
            <span>{{ __(buttonLabel) }}</span>
        </span>
    </button>

    <v-modal
        v-model="dialog"
        :title="__(dialogTitle)"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <!-- Content -->
            <div class="p-6">
                <p class="text-gray-700">
                    {{ __(dialogMessage) }}
                </p>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-between p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl"
            >
                <button
                    @click="recurringPayment"
                    class="px-6 py-2 border border-green-600 text-green-600 rounded-lg font-medium hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                >
                    {{ __("Accept") }}
                </button>
                <button
                    @click="dialog = false"
                    class="px-6 py-2 border border-red-600 text-red-600 rounded-lg font-medium hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                >
                    {{ __("Cancel") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { ref, computed } from "vue";

const emits = defineEmits(["success"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
        default: () => ({}),
    },
});

const dialog = ref(false);

const buttonLabel = computed(() => {
    return props.item?.is_recurring
        ? "Cancel recurring payment"
        : "Enable recurring payment";
});

const dialogTitle = computed(() => {
    return props.item?.is_recurring
        ? "Cancel recurring payment"
        : "Enable recurring payment";
});

const dialogMessage = computed(() => {
    return props.item?.is_recurring
        ? "Are you sure you want to cancel the recurring payment?"
        : "Do you want to enable recurring payment for this item?";
});

const buttonClasses = computed(() => {
    return props.item?.is_recurring
        ? "border-red-600 text-red-600 hover:bg-red-50 focus:ring-red-500"
        : "border-green-600 text-green-600 hover:bg-green-50 focus:ring-green-500";
});

const recurringPayment = async () => {
    try {
        const res = await $server.put(props.item.links.recurring);

        if (res.status === 200) {
            $notify.success(res.data.message);
            emits("success");
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        dialog.value = false;
    }
};
</script>
