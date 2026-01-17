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
    <div class="flex space-x-1">
        <!-- Status Toggle Button -->
        <button
            @click="open"
            :class="[
                'relative group w-12 h-12 border rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
                item.disabled
                    ? 'border-green-600 dark:border-green-400 text-green-600 dark:text-green-400 hover:bg-green-600 dark:hover:bg-green-500 hover:text-white focus:ring-green-200 dark:focus:ring-green-800'
                    : 'border-red-600 dark:border-red-400 text-red-600 dark:text-red-400 hover:bg-red-600 dark:hover:bg-red-500 hover:text-white focus:ring-red-200 dark:focus:ring-red-800',
            ]"
            :title="
                item.disabled ? __('Enable this user') : __('Disable this user')
            "
        >
            <svg
                class="w-5 h-5 mx-auto"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    v-if="item.disabled"
                    fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"
                />
                <path
                    v-else
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                />
            </svg>

            <!-- Tooltip -->
            <div
                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
                :class="
                    item.disabled
                        ? 'bg-green-600 dark:bg-green-500'
                        : 'bg-red-600 dark:bg-red-500'
                "
            >
                {{ item.disabled ? __("Enable User") : __("Disable User") }}
                <div
                    class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent"
                    :class="
                        item.disabled
                            ? 'border-t-green-600 dark:border-t-green-500'
                            : 'border-t-red-600 dark:border-t-red-500'
                    "
                ></div>
            </div>
        </button>

        <!-- Confirmation Dialog -->
        <v-modal
            v-model="dialog"
            :label="item.disabled ? __('Enable User') : __('Disable User')"
            panel-class="w-full lg:w-3xl"
        >
            <template #body>
                <!-- Body Content -->
                <div class="text-center mt-6">
                    <!-- User Info -->
                    <div class="user-info mb-4">
                        <div
                            class="w-12 h-12 bg-blue-600 dark:bg-blue-500 text-white rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-2"
                        >
                            {{
                                item.name
                                    ? item.name.charAt(0).toUpperCase()
                                    : "U"
                            }}
                        </div>
                        <div
                            class="text-lg font-semibold text-gray-800 dark:text-gray-200"
                        >
                            {{ item.name }} {{ item.last_name }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ item.email }}
                        </div>
                    </div>

                    <!-- Confirmation Message -->
                    <div
                        class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 mb-3"
                    >
                        <div class="flex items-center justify-center">
                            <svg
                                class="w-5 h-5 mr-2"
                                :class="
                                    item.disabled
                                        ? 'text-green-600 dark:text-green-500'
                                        : 'text-red-600 dark:text-red-500'
                                "
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    v-if="item.disabled"
                                    fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"
                                />
                                <path
                                    v-else
                                    fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">
                                {{
                                    item.disabled
                                        ? __(
                                              "This user account will be activated and granted access to the system."
                                          )
                                        : __(
                                              "This user account will be disabled and access will be temporarily restricted."
                                          )
                                }}
                            </span>
                        </div>
                    </div>

                    <!-- Warning Message (only for disable) -->
                    <div
                        v-if="!item.disabled"
                        class="bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 rounded-lg p-4 border border-orange-200 dark:border-orange-800"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="text-sm">
                                {{
                                    __(
                                        "The user will not be able to log in until the account is re-enabled."
                                    )
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-center space-x-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700"
                >
                    <button
                        @click="dialog = false"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
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
                        @click="action(item)"
                        :disabled="loading"
                        :class="[
                            'px-4 py-2 text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors disabled:opacity-50 disabled:cursor-not-allowed',
                            item.disabled
                                ? 'bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-600 focus:ring-green-500 dark:focus:ring-green-400'
                                : 'bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-500 dark:focus:ring-red-400',
                        ]"
                    >
                        <svg
                            v-if="loading"
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 inline mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                v-if="item.disabled"
                                fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                            <path
                                v-else
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        {{
                            item.disabled
                                ? __("Enable User")
                                : __("Disable User")
                        }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import VModal from "@/components/VModal.vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["updated"]);

const dialog = ref(false);
const loading = ref(false);
const form = useForm({});

function open() {
    dialog.value = true;
}

const action = (item) => {
    if (item.disabled) {
        enable();
    } else {
        disable();
    }
};

const disable = () => {
    loading.value = true;

    form.delete(props.item.links.disable, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(__("User disabled successfully"));
            emit("updated", true);
        },
        onFinish: () => {
            loading.value = false;
            dialog.value = false;
        },
    });
};

const enable = () => {
    loading.value = true;

    form.get(props.item.links.enable, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("User enabled successfully"));
            emit("updated", true);
        },
        onFinish() {
            loading.value = false;
            dialog.value = false;
        },
    });
};
</script>
