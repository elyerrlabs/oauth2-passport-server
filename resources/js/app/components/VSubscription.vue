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
    <div class="q-pa-md" v-if="period?.id && plan?.id">
        <div class="text-h5 q-mb-md">{{ label }}</div>

        <div class="q-gutter-md row items-start">
            <div v-for="(method, key) in methods" :key="key">
                <q-card
                    v-if="method.enable"
                    @click="selectMethod(key)"
                    flat
                    bordered
                    class="my-card cursor-pointer"
                    :class="{
                        'bg-primary text-white': selected_method === key,
                    }"
                >
                    <q-card-section class="text-center">
                        <q-icon
                            color="positive"
                            :name="method.icon"
                            size="40px"
                            class="q-mb-sm"
                        />
                        <div class="text-subtitle3">{{ method.name }}</div>
                    </q-card-section>
                </q-card>
            </div>
        </div>
        <div class="q-mt-lg">
            <q-banner v-if="selected_method >= 0" class="q-pa-md">
                <div class="text-h6 q-mb-sm">{{ __("Summary") }}</div>
                <div class="text-body2">
                    <strong>{{ __("Plan:") }}</strong> {{ plan?.name }} <br />
                    <strong>{{ __("Billing Period:") }}</strong>
                    {{ period?.billing_period }} <br />
                    <strong>{{ __("Amount:") }}</strong>
                    {{ period?.currency }} {{ period?.amount_format }} <br />
                    <strong>{{ __("Expires:") }}</strong>
                    {{ period?.expiration }} <br />
                    <strong>{{ __("Payment Method:") }}</strong>
                    {{ methods[selected_method]?.name }}
                </div>
            </q-banner>
        </div>

        <div class="q-mt-md">
            <q-btn
                v-if="selected_method >= 0"
                :disabled="disabled"
                color="primary"
                :label="__('Continue to Payment')"
                @click="payment"
                class="full-width"
            />
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
                this.$q.notify({
                    type: "negative",
                    message: "Please login and try again",
                    timeout: 3000,
                });
                return;
            }

            if (!this.user?.id) {
                this.$q.notify({
                    type: "negative",
                    message: "Please select the plan to continue ...",
                    timeout: 3000,
                });
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
            console.log(this.$page.props.routes["subscription"]);

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
            } catch (error) {
                this.disabled = false;
                console.log(error);
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
            } catch (error) {
                this.disabled = false;
                console.log(error);
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
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>

<style scoped>
.my-card {
    width: 150px;
    transition: 0.3s;
}
.my-card:hover {
    transform: scale(1.05);
}
</style>
