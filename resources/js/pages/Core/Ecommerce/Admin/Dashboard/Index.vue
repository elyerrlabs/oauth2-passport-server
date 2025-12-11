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
    <v-admin-layout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Header -->
            <div
                class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
            >
                <div class="px-4 py-4 sm:px-6">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div>
                            <h1
                                class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl"
                            >
                                {{ __("Dashboard Overview") }}
                            </h1>
                            <p
                                class="mt-1 text-sm text-gray-600 dark:text-gray-300"
                            >
                                {{
                                    __(
                                        "Welcome back! Here's what's happening with your store today."
                                    )
                                }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Currency Select -->
                            <div class="w-55">
                                <v-select
                                    :options="currencies"
                                    label-key="name"
                                    value-key="code"
                                    v-model="search.currency"
                                    @change="getData"
                                />
                            </div>

                            <!-- Refresh Button -->
                            <button
                                @click="getData"
                                class="flex items-center justify-center p-2 text-blue-600 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-400 dark:hover:bg-blue-800"
                                title="Refresh data"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 
                   7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6
                   s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-4 sm:p-6">
                <!-- Stats Grid -->
                <div
                    class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <!-- Products Available -->
                    <div
                        class="p-4 transition-shadow bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-blue-100 rounded-lg dark:bg-blue-900"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600 dark:text-blue-400"
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
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white sm:text-xl"
                                >
                                    {{
                                        formatNumber(
                                            dashboard.products_available
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Products Available") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("Total products in inventory") }}
                        </div>
                    </div>

                    <!-- Successful Transactions -->
                    <div
                        class="p-4 transition-shadow bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-green-100 rounded-lg dark:bg-green-900"
                            >
                                <svg
                                    class="w-5 h-5 text-green-600 dark:text-green-400"
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
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white sm:text-xl"
                                >
                                    {{ dashboard.currency_symbol }}
                                    {{
                                        formatCurrencyDisplay(
                                            calculateTotalSuccessful()
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Successful Transactions") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{
                                __("Total revenue from completed transactions")
                            }}
                        </div>
                    </div>

                    <!-- Pending Refund -->
                    <div
                        class="p-4 transition-shadow bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-yellow-100 rounded-lg dark:bg-yellow-900"
                            >
                                <svg
                                    class="w-5 h-5 text-yellow-600 dark:text-yellow-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white sm:text-xl"
                                >
                                    {{ dashboard.currency_symbol }}
                                    {{
                                        formatCurrencyDisplay(
                                            calculateTotalPending()
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Pending Refund") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("Total amount pending refund") }}
                        </div>
                    </div>

                    <!-- Successfully Refunded -->
                    <div
                        class="p-4 transition-shadow bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-red-100 rounded-lg dark:bg-red-900"
                            >
                                <svg
                                    class="w-5 h-5 text-red-600 dark:text-red-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white sm:text-xl"
                                >
                                    {{ dashboard.currency_symbol }}
                                    {{
                                        formatCurrencyDisplay(
                                            calculateTotalRefunded()
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Successfully Refunded") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("Total amount refunded to customers") }}
                        </div>
                    </div>

                    <!-- Today's Sales -->
                    <div
                        class="p-4 transition-shadow bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900"
                            >
                                <svg
                                    class="w-5 h-5 text-purple-600 dark:text-purple-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white sm:text-xl"
                                >
                                    {{ dashboard.currency_symbol }}
                                    {{
                                        formatCurrencyDisplay(
                                            calculateTodaysSales()
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Today's Sales") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("Revenue generated today") }}
                        </div>
                    </div>

                    <!-- Total Transactions -->
                    <div
                        class="p-4 transition-shadow bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-indigo-100 rounded-lg dark:bg-indigo-900"
                            >
                                <svg
                                    class="w-5 h-5 text-indigo-600 dark:text-indigo-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white sm:text-xl"
                                >
                                    {{ successfulTransactionsCount() }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Total Transactions") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("Number of completed transactions") }}
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                    <!-- Transactions Timeline -->
                    <div
                        class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-base font-bold text-gray-900 dark:text-white sm:text-lg"
                            >
                                {{ __("Transactions Timeline") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="line"
                                height="300"
                                :options="transactionsChartOptions"
                                :series="transactionsChartSeries"
                            />
                        </div>
                    </div>

                    <!-- Distribution Chart -->
                    <div
                        class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-base font-bold text-gray-900 dark:text-white sm:text-lg"
                            >
                                {{ __("Revenue Distribution") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="donut"
                                height="300"
                                :options="distributionChartOptions"
                                :series="distributionChartSeries"
                            />
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div
                    class="bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 overflow-hidden"
                >
                    <div
                        class="px-4 py-4 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 sm:px-6"
                    >
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-base font-semibold text-gray-900 dark:text-white sm:text-lg"
                            >
                                {{ __("Recent Transactions") }}
                            </h3>
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Last updated") }}:
                                {{ getLastUpdateDate() }}
                            </div>
                        </div>
                    </div>

                    <!-- Mobile View - Cards -->
                    <div
                        class="divide-y divide-gray-200 dark:divide-gray-600 sm:hidden"
                    >
                        <div
                            v-for="transaction in getSuccessfulTransactions()"
                            :key="transaction.date"
                            class="p-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <div
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ formatDate(transaction.date) }}
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300"
                                >
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    {{ __("Successful") }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Transaction Amount") }}
                                </div>
                                <div
                                    class="text-sm font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ dashboard.currency_symbol }}
                                    {{
                                        formatCurrencyDisplay(transaction.total)
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop View - Table -->
                    <div class="hidden overflow-x-auto sm:block">
                        <table class="w-full">
                            <thead>
                                <tr
                                    class="border-b border-gray-200 bg-gray-50 dark:bg-gray-700/50 dark:border-gray-600"
                                >
                                    <th
                                        class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:px-6"
                                    >
                                        {{ __("Date") }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:px-6"
                                    >
                                        {{ __("Type") }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:px-6"
                                    >
                                        {{ __("Status") }}
                                    </th>
                                    <th
                                        class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 sm:px-6"
                                    >
                                        {{ __("Amount") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-200 dark:divide-gray-600"
                            >
                                <tr
                                    v-for="transaction in getCombinedTransactions()"
                                    :key="transaction.date + transaction.type"
                                    class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30"
                                >
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white sm:px-6"
                                    >
                                        {{ formatDate(transaction.date) }}
                                    </td>
                                    <td
                                        class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300 sm:px-6"
                                    >
                                        {{ transaction.type }}
                                    </td>
                                    <td class="px-4 py-4 text-sm sm:px-6">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full"
                                            :class="
                                                getStatusClasses(
                                                    transaction.status
                                                )
                                            "
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1.5"
                                                fill="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    v-if="
                                                        transaction.status ===
                                                        'successful'
                                                    "
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                                <path
                                                    v-else-if="
                                                        transaction.status ===
                                                        'pending'
                                                    "
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                                <path
                                                    v-else
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            {{ transaction.status }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-4 py-4 text-sm text-right text-gray-900 dark:text-white sm:px-6"
                                    >
                                        {{ dashboard.currency_symbol }}
                                        {{
                                            formatCurrencyDisplay(
                                                transaction.total
                                            )
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VueApexCharts from "vue3-apexcharts";
import VAdminLayout from "../../Components/VAdminLayout.vue";
import { usePage } from "@inertiajs/vue3";
import VSelect from "@/components/VSelect.vue";

const page = usePage();
const apexchart = VueApexCharts;

// Refs
const dashboard = ref({
    currency: "USD",
    currency_symbol: "$",
    products_available: 0,
    successfully_transactions: [],
    pending_refund: [],
    successfully_refund: [],
});

const currencies = ref([]);

const search = ref({
    currency: "USD",
});

// Charts options
const transactionsChartOptions = ref({
    chart: {
        type: "line",
        height: 300,
        toolbar: { show: true },
        background: "transparent",
    },
    colors: ["#3B82F6", "#10B981", "#F59E0B"],
    dataLabels: { enabled: false },
    stroke: { curve: "smooth", width: 2 },
    xaxis: { categories: [] },
    yaxis: {
        labels: {
            formatter: (val) => dashboard.value.currency_symbol + val,
        },
    },
    tooltip: {
        y: {
            formatter: (val) => dashboard.value.currency_symbol + val,
        },
    },
    legend: { position: "top" },
});

const transactionsChartSeries = ref([]);

const distributionChartOptions = ref({
    chart: {
        type: "donut",
        background: "transparent",
    },
    labels: ["Successful", "Pending Refund", "Completed Refund"],
    colors: ["#10B981", "#F59E0B", "#EF4444"],
    legend: { position: "bottom" },
    dataLabels: { enabled: true },
    tooltip: {
        y: {
            formatter: (val) => dashboard.value.currency_symbol + val,
        },
    },
    plotOptions: {
        pie: {
            donut: {
                labels: {
                    show: true,
                    name: { show: true },
                    value: {
                        show: true,
                        formatter: (val) =>
                            dashboard.value.currency_symbol + val,
                    },
                    total: {
                        show: true,
                        label: "Total",
                        formatter: () =>
                            dashboard.value.currency_symbol +
                            formatCurrencyDisplay(
                                calculateTotalSuccessful() +
                                    calculateTotalPending() +
                                    calculateTotalRefunded()
                            ),
                    },
                },
            },
        },
    },
});

const distributionChartSeries = ref([0, 0, 0]);

// Helper function to convert cents to dollars
const convertCentsToDollars = (cents) => {
    return cents / 100;
};

// Helper function to ensure proper number parsing
const parseNumber = (value) => {
    if (typeof value === "string") {
        const cleaned = value.replace(/[^0-9.-]/g, "");
        return parseFloat(cleaned) || 0;
    }
    return parseFloat(value) || 0;
};

// Computed functions for calculations - keep in cents for totals
const calculateTotalSuccessful = () => {
    if (!dashboard.value.successfully_transactions) return 0;
    return dashboard.value.successfully_transactions.reduce(
        (sum, transaction) => {
            const cents = parseNumber(transaction.total || 0);
            return sum + cents;
        },
        0
    );
};

const calculateTotalPending = () => {
    if (!dashboard.value.pending_refund) return 0;
    return dashboard.value.pending_refund.reduce((sum, transaction) => {
        const cents = parseNumber(transaction.total || 0);
        return sum + cents;
    }, 0);
};

const calculateTotalRefunded = () => {
    if (!dashboard.value.successfully_refund) return 0;
    return dashboard.value.successfully_refund.reduce((sum, transaction) => {
        const cents = parseNumber(transaction.total || 0);
        return sum + cents;
    }, 0);
};

const calculateTodaysSales = () => {
    if (!dashboard.value.successfully_transactions) return 0;
    const today = new Date().toISOString().split("T")[0];
    const todayTransaction = dashboard.value.successfully_transactions.find(
        (t) => t.date === today
    );
    return todayTransaction ? parseNumber(todayTransaction.total || 0) : 0;
};

const successfulTransactionsCount = () => {
    return dashboard.value.successfully_transactions?.length || 0;
};

// Helper functions for display
const formatCurrencyDisplay = (cents) => {
    // Convert cents to dollars for display
    const dollars = convertCentsToDollars(cents);

    // Show full number without K/M abbreviations for clarity
    return dollars.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatNumber = (num) => {
    const number = parseNumber(num);
    // Show full number without K/M abbreviations
    return number.toLocaleString("en-US", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
};

const getLastUpdateDate = () => {
    const now = new Date();
    return now.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getSuccessfulTransactions = () => {
    const transactions = dashboard.value.successfully_transactions || [];
    return transactions.map((t) => ({
        ...t,
        total: parseNumber(t.total),
        formattedTotal: formatCurrencyDisplay(parseNumber(t.total)),
    }));
};

const getCombinedTransactions = () => {
    const transactions = [];

    // Add successful transactions
    (dashboard.value.successfully_transactions || []).forEach((t) => {
        const cents = parseNumber(t.total);
        transactions.push({
            ...t,
            total: cents,
            formattedTotal: formatCurrencyDisplay(cents),
            type: "Sale",
            status: "successful",
        });
    });

    // Add pending refunds
    (dashboard.value.pending_refund || []).forEach((t) => {
        const cents = parseNumber(t.total);
        transactions.push({
            ...t,
            total: cents,
            formattedTotal: formatCurrencyDisplay(cents),
            type: "Refund",
            status: "pending",
        });
    });

    // Add completed refunds
    (dashboard.value.successfully_refund || []).forEach((t) => {
        const cents = parseNumber(t.total);
        transactions.push({
            ...t,
            total: cents,
            formattedTotal: formatCurrencyDisplay(cents),
            type: "Refund",
            status: "completed",
        });
    });

    // Sort by date (newest first)
    return transactions.sort((a, b) => new Date(b.date) - new Date(a.date));
};

const getStatusClasses = (status) => {
    const statusClasses = {
        successful:
            "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300",
        pending:
            "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300",
        completed:
            "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300",
    };
    return (
        statusClasses[status] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
    );
};

const updateCharts = () => {
    // Update transactions timeline chart
    const allTransactions = getCombinedTransactions();
    const dates = allTransactions
        .map((t) => t.date)
        .filter((date, index, self) => self.indexOf(date) === index)
        .sort();

    // Use dollars for chart display
    const successfulData = dates.map((date) => {
        const transaction = dashboard.value.successfully_transactions?.find(
            (t) => t.date === date
        );
        return transaction
            ? convertCentsToDollars(parseNumber(transaction.total))
            : 0;
    });

    const pendingData = dates.map((date) => {
        const transaction = dashboard.value.pending_refund?.find(
            (t) => t.date === date
        );
        return transaction
            ? convertCentsToDollars(parseNumber(transaction.total))
            : 0;
    });

    const refundedData = dates.map((date) => {
        const transaction = dashboard.value.successfully_refund?.find(
            (t) => t.date === date
        );
        return transaction
            ? convertCentsToDollars(parseNumber(transaction.total))
            : 0;
    });

    transactionsChartSeries.value = [
        { name: "Successful", data: successfulData },
        { name: "Pending Refund", data: pendingData },
        { name: "Completed Refund", data: refundedData },
    ];

    transactionsChartOptions.value = {
        ...transactionsChartOptions.value,
        xaxis: { categories: dates.map((date) => formatDate(date)) },
    };

    // Update distribution chart - use dollars for display
    distributionChartSeries.value = [
        convertCentsToDollars(calculateTotalSuccessful()),
        convertCentsToDollars(calculateTotalPending()),
        convertCentsToDollars(calculateTotalRefunded()),
    ];
};

// API calls
const getCurrencies = async () => {
    try {
        const res = await $server.get(page.props.api.transactions.currencies);
        if (res.status === 200) {
            currencies.value = res.data.data;
        }
    } catch (e) {
        console.error("Error fetching currencies:", e);
    }
};

const getData = async () => {
    try {
        const res = await $server.get(page.props.routes.dashboard, {
            params: search.value,
        });
        if (res.status == 200) {
            dashboard.value = res.data;

            updateCharts();
        }
    } catch (e) {
        console.error("Error fetching dashboard data:", e);
    }
};

onMounted(() => {
    getData();
    getCurrencies();
});
</script>

<style scoped>
.chart-container {
    position: relative;
}

/* Ensure charts are visible in both themes */
:deep(.apexcharts-text) {
    fill: #374151;
}

.dark :deep(.apexcharts-text) {
    fill: #d1d5db;
}

:deep(.apexcharts-legend-text) {
    color: #374151 !important;
}

.dark :deep(.apexcharts-legend-text) {
    color: #d1d5db !important;
}

:deep(.apexcharts-gridline) {
    stroke: #e5e7eb;
}

.dark :deep(.apexcharts-gridline) {
    stroke: #4b5563;
}

:deep(.apexcharts-tooltip) {
    background-color: white;
    border: 1px solid #e5e7eb;
    color: #374151;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.dark :deep(.apexcharts-tooltip) {
    background-color: #1f2937;
    border: 1px solid #4b5563;
    color: #f3f4f6;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
}

:deep(.apexcharts-tooltip-title) {
    background-color: #f9fafb;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
}

.dark :deep(.apexcharts-tooltip-title) {
    background-color: #374151;
    color: #f3f4f6;
    border-bottom: 1px solid #4b5563;
}

/* Ensure axis text is visible */
:deep(.apexcharts-xaxis-texts-g text),
:deep(.apexcharts-yaxis-texts-g text) {
    fill: #6b7280;
}

.dark :deep(.apexcharts-xaxis-texts-g text),
.dark :deep(.apexcharts-yaxis-texts-g text) {
    fill: #9ca3af;
}

/* Styles for donut chart */
:deep(.apexcharts-datalabels text) {
    fill: #374151;
    font-weight: 600;
}

.dark :deep(.apexcharts-datalabels text) {
    fill: #e5e7eb;
    font-weight: 600;
}
</style>
