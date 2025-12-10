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
    <div class="md:p-4 lg:p-6" v-if="period?.id && plan?.id">
        <div
            class="text-md lg:text-2xl font-bold text-gray-900 dark:text-white mb-2"
        >
            {{ label }}
        </div>
        <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm md:text-md">
            {{ __("Select your preferred payment method to continue") }}
        </p>

        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mb-6"
        >
            <div
                v-for="(method, key) in methods"
                :key="key"
                v-show="method.enable"
            >
                <div
                    @click="selectMethod(key)"
                    class="border-2 rounded-xl cursor-pointer transition-colors duration-200 hover:shadow hover:border-blue-300 dark:hover:border-blue-500"
                    :class="{
                        'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20 shadow-sm':
                            selected_method === key,
                        'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800':
                            selected_method !== key,
                    }"
                >
                    <div class="p-4 text-center">
                        <div class="flex justify-center mb-3">
                            <div
                                class="w-14 h-14 rounded-xl flex items-center justify-center"
                                :class="{
                                    'bg-blue-500 dark:bg-blue-600 text-white':
                                        selected_method === key,
                                    'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400':
                                        selected_method !== key,
                                }"
                            >
                                <svg
                                    v-if="method.icon === 'credit_card'"
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                    />
                                </svg>
                                <svg
                                    v-else-if="method.icon === 'paypal'"
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div
                            class="text-md font-semibold mb-1"
                            :class="{
                                'text-blue-700 dark:text-blue-300':
                                    selected_method === key,
                                'text-gray-900 dark:text-white':
                                    selected_method !== key,
                            }"
                        >
                            {{ method.name }}
                        </div>
                        <div
                            class="text-xs"
                            :class="{
                                'text-blue-600 dark:text-blue-400':
                                    selected_method === key,
                                'text-gray-500 dark:text-gray-400':
                                    selected_method !== key,
                            }"
                        >
                            {{ method.description || __("Secure payment") }}
                        </div>
                    </div>

                    <!-- Selected Indicator -->
                    <div
                        v-if="selected_method === key"
                        class="w-full h-1 bg-blue-500 rounded-b-xl"
                    ></div>
                </div>
            </div>
        </div>

        <transition
            enter-active-class="transition-opacity duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
        >
            <div v-if="selected_method >= 0" class="mb-6">
                <div
                    class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800 rounded-xl p-4"
                >
                    <div class="flex items-center mb-3">
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3"
                        >
                            <svg
                                class="w-5 h-5 text-blue-600 dark:text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-md font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Order Summary") }}
                            </div>
                            <div
                                class="text-blue-600 dark:text-blue-400 text-xs font-medium"
                            >
                                {{ __("Ready to proceed with payment") }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{
                                __("Plan:")
                            }}</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ plan?.name }}</span
                            >
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{
                                __("Billing Period:")
                            }}</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white capitalize"
                                >{{ period?.billing_period }}</span
                            >
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{
                                __("Payment Method:")
                            }}</span>
                            <span
                                class="font-medium text-blue-600 dark:text-blue-400"
                                >{{ methods[selected_method]?.name }}</span
                            >
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{
                                __("Amount:")
                            }}</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white"
                            >
                                {{ period?.currency }}
                                {{ period?.amount_format }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{
                                __("Expires:")
                            }}</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ period?.expiration }}</span
                            >
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{
                                __("Status:")
                            }}</span>
                            <span
                                class="font-medium text-green-600 dark:text-green-400 flex items-center"
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
                                        stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </svg>
                                {{ __("Ready to pay") }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <div
            class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700"
        >
            <div
                class="flex items-center text-xs text-gray-500 dark:text-gray-400"
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
                        stroke-width="1.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                    />
                </svg>
                {{ __("Your payment is secure and encrypted") }}
            </div>

            <button
                v-if="selected_method >= 0"
                :disabled="disabled"
                @click="payment"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 shadow hover:shadow-md flex items-center justify-center space-x-2"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                    />
                </svg>
                <span>{{ __("Continue to Payment") }}</span>
                <svg
                    v-if="disabled"
                    class="w-4 h-4 animate-spin"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        period: {
            type: Object,
            required: false,
        },
        plan: {
            type: Object,
            required: false,
        },
        label: {
            type: String,
            required: false,
            default: "Choose your payment method",
        },
        buy: {
            type: Boolean,
            required: false,
            default: true,
        },
        package: {
            type: Object,
            required: false,
        },
    },

    data() {
        return {
            selected_method: -1,
            methods: [],
            disabled: false,
            guest: false,
        };
    },

    created() {
        this.getPaymentMethods();
        this.user = this.$page.props.user;
    },

    methods: {
        selectMethod(key) {
            this.selected_method = key;
        },

        updateUser(user) {
            this.user = user;
        },

        getReferralLink() {
            const params = new URLSearchParams(window.location.search);
            return params.get("referral_code");
        },

        async payment() {
            if (!this.user?.id) {
                this.guest = true;
                $notify.error(__("Please login and try again"));
                return;
            }

            if (!this.user?.id) {
                $notify.error(__("Please select the plan to continue ..."));
                return;
            }

            if (this.buy) {
                await this.continuePayment();
            } else {
                await this.extendPayment();
            }
        },

        async continuePayment() {
            this.disabled = true;
            try {
                const res = await this.$server.post(
                    this.$page.props.routes["subscription"],
                    {
                        plan: this.plan.id,
                        billing_period: this.period.billing_period,
                        payment_method: this.methods[this.selected_method].key,
                        referral_code: this.getReferralLink(),
                    }
                );

                if (res.status == 201) {
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }

                this.disabled = false;
            }
        },

        async extendPayment() {
            this.disabled = true;

            try {
                const res = await this.$server.post(
                    this.$page.props.routes.renew_package,
                    {
                        package: this.package.id,
                        billing_period: this.period.billing_period,
                        payment_method: this.methods[this.selected_method].key,
                        refer_link: this.getReferralLink(),
                    }
                );

                if (res.status == 201) {
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }

                this.disabled = false;
            }
        },

        async getPaymentMethods() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["methods"]
                );

                if (res.status == 200) {
                    this.methods = res.data.data;
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
