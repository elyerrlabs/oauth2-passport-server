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
    <v-layout>
        <template #aside>
            <v-item-menu
                :items="page.props.menus"
                icon="mdi mdi-apps text-2xl me-2"
                :collapse="true"
            />
        </template>
        <template #main>
            <div class="space-y-6">
                <!-- Header Section -->
                <v-head
                    :title="__('Dashboard Analytics')"
                    :description="
                        __('Monitor your transaction metrics and performance')
                    "
                />

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        v-for="card in statsCards"
                        :key="card.label"
                        class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow duration-300"
                    >
                        <div
                            class="absolute top-0 right-0 w-24 h-24 -mr-8 -mt-8 rounded-full opacity-10"
                            :class="card.bgClass"
                        ></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div
                                    class="p-3 rounded-lg"
                                    :class="card.iconBgClass"
                                >
                                    <i
                                        :class="[
                                            card.icon,
                                            'text-xl',
                                            card.iconColorClass,
                                        ]"
                                    ></i>
                                </div>
                                <span
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ card.label }}
                                </span>
                            </div>
                            <div
                                class="text-3xl font-bold text-gray-900 dark:text-white mb-2"
                            >
                                {{ card.value }}
                            </div>
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ card.subtitle }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div
                        class="p-6 border-b border-gray-200 dark:border-gray-700"
                    >
                        <div class="flex items-center space-x-3">
                            <div
                                class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg"
                            >
                                <i
                                    class="mdi mdi-filter-variant text-indigo-600 dark:text-indigo-400 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h2
                                    class="text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ __("Filters & Configuration") }}
                                </h2>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Customize your analytics view") }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("Start Date") }}
                                </label>
                                <input
                                    v-model="search.start"
                                    type="date"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("End Date") }}
                                </label>
                                <input
                                    v-model="search.end"
                                    type="date"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("Status") }}
                                </label>
                                <v-select
                                    v-model="search.status"
                                    :options="billing_statuses"
                                    :placeholder="__('Select status')"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("Chart Type") }}
                                </label>
                                <v-select
                                    v-model="chartType"
                                    :options="chartTypes"
                                    :placeholder="__('Select type')"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("Group By") }}
                                </label>
                                <v-select
                                    v-model="search.type"
                                    :options="groupingOptions"
                                    :placeholder="__('Select grouping')"
                                />
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <v-button
                                @click="resetFilters"
                                :label="__('Reset')"
                                icon="mdi mdi-refresh"
                                variant="secondary"
                            />
                            <v-button
                                @click="getData"
                                :label="__('Apply Filters')"
                                icon="mdi mdi-filter-check"
                                variant="primary"
                            />
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div
                        class="p-6 border-b border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg"
                                >
                                    <i
                                        class="mdi mdi-chart-bell-curve text-blue-600 dark:text-blue-400 text-xl"
                                    ></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-lg font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ __("Transaction Trends") }}
                                    </h2>
                                    <p
                                        class="text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{
                                            __(
                                                "Monthly transaction distribution",
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 text-sm">
                                <span
                                    class="flex items-center space-x-1 text-gray-500 dark:text-gray-400"
                                >
                                    <i
                                        class="mdi mdi-circle text-blue-500 text-xs"
                                    ></i>
                                    <span>{{ __("Transactions") }}</span>
                                </span>
                                <span class="text-gray-300 dark:text-gray-600"
                                    >|</span
                                >
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ formatDateRange() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div
                            v-if="chartReady"
                            class="w-full"
                            style="height: 400px"
                        >
                            <apex-charts
                                width="100%"
                                height="100%"
                                :type="chartType"
                                :options="chartOptions"
                                :series="chartSeries"
                            />
                        </div>
                        <div
                            v-else
                            class="flex items-center justify-center h-96"
                        >
                            <div class="text-center">
                                <i
                                    class="mdi mdi-chart-line text-6xl text-gray-300 dark:text-gray-600 mb-4"
                                ></i>
                                <p class="text-gray-500 dark:text-gray-400">
                                    {{ __("Loading chart data...") }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-layout>
</template>

<script setup>
import { ref, onMounted, computed, nextTick } from "vue";
import ApexCharts from "vue3-apexcharts";
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import VHead from "@/components/VHead.vue";
import VButton from "@/components/VButton.vue";
import VSelect from "@/components/VSelect.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    billing_statuses: {
        type: Array,
        required: true,
    },
});

const search = useForm({
    start: null,
    end: null,
    type: "month",
    status: "",
});

const chartTypes = [
    { name: __("Line"), id: "line" },
    { name: __("Bar"), id: "bar" },
    { name: __("Area"), id: "area" },
];

const groupingOptions = [
    { name: __("Daily"), id: "day" },
    { name: __("Monthly"), id: "month" },
    { name: __("Yearly"), id: "year" },
];

const chartType = ref("area");
const chartReady = ref(false);
const transactionsByMonth = ref([]);
const chartSeries = ref([]);
const chartOptions = ref({});

