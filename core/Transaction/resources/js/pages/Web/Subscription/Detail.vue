<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
    <v-main-layout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-xs mb-4">
                    <a
                        :href="data?.links?.index"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                    >
                        {{ __("Subscriptions") }}
                    </a>
                    <svg
                        class="w-3 h-3 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                    <span
                        class="text-gray-700 dark:text-gray-300 font-medium"
                        >{{ data?.meta?.name }}</span
                    >
                </nav>

                <!-- Header Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4"
                    >
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="statusClasses"
                                >
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-1.5"
                                        :class="statusDotClass"
                                    ></span>
                                    {{ data?.status }}
                                </span>
                                <span
                                    v-if="data?.is_recurring"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300"
                                >
                                    <svg
                                        class="w-3 h-3 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    {{ __("Recurring") }}
                                </span>
                            </div>
                            <h1
                                class="text-xl font-bold text-gray-900 dark:text-white mb-1"
                            >
                                {{ data?.meta?.name }}
                            </h1>
                            <div
                                v-if="
                                    data?.meta?.description &&
                                    data.meta.description !== '<p></p>'
                                "
                                class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-2xl"
                                v-html="data.meta.description"
                            ></div>
                        </div>

                        <!-- Price Highlight -->
                        <div
                            class="flex-shrink-0 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center min-w-[120px]"
                        >
                            <p
                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                            >
                                {{ __("Price") }}
                            </p>
                            <p
                                class="text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ data?.meta?.price?.amount_format }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ data?.meta?.price?.currency }} /
                                {{ __(data?.meta?.price?.billing_period) }}
                            </p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div
                        class="grid grid-cols-3 gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700"
                    >
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __("Start Date") }}
                            </p>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ formatDate(data?.start_at) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __("End Date") }}
                            </p>
                            <p
                                class="text-sm font-medium"
                                :class="
                                    isExpired
                                        ? 'text-red-600'
                                        : 'text-green-600'
                                "
                            >
                                {{ formatDate(data?.end_at) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __("Last Renewal") }}
                            </p>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ formatDate(data?.last_renewal_at) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Included Services -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-700"
                            >
                                <h2
                                    class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2"
                                >
                                    <svg
                                        class="w-4 h-4 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        />
                                    </svg>
                                    {{ __("Included Services") }}
                                </h2>
                            </div>
                            <div class="p-4">
                                <div class="grid gap-3">
                                    <div
                                        v-for="scope in data?.meta?.scopes"
                                        :key="scope.id"
                                        class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-600/50 hover:border-blue-300 dark:hover:border-blue-700 transition-colors"
                                    >
                                        <div
                                            class="flex items-start justify-between mb-2"
                                        >
                                            <h3
                                                class="text-sm font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{ scope.role?.name }}
                                            </h3>
                                            <div class="flex gap-1">
                                                <span
                                                    v-if="scope.api_key"
                                                    class="px-1.5 py-0.5 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded"
                                                >
                                                    API
                                                </span>
                                                <span
                                                    v-if="scope.web"
                                                    class="px-1.5 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded"
                                                >
                                                    Web
                                                </span>
                                            </div>
                                        </div>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400 mb-2"
                                        >
                                            {{ scope.role?.description }}
                                        </p>
                                        <div
                                            class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500"
                                        >
                                            <span class="font-mono">{{
                                                scope.gsr_id
                                            }}</span>
                                            <span>•</span>
                                            <span>{{
                                                scope.service?.name
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction History -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-700"
                            >
                                <h2
                                    class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2"
                                >
                                    <svg
                                        class="w-4 h-4 text-purple-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        />
                                    </svg>
                                    {{ __("Transactions") }}
                                </h2>
                            </div>
                            <div
                                class="divide-y divide-gray-100 dark:divide-gray-700"
                            >
                                <div
                                    v-for="tx in data?.transactions"
                                    :key="tx.id"
                                    class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors"
                                >
                                    <div
                                        class="flex items-center justify-between mb-3"
                                    >
                                        <div class="flex items-center gap-2">
                                            <svg
                                                class="w-4 h-4"
                                                :class="
                                                    transactionIconColor(
                                                        tx.status,
                                                    )
                                                "
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    :d="
                                                        transactionIcon(
                                                            tx.status,
                                                        )
                                                    "
                                                />
                                            </svg>
                                            <span
                                                class="text-sm font-mono font-medium text-gray-900 dark:text-white"
                                                >{{ tx.code }}</span
                                            >
                                        </div>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-medium capitalize"
                                            :class="
                                                transactionBadgeClass(tx.status)
                                            "
                                        >
                                            {{ setStatus(tx.status) }}
                                        </span>
                                    </div>

                                    <div
                                        class="grid grid-cols-4 gap-3 text-xs mb-3"
                                    >
                                        <div>
                                            <span
                                                class="text-gray-400 dark:text-gray-500"
                                                >{{ __("Amount") }}</span
                                            >
                                            <p
                                                class="font-medium text-gray-900 dark:text-white"
                                            >
                                                {{ tx.total }} {{ tx.currency }}
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-gray-400 dark:text-gray-500"
                                                >{{ __("Method") }}</span
                                            >
                                            <p
                                                class="font-medium text-gray-900 dark:text-white capitalize"
                                            >
                                                {{ tx.payment_method }}
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-gray-400 dark:text-gray-500"
                                                >{{ __("Period") }}</span
                                            >
                                            <p
                                                class="font-medium text-gray-900 dark:text-white capitalize"
                                            >
                                                {{ tx.billing_period }}
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-gray-400 dark:text-gray-500"
                                                >{{ __("Date") }}</span
                                            >
                                            <p
                                                class="font-medium text-gray-900 dark:text-white"
                                            >
                                                {{ formatDate(tx.created) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <a
                                            v-if="tx.payment_url"
                                            :href="tx.payment_url"
                                            target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 mr-1.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                />
                                            </svg>
                                            {{ __("Receipt") }}
                                        </a>

                                        <v-activate
                                            :round="false"
                                            :item="tx"
                                            @updated="changeStatus"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-4">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-4"
                        >
                            <div
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-700"
                            >
                                <h3
                                    class="text-sm font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ __("Details") }}
                                </h3>
                            </div>
                            <div class="p-4 space-y-3 text-sm">
                                <div
                                    class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-700/50"
                                >
                                    <span
                                        class="text-gray-500 dark:text-gray-400"
                                        >{{ __("Code") }}</span
                                    >
                                    <span
                                        class="font-mono text-gray-900 dark:text-white text-xs"
                                        >{{
                                            data?.meta?.transaction_code
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-700/50"
                                >
                                    <span
                                        class="text-gray-500 dark:text-gray-400"
                                        >{{ __("Method") }}</span
                                    >
                                    <span
                                        class="text-gray-900 dark:text-white capitalize"
                                        >{{
                                            data?.transaction?.payment_method
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-700/50"
                                >
                                    <span
                                        class="text-gray-500 dark:text-gray-400"
                                        >{{ __("Billing") }}</span
                                    >
                                    <span
                                        class="text-gray-900 dark:text-white capitalize"
                                        >{{ __(data?.billing_period) }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-700/50"
                                >
                                    <span
                                        class="text-gray-500 dark:text-gray-400"
                                        >{{ __("Recurring") }}</span
                                    >
                                    <span
                                        class="text-xs font-medium"
                                        :class="
                                            data?.is_recurring
                                                ? 'text-green-600'
                                                : 'text-gray-400'
                                        "
                                    >
                                        {{
                                            data?.is_recurring
                                                ? __("Yes")
                                                : __("No")
                                        }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span
                                        class="text-gray-500 dark:text-gray-400"
                                        >{{ __("Total") }}</span
                                    >
                                    <span
                                        class="font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ data?.transaction?.total }}
                                        {{ data?.transaction?.currency }}
                                    </span>
                                </div>
                                <div class="flex flex-col items-center gap-4">
                                    <v-recurring-payment
                                        :item="data"
                                        @success="changeState"
                                    />

                                    <v-renew
                                        :package="data"
                                        :payment_methods="payment_methods"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { computed } from "vue";
import VMainLayout from "@/components/VMainLayout.vue";
import VRecurringPayment from "./RecurringPayment.vue";
import VRenew from "./VRenew.vue";
import VActivate from "../Transaction/VActivate.vue";

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
    payment_methods: {
        type: Object,
        default: () => ({}),
    },
    routes: {
        type: Object,
        default: () => ({}),
    },
});

const changeState = () => {
    props.data.is_recurring = !props.data.is_recurring;
};

const changeStatus = () => {
    setStatus("successful");
};

const setStatus = (status) => {
    return status;
};

// Computed
const statusClasses = computed(() => {
    const classes = {
        successful:
            "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700",
        active: "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700",
        pending:
            "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700",
        failed: "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700",
        cancelled:
            "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600",
        expired:
            "bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border border-orange-200 dark:border-orange-700",
    };
    return classes[props.data?.status] || classes.pending;
});

const statusDotClass = computed(() => {
    const dots = {
        successful: "bg-green-500",
        active: "bg-green-500",
        pending: "bg-yellow-500",
        failed: "bg-red-500",
        cancelled: "bg-gray-500",
        expired: "bg-orange-500",
    };
    return dots[props.data?.status] || "bg-gray-500";
});

const isExpired = computed(() => {
    if (!props.data?.end_at) return false;
    return new Date(props.data.end_at) < new Date();
});

// Functions
const formatDate = (dateString) => {
    if (!dateString) return "—";
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const formatDateTime = (dateString) => {
    if (!dateString) return "—";
    return new Date(dateString).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const transactionIcon = (status) => {
    const icons = {
        successful: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
        pending: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
        failed: "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z",
    };
    return icons[status] || icons.pending;
};

const transactionIconBg = (status) => {
    const bgs = {
        successful: "bg-green-100 dark:bg-green-900/30",
        pending: "bg-yellow-100 dark:bg-yellow-900/30",
        failed: "bg-red-100 dark:bg-red-900/30",
    };
    return bgs[status] || "bg-gray-100 dark:bg-gray-700";
};

const transactionIconColor = (status) => {
    const colors = {
        successful: "text-green-600 dark:text-green-400",
        pending: "text-yellow-600 dark:text-yellow-400",
        failed: "text-red-600 dark:text-red-400",
    };
    return colors[status] || "text-gray-600 dark:text-gray-400";
};

const transactionBadgeClass = (status) => {
    const classes = {
        successful:
            "bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300",
        pending:
            "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300",
        failed: "bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300",
    };
    return classes[status] || classes.pending;
};
</script>
