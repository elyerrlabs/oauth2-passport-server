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
    <v-partner-layout>
        <div class="sales-dashboard q-pa-lg">
            <!-- Header Section -->
            <div class="row items-center justify-between q-mb-lg">
                <div>
                    <div class="text-h4 text-weight-bold text-primary">
                        Sales Performance
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        Track your commissions and sales history
                    </div>
                </div>
                <div class="row items-center q-gutter-sm">
                    <q-btn
                        icon="refresh"
                        round
                        dense
                        color="primary"
                        @click="getSales"
                        class="q-mr-sm"
                    >
                        <q-tooltip>Refresh data</q-tooltip>
                    </q-btn>
                    <q-select
                        v-model="search.per_page"
                        :options="[10, 15, 25, 50]"
                        label="Rows per page"
                        dense
                        outlined
                        style="min-width: 140px"
                        @update:model-value="getSales"
                    />
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="row q-col-gutter-md q-mb-lg">
                <div class="col-12 col-md-4">
                    <q-card flat class="stats-card total-sales-card">
                        <q-card-section>
                            <div class="row items-center">
                                <q-avatar
                                    color="blue-1"
                                    text-color="primary"
                                    icon="mdi-cash"
                                    class="q-mr-md"
                                />
                                <div>
                                    <div class="text-caption text-grey-7">
                                        TOTAL SALES VALUE
                                    </div>
                                    <div class="text-h6 text-weight-bold">
                                        {{ formatCurrency(totalSalesValue) }}
                                    </div>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-12 col-md-4">
                    <q-card flat class="stats-card total-commission-card">
                        <q-card-section>
                            <div class="row items-center">
                                <q-avatar
                                    color="green-1"
                                    text-color="positive"
                                    icon="mdi-percent"
                                    class="q-mr-md"
                                />
                                <div>
                                    <div class="text-caption text-grey-7">
                                        TOTAL COMMISSIONS
                                    </div>
                                    <div
                                        class="text-h6 text-weight-bold text-positive"
                                    >
                                        {{ formatCurrency(totalCommissions) }}
                                    </div>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-12 col-md-4">
                    <q-card flat class="stats-card transactions-card">
                        <q-card-section>
                            <div class="row items-center">
                                <q-avatar
                                    color="orange-1"
                                    text-color="warning"
                                    icon="mdi-file-document"
                                    class="q-mr-md"
                                />
                                <div>
                                    <div class="text-caption text-grey-7">
                                        TOTAL TRANSACTIONS
                                    </div>
                                    <div class="text-h6 text-weight-bold">
                                        {{ sales.length }}
                                    </div>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
            </div>

            <!-- Main Data Table -->
            <q-card flat class="data-card shadow-2">
                <q-card-section class="card-header">
                    <div class="text-h6 text-weight-medium">Sales History</div>
                </q-card-section>

                <q-table
                    :rows="sales"
                    :columns="columns"
                    row-key="id"
                    flat
                    bordered
                    :loading="loading"
                    :rows-per-page-options="[0]"
                    hide-pagination
                    class="sales-table"
                >
                    <template v-slot:loading>
                        <q-inner-loading showing color="primary" />
                    </template>

                    <template v-slot:body-cell-currency="props">
                        <q-td :props="props">
                            <q-badge
                                color="blue-1"
                                text-color="blue-9"
                                class="currency-badge"
                            >
                                {{ props.value }}
                            </q-badge>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-status="props">
                        <q-td :props="props">
                            <q-badge
                                :color="getStatusColor(props.value)"
                                class="status-badge"
                            >
                                {{ props.value }}
                            </q-badge>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-total="props">
                        <q-td :props="props" class="text-right">
                            <div class="column">
                                <div class="text-weight-bold text-primary">
                                    {{
                                        formatCurrency(
                                            calculateCommission(props.row)
                                        )
                                    }}
                                </div>
                                <div class="text-caption text-grey-7">
                                    Commission
                                </div>
                            </div>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-created="props">
                        <q-td :props="props">
                            <div class="column">
                                <div>{{ formatDate(props.value) }}</div>
                                <div class="text-caption text-grey-7">
                                    {{ formatTime(props.value) }}
                                </div>
                            </div>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-updated="props">
                        <q-td :props="props">
                            <div class="column">
                                <div>{{ formatDate(props.value) }}</div>
                                <div class="text-caption text-grey-7">
                                    {{ formatTime(props.value) }}
                                </div>
                            </div>
                        </q-td>
                    </template>

                    <template v-slot:no-data>
                        <div
                            class="full-width row flex-center text-grey q-pa-lg"
                        >
                            <q-icon
                                name="mdi-database-remove"
                                size="2em"
                                class="q-mr-sm"
                            />
                            <span>No sales records found</span>
                        </div>
                    </template>
                </q-table>

                <!-- Pagination -->
                <div
                    class="row justify-between items-center q-pa-md"
                    v-if="pages.total_pages > 1"
                >
                    <div class="text-caption text-grey-7">
                        Page {{ search.page }} of {{ pages.total_pages }} •
                        {{ pages.total_count }} total records
                    </div>

                    <q-pagination
                        v-model="search.page"
                        color="primary"
                        :max="pages.total_pages"
                        :max-pages="6"
                        boundary-numbers
                        direction-links
                        unelevated
                    />
                </div>
            </q-card>
        </div>
    </v-partner-layout>
