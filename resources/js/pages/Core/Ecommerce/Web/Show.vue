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
        <v-header />

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6">
            <!-- Breadcrumbs -->
            <div class="flex items-center text-sm text-gray-500 mb-6">
                <a
                    :href="$page.props.routes.search"
                    class="text-indigo-500 hover:text-indigo-700 transition-colors flex items-center text-xs"
                >
                    <i class="fas fa-home mr-2"></i>
                    {{ __("Home") }}
                </a>
                <i class="fas fa-chevron-right mx-3 text-xs"></i>
                <a
                    :href="product?.category?.links?.index"
                    class="text-indigo-500 hover:text-indigo-700 transition-colors text-xs"
                >
                    {{ product?.category?.name }}
                </a>
                <i class="fas fa-chevron-right mx-3 text-xs"></i>
                <span class="text-gray-800 font-medium text-xs">{{
                    product?.name
                }}</span>
            </div>

            <!-- Product Section -->
            <div
                class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100"
            >
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 p-6">
                    <!-- Product Gallery -->
                    <div class="space-y-4">
                        <!-- Main Image -->
                        <div
                            class="relative bg-gray-50 rounded-xl p-6 overflow-hidden group"
                        >
                            <!-- Badges Container -->
                            <div class="absolute top-4 left-4 z-10 space-y-2">
                                <!-- Discount Badge -->
                                <div
                                    v-if="product.discount"
                                    class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center w-fit"
                                >
                                    <i class="fas fa-tag mr-1 text-xs"></i>
                                    {{ product.discount }}% OFF
                                </div>

                                <!-- Featured Badge -->
                                <div
                                    v-if="product.featured"
                                    class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center w-fit"
                                >
                                    <i class="fas fa-crown mr-1 text-xs"></i>
                                    {{ __("Featured") }}
                                </div>

                                <!-- New Arrival Badge -->
                                <div
                                    v-if="product.is_new"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center w-fit"
                                >
                                    <i class="fas fa-star mr-1 text-xs"></i>
                                    {{ __("New Arrival") }}
                                </div>
                            </div>

                            <div
                                class="relative h-80 lg:h-96 overflow-hidden rounded-lg"
                            >
                                <img
                                    v-if="product?.images?.length"
                                    :src="
                                        product?.images[selectedImageIndex]?.url
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
                                class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            >
                                <button
                                    @click.stop="prevImage"
                                    class="bg-white/90 hover:bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all hover:scale-110 backdrop-blur-sm border border-gray-200"
                                >
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </button>
                                <button
                                    @click.stop="nextImage"
                                    class="bg-white/90 hover:bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all hover:scale-110 backdrop-blur-sm border border-gray-200"
                                >
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                            </div>

                            <!-- Zoom Button -->
                            <button
                                @click.stop="toggleZoom"
                                class="absolute top-4 right-4 bg-white/90 hover:bg-white p-2 rounded-full shadow-lg text-gray-600 hover:text-indigo-600 z-10 transition-all hover:scale-110 backdrop-blur-sm border border-gray-200"
                            >
                                <i
                                    :class="[
                                        isZoomed
                                            ? 'fas fa-search-minus text-sm'
                                            : 'fas fa-search-plus text-sm',
                                    ]"
                                ></i>
                            </button>

                            <!-- Image Counter -->
                            <div
                                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/80 text-white px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm border border-white/20"
                            >
                                {{ selectedImageIndex + 1 }} /
                                {{ product.images?.length }}
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <div
                            v-if="product.images?.length > 1"
                            class="flex gap-2 overflow-auto pb-1"
                        >
                            <div
                                v-for="(image, index) in product?.images"
                                :key="index"
                                @click="selectedImageIndex = index"
                                :class="[
                                    'cursor-pointer p-1 rounded-lg border transition-all duration-300 flex-shrink-0 bg-white hover:shadow-md',
                                    selectedImageIndex === index
                                        ? 'border-indigo-500 scale-105 shadow-sm ring-1 ring-indigo-200'
                                        : 'border-gray-200 hover:border-indigo-300',
                                ]"
                            >
                                <img
                                    :src="image?.url"
                                    class="h-16 w-16 object-cover rounded-md"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-6">
                        <!-- Header -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="space-y-2">
                                    <h1
                                        class="text-2xl font-bold text-gray-900 leading-tight"
                                    >
                                        {{ product.name }}
                                    </h1>
                                    <!-- Additional badges in product info -->
                                    <div class="flex flex-wrap gap-2">
                                        <div
                                            v-if="product.featured"
                                            class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center lg:hidden"
                                        >
                                            <i
                                                class="fas fa-crown mr-1 text-xs"
                                            ></i>
                                            {{ __("Featured") }}
                                        </div>
                                        <div
                                            v-if="product.bestseller"
                                            class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center"
                                        >
                                            <i
                                                class="fas fa-fire mr-1 text-xs"
                                            ></i>
                                            {{ __("Bestseller") }}
                                        </div>
                                        <div
                                            v-if="product.free_shipping"
                                            class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center"
                                        >
                                            <i
                                                class="fas fa-shipping-fast mr-1 text-xs"
                                            ></i>
                                            {{ __("Free Shipping") }}
                                        </div>
                                    </div>
                                </div>
                                <button
                                    class="text-gray-400 hover:text-red-500 transition-colors transform hover:scale-110"
                                >
                                    <i class="far fa-heart text-xl"></i>
                                </button>
                            </div>

                            <!-- Rating and Stock -->
                            <div class="flex items-center space-x-4 text-sm">
                                <div
                                    class="flex items-center bg-blue-50 px-3 py-1 rounded-full"
                                >
                                    <div class="flex text-yellow-400 mr-2">
                                        <i class="fas fa-star text-sm"></i>
                                        <i class="fas fa-star text-sm"></i>
                                        <i class="fas fa-star text-sm"></i>
                                        <i class="fas fa-star text-sm"></i>
                                        <i class="far fa-star text-sm"></i>
                                    </div>
                                    <span
                                        class="text-gray-700 font-medium text-sm"
                                        >4.2</span
                                    >
                                    <span class="text-gray-500 mx-1">•</span>
                                    <span class="text-gray-600 text-sm"
                                        >({{ 42 }} {{ __("reviews") }})</span
                                    >
                                </div>
                                <span
                                    class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium flex items-center text-sm"
                                >
                                    <i
                                        class="fas fa-check-circle mr-1 text-xs"
                                    ></i>
                                    {{ __("In Stock") }}
                                </span>
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div
                            class="p-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-xl border border-blue-200 shadow-sm"
                        >
                            <div class="flex items-end justify-between">
                                <div class="space-y-1">
                                    <div class="flex items-baseline">
                                        <span
                                            class="text-3xl font-bold text-indigo-600"
                                        >
                                            {{ symbol }}
                                            {{ price }}
                                        </span>
                                        <span
                                            v-if="product.originalPrice"
                                            class="ml-3 text-gray-500 line-through text-lg"
                                        >
                                            {{ symbol
                                            }}{{ product.originalPrice }}
                                        </span>
                                    </div>
                                    <div
                                        class="text-green-600 font-medium text-sm flex items-center"
                                    >
                                        <i class="fas fa-bolt mr-1 text-xs"></i>
                                        {{ __("Best Price Guaranteed") }}
                                    </div>
                                </div>
                                <div class="text-right space-y-1">
                                    <div
                                        class="text-gray-600 text-sm font-medium"
                                    >
                                        {{ __("Available") }}:
                                    </div>
                                    <div
                                        class="font-bold text-green-600 text-xl"
                                    >
                                        {{ maxQuantity }} {{ __("units") }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div
                            class="text-gray-700 leading-relaxed text-sm border-l-4 border-indigo-500 pl-4 py-2 bg-indigo-50 rounded-r-lg"
                            v-html="product.short_description"
                        ></div>

                        <!-- Variants Selection -->
                        <div v-if="product.variants?.length" class="space-y-4">
                            <h3
                                class="text-lg font-bold text-gray-900 flex items-center"
                            >
                                <i
                                    class="fas fa-cogs mr-2 text-indigo-500 text-sm"
                                ></i>
                                {{ __("Available Options") }}
                            </h3>
                            <div class="grid grid-cols-1 gap-3">
                                <div
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    @click="selectVariant(variant)"
                                    :class="[
                                        'p-4 rounded-xl border cursor-pointer transition-all transform hover:scale-[1.02] shadow-sm',
                                        form.variant_id === variant.id
                                            ? 'bg-gradient-to-r from-indigo-50 to-blue-50 border-indigo-300 ring-2 ring-indigo-100 shadow-md'
                                            : 'bg-white border-gray-200 hover:border-indigo-300 hover:shadow-lg',
                                    ]"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                :class="[
                                                    'w-4 h-4 rounded-full border transition-all flex items-center justify-center',
                                                    selectedVariant?.id ===
                                                    variant.id
                                                        ? 'bg-indigo-500 border-indigo-500'
                                                        : 'border-gray-400',
                                                ]"
                                            >
                                                <i
                                                    v-if="
                                                        selectedVariant?.id ===
                                                        variant.id
                                                    "
                                                    class="fas fa-check text-white text-xs"
                                                ></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="font-bold text-gray-900 text-base block"
                                                >
                                                    {{ variant.name }}
                                                </span>
                                                <p
                                                    v-if="variant.description"
                                                    class="text-gray-600 mt-1 text-sm"
                                                >
                                                    {{ variant.description }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right space-y-1">
                                            <span
                                                class="font-bold text-indigo-600 text-lg"
                                            >
                                                {{ variant.symbol }}
                                                {{ variant.format_price }}
                                            </span>
                                            <div
                                                class="text-green-600 flex items-center justify-end text-sm font-medium"
                                            >
                                                <i
                                                    class="fas fa-boxes mr-1 text-xs"
                                                ></i>
                                                {{ variant.stock }}
                                                {{ __("in stock") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <v-error :error="errors.variant_id" />
                        </div>

                        <!-- Quantity and Actions -->
                        <div class="space-y-6">
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-xl shadow-sm"
                            >
                                <span
                                    class="text-gray-900 font-bold text-base flex items-center"
                                >
                                    <i
                                        class="fas fa-calculator mr-2 text-indigo-500 text-sm"
                                    ></i>
                                    {{ __("Quantity") }}:
                                </span>
                                <div class="flex items-center">
                                    <div
                                        class="flex items-center border border-gray-300 rounded-xl overflow-hidden bg-white shadow-md"
                                    >
                                        <button
                                            @click="decreaseQuantity"
                                            :disabled="form.quantity <= 1"
                                            :class="[
                                                'px-4 py-2 transition-all text-sm',
                                                form.quantity <= 1
                                                    ? 'text-gray-400 cursor-not-allowed'
                                                    : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600',
                                            ]"
                                        >
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span
                                            class="px-4 py-2 border-l border-r border-gray-300 w-12 text-center text-base font-bold bg-white"
                                        >
                                            {{ form.quantity }}
                                        </span>
                                        <button
                                            @click="increaseQuantity"
                                            :disabled="
                                                form.quantity >= maxQuantity
                                            "
                                            :class="[
                                                'px-4 py-2 transition-all text-sm',
                                                form.quantity >= maxQuantity
                                                    ? 'text-gray-400 cursor-not-allowed'
                                                    : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600',
                                            ]"
                                        >
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <v-error :error="errors.quantity" />

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                <button
                                    @click="addToCart"
                                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-6 rounded-xl font-bold flex items-center justify-center shadow-lg transition-all transform hover:scale-[1.02] hover:shadow-xl text-base group"
                                >
                                    <i
                                        class="fas fa-shopping-cart mr-3 text-sm group-hover:scale-110 transition-transform"
                                    ></i>
                                    {{ __("Add to Cart") }}
                                </button>
                                <button
                                    @click="buyNow"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-3 px-6 rounded-xl font-bold flex items-center justify-center shadow-lg transition-all transform hover:scale-[1.02] hover:shadow-xl text-base group"
                                >
                                    <i
                                        class="fas fa-bolt mr-3 text-sm group-hover:scale-110 transition-transform"
                                    ></i>
                                    {{ __("Buy Now") }}
                                </button>
                            </div>
                        </div>

                        <!-- Product Features -->
                        <div
                            class="grid grid-cols-2 gap-3 text-sm text-gray-700 border-t pt-6 mt-6"
                        >
                            <div
                                class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-gray-200"
                            >
                                <i
                                    class="fas fa-shipping-fast mr-3 text-indigo-500 text-lg"
                                ></i>
                                <div>
                                    <span class="font-bold block text-sm">{{
                                        __("Free Shipping")
                                    }}</span>
                                    <span class="text-gray-500 text-xs">{{
                                        __("On orders over $50")
                                    }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-gray-200"
                            >
                                <i
                                    class="fas fa-shield-alt mr-3 text-green-500 text-lg"
                                ></i>
                                <div>
                                    <span class="font-bold block text-sm">{{
                                        __("2-Year Warranty")
                                    }}</span>
                                    <span class="text-gray-500 text-xs">{{
                                        __("Full coverage")
                                    }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-gray-200"
                            >
                                <i
                                    class="fas fa-undo-alt mr-3 text-blue-500 text-lg"
                                ></i>
                                <div>
                                    <span class="font-bold block text-sm">{{
                                        __("30-Day Returns")
                                    }}</span>
                                    <span class="text-gray-500 text-xs">{{
                                        __("No questions asked")
                                    }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-gray-200"
                            >
                                <i
                                    class="fas fa-headset mr-3 text-purple-500 text-lg"
                                ></i>
                                <div>
                                    <span class="font-bold block text-sm">{{
                                        __("24/7 Support")
                                    }}</span>
                                    <span class="text-gray-500 text-xs">{{
                                        __("Always here to help")
                                    }}</span>
                                </div>
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
                                    'tab-button py-4 px-2 font-semibold text-base transition-all border-b-2 flex items-center',
                                    activeTab === tab.id
                                        ? 'text-indigo-600 border-indigo-600'
                                        : 'text-gray-500 hover:text-gray-700 border-transparent hover:border-gray-300',
                                ]"
                            >
                                <i :class="tab.icon" class="mr-2 text-sm"></i>
                                {{ __(tab.name) }}
                            </button>
                        </nav>
                    </div>

                    <div class="px-6 pb-8 pt-6">
                        <div
                            v-if="activeTab === 'description'"
                            class="prose prose-sm max-w-none text-gray-700 leading-relaxed text-base"
                        >
                            <div v-html="product.description"></div>
                        </div>

                        <div
                            v-if="activeTab === 'specifications'"
                            class="text-sm text-gray-700 leading-relaxed space-y-3"
                        >
                            <div v-html="product.specification"></div>
                        </div>

                        <div
                            v-if="activeTab === 'reviews'"
                            class="text-center py-12 text-gray-500"
                        >
                            <div
                                class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <i
                                    class="fas fa-comments text-2xl text-gray-400"
                                ></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700 mb-3">
                                {{ __("No Reviews Yet") }}
                            </h3>
                            <p class="text-sm max-w-md mx-auto mb-6">
                                {{
                                    __(
                                        "Be the first to share your experience with this product!"
                                    )
                                }}
                            </p>
                            <button
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold text-sm transition-colors"
                            >
                                {{ __("Write a Review") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Children Products Section -->
            <div v-if="hasChildrenProducts" class="mb-8">
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100"
                >
                    <div
                        class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="bg-indigo-600 text-white p-3 rounded-xl mr-4"
                                >
                                    <i class="fas fa-boxes text-lg"></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-xl font-bold text-gray-900 mb-1"
                                    >
                                        {{ __("Frequently Bought Together") }}
                                    </h2>
                                    <p class="text-gray-600 text-sm">
                                        {{
                                            __(
                                                "Products that customers often purchase with this item"
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex items-center text-indigo-600 bg-white px-4 py-2 rounded-xl shadow-sm"
                            >
                                <i class="fas fa-layer-group text-lg mr-2"></i>
                                <span class="font-bold text-base"
                                    >{{ childrenProducts.length }}
                                    {{ __("items") }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div
                            class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6"
                        >
                            <div
                                v-for="childProduct in childrenProducts"
                                :key="childProduct.id"
                                class="bg-white rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 group border border-gray-100 hover:border-indigo-200"
                                @click="goTo(childProduct?.links?.show)"
                            >
                                <div class="flex items-start space-x-3">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-16 h-16 bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center p-2 border border-gray-200 group-hover:border-indigo-300 transition-colors"
                                        >
                                            <img
                                                :src="
                                                    childProduct.images[0]?.url
                                                "
                                                :alt="childProduct.name"
                                                class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            />
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3
                                            class="font-bold text-gray-900 text-sm leading-tight line-clamp-2 group-hover:text-indigo-600 transition-colors mb-1"
                                        >
                                            {{ childProduct.name }}
                                        </h3>

                                        <!-- Category -->
                                        <p
                                            class="text-gray-500 text-xs mb-2 flex items-center"
                                        >
                                            <i
                                                class="fas fa-tag mr-1 text-indigo-500 text-xs"
                                            ></i>
                                            {{ childProduct.category.name }}
                                        </p>

                                        <!-- Rating -->
                                        <div
                                            class="flex items-center mb-2"
                                            v-if="childProduct.rating"
                                        >
                                            <div
                                                class="flex text-yellow-400 text-xs mr-1"
                                            >
                                                <i
                                                    v-for="star in 5"
                                                    :key="star"
                                                    class="fas fa-star"
                                                    :class="{
                                                        'text-gray-300':
                                                            star >
                                                            childProduct.rating,
                                                    }"
                                                ></i>
                                            </div>
                                            <span class="text-gray-500 text-xs"
                                                >({{
                                                    childProduct.reviews || 0
                                                }})</span
                                            >
                                        </div>

                                        <!-- Price -->
                                        <div
                                            class="flex items-center justify-between mb-2"
                                        >
                                            <span
                                                class="text-lg font-bold text-indigo-600"
                                            >
                                                {{ childProduct.symbol }}
                                                {{ childProduct.format_price }}
                                            </span>
                                            <div
                                                v-if="childProduct.featured"
                                                class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full flex items-center"
                                            >
                                                <i
                                                    class="fas fa-star mr-1 text-xs"
                                                ></i>
                                                {{ __("Featured") }}
                                            </div>
                                        </div>

                                        <!-- Stock Status -->
                                        <div class="mb-3">
                                            <span
                                                class="text-xs font-medium px-2 py-1 rounded-full"
                                                :class="
                                                    childProduct.stock > 0
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-red-100 text-red-700'
                                                "
                                            >
                                                <i
                                                    :class="
                                                        childProduct.stock > 0
                                                            ? 'fas fa-check-circle mr-1 text-xs'
                                                            : 'fas fa-times-circle mr-1 text-xs'
                                                    "
                                                ></i>
                                                {{
                                                    childProduct.stock > 0
                                                        ? __("In Stock")
                                                        : __("Out of Stock")
                                                }}
                                            </span>
                                        </div>

                                        <!-- View Product Button -->
                                        <button
                                            @click.stop="
                                                goTo(childProduct?.links?.show)
                                            "
                                            class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white py-2 px-3 rounded-lg font-semibold transition-all transform hover:scale-[1.02] shadow-md hover:shadow-lg flex items-center justify-center group/btn text-sm"
                                        >
                                            <i
                                                class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform text-xs"
                                            ></i>
                                            {{ __("View Product") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mb-6">
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100"
                >
                    <div
                        class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50"
                    >
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div
                                    class="bg-purple-600 text-white p-3 rounded-xl mr-4"
                                >
                                    <i class="fas fa-heart text-lg"></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-xl font-bold text-gray-900 mb-1"
                                    >
                                        {{ __("You May Also Like") }}
                                    </h2>
                                    <p class="text-gray-600 text-sm">
                                        {{
                                            __(
                                                "Similar products you might be interested in"
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                            <a
                                :href="$page.props.routes.search"
                                class="text-purple-600 hover:text-purple-800 flex items-center font-bold text-sm bg-white px-4 py-2 rounded-xl shadow-sm transition-colors"
                            >
                                {{ __("View All") }}
                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        <div
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
                        >
                            <div
                                v-for="product in relatedProducts"
                                :key="product.id"
                                class="bg-white rounded-lg border border-gray-200 overflow-hidden cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 group"
                                @click="goTo(product?.links?.show)"
                            >
                                <div
                                    class="relative h-32 bg-gradient-to-br from-gray-50 to-white flex items-center justify-center p-3"
                                >
                                    <img
                                        :src="product.images[0]?.url"
                                        :alt="product.name"
                                        class="h-24 object-contain transition-transform duration-300 group-hover:scale-110"
                                    />
                                    <!-- Featured badge on related products -->
                                    <div
                                        v-if="product.featured"
                                        class="absolute top-2 left-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md flex items-center"
                                    >
                                        <i
                                            class="fas fa-crown mr-1 text-xs"
                                        ></i>
                                        {{ __("Featured") }}
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h3
                                        class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors leading-tight"
                                    >
                                        {{ product.name }}
                                    </h3>
                                    <div class="flex items-center mb-2">
                                        <div
                                            class="flex text-yellow-400 text-xs mr-1"
                                        >
                                            <i
                                                v-for="star in 5"
                                                :key="star"
                                                :class="[
                                                    star <= 4
                                                        ? 'fas fa-star'
                                                        : 'far fa-star',
                                                ]"
                                            ></i>
                                        </div>
                                        <span class="text-gray-500 text-xs"
                                            >({{ 24 }})</span
                                        >
                                    </div>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-base font-bold text-indigo-600"
                                        >
                                            {{ product.symbol }}
                                            {{ product.format_price }}
                                        </span>
                                        <button
                                            @click.stop="
                                                goTo(product?.links?.show)
                                            "
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg font-semibold text-xs transition-all transform hover:scale-105 shadow-md hover:shadow-lg flex items-center"
                                        >
                                            <i
                                                class="fas fa-shopping-bag mr-1 text-xs"
                                            ></i>
                                            {{ __("Buy") }}
                                        </button>
                                    </div>
                                </div>
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
                    class="absolute top-4 right-4 text-white text-2xl bg-black/50 hover:bg-black/70 rounded-full w-10 h-10 flex items-center justify-center transition-all hover:scale-110 border border-white/20"
                >
                    <i class="fas fa-times"></i>
                </button>

                <!-- Navigation in Modal -->
                <div
                    v-if="product.images?.length > 1"
                    class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-6"
                >
                    <button
                        @click.stop="prevImage"
                        class="bg-white/20 hover:bg-white/30 text-white rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm transition-all hover:scale-110 border border-white/30"
                    >
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <button
                        @click.stop="nextImage"
                        class="bg-white/20 hover:bg-white/30 text-white rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm transition-all hover:scale-110 border border-white/30"
                    >
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>
                </div>

                <!-- Image Counter in Modal -->
                <div
                    class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 text-white px-3 py-2 rounded-full text-sm font-medium backdrop-blur-sm border border-white/20"
                >
                    {{ selectedImageIndex + 1 }} / {{ product.images?.length }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VHeader from "../Components/VHeader.vue";
import VError from "@/components/VError.vue";

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
                {
                    id: "description",
                    name: "Description",
                    icon: "fas fa-file-alt",
                },
                {
                    id: "specifications",
                    name: "Specifications",
                    icon: "fas fa-list-ul",
                },
                { id: "reviews", name: "Reviews", icon: "fas fa-star" },
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

        // Children products from the product data
        childrenProducts() {
            return this.product.children || [];
        },

        // Check if there are children products
        hasChildrenProducts() {
            return this.childrenProducts.length > 0;
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
            if (link) {
                window.location.href = link;
            }
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
                            per_page: 10,
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

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    color: inherit;
}

.prose p {
    margin-bottom: 1em;
    line-height: 1.6;
}

.prose ul,
.prose ol {
    margin-bottom: 1em;
    padding-left: 1.5em;
}

.prose li {
    margin-bottom: 0.5em;
}
</style>
