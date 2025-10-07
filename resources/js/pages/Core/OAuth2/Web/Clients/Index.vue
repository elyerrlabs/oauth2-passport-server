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
        <div class="min-h-screen bg-gray-50 py-8">
            <!-- Header -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i
                            class="mdi mdi-application-cog text-blue-600 text-3xl"
                        ></i>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ __("OAuth Clients") }}
                            </h1>
                            <p class="text-gray-600 text-sm">
                                {{
                                    __(
                                        "Manage your OAuth 2.0 clients and applications"
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                    <v-create @created="getClients()" class="cursor-pointer" />
                </div>
            </div>

            <!-- Clients Table -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden"
                >
                    <!-- Loading -->
                    <div
                        v-if="loading"
                        class="p-12 flex justify-center items-center"
                    >
                        <span
                            class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
                        ></span>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else-if="clients.length === 0"
                        class="p-12 text-center"
                    >
                        <i
                            class="mdi mdi-application-import text-gray-400 text-5xl mb-4"
                        ></i>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __("No OAuth Clients") }}
                        </h3>
                        <p class="text-gray-500 text-sm mb-6">
                            {{
                                __(
                                    "Create your first OAuth client to get started"
                                )
                            }}
                        </p>
                    </div>

                    <!-- Table -->
                    <!-- Table -->
                    <div v-else class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 text-sm hidden md:table"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        v-for="(column, index) in columns"
                                        :key="index"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide"
                                    >
                                        {{ __(column) }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="client in clients"
                                    :key="client.id"
                                    class="hover:bg-gray-50"
                                >
                                    <!-- Client -->
                                    <td class="px-6 py-4">
                                        <div
                                            class="font-semibold text-gray-900 flex items-center"
                                        >
                                            <i
                                                class="mdi mdi-application text-blue-600 mr-2"
                                            ></i>
                                            {{ client.name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ID: {{ client.id }}
                                        </div>
                                    </td>

                                    <!-- Created -->
                                    <td class="px-6 py-4">
                                        <div
                                            class="text-xs uppercase text-gray-500"
                                        >
                                            {{ __("Created") }}
                                        </div>
                                        <div
                                            class="flex items-center text-sm text-gray-900 mt-1"
                                        >
                                            <i
                                                class="mdi mdi-calendar text-gray-400 mr-2"
                                            ></i>
                                            {{ client.created_at }}
                                        </div>
                                    </td>

                                    <!-- Confidential -->
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold',
                                                client.confidential
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : 'bg-gray-100 text-gray-700',
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
                                                    ? __("Yes")
                                                    : __("No")
                                            }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{
                                                client.confidential
                                                    ? __("Confidential client")
                                                    : __("Public client")
                                            }}
                                        </div>
                                    </td>

                                    <!-- Grant Types -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-for="(
                                                    grant, index
                                                ) in getGrantTypes(
                                                    client.grant_types
                                                )"
                                                :key="index"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700"
                                            >
                                                <i
                                                    :class="[
                                                        getGrantIcon(grant),
                                                        'mdi mr-1 text-sm',
                                                    ]"
                                                ></i>
                                                {{ formatGrantType(grant) }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
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

                        <!-- Mobile stacked version -->
                        <div class="md:hidden space-y-4">
                            <div
                                v-for="client in clients"
                                :key="client.id"
                                class="bg-white shadow rounded-lg p-4 border border-gray-200"
                            >
                                <div class="flex items-center mb-2">
                                    <i
                                        class="mdi mdi-application text-blue-600 mr-2"
                                    ></i>
                                    <h3 class="font-semibold text-gray-900">
                                        {{ client.name }}
                                    </h3>
                                </div>
                                <p class="text-xs text-gray-500 mb-3">
                                    ID: {{ client.id }}
                                </p>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <div class="text-gray-500 text-xs">
                                            {{ __("Created") }}
                                        </div>
                                        <div class="flex items-center">
                                            <i
                                                class="mdi mdi-calendar text-gray-400 mr-1"
                                            ></i>
                                            {{ client.created_at }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500 text-xs">
                                            {{ __("Type") }}
                                        </div>
                                        <span
                                            :class="[
                                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold',
                                                client.confidential
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : 'bg-gray-100 text-gray-700',
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
                                </div>

                                <div class="mt-3">
                                    <div class="text-gray-500 text-xs mb-1">
                                        {{ __("Grant Types") }}
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="(
                                                grant, index
                                            ) in getGrantTypes(
                                                client.grant_types
                                            )"
                                            :key="index"
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700"
                                        >
                                            <i
                                                :class="[
                                                    getGrantIcon(grant),
                                                    'mdi mr-1 text-sm',
                                                ]"
                                            ></i>
                                            {{ formatGrantType(grant) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <v-update
                                        :item="client"
                                        @updated="getClients"
                                    />
                                    <v-delete
                                        :item="client"
                                        @deleted="getClients"
                                    />
                                </div>
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
        </div>
    </v-account-layout>
</template>

<script>
import VAccountLayout from "../../../../Components/VAccountLayout.vue";
import VPaginate from "../../../../Components/VPaginate.vue";
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
                authorization_code: "Authorization Code",
                password: "Password",
                client_credentials: "Client Credentials",
                implicit: "Implicit",
                refresh_token: "Refresh Token",
            };
            return map[grant] || grant;
        },

        getGrantIcon(grant) {
            const icons = {
                authorization_code: "mdi mdi-shield-account",
                password: "mdi mdi-form-textbox-password",
                client_credentials: "mdi mdi-account-key",
                implicit: "mdi mdi-flash",
                refresh_token: "mdi mdi-autorenew",
            };
            return icons[grant] || "mdi mdi-cog";
        },
    },
};
</script>
