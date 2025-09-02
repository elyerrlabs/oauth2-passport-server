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
    <v-guest-layout>
        <template #body>
            <q-page class="pricing-page">
                <!-- Hero Section -->
                <div class="hero-section text-center q-pb-xl">
                    <div
                        class="text-h2 text-weight-bold text-primary hero-title"
                    >
                        {{ __("Choose Your Plan") }}
                    </div>
                    <div class="text-h6 text-grey-7 hero-subtitle q-mt-md">
                        {{
                            __(
                                "Select the perfect plan that fits your needs and budget"
                            )
                        }}
                    </div>
                    <div
                        class="hero-description text-body1 text-grey-6 q-mt-lg"
                    >
                        {{
                            __(
                                "All plans include our core features with flexible pricing options"
                            )
                        }}
                    </div>
                </div>

                <!-- Plans Grid -->
                <div class="plans-container">
                    <div class="row q-col-gutter-xl justify-center">
                        <div
                            v-for="plan in plans"
                            :key="plan.id"
                            class="col-12 col-sm-6 col-md-4 col-lg-3"
                        >
                            <q-card
                                class="plan-card"
                                flat
                                :class="{ 'featured-card': plan.featured }"
                            >
                                <!-- Plan Header -->
                                <div
                                    class="plan-header"
                                    :class="{
                                        'featured-header': plan.featured,
                                    }"
                                >
                                    <div
                                        class="plan-badge"
                                        v-if="plan.bonus_enabled"
                                    >
                                        <q-badge
                                            color="warning"
                                            text-color="dark"
                                            class="bonus-badge"
                                        >
                                            +{{ plan.bonus_duration }}
                                            {{ __("days free") }}
                                        </q-badge>
                                    </div>
                                    <div
                                        class="text-h5 text-weight-bold text-white text-center q-pt-lg"
                                    >
                                        {{ plan.name }}
                                    </div>
                                    <div
                                        class="text-subtitle2 text-white text-center q-pb-sm plan-tagline"
                                    >
                                        {{ plan.tagline }}
                                    </div>
                                </div>

                                <!-- Plan Content -->
                                <q-card-section class="plan-content">
                                    <!-- Description -->
                                    <div
                                        class="plan-description text-body2 text-grey-8 q-mb-lg"
                                        v-html="plan.description"
                                    ></div>

                                    <q-separator class="q-mb-lg" />

                                    <!-- Pricing Options -->
                                    <div class="pricing-section">
                                        <div
                                            class="text-subtitle1 text-center text-primary q-mb-md text-weight-medium pricing-title"
                                        >
                                            {{ __("Pricing Options") }}
                                        </div>
                                        <div class="price-options">
                                            <q-item
                                                v-for="(
                                                    price, index
                                                ) in plan.prices"
                                                :key="index"
                                                class="price-item rounded-borders q-mb-sm"
                                                clickable
                                                v-ripple
                                                @click="
                                                    selectPrice(plan, price)
                                                "
                                                :class="{
                                                    'selected-price':
                                                        selected_period ===
                                                        price,
                                                }"
                                            >
                                                <q-item-section side>
                                                    <q-badge
                                                        color="primary"
                                                        class="period-badge"
                                                    >
                                                        {{
                                                            price.billing_period_name
                                                        }}
                                                    </q-badge>
                                                </q-item-section>

                                                <q-item-section>
                                                    <div
                                                        class="text-weight-bold text-body1 price-amount"
                                                    >
                                                        {{ price.currency }}
                                                        {{
                                                            price.amount_format
                                                        }}
                                                    </div>
                                                    <div
                                                        class="text-caption text-grey expiration-date"
                                                    >
                                                        <q-icon
                                                            name="event"
                                                            size="14px"
                                                            class="q-mr-xs"
                                                        />
                                                        {{
                                                            formatDate(
                                                                price.expiration
                                                            )
                                                        }}
                                                    </div>
                                                </q-item-section>

                                                <q-item-section side>
                                                    <q-radio
                                                        :val="price"
                                                        v-model="
                                                            selected_period
                                                        "
                                                        color="primary"
                                                        @click="
                                                            selectPrice(
                                                                plan,
                                                                price
                                                            )
                                                        "
                                                    />
                                                </q-item-section>
                                            </q-item>
                                        </div>
                                    </div>
                                </q-card-section>

                                <!-- Plan Footer -->
                                <q-card-actions class="plan-actions">
                                    <q-btn
                                        :label="__('Select Plan')"
                                        color="primary"
                                        unelevated
                                        class="select-btn full-width"
                                        @click="selectPlan(plan)"
                                        :disable="!selected_period"
                                    >
                                        <q-tooltip v-if="!selected_period">{{
                                            __(
                                                "Please select a pricing option first"
                                            )
                                        }}</q-tooltip>
                                    </q-btn>
                                </q-card-actions>
                            </q-card>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination-section row justify-center q-my-xl">
                    <q-pagination
                        v-model="search.page"
                        color="primary"
                        :max="pages.total_pages"
                        :max-pages="6"
                        boundary-numbers
                        direction-links
                        unelevated
                        input
                        class="custom-pagination"
                    />
                </div>

                <!-- Subscription Sidebar -->
                <q-drawer
                    v-model="showSidebar"
                    side="right"
                    :width="450"
                    bordered
                    overlay
                    class="subscription-drawer"
                    behavior="mobile"
                >
                    <div class="drawer-header">
                        <div class="text-h5 text-weight-bold">
                            {{ __("Complete Subscription") }}
                        </div>
                        <q-btn
                            icon="close"
                            color="grey"
                            round
                            flat
                            dense
                            class="close-btn"
                            @click="showSidebar = false"
                        />
                    </div>

                    <v-subscription
                        :plan="selected_plan"
                        :period="selected_period"
                        @close="showSidebar = false"
                    />
                </q-drawer>
            </q-page>
        </template>
    </v-guest-layout>
