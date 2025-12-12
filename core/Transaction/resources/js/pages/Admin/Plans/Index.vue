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
        <header
            class="bg-green-600 text-white px-4 py-3 rounded-b-2xl shadow dark:bg-gray-800 dark:text-gray-100"
        >
            <div
                class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3"
            >
                <div class="flex-1 min-w-0">
                    <h1 class="text-lg font-bold truncate">
                        {{ __("Subscription Plans") }}
                    </h1>
                    <p
                        class="text-blue-100 text-xs opacity-90 dark:text-gray-400 truncate mt-1"
                    >
                        {{
                            __(
                                "Create, edit, and manage subscription offerings"
                            )
                        }}
                    </p>
                </div>
                <div class="shrink-0">
                    <v-create @created="getPlans" />
                </div>
            </div>
        </header>

        <section
            class="px-3 py-4 bg-white shadow-sm rounded-b-xl border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700"
        >
            <div
                class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3"
            >
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Search") }}
                    </label>
                    <input
                        v-model="search.name"
                        @input="getPlans"
                        type="text"
                        :placeholder="__('Plan name')"
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    />
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Status") }}
                    </label>
                    <select
                        v-model="search.active"
                        @change="getPlans"
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    >
                        <option value="">{{ __("All") }}</option>
                        <option value="1">{{ __("Active") }}</option>
                        <option value="0">{{ __("Inactive") }}</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Bonus") }}
                    </label>
                    <select
                        v-model="search.bonus_activated"
                        @change="getPlans"
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    >
                        <option value="">{{ __("All") }}</option>
                        <option value="1">{{ __("Yes") }}</option>
                        <option value="0">{{ __("No") }}</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Bonus Days") }}
                    </label>
                    <input
                        v-model="search.bonus_duration"
                        @input="getPlans"
                        type="number"
                        min="0"
                        :placeholder="__('Days')"
                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                    />
                </div>
            </div>
        </section>

        <section class="px-3 py-4 max-w-7xl mx-auto">
            <div
                class="bg-white rounded-xl shadow overflow-hidden border border-gray-200 dark:bg-gray-900 dark:border-gray-700"
            >
                <div class="hidden md:block">
                    <table class="w-full">
                        <thead
                            class="bg-gray-50 border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700"
                        >
                            <tr>
                                <th
                                    class="py-3 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Plan") }}
                                </th>
                                <th
                                    class="py-3 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Status") }}
                                </th>
                                <th
                                    class="py-3 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Features") }}
                                </th>
                                <th
                                    class="py-3 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300"
                                >
                                    {{ __("Pricing") }}
                                </th>
                                <th
                                    class="py-3 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300"
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
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800"
                                >
                                    <!-- PLAN NAME -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div
                                                class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-white"
                                                :class="
                                                    plan.active
                                                        ? 'bg-green-500'
                                                        : 'bg-gray-500'
                                                "
                                            >
                                                <i
                                                    class="mdi mdi-crown text-sm"
                                                ></i>
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <div
                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate"
                                                >
                                                    {{ plan.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- STATUS -->
                                    <td class="py-3 px-4">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border',
                                                plan.active
                                                    ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900 dark:text-green-200'
                                                    : 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-200',
                                            ]"
                                        >
                                            <i
                                                :class="
                                                    plan.active
                                                        ? 'mdi mdi-check-circle'
                                                        : 'mdi mdi-close-circle'
                                                "
                                                class="text-xs mr-1"
                                            ></i>
                                            {{
                                                plan.active
                                                    ? __("Active")
                                                    : __("Inactive")
                                            }}
                                        </span>

                                        <div
                                            class="mt-1.5 flex flex-wrap gap-1"
                                        >
                                            <span
                                                v-if="plan.bonus_enabled"
                                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200"
                                            >
                                                <i
                                                    class="mdi mdi-gift text-xs mr-1"
                                                ></i>
                                                {{ plan.bonus_duration }}d
                                            </span>

                                            <span
                                                v-if="plan.trial_enabled"
                                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                                            >
                                                <i
                                                    class="mdi mdi-clock text-xs mr-1"
                                                ></i>
                                                {{ plan.trial_duration }}d
                                            </span>
                                        </div>
                                    </td>

                                    <!-- ACCESS SCOPES -->
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <div
                                                class="flex items-center text-blue-600 dark:text-blue-400 font-medium mb-1"
                                            >
                                                <i
                                                    class="mdi mdi-key-chain-variant mr-1 text-xs"
                                                ></i>
                                                <span class="text-xs">{{
                                                    __("Scopes")
                                                }}</span>
                                            </div>

                                            <div
                                                v-if="plan.scopes.length"
                                                class="text-xs"
                                            >
                                                <div
                                                    class="flex items-center justify-between"
                                                >
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400"
                                                    >
                                                        {{ plan.scopes.length }}
                                                        {{ __("scope(s)") }}
                                                    </span>
                                                    <button
                                                        @click="
                                                            togglePlanExpansion(
                                                                plan.id,
                                                                'scopes'
                                                            )
                                                        "
                                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-400 text-xs flex items-center"
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
                                                                'mdi ml-0.5 transition-transform text-xs',
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

                                                <!-- Expanded scopes -->
                                                <div
                                                    v-if="
                                                        isPlanExpanded(
                                                            plan.id,
                                                            'scopes'
                                                        )
                                                    "
                                                    class="mt-2 border border-gray-200 dark:border-gray-600 rounded overflow-hidden"
                                                >
                                                    <div
                                                        v-for="(
                                                            item, index
                                                        ) in plan.scopes"
                                                        :key="`${plan.id}-${index}`"
                                                        class="border-b border-gray-100 dark:border-gray-700 last:border-b-0 bg-white dark:bg-gray-800"
                                                    >
                                                        <div class="p-2">
                                                            <div
                                                                class="font-medium text-gray-800 dark:text-gray-200 text-xs truncate"
                                                            >
                                                                {{
                                                                    item.service
                                                                        .group
                                                                        .name
                                                                }}
                                                            </div>
                                                            <div
                                                                class="text-gray-600 dark:text-gray-400 text-xs truncate"
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
                                                            <div class="mt-1">
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
                                                class="text-center py-1 text-gray-500 dark:text-gray-400 text-xs"
                                            >
                                                <i
                                                    class="mdi mdi-key-remove mr-0.5"
                                                ></i>
                                                {{ __("No scopes") }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- PRICING -->
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <div
                                                class="flex items-center text-green-600 dark:text-green-400 font-medium mb-1"
                                            >
                                                <i
                                                    class="mdi mdi-currency-usd mr-1 text-xs"
                                                ></i>
                                                <span class="text-xs">{{
                                                    __("Pricing")
                                                }}</span>
                                            </div>

                                            <div
                                                v-if="plan.prices.length"
                                                class="text-xs"
                                            >
                                                <div
                                                    class="flex items-center justify-between"
                                                >
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400"
                                                    >
                                                        {{ plan.prices.length }}
                                                        {{ __("price(s)") }}
                                                    </span>
                                                    <button
                                                        @click="
                                                            togglePricingRow(
                                                                plan.id
                                                            )
                                                        "
                                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-400 text-xs flex items-center"
                                                    >
                                                        {{
                                                            expandedPricing ===
                                                            plan.id
                                                                ? __("Hide")
                                                                : __("View")
                                                        }}
                                                        <i
                                                            :class="[
                                                                'mdi ml-0.5 transition-transform text-xs',
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
                                                class="text-center py-1 text-gray-500 dark:text-gray-400 text-xs"
                                            >
                                                <i
                                                    class="mdi mdi-cash-remove mr-0.5"
                                                ></i>
                                                {{ __("No prices") }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-1.5">
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
                                    class="bg-blue-50 dark:bg-blue-900/20"
                                >
                                    <td colspan="5" class="p-4">
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg border border-blue-200 dark:border-blue-700"
                                        >
                                            <div
                                                class="flex items-center justify-between mb-3 p-3 border-b border-blue-100 dark:border-blue-800"
                                            >
                                                <h3
                                                    class="text-blue-800 dark:text-blue-300 font-semibold text-sm truncate"
                                                >
                                                    <i
                                                        class="mdi mdi-currency-usd mr-1"
                                                    ></i>
                                                    {{ __("Pricing") }}:
                                                    {{ plan.name }}
                                                </h3>
                                                <button
                                                    @click="
                                                        togglePricingRow(
                                                            plan.id
                                                        )
                                                    "
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center text-xs"
                                                >
                                                    {{ __("Close") }}
                                                    <i
                                                        class="mdi mdi-close ml-0.5 text-sm"
                                                    ></i>
                                                </button>
                                            </div>

                                            <div
                                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-3"
                                            >
                                                <div
                                                    v-for="(
                                                        price, index
                                                    ) in plan.prices"
                                                    :key="index"
                                                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded p-3"
                                                >
                                                    <div
                                                        class="flex items-center justify-between mb-2"
                                                    >
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-xs font-semibold"
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
                                                            class="flex justify-between items-center"
                                                        >
                                                            <span
                                                                class="text-gray-600 dark:text-gray-400 text-xs"
                                                                >{{
                                                                    __(
                                                                        "Amount"
                                                                    )
                                                                }}:</span
                                                            >
                                                            <span
                                                                class="font-bold text-gray-900 dark:text-gray-100 text-sm"
                                                            >
                                                                {{
                                                                    price.amount_format
                                                                }}
                                                                <span
                                                                    class="text-xs text-gray-600 dark:text-gray-400 ml-0.5"
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

                <div class="md:hidden">
                    <div class="p-3 space-y-3">
                        <div
                            v-for="plan in plans"
                            :key="plan.id"
                            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center min-w-0">
                                    <div
                                        class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-white mr-2"
                                        :class="
                                            plan.active
                                                ? 'bg-green-500'
                                                : 'bg-gray-500'
                                        "
                                    >
                                        <i class="mdi mdi-crown text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div
                                            class="font-medium text-gray-900 dark:text-gray-100 text-sm truncate"
                                        >
                                            {{ plan.name }}
                                        </div>
                                        <div
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                plan.active
                                                    ? __("Active")
                                                    : __("Inactive")
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
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

                            <div class="flex flex-wrap gap-1 mb-3">
                                <span
                                    v-if="plan.bonus_enabled"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200"
                                >
                                    <i class="mdi mdi-gift text-xs mr-0.5"></i>
                                    {{ plan.bonus_duration }}d
                                </span>

                                <span
                                    v-if="plan.trial_enabled"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                                >
                                    <i class="mdi mdi-clock text-xs mr-0.5"></i>
                                    {{ plan.trial_duration }}d
                                </span>
                            </div>

                            <div class="mb-3">
                                <button
                                    @click="
                                        togglePlanExpansion(plan.id, 'scopes')
                                    "
                                    class="w-full flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700/50 rounded text-xs"
                                >
                                    <div
                                        class="flex items-center text-blue-600 dark:text-blue-400 font-medium"
                                    >
                                        <i
                                            class="mdi mdi-key-chain-variant mr-1.5 text-sm"
                                        ></i>
                                        {{ __("Scopes") }}
                                        <span
                                            class="ml-1.5 text-gray-500 dark:text-gray-400"
                                            >({{ plan.scopes.length }})</span
                                        >
                                    </div>
                                    <i
                                        :class="[
                                            'mdi transition-transform text-sm',
                                            isPlanExpanded(plan.id, 'scopes')
                                                ? 'mdi-chevron-up'
                                                : 'mdi-chevron-down',
                                        ]"
                                    ></i>
                                </button>

                                <div
                                    v-if="isPlanExpanded(plan.id, 'scopes')"
                                    class="mt-2 border border-gray-200 dark:border-gray-600 rounded overflow-hidden"
                                >
                                    <div
                                        v-for="(item, index) in plan.scopes"
                                        :key="`${plan.id}-${index}`"
                                        class="border-b border-gray-100 dark:border-gray-700 last:border-b-0 bg-white dark:bg-gray-800"
                                    >
                                        <div class="p-2">
                                            <div
                                                class="font-medium text-gray-800 dark:text-gray-200 text-xs truncate"
                                            >
                                                {{ item.service.group.name }}
                                            </div>
                                            <div
                                                class="text-gray-600 dark:text-gray-400 text-xs truncate"
                                            >
                                                {{ item.service.name }}
                                            </div>
                                            <div class="mt-1">
                                                <v-revoke-scope
                                                    :item="item"
                                                    @revoked="getPlans"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button
                                    @click="togglePricingRowMobile(plan.id)"
                                    class="w-full flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700/50 rounded text-xs"
                                >
                                    <div
                                        class="flex items-center text-green-600 dark:text-green-400 font-medium"
                                    >
                                        <i
                                            class="mdi mdi-currency-usd mr-1.5 text-sm"
                                        ></i>
                                        {{ __("Pricing") }}
                                        <span
                                            class="ml-1.5 text-gray-500 dark:text-gray-400"
                                            >({{ plan.prices.length }})</span
                                        >
                                    </div>
                                    <i
                                        :class="[
                                            'mdi transition-transform text-sm',
                                            expandedPricingMobile === plan.id
                                                ? 'mdi-chevron-up'
                                                : 'mdi-chevron-down',
                                        ]"
                                    ></i>
                                </button>

                                <div
                                    v-if="expandedPricingMobile === plan.id"
                                    class="mt-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3"
                                >
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <h4
                                            class="text-blue-800 dark:text-blue-300 font-semibold text-sm"
                                        >
                                            {{ __("Pricing") }}
                                        </h4>
                                        <button
                                            @click="
                                                togglePricingRowMobile(plan.id)
                                            "
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-xs"
                                        >
                                            {{ __("Close") }}
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        <div
                                            v-for="(
                                                price, index
                                            ) in plan.prices"
                                            :key="index"
                                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded p-2"
                                        >
                                            <div
                                                class="flex items-center justify-between mb-1.5"
                                            >
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-xs font-semibold"
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
                                            <div class="space-y-0.5">
                                                <div
                                                    class="flex justify-between"
                                                >
                                                    <span
                                                        class="text-gray-600 dark:text-gray-400 text-xs"
                                                        >{{
                                                            __("Amount")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-bold text-gray-900 dark:text-gray-100 text-xs"
                                                    >
                                                        {{
                                                            price.amount_format
                                                        }}
                                                        <span
                                                            class="text-xs text-gray-600 dark:text-gray-400 ml-0.5"
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="!plans.length"
                class="flex flex-col items-center text-center py-12 text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-600 rounded-xl mt-6"
            >
                <i
                    class="mdi mdi-clipboard-list-outline text-4xl text-gray-300 dark:text-gray-600 mb-3"
                ></i>
                <p class="text-base font-semibold mb-2">
                    {{ __("No plans available") }}
                </p>
                <p class="text-gray-400 dark:text-gray-500 text-sm mb-4">
                    {{ __("Create your first plan") }}
                </p>
                <v-create @created="getPlans" />
            </div>
        </section>

        <div class="px-3 pb-4">
            <v-paginate
                v-if="pages.total_pages > 1"
                :total-pages="pages.total_pages"
                v-model="search.page"
                @change="getPlans"
            />
        </div>
    </v-admin-transaction-layout>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import VAdminTransactionLayout from "@/components/VGeneralLayout.vue";
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

<style>
@media (max-width: 475px) {
    button,
    [role="button"],
    .mdi {
        min-height: 44px;
        min-width: 44px;
    }

    .text-xs {
        font-size: 0.75rem;
        line-height: 1rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

* {
    -webkit-tap-highlight-color: transparent;
}
</style>
