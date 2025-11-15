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
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
            <!-- Header -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ __("Dashboard Overview") }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">
                                {{ __("Welcome back! Here's what's happening with your store today.") }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Theme Toggle -->
                            <button
                                @click="toggleTheme"
                                class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                                :title="__('Toggle theme')"
                            >
                                <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </button>
                            
                            <!-- Refresh Button -->
                            <button
                                @click="getData"
                                class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-200"
                                :title="__('Refresh data')"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-4 lg:p-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                    <!-- Total Sales -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ dashboard.currency_symbol }}{{ formatNumber(dashboard.transactions_total) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __("Total Sales") }}</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: 75%"></div>
                        </div>
                    </div>

                    <!-- Today Sales -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-2xl">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ dashboard.currency_symbol }}{{ formatNumber(dashboard.transactions_today) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __("Today Sales") }}</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: 85%"></div>
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-2xl">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ formatNumber(dashboard.products_stock_total) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __("Total Products") }}</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: 65%"></div>
                        </div>
                    </div>

                    <!-- Pending Orders -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-2xl">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ formatNumber(dashboard.products_pending) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __("Pending Orders") }}</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" style="width: 45%"></div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8 mb-6 lg:mb-8">
                    <!-- Sales Overview Chart -->
                    <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 transition-colors duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">
                                {{ __("Sales Overview (Last 30 Days)") }}
                            </h3>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="area"
                                height="350"
                                :options="salesChartOptions"
                                :series="salesChartSeries"
                            />
                        </div>
                    </div>

                    <!-- Revenue Distribution -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 transition-colors duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">
                                {{ __("Revenue Distribution") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="donut"
                                height="350"
                                :options="revenueChartOptions"
                                :series="revenueChartSeries"
                            />
                        </div>
                    </div>
                </div>

                <!-- Second Row Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8">
                    <!-- Top Selling Products -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 transition-colors duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">
                                {{ __("Top Selling Products") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="bar"
                                height="300"
                                :options="topProductsChartOptions"
                                :series="topProductsChartSeries"
                            />
                        </div>
                    </div>

                    <!-- Today's Sales Progress -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 transition-colors duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">
                                {{ __("Today's Sales Progress") }}
                            </h3>
                        </div>
                        <div class="chart-container">
                            <apexchart
                                type="radialBar"
                                height="300"
                                :options="todaySalesChartOptions"
                                :series="todaySalesChartSeries"
                            />
                        </div>
                    </div>
                </div>

                <!-- Data Tables Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    <!-- Recent Orders -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ __("Recent Orders") }}
                                </h3>
                                <button class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors">
                                    {{ __("View All") }}
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __("Order") }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __("Customer") }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __("Date") }}
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __("Total") }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __("Status") }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            {{ __("Actions") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr 
                                        v-for="checkout in checkouts" 
                                        :key="checkout.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200 group"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            #{{ checkout.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ checkout.customer }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ formatDate(checkout.date) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">
                                            {{ dashboard.currency_symbol }}{{ checkout.total }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border transition-all duration-200 transform group-hover:scale-105"
                                                :class="getStatusClasses(checkout.status)"
                                            >
                                                <span class="w-2 h-2 rounded-full mr-1.5" :class="getStatusDotClass(checkout.status)"></span>
                                                {{ checkout.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button
                                                @click="viewOrder(checkout)"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 p-1 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30"
                                                :title="__('View order details')"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Products List -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ __("Top Selling Products") }}
                                </h3>
                                <button class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors">
                                    {{ __("View All") }}
                                </button>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-600">
                            <div
                                v-for="product in topProducts"
                                :key="product.id"
                                class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200 group"
                            >
                                <div class="flex-shrink-0 h-12 w-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-600">
                                    <img
                                        :src="product.image || '/images/placeholder-product.png'"
                                        :alt="product.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ product.name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ product.category }}
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                        {{ dashboard.currency_symbol }}{{ product.price }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __("Sold") }}: {{ product.sold }}
                                    </div>
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
            isDark: false,
            dashboard: {
                currency_symbol: '$',
                transactions_total: 0,
                transactions_today: 0,
                products_stock_total: 0,
                products_pending: 0,
                products_lower_stock: 0,
                transactions: [],
                checkouts: [],
                top_products: []
            },
            revenue: [],
            checkouts: [],
            topProducts: [],

            // Chart options with theme support
            salesChartOptions: {
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
                    background: 'transparent',
                },
                theme: {
                    mode: 'light'
                },
                colors: ["#3B82F6"],
                dataLabels: {
                    enabled: false
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
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            colors: '#6B7280'
                        }
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
                            colors: '#6B7280'
                        },
                        formatter: (val) => this.dashboard.currency_symbol + this.formatNumber(val)
                    },
                },
                grid: {
                    borderColor: '#F3F4F6',
                    strokeDashArray: 4,
                },
                tooltip: {
                    y: {
                        formatter: (val) => this.dashboard.currency_symbol + this.formatNumber(val)
                    },
                },
            },

            salesChartSeries: [],

            revenueChartOptions: {
                chart: {
                    type: "donut",
                    background: 'transparent'
                },
                theme: {
                    mode: 'light'
                },
                labels: [],
                colors: ["#3B82F6", "#10B981", "#F59E0B", "#8B5CF6", "#EF4444"],
                legend: {
                    position: "bottom",
                    labels: {
                        colors: '#6B7280'
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val) => val.toFixed(1) + '%',
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold'
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    color: '#6B7280'
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 'bold',
                                    color: '#111827',
                                    formatter: (val) => this.dashboard.currency_symbol + this.formatNumber(val)
                                },
                                total: {
                                    show: true,
                                    label: __("Total Revenue"),
                                    color: '#6B7280',
                                    formatter: () => this.dashboard.currency_symbol + this.formatNumber(this.revenue.reduce((sum, item) => sum + item.total, 0))
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: (val) => this.dashboard.currency_symbol + this.formatNumber(val)
                    }
                }
            },

            revenueChartSeries: [],

            topProductsChartOptions: {
                chart: {
                    type: "bar",
                    toolbar: { show: false },
                    background: 'transparent'
                },
                theme: {
                    mode: 'light'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: true,
                    }
                },
                colors: ["#10B981"],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            colors: '#6B7280'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6B7280'
                        }
                    }
                },
                grid: {
                    borderColor: '#F3F4F6',
                    strokeDashArray: 4,
                },
                tooltip: {
                    y: {
                        formatter: (val) => val + " " + __("units sold")
                    }
                }
            },

            topProductsChartSeries: [],

            todaySalesChartOptions: {
                chart: {
                    type: "radialBar",
                    background: 'transparent'
                },
                theme: {
                    mode: 'light'
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 225,
                        hollow: {
                            margin: 0,
                            size: "70%",
                            background: "transparent",
                            position: "front",
                        },
                        track: {
                            background: "#F3F4F6",
                            strokeWidth: "67%",
                            margin: 0,
                        },
                        dataLabels: {
                            show: true,
                            name: {
                                offsetY: -10,
                                color: "#6B7280",
                                fontSize: "13px",
                            },
                            value: {
                                formatter: (val) => this.dashboard.currency_symbol + this.formatNumber(val),
                                color: "#111827",
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
                        gradientToColors: ["#10B981"],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                    },
                },
                stroke: {
                    lineCap: "round"
                },
                labels: [__("Today Sales")],
            },

            todaySalesChartSeries: [],
        };
    },

    created() {
        this.detectTheme();
        this.getData();
    },

    methods: {
        detectTheme() {
            this.isDark = document.documentElement.classList.contains('dark');
            this.updateChartThemes();
        },

        toggleTheme() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            this.updateChartThemes();
        },

        updateChartThemes() {
            const themeMode = this.isDark ? 'dark' : 'light';
            const textColor = this.isDark ? '#9CA3AF' : '#6B7280';
            const backgroundColor = this.isDark ? '#1F2937' : '#FFFFFF';
            const gridColor = this.isDark ? '#374151' : '#F3F4F6';

            // Update all chart themes
            const charts = [
                'salesChartOptions',
                'revenueChartOptions', 
                'topProductsChartOptions',
                'todaySalesChartOptions'
            ];

            charts.forEach(chartName => {
                if (this[chartName]) {
                    this[chartName] = {
                        ...this[chartName],
                        theme: { mode: themeMode },
                        chart: {
                            ...this[chartName].chart,
                            background: 'transparent'
                        }
                    };

                    // Update specific properties for each chart type
                    if (this[chartName].xaxis) {
                        this[chartName].xaxis.labels.style.colors = textColor;
                    }
                    if (this[chartName].yaxis) {
                        this[chartName].yaxis.labels.style.colors = textColor;
                    }
                    if (this[chartName].grid) {
                        this[chartName].grid.borderColor = gridColor;
                    }
                    if (this[chartName].legend && this[chartName].legend.labels) {
                        this[chartName].legend.labels.colors = textColor;
                    }
                }
            });
        },

        formatNumber(num) {
            return parseFloat(num || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        formatDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        },

        async getData() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.dashboard
                );

                if (res.status == 200) {
                    this.dashboard = res.data;
                    this.checkouts = res.data.checkouts || [];
                    this.topProducts = res.data.top_products || [];
                    this.revenue = res.data.revenue || [];
                    this.todaySalesChartSeries = [res.data.transactions_today || 0];
                    this.renderSales();
                    this.renderTopProducts();
                    this.renderRevenue();
                }
            } catch (e) {
                console.error('Error fetching dashboard data:', e);
            }
        },

        renderTopProducts() {
            this.topProductsChartOptions = {
                ...this.topProductsChartOptions,
                xaxis: {
                    ...this.topProductsChartOptions.xaxis,
                    categories: this.topProducts.map((item) => item.name || item.category),
                },
            };

            this.topProductsChartSeries = [
                {
                    name: __("Units Sold"),
                    data: this.topProducts.map((item) => item.sold || 0),
                },
            ];
        },

        renderRevenue() {
            const totalRevenue = this.revenue.reduce(
                (sum, item) => sum + (item.total || 0),
                0
            );

            this.revenueChartOptions = {
                ...this.revenueChartOptions,
                labels: this.revenue.map((item) => item.name || 'Unknown'),
            };

            this.revenueChartSeries = this.revenue.map((item) => item.total || 0);
        },

        renderSales() {
            this.salesChartSeries = [
                {
                    name: __("Sales"),
                    data: (this.dashboard.transactions || []).map((item) => item.total || 0),
                },
            ];

            this.salesChartOptions = {
                ...this.salesChartOptions,
                xaxis: {
                    ...this.salesChartOptions.xaxis,
                    categories: (this.dashboard.transactions || []).map(
                        (item) => item.date || ''
                    ),
                },
            };
        },

        getStatusClasses(status) {
            const statusClasses = {
                successful: 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 border-green-200 dark:border-green-700',
                pending: 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-700',
                failed: 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-200 dark:border-red-700',
                refunded: 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-700',
            };

            return statusClasses[status] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-600';
        },

        getStatusDotClass(status) {
            const dotClasses = {
                successful: 'bg-green-500',
                pending: 'bg-yellow-500',
                failed: 'bg-red-500',
                refunded: 'bg-blue-500',
            };

            return dotClasses[status] || 'bg-gray-500';
        },

        viewOrder(order) {
            if (order.link) {
                window.location.href = order.link;
            }
        },
    },
};
</script>

<style scoped>
.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card:hover {
    transform: translateY(-4px);
}

.chart-container {
    position: relative;
}

/* Smooth transitions for dark mode */
* {
    transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
}

/* Custom scrollbar for tables */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.dark .overflow-x-auto::-webkit-scrollbar-track {
    background: #374151;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>