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
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <v-header />

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Order Details Card -->
            <div
                v-if="data.id"
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm"
            >
                <!-- Header -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg"
                        >
                            <i
                                class="fas fa-receipt text-red-600 dark:text-red-400"
                            ></i>
                        </div>
                        <div>
                            <h2
                                class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Order Details") }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                #{{ data.code }}
                            </p>
                        </div>
                    </div>
                    <a
                        :href="data.web?.index"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center justify-center w-full sm:w-auto"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __("Back to Orders") }}
                    </a>
                </div>

                <!-- Order Summary -->
                <div class="p-4 sm:p-6">
                    <!-- Refund Expiration Info - Prominent Display -->
                    <div v-if="!data.transaction?.refund?.id" class="mb-6">
                        <div
                            class="rounded-xl p-4 border-2"
                            :class="
                                getRefundExpirationClass(
                                    data.transaction?.refund_expired
                                )
                            "
                        >
                            <div
                                class="flex flex-col sm:flex-row sm:items-center gap-4"
                            >
                                <div class="flex items-center space-x-3 flex-1">
                                    <div
                                        class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full"
                                        :class="
                                            getRefundExpirationIconClass(
                                                data.transaction?.refund_expired
                                            )
                                        "
                                    >
                                        <i
                                            :class="
                                                getRefundExpirationIcon(
                                                    data.transaction
                                                        ?.refund_expired
                                                )
                                            "
                                        ></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3
                                            class="font-bold text-lg mb-1"
                                            :class="
                                                getRefundExpirationTextClass(
                                                    data.transaction
                                                        ?.refund_expired
                                                )
                                            "
                                        >
                                            {{
                                                getRefundExpirationTitle(
                                                    data.transaction
                                                        ?.refund_expired
                                                )
                                            }}
                                        </h3>
                                        <p
                                            class="text-sm"
                                            :class="
                                                getRefundExpirationDescClass(
                                                    data.transaction
                                                        ?.refund_expired
                                                )
                                            "
                                        >
                                            {{
                                                getRefundExpirationDescription(
                                                    data.transaction
                                                )
                                            }}
                                        </p>
                                        <div
                                            v-if="
                                                !data.transaction
                                                    ?.refund_expired
                                            "
                                            class="mt-3 space-y-2"
                                        >
                                            <!-- Countdown Timer -->
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <div
                                                    class="flex items-center space-x-1"
                                                >
                                                    <i
                                                        class="fas fa-clock text-sm"
                                                        :class="
                                                            getRefundExpirationIconColor(
                                                                data.transaction
                                                                    ?.refund_expired
                                                            )
                                                        "
                                                    ></i>
                                                    <span
                                                        class="text-sm font-semibold"
                                                        :class="
                                                            getRefundExpirationTextClass(
                                                                data.transaction
                                                                    ?.refund_expired
                                                            )
                                                        "
                                                    >
                                                        {{
                                                            formatTimeRemaining(
                                                                data.transaction
                                                                    ?.refund_expiration_date
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="text-xs text-gray-600 dark:text-gray-400"
                                                >
                                                    {{ __("Expiration") }}:
                                                    {{
                                                        formatDate(
                                                            data.transaction
                                                                ?.refund_expiration_date
                                                        )
                                                    }}
                                                </div>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div
                                                class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2"
                                            >
                                                <div
                                                    class="h-2 rounded-full transition-all duration-500"
                                                    :class="
                                                        getRefundExpirationProgressClass(
                                                            data.transaction
                                                                ?.refund_expired
                                                        )
                                                    "
                                                    :style="{
                                                        width:
                                                            getRefundExpirationProgress(
                                                                data.transaction
                                                                    ?.refund_expiration_date
                                                            ) + '%',
                                                    }"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                            </div>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6"
                    >
                        <!-- Transaction Details -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3
                                class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center"
                            >
                                <i
                                    class="fas fa-credit-card mr-2 text-red-500"
                                ></i>
                                {{ __("Transaction Information") }}
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Status") }}:</span
                                    >
                                    <span
                                        class="font-medium"
                                        :class="{
                                            'text-green-600 dark:text-green-400':
                                                data.transaction?.status ===
                                                'successful',
                                            'text-yellow-600 dark:text-yellow-400':
                                                data.transaction?.status ===
                                                'pending',
                                            'text-red-600 dark:text-red-400':
                                                data.transaction?.status ===
                                                'expired',
                                        }"
                                    >
                                        {{
                                            formatStatus(
                                                data.transaction?.status
                                            )
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Payment Method") }}:</span
                                    >
                                    <span
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        {{
                                            formatPaymentMethod(
                                                data.transaction?.payment_method
                                            )
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Transaction Code") }}:</span
                                    >
                                    <span
                                        class="font-mono text-gray-900 dark:text-white text-sm"
                                    >
                                        {{ data.transaction_code }}
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Order Date") }}:</span
                                    >
                                    <span class="text-gray-900 dark:text-white">
                                        {{ data.transaction?.created }}
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Total Amount") }}:</span
                                    >
                                    <span
                                        class="text-lg font-bold text-red-600 dark:text-red-400"
                                    >
                                        {{ data.transaction?.total }}
                                        {{ data.transaction?.currency }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3
                                class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center"
                            >
                                <i class="fas fa-truck mr-2 text-red-500"></i>
                                {{ __("Delivery Information") }}
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("City") }}:</span
                                    >
                                    <span
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        {{
                                            data.delivery_address?.city ||
                                            __("N/A")
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Address") }}:</span
                                    >
                                    <span
                                        class="font-medium text-gray-900 dark:text-white sm:text-right max-w-[200px]"
                                    >
                                        {{
                                            data.delivery_address?.address ||
                                            __("N/A")
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Phone") }}:</span
                                    >
                                    <span
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        {{
                                            data.delivery_address?.phone ||
                                            __("N/A")
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0"
                                >
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("WhatsApp") }}:</span
                                    >
                                    <a
                                        v-if="data.delivery_address?.whatsapp"
                                        :href="data.delivery_address.whatsapp"
                                        target="_blank"
                                        class="text-green-600 dark:text-green-400 hover:underline flex items-center justify-end sm:justify-start"
                                    >
                                        <i class="fab fa-whatsapp mr-1"></i>
                                        {{ __("Contact") }}
                                    </a>
                                    <span v-else class="text-gray-500">{{
                                        __("N/A")
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Details -->
                    <div class="mb-6">
                        <h3
                            class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
                        >
                            <i class="fas fa-boxes mr-2 text-red-500"></i>
                            {{ __("Order Items") }} ({{
                                data.orders?.length || 0
                            }})
                        </h3>
                        <div class="space-y-4">
                            <div
                                v-for="item in data.orders"
                                :key="item.id"
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600"
                            >
                                <div
                                    class="flex flex-col sm:flex-row sm:items-start gap-4"
                                >
                                    <!-- Product Image -->
                                    <div class="shrink-0">
                                        <img
                                            v-if="item.images?.[0]?.url"
                                            :src="item.images[0].url"
                                            :alt="item.meta?.name"
                                            class="w-20 h-20 rounded-lg border border-gray-200 dark:border-gray-600 object-cover mx-auto sm:mx-0"
                                        />
                                        <div
                                            v-else
                                            class="w-20 h-20 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 flex items-center justify-center mx-auto sm:mx-0"
                                        >
                                            <i
                                                class="fas fa-image text-gray-400"
                                            ></i>
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="font-medium text-gray-900 dark:text-white mb-2 text-center sm:text-left"
                                        >
                                            {{ item.meta?.name }}
                                        </h4>

                                        <!-- Product Details Grid -->
                                        <div
                                            class="grid grid-cols-2 gap-3 text-sm mb-3"
                                        >
                                            <div>
                                                <span
                                                    class="text-gray-600 dark:text-gray-400 block text-xs"
                                                    >{{ __("Category") }}:</span
                                                >
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        item.meta?.category
                                                            ?.name || __("N/A")
                                                    }}
                                                </p>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-gray-600 dark:text-gray-400 block text-xs"
                                                    >{{ __("Quantity") }}:</span
                                                >
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ item.quantity }}
                                                </p>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-gray-600 dark:text-gray-400 block text-xs"
                                                    >{{ __("Variant") }}:</span
                                                >
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        item.meta?.variant
                                                            ?.name || __("N/A")
                                                    }}
                                                </p>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-gray-600 dark:text-gray-400 block text-xs"
                                                    >{{
                                                        __("Unit Price")
                                                    }}:</span
                                                >
                                                <p
                                                    class="font-medium text-red-600 dark:text-red-400"
                                                >
                                                    {{ item.currency }}
                                                    {{ item.format_price }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Additional Information -->
                                        <div
                                            class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-xs mb-3"
                                        >
                                            <span
                                                class="px-2 py-1 rounded"
                                                :class="{
                                                    'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400':
                                                        item.stock > 10,
                                                    'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400':
                                                        item.stock > 0 &&
                                                        item.stock <= 10,
                                                    'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400':
                                                        item.stock <= 0,
                                                }"
                                            >
                                                {{ item.stock }}
                                                {{ __("in stock") }}
                                            </span>
                                            <span
                                                v-if="item.meta?.featured"
                                                class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded"
                                            >
                                                <i class="fas fa-star mr-1"></i>
                                                {{ __("Featured") }}
                                            </span>
                                            <span
                                                v-if="item.meta?.published"
                                                class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded"
                                            >
                                                {{ __("Published") }}
                                            </span>
                                        </div>

                                        <!-- Product Links -->
                                        <div
                                            class="flex items-center justify-center sm:justify-start gap-2"
                                        >
                                            <a
                                                v-if="item.web?.product"
                                                :href="item.web.product"
                                                class="px-3 py-1.5 bg-green-600 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-100 dark:text-gray-300 text-xs font-medium rounded hover:bg-green-700 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center"
                                            >
                                                <i
                                                    class="fas fa-shopping-cart mr-1"
                                                ></i>
                                                {{ __("Buy Again") }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex flex-wrap gap-2 sm:gap-3 pt-6 border-t border-gray-200 dark:border-gray-600"
                    >
                        <button
                            v-if="
                                data.transaction?.status != 'expired' &&
                                data.transaction?.payment_url
                            "
                            @click="openReceipt(data.transaction.payment_url)"
                            class="px-3 sm:px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center justify-center w-full sm:w-auto"
                        >
                            <i class="fas fa-receipt mr-2"></i>
                            {{ __("View Receipt") }}
                        </button>
                        <button
                            @click="copyOrderId(data.code)"
                            class="px-3 sm:px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center justify-center w-full sm:w-auto"
                        >
                            <i class="fas fa-copy mr-2"></i>
                            {{ __("Copy Order ID") }}
                        </button>
                        <button
                            v-if="data.transaction_code"
                            @click="copyTransactionCode(data.transaction_code)"
                            class="px-3 sm:px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center justify-center w-full sm:w-auto"
                        >
                            <i class="fas fa-copy mr-2"></i>
                            {{ __("Copy Transaction") }}
                        </button>
                        <v-refund
                            :item="data"
                            v-if="!data?.transaction?.refund?.id"
                            @created="getCheckout"
                        />

                        <v-cancel
                            v-if="isCancel(data)"
                            :item="data"
                            @canceled="getCheckout"
                        />
                    </div>

                    <!-- Refund Status Section -->
                    <div v-if="data.transaction?.refund?.id" class="mt-8">
                        <!-- Refund Status Banner -->
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 rounded-lg p-4 mb-6 border border-blue-200 dark:border-blue-700"
                        >
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="p-2 bg-blue-100 dark:bg-blue-800/40 rounded-lg"
                                    >
                                        <i
                                            class="fas fa-undo-alt text-blue-600 dark:text-blue-400 text-lg"
                                        ></i>
                                    </div>
                                    <div>
                                        <h3
                                            class="font-bold text-gray-900 dark:text-white text-lg"
                                        >
                                            {{ __("Refund Request") }}
                                        </h3>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            {{ __("Current Status") }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-center sm:text-right mb-1">
                                        <span
                                            class="text-lg font-bold text-blue-600 dark:text-blue-400"
                                        >
                                            {{
                                                formatMoney(
                                                    data.transaction.refund
                                                        .amount
                                                )
                                            }}
                                            {{
                                                data.transaction.refund.currency
                                            }}
                                        </span>
                                    </div>
                                    <span
                                        class="px-4 py-2 rounded-lg font-bold shadow-sm inline-block animate-pulse"
                                        :class="
                                            getCurrentStatusClass(
                                                data.transaction.refund.status
                                            )
                                        "
                                    >
                                        {{
                                            formatRefundStatus(
                                                data.transaction.refund.status
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Description -->
                        <div class="mb-6">
                            <div
                                class="bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4"
                            >
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
                                    <h4
                                        class="font-semibold text-gray-900 dark:text-white flex items-center"
                                    >
                                        <i
                                            class="fas fa-file-alt mr-2 text-blue-500"
                                        ></i>
                                        {{ __("Description") }}
                                    </h4>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ data.transaction.refund.reason }}
                                    </span>
                                </div>

                                <div
                                    v-if="data.transaction.refund.description"
                                    class="bg-gray-50 dark:bg-gray-600 rounded p-4 max-h-48 overflow-y-auto"
                                >
                                    <pre
                                        class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-wrap font-sans"
                                        >{{
                                            data.transaction.refund.description
                                        }}</pre
                                    >
                                </div>
                                <div v-else class="text-center py-6">
                                    <i
                                        class="fas fa-comment-slash text-gray-400 text-2xl mb-2"
                                    ></i>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{
                                            __(
                                                "No detailed description provided"
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- All Status Timeline -->
                        <div class="mb-6">
                            <h4
                                class="font-semibold text-gray-900 dark:text-white mb-4"
                            >
                                {{ __("Refund Status Progress") }}
                            </h4>
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600"
                            >
                                <!-- Timeline for mobile -->
                                <div class="sm:hidden">
                                    <div class="relative">
                                        <!-- Timeline line -->
                                        <div
                                            class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-300 dark:bg-gray-600"
                                        ></div>

                                        <div class="space-y-6">
                                            <div
                                                v-for="statusInfo in getAllStatuses()"
                                                :key="statusInfo.status"
                                                class="relative pl-10"
                                            >
                                                <!-- Status circle -->
                                                <div
                                                    class="absolute left-0 top-0"
                                                >
                                                    <div
                                                        class="w-8 h-8 rounded-full flex items-center justify-center border-2"
                                                        :class="
                                                            getStatusCircleClass(
                                                                statusInfo
                                                            )
                                                        "
                                                    >
                                                        <i
                                                            :class="
                                                                getStatusIcon(
                                                                    statusInfo.status
                                                                ) + ' text-xs'
                                                            "
                                                        ></i>
                                                    </div>
                                                </div>

                                                <!-- Status info -->
                                                <div class="min-h-8">
                                                    <div
                                                        class="flex items-center justify-between mb-1"
                                                    >
                                                        <span
                                                            class="font-medium"
                                                            :class="
                                                                getStatusTextClass(
                                                                    statusInfo
                                                                )
                                                            "
                                                        >
                                                            {{
                                                                formatRefundStatus(
                                                                    statusInfo.status
                                                                )
                                                            }}
                                                        </span>
                                                        <span
                                                            v-if="
                                                                statusInfo.current
                                                            "
                                                            class="text-xs animate-pulse"
                                                        >
                                                            <i
                                                                class="fas fa-circle text-blue-500"
                                                            ></i>
                                                        </span>
                                                    </div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400"
                                                    >
                                                        {{
                                                            getStatusDescription(
                                                                statusInfo.status
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline for desktop -->
                                <div class="hidden sm:block">
                                    <div class="relative">
                                        <!-- Timeline line -->
                                        <div
                                            class="absolute left-0 right-0 top-1/2 h-0.5 bg-gray-300 dark:bg-gray-600"
                                        ></div>

                                        <div
                                            class="flex justify-between items-center relative"
                                        >
                                            <div
                                                v-for="statusInfo in getAllStatuses()"
                                                :key="statusInfo.status"
                                                class="flex flex-col items-center"
                                            >
                                                <!-- Status circle -->
                                                <div
                                                    class="w-10 h-10 rounded-full flex items-center justify-center border-4 mb-2"
                                                    :class="
                                                        getStatusCircleClass(
                                                            statusInfo
                                                        )
                                                    "
                                                >
                                                    <i
                                                        :class="
                                                            getStatusIcon(
                                                                statusInfo.status
                                                            )
                                                        "
                                                    ></i>
                                                </div>

                                                <!-- Status label -->
                                                <span
                                                    class="text-xs font-medium text-center px-2"
                                                    :class="
                                                        getStatusTextClass(
                                                            statusInfo
                                                        )
                                                    "
                                                >
                                                    {{
                                                        formatRefundStatus(
                                                            statusInfo.status
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supporting Files -->
                        <div
                            v-if="data?.transaction?.refund?.files?.length"
                            class="mb-6"
                        >
                            <h4
                                class="font-semibold text-gray-900 dark:text-white mb-4"
                            >
                                {{ __("Supporting Documents") }}
                            </h4>
                            <div
                                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"
                            >
                                <div
                                    v-for="file in data.transaction.refund
                                        .files"
                                    :key="file.id"
                                    @click="openFile(file)"
                                    class="group cursor-pointer bg-gray-50 dark:bg-gray-700 rounded-lg p-2 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200"
                                >
                                    <!-- Image thumbnail -->
                                    <div
                                        v-if="
                                            file.mime_type.startsWith('image/')
                                        "
                                        class="w-full aspect-square bg-gray-100 dark:bg-gray-600 rounded mb-2 overflow-hidden"
                                    >
                                        <img
                                            :src="file.links.show"
                                            :alt="file.original_name"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                        />
                                    </div>
                                    <!-- Document icon -->
                                    <div
                                        v-else
                                        class="w-full aspect-square bg-gray-100 dark:bg-gray-600 rounded mb-2 flex items-center justify-center"
                                    >
                                        <i
                                            :class="
                                                getFileIcon(file.mime_type) +
                                                ' text-blue-500 text-xl'
                                            "
                                        ></i>
                                    </div>
                                    <p
                                        class="text-xs font-medium text-gray-900 dark:text-white truncate text-center"
                                    >
                                        {{ file.original_name }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Actions -->
                        <div class="flex flex-wrap gap-2">
                            <button
                                @click="contactSupport"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center justify-center w-full sm:w-auto"
                            >
                                <i class="fas fa-headset mr-2"></i>
                                {{ __("Contact Support") }}
                            </button>

                            <button
                                v-if="
                                    canSubmitAppeal(
                                        data.transaction.refund.status
                                    )
                                "
                                @click="openAppealModal"
                                class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center justify-center w-full sm:w-auto"
                            >
                                <i class="fas fa-gavel mr-2"></i>
                                {{ __("Submit Appeal") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-else class="text-center py-12">
                <div
                    class="w-16 h-16 border-4 border-red-200 dark:border-red-800 border-t-red-500 dark:border-t-red-400 rounded-full animate-spin mb-4 mx-auto"
                ></div>
                <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">
                    {{ __("Loading order details...") }}
                </p>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import VHeader from "@Ecommerce/components/VHeader.vue";
import VRefund from "./Refund.vue";
import VCancel from "./Cancel.vue";

const page = usePage();
const data = ref({});

const formatMoney = (cents) => {
    if (!cents) return "0.00";
    return (cents / 100).toFixed(2);
};

const formatStatus = (status) => {
    const statusMap = {
        successful: __("Completed"),
        expired: __("Expired"),
        pending: __("Processing"),
        refunded: __("Refunded"),
        cancelled: __("Cancelled"),
    };
    return statusMap[status] || status;
};

const isCancel = (item) => {
    return (
        item?.transaction?.refund?.id &&
        !["canceled", "completed", "refunding", "rejected"].includes(
            item?.transaction?.refund?.status
        )
    );
};

const formatPaymentMethod = (method) => {
    const methodMap = {
        offline: __("Offline Payment"),
        card: __("Credit Card"),
        paypal: __("PayPal"),
        bank_transfer: __("Bank Transfer"),
        stripe: __("Stripe"),
    };
    return methodMap[method] || method;
};

const formatRefundStatus = (status) => {
    const statusMap = {
        pending: __("Pending"),
        processing: __("Processing"),
        under_review: __("Under Review"),
        approved: __("Approved"),
        waiting_for_return: __("Waiting for Return"),
        refunding: __("Refunding"),
        completed: __("Completed"),
        rejected: __("Rejected"),
        canceled: __("Canceled"),
    };
    return statusMap[status] || status;
};

const getCurrentStatusClass = (status) => {
    const classMap = {
        pending: "bg-yellow-500 text-white",
        processing: "bg-blue-500 text-white",
        under_review: "bg-blue-500 text-white",
        approved: "bg-green-500 text-white",
        waiting_for_return: "bg-orange-500 text-white",
        refunding: "bg-purple-500 text-white",
        completed: "bg-green-500 text-white",
        rejected: "bg-red-500 text-white",
        canceled: "bg-gray-500 text-white",
    };
    return classMap[status] || "bg-gray-500 text-white";
};

const getAllStatuses = () => {
    const currentStatus = data.value.transaction?.refund?.status;
    const statusHistory = data.value.transaction?.refund?.status_history || {};

    const allStatuses = [
        "pending",
        "processing",
        "under_review",
        "approved",
        "waiting_for_return",
        "refunding",
        "completed",
        "rejected",
        "canceled",
    ];

    return allStatuses.map((status) => {
        const isCompleted =
            getStatusIndex(status) <= getStatusIndex(currentStatus);
        const isCurrent = status === currentStatus;

        return {
            status,
            completed: isCompleted && !isCurrent,
            current: isCurrent,
            date: statusHistory[status] || null,
        };
    });
};

const getStatusIndex = (status) => {
    const order = [
        "pending",
        "processing",
        "under_review",
        "approved",
        "waiting_for_return",
        "refunding",
        "completed",
        "rejected",
        "canceled",
    ];
    return order.indexOf(status);
};

const getStatusCircleClass = (statusInfo) => {
    if (statusInfo.completed) {
        return "bg-green-500 border-green-500 text-white";
    } else if (statusInfo.current) {
        return "bg-blue-500 border-blue-500 text-white animate-pulse";
    } else if (["rejected", "canceled"].includes(statusInfo.status)) {
        return "bg-red-500 border-red-500 text-white";
    } else {
        return "bg-gray-300 dark:bg-gray-600 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400";
    }
};

const getStatusTextClass = (statusInfo) => {
    if (statusInfo.completed) {
        return "text-green-600 dark:text-green-400";
    } else if (statusInfo.current) {
        return "text-blue-600 dark:text-blue-400 font-bold";
    } else if (["rejected", "canceled"].includes(statusInfo.status)) {
        return "text-red-600 dark:text-red-400";
    } else {
        return "text-gray-600 dark:text-gray-400";
    }
};

const getStatusIcon = (status) => {
    const iconMap = {
        pending: "fas fa-clock",
        processing: "fas fa-cog",
        under_review: "fas fa-search",
        approved: "fas fa-check-circle",
        waiting_for_return: "fas fa-truck",
        refunding: "fas fa-money-bill-wave",
        completed: "fas fa-flag-checkered",
        rejected: "fas fa-times-circle",
        canceled: "fas fa-ban",
    };
    return iconMap[status] || "fas fa-circle";
};

const getStatusDescription = (status) => {
    const descriptionMap = {
        pending: __("Refund request has been submitted"),
        processing: __("Request is being processed"),
        under_review: __("Under review by our team"),
        approved: __("Refund has been approved"),
        waiting_for_return: __("Waiting for item return"),
        refunding: __("Processing payment refund"),
        completed: __("Refund has been completed"),
        rejected: __("Refund request was rejected"),
        canceled: __("Refund request was canceled"),
    };
    return descriptionMap[status] || __("Status update");
};

const getFileIcon = (mimeType) => {
    if (mimeType.startsWith("image/")) return "fas fa-image";
    if (mimeType.includes("pdf")) return "fas fa-file-pdf";
    if (mimeType.includes("word") || mimeType.includes("document"))
        return "fas fa-file-word";
    if (mimeType.includes("excel") || mimeType.includes("spreadsheet"))
        return "fas fa-file-excel";
    if (mimeType.includes("zip") || mimeType.includes("compressed"))
        return "fas fa-file-archive";
    return "fas fa-file";
};

// New methods for refund expiration info
const getRefundExpirationClass = (isExpired) => {
    return isExpired
        ? "bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800/40 dark:to-gray-900/30 border-gray-300 dark:border-gray-600"
        : "bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 border-emerald-400 dark:border-emerald-700";
};

const getRefundExpirationIconClass = (isExpired) => {
    return isExpired
        ? "bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700"
        : "bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-800 dark:to-green-800";
};

const getRefundExpirationIcon = (isExpired) => {
    return isExpired
        ? "fas fa-clock text-gray-600 dark:text-gray-400"
        : "fas fa-clock-rotate-left text-emerald-600 dark:text-emerald-400";
};

const getRefundExpirationIconColor = (isExpired) => {
    return isExpired
        ? "text-gray-600 dark:text-gray-400"
        : "text-emerald-600 dark:text-emerald-400";
};

const getRefundExpirationTextClass = (isExpired) => {
    return isExpired
        ? "text-gray-800 dark:text-gray-300"
        : "text-emerald-800 dark:text-emerald-300";
};

const getRefundExpirationDescClass = (isExpired) => {
    return isExpired
        ? "text-gray-600 dark:text-gray-400"
        : "text-emerald-700 dark:text-emerald-400";
};

const getRefundExpirationTitle = (isExpired) => {
    return isExpired ? __("Refund Period Expired") : __("Refund Available");
};

const getRefundExpirationDescription = (transaction) => {
    if (transaction?.refund_expired) {
        return __(
            "The refund period for this order has ended. You can no longer request a refund."
        );
    } else {
        return __(
            "You have a limited time to request a refund for this order."
        );
    }
};

const getRefundRequestButtonClass = (isExpired) => {
    return isExpired
        ? "bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 cursor-not-allowed opacity-60"
        : "bg-emerald-500 hover:bg-emerald-600 text-white hover:shadow-md";
};

const getRefundExpirationProgressClass = (isExpired) => {
    return isExpired
        ? "bg-gray-400 dark:bg-gray-600"
        : "bg-gradient-to-r from-emerald-400 to-green-400 dark:from-emerald-500 dark:to-green-500";
};

const getRefundExpirationProgress = (expirationDate) => {
    if (!expirationDate) return 100;

    const now = new Date();
    const expiration = new Date(expirationDate);
    const created = new Date(data.value.transaction?.created);

    const totalDuration = expiration - created;
    const elapsed = now - created;

    if (elapsed <= 0) return 0;
    if (elapsed >= totalDuration) return 100;

    return Math.min(100, Math.max(0, (elapsed / totalDuration) * 100));
};

const formatTimeRemaining = (expirationDate) => {
    if (!expirationDate) return "";

    const now = new Date();
    const expiration = new Date(expirationDate);
    const diffMs = expiration - now;

    if (diffMs <= 0) return __("Expired");

    const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24));

    if (diffDays <= 0) return __("Expires today");
    if (diffDays === 1) return __("1 day remaining");
    if (diffDays <= 7) return __(`${diffDays} days remaining`);
    if (diffDays <= 30) return __(`${Math.ceil(diffDays / 7)} weeks remaining`);

    const diffMonths = Math.ceil(diffDays / 30);
    return __(`${diffMonths} months remaining`);
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES", {
        day: "2-digit",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Refund request action
const requestRefund = (order) => {
    if (order?.transaction?.refund_expired) {
        if (typeof $notify !== "undefined") {
            $notify.warning(__("Refund period has expired for this order"));
        }
        return;
    }

    if (typeof $notify !== "undefined") {
        $notify.info(__("Refund request feature will be implemented soon"));
    }
};

const openFile = (file) => {
    window.open(file.links.show, "_blank");
};

const canSubmitAppeal = (status) => {
    return ["rejected"].includes(status);
};

const openAppealModal = () => {
    alert(__("Appeal feature coming soon!"));
};

const contactSupport = () => {
    window.open(
        "mailto:" +
            page.props.org_support_email +
            "?subject=" +
            __("Refund Support") +
            " - " +
            data.value.transaction_code,
        "_blank"
    );
};

const openReceipt = (url) => {
    if (url) {
        window.open(url, "_blank");
    }
};

const getCheckout = () => {
    const form = useForm();
    form.get(page.props.routes.show, {
        preserveState: true,
        onSuccess: (page) => {
            data.value = page.props.data;
        },
    });
};

const copyOrderId = (orderCode) => {
    navigator.clipboard.writeText(orderCode);
    if (typeof $notify !== "undefined") {
        $notify.success(__("Order ID copied to clipboard"));
    }
};

const copyTransactionCode = (transactionCode) => {
    navigator.clipboard.writeText(transactionCode);
    if (typeof $notify !== "undefined") {
        $notify.success(__("Transaction code copied to clipboard"));
    }
};

onMounted(() => {
    data.value = page.props.data;
});
</script>

<style scoped>
/* Custom scrollbar for description */
pre {
    font-family: inherit;
    line-height: 1.5;
}

@media (max-width: 640px) {
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
}
</style>