</template>

<script>
export default {
    data() {
        return {
            plans: [],
            selected_plan: null,
            selected_period: null,
            showSidebar: false,
            search: {
                page: 1,
                per_page: 100,
            },
            pages: {
                total_pages: 0,
            },
        };
    },

    mounted() {
        this.getPlans();
    },

    methods: {
        formatDate(date) {
            if (!date) return "No expiration";
            return new Date(date).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        selectPrice(plan, price) {
            this.selected_plan = plan;
            this.selected_period = price;
        },

        selectPlan(plan) {
            if (!this.selected_period) {
                this.$q.notify({
                    message: __("Please select a pricing option first"),
                    color: "warning",
                    icon: "warning",
                    position: "top",
                    timeout: 2000,
                });
                return;
            }
            this.selected_plan = plan;
            this.showSidebar = true;
        },

        async getPlans() {
            const query = new URLSearchParams(window.location.search);
            const query_data = {};
            for (const [key, value] of query.entries()) {
                query_data[key] = value;
            }
            Object.assign(this.search, query_data);

            try {
                const res = await this.$server.get(
                    this.$page.props.routes["plans"],
                    {
                        params: this.search,
                    }
                );

                if (res.status === 200) {
                    this.plans = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (error) {
                console.error("Failed to load plans:", error);
                this.$q.notify({
                    message: "Failed to load plans. Please try again.",
                    color: "negative",
                    icon: "error",
                    position: "top",
                });
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.pricing-page {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    padding: 40px 24px;
}

.hero-section {
    max-width: 800px;
    margin: 0 auto;

    .hero-title {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 16px;
    }

    .hero-subtitle {
        font-size: 1.25rem;
    }

    .hero-description {
        max-width: 600px;
        margin: 0 auto;
    }
}

.plans-container {
    max-width: 1400px;
    margin: 0 auto;
}

.plan-card {
    border-radius: 16px;
    background: white;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;

    &:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: var(--q-primary);
    }

    &.featured-card {
        border-color: var(--q-primary);
        transform: scale(1.02);

        &:hover {
            transform: scale(1.02) translateY(-8px);
        }
    }
}

.plan-header {
    background: linear-gradient(135deg, var(--q-primary) 0%, #3a7bd5 100%);
    color: white;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
    padding: 24px 20px;
    position: relative;

    &.featured-header {
        background: linear-gradient(135deg, var(--q-primary) 0%, #2c5282 100%);
    }

    .plan-badge {
        position: absolute;
        top: 12px;
        right: 12px;

        .bonus-badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 4px 8px;
        }
    }

    .plan-tagline {
        opacity: 0.9;
    }
}

.plan-content {
    padding: 24px;
    flex-grow: 1;

    .plan-description {
        line-height: 1.6;
        min-height: 80px;
    }

    .pricing-section {
        .pricing-title {
            font-size: 1.1rem;
        }

        .price-options {
            .price-item {
                border: 2px solid transparent;
                border-radius: 12px;
                padding: 16px;
                transition: all 0.3s ease;

                &:hover {
                    border-color: var(--q-primary);
                    background: rgba(0, 123, 255, 0.05);
                    transform: translateX(4px);
                }

                &.selected-price {
                    border-color: var(--q-primary);
                    background: rgba(0, 123, 255, 0.1);

                    .period-badge {
                        background: var(--q-primary) !important;
                    }
                }

                .period-badge {
                    font-weight: 600;
                    padding: 4px 8px;
                }

                .price-amount {
                    color: var(--q-primary);
                    font-size: 1.1rem;
                }

                .expiration-date {
                    display: flex;
                    align-items: center;
                }
            }
        }
    }
}

.plan-actions {
    padding: 20px 24px;
    border-top: 1px solid rgba(0, 0, 0, 0.06);

    .select-btn {
        height: 48px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        text-transform: none;
        letter-spacing: 0.5px;
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

.subscription-drawer {
    .drawer-header {
        background: white;
        padding: 24px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        position: sticky;
        top: 0;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;

        .close-btn {
            margin-right: -8px;
        }
    }
}

// Responsive Design
@media (max-width: 1023px) {
    .pricing-page {
        padding: 32px 16px;
    }

    .hero-title {
        font-size: 2.5rem !important;
    }

    .hero-subtitle {
        font-size: 1.1rem !important;
    }
}

@media (max-width: 767px) {
    .pricing-page {
        padding: 24px 12px;
    }

    .hero-title {
        font-size: 2rem !important;
    }

    .plan-card {
        margin-bottom: 24px;

        &:hover {
            transform: translateY(-4px);
        }

        &.featured-card {
            transform: none;

            &:hover {
                transform: translateY(-4px);
            }
        }
    }

    .plan-content {
        padding: 20px;
    }

    .price-item {
        padding: 12px !important;
    }
}

@media (max-width: 599px) {
    .hero-title {
        font-size: 1.75rem !important;
    }

    .plan-header {
        padding: 20px 16px;
    }

    .subscription-drawer {
        width: 100% !important;
    }
}

// Animation for card entrance
.plan-card {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
    transform: translateY(30px);
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

// Stagger animation for cards
.plan-card:nth-child(1) {
    animation-delay: 0.1s;
}
.plan-card:nth-child(2) {
    animation-delay: 0.2s;
}
.plan-card:nth-child(3) {
    animation-delay: 0.3s;
}
.plan-card:nth-child(4) {
    animation-delay: 0.4s;
}
</style>
