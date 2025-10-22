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
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <VHeader />

        <v-loader :loading="loading" />

        <!-- Cart Header -->
        <div
            v-if="!loading"
            class="cart-header bg-gradient-to-r from-purple-600 to-indigo-700 text-white py-6 md:py-8"
        >
            <div class="container mx-auto px-4 text-center">
                <div class="flex items-center justify-center mb-3">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold">
                        {{ __("Your Shopping Cart") }}
                    </h2>
                </div>
                <p
                    class="text-purple-100 text-sm md:text-base max-w-2xl mx-auto"
                >
                    {{ __("Review your items and proceed to checkout") }}
                </p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-6 md:py-8">
            <!-- Empty Cart State -->
            <div
                v-if="orders.length === 0 && !loading"
                class="text-center py-12 md:py-16"
            >
                <div class="max-w-md mx-auto">
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
                    >
                        <div
                            class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6"
                        >
                            <i
                                class="fas fa-shopping-cart text-3xl text-purple-600"
                            ></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            {{ __("Your cart is empty") }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-6">
                            {{
                                __(
                                    "Looks like you haven't added any items to your cart yet"
                                )
                            }}
                        </p>
                        <a
                            :href="$page.props.routes.search"
                            class="inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        >
                            <i class="fas fa-store mr-3"></i>
                            {{ __("Start Shopping") }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cart with Items -->
            <div
                v-else-if="!loading"
                class="flex flex-col lg:flex-row gap-6 lg:gap-8"
            >
                <!-- Left Column - Cart Items -->
                <div class="lg:w-2/3 space-y-4">
                    <!-- Cart Header -->
                    <div
                        class="bg-white rounded-xl p-4 shadow-sm border border-gray-200"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span
                                    class="text-lg font-semibold text-gray-900"
                                >
                                    {{ __("Cart Items") }}
                                </span>
                                <span
                                    class="ml-3 bg-purple-100 text-purple-600 text-sm font-medium px-2 py-1 rounded-full"
                                >
                                    {{ orders.length }} {{ __("items") }}
                                </span>
                            </div>
                            <div
                                class="flex items-center text-sm text-gray-600"
                            >
                                <i
                                    class="fas fa-info-circle mr-2 text-purple-500"
                                ></i>
                                {{ __("Select items to checkout") }}
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items List -->
                    <div class="space-y-3">
                        <div
                            v-for="item in orders"
                            :key="item.id"
                            class="cart-item bg-white rounded-xl p-4 flex items-start gap-4 shadow-sm border border-gray-200 hover:border-purple-200 transition-all duration-300 hover:shadow-md"
                        >
                            <!-- Checkbox -->
                            <div class="flex items-start pt-1">
                                <input
                                    type="checkbox"
                                    v-model="selected_products"
                                    :value="item"
                                    class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                />
                            </div>

                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <div
                                    class="w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden bg-gray-100 border border-gray-200"
                                >
                                    <img
                                        :src="item?.images[0]?.url"
                                        :alt="item.meta?.name"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-grow min-w-0">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2"
                                >
                                    <div class="flex-grow">
                                        <h4
                                            class="font-semibold text-gray-900 text-sm md:text-base leading-tight mb-1"
                                        >
                                            {{ item?.meta?.name }}
                                        </h4>
                                        <p
                                            class="text-gray-500 text-xs md:text-sm mb-2 line-clamp-2"
                                        >
                                            <span
                                                v-html="
                                                    item?.meta
                                                        ?.short_description
                                                "
                                            ></span>
                                        </p>

                                        <!-- Variant Info -->
                                        <div
                                            v-if="item?.meta?.variant"
                                            class="flex items-center text-xs text-gray-600 mb-2"
                                        >
                                            <i
                                                class="fas fa-cube mr-1 text-purple-500"
                                            ></i>
                                            {{ item.meta.variant.name }}
                                        </div>

                                        <!-- Price - Mobile -->
                                        <div
                                            class="sm:hidden flex items-center justify-between mt-2"
                                        >
                                            <span
                                                class="text-lg font-bold text-purple-600"
                                            >
                                                {{ item.currency }}
                                                {{ item.format_price }}
                                            </span>
                                            <button
                                                @click="
                                                    removeItem(
                                                        item?.links?.delete
                                                    )
                                                "
                                                class="text-red-500 hover:text-red-700 text-xs flex items-center transition-colors"
                                            >
                                                <i
                                                    class="fas fa-trash-alt mr-1"
                                                ></i>
                                                {{ __("Remove") }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Desktop Controls -->
                                    <div
                                        class="hidden sm:flex flex-col items-end space-y-2"
                                    >
                                        <span
                                            class="text-lg font-bold text-purple-600"
                                        >
                                            {{ item.currency }}
                                            {{ item.format_price }}
                                        </span>

                                        <!-- Quantity Controls -->
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <div
                                                class="flex items-center border border-gray-300 rounded-lg bg-white"
                                            >
                                                <button
                                                    @click="
                                                        decreaseQuantity(item)
                                                    "
                                                    :disabled="
                                                        item.quantity <= 1
                                                    "
                                                    :class="[
                                                        'w-8 h-8 flex items-center justify-center transition-colors',
                                                        item.quantity <= 1
                                                            ? 'text-gray-400 cursor-not-allowed'
                                                            : 'text-gray-600 hover:bg-gray-100 hover:text-purple-600',
                                                    ]"
                                                >
                                                    <i
                                                        class="fas fa-minus text-xs"
                                                    ></i>
                                                </button>
                                                <span
                                                    class="w-8 text-center font-medium text-sm"
                                                >
                                                    {{ item.quantity }}
                                                </span>
                                                <button
                                                    @click="
                                                        increaseQuantity(item)
                                                    "
                                                    class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-purple-600 transition-colors"
                                                >
                                                    <i
                                                        class="fas fa-plus text-xs"
                                                    ></i>
                                                </button>
                                            </div>
                                            <button
                                                @click="
                                                    removeItem(
                                                        item?.links?.delete
                                                    )
                                                "
                                                class="text-red-500 hover:text-red-700 text-sm flex items-center transition-colors ml-2"
                                            >
                                                <i
                                                    class="fas fa-trash-alt mr-1"
                                                ></i>
                                            </button>
                                        </div>

                                        <!-- Item Total -->
                                        <div
                                            class="text-right text-sm text-gray-600"
                                        >
                                            {{ __("Total") }}:
                                            <span
                                                class="font-semibold text-purple-600"
                                            >
                                                {{ item.currency }}
                                                {{
                                                    (
                                                        (item.price *
                                                            item.quantity) /
                                                        100
                                                    ).toFixed(2)
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile Controls -->
                                <div
                                    class="sm:hidden flex items-center justify-between mt-3 pt-3 border-t border-gray-200"
                                >
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex items-center border border-gray-300 rounded-lg bg-white"
                                        >
                                            <button
                                                @click="decreaseQuantity(item)"
                                                :disabled="item.quantity <= 1"
                                                :class="[
                                                    'w-7 h-7 flex items-center justify-center text-xs',
                                                    item.quantity <= 1
                                                        ? 'text-gray-400 cursor-not-allowed'
                                                        : 'text-gray-600 hover:bg-gray-100',
                                                ]"
                                            >
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span
                                                class="w-6 text-center font-medium text-sm"
                                            >
                                                {{ item.quantity }}
                                            </span>
                                            <button
                                                @click="increaseQuantity(item)"
                                                class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 text-xs"
                                            >
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <span class="text-sm text-gray-600">
                                            {{ __("Qty") }}
                                        </span>
                                    </div>

                                    <!-- Item Total -->
                                    <span
                                        class="text-sm font-semibold text-purple-600"
                                    >
                                        {{ item.currency }}
                                        {{
                                            (
                                                (item.price * item.quantity) /
                                                100
                                            ).toFixed(2)
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <a
                        :href="$page.props.routes.search"
                        class="inline-flex items-center justify-center w-full bg-white text-purple-600 hover:text-purple-700 border border-purple-600 hover:border-purple-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 hover:shadow-md"
                    >
                        <i class="fas fa-arrow-left mr-3"></i>
                        {{ __("Continue Shopping") }}
                    </a>

                    <v-error :error="errors.orders" />
                </div>

                <!-- Right Column - Order Summary -->
                <div class="lg:w-1/3 space-y-6">
                    <!-- Delivery Address -->
                    <div v-if="orders.length > 0" class="w-full">
                        <div
                            class="bg-white rounded-xl shadow-lg p-5 border border-gray-200"
                        >
                            <h3
                                class="text-lg font-bold mb-4 flex items-center"
                            >
                                <i
                                    class="fas fa-map-marker-alt mr-2 text-purple-600"
                                ></i>
                                {{ __("Delivery Address") }}
                            </h3>
                            <v-delivery-address @selected="setAddress" />
                            <v-error :error="errors.delivery" />
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div
                        class="bg-white rounded-xl shadow-lg p-5 border border-gray-200 sticky top-6"
                    >
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <i class="fas fa-receipt mr-2 text-purple-600"></i>
                            {{ __("Order Summary") }}
                        </h3>

                        <!-- Order Details -->
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">
                                    {{ __("Items") }} ({{ cartCount }})
                                </span>
                                <span class="font-semibold text-sm">
                                    {{ orders[0]?.currency }} {{ subtotal }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">
                                    {{ __("Shipping") }}
                                </span>
                                <span
                                    class="text-green-600 text-sm font-semibold"
                                >
                                    {{ __("Free") }}
                                </span>
                            </div>

                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-900 font-bold">
                                        {{ __("Total") }}
                                    </span>
                                    <span
                                        class="text-xl font-bold text-purple-600"
                                    >
                                        {{ orders[0]?.currency }} {{ total }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                {{ __("Payment Method") }}
                            </label>
                            <v-payment-method v-model="form.payment_method" />
                            <v-error :error="errors.payment_method" />
                        </div>

                        <!-- Checkout Button -->
                        <button
                            @click="checkout"
                            :disabled="
                                disabled || selected_products.length === 0
                            "
                            :class="[
                                'w-full py-3 rounded-xl font-bold text-base flex items-center justify-center shadow-lg transition-all duration-200',
                                selected_products.length === 0 || disabled
                                    ? 'bg-gray-400 cursor-not-allowed text-white'
                                    : 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white transform hover:-translate-y-0.5 hover:shadow-xl',
                            ]"
                        >
                            <i class="fas fa-lock mr-3"></i>
                            {{ __("Proceed to Checkout") }}
                            <span
                                class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs"
                            >
                                {{ orders[0]?.currency }} {{ total }}
                            </span>
                        </button>

                        <!-- Security Notice -->
                        <div
                            class="text-center text-xs text-gray-500 flex items-center justify-center mt-3"
                        >
                            <i
                                class="fas fa-shield-alt mr-2 text-green-500"
                            ></i>
                            {{ __("Secure checkout • SSL encrypted") }}
                        </div>

                        <!-- Help Text -->
                        <div
                            v-if="selected_products.length === 0"
                            class="text-center text-xs text-amber-600 bg-amber-50 p-2 rounded-lg mt-3"
                        >
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{
                                __(
                                    "Please select at least one item to checkout"
                                )
                            }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VHeader from "../Components/VHeader.vue";
import VPaymentMethod from "../Components/VPaymentMethod.vue";
import VError from "../Components/VError.vue";
import VDeliveryAddress from "../Components/VDeliveryAddress.vue";
import VLoader from "../Components/VLoader.vue";

export default {
    components: {
        VHeader,
        VPaymentMethod,
        VError,
        VDeliveryAddress,
        VLoader,
    },
    data() {
        return {
            orders: [],
            selected_products: [],
            form: {
                payment_method: "",
                delivery: "",
                orders: [],
            },
            errors: {},
            disabled: false,
            loading: true,
        };
    },
    computed: {
        cartCount() {
            return this.selected_products.reduce(
                (total, item) => total + item.quantity,
                0
            );
        },
        subtotal() {
            return (
                this.selected_products.reduce(
                    (total, item) => total + item.price * item.quantity,
                    0
                ) / 100
            ).toFixed(2);
        },
        total() {
            return this.subtotal;
        },
    },

    created() {
        this.getOrders();
    },

    methods: {
        increaseQuantity(item) {
            item.quantity++;
        },

        decreaseQuantity(item) {
            if (item.quantity > 1) {
                item.quantity--;
            }
        },

        async removeItem(url) {
            if (
                !confirm(
                    this.__(
                        "Are you sure you want to remove this item from your cart?"
                    )
                )
            ) {
                return;
            }

            try {
                const res = await this.$server.delete(url);
                if (res.status == 200) {
                    this.getOrders();
                    $notify.success(res.data.message);
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async checkout() {
            if (this.selected_products.length === 0) {
                $notify.warning(
                    this.__("Please select at least one item to checkout")
                );
                return;
            }

            this.disabled = true;

            this.form.orders = this.selected_products.map((item) => {
                return {
                    id: item.id,
                    variant_id: item?.meta?.variant?.id,
                    quantity: item.quantity,
                };
            });

            try {
                const res = await this.$server.post(
                    this.$page.props.api.ecommerce.payments,
                    this.form
                );

                if (res.status == 201) {
                    $notify.success(this.__(res.data.data.message));
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.disabled = false;
            }
        },

        setAddress(id) {
            this.form.delivery = id;
        },

        async getOrders() {
            try {
                const res = await this.$server.get(
                    this.$page.props.api.ecommerce.orders,
                    { params: { per_page: 50 } }
                );

                if (res.status == 200) {
                    this.orders = res.data.data;
                    // Auto-select all items by default
                    this.selected_products = [...this.orders];
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.cart-header {
    background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
}

.cart-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #c4b5fd;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth animations */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Custom scrollbar for cart items */
.cart-items-container {
    scrollbar-width: thin;
    scrollbar-color: #c4b5fd #f1f5f9;
}

.cart-items-container::-webkit-scrollbar {
    width: 6px;
}

.cart-items-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.cart-items-container::-webkit-scrollbar-thumb {
    background: #c4b5fd;
    border-radius: 3px;
}

.cart-items-container::-webkit-scrollbar-thumb:hover {
    background: #a78bfa;
}
</style>
