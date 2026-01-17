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
    <div>
        <button
            @click="dialog = true"
            class="flex items-center justify-center w-8 h-8 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-gray-900 dark:text-gray-900 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
            :title="__('Revoke Scope')"
        >
            <i class="mdi mdi-key-remove text-sm"></i>
        </button>

        <v-modal
            v-model="dialog"
            :title="__('Revoke Access Scope')"
            panel-class="w-full lg:w-5xl"
        >
            <template #body>
                <div class="text-left space-y-6">
                    <!-- Warning Header -->
                    <div class="flex items-start gap-4">
                        <div
                            class="shrink-0 w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-alert text-2xl text-yellow-600 dark:text-yellow-400"
                            ></i>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2"
                            >
                                {{ __("Confirm Scope Revocation") }}
                            </h3>
                            <p
                                class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed"
                            >
                                {{
                                    __(
                                        "You are about to revoke the following access scope. This will remove permissions from the associated plan and may affect users immediately."
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Scope Details Card -->
                    <div
                        class="bg-white dark:bg-gray-800 border-l-4 border-yellow-500 dark:border-yellow-400 border border-gray-200 dark:border-gray-600 rounded-xl p-4 shadow-sm"
                    >
                        <div
                            class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700"
                        >
                            <i
                                class="mdi mdi-key-chain-variant text-yellow-600 dark:text-yellow-400"
                            ></i>
                            <h4
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wide"
                            >
                                {{ __("Scope Details") }}
                            </h4>
                        </div>

                        <div class="space-y-4 text-sm">
                            <!-- Role Information -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-account-key text-blue-600 dark:text-blue-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >
                                        {{ __("Role") }}
                                    </div>
                                    <div
                                        class="text-yellow-700 dark:text-yellow-300 font-semibold"
                                    >
                                        {{ item.role.name }}
                                    </div>
                                    <div
                                        v-if="item.role.slug"
                                        class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-1"
                                    >
                                        {{ item.role.slug }}
                                    </div>
                                </div>
                            </div>

                            <!-- Service Information -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-server text-green-600 dark:text-green-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >
                                        {{ __("Service") }}
                                    </div>
                                    <div
                                        class="text-gray-900 dark:text-gray-100"
                                    >
                                        {{ item.service.name }}
                                    </div>
                                    <div
                                        v-if="item.service.slug"
                                        class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-1"
                                    >
                                        {{ item.service.slug }}
                                    </div>
                                </div>
                            </div>

                            <!-- Group Information -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-account-group text-purple-600 dark:text-purple-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >
                                        {{ __("Group") }}
                                    </div>
                                    <div
                                        class="text-gray-900 dark:text-gray-100"
                                    >
                                        {{ item.service.group.name }}
                                    </div>
                                    <div
                                        v-if="item.service.group.description"
                                        class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                    >
                                        {{ item.service.group.description }}
                                    </div>
                                </div>
                            </div>

                            <!-- Scope ID -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-identifier text-gray-600 dark:text-gray-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >
                                        {{ __("Scope ID") }}
                                    </div>
                                    <div
                                        class="text-xs font-mono text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded px-3 py-2 border border-gray-200 dark:border-gray-600"
                                    >
                                        {{ item.gsr_id }}
                                    </div>
                                </div>
                            </div>

                            <!-- Role Description -->
                            <div
                                v-if="item.role.description"
                                class="flex items-start gap-3"
                            >
                                <div
                                    class="shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-information text-indigo-600 dark:text-indigo-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        {{ __("Description") }}
                                    </div>
                                    <div
                                        class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed bg-white dark:bg-gray-700/50 p-3 rounded-lg border border-gray-200 dark:border-gray-600"
                                    >
                                        {{ item.role.description }}
                                    </div>
                                </div>
                            </div>

                            <!-- Scope Status -->
                            <div class="flex items-center gap-3">
                                <div
                                    class="shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-check-circle text-gray-600 dark:text-gray-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >
                                        {{ __("Status") }}
                                    </div>
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            item.active
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800'
                                                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
                                        ]"
                                    >
                                        <i
                                            :class="[
                                                'mdi mr-1 text-xs',
                                                item.active
                                                    ? 'mdi-check'
                                                    : 'mdi-close',
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
                        </div>
                    </div>

                    <!-- Warning Alert -->
                    <div
                        class="flex items-start gap-4 p-6 rounded-2xl border-2 shadow-sm"
                        :class="[
                            'bg-amber-50/80 dark:bg-amber-900/20',
                            'border-amber-300 dark:border-amber-600/50',
                            'text-amber-900 dark:text-amber-100',
                            'backdrop-blur-sm',
                        ]"
                    >
                        <div class="flex-shrink-0 w-6 h-6 mt-0.5">
                            <i
                                class="mdi mdi-alert-octagon text-xl text-amber-600 dark:text-amber-400"
                            ></i>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div
                                class="font-bold text-lg text-amber-800 dark:text-amber-50"
                            >
                                {{ __("Important: Action Cannot Be Undone") }}
                            </div>
                            <p
                                class="text-amber-700 dark:text-amber-200/90 leading-relaxed text-base font-medium"
                            >
                                {{
                                    __(
                                        "This revocation is permanent and will take effect immediately. Users subscribed to this plan will lose access to the associated permissions."
                                    )
                                }}
                            </p>
                            <div
                                class="flex items-center gap-4 pt-2 text-sm text-amber-600 dark:text-amber-300"
                            >
                                <span class="flex items-center gap-1">
                                    <i class="mdi mdi-clock-fast"></i>
                                    {{ __("Immediate effect") }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="mdi mdi-undo-variant"></i>
                                    {{ __("Cannot be undone") }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="mdi mdi-account-multiple"></i>
                                    {{ __("Affects all users") }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700"
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
                        @click="revoke"
                        :disabled="loading"
                        class="px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        :class="[
                            loading
                                ? 'bg-yellow-400 dark:bg-yellow-500 cursor-not-allowed'
                                : 'bg-yellow-600 dark:bg-yellow-700 hover:bg-yellow-700 dark:hover:bg-yellow-600',
                            'text-white dark:text-gray-900',
                            'border border-yellow-600 dark:border-yellow-600',
                            'focus:ring-yellow-500 dark:focus:ring-yellow-400',
                            loading ? '' : 'hover:scale-105 transform',
                        ]"
                    >
                        <i
                            v-if="loading"
                            class="mdi mdi-loading mdi-spin text-lg"
                        ></i>
                        <i v-else class="mdi mdi-key-remove text-lg"></i>
                        {{ loading ? __("Revoking...") : __("Revoke Scope") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const emits = defineEmits(["revoked"]);
const props = defineProps({
    item: {
        type: Object,
        required: true,
        validator: (value) => {
            return (
                value &&
                value.role &&
                value.service &&
                value.service.group &&
                value.links &&
                value.links.revoke
            );
        },
    },
});

const dialog = ref(false);
const loading = ref(false);

async function revoke() {
    if (loading.value) return;

    loading.value = true;
    const form = useForm();

    form.delete(props.item.links.revoke, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            dialog.value = false;
            emits("revoked", props.item.id);
            $notify.success(__("Scope revoked successfully"));
        },
        onFinish: () => {
            loading.value = false;
        },
    });
}
</script>

<style scoped>
/* Smooth transitions for all interactive elements */
button {
    transition: all 0.2s ease-in-out;
}

/* Focus states for accessibility */
button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}

/* Dark mode specific adjustments */
@media (prefers-color-scheme: dark) {
    .dark\:focus\:ring-offset-gray-800:focus {
        --tw-ring-offset-color: #1f2937;
    }
}

/* Loading state animation */
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

/* Ensure proper contrast in both themes */
.bg-yellow-50 {
    background-color: rgb(254 252 232);
}

.dark .bg-yellow-900\/20 {
    background-color: rgba(120, 53, 15, 0.2);
}

/* Border contrast improvements */
.border-yellow-200 {
    border-color: rgb(254 240 138);
}

.dark .border-yellow-800 {
    border-color: rgb(114 59 19);
}
</style>
