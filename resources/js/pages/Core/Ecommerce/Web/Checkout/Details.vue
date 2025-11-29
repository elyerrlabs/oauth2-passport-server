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
                                            data.delivery_address?.city || "N/A"
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
                                            "N/A"
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
                                            "N/A"
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
                                    <span v-else class="text-gray-500"
                                        >N/A</span
                                    >
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
                                                            ?.name || "N/A"
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
                                                            ?.name || "N/A"
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
                        <button
                            class="px-4 py-2 bg-red-500 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-100 dark:text-gray-300 font-medium rounded-lg hover:bg-red-700 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex items-center"
                        >
                            <i class="fas fa-copy mr-2"></i>
                            {{ __("Request Refund") }}
                        </button>
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
import { usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import VHeader from "../../Components/VHeader.vue";

const page = usePage();
const data = ref({});

const formatFullDate = (dateString) => {
    if (!dateString) return "N/A";
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
        successful: "Completed",
        expired: "Expired",
        pending: "Processing",
        refunded: "Refunded",
        cancelled: "Cancelled",
    };
    return statusMap[status] || status;
};

const formatPaymentMethod = (method) => {
    const methodMap = {
        offline: "Offline Payment",
        card: "Credit Card",
        paypal: "PayPal",
        bank_transfer: "Bank Transfer",
        stripe: "Stripe",
    };
    return methodMap[method] || method;
};

const openReceipt = (url) => {
    window.open(url, "_blank");
};

const copyOrderId = (orderCode) => {
    navigator.clipboard.writeText(orderCode);
    // Assuming you have a notification system
    if (typeof $notify !== "undefined") {
        $notify.success("Order ID copied to clipboard");
    }
};

const copyTransactionCode = (transactionCode) => {
    navigator.clipboard.writeText(transactionCode);
    if (typeof $notify !== "undefined") {
        $notify.success("Transaction code copied to clipboard");
    }
};

onMounted(() => {
    console.log(page.props.data);
    data.value = page.props.data;
});
</script>
