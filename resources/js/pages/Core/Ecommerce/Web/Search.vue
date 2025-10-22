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
    <main class="w-full xl:w-7xl mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Filters Sidebar -->
            <div class="lg:w-80">
                <v-filters @changed="filters" />
            </div>

            <!-- Products Section -->
            <div class="flex-1">
                <!-- Search and Sort Header -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6"
                >
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
                    >
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-1">
                                {{ __("Products") }}
                            </h1>
                            <p class="text-gray-600 text-sm">
                                {{ products.length }}
                                {{ __("products found") }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <label
                                    for="sort"
                                    class="text-gray-700 font-medium mr-3 text-sm"
                                >
                                    {{ __("Sort by") }}:
                                </label>
                                <select
                                    id="sort"
                                    v-model="sortBy"
                                    class="border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-sm min-w-[180px]"
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
                        class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6"
                    >
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="bg-white cursor-pointer rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group"
                            @click="goTo(product?.links?.show)"
                        >
                            <!-- Product Image Container -->
                            <div
                                class="relative aspect-[4/3] overflow-hidden bg-gray-50"
                            >
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
                                        class="w-full h-full flex-shrink-0 flex items-center justify-center"
                                    >
                                        <img
                                            :src="img.url"
                                            :alt="product.name"
                                            class="w-full h-full object-contain p-2 transition-transform duration-300 group-hover:scale-105"
                                            :class="{
                                                'object-cover': imageNeedsCover(
                                                    img.url
                                                ),
                                            }"
                                        />
                                    </div>
                                </div>

                                <!-- Discount Badge -->
                                <div
                                    v-if="product.discount"
                                    class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg"
                                >
                                    {{ product.discount }}% OFF
                                </div>

                                <!-- Featured Badge -->
                                <div
                                    v-if="product.featured"
                                    class="absolute top-3 right-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg"
                                >
                                    <i class="fas fa-star mr-1"></i>
                                    {{ __("Featured") }}
                                </div>

                                <!-- Slider Controls -->
                                <button
                                    @click.stop="prevImage(product.id)"
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110"
                                >
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </button>
                                <button
                                    @click.stop="nextImage(product.id)"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110"
                                >
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>

                                <!-- Slider Indicators -->
                                <div
                                    v-if="product.images.length > 1"
                                    class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-1.5"
                                >
                                    <div
                                        v-for="(img, index) in product.images"
                                        :key="index"
                                        @click.stop="
                                            setImageIndex(product.id, index)
                                        "
                                        class="w-2 h-2 rounded-full cursor-pointer transition-all duration-300"
                                        :class="
                                            currentImageIndex[product.id] ===
                                            index
                                                ? 'bg-white scale-125'
                                                : 'bg-white/60 hover:bg-white/80'
                                        "
                                    ></div>
                                </div>

                                <!-- Quick Actions Overlay -->
                                <div
                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-all duration-300"
                                ></div>
                            </div>

                            <!-- Product Details -->
                            <div class="p-5">
                                <div
                                    class="flex justify-between items-start mb-2"
                                >
                                    <h3
                                        class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2 group-hover:text-blue-600 transition-colors flex-1 mr-2"
                                    >
                                        {{ product.name }}
                                    </h3>
                                    <i
                                        class="fas fa-heart text-gray-300 hover:text-red-500 transition-colors mt-0.5 flex-shrink-0"
                                    ></i>
                                </div>

                                <p class="text-gray-500 text-xs mb-3">
                                    {{ product.category.name }}
                                </p>

                                <!-- Rating Section -->
                                <div
                                    class="flex items-center mb-3"
                                    v-if="product.rating"
                                >
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 mr-2">
                                            <i
                                                v-for="star in 5"
                                                :key="star"
                                                class="fas fa-star text-xs"
                                                :class="{
                                                    'text-gray-300':
                                                        star > product.rating,
                                                }"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-gray-600 text-xs font-medium"
                                        >
                                            {{ product.rating }}
                                        </span>
                                    </div>
                                    <span class="text-gray-400 mx-2 text-xs"
                                        >•</span
                                    >
                                    <span class="text-gray-500 text-xs">
                                        {{ product.reviews }}
                                        {{ __("reviews") }}
                                    </span>
                                </div>

                                <!-- Price Section -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-baseline space-x-2">
                                        <span
                                            class="text-lg font-bold text-gray-900"
                                        >
                                            {{ product.symbol }}
                                            {{ product.format_price }}
                                        </span>
                                        <span
                                            v-if="product.originalPrice"
                                            class="text-gray-400 line-through text-sm"
                                        >
                                            {{ product.symbol
                                            }}{{ product.originalPrice }}
                                        </span>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button
                                        @click.stop="goTo(product?.links?.show)"
                                        class="bg-blue-50 text-blue-600 p-2 cursor-pointer rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300 transform group-hover:scale-110 shadow-sm"
                                    >
                                        <i
                                            class="fas fa-shopping-cart text-sm"
                                        ></i>
                                    </button>
                                </div>

                                <!-- Stock Status -->
                                <div v-if="product.stock_status" class="mt-3">
                                    <span
                                        class="text-xs font-medium px-2 py-1 rounded-full"
                                        :class="
                                            product.stock_status === 'in_stock'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700'
                                        "
                                    >
                                        {{ __(product.stock_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Load More Button -->
                    <div class="mt-12 text-center" v-if="products.length > 0">
                        <v-paginate
                            v-model="search.page"
                            :total-pages="pages.total_pages"
                            @change="getProducts"
                        />
                    </div>

                    <!-- No Results Message -->
                    <div
                        v-if="products.length === 0 && !loading"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center"
                    >
                        <div class="max-w-md mx-auto">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6"
                            >
                                <i
                                    class="fas fa-search text-3xl text-gray-400"
                                ></i>
                            </div>
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-3"
                            >
                                {{ __("No products found") }}
                            </h3>
                            <p class="text-gray-500 mb-6">
                                {{
                                    __(
                                        "Try adjusting your search or filter criteria to find what you're looking for."
                                    )
                                }}
                            </p>
                            <button
                                @click="clearFilters"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-xl transition-colors duration-300 inline-flex items-center"
                            >
                                <i class="fas fa-times mr-2"></i>
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
import VPaginate from "../Components/VPaginate.vue";
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
                per_page: 50,
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
            this.$set(this.currentImageIndex, product.id, 0);
        });
    },

    methods: {
        imageNeedsCover(imageUrl) {
            return false;
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

        filters(event) {
            const params = {
                ...this.search,
                ...event,
            };
            this.getProducts(params);
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
                per_page: 50,
                random: true,
            };
            this.sortBy = "featured";
            this.getProducts();
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
                    this.$page.props.routes.search_api,
                    { params }
                );

                if (res.status === 200) {
                    this.products = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.search.page = res.data.meta.pagination.current_page;

                    // Initialize image indexes for new products
                    this.products.forEach((product) => {
                        if (!this.currentImageIndex[product.id]) {
                            this.$set(this.currentImageIndex, product.id, 0);
                        }
                    });

                    // Apply sorting
                    this.sortProducts();
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
                window.history.pushState({}, "", newUrl);
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

.aspect-\[4\/3\] {
    aspect-ratio: 4/3;
}

/* Responsive grid adjustments */
@media (min-width: 1536px) {
    .grid {
        grid-template-columns: repeat(5, 1fr);
    }
}

@media (min-width: 1280px) and (max-width: 1535px) {
    .grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1024px) and (max-width: 1279px) {
    .grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 768px) and (max-width: 1023px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 480px) and (max-width: 767px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
