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
    <v-guest-layout>
        <div
            class="min-h-screen bg-linear-to-br from-slate-50 to-blue-50/30 dark:from-slate-900 dark:to-slate-800/30 py-2"
        >
            <!-- Header Compacto -->
            <div class="text-center mb-4 px-2">
                <h1
                    class="text-2xl font-bold text-slate-800 dark:text-slate-200 mb-2"
                >
                    {{ __("Available Plans") }}
                </h1>
                <p
                    class="text-slate-600 dark:text-slate-400 max-w-md mx-auto text-sm"
                >
                    {{ __("Choose the plan that fits your needs") }}
                </p>
            </div>

            <!-- Filtros -->
            <div class="w-full mx-auto px-2 lg:px-4 mb-6">
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4"
                >
                    <div class="flex flex-col sm:flex-row gap-2 items-center">
                        <!-- Buscar -->
                        <div class="flex-1 min-w-0">
                            <div class="relative">
                                <i
                                    class="mdi mdi-magnify absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm"
                                ></i>
                                <input
                                    v-model="search.name"
                                    type="text"
                                    :placeholder="__('Search plans...')"
                                    class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 cursor-text text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                                    @input="debouncedSearch"
                                />
                            </div>
                        </div>

                        <!-- Filtro Periodo -->
                        <div class="w-full sm:w-48">
                            <select
                                v-model="search.period"
                                @change="getPlans"
                                class="w-full px-3 py-2 text-sm bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 cursor-pointer text-slate-900 dark:text-slate-100"
                            >
                                <option value="">
                                    {{ __("All Billing Periods") }}
                                </option>
                                <option
                                    v-for="period in availablePeriods"
                                    :key="period"
                                    :value="period"
                                >
                                    {{ formatPeriodName(period) }}
                                </option>
                            </select>
                        </div>

                        <!-- Reset -->
                        <button
                            @click="resetFilters"
                            class="px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors whitespace-nowrap cursor-pointer"
                        >
                            {{ __("Clear Filters") }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid de Planes -->
            <div class="w-full max-w-6xl mx-auto px-4">
                <!-- Loading -->
                <div v-if="loading" class="flex justify-center py-8">
                    <div
                        class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 dark:border-blue-400"
                    ></div>
                </div>

                <!-- Empty State -->
                <div v-else-if="!plans.length" class="text-center py-12">
                    <i
                        class="mdi mdi-magnify-remove-outline text-4xl text-slate-300 dark:text-slate-600 mb-3"
                    ></i>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        {{ __("No plans found") }}
                    </p>
                </div>

                <!-- Planes -->
                <div
                    v-else
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                >
                    <div
                        v-for="plan in plans"
                        :key="plan.id"
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md dark:hover:shadow-slate-900/50 transition-all duration-200"
                        :class="{
                            'ring-2 ring-blue-500 border-blue-300 dark:ring-blue-400 dark:border-blue-600':
                                selectedPlans[plan.id]?.selected,
                        }"
                    >
                        <!-- Header del Plan -->
                        <div
                            class="p-4 border-b border-slate-100 dark:border-slate-700"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <h3
                                    class="font-semibold text-slate-800 dark:text-slate-200 text-base"
                                >
                                    {{ plan.name }}
                                </h3>
                                <div class="flex gap-2">
                                    <span
                                        v-if="plan.bonus_enabled"
                                        class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded-full font-medium"
                                    >
                                        +{{ plan.bonus_duration }}d
                                    </span>
                                    <!-- Botón para ver detalles -->
                                    <button
                                        @click="openPlanDetails(plan)"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-xs font-medium cursor-pointer flex items-center space-x-1"
                                    >
                                        <i
                                            class="mdi mdi-information-outline text-sm"
                                        ></i>
                                        <span>Details</span>
                                    </button>
                                </div>
                            </div>
                            <p
                                class="text-slate-600 dark:text-slate-400 text-sm line-clamp-2"
                                v-html="plan.description"
                            ></p>
                        </div>

                        <!-- Servicios Incluidos -->
                        <div
                            class="p-3 bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700"
                        >
                            <div class="flex flex-wrap gap-1">
                                <span
                                    v-for="(scope, index) in getUniqueServices(
                                        plan.scopes
                                    )"
                                    :key="index"
                                    class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded text-xs font-medium"
                                >
                                    <i
                                        class="mdi mdi-check-circle text-xs mr-1"
                                    ></i>
                                    {{ scope.service.name }}
                                </span>
                            </div>
                        </div>

                        <!-- Precios con Selección -->
                        <div class="p-4">
                            <div class="space-y-2 mb-3">
                                <div
                                    v-for="price in getDisplayPrices(
                                        plan.prices
                                    )"
                                    :key="price.id"
                                    class="flex justify-between items-center p-2 rounded-lg border transition-all duration-200 cursor-pointer"
                                    :class="{
                                        'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20':
                                            selectedPlans[plan.id]?.priceId ===
                                            price.id,
                                        'border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10':
                                            selectedPlans[plan.id]?.priceId !==
                                            price.id,
                                    }"
                                    @click="selectPrice(plan, price)"
                                >
                                    <div class="flex items-center space-x-2">
                                        <!-- Radio Button Visual -->
                                        <div
                                            class="flex items-center justify-center w-4 h-4 rounded-full border-2 transition-all"
                                            :class="{
                                                'border-blue-500 dark:border-blue-400 bg-blue-500 dark:bg-blue-400':
                                                    selectedPlans[plan.id]
                                                        ?.priceId === price.id,
                                                'border-slate-400 dark:border-slate-500':
                                                    selectedPlans[plan.id]
                                                        ?.priceId !== price.id,
                                            }"
                                        >
                                            <div
                                                v-if="
                                                    selectedPlans[plan.id]
                                                        ?.priceId === price.id
                                                "
                                                class="w-1.5 h-1.5 rounded-full bg-white dark:bg-slate-900"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-slate-600 dark:text-slate-400 text-sm capitalize"
                                        >
                                            {{
                                                formatPeriodName(
                                                    price.billing_period
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <span
                                        class="font-semibold text-slate-800 dark:text-slate-200 text-sm"
                                    >
                                        {{ price.currency }}
                                        {{ price.amount_format }}
                                    </span>
                                </div>
                            </div>

                            <!-- Ver más precios -->
                            <div
                                v-if="plan.prices.length > 3"
                                class="mb-3 pt-2 border-t border-slate-100 dark:border-slate-700"
                            >
                                <button
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-xs font-medium cursor-pointer"
                                    @click="openPlanDetails(plan)"
                                >
                                    +{{ plan.prices.length - 3 }}
                                    {{ __("more options") }}
                                </button>
                            </div>

                            <!-- CTA Principal -->
                            <button
                                @click="selectPlan(plan)"
                                :disabled="!selectedPlans[plan.id]"
                                class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 disabled:bg-slate-400 dark:disabled:bg-slate-600 disabled:cursor-not-allowed text-white py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 cursor-pointer disabled:transform-none hover:scale-[1.02] active:scale-[0.98]"
                            >
                                <span
                                    class="flex items-center justify-center space-x-2"
                                >
                                    <span>{{ __("Select Plan") }}</span>
                                    <i
                                        v-if="selectedPlans[plan.id]"
                                        class="mdi mdi-arrow-right text-sm"
                                    ></i>
                                    <i
                                        v-else
                                        class="mdi mdi-lock-outline text-sm"
                                    ></i>
                                </span>
                            </button>

                            <!-- Estado de selección -->
                            <div
                                v-if="selectedPlans[plan.id]"
                                class="mt-2 text-center"
                            >
                                <span
                                    class="inline-flex items-center px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full text-xs font-medium"
                                >
                                    <i
                                        class="mdi mdi-check-circle text-xs mr-1"
                                    ></i>
                                    {{ selectedPlans[plan.id].billingPeriod }}
                                    selected
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                <v-paginate
                    v-model="search.page"
                    :total-pages="pages.total_pages"
                    @change="getPlans"
                />
            </div>

            <!-- Modal Detalles del Plan -->
            <v-modal
                v-model="showPlanDetails"
                panel-class="w-full lg:w-7xl"
            >
                <template #body>
                    <!-- Header -->
                    <div
                        class="bg-slate-800 dark:bg-slate-900 px-2 lg:px-6 py-2 md:py-4 flex justify-between items-center rounded"
                    >
                        <div>
                            <h3 class="text-md lg:text-xl font-bold text-white">
                                {{ selected_plan_details?.name }}
                            </h3>
                            <p
                                class="text-slate-300 dark:text-slate-400 text-sm mt-1"
                            >
                                {{ __("Plan details and pricing") }}
                            </p>
                        </div>
                        <button
                            @click="showPlanDetails = false"
                            class="text-slate-300 dark:text-slate-400 hover:text-white dark:hover:text-slate-200 transition-colors cursor-pointer"
                        >
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>

                    <!-- Contenido -->
                    <div class="p-2 md:p-4 lg:p-6">
                        <!-- Descripción -->
                        <div class="mb-6">
                            <h4
                                class="font-semibold text-slate-800 dark:text-slate-200 mb-2"
                            >
                                {{ __("Description") }}
                            </h4>
                            <div
                                class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed"
                                v-html="selected_plan_details?.description"
                            ></div>
                        </div>

                        <!-- Servicios y Roles -->
                        <div class="mb-6">
                            <h4
                                class="font-semibold text-slate-800 dark:text-slate-200 mb-3"
                            >
                                {{ __("Included Services") }}
                            </h4>
                            <div class="space-y-3">
                                <div
                                    v-for="scope in selected_plan_details?.scopes"
                                    :key="scope.id"
                                    class="border border-slate-200 dark:border-slate-700 rounded-lg p-3"
                                >
                                    <div
                                        class="flex justify-between items-start mb-2"
                                    >
                                        <span
                                            class="font-medium text-slate-800 dark:text-slate-200"
                                            >{{ __(scope.service.name) }}</span
                                        >
                                        <span
                                            class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs px-2 py-1 rounded-full"
                                        >
                                            {{ __(scope.role.name) }}
                                        </span>
                                    </div>
                                    <p
                                        class="text-slate-600 dark:text-slate-400 text-xs"
                                    >
                                        {{ __(scope.role.description) }}
                                    </p>
                                    <p
                                        class="text-slate-500 dark:text-slate-500 text-xs mt-1"
                                    >
                                        {{ __(scope.service.description) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Todos los Precios con Selección Mejorada -->
                        <div>
                            <h4
                                class="font-semibold text-md md:text-lg lg:text-3xl text-slate-800 dark:text-slate-200 mb-3"
                            >
                                {{ __("Pricing Options") }}
                            </h4>
                            <div class="space-y-3">
                                <div
                                    v-for="price in selected_plan_details?.prices"
                                    :key="price.id"
                                    class="p-2 lg:p-4 border-2 rounded-xl transition-all duration-200 cursor-pointer"
                                    :class="{
                                        'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20 shadow-md scale-[1.02]':
                                            selected_period?.id === price.id,
                                        'border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10':
                                            selected_period?.id !== price.id,
                                    }"
                                    @click="selectPriceFromModal(price)"
                                >
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <div
                                            class="flex items-center lg:space-x-3"
                                        >
                                            <!-- Radio Button Grande -->
                                            <div
                                                class="flex items-center justify-center w-5 h-5 rounded-full border-2 transition-all"
                                                :class="{
                                                    'border-blue-500 dark:border-blue-400 bg-blue-500 dark:bg-blue-400':
                                                        selected_period?.id ===
                                                        price.id,
                                                    'border-slate-400 dark:border-slate-500':
                                                        selected_period?.id !==
                                                        price.id,
                                                }"
                                            >
                                                <div
                                                    v-if="
                                                        selected_period?.id ===
                                                        price.id
                                                    "
                                                    class="w-2 h-2 rounded-full bg-white dark:bg-slate-900"
                                                ></div>
                                            </div>
                                            <div>
                                                <div
                                                    class="font-semibold text-slate-800 dark:text-slate-200 capitalize text-md lg:text-lg"
                                                >
                                                    {{
                                                        formatPeriodName(
                                                            price.billing_period
                                                        )
                                                    }}
                                                </div>
                                                <div
                                                    class="text-slate-500 dark:text-slate-500 text-xs"
                                                >
                                                    {{ __("Expires") }}:
                                                    {{
                                                        formatDate(
                                                            price.expiration
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div
                                                class="font-bold text-slate-800 dark:text-slate-200 text-xl"
                                            >
                                                {{ price.currency }}
                                                {{ price.amount_format }}
                                            </div>
                                            <div
                                                class="text-green-600 dark:text-green-400 text-sm font-medium"
                                            >
                                                {{ __("Available") }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Badge de selección -->
                                    <div
                                        v-if="selected_period?.id === price.id"
                                        class="flex justify-center mt-2"
                                    >
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-xs font-medium"
                                        >
                                            <i
                                                class="mdi mdi-check-circle text-xs mr-1"
                                            ></i>
                                            {{ __("Selected") }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bonificación -->
                        <div
                            v-if="selected_plan_details?.bonus_enabled"
                            class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"
                        >
                            <div class="flex items-center">
                                <i
                                    class="mdi mdi-gift text-green-600 dark:text-green-400 text-lg mr-3"
                                ></i>
                                <div>
                                    <div
                                        class="font-semibold text-green-800 dark:text-green-300 text-sm"
                                    >
                                        {{ __("Special Bonus") }}
                                    </div>
                                    <div
                                        class="text-green-700 dark:text-green-400 text-xs"
                                    >
                                        {{ __("Get") }} +{{
                                            selected_plan_details.bonus_duration
                                        }}
                                        {{ __("extra days") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="border-t border-slate-200 dark:border-slate-700 lg:px-6 py-4 bg-slate-50 dark:bg-slate-800"
                    >
                        <div class="flex justify-between items-center">
                            <div>
                                <div
                                    v-if="selected_period"
                                    class="text-sm text-slate-600 dark:text-slate-400"
                                >
                                    <span
                                        class="font-semibold text-slate-800 dark:text-slate-200"
                                        >{{ selected_period.currency }}
                                        {{
                                            selected_period.amount_format
                                        }}</span
                                    >
                                    ·
                                    {{
                                        formatPeriodName(
                                            selected_period.billing_period
                                        )
                                    }}
                                </div>
                                <div
                                    v-else
                                    class="text-sm text-slate-500 dark:text-slate-500"
                                >
                                    {{ __("Select a pricing option") }}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    @click="showPlanDetails = false"
                                    class="px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                                >
                                    {{ __("Close") }}
                                </button>
                                <button
                                    @click="proceedWithSelectedPlan"
                                    :disabled="!selected_period"
                                    class="px-4 py-2 text-sm bg-blue-600 dark:bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                >
                                    {{ __("Continue") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </v-modal>

            <!-- Modal de Suscripción -->
            <v-modal
                v-model="showSubscription"
                panel-class="w-full lg:w-5xl"
                :title="__('Complete Subscription')"
            >
                <template #body>
                    <div class="md:p-2 lg:p-6">
                        <div class="text-center mb-6">
                            <i
                                class="mdi mdi-lock-check-outline text-3xl text-blue-600 dark:text-blue-400 mb-3"
                            ></i>
                            <p
                                class="text-slate-600 dark:text-slate-400 text-sm"
                            >
                                {{ __("You are about to subscribe to") }}
                            </p>
                        </div>

                        <div
                            class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 mb-6"
                        >
                            <div class="text-center">
                                <div
                                    class="font-bold text-slate-800 dark:text-slate-200 text-lg"
                                >
                                    {{ selected_plan?.name }}
                                </div>
                                <div
                                    class="text-slate-600 dark:text-slate-400 text-sm mb-2"
                                >
                                    {{ selected_period?.billing_period }}
                                </div>
                                <div
                                    class="text-2xl font-bold text-green-600 dark:text-green-400"
                                >
                                    {{ selected_period?.currency }}
                                    {{ selected_period?.amount_format }}
                                </div>
                            </div>
                        </div>
                        <v-subscription
                            :plan="selected_plan"
                            :period="selected_period"
                        />
                    </div>
                </template>
            </v-modal>
        </div>
    </v-guest-layout>
</template>

<script setup>
import VGuestLayout from "@/layouts/VGuestLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VSubscription from "@/components/VSubscription.vue";
import VModal from "@/components/VModal.vue";
import { ref, onMounted, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    routes: {
        type: Object,
    },
});

const plans = ref([]);
const selected_plan = ref(null);
const selected_plan_details = ref(null);
const selected_period = ref(null);
const selectedPlans = ref({}); // Track selected prices for each plan
const showPlanDetails = ref(false);
const showSubscription = ref(false);
const loading = ref(false);

const search = useForm({
    page: 1,
    per_page: 9,
    name: "",
    period: "",
});

const pages = ref({ total_pages: 0 });
let searchTimeout = null;

// Computed
const availablePeriods = computed(() => {
    const periods = new Set();
    plans.value.forEach((plan) => {
        plan.prices.forEach((price) => {
            periods.add(price.billing_period);
        });
    });
    return Array.from(periods);
});

onMounted(() => {
    const values = props.data;
    plans.value = values.data;
    pages.value = values.meta.pagination;
});

// Methods
const formatPeriodName = (period) => {
    const names = {
        daily: "Daily",
        weekly: "Weekly",
        biweekly: "Bi-Weekly",
        monthly: "Monthly",
        quarterly: "Quarterly",
        yearly: "Yearly",
        one_time: "One Time",
    };
    return names[period] || period;
};

const formatDate = (date) => {
    if (!date) return __("No expiration");
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const getUniqueServices = (scopes) => {
    if (!scopes) return [];
    const servicesMap = new Map();
    scopes.forEach((scope) => {
        if (!servicesMap.has(scope.service.id)) {
            servicesMap.set(scope.service.id, scope);
        }
    });
    return Array.from(servicesMap.values());
};

const getDisplayPrices = (prices) => {
    return prices.slice(0, 3);
};

const selectPrice = (plan, price) => {
    selectedPlans.value[plan.id] = {
        selected: true,
        priceId: price.id,
        billingPeriod: price.billing_period,
        price: price,
    };
};

const openPlanDetails = (plan) => {
    selected_plan_details.value = plan;
    // Set current selection if exists
    if (selectedPlans.value[plan.id]) {
        selected_period.value = selectedPlans.value[plan.id].price;
    } else {
        selected_period.value = null;
    }
    showPlanDetails.value = true;
};

const selectPriceFromModal = (price) => {
    selected_period.value = price;
    // Update the selection state for the plan
    if (selected_plan_details.value) {
        selectedPlans.value[selected_plan_details.value.id] = {
            selected: true,
            priceId: price.id,
            billingPeriod: price.billing_period,
            price: price,
        };
    }
};

const proceedWithSelectedPlan = () => {
    if (!selected_period.value) {
        $notify.error(__("Please select a pricing option"));
        return;
    }
    selected_plan.value = selected_plan_details.value;
    showPlanDetails.value = false;
    showSubscription.value = true;
};

const selectPlan = (plan) => {
    if (!selectedPlans.value[plan.id]) {
        $notify.error(__("Please select a pricing option first"));
        return;
    }
    selected_plan.value = plan;
    selected_period.value = selectedPlans.value[plan.id].price;
    showSubscription.value = true;
};

const getPlans = () => {
    loading.value = true;
    search.get(props.routes.plans, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            const values = page.data;
            plans.value = values.data;
            pages.value = values.meta.pagination;
        },
        onError: (e) => {
            loading.value = false;
            if (e?.response?.data?.message) {
                $notify.error(e.response.data.message);
            }
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const debouncedSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        search.page = 1;
        getPlans();
    }, 300);
};

const resetFilters = () => {
    search.name = "";
    search.period = "";
    search.page = 1;
    getPlans();
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
