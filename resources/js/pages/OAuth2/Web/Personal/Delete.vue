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
        <button
            @click="open"
            :disabled="loading"
            :aria-label="__('Delete API key') + ' ' + item.name"
            class="delete-btn inline-flex items-center gap-2 px-3 py-2 text-red-600 dark:text-red-500 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 hover:border-red-300 dark:hover:border-red-700 hover:text-red-700 dark:hover:text-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 group"
        >
            <svg
                class="w-4 h-4 transition-transform group-hover:scale-110"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
            </svg>
            <span class="text-sm font-medium">
                <span v-if="!loading">{{ __("Delete") }}</span>
                <span v-else class="flex items-center gap-2">
                    <svg
                        class="animate-spin h-3 w-3"
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
                    {{ __("Deleting...") }}
                </span>
            </span>
        </button>

        <!-- Delete Confirmation Modal -->
        <v-modal v-model="dialog" panel-class="w-full max-w-md">
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
                            class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0"
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
                                        "Applications using this key will immediately lose access"
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
                        {{ __('Type "DELETE" to confirm:') }}
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
                                "This action is permanent and cannot be reversed."
                            )
                        }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <button
                        @click="close"
                        :disabled="loading"
                        class="cancel-btn px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ __("Cancel") }}
                    </button>
                    <button
                        @click="destroy"
                        :disabled="loading || confirmationText !== 'DELETE'"
                        :class="[
                            'delete-confirm-btn px-4 py-2.5 text-sm font-medium text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center space-x-2',
                            loading || confirmationText !== 'DELETE'
                                ? 'bg-red-400 dark:bg-red-500'
                                : 'bg-red-600 dark:bg-red-600 hover:bg-red-700 dark:hover:bg-red-700',
                        ]"
                    >
                        <svg
                            v-if="loading"
                            class="w-4 h-4 animate-spin"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                        </svg>
                        <span>{{
                            loading ? __("Deleting...") : __("Delete API Key")
                        }}</span>
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    emits: ["deleted"],
    components: {
        VModal,
    },

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
            loading: false,
            confirmationText: "",
        };
    },

    methods: {
        open() {
            this.confirmationText = "";
            this.dialog = true;
        },

        close() {
            this.dialog = false;
            this.loading = false;
            this.confirmationText = "";
        },

        async destroy() {
            if (this.confirmationText !== "DELETE") return;

            this.loading = true;

            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status === 200) {
                    this.$emit("deleted", true);
                    this.close();

                    this.$notify.success({
                        title: this.__("Deleted!"),
                        message: this.__(
                            "API key has been permanently deleted"
                        ),
                        timeout: 3000,
                    });
                }
            } catch (e) {
                const message = this.getErrorMessage(e);
                this.$notify.error({
                    title: this.__("Delete Failed"),
                    message: message,
                    timeout: 5000,
                });
            } finally {
                this.loading = false;
            }
        },

        getErrorMessage(error) {
            if (error?.response?.data?.message) {
                return error.response.data.message;
            }
            if (error?.response?.status === 404) {
                return this.__(
                    "API key not found. It may have been already deleted."
                );
            }
            if (error?.response?.status === 403) {
                return this.__(
                    "You don't have permission to delete this API key."
                );
            }
            return this.__("Failed to delete API key. Please try again.");
        },
    },
};
</script>

<style scoped>
.delete-btn:active {
    transform: scale(0.98);
    transition: transform 0.1s ease;
}
</style>
