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
        <!-- Delete Button -->
        <v-button
            @click="open"
            :disabled="loading"
            :title="__('Delete API key') + ' ' + item.name"
            icon="mdi mdi-delete-outline text-md"
            round
            variant="danger"
        />

        <!-- Delete Confirmation Modal -->
        <v-modal v-model="dialog" panel-class="w-full lg:w-5xl">
            <template #body>
                <!-- Warning Icon -->
                <div class="flex justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-8 h-8 text-red-600 dark:text-red-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2"
                >
                    {{ __("Delete API Key?") }}
                </h3>

                <!-- Warning Message -->
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                            />
                        </svg>
                        <div class="text-red-800 dark:text-red-300">
                            <p class="font-medium text-sm">
                                {{ __("This action cannot be undone") }}
                            </p>
                            <p class="text-sm mt-1">
                                {{
                                    __(
                                        "Applications using this key will immediately lose access",
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Key Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6"
                >
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                        {{ __("You are about to delete:") }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-red-600 dark:text-red-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4
                                class="text-sm font-medium text-gray-900 dark:text-white truncate"
                            >
                                {{ item.name }}
                            </h4>
                            <p
                                class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-1"
                            >
                                ID: {{ item.id }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Input -->
                <div class="mb-6">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Type DELETE to confirm:") }}
                    </label>
                    <input
                        v-model="confirmationText"
                        type="text"
                        placeholder="DELETE"
                        :class="[
                            'w-full px-3 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400',
                            confirmationText === 'DELETE'
                                ? 'border-red-300 dark:border-red-500 focus:ring-red-500 focus:border-red-500'
                                : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500',
                        ]"
                        :disabled="loading"
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "This action is permanent and cannot be reversed.",
                            )
                        }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <v-button
                        @click="close"
                        :disabled="loading"
                        :label="__('Cancel')"
                        variant="danger"
                    />
                    <v-button
                        @click="destroy"
                        :disabled="loading || confirmationText !== 'DELETE'"
                        :label="
                            loading ? __('Deleting...') : __('Delete API Key')
                        "
                    />
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

const emits = defineEmits(["deleted"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const form = useForm({});
const dialog = ref(false);
const loading = ref(false);
const confirmationText = ref("");

const open = () => {
    confirmationText.value = "";
    dialog.value = true;
};

const close = () => {
    dialog.value = false;
    loading.value = false;
    confirmationText.value = "";
};

const destroy = async () => {
    if (confirmationText.value !== "DELETE") return;

    loading.value = true;

    form.delete(props.item.links.destroy, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            emits("deleted");
            close();
            $notify.success(__("API key has been permanently deleted"));
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>
