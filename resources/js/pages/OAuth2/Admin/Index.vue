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
    <v-admin-layout>
        <!-- Header Section -->
        <div
            class="bg-white dark:bg-gray-800 p-6 shadow-lg rounded-lg transition-colors duration-200"
        >
            <div class="md:flex items-center justify-between mb-6">
                <div>
                    <div
                        class="text-md md:text-lg lg:text-3xl font-bold text-blue-600 dark:text-blue-400"
                    >
                        {{ __("OAuth Clients Management") }}
                    </div>
                    <div class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ __("Manage your application's OAuth 2.0 clients") }}
                    </div>
                </div>

                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <v-personal-client @created="getClients" />
                    <v-create @created="getClients" />
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div
            v-if="clients.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4"
        >
            <!-- Total Clients -->
            <div
                class="bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg border border-blue-200 dark:border-blue-800 transition-colors duration-200 hover:shadow-md"
            >
                <div class="p-4 text-center">
                    <div class="text-xl font-semibold">
                        {{ clients.length }} {{ __("Client")
                        }}{{ clients.length !== 1 ? "s" : "" }}
                    </div>
                    <div class="mt-2">
                        <svg
                            class="w-8 h-8 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Confidential Clients -->
            <div
                class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-lg border border-green-200 dark:border-green-800 transition-colors duration-200 hover:shadow-md"
            >
                <div class="p-4 text-center">
                    <div class="text-xl font-semibold">
                        {{ confidentialClientsCount }} {{ __("Confidential") }}
                    </div>
                    <div class="mt-2">
                        <svg
                            class="w-8 h-8 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Public Clients -->
            <div
                class="bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 rounded-lg border border-orange-200 dark:border-orange-800 transition-colors duration-200 hover:shadow-md"
            >
                <div class="p-4 text-center">
                    <div class="text-xl font-semibold">
                        {{ publicClientsCount }} {{ __("Public") }}
                    </div>
                    <div class="mt-2">
                        <svg
                            class="w-8 h-8 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Grant Types -->
            <div
                class="bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 rounded-lg border border-purple-200 dark:border-purple-800 transition-colors duration-200 hover:shadow-md"
            >
                <div class="p-4 text-center">
                    <div class="text-xl font-semibold">
                        {{ totalGrantTypes }} {{ __("Grant Types") }}
                    </div>
                    <div class="mt-2">
                        <svg
                            class="w-8 h-8 mx-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 016 0v2h1V7a5 5 0 00-5-5z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile/Tablet Cards (sm:1, md:2) -->
        <div class="lg:hidden mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="client in clients"
                    :key="client.id"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm"
                >
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3
                                class="font-semibold text-gray-900 dark:text-white truncate"
                            >
                                {{ client.name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ formatDate(client.created_at) }}
                            </p>
                        </div>
                        <span
                            :class="[
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                client.confidential
                                    ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                    : 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300',
                            ]"
                        >
                            <i
                                :class="[
                                    'mdi mr-1 text-xs',
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
                    </div>

                    <div class="space-y-2 mb-3">
                        <div
                            class="flex items-center text-sm text-gray-600 dark:text-gray-400"
                        >
                            <i
                                class="mdi mdi-account text-gray-400 dark:text-gray-500 mr-2"
                            ></i>
                            <span class="truncate">
                                {{ client.created_by?.email || __("System") }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-1">
                            <span
                                v-for="(grant, index) in formatGrantTypes(
                                    client.grant_types
                                )"
                                :key="index"
                                :class="[
                                    'inline-flex items-center px-2 py-0.5 rounded-full text-xs border',
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

                    <div class="flex justify-between items-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __("OAuth Client") }}
                        </div>
                        <div class="flex space-x-1">
                            <v-update :item="client" @updated="getClients" />
                            <v-delete :item="client" @deleted="getClients" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State for Cards -->
            <div
                v-if="!loading && clients.length === 0"
                class="text-center py-12"
            >
                <div
                    class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <svg
                        class="w-8 h-8 text-gray-400 dark:text-gray-500"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="text-gray-500 dark:text-gray-400 text-lg mt-4">
                    {{ __("No clients available") }}
                </div>
                <div class="text-gray-400 dark:text-gray-500 text-sm mt-2">
                    {{ __("Create your first OAuth client to get started") }}
                </div>
            </div>
        </div>

        <!-- Desktop Table (lg+) -->
        <div class="hidden lg:block">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden mt-4 transition-colors duration-200"
            >
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                >
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th
                                v-for="column in columns"
                                :key="column.name"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200"
                                :class="{
                                    'text-left': column.align === 'left',
                                    'text-center': column.align === 'center',
                                    'text-right': column.align === 'right',
                                }"
                            >
                                {{ column.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <!-- Loading State -->
                        <tr v-if="loading">
                            <td
                                :colspan="columns.length"
                                class="px-6 py-12 text-center"
                            >
                                <div class="flex justify-center items-center">
                                    <svg
                                        class="animate-spin h-8 w-8 text-blue-600 dark:text-blue-400"
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
                                </div>
                            </td>
                        </tr>

                        <!-- Clients Rows -->
                        <tr
                            v-for="client in clients"
                            :key="client.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group"
                        >
                            <!-- Client Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                            class="font-bold text-blue-600 dark:text-blue-400"
                                        >
                                            {{ client.name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="flex flex-col items-center space-y-2"
                                >
                                    <span
                                        :class="[
                                            'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-200',
                                            client.confidential
                                                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800'
                                                : 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border border-orange-200 dark:border-orange-800',
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
                                                ? __("Yes")
                                                : __("No")
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

                            <!-- Created By -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="flex items-center text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <i
                                        class="mdi mdi-account text-gray-400 dark:text-gray-500 mr-2"
                                    ></i>
                                    <div>
                                        <div>
                                            {{
                                                client.created_by?.email ||
                                                __("System")
                                            }}
                                        </div>
                                        <div
                                            v-if="client.created_by?.name"
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{ client.created_by.name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Grant Types -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    <span
                                        v-for="(
                                            grant, index
                                        ) in formatGrantTypes(
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
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                            >
                                <div class="flex justify-end space-x-2">
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

                <!-- Empty State for Table -->
                <div
                    v-if="!loading && clients.length === 0"
                    class="text-center py-12"
                >
                    <div
                        class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-gray-400 dark:text-gray-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="text-gray-500 dark:text-gray-400 text-lg mt-4">
                        {{ __("No clients available") }}
                    </div>
                    <div class="text-gray-400 dark:text-gray-500 text-sm mt-2">
                        {{
                            __("Create your first OAuth client to get started")
                        }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-if="clients.length > 0"
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getClients"
        />
    </v-admin-layout>
</template>

<script>
import VAdminLayout from "@/components/VGeneralLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VPersonalClient from "./PersonalClient.vue";

export default {
    components: {
        VAdminLayout,
        VPaginate,
        VCreate,
        VUpdate,
        VDelete,
        VPersonalClient,
    },

    data() {
        return {
            clients: [],
            loading: false,
            pages: {
                total_pages: 1,
            },
            search: {
                page: 1,
                per_page: 15,
            },

            columns: [
                { name: "name", label: "Client Name", align: "left" },
                { name: "created", label: "Created Date", align: "left" },
                {
                    name: "confidential",
                    label: "Confidential",
                    align: "center",
                },
                { name: "created_by", label: "Created By", align: "left" },
                { name: "grant_types", label: "Grant Types", align: "left" },
                { name: "actions", label: "Actions", align: "right" },
            ],
        };
    },

    computed: {
        confidentialClientsCount() {
            return this.clients.filter((client) => client.confidential).length;
        },
        publicClientsCount() {
            return this.clients.filter((client) => !client.confidential).length;
        },
        totalGrantTypes() {
            return this.clients.reduce((total, client) => {
                const grants = this.formatGrantTypes(client.grant_types);
                return total + grants.length;
            }, 0);
        },
    },

    created() {
        this.getClients();
    },

    methods: {
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

            if (diffDays === 1) return this.__("1 day ago");
            if (diffDays < 7)
                return this.__(":count days ago", { count: diffDays });
            if (diffDays < 30)
                return this.__(":count weeks ago", {
                    count: Math.floor(diffDays / 7),
                });
            return this.__(":count months ago", {
                count: Math.floor(diffDays / 30),
            });
        },

        formatGrantTypes(grantTypes) {
            if (!grantTypes) return [];

            if (Array.isArray(grantTypes)) {
                return grantTypes;
            }

            if (typeof grantTypes === "string") {
                if (grantTypes.includes(",")) {
                    return grantTypes
                        .split(",")
                        .map((g) => g.trim())
                        .filter((g) => g);
                }
                return [grantTypes.trim()];
            }

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

        getClients(param = null) {
            this.loading = true;
            const params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.routes.clients, {
                    params: params,
                })
                .then((res) => {
                    this.clients = res.data.data;
                    const meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {
                    if (e?.response?.data?.message) {
                        this.$notify.error(e.response.data.message);
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$notify.success(this.__("Copied to clipboard"));
            } catch (err) {
                this.$notify.error(this.__("Failed to copy"));
            }
        },
    },
};
</script>
