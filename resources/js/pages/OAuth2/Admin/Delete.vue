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
        @click="open"
        class="delete-btn group inline-flex items-center justify-center bg-transparent border border-red-600 dark:border-red-500 text-red-600 dark:text-red-500 rounded-full p-2 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 hover:shadow-md transform hover:-translate-y-0.5"
        :title="__('Delete Client')"
        :disabled="loading"
    >
        <svg
            class="w-5 h-5 transform group-hover:scale-110 transition-transform duration-200"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path
                fill-rule="evenodd"
                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
            />
        </svg>
    </button>

    <!-- Delete Confirmation Modal -->
    <v-modal
        v-model="dialog"
        :title="__('Delete OAuth Client')"
        panel-class="w-full max-w-md"
    >
        <template #body>
            <!-- Warning Icon -->
            <div class="flex justify-center mb-4">
                <div
                    class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-8 h-8 text-red-600 dark:text-red-500"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h3
                class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2"
            >
                {{ __("Delete OAuth Client?") }}
            </h3>

            <!-- Description -->
            <p
                class="text-gray-600 dark:text-gray-400 text-sm text-center mb-6"
            >
                {{
                    __(
                        "Are you sure you want to permanently delete this OAuth client?"
                    )
                }}
            </p>

            <!-- Client Information -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-3">
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

            <!-- Warning Alert -->
            <div
                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6"
            >
                <div class="flex items-start space-x-3">
                    <svg
                        class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <div class="text-red-800 dark:text-red-300">
                        <p class="font-medium text-sm">
                            {{ __("This action cannot be undone") }}
                        </p>
                        <p class="text-sm mt-1">
                            {{
                                __(
                                    "All applications using this client will immediately lose access. Any active sessions will be terminated."
                                )
                            }}
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
                    {{ __("This action is permanent and cannot be reversed.") }}
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
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>{{
                        loading ? __("Deleting...") : __("Delete Client")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    components: {
        VModal,
    },
    emits: ["deleted"],

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

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.close();

                    this.$notify.success({
                        title: this.__("Deleted!"),
                        message: this.__("OAuth client deleted successfully"),
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
                    "Client not found. It may have been already deleted."
                );
            }
            if (error?.response?.status === 403) {
                return this.__(
                    "You don't have permission to delete this client."
                );
            }
            return this.__("Failed to delete client. Please try again.");
        },
    },
};
</script>

<style scoped>
.delete-btn:active {
    transform: translateY(0);
    transition: transform 0.1s ease;
}
</style>
