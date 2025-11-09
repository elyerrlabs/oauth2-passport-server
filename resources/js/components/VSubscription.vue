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
    <div class="p-6" v-if="period?.id && plan?.id">
        <!-- Header -->
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4"
            >
                <i
                    class="mdi mdi-credit-card-outline text-blue-600 dark:text-blue-400 text-2xl"
                ></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {{ label }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                {{
                    __(
                        "Choose your preferred payment method to complete your subscription"
                    )
                }}
            </p>
        </div>

        <!-- Payment Methods -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <div
                v-for="(method, key) in methods"
                :key="key"
                v-if="method.enable"
                @click="selectMethod(key)"
                class="border-2 rounded-xl cursor-pointer transition-all duration-300 group"
                :class="{
                    'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20 shadow-lg scale-105':
                        selected_method === key,
                    'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-md':
                        selected_method !== key,
                }"
            >
                <div class="p-6 text-center">
                    <!-- Icon -->
                    <div class="flex justify-center mb-4">
                        <div
                            class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                            :class="{
                                'bg-blue-500 dark:bg-blue-600 text-white shadow-lg':
                                    selected_method === key,
                                'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 group-hover:text-blue-600 dark:group-hover:text-blue-400':
                                    selected_method !== key,
                            }"
                        >
                            <i
                                v-if="method.icon === 'credit_card'"
                                class="mdi mdi-credit-card-outline text-2xl"
                            ></i>
                            <i
                                v-else-if="method.icon === 'paypal'"
                                class="mdi mdi-paypal text-2xl"
                            ></i>
                            <i
                                v-else-if="method.icon === 'bank'"
                                class="mdi mdi-bank text-2xl"
                            ></i>
                            <i
                                v-else-if="method.icon === 'crypto'"
                                class="mdi mdi-currency-btc text-2xl"
                            ></i>
                            <i
                                v-else
                                class="mdi mdi-wallet-outline text-2xl"
                            ></i>
                        </div>
                    </div>

                    <!-- Method Name -->
                    <div
                        class="text-lg font-semibold mb-2 transition-colors"
                        :class="{
                            'text-blue-700 dark:text-blue-300':
                                selected_method === key,
                            'text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400':
                                selected_method !== key,
                        }"
                    >
                        {{ method.name }}
                    </div>

                    <!-- Method Description -->
                    <div
                        class="text-sm text-gray-500 dark:text-gray-400 mb-3 line-clamp-2"
                    >
                        {{
                            method.description ||
                            __("Secure payment processing")
                        }}
                    </div>

                    <!-- Badge -->
                    <div
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="{
                            'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300':
                                selected_method === key,
                            'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300':
                                selected_method !== key,
                        }"
                    >
                        <i class="mdi mdi-shield-check mr-1 text-xs"></i>
                        {{ __("Secure") }}
                    </div>
                </div>

                <!-- Selected Indicator -->
                <div
                    v-if="selected_method === key"
                    class="w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-b-xl"
                ></div>
            </div>
        </div>

        <!-- Order Summary -->
        <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 transform translate-y-4"
            enter-to-class="opacity-100 transform translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 transform translate-y-0"
            leave-to-class="opacity-0 transform translate-y-4"
        >
            <div v-if="selected_method >= 0" class="mb-8">
                <div
                    class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6"
                >
                    <div class="flex items-center mb-4">
                        <i
                            class="mdi mdi-receipt text-blue-600 dark:text-blue-400 text-xl mr-3"
                        ></i>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            {{ __("Order Summary") }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Plan:") }}</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-white"
                                    >{{ plan?.name }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Billing Period:") }}</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-white capitalize"
                                    >{{ period?.billing_period }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Payment Method:") }}</span
                                >
                                <span
                                    class="font-medium text-blue-600 dark:text-blue-400 flex items-center"
                                >
                                    <i class="mdi mdi-check-circle mr-1"></i>
                                    {{ methods[selected_method]?.name }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Amount:") }}</span
                                >
                                <span
                                    class="font-bold text-lg text-gray-900 dark:text-white"
                                >
                                    {{ period?.currency }}
                                    {{ period?.amount_format }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Expires:") }}</span
                                >
                                <span
                                    class="font-medium text-gray-900 dark:text-white"
                                    >{{ period?.expiration }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ __("Status:") }}</span
                                >
                                <span
                                    class="font-medium text-green-600 dark:text-green-400 flex items-center"
                                >
                                    <i class="mdi mdi-lock-check mr-1"></i>
                                    {{ __("Ready to pay") }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Action Buttons -->
        <div
            class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700"
        >
            <div
                class="flex items-center text-sm text-gray-500 dark:text-gray-400"
            >
                <i class="mdi mdi-shield-lock-outline mr-2"></i>
                {{ __("Your payment is secure and encrypted") }}
            </div>

            <div class="flex gap-3 w-full sm:w-auto">
                <button
                    v-if="selected_method >= 0"
                    :disabled="disabled"
                    @click="payment"
                    class="flex-1 sm:flex-none bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 px-8 rounded-xl font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
                >
                    <i class="mdi mdi-lock-outline"></i>
                    <span>{{ __("Continue to Payment") }}</span>
                    <i
                        v-if="disabled"
                        class="mdi mdi-loading mdi-spin ml-2"
                    ></i>
                </button>
            </div>
        </div>

        <!-- Guest Warning -->
        <transition
            enter-active-class="transition-all duration-300"
            enter-from-class="opacity-0 transform scale-95"
            enter-to-class="opacity-100 transform scale-100"
        >
            <div
                v-if="guest"
                class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl"
            >
                <div class="flex items-start space-x-3">
                    <i
                        class="mdi mdi-alert-circle-outline text-yellow-600 dark:text-yellow-400 text-xl mt-0.5"
                    ></i>
                    <div class="flex-1">
                        <div
                            class="font-medium text-yellow-800 dark:text-yellow-300 mb-1"
                        >
                            {{ __("Authentication Required") }}
                        </div>
                        <div
                            class="text-yellow-700 dark:text-yellow-400 text-sm"
                        >
                            {{
                                __(
                                    "Please log in to your account to complete the payment process."
                                )
                            }}
                        </div>
                    </div>
                    <button
                        @click="guest = false"
                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300"
                    >
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>
        </transition>
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
            user: null,
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
                this.$notify?.error(__("Please login and try again"));
                return;
            }

            if (!this.user?.id) {
                this.$notify?.error(
                    __("Please select the plan to continue ...")
                );
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
                        refer_link: this.getReferralLink(),
                    }
                );

                if (res.status == 201) {
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify?.error(e.response.data.message);
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
                    this.$notify?.error(e.response.data.message);
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
                    this.$notify?.error(e.response.data.message);
                }
            }
        },
    },
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.cursor-pointer {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.cursor-pointer:hover {
    transform: translateY(-2px);
}
</style>
