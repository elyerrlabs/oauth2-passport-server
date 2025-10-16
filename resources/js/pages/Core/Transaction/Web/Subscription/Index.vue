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
    <v-account-layout>
        <div class="min-h-screen bg-gray-50 py-6">
            <!-- Header Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center"
                            >
                                <svg
                                    class="w-8 h-8 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">
                                    {{ __("Subscription Packages") }}
                                </h1>
                                <p class="text-lg text-gray-600 mt-1">
                                    {{
                                        __(
                                            "Manage your subscription packages and billing information"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="getPackages"
                            :disabled="loading"
                            class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                />
                            </svg>
                            {{ __("Refresh") }}
                        </button>
                    </div>
                </div>

                <!-- Packages Table -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                >
                    <!-- Loading State -->
                    <div
                        v-if="loading"
                        class="flex items-center justify-center py-12"
                    >
                        <div
                            class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
                        ></div>
                    </div>

                    <!-- Table Content -->
                    <div v-else>
                        <!-- Table Header -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            v-for="(column, index) in columns"
                                            :key="index"
                                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            {{ column }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <!-- Empty State -->
                                    <tr v-if="packages.length === 0">
                                        <td
                                            :colspan="columns.length"
                                            class="px-6 py-24 text-center"
                                        >
                                            <div
                                                class="flex flex-col items-center"
                                            >
                                                <svg
                                                    class="w-16 h-16 text-gray-300 mb-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                    />
                                                </svg>
                                                <h3
                                                    class="text-lg font-medium text-gray-700 mb-2"
                                                >
                                                    {{
                                                        __("No packages found")
                                                    }}
                                                </h3>
                                                <p class="text-gray-500 mb-6">
                                                    {{
                                                        __(
                                                            "Your subscription packages will appear here"
                                                        )
                                                    }}
                                                </p>
                                                <a
                                                    v-if="
                                                        $page?.props?.routes?.plans
                                                    "
                                                    :href="route('plans.index')"
                                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                                >
                                                    {{ __("Browse Plans") }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Packages Rows -->
                                    <tr
                                        v-for="pkg in packages"
                                        :key="pkg.id"
                                        class="hover:bg-gray-50 transition-colors duration-150"
                                    >
                                        <!-- Name Column -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-lg font-bold text-blue-600"
                                                >
                                                    {{ pkg.meta.name }}
                                                </span>
                                                <span
                                                    class="text-sm text-gray-500 flex items-center mt-1"
                                                >
                                                    <svg
                                                        class="w-4 h-4 mr-1"
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
                                                    {{
                                                        pkg.transaction
                                                            .billing_period_name
                                                    }}
                                                    {{ __("plan") }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Price Column -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-lg font-bold text-gray-900"
                                                >
                                                    {{
                                                        pkg.transaction.currency
                                                    }}
                                                    {{ pkg.transaction.total }}
                                                </span>
                                                <span
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ __(pkg.billing_period) }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Bonus Column -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            <div
                                                v-if="pkg.meta.bonus_enabled"
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-800"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"
                                                    />
                                                </svg>
                                                <span
                                                    class="text-sm font-medium"
                                                >
                                                    +{{
                                                        pkg.meta.bonus_duration
                                                    }}
                                                    {{ __("days free") }}
                                                </span>
                                            </div>
                                            <span v-else class="text-gray-400"
                                                >—</span
                                            >
                                        </td>

                                        <!-- Start Date Column -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-xs text-gray-500"
                                                    >{{ __("Started") }}</span
                                                >
                                                <span
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{
                                                        formatDate(pkg.start_at)
                                                    }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- End Date Column -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-xs text-gray-500"
                                                    >{{ __("Expires") }}</span
                                                >
                                                <span
                                                    class="text-sm font-medium"
                                                    :class="{
                                                        'text-green-600':
                                                            isDateFuture(
                                                                pkg.end_at
                                                            ),
                                                        'text-red-600':
                                                            !isDateFuture(
                                                                pkg.end_at
                                                            ),
                                                    }"
                                                >
                                                    {{ formatDate(pkg.end_at) }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Payment Method Column -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        v-if="
                                                            getPaymentMethodIcon(
                                                                pkg.transaction
                                                                    .payment_method
                                                            ) === 'credit_card'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                                    />
                                                    <path
                                                        v-else-if="
                                                            getPaymentMethodIcon(
                                                                pkg.transaction
                                                                    .payment_method
                                                            ) === 'paypal'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                                    />
                                                    <path
                                                        v-else
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                                    />
                                                </svg>
                                                {{
                                                    pkg.transaction
                                                        .payment_method
                                                }}
                                            </span>
                                        </td>

                                        <!-- Recurring Column -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border"
                                                :class="{
                                                    'bg-green-100 text-green-800 border-green-200':
                                                        pkg.is_recurring,
                                                    'bg-red-100 text-red-800 border-red-200':
                                                        !pkg.is_recurring,
                                                }"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        v-if="pkg.is_recurring"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                                    />
                                                    <path
                                                        v-else
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>
                                                {{
                                                    pkg.is_recurring
                                                        ? __("Active")
                                                        : __("Inactive")
                                                }}
                                            </span>
                                        </td>

                                        <!-- Status Column -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                                :class="
                                                    getStatusClasses(pkg.status)
                                                "
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        v-if="
                                                            getStatusIcon(
                                                                pkg.status
                                                            ) === 'success'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                    <path
                                                        v-else-if="
                                                            getStatusIcon(
                                                                pkg.status
                                                            ) === 'pending'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                    <path
                                                        v-else
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                {{ pkg.status }}
                                            </span>
                                        </td>

                                        <!-- Actions Column -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center"
                                        >
                                            <div
                                                class="flex items-center justify-center space-x-2"
                                            >
                                                <v-recurring-payment
                                                    :item="pkg"
                                                    @success="getPackages"
                                                />
                                                <a
                                                    :href="pkg.links.show"
                                                    class="inline-flex items-center px-3 py-2 border border-blue-600 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                                >
                                                    <svg
                                                        class="w-4 h-4 mr-1"
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
                                                    {{ __("View") }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div
                            v-if="packages.length > 0"
                            class="bg-white px-6 py-4 border-t border-gray-200"
                        >
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    {{ __("Showing page") }} {{ search.page }}
                                    {{ __("of") }} {{ pages.total_pages }}
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        @click="
                                            search.page = Math.max(
                                                1,
                                                search.page - 1
                                            )
                                        "
                                        :disabled="search.page <= 1"
                                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    >
                                        {{ __("Previous") }}
                                    </button>
                                    <button
                                        @click="
                                            search.page = Math.min(
                                                pages.total_pages,
                                                search.page + 1
                                            )
                                        "
                                        :disabled="
                                            search.page >= pages.total_pages
                                        "
                                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    >
                                        {{ __("Next") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script>
import VRecurringPayment from "./RecurringPayment.vue";
import VAccountLayout from "@/layouts/VAccountLayout.vue";

export default {
    components: {
        VRecurringPayment,
        VAccountLayout,
    },

    data() {
        return {
            loading: false,
            user: [],
            packages: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                "Package",
                "Price",
                "Bonus",
                "Start Date",
                "End Date",
                "Payment Method",
                "Recurring",
                "Status",
                "Actions",
            ],
        };
    },

    created() {
        this.user = this.$page.props.user;
        this.getPackages();
    },

    methods: {
        async getPackages() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.packages = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            if (!dateString) return "N/A";
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        isDateFuture(dateString) {
            if (!dateString) return false;
            return new Date(dateString) > new Date();
        },

        getStatusClasses(status) {
            const statusClasses = {
                successful: "bg-green-100 text-green-800",
                active: "bg-green-100 text-green-800",
                pending: "bg-yellow-100 text-yellow-800",
                failed: "bg-red-100 text-red-800",
                cancelled: "bg-gray-100 text-gray-800",
                expired: "bg-orange-100 text-orange-800",
            };
            return statusClasses[status] || "bg-gray-100 text-gray-800";
        },

        getStatusIcon(status) {
            const statusIcons = {
                successful: "success",
                active: "success",
                pending: "pending",
                failed: "error",
                cancelled: "error",
                expired: "error",
            };
            return statusIcons[status] || "error";
        },

        getPaymentMethodIcon(method) {
            const methodIcons = {
                credit_card: "credit_card",
                paypal: "paypal",
                stripe: "credit_card",
                bank_transfer: "credit_card",
                crypto: "credit_card",
                default: "credit_card",
            };
            return methodIcons[method?.toLowerCase()] || methodIcons.default;
        },
    },
};
</script>

<style scoped>
@media (max-width: 768px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
}
</style>
