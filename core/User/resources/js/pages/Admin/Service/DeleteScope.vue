<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <!-- Delete Button -->
    <button
        v-if="scope?.gsr_id"
        @click="dialog = true"
        class="relative group rounded-full p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-800"
    >
        <i class="mdi mdi-trash-can-outline text-lg"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-red-600 dark:bg-red-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ __("Revoke Scope") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-red-600 dark:border-t-red-700"
            ></div>
        </div>
    </button>

    <!-- Delete Confirmation Modal -->
    <v-modal
        v-model="dialog"
        :title="__('Revoke Scope')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
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

                <!-- Scope Info Chips -->
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
                        <i class="mdi mdi-alert mt-0.5 shrink-0"></i>
                        <span class="text-sm text-left">
                            {{
                                __(
                                    "Warning: This will permanently remove access permissions. Users with this role will lose access to the associated service."
                                )
                            }}
                        </span>
                    </div>
                </div>

                <!-- Confirmation Input -->
                <div class="space-y-3 pt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __("To confirm, type the GSR ID below:") }}
                    </p>
                    <div class="flex justify-center">
                        <code
                            class="bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-lg font-mono text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 text-sm"
                        >
                            {{ scope.gsr_id }}
                        </code>
                    </div>
                    <input
                        v-model="confirmationText"
                        type="text"
                        class="w-full max-w-md mx-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                        :placeholder="__('Enter GSR ID')"
                        @keyup.enter="handleConfirm"
                    />
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
                    :disabled="!canDelete || loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200',
                        !canDelete || loading
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
import { ref, computed } from "vue";

const emits = defineEmits(["deleted"]);
const props = defineProps({
    scope: {
        required: true,
        type: Object,
    },
});

const loading = ref(false);
const dialog = ref(false);
const confirmationText = ref("");
const form = useForm({});

const canDelete = computed(() => {
    return confirmationText.value === props.scope.gsr_id.toString();
});

const handleConfirm = () => {
    if (canDelete.value && !loading.value) {
        destroy();
    }
};

const destroy = () => {
    if (!canDelete.value || loading.value) {
        return;
    }

    loading.value = true;

    form.delete(props.scope.links.revoke, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("Scope revoked successfully"));
            emits("deleted");
            dialog.value = false;
            confirmationText.value = "";
        },
        onFinish: () => {
            loading.value = false;
        },
        onError: (e) => {
            if (e.message) {
                $notify.error(e.message);
            } else {
                $notify.error(__("An error occurred while revoking the scope"));
            }
        },
    });
};
</script>
