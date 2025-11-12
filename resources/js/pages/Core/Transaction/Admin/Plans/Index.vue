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
        <!-- HEADER -->
        <header
            class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-8 py-4 rounded-b-3xl shadow-lg dark:from-gray-800 dark:to-gray-900 dark:text-gray-100"
        >
            <div
                class="flex flex-col md:flex-row md:items-center justify-between"
            >
                <div class="flex-1 mb-6 md:mb-0">
                    <h1 class="text-4xl font-bold mb-2">
                        {{ __("Subscription Plans") }}
                    </h1>
                    <p
                        class="text-blue-100 text-lg opacity-90 dark:text-gray-400"
                    >
                        {{
                            __(
                                "Create, edit, and manage your subscription offerings"
                            )
                        }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <v-create @created="getPlans" />
                </div>
            </div>
        </header>

        <!-- FILTERS -->
        <section
            class="px-4 py-6 bg-white shadow-sm rounded-b-2xl border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700"
        >
            <div
                class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-4"
            >
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Search by Name") }}
                    </label>
                    <input
                        v-model="search.name"
                        @input="getPlans"
                        type="text"
                        placeholder="e.g. Premium Plan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200"
                    />
                </div>

                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Status") }}
                    </label>
                    <select
                        v-model="search.active"
                        @change="getPlans"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    >
                        <option value="">{{ __("All") }}</option>
                        <option value="1">{{ __("Active") }}</option>
                        <option value="0">{{ __("Inactive") }}</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Bonus Enabled") }}
                    </label>
                    <select
                        v-model="search.bonus_activated"
                        @change="getPlans"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    >
                        <option value="">{{ __("All") }}</option>
                        <option value="1">{{ __("Yes") }}</option>
                        <option value="0">{{ __("No") }}</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Bonus Duration (Days)") }}
                    </label>
                    <input
                        v-model="search.bonus_duration"
                        @input="getPlans"
                        type="number"
                        min="0"
                        placeholder="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    />
                </div>
            </div>
        </section>

        <!-- PLANS TABLE -->
        <section class="px-4 py-8 max-w-7xl mx-auto">
            <div
                class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:bg-gray-900 dark:border-gray-700"
            >
                <!-- TABLE FOR LARGE SCREENS -->
                <div class="hidden md:block">
                    <table class="w-full">
                        <thead
                            class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700"
                        >
                            <tr>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Plan") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Status") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Features") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Pricing") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Actions") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <template v-for="plan in plans" :key="plan.id">
                                <!-- MAIN ROW -->
                                <tr
                                    class="hover:bg-white dark:hover:bg-gray-800 transition-colors"
                                >
                                    <!-- PLAN NAME -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-white"
                                                :class="
                                                    plan.active
                                                        ? 'bg-gradient-to-r from-green-500 to-green-600'
                                                        : 'bg-gradient-to-r from-gray-500 to-gray-600'
                                                "
                                            >
                                                <i
                                                    class="mdi mdi-crown text-lg"
                                                ></i>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                                >
                                                    {{ plan.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- STATUS -->
                                    <td class="py-4 px-6">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border',
                                                plan.active
                                                    ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900 dark:text-green-200 dark:border-green-700'
                                                    : 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600',
                                            ]"
                                        >
                                            <i
                                                :class="
                                                    plan.active
                                                        ? 'mdi mdi-check-circle'
                                                        : 'mdi mdi-close-circle'
                                                "
                                                class="text-base mr-1"
                                            ></i>
                                            {{
                                                plan.active
                                                    ? __("Active")
                                                    : __("Inactive")
                                            }}
                                        </span>

                                        <div class="mt-2 flex flex-wrap gap-1">
                                            <span
                                                v-if="plan.bonus_enabled"
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200 dark:bg-purple-900 dark:text-purple-200 dark:border-purple-700"
                                            >
                                                <i
                                                    class="mdi mdi-gift text-xs mr-1"
                                                ></i>
                                                {{ __("Bonus") }}:
                                                {{ plan.bonus_duration }}
                                                {{ __("days") }}
                                            </span>

                                            <span
                                                v-if="plan.trial_enabled"
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700"
                                            >
                                                <i
                                                    class="mdi mdi-clock text-xs mr-1"
                                                ></i>
                                                {{ __("Trial") }}:
                                                {{ plan.trial_duration }}
                                                {{ __("days") }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- ACCESS SCOPES -->
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <div
                                                class="flex items-center text-blue-600 dark:text-blue-400 font-semibold mb-2"
                                            >
                                                <i
                                                    class="mdi mdi-key-chain-variant mr-2 text-sm"
                                                ></i>
                                                {{ __("Access Scopes") }}
                                            </div>

                                            <div
                                                v-if="plan.scopes.length"
                                                class="text-sm"
                                            >
                                                <div
                                                    class="flex items-center justify-between mb-1"
                                                >
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            plan.scopes.length
                                                        }}
                                                        {{
                                                            __("scope(s)")
                                                        }}</span
                                                    >
                                                    <button
                                                        @click="
                                                            togglePlanExpansion(
                                                                plan.id,
                                                                'scopes'
                                                            )
                                                        "
                                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-xs flex items-center"
                                                    >
                                                        {{
                                                            isPlanExpanded(
                                                                plan.id,
                                                                "scopes"
                                                            )
                                                                ? __("Hide")
                                                                : __("View")
                                                        }}
                                                        <i
                                                            :class="[
                                                                'mdi ml-1 transition-transform',
                                                                isPlanExpanded(
                                                                    plan.id,
                                                                    'scopes'
                                                                )
                                                                    ? 'mdi-chevron-up'
                                                                    : 'mdi-chevron-down',
                                                            ]"
                                                        ></i>
                                                    </button>
                                                </div>

                                                <!-- Expanded scopes content -->
                                                <div
                                                    v-if="
                                                        isPlanExpanded(
                                                            plan.id,
                                                            'scopes'
                                                        )
                                                    "
                                                    class="mt-2 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden"
                                                >
                                                    <div
                                                        v-for="(
                                                            item, index
                                                        ) in plan.scopes"
                                                        :key="`${plan.id}-${index}`"
                                                        class="border-b border-gray-100 dark:border-gray-700 last:border-b-0 bg-white dark:bg-gray-800"
                                                    >
                                                        <div class="p-3">
                                                            <div
                                                                class="font-medium text-gray-800 dark:text-gray-200"
                                                            >
                                                                {{
                                                                    item.service
                                                                        .group
                                                                        .name
                                                                }}
                                                            </div>
                                                            <div
                                                                class="text-sm text-gray-600 dark:text-gray-400"
                                                            >
                                                                {{
                                                                    item.service
                                                                        .name
                                                                }}
                                                                —
                                                                {{
                                                                    item.role
                                                                        .name
                                                                }}
                                                            </div>
                                                            <div class="mt-2">
                                                                <v-revoke-scope
                                                                    :item="item"
                                                                    @revoked="
                                                                        getPlans
                                                                    "
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                v-else
                                                class="text-center py-2 text-gray-500 dark:text-gray-400 text-sm"
                                            >
                                                <i
                                                    class="mdi mdi-key-remove mr-1"
                                                ></i>
                                                {{ __("No scopes assigned") }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- PRICING TOGGLE -->
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <div
                                                class="flex items-center text-green-600 dark:text-green-400 font-semibold mb-2"
                                            >
                                                <i
                                                    class="mdi mdi-currency-usd mr-2 text-sm"
                                                ></i>
                                                {{ __("Pricing") }}
                                            </div>

                                            <div
                                                v-if="plan.prices.length"
                                                class="text-sm"
                                            >
                                                <div
                                                    class="flex items-center justify-between mb-1"
                                                >
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            plan.prices.length
                                                        }}
                                                        {{
                                                            __("price(s)")
                                                        }}</span
                                                    >
                                                    <button
                                                        @click="
                                                            togglePricingRow(
                                                                plan.id
                                                            )
                                                        "
                                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-xs flex items-center"
                                                    >
                                                        {{
                                                            expandedPricing ===
                                                            plan.id
                                                                ? __("Hide")
                                                                : __("View")
                                                        }}
                                                        <i
                                                            :class="[
                                                                'mdi ml-1 transition-transform',
                                                                expandedPricing ===
                                                                plan.id
                                                                    ? 'mdi-chevron-up'
                                                                    : 'mdi-chevron-down',
                                                            ]"
                                                        ></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div
                                                v-else
                                                class="text-center py-2 text-gray-500 dark:text-gray-400 text-sm"
                                            >
                                                <i
                                                    class="mdi mdi-cash-remove mr-1"
                                                ></i>
                                                {{ __("No prices configured") }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <v-create
                                                :item="plan"
                                                @updated="getPlans"
                                            />
                                            <v-delete
                                                :item="plan"
                                                @deleted="getPlans"
                                            />
                                        </div>
                                    </td>
                                </tr>

                                <!-- EXPANDED PRICING ROW -->
                                <tr
                                    v-if="
                                        plan.prices.length &&
                                        expandedPricing === plan.id
                                    "
                                    class="bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800"
                                >
                                    <td colspan="5" class="p-6">
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-blue-200 dark:border-blue-700"
                                        >
                                            <div
                                                class="flex items-center justify-between mb-4 p-4 border-b border-blue-100 dark:border-blue-800"
                                            >
                                                <h3
                                                    class="text-lg font-semibold text-blue-800 dark:text-blue-300"
                                                >
                                                    <i
                                                        class="mdi mdi-currency-usd mr-2"
                                                    ></i>
                                                    {{
                                                        __(
                                                            "Pricing Details for"
                                                        )
                                                    }}: {{ plan.name }}
                                                </h3>
                                                <button
                                                    @click="
                                                        togglePricingRow(
                                                            plan.id
                                                        )
                                                    "
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center text-sm"
                                                >
                                                    {{ __("Close") }}
                                                    <i
                                                        class="mdi mdi-close ml-1"
                                                    ></i>
                                                </button>
                                            </div>

                                            <div
                                                class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 p-4"
                                            >
                                                <div
                                                    v-for="(
                                                        price, index
                                                    ) in plan.prices"
                                                    :key="index"
                                                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md dark:hover:shadow-gray-900/50 transition-shadow"
                                                >
                                                    <div
                                                        class="flex items-center justify-between mb-3"
                                                    >
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-sm font-semibold"
                                                        >
                                                            {{
                                                                formatBillingPeriod(
                                                                    price.billing_period
                                                                )
                                                            }}
                                                        </span>
                                                        <v-delete-price
                                                            :item="price"
                                                            @deleted="getPlans"
                                                        />
                                                    </div>

                                                    <div class="space-y-2">
                                                        <div
                                                            class="flex justify-between items-center"
                                                        >
                                                            <span
                                                                class="text-gray-600 dark:text-gray-400 text-sm"
                                                                >{{
                                                                    __(
                                                                        "Amount"
                                                                    )
                                                                }}:</span
                                                            >
                                                            <span
                                                                class="font-bold text-lg text-gray-900 dark:text-gray-100"
                                                            >
                                                                {{
                                                                    price.amount_format
                                                                }}
                                                                <span
                                                                    class="text-sm text-gray-600 dark:text-gray-400 ml-1"
                                                                    >{{
                                                                        price.currency
                                                                    }}</span
                                                                >
                                                            </span>
                                                        </div>
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

                <!-- CARDS FOR SMALL SCREENS -->
                <div class="md:hidden">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div v-for="plan in plans" :key="plan.id" class="p-4">
                            <!-- Plan Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-white"
                                        :class="
                                            plan.active
                                                ? 'bg-gradient-to-r from-green-500 to-green-600'
                                                : 'bg-gradient-to-r from-gray-500 to-gray-600'
                                        "
                                    >
                                        <i class="mdi mdi-crown text-lg"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            {{ plan.name }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                plan.active
                                                    ? __("Active")
                                                    : __("Inactive")
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <v-create
                                        :item="plan"
                                        @updated="getPlans"
                                    />
                                    <v-delete
                                        :item="plan"
                                        @deleted="getPlans"
                                    />
                                </div>
                            </div>

                            <!-- Features Tags -->
                            <div class="flex flex-wrap gap-1 mb-3">
                                <span
                                    v-if="plan.bonus_enabled"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200 dark:bg-purple-900 dark:text-purple-200 dark:border-purple-700"
                                >
                                    <i class="mdi mdi-gift text-xs mr-1"></i>
                                    {{ __("Bonus") }}: {{ plan.bonus_duration }}
                                    {{ __("days") }}
                                </span>

                                <span
                                    v-if="plan.trial_enabled"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700"
                                >
                                    <i class="mdi mdi-clock text-xs mr-1"></i>
                                    {{ __("Trial") }}: {{ plan.trial_duration }}
                                    {{ __("days") }}
                                </span>
                            </div>

                            <!-- Access Scopes -->
                            <div class="mb-3">
                                <button
                                    @click="
                                        togglePlanExpansion(plan.id, 'scopes')
                                    "
                                    class="w-full flex justify-between items-center p-2 bg-white dark:bg-gray-800 rounded-lg"
                                >
                                    <div
                                        class="flex items-center text-blue-600 dark:text-blue-400 font-medium"
                                    >
                                        <i
                                            class="mdi mdi-key-chain-variant mr-2"
                                        ></i>
                                        {{ __("Access Scopes") }}
                                        <span
                                            class="ml-2 text-gray-500 dark:text-gray-400 text-sm"
                                            >({{ plan.scopes.length }})</span
                                        >
                                    </div>
                                    <i
                                        :class="[
                                            'mdi transition-transform',
                                            isPlanExpanded(plan.id, 'scopes')
                                                ? 'mdi-chevron-up'
                                                : 'mdi-chevron-down',
                                        ]"
                                    ></i>
                                </button>

                                <div
                                    v-if="isPlanExpanded(plan.id, 'scopes')"
                                    class="mt-2 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden"
                                >
                                    <div
                                        v-for="(item, index) in plan.scopes"
                                        :key="`${plan.id}-${index}`"
                                        class="border-b border-gray-100 dark:border-gray-700 last:border-b-0 bg-white dark:bg-gray-800"
                                    >
                                        <div class="p-3">
                                            <div
                                                class="font-medium text-gray-800 dark:text-gray-200"
                                            >
                                                {{ item.service.group.name }}
                                            </div>
                                            <div
                                                class="text-sm text-gray-600 dark:text-gray-400"
                                            >
                                                {{ item.service.name }} —
                                                {{ item.role.name }}
                                            </div>
                                            <div class="mt-2">
                                                <v-revoke-scope
                                                    :item="item"
                                                    @revoked="getPlans"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div>
                                <button
                                    @click="togglePricingRowMobile(plan.id)"
                                    class="w-full flex justify-between items-center p-2 bg-white dark:bg-gray-800 rounded-lg"
                                >
                                    <div
                                        class="flex items-center text-green-600 dark:text-green-400 font-medium"
                                    >
                                        <i
                                            class="mdi mdi-currency-usd mr-2"
                                        ></i>
                                        {{ __("Pricing") }}
                                        <span
                                            class="ml-2 text-gray-500 dark:text-gray-400 text-sm"
                                            >({{ plan.prices.length }})</span
                                        >
                                    </div>
                                    <i
                                        :class="[
                                            'mdi transition-transform',
                                            expandedPricingMobile === plan.id
                                                ? 'mdi-chevron-up'
                                                : 'mdi-chevron-down',
                                        ]"
                                    ></i>
                                </button>

                                <div
                                    v-if="expandedPricingMobile === plan.id"
                                    class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4"
                                >
                                    <div
                                        class="flex items-center justify-between mb-3"
                                    >
                                        <h4
                                            class="text-blue-800 dark:text-blue-300 font-semibold"
                                        >
                                            {{ __("Pricing Details") }}
                                        </h4>
                                        <button
                                            @click="
                                                togglePricingRowMobile(plan.id)
                                            "
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                        >
                                            {{ __("Close") }}
                                        </button>
                                    </div>
                                    <div class="space-y-3">
                                        <div
                                            v-for="(
                                                price, index
                                            ) in plan.prices"
                                            :key="index"
                                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3"
                                        >
                                            <div
                                                class="flex items-center justify-between mb-2"
                                            >
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-xs font-semibold"
                                                >
                                                    {{
                                                        formatBillingPeriod(
                                                            price.billing_period
                                                        )
                                                    }}
                                                </span>
                                                <v-delete-price
                                                    :item="price"
                                                    @deleted="getPlans"
                                                />
                                            </div>
                                            <div class="space-y-1">
                                                <div
                                                    class="flex justify-between"
                                                >
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400 text-sm"
                                                        >{{
                                                            __("Amount")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-bold text-gray-900 dark:text-gray-100"
                                                    >
                                                        {{
                                                            price.amount_format
                                                        }}
                                                        <span
                                                            class="text-xs text-gray-600 dark:text-gray-400 ml-1"
                                                            >{{
                                                                price.currency
                                                            }}</span
                                                        >
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="price.expiration"
                                                    class="flex justify-between text-xs"
                                                >
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400"
                                                        >{{
                                                            __("Expires")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="text-gray-800 dark:text-gray-300"
                                                        >{{
                                                            price.expiration
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMPTY STATE -->
            <div
                v-if="!plans.length"
                class="text-center py-24 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl mt-8"
            >
                <i
                    class="mdi mdi-clipboard-list-outline text-5xl text-gray-300 dark:text-gray-600 mb-4"
                ></i>
                <p class="text-lg font-semibold">
                    {{ __("No subscription plans available") }}
                </p>
                <p class="text-gray-400 dark:text-gray-500 mb-6">
                    {{ __("Start by creating your first plan") }}
                </p>
                <v-create @created="getPlans" />
            </div>
        </section>

        <!-- PAGINATION -->
        <v-paginate
            v-if="pages.total_pages > 1"
            :total-pages="pages.total_pages"
            v-model="search.page"
            @change="getPlans"
        />
    </v-admin-transaction-layout>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import VAdminTransactionLayout from "@/layouts/VAdminTransactionLayout.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VRevokeScope from "./RevokeScope.vue";
import VDeletePrice from "./DeletePrice.vue";
import VPaginate from "@/components/VPaginate.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    data: Object,
    routes: Object,
    transaction_routes: Object,
});

const plans = ref([]);
const expandedPlans = reactive({});
const expandedPricing = ref(null);
const expandedPricingMobile = ref(null);
const search = useForm({
    page: 1,
    per_page: 15,
    name: "",
    active: "",
    bonus_activated: "",
    bonus_duration: "",
});
const pages = reactive({ total_pages: 0 });

onMounted(() => {
    const values = props.data;

    plans.value = values.data;
    pages.value = values.meta.pagination;
});

/** Functions */
const getPlans = () => {
    search.get(props.routes.plans, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            const values = page.props.data;
            plans.value = values.data;
            pages.value = values.meta.pagination;
            for (const key in expandedPlans) delete expandedPlans[key];
            expandedPricing.value = null;
            expandedPricingMobile.value = null;
        },
    });
};

const togglePlanExpansion = (planId, section) => {
    const key = `${planId}-${section}`;
    expandedPlans[key] = !expandedPlans[key];
};

const isPlanExpanded = (planId, section) => {
    return !!expandedPlans[`${planId}-${section}`];
};

const togglePricingRow = (planId) => {
    expandedPricing.value = expandedPricing.value === planId ? null : planId;
};

const togglePricingRowMobile = (planId) => {
    expandedPricingMobile.value =
        expandedPricingMobile.value === planId ? null : planId;
};

const formatBillingPeriod = (period) => {
    const map = {
        monthly: __("Monthly"),
        yearly: __("Yearly"),
        weekly: __("Weekly"),
        daily: __("Daily"),
    };
    return map[period] || period;
};
</script>
