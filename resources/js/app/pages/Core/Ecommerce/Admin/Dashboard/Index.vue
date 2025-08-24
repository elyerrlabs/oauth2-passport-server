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
                                    Total Sales
                                </div>
                                <div class="text-h4 text-weight-bold">
                                    $24,560
                                </div>
                                <div class="text-caption text-green">
                                    <q-icon name="mdi-arrow-up" /> 12% from last
                                    month
                                </div>
                            </div>
                            <div class="col-auto">
                                <q-icon
                                    name="mdi-currency-usd"
                                    size="lg"
                                    color="blue"
                                />
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
                                    Today Sales
                                </div>
                                <div class="text-h4 text-weight-bold">
                                    $1,850
                                </div>
                                <div class="text-caption text-green">
                                    <q-icon name="mdi-arrow-up" /> 5% from
                                    yesterday
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
                                    Total Products
                                </div>
                                <div class="text-h4 text-weight-bold">
                                    {{ $page.props.products }}
                                </div>
                                <div class="text-caption text-grey">
                                    {{ $page.props.products_low_stock }} low in
                                    stock
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
                                    Pending Orders
                                </div>
                                <div class="text-h4 text-weight-bold">18</div>
                                <div class="text-caption text-red">
                                    <q-icon name="mdi-alert" /> 3 high priority
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
                        <div class="text-h6">Sales Overview (Last 30 Days)</div>
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
                                        <q-item-section
                                            >Export Data</q-item-section
                                        >
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
                        <div class="text-h6">Revenue Sources</div>
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
                        <div class="text-h6">Top Selling Products</div>
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
                        <div class="text-h6">Today's Sales Status</div>
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
                        <div class="text-h6">Recent Orders</div>
                        <q-btn
                            flat
                            color="primary"
                            label="View All"
                            size="sm"
                        />
                    </q-card-section>
                    <q-separator />
                    <q-card-section class="q-pa-none">
                        <q-table
                            flat
                            :rows="recentOrders"
                            :columns="orderColumns"
                            row-key="id"
                            hide-pagination
                            :rows-per-page-options="[0]"
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
                        <div class="text-h6">Top Selling Products</div>
                        <q-btn
                            flat
                            color="primary"
                            label="View All"
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
                                        Sold: {{ product.sold }}
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
import { ref } from "vue";
import VueApexCharts from "vue3-apexcharts";

