<template>
    <v-admin-layout>
        <!-- Header Section -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="text-3xl font-bold text-blue-600">
                        {{ __("OAuth Clients Management") }}
                    </div>
                    <div class="text-gray-600 mt-1">
                        {{ __("Manage your application's OAuth 2.0 clients") }}
                    </div>
                </div>

                <div class="flex items-center space-x-2">
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
                class="bg-blue-50 text-blue-700 rounded-lg border border-blue-200"
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
                class="bg-green-50 text-green-700 rounded-lg border border-green-200"
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
                class="bg-orange-50 text-orange-700 rounded-lg border border-orange-200"
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
                class="bg-purple-50 text-purple-700 rounded-lg border border-purple-200"
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

        <!-- Clients Table -->
        <div
            class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden mt-4"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                v-for="column in columns"
                                :key="column.name"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Loading State -->
                        <tr v-if="loading">
                            <td
                                :colspan="columns.length"
                                class="px-6 py-12 text-center"
                            >
                                <div class="flex justify-center items-center">
                                    <svg
                                        class="animate-spin h-8 w-8 text-blue-600"
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
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <!-- Client Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-blue-600">
                                    {{ client.name }}
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    {{ __("ID:") }} {{ client.id }}
                                </div>
                            </td>

                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ formatDate(client.created_at) }}
                                </div>
                            </td>

                            <!-- Confidential -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                        client.confidential
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-orange-100 text-orange-800',
                                    ]"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            v-if="client.confidential"
                                            fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"
                                        />
                                        <path
                                            v-else
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    {{
                                        client.confidential
                                            ? __("Yes")
                                            : __("No")
                                    }}
                                </span>
                            </td>

                            <!-- Created By -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{
                                        client.created_by?.email || __("System")
                                    }}
                                </div>
                            </td>

                            <!-- Grant Types -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(
                                            grant, index
                                        ) in formatGrantTypes(
                                            client.grant_types
                                        )"
                                        :key="index"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800"
                                    >
                                        {{ grant }}
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

                <!-- Empty State -->
                <div
                    v-if="!loading && clients.length === 0"
                    class="text-center py-12"
                >
                    <svg
                        class="w-16 h-16 text-gray-300 mx-auto"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <div class="text-gray-500 text-lg mt-4">
                        {{ __("No clients available") }}
                    </div>
                    <div class="text-gray-400 text-sm mt-2">
                        {{
                            __("Create your first OAuth client to get started")
                        }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getClients"
        />
    </v-admin-layout>
</template>

<script> 
import VAdminLayout from "@/layouts/VAdminLayout.vue";
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
                "Client Name",
                "Created Date",
                "Confidential",
                "Created By",
                "Grant Types",
                "Actions",
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
            if (!dateString) return "";
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
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
                        $notify.error(e.response.data.message);
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                $notify.success(__("Copied to clipboard"));
            } catch (err) {
                $notify.error(__("Failed to copy"));
            }
        },
    },
};
</script>
