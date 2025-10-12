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
    <div class="min-h-screen">
        <v-header />

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6">
            <!-- Breadcrumbs -->
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <a
                    :href="$page.props.routes.search"
                    class="text-indigo-500 hover:text-indigo-700"
                    >{{ __("Home") }}</a
                >
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a
                    :href="$page.props.routes.show"
                    class="text-indigo-500 hover:text-indigo-700"
                    >{{ product?.category?.name }}</a
                >
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800">{{ product?.name }}</span>
            </div>

            <!-- Product Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
                    <!-- Product Gallery -->
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Thumbnails Vertical -->
                        <div
                            class="flex lg:flex-col gap-2 order-2 lg:order-1 overflow-auto lg:overflow-visible"
                        >
                            <div
                                v-for="(image, index) in product?.images"
                                :key="index"
                                @click="selectedImageIndex = index"
                                :class="[
                                    'cursor-pointer p-1 rounded-lg border-2 transition-all duration-200 flex-shrink-0',
                                    selectedImageIndex === index
                                        ? 'border-indigo-500 scale-105'
                                        : 'border-gray-200 hover:border-indigo-300',
                                ]"
                            >
                                <img
                                    :src="image?.url"
                                    class="h-16 w-16 lg:h-20 lg:w-20 object-contain rounded bg-gray-100"
                                />
                            </div>
                        </div>

                        <!-- Main Image -->
                        <div class="relative flex-1 order-1 lg:order-2">
                            <div
                                class="bg-gray-50 rounded-xl p-4 overflow-hidden"
                            >
                                <div
                                    v-if="product.discount"
                                    class="absolute top-3 left-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10"
                                >
                                    {{ product.discount }}% OFF
                                </div>

                                <div
                                    class="relative h-80 overflow-hidden rounded-lg"
                                >
                                    <img
                                        v-if="product?.images?.length"
                                        :src="
                                            product?.images[selectedImageIndex]
                                                ?.url
                                        "
                                        :alt="product.name"
                                        class="w-full h-full object-contain transition-transform duration-500 cursor-zoom-in"
                                        :class="{ 'scale-150': isZoomed }"
                                        @click="toggleZoom"
                                    />
                                </div>

                                <!-- Navigation Arrows -->
                                <div
                                    v-if="product.images?.length > 1"
                                    class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-2"
                                >
                                    <button
                                        @click="prevImage"
                                        class="bg-white/80 hover:bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all"
                                    >
                                        <i
                                            class="fas fa-chevron-left text-sm"
                                        ></i>
                                    </button>
                                    <button
                                        @click="nextImage"
                                        class="bg-white/80 hover:bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all"
                                    >
                                        <i
                                            class="fas fa-chevron-right text-sm"
                                        ></i>
                                    </button>
                                </div>

                                <!-- Zoom Button -->
                                <button
                                    @click="toggleZoom"
                                    class="absolute top-3 right-3 bg-white/80 hover:bg-white p-2 rounded-full shadow-lg text-gray-600 hover:text-indigo-600 z-10 transition-all"
                                >
                                    <i
                                        :class="[
                                            isZoomed
                                                ? 'fas fa-search-minus'
                                                : 'fas fa-search-plus',
                                        ]"
                                    ></i>
                                </button>

                                <!-- Image Counter -->
                                <div
                                    class="absolute bottom-3 left-1/2 transform -translate-x-1/2 bg-black/50 text-white px-2 py-1 rounded-full text-xs"
                                >
                                    {{ selectedImageIndex + 1 }} /
                                    {{ product.images?.length }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ product.name }}
                            </h1>
                        </div>

                        <div class="flex items-center mb-3 text-sm">
                            <div class="flex text-yellow-400 mr-2">
                                <i
                                    v-for="star in 5"
                                    :key="star"
                                    :class="[
                                        star <= 3
                                            ? 'fas fa-star'
                                            : 'far fa-star',
                                    ]"
                                ></i>
                            </div>
                            <span class="text-gray-600"
                                >({{ 3 }} {{ __("reviews") }})</span
                            >
                            <span class="mx-2 text-gray-300">|</span>
                            <span
                                class="text-green-600 font-medium flex items-center"
                            >
                                <i class="fas fa-check-circle mr-1 text-xs"></i>
                                {{ __("In stock") }}
                            </span>
                        </div>

                        <!-- Price Section -->
                        <div
                            class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100"
                        >
                            <div class="flex items-end justify-between">
                                <div>
                                    <span
                                        class="text-2xl font-bold text-indigo-600"
                                    >
                                        {{ symbol }}
                                        {{ price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="ml-2 text-gray-500 line-through text-sm"
                                    >
                                        {{ product.originalPrice }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">
                                        {{ __("Available") }}:
                                    </div>
                                    <div class="font-semibold text-green-600">
                                        {{ maxQuantity }} {{ __("units") }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <p
                            class="text-gray-700 mb-4 text-sm leading-relaxed"
                            v-html="product.short_description"
                        ></p>

                        <!-- Variants Selection -->
                        <div v-if="product.variants?.length" class="mb-4">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-3"
                            >
                                {{ __("Available Variants") }}
                            </h3>
                            <div class="grid grid-cols-1 gap-2">
                                <div
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    @click="selectVariant(variant)"
                                    :class="[
                                        'p-3 rounded-lg border cursor-pointer transition-all transform hover:scale-[1.02]',
                                        form.variant_id === variant.id
                                            ? 'bg-indigo-50 border-indigo-300 ring-2 ring-indigo-200 shadow-sm'
                                            : 'bg-white border-gray-200 hover:border-indigo-300',
                                    ]"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                :class="[
                                                    'w-3 h-3 rounded-full border-2 transition-all',
                                                    selectedVariant?.id ===
                                                    variant.id
                                                        ? 'bg-indigo-500 border-indigo-500'
                                                        : 'border-gray-400',
                                                ]"
                                            ></div>
                                            <div>
                                                <span
                                                    class="font-medium text-gray-900 block"
                                                    >{{ variant.name }}</span
                                                >
                                                <p
                                                    v-if="variant.description"
                                                    class="text-xs text-gray-600 mt-1"
                                                >
                                                    {{ variant.description }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="font-bold text-indigo-600 text-sm"
                                                >{{ variant.symbol }}
                                                {{ variant.format_price }}</span
                                            >
                                            <div
                                                class="text-xs text-green-600 mt-1 flex items-center justify-end"
                                            >
                                                <i class="fas fa-box mr-1"></i
                                                >{{ variant.stock }}
                                                {{ __("available") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <v-error :error="errors.variant_id" />
                            </div>
                        </div>

                        <!-- Quantity and Actions -->
                        <div class="mb-4">
                            <div
                                class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg"
                            >
                                <span class="text-gray-900 font-medium text-sm"
                                    >{{ __("Quantity") }}:</span
                                >
                                <div class="flex items-center">
                                    <div
                                        class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white"
                                    >
                                        <button
                                            @click="decreaseQuantity"
                                            :disabled="form.quantity <= 1"
                                            :class="[
                                                'px-3 py-2 transition',
                                                form.quantity <= 1
                                                    ? 'text-gray-400 cursor-not-allowed'
                                                    : 'text-gray-600 hover:bg-gray-100',
                                            ]"
                                        >
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span
                                            class="px-4 py-2 border-l border-r border-gray-300 w-12 text-center text-sm font-medium"
                                            >{{ form.quantity }}</span
                                        >
                                        <button
                                            @click="increaseQuantity"
                                            :disabled="
                                                form.quantity >= maxQuantity
                                            "
                                            :class="[
                                                'px-3 py-2 transition',
                                                form.quantity >= maxQuantity
                                                    ? 'text-gray-400 cursor-not-allowed'
                                                    : 'text-gray-600 hover:bg-gray-100',
                                            ]"
                                        >
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <v-error :error="errors.quantity" />

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button
                                    @click="addToCart"
                                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center shadow-md transition-all transform hover:scale-[1.02]"
                                >
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    {{ __("Add to Cart") }}
                                </button>
                                <button
                                    @click="buyNow"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center shadow-md transition-all transform hover:scale-[1.02]"
                                >
                                    <i class="fas fa-bolt mr-2"></i>
                                    {{ __("Buy Now") }}
                                </button>
                            </div>
                        </div>

                        <!-- Product Features -->
                        <div
                            class="grid grid-cols-2 gap-3 text-xs text-gray-600 border-t pt-4 mt-4"
                        >
                            <!--
                            <div class="flex items-center">
                                <i
                                class="fas fa-shipping-fast mr-2 text-indigo-500"
                                ></i>
                                <span>{{ __("Free shipping") }}</span>
                            </div>
                            <div class="flex items-center">
                                <i
                                class="fas fa-shield-alt mr-2 text-green-500"
                                ></i>
                                <span>{{ __("2-year warranty") }}</span>
                            </div>
                            <div class="flex items-center">
                                <i
                                class="fas fa-undo-alt mr-2 text-blue-500"
                                ></i>
                                <span>{{ __("30-day returns") }}</span>
                            </div>
                            -->
                            <div class="flex items-center">
                                <i
                                    class="fas fa-headset mr-2 text-purple-500"
                                ></i>
                                <span>{{ __("24/7 support") }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details Tabs -->
                <div class="border-t border-gray-200 mt-6">
                    <div class="px-6">
                        <nav class="flex space-x-8 border-b">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="activeTab = tab.id"
                                :class="[
                                    'tab-button py-4 px-1 font-medium text-sm transition-all border-b-2',
                                    activeTab === tab.id
                                        ? 'text-indigo-600 border-indigo-600 font-semibold'
                                        : 'text-gray-500 hover:text-gray-700 border-transparent',
                                ]"
                            >
                                {{ __(tab.name) }}
                            </button>
                        </nav>
                    </div>

                    <div class="px-6 pb-6 pt-4">
                        <div
                            v-if="activeTab === 'description'"
                            class="prose prose-sm max-w-none text-gray-700 leading-relaxed"
                        >
                            <div v-html="product.description"></div>
                        </div>

                        <div
                            v-if="activeTab === 'specifications'"
                            class="text-sm text-gray-700"
                        >
                            <div v-html="product.specification"></div>
                        </div>

                        <div
                            v-if="activeTab === 'reviews'"
                            class="text-center py-8 text-gray-500"
                        >
                            <i
                                class="fas fa-comments text-4xl mb-3 text-gray-300"
                            ></i>
                            <p>
                                {{
                                    __(
                                        "No reviews yet. Be the first to review this product!"
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ __("Related Products") }}
                    </h2>
                    <a
                        :href="$page.props.routes.search"
                        class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm"
                    >
                        {{ __("View all") }}
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                >
                    <div
                        v-for="product in relatedProducts"
                        :key="product.id"
                        class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition-all transform hover:scale-[1.02]"
                        @click="goTo(product?.links?.show)"
                    >
                        <div
                            class="h-32 bg-gray-50 flex items-center justify-center p-3 relative"
                        >
                            <img
                                :src="product.images[0]?.url"
                                :alt="product.name"
                                class="h-24 object-contain transition-transform duration-300 hover:scale-110"
                            />
                        </div>
                        <div class="p-3">
                            <h3
                                class="font-medium text-gray-900 text-sm mb-1 truncate"
                            >
                                {{ product.name }}
                            </h3>
                            <div class="flex items-center mb-1">
                                <div class="flex text-yellow-400 text-xs mr-1">
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        :class="[
                                            star <= 3
                                                ? 'fas fa-star'
                                                : 'far fa-star',
                                        ]"
                                    ></i>
                                </div>
                                <span class="text-gray-500 text-xs"
                                    >({{ 3 }})</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-base font-bold text-indigo-600"
                                >
                                    {{ product.symbol }}
                                    {{ product.format_price }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Image Zoom Modal -->
        <div
            v-if="isZoomed"
            class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50 p-4"
            @click="isZoomed = false"
        >
            <div class="max-w-6xl w-full max-h-full relative">
                <img
                    :src="product?.images[selectedImageIndex]?.url"
                    :alt="product.name"
                    class="w-full h-full object-contain rounded-lg"
                />
                <button
                    @click="isZoomed = false"
                    class="absolute top-4 right-4 text-white text-2xl bg-black/50 hover:bg-black/70 rounded-full w-10 h-10 flex items-center justify-center transition-all"
                >
                    <i class="fas fa-times"></i>
                </button>

                <!-- Navigation in Modal -->
                <div
                    v-if="product.images?.length > 1"
                    class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-4"
                >
                    <button
                        @click.stop="prevImage"
                        class="bg-white/20 hover:bg-white/30 text-white rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm transition-all"
                    >
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button
                        @click.stop="nextImage"
                        class="bg-white/20 hover:bg-white/30 text-white rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm transition-all"
                    >
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Image Counter in Modal -->
                <div
                    class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 text-white px-3 py-1 rounded-full text-sm"
                >
                    {{ selectedImageIndex + 1 }} / {{ product.images?.length }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VHeader from "../Components/VHeader.vue";
import VError from "../Components/VError.vue";

export default {
    components: {
        VHeader,
        VError,
    },

    data() {
        return {
            form: {
                variant_id: "",
                quantity: 1,
            },
            errors: {},
            status: false,
            selectedImageIndex: 0,
            activeTab: "description",
            isZoomed: false,
            selectedVariant: null,

            // Product data
            product: {},

            // Tabs for information section
            tabs: [
                { id: "description", name: "Description" },
                { id: "specifications", name: "Specifications" },
                { id: "reviews", name: "Reviews" },
            ],

            // Related products
            relatedProducts: [],
        };
    },

    computed: {
        maxQuantity() {
            return this.selectedVariant?.stock || this.product.stock || 0;
        },

        price() {
            return (
                this.selectedVariant?.format_price || this.product.format_price
            );
        },

        symbol() {
            return this.selectedVariant?.symbol || this.product.symbol;
        },
    },

    created() {
        this.getProduct();
    },

    methods: {
        increaseQuantity() {
            if (this.form.quantity < this.maxQuantity) {
                this.form.quantity++;
            }
        },

        decreaseQuantity() {
            if (this.form.quantity > 1) {
                this.form.quantity--;
            }
        },

        clean() {
            this.form.variant_id = "";
            this.form.quantity = 1;
        },

        goTo(link) {
            window.location.href = link;
        },

        toggleZoom() {
            this.isZoomed = !this.isZoomed;
        },

        prevImage() {
            if (this.product.images?.length) {
                this.selectedImageIndex =
                    this.selectedImageIndex === 0
                        ? this.product.images.length - 1
                        : this.selectedImageIndex - 1;
            }
        },

        nextImage() {
            if (this.product.images?.length) {
                this.selectedImageIndex =
                    this.selectedImageIndex === this.product.images.length - 1
                        ? 0
                        : this.selectedImageIndex + 1;
            }
        },

        selectVariant(variant) {
            this.form.variant_id = variant.id;
            this.selectedVariant = variant;
        },

        async getProduct() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.show_api
                );

                if (res.status === 200) {
                    this.product = res.data.data;
                    if (this.product.variants?.length) {
                        this.selectVariant(this.product.variants[0]);
                    }
                    this.getRelatedProducts(this.product);
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                     $notify.error(e.response.data.message);
                }
            }
        },

        async getRelatedProducts(item) {
            try {
                const response = await this.$server.get(
                    this.$page.props.api.ecommerce.search,
                    {
                        params: {
                            random: true,
                            per_page: 8,
                            tags: item.tags.map((tag) => tag.name).join(","),
                        },
                    }
                );

                if (response.status === 200) {
                    this.relatedProducts = response.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                     $notify.error(e.response.data.message);
                }
            }
        },

        async addToCart() {
            this.form.product_id = this.selectedVariant?.id || this.product.id;
            try {
                const res = await this.$server.post(
                    this.$page.props.api.ecommerce.orders,
                    this.form
                );
                if (res.status == 201) {
                    this.errors = {};
                    this.clean();
                     $notify.success(
                        __(":name added to cart", {
                            ":name": this.product.name,
                        })
                    );
                    this.status = true;
                }
            } catch (e) {
                this.status = false;

                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.status == 401) {
                     $notify.error(
                        __("To continue the process, Please login first")
                    );
                }

                if (e?.response?.data?.message) {
                     $notify.error(e.response.data.message);
                }
            }
        },

        async buyNow() {
            await this.addToCart();

            if (this.status) {
                window.location.href = this.$page.props.routes.orders;
            }
        },
    },
};
</script>
