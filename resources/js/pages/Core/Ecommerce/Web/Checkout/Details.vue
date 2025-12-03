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
                    class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-600"
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
                                class="text-xl font-bold text-gray-900 dark:text-white"
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
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __("Back to Orders") }}
                    </a>
                </div>

                <!-- Order Summary -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
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
                                <div class="flex justify-between items-center">
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
                                <div class="flex justify-between items-center">
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
                                <div class="flex justify-between items-center">
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
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Order Date") }}:</span
                                    >
                                    <span class="text-gray-900 dark:text-white">
                                        {{
                                            formatFullDate(
                                                data.transaction?.created
                                            )
                                        }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
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
                                <div class="flex justify-between items-center">
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
                                <div class="flex justify-between items-start">
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("Address") }}:</span
                                    >
                                    <span
                                        class="font-medium text-gray-900 dark:text-white text-right max-w-[200px]"
                                    >
                                        {{
                                            data.delivery_address?.address ||
                                            __("N/A")
                                        }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
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
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-gray-600 dark:text-gray-400"
                                        >{{ __("WhatsApp") }}:</span
                                    >
                                    <a
                                        v-if="data.delivery_address?.whatsapp"
                                        :href="data.delivery_address.whatsapp"
                                        target="_blank"
                                        class="text-green-600 dark:text-green-400 hover:underline flex items-center"
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
                                <div class="flex items-start space-x-4">
                                    <!-- Product Image -->
                                    <div class="shrink-0">
                                        <img
                                            v-if="item.images?.[0]?.url"
                                            :src="item.images[0].url"
                                            :alt="item.meta?.name"
                                            class="w-20 h-20 rounded-lg border border-gray-200 dark:border-gray-600 object-cover"
                                        />
                                        <div
                                            v-else
                                            class="w-20 h-20 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-image text-gray-400"
                                            ></i>
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="font-medium text-gray-900 dark:text-white mb-2"
                                        >
                                            {{ item.meta?.name }}
                                        </h4>

                                        <!-- Product Details Grid -->
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm mb-3"
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
                                            class="flex flex-wrap items-center gap-3 text-xs"
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

                                        <!-- Product Description -->
                                        <div
                                            v-if="item.meta?.short_description"
                                            class="mt-3 text-xs text-gray-600 dark:text-gray-400"
                                        >
                                            <div
                                                v-html="
                                                    item.meta.short_description
                                                "
                                            ></div>
                                        </div>

                                        <!-- Product Links -->
                                        <div
                                            class="flex items-center gap-2 mt-3"
                                        >
                                            <a
                                                v-if="item.web?.product"
                                                :href="item.web.product"
                                                class="px-3 py-1 bg-green-600 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-100 dark:text-gray-300 text-xs font-medium rounded hover:bg-green-700 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center"
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
                        class="flex flex-wrap gap-3 pt-6 border-t border-gray-200 dark:border-gray-600"
                    >
                        <button
                            v-if="data.transaction?.status != 'expired'"
                            @click="openReceipt(data.transaction.payment_url)"
                            class="px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center"
                        >
                            <i class="fas fa-receipt mr-2"></i>
                            {{ __("View Receipt") }}
                        </button>
                        <button
                            @click="copyOrderId(data.code)"
                            class="px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center"
                        >
                            <i class="fas fa-copy mr-2"></i>
                            {{ __("Copy Order ID") }}
                        </button>
                        <button
                            v-if="data.transaction_code"
                            @click="copyTransactionCode(data.transaction_code)"
                            class="px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center"
                        >
                            <i class="fas fa-copy mr-2"></i>
                            {{ __("Copy Transaction Code") }}
                        </button>
                        <v-refund
                            :item="data"
                            v-if="!data?.transaction?.refund?.id"
                            @created="getCheckout"
                        />
                    </div>

                    <!-- Refund Status Section -->
                    <div v-if="data.transaction?.refund?.id" class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="font-semibold text-gray-900 dark:text-white flex items-center"
                            >
                                <i
                                    class="fas fa-undo-alt mr-2 text-red-500"
                                ></i>
                                {{ __("Refund Status") }}
                            </h3>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium"
                                :class="
                                    getRefundStatusClass(
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

                        <!-- Refund Information Card -->
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm"
                            >
                                <div>
                                    <span
                                        class="text-blue-700 dark:text-blue-300 font-medium"
                                        >{{ __("Requested Amount") }}:</span
                                    >
                                    <p
                                        class="text-blue-800 dark:text-blue-200 text-lg font-bold"
                                    >
                                        {{
                                            formatMoney(
                                                data.transaction.refund.amount
                                            )
                                        }}
                                        {{ data.transaction.refund.currency }}
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="text-blue-700 dark:text-blue-300 font-medium"
                                        >{{ __("Reason") }}:</span
                                    >
                                    <p class="text-blue-800 dark:text-blue-200">
                                        {{ data.transaction.refund.reason }}
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="text-blue-700 dark:text-blue-300 font-medium"
                                        >{{ __("Description") }}:</span
                                    >
                                    <p class="text-blue-800 dark:text-blue-200">
                                        {{
                                            data.transaction.refund.description
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Timeline -->
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-4"
                        >
                            <h4
                                class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
                            >
                                <i
                                    class="fas fa-history mr-2 text-blue-500"
                                ></i>
                                {{ __("Refund Process Timeline") }}
                            </h4>
                            <div class="space-y-4">
                                <!-- Main Refund Timeline -->
                                <div class="flex items-start space-x-4">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-3 h-3 bg-blue-500 rounded-full"
                                        ></div>
                                        <div
                                            class="w-0.5 h-16 bg-blue-500 mt-1"
                                        ></div>
                                    </div>
                                    <div class="flex-1 pb-4">
                                        <div
                                            class="flex items-center justify-between mb-1"
                                        >
                                            <span
                                                class="font-medium text-gray-900 dark:text-white"
                                                >{{
                                                    __(
                                                        "Refund Request Submitted"
                                                    )
                                                }}</span
                                            >
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{
                                                    formatFullDate(
                                                        data.transaction
                                                            ?.created
                                                    )
                                                }}
                                            </span>
                                        </div>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400 mb-2"
                                        >
                                            {{
                                                __(
                                                    "Your refund request has been received and is now in the system."
                                                )
                                            }}
                                        </p>
                                        <div
                                            class="bg-white dark:bg-gray-600 rounded p-3 border border-gray-200 dark:border-gray-500"
                                        >
                                            <p
                                                class="text-sm text-gray-700 dark:text-gray-300"
                                            >
                                                <strong
                                                    >{{
                                                        __("Request Details")
                                                    }}:</strong
                                                >
                                                {{
                                                    data.transaction.refund
                                                        .reason
                                                }}
                                            </p>
                                            <p
                                                class="text-sm text-gray-700 dark:text-gray-300 mt-1"
                                            >
                                                <strong
                                                    >{{ __("Amount") }}:</strong
                                                >
                                                {{
                                                    formatMoney(
                                                        data.transaction.refund
                                                            .amount
                                                    )
                                                }}
                                                {{
                                                    data.transaction.refund
                                                        .currency
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Status Steps -->
                                <div
                                    v-for="step in getTimelineSteps(
                                        data.transaction.refund.status
                                    )"
                                    :key="step.status"
                                    class="flex items-start space-x-4"
                                >
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-3 h-3 rounded-full"
                                            :class="
                                                step.completed
                                                    ? 'bg-green-500'
                                                    : step.current
                                                    ? 'bg-blue-500 animate-pulse'
                                                    : 'bg-gray-300 dark:bg-gray-600'
                                            "
                                        ></div>
                                        <div
                                            v-if="!step.last"
                                            class="w-0.5 h-16 mt-1"
                                            :class="
                                                step.completed
                                                    ? 'bg-green-500'
                                                    : 'bg-gray-300 dark:bg-gray-600'
                                            "
                                        ></div>
                                    </div>
                                    <div class="flex-1 pb-4">
                                        <div
                                            class="flex items-center justify-between mb-1"
                                        >
                                            <span
                                                class="font-medium"
                                                :class="
                                                    step.completed
                                                        ? 'text-green-600 dark:text-green-400'
                                                        : step.current
                                                        ? 'text-blue-600 dark:text-blue-400'
                                                        : 'text-gray-900 dark:text-white'
                                                "
                                            >
                                                {{ step.title }}
                                            </span>
                                            <span
                                                v-if="step.completed"
                                                class="text-xs text-green-600 dark:text-green-400 flex items-center"
                                            >
                                                <i
                                                    class="fas fa-check mr-1"
                                                ></i>
                                                {{ __("Completed") }}
                                            </span>
                                            <span
                                                v-else-if="step.current"
                                                class="text-xs text-blue-600 dark:text-blue-400 flex items-center"
                                            >
                                                <i
                                                    class="fas fa-spinner fa-spin mr-1"
                                                ></i>
                                                {{ __("In Progress") }}
                                            </span>
                                            <span
                                                v-else
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ __("Pending") }}
                                            </span>
                                        </div>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400 mb-2"
                                        >
                                            {{ step.description }}
                                        </p>
                                        <div
                                            v-if="step.details"
                                            class="bg-white dark:bg-gray-600 rounded p-3 border border-gray-200 dark:border-gray-500"
                                        >
                                            <p
                                                class="text-sm text-gray-700 dark:text-gray-300"
                                            >
                                                {{ step.details }}
                                            </p>
                                            <p
                                                v-if="step.estimatedTime"
                                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                            >
                                                <i
                                                    class="fas fa-clock mr-1"
                                                ></i>
                                                {{ step.estimatedTime }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rejected or Canceled State -->
                                <div
                                    v-if="
                                        ['rejected', 'canceled'].includes(
                                            data.transaction.refund.status
                                        )
                                    "
                                    class="flex items-start space-x-4"
                                >
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-3 h-3 bg-red-500 rounded-full"
                                        ></div>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="flex items-center justify-between mb-1"
                                        >
                                            <span
                                                class="font-medium text-red-600 dark:text-red-400"
                                            >
                                                {{
                                                    data.transaction.refund
                                                        .status === "rejected"
                                                        ? __("Refund Rejected")
                                                        : __("Refund Canceled")
                                                }}
                                            </span>
                                            <span
                                                class="text-xs text-red-600 dark:text-red-400 flex items-center"
                                            >
                                                <i
                                                    class="fas fa-times mr-1"
                                                ></i>
                                                {{ __("Final Status") }}
                                            </span>
                                        </div>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400 mb-2"
                                        >
                                            {{
                                                data.transaction.refund
                                                    .status === "rejected"
                                                    ? __(
                                                          "Your refund request has been reviewed and unfortunately could not be approved."
                                                      )
                                                    : __(
                                                          "The refund process has been canceled."
                                                      )
                                            }}
                                        </p>
                                        <div
                                            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded p-3"
                                        >
                                            <p
                                                class="text-sm text-red-700 dark:text-red-300"
                                            >
                                                <strong
                                                    >{{
                                                        __("Next Steps")
                                                    }}:</strong
                                                >
                                                {{
                                                    data.transaction.refund
                                                        .status === "rejected"
                                                        ? __(
                                                              "You can submit an appeal if you believe this decision was made in error. Contact our support team for more details."
                                                          )
                                                        : __(
                                                              "If this was done in error, please contact our support team immediately."
                                                          )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supporting Evidence Section -->
                        <div
                            v-if="data?.transaction?.refund?.files?.length"
                            class="mb-6"
                        >
                            <h4
                                class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-blue-500"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828L18 9.828m-4.828-2.828L6 14.172a4 4 0 105.657 5.657L21 10.485"
                                    />
                                </svg>
                                {{ __("Supporting Evidence Documents") }}
                            </h4>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="file in data.transaction.refund
                                        .files"
                                    :key="file.id"
                                    class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden"
                                >
                                    <!-- Previsualización si es imagen -->
                                    <div
                                        v-if="
                                            file.mime_type.startsWith('image/')
                                        "
                                        class="w-full h-40 bg-gray-100 dark:bg-gray-800 overflow-hidden"
                                    >
                                        <img
                                            :src="file.links.show"
                                            alt="Preview"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>

                                    <!-- Ícono genérico si NO es imagen -->
                                    <div
                                        v-else
                                        class="w-full h-40 flex items-center justify-center bg-gray-100 dark:bg-gray-800"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 text-gray-500"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                        >
                                            <path
                                                d="M16.5 2h-9A2.5 2.5 0 005 4.5v15A2.5 2.5 0 007.5 22h9a2.5 2.5 0 002.5-2.5v-15A2.5 2.5 0 0016.5 2zm-1 14h-7v-2h7v2zm0-4h-7V10h7v2zm0-4h-7V6h7v2z"
                                            />
                                        </svg>
                                    </div>

                                    <!-- Información -->
                                    <div class="p-4">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white truncate"
                                        >
                                            {{ file.original_name }}
                                        </div>

                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-300 mt-1"
                                        >
                                            {{ file.mime_type }} ·
                                            {{ Math.round(file.size / 1024) }}
                                            KB
                                        </div>

                                        <a
                                            :href="file.links.show"
                                            target="_blank"
                                            class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors"
                                        >
                                            {{ __("View Document") }}
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M13 5h6v6h-2V8.414l-7.293 7.293-1.414-1.414L15.586 7H13V5z"
                                                />
                                                <path
                                                    d="M5 5h5V3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5h-2v5H5V5z"
                                                />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appeal History Section -->
                        <div
                            v-if="
                                data.transaction.refund.appeal &&
                                data.transaction.refund.appeal.length > 0
                            "
                            class="mb-4"
                        >
                            <h4
                                class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center"
                            >
                                <i
                                    class="fas fa-gavel mr-2 text-orange-500"
                                ></i>
                                {{ __("Appeal History") }}
                                <span
                                    class="ml-2 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-xs px-2 py-1 rounded-full"
                                >
                                    {{ data.transaction.refund.appeal.length }}
                                    {{ __("appeal(s)") }}
                                </span>
                            </h4>
                            <div class="space-y-4">
                                <div
                                    v-for="(appeal, index) in data.transaction
                                        .refund.appeal"
                                    :key="appeal.id"
                                    class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg p-4"
                                >
                                    <div
                                        class="flex items-center justify-between mb-3"
                                    >
                                        <div class="flex items-center">
                                            <span
                                                class="font-medium text-orange-800 dark:text-orange-300"
                                            >
                                                {{ __("Appeal Request") }} #{{
                                                    index + 1
                                                }}
                                            </span>
                                            <span
                                                class="ml-3 text-xs px-2 py-1 rounded-full"
                                                :class="
                                                    getRefundStatusClass(
                                                        appeal.status
                                                    )
                                                "
                                            >
                                                {{
                                                    formatRefundStatus(
                                                        appeal.status
                                                    )
                                                }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-xs text-orange-600 dark:text-orange-400"
                                        >
                                            {{ __("Submitted on") }}
                                            {{
                                                formatFullDate(
                                                    appeal.created_at
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm"
                                    >
                                        <div>
                                            <strong
                                                class="text-orange-700 dark:text-orange-400"
                                                >{{
                                                    __("Appeal Reason")
                                                }}:</strong
                                            >
                                            <p
                                                class="text-orange-600 dark:text-orange-300 mt-1"
                                            >
                                                {{ appeal.reason }}
                                            </p>
                                        </div>
                                        <div>
                                            <strong
                                                class="text-orange-700 dark:text-orange-400"
                                                >{{
                                                    __("Detailed Explanation")
                                                }}:</strong
                                            >
                                            <p
                                                class="text-orange-600 dark:text-orange-300 mt-1"
                                            >
                                                {{ appeal.description }}
                                            </p>
                                        </div>
                                        <div>
                                            <strong
                                                class="text-orange-700 dark:text-orange-400"
                                                >{{
                                                    __("Requested Amount")
                                                }}:</strong
                                            >
                                            <p
                                                class="text-orange-600 dark:text-orange-300 mt-1 font-bold"
                                            >
                                                {{ formatMoney(appeal.amount) }}
                                                {{ appeal.currency }}
                                            </p>
                                        </div>
                                        <div>
                                            <strong
                                                class="text-orange-700 dark:text-orange-400"
                                                >{{
                                                    __("Appeal Type")
                                                }}:</strong
                                            >
                                            <p
                                                class="text-orange-600 dark:text-orange-300 mt-1 capitalize"
                                            >
                                                {{
                                                    appeal.type ||
                                                    __("refund appeal")
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Appeal Evidence -->
                                    <div
                                        v-if="
                                            appeal.files &&
                                            appeal.files.length > 0
                                        "
                                        class="mt-3 pt-3 border-t border-orange-200 dark:border-orange-600"
                                    >
                                        <strong
                                            class="text-orange-700 dark:text-orange-400 text-sm"
                                            >{{
                                                __("Additional Evidence")
                                            }}:</strong
                                        >
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span
                                                v-for="(
                                                    file, fileIndex
                                                ) in appeal.files"
                                                :key="fileIndex"
                                                class="text-xs bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 px-2 py-1 rounded"
                                            >
                                                <i
                                                    class="fas fa-paperclip mr-1"
                                                ></i>
                                                {{ file.name || __("File") }}
                                                {{ fileIndex + 1 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Action Buttons -->
                        <div
                            class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-gray-600"
                        >
                            <button
                                v-if="
                                    canSubmitAppeal(
                                        data.transaction.refund.status
                                    )
                                "
                                @click="openAppealModal"
                                class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center"
                            >
                                <i class="fas fa-gavel mr-2"></i>
                                {{ __("Submit Appeal Request") }}
                            </button>
                            <button
                                @click="downloadRefundDetails"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center"
                            >
                                <i class="fas fa-download mr-2"></i>
                                {{ __("Download Refund Details") }}
                            </button>

                            <button
                                @click="contactSupport"
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center"
                            >
                                <i class="fas fa-headset mr-2"></i>
                                {{ __("Contact Support Team") }}
                            </button>
                            <button
                                v-if="
                                    canSubmitNewRefund(
                                        data.transaction.refund.status
                                    )
                                "
                                @click="openNewRefundModal"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 cursor-pointer flex items-center"
                            >
                                <i class="fas fa-undo-alt mr-2"></i>
                                {{ __("Submit New Refund Request") }}
                            </button>
                        </div>

                        <!-- Help Information -->
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mt-4"
                        >
                            <h5
                                class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center"
                            >
                                <i
                                    class="fas fa-info-circle mr-2 text-blue-500"
                                ></i>
                                {{ __("Need Help With Your Refund?") }}
                            </h5>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400"
                            >
                                <div>
                                    <p class="mb-2">
                                        <strong
                                            >{{
                                                __("Refund Processing Time")
                                            }}:</strong
                                        >
                                        {{
                                            __(
                                                "Typically 5-10 business days after approval"
                                            )
                                        }}
                                    </p>
                                    <p class="mb-2">
                                        <strong
                                            >{{ __("Payment Method") }}:</strong
                                        >
                                        {{
                                            __(
                                                "Refunds are processed to your original payment method"
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
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
import VHeader from "../../Components/VHeader.vue";
import VRefund from "./Refund.vue";

const page = usePage();
const data = ref({});

const formatMoney = (cents) => {
    if (!cents) return "0.00";
    return (cents / 100).toFixed(2);
};

const formatFullDate = (dateString) => {
    if (!dateString) return __("N/A");
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
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
        pending: __("Under Review"),
        under_review: __("Under Review"),
        approved: __("Approved"),
        waiting_for_return: __("Waiting for Return"),
        processing: __("Processing Refund"),
        completed: __("Refund Completed"),
        rejected: __("Rejected"),
        canceled: __("Canceled"),
    };
    return statusMap[status] || status;
};

const getRefundStatusClass = (status) => {
    const classMap = {
        pending:
            "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300",
        under_review:
            "bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300",
        approved:
            "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300",
        waiting_for_return:
            "bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300",
        processing:
            "bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300",
        completed:
            "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300",
        rejected:
            "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300",
        canceled:
            "bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300",
    };
    return (
        classMap[status] ||
        "bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300"
    );
};

const getTimelineSteps = (currentStatus) => {
    const steps = [
        {
            status: "under_review",
            title: __("Under Review"),
            description: __(
                "Our team is carefully reviewing your refund request and the provided evidence."
            ),
            details: __(
                "We verify the details and check if your request meets our refund policy requirements."
            ),
            estimatedTime: __("Estimated time: 1-2 business days"),
            completed: [
                "approved",
                "waiting_for_return",
                "processing",
                "completed",
            ].includes(currentStatus),
            current: currentStatus === "under_review",
            last: false,
        },
        {
            status: "approved",
            title: __("Approved"),
            description: __(
                "Your refund request has been approved and we're preparing to process your refund."
            ),
            details: __(
                "The approved amount will be refunded to your original payment method."
            ),
            estimatedTime: __("Refund will be processed within 24 hours"),
            completed: [
                "waiting_for_return",
                "processing",
                "completed",
            ].includes(currentStatus),
            current: currentStatus === "approved",
            last: false,
        },
        {
            status: "processing",
            title: __("Processing Refund"),
            description: __(
                "The refund is being processed by our payment system and your bank."
            ),
            details: __(
                "The funds are being transferred back to your original payment method."
            ),
            estimatedTime: __(
                "Typically takes 3-5 business days to appear in your account"
            ),
            completed: ["completed"].includes(currentStatus),
            current: currentStatus === "processing",
            last: false,
        },
        {
            status: "completed",
            title: __("Refund Completed"),
            description: __(
                "The refund has been successfully processed and the funds should be in your account."
            ),
            details: __(
                "Check your bank statement or payment method for the refund transaction."
            ),
            estimatedTime: __("Process completed successfully"),
            completed: currentStatus === "completed",
            current: currentStatus === "completed",
            last: true,
        },
    ];

    if (currentStatus === "waiting_for_return") {
        steps.splice(2, 0, {
            status: "waiting_for_return",
            title: __("Waiting for Item Return"),
            description: __(
                "We need you to return the item to complete the refund process."
            ),
            details: __(
                "Please follow the return instructions provided by our team. The refund will be processed once we receive the item."
            ),
            estimatedTime: __("Please return within 14 days"),
            completed: false,
            current: true,
            last: false,
        });
    }

    return steps.filter(
        (step) =>
            !(
                currentStatus === "rejected" &&
                ["processing", "completed"].includes(step.status)
            ) &&
            !(
                currentStatus === "canceled" &&
                ["processing", "completed"].includes(step.status)
            )
    );
};

const canSubmitAppeal = (status) => {
    return ["rejected", "canceled"].includes(status);
};

const canSubmitNewRefund = (status) => {
    return !status || ["rejected", "canceled", "completed"].includes(status);
};

const openAppealModal = () => {
    alert(
        __(
            "Appeal feature coming soon! You will be able to submit an appeal with additional evidence and explanations."
        )
    );
};

const openNewRefundModal = () => {
    alert(__("New refund request feature coming soon!"));
};

const downloadRefundDetails = () => {
    alert(
        __(
            "Download feature coming soon! You will be able to download a PDF with all refund details."
        )
    );
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
    window.open(url, "_blank");
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
