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
    <v-admin-transaction-layout>
        <!-- Header Section -->
        <div class="bg-white q-pa-md shadow-2 rounded-borders">
            <div class="row items-center justify-between q-mb-md">
                <div>
                    <div class="text-h4 text-primary text-weight-bold">
                        {{ __("Transactions Management") }}
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        {{ __("Monitor and manage all transaction records") }}
                    </div>
                </div>

                <!-- Toggle View Mode -->
                <q-btn-toggle
                    v-model="viewMode"
                    dense
                    toggle-color="primary"
                    :options="[
                        {
                            value: 'list',
                            icon: 'mdi-format-list-bulleted',
                            label: __('List'),
                        },
                        {
                            value: 'grid',
                            icon: 'mdi-view-grid-outline',
                            label: __('Grid'),
                        },
                    ]"
                    unelevated
                    class="view-toggle"
                />
            </div>

            <!-- Filter Component -->
            <v-filter :params="params" @change="searching" class="q-mb-sm" />
        </div>

        <!-- Statistics Overview -->
        <div
            class="row q-col-gutter-md q-mb-md q-mt-sm"
            v-if="transactions.length > 0"
        >
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-blue-1 text-blue-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ transactions.length }} {{ __("Transaction")
                            }}{{ transactions.length !== 1 ? "s" : "" }}
                        </div>
                        <q-icon name="mdi-receipt" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-green-1 text-green-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ successfulCount }} {{ __("Successful") }}
                        </div>
                        <q-icon name="mdi-check-circle" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-orange-1 text-orange-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ pendingCount }} {{ __("Pending") }}
                        </div>
                        <q-icon name="mdi-clock-outline" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-red-1 text-red-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ failedCount }} {{ __("Failed") }}
                        </div>
                        <q-icon name="mdi-close-circle" size="md" />
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && transactions.length > 0"
            class="row q-col-gutter-lg q-pa-md"
        >
            <div
                v-for="(item, index) in transactions"
                :key="index"
                class="col-12 col-sm-6 col-md-4 col-lg-3"
            >
                <q-card bordered class="transaction-card shadow-3">
                    <q-card-section class="card-header bg-grey-2">
                        <div class="row items-center justify-between">
                            <div class="text-h6 text-primary text-weight-bold">
                                {{ item.billing_period }} {{ __("plan") }}
                            </div>
                            <div class="row items-center q-gutter-xs">
                                <v-activate
                                    @updated="getTransactions"
                                    v-if="check(item)"
                                    :item="item"
                                />
                                <v-detail :item="item" />
                            </div>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="q-pt-md">
                        <div class="transaction-details">
                            <div class="detail-item">
                                <q-icon
                                    name="mdi-receipt"
                                    color="blue"
                                    size="sm"
                                />
                                <span class="q-ml-sm"
                                    ><strong>{{ __("Code:") }}</strong>
                                    {{ item.code }}</span
                                >
                            </div>

                            <div class="detail-item">
                                <q-icon
                                    name="mdi-currency-usd"
                                    color="green"
                                    size="sm"
                                />
                                <span class="q-ml-sm"
                                    ><strong>{{ __("Price:") }}</strong>
                                    {{ item.total }} {{ item.currency }}</span
                                >
                            </div>

                            <div class="detail-item">
                                <q-icon
                                    name="mdi-calendar"
                                    color="purple"
                                    size="sm"
                                />
                                <span class="q-ml-sm"
                                    ><strong>{{ __("Created:") }}</strong>
                                    {{ item.created }}</span
                                >
                            </div>

                            <div class="detail-item">
                                <q-icon
                                    name="mdi-update"
                                    color="orange"
                                    size="sm"
                                />
                                <span class="q-ml-sm"
                                    ><strong>{{ __("Updated:") }}</strong>
                                    {{ item.updated }}</span
                                >
                            </div>

                            <div class="detail-item">
                                <q-icon
                                    name="mdi-credit-card"
                                    color="teal"
                                    size="sm"
                                />
                                <span class="q-ml-sm"
                                    ><strong>{{ __("Method:") }}</strong>
                                    {{ item.payment_method }}</span
                                >
                            </div>

                            <div class="detail-item">
                                <q-icon
                                    name="mdi-check-circle"
                                    color="green"
                                    size="sm"
                                />
                                <span class="q-ml-sm"
                                    ><strong>{{ __("Status:") }}</strong></span
                                >
                                <q-badge
                                    :color="getStatusColor(item.status)"
                                    class="q-ml-sm status-badge"
                                >
                                    {{ item.status }}
                                </q-badge>
                            </div>

                            <div class="detail-item">
                                <q-icon
                                    name="mdi-calendar-check"
                                    color="blue"
                                    size="sm"
                                />
                                <span class="q-ml-sm"
                                    ><strong>{{ __("Activated:") }}</strong>
                                    {{ item.activated }}</span
                                >
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && transactions.length === 0"
            class="text-center q-pa-xl"
        >
            <q-icon name="mdi-receipt-off" size="xl" color="grey-4" />
            <div class="text-h6 text-grey-6 q-mt-md">
                {{ __("No transactions found") }}
            </div>
            <div class="text-grey-5">
                {{ __("Try adjusting your search filters") }}
            </div>
        </div>

        <!-- List View -->
        <q-table
            v-else
            :rows="transactions"
            :columns="columns"
            row-key="code"
            flat
            bordered
            :loading="loading"
            :pagination="pagination"
            hide-pagination
            class="shadow-1 rounded-borders q-mt-md"
        >
            <template v-slot:body="props">
                <q-tr :props="props" class="q-hoverable">
                    <q-td key="code" :props="props">
                        <div class="text-weight-medium text-primary">
                            {{ props.row.code }}
                        </div>
                    </q-td>

                    <q-td key="price" :props="props">
                        <div class="text-weight-medium">
                            {{ props.row.total }} {{ props.row.currency }}
                        </div>
                    </q-td>

                    <q-td key="billing_period" :props="props">
                        <div class="text-caption">
                            {{ props.row.billing_period }} {{ __("plan") }}
                        </div>
                    </q-td>

                    <q-td key="created" :props="props">
                        <div class="text-caption">
                            {{ props.row.created }}
                        </div>
                    </q-td>

                    <q-td key="updated" :props="props">
                        <div class="text-caption">
                            {{ props.row.updated }}
                        </div>
                    </q-td>

                    <q-td key="payment_method" :props="props">
                        <div class="text-caption">
                            {{ props.row.payment_method }}
                        </div>
                    </q-td>

                    <q-td key="status" :props="props">
                        <q-badge
                            :color="getStatusColor(props.row.status)"
                            class="status-badge"
                        >
                            {{ props.row.status }}
                        </q-badge>
                    </q-td>

                    <q-td key="activated" :props="props">
                        <div class="text-caption">
                            {{ props.row.activated }}
                        </div>
                    </q-td>

                    <q-td key="actions" :props="props" auto-width>
                        <div class="flex justify-between">
                            <v-transaction-activate
                                @updated="getTransactions"
                                v-if="check(props.row)"
                                :item="props.row"
                            />
                            <v-detail :item="props.row" />
                        </div>
                    </q-td>
                </q-tr>
            </template>

            <template v-slot:no-data>
                <div class="full-width row flex-center text-grey-6 q-pa-xl">
                    <q-icon name="mdi-receipt-off" size="xl" />
                    <div class="q-ml-sm">
                        {{ __("No transactions available") }}
                    </div>
                </div>
            </template>

            <template v-slot:loading>
                <q-inner-loading showing color="primary" />
            </template>
        </q-table>

        <!-- Pagination -->
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

            <q-select
                v-model="search.per_page"
                :options="[10, 15, 25, 50]"
                :label="__('Items per page')"
                dense
                outlined
                class="q-ml-md"
                style="min-width: 140px"
                @update:model-value="getTransactions"
            >
                <template v-slot:prepend>
                    <q-icon name="mdi-format-list-numbered" />
                </template>
            </q-select>
        </div>
    </v-admin-transaction-layout>
