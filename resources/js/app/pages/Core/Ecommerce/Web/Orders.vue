<template>
    <v-ecommerce>
        <div class="orders-container">
            <!-- Header Section -->
            <div class="row items-center q-mb-lg header-section">
                <div class="header-content">
                    <q-icon
                        name="shopping_cart"
                        size="32px"
                        class="header-icon q-mr-sm"
                        color="primary"
                    />
                    <div class="text-h4 text-weight-bold header-title">
                        {{ __("Shopping Cart") }}
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
                </div>
                <q-space />
                <q-btn
                    :label="__('Continue Shopping')"
                    color="secondary"
                    outline
                    no-caps
                    icon="arrow_back"
                    padding="10px 20px"
                    class="continue-shopping-btn"
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
                            size="80px"
                            class="empty-icon"
                        />
                    </div>
                    <div class="text-h5 empty-title q-mt-lg text-weight-bold">
                        {{ __("Your cart is empty") }}
                    </div>
                    <div class="text-body1 empty-subtitle q-mb-xl text-center">
                        {{ __("Add products to your cart to get started") }}
                    </div>
                    <q-btn
                        :label="__('Browse Products')"
                        color="primary"
                        unelevated
                        no-caps
                        class="shopping-btn"
                        icon="store"
                        padding="14px 28px"
                        @click="goTo($page.props.routes.search)"
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

                        <q-separator class="q-mb-lg separator" />

                        <div class="orders-items-list">
                            <q-item
                                v-for="(product, index) in orders"
                                :key="product.id"
                                class="orders-item q-pa-lg"
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
                                            width="100px"
                                            height="100px"
                                        />
                                        <q-badge
                                            v-if="product.discount > 0"
                                            color="accent"
                                            class="discount-badge"
                                        >
                                            -{{ product.discount }}%
                                        </q-badge>
                                    </div>
                                </q-item-section>

                                <q-item-section class="product-info q-pl-md">
                                    <q-item-label
                                        class="product-name text-weight-bold"
                                    >
                                        {{ product?.meta?.name }}
                                    </q-item-label>
                                    <div
                                        class="text-caption text-grey-7 product-sku q-mt-xs"
                                        v-if="product?.meta?.sku"
                                    >
                                        SKU: {{ product?.meta?.sku }}
                                    </div>
                                    <div class="product-attributes q-mt-sm">
                                        <div
                                            v-for="(value, attr) in product
                                                ?.meta?.attributes"
                                            :key="attr"
                                            class="attribute-chip"
                                        >
                                            {{ attr }}: {{ value }}
                                        </div>
                                    </div>
                                    <div class="product-tags q-mt-md">
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
                                            class="q-ml-sm view-product-btn"
                                            @click="goTo(product?.links.show)"
                                        >
                                            {{ __("View Details") }}
                                        </q-btn>
                                    </div>
                                </q-item-section>

                                <q-item-section side class="price-section">
                                    <div class="price-container">
                                        <div
                                            v-if="product.discount > 0"
                                            class="original-price text-strike text-caption text-grey-6"
                                        >
                                            {{ product.currency }}
                                            {{
                                                formatPrice(
                                                    product.original_price
                                                )
                                            }}
                                        </div>
                                        <div
                                            class="product-price text-h5 text-weight-bold text-primary"
                                        >
                                            {{ product.currency }}
                                            {{ product.format_price }}
                                        </div>
                                        <div
                                            v-if="product.discount > 0"
                                            class="savings text-positive text-caption text-weight-medium q-mt-xs"
                                        >
                                            {{ __("You save") }}
                                            {{ product.currency }}
                                            {{
                                                formatPrice(
                                                    (product.original_price -
                                                        product.price) *
                                                        product.quantity
                                                )
                                            }}
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

                            <v-error :error="errors.orders"></v-error>
                        </div>
                    </q-card-section>

                    <q-separator class="separator" />

                    <!-- Order Summary -->
                    <q-card-actions class="order-summary-section q-pa-xl">
                        <div class="row justify-between items-start full-width">
                            <div class="col-12 col-md-7 order-summary">
                                <div
                                    class="text-h5 text-weight-bold q-mb-lg summary-title"
                                >
                                    {{ __("Order Summary") }}
                                </div>
                                <v-delivery-address @selected="setAddress" />
                                <v-error :error="errors.delivery"></v-error>

                                <div class="summary-details q-pa-md">
                                    <div
                                        class="row justify-between items-center q-mb-sm summary-line"
                                    >
                                        <div class="text-body1">
                                            {{ __("Items") }} ({{
                                                selected_products.length
                                            }})
                                        </div>
                                        <div
                                            class="text-body1 text-weight-medium"
                                        >
                                            {{ orders[0]?.currency }}
                                            {{ formatPrice(subtotal) }}
                                        </div>
                                    </div>
                                    <!--
                                    <div
                                        class="row justify-between items-center q-mb-sm summary-line"
                                        v-if="shipping > 0"
                                    >
                                        <div class="text-body1">
                                            {{ __("Shipping") }}
                                        </div>
                                        <div
                                            class="text-body1 text-weight-medium"
                                        >
                                            {{ orders[0]?.currency }}
                                            {{ formatPrice(shipping) }}
                                        </div>
                                    </div>
                                    
                                    <div
                                        class="row justify-between items-center q-mb-sm summary-line"
                                        v-if="estimatedTax > 0"
                                    >
                                        <div class="text-body1">
                                            {{ __("Estimated Tax") }}
                                        </div>
                                        <div
                                            class="text-body1 text-weight-medium"
                                        >
                                            {{ orders[0]?.currency }}
                                            {{ formatPrice(estimatedTax) }}
                                        </div>
                                    </div>

                                    <div
                                        class="row justify-between items-center q-mb-md summary-line"
                                        v-if="totalDiscount > 0"
                                    >
                                        <div class="text-body1 text-positive">
                                            {{ __("Discount") }}
                                        </div>
                                        <div
                                            class="text-body1 text-weight-medium text-positive"
                                        >
                                            -{{ orders[0]?.currency }}
                                            {{ formatPrice(totalDiscount) }}
                                        </div>
                                    </div>
                                    -->

                                    <q-separator class="q-my-md separator" />

                                    <div
                                        class="summary-total row justify-between items-center"
                                    >
                                        <div class="text-h5 text-weight-bold">
                                            {{ __("Total") }}
                                        </div>
                                        <div
                                            class="text-h3 text-weight-bold text-primary"
                                        >
                                            {{ orders[0]?.currency }}
                                            {{ formatPrice(total) }}
                                        </div>
                                    </div>
                                    <!--
                                        <div
                                        class="shipping-notice q-mt-md q-pa-sm rounded-borders text-center"
                                        >
                                        <q-icon
                                        name="local_shipping"
                                        size="16px"
                                        class="q-mr-xs"
                                        />
                                        {{ __("Free shipping on orders over") }}
                                        {{ orders[0]?.currency }}100
                                    </div>
                                    -->
                                </div>
                            </div>

                            <div class="col-12 col-md-5 checkout-actions">
                                <div class="checkout-card q-pa-lg">
                                    <div
                                        class="text-h5 text-weight-bold q-mb-md"
                                    >
                                        {{ __("Complete Purchase") }}
                                    </div>

                                    <div class="pricing-breakdown q-mb-lg">
                                        <div
                                            class="row justify-between items-center q-mb-xs"
                                        >
                                            <div class="text-body2">
                                                {{ __("Subtotal") }}
                                            </div>
                                            <div
                                                class="text-body2 text-weight-medium"
                                            >
                                                {{ orders[0]?.currency }}
                                                {{ formatPrice(subtotal) }}
                                            </div>
                                        </div>
                                        <!--
                                        <div
                                            class="row justify-between items-center q-mb-xs"
                                            v-if="shipping > 0"
                                        >
                                            <div class="text-body2">
                                                {{ __("Shipping") }}
                                            </div>
                                            <div
                                                class="text-body2 text-weight-medium"
                                            >
                                                {{ orders[0]?.currency }}
                                                {{ formatPrice(shipping) }}
                                            </div>
                                        </div>
                                        <div
                                            class="row justify-between items-center q-mb-xs"
                                            v-if="estimatedTax > 0"
                                        >
                                            <div class="text-body2">
                                                {{ __("Tax") }}
                                            </div>
                                            <div
                                                class="text-body2 text-weight-medium"
                                            >
                                                {{ orders[0]?.currency }}
                                                {{ formatPrice(estimatedTax) }}
                                            </div>
                                        </div>
                                        <div
                                            class="row justify-between items-center q-mb-sm"
                                            v-if="totalDiscount > 0"
                                        >
                                            <div
                                                class="text-body2 text-positive"
                                            >
                                                {{ __("Discount") }}
                                            </div>
                                            <div
                                                class="text-body2 text-weight-medium text-positive"
                                            >
                                                -{{ orders[0]?.currency }}
                                                {{ formatPrice(totalDiscount) }}
                                            </div>
                                        </div>
                                        -->

                                        <q-separator
                                            class="q-my-sm separator"
                                        />
                                        <div
                                            class="row justify-between items-center"
                                        >
                                            <div
                                                class="text-h6 text-weight-bold"
                                            >
                                                {{ __("Total") }}
                                            </div>
                                            <div
                                                class="text-h6 text-weight-bold text-primary"
                                            >
                                                {{ orders[0]?.currency }}
                                                {{ formatPrice(total) }}
                                            </div>
                                        </div>
                                    </div>

                                    <v-payment-method
                                        class="q-mb-lg"
                                        v-model="form.payment_method"
                                    />
                                    <v-error :error="errors.payment_method" />

                                    <q-btn
                                        :label="__('Proceed to Checkout')"
                                        color="primary"
                                        unelevated
                                        no-caps
                                        class="full-width checkout-btn q-mb-md"
                                        size="lg"
                                        :disable="disabled"
                                        @click="checkout"
                                    >
                                        <q-icon
                                            name="arrow_forward"
                                            class="q-ml-sm"
                                            size="20px"
                                        />
                                    </q-btn>

                                    <div
                                        class="security-note row items-center justify-center"
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
            form: {
                payment_method: "",
                delivery: "",
                orders: [],
            },
            errors: {},
            shipping: 0,
            estimatedTax: 0,
            disabled: false,
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

        subtotal() {
            return this.selected_products.reduce((sum, item) => {
                return sum + item.price * item.quantity;
            }, 0);
        },

        totalDiscount() {
            return this.selected_products.reduce((sum, item) => {
                if (item.discount > 0) {
                    const originalTotal = item.original_price * item.quantity;
                    const discountedTotal = item.price * item.quantity;
                    return sum + (originalTotal - discountedTotal);
                }
                return sum;
            }, 0);
        },

        total() {
            // return this.subtotal + this.shipping + this.estimatedTax;
            return this.subtotal;
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
                    // Calculate shipping based on order value
                    this.shipping = this.subtotal > 100 ? 0 : 10;
                    this.estimatedTax = this.subtotal * 0.08; // 8% tax
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
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
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
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
                // Recalculate shipping and tax when quantity changes
                this.shipping = this.subtotal > 100 ? 0 : 10;
                this.estimatedTax = this.subtotal * 0.08;
            }
        },

        toggleSelectAll(val) {
            if (val === true) {
                this.selected_products = [...this.orders];
            } else if (val === false) {
                this.selected_products = [];
            }
            // Recalculate shipping and tax when selection changes
            this.shipping = this.subtotal > 100 ? 0 : 10;
            this.estimatedTax = this.subtotal * 0.08;
        },

        getStatusColor(status) {
            const statusColors = {
                pending: "warning",
                confirmed: "info",
                shipped: "primary",
                delivered: "positive",
                cancelled: "negative",
            };
            return statusColors[status] || "grey";
        },

        setAddress(id) {
            this.form.delivery = id;
        },

        async checkout() {
            this.disabled = true;

            this.form.orders = this.selected_products.map((item) => {
                return {
                    id: item.id,
                    product_id: item?.meta?.id,
                    quantity: item.quantity,
                };
            });

            try {
                const res = await this.$server.post(
                    this.$page.props.routes.payment,
                    this.form
                );

                if (res.status == 201) {
                    this.$q.notify({
                        message: this.__(res.data.data.message),
                        color: "positive",
                        position: "top-right",
                        icon: "mdi-check-circle",
                    });
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        message: this.__(
                            "Some errors were detected. Please review them and try again."
                        ),
                        color: "warning",
                        position: "top-right",
                        icon: "mdi-alert-circle",
                    });
                }

                if (e?.response?.status == 409) {
                    this.$q.notify({
                        message: this.__(e.response.data.message),
                        color: "warning",
                        position: "top-right",
                        icon: "mdi-alert-circle",
                    });
                }

                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                this.disabled = false;
            }
        },
    },
};
</script>

