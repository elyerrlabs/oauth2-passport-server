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
            class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 py-8"
        >
            <!-- Hero Section -->
            <div class="text-center mb-16 px-4">
                <h1
                    class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent mb-4"
                >
                    {{ __("Choose Your Plan") }}
                </h1>
                <p class="text-xl text-slate-600 mb-4 max-w-2xl mx-auto">
                    {{
                        __(
                            "Select the perfect plan that fits your needs and budget."
                        )
                    }}
                </p>
                <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                    {{
                        __(
                            "All plans include our core features with flexible pricing options."
                        )
                    }}
                </p>
            </div>

            <!-- Plans Grid -->
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"
                >
                    <div v-for="plan in plans" :key="plan.id" class="flex">
                        <div
                            class="bg-white rounded-3xl shadow-lg border border-slate-200/60 flex-1 transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] backdrop-blur-sm flex flex-col relative"
                            :class="{
                                'ring-4 ring-blue-500/30 shadow-2xl scale-[1.02]':
                                    plan.featured,
                            }"
                        >
                            <!-- Featured Badge -->
                            <div
                                v-if="plan.featured"
                                class="absolute -top-2 -right-2"
                            >
                                <div
                                    class="bg-gradient-to-r from-amber-400 to-orange-500 text-white px-6 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg rotate-12"
                                >
                                    {{ __("Most Popular") }}
                                </div>
                            </div>

                            <!-- Plan Header -->
                            <div
                                class="rounded-t-3xl p-8 relative overflow-hidden"
                                :class="{
                                    'bg-gradient-to-br from-blue-600 to-violet-700':
                                        plan.featured,
                                    'bg-gradient-to-br from-slate-900 to-slate-800':
                                        !plan.featured,
                                }"
                            >
                                <div
                                    v-if="plan.bonus_enabled"
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2 z-10"
                                >
                                    <span
                                        class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-xl border-2 border-white/20"
                                    >
                                        🔥 +{{ plan.bonus_duration }} days free
                                    </span>
                                </div>

                                <div class="text-center pt-6 pb-2">
                                    <h3
                                        class="text-2xl font-bold text-white mb-3"
                                    >
                                        {{ plan.name }}
                                    </h3>
                                    <p
                                        class="text-blue-100/80 text-sm font-medium"
                                    >
                                        {{ plan.tagline }}
                                    </p>
                                </div>
                            </div>

                            <!-- Plan Content -->
                            <div class="p-8 flex-1">
                                <div class="mb-6">
                                    <div
                                        class="text-slate-700 text-sm leading-relaxed font-medium line-clamp-3"
                                    >
                                        <div v-html="plan.description"></div>
                                    </div>
                                    <button
                                        @click="openPlanDetails(plan)"
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 flex items-center space-x-1 transition-colors duration-200"
                                    >
                                        <span>View details</span>
                                        <i
                                            class="mdi mdi-chevron-right text-base"
                                        ></i>
                                    </button>
                                </div>

                                <hr class="mb-6 border-slate-200/60" />

                                <!-- Pricing Options -->
                                <h4
                                    class="text-lg font-bold text-slate-900 text-center mb-6"
                                >
                                    {{ __("Pricing Options") }}
                                </h4>
                                <div class="space-y-4">
                                    <div
                                        v-for="(price, index) in plan.prices"
                                        :key="index"
                                        class="flex items-center p-5 rounded-2xl border-2 cursor-pointer transition-all duration-300 group hover:border-blue-400/50 hover:bg-blue-50/50 hover:shadow-md"
                                        :class="{
                                            'border-blue-500 bg-gradient-to-r from-blue-50 to-indigo-50/50 shadow-lg scale-[1.02]':
                                                selected_period === price,
                                            'border-slate-200/80 bg-white/80':
                                                selected_period !== price,
                                        }"
                                        @click="selectPrice(plan, price)"
                                    >
                                        <div class="flex-shrink-0 mr-4">
                                            <span
                                                class="bg-gradient-to-r from-blue-600 to-violet-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md"
                                            >
                                                {{ price.billing_period_name }}
                                            </span>
                                        </div>

                                        <div class="flex-grow">
                                            <div
                                                class="font-bold text-slate-900 text-lg mb-1"
                                            >
                                                {{ price.currency }}
                                                {{ price.amount_format }}
                                            </div>
                                            <div
                                                class="text-xs text-slate-500 flex items-center"
                                            >
                                                <i
                                                    class="mdi mdi-calendar text-slate-400 mr-1.5"
                                                ></i>
                                                {{
                                                    formatDate(price.expiration)
                                                }}
                                            </div>
                                        </div>

                                        <div class="flex-shrink-0 ml-4">
                                            <div
                                                class="w-7 h-7 rounded-full border-3 flex items-center justify-center transition-all duration-300 shadow-inner"
                                                :class="{
                                                    'border-blue-600 bg-blue-600 shadow-blue-200':
                                                        selected_period ===
                                                        price,
                                                    'border-slate-300 bg-white group-hover:border-blue-400':
                                                        selected_period !==
                                                        price,
                                                }"
                                            >
                                                <div
                                                    v-if="
                                                        selected_period ===
                                                        price
                                                    "
                                                    class="w-2 h-2 rounded-full bg-white"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan Footer -->
                            <div class="px-8 pb-8">
                                <button
                                    @click="selectPlan(plan)"
                                    :disabled="!selected_period"
                                    class="w-full bg-gradient-to-r from-blue-600 to-violet-600 hover:from-blue-700 hover:to-violet-700 text-white py-4 px-8 rounded-2xl font-bold shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none disabled:hover:shadow-lg group"
                                >
                                    <span
                                        class="flex items-center justify-center space-x-3"
                                    >
                                        <span class="text-lg">Select Plan</span>
                                        <i
                                            v-if="selected_period"
                                            class="mdi mdi-arrow-right text-lg group-hover:translate-x-1 transition-transform"
                                        ></i>
                                        <i
                                            v-else
                                            class="mdi mdi-lock-outline text-lg opacity-70"
                                        ></i>
                                    </span>
                                </button>

                                <div
                                    v-if="!selected_period"
                                    class="text-xs text-slate-500 text-center mt-3 font-medium"
                                >
                                    {{ __("Select a pricing option first") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                <v-paginate
                    v-model="search.page"
                    :total-pages="pages.total_pages"
                    @change="getPlans"
                />
            </div>

            <!-- Subscription Modal -->
            <v-modal v-model="showSidebar" panel-class="w-full lg:w-7xl">
                <template #body>
                    <div
                        class="bg-gradient-to-r from-blue-600 to-violet-600 px-6 py-5 flex items-center justify-between"
                    >
                        <div>
                            <DialogTitle class="text-2xl font-bold text-white">
                                {{ __("Complete Subscription") }}
                            </DialogTitle>
                            <p class="text-blue-100/80 text-sm">
                                {{ __("Finalize your plan selection") }}
                            </p>
                        </div>
                        <button
                            @click="showSidebar = false"
                            class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors duration-200"
                        >
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto bg-slate-50/40">
                        <div
                            class="px-6 py-4 border-b border-slate-200 bg-white/70"
                        >
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600 font-medium"
                                    >{{ __("Selected Plan") }}:</span
                                >
                                <span class="text-slate-900 font-bold">{{
                                    selected_plan?.name
                                }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-2">
                                <span class="text-slate-600 font-medium"
                                    >{{ __("Amount") }}:</span
                                >
                                <span class="text-green-600 font-bold">
                                    {{ selected_period?.currency }}
                                    {{ selected_period?.amount_format }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <v-subscription
                                :plan="selected_plan"
                                :period="selected_period"
                                @close="showSidebar = false"
                            />
                        </div>
                    </div>

                    <div
                        class="border-t border-slate-200/60 px-6 py-4 bg-slate-50/70 text-center text-xs text-slate-500"
                    >
                        {{ __("Your payment is secure and encrypted.") }}
                    </div>
                </template>
            </v-modal>

            <!-- Plan Details Modal -->
            <v-modal v-model="showPlanDetails" panel-class="w-full lg:w-7xl">
                <template #body>
                    <!-- Header -->
                    <div
                        class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-5 flex items-center justify-between"
                    >
                        <div>
                            <h3 class="text-2xl font-bold text-white">
                                {{ selected_plan_details?.name }}
                            </h3>
                            <p class="text-blue-100/80 text-sm">
                                {{ selected_plan_details?.tagline }}
                            </p>
                        </div>
                        <button
                            @click="showPlanDetails = false"
                            class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors duration-200"
                        >
                            <i class="mdi mdi-close text-lg"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="overflow-y-auto bg-white/70 p-6 max-h-[80vh]">
                        <div
                            class="prose max-w-none text-slate-700 leading-relaxed"
                            v-html="selected_plan_details?.description"
                        ></div>

                        <div
                            v-if="selected_plan_details?.features?.length"
                            class="mt-8"
                        >
                            <h3 class="text-lg font-bold text-slate-900 mb-4">
                                {{ __("Included Features") }}
                            </h3>
                            <ul class="grid sm:grid-cols-2 gap-3">
                                <li
                                    v-for="(
                                        feature, index
                                    ) in selected_plan_details.features"
                                    :key="index"
                                    class="flex items-center space-x-2 text-slate-700"
                                >
                                    <i
                                        class="mdi mdi-check-circle text-green-500 text-lg"
                                    ></i>
                                    <span>{{ feature }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="border-t border-slate-200/60 px-6 py-4 bg-slate-50/70 text-center"
                    >
                        <button
                            @click="showPlanDetails = false"
                            class="bg-gradient-to-r from-blue-600 to-violet-600 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg transition-transform hover:scale-[1.02]"
                        >
                            {{ __("Close") }}
                        </button>
                    </div>
                </template>
            </v-modal>
        </div>
    </v-guest-layout>
</template>

<script>
import VGuestLayout from "@/layouts/VGuestLayout.vue";
import VPaginate from "@/components/VPaginate.vue";
import VSubscription from "@/components/VSubscription.vue";
import VModal from "@/components/VModal.vue";

export default {
    components: {
        VGuestLayout,
        VPaginate,
        VSubscription,
        VModal,
    },
    data() {
        return {
            plans: [],
            selected_plan: null,
            selected_period: null,
            selected_plan_details: null,
            showSidebar: false,
            showPlanDetails: false,
            search: { page: 1, per_page: 100 },
            pages: { total_pages: 0 },
        };
    },
    created() {
        this.getPlans();
    },
    methods: {
        formatDate(date) {
            if (!date) return "No expiration";
            return new Date(date).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },
        selectPrice(plan, price) {
            this.selected_plan = plan;
            this.selected_period = price;
        },
        selectPlan(plan) {
            if (!this.selected_period)
                return $notify.error("Please select a pricing option first.");
            this.selected_plan = plan;
            this.showSidebar = true;
        },
        openPlanDetails(plan) {
            this.selected_plan_details = plan;
            this.showPlanDetails = true;
        },
        async getPlans() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["plans"],
                    {
                        params: this.search,
                    }
                );
                if (res.status === 200) {
                    this.plans = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },
    },
};
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}
.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
