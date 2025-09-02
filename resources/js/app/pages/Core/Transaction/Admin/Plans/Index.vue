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
        <div class="header-section q-pa-lg bg-primary text-white">
            <div class="row items-center">
                <div class="col">
                    <div class="text-h4 text-weight-bold">
                        {{ __("Subscription Plans") }}
                    </div>
                    <div class="text-subtitle1 opacity-70">
                        {{ __("Manage your subscription offerings") }}
                    </div>
                </div>
                <div class="col-auto">
                    <v-create @created="getPlans" />
                </div>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="q-pa-lg">
            <div class="row q-col-gutter-lg">
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    class="col-12 col-md-6 col-lg-4"
                >
                    <!-- Plan Card -->
                    <q-card class="plan-card shadow-5 rounded-borders" flat>
                        <q-card-section
                            class="plan-header"
                            :class="plan.active ? 'bg-active' : 'bg-inactive'"
                        >
                            <div class="row items-center no-wrap">
                                <div class="col">
                                    <div
                                        class="text-h6 text-weight-bold ellipsis plan-name"
                                    >
                                        {{ plan.name }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="row no-wrap q-gutter-xs">
                                        <v-update
                                            @updated="getPlans"
                                            :item="plan"
                                        />
                                        <v-delete
                                            @deleted="getPlans"
                                            :item="plan"
                                        />
                                    </div>
                                </div>
                            </div>
                        </q-card-section>

                        <!-- Plan Description -->
                        <q-card-section class="q-pt-md">
                            <div
                                class="plan-description text-body2"
                                v-html="plan.description"
                            ></div>
                        </q-card-section>

                        <!-- Status Indicators -->
                        <q-card-section class="q-pt-none">
                            <div class="row q-col-gutter-xs">
                                <div class="col-auto">
                                    <q-badge
                                        :color="
                                            plan.active ? 'positive' : 'grey-5'
                                        "
                                        :text-color="
                                            plan.active ? 'white' : 'dark'
                                        "
                                        class="status-badge"
                                    >
                                        <q-icon
                                            :name="
                                                plan.active
                                                    ? 'mdi-check-circle'
                                                    : 'mdi-close-circle'
                                            "
                                            size="16px"
                                            class="q-mr-xs"
                                        />
                                        {{
                                            plan.active
                                                ? __("Active")
                                                : __("Inactive")
                                        }}
                                    </q-badge>
                                </div>

                                <div class="col-auto" v-if="plan.bonus_enabled">
                                    <q-badge
                                        color="accent"
                                        class="status-badge"
                                    >
                                        <q-icon
                                            name="mdi-gift"
                                            size="16px"
                                            class="q-mr-xs"
                                        />
                                        {{ __("Bonus:") }}
                                        {{ plan.bonus_duration }}
                                        {{ __("days") }}
                                    </q-badge>
                                </div>

                                <div class="col-auto" v-if="plan.trial_enabled">
                                    <q-badge
                                        color="secondary"
                                        class="status-badge"
                                    >
                                        <q-icon
                                            name="mdi-clock"
                                            size="16px"
                                            class="q-mr-xs"
                                        />
                                        {{ __("Trial:") }}
                                        {{ plan.trial_duration }}
                                        {{ __("days") }}
                                    </q-badge>
                                </div>
                            </div>
                        </q-card-section>

                        <q-separator />

                        <!-- Scopes Section -->
                        <q-card-section>
                            <div
                                class="text-subtitle2 text-weight-medium section-title q-mb-sm"
                            >
                                <q-icon
                                    name="mdi-key-chain-variant"
                                    class="q-mr-sm"
                                />
                                {{ __("Access Scopes") }}
                            </div>

                            <div v-if="plan.scopes.length">
                                <q-list class="scope-list rounded-borders">
                                    <q-expansion-item
                                        v-for="(item, index) in plan.scopes"
                                        :key="index"
                                        expand-icon-toggle
                                        switch-toggle-side
                                        class="scope-item"
                                        :class="index === 0 ? 'first-item' : ''"
                                    >
                                        <template v-slot:header>
                                            <q-item-section>
                                                <div class="text-weight-medium">
                                                    {{
                                                        item.service.group.name
                                                    }}
                                                </div>
                                                <div class="text-caption">
                                                    {{ item.service.name }} -
                                                    {{ item.role.name }}
                                                </div>
                                            </q-item-section>
                                        </template>

                                        <q-card class="bg-section">
                                            <q-card-section class="q-pa-sm">
                                                <div
                                                    class="text-caption text-grey-7 q-mb-xs"
                                                >
                                                    {{ item.role.description }}
                                                </div>
                                                <v-revoke-scope
                                                    @revoked="getPlans"
                                                    :item="item"
                                                />
                                            </q-card-section>
                                        </q-card>
                                    </q-expansion-item>
                                </q-list>
                            </div>
                            <div v-else class="empty-state text-center q-pa-md">
                                <q-icon
                                    name="mdi-key-remove"
                                    size="32px"
                                    color="grey-4"
                                    class="q-mb-sm"
                                />
                                <div class="text-grey-6">
                                    {{ __("No scopes assigned") }}
                                </div>
                            </div>
                        </q-card-section>

                        <q-separator />

                        <!-- Pricing Section -->
                        <q-card-section>
                            <div
                                class="text-subtitle2 text-weight-medium section-title q-mb-sm"
                            >
                                <q-icon
                                    name="mdi-currency-usd"
                                    class="q-mr-sm"
                                />
                                {{ __("Pricing") }}
                            </div>

                            <div v-if="plan.prices.length">
                                <div
                                    v-for="(item, index) in plan.prices"
                                    :key="index"
                                    class="price-item row items-center q-pa-sm rounded-borders q-mb-xs"
                                    :class="
                                        index % 2 === 0 ? 'bg-even' : 'bg-odd'
                                    "
                                >
                                    <div class="col-auto">
                                        <q-badge
                                            color="primary"
                                            class="period-badge"
                                        >
                                            {{
                                                formatBillingPeriod(
                                                    item.billing_period
                                                )
                                            }}
                                        </q-badge>
                                    </div>

                                    <div class="col">
                                        <div class="row items-center">
                                            <div
                                                class="price-amount text-weight-bold"
                                            >
                                                {{ item.amount_format }}
                                                <span
                                                    class="text-caption text-grey-7 q-ml-xs"
                                                    >{{ item.currency }}</span
                                                >
                                            </div>
                                        </div>
                                        <div
                                            v-if="item.expiration"
                                            class="text-caption text-grey-7"
                                        >
                                            {{ __("Expires:") }}
                                            {{ item.expiration }}
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <v-delete-price
                                            :item="item"
                                            @deleted="getPlans"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div v-else class="empty-state text-center q-pa-md">
                                <q-icon
                                    name="mdi-cash-remove"
                                    size="32px"
                                    color="grey-4"
                                    class="q-mb-sm"
                                />
                                <div class="text-grey-6">
                                    {{ __("No prices configured") }}
                                </div>
                            </div>
                        </q-card-section>

                        <q-separator />

                        <!-- Timestamps -->
                        <q-card-section class="timestamp-section">
                            <div
                                class="row items-center text-caption text-grey-6 q-mb-xs"
                            >
                                <q-icon
                                    name="mdi-calendar-plus"
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{ __("Created:") }} {{ plan.created }}
                            </div>
                            <div
                                class="row items-center text-caption text-grey-6"
                            >
                                <q-icon
                                    name="mdi-calendar-edit"
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{ __("Updated:") }} {{ plan.updated }}
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="row justify-center q-mt-xl q-mb-lg">
            <q-pagination
                v-model="search.page"
                color="primary"
                :max="pages.total_pages"
                :max-pages="6"
                size="md"
                boundary-numbers
                direction-links
                ellipses
                class="pagination-control"
            />
        </div>
    </v-admin-transaction-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";
