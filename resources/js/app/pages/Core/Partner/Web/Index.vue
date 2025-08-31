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
        <div class="dashboard-container q-pa-lg">
            <!-- Header Section -->
            <div class="row items-center justify-between q-mb-lg">
                <div>
                    <div class="text-h4 text-weight-bold text-primary">
                        Sales Dashboard
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        Monitor your sales performance and commissions
                    </div>
                </div>
                <div class="row items-center q-gutter-sm">
                    <q-btn
                        icon="refresh"
                        round
                        dense
                        color="primary"
                        @click="getSales"
                        class="q-mr-sm"
                    >
                        <q-tooltip>Refresh data</q-tooltip>
                    </q-btn>
                    <q-btn
                        icon="auto_awesome"
                        round
                        dense
                        color="primary"
                        @click="toggleDarkMode"
                    >
                        <q-tooltip>Toggle dark mode</q-tooltip>
                    </q-btn>
                </div>
            </div>

            <!-- Filters Section -->
            <q-card flat class="filter-card q-mb-lg">
                <q-card-section>
                    <div class="text-h6 text-weight-medium q-mb-md">
                        Filters
                    </div>
                    <div class="row q-col-gutter-md">
                        <div class="col-12 col-sm-6 col-md-3">
                            <q-input
                                v-model="params.start"
                                type="date"
                                label="Start Date"
                                outlined
                                dense
                                stack-label
                                class="filter-input"
                            >
                                <template v-slot:prepend>
                                    <q-icon name="event" />
                                </template>
                            </q-input>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <q-input
                                v-model="params.end"
                                type="date"
                                label="End Date"
                                outlined
                                dense
                                stack-label
                                class="filter-input"
                            >
                                <template v-slot:prepend>
                                    <q-icon name="event" />
                                </template>
                            </q-input>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                            <q-select
                                v-model="chartType"
                                :options="chartTypes"
                                label="Chart Type"
                                outlined
                                dense
                                stack-label
                                emit-value
                                map-options
                            />
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                            <q-select
                                v-model="params.type"
                                :options="types"
                                label="Date Range"
                                outlined
                                dense
                                stack-label
                                emit-value
                                map-options
                            >
                                <template
                                    v-slot:option="{
                                        itemProps,
                                        opt,
                                        selected,
                                        toggleOption,
                                    }"
                                >
                                    <q-item v-bind="itemProps">
                                        <q-item-section>
                                            <q-item-label>{{
                                                opt.label
                                            }}</q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                :name="
                                                    selected
                                                        ? 'radio_button_checked'
                                                        : 'radio_button_unchecked'
                                                "
                                                color="primary"
                                            />
                                        </q-item-section>
                                    </q-item>
                                </template>
                            </q-select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 flex items-end">
                            <q-btn
                                label="Apply Filters"
                                @click="getSales"
                                color="primary"
                                icon="filter_alt"
                                class="full-width"
                                unelevated
                            />
                        </div>
                    </div>
                </q-card-section>
            </q-card>

            <!-- KPI Cards -->
            <div class="row q-col-gutter-md q-mb-lg">
                <div class="col-12 col-md-6">
                    <q-card flat class="kpi-card sales-card">
                        <q-card-section>
                            <div class="row items-center">
                                <q-avatar
                                    color="blue-1"
                                    text-color="primary"
                                    icon="shopping_cart"
                                    class="q-mr-md"
                                />
                                <div>
                                    <div class="text-caption text-grey-7">
                                        TOTAL SALES
                                    </div>
                                    <div class="text-h4 text-weight-bold">
                                        {{ formatCurrency(total_sales) }}
                                    </div>
                                </div>
                            </div>
                            <q-linear-progress
                                size="8px"
                                :value="0.7"
                                color="primary"
                                class="q-mt-md"
                            />
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-12 col-md-6">
                    <q-card flat class="kpi-card commission-card">
                        <q-card-section>
                            <div class="row items-center">
                                <q-avatar
                                    color="green-1"
                                    text-color="positive"
                                    icon="payments"
                                    class="q-mr-md"
                                />
                                <div>
                                    <div class="text-caption text-grey-7">
                                        TOTAL COMMISSION
                                    </div>
                                    <div
                                        v-for="(
                                            item, index
                                        ) in total_commission"
                                        :key="index"
                                        class="text-h4 text-weight-bold text-positive"
                                    >
                                        {{ formatCurrency(item.total) }}
                                        {{ item.currency }}
                                    </div>
                                    <div
                                        v-if="!total_commission.length"
                                        class="text-h4 text-weight-bold text-positive"
                                    >
                                        $0.00
                                    </div>
                                </div>
                            </div>
                            <q-linear-progress
                                size="8px"
                                :value="0.5"
                                color="positive"
                                class="q-mt-md"
                            />
                        </q-card-section>
                    </q-card>
                </div>
            </div>

            <!-- Chart Section -->
            <q-card flat class="chart-card">
                <q-card-section>
                    <div class="row items-center justify-between q-mb-md">
                        <div class="text-h6 text-weight-medium">
                            Sales Performance
                        </div>
                        <div class="row items-center q-gutter-xs">
                            <q-btn-toggle
                                v-model="chartType"
                                :options="chartTypes"
                                toggle-color="primary"
                                size="sm"
                                unelevated
                            />
                        </div>
                    </div>
                    <apex-charts
                        width="100%"
                        height="350"
                        :type="chartType"
                        :options="chartOptions"
                        :series="chartSeries"
                    />
                </q-card-section>
            </q-card>

            <!-- Data Refresh Indicator -->
            <div class="row justify-end q-mt-sm">
                <div class="text-caption text-grey-6">
                    <q-icon name="schedule" size="14px" class="q-mr-xs" />
                    Auto-refresh in {{ refreshCountdown }}s
                    <q-btn
                        flat
                        dense
                        size="sm"
                        label="Disable"
                        @click="disableAutoRefresh"
                        class="q-ml-sm"
                    />
                </div>
            </div>
        </div>
    </v-partner-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";

