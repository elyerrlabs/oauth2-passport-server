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
    <v-admin-ecommerce-layout>
        <div class="customer-detail-container q-pa-md">
            <!-- Loading state -->
            <div v-if="loading" class="text-center q-pa-xl">
                <q-spinner-gears size="50px" color="primary" />
                <div class="q-mt-md">{{ __("Loading customer data...") }}</div>
            </div>

            <!-- Customer details card -->
            <q-card
                v-else
                class="customer-card"
                v-for="(item, index) in customers"
                :key="index"
            >
                <q-card-section class="bg-primary text-white">
                    <div class="row items-center">
                        <div class="col-auto">
                            <q-avatar
                                size="80px"
                                font-size="40px"
                                color="white"
                                text-color="primary"
                            >
                                {{ customerInitials(item) }}
                            </q-avatar>
                        </div>
                        <div class="col q-pl-md">
                            <div class="text-h5">
                                {{ item.name }} {{ item.last_name }}
                            </div>
                            <div class="text-subtitle1">
                                {{ item.email }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <q-badge color="white" text-color="primary">
                                {{ item.checkouts_count }} {{ __("Orders") }}
                            </q-badge>
                        </div>
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <!-- Customer Information -->
                        <div class="col-12 col-md-6">
                            <div class="text-h6 q-mb-md">
                                {{ __("Customer Information") }}
                            </div>

                            <div class="row q-mb-sm">
                                <div class="col-4 text-weight-medium">
                                    {{ __("Customer ID") }}:
                                </div>
                                <div class="col-8">{{ item.id }}</div>
                            </div>

                            <div class="row q-mb-sm">
                                <div class="col-4 text-weight-medium">
                                    {{ __("Full Name") }}:
                                </div>
                                <div class="col-8">
                                    {{ item.name }} {{ item.last_name }}
                                </div>
                            </div>

                            <div class="row q-mb-sm">
                                <div class="col-4 text-weight-medium">
                                    {{ __("Email") }}:
                                </div>
                                <div class="col-8">
                                    <a
                                        :href="`mailto:${item.email}`"
                                        class="text-primary"
                                        >{{ item.email }}</a
                                    >
                                </div>
                            </div>

                            <div class="row q-mb-sm">
                                <div class="col-4 text-weight-medium">
                                    {{ __("Phone") }}:
                                </div>
                                <div class="col-8 flex items-center">
                                    <span v-if="item.full_phone">{{
                                        item.full_phone
                                    }}</span>
                                    <span v-else>{{ __("Not provided") }}</span>

                                    <q-btn
                                        v-if="item.full_phone"
                                        flat
                                        round
                                        size="sm"
                                        color="green"
                                        icon="mdi-whatsapp"
                                        class="q-ml-sm"
                                        :href="`https://wa.me/${item.full_phone.replace(
                                            /\D/g,
                                            ''
                                        )}`"
                                        target="_blank"
                                    />
                                </div>
                            </div>

                            <div class="row q-mb-sm">
                                <div class="col-4 text-weight-medium">
                                    City:
                                </div>
                                <div class="col-8">
                                    {{ item.city || __("Not provided") }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4 text-weight-medium">
                                    Address:
                                </div>
                                <div class="col-8">
                                    {{ item.address || __("Not provided") }}
                                </div>
                            </div>
                        </div>

                        <!-- Order Statistics -->
                        <div class="col-12 col-md-6">
                            <div class="text-h6 q-mb-md">
                                {{ __("Order Statistics") }}
                            </div>

                            <div class="row q-mb-md">
                                <div class="col-6 text-weight-medium">
                                    {{ __("Total Orders") }}:
                                </div>
                                <div class="col-6">
                                    {{ item.checkouts_count }}
                                </div>
                            </div>

                            <div class="row q-mb-md">
                                <div class="col-12 text-weight-medium">
                                    {{ __("Totals by Currency") }}:
                                </div>
                                <div class="col-12">
                                    <div
                                        v-for="(c, idx) in item.checkout"
                                        :key="idx"
                                        class="row q-mb-xs"
                                    >
                                        <div class="col-6">
                                            {{ c.currency }}
                                        </div>
                                        <div class="col-6">
                                            {{ c.total }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <q-linear-progress
                                size="25px"
                                :value="item.checkouts_count / 10"
                                color="primary"
                                class="q-mb-lg"
                            >
                                <div class="absolute-full flex flex-center">
                                    <q-badge
                                        color="white"
                                        text-color="primary"
                                        :label="
                                            `${item.checkouts_count}` +
                                            __('Orders')
                                        "
                                    />
                                </div>
                            </q-linear-progress>
                        </div>
                    </div>
                </q-card-section>

                <q-separator />

                <!-- Action buttons -->
                <q-card-actions align="right">
                    <q-btn
                        flat
                        color="primary"
                        icon="email"
                        :label="__('Send Email')"
                        :href="`mailto:${item.email}`"
                        target="_blank"
                    />
                </q-card-actions>
            </q-card>

            <!-- Recent orders -->
            <q-card
                class="q-mt-md"
                v-if="customers.length > 0 && customers[0].orders > 0"
            >
                <q-card-section>
                    <div class="text-h6">{{ __("Order History") }}</div>
                    <div class="text-caption text-grey">
                        {{ __("This customer has placed") }}
                        {{ customers[0].orders }}
                        {{ __("orders totaling") }} ${{
                            customers[0].transactions_total
                        }}
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-section>
                    <div class="text-center q-pa-lg">
                        <q-icon name="receipt" size="50px" color="grey-5" />
                        <div class="text-grey q-mt-md">
                            {{ __("Order details would be shown here") }}
                        </div>
                        <q-btn
                            :label="__('View All Orders')"
                            color="primary"
                            flat
                            class="q-mt-md"
                        />
                    </div>
                </q-card-section>
            </q-card>

            <!-- Empty state if no orders -->
            <q-card class="q-mt-md" v-else-if="customers.length > 0">
                <q-card-section>
                    <div class="text-h6">{{ __("Order History") }}</div>
                </q-card-section>

                <q-separator />

                <q-card-section>
                    <div class="text-center q-pa-lg">
                        <q-icon
                            name="shopping_cart"
                            size="50px"
                            color="grey-5"
                        />
                        <div class="text-grey q-mt-md">
                            {{
                                __("This customer hasn't placed any orders yet")
                            }}
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </div>

        <div class="row justify-center q-my-lg" v-if="pages.total_pages > 1">
            <q-pagination
                v-model="search.page"
                color="primary"
                :max="pages.total_pages"
                :max-pages="6"
                boundary-numbers
                direction-links
                ellipses
                class="q-pa-sm bg-white rounded-borders shadow-1"
            />
        </div>
    </v-admin-ecommerce-layout>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            customers: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    watch: {
        "search.page"(value) {
            this.getCustomer();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getCustomer();
            }
        },
    },

    created() {
        this.getCustomer();
    },

    methods: {
        customerInitials(item) {
            if (item.name && item.last_name) {
                return item.name.charAt(0) + item.last_name.charAt(0);
            }
            return "CU";
        },

        async getCustomer(param = null) {
            var params = { ...this.search, ...param };
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.customers,
                    { params: params }
                );

                if (res.status == 200) {
                    this.customers = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) {
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.customer-detail-container {
    max-width: 1200px;
    margin: 0 auto;
}

.customer-card {
    border-radius: 8px;
    margin-bottom: 20px;
}
</style>
