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
        <div class="packages-page">
            <!-- Header Section -->
            <div class="page-header">
                <q-toolbar class="header-toolbar">
                    <q-icon
                        name="mdi-package-variant"
                        size="32px"
                        color="primary"
                        class="header-icon"
                    />
                    <q-toolbar-title
                        class="text-h4 text-weight-bold text-grey-8"
                    >
                        {{ __("Subscription Packages") }}
                    </q-toolbar-title>
                    <q-space />
                    <q-btn
                        color="primary"
                        icon="mdi-refresh"
                        :label="__('Refresh')"
                        outline
                        @click="getPackages"
                        :loading="loading"
                    />
                </q-toolbar>
                <div class="text-subtitle1 text-grey-7 q-mt-sm header-subtitle">
                    {{
                        __(
                            "Manage your subscription packages and billing information"
                        )
                    }}
                </div>
            </div>

            <!-- Packages Table -->
            <div class="packages-container q-pa-md">
                <q-card flat bordered class="packages-card">
                    <q-table
                        :rows="packages"
                        :columns="columns"
                        row-key="id"
                        flat
                        bordered
                        hide-bottom
                        :rows-per-page-options="[search.per_page]"
                        :loading="loading"
                        class="packages-table"
                    >
                        <!-- Loading State -->
                        <template v-slot:loading>
                            <q-inner-loading showing color="primary" />
                        </template>

                        <!-- Name Column -->
                        <template v-slot:body-cell-name="props">
                            <q-td class="name-cell">
                                <div
                                    class="package-name text-weight-bold text-primary"
                                >
                                    {{ props.row.meta.name }}
                                </div>
                                <div
                                    class="package-period text-caption text-grey-6"
                                >
                                    <q-icon
                                        name="mdi-calendar"
                                        size="14px"
                                        class="q-mr-xs"
                                    />
                                    {{
                                        props.row.transaction
                                            .billing_period_name
                                    }}
                                    {{ __("plan") }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Price Column -->
                        <template v-slot:body-cell-price="props">
                            <q-td class="price-cell">
                                <div class="price-amount text-weight-bold">
                                    {{ props.row.transaction.currency }}
                                    {{ props.row.transaction.total }}
                                </div>
                                <div
                                    class="price-frequency text-caption text-grey-6"
                                >
                                    {{ __("One-time payment") }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Bonus Column -->
                        <template v-slot:body-cell-bonus="props">
                            <q-td class="bonus-cell">
                                <div
                                    v-if="props.row.meta.bonus_enabled"
                                    class="bonus-badge"
                                >
                                    <q-icon
                                        name="mdi-gift"
                                        color="orange"
                                        size="18px"
                                        class="q-mr-xs"
                                    />
                                    <span
                                        class="text-orange text-weight-medium"
                                    >
                                        +{{ props.row.meta.bonus_duration }}
                                        {{ __("days free") }}
                                    </span>
                                </div>
                                <div v-else class="text-grey-5">—</div>
                            </q-td>
                        </template>

                        <!-- Date Columns -->
                        <template v-slot:body-cell-start="props">
                            <q-td class="date-cell">
                                <div class="date-label">
                                    {{ __("Started") }}
                                </div>
                                <div class="date-value text-weight-medium">
                                    {{ formatDate(props.row.start_at) }}
                                </div>
                            </q-td>
                        </template>
                        <template v-slot:body-cell-end="props">
                            <q-td class="date-cell">
                                <div class="date-label">
                                    {{ __("Expires") }}
                                </div>
                                <div
                                    class="date-value text-weight-medium"
                                    :class="{
                                        'text-positive': isDateFuture(
                                            props.row.end_at
                                        ),
                                        'text-negative': !isDateFuture(
                                            props.row.end_at
                                        ),
                                    }"
                                >
                                    {{ formatDate(props.row.end_at) }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Payment Method Column -->
                        <template v-slot:body-cell-method="props">
                            <q-td class="method-cell">
                                <q-badge
                                    color="blue-1"
                                    text-color="blue-8"
                                    class="method-badge"
                                >
                                    <q-icon
                                        :name="
                                            getPaymentMethodIcon(
                                                props.row.transaction
                                                    .payment_method
                                            )
                                        "
                                        size="14px"
                                        class="q-mr-xs"
                                    />
                                    {{ props.row.transaction.payment_method }}
                                </q-badge>
                            </q-td>
                        </template>

                        <!-- Recurring Column -->
                        <template v-slot:body-cell-recurring="props">
                            <q-td class="recurring-cell">
                                <q-badge
                                    :color="
                                        props.row.is_recurring
                                            ? 'green-1'
                                            : 'grey-3'
                                    "
                                    :text-color="
                                        props.row.is_recurring
                                            ? 'green-8'
                                            : 'grey-7'
                                    "
                                    class="recurring-badge"
                                    outline
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
                                    {{
                                        props.row.is_recurring
                                            ? __("Active")
                                            : __("Inactive")
                                    }}
                                </q-badge>
                            </q-td>
                        </template>

                        <!-- Status Column -->
                        <template v-slot:body-cell-status="props">
                            <q-td class="status-cell">
                                <q-badge
                                    :color="getStatusColor(props.row.status)"
                                    class="status-badge"
                                    rounded
                                >
                                    <q-icon
                                        :name="getStatusIcon(props.row.status)"
                                        class="q-mr-xs"
                                        size="14px"
                                    />
                                    {{ props.row.status }}
                                </q-badge>
                            </q-td>
                        </template>

                        <!-- Actions Column -->
                        <template v-slot:body-cell-actions="props">
                            <q-td class="actions-cell">
                                <div class="action-buttons">
                                    <v-recurring-payment
                                        :item="props.row"
                                        @success="getPackages"
                                        class="q-mr-sm"
                                    />
                                    <q-btn
                                        color="primary"
                                        size="sm"
                                        outline
                                        icon="mdi-eye"
                                        :href="props.row.links.show"
                                        :label="__('View')"
                                        class="view-btn"
                                    />
                                </div>
                            </q-td>
                        </template>

                        <!-- Empty State -->
                        <template v-slot:no-data>
                            <div class="empty-state text-center q-pa-xl">
                                <q-icon
                                    name="mdi-package-variant-closed"
                                    size="64px"
                                    color="grey-4"
                                    class="empty-icon"
                                />
                                <div
                                    class="empty-title text-h6 text-grey-7 q-mt-md"
                                >
                                    {{ __("No packages found") }}
                                </div>
                                <div class="empty-subtitle text-grey-5">
                                    {{
                                        __(
                                            "Your subscription packages will appear here"
                                        )
                                    }}
                                </div>
                                <q-btn
                                    color="primary"
                                    :label="__('Browse Plans')"
                                    unelevated
                                    class="q-mt-md"
                                    :href="route('plans.index')"
                                    v-if="$page.props.routes.plans"
                                />
                            </div>
                        </template>
                    </q-table>
                </q-card>
            </div>

            <!-- Pagination -->
            <div class="pagination-section row justify-center q-mt-lg q-mb-xl">
                <q-pagination
                    v-model="search.page"
                    color="primary"
                    :max="pages.total_pages"
                    :max-pages="6"
                    size="md"
                    direction-links
                    boundary-numbers
                    unelevated
                    class="custom-pagination"
                />
            </div>
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
                    label: this.__("Package"),
                    field: "meta.name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "price",
                    label: this.__("Price"),
                    align: "left",
                    sortable: true,
                },
                {
                    name: "bonus",
                    label: this.__("Bonus"),
                    align: "center",
                    sortable: false,
                },
                {
                    name: "start",
                    label: this.__("Start Date"),
                    align: "left",
                    sortable: true,
                },
                {
                    name: "end",
                    label: this.__("End Date"),
                    align: "left",
                    sortable: true,
                },
                {
                    name: "method",
                    label: this.__("Payment Method"),
                    align: "center",
                    sortable: true,
                },
                {
                    name: "recurring",
                    label: this.__("Recurring"),
                    align: "center",
                    field: "is_recurring",
                    sortable: true,
                },
                {
                    name: "status",
                    label: this.__("Status"),
                    align: "center",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: this.__("Actions"),
                    align: "center",
                    sortable: false,
                },
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
            this.loading = true;
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.packages = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {
                console.error("Failed to load packages:", error);
                this.$q.notify({
                    message: "Failed to load packages. Please try again.",
                    color: "negative",
                    icon: "error",
                    position: "top",
                });
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            if (!dateString) return "N/A";
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        isDateFuture(dateString) {
            if (!dateString) return false;
            return new Date(dateString) > new Date();
        },

        getStatusColor(status) {
            const statusColors = {
                successful: "positive",
                active: "positive",
                pending: "warning",
                failed: "negative",
                cancelled: "grey",
                expired: "orange",
            };
            return statusColors[status] || "grey";
        },

        getStatusIcon(status) {
            const statusIcons = {
                successful: "mdi-check-circle",
                active: "mdi-check-circle",
                pending: "mdi-clock-outline",
                failed: "mdi-close-circle",
                cancelled: "mdi-cancel",
                expired: "mdi-alert-circle",
            };
            return statusIcons[status] || "mdi-help-circle";
        },

        getPaymentMethodIcon(method) {
            const methodIcons = {
                credit_card: "mdi-credit-card",
                paypal: "mdi-paypal",
                stripe: "mdi-credit-card",
                bank_transfer: "mdi-bank",
                crypto: "mdi-bitcoin",
                default: "mdi-cash",
            };
            return methodIcons[method?.toLowerCase()] || methodIcons.default;
        },
    },
};
</script>

