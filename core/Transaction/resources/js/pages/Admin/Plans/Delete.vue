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
            @click="dialog = true"
            class="group relative flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 text-white shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
        >
            <i
                class="mdi mdi-trash-can-outline text-lg transition-transform duration-300 group-hover:rotate-12"
            ></i>

            <!-- Tooltip -->
            <span
                class="absolute bottom-14 px-2.5 py-1 text-xs rounded-md bg-gray-800 dark:bg-gray-700 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap shadow-lg"
            >
                {{ __("Delete Plan") }}
            </span>
        </button>

        <!-- Confirm Modal -->
        <VModal
            v-model="dialog"
            :title="__('Delete Subscription Plan')"
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
                                class="mdi mdi-alert-circle-outline text-2xl text-red-600 dark:text-red-400"
                            ></i>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2"
                            >
                                {{ __("Delete Subscription Plan") }}
                            </h3>
                            <p
                                class="text-gray-600 dark:text-gray-300 leading-relaxed"
                            >
                                {{
                                    __(
                                        "You are about to permanently delete the plan"
                                    )
                                }}
                                <strong
                                    class="text-red-600 dark:text-red-400 font-semibold"
                                    >"{{ item.name }}"</strong
                                >.
                                {{
                                    __(
                                        "This action cannot be undone and will affect all associated data."
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Plan Details Card -->
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl p-6 shadow-sm"
                    >
                        <div
                            class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700"
                        >
                            <i
                                class="mdi mdi-information text-gray-500 dark:text-gray-400"
                            ></i>
                            <h4
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wide"
                            >
                                {{ __("Plan Information") }}
                            </h4>
                        </div>

                        <div class="space-y-4">
                            <!-- Name -->
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-tag text-blue-600 dark:text-blue-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 text-sm mb-1"
                                    >
                                        {{ __("Plan Name") }}
                                    </div>
                                    <div
                                        class="text-gray-900 dark:text-gray-100 font-semibold"
                                    >
                                        {{ item.name }}
                                    </div>
                                    <div
                                        v-if="item.slug"
                                        class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-1"
                                    >
                                        {{ item.slug }}
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div
                                v-if="
                                    item.description &&
                                    item.description !== '<p></p>'
                                "
                                class="flex items-start gap-3"
                            >
                                <div
                                    class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-text-short text-indigo-600 dark:text-indigo-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 text-sm mb-2"
                                    >
                                        {{ __("Description") }}
                                    </div>
                                    <div
                                        class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-200 dark:border-gray-600"
                                    >
                                        <div
                                            v-html="
                                                truncateHtml(
                                                    item.description,
                                                    200
                                                )
                                            "
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-check-circle text-gray-600 dark:text-gray-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 text-sm mb-1"
                                    >
                                        {{ __("Status") }}
                                    </div>
                                    <span
                                        :class="[
                                            'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border',
                                            item.active
                                                ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800'
                                                : 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
                                        ]"
                                    >
                                        <i
                                            :class="[
                                                'mdi mr-1.5 text-base',
                                                item.active
                                                    ? 'mdi-check-circle'
                                                    : 'mdi-close-circle',
                                            ]"
                                        ></i>
                                        {{
                                            item.active
                                                ? __("Active")
                                                : __("Inactive")
                                        }}
                                    </span>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-gray-100 dark:border-gray-700"
                            >
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <i class="mdi mdi-cash-multiple"></i>
                                    <span
                                        >{{ __("Prices:") }}
                                        <strong
                                            class="text-gray-800 dark:text-gray-200"
                                            >{{
                                                item.prices?.length || 0
                                            }}</strong
                                        ></span
                                    >
                                </div>
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <i class="mdi mdi-key-chain"></i>
                                    <span
                                        >{{ __("Scopes:") }}
                                        <strong
                                            class="text-gray-800 dark:text-gray-200"
                                            >{{
                                                item.scopes?.length || 0
                                            }}</strong
                                        ></span
                                    >
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
                                {{ __("Critical: Permanent Deletion") }}
                            </div>
                            <p
                                class="text-red-700 dark:text-red-200/90 leading-relaxed"
                            >
                                {{
                                    __(
                                        "This will permanently delete the plan, all associated pricing configurations, and access scopes. This action cannot be undone and may affect existing subscriptions."
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
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
                            <i
                                v-else
                                class="mdi mdi-trash-can-outline text-lg"
                            ></i>
                            {{
                                loading ? __("Deleting...") : __("Delete Plan")
                            }}
                        </button>
                    </div>
                </div>
            </template>
        </VModal>
    </div>
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
        onSuccess: () => {
            dialog.value = false;
            emits("deleted", props.item.id);
            loading.value = false;
            this.$notify.success({
                title: __("Success"),
                message: __("Plan was deleted successfully"),
            });
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const truncateHtml = (html, length) => {
    if (!html) return "";
    const text = html.replace(/<[^>]*>/g, "");
    return text.length > length ? text.substring(0, length) + "..." : text;
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