</template>

<script>
import VDetail from "./Detail.vue";

export default {
    components: {
        VDetail,
    },

    data() {
        return {
            viewMode: "list",
            loading: false,
            params: [
                { key: "code", value: "code" },
                { key: "session", value: "session_id" },
                { key: "intent", value: "payment_intent_id" },
                { key: "created", value: "created" },
                { key: "updated", value: "updated" },
            ],
            transactions: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            pagination: {
                sortBy: "created",
                descending: true,
                page: 1,
                rowsPerPage: 15,
            },
            columns: [
                {
                    name: "code",
                    label: this.__("Transaction Code"),
                    field: "code",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "price",
                    label: this.__("Amount"),
                    field: (row) => `${row.total} ${row.currency}`,
                    align: "left",
                    sortable: true,
                },
                {
                    name: "billing_period",
                    label: this.__("Plan Type"),
                    field: "billing_period",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "created",
                    label: this.__("Created Date"),
                    field: "created",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "payment_method",
                    label: this.__("Payment Method"),
                    field: "payment_method",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "status",
                    label: this.__("Status"),
                    field: "status",
                    align: "center",
                    sortable: true,
                },
                {
                    name: "activated",
                    label: this.__("Activation Date"),
                    field: "activated",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: this.__("Actions"),
                    field: "actions",
                    align: "right",
                    sortable: false,
                },
            ],
        };
    },

    computed: {
        successfulCount() {
            return this.transactions.filter((t) => t.status === "successful")
                .length;
        },
        pendingCount() {
            return this.transactions.filter((t) => t.status === "pending")
                .length;
        },
        failedCount() {
            return this.transactions.filter((t) => t.status === "failed")
                .length;
        },
    },

    watch: {
        "search.page"(value) {
            this.getTransactions();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getTransactions();
            }
        },
    },

    created() {
        this.getTransactions();
    },

    methods: {
        getStatusColor(status) {
            switch (status) {
                case "successful":
                    return "green";
                case "pending":
                    return "orange";
                case "failed":
                    return "red";
                default:
                    return "grey";
            }
        },

        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getTransactions(event);
        },

        async getTransactions(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: params,
                });
                if (res.status == 200) {
                    this.transactions = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        check(item) {
            return item.status == "pending" || item.status == "failed";
        },

        toggleView() {
            this.viewMode = this.viewMode === "grid" ? "list" : "grid";
        },
    },
};
</script>

<style lang="css" scoped>
.transaction-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
}

.transaction-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.transaction-details {
    display: grid;
    gap: 0.75rem;
}

.detail-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    background: #fafafa;
    border-radius: 6px;
    border: 1px solid #f0f0f0;
}

.status-badge {
    font-size: 0.75em;
    padding: 4px 8px;
    border-radius: 12px;
}

.view-toggle {
    border-radius: 8px;
    overflow: hidden;
}

.shadow-3 {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.08);
}
</style>
