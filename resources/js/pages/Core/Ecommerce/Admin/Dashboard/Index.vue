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
                <div class="px-4 sm:px-6 py-4">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div>
                            <h1
                                class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Dashboard Overview") }}
                            </h1>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-300 mt-1"
                            >
                                {{
                                    __(
                                        "Welcome back! Here's what's happening with your store today."
                                    )
                                }}
                            </p>
                        </div>
                        <div class="flex items-center">
                            <!-- Refresh Button -->
                            <button
                                @click="getData"
                                class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors"
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-4 sm:p-6">
                <!-- Stats Grid -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"
                >
                    <!-- Total Sales -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg"
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
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    {{ dashboard.currency_symbol
                                    }}{{
                                        formatNumber(
                                            dashboard.transactions_total
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Total Sales") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("All time revenue") }}
                        </div>
                    </div>

                    <!-- Today Sales -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-green-100 dark:bg-green-900 rounded-lg"
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
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    {{ dashboard.currency_symbol
                                    }}{{
                                        formatNumber(
                                            dashboard.transactions_today
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Today Sales") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("Today's revenue") }}
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg"
                            >
                                <svg
                                    class="w-5 h-5 text-orange-600 dark:text-orange-400"
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
                                    class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    {{
                                        formatNumber(
                                            dashboard.products_stock_total
                                        )
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Total Products") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("In stock items") }}
                        </div>
                    </div>

                    <!-- Pending Orders -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg"
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
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    {{
                                        formatNumber(dashboard.products_pending)
                                    }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Pending Orders") }}
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __("Awaiting processing") }}
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Sales Overview Chart -->
                    <div
                        class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-base sm:text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Sales Overview (Last 30 Days)") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="area"
                                height="300"
                                :options="salesChartOptions"
                                :series="salesChartSeries"
                            />
                        </div>
                    </div>

                    <!-- Revenue Distribution -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-base sm:text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Revenue Distribution") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="donut"
                                height="300"
                                :options="revenueChartOptions"
                                :series="revenueChartSeries"
                            />
                        </div>
                    </div>
                </div>

                <!-- Second Row Charts -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <!-- Top Selling Products -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-base sm:text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Top Selling Products") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="bar"
                                height="250"
                                :options="topProductsChartOptions"
                                :series="topProductsChartSeries"
                            />
                        </div>
                    </div>

                    <!-- Today's Sales Progress -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-base sm:text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Today's Sales Progress") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="radialBar"
                                height="250"
                                :options="todaySalesChartOptions"
                                :series="todaySalesChartSeries"
                            />
                        </div>
                    </div>
                </div>

                <!-- Data Tables Section -->
                <div class="space-y-6">
                    <!-- Recent Orders - Mobile Cards / Desktop Table -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
                    >
                        <div
                            class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ __("Recent Orders") }}
                                </h3>
                                <button
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors"
                                >
                                    {{ __("View All") }}
                                </button>
                            </div>
                        </div>

                        <!-- Mobile View - Cards -->
                        <div
                            class="sm:hidden divide-y divide-gray-200 dark:divide-gray-600"
                        >
                            <div
                                v-for="checkout in checkouts"
                                :key="checkout.id"
                                class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            >
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <div
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        #{{ checkout.id }}
                                    </div>
                                    <span
                                        class="text-xs font-medium px-2 py-1 rounded-full"
                                        :class="
                                            getStatusClasses(checkout.status)
                                        "
                                    >
                                        {{ checkout.status }}
                                    </span>
                                </div>
                                <div
                                    class="text-sm text-gray-600 dark:text-gray-300 mb-2"
                                >
                                    {{ checkout.customer }}
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <div
                                        class="text-gray-500 dark:text-gray-400"
                                    >
                                        {{ formatDate(checkout.date) }}
                                    </div>
                                    <div
                                        class="font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ dashboard.currency_symbol
                                        }}{{ checkout.total }}
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button
                                        @click="viewOrder(checkout)"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors"
                                        :title="__('View order details')"
                                    >
                                        {{ __("View Details") }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop View - Table -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr
                                        class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600"
                                    >
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Order") }}
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Customer") }}
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Date") }}
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Total") }}
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Status") }}
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Actions") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 dark:divide-gray-600"
                                >
                                    <tr
                                        v-for="checkout in checkouts"
                                        :key="checkout.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                                    >
                                        <td
                                            class="px-4 sm:px-6 py-4 text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            #{{ checkout.id }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-sm text-gray-600 dark:text-gray-300"
                                        >
                                            {{ checkout.customer }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-sm text-gray-600 dark:text-gray-300"
                                        >
                                            {{ formatDate(checkout.date) }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-sm text-gray-900 dark:text-white text-right"
                                        >
                                            {{ dashboard.currency_symbol
                                            }}{{ checkout.total }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-center"
                                        >
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full"
                                                :class="
                                                    getStatusClasses(
                                                        checkout.status
                                                    )
                                                "
                                            >
                                                <span
                                                    class="w-2 h-2 rounded-full mr-1.5"
                                                    :class="
                                                        getStatusDotClass(
                                                            checkout.status
                                                        )
                                                    "
                                                ></span>
                                                {{ checkout.status }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-center"
                                        >
                                            <button
                                                @click="viewOrder(checkout)"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors"
                                                :title="
                                                    __('View order details')
                                                "
                                            >
                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Products List -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
                    >
                        <div
                            class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50"
                        >
                            <div class="flex items-center justify-between">
                                <h3
                                    class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ __("Top Selling Products") }}
                                </h3>
                                <button
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors"
                                >
                                    {{ __("View All") }}
                                </button>
                            </div>
                        </div>
                        <div
                            class="divide-y divide-gray-200 dark:divide-gray-600"
                        >
                            <div
                                v-for="product in topProducts"
                                :key="product.id"
                                class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            >
                                <div
                                    class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-600"
                                >
                                    <img
                                        :src="
                                            product.image ||
                                            '/images/placeholder-product.png'
                                        "
                                        :alt="product.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div
                                        class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                    >
                                        {{ product.name }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ product.category }}
                                    </div>
                                </div>
                                <div class="ml-3 text-right">
                                    <div
                                        class="text-sm font-semibold text-blue-600 dark:text-blue-400"
                                    >
                                        {{ product.price }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Sold") }}: {{ product.sold }}
                                    </div>
                                </div>
                            </div>
                        </div>
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

const page = usePage();
const apexchart = VueApexCharts;

// Refs
const isDark = ref(false);
const dashboard = ref({
    currency_symbol: "",
    transactions_total: 0,
    transactions_today: 0,
    products_stock_total: 0,
    products_pending: 0,
    products_lower_stock: 0,
    transactions: [],
    checkouts: [],
    top_products: [],
});

const revenue = ref([]);
const checkouts = ref([]);
const topProducts = ref([]);

// Graphics options
const salesChartOptions = ref({
    chart: {
        type: "area",
        height: 300,
        toolbar: { show: true },
        background: "transparent",
    },
    colors: ["#3B82F6"],
    dataLabels: { enabled: false },
    stroke: { curve: "smooth", width: 2 },
    xaxis: { categories: [] },
    yaxis: {
        labels: {
            formatter: (val) =>
                dashboard.value.currency_symbol + formatNumber(val),
        },
    },
    tooltip: {
        y: {
            formatter: (val) =>
                dashboard.value.currency_symbol + formatNumber(val),
        },
    },
});

const salesChartSeries = ref([]);

const revenueChartOptions = ref({
    chart: {
        type: "donut",
        background: "transparent",
    },
    labels: [],
    colors: ["#3B82F6", "#10B981", "#F59E0B", "#8B5CF6", "#EF4444"],
    legend: { position: "bottom" },
    dataLabels: { enabled: true },
    tooltip: {
        y: {
            formatter: (val) =>
                dashboard.value.currency_symbol + formatNumber(val),
        },
    },
});

const revenueChartSeries = ref([]);

const topProductsChartOptions = ref({
    chart: {
        type: "bar",
        toolbar: { show: false },
        background: "transparent",
    },
    plotOptions: {
        bar: { borderRadius: 4, horizontal: true },
    },
    colors: ["#10B981"],
    dataLabels: { enabled: false },
    xaxis: { categories: [] },
    tooltip: {
        y: { formatter: (val) => val + " " + __("units sold") },
    },
});

const topProductsChartSeries = ref([]);

const todaySalesChartOptions = ref({
    chart: {
        type: "radialBar",
        background: "transparent",
    },
    plotOptions: {
        radialBar: {
            hollow: { size: "70%" },
            dataLabels: {
                name: { fontSize: "12px" },
                value: {
                    formatter: (val) =>
                        dashboard.value.currency_symbol + formatNumber(val),
                    fontSize: "24px",
                },
            },
        },
    },
    colors: ["#10B981"],
    labels: [__("Today Sales")],
});

const todaySalesChartSeries = ref([0]);

// Functions
const detectTheme = () => {
    isDark.value = document.documentElement.classList.contains("dark");
};

const formatNumber = (num) => {
    return parseFloat(num || 0).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
};

const getData = async () => {
    try {
        const res = await $server.get(page.props.routes.dashboard);
        if (res.status == 200) {
            dashboard.value = res.data;
            checkouts.value = res.data.checkouts || [];
            topProducts.value = res.data.top_products || [];
            revenue.value = res.data.revenue || [];
            todaySalesChartSeries.value = [res.data.transactions_today || 0];
            renderSales();
            renderTopProducts();
            renderRevenue();
        }
    } catch (e) {
        console.error("Error fetching dashboard data:", e);
    }
};

const renderTopProducts = () => {
    topProductsChartOptions.value = {
        ...topProductsChartOptions.value,
        xaxis: {
            categories: topProducts.value.map(
                (item) => item.name || item.category
            ),
        },
    };

    topProductsChartSeries.value = [
        {
            name: __("Units Sold"),
            data: topProducts.value.map((item) => item.sold || 0),
        },
    ];
};

const renderRevenue = () => {
    revenueChartOptions.value = {
        ...revenueChartOptions.value,
        labels: revenue.value.map((item) => item.name || "Unknown"),
    };

    revenueChartSeries.value = revenue.value.map((item) => item.total || 0);
};

const renderSales = () => {
    salesChartSeries.value = [
        {
            name: __("Sales"),
            data: (dashboard.value.transactions || []).map(
                (item) => item.total || 0
            ),
        },
    ];

    salesChartOptions.value = {
        ...salesChartOptions.value,
        xaxis: {
            categories: (dashboard.value.transactions || []).map(
                (item) => item.date || ""
            ),
        },
    };
};

const getStatusClasses = (status) => {
    const statusClasses = {
        successful:
            "bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300",
        pending:
            "bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300",
        failed: "bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300",
        refunded:
            "bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300",
    };

    return (
        statusClasses[status] ||
        "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"
    );
};

const getStatusDotClass = (status) => {
    const dotClasses = {
        successful: "bg-green-500",
        pending: "bg-yellow-500",
        failed: "bg-red-500",
        refunded: "bg-blue-500",
    };

    return dotClasses[status] || "bg-gray-500";
};

const viewOrder = (order) => {
    if (order.link) {
        window.location.href = order.link;
    }
};

onMounted(() => {
    detectTheme();
    getData();
});
</script>

<style scoped>
.chart-container {
    position: relative;
}
</style>
