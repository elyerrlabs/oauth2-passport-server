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
        <div class="q-pa-md">
            <!-- Compact Header Section -->
            <div class="compact-header q-mb-md">
                <div class="header-content">
                    <q-icon name="shopping_bag" size="sm" class="header-icon" />
                    <div class="header-text">
                        <h1 class="header-title">Order History</h1>
                        <p class="header-subtitle">
                            Review your past purchases
                        </p>
                    </div>
                </div>
                <q-btn
                    v-if="orders.length > 0"
                    flat
                    round
                    icon="refresh"
                    @click="getCheckouts"
                    class="refresh-btn"
                    :loading="loading"
                >
                    <q-tooltip>Refresh orders</q-tooltip>
                </q-btn>
            </div>

            <!-- Empty State -->
            <div
                v-if="orders.length === 0 && !loading"
                class="empty-state text-center q-pa-lg"
            >
                <q-icon name="inventory_2" size="lg" class="empty-icon" />
                <h3 class="empty-title">No orders yet</h3>
                <p class="empty-subtitle">
                    Your order history will appear here once you make a purchase
                </p>
                <q-btn
                    color="primary"
                    label="Start Shopping"
                    icon="shopping_cart"
                    unelevated
                    class="q-mt-sm"
                    :to="{ name: 'products' }"
                />
            </div>

            <!-- Orders List -->
            <div v-else-if="orders.length > 0" class="orders-container">
                <q-list class="orders-list" separator>
                    <q-expansion-item
                        v-for="order in orders"
                        :key="order.id"
                        class="order-card"
                        expand-separator
                        :header-class="`order-header status-${order.transaction.status}`"
                    >
                        <!-- Order Header -->
                        <template v-slot:header>
                            <q-item-section avatar class="order-avatar">
                                <q-avatar
                                    size="md"
                                    color="primary"
                                    text-color="white"
                                >
                                    {{ orderNumberIcon(order.code) }}
                                </q-avatar>
                            </q-item-section>

                            <q-item-section class="order-info">
                                <div class="order-code">
                                    Order #{{ order.code }}
                                </div>
                                <div class="order-date">
                                    {{ formatCompactDate(order.created_at) }}
                                </div>
                            </q-item-section>

                            <q-item-section side class="order-status">
                                <q-badge
                                    :class="`status-badge status-${order.transaction.status}`"
                                    :label="
                                        formatStatus(order.transaction.status)
                                    "
                                />
                                <div class="order-total text-bold">
                                    {{ order.transaction.total }}
                                    {{ order.transaction.currency }}
                                </div>
                            </q-item-section>
                        </template>

                        <!-- Order Content -->
                        <q-card class="order-content">
                            <q-card-section class="q-pa-md">
                                <!-- Transaction Details -->
                                <div class="section-container">
                                    <div class="section-header">
                                        <q-icon name="receipt" size="sm" />
                                        <span class="section-title"
                                            >Transaction</span
                                        >
                                    </div>
                                    <div class="details-grid">
                                        <div class="detail-item">
                                            <span class="detail-label"
                                                >Status:</span
                                            >
                                            <span
                                                :class="`detail-value status-${order.transaction.status}`"
                                            >
                                                {{
                                                    formatStatus(
                                                        order.transaction.status
                                                    )
                                                }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"
                                                >Payment:</span
                                            >
                                            <span class="detail-value">{{
                                                formatPaymentMethod(
                                                    order.transaction
                                                        .payment_method
                                                )
                                            }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label"
                                                >Total:</span
                                            >
                                            <span
                                                class="detail-value total-amount"
                                            >
                                                {{ order.transaction.total }}
                                                {{ order.transaction.currency }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Address -->
                                <div class="section-container">
                                    <div class="section-header">
                                        <q-icon name="location_on" size="sm" />
                                        <span class="section-title"
                                            >Delivery</span
                                        >
                                    </div>
                                    <div class="address-card">
                                        <div class="address-details">
                                            <p class="address-name">
                                                {{
                                                    order.delivery_address
                                                        .full_name
                                                }}
                                            </p>
                                            <p class="address-street">
                                                {{
                                                    order.delivery_address
                                                        .address
                                                }}
                                            </p>
                                            <p class="address-location">
                                                {{
                                                    order.delivery_address.city
                                                }},
                                                {{
                                                    order.delivery_address
                                                        .country
                                                }}
                                            </p>
                                            <p class="address-phone">
                                                <q-icon
                                                    name="phone"
                                                    size="xs"
                                                />
                                                {{
                                                    order.delivery_address.phone
                                                }}
                                            </p>
                                        </div>
                                        <q-btn
                                            v-if="
                                                order.delivery_address.whatsapp
                                            "
                                            flat
                                            round
                                            color="green"
                                            icon="mdi-whatsapp"
                                            class="whatsapp-btn"
                                            type="a"
                                            :href="
                                                order.delivery_address.whatsapp
                                            "
                                            target="_blank"
                                        >
                                            <q-tooltip
                                                >Contact via WhatsApp</q-tooltip
                                            >
                                        </q-btn>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <div class="section-container">
                                    <div class="section-header">
                                        <q-icon name="inventory_2" size="sm" />
                                        <span class="section-title"
                                            >Items ({{
                                                order.items.length
                                            }})</span
                                        >
                                    </div>
                                    <div class="items-list">
                                        <div
                                            v-for="item in order.items"
                                            :key="item.id"
                                            class="item-card"
                                        >
                                            <div class="item-image-container">
                                                <q-img
                                                    :src="item.image"
                                                    :alt="item.name"
                                                    class="item-image"
                                                    spinner-color="primary"
                                                    :ratio="1"
                                                />
                                            </div>
                                            <div class="item-details">
                                                <h4 class="item-name">
                                                    {{ item.name }}
                                                </h4>
                                                <div class="item-meta">
                                                    <span class="item-quantity"
                                                        >Qty:
                                                        {{
                                                            item.quantity
                                                        }}</span
                                                    >
                                                    <span class="item-price">
                                                        {{ item.unitPrice }}
                                                        {{ item.currency }} each
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="item-total">
                                                <span class="item-total-price">
                                                    {{ item.total }}
                                                    {{ item.currency }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons">
                                    <q-btn
                                        v-if="order.transaction.payment_url"
                                        flat
                                        color="primary"
                                        icon="receipt"
                                        label="Receipt"
                                        :href="order.transaction.payment_url"
                                        target="_blank"
                                        size="sm"
                                    />
                                    <q-btn
                                        v-if="canReorder(order)"
                                        flat
                                        color="secondary"
                                        icon="replay"
                                        label="Reorder"
                                        size="sm"
                                        @click="reorder(order)"
                                    />
                                    <q-btn
                                        flat
                                        color="grey"
                                        icon="content_copy"
                                        label="Copy Order ID"
                                        size="sm"
                                        @click="copyOrderId(order.code)"
                                    />
                                </div>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>
                </q-list>
            </div>

            <!-- Loading State -->
            <q-inner-loading :showing="loading">
                <q-spinner-gears size="40px" color="primary" />
                <p class="q-mt-sm">Loading orders...</p>
            </q-inner-loading>

            <!-- Pagination (if needed in future) -->
            <div v-if="orders.length > 5" class="pagination-container q-mt-md">
                <q-pagination
                    v-model="currentPage"
                    :max="Math.ceil(orders.length / 5)"
                    :max-pages="6"
                    direction-links
                    boundary-links
                    icon-first="skip_previous"
                    icon-last="skip_next"
                    icon-prev="fast_rewind"
                    icon-next="fast_forward"
                    size="sm"
                />
            </div>
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
            orders: [],
            loading: false,
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    created() {
        this.getCheckouts();
    },

    watch: {
        "search.page"(value) {
            this.getCheckouts();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getCheckouts();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        async getCheckouts() {
            this.loading = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.orders,
                    {
                        params: {
                            status: "pending",
                        },
                    }
                );
                if (res.status === 200) {
                    this.orders = Object.values(res.data.data).map(
                        (checkout) => {
                            const transaction = checkout.transaction;

                            const items = checkout.orders.map((o) => ({
                                id: o.id,
                                name: o.meta.name,
                                quantity: o.quantity,
                                unitPrice: (o.meta.price.amount / 100).toFixed(
                                    2
                                ),
                                total: (
                                    (o.quantity * o.meta.price.amount) /
                                    100
                                ).toFixed(2),
                                currency: o.currency || transaction.currency,
                                image: o.images?.[0]?.url
                                    ? o.images[0].url
                                    : "https://via.placeholder.com/150?text=" +
                                      encodeURIComponent(o.meta.name),
                            }));

                            return {
                                id: checkout.id,
                                code: checkout.code,
                                transaction_code: checkout.transaction_code,
                                transaction,
                                delivery_address: checkout.delivery_address,
                                items,
                                created_at: checkout.created_at,
                            };
                        }
                    );

                    this.pages = res.data.meta.pagination;
                    this.search.current_page = res.data.pagination.current_page;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
                console.error("Error loading orders:", e);
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        },

        formatCompactDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
            });
        },

        orderNumberIcon(code) {
            // Get the last character of order code for avatar
            return code.slice(-1).toUpperCase();
        },

        formatStatus(status) {
            const statusMap = {
                successful: "Successful",
                failed: "Failed",
                pending: "Pending",
                refunded: "Refunded",
                cancelled: "Cancelled",
            };
            return statusMap[status] || status;
        },

        formatPaymentMethod(method) {
            const methodMap = {
                offline: "Offline",
                card: "Credit Card",
                paypal: "PayPal",
                bank_transfer: "Bank Transfer",
            };
            return methodMap[method] || method;
        },

        canReorder(order) {
            return (
                order.transaction.status === "successful" ||
                order.transaction.status === "completed"
            );
        },

        reorder(order) {
            this.$q.notify({
                message: `Adding items from order ${order.code} to cart`,
                color: "positive",
                icon: "add_shopping_cart",
                timeout: 2000,
            });
            // Implementation for reorder functionality
        },

        copyOrderId(orderCode) {
            navigator.clipboard.writeText(orderCode);
            this.$q.notify({
                message: "Order ID copied to clipboard",
                color: "info",
                icon: "content_copy",
                timeout: 1500,
            });
        },
    },
};
</script>

<style lang="scss" scoped>
@use "sass:color";

// Color Variables - Using direct colors instead of Sass functions
$primary-color: #1976d2;
$secondary-color: #26a69a;
$success-color: #21ba45;
$warning-color: #f2c037;
$negative-color: #c10015;
$info-color: #31ccec;
$dark-color: #1d1d1d;
$light-color: #f8f9fa;
$gray-color: #6c757d;
$border-color: #dee2e6;

// Compact Header
.compact-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid $border-color;
    margin-bottom: 1rem;

    .header-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-icon {
        color: $primary-color;
    }

    .header-text {
        line-height: 1.2;
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        color: $dark-color;
    }

    .header-subtitle {
        font-size: 0.875rem;
        color: $gray-color;
        margin: 0;
    }

    .refresh-btn {
        color: $gray-color;
        &:hover {
            color: $primary-color;
        }
    }
}

