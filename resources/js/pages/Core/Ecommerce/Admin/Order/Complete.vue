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
        <div
            class="min-h-screen bg-linear-to-br from-gray-50 to-blue-50/30 dark:from-gray-900 dark:to-gray-800 py-4 sm:py-6 px-3 sm:px-6 lg:px-8 transition-colors duration-300"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6 sm:mb-8 transition-colors duration-300"
                >
                    <div
                        class="bg-linear-to-r from-blue-600 to-purple-600 dark:from-blue-700 dark:to-purple-700 px-4 sm:px-6 py-6 sm:py-8"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div
                                class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-0"
                            >
                                <div
                                    class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 dark:bg-black/20 rounded-xl sm:rounded-2xl flex items-center justify-center backdrop-blur-sm"
                                >
                                    <i
                                        class="fas fa-shopping-bag text-white text-lg sm:text-2xl"
                                    ></i>
                                </div>
                                <div>
                                    <h1
                                        class="text-lg sm:text-xl md:text-2xl font-bold text-white"
                                    >
                                        {{
                                            __("Orders Successfully Processed")
                                        }}
                                    </h1>
                                    <p
                                        class="text-blue-100 dark:text-blue-200 mt-1 text-xs sm:text-sm"
                                    >
                                        {{
                                            __(
                                                "Manage and track all your customer orders"
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                            <button
                                v-if="orders.length > 0"
                                @click="getCheckouts"
                                :disabled="loading"
                                class="px-3 sm:px-4 py-2 bg-white/20 dark:bg-black/20 hover:bg-white/30 dark:hover:bg-black/30 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-white/30 dark:border-white/20 text-sm"
                            >
                                <i
                                    class="fas fa-sync-alt text-xs sm:text-sm"
                                    :class="{ 'animate-spin': loading }"
                                ></i>
                                <span class="font-medium">{{
                                    __("Refresh")
                                }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="orders.length === 0 && !loading"
                    class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sm:p-8 lg:p-12 text-center transition-colors duration-300"
                >
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-linear-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-2xl sm:rounded-3xl flex items-center justify-center mx-auto mb-4 sm:mb-6"
                        >
                            <i
                                class="fas fa-inbox text-gray-400 dark:text-gray-500 text-2xl sm:text-3xl lg:text-4xl"
                            ></i>
                        </div>
                        <h3
                            class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("No Orders Yet") }}
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 mb-4 sm:mb-6 text-xs sm:text-sm"
                        >
                            {{
                                __(
                                    "Your orders will appear here once customers start purchasing your products"
                                )
                            }}
                        </p>
                        <a
                            href="#"
                            class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-linear-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 dark:hover:from-blue-700 dark:hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl text-sm sm:text-base"
                        >
                            <i class="fas fa-store mr-2 text-xs sm:text-sm"></i>
                            {{ __("View Products") }}
                        </a>
                    </div>
                </div>

                <!-- Orders Content -->
                <div v-else-if="orders.length > 0">
                    <!-- Stats Overview -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8"
                    >
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 transition-colors duration-300"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-2 sm:mr-3 lg:mr-4"
                                >
                                    <i
                                        class="fas fa-shopping-bag text-blue-600 dark:text-blue-500 text-sm sm:text-base lg:text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <p
                                        class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400"
                                    >
                                        {{ __("Total Orders") }}
                                    </p>
                                    <p
                                        class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ orders.length }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 transition-colors duration-300"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-2 sm:mr-3 lg:mr-4"
                                >
                                    <i
                                        class="fas fa-check-circle text-green-600 dark:text-green-500 text-sm sm:text-base lg:text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <p
                                        class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400"
                                    >
                                        {{ __("Completed") }}
                                    </p>
                                    <p
                                        class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ completedOrdersCount }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 transition-colors duration-300"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-2 sm:mr-3 lg:mr-4"
                                >
                                    <i
                                        class="fas fa-clock text-yellow-600 dark:text-yellow-500 text-sm sm:text-base lg:text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <p
                                        class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400"
                                    >
                                        {{ __("Pending") }}
                                    </p>
                                    <p
                                        class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ pendingOrdersCount }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-4 lg:p-6 transition-colors duration-300"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-2 sm:mr-3 lg:mr-4"
                                >
                                    <i
                                        class="fas fa-times-circle text-red-600 dark:text-red-500 text-sm sm:text-base lg:text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <p
                                        class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400"
                                    >
                                        {{ __("Failed") }}
                                    </p>
                                    <p
                                        class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ failedOrdersCount }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Per Page Selector -->
                    <div
                        class="flex items-center justify-end mb-4 sm:mb-5 p-3 sm:p-4 space-x-2 bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm transition-colors duration-300"
                    >
                        <i
                            class="fas fa-list-ol text-gray-400 dark:text-gray-500 text-sm"
                        ></i>
                        <select
                            v-model="search.per_page"
                            @change="getCheckouts"
                            class="border-0 focus:ring-0 text-gray-700 dark:text-gray-300 font-medium bg-transparent dark:bg-gray-800 text-sm sm:text-base"
                        >
                            <option value="5">5 {{ __("per page") }}</option>
                            <option value="10">10 {{ __("per page") }}</option>
                            <option value="15">15 {{ __("per page") }}</option>
                            <option value="25">25 {{ __("per page") }}</option>
                            <option value="50">50 {{ __("per page") }}</option>
                            <option value="100">
                                100 {{ __("per page") }}
                            </option>
                        </select>
                    </div>

                    <!-- Orders List -->
                    <div class="space-y-3 sm:space-y-4 ">
                        <div
                            v-for="order in orders"
                            :key="order.id"
                            class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl dark:hover:shadow-2xl transition-colors duration-300"
                        >
                            <!-- Order Header -->
                            <div
                                class="p-4 sm:p-6 cursor-pointer border-b border-gray-100 dark:border-gray-700 transition-colors duration-300"
                                @click="toggleOrder(order.id)"
                            >
                                <div class="flex items-center justify-between">
                                    <div
                                        class="flex items-center space-x-3 sm:space-x-4"
                                    >
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 bg-linear-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg sm:rounded-xl flex items-center justify-center"
                                        >
                                            <span
                                                class="text-blue-600 dark:text-blue-500 font-bold text-base sm:text-lg"
                                                >{{
                                                    orderNumberIcon(order.code)
                                                }}</span
                                            >
                                        </div>
                                        <div>
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base"
                                            >
                                                {{ __("Order") }} #{{
                                                    order.code
                                                }}
                                            </h3>
                                            <p
                                                class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1"
                                            >
                                                <i
                                                    class="fas fa-calendar-alt mr-2 text-blue-500 dark:text-blue-400 text-xs"
                                                ></i>
                                                {{ order.created_at }}
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="flex items-center space-x-2 sm:space-x-4"
                                    >
                                        <div class="text-right">
                                            <span
                                                :class="
                                                    getStatusBadgeClass(
                                                        order.transaction.status
                                                    )
                                                "
                                                class="text-xs sm:text-sm"
                                            >
                                                {{
                                                    __(order.transaction.status)
                                                }}
                                            </span>
                                            <p
                                                class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mt-1"
                                            >
                                                {{ order.transaction.total }}
                                                {{ order.transaction.currency }}
                                            </p>
                                        </div>
                                        <i
                                            class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-300 text-xs sm:text-sm"
                                            :class="{
                                                'rotate-180':
                                                    expandedOrders[order.id],
                                            }"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div
                                v-if="expandedOrders[order.id]"
                                class="p-4 sm:p-6 bg-gray-50/50 dark:bg-gray-700/50 transition-colors duration-300"
                            >
                                <div class="space-y-4 sm:space-y-6">
                                    <!-- Transaction and Delivery Info -->
                                    <div
                                        class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6"
                                    >
                                        <!-- Transaction Details -->
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        >
                                            <div
                                                class="flex items-center mb-3 sm:mb-4"
                                            >
                                                <i
                                                    class="fas fa-receipt text-blue-500 dark:text-blue-400 mr-2 sm:mr-3 text-sm sm:text-base"
                                                ></i>
                                                <h4
                                                    class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base"
                                                >
                                                    {{
                                                        __(
                                                            "Transaction Details"
                                                        )
                                                    }}
                                                </h4>
                                            </div>
                                            <div class="space-y-2 sm:space-y-3">
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __("Status")
                                                        }}</span
                                                    >
                                                    <span
                                                        :class="
                                                            getStatusTextClass(
                                                                order
                                                                    .transaction
                                                                    .status
                                                            )
                                                        "
                                                        class="text-xs sm:text-sm"
                                                    >
                                                        {{
                                                            __(
                                                                order
                                                                    .transaction
                                                                    .status
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __("Payment Method")
                                                        }}</span
                                                    >
                                                    <span
                                                        class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white"
                                                        >{{
                                                            order.transaction
                                                                .payment_method
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __("Total Amount")
                                                        }}</span
                                                    >
                                                    <span
                                                        class="text-base sm:text-lg font-bold text-green-600 dark:text-green-500"
                                                    >
                                                        {{
                                                            order.transaction
                                                                .total
                                                        }}
                                                        {{
                                                            order.transaction
                                                                .currency
                                                        }}
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        order.transaction
                                                            .payment_intent_id
                                                    "
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __("Payment Intent")
                                                        }}</span
                                                    >
                                                    <div
                                                        class="flex items-center space-x-2"
                                                    >
                                                        <code
                                                            class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-300"
                                                        >
                                                            {{
                                                                order
                                                                    .transaction
                                                                    .payment_intent_id
                                                            }}
                                                        </code>
                                                        <button
                                                            @click="
                                                                copyToClipboard(
                                                                    order
                                                                        .transaction
                                                                        .payment_intent_id,
                                                                    'Payment Intent ID'
                                                                )
                                                            "
                                                            class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                                            title="Copy Payment Intent ID"
                                                        >
                                                            <i
                                                                class="fas fa-copy text-xs"
                                                            ></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div
                                                    v-if="
                                                        order.transaction.code
                                                    "
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __(
                                                                "Transaction Code"
                                                            )
                                                        }}</span
                                                    >
                                                    <div
                                                        class="flex items-center space-x-2"
                                                    >
                                                        <code
                                                            class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-300"
                                                        >
                                                            {{
                                                                order
                                                                    .transaction
                                                                    .code
                                                            }}
                                                        </code>
                                                        <button
                                                            @click="
                                                                copyToClipboard(
                                                                    order
                                                                        .transaction
                                                                        .code,
                                                                    'Transaction Code'
                                                                )
                                                            "
                                                            class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                                            title="Copy Transaction Code"
                                                        >
                                                            <i
                                                                class="fas fa-copy text-xs"
                                                            ></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delivery Address -->
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        >
                                            <div
                                                class="flex items-center mb-3 sm:mb-4"
                                            >
                                                <i
                                                    class="fas fa-truck text-green-500 dark:text-green-400 mr-2 sm:mr-3 text-sm sm:text-base"
                                                ></i>
                                                <h4
                                                    class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base"
                                                >
                                                    {{ __("Delivery Address") }}
                                                </h4>
                                            </div>
                                            <div class="space-y-2">
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white text-sm sm:text-base"
                                                >
                                                    {{
                                                        order.delivery_address
                                                            .full_name
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                >
                                                    {{
                                                        order.delivery_address
                                                            .address
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                >
                                                    {{
                                                        order.delivery_address
                                                            .city
                                                    }},
                                                    {{
                                                        order.delivery_address
                                                            .district
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                >
                                                    {{
                                                        order.delivery_address
                                                            .country
                                                    }}
                                                </p>
                                                <div
                                                    class="flex items-center justify-between mt-3"
                                                >
                                                    <div
                                                        class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                    >
                                                        <i
                                                            class="fas fa-phone mr-2 text-xs"
                                                        ></i>
                                                        {{
                                                            order
                                                                .delivery_address
                                                                .phone
                                                        }}
                                                    </div>
                                                    <a
                                                        v-if="
                                                            order
                                                                .delivery_address
                                                                .whatsapp
                                                        "
                                                        :href="
                                                            order
                                                                .delivery_address
                                                                .whatsapp
                                                        "
                                                        target="_blank"
                                                        class="w-6 h-6 sm:w-8 sm:h-8 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded-full flex items-center justify-center transition-colors"
                                                    >
                                                        <i
                                                            class="fab fa-whatsapp text-xs sm:text-sm"
                                                        ></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4 sm:mb-6"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-boxes text-purple-500 dark:text-purple-400 mr-2 sm:mr-3 text-sm sm:text-base"
                                                ></i>
                                                <h4
                                                    class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base"
                                                >
                                                    {{ __("Order Items") }}
                                                </h4>
                                            </div>
                                            <span
                                                class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium"
                                            >
                                                {{ order.orders.length }}
                                                {{ __("items") }}
                                            </span>
                                        </div>
                                        <div
                                            class="grid grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 gap-3 sm:gap-4"
                                        >
                                            <div
                                                v-for="item in order.orders"
                                                :key="item.id"
                                                class="flex items-center space-x-3 sm:space-x-4 p-3 sm:p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-colors duration-300"
                                            >
                                                <img
                                                    v-if="
                                                        item.images &&
                                                        item.images.length > 0
                                                    "
                                                    :src="
                                                        getImageUrl(
                                                            item.images[0].url
                                                        )
                                                    "
                                                    :alt="item.meta.name"
                                                    class="w-12 h-12 sm:w-16 sm:h-16 rounded-lg object-cover flex-shrink-0"
                                                />
                                                <div
                                                    v-else
                                                    class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0"
                                                >
                                                    <i
                                                        class="fas fa-image text-gray-400 dark:text-gray-500 text-base sm:text-xl"
                                                    ></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white truncate"
                                                    >
                                                        {{ item.meta.name }}
                                                    </p>
                                                    <p
                                                        v-if="item.meta.variant"
                                                        class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1"
                                                    >
                                                        <strong
                                                            >{{
                                                                __("Variant")
                                                            }}:</strong
                                                        >
                                                        {{
                                                            item.meta.variant
                                                                .name
                                                        }}
                                                    </p>
                                                    <div
                                                        class="flex items-center space-x-3 sm:space-x-4 mt-2"
                                                    >
                                                        <p
                                                            class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                        >
                                                            <strong
                                                                >{{
                                                                    __("Qty")
                                                                }}:</strong
                                                            >
                                                            {{ item.quantity }}
                                                        </p>
                                                        <p
                                                            class="text-xs sm:text-sm text-gray-600 dark:text-gray-400"
                                                        >
                                                            <strong
                                                                >{{
                                                                    __(
                                                                        "Unit Price"
                                                                    )
                                                                }}:</strong
                                                            >
                                                            {{
                                                                item.format_price
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="text-right flex-shrink-0"
                                                >
                                                    <p
                                                        class="text-base sm:text-lg font-bold text-green-600 dark:text-green-500"
                                                    >
                                                        {{
                                                            calculateItemTotal(
                                                                item
                                                            )
                                                        }}
                                                    </p>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                                    >
                                                        {{ __("Total") }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div
                                    class="flex flex-wrap gap-2 sm:gap-3 mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-600 transition-colors duration-300"
                                >
                                    <a
                                        v-if="order.transaction.payment_url"
                                        :href="order.transaction.payment_url"
                                        target="_blank"
                                        class="px-3 sm:px-4 py-2 bg-blue-500 cursor-pointer hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md text-xs sm:text-sm"
                                    >
                                        <i
                                            class="fas fa-receipt text-xs sm:text-sm"
                                        ></i>
                                        <span>{{ __("View Receipt") }}</span>
                                    </a>

                                    <button
                                        @click="copyOrderId(order.code)"
                                        class="px-3 sm:px-4 py-2 bg-gray-500 cursor-pointer hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md text-xs sm:text-sm"
                                    >
                                        <i
                                            class="fas fa-copy text-xs sm:text-sm"
                                        ></i>
                                        <span>{{ __("Copy Order ID") }}</span>
                                    </button>

                                    <button
                                        v-if="order.transaction.code"
                                        @click="
                                            copyToClipboard(
                                                order.transaction.code,
                                                'Transaction Code'
                                            )
                                        "
                                        class="px-3 sm:px-4 py-2 bg-purple-500 cursor-pointer hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md text-xs sm:text-sm"
                                    >
                                        <i
                                            class="fas fa-copy text-xs sm:text-sm"
                                        ></i>
                                        <span>{{
                                            __("Copy Transaction Code")
                                        }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-6 sm:mt-8">
                        <v-paginate
                            :total-pages="pages.total_pages"
                            v-model="search.page"
                            @change="getCheckouts"
                        />
                    </div>
                </div>

                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-12 sm:py-20"
                >
                    <div class="text-center">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 bg-linear-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg"
                        >
                            <i
                                class="fas fa-spinner fa-spin text-blue-600 dark:text-blue-500 text-lg sm:text-2xl"
                            ></i>
                        </div>
                        <h3
                            class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Loading Orders") }}
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm"
                        >
                            {{ __("Please wait while we fetch your orders") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";

export default {
    components: {
        VAdminLayout,
        VPaginate,
    },

    data() {
        return {
            orders: [],
            loading: false,
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            expandedOrders: {},
        };
    },

    computed: {
        completedOrdersCount() {
            return this.orders.filter(
                (order) =>
                    order.transaction.status === "successful" ||
                    order.transaction.status === "completed"
            ).length;
        },

        pendingOrdersCount() {
            return this.orders.filter(
                (order) =>
                    order.transaction.status === "pending" ||
                    order.transaction.status === "processing"
            ).length;
        },

        failedOrdersCount() {
            return this.orders.filter(
                (order) =>
                    order.transaction.status === "failed" ||
                    order.transaction.status === "cancelled"
            ).length;
        },
    },

    created() {
        this.getCheckouts();
    },

    methods: {
        async getCheckouts() {
            this.loading = true;

            try {
                const res = await this.$server.get(
                    this.$page.props.routes.orders,
                    { params: this.search }
                );
                if (res.status === 200) {
                    const values = res.data;

                    // Manejar la estructura de datos anidada
                    if (values.data && typeof values.data === "object") {
                        // Si los datos vienen como objeto con claves numéricas
                        this.orders = Object.values(values.data);
                    } else {
                        this.orders = values.data || [];
                    }

                    this.pages = values.meta?.pagination || { total_pages: 0 };
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        getImageUrl(imagePath) {
            // Si la URL ya es completa, devolverla tal cual
            if (imagePath.startsWith("http")) {
                return imagePath;
            }
            // Si es una ruta relativa, construir la URL completa
            return `${window.location.origin}${imagePath}`;
        },

        calculateItemTotal(item) {
            // Calcular el total del item (precio * cantidad)
            const price = parseFloat(item.price) || 0;
            const quantity = item.quantity || 0;
            const total = price * quantity;

            // Formatear el total similar al formato_price
            return total.toLocaleString("es-PE", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },

        orderNumberIcon(code) {
            return code.slice(-1).toUpperCase();
        },

        toggleOrder(orderId) {
            this.expandedOrders = {
                ...this.expandedOrders,
                [orderId]: !this.expandedOrders[orderId],
            };
        },

        getStatusBadgeClass(status) {
            const baseClasses =
                "px-3 py-1 rounded-full text-xs font-semibold capitalize";
            const statusClasses = {
                successful:
                    "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700",
                completed:
                    "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700",
                pending:
                    "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700",
                processing:
                    "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700",
                failed: "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700",
                cancelled:
                    "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700",
            };
            return `${baseClasses} ${
                statusClasses[status] ||
                "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600"
            }`;
        },

        getStatusTextClass(status) {
            const baseClasses = "font-semibold capitalize";
            const statusClasses = {
                successful: "text-green-600 dark:text-green-400",
                completed: "text-green-600 dark:text-green-400",
                pending: "text-yellow-600 dark:text-yellow-400",
                processing: "text-yellow-600 dark:text-yellow-400",
                failed: "text-red-600 dark:text-red-400",
                cancelled: "text-red-600 dark:text-red-400",
            };
            return `${baseClasses} ${
                statusClasses[status] || "text-gray-600 dark:text-gray-400"
            }`;
        },

        copyOrderId(orderCode) {
            navigator.clipboard.writeText(orderCode);
            this.$notify.success(__("Order ID copied to clipboard"));
        },

        copyToClipboard(text, label = "Text") {
            navigator.clipboard.writeText(text);
            this.$notify.success(`${label} ${__("copied to clipboard")}`);
        },
    },
};
</script>
