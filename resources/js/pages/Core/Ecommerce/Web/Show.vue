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
    <div>
        <v-header />

        <!-- Main Content -->
        <main class="px-4 py-4 max-w-7xl mx-auto">
            <!-- Breadcrumbs -->
            <div
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4"
            >
                <a
                    :href="$page.props.routes.search"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors flex items-center text-xs"
                >
                    <i class="fas fa-home mr-1"></i>
                    {{ __("Home") }}
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a
                    :href="product?.category?.web?.index"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors text-xs"
                >
                    {{ product?.category?.name }}
                </a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span
                    class="text-gray-800 dark:text-gray-200 font-medium text-xs"
                    >{{ product?.name }}</span
                >
            </div>

            <!-- Product Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6"
            >
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
                    <!-- Product Gallery - Más compacta -->
                    <div class="space-y-3">
                        <!-- Main Image -->
                        <div
                            class="relative bg-white dark:bg-gray-700 rounded-lg overflow-hidden group border border-gray-300 dark:border-gray-600"
                        >
                            <!-- Badges Container -->
                            <div class="absolute top-2 left-2 z-10 space-y-1">
                                <!-- Discount Badge -->
                                <div
                                    v-if="product.discount"
                                    class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold flex items-center w-fit"
                                >
                                    -{{ product.discount }}%
                                </div>
                                <!-- Featured Badge -->
                                <div
                                    v-if="product.featured"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold flex items-center w-fit"
                                >
                                    <i class="fas fa-crown mr-1 text-xs"></i>
                                    {{ __("Featured") }}
                                </div>
                            </div>

                            <div
                                class="relative h-80 overflow-hidden flex items-center justify-center p-4"
                            >
                                <img
                                    v-if="product?.images?.length"
                                    :src="
                                        product?.images[selectedImageIndex]?.url
                                    "
                                    :alt="product.name"
                                    class="max-h-full max-w-full object-contain transition-transform duration-300 cursor-zoom-in"
                                    :class="{ 'scale-150': isZoomed }"
                                    @click="toggleZoom"
                                />
                            </div>

                            <!-- Navigation Arrows -->
                            <div
                                v-if="product.images?.length > 1"
                                class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            >
                                <button
                                    @click.stop="prevImage"
                                    class="bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full w-6 h-6 flex items-center justify-center shadow-md transition-all hover:scale-110 border border-gray-300 dark:border-gray-500"
                                >
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>
                                <button
                                    @click.stop="nextImage"
                                    class="bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full w-6 h-6 flex items-center justify-center shadow-md transition-all hover:scale-110 border border-gray-300 dark:border-gray-500"
                                >
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </div>

                            <!-- Image Counter -->
                            <div
                                class="absolute bottom-2 left-1/2 transform -translate-x-1/2 bg-black/70 text-white px-2 py-1 rounded text-xs font-medium"
                            >
                                {{ selectedImageIndex + 1 }}/{{
                                    product.images?.length
                                }}
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <div
                            v-if="product.images?.length > 1"
                            class="flex gap-1 overflow-auto"
                        >
                            <div
                                v-for="(image, index) in product?.images"
                                :key="index"
                                @click="selectedImageIndex = index"
                                :class="[
                                    'cursor-pointer p-0.5 rounded border transition-all duration-200 shrink-0 bg-white dark:bg-gray-700',
                                    selectedImageIndex === index
                                        ? 'border-blue-500 scale-105 shadow-sm'
                                        : 'border-gray-300 dark:border-gray-600 hover:border-blue-400',
                                ]"
                            >
                                <img
                                    :src="image?.url"
                                    class="h-12 w-12 object-cover rounded-sm"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-4">
                        <!-- Header -->
                        <div class="space-y-2">
                            <h1
                                class="text-lg font-semibold text-gray-900 dark:text-white leading-tight"
                            >
                                {{ product.name }}
                            </h1>

                            <!-- Rating -->
                            <div class="flex items-center space-x-2">
                                <div class="flex text-orange-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="far fa-star text-xs"></i>
                                </div>
                                <span
                                    class="text-gray-600 dark:text-gray-400 text-xs"
                                    >4.2 (42 {{ __("reviews") }})</span
                                >
                                <span
                                    class="text-green-600 dark:text-green-400 text-xs font-medium flex items-center"
                                >
                                    <i
                                        class="fas fa-check-circle mr-1 text-xs"
                                    ></i>
                                    {{ __("In Stock") }}
                                </span>
                            </div>
                        </div>

                        <!-- Price Section - Estilo AliExpress -->
                        <div
                            class="p-3 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600"
                        >
                            <div class="space-y-1">
                                <div class="flex items-baseline space-x-2">
                                    <span
                                        class="text-2xl font-bold text-red-600 dark:text-red-400"
                                    >
                                        {{ symbol }}{{ price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-gray-500 dark:text-gray-400 line-through text-sm"
                                    >
                                        {{ symbol }}{{ product.originalPrice }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div
                                        class="text-gray-600 dark:text-gray-400 text-xs"
                                    >
                                        {{ __("Available") }}:
                                        <span
                                            class="font-bold text-green-600 dark:text-green-400"
                                            >{{ maxQuantity }}
                                            {{ __("units") }}</span
                                        >
                                    </div>
                                    <div
                                        class="text-blue-600 dark:text-blue-400 text-xs font-medium flex items-center"
                                    >
                                        <i
                                            class="fas fa-shipping-fast mr-1 text-xs"
                                        ></i>
                                        {{ __("Free Shipping") }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Short Description - Mejor posicionada -->
                        <div
                            v-if="description"
                            class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed bg-blue-50 dark:bg-blue-900/20 rounded p-3 border border-blue-200 dark:border-blue-800"
                        >
                            <div v-html="description"></div>
                        </div>

                        <!-- Variants Selection -->
                        <div v-if="product.variants?.length" class="space-y-3">
                            <h3
                                class="text-sm font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Available Options") }}
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <div
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    @click="selectVariant(variant)"
                                    :class="[
                                        'px-3 py-2 rounded border cursor-pointer transition-all',
                                        form.variant_id === variant.id
                                            ? 'bg-blue-500 border-blue-500 text-white shadow-sm'
                                            : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-blue-400',
                                    ]"
                                >
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-medium text-sm whitespace-nowrap"
                                        >
                                            {{ variant.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <v-error :error="errors.variant_id" />
                        </div>

                        <!-- Quantity and Actions - Más compacto -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-gray-900 dark:text-white font-medium text-sm"
                                >
                                    {{ __("Quantity") }}:
                                </span>
                                <div
                                    class="flex items-center border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-600"
                                >
                                    <button
                                        @click="decreaseQuantity"
                                        :disabled="form.quantity <= 1"
                                        :class="[
                                            'px-3 py-1 transition-all text-sm',
                                            form.quantity <= 1
                                                ? 'text-gray-400 cursor-not-allowed'
                                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-500',
                                        ]"
                                    >
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span
                                        class="px-3 py-1 border-l border-r border-gray-300 dark:border-gray-600 w-8 text-center text-sm font-bold bg-white dark:bg-gray-600 text-gray-900 dark:text-white"
                                    >
                                        {{ form.quantity }}
                                    </span>
                                    <button
                                        @click="increaseQuantity"
                                        :disabled="form.quantity >= maxQuantity"
                                        :class="[
                                            'px-3 py-1 transition-all text-sm',
                                            form.quantity >= maxQuantity
                                                ? 'text-gray-400 cursor-not-allowed'
                                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-500',
                                        ]"
                                    >
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <v-error :error="errors.quantity" />

                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    @click="addToCart"
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-medium flex items-center justify-center shadow-sm transition-colors text-sm group"
                                >
                                    <i
                                        class="fas fa-shopping-cart mr-2 group-hover:scale-110 transition-transform text-xs"
                                    ></i>
                                    {{ __("Add to Cart") }}
                                </button>
                                <button
                                    @click="buyNow"
                                    class="bg-orange-500 hover:bg-orange-600 text-white py-3 rounded font-medium flex items-center justify-center shadow-sm transition-colors text-sm group"
                                >
                                    <i
                                        class="fas fa-bolt mr-2 group-hover:scale-110 transition-transform text-xs"
                                    ></i>
                                    {{ __("Buy Now") }}
                                </button>
                            </div>
                        </div>

                        <!-- Service Features - Estilo AliExpress -->
                        <div
                            class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-600 pt-3"
                        >
                            <div class="flex items-center">
                                <i
                                    class="fas fa-shield-alt mr-2 text-green-500 text-xs"
                                ></i>
                                <span>{{ __("Buyer Protection") }}</span>
                            </div>
                            <div class="flex items-center">
                                <i
                                    class="fas fa-undo-alt mr-2 text-blue-500 text-xs"
                                ></i>
                                <span>{{ __("Returns Accepted") }}</span>
                            </div>
                            <div class="flex items-center">
                                <i
                                    class="fas fa-shipping-fast mr-2 text-purple-500 text-xs"
                                ></i>
                                <span>{{ __("Fast Shipping") }}</span>
                            </div>
                            <div class="flex items-center">
                                <i
                                    class="fas fa-headset mr-2 text-orange-500 text-xs"
                                ></i>
                                <span>{{ __("Customer Support") }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details Tabs -->
                <div class="border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4">
                        <nav
                            class="flex space-x-6 border-b border-gray-200 dark:border-gray-600 overflow-x-auto"
                        >
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="activeTab = tab.id"
                                :class="[
                                    'tab-button py-3 font-medium text-sm transition-all border-b-2 flex items-center whitespace-nowrap',
                                    activeTab === tab.id
                                        ? 'text-blue-600 dark:text-blue-400 border-blue-600 dark:border-blue-400'
                                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-transparent',
                                ]"
                            >
                                {{ __(tab.name) }}
                            </button>
                        </nav>
                    </div>

                    <div class="px-4 pb-4 pt-3">
                        <div
                            v-if="activeTab === 'description'"
                            class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                        >
                            <div v-html="product.description"></div>
                        </div>

                        <div
                            v-if="activeTab === 'specifications'"
                            class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"
                        >
                            <div v-html="product.specification"></div>
                        </div>

                        <div
                            v-if="activeTab === 'reviews'"
                            class="text-center py-6 text-gray-500 dark:text-gray-400"
                        >
                            <div
                                class="bg-gray-100 dark:bg-gray-700 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3"
                            >
                                <i
                                    class="fas fa-comments text-lg text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                            <h3
                                class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                            >
                                {{ __("No Reviews Yet") }}
                            </h3>
                            <p class="text-xs max-w-md mx-auto mb-4">
                                {{
                                    __(
                                        "Be the first to share your experience with this product!"
                                    )
                                }}
                            </p>
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium text-xs transition-colors"
                            >
                                {{ __("Write a Review") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Children Products Section -->
            <div v-if="hasChildrenProducts" class="mb-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700"
                >
                    <div
                        class="p-4 border-b border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div class="flex items-center mb-3 sm:mb-0">
                                <div
                                    class="bg-blue-600 text-white p-2 rounded mr-3"
                                >
                                    <i class="fas fa-boxes text-sm"></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-base font-bold text-gray-900 dark:text-white mb-1"
                                    >
                                        {{ __("Frequently Bought Together") }}
                                    </h2>
                                    <p
                                        class="text-gray-600 dark:text-gray-400 text-xs"
                                    >
                                        {{
                                            __(
                                                "Products that customers often purchase with this item"
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex items-center text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-700 px-3 py-1 rounded shadow-sm"
                            >
                                <i class="fas fa-layer-group text-sm mr-2"></i>
                                <span class="font-bold text-sm"
                                    >{{ childrenProducts.length }}
                                    {{ __("items") }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <div
                            class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4"
                        >
                            <div
                                v-for="childProduct in childrenProducts"
                                :key="childProduct.id"
                                class="bg-white dark:bg-gray-700 rounded-lg p-3 cursor-pointer hover:shadow-md transition-all duration-300 group border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500"
                                @click="goTo(childProduct?.web?.show)"
                            >
                                <div class="flex items-start space-x-3">
                                    <!-- Product Image -->
                                    <div class="shrink-0">
                                        <div
                                            class="w-12 h-12 bg-gray-50 dark:bg-gray-600 rounded overflow-hidden flex items-center justify-center p-1 border border-gray-200 dark:border-gray-500 group-hover:border-blue-300 transition-colors"
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
                                            class="font-semibold text-gray-900 dark:text-white text-xs leading-tight line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-1"
                                        >
                                            {{ childProduct.name }}
                                        </h3>

                                        <!-- Category -->
                                        <p
                                            class="text-gray-500 dark:text-gray-400 text-xs mb-1 flex items-center"
                                        >
                                            <i
                                                class="fas fa-tag mr-1 text-blue-500 text-xs"
                                            ></i>
                                            {{ childProduct.category.name }}
                                        </p>

                                        <!-- Price -->
                                        <div
                                            class="flex items-center justify-between mb-2"
                                        >
                                            <span
                                                class="text-sm font-bold text-blue-600 dark:text-blue-400"
                                            >
                                                {{ childProduct.symbol }}
                                                {{ childProduct.format_price }}
                                            </span>
                                        </div>

                                        <!-- Stock Status -->
                                        <div class="mb-2">
                                            <span
                                                class="text-xs font-medium px-2 py-1 rounded-full"
                                                :class="
                                                    childProduct.stock > 0
                                                        ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                                                        : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'
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
                                                goTo(childProduct?.web?.show)
                                            "
                                            class="w-full cursor-pointer bg-blue-600 hover:bg-blue-700 text-white py-1 px-2 rounded font-medium transition-all transform hover:scale-[1.02] shadow-sm hover:shadow flex items-center justify-center text-xs"
                                        >
                                            <i
                                                class="fas fa-eye mr-1 text-xs"
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
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700"
                >
                    <div
                        class="p-4 border-b border-gray-200 dark:border-gray-600 bg-purple-50 dark:bg-purple-900/20"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center"
                        >
                            <div class="flex items-center mb-3 sm:mb-0">
                                <div
                                    class="bg-purple-600 text-white p-2 rounded mr-3"
                                >
                                    <i class="fas fa-heart text-sm"></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-base font-bold text-gray-900 dark:text-white mb-1"
                                    >
                                        {{ __("You May Also Like") }}
                                    </h2>
                                    <p
                                        class="text-gray-600 dark:text-gray-400 text-xs"
                                    >
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
                                class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 flex items-center font-bold text-xs bg-white dark:bg-gray-700 px-3 py-1 rounded shadow-sm transition-colors w-fit"
                            >
                                {{ __("View All") }}
                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <div class="p-4">
                        <div
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3"
                        >
                            <div
                                v-for="product in relatedProducts"
                                :key="product.id"
                                class="bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600 overflow-hidden cursor-pointer hover:shadow-md transition-all duration-300 group"
                                @click="goTo(product?.web?.show)"
                            >
                                <div
                                    class="relative h-20 bg-gray-50 dark:bg-gray-600 flex items-center justify-center p-2"
                                >
                                    <img
                                        :src="product.images[0]?.url"
                                        :alt="product.name"
                                        class="h-12 object-contain transition-transform duration-300 group-hover:scale-110"
                                    />
                                    <!-- Featured badge -->
                                    <div
                                        v-if="product.featured"
                                        class="absolute top-1 left-1 bg-yellow-500 text-white text-xs font-bold px-1 py-0.5 rounded shadow flex items-center"
                                    >
                                        <i
                                            class="fas fa-crown mr-1 text-xs"
                                        ></i>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <h3
                                        class="font-medium text-gray-900 dark:text-white text-xs mb-1 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-tight"
                                    >
                                        {{ product.name }}
                                    </h3>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-xs font-bold text-blue-600 dark:text-blue-400"
                                        >
                                            {{ product.symbol }}
                                            {{ product.format_price }}
                                        </span>
                                        <button
                                            @click.stop="
                                                goTo(product?.web?.show)
                                            "
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded font-medium text-xs transition-all transform hover:scale-105 shadow-sm flex items-center"
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
            product: {},
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

        description() {
            return (
                this.selectedVariant?.description ||
                this.product.short_description
            );
        },

        symbol() {
            return this.selectedVariant?.symbol || this.product.symbol;
        },

        childrenProducts() {
            return this.product.children || [];
        },

        hasChildrenProducts() {
            return this.childrenProducts.length > 0;
        },
    },

    created() {
        this.getProduct();
    },

    mounted() {
        this.$nextTick(() => {
            const footer = document.getElementById("footer");
            if (footer) {
                footer.style.display = "block";
            }
        });
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
                    this.$page.props.api.product_show
                );

                if (res.status === 200) {
                    this.product = res.data;
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
                const res = await this.$server.get(
                    this.$page.props.api.search,
                    {
                        params: {
                            random: true,
                            per_page: 30,
                            category: item.category.slug,
                        },
                    }
                );

                if (res.status === 200) {
                    this.relatedProducts = res.data.data;
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
                    this.$page.props.api.orders,
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
