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
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6"
        >
            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6"
            >
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full"
                        ></div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent"
                        >
                            {{ __("Transactions Management") }}
                        </h1>
                    </div>
                    <p class="text-gray-600 text-base ml-5">
                        {{ __("Monitor and manage all transaction records") }}
                    </p>
                </div>
            </div>

            <!-- Custom Filter Component -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <!-- Name Filter -->
                    <v-input
                        :label="__('Name')"
                        v-model="search.name"
                        @input="debouncedSearch"
                        :placeholder="__('Filter by name')"
                    />

                    <!-- Code Filter -->
                    <v-input
                        :label="__('Code')"
                        v-model="search.code"
                        @input="debouncedSearch"
                        :placeholder="__('Filter by code')"
                    />

                    <!-- Email Filter -->
                    <v-input
                        :label="__('Email')"
                        v-model="search.email"
                        @input="debouncedSearch"
                        :placeholder="__('Filter by email')"
                    />

                    <!-- Type Filter -->
                    <v-select
                        :label="__('Type')"
                        v-model="search.type"
                        :options="types"
                        @change="searching"
                        :placeholder="__('All types')"
                    />

                    <!-- Status Filter -->
                    <v-select
                        :label="__('Status')"
                        v-model="search.status"
                        :options="statuses"
                        @change="searching"
                        :placeholder="__('All statuses')"
                    />

                    <!-- Clear Filters -->
                    <div class="flex items-end">
                        <button
                            @click="clearFilters"
                            class="w-full px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium shadow-sm"
                        >
                            {{ __("Clear Filters") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <!-- Table Header -->
            <div
                class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ __("Transaction Records") }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ __("Total") }}: {{ transactions.length }}
                            {{ __("records") }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500"
                            >{{ __("View") }}:</span
                        >
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button
                                @click="viewMode = 'list'"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm font-medium transition-all duration-200',
                                    viewMode === 'list'
                                        ? 'bg-white text-blue-600 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-800',
                                ]"
                            >
                                {{ __("List") }}
                            </button>
                            <button
                                @click="viewMode = 'grid'"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm font-medium transition-all duration-200',
                                    viewMode === 'grid'
                                        ? 'bg-white text-blue-600 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-800',
                                ]"
                            >
                                {{ __("Grid") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div v-if="viewMode === 'list'" class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    {{ __("Transaction") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    {{ __("Amount") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    {{ __("Type") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    {{ __("Status") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                >
                                    {{ __("Refund") }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20"
                                >
                                    {{ __("Details") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template
                                v-for="(item, index) in transactions"
                                :key="index"
                            >
                                <!-- Main Row -->
                                <tr
                                    class="hover:bg-gray-50 transition-colors duration-200 group"
                                >
                                    <!-- Transaction Info -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm"
                                                >
                                                    <i
                                                        class="mdi mdi-receipt text-blue-600 text-lg"
                                                    ></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div
                                                    class="text-sm font-semibold text-gray-900 truncate cursor-pointer hover:text-blue-600 transition-colors group-hover:underline"
                                                    @click="
                                                        copyToClipboard(
                                                            item.code
                                                        )
                                                    "
                                                    :title="
                                                        __(
                                                            'Click to copy transaction code'
                                                        )
                                                    "
                                                >
                                                    {{ item.code }}
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 mt-1 flex items-center gap-1"
                                                >
                                                    <i
                                                        class="mdi mdi-calendar-clock text-gray-400"
                                                    ></i>
                                                    {{ item.created }}
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 mt-1 flex items-center gap-1"
                                                >
                                                    <i
                                                        class="mdi mdi-credit-card-outline text-gray-400"
                                                    ></i>
                                                    {{ item.payment_method }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Amount -->
                                    <td class="px-6 py-4">
                                        <div
                                            class="text-sm font-semibold text-gray-900"
                                        >
                                            {{ item.total }} {{ item.currency }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ item.billing_period }}
                                            {{ __("plan") }}
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        {{ item.type }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-2">
                                            <span
                                                :class="[
                                                    'px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide inline-flex items-center justify-center w-fit',
                                                    getStatusClasses(
                                                        item.status
                                                    ),
                                                ]"
                                            >
                                                {{ item.status }}
                                            </span>
                                            <div
                                                v-if="item.activated"
                                                class="text-xs text-gray-500 flex items-center gap-1"
                                            >
                                                <i
                                                    class="mdi mdi-calendar-check"
                                                ></i>
                                                {{ __("Activated") }}:
                                                {{ item.activated }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Refund Status -->
                                    <td class="px-6 py-4 text-center">
                                        <div
                                            v-if="hasRefund(item)"
                                            class="flex flex-col gap-2"
                                        >
                                            <span
                                                class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide inline-flex items-center justify-center w-fit bg-purple-100 text-purple-800 border border-purple-200"
                                            >
                                                <i
                                                    class="mdi mdi-cash-refund mr-1"
                                                ></i>
                                                {{ item.refund.status }}
                                            </span>
                                            <div class="text-xs text-gray-500">
                                                {{ item.refund.amount }}
                                                {{ item.refund.currency }}
                                            </div>
                                        </div>
                                        <div
                                            v-else
                                            class="text-xs text-gray-400 italic"
                                        >
                                            {{ __("No refund") }}
                                        </div>
                                    </td>

                                    <!-- Toggle Button -->
                                    <td class="px-6 py-4">
                                        <button
                                            @click="toggleRowExpansion(index)"
                                            :class="[
                                                'w-full flex items-center justify-center p-2 rounded-lg border transition-all duration-200 font-medium text-sm',
                                                expandedRow === index
                                                    ? 'bg-blue-50 text-blue-600 border-blue-200 shadow-inner'
                                                    : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:border-gray-400 hover:text-gray-800',
                                            ]"
                                            :title="
                                                expandedRow === index
                                                    ? __('Collapse details')
                                                    : __('Expand details')
                                            "
                                        >
                                            <i
                                                :class="[
                                                    'mdi transition-transform duration-200',
                                                    expandedRow === index
                                                        ? 'mdi-chevron-up'
                                                        : 'mdi-chevron-down',
                                                ]"
                                            ></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Expanded Details Row -->
                                <tr
                                    v-if="expandedRow === index"
                                    class="bg-blue-50/30 border-b border-blue-200/50"
                                >
                                    <td colspan="5" class="p-6">
                                        <div
                                            class="grid grid-cols-1 lg:grid-cols-2 gap-8"
                                        >
                                            <!-- Transaction Information -->
                                            <div class="space-y-6">
                                                <!-- Basic Transaction Details -->
                                                <div>
                                                    <h4
                                                        class="text-sm font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2 mb-4"
                                                    >
                                                        <i
                                                            class="mdi mdi-information-outline"
                                                        ></i>
                                                        {{
                                                            __(
                                                                "Transaction Information"
                                                            )
                                                        }}
                                                    </h4>
                                                    <div
                                                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                                    >
                                                        <div class="space-y-3">
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Transaction Code"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm font-mono text-gray-900 cursor-pointer hover:text-blue-600 transition-colors bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                    @click="
                                                                        copyToClipboard(
                                                                            item.code
                                                                        )
                                                                    "
                                                                    :title="
                                                                        __(
                                                                            'Click to copy'
                                                                        )
                                                                    "
                                                                >
                                                                    {{
                                                                        item.code
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Payment Method"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item.payment_method
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Billing Period"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item.billing_period
                                                                    }}
                                                                    {{
                                                                        __(
                                                                            "plan"
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="space-y-3">
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Session ID"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm font-mono text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200 truncate"
                                                                >
                                                                    {{
                                                                        item.session_id ||
                                                                        __(
                                                                            "N/A"
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Payment Intent"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm font-mono text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200 truncate"
                                                                >
                                                                    {{
                                                                        item.payment_intent_id ||
                                                                        __(
                                                                            "N/A"
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Renewal"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item.renew
                                                                            ? __(
                                                                                  "Yes"
                                                                              )
                                                                            : __(
                                                                                  "No"
                                                                              )
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Financial Information -->
                                                <div>
                                                    <h4
                                                        class="text-sm font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2 mb-4"
                                                    >
                                                        <i
                                                            class="mdi mdi-cash"
                                                        ></i>
                                                        {{
                                                            __(
                                                                "Financial Details"
                                                            )
                                                        }}
                                                    </h4>
                                                    <div
                                                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                                    >
                                                        <div class="space-y-3">
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Total Amount"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-lg font-semibold text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item.total
                                                                    }}
                                                                    {{
                                                                        item.currency
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="space-y-3">
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Commission Rate"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item.partner_commission_rate ||
                                                                        "0"
                                                                    }}%
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Refund Information -->
                                                <div v-if="hasRefund(item)">
                                                    <h4
                                                        class="text-sm font-semibold text-purple-800 uppercase tracking-wide flex items-center gap-2 mb-4"
                                                    >
                                                        <i
                                                            class="mdi mdi-cash-refund"
                                                        ></i>
                                                        {{
                                                            __(
                                                                "Refund Information"
                                                            )
                                                        }}
                                                    </h4>
                                                    <div
                                                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                                    >
                                                        <div class="space-y-3">
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Refund Amount"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm font-semibold text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item
                                                                            .refund
                                                                            .amount
                                                                    }}
                                                                    {{
                                                                        item
                                                                            .refund
                                                                            .currency
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Refund Type"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item
                                                                            .refund
                                                                            .type
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="space-y-3">
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Refund Status"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    :class="[
                                                                        'text-sm font-semibold px-3 py-2 rounded-lg border',
                                                                        getRefundStatusClasses(
                                                                            item
                                                                                .refund
                                                                                .status
                                                                        ),
                                                                    ]"
                                                                >
                                                                    {{
                                                                        item
                                                                            .refund
                                                                            .status
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Reason"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item
                                                                            .refund
                                                                            .reason ||
                                                                        __(
                                                                            "No reason provided"
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            item.refund
                                                                .description
                                                        "
                                                        class="mt-3"
                                                    >
                                                        <div
                                                            class="flex flex-col"
                                                        >
                                                            <span
                                                                class="text-xs font-medium text-gray-600 mb-1"
                                                                >{{
                                                                    __(
                                                                        "Description"
                                                                    )
                                                                }}</span
                                                            >
                                                            <span
                                                                class="text-sm text-gray-700 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                            >
                                                                {{
                                                                    item.refund
                                                                        .description
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- User & Timeline Information -->
                                            <div class="space-y-6">
                                                <!-- User Information -->
                                                <div>
                                                    <h4
                                                        class="text-sm font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2 mb-4"
                                                    >
                                                        <i
                                                            class="mdi mdi-account"
                                                        ></i>
                                                        {{
                                                            __(
                                                                "User Information"
                                                            )
                                                        }}
                                                    </h4>
                                                    <div class="space-y-3">
                                                        <div
                                                            class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                                        >
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Owner Name"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item
                                                                            .owner
                                                                            ?.name ||
                                                                        __(
                                                                            "N/A"
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <span
                                                                    class="text-xs font-medium text-gray-600 mb-1"
                                                                    >{{
                                                                        __(
                                                                            "Owner Last Name"
                                                                        )
                                                                    }}</span
                                                                >
                                                                <span
                                                                    class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                                >
                                                                    {{
                                                                        item
                                                                            .owner
                                                                            ?.last_name ||
                                                                        __(
                                                                            "N/A"
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="flex flex-col"
                                                        >
                                                            <span
                                                                class="text-xs font-medium text-gray-600 mb-1"
                                                                >{{
                                                                    __(
                                                                        "Owner Email"
                                                                    )
                                                                }}</span
                                                            >
                                                            <span
                                                                class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                            >
                                                                {{
                                                                    item.owner
                                                                        ?.email ||
                                                                    __("N/A")
                                                                }}
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="flex flex-col"
                                                        >
                                                            <span
                                                                class="text-xs font-medium text-gray-600 mb-1"
                                                                >{{
                                                                    __(
                                                                        "Activated By"
                                                                    )
                                                                }}</span
                                                            >
                                                            <span
                                                                class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                            >
                                                                {{
                                                                    item.activated
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Timeline -->
                                                <div>
                                                    <h4
                                                        class="text-sm font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2 mb-4"
                                                    >
                                                        <i
                                                            class="mdi mdi-clock-outline"
                                                        ></i>
                                                        {{ __("Timeline") }}
                                                    </h4>
                                                    <div class="space-y-3">
                                                        <div
                                                            class="flex flex-col"
                                                        >
                                                            <span
                                                                class="text-xs font-medium text-gray-600 mb-1"
                                                                >{{
                                                                    __(
                                                                        "Created"
                                                                    )
                                                                }}</span
                                                            >
                                                            <span
                                                                class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                            >
                                                                {{
                                                                    item.created
                                                                }}
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="flex flex-col"
                                                        >
                                                            <span
                                                                class="text-xs font-medium text-gray-600 mb-1"
                                                                >{{
                                                                    __(
                                                                        "Last Updated"
                                                                    )
                                                                }}</span
                                                            >
                                                            <span
                                                                class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                            >
                                                                {{
                                                                    item.updated
                                                                }}
                                                            </span>
                                                        </div>
                                                        <div
                                                            v-if="
                                                                item.cancellation_at
                                                            "
                                                            class="flex flex-col"
                                                        >
                                                            <span
                                                                class="text-xs font-medium text-gray-600 mb-1"
                                                                >{{
                                                                    __(
                                                                        "Cancellation Date"
                                                                    )
                                                                }}</span
                                                            >
                                                            <span
                                                                class="text-sm text-gray-900 bg-white px-3 py-2 rounded-lg border border-gray-200"
                                                            >
                                                                {{
                                                                    item.cancellation_at
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Actions Section -->
                                                <div>
                                                    <h4
                                                        class="text-sm font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2 mb-4"
                                                    >
                                                        <i
                                                            class="mdi mdi-cog-outline"
                                                        ></i>
                                                        {{ __("Actions") }}
                                                    </h4>
                                                    <div
                                                        class="grid grid-cols-1 sm:grid-cols-2 gap-3"
                                                    >
                                                        <v-transaction-activate
                                                            @updated="
                                                                getTransactions
                                                            "
                                                            v-if="check(item)"
                                                            :item="item"
                                                        />
                                                        <v-detail
                                                            :item="item"
                                                            class="w-full"
                                                        />
                                                        <button
                                                            @click="
                                                                toggleRowExpansion(
                                                                    index
                                                                )
                                                            "
                                                            class="col-span-full px-4 py-2.5 text-sm text-gray-700 hover:text-gray-900 hover:bg-white border border-gray-300 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 font-medium"
                                                        >
                                                            <i
                                                                class="mdi mdi-close"
                                                            ></i>
                                                            {{
                                                                __(
                                                                    "Close Details"
                                                                )
                                                            }}
                                                        </button>
                                                    </div>
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

            <!-- Mobile Cards -->
            <div v-else class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                        v-for="(item, index) in transactions"
                        :key="index"
                        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden"
                    >
                        <!-- Card Header -->
                        <div
                            class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white"
                        >
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm"
                                    >
                                        <i
                                            class="mdi mdi-receipt text-blue-600 text-lg"
                                        ></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-semibold text-gray-900 cursor-pointer hover:text-blue-600 transition-colors"
                                            @click="copyToClipboard(item.code)"
                                            :title="
                                                __(
                                                    'Click to copy transaction code'
                                                )
                                            "
                                        >
                                            {{ truncateCode(item.code) }}
                                        </div>
                                        <div
                                            class="text-xs text-gray-500 mt-1 flex items-center gap-1"
                                        >
                                            <i
                                                class="mdi mdi-calendar-clock text-gray-400"
                                            ></i>
                                            {{ item.created }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span
                                        :class="[
                                            'px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                                            getStatusClasses(item.status),
                                        ]"
                                    >
                                        {{ item.status }}
                                    </span>
                                    <span
                                        v-if="hasRefund(item)"
                                        :class="[
                                            'px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-purple-100 text-purple-800 border border-purple-200',
                                        ]"
                                    >
                                        <i class="mdi mdi-cash-refund mr-1"></i>
                                        {{ __("Refund") }}
                                    </span>
                                </div>
                            </div>

                            <!-- Toggle Button for Mobile -->
                            <button
                                @click="toggleRowExpansion(index)"
                                :class="[
                                    'w-full flex items-center justify-center p-2 rounded-lg border transition-all duration-200 text-sm font-medium',
                                    expandedRow === index
                                        ? 'bg-blue-50 text-blue-600 border-blue-200'
                                        : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50',
                                ]"
                            >
                                <span class="mr-2">{{
                                    expandedRow === index
                                        ? __("Hide Details")
                                        : __("Show Details")
                                }}</span>
                                <i
                                    :class="[
                                        'mdi transition-transform duration-200',
                                        expandedRow === index
                                            ? 'mdi-chevron-up'
                                            : 'mdi-chevron-down',
                                    ]"
                                ></i>
                            </button>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600"
                                    >{{ __("Amount") }}:</span
                                >
                                <span
                                    class="text-sm font-semibold text-gray-900"
                                >
                                    {{ item.total }} {{ item.currency }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600"
                                    >{{ __("Payment Method") }}:</span
                                >
                                <span class="text-sm text-gray-900">{{
                                    item.payment_method
                                }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600"
                                    >{{ __("Plan") }}:</span
                                >
                                <span class="text-sm text-gray-900"
                                    >{{ item.billing_period }}
                                    {{ __("plan") }}</span
                                >
                            </div>
                            <div
                                v-if="item.activated"
                                class="flex justify-between items-center"
                            >
                                <span class="text-sm text-gray-600"
                                    >{{ __("Activated") }}:</span
                                >
                                <span class="text-sm text-gray-900">{{
                                    item.activated
                                }}</span>
                            </div>
                            <div
                                v-if="hasRefund(item)"
                                class="flex justify-between items-center"
                            >
                                <span class="text-sm text-gray-600"
                                    >{{ __("Refund Amount") }}:</span
                                >
                                <span
                                    class="text-sm font-semibold text-purple-600"
                                >
                                    {{ item.refund.amount }}
                                    {{ item.refund.currency }}
                                </span>
                            </div>
                        </div>

                        <!-- Expanded Details for Mobile -->
                        <div
                            v-if="expandedRow === index"
                            class="p-4 bg-blue-50/30 border-t border-blue-200/50"
                        >
                            <div class="space-y-4">
                                <!-- User Information -->
                                <div class="space-y-3">
                                    <h4
                                        class="text-xs font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2"
                                    >
                                        <i class="mdi mdi-account"></i>
                                        {{ __("User Info") }}
                                    </h4>
                                    <div class="space-y-2 text-xs">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Name") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{
                                                    item.owner?.name ||
                                                    __("N/A")
                                                }}</span
                                            >
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Email") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{
                                                    item.owner?.email ||
                                                    __("N/A")
                                                }}</span
                                            >
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Activated By") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ item.activated }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Details -->
                                <div class="space-y-3">
                                    <h4
                                        class="text-xs font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2"
                                    >
                                        <i class="mdi mdi-cash"></i>
                                        {{ __("Financial") }}
                                    </h4>
                                    <div class="space-y-2 text-xs">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Commission") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{
                                                    item.partner_commission_rate ||
                                                    "0"
                                                }}%</span
                                            >
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Renewal") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{
                                                    item.renew
                                                        ? __("Yes")
                                                        : __("No")
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Refund Information for Mobile -->
                                <div v-if="hasRefund(item)" class="space-y-3">
                                    <h4
                                        class="text-xs font-semibold text-purple-800 uppercase tracking-wide flex items-center gap-2"
                                    >
                                        <i class="mdi mdi-cash-refund"></i>
                                        {{ __("Refund Info") }}
                                    </h4>
                                    <div class="space-y-2 text-xs">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Amount") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ item.refund.amount }}
                                                {{ item.refund.currency }}</span
                                            >
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Type") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ item.refund.type }}</span
                                            >
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600"
                                                >{{ __("Status") }}:</span
                                            >
                                            <span
                                                :class="[
                                                    'font-medium',
                                                    getRefundStatusTextClasses(
                                                        item.refund.status
                                                    ),
                                                ]"
                                            >
                                                {{ item.refund.status }}
                                            </span>
                                        </div>
                                        <div
                                            v-if="item.refund.reason"
                                            class="flex justify-between"
                                        >
                                            <span class="text-gray-600"
                                                >{{ __("Reason") }}:</span
                                            >
                                            <span
                                                class="font-medium text-gray-900 text-right"
                                                >{{ item.refund.reason }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions Section -->
                                <div class="space-y-3">
                                    <h4
                                        class="text-xs font-semibold text-blue-800 uppercase tracking-wide flex items-center gap-2"
                                    >
                                        <i class="mdi mdi-cog-outline"></i>
                                        {{ __("Actions") }}
                                    </h4>
                                    <div class="grid grid-cols-1 gap-2">
                                        <v-transaction-activate
                                            @updated="getTransactions"
                                            v-if="check(item)"
                                            :item="item"
                                        />
                                        <v-detail
                                            :item="item"
                                            size="small"
                                            class="w-full"
                                        />
                                        <button
                                            @click="toggleRowExpansion(index)"
                                            class="w-full px-3 py-2 text-xs text-gray-600 hover:text-gray-800 hover:bg-white border border-gray-300 rounded transition-colors duration-200 flex items-center justify-center gap-1 font-medium"
                                        >
                                            <i class="mdi mdi-close"></i>
                                            {{ __("Close") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-16">
                <div class="text-center">
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"
                    ></div>
                    <p class="text-gray-600 font-medium">
                        {{ __("Loading transactions...") }}
                    </p>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="!loading && transactions.length === 0"
                class="text-center py-16 px-6"
            >
                <div class="max-w-md mx-auto">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4"
                    >
                        <i class="mdi mdi-receipt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">
                        {{ __("No transactions available") }}
                    </h3>
                    <p class="text-gray-500">
                        {{
                            __("Get started by creating your first transaction")
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <v-paginate
            v-model="search.page"
            :total-pages="pages.total_pages"
            v-if="pages.total_pages > 1"
            @change="getTransactions"
        />
    </v-admin-transaction-layout>
</template>

<script setup>
import VAdminTransactionLayout from "@/layouts/VAdminTransactionLayout.vue";
import VTransactionActivate from "@/components/VTransactionActivate.vue";
import VDetail from "./Detail.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VPaginate from "@/components/VPaginate.vue";
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    route: String,
    transaction_routes: Object,
});

const viewMode = ref("list");
const loading = ref(false);
const expandedRow = ref(null);
const transactions = ref([]);
const pages = ref({
    total_pages: 0,
});

const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    code: "",
    email: "",
    status: "",
    type: "",
});

const searchTimeout = ref(null);
const statuses = ref([]);
const types = ref([]);

onMounted(() => {
    const values = props.data;
    transactions.value = { ...values.data };
    pages.value = { ...values.meta.pagination };
    getStatuses();
    getType();
});

// functions
const getStatusClasses = (status) => {
    switch (status) {
        case "successful":
            return "bg-green-100 text-green-800 border border-green-200";
        case "pending":
            return "bg-orange-100 text-orange-800 border border-orange-200";
        case "failed":
            return "bg-red-100 text-red-800 border border-red-200";
        default:
            return "bg-gray-100 text-gray-800 border border-gray-200";
    }
};

const getRefundStatusClasses = (status) => {
    switch (status?.toLowerCase()) {
        case "completed":
        case "succeeded":
            return "bg-green-100 text-green-800 border-green-200";
        case "pending":
            return "bg-orange-100 text-orange-800 border-orange-200";
        case "failed":
        case "canceled":
            return "bg-red-100 text-red-800 border-red-200";
        default:
            return "bg-gray-100 text-gray-800 border-gray-200";
    }
};

const getRefundStatusTextClasses = (status) => {
    switch (status?.toLowerCase()) {
        case "completed":
        case "succeeded":
            return "text-green-600";
        case "pending":
            return "text-orange-600";
        case "failed":
        case "canceled":
            return "text-red-600";
        default:
            return "text-gray-600";
    }
};

const hasRefund = (item) => {
    return (
        item.refund && Object.keys(item.refund).length > 0 && item.refund.amount
    );
};

const toggleRowExpansion = (index) => {
    if (expandedRow.value === index) {
        expandedRow.value = null;
    } else {
        expandedRow.value = index;
    }
};

const getStatuses = async () => {
    try {
        const res = await $server.get("/api/transaction/payments/statuses");
        if (res.status == 200) {
            statuses.value = res.data.data;
        }
    } catch (e) {
        console.error("Error fetching statuses:", e);
    }
};

const getType = async () => {
    try {
        const res = await $server.get("/api/transaction/payments/types");
        if (res.status == 200) {
            types.value = res.data.data;
        }
    } catch (e) {
        console.error("Error fetching types:", e);
    }
};

const searching = () => {
    search.page = 1;
    getTransactions();
};

const debouncedSearch = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    searchTimeout = setTimeout(() => {
        searching();
    }, 500);
};

const getTransactions = () => {
    loading.value = true;
    expandedRow.value = null;

    search.get(props.route, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            const values = page.props.data;
            transactions.value = values.data;
            pages.value = values.meta.pagination;
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

const check = (item) => {
    return item.status == "pending" || item.status == "failed";
};

const clearFilters = () => {
    search.page = 1;
    search.per_page = 15;
    search.name = "";
    search.code = "";
    search.email = "";
    search.status = "";
    search.type = "";
    searching();
};

const copyToClipboard = (text) => {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            $notify.success(__("Transaction code copied to clipboard"));
        })
        .catch((err) => {
            $notify.error(__("Failed to copy text"));
        });
};

const truncateCode = (code) => {
    if (code && code.length > 12) {
        return code.substring(0, 8) + "...";
    }
    return code;
};
</script>