import VUpdate from "./Updated.vue";
import VRevokeScope from "./RevokeScope.vue";
import VDeletePrice from "./DeletePrice.vue";

export default {
    components: {
        VCreate,
        VDelete,
        VUpdate,
        VRevokeScope,
        VDeletePrice,
    },
    data() {
        return {
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            plans: [],
        };
    },

    watch: {
        "search.page"(value) {
            this.getPlans();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getPlans();
            }
        },
    },

    created() {
        this.getPlans();
    },

    methods: {
        async getPlans(param) {
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(
                    this.$page.props.route.plans,
                    {
                        params: params,
                    }
                );

                if (res.status == 200) {
                    this.plans = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (error) {
                console.error("Error fetching plans:", error);
            }
        },

        formatBillingPeriod(period) {
            const periodMap = {
                monthly: "Monthly",
                yearly: "Yearly",
                weekly: "Weekly",
                daily: "Daily",
            };
            return periodMap[period] || period;
        },
    },
};
</script>

<style scoped>
/* CSS Variables for Theme Consistency */
:root {
    --color-primary: #1976d2;
    --color-secondary: #26a69a;
    --color-accent: #ff6b35;
    --color-positive: #21ba45;
    --color-negative: #c10015;
    --color-warning: #f2c037;
    --color-dark: #1d1d1d;
    --color-light: #f5f5f5;
    --color-section: #f8f9fa;
    --border-radius: 12px;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition-speed: 0.3s;
}

