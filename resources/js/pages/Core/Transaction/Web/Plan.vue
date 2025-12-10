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
        <div class="min-h-screen bg-white dark:bg-slate-900 py-2">
            <!-- Header -->
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

            <!-- Filters -->
            <div class="w-full mx-auto px-2 lg:px-4 mb-6">
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4"
                >
                    <div
                        class="grid grid-1 sm:grid-cols-2 lg:grid-cols-4 gap-2"
                    >
                        <v-input
                            v-model="search.name"
                            :placeholder="__('Search plans...')"
                            @input="debouncedSearch"
                        />

                        <v-select
                            v-model="search.service"
                            :options="services"
                            value-key="name"
                            @change="debouncedSearch"
                        />

                        <v-select
                            v-model="search.period"
                            :options="periods"
                            @change="debouncedSearch"
                        />

                        <button
                            @click="resetFilters"
                            class="px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 whitespace-nowrap cursor-pointer"
                        >
                            {{ __("Clear Filters") }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Plans Grid -->
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

                <!-- Plans -->
                <div
                    v-else
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                >
                    <div
                        v-for="plan in plans"
                        :key="plan.id"
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700"
                        :class="{
                            'ring-2 ring-blue-500 border-blue-300 dark:ring-blue-400 dark:border-blue-600':
                                selectedPlans[plan.id]?.selected,
                        }"
                    >
                        <!-- Plan Header -->
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
                                    <button
                                        @click="openPlanDetails(plan)"
                                        class="text-blue-600 dark:text-blue-400 text-xs font-medium cursor-pointer flex items-center space-x-1"
                                    >
                                        <i
                                            class="mdi mdi-information-outline text-sm"
                                        ></i>
                                        <span>{{ __("Details") }}</span>
                                    </button>
                                </div>
                            </div>
                            <p
                                class="text-slate-600 dark:text-slate-400 text-sm line-clamp-2"
                                v-html="plan.description"
                            ></p>
                        </div>

                        <!-- Included Services -->
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

                        <!-- Simplified Prices Section -->
                        <div class="p-4">
                            <div class="space-y-1 mb-3">
                                <div
                                    v-for="price in getDisplayPrices(
                                        plan.prices
                                    )"
                                    :key="price.id"
                                    class="price-option"
                                    :class="{
                                        selected:
                                            selectedPlans[plan.id]?.priceId ===
                                            price.id,
                                        unselected:
                                            selectedPlans[plan.id]?.priceId !==
                                            price.id,
                                    }"
                                    @click="selectPrice(plan, price)"
                                >
                                    <div class="price-content">
                                        <div class="price-left">
                                            <div
                                                class="price-radio"
                                                :class="{
                                                    selected:
                                                        selectedPlans[plan.id]
                                                            ?.priceId ===
                                                        price.id,
                                                }"
                                            >
                                                <div
                                                    v-if="
                                                        selectedPlans[plan.id]
                                                            ?.priceId ===
                                                        price.id
                                                    "
                                                    class="price-radio-dot"
                                                ></div>
                                            </div>
                                            <span class="price-period">
                                                {{ price.billing_period }}
                                            </span>
                                        </div>
                                        <span class="price-amount">
                                            {{ price.currency }}
                                            {{ price.amount_format }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- View more prices -->
                            <div
                                v-if="plan.prices.length > 3"
                                class="mb-3 pt-2 border-t border-slate-100 dark:border-slate-700"
                            >
                                <button
                                    class="text-blue-600 dark:text-blue-400 text-xs font-medium cursor-pointer"
                                    @click="openPlanDetails(plan)"
                                >
                                    +{{ plan.prices.length - 3 }}
                                    {{ __("more options") }}
                                </button>
                            </div>

                            <!-- Main CTA -->
                            <button
                                @click="selectPlan(plan)"
                                :disabled="!selectedPlans[plan.id]"
                                class="w-full bg-blue-600 dark:bg-blue-500 disabled:bg-slate-400 dark:disabled:bg-slate-600 disabled:cursor-not-allowed text-white py-2 px-4 rounded-lg font-medium text-sm cursor-pointer"
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

                            <!-- Selection status -->
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

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <v-paginate
                    v-model="search.page"
                    :total-pages="pages.total_pages"
                    @change="getPlans"
                />
            </div>

            <!-- Plan Details Modal -->
            <v-modal v-model="showPlanDetails" panel-class="w-full lg:w-7xl">
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
                            class="text-slate-300 dark:text-slate-400 cursor-pointer"
                        >
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-2 md:p-4 lg:p-6">
                        <!-- Description -->
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

                        <!-- Services and Roles -->
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
                                        >
                                            {{ __(scope.service.name) }}
                                        </span>
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

                        <!-- Simplified All Prices -->
                        <div>
                            <h4
                                class="font-semibold text-slate-800 dark:text-slate-200 mb-3"
                            >
                                {{ __("Pricing Options") }}
                            </h4>
                            <div class="space-y-2">
                                <div
                                    v-for="price in selected_plan_details?.prices"
                                    :key="price.id"
                                    class="modal-price-option"
                                    :class="{
                                        selected:
                                            selected_period?.id === price.id,
                                    }"
                                    @click="selectPriceFromModal(price)"
                                >
                                    <div class="modal-price-content">
                                        <div class="modal-price-left">
                                            <div
                                                class="modal-price-radio"
                                                :class="{
                                                    selected:
                                                        selected_period?.id ===
                                                        price.id,
                                                }"
                                            >
                                                <div
                                                    v-if="
                                                        selected_period?.id ===
                                                        price.id
                                                    "
                                                    class="modal-price-radio-dot"
                                                ></div>
                                            </div>
                                            <div>
                                                <div class="modal-price-period">
                                                    {{ price.billing_period }}
                                                </div>
                                                <div class="modal-price-expiry">
                                                    {{ __("Expires") }}:
                                                    {{ price.expiration }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-price-right">
                                            <div class="modal-price-amount">
                                                {{ price.currency }}
                                                {{ price.amount_format }}
                                            </div>
                                            <div class="modal-price-available">
                                                {{ __("Available") }}
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        v-if="selected_period?.id === price.id"
                                        class="modal-price-selected-badge"
                                    >
                                        <i
                                            class="mdi mdi-check-circle text-xs mr-1"
                                        ></i>
                                        {{ __("Selected") }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bonus -->
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
                                    >
                                        {{ selected_period.currency }}
                                        {{ selected_period.amount_format }}
                                    </span>
                                    ·
                                    {{ selected_period.billing_period }}
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
                                    class="px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer"
                                >
                                    {{ __("Close") }}
                                </button>
                                <button
                                    @click="proceedWithSelectedPlan"
                                    :disabled="!selected_period"
                                    class="px-4 py-2 text-sm bg-blue-600 dark:bg-blue-500 text-white rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                >
                                    {{ __("Continue") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </v-modal>

            <!-- Subscription Modal -->
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
import VGuestLayout from "@/components/VGuestLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VSubscription from "@/components/VSubscription.vue";
import VSelect from "@/components/VSelect.vue";
import VInput from "@/components/VInput.vue";
import VModal from "@/components/VModal.vue";
import { ref, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    routes: {
        type: Object,
    },
});

const page = usePage();
const plans = ref([]);
const selected_plan = ref(null);
const selected_plan_details = ref(null);
const selected_period = ref(null);
const selectedPlans = ref({});
const showPlanDetails = ref(false);
const showSubscription = ref(false);
const loading = ref(false);
const periods = ref([]);
const services = ref([]);

const search = useForm({
    page: 1,
    per_page: 9,
    name: "",
    period: "",
    service: "",
});

const pages = ref({ total_pages: 0 });
let searchTimeout = null;

onMounted(async () => {
    const values = props.data;
    plans.value = values.data;
    pages.value = values.meta.pagination;

    await getPeriods();
    await getServices();
});

const getPeriods = async () => {
    try {
        const res = await $server.get(
            page.props.api.transactions.billing_period
        );
        if (res.status == 200) {
            periods.value = res.data.data;
        }
    } catch (e) {}
};

const getServices = async () => {
    try {
        const res = await $server.get(
            page.props.api.transactions.services_list
        );
        if (res.status == 200) {
            services.value = res.data.data;
        }
    } catch (e) {}
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
    if (selectedPlans.value[plan.id]) {
        selected_period.value = selectedPlans.value[plan.id].price;
    } else {
        selected_period.value = null;
    }
    showPlanDetails.value = true;
};

const selectPriceFromModal = (price) => {
    selected_period.value = price;
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
            const values = page.props.data;
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
    search.service = "";
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

/* Simplified Price Options */
.price-option {
    padding: 0.5rem;
    border-radius: 0.5rem;
    cursor: pointer;
}

.price-option.selected {
    border: 2px solid #3b82f6;
    background-color: rgba(59, 130, 246, 0.05);
}

.price-option.unselected {
    border: 1px solid #e2e8f0;
}

.dark .price-option.selected {
    border-color: #60a5fa;
    background-color: rgba(59, 130, 246, 0.1);
}

.dark .price-option.unselected {
    border-color: #475569;
}

.price-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.price-left {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.price-radio {
    width: 1rem;
    height: 1rem;
    border-radius: 9999px;
    border: 2px solid #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
}

.price-radio.selected {
    border-color: #3b82f6;
    background-color: #3b82f6;
}

.dark .price-radio {
    border-color: #64748b;
}

.dark .price-radio.selected {
    border-color: #60a5fa;
    background-color: #60a5fa;
}

.price-radio-dot {
    width: 0.375rem;
    height: 0.375rem;
    border-radius: 9999px;
    background-color: white;
}

.dark .price-radio-dot {
    background-color: #0f172a;
}

.price-period {
    font-size: 0.875rem;
    color: #475569;
    text-transform: capitalize;
}

.dark .price-period {
    color: #cbd5e1;
}

.price-amount {
    font-weight: 600;
    font-size: 0.875rem;
    color: #1e293b;
}

.dark .price-amount {
    color: #e2e8f0;
}

/* Modal Price Options */
.modal-price-option {
    padding: 0.5rem 1rem;
    border-width: 2px;
    border-radius: 0.75rem;
    cursor: pointer;
}

.modal-price-option.selected {
    border-color: #3b82f6;
    background-color: rgba(59, 130, 246, 0.05);
}

.dark .modal-price-option.selected {
    border-color: #60a5fa;
    background-color: rgba(59, 130, 246, 0.1);
}

.modal-price-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.25rem;
}

.modal-price-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-price-radio {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 9999px;
    border: 2px solid #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-price-radio.selected {
    border-color: #3b82f6;
    background-color: #3b82f6;
}

.dark .modal-price-radio {
    border-color: #64748b;
}

.dark .modal-price-radio.selected {
    border-color: #60a5fa;
    background-color: #60a5fa;
}

.modal-price-radio-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 9999px;
    background-color: white;
}

.dark .modal-price-radio-dot {
    background-color: #0f172a;
}

.modal-price-period {
    font-weight: 600;
    color: #1e293b;
    text-transform: capitalize;
}

.dark .modal-price-period {
    color: #e2e8f0;
}

.modal-price-expiry {
    font-size: 0.75rem;
    color: #64748b;
}

.dark .modal-price-expiry {
    color: #94a3b8;
}

.modal-price-right {
    text-align: right;
}

.modal-price-amount {
    font-weight: 700;
    font-size: 1.25rem;
    color: #1e293b;
}

.dark .modal-price-amount {
    color: #e2e8f0;
}

.modal-price-available {
    font-size: 0.875rem;
    font-weight: 500;
    color: #16a34a;
}

.dark .modal-price-available {
    color: #4ade80;
}

.modal-price-selected-badge {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 0.5rem;
    padding: 0.25rem 0.75rem;
    background-color: rgba(59, 130, 246, 0.1);
    color: #1e40af;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.dark .modal-price-selected-badge {
    background-color: rgba(59, 130, 246, 0.2);
    color: #93c5fd;
}
</style>
