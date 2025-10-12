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
        <div class="p-4">
            <!-- Header Section   -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ __("Sales Dashboard") }}
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">
                        {{
                            __("Monitor your sales performance and commissions")
                        }}
                    </p>
                </div>
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
            </div>

            <!-- Filters Section - Más compacto -->
            <div class="bg-white rounded-lg border border-gray-200 mb-6 p-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-700 mb-1"
                        >
                            {{ __("Start Date") }}
                        </label>
                        <input
                            v-model="params.start"
                            type="date"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-700 mb-1"
                        >
                            {{ __("End Date") }}
                        </label>
                        <input
                            v-model="params.end"
                            type="date"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-700 mb-1"
                        >
                            {{ __("Chart Type") }}
                        </label>
                        <select
                            v-model="chartType"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-700 mb-1"
                        >
                            {{ __("Date Range") }}
                        </label>
                        <select
                            v-model="params.type"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                    <div class="flex items-end">
                        <button
                            @click="getSales"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 text-sm rounded-md transition-colors flex items-center justify-center space-x-2"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"
                                />
                            </svg>
                            <span>{{ __("Apply") }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- KPI Cards - Más compactas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Total Sales Card -->
                <div
                    class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg
                                    class="w-5 h-5 text-blue-600"
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
                                    class="text-xs text-gray-500 uppercase tracking-wide font-medium"
                                >
                                    {{ __("TOTAL SALES") }}
                                </div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ formatCurrency(total_sales) }}
                                </div>
                            </div>
                        </div>
                        <div class="w-16 bg-gray-200 rounded-full h-1.5">
                            <div
                                class="bg-blue-600 h-1.5 rounded-full"
                                style="width: 70%"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Total Commission Card -->
                <div
                    class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-50 rounded-lg">
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
                                    {{ __("TOTAL COMMISSION") }}
                                </div>
                                <div
                                    v-if="total_commission.length"
                                    class="text-xl font-bold text-green-600"
                                >
                                    {{
                                        formatCurrency(
                                            total_commission[0].total
                                        )
                                    }}
                                    {{ total_commission[0].currency }}
                                </div>
                                <div
                                    v-else
                                    class="text-xl font-bold text-green-600"
                                >
                                    $0.00
                                </div>
                            </div>
                        </div>
                        <div class="w-16 bg-gray-200 rounded-full h-1.5">
                            <div
                                class="bg-green-600 h-1.5 rounded-full"
                                style="width: 50%"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section - Más compacto -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __("Sales Performance") }}
                    </h3>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button
                            v-for="option in chartTypes"
                            :key="option.value"
                            @click="chartType = option.value"
                            :class="[
                                'px-3 py-1 text-xs font-medium rounded-md transition-colors',
                                chartType === option.value
                                    ? 'bg-blue-600 text-white shadow-sm'
                                    : 'text-gray-600 hover:text-gray-800',
                            ]"
                        >
                            {{ option.label }}
                        </button>
                    </div>
                </div>
                <apex-charts
                    width="100%"
                    height="320"
                    :type="chartType"
                    :options="chartOptions"
                    :series="chartSeries"
                />
            </div>

            <!-- Data Refresh Indicator - Más discreto -->
            <div class="flex justify-end mt-3">
                <div class="text-xs text-gray-500 flex items-center">
                    <svg
                        class="w-3 h-3 mr-1"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"
                        />
                    </svg>
                    {{ __("Auto-refresh in") }} {{ refreshCountdown }}s
                    <button
                        @click="disableAutoRefresh"
                        class="ml-2 text-blue-600 hover:text-blue-800 text-xs font-medium"
                    >
                        {{ __("Disable") }}
                    </button>
                </div>
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
                type: "month",
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
                    height: 320,
                    type: "line",
                    zoom: { enabled: false },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false,
                        },
                    },
                },
                dataLabels: {
                    enabled: false, // Desactivado para mejor legibilidad
                },
                stroke: {
                    curve: "smooth",
                    width: 2,
                },
                colors: ["#3B82F6", "#10B981"],
                grid: {
                    borderColor: "#f1f5f9",
                    strokeDashArray: 4,
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            fontSize: "11px",
                            colors: "#6b7280",
                        },
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return "$" + val.toLocaleString();
                        },
                        style: {
                            fontSize: "11px",
                            colors: "#6b7280",
                        },
                    },
                },
                legend: {
                    position: "top",
                    horizontalAlign: "right",
                    fontSize: "12px",
                },
                tooltip: {
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
                    data: [],
                },
                {
                    name: __("Commission"),
                    data: [],
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
        this.getSales();
        this.startAutoRefresh();
    },

    beforeUnmount() {
        this.clearIntervals();
    },

    methods: {
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
                    $notify.error(e.response.data.message);
                }
            }
        },

        updateChart(data) {
            this.chartSeries = [
                {
                    name: __("Sales"),
                    data: data.map((item) => item.total),
                },
                {
                    name: __("Commission"),
                    data: data.map((item) => item.commission),
                },
            ];

            this.chartOptions = {
                ...this.chartOptions,
                xaxis: {
                    ...this.chartOptions.xaxis,
                    categories: data.map((item) => item.date),
                },
            };
        },

        formatCurrency(value) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
            }).format(value);
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
            $notify.error(__("Auto-refresh disabled"));
        },

        clearIntervals() {
            if (this.refreshInterval) clearInterval(this.refreshInterval);
            if (this.countdownInterval) clearInterval(this.countdownInterval);
        },
    },
};
</script>
