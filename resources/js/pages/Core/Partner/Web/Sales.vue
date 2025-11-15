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
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        {{ __("Sales Performance") }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                        {{ __("Track your commissions and sales history") }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button
                        @click="getSales"
                        class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all duration-300 hover:scale-105"
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
                        class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[140px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
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
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-all duration-300"
                >
                    <div class="flex items-center">
                        <div
                            class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg mr-3"
                        >
                            <svg
                                class="w-5 h-5 text-blue-600 dark:text-blue-400"
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
                                class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide font-medium"
                            >
                                {{ __("TOTAL SALES VALUE") }}
                            </div>
                            <div
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ totalSalesValue }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Commissions -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-all duration-300"
                >
                    <div class="flex items-center">
                        <div
                            class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg mr-3"
                        >
                            <svg
                                class="w-5 h-5 text-green-600 dark:text-green-400"
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
                                class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide font-medium"
                            >
                                {{ __("TOTAL COMMISSIONS") }}
                            </div>
                            <div
                                class="text-lg font-bold text-green-600 dark:text-green-400"
                            >
                                {{ formatCurrency(totalCommissions) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-all duration-300"
                >
                    <div class="flex items-center">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-lg mr-3"
                        >
                            <svg
                                class="w-5 h-5 text-orange-600 dark:text-orange-400"
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
                                class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide font-medium"
                            >
                                {{ __("TOTAL TRANSACTIONS") }}
                            </div>
                            <div
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ sales.length }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Data Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300"
            >
                <!-- Card Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-600"
                >
                    <h2
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        {{ __("Sales History") }}
                    </h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-600"
                            >
                                <th
                                    v-for="(column, index) in columns"
                                    :key="index"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __(column) }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <tr
                                v-for="row in sales"
                                :key="row.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700"
                                    >
                                        {{ row.total }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600"
                                    >
                                        {{ row.currency }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-center"
                                >
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border"
                                        :class="getStatusClasses(row.status)"
                                    >
                                        <span
                                            class="w-2 h-2 rounded-full mr-1.5"
                                            :class="
                                                getStatusDotClass(row.status)
                                            "
                                        ></span>
                                        {{ __(row.status) }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right"
                                >
                                    <div class="flex flex-col items-end">
                                        <span
                                            class="font-semibold text-green-600 dark:text-green-400"
                                        >
                                            {{ row.commission }}
                                        </span>
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{ __("Commission") }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{
                                            formatDate(row.created)
                                        }}</span>
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{ formatTime(row.created) }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                >
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{
                                            formatDate(row.updated)
                                        }}</span>
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{ formatTime(row.updated) }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div
                        v-if="!sales.length && !loading"
                        class="text-center py-12"
                    >
                        <svg
                            class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            />
                        </svg>
                        <p
                            class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2"
                        >
                            {{ __("No sales data available") }}
                        </p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm">
                            {{ __("Your sales history will appear here") }}
                        </p>
                    </div>

                    <!-- Loading State -->
                    <div v-if="loading" class="text-center py-12">
                        <div
                            class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400 mx-auto mb-4"
                        ></div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            {{ __("Loading sales data...") }}
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="sales.length && pages.total_pages > 1"
                    class="border-t border-gray-200 dark:border-gray-600"
                >
                    <v-paginate
                        :total-pages="pages.total_pages"
                        v-model="search.page"
                        @change="getSales"
                    />
                </div>
            </div>

            <!-- Summary Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Commission Summary -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 shadow-sm"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
                    >
                        {{ __("Commission Summary") }}
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                                >{{ __("Commission Rate") }}</span
                            >
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ getAverageCommissionRate() }}%
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                                >{{ __("Average Commission") }}</span
                            >
                            <span
                                class="text-sm font-medium text-green-600 dark:text-green-400"
                            >
                                {{ formatCurrency(getAverageCommission()) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                                >{{ __("Highest Commission") }}</span
                            >
                            <span
                                class="text-sm font-medium text-green-600 dark:text-green-400"
                            >
                                {{ formatCurrency(getHighestCommission()) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Status Distribution -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 shadow-sm"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
                    >
                        {{ __("Status Distribution") }}
                    </h3>
                    <div class="space-y-2">
                        <div
                            v-for="status in getStatusDistribution()"
                            :key="status.name"
                            class="flex justify-between items-center text-sm"
                        >
                            <div class="flex items-center">
                                <span
                                    class="w-3 h-3 rounded-full mr-2"
                                    :class="getStatusDotClass(status.name)"
                                ></span>
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ __(status.name) }}</span
                                >
                            </div>
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ status.count }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-partner-layout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import VPartnerLayout from "@/layouts/VPartnerLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
const sales = ref([]);
const loading = ref(false);

const columns = ref([
    "Amount",
    "Currency",
    "Status",
    "Commission",
    "Created Date",
    "Updated Date",
]);

const pages = ref({
    total_pages: 0,
});

const search = useForm({
    page: 1,
    per_page: 15,
});

const totalSalesValue = computed(() =>
    (sales.value.reduce((sum, sale) => sum + sale.cents, 0) / 100).toFixed(2)
);

const calculateCommission = (row) => {
    return (
        (parseFloat(row.total || 0) *
            parseFloat(row.partner_commission_rate || 0)) /
        100
    );
};

const totalCommissions = computed(() =>
    (
        sales.value.reduce((sum, sale) => sum + sale.commission_cents, 0) / 100
    ).toFixed(2)
);

onMounted(() => {
    getSales();
});

const getSales = () => {
    loading.value = true;

    search.get(page.props.route, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            const values = page.props.data;
            sales.value = values.data;
            pages.value = values.meta.pagination;
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

function getStatusClasses(status) {
    const statusClasses = {
        completed:
            "bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 border-green-200 dark:border-green-700",
        pending:
            "bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-700",
        failed: "bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-200 dark:border-red-700",
        refunded:
            "bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-700",
        processing:
            "bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 border-purple-200 dark:border-purple-700",
    };

    return (
        statusClasses[status.toLowerCase()] ||
        "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-600"
    );
}

function getStatusDotClass(status) {
    const dotClasses = {
        completed: "bg-green-500",
        pending: "bg-yellow-500",
        failed: "bg-red-500",
        refunded: "bg-blue-500",
        processing: "bg-purple-500",
    };

    return dotClasses[status.toLowerCase()] || "bg-gray-500";
}

function formatCurrency(amount) {
    const numAmount = parseFloat(amount);
    if (isNaN(numAmount)) return "$0.00";

    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(numAmount);
}

function formatDate(dateString) {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString();
}

function formatTime(dateString) {
    return dateString;
    if (!dateString) return "-";
    return new Date(dateString).toLocaleTimeString();
}

function getAverageCommissionRate() {
    if (sales.value.length === 0) return 0;
    const totalRate = sales.value.reduce(
        (sum, sale) => sum + parseFloat(sale.partner_commission_rate || 0),
        0
    );
    return (totalRate / sales.value.length).toFixed(1);
}

function getAverageCommission() {
    if (sales.value.length === 0) return 0;
    return totalCommissions.value / sales.value.length;
}

function getHighestCommission() {
    if (sales.value.length === 0) return 0;
    return Math.max(...sales.value.map((sale) => calculateCommission(sale)));
}

function getStatusDistribution() {
    const distribution = {};

    sales.value.forEach((sale) => {
        const status = sale.status.toLowerCase();
        distribution[status] = (distribution[status] || 0) + 1;
    });

    return Object.entries(distribution).map(([name, count]) => ({
        name: name.charAt(0).toUpperCase() + name.slice(1),
        count,
    }));
}
</script>

<style scoped>
/* Transiciones suaves para todos los elementos */
* {
    transition: background-color 0.3s ease, border-color 0.3s ease,
        color 0.3s ease;
}

/* Mejoras de hover para elementos interactivos */
button:hover {
    transform: translateY(-1px);
}

/* Scrollbar personalizado para dark mode */
.dark ::-webkit-scrollbar {
    width: 6px;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 3px;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Animación de loading */
@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
