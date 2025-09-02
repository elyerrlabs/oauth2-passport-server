<template>
    <v-ecommerce>
        <div class="orders-container">
            <div class="row items-center q-mb-lg">
                <q-icon
                    name="shopping_cart"
                    size="28px"
                    class="header-icon q-mr-sm"
                    color="primary"
                />
                <div class="text-h5 text-weight-bold header-title">
                    {{ __("Shopping Orders") }}
                </div>
                <q-badge
                    v-if="orders.length"
                    color="accent"
                    class="q-ml-md item-count-badge"
                    rounded
                >
                    {{ orders.length }}
                    {{ orders.length === 1 ? __("item") : __("items") }}
                </q-badge>
                <q-space />
                <q-btn
                    :label="__('Continue Shopping')"
                    color="secondary"
                    outline
                    no-caps
                    icon="arrow_back"
                    padding="8px 16px"
                    @click="goTo($page.props.routes.search)"
                />
            </div>

            <!-- Main Content -->
            <q-card class="orders-card" flat>
                <!-- Empty State -->
                <div
                    v-if="!orders.length"
                    class="empty-orders-state column items-center justify-center q-pa-xl"
                >
                    <div class="empty-icon-container">
                        <q-icon
                            name="shopping_bag"
                            size="72px"
                            class="empty-icon"
                        />
                    </div>
                    <div class="text-h6 empty-title q-mt-lg">
                        {{ __("Your order is empty") }}
                    </div>
                    <div class="text-body2 empty-subtitle q-mb-xl text-center">
                        {{ __("Add products to your cart to get started") }}
                    </div>
                    <q-btn
                        :label="__('Browse Products')"
                        color="primary"
                        unelevated
                        no-caps
                        class="shopping-btn"
                        icon="store"
                        padding="12px 24px"
                        @click="$router.push('/products')"
                    />
                </div>

                <!-- Orders Items -->
                <div v-else>
                    <q-card-section class="orders-items-section">
                        <div
                            class="row items-center selection-controls q-mb-md"
                        >
                            <q-checkbox
                                v-model="selectAll"
                                indeterminate-value="some"
                                :label="__('Select all items')"
                                color="primary"
                                class="select-all-checkbox"
                                dense
                                @update:model-value="toggleSelectAll"
                            />
                            <q-space />
                            <div
                                class="text-caption selected-count text-weight-medium"
                            >
                                {{ selected_products.length }} {{ __("of") }}
                                {{ orders.length }} {{ __("selected") }}
                            </div>
                        </div>

                        <q-separator class="q-mb-md" />

                        <div class="orders-items-list">
                            <q-item
                                v-for="(product, index) in orders"
                                :key="product.id"
                                class="orders-item q-pa-md"
                                :class="{
                                    'item-selected':
                                        selected_products.includes(product),
                                }"
                            >
                                <q-item-section side class="q-pr-none">
                                    <q-checkbox
                                        v-model="selected_products"
                                        :val="product"
                                        color="primary"
                                        size="md"
                                        class="item-checkbox"
                                        dense
                                    />
                                </q-item-section>

                                <q-item-section avatar class="q-pa-sm">
                                    <div class="product-image-container">
                                        <q-img
                                            :src="product.images[0]?.url"
                                            :alt="product?.meta?.name"
                                            class="product-image"
                                            :ratio="1"
                                            width="80px"
                                            height="80px"
                                        />
                                    </div>
                                </q-item-section>

                                <q-item-section class="product-info q-pl-md">
                                    <q-item-label
                                        class="product-name text-weight-medium"
                                    >
                                        {{ product?.meta?.name }}
                                    </q-item-label>
                                    <div class="product-attributes q-mt-xs">
                                        <div
                                            v-for="(value, attr) in product
                                                ?.meta?.attributes"
                                            :key="attr"
                                            class="attribute-chip"
                                        >
                                            {{ attr }}: {{ value }}
                                        </div>
                                    </div>
                                    <div class="product-tags q-mt-sm">
                                        <q-badge
                                            v-if="product.stock > 0"
                                            color="positive"
                                            class="q-mr-xs stock-badge"
                                        >
                                            {{ __("In Stock") }} ({{
                                                product.stock
                                            }})
                                        </q-badge>
                                        <q-badge
                                            v-else
                                            color="negative"
                                            class="q-mr-xs stock-badge"
                                        >
                                            {{ __("Out of Stock") }}
                                        </q-badge>
                                        <q-badge color="info" class="q-mr-xs">
                                            {{ __("Qty:") }}
                                            {{ product.quantity }}
                                        </q-badge>
                                        <q-badge
                                            :color="
                                                getStatusColor(product.status)
                                            "
                                            class="status-badge"
                                        >
                                            {{ product.status }}
                                        </q-badge>
                                        <q-btn
                                            outline
                                            icon="mdi-eye"
                                            color="primary"
                                            size="sm"
                                            class="q-ma-sm"
                                            @click="goTo(product?.links.show)"
                                        >
                                            {{ __("View product") }}
                                        </q-btn>
                                    </div>
                                </q-item-section>

                                <q-item-section side class="price-section">
                                    <div class="price-container">
                                        <div
                                            class="product-price text-h6 text-weight-bold"
                                        >
                                            {{ product.currency }}
                                            {{ product.format_price }}
                                        </div>
                                    </div>
                                </q-item-section>

                                <q-item-section side class="quantity-section">
                                    <div
                                        class="quantity-controls column items-center"
                                    >
                                        <div
                                            class="text-caption text-weight-medium q-mb-xs"
                                        >
                                            {{ __("Quantity") }}
                                        </div>
                                        <div class="row items-center no-wrap">
                                            <q-btn
                                                icon="remove"
                                                size="sm"
                                                round
                                                unelevated
                                                :color="
                                                    product.quantity <= 1
                                                        ? 'grey-4'
                                                        : 'control'
                                                "
                                                :text-color="
                                                    product.quantity <= 1
                                                        ? 'grey-6'
                                                        : 'dark'
                                                "
                                                :disable="product.quantity <= 1"
                                                @click.stop="
                                                    updateQuantity(
                                                        product.id,
                                                        product.quantity - 1
                                                    )
                                                "
                                                class="quantity-btn"
                                            />
                                            <div
                                                class="quantity-display text-weight-bold"
                                            >
                                                {{ product.quantity }}
                                            </div>
                                            <q-btn
                                                icon="add"
                                                size="sm"
                                                round
                                                unelevated
                                                color="control"
                                                text-color="dark"
                                                :disable="
                                                    product.quantity >=
                                                    product.stock
                                                "
                                                @click.stop="
                                                    updateQuantity(
                                                        product.id,
                                                        product.quantity + 1
                                                    )
                                                "
                                                class="quantity-btn"
                                            />
                                        </div>
                                    </div>
                                </q-item-section>

                                <q-item-section side class="actions-section">
                                    <div class="item-actions column items-end">
                                        <q-btn
                                            icon="favorite_border"
                                            size="sm"
                                            flat
                                            round
                                            color="grey-6"
                                            class="action-btn"
                                        >
                                            <q-tooltip>
                                                {{ __("Save for later") }}
                                            </q-tooltip>
                                        </q-btn>
                                        <q-btn
                                            icon="delete_outline"
                                            size="sm"
                                            flat
                                            round
                                            color="grey-6"
                                            class="action-btn q-mt-sm"
                                            @click.stop="
                                                deleteItem(product.deleteUrl)
                                            "
                                        >
                                            <q-tooltip>{{
                                                __("Remove item")
                                            }}</q-tooltip>
                                        </q-btn>
                                    </div>
                                </q-item-section>
                            </q-item>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <!-- Order Summary -->
                    <q-card-actions class="order-summary-section q-pa-lg">
                        <div class="row justify-between items-start full-width">
                            <div class="col-12 col-md-7 order-summary">
                                <div class="text-h6 text-weight-bold q-mb-md">
                                    {{ __("Order Summary") }}
                                </div>

                                <div class="summary-details q-pa-md">
                                    <div
                                        class="row justify-between items-center q-mb-sm"
                                    >
                                        <div class="text-body1">
                                            {{ __("Total items") }} ({{
                                                selected_products.length
                                            }}
                                            )
                                        </div>
                                        <div
                                            class="text-body1 text-weight-medium"
                                        >
                                            <div class="text-body1">
                                                {{ __("Quantities") }} (
                                                {{ totalItem }})
                                            </div>
                                        </div>
                                    </div>

                                    <!--
                                        <div
                                        class="row justify-between items-center q-mb-sm"
                                        v-if="estimatedTax > 0"
                                        >
                                        <div class="text-body1">
                                            {{__('Estimated Tax')}}
                                        </div>
                                        <div
                                            class="text-body1 text-weight-medium"
                                        >
                                            {{ orders[0]?.currency }}
                                            {{ formatPrice(estimatedTax) }}
                                        </div>
                                    </div>
                                    -->

                                    <q-separator class="q-my-md" />

                                    <div
                                        class="summary-total row justify-between items-center"
                                    >
                                        <div class="text-h6">
                                            {{ __("Total") }}
                                        </div>
                                        <div
                                            class="text-h4 text-weight-bold text-primary"
                                        >
                                            {{ orders[0]?.currency }}
                                            {{ formatPrice(total) }}
                                        </div>
                                    </div>

                                    <div
                                        class="text-caption text-secondary q-mt-sm"
                                    >
                                        *
                                        {{
                                            __(
                                                "Total will be finalized during checkout"
                                            )
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 checkout-actions">
                                <div class="checkout-card q-pa-md">
                                    <div
                                        class="text-h6 q-mb-md text-weight-medium"
                                    >
                                        {{ __("Complete Purchase") }}
                                    </div>

                                    <div class="pricing-breakdown q-mb-lg">
                                        <div
                                            class="row justify-between items-center"
                                        >
                                            <div class="text-body2">
                                                {{ __("Order Total") }}
                                            </div>
                                            <div
                                                class="text-body2 text-weight-medium"
                                            >
                                                {{ orders[0]?.currency }}
                                                {{ formatPrice(total) }}
                                            </div>
                                        </div>
                                        <q-separator class="q-my-sm" />
                                    </div>

                                    <q-btn
                                        :label="__('Proceed to Checkout')"
                                        color="primary"
                                        unelevated
                                        no-caps
                                        class="full-width checkout-btn q-mb-md"
                                        size="lg"
                                        :disable="!selected_products.length"
                                        @click="checkout"
                                    >
                                        <q-icon
                                            name="arrow_forward"
                                            class="q-ml-sm"
                                            size="20px"
                                        />
                                    </q-btn>

                                    <div
                                        class="security-note row items-center justify-center q-mb-lg"
                                    >
                                        <q-icon
                                            name="lock"
                                            size="16px"
                                            class="q-mr-xs"
                                        />
                                        <div
                                            class="text-caption text-weight-medium"
                                        >
                                            {{ __("Secure checkout") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </q-card-actions>
                </div>
            </q-card>
        </div>
    </v-ecommerce>
</template>

<script>
export default {
    data() {
        return {
            orders: [],
            selectAll: false,
            selected_products: [],
        };
    },

    created() {
        this.getOrders();
    },

    computed: {
        totalItem() {
            return this.selected_products.reduce((sum, item) => {
                return sum + item.quantity;
            }, 0);
        },

        total() {
            return this.selected_products.reduce((sum, item) => {
                return sum + item.price * item.quantity;
            }, 0);
        },
    },

    methods: {
        goTo(route) {
            window.location.href = route;
        },

        async getOrders() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.orders,
                    { params: { per_page: 50 } }
                );

                if (res.status == 200) {
                    this.orders = res.data.data;
                }
            } catch (error) {}
        },

        async deleteItem(url) {
            try {
                const res = await this.$server.delete(url);
                if (res.status == 200) {
                    this.getOrders();
                    this.$q.notify({
                        message: res.data.message,
                        color: "positive",
                        icon: "delete",
                        position: "top-right",
                    });
                }
            } catch (error) {}
        },

        formatPrice(value) {
            return (value / 100).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },

        updateQuantity(id, newQuantity) {
            const product = this.orders.find((p) => p.id === id);
            if (product) {
                product.quantity = Math.max(1, newQuantity);
            }
        },

        toggleSelectAll(val) {
            if (val === true) {
                this.selected_products = [...this.orders];
            } else if (val === false) {
                this.selected_products = [];
            }
        },

        calculateDiscountPercentage(product) {
            if (
                !product.originalPrice ||
                !product.price ||
                product.originalPrice <= product.price
            )
                return 0;
            return Math.round(
                ((product.originalPrice - product.price) /
                    product.originalPrice) *
                    100
            );
        },

        getStatusColor(status) {
            const statusColors = {
                pending: "warning",
                confirmed: "info",
                shipped: "primary",
                delivered: "positive",
                cancelled: "negative",
            };
            return statusColors[status.toLowerCase()] || "grey";
        },

        async checkout() {
            const form = this.selected_products.map((item) => {
                return {
                    order_id: item.id,
                    product_id: item?.meta?.id,
                    quantity: item.quantity,
                };
            });

            /*try {
                const res = await this.$server.post(
                    this.$page.props.routes.checkout,
                    {
                        product_id: this,
                    }
                );
            } catch (error) {}*/
        },
    },
};
</script>

<style scoped>
/* CSS Variables for theming */
.orders-container {
    --color-primary: #1976d2;
    --color-primary-light: #4791db;
    --color-primary-dark: #115293;
    --color-secondary: #6c757d;
    --color-accent: #ff6b35;
    --color-positive: #28a745;
    --color-negative: #dc3545;
    --color-warning: #ffc107;
    --color-info: #17a2b8;
    --color-control: #f8f9fa;
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --bg-tertiary: #e9ecef;
    --text-primary: #212529;
    --text-secondary: #6c757d;
    --text-muted: #adb5bd;
    --border-radius: 12px;
    --border-radius-sm: 8px;
    --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    --hover-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    --spacing-xs: 8px;
    --spacing-sm: 12px;
    --spacing-md: 16px;
    --spacing-lg: 24px;
    --spacing-xl: 32px;
    --transition-speed: 0.2s;
}

.orders-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: var(--spacing-md);
}