// Empty State
.empty-state {
    padding: 2rem 1rem;

    .empty-icon {
        color: $gray-color;
        opacity: 0.4;
        margin-bottom: 0.75rem;
    }

    .empty-title {
        font-size: 1.125rem;
        color: $dark-color;
        margin: 0 0 0.5rem;
        font-weight: 500;
    }

    .empty-subtitle {
        font-size: 0.875rem;
        color: $gray-color;
        margin: 0 0 1rem;
    }
}

// Orders List
.orders-list {
    background: transparent;
    gap: 0.75rem;
    display: flex;
    flex-direction: column;
}

// Order Card
.order-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid $border-color;
    transition: all 0.2s ease;

    &:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .order-header {
        min-height: 60px;
        padding: 0.5rem;

        &.status-successful {
            border-left: 3px solid $success-color;
        }

        &.status-pending {
            border-left: 3px solid $warning-color;
        }

        &.status-failed {
            border-left: 3px solid $negative-color;
        }
    }

    .order-avatar {
        min-width: 40px;
    }

    .order-info {
        min-width: 0;
        padding: 0 0.5rem;

        .order-code {
            font-weight: 600;
            color: $dark-color;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .order-date {
            color: $gray-color;
            font-size: 0.75rem;
        }
    }

    .order-status {
        text-align: right;
        min-width: fit-content;

        .order-total {
            color: $primary-color;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
    }
}

// Status Badges
.status-badge {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;

    &.status-successful {
        background: rgba($success-color, 0.12);
        color: color.adjust($success-color, $lightness: 8%);
    }

    &.status-pending {
        background: rgba($warning-color, 0.12);
        color: color.adjust($warning-color, $lightness: 8%);
    }

    &.status-failed {
        background: rgba($negative-color, 0.12);
        color: color.adjust($negative-color, $lightness: 8%);
    }
}

// Order Content
.order-content {
    .section-container {
        margin-bottom: 1.25rem;

        &:last-child {
            margin-bottom: 0;
        }
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid $border-color;

        .q-icon {
            color: $primary-color;
        }

        .section-title {
            font-weight: 600;
            color: $dark-color;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    }
}

// Details Grid
.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
    font-size: 0.8rem;

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
    }

    .detail-label {
        color: $gray-color;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .detail-value {
        font-weight: 500;
        color: $dark-color;

        &.status-successful {
            color: $success-color;
        }

        &.status-pending {
            color: $warning-color;
        }

        &.status-failed {
            color: $negative-color;
        }

        &.total-amount {
            font-weight: 600;
            color: $primary-color;
        }
    }
}

// Address Card
.address-card {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.75rem;
    background: $light-color;
    border-radius: 6px;
    font-size: 0.8rem;

    .address-details {
        flex: 1;

        .address-name {
            font-weight: 600;
            margin: 0 0 0.25rem;
            color: $dark-color;
        }

        .address-street,
        .address-location,
        .address-phone {
            margin: 0.15rem 0;
            color: $dark-color;
            line-height: 1.3;
        }

        .address-phone {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
    }

    .whatsapp-btn {
        margin-left: 0.5rem;
        flex-shrink: 0;
    }
}

// Items List
.items-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;

    .item-card {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: $light-color;
        border-radius: 6px;
        gap: 0.75rem;

        .item-image-container {
            width: 50px;
            height: 50px;
            flex-shrink: 0;

            .item-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 4px;
            }
        }

        .item-details {
            flex: 1;
            min-width: 0;

            .item-name {
                font-weight: 600;
                color: $dark-color;
                margin: 0 0 0.25rem;
                font-size: 0.8rem;
                line-height: 1.2;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .item-meta {
                display: flex;
                gap: 0.75rem;
                font-size: 0.7rem;
                color: $gray-color;
            }
        }

        .item-total {
            flex-shrink: 0;
            text-align: right;

            .item-total-price {
                font-weight: 600;
                color: $primary-color;
                font-size: 0.8rem;
            }
        }
    }
}

// Action Buttons
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    padding-top: 0.75rem;
    border-top: 1px solid $border-color;
    flex-wrap: wrap;

    .q-btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        min-height: 28px;
    }
}

