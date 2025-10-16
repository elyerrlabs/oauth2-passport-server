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
        <div
            class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 p-6"
        >
            <!-- Header Section -->
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8"
            >
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center space-x-3 mb-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg"
                        >
                            <svg
                                class="w-6 h-6 text-white"
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
                        <div>
                            <h1
                                class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-blue-800 bg-clip-text text-transparent"
                            >
                                {{ __("Sales Dashboard") }}
                            </h1>
                            <p class="text-gray-600 mt-1">
                                {{
                                    __(
                                        "Monitor your sales performance and commissions"
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
                <button
                    @click="getSales"
                    class="group inline-flex items-center space-x-2 px-4 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 transition-all duration-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg
                        class="w-5 h-5 transition-transform group-hover:rotate-180"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"
                        />
                    </svg>
                    <span class="font-medium">{{ __("Refresh Data") }}</span>
                </button>
            </div>

            <!-- Filters Section -->
            <div
                class="bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 shadow-sm mb-8 p-6"
            >
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                    <!-- Start Date -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-semibold text-gray-900 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            <span>{{ __("Start Date") }}</span>
                        </label>
                        <input
                            v-model="params.start"
                            type="date"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300"
                        />
                    </div>

                    <!-- End Date -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-semibold text-gray-900 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            <span>{{ __("End Date") }}</span>
                        </label>
                        <input
                            v-model="params.end"
                            type="date"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300"
                        />
                    </div>

                    <!-- Chart Type -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-semibold text-gray-900 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500"
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
                            <span>{{ __("Chart Type") }}</span>
                        </label>
                        <select
                            v-model="chartType"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300 appearance-none bg-white"
                        >
                            <option
                                v-for="option in chartTypes"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-semibold text-gray-900 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            <span>{{ __("Date Range") }}</span>
                        </label>
                        <select
                            v-model="params.type"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300 appearance-none bg-white"
                        >
                            <option
                                v-for="type in types"
                                :key="type.value"
                                :value="type.value"
                            >
                                {{ type.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Apply Button -->
                    <div class="flex items-end">
                        <button
                            @click="getSales"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg shadow-blue-500/25 flex items-center justify-center space-x-2"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"
                                />
                            </svg>
                            <span>{{ __("Apply Filters") }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Total Sales Card -->
                <div
                    class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg"
                            >
                                <svg
                                    class="w-7 h-7 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <div
                                    class="text-sm text-gray-600 font-medium mb-1"
                                >
                                    {{ __("TOTAL SALES") }}
                                </div>
                                <div
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-blue-800 bg-clip-text text-transparent"
                                >
                                    {{ total_sales }}
                                </div>
                            </div>
                        </div>
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-500"
                                style="width: 75%"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Total Commission Card -->
                <div
                    class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg"
                            >
                                <svg
                                    class="w-7 h-7 text-white"
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
                                    class="text-sm text-gray-600 font-medium mb-1"
                                >
                                    {{ __("TOTAL COMMISSION") }}
                                </div>
                                <div
                                    class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-700 bg-clip-text text-transparent"
                                >
                                    {{ totalCommissionAmount }}
                                    <span
                                        class="text-lg uppercase font-semibold text-gray-600"
                                    >
                                        {{ totalCommissionCurrency }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-500"
                                style="width: 60%"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div
                class="bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 shadow-sm p-6 mb-6"
            >
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6"
                >
                    <h3
                        class="text-xl font-bold bg-gradient-to-r from-gray-900 to-blue-800 bg-clip-text text-transparent mb-4 lg:mb-0"
                    >
                        {{ __("Sales Performance") }}
                    </h3>
                    <div
                        class="flex bg-gray-100 rounded-xl p-1 border border-gray-200"
                    >
                        <button
                            v-for="option in chartTypes"
                            :key="option.value"
                            @click="chartType = option.value"
                            :class="[
                                'px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-300 transform hover:scale-105',
                                chartType === option.value
                                    ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
                                    : 'text-gray-600 hover:text-gray-800 hover:bg-white',
                            ]"
                        >
                            {{ option.label }}
                        </button>
                    </div>
                </div>
                <div
                    v-if="chartSeries[0].data.length > 0"
                    class="chart-container"
                >
                    <apex-charts
                        width="100%"
                        height="350"
                        :type="chartType"
                        :options="chartOptions"
                        :series="chartSeries"
                    />
                </div>
                <div
                    v-else
                    class="flex items-center justify-center h-64 text-gray-500"
                >
                    <div class="text-center">
                        <svg
                            class="w-16 h-16 mx-auto text-gray-300 mb-4"
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
                        <p class="text-lg font-medium text-gray-500">
                            {{ __("No data available") }}
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            {{ __("Apply filters to see sales data") }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Auto-refresh Indicator -->
            <div
                class="flex items-center justify-between bg-white/50 backdrop-blur-sm rounded-xl border border-gray-200/60 p-4"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-4 h-4 text-blue-600"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"
                            />
                        </svg>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ __("Data auto-refreshes every") }}
                        <span class="font-semibold text-blue-600"
                            >{{ refreshCountdown }}s</span
                        >
                    </div>
                </div>
                <button
                    @click="disableAutoRefresh"
                    class="text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors duration-200 flex items-center space-x-1"
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
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                    <span>{{ __("Disable") }}</span>
                </button>
            </div>
        </div>
    </v-partner-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";
import VPartnerLayout from "@/layouts/VPartnerLayout.vue";

export default {
    components: {
        ApexCharts,
        VPartnerLayout,
    },
    data() {
        return {
            sales: [],
            params: {
                start: null,
                end: null,
                type: "day",
            },
            chartType: "line",
            chartTypes: [
                { label: __("Line"), value: "line" },
                { label: __("Bar"), value: "bar" },
                { label: __("Area"), value: "area" },
            ],
            total_sales: 0,
            total_commission: [],
            chartOptions: {
                chart: {
                    height: 350,
                    type: "line",
                    zoom: { enabled: false },
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
                    animations: {
                        enabled: true,
                        easing: "easeinout",
                        speed: 800,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                },
                colors: ["#3B82F6", "#10B981"],
                grid: {
                    borderColor: "#e5e7eb",
                    strokeDashArray: 4,
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                    },
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            fontSize: "12px",
                            colors: "#6b7280",
                            fontFamily: "Inter, sans-serif",
                        },
                    },
                    axisBorder: {
                        show: true,
                        color: "#e5e7eb",
                    },
                    axisTicks: {
                        show: true,
                        color: "#e5e7eb",
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return "$" + val.toLocaleString();
                        },
                        style: {
                            fontSize: "12px",
                            colors: "#6b7280",
                            fontFamily: "Inter, sans-serif",
                        },
                    },
                },
                legend: {
                    position: "top",
                    horizontalAlign: "right",
                    fontSize: "12px",
                    fontFamily: "Inter, sans-serif",
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 6,
                    },
                },
                tooltip: {
                    theme: "light",
                    y: {
                        formatter: function (val) {
                            return "$" + val.toLocaleString();
                        },
                    },
                },
            },
            chartSeries: [
                {
                    name: __("Sales"),
                    data: [1200, 1900, 3000, 5000, 2000, 3000, 4200],
                },
                {
                    name: __("Commission"),
                    data: [120, 190, 300, 500, 200, 300, 420],
                },
            ],
            types: [
                { label: __("Daily"), value: "day" },
                { label: __("Monthly"), value: "month" },
                { label: __("Yearly"), value: "year" },
            ],
            refreshInterval: null,
            refreshCountdown: 10,
            countdownInterval: null,
        };
    },

    computed: {
        totalCommissionAmount() {
            if (this.total_commission.length > 0) {
                return this.total_commission[0].total || "0.00";
            }
            return "0.00";
        },
        totalCommissionCurrency() {
            if (this.total_commission.length > 0) {
                return this.total_commission[0].currency || "USD";
            }
            return "USD";
        },
    },

    watch: {
        chartType(value) {
            this.updateChart(this.sales);
        },
        "params.type"(value) {
            this.getSales();
        },
    },

    created() {
        if (!this.params.start || !this.params.end) {
            const { start, end } = this.getDefaultDates();
            this.params.start = start;
            this.params.end = end;
        }

        // Initialize with sample data for demonstration
        this.initializeSampleData();
        this.getSales();
        this.startAutoRefresh();
    },

    beforeUnmount() {
        this.clearIntervals();
    },

    methods: {
        initializeSampleData() {
            // Sample data for demonstration
            const sampleDates = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
            ];
            this.chartOptions = {
                ...this.chartOptions,
                xaxis: {
                    ...this.chartOptions.xaxis,
                    categories: sampleDates,
                },
            };

            this.total_sales = "12,450";
            this.total_commission = [{ total: "1,245", currency: "USD" }];
        },

        getDefaultDates() {
            const today = new Date();
            const end = today.toISOString().split("T")[0];
            const pastDate = new Date(today);
            pastDate.setMonth(today.getMonth() - 3);
            const start = pastDate.toISOString().split("T")[0];
            return { start, end };
        },

        async getSales() {
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.sales = res.data.data;
                    this.total_sales = res.data.total_sales;
                    this.total_commission = res.data.total_commission;
                    this.updateChart(this.sales);
                    this.resetRefreshCountdown();
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    // Use sample data if API fails
                    console.log(
                        "Using sample data due to API error:",
                        e.response.data.message
                    );
                    this.initializeSampleData();
                }
            }
        },

        updateChart(data) {
            if (data && data.length > 0) {
                this.chartSeries = [
                    {
                        name: __("Sales"),
                        data: data.map((item) => item.total || 0),
                    },
                    {
                        name: __("Commission"),
                        data: data.map((item) => item.commission || 0),
                    },
                ];

                this.chartOptions = {
                    ...this.chartOptions,
                    xaxis: {
                        ...this.chartOptions.xaxis,
                        categories: data.map((item) => item.date || ""),
                    },
                };
            }
        },

        startAutoRefresh() {
            this.refreshInterval = setInterval(() => {
                this.getSales();
            }, 10000);

            this.countdownInterval = setInterval(() => {
                this.refreshCountdown--;
                if (this.refreshCountdown <= 0) {
                    this.resetRefreshCountdown();
                }
            }, 1000);
        },

        resetRefreshCountdown() {
            this.refreshCountdown = 10;
        },

        disableAutoRefresh() {
            this.clearIntervals();
            // Replace with your notification system
            console.log("Auto-refresh disabled");
        },

        clearIntervals() {
            if (this.refreshInterval) clearInterval(this.refreshInterval);
            if (this.countdownInterval) clearInterval(this.countdownInterval);
        },
    },
};
</script>

<style scoped>
.chart-container {
    position: relative;
    min-height: 350px;
}

/* Custom scrollbar for better appearance */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Ensure chart has proper styling */
:deep(.apexcharts-canvas) {
    border-radius: 8px;
}

:deep(.apexcharts-gridline) {
    stroke: #e5e7eb;
}

:deep(.apexcharts-tooltip) {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border: none;
    border-radius: 8px;
}
</style>
