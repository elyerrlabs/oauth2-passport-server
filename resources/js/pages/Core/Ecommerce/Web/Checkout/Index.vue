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
                                class="text-xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("My Orders") }}
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
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
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Empty State -->
            <div
                v-if="orders.length == 0 && !loading"
                class="text-center bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 max-w-md mx-auto"
            >
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full mb-4"
                >
                    <i
                        class="fas fa-box-open text-2xl text-red-500 dark:text-red-400"
                    ></i>
                </div>
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
                >
                    {{ __("No orders found") }}
                </h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                    {{ __("You haven't placed any orders yet") }}
                </p>
                <a
                    :href="$page.props.routes.search"
                    class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200"
                >
                    <i class="fas fa-shopping-cart mr-2 text-sm"></i>
                    {{ __("Start Shopping") }}
                </a>
            </div>

            <!-- Orders List -->
            <div v-else class="space-y-4">
                <div
                    v-for="order in orders"
                    :key="order.id"
                    class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-800"
                >
                    <!-- Order Header -->
                    <div
                        class="p-4 border-b border-gray-100 dark:border-gray-600"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                        >
                            <div
                                class="flex flex-col sm:flex-row sm:items-center gap-3"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                        >
                                            {{ __("Order") }}:
                                        </span>
                                        <span
                                            class="text-sm font-bold text-gray-900 dark:text-white"
                                        >
                                            #{{ order.code }}
                                        </span>
                                    </div>
                                    <div
                                        class="w-px h-4 bg-gray-300 dark:bg-gray-600"
                                    ></div>
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                        >
                                            {{ __("Transaction") }}:
                                        </span>
                                        <span
                                            class="text-sm font-mono text-gray-900 dark:text-white"
                                        >
                                            {{ order.transaction_code }}
                                        </span>
                                    </div>
                                </div>
                                <div
                                    class="w-px h-4 bg-gray-300 dark:bg-gray-600 sm:block hidden"
                                ></div>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ order?.transaction?.created }}
                                </span>
                            </div>

                            <div class="flex items-center space-x-4">
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
                                    {{ __(order?.transaction?.status ?? "") }}
                                </span>
                                <span
                                    :class="[
                                        'text-lg font-bold text-red-600 dark:text-red-400',
                                        order?.transaction?.status == 'expired'
                                            ? 'line-through'
                                            : '',
                                    ]"
                                >
                                    {{ order?.transaction?.total }}
                                    {{ order?.transaction?.currency }}
                                </span>
                            </div>
                        </div>

                        <!-- Refund Status Banner -->
                        <div
                            v-if="order?.transaction?.refund?.id"
                            class="mt-4 p-3 rounded-lg border animate-pulse"
                            :class="
                                getRefundBannerClass(order?.transaction?.refund)
                            "
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-white/80"
                                    >
                                        <i
                                            :class="
                                                getRefundIcon(
                                                    order?.transaction?.refund
                                                )
                                            "
                                        ></i>
                                    </div>
                                    <div>
                                        <h4
                                            class="font-semibold"
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
                                <div class="flex items-center">
                                    <span
                                        class="text-sm font-medium px-3 py-1 rounded-full bg-white/80"
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

                    <!-- Order Items -->
                    <div class="p-4">
                        <div class="space-y-3">
                            <div
                                v-for="item in order.orders"
                                :key="item.id"
                                class="flex items-center space-x-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                            >
                                <img
                                    :src="item.images[0]?.url"
                                    :alt="item?.meta?.name"
                                    class="w-16 h-16 rounded border border-gray-200 dark:border-gray-600 object-cover"
                                />
                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 mb-1"
                                    >
                                        {{ item?.meta?.name }}
                                    </h3>
                                    <div
                                        class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        <span
                                            >{{ __("Qty") }}:
                                            {{ item.quantity }}</span
                                        >
                                        <span
                                            class="text-red-600 dark:text-red-400 font-bold"
                                        >
                                            {{ item.currency }}
                                            {{ item.format_price }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button
                                        @click.stop="goTo(item.web.product)"
                                        class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200 cursor-pointer"
                                    >
                                        {{ __("Buy Again") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Footer -->
                    <div
                        class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600"
                    >
                        <div
                            class="flex flex-wrap items-center justify-between gap-3"
                        >
                            <div
                                class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400"
                            >
                                <span class="flex items-center">
                                    <i
                                        class="fas fa-credit-card mr-1 text-xs"
                                    ></i>
                                    {{
                                        formatPaymentMethod(
                                            order?.transaction?.payment_method
                                        )
                                    }}
                                </span>
                                <span class="flex items-center">
                                    <i
                                        class="fas fa-location-dot mr-1 text-xs"
                                    ></i>
                                    {{ order?.delivery_address?.city }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    v-if="order.transaction.payment_url"
                                    @click.stop="
                                        openReceipt(
                                            order.transaction.payment_url
                                        )
                                    "
                                    :class="[
                                        'px-3 py-1.5 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-xs font-medium rounded   transition-colors duration-200 cursor-pointer',
                                        setStatusColor(
                                            order.transaction.status
                                        ),
                                    ]"
                                >
                                    <i class="fas fa-receipt mr-1"></i>
                                    {{ setStatus(order.transaction.status) }}
                                </button>
                                <button
                                    @click.stop="copyOrderId(order.code)"
                                    class="px-3 py-1.5 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-xs font-medium rounded hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer"
                                >
                                    <i class="fas fa-copy mr-1"></i>
                                    {{ __("Copy Order ID") }}
                                </button>
                                <button
                                    v-if="order.transaction_code"
                                    @click.stop="
                                        copyTransactionCode(
                                            order.transaction_code
                                        )
                                    "
                                    class="px-3 py-1.5 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-xs font-medium rounded hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200 cursor-pointer"
                                >
                                    <i class="fas fa-copy mr-1"></i>
                                    {{ __("Copy Transaction") }}
                                </button>

                                <button
                                    @click.stop="goTo(order.web.show)"
                                    class="px-3 py-1.5 bg-blue-700 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-100 dark:text-gray-300 text-xs font-medium rounded hover:bg-blue-800 dark:hover:bg-blue-500 transition-colors duration-200 cursor-pointer"
                                >
                                    {{ __("Details") }}
                                </button>
                            </div>
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
                    class="w-12 h-12 border-4 border-red-200 dark:border-red-800 border-t-red-500 dark:border-t-red-400 rounded-full animate-spin mb-3"
                ></div>
                <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">
                    {{ __("Loading orders...") }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import VHeader from "../../Components/VHeader.vue";
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

        setStatus(status) {
            switch (status) {
                case "pending":
                    return __("Pay Now");
                case "expired":
                    return __("Expired");
                case "successful":
                    return __("View Receipt");
                default:
                    break;
            }
        },

        setStatusColor(status) {
            switch (status) {
                case "pending":
                    return "bg-yellow-500 text-white";
                case "expired":
                    return "bg-red-500 text-white";
                case "successful":
                    return "bg-green-500 text-white";
                default:
                    break;
            }
        },

        // Refund related methods
        formatRefundStatus(status) {
            const statusMap = {
                pending: __("Pending"),
                processing: __("Processing"),
                under_review: __("Under Review"),
                approved: __("Approved"),
                waiting_for_return: __("Waiting Return"),
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
                        "fa-clock text-yellow-600 dark:text-yellow-400";
                    break;
                case "processing":
                case "refunding":
                    iconClass +=
                        "fa-spinner fa-spin text-blue-600 dark:text-blue-400";
                    break;
                case "waiting_for_return":
                    iconClass +=
                        "fa-truck-arrow-rotate-right text-blue-600 dark:text-blue-400";
                    break;
                case "approved":
                    iconClass +=
                        "fa-check-circle text-green-600 dark:text-green-400";
                    break;
                case "completed":
                    iconClass +=
                        "fa-circle-check text-emerald-600 dark:text-emerald-400";
                    break;
                case "rejected":
                    iconClass +=
                        "fa-circle-xmark text-red-600 dark:text-red-400";
                    break;
                case "canceled":
                    iconClass += "fa-ban text-gray-600 dark:text-gray-400";
                    break;
                default:
                    iconClass +=
                        "fa-rotate-left text-yellow-600 dark:text-yellow-400";
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
                    return __("Pending Review");
                case "under_review":
                    return __("Under Review");
                case "processing":
                    return __("Processing Request");
                case "approved":
                    return __("Approved");
                case "waiting_for_return":
                    return __("Waiting for Item Return");
                case "refunding":
                    return __("Processing Payment Refund");
                case "completed":
                    return __("Successfully Completed");
                case "rejected":
                    return __("Request Rejected");
                case "canceled":
                    return __("Canceled");
                default:
                    return __("Requested");
            }
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
                    //this.pages = values.meta.pagination;
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

        openOrderDetails(order) {},

        formatCompactDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
            });
        },

        formatFullDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        },

        formatPaymentMethod(method) {
            const methodMap = {
                offline: "Offline",
                card: "Credit Card",
                paypal: "PayPal",
                bank_transfer: "Bank Transfer",
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