// Pagination
.pagination-container {
    display: flex;
    justify-content: center;
    padding: 1rem 0;
}

// Responsive Design
@media (max-width: 768px) {
    .compact-header {
        padding: 0.5rem;

        .header-title {
            font-size: 1.1rem;
        }

        .header-subtitle {
            display: none;
        }
    }

    .order-header {
        padding: 0.5rem !important;
    }

    .order-info {
        padding: 0 0.25rem !important;

        .order-code {
            font-size: 0.8rem !important;
        }

        .order-date {
            font-size: 0.7rem !important;
        }
    }

    .details-grid {
        grid-template-columns: 1fr !important;
        gap: 0.5rem !important;
    }

    .address-card {
        flex-direction: column;
        gap: 0.5rem;

        .whatsapp-btn {
            margin: 0 !important;
            align-self: flex-end;
        }
    }

    .item-card {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;

        .item-image-container {
            margin: 0 auto;
        }

        .item-meta {
            justify-content: center;
        }

        .item-total {
            text-align: center !important;
        }
    }

    .action-buttons {
        justify-content: center !important;

        .q-btn {
            flex: 1;
            min-width: 100px;
        }
    }
}

@media (max-width: 480px) {
    .order-status {
        .status-badge {
            font-size: 0.6rem !important;
            padding: 2px 6px !important;
        }

        .order-total {
            font-size: 0.8rem !important;
        }
    }

    .action-buttons {
        flex-direction: column;

        .q-btn {
            width: 100%;
        }
    }
}

// Animation
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