export default {
    components: {
        ApexCharts,
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
                { label: "Line", value: "line", icon: "show_chart" },
                { label: "Bar", value: "bar", icon: "bar_chart" },
                { label: "Area", value: "area", icon: "area_chart" },
            ],
            total_sales: 0,
            total_commission: [],
            chartOptions: {
                chart: {
                    height: 350,
                    type: "line",
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
                            reset: false,
                        },
                    },
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: "12px",
                        fontWeight: "bold",
                    },
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                },
                colors: ["#1976D2", "#4CAF50"],
                title: {
                    text: "Sales Performance",
                    align: "left",
                    style: {
                        fontSize: "16px",
                        fontWeight: "bold",
                        color: "#333",
                    },
                },
                grid: {
                    row: {
                        colors: ["#f3f3f3", "transparent"],
                        opacity: 0.5,
                    },
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            fontSize: "12px",
                        },
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return "$" + val.toLocaleString();
                        },
                        style: {
                            fontSize: "12px",
                        },
                    },
                },
                legend: {
                    position: "top",
                    horizontalAlign: "right",
                    fontSize: "14px",
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
                    name: "Sales",
                    data: [],
                },
                {
                    name: "Commission",
                    data: [],
                },
            ],
            types: [
                { label: "Daily", value: "day" },
                { label: "Monthly", value: "month" },
                { label: "Yearly", value: "year" },
            ],
            refreshInterval: null,
            refreshCountdown: 10,
            countdownInterval: null,
            darkMode: false,
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
            } catch (error) {
                console.error("Error fetching sales data:", error);
                this.$q.notify({
                    type: "negative",
                    message: "Failed to load sales data",
                    position: "top-right",
                });
            }
        },

        updateChart(data) {
            this.chartSeries = [
                {
                    name: "Sales",
                    data: data.map((item) => item.total),
                },
                {
                    name: "Commission",
                    data: data.map((item) => item.commission),
                },
            ];

            this.chartOptions = {
                ...this.chartOptions,
                xaxis: {
                    ...this.chartOptions.xaxis,
                    categories: data.map((item) => item.date),
                },
                title: {
                    ...this.chartOptions.title,
                    text: `Sales by ${this.params.type}`,
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
            this.$q.notify({
                type: "info",
                message: "Auto-refresh disabled",
                position: "top-right",
            });
        },

        clearIntervals() {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }
        },

        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            this.$q.dark.set(this.darkMode);
        },
    },
};
</script>

<style scoped>
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
}

.filter-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.kpi-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
}

.kpi-card:hover {
    transform: translateY(-5px);
}

.chart-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.filter-input {
    border-radius: 8px;
}

.sales-card {
    border-left: 4px solid #1976d2;
}

.commission-card {
    border-left: 4px solid #4caf50;
}

:deep(.apexcharts-tooltip) {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
    border-radius: 8px !important;
}

:deep(.apexcharts-menu-item) {
    padding: 8px 16px !important;
}

@media (max-width: 600px) {
    .dashboard-container {
        padding: 12px;
    }

    .text-h4 {
        font-size: 1.5rem;
    }

    .kpi-card .text-h4 {
        font-size: 1.25rem;
    }
}
</style>
