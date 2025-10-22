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
        <v-header />

        <!-- Hero Carousel -->
        <section
            class="relative overflow-hidden bg-gradient-to-r from-purple-900 via-purple-700 to-indigo-800"
        >
            <div
                class="flex carousel-transition"
                :style="`transform: translateX(-${currentSlide * 100}%)`"
            >
                <div
                    v-for="(slide, index) in carouselSlides"
                    :key="index"
                    class="min-w-full flex-shrink-0"
                >
                    <div class="text-white py-16 md:py-24">
                        <div class="container mx-auto px-4">
                            <div class="flex flex-col md:flex-row items-center">
                                <div class="md:w-1/2 mb-8 md:mb-0 md:pr-10">
                                    <h2
                                        class="text-4xl md:text-6xl font-bold mb-6 leading-tight"
                                    >
                                        {{ slide.name }}
                                    </h2>
                                    <p
                                        class="text-xl md:text-2xl mb-8 leading-relaxed opacity-90"
                                        v-html="slide.short_description"
                                    ></p>
                                    <button
                                        class="bg-white text-purple-700 font-bold py-4 px-10 rounded-xl shadow-lg hover:bg-gray-100 transform hover:-translate-y-1 transition-all duration-300 text-lg"
                                        @click.stop="goTo(slide?.links?.show)"
                                    >
                                        {{ __("Shop Now") }}
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>
                                <div class="md:w-1/2 flex justify-center">
                                    <div class="relative">
                                        <div
                                            class="absolute -inset-4 bg-white/20 rounded-2xl blur-xl"
                                        ></div>
                                        <img
                                            :src="slide.images[0].url"
                                            :alt="slide.name"
                                            class="rounded-2xl shadow-2xl w-full max-w-md relative z-10 transform hover:scale-105 transition-transform duration-500"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button
                @click="prevSlide"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-purple-700 rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 z-20"
            >
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <button
                @click="nextSlide"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-purple-700 rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 z-20"
            >
                <i class="fas fa-chevron-right text-lg"></i>
            </button>

            <!-- Navigation Dots -->
            <div
                class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20"
            >
                <button
                    v-for="(slide, index) in carouselSlides"
                    :key="index"
                    @click="currentSlide = index"
                    class="w-4 h-4 rounded-full transition-all duration-300 border-2 border-white"
                    :class="
                        currentSlide === index
                            ? 'bg-white scale-125'
                            : 'bg-transparent hover:bg-white/50'
                    "
                ></button>
            </div>
        </section>

        <!-- Featured Categories Section -->
        <section class="py-5 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2
                        class="text-4xl font-bold mb-4 bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent"
                    >
                        {{ __("Featured Categories") }}
                    </h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        {{ __("Discover our most popular product categories") }}
                    </p>
                </div>

                <!-- Categories Grid -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6"
                >
                    <div
                        v-for="category in featuredCategories"
                        :key="category.id"
                        class="category-card group relative rounded-2xl overflow-hidden shadow-lg cursor-pointer transform transition-all duration-500 hover:-translate-y-2"
                        @click="goTo(category.links.index)"
                    >
                        <!-- Category Image Container -->
                        <div class="relative h-40 overflow-hidden">
                            <!-- Category Image -->
                            <img
                                :src="category.images[0].url"
                                :alt="category.name"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                            />

                            <!-- Featured Star Badge -->
                            <div
                                class="absolute top-3 left-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white p-2 rounded-full shadow-lg z-10 animate-pulse"
                            >
                                <i class="fas fa-star text-xs"></i>
                            </div>

                            <!-- Product Count Badge -->
                            <div
                                v-if="category.product_count"
                                class="absolute top-3 right-3 bg-black/70 text-white text-xs font-semibold px-2 py-1 rounded-full backdrop-blur-sm"
                            >
                                {{ category.product_count }} {{ __("items") }}
                            </div>

                            <!-- Gradient Overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-300"
                            ></div>

                            <!-- Category Content -->
                            <div
                                class="absolute bottom-4 left-0 right-0 text-center z-10"
                            >
                                <h3
                                    class="text-white text-lg font-bold px-2 mb-1"
                                >
                                    {{ category.name }}
                                </h3>
                                <!-- Featured Label -->
                                <div class="flex items-center justify-center">
                                    <span class="text-yellow-300 text-sm mr-1">
                                        <i class="fas fa-star"></i>
                                    </span>
                                    <span
                                        class="text-white text-sm font-medium"
                                    >
                                        {{ __("Featured") }}
                                    </span>
                                </div>
                            </div>

                            <!-- Hover Glow Effect -->
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-yellow-200/20 to-purple-200/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                            ></div>
                        </div>

                        <!-- Mobile Floating Badge -->
                        <div
                            class="absolute -top-2 -right-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg md:hidden z-20"
                        >
                            <i class="fas fa-star text-xs"></i>
                        </div>

                        <!-- Popularity Indicator -->
                        <div
                            v-if="category.popularity"
                            class="absolute -bottom-2 -left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg"
                        >
                            {{ __("Popular") }}
                        </div>
                    </div>
                </div>

                <!-- Information Section -->

                <!-- Call to Action -->
                <div class="text-center mt-8">
                    <button
                        @click="goTo($page.props.routes.categories_index)"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center"
                    >
                        {{ __("View All Categories") }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- New Arrivals -->
        <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
            <div class="container mx-auto px-4">
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-12"
                >
                    <div>
                        <h2
                            class="text-4xl font-bold mb-2 bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent"
                        >
                            {{ __("New Arrivals") }}
                        </h2>
                        <p class="text-gray-600">
                            {{ __("Check out our latest products") }}
                        </p>
                    </div>
                    <a
                        href="#"
                        @click="goTo($page.props.routes.search)"
                        class="mt-4 md:mt-0 inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold text-lg group"
                    >
                        {{ __("View all") }}
                        <i
                            class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"
                        ></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8"
                >
                    <div
                        v-for="(product, index) in latest_products"
                        :key="index"
                        class="product-card bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-500 hover:-translate-y-3 group cursor-pointer"
                        @click="goTo(product.links.show)"
                    >
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-700"
                            />
                            <div
                                class="absolute top-3 left-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md"
                            >
                                {{ __("NEW") }}
                            </div>
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"
                            ></div>
                        </div>
                        <div class="p-5">
                            <h3
                                class="font-bold text-lg mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors"
                            >
                                {{ product.name }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-3">
                                {{ product.category.name }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-purple-600">
                                    {{ product.symbol }}
                                    {{ product.format_price }}
                                </span>
                                <button
                                    class="bg-purple-100 text-purple-600 p-3 rounded-full hover:bg-purple-600 hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-md"
                                    @click.stop="addToCart(product)"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Best Sellers -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-12"
                >
                    <div>
                        <h2
                            class="text-4xl font-bold mb-2 bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent"
                        >
                            {{ __("Best Sellers") }}
                        </h2>
                        <p class="text-gray-600">
                            {{ __("Our most popular products") }}
                        </p>
                    </div>
                    <a
                        href="#"
                        @click="goTo($page.props.routes.search)"
                        class="mt-4 md:mt-0 inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold text-lg group"
                    >
                        {{ __("View all") }}
                        <i
                            class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"
                        ></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"
                >
                    <div
                        v-for="(product, index) in latest_seller"
                        :key="index"
                        class="product-card bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-500 hover:-translate-y-3 group cursor-pointer"
                        @click="goTo(product.links.show)"
                    >
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-700"
                            />
                            <div
                                class="absolute top-3 left-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md flex items-center"
                            >
                                <i class="fas fa-crown mr-1 text-xs"></i>
                                {{ __("Best Seller") }}
                            </div>
                            <div
                                v-if="product.discount"
                                class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md"
                            >
                                -{{ product.discount }}%
                            </div>
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"
                            ></div>
                        </div>
                        <div class="p-5">
                            <h3
                                class="font-bold text-lg mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors"
                            >
                                {{ product.name }}
                            </h3>
                            <div
                                class="flex items-center mb-2"
                                v-if="product.rating"
                            >
                                <div class="flex text-yellow-400 mr-2">
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        class="fas fa-star text-sm"
                                        :class="{
                                            'text-gray-300':
                                                star > product.rating,
                                        }"
                                    ></i>
                                </div>
                                <span
                                    v-if="product.reviews"
                                    class="text-gray-500 text-sm"
                                    >({{ product.reviews }})</span
                                >
                            </div>
                            <p
                                v-if="product.soldCount"
                                class="text-gray-500 text-sm mb-3"
                            >
                                {{ product.soldCount }} {{ __("sold") }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-xl font-bold text-purple-600"
                                    >
                                        {{ product.symbol }}
                                        {{ product.format_price }}</span
                                    >
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-sm text-gray-500 line-through ml-2"
                                        >{{ product.symbol
                                        }}{{ product.originalPrice }}</span
                                    >
                                </div>
                                <button
                                    class="bg-purple-100 text-purple-600 p-3 rounded-full hover:bg-purple-600 hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-md"
                                    @click.stop="addToCart(product)"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section
            class="py-16 bg-gradient-to-br from-purple-50 to-indigo-50 relative overflow-hidden"
        >
            <!-- Background Decoration -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-purple-200 rounded-full -translate-y-32 translate-x-32 opacity-20"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-200 rounded-full translate-y-24 -translate-x-24 opacity-30"
            ></div>

            <div class="container mx-auto px-4 relative z-10">
                <!-- Section Header -->
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-12"
                >
                    <div class="text-center md:text-left">
                        <div
                            class="flex items-center justify-center md:justify-start mb-3"
                        >
                            <div
                                class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-2 rounded-lg mr-3"
                            >
                                <i class="fas fa-crown text-lg"></i>
                            </div>
                            <h2
                                class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent"
                            >
                                {{ __("Featured Products") }}
                            </h2>
                        </div>
                        <p class="text-gray-600 text-lg">
                            {{ __("Handpicked premium items just for you") }}
                        </p>
                    </div>
                    <a
                        href="#"
                        @click="goTo($page.props.routes.search)"
                        class="mt-4 md:mt-0 inline-flex items-center bg-white text-purple-600 hover:text-purple-800 font-semibold text-lg group py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300"
                    >
                        {{ __("View All Featured") }}
                        <i
                            class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"
                        ></i>
                    </a>
                </div>

                <!-- Products Grid -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"
                >
                    <div
                        v-for="product in featuredProducts"
                        :key="product.id"
                        class="product-card bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-500 hover:-translate-y-3 group cursor-pointer border-2 border-transparent hover:border-purple-200 relative"
                        @click="goTo(product.links.show)"
                    >
                        <!-- Premium Featured Badge -->
                        <div
                            class="absolute top-3 left-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white p-2 rounded-full shadow-lg z-20"
                        >
                            <i class="fas fa-star text-xs"></i>
                        </div>

                        <!-- Product Image Container -->
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-700"
                            />

                            <!-- Discount Badge -->
                            <div
                                v-if="product.discount"
                                class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md z-20"
                            >
                                -{{ product.discount }}%
                            </div>

                            <!-- Exclusive Ribbon -->
                            <div
                                class="absolute top-0 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-bold px-4 py-1 rounded-b-lg shadow-md z-20"
                            >
                                {{ __("EXCLUSIVE") }}
                            </div>

                            <!-- Hover Overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-purple-900/30 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"
                            ></div>

                            <!-- Quick View Button -->
                            <div
                                class="absolute bottom-3 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-20"
                            >
                                <button
                                    class="bg-white text-purple-600 font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-purple-50 transition-colors text-sm"
                                >
                                    {{ __("Quick View") }}
                                </button>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="p-5 relative">
                            <!-- Featured Product Label -->
                            <div
                                class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full"
                            >
                                {{ __("FEATURED") }}
                            </div>

                            <h3
                                class="font-bold text-lg mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors text-center mt-2"
                            >
                                {{ product.name }}
                            </h3>

                            <!-- Rating Section -->
                            <div
                                class="flex items-center justify-center mb-3"
                                v-if="product.reviews"
                            >
                                <div class="flex text-yellow-400 mr-2">
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        class="fas fa-star text-sm"
                                        :class="{
                                            'text-gray-300':
                                                star > product.rating,
                                        }"
                                    ></i>
                                </div>
                                <span class="text-gray-500 text-sm"
                                    >({{ product.reviews }})</span
                                >
                            </div>

                            <!-- Popularity Indicator 
                            <div
                                v-if="product.popularity"
                                class="flex justify-center mb-3"
                            >
                                <span
                                    class="bg-green-100 text-green-600 text-xs font-semibold px-2 py-1 rounded-full"
                                >
                                    <i class="fas fa-fire mr-1"></i>
                                    {{ __("Trending") }}
                                </span>
                            </div>-->

                            <!-- Price and Add to Cart -->
                            <div class="flex items-center justify-between">
                                <div class="text-center flex-1">
                                    <span
                                        class="text-xl font-bold text-purple-600 block"
                                    >
                                        {{ product.symbol
                                        }}{{ product.format_price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-sm text-gray-500 line-through"
                                    >
                                        {{ product.symbol
                                        }}{{ product.originalPrice }}
                                    </span>
                                </div>
                                <button
                                    class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white cursor-pointer p-3 rounded-full hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 shadow-md"
                                    @click="goTo(product.links.show)"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>

                            <!-- Additional Features 
                            <div
                                class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100"
                            >
                                <div
                                    class="flex items-center text-green-600 text-sm"
                                >
                                    <i class="fas fa-shipping-fast mr-1"></i>
                                    <span>{{ __("Free Shipping") }}</span>
                                </div>
                                <div
                                    class="flex items-center text-blue-600 text-sm"
                                >
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    <span>{{ __("Warranty") }}</span>
                                </div>
                            </div>-->
                        </div>

                        <!-- Corner Accent -->
                        <div
                            class="absolute top-0 right-0 w-6 h-6 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-bl-2xl"
                        ></div>
                    </div>
                </div>

                <!-- Quality Assurance Banner
                <div
                    class="mt-12 bg-white rounded-2xl shadow-lg p-6 border border-purple-200"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between"
                    >
                        <div class="flex items-center mb-4 md:mb-0">
                            <div
                                class="bg-green-100 text-green-600 p-3 rounded-full mr-4"
                            >
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">
                                    {{ __("Quality Guaranteed") }}
                                </h3>
                                <p class="text-gray-600">
                                    {{
                                        __(
                                            "All featured products undergo strict quality checks"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-4">
                            <div class="text-center">
                                <div
                                    class="bg-purple-100 text-purple-600 p-2 rounded-lg mb-1"
                                >
                                    <i class="fas fa-award"></i>
                                </div>
                                <span class="text-xs text-gray-600">{{
                                    __("Premium Quality")
                                }}</span>
                            </div>
                            <div class="text-center">
                                <div
                                    class="bg-blue-100 text-blue-600 p-2 rounded-lg mb-1"
                                >
                                    <i class="fas fa-truck"></i>
                                </div>
                                <span class="text-xs text-gray-600">{{
                                    __("Fast Delivery")
                                }}</span>
                            </div>
                            <div class="text-center">
                                <div
                                    class="bg-green-100 text-green-600 p-2 rounded-lg mb-1"
                                >
                                    <i class="fas fa-headset"></i>
                                </div>
                                <span class="text-xs text-gray-600">{{
                                    __("24/7 Support")
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </section>

        <!-- Random Products -->
        <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2
                        class="text-4xl font-bold mb-4 bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent"
                    >
                        {{ __("You Might Also Like") }}
                    </h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        {{ __("Discover products tailored to your interests") }}
                    </p>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"
                >
                    <div
                        v-for="product in randomProducts"
                        :key="product.id"
                        class="product-card bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-500 hover:-translate-y-3 group cursor-pointer"
                        @click="goTo(product.links.show)"
                    >
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-700"
                            />
                            <div
                                v-if="product.discount"
                                class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md"
                            >
                                -{{ product.discount }}%
                            </div>
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"
                            ></div>
                        </div>
                        <div class="p-5">
                            <h3
                                class="font-bold text-lg mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors"
                            >
                                {{ product.name }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-3">
                                {{ product.category.name }}
                            </p>
                            <div
                                class="flex items-center mb-3"
                                v-if="product.reviews"
                            >
                                <div class="flex text-yellow-400 mr-2">
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        class="fas fa-star text-sm"
                                        :class="{
                                            'text-gray-300':
                                                star > product.rating,
                                        }"
                                    ></i>
                                </div>
                                <span class="text-gray-500 text-sm"
                                    >({{ product.reviews }})</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-xl font-bold text-purple-600"
                                    >
                                        {{ product.symbol }}
                                        {{ product.format_price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-sm text-gray-500 line-through ml-2"
                                        >{{ product.symbol
                                        }}{{ product.originalPrice }}</span
                                    >
                                </div>
                                <button
                                    class="bg-purple-100 text-purple-600 p-3 rounded-full hover:bg-purple-600 hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-md"
                                    @click.stop="addToCart(product)"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-12">
                    <button
                        @click="goTo($page.props.routes.search)"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-4 px-10 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-lg"
                    >
                        {{ __("Load More Products") }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section
            class="py-20 bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white relative overflow-hidden"
        >
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute -top-24 -right-24 w-64 h-64 bg-white rounded-full"
                ></div>
                <div
                    class="absolute -bottom-24 -left-24 w-64 h-64 bg-white rounded-full"
                ></div>
            </div>
            <div class="container mx-auto px-4 text-center relative z-10">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    {{ __("Can't find what you're looking for?") }}
                </h2>
                <p
                    class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90 leading-relaxed"
                >
                    {{
                        __(
                            "Explore our full catalog with thousands of products across all categories"
                        )
                    }}
                </p>
                <button
                    @click="goTo($page.props.routes.search)"
                    class="bg-white text-purple-700 font-bold py-4 px-12 rounded-xl shadow-2xl hover:bg-gray-100 transform hover:-translate-y-1 transition-all duration-300 text-lg inline-flex items-center"
                >
                    {{ __("Browse All Products") }}
                    <i class="fas fa-arrow-right ml-3"></i>
                </button>
            </div>
        </section>
    </div>
</template>

<script>
import VHeader from "../Components/VHeader.vue";

export default {
    components: {
        VHeader,
    },

    data() {
        return {
            mobileMenuOpen: false,
            currentSlide: 0,
            carouselInterval: null,
            carouselSlides: [],
            featuredCategories: [],
            newProducts: [],
            bestSellers: [],
            featuredProducts: [],
            randomProducts: [],
            latest_products: [],
            latest_seller: [],
        };
    },

    created() {
        this.getFeaturedProducts();
    },

    mounted() {
        this.getFeaturedCategories();
        this.getProducts();
        this.getLatestProducts();
        this.getLatestSeller();

        this.carouselInterval = setInterval(() => {
            this.nextSlide();
        }, 5000);
    },

    methods: {
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        nextSlide() {
            this.currentSlide =
                (this.currentSlide + 1) % this.carouselSlides.length;
        },
        prevSlide() {
            this.currentSlide =
                (this.currentSlide - 1 + this.carouselSlides.length) %
                this.carouselSlides.length;
        },

        goTo(link) {
            if (link) {
                window.location.href = link;
            }
        },

        addToCart(product) {
            // Implementar lógica para agregar al carrito
            // Por ahora solo prevenimos la navegación
            event.stopPropagation();
            $notify.success(`${product.name} added to cart!`);
        },

        async getFeaturedProducts() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.search_api,
                    {
                        params: {
                            per_page: 20,
                            random: true,
                            featured: true,
                        },
                    }
                );

                if (res.status == 200) {
                    this.carouselSlides = res.data.data;
                    this.featuredProducts = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getProducts() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.search_api,
                    {
                        params: {
                            per_page: 100,
                            random: true,
                        },
                    }
                );

                if (res.status == 200) {
                    this.randomProducts = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getLatestProducts() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.search_api,
                    {
                        params: {
                            per_page: 20,
                            random: true,
                            latest: 15,
                        },
                    }
                );

                if (res.status == 200) {
                    this.latest_products = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getLatestSeller() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.search_api,
                    {
                        params: {
                            per_page: 30,
                            random: true,
                            latest_seller: true,
                        },
                    }
                );

                if (res.status == 200) {
                    this.latest_seller = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getFeaturedCategories() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.categories_api,
                    {
                        params: {
                            featured: true,
                            per_page: 10,
                            random: true,
                        },
                    }
                );

                if (res.status == 200) {
                    this.featuredCategories = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },
    },
    beforeUnmount() {
        if (this.carouselInterval) {
            clearInterval(this.carouselInterval);
        }
    },
};
</script>

<style scoped>
.carousel-transition {
    transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.product-card:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animación sutil para los botones de navegación del carousel */
@keyframes pulse-glow {
    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
    }
}

.carousel-nav-btn:hover {
    animation: pulse-glow 2s infinite;
}
</style>