export default {
    components: {
        apexchart: VueApexCharts,
    },

    setup() {
        const leftDrawerOpen = ref(true);

        // Stats Data
        const stats = ref([
            {
                title: "Total Sales",
                value: "$24,560",
                icon: "mdi-currency-usd",
                iconColor: "blue",
                trendIcon: "mdi-arrow-up",
                trendText: "12% from last month",
                trendColor: "text-green",
            },
            {
                title: "Today Sales",
                value: "$1,850",
                icon: "mdi-cash-fast",
                iconColor: "green",
                trendIcon: "mdi-arrow-up",
                trendText: "5% from yesterday",
                trendColor: "text-green",
            },
            {
                title: "Total Products",
                value: "342",
                icon: "mdi-package-variant",
                iconColor: "orange",
                trendText: "12 low in stock",
                trendColor: "text-grey",
            },
            {
                title: "Pending Orders",
                value: "18",
                icon: "mdi-clock-outline",
                iconColor: "purple",
                trendIcon: "mdi-alert",
                trendText: "3 high priority",
                trendColor: "text-red",
            },
        ]);

        // Sales Chart Data
        const salesChartOptions = ref({
            chart: {
                type: "area",
                height: 350,
                toolbar: {
                    show: false,
                },
            },
            colors: ["#4CAF50"],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
                width: 2,
            },
            xaxis: {
                categories: Array.from(
                    { length: 30 },
                    (_, i) => `Day ${i + 1}`
                ),
                labels: {
                    show: false,
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
                    formatter: function (val) {
                        return "$" + val.toFixed(0);
                    },
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$" + val.toFixed(2);
                    },
                },
            },
            grid: {
                borderColor: "#f1f1f1",
            },
        });

        const salesChartSeries = ref([
            {
                name: "Sales",
                data: Array.from(
                    { length: 30 },
                    () => Math.floor(Math.random() * 2000) + 500
                ),
            },
        ]);

        // Revenue Chart Data
        const revenueChartOptions = ref({
            chart: {
                type: "donut",
            },
            labels: [
                "Electronics",
                "Clothing",
                "Home Goods",
                "Accessories",
                "Other",
            ],
            colors: ["#2196F3", "#4CAF50", "#FF9800", "#9C27B0", "#607D8B"],
            legend: {
                position: "bottom",
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: "Total Revenue",
                                formatter: function () {
                                    return "$24,560";
                                },
                            },
                        },
                    },
                },
            },
            dataLabels: {
                enabled: false,
            },
        });

        const revenueChartSeries = ref([12500, 5800, 3200, 1800, 1260]);

        // Top Products Chart Data
        const topProductsChartOptions = ref({
            chart: {
                type: "bar",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                },
            },
            colors: ["#2196F3"],
            dataLabels: {
                enabled: false,
            },
            xaxis: {
                categories: [
                    "Wireless Headphones",
                    "Smart Watch",
                    "Running Shoes",
                    "Bluetooth Speaker",
                    "Coffee Maker",
                ],
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " units sold";
                    },
                },
            },
        });

        const topProductsChartSeries = ref([
            {
                name: "Units Sold",
                data: [45, 32, 28, 25, 22],
            },
        ]);

        // Today Sales Chart Data
        const todaySalesChartOptions = ref({
            chart: {
                type: "radialBar",
            },
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
                            formatter: function (val) {
                                return "$" + val;
                            },
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
            stroke: {
                lineCap: "round",
            },
            labels: ["Today Sales Goal"],
        });

        const todaySalesChartSeries = ref([85]);

        // Orders Data
        const recentOrders = ref([
            {
                id: 1256,
                customer: "John Doe",
                date: "2023-05-15",
                amount: 245.99,
                status: "Completed",
                payment: "Credit Card",
            },
            {
                id: 1255,
                customer: "Jane Smith",
                date: "2023-05-14",
                amount: 189.5,
                status: "Processing",
                payment: "PayPal",
            },
            {
                id: 1254,
                customer: "Robert Johnson",
                date: "2023-05-14",
                amount: 320.0,
                status: "Shipped",
                payment: "Credit Card",
            },
            {
                id: 1253,
                customer: "Emily Davis",
                date: "2023-05-13",
                amount: 95.75,
                status: "Pending",
                payment: "Bank Transfer",
            },
            {
                id: 1252,
                customer: "Michael Wilson",
                date: "2023-05-12",
                amount: 156.3,
                status: "Completed",
                payment: "Credit Card",
            },
        ]);

        const orderColumns = ref([
            { name: "id", label: "Order ID", field: "id", align: "left" },
            {
                name: "customer",
                label: "Customer",
                field: "customer",
                align: "left",
            },
            { name: "date", label: "Date", field: "date", align: "left" },
            {
                name: "amount",
                label: "Amount",
                field: "amount",
                align: "right",
            },
            {
                name: "status",
                label: "Status",
                field: "status",
                align: "center",
            },
            { name: "actions", label: "", align: "center" },
        ]);

        // Top Products Data
        const topProducts = ref([
            {
                id: 1,
                name: "Wireless Headphones",
                category: "Electronics",
                price: 99.99,
                sold: 45,
                image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80",
            },
            {
                id: 2,
                name: "Smart Watch",
                category: "Wearables",
                price: 199.99,
                sold: 32,
                image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80",
            },
            {
                id: 3,
                name: "Running Shoes",
                category: "Sports",
                price: 79.99,
                sold: 28,
                image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80",
            },
            {
                id: 4,
                name: "Bluetooth Speaker",
                category: "Electronics",
                price: 59.99,
                sold: 25,
                image: "https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80",
            },
            {
                id: 5,
                name: "Coffee Maker",
                category: "Home",
                price: 129.99,
                sold: 22,
                image: "https://images.unsplash.com/photo-1556910006-89a1c7f5b1c3?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80",
            },
        ]);

        const toggleLeftDrawer = () => {
            leftDrawerOpen.value = !leftDrawerOpen.value;
        };

        const getStatusColor = (status) => {
            switch (status) {
                case "Completed":
                    return "green";
                case "Processing":
                    return "orange";
                case "Shipped":
                    return "blue";
                default:
                    return "grey";
            }
        };

        const viewOrder = (order) => {
            console.log("View order:", order.id);
            // Aquí iría la lógica para ver el detalle del pedido
        };

        return {
            leftDrawerOpen,
            stats,
            salesChartOptions,
            salesChartSeries,
            revenueChartOptions,
            revenueChartSeries,
            topProductsChartOptions,
            topProductsChartSeries,
            todaySalesChartOptions,
            todaySalesChartSeries,
            recentOrders,
            orderColumns,
            topProducts,
            toggleLeftDrawer,
            getStatusColor,
            viewOrder,
        };
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
