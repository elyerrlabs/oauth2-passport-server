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
            class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-50/30 py-6 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-8"
                >
                    <div
                        class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-8"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div
                                class="flex items-center space-x-4 mb-4 sm:mb-0"
                            >
                                <div
                                    class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm"
                                >
                                    <i
                                        class="fas fa-clock text-white text-2xl"
                                    ></i>
                                </div>
                                <div>
                                    <h1
                                        class="text-2xl md:text-3xl font-bold text-white"
                                    >
                                        {{ __("Pending Orders") }}
                                    </h1>
                                    <p class="text-yellow-100 mt-1">
                                        {{
                                            __(
                                                "Review and manage pending customer orders"
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                            <button
                                v-if="orders.length > 0"
                                @click="getCheckouts"
                                :disabled="loading"
                                class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-white/30"
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
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center"
                >
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-3xl flex items-center justify-center mx-auto mb-6"
                        >
                            <i
                                class="fas fa-clock text-yellow-400 text-4xl"
                            ></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ __("No Pending Orders") }}
                        </h3>
                        <p class="text-gray-600 mb-6">
                            {{
                                __(
                                    "All orders are currently processed. New pending orders will appear here."
                                )
                            }}
                        </p>
                        <a
                            href="#"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl"
                        >
                            <i class="fas fa-store mr-2"></i>
                            {{ __("View Products") }}
                        </a>
                    </div>
                </div>

                <!-- Orders Content -->
                <div v-else-if="orders.length > 0">
                    <!-- Stats Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4"
                                >
                                    <i
                                        class="fas fa-clock text-yellow-600 text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        {{ __("Pending Orders") }}
                                    </p>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ orders.length }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4"
                                >
                                    <i
                                        class="fas fa-dollar-sign text-blue-600 text-lg"
                                    ></i>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4"
                                >
                                    <i
                                        class="fas fa-boxes text-green-600 text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        {{ __("Total Items") }}
                                    </p>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ totalItemsCount }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end mb-5 p-4 space-x-2 bg-white rounded-xl px-3 py-2 border border-gray-300 shadow-sm"
                    >
                        <i class="fas fa-list-ol text-gray-400"></i>
                        <select
                            v-model="search.per_page"
                            @change="getCheckouts"
                            class="border-0 focus:ring-0 text-gray-700 font-medium bg-transparent"
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
                    <div class="space-y-4">
                        <div
                            v-for="order in orders"
                            :key="order.id"
                            class="bg-white rounded-2xl shadow-lg border border-yellow-200 overflow-hidden transition-all duration-300 hover:shadow-xl"
                        >
                            <!-- Order Header -->
                            <div
                                class="p-6 cursor-pointer border-b border-yellow-100"
                                @click="toggleOrder(order.id)"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center"
                                        >
                                            <span
                                                class="text-yellow-600 font-bold text-lg"
                                                >{{
                                                    orderNumberIcon(order.code)
                                                }}</span
                                            >
                                        </div>
                                        <div>
                                            <h3
                                                class="font-semibold text-gray-900"
                                            >
                                                {{ __("Order") }} #{{
                                                    order.code
                                                }}
                                            </h3>
                                            <p
                                                class="text-sm text-gray-600 flex items-center mt-1"
                                            >
                                                <i
                                                    class="fas fa-calendar-alt mr-2 text-yellow-500"
                                                ></i>
                                                {{
                                                    formatCompactDate(
                                                        order.created_at
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold capitalize bg-yellow-100 text-yellow-800 border border-yellow-200"
                                            >
                                                {{
                                                    __(order.transaction.status)
                                                }}
                                            </span>
                                            <p
                                                class="text-lg font-bold text-gray-900 mt-1"
                                            >
                                                {{ order.transaction.total }}
                                                {{ order.transaction.currency }}
                                            </p>
                                        </div>
                                        <i
                                            class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
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
                                class="p-6 bg-yellow-50/30"
                            >
                                <div
                                    class="grid grid-cols-1 lg:grid-cols-3 gap-6"
                                >
                                    <!-- Transaction Details -->
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-sm border border-gray-200"
                                    >
                                        <div class="flex items-center mb-4">
                                            <i
                                                class="fas fa-receipt text-yellow-500 mr-3"
                                            ></i>
                                            <h4
                                                class="font-semibold text-gray-900"
                                            >
                                                {{ __("Transaction Details") }}
                                            </h4>
                                        </div>
                                        <div class="space-y-3">
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-sm text-gray-600"
                                                    >{{ __("Status") }}</span
                                                >
                                                <span
                                                    class="font-semibold capitalize text-yellow-600"
                                                >
                                                    {{
                                                        __(
                                                            order.transaction
                                                                .status
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-sm text-gray-600"
                                                    >{{
                                                        __("Payment Method")
                                                    }}</span
                                                >
                                                <span
                                                    class="text-sm font-medium text-gray-900"
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
                                                    class="text-sm text-gray-600"
                                                    >{{
                                                        __("Total Amount")
                                                    }}</span
                                                >
                                                <span
                                                    class="text-lg font-bold text-yellow-600"
                                                >
                                                    {{
                                                        order.transaction.total
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
                                        class="bg-white rounded-xl p-6 shadow-sm border border-gray-200"
                                    >
                                        <div class="flex items-center mb-4">
                                            <i
                                                class="fas fa-truck text-green-500 mr-3"
                                            ></i>
                                            <h4
                                                class="font-semibold text-gray-900"
                                            >
                                                {{ __("Delivery Address") }}
                                            </h4>
                                        </div>
                                        <div class="space-y-2">
                                            <p
                                                class="font-medium text-gray-900"
                                            >
                                                {{
                                                    order.delivery_address
                                                        .full_name
                                                }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{
                                                    order.delivery_address
                                                        .address
                                                }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{
                                                    order.delivery_address.city
                                                }},
                                                {{
                                                    order.delivery_address
                                                        .country
                                                }}
                                            </p>
                                            <div
                                                class="flex items-center justify-between mt-3"
                                            >
                                                <div
                                                    class="flex items-center text-sm text-gray-600"
                                                >
                                                    <i
                                                        class="fas fa-phone mr-2"
                                                    ></i>
                                                    {{
                                                        order.delivery_address
                                                            .phone
                                                    }}
                                                </div>
                                                <a
                                                    v-if="
                                                        order.delivery_address
                                                            .whatsapp
                                                    "
                                                    :href="
                                                        order.delivery_address
                                                            .whatsapp
                                                    "
                                                    target="_blank"
                                                    class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-colors"
                                                >
                                                    <i
                                                        class="fab fa-whatsapp text-sm"
                                                    ></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <div
                                        class="bg-white rounded-xl p-6 shadow-sm border border-gray-200"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-boxes text-purple-500 mr-3"
                                                ></i>
                                                <h4
                                                    class="font-semibold text-gray-900"
                                                >
                                                    {{ __("Order Items") }}
                                                </h4>
                                            </div>
                                            <span
                                                class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-medium"
                                            >
                                                {{ order.items.length }}
                                                {{ __("items") }}
                                            </span>
                                        </div>
                                        <div
                                            class="space-y-3 max-h-48 overflow-y-auto"
                                        >
                                            <div
                                                v-for="item in order.items"
                                                :key="item.id"
                                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50"
                                            >
                                                <img
                                                    :src="item.image"
                                                    :alt="item.name"
                                                    class="w-10 h-10 rounded-lg object-cover"
                                                />
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 truncate"
                                                    >
                                                        {{ item.name }}
                                                    </p>
                                                    <p
                                                        class="text-xs text-gray-600"
                                                    >
                                                        {{ __("Qty") }}:
                                                        {{ item.quantity }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p
                                                        class="text-sm font-semibold text-gray-900"
                                                    >
                                                        {{ item.total }}
                                                        {{ item.currency }}
                                                    </p>
                                                    <p
                                                        class="text-xs text-gray-600"
                                                    >
                                                        {{ item.unitPrice }}
                                                        {{ __("each") }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div
                                    class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-yellow-200"
                                >
                                    <a
                                        v-if="order.transaction.payment_url"
                                        :href="order.transaction.payment_url"
                                        target="_blank"
                                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md"
                                    >
                                        <i class="fas fa-receipt"></i>
                                        <span>{{ __("View Receipt") }}</span>
                                    </a>

                                    <button
                                        @click="copyOrderId(order.code)"
                                        class="px-4 py-2 bg-gray-500 cursor-pointer hover:bg-gray-600 text-white rounded-lg transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md"
                                    >
                                        <i class="fas fa-copy"></i>
                                        <span>{{ __("Copy Order ID") }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
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
                    class="flex justify-center items-center py-20"
                >
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg"
                        >
                            <i
                                class="fas fa-spinner fa-spin text-yellow-600 text-2xl"
                            ></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ __("Loading Pending Orders") }}
                        </h3>
                        <p class="text-gray-600">
                            {{
                                __(
                                    "Please wait while we fetch your pending orders"
                                )
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VPaginate from "../../Components/VPaginate.vue";

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
        totalPendingAmount() {
            return this.orders
                .reduce((total, order) => {
                    return total + parseFloat(order.transaction.total || 0);
                }, 0)
                .toFixed(2);
        },

        totalItemsCount() {
            return this.orders.reduce((total, order) => {
                return total + order.items.length;
            }, 0);
        },
    },

    created() {
        this.getCheckouts();
    },

    methods: {
        async getCheckouts() {
            this.loading = true;

            const params = { ...this.search, status: "pending" };

            try {
                const res = await this.$server.get(
                    this.$page.props.routes.orders,
                    {
                        params: params,
                    }
                );
                if (res.status === 200) {
                    this.orders = Object.values(res.data.data).map(
                        (checkout) => {
                            const transaction = checkout.transaction;

                            const items = checkout.orders.map((o) => ({
                                id: o.id,
                                name: o.meta.name,
                                quantity: o.quantity,
                                unitPrice: (o.meta.price.amount / 100).toFixed(
                                    2
                                ),
                                total: (
                                    (o.quantity * o.meta.price.amount) /
                                    100
                                ).toFixed(2),
                                currency: o.currency || transaction.currency,
                                image: o.images?.[0]?.url
                                    ? o.images[0].url
                                    : "https://via.placeholder.com/150?text=" +
                                      encodeURIComponent(o.meta.name),
                            }));

                            return {
                                id: checkout.id,
                                code: checkout.code,
                                transaction_code: checkout.transaction_code,
                                transaction,
                                delivery_address: checkout.delivery_address,
                                items,
                                created_at: checkout.created_at,
                            };
                        }
                    );

                    this.pages = res.data.meta.pagination;
                    this.search.current_page =
                        res.data.meta.pagination.current_page;

                    // Initialize expandedOrders state
                    const initialExpandedState = {};
                    this.orders.forEach((order) => {
                        initialExpandedState[order.id] = false;
                    });
                    this.expandedOrders = initialExpandedState;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.success(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        },

        formatCompactDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
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

        copyOrderId(orderCode) {
            navigator.clipboard.writeText(orderCode);
            this.$notify.success(__("Order ID copied to clipboard"));
        },
    },
};
</script>
