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
    <v-admin-ecommerce-layout>
        <div class="row q-pa-sm q-pa-md">
            <div class="col-12 col-sm-6 col-md-3">
                <q-card class="stat-card bg-blue-1">
                    <q-card-section>
                        <div class="row items-center no-wrap">
                            <div class="col">
                                <div class="text-subtitle1 text-grey-7">
                                    {{ __("Total Sales") }}
                                </div>
                                <div class="text-h6 text-weight-bold">
                                    {{ dashboard.currency_symbol }}
                                    {{ dashboard.transactions_total }}
                                </div>
                                <div class="text-caption text-green">
                                    <q-icon name="mdi-arrow-up" />
                                    {{ __("last month") }}
                                </div>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <q-card class="stat-card bg-green-1">
                    <q-card-section>
                        <div class="row items-center no-wrap">
                            <div class="col">
                                <div class="text-subtitle1 text-grey-7">
                                    {{ __("Today Sales") }}
                                </div>
                                <div class="text-h6 text-weight-bold">
                                    {{ dashboard.currency_symbol }}
                                    {{ dashboard.transactions_today }}
                                </div>
                                <div class="text-caption text-green">
                                    <q-icon name="mdi-arrow-up" />
                                    {{ __("from yesterday") }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <q-icon
                                    name="mdi-cash-fast"
                                    size="lg"
                                    color="green"
                                />
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <q-card class="stat-card bg-orange-1">
                    <q-card-section>
                        <div class="row items-center no-wrap">
                            <div class="col">
                                <div class="text-subtitle1 text-grey-7">
                                    {{ __("Total Products") }}
                                </div>
                                <div class="text-h6 text-weight-bold">
                                    {{ dashboard.products_stock_total }}
                                </div>
                                <div class="text-caption text-grey">
                                    {{ dashboard.products_lower_stock }}
                                    {{ __("low in stock") }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <q-icon
                                    name="mdi-package-variant"
                                    size="lg"
                                    color="orange"
                                />
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <q-card class="stat-card bg-purple-1">
                    <q-card-section>
                        <div class="row items-center no-wrap">
                            <div class="col">
                                <div class="text-subtitle1 text-grey-7">
                                    {{ __("Pending Orders") }}
                                </div>
                                <div class="text-h6 text-weight-bold">
                                    {{ dashboard.products_pending }}
                                </div>
                                <div class="text-caption text-red">
                                    <q-icon name="mdi-alert" />
                                    {{ __("high priority") }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <q-icon
                                    name="mdi-clock-outline"
                                    size="lg"
                                    color="purple"
                                />
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row q-col-gutter-md q-mb-md">
            <div class="col-12 col-lg-8">
                <q-card class="chart-card">
                    <q-card-section class="card-header">
                        <div class="text-h6">
                            {{ __("Sales Overview (Last 30 Days)") }}
                        </div>
                        <q-btn
                            flat
                            round
                            dense
                            icon="mdi-dots-vertical"
                            class="card-menu"
                        >
                            <q-menu>
                                <q-list>
                                    <q-item clickable v-close-popup>
                                        <q-item-section>{{
                                            __("Export Data")
                                        }}</q-item-section>
                                    </q-item>
                                </q-list>
                            </q-menu>
                        </q-btn>
                    </q-card-section>
                    <q-card-section>
                        <apexchart
                            type="area"
                            height="350"
                            :options="salesChartOptions"
                            :series="salesChartSeries"
                        />
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-lg-4">
                <q-card class="chart-card">
                    <q-card-section class="card-header">
                        <div class="text-h6">{{ __("Revenue Sources") }}</div>
                    </q-card-section>
                    <q-card-section>
                        <apexchart
                            type="donut"
                            height="350"
                            :options="revenueChartOptions"
                            :series="revenueChartSeries"
                        />
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Second Row Charts -->
        <div class="row q-col-gutter-md q-mb-md">
            <div class="col-12 col-md-6">
                <q-card class="chart-card">
                    <q-card-section class="card-header">
                        <div class="text-h6">
                            {{ __("Top Selling Products") }}
                        </div>
                    </q-card-section>
                    <q-card-section>
                        <apexchart
                            type="bar"
                            height="300"
                            :options="topProductsChartOptions"
                            :series="topProductsChartSeries"
                        />
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-md-6">
                <q-card class="chart-card">
                    <q-card-section class="card-header">
                        <div class="text-h6">
                            {{ __("Today's Sales Status") }}
                        </div>
                    </q-card-section>
                    <q-card-section>
                        <apexchart
                            type="radialBar"
                            height="300"
                            :options="todaySalesChartOptions"
                            :series="todaySalesChartSeries"
                        />
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="row q-col-gutter-md">
            <div class="col-12 col-lg-7">
                <q-card class="data-card">
                    <q-card-section class="card-header">
                        <div class="text-h6">{{ __("Recent Orders") }}</div>
                        <q-btn
                            flat
                            color="primary"
                            :label="__('View All')"
                            size="sm"
                        />
                    </q-card-section>
                    <q-separator />
                    <q-card-section class="q-pa-none">
                        <q-table
                            flat
                            :rows="checkouts"
                            :columns="columns"
                            hide-pagination
                        >
                            <template v-slot:body-cell-status="props">
                                <q-td :props="props">
                                    <q-badge
                                        :color="
                                            getStatusColor(props.row.status)
                                        "
                                        class="status-badge"
                                    >
                                        {{ props.row.status }}
                                    </q-badge>
                                </q-td>
                            </template>

                            <template v-slot:body-cell-actions="props">
                                <q-td :props="props" class="actions-cell">
                                    <q-btn
                                        flat
                                        round
                                        dense
                                        icon="mdi-eye-outline"
                                        color="blue"
                                        size="sm"
                                        @click="viewOrder(props.row)"
                                    />
                                </q-td>
                            </template>
                        </q-table>
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-lg-5">
                <q-card class="data-card">
                    <q-card-section class="card-header">
                        <div class="text-h6">
                            {{ __("Top Selling Products") }}
                        </div>
                        <q-btn
                            flat
                            color="primary"
                            :label="__('View All')"
                            size="sm"
                        />
                    </q-card-section>
                    <q-separator />
                    <q-card-section class="q-pa-none">
                        <q-list bordered separator class="product-list">
                            <q-item
                                v-for="product in topProducts"
                                :key="product.id"
                                class="product-item"
                            >
                                <q-item-section avatar>
                                    <q-avatar rounded>
                                        <img
                                            :src="product.image"
                                            :alt="product.name"
                                        />
                                    </q-avatar>
                                </q-item-section>

                                <q-item-section>
                                    <q-item-label class="product-name">{{
                                        product.name
                                    }}</q-item-label>
                                    <q-item-label
                                        caption
                                        class="product-category"
                                        >{{ product.category }}</q-item-label
                                    >
                                </q-item-section>

                                <q-item-section side>
                                    <div class="text-weight-bold text-primary">
                                        ${{ product.price }}
                                    </div>
                                    <div class="text-caption text-grey-7">
                                        {{ __("Sold:") }} {{ product.sold }}
                                    </div>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-card-section>
                </q-card>
            </div>
        </div>
    </v-admin-ecommerce-layout>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    components: {
        apexchart: VueApexCharts,
    },

    data() {
        return {
            leftDrawerOpen: true,

            revenue: [],
            dashboard: [],
            checkouts: [],
            columns: [
                {
                    name: "id",
                    label: this.__("Order"),
                    field: "id",
                    align: "left",
                },
                {
                    name: "customer",
                    label: this.__("Customer"),
                    field: "customer",
                    align: "left",
                },
                {
                    name: "date",
                    label: this.__("Date"),
                    field: "date",
                    align: "left",
                },
                {
                    name: "total",
                    label: this.__("Total"),
                    field: "total",
                    align: "right",
                },
                {
                    name: "status",
                    label: this.__("Status"),
                    field: "status",
                    align: "center",
                },
                { name: "actions", label: "", align: "center" },
            ],

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
                labels: [this.__("Today Sales Goal")],
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
                        formatter: (val) => val + " " + this.__("units sold"),
                    },
                },
            };

            this.topProductsChartSeries = [
                {
                    name: this.__("Units Sold"),
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
                                    label: this.__("Total Revenue"),
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
                    name: "Sales",
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
                    return "green";
                case "pending":
                    return "orange";
                default:
                    return "grey";
            }
        },

        viewOrder(order) {
            window.location.href = order.link;
        },
    },
};
</script>

<style scoped>
/* Cards Styles */
.stat-card {
    transition: all 0.3s ease;
    border-left: 4px solid;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-card.bg-blue-1 {
    border-left-color: #2196f3;
}

.stat-card.bg-green-1 {
    border-left-color: #4caf50;
}

.stat-card.bg-orange-1 {
    border-left-color: #ff9800;
}

.stat-card.bg-purple-1 {
    border-left-color: #9c27b0;
}

/* Chart Cards */
.chart-card,
.data-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.chart-card:hover,
.data-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Card Headers */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.card-menu {
    margin-right: -8px;
}

/* Status Badges */
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
}

/* Product List */
.product-list {
    border-radius: 0 0 12px 12px;
}

.product-item {
    transition: all 0.2s ease;
    padding: 12px 16px;
}

.product-item:hover {
    background-color: #f5f5f5;
}

.product-name {
    font-weight: 500;
    font-size: 0.9rem;
}

.product-category {
    font-size: 0.75rem;
}

/* Responsive Adjustments */
@media (max-width: 1023px) {
    .chart-card {
        margin-bottom: 16px;
    }
}

@media (max-width: 599px) {
    .card-header {
        padding: 12px;
    }

    .product-item {
        padding: 8px 12px;
    }
}
</style>
