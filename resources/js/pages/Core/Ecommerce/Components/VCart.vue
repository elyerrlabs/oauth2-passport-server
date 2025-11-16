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
    <div
        class="justify-center md:justify-between items-center space-x-4 p-2 flex"
    >
        <!-- Orders Button -->
        <button
            @click="open($page.props.ecommerce_orders.route)"
            class="px-2 py-1 text-gray-200 bg-purple-700 rounded-full hover:text-white relative cursor-pointer"
        >
            <i class="mdi mdi-cart text-xl"></i>
            <!-- Badge -->
            <span
                v-if="orders.length"
                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
            >
                {{ orders.length }}
            </span>
        </button>

        <!-- Checkout Button -->
        <button
            @click="open($page.props.ecommerce_checkout.route)"
            class="px-2 py-1 text-gray-200 bg-purple-700 rounded-full hover:text-white relative cursor-pointer"
        >
            <i class="mdi mdi-credit-card lg:text-xl"></i>
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            orders: [],
        };
    },
    created() {
        if (this.$page.props.user?.id) {
            this.getOrders();
        }
    },
    mounted() {
        this.getOrders();
    },
    methods: {
        open(url) {
            if (window.location.href !== url) {
                window.location.href = url;
            }
        },
        async getOrders() {
            try {
                const res = await this.$server.get(
                    this.$page.props.api.ecommerce.orders,
                    {
                        params: { per_page: 100 },
                    }
                );
                if (res.status === 200) {
                    this.orders = res.data.data;
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