/* Header Section */
.header-icon {
    color: var(--color-primary);
}

.header-title {
    color: var(--text-primary);
    margin-bottom: 0;
}

.item-count-badge {
    font-size: 0.8rem;
    padding: 4px 8px;
}

/* Orders Card */
.orders-card {
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    transition: box-shadow var(--transition-speed) ease;
}

.orders-card:hover {
    box-shadow: var(--hover-shadow);
}

/* Empty State */
.empty-orders-state {
    min-height: 400px;
    text-align: center;
}

.empty-icon-container {
    background: var(--bg-secondary);
    border-radius: 50%;
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon {
    color: var(--text-muted);
    opacity: 0.7;
}

.empty-title {
    color: var(--text-primary);
    margin-bottom: var(--spacing-xs);
    font-weight: 600;
}

.empty-subtitle {
    color: var(--text-secondary);
}

.shopping-btn {
    border-radius: var(--border-radius-sm);
    font-weight: 500;
}

/* Orders Items Section */
.orders-items-section {
    padding: var(--spacing-lg);
}

.selection-controls {
    padding: 0 var(--spacing-xs);
}

.select-all-checkbox :deep(.q-checkbox__label) {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1rem;
}

.selected-count {
    color: var(--text-secondary);
    font-weight: 500;
}

.orders-items-list {
    border-radius: var(--border-radius-sm);
    overflow: hidden;
}

.orders-item {
    transition: all var(--transition-speed) ease;
    border-radius: var(--border-radius-sm);
    margin-bottom: var(--spacing-sm);
    border: 1px solid transparent;
    cursor: pointer;
}

.orders-item:hover {
    background-color: rgba(var(--color-primary-light), 0.03);
    border-color: rgba(var(--color-primary-light), 0.1);
    transform: translateY(-1px);
}

.item-selected {
    background-color: rgba(var(--color-primary-light), 0.06);
    border-left: 3px solid var(--color-primary);
}

.product-image-container {
    position: relative;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
}

.product-image {
    border-radius: var(--border-radius-sm);
}

.discount-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    border-radius: 10px;
    padding: 3px 6px;
    font-size: 0.65rem;
    font-weight: bold;
}

