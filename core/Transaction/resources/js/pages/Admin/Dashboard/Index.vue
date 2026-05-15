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
            <!-- Header Section -->
            <v-head
                :title="__('Dashboard Analytics')"
                :descripction="
                    __('Monitor your transaction metrics and performance')
                "
            >
                <template #bottom>
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6 mb-8 shadow-sm"
                    >
                        <div class="flex items-center space-x-2 mb-4">
                            <i
                                class="mdi mdi-tune text-gray-400 dark:text-gray-500 text-base"
                            ></i>
                            <h2
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Filter Analytics") }}
                            </h2>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-2"
                        >
                            <v-input
                                :label="__('Start date')"
                                v-model="search.start"
                                type="date"
                            />
                            <v-input
                                :label="__('End date')"
                                v-model="search.end"
                                type="date"
                            />

                            <v-select
                                :label="__('Status')"
                                v-model="search.status"
                                :options="billing_statuses"
                                label-key="value"
                                value-key="value"
                            />

                            <v-select
                                :label="__('Chart type')"
                                v-model="chartType"
                                :options="[
                                    { name: 'Bar', id: 'bar' },
                                    { name: 'Line', id: 'line' },
                                    { name: 'Area', id: 'Area' },
                                ]"
                            />

                            <v-select
                                :label="__('Date grouping')"
                                v-model="search.type"
                                :options="[
                                    { name: 'Day', id: 'day' },
                                    { name: 'Month', id: 'month' },
                                    { name: 'Year', id: 'year' },
                                ]"
                            />

                            <!-- Apply Filters Button -->
                            <div class="flex items-end">
                                <v-button
                                    @click="getData"
                                    icon="mdi mdi-filter text-xs"
                                    :label="__('Apply')"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </v-head>

            <!-- Chart Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm"
            >
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6"
                >
                    <div class="flex items-center space-x-3 mb-3 lg:mb-0">
                        <div
                            class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg"
                        >
                            <i
                                class="mdi mdi-chart-line text-blue-600 dark:text-blue-400 text-base"
                            ></i>
                        </div>
                        <div>
                            <h2
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Transaction Analytics") }}
                            </h2>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs mt-1"
                            >
                                {{
                                    __(
                                        "Real-time insights and performance metrics",
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-full text-xs font-medium flex items-center space-x-1.5"
                    >
                        <i
                            class="mdi mdi-sync animate-spin text-blue-600 dark:text-blue-400 text-xs"
                        ></i>
                        <span>{{ __("Auto-refresh: 10s") }}</span>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-900 rounded-lg p-4 border border-gray-100 dark:border-gray-700"
                >
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
        </template>
    </v-layout>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import ApexCharts from "vue3-apexcharts";
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import VHead from "@/components/VHead.vue";
import VButton from "@/components/VButton.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();

const search = useForm({
    start: null,
    end: null,
    type: "month",
    status: "successful",
});

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

const chartType = ref("line");
const transactions_by_month = ref([]);
const plans_count = ref(0);
const package_count = ref(0);
const cards = ref([]);
const chartSeries = ref([]);
const chartOptions = ref({});

watch(
    () => search.type,
    () => {
        getData();
    },
);

watch(
    () => chartType.value,
    () => {
        renderChart();
    },
);

onMounted(() => {
    if (!search.start || !search.end) {
        const { start, end } = getDefaultDates();
        search.start = start;
        search.end = end;
    }

    loadData(props.data);
});

const getDefaultDates = () => {
    const today = new Date();
    const end = today.toISOString().split("T")[0];

    const pastDate = new Date(today);
    pastDate.setMonth(today.getMonth() - 6);
    const start = pastDate.toISOString().split("T")[0];

    return { start, end };
};

const loadData = (data) => {
    transactions_by_month.value = data["transactions_by_month"];

    plans_count.value = data["plans"];
    package_count.value = data["packages"];

    cards.value = [
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

    renderChart();
};

const getData = () => {
    search.get(page.props.route, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (res) => {
            loadData(res.props.data);
        },
        onError: (e) => {
            if (e?.response?.data?.message) {
                console.error("Error:", e.response.data.message);
            }
        },
    });
};

const renderChart = () => {
    chartSeries.value = [
        {
            name: __("Transactions"),
            data: transactions_by_month.value.map((item) => item.total),
        },
    ];

    const isDark = document.documentElement.classList.contains("dark");

    chartOptions.value = {
        chart: {
            height: 350,
            type: chartType.value,
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
            foreColor: isDark ? "#9CA3AF" : "#6B7280",
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
            borderColor: isDark ? "#374151" : "#F3F4F6",
            strokeDashArray: 3,
        },
        xaxis: {
            categories: transactions_by_month.value.map((item) => item.month),
            labels: {
                style: {
                    colors: isDark ? "#9CA3AF" : "#6B7280",
                    fontSize: "11px",
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: isDark ? "#9CA3AF" : "#6B7280",
                    fontSize: "11px",
                },
            },
        },
        tooltip: {
            theme: isDark ? "dark" : "light",
            style: {
                fontSize: "12px",
            },
        },
        theme: {
            mode: isDark ? "dark" : "light",
        },
    };
};
</script>

<style scoped>
.chart-container {
    border-radius: 0.5rem;
}

/* Smooth transitions for theme switching */
.bg-white,
.bg-gray-50,
.bg-blue-50,
.border-gray-100,
.border-gray-200 {
    transition: all 0.3s ease-in-out;
}

/* Dark mode transitions */
.dark .bg-gray-800,
.dark .bg-gray-700,
.dark .bg-blue-900\/30,
.dark .border-gray-700,
.dark .border-gray-600 {
    transition: all 0.3s ease-in-out;
}
</style>
