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
            class="min-h-screen bg-gray-50 dark:bg-gray-900 py-3 px-3 sm:py-6 sm:px-6 lg:px-8"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden mb-4 sm:mb-8"
                >
                    <div
                        class="bg-blue-600 dark:bg-blue-700 px-4 py-5 sm:px-6 sm:py-8"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div
                                class="flex items-center space-x-3 mb-4 sm:mb-0"
                            >
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 dark:bg-black/20 rounded-lg sm:rounded-xl flex items-center justify-center"
                                >
                                    <i
                                        class="fas fa-shopping-bag text-white text-base sm:text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <h1
                                        class="text-base sm:text-xl font-bold text-white"
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
                                class="px-3 py-2 bg-white/20 dark:bg-black/20 hover:bg-white/30 dark:hover:bg-black/30 text-white rounded-lg flex items-center space-x-2 text-xs sm:text-sm transition-colors"
                            >
                                <i
                                    class="fas fa-sync-alt"
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
                    class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 sm:p-8 text-center"
                >
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4"
                        >
                            <i
                                class="fas fa-inbox text-gray-400 dark:text-gray-500 text-2xl"
                            ></i>
                        </div>
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("No Orders Yet") }}
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 mb-6 text-sm"
                        >
                            {{
                                __(
                                    "Your orders will appear here once customers start purchasing your products"
                                )
                            }}
                        </p>
                        <a
                            href="#"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg text-sm transition-colors"
                        >
                            <i class="fas fa-store mr-2 text-xs"></i>
                            {{ __("View Products") }}
                        </a>
                    </div>
                </div>

                <!-- Orders Content -->
                <div v-else-if="orders.length > 0">
                    <!-- Stats Overview -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mb-4 sm:mb-6"
                    >
                        <div
                            v-for="stat in stats"
                            :key="stat.label"
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-4"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center mr-3"
                                    :class="stat.bgColor"
                                >
                                    <i :class="stat.icon" class="text-sm"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-medium text-gray-600 dark:text-gray-400"
                                    >
                                        {{ stat.label }}
                                    </p>
                                    <p
                                        class="text-lg font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ stat.value }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Per Page Selector -->
                    <div
                        class="flex items-center justify-end mb-3 sm:mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600"
                    >
                        <i
                            class="fas fa-list-ol text-gray-400 dark:text-gray-500 text-sm mr-2"
                        ></i>
                        <select
                            v-model="search.per_page"
                            @change="getCheckouts"
                            class="border-0 focus:ring-0 text-gray-700 dark:text-gray-300 font-medium bg-transparent dark:bg-gray-800 text-sm"
                        >
                            <option value="5">5 {{ __("per page") }}</option>
                            <option value="10">10 {{ __("per page") }}</option>
                            <option value="15">15 {{ __("per page") }}</option>
                            <option value="25">25 {{ __("per page") }}</option>
                            <option value="50">50 {{ __("per page") }}</option>
                        </select>
                    </div>

                    <!-- Orders List -->
                    <div class="space-y-3">
                        <div
                            v-for="order in orders"
                            :key="order.id"
                            class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow border border-gray-200 dark:border-gray-700"
                        >
                            <!-- Order Header -->
                            <div
                                class="p-4 cursor-pointer border-b border-gray-100 dark:border-gray-700"
                                @click="toggleOrder(order.id)"
                            >
                                <div class="flex items-center justify-between">
                                    <div
                                        class="flex items-center space-x-3 min-w-0"
                                    >
                                        <div
                                            class="shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
                                        >
                                            <span
                                                class="text-blue-600 dark:text-blue-500 font-bold text-sm"
                                            >
                                                {{
                                                    orderNumberIcon(order.code)
                                                }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white text-sm truncate"
                                            >
                                                {{ __("Order") }} #{{
                                                    order.code
                                                }}
                                            </h3>
                                            <p
                                                class="text-xs text-gray-600 dark:text-gray-400 flex items-center mt-1"
                                            >
                                                <i
                                                    class="fas fa-calendar-alt mr-1 text-blue-500 dark:text-blue-400"
                                                ></i>
                                                {{ order.created_at }}
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="flex items-center space-x-2 sm:space-x-3"
                                    >
                                        <div class="text-right hidden xs:block">
                                            <span
                                                :class="
                                                    getStatusBadgeClass(
                                                        order.transaction.status
                                                    )
                                                "
                                                class="text-xs"
                                            >
                                                {{
                                                    __(order.transaction.status)
                                                }}
                                            </span>
                                            <p
                                                class="text-sm font-bold text-gray-900 dark:text-white mt-1"
                                            >
                                                {{ order.transaction.total }}
                                                {{ order.transaction.currency }}
                                            </p>
                                        </div>
                                        <i
                                            class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-sm transition-transform"
                                            :class="{
                                                'rotate-180':
                                                    expandedOrders[order.id],
                                            }"
                                        ></i>
                                    </div>
                                </div>

                                <!-- Mobile status and amount -->
                                <div
                                    class="flex justify-between items-center mt-3 xs:hidden"
                                >
                                    <span
                                        :class="
                                            getStatusBadgeClass(
                                                order.transaction.status
                                            )
                                        "
                                        class="text-xs"
                                    >
                                        {{ __(order.transaction.status) }}
                                    </span>
                                    <p
                                        class="text-sm font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ order.transaction.total }}
                                        {{ order.transaction.currency }}
                                    </p>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div
                                v-if="expandedOrders[order.id]"
                                class="p-4 bg-gray-50 dark:bg-gray-700"
                            >
                                <div class="space-y-4">
                                    <!-- Transaction and Delivery Info -->
                                    <div
                                        class="grid grid-cols-1 lg:grid-cols-2 gap-4"
                                    >
                                        <!-- Transaction Details -->
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700"
                                        >
                                            <div class="flex items-center mb-3">
                                                <i
                                                    class="fas fa-receipt text-blue-500 dark:text-blue-400 mr-2"
                                                ></i>
                                                <h4
                                                    class="font-semibold text-gray-900 dark:text-white text-sm"
                                                >
                                                    {{
                                                        __(
                                                            "Transaction Details"
                                                        )
                                                    }}
                                                </h4>
                                            </div>
                                            <div class="space-y-2">
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-xs text-gray-600 dark:text-gray-400"
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
                                                        class="text-xs"
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
                                                        class="text-xs text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __("Payment Method")
                                                        }}</span
                                                    >
                                                    <span
                                                        class="text-xs font-medium text-gray-900 dark:text-white"
                                                    >
                                                        {{
                                                            order.transaction
                                                                .payment_method
                                                        }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-xs text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __("Total Amount")
                                                        }}</span
                                                    >
                                                    <span
                                                        class="text-sm font-bold text-green-600 dark:text-green-500"
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
                                            </div>
                                        </div>

                                        <!-- Delivery Address -->
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700"
                                        >
                                            <div class="flex items-center mb-3">
                                                <i
                                                    class="fas fa-truck text-green-500 dark:text-green-400 mr-2"
                                                ></i>
                                                <h4
                                                    class="font-semibold text-gray-900 dark:text-white text-sm"
                                                >
                                                    {{ __("Delivery Address") }}
                                                </h4>
                                            </div>
                                            <div class="space-y-2">
                                                <p
                                                    class="font-medium text-gray-900 dark:text-white text-sm"
                                                >
                                                    {{
                                                        order.delivery_address
                                                            .full_name
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-600 dark:text-gray-400"
                                                >
                                                    {{
                                                        order.delivery_address
                                                            .address
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-600 dark:text-gray-400"
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
                                                <div
                                                    class="flex items-center justify-between mt-3"
                                                >
                                                    <div
                                                        class="flex items-center text-xs text-gray-600 dark:text-gray-400"
                                                    >
                                                        <i
                                                            class="fas fa-phone mr-1"
                                                        ></i>
                                                        {{
                                                            order
                                                                .delivery_address
                                                                .phone
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-boxes text-purple-500 dark:text-purple-400 mr-2"
                                                ></i>
                                                <h4
                                                    class="font-semibold text-gray-900 dark:text-white text-sm"
                                                >
                                                    {{ __("Order Items") }}
                                                </h4>
                                            </div>
                                            <span
                                                class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs font-medium"
                                            >
                                                {{ order.orders.length }}
                                                {{ __("items") }}
                                            </span>
                                        </div>
                                        <div class="space-y-3">
                                            <div
                                                v-for="item in order.orders"
                                                :key="item.id"
                                                class="flex items-center space-x-3 p-3 rounded border border-gray-200 dark:border-gray-600"
                                            >
                                                <div
                                                    class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center shrink-0"
                                                >
                                                    <i
                                                        v-if="
                                                            !item.images ||
                                                            item.images
                                                                .length === 0
                                                        "
                                                        class="fas fa-image text-gray-400 dark:text-gray-500"
                                                    ></i>
                                                    <img
                                                        v-else
                                                        :src="
                                                            getImageUrl(
                                                                item.images[0]
                                                                    .url
                                                            )
                                                        "
                                                        :alt="item.meta.name"
                                                        class="w-12 h-12 rounded object-cover"
                                                        loading="lazy"
                                                    />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                                    >
                                                        {{ item.meta.name }}
                                                    </p>
                                                    <div
                                                        class="flex flex-wrap items-center gap-2 mt-1"
                                                    >
                                                        <p
                                                            class="text-xs text-gray-600 dark:text-gray-400"
                                                        >
                                                            <strong
                                                                >{{
                                                                    __("Qty")
                                                                }}:</strong
                                                            >
                                                            {{ item.quantity }}
                                                        </p>
                                                        <p
                                                            class="text-xs text-gray-600 dark:text-gray-400"
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
                                                    class="text-right shrink-0"
                                                >
                                                    <p
                                                        class="text-sm font-bold text-green-600 dark:text-green-500"
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
                                    class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-600"
                                >
                                    <button
                                        @click="copyOrderId(order.code)"
                                        class="px-3 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white rounded flex items-center space-x-2 text-xs transition-colors"
                                    >
                                        <i class="fas fa-copy"></i>
                                        <span>{{ __("Copy Order ID") }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-6">
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
                    class="flex justify-center items-center py-12"
                >
                    <div class="text-center">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center mx-auto mb-4"
                        >
                            <i
                                class="fas fa-spinner fa-spin text-blue-600 dark:text-blue-500 text-xl"
                            ></i>
                        </div>
                        <h3
                            class="text-base font-semibold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Loading Orders") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{ __("Please wait while we fetch your orders") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const orders = ref([]);
const loading = ref(false);
const pages = ref({
    total_pages: 0,
});
const search = ref({
    page: 1,
    per_page: 15,
});
const expandedOrders = ref({});

// Computed para estadísticas optimizadas
const stats = computed(() => [
    {
        label: __("Total Orders"),
        value: orders.value.length,
        icon: "fas fa-shopping-bag text-blue-600 dark:text-blue-500",
        bgColor: "bg-blue-100 dark:bg-blue-900",
    },
    {
        label: __("Completed"),
        value: orders.value.filter(
            (order) =>
                order.transaction.status === "successful" ||
                order.transaction.status === "completed"
        ).length,
        icon: "fas fa-check-circle text-green-600 dark:text-green-500",
        bgColor: "bg-green-100 dark:bg-green-900",
    },
    {
        label: __("Pending"),
        value: orders.value.filter(
            (order) =>
                order.transaction.status === "pending" ||
                order.transaction.status === "processing"
        ).length,
        icon: "fas fa-clock text-yellow-600 dark:text-yellow-500",
        bgColor: "bg-yellow-100 dark:bg-yellow-900",
    },
    {
        label: __("Failed"),
        value: orders.value.filter(
            (order) =>
                order.transaction.status === "failed" ||
                order.transaction.status === "cancelled"
        ).length,
        icon: "fas fa-times-circle text-red-600 dark:text-red-500",
        bgColor: "bg-red-100 dark:bg-red-900",
    },
]);

onMounted(() => {
    getCheckouts();
});

async function getCheckouts() {
    loading.value = true;

    try {
        const res = await $server.get(page.props.api.orders, {
            params: search.value,
        });
        if (res.status === 200) {
            const values = res.data;

            if (values.data && typeof values.data === "object") {
                orders.value = Object.values(values.data);
            } else {
                orders.value = values.data || [];
            }

            pages.value = values.meta?.pagination || { total_pages: 0 };
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
}

function getImageUrl(imagePath) {
    if (!imagePath) return "";
    if (imagePath.startsWith("http")) {
        return imagePath;
    }
    return `${location.origin}${imagePath}`;
}

function calculateItemTotal(item) {
    const price = parseFloat(item.price) || 0;
    const quantity = item.quantity || 0;
    const total = price * quantity;
    return (total / 100).toFixed(2);
}

function orderNumberIcon(code) {
    return code?.slice(-1).toUpperCase() || "#";
}

function toggleOrder(orderId) {
    expandedOrders.value = {
        ...expandedOrders.value,
        [orderId]: !expandedOrders.value[orderId],
    };
}

function getStatusBadgeClass(status) {
    const baseClasses =
        "px-2 py-1 rounded-full text-xs font-semibold capitalize inline-block";
    const statusClasses = {
        successful:
            "bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300",
        completed:
            "bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300",
        pending:
            "bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300",
        processing:
            "bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300",
        failed: "bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300",
        cancelled: "bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300",
    };
    return `${baseClasses} ${
        statusClasses[status] ||
        "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"
    }`;
}

function getStatusTextClass(status) {
    const statusClasses = {
        successful: "text-green-600 dark:text-green-400",
        completed: "text-green-600 dark:text-green-400",
        pending: "text-yellow-600 dark:text-yellow-400",
        processing: "text-yellow-600 dark:text-yellow-400",
        failed: "text-red-600 dark:text-red-400",
        cancelled: "text-red-600 dark:text-red-400",
    };
    return `font-semibold capitalize ${
        statusClasses[status] || "text-gray-600 dark:text-gray-400"
    }`;
}

function copyOrderId(orderCode) {
    navigator.clipboard.writeText(orderCode);
    $notify.success(__("Order ID copied to clipboard"));
}
</script>

<style>
@media (max-width: 475px) {
    .xs\:hidden {
        display: none !important;
    }

    .xs\:block {
        display: block !important;
    }
}

* {
    -webkit-tap-highlight-color: transparent;
    -webkit-font-smoothing: antialiased;
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}
</style>
