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
        <v-button
            @click="open"
            round
            size="md"
            variant="outline"
            :icon="
                item.disabled
                    ? 'mdi mdi-check text-lg'
                    : 'mdi mdi-close text-lg'
            "
            :aria-label="
                item.disabled ? __('Enable this user') : __('Disable this user')
            "
            :title="
                item.disabled ? __('Enable this user') : __('Disable this user')
            "
        />

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
                                              "This user account will be activated and granted access to the system.",
                                          )
                                        : __(
                                              "This user account will be disabled and access will be temporarily restricted.",
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
                                        "The user will not be able to log in until the account is re-enabled.",
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
                    <v-button
                        @click="dialog = false"
                        variant="light"
                        left-icon="mdi mdi-close"
                    >
                        {{ __("Cancel") }}
                    </v-button>
                    <v-button
                        @click="action(item)"
                        :disabled="loading"
                        :loading="loading"
                        :variant="item.disabled ? 'success' : 'danger'"
                        :left-icon="
                            item.disabled ? 'mdi mdi-check' : 'mdi mdi-delete'
                        "
                    >
                        {{
                            item.disabled
                                ? __("Enable User")
                                : __("Disable User")
                        }}
                    </v-button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref } from "vue";
import VModal from "@/components/VModal.vue";
import VButton from "@/components/VButton.vue";
import { useForm } from "@inertiajs/vue3";

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

const disable = async () => {
    loading.value = true;

    form.delete(props.item.links.disabled, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("User disabled successfully"));
            emit("updated", true);
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
            dialog.value = false;
        },
    });
};

const enable = async () => {
    loading.value = true;

    form.put(props.item.links.enabled, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("User enabled successfully"));
            emit("updated", true);
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
            dialog.value = false;
        },
    });
};
</script>
