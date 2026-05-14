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
    <v-layout>
        <template #aside>
            <v-item-menu
                :items="page.props.menus"
                icon="mdi mdi-apps text-2xl"
                :collapse="true"
            />
        </template>
        <template #main>
            <!-- Header Section -->
            <v-head
                :title="__('Transactions Management')"
                :description="__('Monitor and manage all transaction records')"
            >
                <template #bottom>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4"
                    >
                        <!-- Name Filter -->
                        <v-input
                            :label="__('Name')"
                            v-model="search.name"
                            @input="debouncedSearch"
                            :placeholder="__('Filter by name')"
                        />

                        <!-- Code Filter -->
                        <v-input
                            :label="__('Code')"
                            v-model="search.code"
                            @input="debouncedSearch"
                            :placeholder="__('Transaction code')"
                        />

                        <!-- Email Filter -->
                        <v-input
                            :label="__('Email')"
                            v-model="search.email"
                            @input="debouncedSearch"
                            :placeholder="__('Email')"
                        />

                        <!-- Type Filter -->
                        <v-select
                            :label="__('Type')"
                            v-model="search.type"
                            :options="types"
                            @change="searching"
                            :placeholder="__('All types')"
                        />

                        <!-- Status Filter -->
                        <v-select
                            :label="__('Status')"
                            v-model="search.status"
                            :options="statuses"
                            @change="searching"
                            :placeholder="__('All statuses')"
                        />

                        <!-- Clear Filters -->
                        <div class="flex items-end">
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
                                @click="toggleRowExpansion(index)"
                            >
                                <!-- Owner -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ item.owner?.name }}
                                        {{ item.owner?.last_name }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ item.owner?.email }}
                                    </div>
                                </td>

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
                                        <span>{{
                                            truncateCode(item.code)
                                        }}</span>
                                        <button
                                            @click.stop="
                                                copyToClipboard(item.code)
                                            "
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                                            :title="__('Copy code')"
                                        >
                                            <i
                                                class="mdi mdi-content-copy text-sm"
                                            ></i>
                                        </button>
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
                                        :class="
                                            getStatusBadgeClass(item.status)
                                        "
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
                                        <v-transaction-activate
                                            :item="item"
                                            @updated="getTransactions"
                                        />

                                        <v-detail :item="item" />
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
                v-if="pages.total_pages > 1"
                @change="getTransactions"
            />
        </template>
    </v-layout>
</template>

<script setup>
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import VTransactionActivate from "@/components/VTransactionActivate.vue";
import VDetail from "./Detail.vue";
import VInput from "@/components/VInput.vue";
import VButton from "@/components/VButton.vue";
import VHead from "@/components/VHead.vue";
import VSelect from "@/components/VSelect.vue";
import VTable from "@/components/VTable.vue";
import VPaginate from "@/components/VPaginate.vue";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const loading = ref(false);
const expandedRow = ref(null);
const transactions = ref([]);
const selectedTransaction = ref(null);
const pages = ref({
    total_pages: 0,
});

const columns = [
    "Owner",
    "Payment method",
    "Period",
    "Total",
    "Code",
    "Created",
    "Status",
    "Actions",
];

const search = ref({
    page: 1,
    per_page: 15,
    name: "",
    code: "",
    email: "",
    status: "",
    type: "",
});

const searchTimeout = ref(null);
const statuses = ref([]);
const types = ref([]);

onMounted(async () => {
    await getTransactions();
    await getStatuses();
    await getType();
});

//  visibilidad del gateway response
const showGatewayResponse = ref(null);

const toggleGatewayResponse = (index) => {
    if (showGatewayResponse.value === index) {
        showGatewayResponse.value = null;
    } else {
        showGatewayResponse.value = index;
    }
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

// Helper: truncate code
const truncateCode = (code) => {
    if (code && code.length > 12) {
        return code.substring(0, 10) + "...";
    }
    return code;
};

// Check if transaction can be activated
const check = (item) => {
    return item.status === "pending" || item.status === "failed";
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

// Actions
const activateTransaction = (item) => {
    // Emit or open modal – implement according to your VTransactionActivate component
    // For example: $emit('activate', item) or use a modal
    $notify.info(__("Activation feature: ") + item.code);
};

const viewDetails = (item) => {
    selectedTransaction.value = item;
};

const toggleRowExpansion = (index) => {
    expandedRow.value = expandedRow.value === index ? null : index;
};

// Data fetching
const getStatuses = async () => {
    try {
        const res = await $server.get(page.props.api.payment_status);
        if (res.status === 200) {
            statuses.value = res.data.data;
        }
    } catch (e) {
        console.error("Error fetching statuses:", e);
    }
};

const getType = async () => {
    try {
        const res = await $server.get(page.props.api.payment_types);
        if (res.status === 200) {
            types.value = res.data.data;
        }
    } catch (e) {
        console.error("Error fetching types:", e);
    }
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
    expandedRow.value = null;

    try {
        const res = await $server.get(
            page.props.api.transactions,
            search.value,
        );
        if (res.status === 200) {
            let data = res.data.data;
            // Ensure data is an array (in case API returns object with numeric keys)
            if (data && !Array.isArray(data)) {
                data = Object.values(data);
            }
            transactions.value = data || [];
            pages.value = res.data.meta?.pagination || { total_pages: 0 };
        }
    } catch (error) {
        const message =
            error?.response?.data?.message || __("Failed to load transactions");
        $notify.error(message);
    } finally {
        loading.value = false;
    }
};

const clearFilters = () => {
    search.value = {
        page: 1,
        per_page: 15,
        name: "",
        code: "",
        email: "",
        status: "",
        type: "",
    };
    searching();
};
</script>
