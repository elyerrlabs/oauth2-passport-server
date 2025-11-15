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
    <!-- Delete Button - Consistent with other action buttons -->
    <button
        v-if="scope?.gsr_id"
        @click="dialog = true"
        class="relative group w-12 h-12 gap-2 border border-red-600 dark:border-red-400 px-4 py-2 text-red-600 dark:text-red-400 rounded-full hover:bg-red-600 dark:hover:bg-red-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-800"
    >
        <i class="mdi mdi-trash-can-outline text-lg"></i>

        <!-- Tooltip - Same as other buttons -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-red-600 dark:bg-red-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ __("Revoke Scope") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-red-600 dark:border-t-red-500"
            ></div>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Revoke Scope')"
        panel-class="w-full lg:w-5xl"
    >
        <template #body>
            <!-- Header -->
            <div
                class="bg-red-200 dark:bg-red-500 text-white rounded-t-2xl p-6 text-center"
            >
                <i class="mdi mdi-lock-remove text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">
                    {{ __("Revoke Scope Access") }}
                </h3>
                <p class="text-red-100 dark:text-red-200 mt-1 text-sm">
                    {{ __("This action is permanent") }}
                </p>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-4 text-center">
                <!-- Confirmation Message -->
                <p class="text-gray-700 dark:text-gray-300">
                    {{
                        __("Are you sure you want to revoke the scope for role")
                    }}
                    <span class="font-bold text-blue-600 dark:text-blue-400"
                        >"{{ __(scope.role?.name) }}"</span
                    >?
                </p>

                <!-- Role Info -->
                <div class="flex justify-center gap-2 flex-wrap">
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-sm transition-colors duration-200"
                    >
                        <i class="mdi mdi-identifier"></i>
                        {{ __("Role ID") }}: {{ scope.role?.id }}
                    </span>
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-full text-sm transition-colors duration-200"
                    >
                        <i class="mdi mdi-key-chain"></i>
                        {{ __("GSR ID") }}: {{ scope.gsr_id }}
                    </span>
                </div>

                <!-- Permissions Summary -->
                <div
                    class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors duration-200"
                >
                    <div
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Current Permissions:") }}
                    </div>
                    <div class="flex justify-center gap-2 flex-wrap">
                        <span
                            v-if="scope.api_key"
                            class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-xs transition-colors duration-200"
                        >
                            <i class="mdi mdi-key"></i>
                            {{ __("API Key Access") }}
                        </span>
                        <span
                            v-if="scope.web"
                            class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 rounded-full text-xs transition-colors duration-200"
                        >
                            <i class="mdi mdi-web"></i>
                            {{ __("Web Access") }}
                        </span>
                        <span
                            v-if="scope.active"
                            class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 rounded-full text-xs transition-colors duration-200"
                        >
                            <i class="mdi mdi-check-circle"></i>
                            {{ __("Active") }}
                        </span>
                        <span
                            v-if="scope.public"
                            class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300 rounded-full text-xs transition-colors duration-200"
                        >
                            <i class="mdi mdi-earth"></i>
                            {{ __("Public") }}
                        </span>
                        <span
                            v-if="
                                !scope.api_key &&
                                !scope.web &&
                                !scope.active &&
                                !scope.public
                            "
                            class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full text-xs transition-colors duration-200"
                        >
                            {{ __("No permissions set") }}
                        </span>
                    </div>
                </div>

                <!-- Warning Message -->
                <div
                    class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 rounded-lg transition-colors duration-200"
                >
                    <div class="flex items-start gap-2">
                        <i class="mdi mdi-alert mt-0.5 flex-shrink-0"></i>
                        <span class="text-sm text-left">
                            {{
                                __(
                                    "Warning: This will permanently remove access permissions. Users with this role will lose access to the associated service."
                                )
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-center gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl transition-colors duration-200"
            >
                <button
                    @click="dialog = false"
                    :disabled="loading"
                    class="flex items-center gap-2 px-6 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-600 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-close-circle"></i>
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="destroy"
                    :disabled="loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200',
                        loading
                            ? 'bg-red-400 dark:bg-red-600 cursor-not-allowed'
                            : 'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-200 dark:focus:ring-red-800',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i v-else class="mdi mdi-trash-can-outline"></i>
                    <span>{{
                        loading ? __("Revoking...") : __("Revoke Scope")
                    }}</span>
                </button>
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
    scope: {
        required: true,
        type: Object,
    },
});

const dialog = ref(false);
const loading = ref(false);

const destroy = async () => {
    loading.value = true;
    const form = useForm();

    form.delete(props.scope.links.revoke, {
        onSuccess: () => {
            $notify.success(__("Scope revoked successfully"));
            emits("deleted");
            dialog.value = false;
        },
    });
};
</script>

<style scoped>
.animate-spin {
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
