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
    <v-header />
    <!-- Main Content -->
    <main class="w-full max-w-7xl mx-auto px-4 py-4">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Filters Sidebar -->
            <div class="lg:w-64">
                <v-filters @changed="filters" />
            </div>

            <!-- Products Section -->
            <div class="flex-1">
                <!-- Search and Sort Header -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4"
                >
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3"
                    >
                        <div>
                            <h1
                                class="text-base font-bold text-gray-900 dark:text-white mb-1"
                            >
                                {{ __("Products") }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">
                                {{ products.length }}
                                {{ __("products found") }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <label
                                    for="sort"
                                    class="text-gray-700 dark:text-gray-300 font-medium mr-2 text-xs"
                                >
                                    {{ __("Sort by") }}:
                                </label>
                                <select
                                    id="sort"
                                    v-model="sortBy"
                                    class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs min-w-[160px]"
                                >
                                    <option value="featured">
                                        {{ __("Featured") }}
                                    </option>
                                    <option value="price-low">
                                        {{ __("Price: Low to High") }}
                                    </option>
                                    <option value="price-high">
                                        {{ __("Price: High to Low") }}
                                    </option>
                                    <option value="newest">
                                        {{ __("Newest First") }}
                                    </option>
                                    <option value="rating">
                                        {{ __("Highest Rated") }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <v-loader :loading="loading" />

                <div v-if="!loading">
                    <!-- Products Grid -->
                    <div
                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 justify-items-center"
                    >
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="bg-white dark:bg-gray-800 cursor-pointer rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-300 group w-full max-w-[200px]"
                            @click="goTo(product?.web?.show)"
                        >
                            <!-- Product Image Container -->
                            <div
                                class="relative aspect-square overflow-hidden bg-gray-50 dark:bg-gray-700"
                            >
                                <!-- Stock Overlays Centered -->
                                <div
                                    v-if="!product.stock"
                                    class="absolute inset-0 flex items-center justify-center bg-black/50 dark:bg-black/70 z-10"
                                >
                                    <span
                                        class="text-sm font-bold text-white bg-red-500 px-3 py-2 rounded-lg"
                                    >
                                        {{ __("Out of Stock") }}
                                    </span>
                                </div>

                                <div
                                    v-else-if="
                                        product.stock > 0 && product.stock < 10
                                    "
                                    class="absolute top-2 left-2 z-10"
                                >
                                    <span
                                        class="text-xs font-bold px-2 py-1 rounded bg-orange-500 text-white"
                                    >
                                        {{
                                            __(":stock left", {
                                                ":stock": product.stock,
                                            })
                                        }}
                                    </span>
                                </div>

                                <!-- Image Slider -->
                                <div
                                    class="absolute inset-0 flex transition-transform duration-500 ease-in-out"
                                    :style="{
                                        transform: `translateX(-${
                                            currentImageIndex[product.id] * 100
                                        }%)`,
                                    }"
                                >
                                    <div
                                        v-for="(img, index) in product.images"
                                        :key="index"
                                        class="w-full h-full shrink-0 flex items-center justify-center p-2"
                                    >
                                        <img
                                            :src="img.url"
                                            :alt="product.name"
                                            class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
                                        />
                                    </div>
                                </div>

                                <!-- Discount Badge -->
                                <div
                                    v-if="product.discount"
                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded z-10"
                                >
                                    -{{ product.discount }}%
                                </div>

                                <!-- Slider Controls -->
                                <button
                                    @click.stop="prevImage(product.id)"
                                    class="absolute left-1 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full w-6 h-6 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110 z-20"
                                >
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>
                                <button
                                    @click.stop="nextImage(product.id)"
                                    class="absolute right-1 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full w-6 h-6 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110 z-20"
                                >
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>

                                <!-- Slider Indicators -->
                                <div
                                    v-if="product.images.length > 1"
                                    class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1 z-10"
                                >
                                    <div
                                        v-for="(img, index) in product.images"
                                        :key="index"
                                        @click.stop="
                                            setImageIndex(product.id, index)
                                        "
                                        class="w-1.5 h-1.5 rounded-full cursor-pointer transition-all duration-300"
                                        :class="
                                            currentImageIndex[product.id] ===
                                            index
                                                ? 'bg-white dark:bg-gray-200 scale-125'
                                                : 'bg-white/60 dark:bg-gray-200/60 hover:bg-white/80'
                                        "
                                    ></div>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="p-3">
                                <h3
                                    class="font-medium flex justify-between items-center text-gray-900 dark:text-white text-xs leading-tight line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-1"
                                >
                                    {{ product.name }}

                                    <!-- Featured Badge -->
                                    <span
                                        v-show="product.featured"
                                        class="bg-yellow-500/80 text-red-600 text-xs h-8 w-8 flex justify-center items-center rounded-full animate-[spin_2s_linear_infinite] shadow-sm"
                                        aria-hidden="true"
                                    >
                                        <i class="fas fa-star"></i>
                                    </span>
                                </h3>

                                <p
                                    class="text-gray-500 dark:text-gray-400 text-xs mb-2"
                                >
                                    {{ product.category.name }}
                                </p>

                                <!-- Price Section -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-baseline space-x-1">
                                        <span
                                            class="text-sm font-bold text-green-600 dark:text-red-400"
                                        >
                                            {{ product.symbol }}
                                            {{ product.format_price }}
                                        </span>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button
                                        @click.stop="goTo(product?.web?.show)"
                                        class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-1.5 cursor-pointer rounded hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm"
                                    >
                                        <i
                                            class="fas fa-shopping-cart text-xs"
                                        ></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Load More Button -->
                    <div class="mt-6 text-center" v-if="products.length > 0">
                        <v-paginate
                            v-model="search.page"
                            :total-pages="pages.total_pages"
                            @change="changePage"
                        />
                    </div>

                    <!-- No Results Message -->
                    <div
                        v-if="products.length === 0 && !loading"
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center"
                    >
                        <div class="max-w-md mx-auto">
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <i
                                    class="fas fa-search text-xl text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                            <h3
                                class="text-base font-semibold text-gray-900 dark:text-white mb-2"
                            >
                                {{ __("No products found") }}
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs mb-4"
                            >
                                {{
                                    __(
                                        "Try adjusting your search or filter criteria to find what you're looking for."
                                    )
                                }}
                            </p>
                            <button
                                @click="clearFilters"
                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-medium px-4 py-2 rounded transition-colors duration-300 inline-flex items-center text-xs"
                            >
                                <i class="fas fa-times mr-1"></i>
                                {{ __("Clear All Filters") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import VHeader from "../Components/VHeader.vue";
import VPaginate from "@/components/VPaginate.vue";
import VLoader from "../Components/VLoader.vue";
import VFilters from "../Components/VFilters.vue";

export default {
    components: {
        VHeader,
        VPaginate,
        VLoader,
        VFilters,
    },

    data() {
        return {
            products: [],
            priceFilter: "all",
            ratingFilter: 0,
            sortBy: "featured",
            currentImageIndex: {},
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 100,
                random: true,
            },
            loading: true,
        };
    },

    created() {
        const params = {
            ...this.getParams(),
            ...this.search,
        };
        this.getProducts(params);
    },

    watch: {
        sortBy(value) {
            this.sortProducts();
        },
    },

    mounted() {
        // Initialize image indexes for all products
        this.products.forEach((product) => {
            this.currentImageIndex[product.id] = 0;
        });

        this.$nextTick(() => {
            const footer = document.getElementById("footer");
            if (footer) {
                footer.style.display = "block";
            }
        });
    },

    methods: {
        scrollToTop() {
            // Smooth scroll to top of the page
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });

            // Alternative: Scroll to the main content area
            // const mainElement = document.querySelector('main');
            // if (mainElement) {
            //     mainElement.scrollIntoView({ behavior: 'smooth' });
            // }
        },

        sortProducts() {
            switch (this.sortBy) {
                case "price-low":
                    this.products.sort((a, b) => a.price - b.price);
                    break;
                case "price-high":
                    this.products.sort((a, b) => b.price - a.price);
                    break;
                case "featured":
                    this.products.sort((a, b) => {
                        const af = a.featured ? 1 : 0;
                        const bf = b.featured ? 1 : 0;
                        return bf - af;
                    });
                    break;
                case "newest":
                    this.products.sort(
                        (a, b) =>
                            new Date(b.created_at) - new Date(a.created_at)
                    );
                    break;
                case "rating":
                    this.products.sort(
                        (a, b) => (b.rating || 0) - (a.rating || 0)
                    );
                    break;
            }
            // Trigger reactivity
            this.products = [...this.products];
        },

        async filters(event) {
            const params = {
                ...this.search,
                ...event,
            };
            await this.getProducts(params);
            this.scrollToTop();
        },

        async changePage() {
            const params = {
                ...this.getParams(),
                ...this.search,
            };
            await this.getProducts(params);
            // Scroll to top when page changes
            this.scrollToTop();
        },

        nextImage(productId) {
            const product = this.products.find((p) => p.id === productId);
            if (product && product.images.length > 1) {
                this.currentImageIndex[productId] =
                    (this.currentImageIndex[productId] + 1) %
                    product.images.length;
            }
        },

        prevImage(productId) {
            const product = this.products.find((p) => p.id === productId);
            if (product && product.images.length > 1) {
                this.currentImageIndex[productId] =
                    (this.currentImageIndex[productId] -
                        1 +
                        product.images.length) %
                    product.images.length;
            }
        },

        setImageIndex(productId, index) {
            this.currentImageIndex[productId] = index;
        },

        clearFilters() {
            // Clear all filters and refresh
            this.search = {
                page: 1,
                per_page: 150,
                random: true,
            };
            this.sortBy = "featured";
            this.getProducts();
            // Scroll to top when clearing filters
            this.scrollToTop();
        },

        getParams() {
            return Object.fromEntries(
                new URLSearchParams(window.location.search)
            );
        },

        goTo(url) {
            if (url) {
                window.location.href = url;
            }
        },

        async getProducts(params = {}) {
            this.loading = true;
            const queryString = new URLSearchParams(params).toString();
            const newUrl = `${window.location.pathname}?${queryString}`;
            try {
                const res = await this.$server.get(
                    this.$page.props.api.search,
                    {
                        params,
                    }
                );

                if (res.status === 200) {
                    const values = res.data;
                    this.products = values.data;
                    this.pages = values.meta.pagination;

                    // Initialize image indexes for new products
                    this.products.forEach((product) => {
                        if (!(product.id in this.currentImageIndex)) {
                            this.currentImageIndex[product.id] = 0;
                        }
                    });

                    // Apply sorting
                    this.sortProducts();
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
                console.log(e);
            } finally {
                this.loading = false;
                window.history.pushState({}, "", newUrl);
            }
        },
    },
};
</script>
