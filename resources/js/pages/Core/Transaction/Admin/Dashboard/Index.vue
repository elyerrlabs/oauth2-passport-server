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
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-2 bg-blue-50 rounded-lg">
                    <i
                        class="mdi mdi-chart-areaspline text-blue-600 text-lg"
                    ></i>
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ __("Dashboard Analytics") }}
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">
                        {{
                            __(
                                "Monitor your transaction metrics and performance"
                            )
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
            <div
                v-for="card in cards"
                :key="card.label"
                class="group bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:border-blue-100"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <div
                            class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1"
                        >
                            {{ card.label }}
                        </div>
                        <div class="text-2xl font-bold text-gray-900">
                            {{ card.value }}
                        </div>
                    </div>
                    <div
                        class="p-2 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-colors duration-300"
                    >
                        <i :class="[card.icon, 'text-blue-600 text-base']"></i>
                    </div>
                </div>
                <div
                    class="mt-3 w-8 h-0.5 bg-blue-200 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                ></div>
            </div>
        </div>

        <!-- Filters Section -->
        <div
            class="bg-white rounded-xl border border-gray-100 p-6 mb-8 shadow-sm"
        >
            <div class="flex items-center space-x-2 mb-4">
                <i class="mdi mdi-tune text-gray-400 text-base"></i>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __("Filter Analytics") }}
                </h2>
            </div>

            <div
                class="grid grid-cols-1 xl:grid-cols-6 lg:grid-cols-3 md:grid-cols-2 gap-4"
            >
                <!-- Start Date -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-600">
                        {{ __("Start date") }}
                    </label>
                    <div class="relative">
                        <input
                            v-model="params.start"
                            type="date"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                        />
                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none"
                        >
                            <i
                                class="mdi mdi-calendar-start text-gray-400 text-sm"
                            ></i>
                        </div>
                    </div>
                </div>

                <!-- End Date -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-600">
                        {{ __("End date") }}
                    </label>
                    <div class="relative">
                        <input
                            v-model="params.end"
                            type="date"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                        />
                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none"
                        >
                            <i
                                class="mdi mdi-calendar-end text-gray-400 text-sm"
                            ></i>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-600">
                        {{ __("Status") }}
                    </label>
                    <div class="relative">
                        <select
                            v-model="params.status"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none"
                        >
                            <option
                                v-for="option in status"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none"
                        >
                            <i class="mdi mdi-status text-gray-400 text-sm"></i>
                        </div>
                        <div
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none"
                        >
                            <i
                                class="mdi mdi-chevron-down text-gray-400 text-sm"
                            ></i>
                        </div>
                    </div>
                </div>

                <!-- Chart Type -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-600">
                        {{ __("Chart type") }}
                    </label>
                    <div class="relative">
                        <select
                            v-model="chartType"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none"
                        >
                            <option
                                v-for="type in chartTypes"
                                :key="type"
                                :value="type"
                            >
                                {{
                                    __(
                                        type.charAt(0).toUpperCase() +
                                            type.slice(1)
                                    )
                                }}
                            </option>
                        </select>
                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none"
                        >
                            <i
                                class="mdi mdi-chart-bar text-gray-400 text-sm"
                            ></i>
                        </div>
                        <div
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none"
                        >
                            <i
                                class="mdi mdi-chevron-down text-gray-400 text-sm"
                            ></i>
                        </div>
                    </div>
                </div>

                <!-- Date Grouping -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-600">
                        {{ __("Date grouping") }}
                    </label>
                    <div class="relative">
                        <select
                            v-model="params.type"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none"
                        >
                            <option
                                v-for="type in types"
                                :key="type"
                                :value="type"
                            >
                                {{
                                    __(
                                        type.charAt(0).toUpperCase() +
                                            type.slice(1)
                                    )
                                }}
                            </option>
                        </select>
                        <div
                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none"
                        >
                            <i
                                class="mdi mdi-calendar-group text-gray-400 text-sm"
                            ></i>
                        </div>
                        <div
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none"
                        >
                            <i
                                class="mdi mdi-chevron-down text-gray-400 text-sm"
                            ></i>
                        </div>
                    </div>
                </div>

                <!-- Apply Filters Button -->
                <div class="flex items-end">
                    <button
                        @click="getData"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2 shadow-sm hover:shadow"
                    >
                        <i class="mdi mdi-filter text-xs"></i>
                        <span>{{ __("Apply") }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6"
            >
                <div class="flex items-center space-x-3 mb-3 lg:mb-0">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <i
                            class="mdi mdi-chart-line text-blue-600 text-base"
                        ></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ __("Transaction Analytics") }}
                        </h2>
                        <p class="text-gray-500 text-xs mt-1">
                            {{
                                __("Real-time insights and performance metrics")
                            }}
                        </p>
                    </div>
                </div>
                <div
                    class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-xs font-medium flex items-center space-x-1.5"
                >
                    <i
                        class="mdi mdi-sync animate-spin text-blue-600 text-xs"
                    ></i>
                    <span>{{ __("Auto-refresh: 10s") }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <apex-charts
                    width="100%"
                    height="350"
                    :type="chartType"
                    :options="chartOptions"
                    :series="chartSeries"
                    class="chart-container"
                />
            </div>
        </div>
    </v-admin-transaction-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";
import VAdminTransactionLayout from "@/layouts/VAdminTransactionLayout.vue";

export default {
    components: {
        ApexCharts,
        VAdminTransactionLayout,
    },

    data() {
        return {
            params: {
                start: null,
                end: null,
                type: "month",
                status: "successful",
            },
            chartType: "line",
            chartTypes: ["bar", "line", "area"],
            types: ["day", "month", "year"],
            transactions_by_month: [],
            plans_count: 0,
            package_count: 0,
            cards: [],
            chartSeries: [],
            chartOptions: {},
            status: [],
        };
    },

    watch: {
        "params.type"(value) {
            this.getData();
        },

        chartType(value) {
            this.renderChart();
        },
    },

    created() {
        if (!this.params.start || !this.params.end) {
            const { start, end } = this.getDefaultDates();
            this.params.start = start;
            this.params.end = end;
        }

        this.getData();
    },

    mounted() {
        this.getStatus();
        setInterval(() => {
            this.getData();
        }, 10000);
    },

    methods: {
        getDefaultDates() {
            const today = new Date();
            const end = today.toISOString().split("T")[0];

            const pastDate = new Date(today);
            pastDate.setMonth(today.getMonth() - 6);
            const start = pastDate.toISOString().split("T")[0];

            return { start, end };
        },

        loadData(data) {
            this.transactions_by_month = data["transactions_by_month"];

            this.plans_count = data["plans"];
            this.package_count = data["packages"];

            this.cards = [
                {
                    label: __("Packages"),
                    value: data["packages"],
                    icon: "mdi mdi-package-variant",
                },
                {
                    label: __("Plans"),
                    value: data["plans"],
                    icon: "mdi mdi-inventory",
                },
                {
                    label: __("Total Transactions"),
                    value: data["transactions"],
                    icon: "mdi mdi-chart-bar",
                },
            ];

            this.renderChart();
        },

        async getData() {
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.loadData(res.data.data);
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    console.error("Error:", e.response.data.message);
                }
            }
        },

        async getStatus() {
            try {
                const res = await this.$server.get(this.$page.props.status);

                if (res.status == 200) {
                    this.status = res.data.data.map((item) => ({
                        label: item.name,
                        value: item.name,
                        description: item.description,
                    }));
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    console.error("Error:", e.response.data.message);
                }
            }
        },

        renderChart() {
            this.chartSeries = [
                {
                    name: __("Transactions"),
                    data: this.transactions_by_month.map((item) => item.total),
                },
            ];

            this.chartOptions = {
                chart: {
                    height: 350,
                    type: this.chartType,
                    zoom: {
                        enabled: false,
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: true,
                        },
                    },
                    animations: {
                        enabled: true,
                        easing: "easeinout",
                        speed: 600,
                    },
                },
                colors: ["#3B82F6"],
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                    width: 2,
                },
                grid: {
                    borderColor: "#F3F4F6",
                    strokeDashArray: 3,
                },
                xaxis: {
                    categories: this.transactions_by_month.map(
                        (item) => item.month
                    ),
                    labels: {
                        style: {
                            colors: "#6B7280",
                            fontSize: "11px",
                        },
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: "#6B7280",
                            fontSize: "11px",
                        },
                    },
                },
                tooltip: {
                    theme: "light",
                    style: {
                        fontSize: "12px",
                    },
                },
            };
        },

        formatDate(dateStr) {
            const options = { year: "numeric", month: "short", day: "numeric" };
            return new Date(dateStr).toLocaleDateString("en-US", options);
        },
    },
};
</script>

<style scoped>
.chart-container {
    border-radius: 8px;
}
</style>
