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
    <v-main-layout>
        <v-head
            :title="__('OAuth Clients Management')"
            :description="__('Manage your application\'s OAuth 2.0 clients')"
        >
            <template #actions>
                <v-personal-client @created="getClients" />
                <v-create @created="getClients" />
            </template>
        </v-head>

        <v-table
            :items="clients"
            :loading="loading"
            :per-page="search.per_page"
            :show-pagination="false"
            :empty-text="__('Create your first OAuth client to get started')"
            empty-icon="mdi-file-document-outline"
            loading-text="Loading clients..."
            table-class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
            thead-class="bg-gray-50 dark:bg-gray-700/50"
            tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
        >
            <template #head>
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
            </template>

            <template #default="{ items }">
                <tr
                    v-for="client in items"
                    :key="client.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group"
                >
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
                                    {{ formatTimeAgo(client.created_at) }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col items-center space-y-2">
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
                                {{ client.confidential ? __("Yes") : __("No") }}
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
                                        client.created_by?.email || __("System")
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

                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1.5 max-w-xs">
                            <span
                                v-for="(grant, index) in formatGrantTypes(
                                    client.grant_types,
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

                    <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                    >
                        <div class="flex justify-end space-x-2">
                            <v-create :item="client" @updated="getClients" />
                            <v-delete :item="client" @deleted="getClients" />
                        </div>
                    </td>
                </tr>
            </template>
        </v-table>

        <!-- Pagination -->
        <v-paginate
            v-if="clients.length > 0"
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getClients"
        />
    </v-main-layout>
</template>

<script setup>
import VMainLayout from "@/components/VMainLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VTable from "@/components/VTable.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VPersonalClient from "./PersonalClient.vue";
import VHead from "@/components/VHead.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const page = usePage();

const clients = ref([]);
const loading = ref(false);
const pages = ref({
    total_pages: 1,
});
const search = ref({
    page: 1,
    per_page: 50,
});

const columns = ref([
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
]);

onMounted(async () => {
    await getClients();
});

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const formatTimeAgo = (dateString) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 1) return __("1 day ago");
    if (diffDays < 7) return __(":count days ago", { count: diffDays });
    if (diffDays < 30)
        return __(":count weeks ago", {
            count: Math.floor(diffDays / 7),
        });
    return __(":count months ago", {
        count: Math.floor(diffDays / 30),
    });
};

const formatGrantTypes = (grantTypes) => {
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
};

const formatGrantType = (grant) => {
    const map = {
        authorization_code: "Auth Code",
        password: "Password",
        client_credentials: "Client Credentials",
        implicit: "Implicit",
        refresh_token: "Refresh Token",
    };
    return map[grant] || grant;
};

const getGrantIcon = (grant) => {
    const icons = {
        authorization_code: "mdi-shield-account",
        password: "mdi-form-textbox-password",
        client_credentials: "mdi-account-key",
        implicit: "mdi-flash",
        refresh_token: "mdi-autorenew",
    };
    return `mdi ${icons[grant] || "mdi-cog"}`;
};

const getGrantColor = (grant) => {
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
};

const getGrantDescription = (grant) => {
    const descriptions = {
        authorization_code: "Authorization Code Flow - for web applications",
        password:
            "Resource Owner Password Credentials - for trusted applications",
        client_credentials:
            "Client Credentials Flow - for machine-to-machine authentication",
        implicit: "Implicit Flow - for single-page applications (legacy)",
        refresh_token: "Refresh Token - for obtaining new access tokens",
    };
    return descriptions[grant] || "OAuth 2.0 Grant Type";
};

const getClients = (param = null) => {
    loading.value = true;
    const params = { ...search.value, ...param };

    $server
        .get(page.props.routes.clients, {
            params: params,
        })
        .then((res) => {
            clients.value = res.data.data;
            const meta = res.data.meta;
            pages.value = meta.pagination;
            search.value.current_page = meta.pagination.current_page;
        })
        .catch((e) => {
            if (e?.response?.data?.message) {
                $notify.error(e.response.data.message);
            }
        })
        .finally(() => {
            loading.value = false;
        });
};
</script>
