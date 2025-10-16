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
    <v-admin-transaction-layout>
        <!-- Header Section -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-6"
            >
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full"
                        ></div>
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent"
                        >
                            {{ __("Transactions Management") }}
                        </h1>
                    </div>
                    <p class="text-gray-600 text-lg ml-5">
                        {{ __("Monitor and manage all transaction records") }}
                    </p>
                </div>
            </div>

            <!-- Filter Component -->
            <div class="bg-gray-50 rounded-xl p-1 border border-gray-200">
                <v-filter :params="params" @change="searching" />
            </div>
        </div>

        <!-- Statistics Overview -->
        <div
            v-if="transactions.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 my-8"
        >
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-blue-200 p-6 relative overflow-hidden group hover:shadow-lg transition-all duration-300"
            >
                <div class="relative z-10">
                    <div class="text-2xl font-bold text-blue-900 mb-2">
                        {{ transactions.length }}
                    </div>
                    <div class="text-blue-700 font-medium text-sm">
                        {{ __("Total Transactions") }}
                    </div>
                </div>
                <i
                    class="mdi mdi-receipt text-blue-400 text-3xl absolute top-4 right-4 group-hover:scale-110 transition-transform duration-300"
                ></i>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl border border-green-200 p-6 relative overflow-hidden group hover:shadow-lg transition-all duration-300"
            >
                <div class="relative z-10">
                    <div class="text-2xl font-bold text-green-900 mb-2">
                        {{ successfulCount }}
                    </div>
                    <div class="text-green-700 font-medium text-sm">
                        {{ __("Successful") }}
                    </div>
                </div>
                <i
                    class="mdi mdi-check-circle text-green-400 text-3xl absolute top-4 right-4 group-hover:scale-110 transition-transform duration-300"
                ></i>
            </div>

            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl border border-orange-200 p-6 relative overflow-hidden group hover:shadow-lg transition-all duration-300"
            >
                <div class="relative z-10">
                    <div class="text-2xl font-bold text-orange-900 mb-2">
                        {{ pendingCount }}
                    </div>
                    <div class="text-orange-700 font-medium text-sm">
                        {{ __("Pending") }}
                    </div>
                </div>
                <i
                    class="mdi mdi-clock-outline text-orange-400 text-3xl absolute top-4 right-4 group-hover:scale-110 transition-transform duration-300"
                ></i>
            </div>

            <div
                class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl border border-red-200 p-6 relative overflow-hidden group hover:shadow-lg transition-all duration-300"
            >
                <div class="relative z-10">
                    <div class="text-2xl font-bold text-red-900 mb-2">
                        {{ failedCount }}
                    </div>
                    <div class="text-red-700 font-medium text-sm">
                        {{ __("Failed") }}
                    </div>
                </div>
                <i
                    class="mdi mdi-close-circle text-red-400 text-3xl absolute top-4 right-4 group-hover:scale-110 transition-transform duration-300"
                ></i>
            </div>
        </div>

        <!-- Transactions Table -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 mt-6 overflow-hidden"
        >
            <!-- Table Header -->
            <div
                class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200"
            >
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __("Transaction Records") }}
                    </h3>
                    <div class="text-sm text-gray-500">
                        {{ transactions.length }} {{ __("records") }}
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                {{ __("Transaction") }}
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                {{ __("Amount") }}
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                {{ __("Status") }}
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                            >
                                {{ __("Actions") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template
                            v-for="(item, index) in transactions"
                            :key="index"
                        >
                            <!-- Main Row -->
                            <tr
                                class="hover:bg-gray-50 transition-colors duration-200"
                            >
                                <!-- Transaction Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                                            >
                                                <i
                                                    class="mdi mdi-receipt text-blue-600 text-lg"
                                                ></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div
                                                class="text-sm font-semibold text-gray-900 truncate"
                                            >
                                                {{ item.code }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-500 mt-1"
                                            >
                                                <i
                                                    class="mdi mdi-calendar-clock mr-1"
                                                ></i>
                                                {{ item.created }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-500 mt-1"
                                            >
                                                <i
                                                    class="mdi mdi-credit-card-outline mr-1"
                                                ></i>
                                                {{ item.payment_method }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4">
                                    <div
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        {{ item.total }} {{ item.currency }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ item.billing_period }}
                                        {{ __("plan") }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span
                                        :class="[
                                            'px-3 py-2 rounded-full text-xs font-bold uppercase tracking-wide',
                                            getStatusClasses(item.status),
                                        ]"
                                    >
                                        {{ item.status }}
                                    </span>
                                    <div
                                        v-if="item.activated"
                                        class="text-xs text-gray-500 mt-1"
                                    >
                                        <i
                                            class="mdi mdi-calendar-check mr-1"
                                        ></i>
                                        {{ __("Activated") }}:
                                        {{ item.activated }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <v-transaction-activate
                                            @updated="getTransactions"
                                            v-if="check(item)"
                                            :item="item"
                                        />
                                        <v-detail :item="item" />
                                        <button
                                            @click="toggleRowExpansion(index)"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                            :title="
                                                expandedRow === index
                                                    ? __('Collapse details')
                                                    : __('Expand details')
                                            "
                                        >
                                            <i
                                                :class="[
                                                    'mdi transition-transform duration-200',
                                                    expandedRow === index
                                                        ? 'mdi-chevron-up'
                                                        : 'mdi-chevron-down',
                                                ]"
                                            ></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Expanded Details Row -->
                            <tr
                                v-if="expandedRow === index"
                                class="bg-blue-50 border-b border-blue-200"
                            >
                                <td colspan="4" class="px-6 py-4">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                                    >
                                        <!-- Transaction Details -->
                                        <div class="space-y-4">
                                            <h4
                                                class="text-sm font-semibold text-blue-800 uppercase tracking-wide"
                                            >
                                                <i
                                                    class="mdi mdi-information-outline mr-2"
                                                ></i>
                                                {{ __("Transaction Details") }}
                                            </h4>
                                            <div class="space-y-3">
                                                <div
                                                    class="flex justify-between text-sm"
                                                >
                                                    <span class="text-gray-600"
                                                        >{{
                                                            __(
                                                                "Transaction Code"
                                                            )
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                        >{{ item.code }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex justify-between text-sm"
                                                >
                                                    <span class="text-gray-600"
                                                        >{{
                                                            __("Created Date")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                        >{{
                                                            item.created
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex justify-between text-sm"
                                                >
                                                    <span class="text-gray-600"
                                                        >{{
                                                            __(
                                                                "Activation Date"
                                                            )
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                        >{{
                                                            item.activated ||
                                                            __("Not activated")
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment Information -->
                                        <div class="space-y-4">
                                            <h4
                                                class="text-sm font-semibold text-blue-800 uppercase tracking-wide"
                                            >
                                                <i
                                                    class="mdi mdi-credit-card-outline mr-2"
                                                ></i>
                                                {{ __("Payment Information") }}
                                            </h4>
                                            <div class="space-y-3">
                                                <div
                                                    class="flex justify-between text-sm"
                                                >
                                                    <span class="text-gray-600"
                                                        >{{
                                                            __(
                                                                "Payment Method"
                                                            )
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                        >{{
                                                            item.payment_method
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex justify-between text-sm"
                                                >
                                                    <span class="text-gray-600"
                                                        >{{
                                                            __("Plan Type")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                        >{{
                                                            item.billing_period
                                                        }}
                                                        {{ __("plan") }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex justify-between text-sm"
                                                >
                                                    <span class="text-gray-600"
                                                        >{{
                                                            __("Currency")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                        >{{
                                                            item.currency
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Actions -->
                                        <div class="space-y-4">
                                            <h4
                                                class="text-sm font-semibold text-blue-800 uppercase tracking-wide"
                                            >
                                                <i
                                                    class="mdi mdi-cog-outline mr-2"
                                                ></i>
                                                {{ __("Quick Actions") }}
                                            </h4>
                                            <div class="flex flex-wrap gap-2">
                                                <v-transaction-activate
                                                    @updated="getTransactions"
                                                    v-if="check(item)"
                                                    :item="item"
                                                    class="w-full"
                                                />
                                                <v-detail
                                                    :item="item"
                                                    class="w-full"
                                                />
                                                <button
                                                    @click="
                                                        toggleRowExpansion(
                                                            index
                                                        )
                                                    "
                                                    class="w-full px-4 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white border border-gray-300 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                                                >
                                                    <i
                                                        class="mdi mdi-close"
                                                    ></i>
                                                    {{ __("Close Details") }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-16">
                <div class="text-center">
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"
                    ></div>
                    <p class="text-gray-600 font-medium">
                        {{ __("Loading transactions...") }}
                    </p>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="!loading && transactions.length === 0"
                class="text-center py-16 px-6"
            >
                <div class="max-w-md mx-auto">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4"
                    >
                        <i class="mdi mdi-receipt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">
                        {{ __("No transactions available") }}
                    </h3>
                    <p class="text-gray-500">
                        {{
                            __("Get started by creating your first transaction")
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            v-if="pages.total_pages > 1"
        />
    </v-admin-transaction-layout>
</template>

<script>
import VFilter from "@/components/VFilter.vue";
import VAdminTransactionLayout from "@/layouts/VAdminTransactionLayout.vue";
import VTransactionActivate from "@/components/VTransactionActivate.vue";
import VDetail from "./Detail.vue";

export default {
    components: {
        VAdminTransactionLayout,
        VDetail,
        VFilter,
        VTransactionActivate,
    },

    data() {
        return {
            viewMode: "list",
            loading: false,
            expandedRow: null,
            params: [
                { key: "code", value: "code" },
                { key: "session", value: "session_id" },
                { key: "intent", value: "payment_intent_id" },
                { key: "created", value: "created" },
                { key: "updated", value: "updated" },
            ],
            transactions: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    computed: {
        successfulCount() {
            return this.transactions.filter((t) => t.status === "successful")
                .length;
        },
        pendingCount() {
            return this.transactions.filter((t) => t.status === "pending")
                .length;
        },
        failedCount() {
            return this.transactions.filter((t) => t.status === "failed")
                .length;
        },
    },

    created() {
        this.getTransactions();
    },

    methods: {
        getStatusClasses(status) {
            switch (status) {
                case "successful":
                    return "bg-green-100 text-green-800 border border-green-200";
                case "pending":
                    return "bg-orange-100 text-orange-800 border border-orange-200";
                case "failed":
                    return "bg-red-100 text-red-800 border border-red-200";
                default:
                    return "bg-gray-100 text-gray-800 border border-gray-200";
            }
        },

        toggleRowExpansion(index) {
            if (this.expandedRow === index) {
                this.expandedRow = null;
            } else {
                this.expandedRow = index;
            }
        },

        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getTransactions(event);
        },

        async getTransactions(param = null) {
            this.loading = true;
            this.expandedRow = null;
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: params,
                });
                if (res.status == 200) {
                    this.transactions = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    console.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        check(item) {
            return item.status == "pending" || item.status == "failed";
        },

        toggleView() {
            this.viewMode = this.viewMode === "grid" ? "list" : "grid";
        },
    },
};
</script>
