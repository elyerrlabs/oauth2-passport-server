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
        <!-- Header Section -->
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
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ __("Subscription Packages") }}
                        </h1>
                        <p class="text-gray-600 mt-1">
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

        <!-- Packages Content -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <!-- Loading State -->
            <div v-if="loading" class="flex items-center justify-center py-12">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
                ></div>
            </div>

            <!-- Content when not loading -->
            <div v-else>
                <!-- Empty State -->
                <div
                    v-if="packages.length === 0"
                    class="px-6 py-24 text-center"
                >
                    <div class="flex flex-col items-center">
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
                        <h3 class="text-lg font-medium text-gray-700 mb-2">
                            {{ __("No packages found") }}
                        </h3>
                        <p class="text-gray-500 mb-6">
                            {{
                                __(
                                    "Your subscription packages will appear here"
                                )
                            }}
                        </p>
                        <a
                            v-if="$page?.props?.routes?.plans"
                            :href="route('plans.index')"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                        >
                            {{ __("Browse Plans") }}
                        </a>
                    </div>
                </div>

                <!-- Packages when not empty -->
                <div v-else>
                    <!-- Mobile View - Cards -->
                    <div class="lg:hidden">
                        <div class="divide-y divide-gray-200">
                            <div
                                v-for="pkg in packages"
                                :key="pkg.id"
                                class="p-4 hover:bg-gray-50 transition-colors duration-150"
                            >
                                <!-- Card Header -->
                                <div
                                    class="flex justify-between items-start mb-3"
                                >
                                    <div class="flex-1">
                                        <h3 class="font-bold text-blue-600">
                                            {{ pkg.meta.name }}
                                        </h3>
                                        <p
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
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                        :class="getStatusClasses(pkg.status)"
                                    >
                                        {{ pkg.status }}
                                    </span>
                                </div>

                                <!-- Price and Basic Info -->
                                <div class="grid grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            {{ __("Price") }}
                                        </p>
                                        <p class="font-bold text-gray-900">
                                            {{ pkg.transaction.currency }}
                                            {{ pkg.transaction.total }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ __(pkg.billing_period) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            {{ __("Payment") }}
                                        </p>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs"
                                        >
                                            {{ pkg.transaction.payment_method }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Dates -->
                                <div class="grid grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            {{ __("Start Date") }}
                                        </p>
                                        <p class="font-medium">
                                            {{ formatDate(pkg.start_at) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            {{ __("End Date") }}
                                        </p>
                                        <p
                                            class="font-medium"
                                            :class="{
                                                'text-green-600': isDateFuture(
                                                    pkg.end_at
                                                ),
                                                'text-red-600': !isDateFuture(
                                                    pkg.end_at
                                                ),
                                            }"
                                        >
                                            {{ formatDate(pkg.end_at) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Expandable Section -->
                                <div>
                                    <button
                                        @click="toggleExpanded(pkg.id)"
                                        class="w-full flex items-center justify-between py-2 text-sm text-blue-600 hover:text-blue-800 focus:outline-none"
                                    >
                                        <span>{{ __("View Details") }}</span>
                                        <svg
                                            class="w-4 h-4 transition-transform duration-200"
                                            :class="{
                                                'rotate-180':
                                                    expandedItems[pkg.id],
                                            }"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 9l-7 7-7-7"
                                            />
                                        </svg>
                                    </button>

                                    <div
                                        v-if="expandedItems[pkg.id]"
                                        class="mt-3 pt-3 border-t border-gray-200 space-y-3"
                                    >
                                        <!-- Bonus Information -->
                                        <div
                                            v-if="pkg.meta.bonus_enabled"
                                            class="flex items-center justify-between"
                                        >
                                            <span
                                                class="text-sm text-gray-600"
                                                >{{ __("Bonus Days") }}</span
                                            >
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full bg-orange-100 text-orange-800 text-xs"
                                            >
                                                +{{ pkg.meta.bonus_duration }}
                                                {{ __("days free") }}
                                            </span>
                                        </div>

                                        <!-- Recurring Status -->
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <span
                                                class="text-sm text-gray-600"
                                                >{{ __("Recurring") }}</span
                                            >
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs border"
                                                :class="{
                                                    'bg-green-100 text-green-800 border-green-200':
                                                        pkg.is_recurring,
                                                    'bg-red-100 text-red-800 border-red-200':
                                                        !pkg.is_recurring,
                                                }"
                                            >
                                                {{
                                                    pkg.is_recurring
                                                        ? __("Active")
                                                        : __("Inactive")
                                                }}
                                            </span>
                                        </div>

                                        <!-- Transaction Code -->
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <span
                                                class="text-sm text-gray-600"
                                                >{{ __("Transaction") }}</span
                                            >
                                            <span
                                                class="text-sm font-mono text-gray-900"
                                            >
                                                {{ pkg.meta.transaction_code }}
                                            </span>
                                        </div>

                                        <!-- Last Renewal -->
                                        <div
                                            v-if="pkg.last_renewal_at"
                                            class="flex items-center justify-between"
                                        >
                                            <span
                                                class="text-sm text-gray-600"
                                                >{{ __("Last Renewal") }}</span
                                            >
                                            <span class="text-sm text-gray-900">
                                                {{
                                                    formatDate(
                                                        pkg.last_renewal_at
                                                    )
                                                }}
                                            </span>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex space-x-2 pt-2">
                                            <v-recurring-payment
                                                :item="pkg"
                                                @success="getPackages"
                                            />
                                            <a
                                                :href="pkg.links.show"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-blue-600 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop View - Table -->
                    <div class="hidden lg:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            v-for="(
                                                column, index
                                            ) in visibleColumns"
                                            :key="index"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            {{ column }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            {{ __("Actions") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <template
                                        v-for="pkg in packages"
                                        :key="pkg.id"
                                    >
                                        <tr
                                            class="hover:bg-gray-50 transition-colors duration-150"
                                        >
                                            <!-- Name Column -->
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-bold text-blue-600"
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
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-bold text-gray-900"
                                                    >
                                                        {{
                                                            pkg.transaction
                                                                .currency
                                                        }}
                                                        {{
                                                            pkg.transaction
                                                                .total
                                                        }}
                                                    </span>
                                                    <span
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{
                                                            __(
                                                                pkg.billing_period
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Bonus Column -->
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-center"
                                            >
                                                <div
                                                    v-if="
                                                        pkg.meta.bonus_enabled
                                                    "
                                                    class="inline-flex items-center px-2 py-1 rounded-full bg-orange-100 text-orange-800 text-xs"
                                                >
                                                    <svg
                                                        class="w-3 h-3 mr-1"
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
                                                    <span class="font-medium">
                                                        +{{
                                                            pkg.meta
                                                                .bonus_duration
                                                        }}
                                                        {{ __("days") }}
                                                    </span>
                                                </div>
                                                <span
                                                    v-else
                                                    class="text-gray-400 text-sm"
                                                    >—</span
                                                >
                                            </td>

                                            <!-- Start Date Column -->
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-xs text-gray-500"
                                                        >{{
                                                            __("Started")
                                                        }}</span
                                                    >
                                                    <span
                                                        class="font-medium text-gray-900"
                                                    >
                                                        {{
                                                            formatDate(
                                                                pkg.start_at
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- End Date Column -->
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-xs text-gray-500"
                                                        >{{
                                                            __("Expires")
                                                        }}</span
                                                    >
                                                    <span
                                                        class="font-medium"
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
                                                        {{
                                                            formatDate(
                                                                pkg.end_at
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Actions Column -->
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-center"
                                            >
                                                <div
                                                    class="flex items-center justify-center space-x-2"
                                                >
                                                    <button
                                                        @click="
                                                            toggleExpanded(
                                                                pkg.id
                                                            )
                                                        "
                                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                                    >
                                                        <svg
                                                            class="w-4 h-4 mr-1 transition-transform duration-200"
                                                            :class="{
                                                                'rotate-180':
                                                                    expandedItems[
                                                                        pkg.id
                                                                    ],
                                                            }"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 9l-7 7-7-7"
                                                            />
                                                        </svg>
                                                        {{
                                                            expandedItems[
                                                                pkg.id
                                                            ]
                                                                ? __("Less")
                                                                : __("More")
                                                        }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Expanded Row Details -->
                                        <tr v-if="expandedItems[pkg.id]">
                                            <td
                                                colspan="6"
                                                class="px-6 py-4 bg-gray-50"
                                            >
                                                <div
                                                    class="grid grid-cols-2 gap-6"
                                                >
                                                    <!-- Left Column -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-900 mb-2"
                                                            >
                                                                {{
                                                                    __(
                                                                        "Payment Details"
                                                                    )
                                                                }}
                                                            </h4>
                                                            <div
                                                                class="space-y-2"
                                                            >
                                                                <div
                                                                    class="flex justify-between"
                                                                >
                                                                    <span
                                                                        class="text-sm text-gray-600"
                                                                        >{{
                                                                            __(
                                                                                "Payment Method"
                                                                            )
                                                                        }}</span
                                                                    >
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-sm"
                                                                    >
                                                                        {{
                                                                            pkg
                                                                                .transaction
                                                                                .payment_method
                                                                        }}
                                                                    </span>
                                                                </div>
                                                                <div
                                                                    class="flex justify-between"
                                                                >
                                                                    <span
                                                                        class="text-sm text-gray-600"
                                                                        >{{
                                                                            __(
                                                                                "Recurring"
                                                                            )
                                                                        }}</span
                                                                    >
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-full text-sm border"
                                                                        :class="{
                                                                            'bg-green-100 text-green-800 border-green-200':
                                                                                pkg.is_recurring,
                                                                            'bg-red-100 text-red-800 border-red-200':
                                                                                !pkg.is_recurring,
                                                                        }"
                                                                    >
                                                                        {{
                                                                            pkg.is_recurring
                                                                                ? __(
                                                                                      "Active"
                                                                                  )
                                                                                : __(
                                                                                      "Inactive"
                                                                                  )
                                                                        }}
                                                                    </span>
                                                                </div>
                                                                <div
                                                                    class="flex justify-between"
                                                                >
                                                                    <span
                                                                        class="text-sm text-gray-600"
                                                                        >{{
                                                                            __(
                                                                                "Status"
                                                                            )
                                                                        }}</span
                                                                    >
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium"
                                                                        :class="
                                                                            getStatusClasses(
                                                                                pkg.status
                                                                            )
                                                                        "
                                                                    >
                                                                        {{
                                                                            pkg.status
                                                                        }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Right Column -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <h4
                                                                class="font-medium text-gray-900 mb-2"
                                                            >
                                                                {{
                                                                    __(
                                                                        "Additional Information"
                                                                    )
                                                                }}
                                                            </h4>
                                                            <div
                                                                class="space-y-2"
                                                            >
                                                                <div
                                                                    v-if="
                                                                        pkg.last_renewal_at
                                                                    "
                                                                    class="flex justify-between"
                                                                >
                                                                    <span
                                                                        class="text-sm text-gray-600"
                                                                        >{{
                                                                            __(
                                                                                "Last Renewal"
                                                                            )
                                                                        }}</span
                                                                    >
                                                                    <span
                                                                        class="text-sm text-gray-900"
                                                                    >
                                                                        {{
                                                                            formatDate(
                                                                                pkg.last_renewal_at
                                                                            )
                                                                        }}
                                                                    </span>
                                                                </div>
                                                                <div
                                                                    class="flex justify-between"
                                                                >
                                                                    <span
                                                                        class="text-sm text-gray-600"
                                                                        >{{
                                                                            __(
                                                                                "Transaction Code"
                                                                            )
                                                                        }}</span
                                                                    >
                                                                    <span
                                                                        class="text-sm font-mono text-gray-900"
                                                                    >
                                                                        {{
                                                                            pkg
                                                                                .meta
                                                                                .transaction_code
                                                                        }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Actions -->
                                                        <div
                                                            class="flex space-x-2 pt-2"
                                                        >
                                                            <v-recurring-payment
                                                                :item="pkg"
                                                                @success="
                                                                    getPackages
                                                                "
                                                            />
                                                            <a
                                                                :href="
                                                                    pkg.links
                                                                        .show
                                                                "
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
                                                                {{
                                                                    __(
                                                                        "View Details"
                                                                    )
                                                                }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <v-paginate
                    v-if="pages.total_pages > 1"
                    v-model="search.page"
                    :total-pages="pages.total_pages"
                    @change="getPackages"
                />
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";
import VRecurringPayment from "./RecurringPayment.vue";
import VAccountLayout from "@/layouts/VAccountLayout.vue";
import VPaginate from "@/components/VPaginate.vue";

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    route: String,
});

const loading = ref(false);
const packages = ref([]);
const pages = ref({
    total_pages: 0,
});
const expandedItems = ref({});

const search = useForm({
    page: 1,
    per_page: 15,
    code: "",
});

const visibleColumns = reactive([
    "Package",
    "Price",
    "Bonus",
    "Start Date",
    "End Date",
]);

onMounted(() => {
    const values = props.data;
    packages.value = values.data;
    search.value = values.meta;
});

// functions
const getPackages = () => {
    loading.value = true;

    search.get(props.route, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            const values = page.props.data;
            packages.value = values.data;
            pages.value = values.meta.pagination;
            // Reset expanded items when data changes
            expandedItems.value = {};
        },
        onError: (e) => {
            if (e?.response?.data?.message) {
                $notify.error(e.response.data.message);
            }
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const toggleExpanded = (packageId) => {
    expandedItems.value[packageId] = !expandedItems.value[packageId];
};

const formatDate = (dateString) => {
    if (!dateString) {
        return "N/A";
    }
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const isDateFuture = (dateString) => {
    if (!dateString) return false;
    return new Date(dateString) > new Date();
};

const getStatusClasses = (status) => {
    const statusClasses = {
        successful: "bg-green-100 text-green-800",
        active: "bg-green-100 text-green-800",
        pending: "bg-yellow-100 text-yellow-800",
        failed: "bg-red-100 text-red-800",
        cancelled: "bg-gray-100 text-gray-800",
        expired: "bg-orange-100 text-orange-800",
    };
    return statusClasses[status] || "bg-gray-100 text-gray-800";
};

const getStatusIcon = (status) => {
    const statusIcons = {
        successful: "success",
        active: "success",
        pending: "pending",
        failed: "error",
        cancelled: "error",
        expired: "error",
    };
    return statusIcons[status] || "error";
};

const getPaymentMethodIcon = (method) => {
    const methodIcons = {
        credit_card: "credit_card",
        paypal: "paypal",
        stripe: "credit_card",
        bank_transfer: "credit_card",
        crypto: "credit_card",
        default: "credit_card",
    };
    return methodIcons[method?.toLowerCase()] || methodIcons.default;
};
</script>
