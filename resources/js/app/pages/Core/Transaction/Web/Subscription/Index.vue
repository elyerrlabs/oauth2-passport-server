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
    <v-user-layout>
        <q-toolbar>
            <q-toolbar-title>
                <q-icon name="list_alt" class="q-mr-sm" />
                List of packages
            </q-toolbar-title>
        </q-toolbar>

        <div class="q-pa-md">
            <q-table
                :rows="packages"
                :columns="columns"
                row-key="id"
                flat
                bordered
                hide-bottom
                :rows-per-page-options="[search.per_page]"
            >
                <!-- Name -->
                <template v-slot:body-cell-name="props">
                    <q-td>
                        <div class="text-weight-medium text-primary">
                            {{ props.row.meta.name }}
                        </div>
                        <div class="text-caption text-grey">
                            {{ props.row.transaction.billing_period_name }} plan
                        </div>
                    </q-td>
                </template>

                <!-- Price -->
                <template v-slot:body-cell-price="props">
                    <q-td>
                        {{ props.row.transaction.total }}
                        {{ props.row.transaction.currency }}
                    </q-td>
                </template>

                <!-- Bonus -->
                <template v-slot:body-cell-bonus="props">
                    <q-td>
                        <div v-if="props.row.meta.bonus_enabled">
                            🎁 {{ props.row.meta.bonus_duration }} days
                        </div>
                        <div v-else class="text-grey">—</div>
                    </q-td>
                </template>

                <!-- Start / End -->
                <template v-slot:body-cell-start="props">
                    <q-td>{{ props.row.start_at }}</q-td>
                </template>
                <template v-slot:body-cell-end="props">
                    <q-td>{{ props.row.end_at }}</q-td>
                </template>

                <!-- Method -->
                <template v-slot:body-cell-method="props">
                    <q-td>{{ props.row.transaction.payment_method }}</q-td>
                </template>

                <!-- Recurring -->
                <template v-slot:body-cell-recurring="props">
                    <q-td>
                        <q-badge
                            :color="props.row.is_recurring ? 'green' : 'grey'"
                            outline
                            align="middle"
                        >
                            <q-icon
                                :name="
                                    props.row.is_recurring
                                        ? 'mdi-autorenew'
                                        : 'mdi-cancel'
                                "
                                class="q-mr-xs"
                                size="16px"
                            />
                            {{ props.row.is_recurring ? "Yes" : "No" }}
                        </q-badge>
                    </q-td>
                </template>

                <!-- Status -->
                <template v-slot:body-cell-status="props">
                    <q-td>
                        <q-badge
                            :color="
                                props.row.status === 'successful'
                                    ? 'green'
                                    : 'orange'
                            "
                            text-color="white"
                            align="middle"
                        >
                            {{ props.row.status }}
                        </q-badge>
                    </q-td>
                </template>

                <!-- Actions -->
                <template v-slot:body-cell-actions="props">
                    <q-td>
                        <v-recurring-payment
                            :item="props.row"
                            @success="getPackages"
                        />

                        <q-btn
                            clicked
                            class="text-primary q-ma-sm"
                            size="sm"
                            outline
                            icon="mdi-eye"
                            :href="props.row.links.show"
                        >
                            view
                        </q-btn>
                    </q-td>
                </template>
            </q-table>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="grey-8"
                :max="pages.total_pages"
                size="md"
                direction-links
                boundary-numbers
            />
        </div>
    </v-user-layout>
</template>

<script>
import VRecurringPayment from "./RecurringPayment.vue";

export default {
    components: {
        VRecurringPayment,
    },

    data() {
        return {
            loading: false,
            user: [],
            packages: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                {
                    name: "name",
                    label: "Name",
                    field: "meta.name",
                    align: "left",
                },
                { name: "price", label: "Price", align: "left" },
                { name: "bonus", label: "Bonus", align: "left" },
                { name: "start", label: "Start", align: "left" },
                { name: "end", label: "End", align: "left" },
                { name: "method", label: "Method", align: "left" },
                {
                    name: "recurring",
                    label: "Recurring",
                    align: "center",
                    field: "is_recurring",
                    sortable: false,
                },
                { name: "status", label: "Status", align: "center" },
                { name: "actions", label: "", align: "center" },
            ],
        };
    },

    created() {
        this.user = this.$page.props.user;

        this.getPackages();
    },

    watch: {
        "search.page"(value) {
            this.getPackages();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getPackages();
            }
        },
    },

    methods: {
        async getPackages() {
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.packages = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
