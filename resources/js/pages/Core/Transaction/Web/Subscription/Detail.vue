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
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Main Card -->
                <div
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden"
                >
                    <!-- Enhanced Header -->
                    <div
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-6"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center"
                                >
                                    <svg
                                        class="w-7 h-7 text-white"
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
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold">
                                        {{ __("Plan Details") }}
                                    </h1>
                                    <div
                                        class="h-1 w-16 bg-white/40 rounded-full mt-2"
                                    ></div>
                                </div>
                            </div>
                            <span
                                class="px-4 py-2 bg-white/20 rounded-full text-sm font-semibold backdrop-blur-sm"
                                :class="
                                    item?.status === 'successful'
                                        ? 'text-green-100'
                                        : 'text-red-100'
                                "
                            >
                                {{ item?.status?.toUpperCase() }}
                            </span>
                        </div>

                        <div class="mt-6">
                            <h2 class="text-xl font-semibold mb-2">
                                {{ item?.meta?.name }}
                            </h2>
                            <div
                                class="text-blue-100/90 leading-relaxed"
                                v-html="item?.meta?.description"
                            ></div>
                        </div>
                    </div>

                    <!-- Plan Information -->
                    <div class="p-8">
                        <h3
                            class="text-xl font-bold text-gray-900 mb-6 flex items-center"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ __("Plan Information") }}
                        </h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-blue-600"
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
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("Plan Name") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ item?.meta?.name }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-green-600"
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
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("Transaction Code") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900 font-mono"
                                        >
                                            {{ item?.transaction?.code }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-purple-600"
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
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("Start Date") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ item?.start_at }}
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="item?.last_renewal_at"
                                    class="flex items-start space-x-4"
                                >
                                    <div
                                        class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-orange-600"
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
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("Last Renewal") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ item?.last_renewal_at }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-emerald-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                            />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("Price") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ item?.transaction?.total }}
                                            {{ item?.transaction?.currency }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-cyan-600"
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
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("Billing Period") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{
                                                item?.transaction
                                                    ?.billing_period
                                            }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-red-600"
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
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("End Date") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ item?.end_at }}
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="item?.cancellation_at"
                                    class="flex items-start space-x-4"
                                >
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-gray-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="text-sm text-gray-500 font-medium"
                                        >
                                            {{ __("Cancellation") }}
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ item?.cancellation_at }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-5 h-5 text-blue-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">
                                            {{ __("Payment Method") }}
                                        </div>
                                        <div
                                            class="font-semibold text-gray-900"
                                        >
                                            {{
                                                item?.transaction
                                                    ?.payment_method
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-lg flex items-center justify-center"
                                        :class="
                                            item?.is_recurring
                                                ? 'bg-green-100'
                                                : 'bg-gray-100'
                                        "
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            :class="
                                                item?.is_recurring
                                                    ? 'text-green-600'
                                                    : 'text-gray-600'
                                            "
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                v-if="item?.is_recurring"
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
                                                d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">
                                            {{ __("Recurring") }}
                                        </div>
                                        <div
                                            class="font-semibold text-gray-900"
                                        >
                                            {{
                                                item?.is_recurring
                                                    ? __("Yes")
                                                    : __("No")
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200" />

                    <!-- Included Services -->
                    <div class="p-8">
                        <h3
                            class="text-xl font-bold text-gray-900 mb-6 flex items-center"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            {{ __("Included Services") }}
                        </h3>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                        >
                            <div
                                v-for="scope in item?.meta?.scopes"
                                :key="scope.id"
                                class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow duration-200"
                            >
                                <h4
                                    class="text-lg font-bold text-gray-900 mb-2"
                                >
                                    {{ __(scope.role.name) }}
                                </h4>
                                <p
                                    class="text-gray-600 text-sm mb-4 leading-relaxed"
                                >
                                    {{ __(scope.role.description) }}
                                </p>
                                <div
                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium"
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
                                            d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"
                                        />
                                    </svg>
                                    {{ scope.service.name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200" />

                    <!-- Transactions -->
                    <div class="p-8">
                        <h3
                            class="text-xl font-bold text-gray-900 mb-6 flex items-center"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                />
                            </svg>
                            {{ __("Transactions") }}
                        </h3>

                        <div class="space-y-6">
                            <div
                                v-for="(tx, index) in item?.transactions"
                                :key="index"
                                class="bg-white border rounded-xl p-6 transition-all duration-200 hover:shadow-md"
                                :class="{
                                    'border-green-200 bg-green-50/30':
                                        tx.status === 'successful',
                                    'border-orange-200 bg-orange-50/30':
                                        tx.status === 'pending',
                                    'border-red-200 bg-red-50/30':
                                        tx.status === 'failed',
                                }"
                            >
                                <!-- Transaction Header -->
                                <div
                                    class="flex items-center justify-between mb-4"
                                >
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center"
                                            :class="{
                                                'bg-green-100':
                                                    tx.status === 'successful',
                                                'bg-orange-100':
                                                    tx.status === 'pending',
                                                'bg-red-100':
                                                    tx.status === 'failed',
                                            }"
                                        >
                                            <svg
                                                class="w-5 h-5"
                                                :class="{
                                                    'text-green-600':
                                                        tx.status ===
                                                        'successful',
                                                    'text-orange-600':
                                                        tx.status === 'pending',
                                                    'text-red-600':
                                                        tx.status === 'failed',
                                                }"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    v-if="
                                                        tx.status ===
                                                        'successful'
                                                    "
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                                <path
                                                    v-if="
                                                        tx.status === 'pending'
                                                    "
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                                <path
                                                    v-if="
                                                        tx.status === 'failed'
                                                    "
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <div
                                                class="font-bold text-gray-900"
                                            >
                                                {{ __("Transaction #")
                                                }}{{ tx.code }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ tx.created }}
                                            </div>
                                        </div>
                                    </div>
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold"
                                        :class="{
                                            'bg-green-100 text-green-800':
                                                tx.status === 'successful',
                                            'bg-orange-100 text-orange-800':
                                                tx.status === 'pending',
                                            'bg-red-100 text-red-800':
                                                tx.status === 'failed',
                                        }"
                                    >
                                        {{ __(tx.status) }}
                                    </span>
                                </div>

                                <hr class="border-gray-200 mb-4" />

                                <!-- Transaction Details -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4"
                                >
                                    <div>
                                        <div class="text-sm text-gray-500">
                                            {{ __("Total") }}
                                        </div>
                                        <div
                                            class="font-semibold text-gray-900"
                                        >
                                            {{ tx.total }} {{ tx.currency }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">
                                            {{ __("Payment Method") }}
                                        </div>
                                        <div
                                            class="font-semibold text-gray-900"
                                        >
                                            {{ tx.payment_method }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">
                                            {{ __("Billing Period") }}
                                        </div>
                                        <div
                                            class="font-semibold text-gray-900"
                                        >
                                            {{ tx.billing_period || __("N/A") }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Technical Details -->
                                <div
                                    v-if="tx.session_id || tx.payment_intent_id"
                                    class="mb-4"
                                >
                                    <details class="group">
                                        <summary
                                            class="cursor-pointer text-blue-600 hover:text-blue-700 font-medium flex items-center"
                                        >
                                            <svg
                                                class="w-4 h-4 mr-2 transform group-open:rotate-90 transition-transform"
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
                                            {{ __("Technical Details") }}
                                        </summary>
                                        <div
                                            class="mt-3 bg-gray-50 rounded-lg p-4 space-y-2"
                                        >
                                            <div v-if="tx.session_id">
                                                <div
                                                    class="text-xs text-gray-500"
                                                >
                                                    {{ __("Session ID:") }}
                                                </div>
                                                <div
                                                    class="font-mono text-sm bg-white px-3 py-2 rounded border"
                                                >
                                                    {{ tx.session_id }}
                                                </div>
                                            </div>
                                            <div v-if="tx.payment_intent_id">
                                                <div
                                                    class="text-xs text-gray-500"
                                                >
                                                    {{ __("Payment Intent:") }}
                                                </div>
                                                <div
                                                    class="font-mono text-sm bg-white px-3 py-2 rounded border"
                                                >
                                                    {{ tx.payment_intent_id }}
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3">
                                    <v-cancel
                                        v-if="tx.status === 'pending'"
                                        :item="tx"
                                    />

                                    <v-transaction-activate
                                        @updated="getPackages"
                                        v-if="check(tx)"
                                        :item="tx"
                                    />

                                    <a
                                        v-if="
                                            tx.status === 'successful' &&
                                            tx.payment_url
                                        "
                                        :href="tx.payment_url"
                                        target="_blank"
                                        class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                        {{ __("View Invoice") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                        <v-subscription
                            :period="item?.meta?.price"
                            :plan="item"
                            :label="__('Extend or renew package')"
                            :buy="false"
                            :package="item"
                            class="w-full"
                        />
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VAccountLayout from "@/layouts/VAccountLayout.vue";
import VSubscription from "@/components/VSubscription.vue";
import VCancel from "./Cancel.vue";
import VTransactionActivate from "@/components/VTransactionActivate.vue";

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    routes: Object,
});

const item = ref(null);

onMounted(() => {
    item.value = props.data;
});

// functions
const check = (item) => {
    return item.status == "pending" || item.status == "failed";
};
</script>

<style scoped>
/* Custom styles for better transitions */
.group summary {
    list-style: none;
}
.group summary::-webkit-details-marker {
    display: none;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
}

.duration-200 {
    transition-duration: 200ms;
}
</style>
