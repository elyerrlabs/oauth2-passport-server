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

        <!-- Header -->
        <div
            class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div
                            class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg"
                        >
                            <i
                                class="fas fa-shopping-bag text-red-600 dark:text-red-400 text-lg"
                            ></i>
                        </div>
                        <div>
                            <h1
                                class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("My Orders") }}
                            </h1>
                            <p
                                class="text-xs sm:text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Track and manage your orders") }}
                            </p>
                        </div>
                    </div>
                    <button
                        v-if="orders.length"
                        @click="getCheckouts"
                        class="p-2 bg-white dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600 hover:border-red-300 dark:hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200"
                        :disabled="loading"
                        :class="{ 'opacity-50 cursor-not-allowed': loading }"
                    >
                        <i
                            class="fas fa-sync-alt text-red-600 dark:text-red-400 text-sm"
                            :class="{ 'animate-spin': loading }"
                        ></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
            <!-- Empty State -->
            <div
                v-if="orders.length == 0 && !loading"
                class="text-center bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 sm:p-8 max-w-md mx-auto"
            >
                <div
                    class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-red-100 dark:bg-red-900/30 rounded-full mb-4"
                >
                    <i
                        class="fas fa-box-open text-xl sm:text-2xl text-red-500 dark:text-red-400"
                    ></i>
                </div>
                <h3
                    class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2"
                >
                    {{ __("No orders found") }}
                </h3>
                <p
                    class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4 sm:mb-6"
                >
                    {{ __("You haven't placed any orders yet") }}
                </p>
                <a
                    :href="$page.props.routes.search"
                    class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                >
                    <i class="fas fa-shopping-cart mr-2 text-sm"></i>
                    {{ __("Start Shopping") }}
                </a>
            </div>

            <!-- Orders List -->
            <div v-else class="space-y-3 sm:space-y-4">
                <div
                    v-for="order in orders"
                    :key="order.id"
                    class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-800"
                >
                    <!-- Order Header - Mobile Optimized -->
                    <div
                        class="p-3 sm:p-4 border-b border-gray-100 dark:border-gray-600"
                    >
                        <!-- Top Row: Order Info -->
                        <div class="flex flex-col space-y-2 mb-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Order") }}:
                                    </span>
                                    <span
                                        class="text-xs font-bold text-gray-900 dark:text-white truncate max-w-[120px] sm:max-w-none"
                                    >
                                        #{{ order.code }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Date") }}:
                                    </span>
                                    <span
                                        class="text-xs text-gray-600 dark:text-gray-300"
                                    >
                                        {{ order?.transaction?.created }}
                                    </span>
                                </div>
                            </div>

                            <!-- Transaction & Amount Row -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 hidden sm:inline"
                                    >
                                        {{ __("Transaction") }}:
                                    </span>
                                    <span
                                        class="text-xs font-mono text-gray-900 dark:text-white truncate max-w-[100px] sm:max-w-none"
                                    >
                                        {{ order.transaction_code }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                        :class="{
                                            'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400':
                                                order?.transaction?.status ===
                                                'successful',
                                            'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400':
                                                order?.transaction?.status ===
                                                'pending',
                                            'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400':
                                                order?.transaction?.status ===
                                                'expired',
                                        }"
                                    >
                                        {{
                                            getShortStatus(
                                                order?.transaction?.status ?? ""
                                            )
                                        }}
                                    </span>
                                    <span
                                        :class="[
                                            'text-sm sm:text-base font-bold',
                                            order?.transaction?.status ==
                                            'successful'
                                                ? 'text-red-600 dark:text-red-400'
                                                : order?.transaction?.status ==
                                                  'expired'
                                                ? 'text-gray-500 dark:text-gray-500 line-through'
                                                : 'text-yellow-600 dark:text-yellow-400',
                                        ]"
                                    >
                                        {{ order?.transaction?.total }}
                                        <span class="text-xs font-normal">{{
                                            order?.transaction?.currency
                                        }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Expiration Info - Mobile Optimized -->
                        <div
                            v-show="!order?.transaction?.refund?.id"
                            class="mb-3"
                        >
                            <div
                                class="flex flex-col p-3 rounded-lg"
                                :class="
                                    getRefundExpirationClass(
                                        order?.transaction?.refund_expired
                                    )
                                "
                            >
                                <div class="flex items-start space-x-3 mb-2">
                                    <div
                                        class="shrink-0 flex items-center justify-center w-7 h-7 rounded-full mt-0.5"
                                        :class="
                                            getRefundExpirationIconClass(
                                                order?.transaction
                                                    ?.refund_expired
                                            )
                                        "
                                    >
                                        <i
                                            :class="
                                                getRefundExpirationIcon(
                                                    order?.transaction
                                                        ?.refund_expired
                                                )
                                            "
                                        ></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="font-semibold text-xs sm:text-sm mb-1"
                                            :class="
                                                getRefundExpirationTextClass(
                                                    order?.transaction
                                                        ?.refund_expired
                                                )
                                            "
                                        >
                                            {{
                                                getRefundExpirationTitle(
                                                    order?.transaction
                                                        ?.refund_expired
                                                )
                                            }}
                                        </h4>
                                        <p
                                            class="text-xs"
                                            :class="
                                                getRefundExpirationDescClass(
                                                    order?.transaction
                                                        ?.refund_expired
                                                )
                                            "
                                        >
                                            {{
                                                getRefundExpirationDescription(
                                                    order?.transaction
                                                )
                                            }}
                                        </p>
                                        <div
                                            v-if="
                                                !order?.transaction
                                                    ?.refund_expired
                                            "
                                            class="mt-2 space-y-1"
                                        >
                                            <div
                                                class="flex items-center space-x-2"
                                            >
                                                <i
                                                    class="fas fa-clock text-xs"
                                                    :class="
                                                        getRefundExpirationIconColor(
                                                            order?.transaction
                                                                ?.refund_expired
                                                        )
                                                    "
                                                ></i>
                                                <span
                                                    class="text-xs font-medium"
                                                    :class="
                                                        getRefundExpirationTextClass(
                                                            order?.transaction
                                                                ?.refund_expired
                                                        )
                                                    "
                                                >
                                                    {{
                                                        order?.transaction
                                                            ?.refund_expiration_date
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                class="text-xs text-gray-600 dark:text-gray-400"
                                            >
                                                {{ __("Expires") }}:
                                                {{
                                                    formatMobileDate(
                                                        order?.transaction
                                                            ?.refund_expiration_date
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Status Banner - Mobile Optimized -->
                        <div
                            v-show="order?.transaction?.refund?.id"
                            class="p-3 rounded-lg border animate-pulse"
                            :class="
                                getRefundBannerClass(order?.transaction?.refund)
                            "
                        >
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2"
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="shrink-0 flex items-center justify-center w-7 h-7 rounded-full bg-white/80"
                                    >
                                        <i
                                            :class="
                                                getRefundIcon(
                                                    order?.transaction?.refund
                                                )
                                            "
                                        ></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="font-semibold text-sm"
                                            :class="
                                                getRefundTextClass(
                                                    order?.transaction?.refund
                                                )
                                            "
                                        >
                                            {{ __("Refund") }}
                                            {{
                                                getRefundStatusText(
                                                    order?.transaction?.refund
                                                )
                                            }}
                                        </h4>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-end sm:justify-start mt-2 sm:mt-0"
                                >
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        :class="
                                            getRefundBadgeClass(
                                                order?.transaction?.refund
                                            )
                                        "
                                    >
                                        {{
                                            formatRefundStatus(
                                                order?.transaction?.refund
                                                    ?.status
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items - Mobile Optimized -->
                    <div class="p-3 sm:p-4">
                        <div class="space-y-2 sm:space-y-3">
                            <div
                                v-for="item in order.orders"
                                :key="item.id"
                                class="flex items-start sm:items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                            >
                                <img
                                    :src="item.images[0]?.url"
                                    :alt="item?.meta?.name"
                                    class="w-12 h-12 sm:w-16 sm:h-16 rounded border border-gray-200 dark:border-gray-600 object-cover shrink-0"
                                />
                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 mb-1"
                                    >
                                        {{ item?.meta?.name }}
                                    </h3>
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-4 text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        <span
                                            >{{ __("Qty") }}:
                                            {{ item.quantity }}</span
                                        >
                                        <span class="hidden sm:inline">•</span>
                                        <span
                                            class="text-red-600 dark:text-red-400 font-bold"
                                        >
                                            {{ item.currency
                                            }}{{ item.format_price }}
                                        </span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <button
                                        @click.stop="goTo(item.web.product)"
                                        class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200 cursor-pointer whitespace-nowrap"
                                    >
                                        {{ __("Buy Again") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Footer - Mobile Optimized -->
                    <div
                        class="px-3 py-3 sm:px-4 sm:py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                        >
                            <div
                                class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-600 dark:text-gray-400"
                            >
                                <span class="inline-flex items-center">
                                    <i
                                        class="fas fa-credit-card mr-1 text-xs"
                                    ></i>
                                    <span
                                        class="truncate max-w-[80px] sm:max-w-none"
                                    >
                                        {{
                                            formatPaymentMethod(
                                                order?.transaction
                                                    ?.payment_method
                                            )
                                        }}
                                    </span>
                                </span>
                                <span class="hidden sm:inline">•</span>
                                <span class="inline-flex items-center">
                                    <i
                                        class="fas fa-location-dot mr-1 text-xs"
                                    ></i>
                                    <span
                                        class="truncate max-w-[80px] sm:max-w-none"
                                    >
                                        {{ order?.delivery_address?.city }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button
                                    v-if="order.transaction.payment_url"
                                    @click.stop="
                                        openReceipt(
                                            order.transaction.payment_url
                                        )
                                    "
                                    :class="[
                                        'px-3 py-1.5 border text-xs font-medium rounded transition-colors duration-200 cursor-pointer flex-1 sm:flex-none',
                                        setStatusColorMobile(
                                            order.transaction.status
                                        ),
                                    ]"
                                >
                                    <i class="fas fa-receipt mr-1"></i>
                                    {{
                                        setStatusMobile(
                                            order.transaction.status
                                        )
                                    }}
                                </button>

                                <div
                                    class="flex items-center gap-2 w-full sm:w-auto"
                                >
                                    <button
                                        @click.stop="copyOrderId(order.code)"
                                        class="px-3 py-1.5 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-xs font-medium rounded hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex-1 sm:flex-none"
                                    >
                                        <i
                                            class="fas fa-copy mr-1 sm:hidden"
                                        ></i>
                                        <span class="hidden sm:inline">{{
                                            __("Copy ID")
                                        }}</span>
                                        <span class="sm:hidden">{{
                                            __("Order ID")
                                        }}</span>
                                    </button>

                                    <button
                                        @click.stop="goTo(order.web.show)"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-gray-600 dark:hover:bg-blue-500 text-white text-xs font-medium rounded transition-colors duration-200 cursor-pointer flex-1 sm:flex-none"
                                    >
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ __("Details") }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile-only action buttons -->
                        <div
                            class="flex items-center justify-between gap-2 mt-3 sm:hidden"
                        >
                            <button
                                v-if="order.transaction_code"
                                @click.stop="
                                    copyTransactionCode(order.transaction_code)
                                "
                                class="px-3 py-1.5 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-xs font-medium rounded hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer flex-1"
                            >
                                <i class="fas fa-copy mr-1"></i>
                                {{ __("Copy TXN") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center" v-if="orders.length > 0">
                <v-paginate
                    v-model="search.page"
                    :total-pages="pages.total_pages"
                    @change="changePage"
                />
            </div>
        </main>

        <!-- Loading -->
        <div
            v-if="loading"
            class="fixed inset-0 bg-white dark:bg-gray-900 bg-opacity-90 flex items-center justify-center z-50"
        >
            <div class="text-center">
                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 border-4 border-red-200 dark:border-red-800 border-t-red-500 dark:border-t-red-400 rounded-full animate-spin mb-3"
                ></div>
                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                    {{ __("Loading orders...") }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import VHeader from "@Ecommerce/components/VHeader.vue";
import VPaginate from "@/components/VPaginate.vue";
import { router } from "@inertiajs/vue3";

export default {
    components: {
        VHeader,
        VPaginate,
    },

    data() {
        return {
            dialog: false,
            selectedOrder: null,
            orders: [],
            loading: false,
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 20,
            },
        };
    },

    async created() {
        await this.getCheckouts();
    },

    watch: {
        "search.page"(value) {
            this.getCheckouts();
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        // Mobile optimized status methods
        getShortStatus(status) {
            const statusMap = {
                pending: "Pend",
                successful: "OK",
                expired: "Exp",
                failed: "Fail",
                cancelled: "Cancel",
            };
            return statusMap[status] || status.substring(0, 4);
        },

        setStatusMobile(status) {
            const statusMap = {
                pending: __("Pay"),
                expired: __("Exp"),
                successful: __("Receipt"),
            };
            return statusMap[status] || status.substring(0, 6);
        },

        setStatusColorMobile(status) {
            switch (status) {
                case "pending":
                    return "bg-yellow-500 border-yellow-500 text-white";
                case "expired":
                    return "bg-red-500 border-red-500 text-white";
                case "successful":
                    return "bg-green-500 border-green-500 text-white";
                default:
                    return "bg-gray-500 border-gray-500 text-white";
            }
        },

        formatMobileDate(dateString) {
            if (!dateString) return "";
            const date = new Date(dateString);
            const now = new Date();
            const isToday = date.toDateString() === now.toDateString();

            if (isToday) {
                return date.toLocaleTimeString("es-ES", {
                    hour: "2-digit",
                    minute: "2-digit",
                });
            }

            return date.toLocaleDateString("es-ES", {
                day: "2-digit",
                month: "short",
            });
        },

        // Refund expiration related methods
        getRefundExpirationClass(isExpired) {
            return isExpired
                ? "bg-gray-100 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600"
                : "bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700";
        },

        getRefundExpirationIconClass(isExpired) {
            return isExpired
                ? "bg-gray-200 dark:bg-gray-600"
                : "bg-emerald-100 dark:bg-emerald-800";
        },

        getRefundExpirationIcon(isExpired) {
            return isExpired
                ? "fas fa-clock text-gray-600 dark:text-gray-400 text-xs"
                : "fas fa-clock-rotate-left text-emerald-600 dark:text-emerald-400 text-xs";
        },

        getRefundExpirationIconColor(isExpired) {
            return isExpired
                ? "text-gray-600 dark:text-gray-400"
                : "text-emerald-600 dark:text-emerald-400";
        },

        getRefundExpirationTextClass(isExpired) {
            return isExpired
                ? "text-gray-800 dark:text-gray-300"
                : "text-emerald-800 dark:text-emerald-300";
        },

        getRefundExpirationDescClass(isExpired) {
            return isExpired
                ? "text-gray-600 dark:text-gray-400"
                : "text-emerald-700 dark:text-emerald-400";
        },

        getRefundExpirationTitle(isExpired) {
            return isExpired ? __("Refund Expired") : __("Refund Available");
        },

        getRefundExpirationDescription(transaction) {
            if (transaction?.refund_expired) {
                return __("Refund period has ended");
            } else {
                return __("You can request a refund");
            }
        },

        getRefundRequestButtonClass(isExpired) {
            return isExpired
                ? "bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 cursor-not-allowed opacity-60"
                : "bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm hover:shadow";
        },

        formatTimeRemaining(expirationDate) {
            if (!expirationDate) return "";

            const now = new Date();
            const expiration = new Date(expirationDate);
            const diffMs = expiration - now;
            const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24));

            if (diffDays <= 0) return __("Expires today");
            if (diffDays === 1) return __("1 day left");
            if (diffDays <= 7) return __(`${diffDays} days left`);
            if (diffDays <= 30)
                return __(`${Math.ceil(diffDays / 7)} weeks left`);

            const diffMonths = Math.ceil(diffDays / 30);
            return __(`${diffMonths} months left`);
        },

        // Refund related methods
        formatRefundStatus(status) {
            const statusMap = {
                pending: __("Pending"),
                processing: __("Processing"),
                under_review: __("Review"),
                approved: __("Approved"),
                waiting_for_return: __("Return"),
                refunding: __("Refunding"),
                completed: __("Completed"),
                rejected: __("Rejected"),
                canceled: __("Canceled"),
            };
            return statusMap[status] || status;
        },

        getRefundBannerClass(refund) {
            const baseClass = "border-l-4 ";
            const status = refund?.status;

            switch (status) {
                case "pending":
                case "under_review":
                    return (
                        baseClass +
                        "bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400 dark:border-yellow-500"
                    );
                case "processing":
                case "refunding":
                case "waiting_for_return":
                    return (
                        baseClass +
                        "bg-blue-50 dark:bg-blue-900/20 border-blue-400 dark:border-blue-500"
                    );
                case "approved":
                    return (
                        baseClass +
                        "bg-green-50 dark:bg-green-900/20 border-green-400 dark:border-green-500"
                    );
                case "completed":
                    return (
                        baseClass +
                        "bg-emerald-50 dark:bg-emerald-900/20 border-emerald-400 dark:border-emerald-500"
                    );
                case "rejected":
                    return (
                        baseClass +
                        "bg-red-50 dark:bg-red-900/20 border-red-400 dark:border-red-500"
                    );
                case "canceled":
                    return (
                        baseClass +
                        "bg-gray-100 dark:bg-gray-700 border-gray-400 dark:border-gray-500"
                    );
                default:
                    return (
                        baseClass +
                        "bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400 dark:border-yellow-500"
                    );
            }
        },

        getRefundIcon(refund) {
            const status = refund?.status;
            let iconClass = "fas ";

            switch (status) {
                case "pending":
                case "under_review":
                    iconClass +=
                        "fa-clock text-yellow-600 dark:text-yellow-400 text-xs";
                    break;
                case "processing":
                case "refunding":
                    iconClass +=
                        "fa-spinner fa-spin text-blue-600 dark:text-blue-400 text-xs";
                    break;
                case "waiting_for_return":
                    iconClass +=
                        "fa-truck-arrow-rotate-right text-blue-600 dark:text-blue-400 text-xs";
                    break;
                case "approved":
                    iconClass +=
                        "fa-check-circle text-green-600 dark:text-green-400 text-xs";
                    break;
                case "completed":
                    iconClass +=
                        "fa-circle-check text-emerald-600 dark:text-emerald-400 text-xs";
                    break;
                case "rejected":
                    iconClass +=
                        "fa-circle-xmark text-red-600 dark:text-red-400 text-xs";
                    break;
                case "canceled":
                    iconClass +=
                        "fa-ban text-gray-600 dark:text-gray-400 text-xs";
                    break;
                default:
                    iconClass +=
                        "fa-rotate-left text-yellow-600 dark:text-yellow-400 text-xs";
            }

            return iconClass;
        },

        getRefundTextClass(refund) {
            const status = refund?.status;

            switch (status) {
                case "pending":
                case "under_review":
                    return "text-yellow-800 dark:text-yellow-300";
                case "processing":
                case "refunding":
                case "waiting_for_return":
                    return "text-blue-800 dark:text-blue-300";
                case "approved":
                    return "text-green-800 dark:text-green-300";
                case "completed":
                    return "text-emerald-800 dark:text-emerald-300";
                case "rejected":
                    return "text-red-800 dark:text-red-300";
                case "canceled":
                    return "text-gray-800 dark:text-gray-300";
                default:
                    return "text-yellow-800 dark:text-yellow-300";
            }
        },

        getRefundBadgeClass(refund) {
            const status = refund?.status;

            switch (status) {
                case "pending":
                case "under_review":
                    return "text-yellow-700 dark:text-yellow-300 bg-yellow-100 dark:bg-yellow-900/40";
                case "processing":
                case "refunding":
                case "waiting_for_return":
                    return "text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/40";
                case "approved":
                    return "text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/40";
                case "completed":
                    return "text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/40";
                case "rejected":
                    return "text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/40";
                case "canceled":
                    return "text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-900/40";
                default:
                    return "text-yellow-700 dark:text-yellow-300 bg-yellow-100 dark:bg-yellow-900/40";
            }
        },

        getRefundStatusText(refund) {
            const status = refund?.status;

            switch (status) {
                case "pending":
                    return __("Pending");
                case "under_review":
                    return __("Reviewing");
                case "processing":
                    return __("Processing");
                case "approved":
                    return __("Approved");
                case "waiting_for_return":
                    return __("Returning");
                case "refunding":
                    return __("Refunding");
                case "completed":
                    return __("Completed");
                case "rejected":
                    return __("Rejected");
                case "canceled":
                    return __("Canceled");
                default:
                    return __("Requested");
            }
        },

        // Request refund action
        requestRefund(order) {
            if (order?.transaction?.refund_expired) {
                $notify.warning(__("Refund period has expired for this order"));
                return;
            }

            $notify.info(__("Refund request feature will be implemented soon"));
        },

        async getCheckouts() {
            this.loading = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.api.checkouts,
                    {
                        params: this.search,
                    }
                );
                if (res.status === 200) {
                    const values = res.data;
                    this.orders = values.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
                console.log(e);
            } finally {
                this.loading = false;
            }
        },

        formatPaymentMethod(method) {
            const methodMap = {
                offline: "Offline",
                card: "Card",
                paypal: "PayPal",
                bank_transfer: "Bank",
                stripe: "Stripe",
            };
            return methodMap[method] || method;
        },

        goTo(link) {
            router.visit(link);
        },

        openReceipt(url) {
            window.open(url, "_blank");
        },

        copyOrderId(orderCode) {
            navigator.clipboard.writeText(orderCode);
            $notify.success(__("Order ID copied to clipboard"));
        },

        copyTransactionCode(transactionCode) {
            navigator.clipboard.writeText(transactionCode);
            $notify.success(__("Transaction code copied to clipboard"));
        },
    },
};
</script>

<style scoped>
/* Optimización adicional para móviles */
@media (max-width: 640px) {
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>
