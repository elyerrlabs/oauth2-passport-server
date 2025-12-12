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
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <v-header />

        <!-- Hero Carousel -->
        <section class="relative overflow-hidden bg-blue-600 dark:bg-blue-900">
            <div class="relative overflow-hidden">
                <div
                    class="flex"
                    :style="`transform: translateX(-${currentSlide * 100}%)`"
                >
                    <div
                        v-for="(slide, index) in carouselSlides"
                        :key="index"
                        class="min-w-full shrink-0"
                    >
                        <div class="text-white py-4 sm:py-8 md:py-12">
                            <div class="container mx-auto px-3 sm:px-4">
                                <div
                                    class="flex flex-col lg:flex-row items-center"
                                >
                                    <!-- Contenido del texto -->
                                    <div
                                        class="lg:w-1/2 mb-4 sm:mb-6 lg:mb-0 lg:pr-8 text-center lg:text-left"
                                    >
                                        <h2
                                            class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold mb-2 sm:mb-3 lg:mb-4 leading-tight"
                                        >
                                            {{ slide.name }}
                                        </h2>
                                        <p
                                            class="text-xs sm:text-sm md:text-base lg:text-lg mb-3 sm:mb-4 lg:mb-6 leading-relaxed opacity-90 max-w-2xl mx-auto lg:mx-0"
                                            v-html="slide.short_description"
                                        ></p>
                                        <button
                                            class="bg-white dark:bg-gray-800 cursor-pointer text-blue-600 dark:text-blue-400 font-bold py-1.5 px-3 sm:py-2 sm:px-4 md:py-3 md:px-6 rounded-lg shadow text-xs sm:text-sm md:text-base"
                                            @click.stop="goTo(slide?.web?.show)"
                                        >
                                            {{ __("Shop Now") }}
                                            <i
                                                class="fas fa-arrow-right ml-1 sm:ml-2"
                                            ></i>
                                        </button>
                                    </div>

                                    <!-- Imagen -->
                                    <div
                                        class="lg:w-1/2 flex justify-center mt-4 sm:mt-6 lg:mt-0"
                                    >
                                        <div
                                            class="relative w-full max-w-[180px] xs:max-w-[200px] sm:max-w-xs md:max-w-sm"
                                        >
                                            <div
                                                class="absolute -inset-1 sm:-inset-2 bg-white/20 dark:bg-gray-800/30 rounded-xl"
                                            ></div>
                                            <img
                                                :src="slide.images[0].url"
                                                :alt="slide.name"
                                                class="rounded-xl shadow-lg w-full relative z-10"
                                                loading="lazy"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows - Solo en pantallas grandes -->
            <button
                @click="prevSlide"
                class="hidden sm:flex absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/90 dark:bg-gray-800/90 text-blue-600 dark:text-blue-400 rounded-full w-8 h-8 md:w-10 md:h-10 items-center justify-center shadow z-20"
            >
                <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
            </button>
            <button
                @click="nextSlide"
                class="hidden sm:flex absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/90 dark:bg-gray-800/90 text-blue-600 dark:text-blue-400 rounded-full w-8 h-8 md:w-10 md:h-10 items-center justify-center shadow z-20"
            >
                <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
            </button>

            <!-- Navigation Dots -->
            <div
                class="absolute bottom-2 sm:bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-1 sm:space-x-2 z-20"
            >
                <button
                    v-for="(slide, index) in carouselSlides"
                    :key="index"
                    @click="currentSlide = index"
                    class="w-1.5 h-1.5 sm:w-2 sm:h-2 md:w-3 md:h-3 rounded-full border border-white"
                    :class="
                        currentSlide === index ? 'bg-white' : 'bg-transparent'
                    "
                ></button>
            </div>
        </section>

        <!-- Featured Categories Section -->
        <section class="py-4 sm:py-6 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-3 sm:px-4">
                <div class="text-center mb-4 sm:mb-6">
                    <h2
                        class="text-sm sm:text-base md:text-xl font-bold mb-1 sm:mb-2 text-blue-600 dark:text-blue-400"
                    >
                        {{ __("Featured Categories") }}
                    </h2>
                    <p
                        class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm"
                    >
                        {{ __("Discover our most popular product categories") }}
                    </p>
                </div>
                <div
                    class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3"
                >
                    <div
                        v-for="category in featuredCategories"
                        :key="category.id"
                        class="category-card relative rounded-lg overflow-hidden shadow-sm cursor-pointer"
                        @click="goTo(category.web.index)"
                    >
                        <div class="relative h-20 sm:h-24 overflow-hidden">
                            <img
                                :src="category.images[0].url"
                                :alt="category.name"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            />
                            <div
                                v-if="category.product_count"
                                class="absolute top-1 right-1 bg-black/70 dark:bg-gray-900/80 text-white text-[10px] xs:text-xs px-1 py-0.5 rounded"
                            >
                                {{ category.product_count }}
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-80"
                            ></div>
                            <div
                                class="absolute bottom-1 sm:bottom-2 left-0 right-0 text-center z-10"
                            >
                                <h3
                                    class="text-white text-[10px] xs:text-xs font-bold px-1 mb-0.5"
                                >
                                    {{ category.name }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- New Arrivals -->
        <section class="py-6 sm:py-8 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-3 sm:px-4">
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-4 sm:mb-6"
                >
                    <div>
                        <h2
                            class="text-sm sm:text-base md:text-xl font-bold mb-1 text-blue-600 dark:text-blue-400"
                        >
                            {{ __("New Arrivals") }}
                        </h2>
                        <p
                            class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm"
                        >
                            {{ __("Check out our latest products") }}
                        </p>
                    </div>
                    <a
                        href="#"
                        @click="goTo($page.props.routes.search)"
                        class="mt-1 sm:mt-2 md:mt-0 inline-flex items-center text-blue-600 dark:text-blue-400 font-medium text-xs sm:text-sm"
                    >
                        {{ __("View all") }}
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3"
                >
                    <div
                        v-for="(product, index) in latest_products"
                        :key="index"
                        class="product-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700"
                        @click="goTo(product.web.show)"
                    >
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-28 sm:h-32 object-cover"
                                loading="lazy"
                            />
                            <div
                                class="absolute top-1 left-1 bg-green-500 text-white text-[10px] xs:text-xs font-bold px-1 py-0.5 rounded"
                            >
                                {{ __("NEW") }}
                            </div>
                        </div>
                        <div class="p-2">
                            <h3
                                class="font-medium text-gray-900 dark:text-white text-[10px] xs:text-xs leading-tight line-clamp-2 mb-1"
                            >
                                {{ product.name }}
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-[10px] xs:text-xs mb-1"
                            >
                                {{ product.category.name }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs sm:text-sm font-bold text-red-600 dark:text-red-400"
                                >
                                    {{ product.symbol }}
                                    {{ product.format_price }}
                                </span>
                                <button
                                    class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-1 sm:p-1.5 rounded shadow-sm"
                                >
                                    <i class="fas fa-shopping-cart text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Best Sellers -->
        <section class="py-6 sm:py-8 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-3 sm:px-4">
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-4 sm:mb-6"
                >
                    <div>
                        <h2
                            class="text-sm sm:text-base md:text-xl font-bold mb-1 text-blue-600 dark:text-blue-400"
                        >
                            {{ __("Best Sellers") }}
                        </h2>
                        <p
                            class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm"
                        >
                            {{ __("Our most popular products") }}
                        </p>
                    </div>
                    <button
                        @click="goTo($page.props.routes.search)"
                        class="mt-1 sm:mt-2 md:mt-0 inline-flex items-center text-blue-600 dark:text-blue-400 font-medium text-xs sm:text-sm"
                    >
                        {{ __("View all") }}
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </button>
                </div>
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3"
                >
                    <div
                        v-for="(product, index) in latest_seller"
                        :key="index"
                        class="product-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700"
                        @click="goTo(product.web.show)"
                    >
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-28 sm:h-32 object-cover"
                                loading="lazy"
                            />
                            <div
                                class="absolute top-1 left-1 bg-yellow-500 text-white text-[10px] xs:text-xs font-bold px-1 py-0.5 rounded flex items-center"
                            >
                                <i class="fas fa-crown mr-0.5 text-xs"></i>
                            </div>
                            <div
                                v-if="product.discount"
                                class="absolute top-1 right-1 bg-red-500 text-white text-[10px] xs:text-xs font-bold px-1 py-0.5 rounded"
                            >
                                -{{ product.discount }}%
                            </div>
                        </div>
                        <div class="p-2">
                            <h3
                                class="font-medium text-gray-900 dark:text-white text-[10px] xs:text-xs leading-tight line-clamp-2 mb-1"
                            >
                                {{ product.name }}
                            </h3>
                            <div
                                class="flex items-center mb-1"
                                v-if="product.rating"
                            >
                                <div class="flex text-yellow-400 mr-1">
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        class="fas fa-star text-[10px] xs:text-xs"
                                        :class="
                                            star > product.rating
                                                ? 'text-gray-300 dark:text-gray-600'
                                                : ''
                                        "
                                    ></i>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-xs sm:text-sm font-bold text-red-600 dark:text-red-400"
                                    >
                                        {{ product.symbol }}
                                        {{ product.format_price }}</span
                                    >
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-[10px] xs:text-xs text-gray-500 dark:text-gray-400 line-through ml-1"
                                    >
                                        {{ product.symbol }}
                                        {{ product.originalPrice }}
                                    </span>
                                </div>
                                <button
                                    class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-1 sm:p-1.5 rounded shadow-sm"
                                    @click.stop="addToCart(product)"
                                >
                                    <i class="fas fa-shopping-cart text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="py-6 sm:py-8 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-3 sm:px-4">
                <div
                    class="flex flex-col md:flex-row justify-between items-center mb-4 sm:mb-6"
                >
                    <div class="text-center md:text-left">
                        <div
                            class="flex items-center justify-center md:justify-start mb-2"
                        >
                            <div
                                class="bg-blue-600 text-white p-1 sm:p-1.5 rounded mr-2"
                            >
                                <i class="fas fa-crown text-xs sm:text-sm"></i>
                            </div>
                            <h2
                                class="text-sm sm:text-base md:text-xl font-bold text-blue-600 dark:text-blue-400"
                            >
                                {{ __("Featured Products") }}
                            </h2>
                        </div>
                        <p
                            class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm"
                        >
                            {{ __("Handpicked premium items just for you") }}
                        </p>
                    </div>
                    <button
                        @click="goTo($page.props.routes.search)"
                        class="mt-1 sm:mt-2 md:mt-0 inline-flex items-center bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 font-medium text-xs sm:text-sm py-1.5 px-3 sm:py-2 sm:px-4 rounded-lg shadow-sm"
                    >
                        {{ __("View All") }}
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </button>
                </div>
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3"
                >
                    <div
                        v-for="product in featuredProducts"
                        :key="product.id"
                        class="product-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700"
                        @click="goTo(product.web.show)"
                    >
                        <div
                            class="absolute top-1 left-1 bg-yellow-500 text-white p-1 rounded z-20"
                        >
                            <i class="fas fa-star text-xs"></i>
                        </div>
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-28 sm:h-32 object-cover"
                                loading="lazy"
                            />
                            <div
                                v-if="product.discount"
                                class="absolute top-1 right-1 bg-red-500 text-white text-[10px] xs:text-xs font-bold px-1 py-0.5 rounded z-20"
                            >
                                -{{ product.discount }}%
                            </div>
                        </div>
                        <div class="p-2">
                            <h3
                                class="font-medium text-gray-900 dark:text-white text-[10px] xs:text-xs leading-tight line-clamp-2 text-center mb-1"
                            >
                                {{ product.name }}
                            </h3>
                            <div class="flex items-center justify-between">
                                <div class="text-center flex-1">
                                    <span
                                        class="text-xs sm:text-sm font-bold text-red-600 dark:text-red-400 block"
                                    >
                                        {{ product.symbol
                                        }}{{ product.format_price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-[10px] xs:text-xs text-gray-500 dark:text-gray-400 line-through"
                                    >
                                        {{ product.symbol
                                        }}{{ product.originalPrice }}
                                    </span>
                                </div>
                                <button
                                    class="bg-blue-600 text-white cursor-pointer p-1 sm:p-1.5 rounded shadow-sm"
                                    @click="goTo(product.links.show)"
                                >
                                    <i class="fas fa-shopping-cart text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Random Products -->
        <section class="py-6 sm:py-8 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-3 sm:px-4">
                <div class="text-center mb-4 sm:mb-6">
                    <h2
                        class="text-sm sm:text-base md:text-xl font-bold mb-1 sm:mb-2 text-blue-600 dark:text-blue-400"
                    >
                        {{ __("You Might Also Like") }}
                    </h2>
                    <p
                        class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm max-w-2xl mx-auto"
                    >
                        {{ __("Discover products tailored to your interests") }}
                    </p>
                </div>
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3"
                >
                    <div
                        v-for="product in randomProducts"
                        :key="product.id"
                        class="product-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700"
                        @click="goTo(product.web.show)"
                    >
                        <div class="relative overflow-hidden">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-28 sm:h-32 object-cover"
                                loading="lazy"
                            />
                            <div
                                v-if="product.discount"
                                class="absolute top-1 right-1 bg-red-500 text-white text-[10px] xs:text-xs font-bold px-1 py-0.5 rounded"
                            >
                                -{{ product.discount }}%
                            </div>
                        </div>
                        <div class="p-2">
                            <h3
                                class="font-medium text-gray-900 dark:text-white text-[10px] xs:text-xs leading-tight line-clamp-2 mb-1"
                            >
                                {{ product.name }}
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-[10px] xs:text-xs mb-1"
                            >
                                {{ product.category.name }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-xs sm:text-sm font-bold text-red-600 dark:text-red-400"
                                    >
                                        {{ product.symbol }}
                                        {{ product.format_price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-[10px] xs:text-xs text-gray-500 dark:text-gray-400 line-through ml-1"
                                        >{{ product.symbol
                                        }}{{ product.originalPrice }}</span
                                    >
                                </div>
                                <button
                                    class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-1 sm:p-1.5 rounded shadow-sm"
                                    @click.stop="addToCart(product)"
                                >
                                    <i class="fas fa-shopping-cart text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4 sm:mt-6">
                    <button
                        @click="goTo($page.props.routes.search)"
                        class="bg-blue-600 text-white font-bold py-1.5 px-4 sm:py-2 sm:px-6 rounded-lg shadow text-xs sm:text-sm"
                    >
                        {{ __("Load More Products") }}
                        <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-8 sm:py-12 bg-blue-600 dark:bg-blue-900 text-white">
            <div class="container mx-auto px-3 sm:px-4 text-center">
                <h2
                    class="text-sm sm:text-base md:text-xl font-bold mb-2 sm:mb-3"
                >
                    {{ __("Can't find what you're looking for?") }}
                </h2>
                <p
                    class="text-xs sm:text-sm md:text-base mb-4 sm:mb-6 max-w-3xl mx-auto opacity-90 leading-relaxed"
                >
                    {{
                        __(
                            "Explore our full catalog with thousands of products across all categories"
                        )
                    }}
                </p>
                <button
                    @click="goTo($page.props.routes.search)"
                    class="bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 font-bold py-1.5 px-4 sm:py-2 sm:px-6 rounded-lg shadow text-xs sm:text-sm inline-flex items-center"
                >
                    {{ __("Browse All Products") }}
                    <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                </button>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { usePage } from "@inertiajs/vue3";
import VHeader from "@Ecommerce/components/VHeader.vue";

// Globales
const page = usePage();
const $server = window.$server;
const $notify = window.$notify;

// STATE
const currentSlide = ref(0);
const carouselInterval = ref(null);

const carouselSlides = ref([]);
const featuredCategories = ref([]);
const featuredProducts = ref([]);
const randomProducts = ref([]);
const latest_products = ref([]);
const latest_seller = ref([]);

// ACTIONS
const nextSlide = () => {
    if (!carouselSlides.value.length) return;
    currentSlide.value = (currentSlide.value + 1) % carouselSlides.value.length;
};

const prevSlide = () => {
    if (!carouselSlides.value.length) return;
    currentSlide.value =
        (currentSlide.value - 1 + carouselSlides.value.length) %
        carouselSlides.value.length;
};

const goTo = (link) => {
    if (link) window.location.href = link;
};

const addToCart = (product) => {
    // pendiente
};

// API REQUESTS
const getFeaturedProducts = async () => {
    try {
        const res = await $server.get(page.props.api.search, {
            params: { per_page: 20, random: true, featured: true },
        });

        if (res.status === 200) {
            carouselSlides.value = res.data.data;
            featuredProducts.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const getProducts = async () => {
    try {
        const res = await $server.get(page.props.api.search, {
            params: { per_page: 100, random: true },
        });

        if (res.status === 200) {
            randomProducts.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const getLatestProducts = async () => {
    try {
        const res = await $server.get(page.props.api.search, {
            params: { per_page: 20, random: true, latest: 30 },
        });

        if (res.status === 200) {
            latest_products.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const getLatestSeller = async () => {
    try {
        const res = await $server.get(page.props.api.search, {
            params: { per_page: 30, random: true, latest_seller: true },
        });

        if (res.status === 200) {
            latest_seller.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const getFeaturedCategories = async () => {
    try {
        const res = await $server.get(page.props.api.categories, {
            params: { featured: true, per_page: 10, random: true },
        });

        if (res.status === 200) {
            featuredCategories.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

// LIFECYCLE
onMounted(async () => {
    // Load initial data
    await getFeaturedProducts();
    getFeaturedCategories();

    // Load other data after initial render
    setTimeout(() => {
        getProducts();
        getLatestProducts();
        getLatestSeller();
    }, 100);

    carouselInterval.value = setInterval(() => nextSlide(), 5000);
});

onBeforeUnmount(() => {
    if (carouselInterval.value) {
        clearInterval(carouselInterval.value);
    }
});
</script>

<style scoped>
.carousel-transition {
    transition: transform 0.3s ease;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Breakpoints personalizados para xs */
@media (min-width: 375px) {
    .xs\:text-xs {
        font-size: 0.75rem;
    }

    .xs\:max-w-\[200px\] {
        max-width: 200px;
    }
}
</style>