</template>

<script>
export default {
    data() {
        return {
            sales: [],
            loading: false,
            columns: [
                {
                    name: "currency",
                    label: "Currency",
                    field: "currency",
                    align: "left",
                    sortable: true,
                    headerClasses: "text-weight-bold",
                },
                {
                    name: "status",
                    label: "Status",
                    field: "status",
                    align: "center",
                    sortable: true,
                    headerClasses: "text-weight-bold",
                },
                {
                    name: "total",
                    label: "Commission",
                    field: "total",
                    align: "right",
                    sortable: true,
                    headerClasses: "text-weight-bold",
                },
                {
                    name: "created",
                    label: "Created Date",
                    field: "created",
                    align: "left",
                    sortable: true,
                    headerClasses: "text-weight-bold",
                },
                {
                    name: "updated",
                    label: "Updated Date",
                    field: "updated",
                    align: "left",
                    sortable: true,
                    headerClasses: "text-weight-bold",
                },
            ],
            pages: {
                total_pages: 0,
                total_count: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    computed: {
        totalSalesValue() {
            return this.sales.reduce(
                (sum, sale) => sum + parseFloat(sale.total || 0),
                0
            );
        },
        totalCommissions() {
            return this.sales.reduce(
                (sum, sale) => sum + this.calculateCommission(sale),
                0
            );
        },
    },

    watch: {
        "search.page"(value) {
            this.getSales();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getSales();
            }
        },
    },

    created() {
        this.getSales();
    },

    methods: {
        async getSales(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: params,
                });

                if (res.status == 200) {
                    var values = res.data;
                    this.sales = values.data;
                    this.pages = res.data.meta.pagination;
                    this.search.total_pages =
                        res.data.meta.pagination.total_pages;
                }
            } catch (error) {
                console.error("Error loading sales data:", error);
                this.$q.notify({
                    type: "negative",
                    message: "Failed to load sales data",
                    position: "top-right",
                });
            } finally {
                this.loading = false;
            }
        },

        calculateCommission(row) {
            return (
                (parseFloat(row.total || 0) *
                    parseFloat(row.partner_commission_rate || 0)) /
                100
            );
        },

        formatCurrency(value) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value);
        },

        formatDate(dateString) {
            if (!dateString) return "-";
            return new Date(dateString).toLocaleDateString();
        },

        formatTime(dateString) {
            if (!dateString) return "-";
            return new Date(dateString).toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit",
            });
        },

        getStatusColor(status) {
            const statusColors = {
                completed: "positive",
                pending: "warning",
                failed: "negative",
                refunded: "info",
                processing: "blue",
            };
            return statusColors[status.toLowerCase()] || "grey";
        },
    },
};
</script>

<style scoped>
.sales-dashboard {
    max-width: 1400px;
    margin: 0 auto;
}

.stats-card {
    border-radius: 12px;
    border-left: 4px solid;
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.total-sales-card {
    border-left-color: #1976d2;
}

.total-commission-card {
    border-left-color: #4caf50;
}

.transactions-card {
    border-left-color: #ff9800;
}

.data-card {
    border-radius: 12px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.sales-table {
    border-radius: 0 0 12px 12px;
}

.status-badge {
    border-radius: 12px;
    padding: 4px 12px;
    font-size: 0.8rem;
}

.currency-badge {
    border-radius: 8px;
    padding: 4px 8px;
    font-weight: 500;
}

::v-deep .q-table__top {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

::v-deep .q-table thead tr {
    height: 50px;
}

::v-deep .q-table thead th {
    font-size: 0.9rem;
    font-weight: 600;
    background-color: #f8f9fa;
}

::v-deep .q-table tbody td {
    font-size: 0.9rem;
    height: 60px;
}

::v-deep .q-pagination .q-btn {
    border-radius: 8px;
    margin: 0 2px;
}

@media (max-width: 600px) {
    .sales-dashboard {
        padding: 12px;
    }

    .text-h4 {
        font-size: 1.5rem;
    }

    .stats-card .text-h6 {
        font-size: 1.1rem;
    }

    ::v-deep .q-table thead th {
        font-size: 0.8rem;
    }

    ::v-deep .q-table tbody td {
        font-size: 0.8rem;
        padding: 8px 4px;
    }
}
</style>
