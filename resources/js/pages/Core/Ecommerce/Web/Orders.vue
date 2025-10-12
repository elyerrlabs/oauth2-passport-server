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
            class="cart-header bg-gradient-to-r from-purple-600 to-indigo-700 text-white py-4 md:py-6"
        >
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-2xl md:text-4xl font-bold mb-2 md:mb-3">
                    {{ __("Your Shopping Cart") }}
                </h2>
                <p class="text-sm md:text-xl max-w-2xl mx-auto">
                    {{ __("Review your items and proceed to checkout") }}
                </p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-4 md:py-8">
            <div v-if="orders.length === 0" class="text-center py-12 md:py-16">
                <p class="text-lg md:text-xl text-gray-600 mb-4 md:mb-6">
                    {{ __("You don't have any orders yet") }}
                </p>
                <a
                    :href="$page.props.routes.search"
                    class="inline-flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 md:py-3 md:px-6 rounded-lg md:rounded-xl transition duration-200 shadow-md"
                >
                    <i class="fas fa-shopping-cart mr-2"></i>
                    {{ __("Go Shopping") }}
                </a>
            </div>

            <!-- Cart Items -->
            <div v-else class="w-full flex flex-col gap-6">
                <!-- Left Column - Items -->
                <div class="w-full space-y-4">
                    <!-- Cart Item -->
                    <div
                        v-for="item in orders"
                        :key="item.id"
                        class="cart-item bg-white rounded-lg md:rounded-xl p-4 flex flex-col sm:flex-row items-start gap-4 shadow-sm"
                    >
                        <!-- Checkbox -->
                        <input
                            type="checkbox"
                            v-model="selected_products"
                            :value="item"
                            class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 mt-1"
                        />

                        <div
                            class="w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden flex-shrink-0"
                        >
                            <img
                                :src="item?.images[0]?.url"
                                :alt="item.meta?.name"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <!-- Info -->
                        <div class="flex-grow w-full sm:w-auto">
                            <h4 class="font-semibold text-base md:text-lg">
                                {{ item?.meta?.name }}
                            </h4>
                            <p
                                class="text-gray-500 text-xs md:text-sm mb-2 md:mb-0"
                            >
                                <span
                                    v-html="item?.meta?.short_description"
                                ></span>
                            </p>

                            <!-- Mobile price and controls -->
                            <div
                                class="flex justify-between items-center sm:hidden mt-2"
                            >
                                <p class="text-lg font-bold text-purple-600">
                                    {{ item.currency }} {{ item.format_price }}
                                </p>
                                <div class="flex items-center">
                                    <button
                                        @click="decreaseQuantity(item)"
                                        class="quantity-btn w-7 h-7 md:w-8 md:h-8 rounded-full border border-gray-300 flex items-center justify-center"
                                    >
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="mx-2 font-medium">
                                        {{ item.quantity }}
                                    </span>
                                    <button
                                        @click="increaseQuantity(item)"
                                        class="quantity-btn w-7 h-7 md:w-8 md:h-8 rounded-full border border-gray-300 flex items-center justify-center"
                                    >
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Controls - Desktop -->
                        <div
                            class="hidden sm:flex flex-col sm:items-end w-full sm:w-auto"
                        >
                            <p class="text-lg font-bold text-purple-600 mb-2">
                                {{ item.currency }} {{ item.format_price }}
                            </p>
                            <div class="flex items-center">
                                <button
                                    @click="decreaseQuantity(item)"
                                    class="quantity-btn w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors"
                                >
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="mx-3 font-medium">
                                    {{ item.quantity }}
                                </span>
                                <button
                                    @click="increaseQuantity(item)"
                                    class="quantity-btn w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors"
                                >
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <button
                                @click="removeItem(item?.links.delete)"
                                class="text-red-500 hover:text-red-700 text-sm mt-2 flex items-center transition-colors"
                            >
                                <i class="fas fa-trash-alt mr-1"></i>
                                {{ __("Remove") }}
                            </button>
                        </div>

                        <!-- Remove button for mobile -->
                        <button
                            @click="removeItem(item?.links.delete)"
                            class="sm:hidden text-red-500 hover:text-red-700 text-xs flex items-center self-end mt-2 transition-colors"
                        >
                            <i class="fas fa-trash-alt mr-1"></i>
                            {{ __("Remove") }}
                        </button>
                    </div>

                    <v-error :error="errors.orders" />

                    <a
                        :href="$page.props.routes.search"
                        class="inline-flex items-center justify-center w-full sm:w-auto text-purple-600 hover:text-purple-700 border border-purple-600 hover:border-purple-700 font-medium py-2 px-4 md:py-3 md:px-6 rounded-lg md:rounded-xl transition duration-200"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __("Continue Shopping") }}
                    </a>
                </div>

                <!-- Right Column - Summary -->
                <div class="w-full space-y-6">
                    <div v-if="orders.length > 0" class="w-full">
                        <v-delivery-address @selected="setAddress" />
                        <v-error :error="errors.delivery"></v-error>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-lg p-4 md:p-6 sticky top-24"
                    >
                        <h3
                            class="text-lg md:text-xl font-bold mb-4 md:mb-6 flex items-center"
                        >
                            <i class="fas fa-receipt mr-2 text-purple-600"></i>
                            {{ __("Order Summary") }}
                        </h3>

                        <div class="space-y-3 md:space-y-4 mb-4 md:mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm md:text-base"
                                    >{{ __("Total") }} ({{ cartCount }}
                                    {{ __("items") }})</span
                                >
                                <span class="font-medium text-sm md:text-base"
                                    >{{ orders[0]?.currency }}
                                    {{ subtotal }}</span
                                >
                            </div>
                        </div>

                        <div class="w-full mb-4 md:mb-6">
                            <v-payment-method v-model="form.payment_method" />
                            <v-error :error="errors.payment_method" />
                        </div>

                        <div class="w-full flex justify-center items-center">
                            <!-- Checkout Button -->
                            <button
                                @click="checkout"
                                :disable="disabled"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 md:py-4 rounded-xl font-bold text-base md:text-lg flex items-center justify-center shadow-md transition duration-200"
                            >
                                <i class="fas fa-lock mr-2"></i>
                                {{ __("Proceed to Checkout") }}
                            </button>
                        </div>

                        <!-- Security Notice -->
                        <div
                            class="text-center text-xs md:text-sm text-gray-500 flex items-center justify-center mt-3 md:mt-4"
                        >
                            <i
                                class="fas fa-shield-alt mr-2 text-green-500"
                            ></i>
                            {{ __("Secure checkout. All data is encrypted.") }}
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

        async removeItem(url) {
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
                     $notify.success(__(res.data.data.message));
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
    background: linear-gradient(120deg, #7c3aed 0%, #8b5cf6 100%);
}
.cart-item {
    transition: all 0.3s ease;
}
.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
.quantity-btn {
    transition: all 0.2s ease;
}
.quantity-btn:hover {
    background-color: #ede9fe;
    color: #7c3aed;
}
.checkout-btn {
    transition: all 0.3s ease;
    background: linear-gradient(to right, #7c3aed, #8b5cf6);
}
.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(124, 58, 237, 0.4);
    background: linear-gradient(to right, #8b5cf6, #7c3aed);
}
.continue-shopping {
    transition: all 0.3s ease;
}
.continue-shopping:hover {
    background-color: #f1f5f9;
    transform: translateX(-4px);
}
.empty-cart {
    animation: fadeIn 0.5s ease-in;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
