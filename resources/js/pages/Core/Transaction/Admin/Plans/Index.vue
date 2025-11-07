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
            class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-8 py-4 rounded-b-3xl shadow-lg"
        >
            <div
                class="flex flex-col md:flex-row md:items-center justify-between"
            >
                <div class="flex-1 mb-6 md:mb-0">
                    <h1 class="text-4xl font-bold mb-2">
                        {{ __("Subscription Plans") }}
                    </h1>
                    <p class="text-blue-100 text-lg opacity-90">
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

        <!-- PLANS TABLE -->
        <section class="px-4 py-8 max-w-7xl mx-auto">
            <div
                class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200"
            >
                <!-- TABLE FOR LARGE SCREENS -->
                <div class="hidden md:block">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700"
                                >
                                    {{ __("Plan") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700"
                                >
                                    {{ __("Status") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700"
                                >
                                    {{ __("Features") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700"
                                >
                                    {{ __("Pricing") }}
                                </th>
                                <th
                                    class="py-4 px-6 text-left text-sm font-semibold text-gray-700"
                                >
                                    {{ __("Actions") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template v-for="plan in plans" :key="plan.id">
                                <!-- MAIN ROW -->
                                <tr class="hover:bg-gray-50 transition-colors">
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
                                                    class="text-sm font-medium text-gray-900"
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
                                                    ? 'bg-green-100 text-green-800 border-green-200'
                                                    : 'bg-gray-100 text-gray-800 border-gray-200',
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
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200"
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
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"
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
                                                class="flex items-center text-blue-600 font-semibold mb-2"
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
                                                    <span class="text-gray-600"
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
                                                        class="text-blue-500 hover:text-blue-700 text-xs flex items-center"
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
                                                    class="mt-2 border border-gray-200 rounded-lg overflow-hidden"
                                                >
                                                    <div
                                                        v-for="(
                                                            item, index
                                                        ) in plan.scopes"
                                                        :key="`${plan.id}-${index}`"
                                                        class="border-b border-gray-100 last:border-b-0 bg-white"
                                                    >
                                                        <div class="p-3">
                                                            <div
                                                                class="font-medium text-gray-800"
                                                            >
                                                                {{
                                                                    item.service
                                                                        .group
                                                                        .name
                                                                }}
                                                            </div>
                                                            <div
                                                                class="text-sm text-gray-600"
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
                                                class="text-center py-2 text-gray-500 text-sm"
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
                                                class="flex items-center text-green-600 font-semibold mb-2"
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
                                                    <span class="text-gray-600"
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
                                                        class="text-blue-500 hover:text-blue-700 text-xs flex items-center"
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
                                                class="text-center py-2 text-gray-500 text-sm"
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
                                            <v-update
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
                                    class="bg-blue-50 border-b border-blue-200"
                                >
                                    <td colspan="5" class="p-6">
                                        <div
                                            class="bg-white rounded-lg shadow-sm border border-blue-200"
                                        >
                                            <div
                                                class="flex items-center justify-between mb-4 p-4 border-b border-blue-100"
                                            >
                                                <h3
                                                    class="text-lg font-semibold text-blue-800"
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
                                                    class="text-blue-600 hover:text-blue-800 flex items-center text-sm"
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
                                                    class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                                                >
                                                    <div
                                                        class="flex items-center justify-between mb-3"
                                                    >
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold"
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
                                                                class="text-gray-600 text-sm"
                                                                >{{
                                                                    __(
                                                                        "Amount"
                                                                    )
                                                                }}:</span
                                                            >
                                                            <span
                                                                class="font-bold text-lg text-gray-900"
                                                            >
                                                                {{
                                                                    price.amount_format
                                                                }}
                                                                <span
                                                                    class="text-sm text-gray-600 ml-1"
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
                    <div class="divide-y divide-gray-200">
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
                                        <div class="font-medium text-gray-900">
                                            {{ plan.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                plan.active
                                                    ? __("Active")
                                                    : __("Inactive")
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <v-update
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
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200"
                                >
                                    <i class="mdi mdi-gift text-xs mr-1"></i>
                                    {{ __("Bonus") }}: {{ plan.bonus_duration }}
                                    {{ __("days") }}
                                </span>

                                <span
                                    v-if="plan.trial_enabled"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"
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
                                    class="w-full flex justify-between items-center p-2 bg-gray-50 rounded-lg"
                                >
                                    <div
                                        class="flex items-center text-blue-600 font-medium"
                                    >
                                        <i
                                            class="mdi mdi-key-chain-variant mr-2"
                                        ></i>
                                        {{ __("Access Scopes") }}
                                        <span class="ml-2 text-gray-500 text-sm"
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
                                    class="mt-2 border border-gray-200 rounded-lg overflow-hidden"
                                >
                                    <div
                                        v-for="(item, index) in plan.scopes"
                                        :key="`${plan.id}-${index}`"
                                        class="border-b border-gray-100 last:border-b-0 bg-white"
                                    >
                                        <div class="p-3">
                                            <div
                                                class="font-medium text-gray-800"
                                            >
                                                {{ item.service.group.name }}
                                            </div>
                                            <div class="text-sm text-gray-600">
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
                                    class="w-full flex justify-between items-center p-2 bg-gray-50 rounded-lg"
                                >
                                    <div
                                        class="flex items-center text-green-600 font-medium"
                                    >
                                        <i
                                            class="mdi mdi-currency-usd mr-2"
                                        ></i>
                                        {{ __("Pricing") }}
                                        <span class="ml-2 text-gray-500 text-sm"
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
                                    class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4"
                                >
                                    <div
                                        class="flex items-center justify-between mb-3"
                                    >
                                        <h4 class="text-blue-800 font-semibold">
                                            {{ __("Pricing Details") }}
                                        </h4>
                                        <button
                                            @click="
                                                togglePricingRowMobile(plan.id)
                                            "
                                            class="text-blue-600 hover:text-blue-800 text-sm"
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
                                            class="bg-white border border-gray-200 rounded-lg p-3"
                                        >
                                            <div
                                                class="flex items-center justify-between mb-2"
                                            >
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold"
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
                                                        class="text-gray-600 text-sm"
                                                        >{{
                                                            __("Amount")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="font-bold text-gray-900"
                                                    >
                                                        {{
                                                            price.amount_format
                                                        }}
                                                        <span
                                                            class="text-xs text-gray-600 ml-1"
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
                                                    <span class="text-gray-600"
                                                        >{{
                                                            __("Expires")
                                                        }}:</span
                                                    >
                                                    <span
                                                        class="text-gray-800"
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
                class="text-center py-24 text-gray-500 border-2 border-dashed border-gray-300 rounded-2xl mt-8"
            >
                <i
                    class="mdi mdi-clipboard-list-outline text-5xl text-gray-300 mb-4"
                ></i>
                <p class="text-lg font-semibold">
                    {{ __("No subscription plans available") }}
                </p>
                <p class="text-gray-400 mb-6">
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

<script>
import VAdminTransactionLayout from "@/layouts/VAdminTransactionLayout.vue";
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VUpdate from "./Updated.vue";
import VRevokeScope from "./RevokeScope.vue";
import VDeletePrice from "./DeletePrice.vue";
import VPaginate from "@/components/VPaginate.vue";

export default {
    components: {
        VAdminTransactionLayout,
        VCreate,
        VDelete,
        VUpdate,
        VRevokeScope,
        VDeletePrice,
    },
    data() {
        return {
            plans: [],
            expandedPlans: {},
            expandedPricing: null,
            expandedPricingMobile: null,
            search: { page: 1, per_page: 15 },
            pages: { total_pages: 0 },
        };
    },

    created() {
        this.getPlans();
    },
    methods: {
        async getPlans(params) {
            try {
                const res = await this.$server.get(
                    this.$page.props.route.plans,
                    {
                        params: { ...this.search, ...params },
                    }
                );
                if (res.status === 200) {
                    this.plans = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.expandedPlans = {};
                    this.expandedPricing = null;
                    this.expandedPricingMobile = null;
                }
            } catch (e) {
                console.error(
                    e?.response?.data?.message || "Failed to load plans"
                );
            }
        },

        togglePlanExpansion(planId, section) {
            const key = `${planId}-${section}`;
            this.expandedPlans[key] = !this.expandedPlans[key];
        },

        isPlanExpanded(planId, section) {
            return !!this.expandedPlans[`${planId}-${section}`];
        },

        togglePricingRow(planId) {
            // If clicking the same row, toggle it
            // If clicking a different row, close the current one and open the new one
            if (this.expandedPricing === planId) {
                this.expandedPricing = null;
            } else {
                this.expandedPricing = planId;
            }
        },

        togglePricingRowMobile(planId) {
            if (this.expandedPricingMobile === planId) {
                this.expandedPricingMobile = null;
            } else {
                this.expandedPricingMobile = planId;
            }
        },

        formatBillingPeriod(period) {
            const map = {
                monthly: this.__("Monthly"),
                yearly: this.__("Yearly"),
                weekly: this.__("Weekly"),
                daily: this.__("Daily"),
            };
            return map[period] || period;
        },
        pageBtnClass(disabled) {
            return [
                "flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-200 border",
                disabled
                    ? "bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed"
                    : "bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400",
            ];
        },
        pageClass(page) {
            return [
                "flex items-center justify-center w-10 h-10 rounded-xl text-sm font-semibold transition-all duration-200 border",
                this.search.page === page
                    ? "bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg border-blue-600"
                    : "bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400",
            ];
        },
    },
};
</script>
