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
    <div class="flex space-x-1">
        <!-- View Scopes Button -->
        <button
            @click="openDialog"
            class="bg-transparent border border-blue-600 text-blue-600 rounded-full p-2 hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            :title="__('View assigned scopes')"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                    fill-rule="evenodd"
                    d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                />
            </svg>
        </button>

        <!-- Main Dialog -->
        <v-modal v-model="dialog" panel-class="w-full lg:w-7xl  ">
            <!-- Header -->
            <template #body>
                <div
                    class="bg-blue-600 text-white rounded-t-lg -mx-6 -mt-6 px-6 py-4"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold">
                                {{ __("Assigned Access Scopes") }}
                            </div>
                            <div class="text-lg mt-1">
                                {{ __("For user:") }}
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

                <div class="border-t border-gray-200 my-4"></div>

                <!-- Body -->
                <div class="mt-4">
                    <!-- Loading State -->
                    <div v-if="loading" class="text-center py-12">
                        <svg
                            class="animate-spin h-12 w-12 text-blue-600 mx-auto"
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
                        <div class="text-xl text-blue-600 mt-4 font-semibold">
                            {{ __("Loading assigned scopes...") }}
                        </div>
                        <div class="text-gray-600 mt-2">
                            {{
                                __(
                                    "Please wait while we fetch the user's permissions"
                                )
                            }}
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else-if="!loading && user_roles.length === 0"
                        class="text-center py-12"
                    >
                        <svg
                            class="w-16 h-16 text-gray-300 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="text-xl text-gray-500 mt-4">
                            {{ __("No scopes assigned") }}
                        </div>
                        <div class="text-gray-400 mt-2">
                            {{
                                __(
                                    "This user doesn't have any access permissions yet"
                                )
                            }}
                        </div>
                    </div>

                    <!-- Scopes Content -->
                    <div v-else class="space-y-8">
                        <div
                            v-for="[groupName, services] in groupedRoles"
                            :key="groupName"
                            class="group-section"
                        >
                            <!-- Group Header -->
                            <div
                                class="bg-blue-50 text-blue-700 px-4 py-3 rounded-lg border border-blue-200"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-5 h-5 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                                        />
                                    </svg>
                                    <span class="text-lg font-bold">{{
                                        groupName
                                    }}</span>
                                </div>
                            </div>

                            <!-- Services -->
                            <div
                                v-for="[serviceName, roles] in Object.entries(
                                    services
                                )"
                                :key="serviceName"
                                class="service-section mt-4 ml-4"
                            >
                                <!-- Service Header -->
                                <div class="bg-gray-100 px-3 py-2 rounded-lg">
                                    <div class="flex items-center">
                                        <svg
                                            class="w-4 h-4 mr-2 text-gray-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <span
                                            class="font-medium text-gray-700"
                                            >{{ serviceName }}</span
                                        >
                                    </div>
                                </div>

                                <!-- Roles Grid -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3"
                                >
                                    <div
                                        v-for="(item, index) in roles"
                                        :key="index"
                                        class="role-card"
                                    >
                                        <div
                                            class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow p-4"
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div class="flex-grow">
                                                    <div
                                                        class="font-bold text-blue-600 flex items-center"
                                                    >
                                                        <svg
                                                            class="w-4 h-4 mr-1"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                                clip-rule="evenodd"
                                                            />
                                                        </svg>
                                                        {{
                                                            item.scope.role.name
                                                        }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-600 mt-1"
                                                    >
                                                        {{
                                                            item.scope.role
                                                                .description ||
                                                            __(
                                                                "No description available"
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <button
                                                        @click="
                                                            confirmAction(item)
                                                        "
                                                        class="text-red-600 hover:bg-red-50 rounded-full p-1 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                                                        :title="
                                                            __(
                                                                'Revoke this permission'
                                                            )
                                                        "
                                                    >
                                                        <svg
                                                            class="w-5 h-5"
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
                                                </div>
                                            </div>

                                            <!-- Permission Badges -->
                                            <div
                                                class="flex flex-wrap gap-2 mt-4"
                                            >
                                                <span
                                                    :class="[
                                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                                        item.scope.api_key
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-800',
                                                    ]"
                                                >
                                                    <svg
                                                        class="w-3 h-3 mr-1"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            v-if="
                                                                item.scope
                                                                    .api_key
                                                            "
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
                                                    {{ __("API Key:") }}
                                                    {{
                                                        item.scope.api_key
                                                            ? __("Yes")
                                                            : __("No")
                                                    }}
                                                </span>
                                                <span
                                                    :class="[
                                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                                        item.scope.active
                                                            ? 'bg-blue-100 text-blue-800'
                                                            : 'bg-gray-100 text-gray-800',
                                                    ]"
                                                >
                                                    <svg
                                                        class="w-3 h-3 mr-1"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            v-if="
                                                                item.scope
                                                                    .active
                                                            "
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
                                                    {{ __("Active:") }}
                                                    {{
                                                        item.scope.active
                                                            ? __("Yes")
                                                            : __("No")
                                                    }}
                                                </span>
                                                <span
                                                    :class="[
                                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                                        item.scope.public
                                                            ? 'bg-orange-100 text-orange-800'
                                                            : 'bg-gray-100 text-gray-800',
                                                    ]"
                                                >
                                                    <svg
                                                        class="w-3 h-3 mr-1"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            v-if="
                                                                item.scope
                                                                    .public
                                                            "
                                                            d="M10 12a2 2 0 100-4 2 2 0 000 4z"
                                                        />
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                            clip-rule="evenodd"
                                                        />
                                                        <path
                                                            v-if="
                                                                !item.scope
                                                                    .public
                                                            "
                                                            fill-rule="evenodd"
                                                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    {{ __("Public:") }}
                                                    {{
                                                        item.scope.public
                                                            ? __("Yes")
                                                            : __("No")
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="flex justify-end mt-8 pt-4 border-t border-gray-200"
                >
                    <button
                        @click="dialog = false"
                        class="px-4 py-2 text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
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
                        {{ __("Close") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VModal from "@/components/VModal.vue";
export default {
    components: {
        VModal,
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
            confirm: false,
            selected_scope: {},
            loading: true,
            revoking: false,
        };
    },

    computed: {
        groupedRoles() {
            const grouped = {};

            for (const item of this.user_roles) {
                const group =
                    item.scope?.service?.group?.name || "Unknown Group";
                const service = item.scope?.service?.name || "Unknown Service";

                if (!grouped[group]) {
                    grouped[group] = {};
                }

                if (!grouped[group][service]) {
                    grouped[group][service] = [];
                }

                grouped[group][service].push(item);
            }

            return Object.entries(grouped);
        },
    },

    methods: {
        openDialog() {
            this.dialog = true;
            this.userRoles();
        },

        confirmAction(item) {
            this.selected_scope = item;
            this.confirm = true;
        },

        async userRoles() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.item.links.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status === 200) {
                    this.user_roles = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        async revoke() {
            this.revoking = true;
            try {
                const res = await this.$server.put(
                    this.selected_scope.links.revoke
                );

                if (res.status === 200) {
                    $notify.success(__("Permission revoked successfully"));
                    this.userRoles();
                    this.confirm = false;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.revoking = false;
            }
        },
    },
};
</script>
