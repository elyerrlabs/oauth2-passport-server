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
    <v-admin-transaction-layout>
        <!-- Header Section -->
        <div class="my-2">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __("Refund Management") }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __("Manage and review refund requests") }}
            </p>
        </div>

        <!-- Search and Filters -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-4">
            <v-input
                :label="__('Transaction code')"
                v-model="search.code"
                @input="getRefunds"
            />

            <v-input
                :label="__('Email')"
                v-model="search.email"
                @input="getRefunds"
            />

            <v-select
                :label="__('All Status')"
                v-model="search.status"
                :options="statusOptions"
                clearable
                @change="getRefunds"
            />
        </div>

        <!-- Mobile/XS View (Cards) - Mejor Distribución -->
        <div class="block md:hidden space-y-3">
            <div
                v-for="refund in refunds"
                :key="refund.id"
                class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow"
            >
                <!-- Header Row -->
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div
                            class="font-bold text-gray-900 dark:text-white text-base"
                        >
                            {{ refund.transaction?.owner?.name }}
                        </div>
                        <div
                            class="text-xs text-blue-600 dark:text-blue-400 font-medium"
                        >
                            {{ refund.transaction?.code }}
                        </div>
                    </div>
                    <div
                        :class="`px-3 py-1 rounded-full text-xs font-bold ${getStatusColorClass(
                            refund.status
                        )}`"
                    >
                        {{ __(refund.status) }}
                    </div>
                </div>

                <!-- Main Info Grid -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <!-- Amount Column -->
                    <div class="space-y-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __("Refund Amount") }}
                        </div>
                        <div
                            class="text-lg font-bold text-green-600 dark:text-green-400"
                        >
                            {{ refund.transaction?.refund?.amount }}
                            {{ refund.currency }}
                        </div>
                    </div>

                    <!-- Date Column -->
                    <div class="space-y-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __("Date") }}
                        </div>
                        <div
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                        >
                            {{ refund.transaction?.created }}
                        </div>
                    </div>
                </div>

                <!-- Reason Section -->
                <div class="mb-3">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        {{ __("Reason") }}
                    </div>
                    <p
                        class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2 bg-gray-50 dark:bg-gray-900/30 p-2 rounded"
                    >
                        {{ refund.reason }}
                    </p>
                </div>

                <!-- Footer Row -->
                <div
                    class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700"
                >
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ refund.transaction?.payment_method }}
                    </div>
                    <a
                        :href="refund.links?.update"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors"
                    >
                        {{ __("Details") }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Tablet/MD View (Grid) - Mejor Distribución -->
        <div class="hidden md:block lg:hidden">
            <div class="grid grid-cols-1 gap-3">
                <div
                    v-for="refund in refunds"
                    :key="refund.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow"
                >
                    <!-- Top Section: Customer & Status -->
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ refund.transaction?.owner?.name }}
                            </div>
                            <div
                                class="text-sm text-blue-600 dark:text-blue-400"
                            >
                                {{ refund.transaction?.owner?.email }}
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                :class="`px-3 py-1 rounded-lg text-sm font-bold ${getStatusColorClass(
                                    refund.status
                                )}`"
                            >
                                {{ __(refund.status) }}
                            </div>
                        </div>
                    </div>

                    <!-- Main Info Grid -->
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <!-- Transaction ID -->
                        <div class="col-span-2">
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                            >
                                {{ __("Transaction ID") }}
                            </div>
                            <div
                                class="text-sm font-mono text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/30 p-2 rounded"
                            >
                                {{ refund.transaction?.code }}
                            </div>
                        </div>

                        <!-- Refund Amount -->
                        <div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                            >
                                {{ __("Refund") }}
                            </div>
                            <div
                                class="text-lg font-bold text-green-600 dark:text-green-400"
                            >
                                {{ refund.transaction?.refund?.amount }}<br />
                                <span class="text-xs">{{
                                    refund.currency
                                }}</span>
                            </div>
                        </div>

                        <!-- Original Amount -->
                        <div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                            >
                                {{ __("Original") }}
                            </div>
                            <div
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                            >
                                {{ refund.transaction?.total }}
                            </div>
                        </div>
                    </div>

                    <!-- Reason Section -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Reason") }}
                            </div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{ refund.transaction?.created }}
                            </div>
                        </div>
                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30 p-3 rounded"
                        >
                            {{ refund.reason }}
                        </p>
                    </div>

                    <!-- Footer: Payment Method & Action -->
                    <div
                        class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700"
                    >
                        <div class="flex items-center gap-2">
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ refund.transaction?.payment_method }}
                            </span>
                        </div>
                        <a
                            :href="refund.links?.update"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors"
                        >
                            {{ __("Review Details") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop/LG View (Table)   -->
        <div class="hidden lg:block">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/4"
                                >
                                    {{ __("Customer & Transaction") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/4"
                                >
                                    {{ __("Refund Information") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/4"
                                >
                                    {{ __("Amount & Status") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-1/4"
                                >
                                    {{ __("Action") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <tr
                                v-for="refund in refunds"
                                :key="refund.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors"
                            >
                                <!-- Customer & Transaction -->
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <!-- Customer Info -->
                                        <div>
                                            <div
                                                class="text-sm font-bold text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    refund.transaction?.owner
                                                        ?.name
                                                }}
                                            </div>
                                            <div
                                                class="text-xs text-blue-600 dark:text-blue-400"
                                            >
                                                {{
                                                    refund.transaction?.owner
                                                        ?.email
                                                }}
                                            </div>
                                        </div>

                                        <!-- Transaction Info -->
                                        <div
                                            class="pt-2 border-t border-gray-100 dark:border-gray-700"
                                        >
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                            >
                                                {{ __("Transaction ID") }}
                                            </div>
                                            <div
                                                class="text-sm font-mono text-gray-700 dark:text-gray-300"
                                            >
                                                {{ refund.transaction?.code }}
                                            </div>
                                        </div>

                                        <!-- Payment Method -->
                                        <div
                                            class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            <svg
                                                class="w-3 h-3"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{
                                                refund.transaction
                                                    ?.payment_method
                                            }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Refund Information -->
                                <td class="px-6 py-4">
                                    <div class="space-y-3">
                                        <!-- Reason -->
                                        <div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                            >
                                                {{ __("Reason") }}
                                            </div>
                                            <p
                                                class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2"
                                            >
                                                {{ refund.reason }}
                                            </p>
                                        </div>

                                        <!-- Date -->
                                        <div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                            >
                                                {{ __("Transaction Date") }}
                                            </div>
                                            <div
                                                class="text-sm text-gray-700 dark:text-gray-300"
                                            >
                                                {{
                                                    refund.transaction?.created
                                                }}
                                            </div>
                                        </div>

                                        <!-- Original Amount -->
                                        <div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                            >
                                                {{ __("Original Amount") }}
                                            </div>
                                            <div
                                                class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            >
                                                {{ refund.transaction?.total }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Amount & Status -->
                                <td class="px-6 py-4">
                                    <div class="space-y-4">
                                        <!-- Refund Amount -->
                                        <div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                            >
                                                {{ __("Refund Amount") }}
                                            </div>
                                            <div
                                                class="text-xl font-bold text-green-600 dark:text-green-400"
                                            >
                                                {{
                                                    refund.transaction?.refund
                                                        ?.amount
                                                }}
                                                <span class="text-sm">{{
                                                    refund.currency
                                                }}</span>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div>
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mb-2"
                                            >
                                                {{ __("Status") }}
                                            </div>
                                            <div
                                                :class="`inline-flex items-center px-4 py-2 rounded-lg font-bold ${getStatusColorClass(
                                                    refund.status
                                                )}`"
                                            >
                                                {{ __(refund.status) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4">
                                    <div
                                        class="flex flex-col items-center justify-center h-full"
                                    >
                                        <a
                                            :href="refund.web?.show"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors shadow-sm hover:shadow"
                                        >
                                            {{ __("View Details") }}
                                        </a>
                                        <div
                                            class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center"
                                        >
                                            {{ __("Click to review") }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="refunds.length === 0" class="text-center py-12">
            <div class="mb-4">
                <svg
                    class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
            </div>
            <h3
                class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2"
            >
                {{ __("No refunds found") }}
            </h3>
            <p class="text-gray-500 dark:text-gray-400">
                {{ __("No refund requests match your search criteria") }}
            </p>
        </div>

        <!-- Pagination -->
        <div v-if="refunds.length > 0" class="mt-6">
            <v-pagination
                v-model="search.page"
                :total-pages="pages.total_pages"
                @update:model-value="getRefunds"
            />
        </div>
    </v-admin-transaction-layout>
</template>

<script setup>
import VAdminTransactionLayout from "@/components/VGeneralLayout.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const page = usePage();
const refunds = ref([]);

const search = useForm({
    page: 1,
    per_page: 15,
    code: "",
    email: "",
    status: "",
});

const pages = ref({
    total_pages: 0,
});

const statusOptions = [
    { id: "pending", name: __("Pending") },
    { id: "processing", name: __("processing") },
    { id: "under_review", name: __("under_review") },
    { id: "approved", name: __("approved") },
    { id: "waiting_for_return", name: __("waiting_for_return") },
    { id: "refunding", name: __("refunding") },
    { id: "completed", name: __("completed") },
    { id: "rejected", name: __("rejected") },
    { id: "canceled", name: __("canceled") },
];

onMounted(() => {
    const values = page.props.data;
    refunds.value = values.data;
    pages.value = values.meta.pagination;
});

// Methods
const getRefunds = () => {
    search.get(page.props.routes.index, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            const values = page.props.data;
            refunds.value = values.data;
            pages.value = values.meta.pagination;
        },
    });
};

const getStatusColorClass = (status) => {
    const colors = {
        pending:
            "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300",
        refunding:
            "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300",
        completed:
            "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300",
        rejected:
            "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300",
    };
    return (
        colors[status] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300"
    );
};
</script>
