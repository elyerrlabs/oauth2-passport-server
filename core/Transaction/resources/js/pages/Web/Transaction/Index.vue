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
        <!-- Header Section -->
        <v-head
            :title="__('Transactions Management')"
            :description="__('Monitor and manage all transaction records')"
        >
            <template #bottom>
                <div class="flex justify-around gap-4 flex-wrap">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Code Filter -->
                        <v-input
                            :label="__('Code')"
                            v-model="search.code"
                            @input="debouncedSearch"
                            :placeholder="__('code')"
                        />

                        <!-- Type Filter -->
                        <v-select
                            :label="__('Type')"
                            v-model="search.type"
                            :options="billing_types"
                            @change="searching"
                            :placeholder="__('All types')"
                        />

                        <!-- Status Filter -->
                        <v-select
                            :label="__('Status')"
                            v-model="search.status"
                            :options="billing_statuses"
                            @change="searching"
                            :placeholder="__('All statuses')"
                        />
                    </div>

                    <!-- Clear Filters -->
                    <div class="flex shrink-0 items-end">
                        <v-button
                            @click="clearFilters"
                            :label="__('Clear filters')"
                            variant="secondary"
                        />
                    </div>
                </div>
            </template>
        </v-head>

        <div class="mb-6">
            <v-table
                :items="transactions"
                :loading="loading"
                :per-page="search.per_page"
                :show-pagination="false"
                :empty-text="
                    __('No transactions found. Try adjusting your filters.')
                "
                empty-icon="mdi-receipt-off-outline"
                loading-text="Loading transactions..."
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
                    </tr>
                </template>

                <template #default="{ items }">
                    <template v-for="(item, index) in items" :key="item.id">
                        <!-- Main row -->
                        <tr
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors duration-150"
                        >
                            <!-- Payment method -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                                >
                                    {{ item.payment_method || "—" }}
                                </span>
                            </td>

                            <!-- Billing period -->
                            <td
                                class="px-6 py-4 whitespace-nowrap capitalize text-gray-900 dark:text-white"
                            >
                                {{ item.billing_period || "—" }}
                            </td>

                            <!-- Total -->
                            <td
                                class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white"
                            >
                                {{ formattedTotal(item) }}
                            </td>

                            <!-- Code with copy button -->
                            <td
                                class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900 dark:text-white"
                            >
                                <div class="flex items-center gap-1">
                                    <span>{{ item.code }}</span>
                                    <v-button
                                        @click.stop="copyToClipboard(item.code)"
                                        :title="__('Copy code')"
                                        icon="mdi mdi-content-copy text-sm"
                                        round
                                        variant="ghost"
                                    />
                                </div>
                            </td>

                            <!-- Created -->
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                            >
                                {{ formatDate(item.created) }}
                            </td>

                            <!-- Status badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="getStatusBadgeClass(item.status)"
                                    class="px-2 py-1 text-xs rounded-full font-medium"
                                >
                                    {{ item.status || "unknown" }}
                                </span>
                            </td>

                            <!-- Actions & expand indicator -->
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                            >
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <v-activate
                                        :item="item"
                                        @updated="getTransactions"
                                    />

                                    <v-button
                                        :item="item"
                                        icon="mdi mdi-eye"
                                        :title="__('Show details')"
                                        variant="success"
                                        round
                                        :to="item.links.show"
                                        as="a"
                                    />
                                </div>
                            </td>
                        </tr>
                    </template>
                </template>
            </v-table>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            @change="getTransactions"
        />
    </v-main-layout>
</template>

<script setup>
import VMainLayout from "@/components/VMainLayout.vue";
import VActivate from "./VActivate.vue";
import VInput from "@/components/VInput.vue";
import VButton from "@/components/VButton.vue";
import VHead from "@/components/VHead.vue";
import VSelect from "@/components/VSelect.vue";
import VTable from "@/components/VTable.vue";
import VPaginate from "@/components/VPaginate.vue";
import { onMounted, ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
    billing_types: {
        type: Array,
        default: () => [],
    },
    billing_statuses: {
        type: Array,
        default: () => [],
    },
    routes: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();

const loading = ref(false);
const transactions = ref([]);
const pages = ref({
    total_pages: 0,
});

const columns = [
    "Payment method",
    "Period",
    "Total",
    "Code",
    "Created",
    "Status",
    "Actions",
];

const search = useForm({
    page: 1,
    per_page: 15,
    code: "",
    status: "",
    type: "",
});

const searchTimeout = ref(null);

onMounted(() => {
    data(props.data);
});

const data = (data) => {
    const values = data;
    transactions.value = values.data;
    pages.value = values.meta?.pagination;
};

// Helper: formatted total
const formattedTotal = (item) => {
    if (item.meta?.meta?.price?.amount_format) {
        return `${item.meta.meta.price.amount_format} ${item.currency}`;
    }
    return `${item.total} ${item.currency}`;
};

// Helper: status badge classes
const getStatusBadgeClass = (status) => {
    switch (status?.toLowerCase()) {
        case "successful":
            return "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700";
        case "pending":
            return "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700";
        case "failed":
            return "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700";
        default:
            return "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600";
    }
};

// Helper: format date
const formatDate = (dateString) => {
    if (!dateString) return "—";
    try {
        return new Intl.DateTimeFormat(undefined, {
            dateStyle: "medium",
            timeStyle: "short",
        }).format(new Date(dateString));
    } catch {
        return dateString;
    }
};

// Copy to clipboard
const copyToClipboard = (text) => {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            $notify.success(__("Transaction code copied to clipboard"));
        })
        .catch(() => {
            $notify.error(__("Failed to copy text"));
        });
};

const searching = () => {
    search.value.page = 1;
    getTransactions();
};

const debouncedSearch = () => {
    if (searchTimeout.value) clearTimeout(searchTimeout.value);
    searchTimeout.value = setTimeout(() => {
        searching();
    }, 500);
};

const getTransactions = async () => {
    loading.value = true;

    search.get(props.routes.index, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            data(response.data);
        },
        onError: (error) => {
            const message =
                error?.response?.data?.message ||
                __("Failed to load transactions");
            $notify.error(message);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const clearFilters = () => {
    search.resetAndClearErrors();
    searching();
};
</script>
