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
            class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 dark:from-gray-900 dark:to-blue-900/20 p-4 lg:p-6 transition-colors duration-300"
        >
            <!-- Header Section -->
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 lg:mb-8"
            >
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center space-x-3 mb-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 rounded-2xl flex items-center justify-center shadow-lg"
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
                                class="text-2xl lg:text-3xl font-bold liner-gradient from-gray-900 to-blue-800 dark:from-gray-100 dark:to-blue-200 bg-clip-text text-transparent"
                            >
                                {{ __("Sales Dashboard") }}
                            </h1>
                            <p
                                class="text-gray-600 dark:text-gray-300 mt-1 text-sm lg:text-base"
                            >
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
                    class="group inline-flex items-center space-x-2 px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-200 dark:hover:border-blue-500 hover:text-blue-700 dark:hover:text-blue-400 transition-all duration-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
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
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-sm mb-6 p-4 lg:p-6 transition-colors duration-300"
            >
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                    <!-- Start Date -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400"
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
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        />
                    </div>

                    <!-- End Date -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400"
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
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        />
                    </div>

                    <!-- Chart Type -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400"
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
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300 dark:hover:border-gray-500 appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
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
                            class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4 text-gray-500 dark:text-gray-400"
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
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 hover:border-gray-300 dark:hover:border-gray-500 appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
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
                            class="w-full liner-gradient from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 shadow-lg shadow-blue-500/25 flex items-center justify-center space-x-2"
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

            <!-- KPI Cards Grid -->
            <div
                class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8"
            >
                <!-- Total Sales -->
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 p-4 lg:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600 dark:text-blue-400"
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
                        <div class="text-right">
                            <div
                                class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ total_sales }}
                            </div>
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Total Sales") }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2"
                    >
                        <div
                            class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                            style="width: 75%"
                        ></div>
                    </div>
                </div>

                <!-- Total Commission -->
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 p-4 lg:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-green-100 dark:bg-green-900/30 rounded-2xl"
                        >
                            <svg
                                class="w-6 h-6 text-green-600 dark:text-green-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="text-right">
                            <div
                                class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ formatTotalCommission }}
                            </div>
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Total Commission") }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2"
                    >
                        <div
                            class="bg-green-500 h-2 rounded-full transition-all duration-500"
                            style="width: 85%"
                        ></div>
                    </div>
                </div>

                <!-- Currency Distribution -->
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 p-4 lg:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-2xl"
                        >
                            <svg
                                class="w-6 h-6 text-purple-600 dark:text-purple-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                />
                            </svg>
                        </div>
                        <div class="text-right">
                            <div
                                class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ currencyCount }}
                            </div>
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Currencies") }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2"
                    >
                        <div
                            class="bg-purple-500 h-2 rounded-full transition-all duration-500"
                            :style="`width: ${Math.min(
                                currencyCount * 25,
                                100
                            )}%`"
                        ></div>
                    </div>
                </div>

                <!-- Average Commission -->
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 p-4 lg:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-2xl"
                        >
                            <svg
                                class="w-6 h-6 text-orange-600 dark:text-orange-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <div class="text-right">
                            <div
                                class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ averageCommission }}
                            </div>
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Avg Commission") }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2"
                    >
                        <div
                            class="bg-orange-500 h-2 rounded-full transition-all duration-500"
                            style="width: 65%"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div
                class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8"
            >
                <!-- Sales by Currency Chart -->
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-sm p-4 lg:p-6 transition-colors duration-300"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3
                            class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ __("Commissions by Currency") }}
                        </h3>
                        <div class="flex space-x-2">
                            <button
                                @click="currencyChartType = 'donut'"
                                :class="[
                                    'px-3 py-1 text-sm rounded-lg transition-all duration-200',
                                    currencyChartType === 'donut'
                                        ? 'bg-blue-500 text-white shadow-lg'
                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                                ]"
                            >
                                Donut
                            </button>
                            <button
                                @click="currencyChartType = 'pie'"
                                :class="[
                                    'px-3 py-1 text-sm rounded-lg transition-all duration-200',
                                    currencyChartType === 'pie'
                                        ? 'bg-blue-500 text-white shadow-lg'
                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                                ]"
                            >
                                Pie
                            </button>
                        </div>
                    </div>
                    <div
                        v-if="currencyChartData.length > 0"
                        class="chart-container"
                    >
                        <apex-charts
                            width="100%"
                            height="350"
                            :type="currencyChartType"
                            :options="currencyChartOptions"
                            :series="currencyChartSeries"
                        />
                    </div>
                    <div
                        v-else
                        class="flex items-center justify-center h-64 text-gray-500 dark:text-gray-400"
                    >
                        <div class="text-center">
                            <svg
                                class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4"
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
                            <p
                                class="text-lg font-medium text-gray-500 dark:text-gray-400"
                            >
                                {{ __("No data available") }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Daily Performance Chart -->
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-sm p-4 lg:p-6 transition-colors duration-300"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3
                            class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ __("Daily Performance") }}
                        </h3>
                        <div
                            class="flex bg-gray-100 dark:bg-gray-700 rounded-xl p-1 border border-gray-200 dark:border-gray-600"
                        >
                            <button
                                v-for="option in chartTypes"
                                :key="option.value"
                                @click="chartType = option.value"
                                :class="[
                                    'px-3 py-1 text-sm font-semibold rounded-lg transition-all duration-200',
                                    chartType === option.value
                                        ? 'bg-blue-500 text-white shadow-lg'
                                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-white dark:hover:bg-gray-600',
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
                        class="flex items-center justify-center h-64 text-gray-500 dark:text-gray-400"
                    >
                        <div class="text-center">
                            <svg
                                class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4"
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
                            <p
                                class="text-lg font-medium text-gray-500 dark:text-gray-400"
                            >
                                {{ __("No data available") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Commission Table -->
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-sm p-4 lg:p-6 transition-colors duration-300 mb-6 lg:mb-8"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3
                        class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white"
                    >
                        {{ __("Detailed Commission Breakdown") }}
                    </h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __("Last Updated") }}: {{ lastUpdated }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="border-b border-gray-200 dark:border-gray-600"
                            >
                                <th
                                    class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Date") }}
                                </th>
                                <th
                                    class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Currency") }}
                                </th>
                                <th
                                    class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Commission") }}
                                </th>
                                <th
                                    class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Total Sales") }}
                                </th>
                                <th
                                    class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Actions") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-gray-600"
                        >
                            <tr
                                v-for="(sale, index) in sales"
                                :key="index"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                            >
                                <td
                                    class="py-3 px-4 text-sm text-gray-900 dark:text-white"
                                >
                                    {{ formatDate(sale.date) }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                            :class="
                                                getCurrencyColor(sale.currency)
                                            "
                                        >
                                            {{ sale.currency }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ sale.currency }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div
                                        class="text-sm font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{
                                            formatCurrency(
                                                sale.commission,
                                                sale.currency
                                            )
                                        }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-300"
                                    >
                                        {{ sale.total }} {{ __("sales") }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button
                                        @click="viewSaleDetails(sale)"
                                        class="inline-flex items-center space-x-1 px-3 py-1 text-sm bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-200"
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
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        <span>{{ __("View") }}</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="sales.length === 0" class="text-center py-12">
                    <svg
                        class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                    <p
                        class="text-lg font-medium text-gray-500 dark:text-gray-400 mb-2"
                    >
                        {{ __("No sales data available") }}
                    </p>
                    <p class="text-sm text-gray-400 dark:text-gray-500">
                        {{
                            __(
                                "Sales will appear here once you start generating commissions"
                            )
                        }}
                    </p>
                </div>
            </div>

            <!-- Currency Summary Cards -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6"
            >
                <div
                    v-for="(currency, index) in currencySummary"
                    :key="currency.currency"
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-gray-700/60 p-4 lg:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer group"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-3 rounded-2xl text-white"
                            :class="getCurrencyColor(currency.currency)"
                        >
                            <span class="text-sm font-bold">{{
                                currency.currency
                            }}</span>
                        </div>
                        <div class="text-right">
                            <div
                                class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{
                                    formatCurrency(
                                        currency.total,
                                        currency.currency
                                    )
                                }}
                            </div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{ currency.salesCount }} {{ __("sales") }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2"
                    >
                        <div
                            class="h-2 rounded-full transition-all duration-500"
                            :class="getCurrencyColor(currency.currency)"
                            :style="`width: ${currency.percentage}%`"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Auto-refresh Indicator -->
            <div
                class="flex items-center justify-between bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-200/60 dark:border-gray-700/60 p-4 mt-6 transition-colors duration-300"
            >
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-4 h-4 text-blue-600 dark:text-blue-400"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"
                            />
                        </svg>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Data auto-refreshes every") }}
                        <span
                            class="font-semibold text-blue-600 dark:text-blue-400"
                            >{{ refreshCountdown }}s</span
                        >
                    </div>
                </div>
                <button
                    @click="disableAutoRefresh"
                    class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium transition-colors duration-200 flex items-center space-x-1"
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
import { useForm } from "@inertiajs/vue3";

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
            currencyChartType: "donut",
            chartTypes: [
                { label: __("Line"), value: "line" },
                { label: __("Bar"), value: "bar" },
                { label: __("Area"), value: "area" },
            ],
            total_sales: 0,
            total_commission: [],
            lastUpdated: new Date().toLocaleTimeString(),

            // Main chart options
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
                    background: "transparent",
                },
                theme: {
                    mode: "light",
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                },
                colors: ["#3B82F6", "#10B981", "#8B5CF6", "#F59E0B"],
                grid: {
                    borderColor: "#e5e7eb",
                    strokeDashArray: 4,
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
                            return val.toLocaleString();
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
                            return val.toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            });
                        },
                    },
                },
            },
            chartSeries: [
                {
                    name: __("Total Sales"),
                    data: [],
                },
                {
                    name: __("Commission"),
                    data: [],
                },
            ],

            // Currency chart options
            currencyChartOptions: {
                chart: {
                    type: "donut",
                    background: "transparent",
                },
                theme: {
                    mode: "light",
                },
                labels: [],
                colors: [
                    "#3B82F6",
                    "#10B981",
                    "#8B5CF6",
                    "#F59E0B",
                    "#EF4444",
                    "#06B6D4",
                ],
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return (
                            opts.w.config.labels[opts.seriesIndex] +
                            ": " +
                            val.toFixed(1) +
                            "%"
                        );
                    },
                },
                legend: {
                    position: "bottom",
                    horizontalAlign: "center",
                    fontSize: "12px",
                    fontFamily: "Inter, sans-serif",
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val.toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            });
                        },
                    },
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: __("Total Commission"),
                                    formatter: function (w) {
                                        const total =
                                            w.globals.seriesTotals.reduce(
                                                (a, b) => a + b,
                                                0
                                            );
                                        return total.toLocaleString("en-US", {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2,
                                        });
                                    },
                                },
                            },
                        },
                    },
                },
            },
            currencyChartSeries: [],
            currencyChartData: [],

            types: [
                { label: __("Daily"), value: "day" },
                { label: __("Monthly"), value: "month" },
                { label: __("Yearly"), value: "year" },
            ],
            refreshInterval: null,
            refreshCountdown: 10,
            countdownInterval: null,
            themeObserver: null,
        };
    },

    computed: {
        formatTotalCommission() {
            if (this.total_commission.length === 0) return "$0.00";

            // Sum all commissions across currencies
            const totalAmount = this.total_commission.reduce((sum, curr) => {
                const amount = this.parseAmount(curr.total);
                return sum + amount;
            }, 0);

            return this.formatNumber(totalAmount);
        },

        currencyCount() {
            const currencies = new Set(this.sales.map((sale) => sale.currency));
            return currencies.size;
        },

        averageCommission() {
            if (this.sales.length === 0) return "$0.00";

            const totalCommission = this.sales.reduce((sum, sale) => {
                return sum + this.parseAmount(sale.commission);
            }, 0);

            const avg = totalCommission / this.sales.length;
            return this.formatNumber(avg);
        },

        currencySummary() {
            const summary = {};

            this.sales.forEach((sale) => {
                if (!summary[sale.currency]) {
                    summary[sale.currency] = {
                        currency: sale.currency,
                        total: 0,
                        salesCount: 0,
                    };
                }
                summary[sale.currency].total += this.parseAmount(
                    sale.commission
                );
                summary[sale.currency].salesCount += sale.total;
            });

            // Calculate percentages for progress bars
            const totalCommission = Object.values(summary).reduce(
                (sum, curr) => sum + curr.total,
                0
            );
            Object.values(summary).forEach((currency) => {
                currency.percentage =
                    totalCommission > 0
                        ? (currency.total / totalCommission) * 100
                        : 0;
            });

            return Object.values(summary);
        },
    },

    watch: {
        chartType(value) {
            this.updateChart(this.sales);
        },
        currencyChartType(value) {
            this.updateCurrencyChart(this.sales);
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
        this.setupThemeListener();
    },

    beforeUnmount() {
        this.clearIntervals();
        if (this.themeObserver) {
            this.themeObserver.disconnect();
        }
    },

    methods: {
        // Helper function to parse amounts safely
        parseAmount(amount) {
            if (typeof amount === "number") return amount;
            if (typeof amount === "string") {
                // Remove commas and convert to number
                return parseFloat(amount.replace(/,/g, "")) || 0;
            }
            return 0;
        },

        // Helper function to format numbers as currency
        formatNumber(amount) {
            return new Intl.NumberFormat("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(amount);
        },

        setupThemeListener() {
            this.themeObserver = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === "class") {
                        this.updateChartTheme();
                    }
                });
            });

            this.themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ["class"],
            });

            this.updateChartTheme();
        },

        updateChartTheme() {
            const isDark = document.documentElement.classList.contains("dark");
            const themeMode = isDark ? "dark" : "light";
            const textColor = isDark ? "#9ca3af" : "#6b7280";
            const borderColor = isDark ? "#374151" : "#e5e7eb";
            const gridColor = isDark ? "#374151" : "#e5e7eb";

            // Update main chart theme
            this.chartOptions = {
                ...this.chartOptions,
                theme: { mode: themeMode },
                grid: { ...this.chartOptions.grid, borderColor: gridColor },
                xaxis: {
                    ...this.chartOptions.xaxis,
                    labels: {
                        ...this.chartOptions.xaxis.labels,
                        colors: textColor,
                    },
                    axisBorder: {
                        ...this.chartOptions.xaxis.axisBorder,
                        color: borderColor,
                    },
                    axisTicks: {
                        ...this.chartOptions.xaxis.axisTicks,
                        color: borderColor,
                    },
                },
                yaxis: {
                    ...this.chartOptions.yaxis,
                    labels: {
                        ...this.chartOptions.yaxis.labels,
                        colors: textColor,
                    },
                },
                tooltip: { ...this.chartOptions.tooltip, theme: themeMode },
            };

            // Update currency chart theme
            this.currencyChartOptions = {
                ...this.currencyChartOptions,
                theme: { mode: themeMode },
            };
        },

        getDefaultDates() {
            const today = new Date();
            const end = today.toISOString().split("T")[0];
            const pastDate = new Date(today);
            pastDate.setMonth(today.getMonth() - 3);
            const start = pastDate.toISOString().split("T")[0];
            return { start, end };
        },

        getSales() {
            const form = useForm(this.params);
            form.get(this.$page.props.route, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    this.sales = page.props.data.data || [];
                    this.total_sales = page.props.data.total_sales || 0;
                    this.total_commission =
                        page.props.data.total_commission || [];
                    this.lastUpdated = new Date().toLocaleTimeString();

                    this.updateChart(this.sales);
                    this.updateCurrencyChart(this.sales);
                    this.resetRefreshCountdown();
                },
            });
        },

        updateChart(data) {
            if (data && data.length > 0) {
                // Group by date for time series
                const dateGroups = {};
                data.forEach((item) => {
                    if (!dateGroups[item.date]) {
                        dateGroups[item.date] = {
                            date: item.date,
                            totalSales: 0,
                            totalCommission: 0,
                        };
                    }
                    dateGroups[item.date].totalSales += item.total;
                    dateGroups[item.date].totalCommission += this.parseAmount(
                        item.commission
                    );
                });

                const sortedDates = Object.keys(dateGroups).sort();

                this.chartSeries = [
                    {
                        name: __("Total Sales"),
                        data: sortedDates.map(
                            (date) => dateGroups[date].totalSales
                        ),
                    },
                    {
                        name: __("Commission"),
                        data: sortedDates.map(
                            (date) => dateGroups[date].totalCommission
                        ),
                    },
                ];

                this.chartOptions = {
                    ...this.chartOptions,
                    xaxis: {
                        ...this.chartOptions.xaxis,
                        categories: sortedDates.map((date) =>
                            this.formatDate(date)
                        ),
                    },
                };
            } else {
                // Reset chart data
                this.chartSeries = [
                    { name: __("Total Sales"), data: [] },
                    { name: __("Commission"), data: [] },
                ];
            }
        },

        updateCurrencyChart(data) {
            if (data && data.length > 0) {
                const currencyTotals = {};

                data.forEach((item) => {
                    if (!currencyTotals[item.currency]) {
                        currencyTotals[item.currency] = 0;
                    }
                    currencyTotals[item.currency] += this.parseAmount(
                        item.commission
                    );
                });

                this.currencyChartData = Object.keys(currencyTotals).map(
                    (currency) => ({
                        currency,
                        total: currencyTotals[currency],
                    })
                );

                this.currencyChartSeries = this.currencyChartData.map(
                    (item) => item.total
                );
                this.currencyChartOptions = {
                    ...this.currencyChartOptions,
                    labels: this.currencyChartData.map((item) => item.currency),
                };
            } else {
                this.currencyChartSeries = [];
                this.currencyChartOptions = {
                    ...this.currencyChartOptions,
                    labels: [],
                };
            }
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
            });
        },

        formatCurrency(amount, currency) {
            const numericAmount = this.parseAmount(amount);
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: currency,
                minimumFractionDigits: 2,
            }).format(numericAmount);
        },

        getCurrencyColor(currency) {
            const colors = {
                USD: "bg-blue-500",
                EUR: "bg-green-500",
                PEN: "bg-purple-500",
                AUD: "bg-orange-500",
                GBP: "bg-red-500",
                JPY: "bg-yellow-500",
            };
            return colors[currency] || "bg-gray-500";
        },

        viewSaleDetails(sale) {
            // Implement sale details view logic
            console.log("View sale details:", sale);
        },

        startAutoRefresh() {
            this.refreshInterval = setInterval(() => {
                this.getSales();
            }, 30000); // Refresh every 30 seconds

            this.countdownInterval = setInterval(() => {
                this.refreshCountdown--;
                if (this.refreshCountdown <= 0) {
                    this.resetRefreshCountdown();
                }
            }, 1000);
        },

        resetRefreshCountdown() {
            this.refreshCountdown = 30;
        },

        disableAutoRefresh() {
            this.clearIntervals();
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
/* Transiciones suaves para todos los elementos */
* {
    transition: background-color 0.3s ease, border-color 0.3s ease,
        color 0.3s ease;
}

/* Mejoras de contraste para dark mode */
.dark .bg-gradient-to-br {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Efectos hover mejorados */
.group:hover .group-hover\:rotate-180 {
    transform: rotate(180deg);
}

/* Mejoras de accesibilidad */
button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}

/* Scrollbar personalizado para dark mode */
.dark ::-webkit-scrollbar {
    width: 8px;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 4px;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Responsive table styles */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .table-responsive th,
    .table-responsive td {
        padding: 0.5rem 0.25rem;
    }
}
</style>