const statsCards = computed(() => [
    {
        label: __("Total Transactions"),
        value: props.data.transactions || 0,
        icon: "mdi mdi-swap-horizontal-bold",
        iconBgClass: "bg-blue-100 dark:bg-blue-900/30",
        iconColorClass: "text-blue-600 dark:text-blue-400",
        bgClass: "bg-blue-500",
        subtitle: __("All time transactions"),
    },
    {
        label: __("Active Packages"),
        value: props.data.packages || 0,
        icon: "mdi mdi-package-variant-closed",
        iconBgClass: "bg-green-100 dark:bg-green-900/30",
        iconColorClass: "text-green-600 dark:text-green-400",
        bgClass: "bg-green-500",
        subtitle: __("Available packages"),
    },
    {
        label: __("Active Plans"),
        value: props.data.plans || 0,
        icon: "mdi mdi-clipboard-list-outline",
        iconBgClass: "bg-purple-100 dark:bg-purple-900/30",
        iconColorClass: "text-purple-600 dark:text-purple-400",
        bgClass: "bg-purple-500",
        subtitle: __("Subscription plans"),
    },
]);

onMounted(() => {
    if (!search.start || !search.end) {
        const { start, end } = getDefaultDates();
        search.start = start;
        search.end = end;
    }

    loadChartData();
});

const getDefaultDates = () => {
    const today = new Date();
    const end = today.toISOString().split("T")[0];

    const pastDate = new Date(today);
    pastDate.setMonth(today.getMonth() - 6);
    const start = pastDate.toISOString().split("T")[0];

    return { start, end };
};

const loadChartData = async () => {
    chartReady.value = false;

    // Pequeño delay para asegurar que el DOM está listo
    await nextTick();

    transactionsByMonth.value = props.data.transactions_by_month || [];

    await nextTick();
    renderChart();

    // Delay adicional para que ApexCharts se inicialice correctamente
    setTimeout(() => {
        chartReady.value = true;
    }, 100);
};

const getData = () => {
    search.get(page.props.route, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: async (res) => {
            transactionsByMonth.value =
                res.props.data.transactions_by_month || [];
            await nextTick();
            renderChart();
            chartReady.value = true;
        },
        onError: (e) => {
            console.error("Error loading data:", e);
            $notify?.error?.(__("Error loading analytics data"));
        },
    });
};

const resetFilters = () => {
    const { start, end } = getDefaultDates();
    search.start = start;
    search.end = end;
    search.status = "";
    search.type = "month";
    getData();
};

const formatDateRange = () => {
    if (!search.start || !search.end) return "";

    const start = new Date(search.start);
    const end = new Date(search.end);

    const formatDate = (date) => {
        return date.toLocaleDateString(undefined, {
            month: "short",
            day: "numeric",
            year: "numeric",
        });
    };

    return `${formatDate(start)} - ${formatDate(end)}`;
};

const renderChart = () => {
    const isDark = document.documentElement.classList.contains("dark");
    const categories = transactionsByMonth.value.map((item) => {
        // Formatear la fecha según el tipo de agrupación
        if (search.type === "year") return item.month;
        if (search.type === "day") return item.month;

        // Para month, formatear como "Mar 2026"
        const [year, month] = item.month.split("-");
        const date = new Date(year, month - 1);
        return date.toLocaleDateString(undefined, {
            month: "short",
            year: "numeric",
        });
    });

    chartSeries.value = [
        {
            name: __("Transactions"),
            data: transactionsByMonth.value.map(
                (item) => Number(item.total) || 0,
            ),
        },
    ];

    chartOptions.value = {
        chart: {
            type: chartType.value,
            height: 400,
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
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150,
                },
            },
            background: "transparent",
        },
        colors: ["#3B82F6", "#10B981", "#8B5CF6"],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
            width: chartType.value === "bar" ? 0 : 3,
        },
        fill: {
            type: chartType.value === "area" ? "gradient" : "solid",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100],
            },
        },
        grid: {
            borderColor: isDark ? "#374151" : "#E5E7EB",
            strokeDashArray: 4,
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 10,
            },
        },
        xaxis: {
            categories: categories,
            labels: {
                style: {
                    colors: isDark ? "#9CA3AF" : "#4B5563",
                    fontSize: "12px",
                    fontFamily: "Inter, system-ui, sans-serif",
                },
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: isDark ? "#9CA3AF" : "#4B5563",
                    fontSize: "12px",
                    fontFamily: "Inter, system-ui, sans-serif",
                },
                formatter: (value) => {
                    return Math.round(value);
                },
            },
            min: 0,
            forceNiceScale: true,
        },
        tooltip: {
            theme: isDark ? "dark" : "light",
            style: {
                fontSize: "12px",
                fontFamily: "Inter, system-ui, sans-serif",
            },
            y: {
                formatter: (value) => {
                    return `${value} ${value === 1 ? __("transaction") : __("transactions")}`;
                },
            },
        },
        legend: {
            show: false,
        },
        markers: {
            size: chartType.value === "line" ? 4 : 0,
            strokeWidth: 2,
            hover: {
                size: 6,
            },
        },
    };
};
</script>

<style scoped>
/* Transiciones suaves */
.bg-white,
.bg-gray-800 {
    transition: background-color 0.3s ease-in-out;
}

/* Mejora visual para los inputs de fecha */
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
    cursor: pointer;
}

.dark input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
}
</style>
