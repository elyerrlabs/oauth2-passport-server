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
    <v-account-layout>
        <!-- Header -->
        <div class="w-full mx-auto px-4 sm:px-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center transition-colors duration-200"
                    >
                        <i
                            class="mdi mdi-application-cog text-blue-600 dark:text-blue-400 text-2xl"
                        ></i>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ __("OAuth Clients") }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{
                                __(
                                    "Manage your OAuth 2.0 clients and applications"
                                )
                            }}
                        </p>
                    </div>
                </div>
                <v-create @created="getClients()" />
            </div>
        </div>

        <!-- Clients Table -->
        <div class="w-full mx-auto px-4 sm:px-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md"
            >
                <!-- Loading -->
                <div
                    v-if="loading"
                    class="p-12 flex justify-center items-center"
                >
                    <div class="text-center">
                        <span
                            class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 dark:border-blue-400 mx-auto mb-4"
                        ></span>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            {{ __("Loading OAuth clients...") }}
                        </p>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="clients.length === 0" class="p-12 text-center">
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors duration-200"
                    >
                        <i
                            class="mdi mdi-application-import text-gray-400 dark:text-gray-500 text-3xl"
                        ></i>
                    </div>
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
                    >
                        {{ __("No OAuth Clients") }}
                    </h3>
                    <p
                        class="text-gray-500 dark:text-gray-400 text-sm mb-6 max-w-md mx-auto"
                    >
                        {{
                            __(
                                "Create your first OAuth client to get started with API integrations and authentication"
                            )
                        }}
                    </p>
                    <v-create @created="getClients()" />
                </div>

                <!-- Table -->

                <!-- Desktop Table -->
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm hidden md:table"
                >
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th
                                v-for="(column, index) in columns"
                                :key="index"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide transition-colors duration-200"
                            >
                                {{ __(column) }}
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <tr
                            v-for="client in clients"
                            :key="client.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group"
                        >
                            <!-- Client -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center transition-colors duration-200 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/30"
                                    >
                                        <i
                                            class="mdi mdi-application text-blue-600 dark:text-blue-400 text-lg"
                                        ></i>
                                    </div>
                                    <div>
                                        <div
                                            class="font-semibold text-gray-900 dark:text-white"
                                        >
                                            {{ client.name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Created -->
                            <td class="px-6 py-4">
                                <div
                                    class="flex items-center text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <i
                                        class="mdi mdi-calendar-clock text-gray-400 dark:text-gray-500 mr-2"
                                    ></i>
                                    <div>
                                        <div>
                                            {{ formatDate(client.created_at) }}
                                        </div>
                                        <div
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                formatTimeAgo(client.created_at)
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Confidential -->
                            <td class="px-6 py-4">
                                <div
                                    class="flex flex-col items-center space-y-2"
                                >
                                    <span
                                        :class="[
                                            'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-200',
                                            client.confidential
                                                ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
                                        ]"
                                    >
                                        <i
                                            :class="[
                                                'mdi mr-1.5',
                                                client.confidential
                                                    ? 'mdi-lock-outline'
                                                    : 'mdi-lock-open-outline',
                                            ]"
                                        ></i>
                                        {{
                                            client.confidential
                                                ? __("Confidential")
                                                : __("Public")
                                        }}
                                    </span>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400 text-center"
                                    >
                                        {{
                                            client.confidential
                                                ? __("With secret")
                                                : __("No secret")
                                        }}
                                    </div>
                                </div>
                            </td>

                            <!-- Grant Types -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    <span
                                        v-for="(grant, index) in getGrantTypes(
                                            client.grant_types
                                        )"
                                        :key="index"
                                        :class="[
                                            'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border transition-colors duration-200',
                                            getGrantColor(grant),
                                        ]"
                                        :title="getGrantDescription(grant)"
                                    >
                                        <i
                                            :class="[
                                                getGrantIcon(grant),
                                                'mdi mr-1.5 text-xs',
                                            ]"
                                        ></i>
                                        {{ formatGrantType(grant) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div
                                    class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                >
                                    <v-update
                                        :item="client"
                                        @updated="getClients"
                                    />
                                    <v-delete
                                        :item="client"
                                        @deleted="getClients"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Mobile stacked version -->
                <div class="md:hidden space-y-4 p-4">
                    <div
                        v-for="client in clients"
                        :key="client.id"
                        class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md"
                    >
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-application text-blue-600 dark:text-blue-400"
                                    ></i>
                                </div>
                                <div>
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ client.name }}
                                    </h3>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 font-mono"
                                    >
                                        {{ client.id }}
                                    </p>
                                </div>
                            </div>
                            <span
                                :class="[
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold',
                                    client.confidential
                                        ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300'
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                                ]"
                            >
                                <i
                                    :class="[
                                        'mdi mr-1',
                                        client.confidential
                                            ? 'mdi-lock'
                                            : 'mdi-lock-open',
                                    ]"
                                ></i>
                                {{
                                    client.confidential
                                        ? __("Confidential")
                                        : __("Public")
                                }}
                            </span>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                            <div>
                                <div
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium mb-1"
                                >
                                    {{ __("Created") }}
                                </div>
                                <div
                                    class="flex items-center text-gray-900 dark:text-gray-100"
                                >
                                    <i
                                        class="mdi mdi-calendar text-gray-400 dark:text-gray-500 mr-1 text-xs"
                                    ></i>
                                    <span class="text-sm">{{
                                        formatDate(client.created_at)
                                    }}</span>
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium mb-1"
                                >
                                    {{ __("Type") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-gray-100"
                                >
                                    {{
                                        client.confidential
                                            ? __("With secret")
                                            : __("No secret")
                                    }}
                                </div>
                            </div>
                        </div>

                        <!-- Grant Types -->
                        <div class="mb-4">
                            <div
                                class="text-gray-500 dark:text-gray-400 text-xs font-medium mb-2"
                            >
                                {{ __("Grant Types") }}
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <span
                                    v-for="(grant, index) in getGrantTypes(
                                        client.grant_types
                                    )"
                                    :key="index"
                                    :class="[
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border',
                                        getGrantColor(grant),
                                    ]"
                                >
                                    <i
                                        :class="[
                                            getGrantIcon(grant),
                                            'mdi mr-1 text-xs',
                                        ]"
                                    ></i>
                                    {{ formatGrantType(grant) }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex justify-end space-x-2 pt-3 border-t border-gray-200 dark:border-gray-600"
                        >
                            <v-update :item="client" @updated="getClients" />
                            <v-delete :item="client" @deleted="getClients" />
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <v-paginate
                    v-if="clients.length"
                    v-model="search.page"
                    :total-pages="pages.total_pages"
                    @change="getClients"
                />
            </div>
        </div>
    </v-account-layout>
</template>

<script>
import VAccountLayout from "@/layouts/VAccountLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    components: { VCreate, VUpdate, VDelete, VAccountLayout, VPaginate },

    data() {
        return {
            clients: [],
            loading: false,
            pages: { total_pages: 1 },
            search: { page: 1, per_page: 15 },
            columns: [
                "Client",
                "Created",
                "Client Type",
                "Grant Types",
                "Actions",
            ],
        };
    },

    created() {
        this.getClients();
    },

    methods: {
        async getClients() {
            this.loading = true;

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.search,
                });

                if (res.status == 200) {
                    const values = res.data;

                    this.clients = values.data;
                    this.pages = values.meta.pagination;
                    this.search.current_page =
                        values.meta.pagination.current_page;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        getGrantTypes(grantTypes) {
            if (!grantTypes) return [];
            if (Array.isArray(grantTypes)) return grantTypes;
            if (typeof grantTypes === "string") return grantTypes.split(",");
            return [];
        },

        formatGrantType(grant) {
            const map = {
                authorization_code: "Auth Code",
                password: "Password",
                client_credentials: "Client Credentials",
                implicit: "Implicit",
                refresh_token: "Refresh Token",
            };
            return map[grant] || grant;
        },

        getGrantIcon(grant) {
            const icons = {
                authorization_code: "mdi-shield-account",
                password: "mdi-form-textbox-password",
                client_credentials: "mdi-account-key",
                implicit: "mdi-flash",
                refresh_token: "mdi-autorenew",
            };
            return `mdi ${icons[grant] || "mdi-cog"}`;
        },

        getGrantColor(grant) {
            const colors = {
                authorization_code:
                    "bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800",
                password:
                    "bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800",
                client_credentials:
                    "bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800",
                implicit:
                    "bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800",
                refresh_token:
                    "bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800",
            };
            return (
                colors[grant] ||
                "bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600"
            );
        },

        getGrantDescription(grant) {
            const descriptions = {
                authorization_code:
                    "Authorization Code Flow - for web applications",
                password:
                    "Resource Owner Password Credentials - for trusted applications",
                client_credentials:
                    "Client Credentials Flow - for machine-to-machine authentication",
                implicit:
                    "Implicit Flow - for single-page applications (legacy)",
                refresh_token:
                    "Refresh Token - for obtaining new access tokens",
            };
            return descriptions[grant] || "OAuth 2.0 Grant Type";
        },

        formatDate(dateString) {
            if (!dateString) return "N/A";
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        formatTimeAgo(dateString) {
            if (!dateString) return "";

            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 1) return "1 day ago";
            if (diffDays < 7) return `${diffDays} days ago`;
            if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
            return `${Math.floor(diffDays / 30)} months ago`;
        },
    },
};
</script>