.header-section {
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    margin-bottom: 24px;
}

.plan-card {
    border-radius: var(--border-radius);
    transition: transform var(--transition-speed) ease,
        box-shadow var(--transition-speed) ease;
    overflow: hidden;
    height: 100%;
    border: 1px solid rgba(0, 0, 0, 0.08);
}

.plan-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--card-shadow) !important;
}

.plan-header {
    padding: 16px;
    color: white;
}

.plan-header.bg-active {
    background: linear-gradient(135deg, var(--color-primary), #1565c0);
}

.plan-header.bg-inactive {
    background: linear-gradient(135deg, #78909c, #546e7a);
}

.plan-name {
    font-size: 1.1rem;
}

.plan-description {
    line-height: 1.5;
    min-height: 40px;
}

.status-badge {
    border-radius: 16px;
    padding: 6px 10px;
    font-size: 0.75rem;
}

.section-title {
    color: var(--color-primary);
    padding-bottom: 8px;
}

.scope-list {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 8px;
}

.scope-item {
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.scope-item:last-child {
    border-bottom: none;
}

.bg-section {
    background-color: var(--color-section);
}

.bg-even {
    background-color: rgba(0, 0, 0, 0.02);
}

.bg-odd {
    background-color: rgba(0, 0, 0, 0.01);
}

.price-item {
    transition: background-color var(--transition-speed) ease;
}

.price-item:hover {
    background-color: rgba(var(--color-primary-rgb), 0.05);
}

.period-badge {
    border-radius: 6px;
    padding: 4px 8px;
    font-weight: 600;
}

.price-amount {
    font-size: 1.1rem;
}

.empty-state {
    border: 2px dashed rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.timestamp-section {
    background-color: var(--color-section);
    padding: 12px 16px;
}

.pagination-control {
    background: white;
    padding: 12px 20px;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.opacity-70 {
    opacity: 0.7;
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .header-section {
        border-radius: 0;
        margin-bottom: 16px;
    }

    .plan-card {
        margin-bottom: 16px;
    }
}

@media (max-width: 599px) {
    .header-section .text-h4 {
        font-size: 1.5rem;
    }

    .status-badge {
        margin-bottom: 4px;
    }
}
</style>
