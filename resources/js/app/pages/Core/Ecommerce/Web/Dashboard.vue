<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <v-header />
        <!-- Hero Carousel -->
        <section class="relative overflow-hidden">
            <div
                class="flex carousel-transition"
                :style="`transform: translateX(-${currentSlide * 100}%)`"
            >
                <div
                    v-for="(slide, index) in carouselSlides"
                    :key="index"
                    class="min-w-full flex-shrink-0"
                >
                    <div class="gradient-bg text-white">
                        <div class="container mx-auto px-4 py-16 md:py-24">
                            <div class="flex flex-col md:flex-row items-center">
                                <div class="md:w-1/2 mb-8 md:mb-0">
                                    <h2
                                        class="text-4xl md:text-5xl font-bold mb-4"
                                    >
                                        {{ slide.name }}
                                    </h2>
                                    <p
                                        class="text-xl mb-6"
                                        v-html="slide.description"
                                    ></p>
                                    <button
                                        class="bg-white text-purple-700 font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-gray-100 transition duration-300"
                                        @click.stop="goTo(slide?.links?.show)"
                                    >
                                        {{ __("Shop Now") }}
                                    </button>
                                </div>
                                <div class="md:w-1/2 flex justify-center">
                                    <img
                                        :src="slide.images[0].url"
                                        :alt="slide.name"
                                        class="rounded-lg shadow-xl w-full max-w-md"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button
                @click="prevSlide"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md transition-all"
            >
                <i class="fas fa-chevron-left"></i>
            </button>
            <button
                @click="nextSlide"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md transition-all"
            >
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Navigation Dots -->
            <div
                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2"
            >
                <button
                    v-for="(slide, index) in carouselSlides"
                    :key="index"
                    @click="currentSlide = index"
                    class="w-3 h-3 rounded-full transition-all"
                    :class="currentSlide === index ? 'bg-white' : 'bg-white/50'"
                ></button>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-8">
                    {{ __("Featured Categories") }}
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div
                        v-for="category in featuredCategories"
                        :key="category.id"
                        class="category-card relative rounded-xl overflow-hidden shadow-md group cursor-pointer"
                        @click="goTo(category.links.index)"
                    >
                        <img
                            :src="category.images[0].url"
                            :alt="category.name"
                            class="w-full h-40 object-cover"
                        />
                        <div
                            class="category-overlay absolute inset-0 bg-black/40 opacity-20 transition-opacity duration-300 flex items-center justify-center"
                        >
                            <h3 class="text-white text-xl font-semibold">
                                {{ category.name }}
                            </h3>
                        </div>
                        <div class="p-4 bg-white">
                            <h3 class="font-semibold text-center">
                                {{ category.name }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- New Arrivals -->
        <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold">{{ __("New Arrivals") }}</h2>
                    <a
                        href="#"
                        @click="goTo($page.props.routes.search)"
                        class="text-purple-600 hover:text-purple-800 font-medium flex items-center"
                    >
                        {{ __("View all") }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 cursor-pointer"
                >
                    <div
                        v-for="product in newProducts"
                        :key="product.id"
                        class="product-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300"
                        @click="goTo(product.links.show)"
                    >
                        <div class="relative">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-48 object-cover"
                            />
                            <div
                                class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded"
                            >
                                {{ __("NEW") }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">
                                {{ product.name }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-2">
                                {{ product.category.name }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-purple-600">
                                    {{ product.symbol }}
                                    {{ product.format_price }}
                                </span>
                                <button
                                    class="bg-purple-100 text-purple-600 p-2 rounded-full hover:bg-purple-600 hover:text-white transition-colors"
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
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold">{{ __("Best Sellers") }}</h2>
                    <a
                        href="#"
                        @click="goTo($page.props.routes.search)"
                        class="text-purple-600 hover:text-purple-800 font-medium flex items-center"
                    >
                        {{ __("View all") }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in bestSellers"
                        :key="product.id"
                        class="product-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 cursor-pointer"
                        @click="goTo(product.links.show)"
                    >
                        <div class="relative">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-48 object-cover"
                            />
                            <div class="best-seller-badge">
                                <i class="fas fa-crown mr-1"></i>
                                {{ __("Best Seller") }}
                            </div>
                            <div
                                v-if="product.discount"
                                class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"
                            >
                                -{{ product.discount }}%
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">
                                {{ product.name }}
                            </h3>
                            <div class="flex items-center mb-2">
                                <div
                                    class="flex text-yellow-400"
                                    v-if="product.rating"
                                >
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        class="fas fa-star"
                                        :class="{
                                            'text-gray-300':
                                                star > product.rating,
                                        }"
                                    ></i>
                                </div>
                                <span
                                    v-if="product.reviews"
                                    class="text-gray-500 text-sm ml-2"
                                    >({{ product.reviews }})</span
                                >
                            </div>
                            <p
                                v-if="product.soldCount"
                                class="text-gray-500 text-sm mb-2"
                            >
                                {{ product.soldCount }} {{ __("sold") }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-lg font-bold text-purple-600"
                                    >
                                        {{ product.symbol }}
                                        {{ product.format_price }}</span
                                    >
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-sm text-gray-500 line-through ml-2"
                                        >${{ product.originalPrice }}</span
                                    >
                                </div>
                                <button
                                    class="bg-purple-100 text-purple-600 p-2 rounded-full hover:bg-purple-600 hover:text-white transition-colors"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-12 bg-gray-100">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold">
                        {{ __("Featured Products") }}
                    </h2>
                    <a
                        href="#"
                        @click="goTo($page.props.routes.search)"
                        class="text-purple-600 hover:text-purple-800 font-medium flex items-center"
                    >
                        {{ __("View all") }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in featuredProducts"
                        :key="product.id"
                        class="product-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 cursor-pointer"
                        @click="goTo(product.links.show)"
                    >
                        <div class="relative">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-48 object-cover"
                            />
                            <div
                                v-if="product.discount"
                                class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"
                            >
                                -{{ product.discount }}%
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">
                                {{ product.name }}
                            </h3>
                            <div
                                class="flex items-center mb-2"
                                v-if="product.reviews"
                            >
                                <div class="flex text-yellow-400">
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        class="fas fa-star"
                                        :class="{
                                            'text-gray-300':
                                                star > product.rating,
                                        }"
                                    ></i>
                                </div>
                                <span class="text-gray-500 text-sm ml-2"
                                    >({{ product.reviews }})</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-lg font-bold text-purple-600"
                                    >
                                        {{ product.symbol }}
                                        {{ product.format_price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-sm text-gray-500 line-through ml-2"
                                        >${{ product.originalPrice }}</span
                                    >
                                </div>
                                <button
                                    class="bg-purple-100 text-purple-600 p-2 rounded-full hover:bg-purple-600 hover:text-white transition-colors"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Random Products -->
        <section class="py-12 random-products">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-8">
                    {{ __("You Might Also Like") }}
                </h2>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in randomProducts"
                        :key="product.id"
                        class="product-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 cursor-pointer"
                        @click="goTo(product.links.show)"
                    >
                        <div class="relative">
                            <img
                                :src="product.images[0].url"
                                :alt="product.name"
                                class="w-full h-48 object-cover"
                            />
                            <div
                                v-if="product.discount"
                                class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"
                            >
                                -{{ product.discount }}%
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">
                                {{ product.name }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-2">
                                {{ product.category.name }}
                            </p>
                            <div
                                class="flex items-center mb-2"
                                v-if="product.reviews"
                            >
                                <div class="flex text-yellow-400">
                                    <i
                                        v-for="star in 5"
                                        :key="star"
                                        class="fas fa-star"
                                        :class="{
                                            'text-gray-300':
                                                star > product.rating,
                                        }"
                                    ></i>
                                </div>
                                <span class="text-gray-500 text-sm ml-2"
                                    >({{ product.reviews }})</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-lg font-bold text-purple-600"
                                    >
                                        {{ product.symbol }}
                                        {{ product.format_price }}
                                    </span>
                                    <span
                                        v-if="product.originalPrice"
                                        class="text-sm text-gray-500 line-through ml-2"
                                        >${{ product.originalPrice }}</span
                                    >
                                </div>
                                <button
                                    class="bg-purple-100 text-purple-600 p-2 rounded-full hover:bg-purple-600 hover:text-white transition-colors"
                                >
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-8">
                    <button
                        @click="goTo($page.props.routes.search)"
                        class="bg-purple-600 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-purple-700 transition duration-300"
                    >
                        {{ __("Load More Products") }}
                    </button>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-16 bg-purple-700 text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    {{ __("Can't find what you're looking for?") }}
                </h2>
                <p class="text-xl mb-8 max-w-2xl mx-auto">
                    {{
                        __(
                            "Explore our full catalog with thousands of products across all categories"
                        )
                    }}
                </p>
                <button
                    @click="goTo($page.props.routes.search)"
                    class="bg-white text-purple-700 font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-gray-100 transition duration-300"
                >
                    {{ __("Continue Shopping") }}
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
        };
    },

    created() {
        this.getFeaturedCategories();
        this.getProducts();
        this.getFeaturedProducts();
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
                    this.$notify.error(e.response.data.message);
                }
            }
        },

        async getProducts() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.search_api,
                    {
                        params: {
                            per_page: 150,
                            random: true,
                        },
                    }
                );

                if (res.status == 200) {
                    this.newProducts = res.data.data;
                    this.bestSellers = res.data.data;
                    this.randomProducts = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
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
                            random: true,
                            per_page: 10,
                        },
                    }
                );

                if (res.status == 200) {
                    this.featuredCategories = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            }
        },
    },
    mounted() {
        this.carouselInterval = setInterval(() => {
            this.nextSlide();
        }, 5000);
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
    transition: transform 0.5s ease-in-out;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}
.category-card:hover .category-overlay {
    opacity: 1;
}
.gradient-bg {
    background: linear-gradient(120deg, #6b46c1 0%, #805ad5 100%);
}
.best-seller-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(to right, #f59e0b, #ef4444);
    color: white;
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}
.random-products {
    background: linear-gradient(to bottom, #f9fafb, #e5e7eb);
}
</style>