<style>
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
    --border-radius: 16px;
    --border-radius-sm: 8px;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    --spacing-xs: 8px;
    --spacing-sm: 12px;
    --spacing-md: 16px;
    --spacing-lg: 24px;
    --spacing-xl: 32px;
    --spacing-xxl: 48px;
    --transition-speed: 0.2s;
}

.orders-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--spacing-md);
}

/* Header Section */
.header-section {
    padding: var(--spacing-lg) 0;
}

.header-content {
    display: flex;
    align-items: center;
}

.header-icon {
    color: var(--color-primary);
}

.header-title {
    color: var(--text-primary);
    margin-bottom: 0;
}

.item-count-badge {
    font-size: 0.9rem;
    padding: 6px 12px;
}

.continue-shopping-btn {
    border-radius: var(--border-radius-sm);
    font-weight: 500;
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
    width: 140px;
    height: 140px;
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
    max-width: 300px;
}

.shopping-btn {
    border-radius: var(--border-radius-sm);
    font-weight: 500;
    font-size: 1rem;
}

/* Orders Items Section */
.orders-items-section {
    padding: var(--spacing-xl);
}

.selection-controls {
    padding: 0 var(--spacing-xs);
}

.select-all-checkbox :deep(.q-checkbox__label) {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.selected-count {
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 0.9rem;
}

.separator {
    background-color: rgba(0, 0, 0, 0.08);
}

.orders-items-list {
    border-radius: var(--border-radius-sm);
    overflow: hidden;
}

.orders-item {
    transition: all var(--transition-speed) ease;
    border-radius: var(--border-radius-sm);
    margin-bottom: var(--spacing-md);
    border: 1px solid transparent;
    cursor: pointer;
    background: var(--bg-primary);
}

.orders-item:hover {
    background-color: rgba(var(--color-primary-light), 0.03);
    border-color: rgba(var(--color-primary-light), 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.item-selected {
    background-color: rgba(var(--color-primary-light), 0.08);
    border-left: 4px solid var(--color-primary);
}

.product-image-container {
    position: relative;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    width: 100px;
    height: 100px;
}

.product-image {
    border-radius: var(--border-radius-sm);
    object-fit: cover;
}

.discount-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    border-radius: 12px;
    padding: 4px 8px;
    font-size: 0.7rem;
    font-weight: bold;
}

.product-info {
    min-width: 250px;
}

.product-name {
    color: var(--text-primary);
    font-size: 1.1rem;
    line-height: 1.4;
}

.product-sku {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.product-attributes {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-xs);
    margin-top: var(--spacing-sm);
}

.attribute-chip {
    background: var(--bg-secondary);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.product-tags {
    margin-top: var(--spacing-sm);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: var(--spacing-xs);
}

.stock-badge,
.status-badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.view-product-btn {
    border-radius: var(--border-radius-sm);
}

.price-section {
    min-width: 120px;
}

.price-container {
    text-align: right;
}

.product-price {
    color: var(--text-primary);
    font-size: 1.2rem;
}

.original-price {
    color: var(--text-muted);
    margin-bottom: 2px;
    font-size: 0.9rem;
}

.savings {
    font-weight: 500;
    font-size: 0.85rem;
}

.quantity-section {
    min-width: 120px;
}

.quantity-controls {
    background: var(--bg-secondary);
    border-radius: var(--border-radius-sm);
    padding: var(--spacing-sm);
}

.quantity-btn {
    width: 32px;
    height: 32px;
}

.quantity-btn:disabled {
    opacity: 0.5;
}

.quantity-display {
    min-width: 40px;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
}

.actions-section {
    min-width: 60px;
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

.summary-title {
    color: var(--text-primary);
}

.order-summary {
    padding-right: var(--spacing-xl);
}

.summary-details {
    background: var(--bg-primary);
    border-radius: var(--border-radius-sm);
    padding: var(--spacing-lg);
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

.summary-line {
    color: var(--text-secondary);
    padding: var(--spacing-xs) 0;
}

.summary-total {
    color: var(--text-primary);
    padding: var(--spacing-sm) 0;
}

.shipping-notice {
    background: rgba(var(--color-info), 0.1);
    border: 1px solid rgba(var(--color-info), 0.2);
    color: var(--color-info);
    border-radius: var(--border-radius-sm);
    padding: var(--spacing-xs);
    margin-top: var(--spacing-md);
}

.checkout-actions {
    padding-left: var(--spacing-xl);
}

.checkout-card {
    background: var(--bg-primary);
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    position: sticky;
    top: var(--spacing-xl);
}

.checkout-btn {
    border-radius: var(--border-radius-sm);
    padding: 12px;
    font-weight: 600;
    height: 48px;
    font-size: 1rem;
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
        padding: var(--spacing-lg);
    }

    .header-title {
        font-size: 1.8rem;
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
        margin-bottom: var(--spacing-xl);
    }

    .checkout-card {
        margin-top: var(--spacing-lg);
        position: static;
    }

    .orders-item {
        flex-wrap: wrap;
        padding: var(--spacing-md);
    }

    .product-info {
        min-width: 100%;
        order: 3;
        margin-top: var(--spacing-md);
    }

    .price-section,
    .quantity-section,
    .actions-section {
        min-width: auto;
    }

    .product-name {
        font-size: 1.1rem;
    }

    .product-price {
        font-size: 1.2rem;
    }

    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-md);
    }

    .header-content {
        margin-bottom: var(--spacing-sm);
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
        padding: var(--spacing-lg) var(--spacing-md);
    }

    .header-title {
        font-size: 1.5rem;
    }

    .orders-item {
        padding: var(--spacing-md);
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
        width: 80px;
        height: 80px;
    }

    .checkout-btn {
        font-size: 0.95rem;
    }

    .empty-icon-container {
        width: 120px;
        height: 120px;
    }

    .empty-icon {
        size: 60px;
    }
}
</style>
