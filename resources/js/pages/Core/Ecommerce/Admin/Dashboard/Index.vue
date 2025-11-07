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
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-3 md:p-6"
        >
            <!-- Total Sales Card -->
            <div
                class="stat-card bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
            >
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600">Total Sales</div>
                            <div class="text-xl font-bold">
                                {{ dashboard.currency_symbol }}
                                {{ dashboard.transactions_total }}
                            </div>
                            <div
                                class="text-xs text-green-600 flex items-center mt-1"
                            >
                                <i class="fas fa-arrow-up mr-1"></i>
                                {{ __("last month") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today Sales Card -->
            <div
                class="stat-card bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
            >
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600">
                                {{ __("Today Sales") }}
                            </div>
                            <div class="text-xl font-bold">
                                {{ dashboard.currency_symbol }}
                                {{ dashboard.transactions_today }}
                            </div>
                            <div
                                class="text-xs text-green-600 flex items-center mt-1"
                            >
                                <i class="fas fa-arrow-up mr-1"></i>
                                {{ __("from yesterday") }}
                            </div>
                        </div>
                        <div class="text-green-500 text-2xl">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div
                class="stat-card bg-orange-50 border-l-4 border-orange-500 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
            >
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600">
                                {{ __("Total Products") }}
                            </div>
                            <div class="text-xl font-bold">
                                {{ dashboard.products_stock_total }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ dashboard.products_lower_stock }}
                                {{ __("low in stock") }}
                            </div>
                        </div>
                        <div class="text-orange-500 text-2xl">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Orders Card -->
            <div
                class="stat-card bg-purple-50 border-l-4 border-purple-500 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
            >
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600">
                                {{ __("Pending Orders") }}
                            </div>
                            <div class="text-xl font-bold">
                                {{ dashboard.products_pending }}
                            </div>
                            <div
                                class="text-xs text-red-600 flex items-center mt-1"
                            >
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ __("high priority") }}
                            </div>
                        </div>
                        <div class="text-purple-500 text-2xl">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 gap-6 p-3 md:p-6">
            <div>
                <div
                    class="chart-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
                >
                    <div
                        class="flex justify-between items-center p-6 border-b border-gray-100"
                    >
                        <div class="text-lg font-semibold">
                            {{ __("Sales Overview (Last 30 Days)") }}
                        </div>
                        <div class="relative">
                            <button
                                class="text-gray-400 hover:text-gray-600 p-2"
                            >
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <apexchart
                            type="area"
                            height="350"
                            :options="salesChartOptions"
                            :series="salesChartSeries"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-3 md:p-6">
            <div>
                <div
                    class="chart-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
                >
                    <div
                        class="flex justify-between items-center p-6 border-b border-gray-100"
                    >
                        <div class="text-lg font-semibold">
                            {{ __("Top Selling Products") }}
                        </div>
                    </div>
                    <div class="p-6">
                        <apexchart
                            type="bar"
                            height="300"
                            :options="topProductsChartOptions"
                            :series="topProductsChartSeries"
                        />
                    </div>
                </div>
            </div>

            <div>
                <div
                    class="chart-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
                >
                    <div
                        class="flex justify-between items-center p-6 border-b border-gray-100"
                    >
                        <div class="text-lg font-semibold">
                            {{ __("Today's Sales Status") }}
                        </div>
                    </div>
                    <div class="p-6">
                        <apexchart
                            type="radialBar"
                            height="300"
                            :options="todaySalesChartOptions"
                            :series="todaySalesChartSeries"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-3 md:p-6">
            <div>
                <div
                    class="data-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
                >
                    <div
                        class="flex justify-between items-center p-6 border-b border-gray-100"
                    >
                        <div class="text-lg font-semibold">
                            {{ __("Recent Orders") }}
                        </div>
                        <button
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        >
                            {{ __("View All") }}
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr
                                    class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    <th class="px-6 py-3">{{ __("Order") }}</th>
                                    <th class="px-6 py-3">
                                        {{ __("Customer") }}
                                    </th>
                                    <th class="px-6 py-3">{{ __("Date") }}</th>
                                    <th class="px-6 py-3 text-right">
                                        {{ __("Total") }}
                                    </th>
                                    <th class="px-6 py-3 text-center">
                                        {{ __("Status") }}
                                    </th>
                                    <th class="px-6 py-3 text-center">
                                        {{ __("Actions") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr
                                    v-for="checkout in checkouts"
                                    :key="checkout.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{ checkout.id }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ checkout.customer }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ checkout.date }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right"
                                    >
                                        {{ checkout.total }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center"
                                    >
                                        <span
                                            :class="`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(
                                                checkout.status
                                            )}`"
                                        >
                                            {{ checkout.status }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium"
                                    >
                                        <button
                                            @click="viewOrder(checkout)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div>
                <div
                    class="data-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
                >
                    <div
                        class="flex justify-between items-center p-6 border-b border-gray-100"
                    >
                        <div class="text-lg font-semibold">
                            {{ __("Top Selling Products") }}
                        </div>
                        <button
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        >
                            {{ __("View All") }}
                        </button>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="product in topProducts"
                            :key="product.id"
                            class="flex items-center p-4 hover:bg-gray-50"
                        >
                            <div
                                class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden"
                            >
                                <img
                                    :src="product.image"
                                    :alt="product.name"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ product.name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ product.category }}
                                </div>
                            </div>
                            <div class="ml-4 text-right">
                                <div class="text-sm font-medium text-blue-600">
                                    {{ product.price }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ __("Sold") }}: {{ product.sold }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import VAdminLayout from "../../Components/VAdminLayout.vue";

export default {
    components: {
        apexchart: VueApexCharts,
        VAdminLayout,
    },

    data() {
        return {
            leftDrawerOpen: true,

            revenue: [],
            dashboard: [],
            checkouts: [],

            salesChartOptions: {},
            salesChartSeries: [],

            revenueChartOptions: {},
            revenueChartSeries: [],

            topProductsChartOptions: {},
            topProductsChartSeries: [],

            todaySalesChartOptions: {
                chart: { type: "radialBar" },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 225,
                        hollow: {
                            margin: 0,
                            size: "70%",
                            background: "#fff",
                            position: "front",
                        },
                        track: {
                            background: "#f1f1f1",
                            strokeWidth: "67%",
                            margin: 0,
                        },
                        dataLabels: {
                            show: true,
                            name: {
                                offsetY: -10,
                                color: "#888",
                                fontSize: "13px",
                            },
                            value: {
                                formatter: (val) =>
                                    this.dashboard.currency_symbol + val,
                                color: "#111",
                                fontSize: "30px",
                                show: true,
                            },
                        },
                    },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "dark",
                        type: "horizontal",
                        shadeIntensity: 0.5,
                        gradientToColors: ["#4CAF50"],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                    },
                },
                stroke: { lineCap: "round" },
                labels: [__("Today Sales Goal")],
            },

            todaySalesChartSeries: [],

            topProducts: [],
        };
    },

    created() {
        this.getData();
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        async getData() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.dashboard
                );

                if (res.status == 200) {
                    this.dashboard = res.data;
                    this.checkouts = res.data.checkouts;
                    this.topProducts = res.data.top_products;
                    this.revenue = res.data.revenue;
                    this.todaySalesChartSeries = [res.data.transactions_today];
                    this.renderSales();
                    this.renderTopProducts();
                    this.renderRevenue();
                }
            } catch (e) {}
        },

        renderTopProducts() {
            this.topProductsChartOptions = {
                chart: { type: "bar", toolbar: { show: false } },
                plotOptions: {
                    bar: { borderRadius: 4, horizontal: true },
                },
                colors: ["#2196F3"],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: this.topProducts.map((item) => item.category),
                },
                tooltip: {
                    y: {
                        formatter: (val) => val + " " + __("units sold"),
                    },
                },
            };

            this.topProductsChartSeries = [
                {
                    name: __("Units Sold"),
                    data: this.topProducts.map((item) => item.sold),
                },
            ];
        },

        renderRevenue() {
            const totalRevenue = this.revenue.reduce(
                (sum, item) => sum + item.total,
                0
            );

            this.revenueChartOptions = {
                chart: { type: "donut" },
                labels: this.revenue.map((item) => item.name),
                colors: ["#2196F3", "#4CAF50", "#FF9800", "#9C27B0", "#607D8B"],
                legend: { position: "bottom" },
                dataLabels: { enabled: true },
                tooltip: {
                    y: {
                        formatter: (val) =>
                            this.dashboard.currency_symbol +
                            " " +
                            (val / 100).toFixed(2),
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
                                        this.dashboard.currency_symbol +
                                        " " +
                                        (val / 100).toFixed(2),
                                },
                                total: {
                                    show: true,
                                    label: __("Total Revenue"),
                                    formatter: () =>
                                        this.dashboard.currency_symbol +
                                        " " +
                                        (totalRevenue / 100).toFixed(2),
                                },
                            },
                        },
                    },
                },
            };

            this.revenueChartSeries = this.revenue.map((item) => item.total);
        },

        renderSales() {
            this.salesChartSeries = [
                {
                    name: __("Sales"),
                    data: this.dashboard.transactions.map((item) => item.total),
                },
            ];

            this.salesChartOptions = {
                chart: {
                    type: "area",
                    height: 350,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true,
                        },
                    },
                },
                colors: ["#4CAF50"],
                dataLabels: {
                    enabled: false,
                    formatter: (val) => {
                        return (
                            this.dashboard.currency_symbol +
                            " " +
                            (val / 100).toFixed(2)
                        );
                    },
                },
                stroke: { curve: "smooth", width: 2 },
                xaxis: {
                    categories: this.dashboard.transactions.map(
                        (item) => item.date
                    ),

                    labels: { show: false },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    labels: {
                        formatter: (val) =>
                            this.dashboard.currency_symbol +
                            " " +
                            (val / 100).toFixed(2),
                    },
                },
                tooltip: {
                    y: {
                        formatter: (val) =>
                            this.dashboard.currency_symbol +
                            " " +
                            (val / 100).toFixed(2),
                    },
                },
                grid: { borderColor: "#f1f1f1" },
            };
        },

        getStatusColor(status) {
            switch (status) {
                case "successful":
                    return "bg-green-100 text-green-800";
                case "pending":
                    return "bg-orange-100 text-orange-800";
                default:
                    return "bg-gray-100 text-gray-800";
            }
        },

        viewOrder(order) {
            window.location.href = order.link;
        },
    },
};
</script>
