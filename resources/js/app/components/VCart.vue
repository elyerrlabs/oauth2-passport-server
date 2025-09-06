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
    <div class="text-center q-ma-sm">
        <q-btn
            no-caps
            dense
            rounded
            round
            outline
            icon="mdi-cart"
            class="text-white"
            @click="open()"
        >
            <q-badge color="red" floating>{{ orders.length }}</q-badge>
        </q-btn>
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
        setInterval(() => {
            if (this.$page.props.user?.id) {
                this.getOrders();
            }
        }, 10000);
    },

    methods: {
        open() {
            if (
                window.location.href != this.$page.props.ecommerce_orders.route
            ) {
                window.location.href = this.$page.props.ecommerce_orders.route;
            }
        },

        async getOrders() {
            try {
                const res = await this.$server.get(
                    this.$page.props.ecommerce_orders.route,
                    {
                        params: {
                            per_page: 100,
                        },
                    }
                );

                if (res.status == 200) {
                    this.orders = res.data.data;
                }
            } catch (error) {}
        },
    },
};
</script>
<style lang=""></style>
