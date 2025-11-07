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
    <v-partner-layout>
        <div class="sales-dashboard p-4">
            <!-- Header Section -->
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6"
            >
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ __("Sales Performance") }}
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">
                        {{ __("Track your commissions and sales history") }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button
                        @click="getSales"
                        class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                        :title="__('Refresh data')"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"
                            />
                        </svg>
                    </button>
                    <select
                        v-model="search.per_page"
                        @change="getSales"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[140px]"
                    >
                        <option value="10">10 {{ __("rows") }}</option>
                        <option value="15">15 {{ __("rows") }}</option>
                        <option value="25">25 {{ __("rows") }}</option>
                        <option value="50">50 {{ __("rows") }}</option>
                    </select>
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Total Sales Value -->
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-50 rounded-lg mr-3">
                            <svg
                                class="w-5 h-5 text-blue-600"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                />
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-xs text-gray-500 uppercase tracking-wide font-medium"
                            >
                                {{ __("TOTAL SALES VALUE") }}
                            </div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ totalSalesValue }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Commissions -->
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-50 rounded-lg mr-3">
                            <svg
                                class="w-5 h-5 text-green-600"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                                />
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-xs text-gray-500 uppercase tracking-wide font-medium"
                            >
                                {{ __("TOTAL COMMISSIONS") }}
                            </div>
                            <div class="text-lg font-bold text-green-600">
                                {{ totalCommissions }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-50 rounded-lg mr-3">
                            <svg
                                class="w-5 h-5 text-orange-600"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"
                                />
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-xs text-gray-500 uppercase tracking-wide font-medium"
                            >
                                {{ __("TOTAL TRANSACTIONS") }}
                            </div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ sales.length }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Data Table -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ __("Sales History") }}
                    </h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th
                                    v-for="(column, index) in columns"
                                    :key="index"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ __(column) }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="row in sales"
                                :key="row.id"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                    >
                                        {{ row.total }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                    >
                                        {{ row.currency }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-center"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getStatusClasses(row.status)"
                                    >
                                        {{ row.status }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right"
                                >
                                    <div class="flex flex-col items-end">
                                        <span
                                            class="font-semibold text-blue-600"
                                        >
                                            {{
                                                calculateCommission(
                                                    row
                                                ).toFixed(2)
                                            }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ __("Commission") }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    <div class="flex flex-col">
                                        <span>{{ row.created }}</span>
                                        <span class="text-xs text-gray-500">
                                            {{ row.created }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    <div class="flex flex-col">
                                        <span>{{ row.updated }}</span>
                                        <span class="text-xs text-gray-500">
                                            {{ row.updated }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <v-paginate
                    v-if="sales.length"
                    :total-pages="pages.total_pages"
                    v-model="search.page"
                    @change="getSales"
                />
            </div>
        </div>
    </v-partner-layout>
</template>

<script>
import VPartnerLayout from "@/layouts/VPartnerLayout.vue";
import VPaginate from "@/components/VPaginate.vue";

export default {
    components: {
        VPartnerLayout,
        VPaginate,
    },
    data() {
        return {
            sales: [],
            loading: false,
            columns: [
                "Amount",
                "Currency",
                "Status",
                "Commission",
                "Created Date",
                "Updated Date",
            ],
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
        totalSalesValue() {
            return this.sales.reduce(
                (sum, sale) => sum + parseFloat(sale.total || 0),
                0
            );
        },
        totalCommissions() {
            return this.sales
                .reduce((sum, sale) => sum + this.calculateCommission(sale), 0)
                .toFixed(2);
        },
    },

    created() {
        this.getSales();
    },

    methods: {
        async getSales() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.search,
                });

                if (res.status == 200) {
                    var values = res.data;
                    this.sales = values.data;
                    this.pages = res.data.meta.pagination;
                    this.search.total_pages =
                        res.data.meta.pagination.total_pages;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        calculateCommission(row) {
            return (
                (parseFloat(row.total || 0) *
                    parseFloat(row.partner_commission_rate || 0)) /
                100
            );
        },

        getStatusClasses(status) {
            const statusClasses = {
                completed: "bg-green-100 text-green-800",
                pending: "bg-yellow-100 text-yellow-800",
                failed: "bg-red-100 text-red-800",
                refunded: "bg-blue-100 text-blue-800",
                processing: "bg-purple-100 text-purple-800",
            };
            return (
                statusClasses[status.toLowerCase()] ||
                "bg-gray-100 text-gray-800"
            );
        },
    },
};
</script>

<style scoped>
.sales-dashboard {
    max-width: 1400px;
    margin: 0 auto;
}
</style>