.product-info {
    min-width: 200px;
}

.product-name {
    color: var(--text-primary);
    font-size: 1rem;
    line-height: 1.4;
}

.product-sku {
    color: var(--text-muted);
    font-size: 0.8rem;
}

.product-attributes {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-xs);
}

.attribute-chip {
    background: var(--bg-secondary);
    padding: 3px 6px;
    border-radius: 10px;
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.product-tags {
    margin-top: var(--spacing-xs);
}

.stock-badge,
.status-badge {
    font-size: 0.7rem;
}

.price-section {
    min-width: 100px;
}

.price-container {
    text-align: right;
}

.product-price {
    color: var(--text-primary);
    font-size: 1.1rem;
}

.original-price {
    color: var(--text-muted);
    margin-bottom: 2px;
    font-size: 0.8rem;
}

.savings {
    font-weight: 500;
    font-size: 0.8rem;
}

.quantity-section {
    min-width: 100px;
}

.quantity-controls {
    background: var(--bg-secondary);
    border-radius: var(--border-radius-sm);
    padding: var(--spacing-xs);
}

.quantity-btn {
    width: 28px;
    height: 28px;
}

.quantity-btn:disabled {
    opacity: 0.5;
}

.quantity-display {
    min-width: 36px;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.actions-section {
    min-width: 50px;
}

.action-btn {
    transition: all var(--transition-speed) ease;
}

.action-btn:hover {
    background: var(--bg-tertiary);
    transform: scale(1.1);
}

/* Order Summary Section */
.order-summary-section {
    background-color: var(--bg-secondary);
}

.order-summary {
    padding-right: var(--spacing-lg);
}

.summary-details {
    background: var(--bg-primary);
    border-radius: var(--border-radius-sm);
    padding: var(--spacing-md);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.summary-line {
    color: var(--text-secondary);
}

.summary-total {
    color: var(--text-primary);
}

.shipping-notice {
    background: rgba(var(--color-info), 0.08);
    border: 1px solid rgba(var(--color-info), 0.15);
    color: var(--text-secondary);
    border-radius: var(--border-radius-sm);
}

.checkout-actions {
    padding-left: var(--spacing-lg);
}

.checkout-card {
    background: var(--bg-primary);
    border-radius: var(--border-radius-sm);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: var(--spacing-lg);
}

.checkout-btn {
    border-radius: var(--border-radius-sm);
    padding: 10px;
    font-weight: 600;
    height: 44px;
}

.security-note {
    color: var(--text-muted);
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .orders-container {
        padding: var(--spacing-sm);
    }

    .orders-items-section,
    .order-summary-section {
        padding: var(--spacing-md);
    }
}

@media (max-width: 767px) {
    .order-summary-section .row {
        flex-direction: column;
    }

    .order-summary,
    .checkout-actions {
        padding: 0;
        width: 100%;
    }

    .order-summary {
        margin-bottom: var(--spacing-lg);
    }

    .checkout-card {
        margin-top: var(--spacing-md);
        position: static;
    }

    .orders-item {
        flex-wrap: wrap;
    }

    .product-info {
        min-width: 100%;
        order: 3;
        margin-top: var(--spacing-sm);
    }

    .price-section,
    .quantity-section,
    .actions-section {
        min-width: auto;
    }

    .product-name {
        font-size: 1rem;
    }

    .product-price {
        font-size: 1.1rem;
    }
}

@media (max-width: 599px) {
    .orders-container {
        padding: var(--spacing-xs);
    }

    .orders-card {
        border-radius: var(--border-radius-sm);
    }

    .orders-items-section {
        padding: var(--spacing-md) var(--spacing-sm);
    }

    .header-title {
        font-size: 1.4rem;
    }

    .orders-item {
        padding: var(--spacing-sm);
    }

    .quantity-controls {
        flex-direction: row !important;
        align-items: center;
    }

    .quantity-controls .text-caption {
        margin-right: var(--spacing-xs);
        margin-bottom: 0 !important;
    }

    .product-image-container {
        width: 60px;
        height: 60px;
    }

    .checkout-btn {
        font-size: 0.9rem;
    }
}
</style>
