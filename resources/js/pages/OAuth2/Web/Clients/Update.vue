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
    <!-- Edit Button -->
    <button
        @click="open(item)"
        class="edit-btn group relative inline-flex items-center justify-center bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-600 hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 font-medium py-2.5 px-4 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900"
    >
        <!-- Animated icon container -->
        <div class="relative flex items-center justify-center w-5 h-5 mr-2">
            <svg
                class="w-5 h-5 transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
            </svg>

            <!-- Pulse effect on hover -->
            <div
                class="absolute inset-0 rounded-full bg-blue-100 dark:bg-blue-900/40 opacity-0 group-hover:opacity-100 scale-150 group-hover:scale-100 transition-all duration-300 -z-10"
            ></div>
        </div>

        <!-- Button text -->
        <span class="text-sm font-semibold tracking-wide">
            {{ __("Edit") }}
        </span>

        <!-- Hover border animation -->
        <div
            class="absolute inset-0 rounded-xl border border-transparent group-hover:border-blue-200 dark:group-hover:border-blue-700 transition-colors duration-200 -z-10"
        ></div>
    </button>

    <v-modal v-model="dialog" panel-class="w-full lg:w-5xl">
        <template #body>
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center transition-colors duration-200"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600 dark:text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ __("Update OAuth Client") }}
                        </h3>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    {{
                        __("Modify your OAuth 2.0 client application settings")
                    }}
                </p>
            </div>

            <!-- Form Content -->
            <div class="space-y-6">
                <!-- Client Name -->
                <div class="form-section">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                />
                            </svg>
                            {{ __("Client Name") }}
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                />
                            </svg>
                        </div>
                        <input
                            v-model="form.name"
                            type="text"
                            :placeholder="
                                __(
                                    'Enter a descriptive name for your client application'
                                )
                            "
                            :class="[
                                'w-full pl-10 pr-3 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400',
                                errors.name
                                    ? 'border-red-300 dark:border-red-500'
                                    : 'border-gray-300 dark:border-gray-600',
                            ]"
                        />
                    </div>
                    <v-error :error="errors.name" class="mt-1"></v-error>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __("This will help you identify the client later") }}
                    </p>
                </div>

                <!-- Redirect URI -->
                <div class="form-section">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                />
                            </svg>
                            {{ __("Redirect URI") }}
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                />
                            </svg>
                        </div>
                        <input
                            v-model="form.redirect"
                            type="text"
                            placeholder="https://yourapp.com/oauth/callback"
                            :class="[
                                'w-full pl-10 pr-3 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400',
                                errors.redirect
                                    ? 'border-red-300 dark:border-red-500'
                                    : 'border-gray-300 dark:border-gray-600',
                            ]"
                        />
                    </div>
                    <v-error :error="errors.redirect" class="mt-1"></v-error>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "The URI where users will be redirected after authorization"
                            )
                        }}
                    </p>
                </div>

                <!-- Client ID Display (Read-only) -->
                <div class="form-section" v-if="form.id">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400 mr-2"
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
                            {{ __("Client ID") }}
                        </div>
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
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
                        <input
                            :value="form.id"
                            type="text"
                            readonly
                            :class="[
                                'w-full pl-10 pr-12 py-2.5 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400 cursor-not-allowed transition-colors duration-200',
                            ]"
                        />
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                        >
                            <button
                                @click="copyToClipboard(form.id)"
                                class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 p-1 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :title="__('Copy Client ID')"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __("This identifier cannot be changed") }}
                    </p>
                </div>

                <!-- Additional Information -->
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4"
                >
                    <div class="flex items-start space-x-3">
                        <svg
                            class="w-5 h-5 text-blue-500 dark:text-blue-400 mt-0.5 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <div class="text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-medium mb-1">
                                {{ __("Update Information") }}
                            </p>
                            <p>
                                {{
                                    __(
                                        "Updating client details will not affect existing access tokens. Changes take effect immediately for new authorizations."
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <button
                    @click="close"
                    class="cancel-btn px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="updateClient"
                    :disabled="loading || !isFormValid"
                    :class="[
                        'update-btn px-4 py-2.5 text-sm font-medium text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center space-x-2',
                        loading || !isFormValid
                            ? 'bg-blue-400 dark:bg-blue-500'
                            : 'bg-blue-600 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-700',
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
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                    <span>{{
                        loading ? __("Updating...") : __("Update Client")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VError from "@/components/VError.vue";

export default {
    emits: ["updated"],
    
    components: {
        VModal,
        VError,
    },

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            errors: {
                name: "",
                redirect: "",
            },
            form: {},
            dialog: false,
            loading: false,
        };
    },

    computed: {
        isFormValid() {
            return this.form.name?.trim() && this.form.redirect?.trim();
        },
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
            this.loading = false;
        },

        open(item) {
            this.form = { ...item };
            this.errors = {};
            this.dialog = true;
        },

        async updateClient() {
            if (!this.isFormValid) return;

            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.$emit("updated", true);
                    this.dialog = false;

                    this.$notify.success(
                        this.__("OAuth client updated successfully")
                    );
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$notify.success(this.__("Client ID copied to clipboard"));
            } catch (err) {
                this.$notify.error(this.__("Failed to copy to clipboard"));
            }
        },
    },
};
</script>

<style scoped>
.edit-btn:active {
    transform: translateY(0);
    transition: transform 0.1s ease;
}
</style>
