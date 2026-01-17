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
    <v-admin-layout>
        <div
            class="admin-dashboard-page min-h-screen p-6 bg-white dark:bg-gray-900"
        >
            <!-- Header Section -->
            <div class="page-header mb-8">
                <div class="header-content flex items-center space-x-3 mb-2">
                    <div
                        class="header-icon w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center"
                    >
                        <i
                            class="mdi mdi-view-dashboard text-white text-lg"
                        ></i>
                    </div>
                    <h1
                        class="text-3xl font-bold text-gray-800 dark:text-white"
                    >
                        {{ __("Admin Dashboard") }}
                    </h1>
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-lg">
                    {{
                        __(
                            "Monitor your application's performance and user activity"
                        )
                    }}
                </div>
            </div>

            <!-- Stats Cards -->
            <div
                class="stats-cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8"
            >
                <div
                    v-for="card in cards"
                    :key="card.label"
                    class="stats-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="card-content">
                        <div
                            class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 mb-1"
                        >
                            {{ __(card.label) }}
                        </div>
                        <div class="flex items-center justify-between">
                            <div
                                class="text-4xl font-bold text-blue-600 dark:text-blue-400"
                            >
                                {{ card.value }}
                            </div>
                            <div
                                class="card-icon w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center"
                            >
                                <i
                                    :class="card.icon"
                                    class="text-blue-600 dark:text-blue-400 text-xl"
                                ></i>
                            </div>
                        </div>
                        <div class="progress-bar mt-4">
                            <div
                                class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5"
                            >
                                <div
                                    class="bg-blue-600 dark:bg-blue-500 h-1.5 rounded-full w-3/4"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div
                class="filters-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 shadow-sm"
            >
                <div class="p-6">
                    <div
                        class="text-xl font-medium text-gray-800 dark:text-white mb-4 flex items-center"
                    >
                        <i class="mdi mdi-filter mr-2 text-blue-500"></i>
                        {{ __("Filter Analytics") }}
                    </div>
                    <div
                        class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end"
                    >
                        <div class="input-group">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Start Date") }}
                            </label>
                            <div class="relative">
                                <input
                                    v-model="params.start"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                />
                                <i
                                    class="mdi mdi-calendar-start absolute right-3 top-2.5 text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                        </div>
                        <div class="input-group">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("End Date") }}
                            </label>
                            <div class="relative">
                                <input
                                    v-model="params.end"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                />
                                <i
                                    class="mdi mdi-calendar-end absolute right-3 top-2.5 text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                        </div>
                        <div class="input-group">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Date Range") }}
                            </label>
                            <select
                                v-model="params.type"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
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
                        <div class="input-group">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                            >
                                {{ __("Chart Type") }}
                            </label>
                            <select
                                v-model="chartType"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option
                                    v-for="chartType in chartTypes"
                                    :key="chartType.value"
                                    :value="chartType.value"
                                >
                                    {{ chartType.label }}
                                </option>
                            </select>
                        </div>
                        <div class="input-group">
                            <button
                                @click="getData"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors flex items-center justify-center"
                                :disabled="loading"
                            >
                                <i class="mdi mdi-check mr-2"></i>
                                {{ __("Apply Filters") }}
                                <div v-if="loading" class="ml-2">
                                    <div
                                        class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
                                    ></div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart and Users Section -->
            <div class="grid grid-cols-1 gap-8">
                <!-- Chart Column -->
                <div
                    class="chart-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="p-6">
                        <div
                            class="text-xl font-medium text-gray-800 dark:text-white mb-4 flex items-center"
                        >
                            <i
                                class="mdi mdi-chart-line mr-2 text-blue-500"
                            ></i>
                            {{ __("User Growth Analytics") }}
                        </div>
                        <apex-charts
                            width="100%"
                            height="400"
                            :type="chartType"
                            :options="chartOptions"
                            :series="chartSeries"
                        />
                    </div>
                </div>

                <!-- Recent Users Column -->
                <div
                    class="users-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="p-6">
                        <div
                            class="text-xl font-medium text-gray-800 dark:text-white mb-4 flex items-center"
                        >
                            <i
                                class="mdi mdi-account-group mr-2 text-blue-500"
                            ></i>
                            {{ __("Recent Users") }}
                        </div>
                        <div class="users-list space-y-2">
                            <div
                                v-for="user in last_users"
                                :key="user.id"
                                class="user-item p-4 hover:bg-white dark:hover:bg-gray-700 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium"
                                        >
                                            {{ getUserInitials(user.name) }}
                                        </div>
                                        <div>
                                            <div
                                                class="font-medium text-gray-800 dark:text-white"
                                            >
                                                {{ user.name }}
                                            </div>
                                            <div
                                                class="text-gray-600 dark:text-gray-400 text-sm"
                                            >
                                                {{ user.email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="date-badge border border-blue-200 dark:border-blue-600 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-full text-sm flex items-center"
                                    >
                                        <i
                                            class="mdi mdi-calendar mr-1 text-sm"
                                        ></i>
                                        {{ formatDate(user.created_at) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="last_users.length === 0"
                            class="text-center py-8"
                        >
                            <div
                                class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3"
                            >
                                <i
                                    class="mdi mdi-account-off text-gray-400 text-2xl"
                                ></i>
                            </div>
                            <div class="text-gray-500 dark:text-gray-400">
                                {{ __("No recent users") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auto-refresh Indicator -->
            <div class="fixed bottom-4 right-4">
                <div
                    class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-sm flex items-center"
                >
                    <i class="mdi mdi-autorenew mr-1 text-sm"></i>
                    {{ __("Auto-refresh every 10 seconds") }}
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";
import VAdminLayout from "@/components/VGeneralLayout.vue";

export default {
    components: {
        ApexCharts,
        VAdminLayout,
    },

    data() {
        return {
            params: {
                start: null,
                end: null,
                type: "month",
            },
            chartType: "line",
            chartTypes: [
                { label: "Line Chart", value: "line" },
                { label: "Bar Chart", value: "bar" },
                { label: "Area Chart", value: "area" },
            ],
            types: [
                { label: "Daily", value: "day" },
                { label: "Monthly", value: "month" },
                { label: "Yearly", value: "year" },
            ],
            users_by_month: [],
            last_users: [],
            groups: 0,
            roles: 0,
            services: 0,
            users: 0,
            cards: [],
            chartSeries: [],
            chartOptions: {},
            loading: false,
            refreshInterval: null,
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
        this.refreshInterval = setInterval(() => {
            this.getData();
        }, 10000);
    },

    beforeUnmount() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
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
            this.users_by_month = data["users_by_month"];
            this.last_users = data["last_users"];

            this.groups = data["groups"];
            this.roles = data["roles"];
            this.services = data["services"];
            this.users = data["users"];

            this.cards = [
                {
                    label: "Groups",
                    value: this.groups,
                    icon: "mdi mdi-account-group",
                },
                {
                    label: "Roles",
                    value: this.roles,
                    icon: "mdi mdi-shield-account",
                },
                { label: "Users", value: this.users, icon: "mdi mdi-account" },
                {
                    label: "Services",
                    value: this.services,
                    icon: "mdi mdi-cog",
                },
            ];

            this.renderChart();
        },

        async getData() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.loadData(res.data.data);
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    // Mantener la notificación de Quasar si es necesario
                    this.$q?.notify?.({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        renderChart() {
            this.chartSeries = [
                {
                    name: "Users",
                    data: this.users_by_month.map((item) => item.total),
                },
            ];

            this.chartOptions = {
                chart: {
                    height: 350,
                    type: this.chartType,
                    zoom: {
                        enabled: true,
                    },
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
                colors: ["#3b82f6"],
                dataLabels: {
                    enabled: this.chartType === "bar",
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                    },
                },
                title: {
                    text: `User Growth - ${
                        this.params.type.charAt(0).toUpperCase() +
                        this.params.type.slice(1)
                    }ly`,
                    align: "left",
                    style: {
                        fontSize: "16px",
                        fontWeight: "600",
                        color: "#374151",
                    },
                },
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
                    categories: this.users_by_month.map((item) => item.month),
                    labels: {
                        style: {
                            colors: "#6b7280",
                            fontSize: "12px",
                        },
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: "#6b7280",
                            fontSize: "12px",
                        },
                    },
                },
                tooltip: {
                    theme: "light",
                    x: {
                        format: "MMM yyyy",
                    },
                },
            };
        },

        formatDate(dateStr) {
            const options = { year: "numeric", month: "short", day: "numeric" };
            return new Date(dateStr).toLocaleDateString("en-US", options);
        },

        getUserInitials(name) {
            if (!name) return "U";
            return name
                .split(" ")
                .map((n) => n[0])
                .join("")
                .toUpperCase()
                .substring(0, 2);
        },
    },
};
</script>
