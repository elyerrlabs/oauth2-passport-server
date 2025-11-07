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
    <div class="p-4" v-if="period?.id && plan?.id">
        <div class="text-xl font-medium mb-4">{{ label }}</div>

        <div class="flex flex-wrap gap-4 items-start">
            <div v-for="(method, key) in methods" :key="key">
                <div
                    v-if="method.enable"
                    @click="selectMethod(key)"
                    class="border border-gray-300 rounded-lg cursor-pointer transition-all duration-200 hover:shadow-md"
                    :class="{
                        'bg-blue-600 text-white': selected_method === key,
                        'bg-white text-gray-900': selected_method !== key,
                    }"
                >
                    <div class="p-4 text-center">
                        <div class="flex justify-center mb-2">
                            <div
                                class="w-10 h-10 flex items-center justify-center"
                            >
                                <svg
                                    v-if="method.icon === 'credit_card'"
                                    class="w-8 h-8"
                                    :class="
                                        selected_method === key
                                            ? 'text-white'
                                            : 'text-green-500'
                                    "
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
                                <svg
                                    v-else-if="method.icon === 'paypal'"
                                    class="w-8 h-8"
                                    :class="
                                        selected_method === key
                                            ? 'text-white'
                                            : 'text-green-500'
                                    "
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="w-8 h-8"
                                    :class="
                                        selected_method === key
                                            ? 'text-white'
                                            : 'text-green-500'
                                    "
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm font-medium">{{ method.name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div
                v-if="selected_method >= 0"
                class="bg-blue-50 border border-blue-200 rounded-lg p-4"
            >
                <div class="text-lg font-medium mb-2">{{ __("Summary") }}</div>
                <div class="text-sm space-y-1">
                    <div>
                        <strong>{{ __("Plan:") }}</strong> {{ plan?.name }}
                    </div>
                    <div>
                        <strong>{{ __("Billing Period:") }}</strong>
                        {{ period?.billing_period }}
                    </div>
                    <div>
                        <strong>{{ __("Amount:") }}</strong>
                        {{ period?.currency }} {{ period?.amount_format }}
                    </div>
                    <div>
                        <strong>{{ __("Expires:") }}</strong>
                        {{ period?.expiration }}
                    </div>
                    <div>
                        <strong>{{ __("Payment Method:") }}</strong>
                        {{ methods[selected_method]?.name }}
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button
                v-if="selected_method >= 0"
                :disabled="disabled"
                @click="payment"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
            >
                {{ __("Continue to Payment") }}
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

<style scoped>
.cursor-pointer {
    transition: transform 0.3s;
}
.cursor-pointer:hover {
    transform: scale(1.05);
}
</style>
