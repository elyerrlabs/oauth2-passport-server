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
    <button
        @click="openDialog"
        class="bg-transparent border border-blue-600 text-blue-600 rounded-full p-2 hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        :title="__('Add access scopes')"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
            />
            <path
                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
            />
        </svg>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Manage Access Scopes')"
        panel-class="w-full lg:7xl"
    >
        <template #body>
            <!-- Header -->
            <div
                class="bg-blue-600 text-white rounded-t-lg -mx-6 -mt-6 px-6 py-4"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg mt-1">
                            {{ __("Add permissions for:") }}
                            <span class="font-bold"
                                >{{ item.name }} {{ item.last_name }}</span
                            >
                        </div>
                        <div class="text-sm opacity-90 mt-1">
                            {{ item.email }}
                        </div>
                    </div>
                    <button
                        @click="dialog = false"
                        class="text-white hover:bg-blue-700 rounded-full p-2 transition-colors focus:outline-none focus:ring-2 focus:ring-white"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="mt-6">
                <!-- User Summary -->
                <div
                    class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200"
                >
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-lg font-bold mr-4"
                        >
                            {{
                                item.name
                                    ? item.name.charAt(0).toUpperCase()
                                    : "U"
                            }}
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-blue-600">
                                {{ item.name }} {{ item.last_name }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ item.email }}
                            </div>
                            <div class="mt-1">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                        item.disabled
                                            ? 'bg-orange-100 text-orange-800'
                                            : 'bg-green-100 text-green-800',
                                    ]"
                                >
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            v-if="!item.disabled"
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
                                    {{
                                        item.disabled
                                            ? __("Inactive")
                                            : __("Active")
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scopes Component -->
                <div class="scopes-container">
                    <v-scopes
                        :default_roles="user_roles"
                        @checked="checkedRoles"
                        :loading="loading"
                    />
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200"
            >
                <button
                    @click="dialog = false"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
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
                    @click="add"
                    :disabled="loading"
                    class="px-4 py-2 text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
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
                            fill-rule="evenodd"
                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ __("Save Permissions") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VScopes from "@/components/VScopes.vue";
export default {
    components: {
        VModal,
        VScopes,
    },
    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            user_roles: [],
            dialog: false,
            loading: false,
            form: {
                scopes: [],
                end_date: "",
            },
            errors: {},
        };
    },

    methods: {
        async openDialog() {
            this.dialog = true;
            this.loading = true;
            await this.userRoles();
            this.loading = false;
        },

        async userRoles() {
            try {
                const res = await this.$server.get(this.item.links.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status == 200) {
                    this.user_roles = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        checkedRoles(event) {
            this.form.scopes = event;
        },

        async add() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.item.links.scopes,
                    this.form
                );

                if (res.status == 201) {
                    $notify.success(__("Permissions updated successfully"));
                    this.errors = {};
                    this.userRoles();
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
