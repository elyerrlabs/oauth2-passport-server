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
            :title="__('Subscription Packages')"
            :description="
                __('Manage your subscription packages and billing information')
            "
        >
            <template #actions>
                <v-button
                    :label="__('Browse Plans')"
                    as="a"
                    :href="routes.plans"
                    icon="mdi mdi-cart"
                />
            </template>
        </v-head>

        <!-- Packages Content -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
        >
            <!-- Loading State -->
            <div v-if="loading" class="flex items-center justify-center py-12">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 dark:border-blue-400"
                ></div>
            </div>

            <!-- Empty State -->
            <div
                v-else-if="packages.length === 0"
                class="px-6 py-24 text-center"
            >
                <div class="flex flex-col items-center">
                    <svg
                        class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        />
                    </svg>
                    <h3
                        class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("No packages found") }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        {{ __("Your subscription packages will appear here") }}
                    </p>
                    <v-button
                        :label="__('Browse Plans')"
                        as="a"
                        :href="routes.plans"
                        icon="mdi mdi-cart"
                    />
                </div>
            </div>

            <!-- Packages Table -->
            <div v-else>
                <v-table
                    :items="packages"
                    :loading="loading"
                    :per-page="search.per_page"
                    :show-pagination="false"
                    :empty-text="__('No packages found')"
                    empty-icon="mdi-package-variant-off"
                    loading-text="Loading packages..."
                    table-class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    thead-class="bg-gray-50 dark:bg-gray-700"
                    tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                >
                    <template #head>
                        <tr>
                            <th
                                v-for="(column, index) in columns"
                                :key="index"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ column }}
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            >
                                {{ __("Actions") }}
                            </th>
                        </tr>
                    </template>

                    <template #default="{ items }">
                        <tr
                            v-for="pkg in items"
                            :key="pkg.id"
                            class="hover:bg-gray-50 text-sm dark:hover:bg-gray-700/50 transition-colors duration-150"
                        >
                            <!-- Package Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-blue-600 dark:text-blue-400"
                                    >
                                        {{ pkg.meta?.name || "—" }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ pkg.meta?.transaction_code || "—" }}
                                    </span>
                                </div>
                            </td>

                            <!-- Price -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ pkg.transaction?.total || "0" }}
                                        {{ pkg.transaction?.currency || "USD" }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 capitalize"
                                    >
                                        {{
                                            __(pkg.billing_period || "one-time")
                                        }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                    :class="getStatusClasses(pkg.status)"
                                >
                                    {{ pkg.status || __("unknown") }}
                                </span>
                            </td>

                            <!-- Start Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="text-sm text-gray-900 dark:text-white"
                                >
                                    {{ formatDate(pkg.start_at) }}
                                </span>
                            </td>

                            <!-- End Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="text-sm"
                                    :class="{
                                        'text-green-600 dark:text-green-400':
                                            isDateFuture(pkg.end_at),
                                        'text-red-600 dark:text-red-400':
                                            !isDateFuture(pkg.end_at),
                                    }"
                                >
                                    {{ formatDate(pkg.end_at) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm"
                            >
                                <div
                                    class="flex items-center justify-end space-x-2"
                                >
                                    <v-button
                                        :title="__('Show detail')"
                                        as="a"
                                        :href="pkg.links.show"
                                        icon="mdi mdi-eye"
                                        variant="success"
                                        round
                                    />
                                </div>
                            </td>
                        </tr>
                    </template>
                </v-table>

                <!-- Pagination -->
                <div
                    v-if="pages.total_pages > 1"
                    class="px-6 py-4 border-t border-gray-200 dark:border-gray-700"
                >
                    <v-paginate
                        v-model="search.page"
                        :total-pages="pages.total_pages"
                        @change="getPackages"
                    />
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import VMainLayout  from "@/components/VMainLayout.vue";
import VHead from "@/components/VHead.vue";
import VButton from "@/components/VButton.vue";
import VTable from "@/components/VTable.vue";
import VPaginate from "@/components/VPaginate.vue";

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
    routes: {
        type: Object,
        default: () => ({}),
    },
});

const loading = ref(false);
const packages = ref([]);
const pages = ref({
    total_pages: 0,
});

const search = useForm({
    page: 1,
    per_page: 20,
});

const columns = [
    __("Package"),
    __("Price"),
    __("Status"),
    __("Start Date"),
    __("End Date"),
];

onMounted(() => {
    loadData(props.data);
});

const loadData = (data) => {
    packages.value = data.data;
    pages.value = data.meta?.pagination;
};

const getPackages = () => {
    loading.value = true;

    search.get(props.routes.subscriptions, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            loadData(response.data);
        },
        onError: (e) => {
            $notify?.error?.(
                e?.response?.data?.message || __("Error loading packages"),
            );
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "—";
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const isDateFuture = (dateString) => {
    if (!dateString) return false;
    return new Date(dateString) > new Date();
};

const getStatusClasses = (status) => {
    const classes = {
        active: "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300",
        successful:
            "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300",
        pending:
            "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300",
        failed: "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300",
        cancelled:
            "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300",
        expired:
            "bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300",
    };
    return (
        classes[status?.toLowerCase()] ||
        "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"
    );
};
</script>