<style lang="scss" scoped>
.packages-page {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

.page-header {
    background: white;
    padding: 24px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);

    .header-toolbar {
        padding: 0;

        .header-icon {
            background: rgba(0, 0, 0, 0.05);
            padding: 12px;
            border-radius: 50%;
        }
    }

    .header-subtitle {
        max-width: 800px;
    }
}

.packages-container {
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.packages-card {
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.packages-table {
    :deep(.q-table__top) {
        padding: 20px 24px;
        background: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    :deep(.q-table thead tr) {
        background: rgba(0, 0, 0, 0.02);

        th {
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
            padding: 16px 12px;

            &.sortable:hover {
                color: var(--q-primary);
            }
        }
    }

    :deep(.q-table tbody td) {
        padding: 16px 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    }

    :deep(.q-table tbody tr:hover) {
        background: rgba(0, 123, 255, 0.03) !important;
    }
}

// Custom cell styles
.name-cell {
    .package-name {
        font-size: 1rem;
        margin-bottom: 4px;
    }

    .package-period {
        display: flex;
        align-items: center;
    }
}

.price-cell {
    .price-amount {
        font-size: 1.1rem;
        color: var(--q-primary);
    }

    .price-frequency {
        font-size: 0.75rem;
    }
}

.bonus-cell {
    .bonus-badge {
        display: flex;
        align-items: center;
        padding: 4px 8px;
        background: rgba(255, 152, 0, 0.1);
        border-radius: 6px;
    }
}

.date-cell {
    .date-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 2px;
    }

    .date-value {
        font-size: 0.9rem;
    }
}

.method-cell {
    .method-badge {
        padding: 6px 10px;
        border-radius: 16px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
}

.recurring-cell {
    .recurring-badge {
        padding: 6px 10px;
        border-radius: 16px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
}

.status-cell {
    .status-badge {
        padding: 6px 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
}

.actions-cell {
    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        .view-btn {
            min-width: 80px;
        }
    }
}

.empty-state {
    .empty-icon {
        opacity: 0.5;
    }

    .empty-title {
        font-weight: 500;
    }

    .empty-subtitle {
        font-size: 0.9rem;
    }
}

.pagination-section {
    .custom-pagination {
        :deep(.q-btn) {
            border-radius: 8px;
            margin: 0 4px;

            &.q-btn--active {
                background: var(--q-primary);
                color: white;
            }
        }
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .page-header {
        padding: 20px;

        .text-h4 {
            font-size: 1.75rem;
        }
    }

    .packages-container {
        padding: 16px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 8px;

        .view-btn {
            min-width: 60px !important;
        }
    }
}

@media (max-width: 767px) {
    .page-header {
        padding: 16px;

        .text-h4 {
            font-size: 1.5rem;
        }

        .header-toolbar {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
    }

    .packages-container {
        padding: 12px;
    }

    .packages-table {
        :deep(.q-table thead) {
            display: none;
        }

        :deep(.q-table tbody tr) {
            display: block;
            margin-bottom: 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 16px;
        }

        :deep(.q-table tbody td) {
            display: block;
            text-align: left !important;
            border: none;
            padding: 8px 0;

            &:before {
                content: attr(data-label);
                font-weight: 600;
                color: #374151;
                display: block;
                margin-bottom: 4px;
                font-size: 0.8rem;
            }
        }
    }
}
</style>
