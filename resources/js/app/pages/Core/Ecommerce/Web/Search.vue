<template>
    <div>
        <!-- Header -->
        <v-header />
        <!-- Main Content -->
        <main class="container-fluid mx-auto p-4">
            <div class="flex flex-col md:flex-row gap-2">
                <!-- Products Section -->
                <v-filters @changed="filters" />

                <div class="flex-1">
                    <!-- Search and Sort Header -->
                    <div
                        class="bg-white rounded-lg shadow p-4 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center"
                    >
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-xl font-bold text-gray-800">
                                {{ __("Products") }}
                            </h1>
                            <p class="text-gray-600">
                                {{ products.length }} {{ __("products found") }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <label for="sort" class="text-gray-600 mr-2">
                                    {{ __("Sort by") }}:
                                </label>
                                <select
                                    id="sort"
                                    v-model="sortBy"
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-200"
                                >
                                    <option value="featured">
                                        {{ __("Featured") }}
                                    </option>
                                    <option value="price-low">
                                        {{ __("Price") }}:
                                        {{ __("Low to High") }}
                                    </option>
                                    <option value="price-high">
                                        {{ __("Price") }}:
                                        {{ __("High to Low") }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <v-loader :loading="loading" />
                    <div v-if="!loading">
                        <!-- Products Grid -->
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                        >
                            <div
                                v-for="product in products"
                                :key="product.id"
                                class="bg-white cursor-pointer rounded-lg shadow overflow-hidden hover:shadow-lg transition"
                                @click="goTo(product?.links?.show)"
                            >
                                <!-- Product Image Slider -->
                                <div class="relative h-48 overflow-hidden">
                                    <div
                                        class="absolute inset-0 flex transition-transform duration-500 ease-in-out"
                                        :style="{
                                            transform: `translateX(-${
                                                currentImageIndex[product.id] *
                                                100
                                            }%)`,
                                        }"
                                    >
                                        <img
                                            v-for="(
                                                img, index
                                            ) in product.images"
                                            :key="index"
                                            :src="img.url"
                                            :alt="product.name"
                                            class="w-full h-full object-cover flex-shrink-0"
                                        />
                                    </div>

                                    <!-- Discount Badge -->
                                    <div
                                        v-if="product.discount"
                                        class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"
                                    >
                                        {{ product.discount }}% OFF
                                    </div>

                                    <!-- Slider Controls -->
                                    <button
                                        @click="prevImage(product.id)"
                                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-1"
                                    >
                                        <i
                                            class="fas fa-chevron-left text-gray-800"
                                        ></i>
                                    </button>
                                    <button
                                        @click="nextImage(product.id)"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-1"
                                    >
                                        <i
                                            class="fas fa-chevron-right text-gray-800"
                                        ></i>
                                    </button>

                                    <!-- Slider Indicators -->
                                    <div
                                        class="absolute bottom-2 left-0 right-0 flex justify-center space-x-1"
                                    >
                                        <div
                                            v-for="(
                                                img, index
                                            ) in product.images"
                                            :key="index"
                                            @click="
                                                setImageIndex(product.id, index)
                                            "
                                            class="w-2 h-2 rounded-full cursor-pointer"
                                            :class="
                                                currentImageIndex[
                                                    product.id
                                                ] === index
                                                    ? 'bg-white'
                                                    : 'bg-white bg-opacity-50'
                                            "
                                        ></div>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="p-4">
                                    <div class="flex justify-between">
                                        <h3
                                            class="font-semibold text-lg text-gray-800"
                                        >
                                            {{ product.name }}
                                        </h3>
                                        <i
                                            class="mdi mdi-star-circle-outline text-yellow-400 text-3xl"
                                        ></i>
                                    </div>
                                    <p class="text-gray-600 text-sm mt-1">
                                        {{ product.category.name }}
                                    </p>

                                    <div
                                        class="flex items-center mt-2"
                                        v-if="product.rating"
                                    >
                                        <div class="flex items-center">
                                            <i
                                                class="fas fa-star text-yellow-400"
                                            ></i>
                                            <span class="text-gray-700 ml-1">{{
                                                product.rating
                                            }}</span>
                                        </div>
                                        <span class="text-gray-400 mx-2"
                                            >|</span
                                        >
                                        <span class="text-gray-500 text-sm"
                                            >{{ product.reviews }} reviews</span
                                        >
                                    </div>

                                    <div class="flex items-center mt-3">
                                        <span
                                            class="text-primary-700 font-bold text-lg"
                                        >
                                            {{ product.symbol }}
                                            {{ product.format_price }}
                                        </span>
                                        <span
                                            v-if="product.originalPrice"
                                            class="text-gray-400 line-through text-sm ml-2"
                                            >${{ product.originalPrice }}</span
                                        >
                                    </div>

                                    <div class="mt-4 flex space-x-2 sm:hidden">
                                        <button
                                            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-100 transition"
                                        >
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Load More Button -->
                        <div
                            class="mt-8 text-center"
                            v-if="products.length > 0"
                        >
                            <v-paginate
                                v-model="search.page"
                                :total-pages="pages.total_pages"
                                @change="searcher"
                            />
                        </div>

                        <!-- No Results Message -->
                        <div
                            v-if="products.length === 0"
                            class="bg-white rounded-lg shadow p-8 text-center"
                        >
                            <i
                                class="fas fa-search text-4xl text-gray-300 mb-4"
                            ></i>
                            <h3 class="text-xl font-semibold text-gray-700">
                                {{ __("No products found") }}
                            </h3>
                            <p class="text-gray-500 mt-2">
                                {{
                                    __(
                                        "Try adjusting your search or filter criteria"
                                    )
                                }}
                            </p>
                            <a
                                :href="$page.props.ecommerce_dashboard.route"
                                class="mt-4 bg-primary-600 block mx-auto cursor-pointer text-white md:w-full lg:w-1/2 px-4 py-2 rounded-lg hover:bg-primary-700 transition text-center"
                            >
                                {{ __("Clear All Filters") }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
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
            products: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 50,
                random: true,
            },
            filter: {},
            loading: true,
        };
    },

    created() {
        this.getProducts();
    },

    watch: {
        sortBy(value) {
            this.sortProducts();
            console.log(value);
        },
    },

    mounted() {
        this.products.forEach((product) => {
            this.$set(this.currentImageIndex, product.id, 0);
        });

        setInterval(() => {
            this.products.forEach((product) => {
                this.nextImage(product.id);
            });
        }, 5000);
    },

    methods: {
        sortProducts() {
            switch (this.sortBy) {
                case "price-low":
                    this.products = this.products.sort(
                        (a, b) => a.price - b.price
                    );
                    break;
                case "price-high":
                    this.products = this.products.sort(
                        (a, b) => b.price - a.price
                    );
                    break;
                case "featured":
                    this.products = this.products.sort((a, b) => {
                        const af = a.featured ? 1 : 0;
                        const bf = b.featured ? 1 : 0;
                        return bf - af;
                    });
                    break;
            }
        },

        filters(event) {
            this.getProducts(event);
        },

        nextImage(productId) {
            const product = this.products.find((p) => p.id === productId);
            if (product) {
                this.currentImageIndex[productId] =
                    (this.currentImageIndex[productId] + 1) %
                    product.images.length;
            }
        },
        prevImage(productId) {
            const product = this.products.find((p) => p.id === productId);
            if (product) {
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

        getParams() {
            return Object.fromEntries(
                new URLSearchParams(window.location.search)
            );
        },

        goTo(url) {
            window.location.href = url;
        },

        async getProducts(filter = {}) {
            this.loading = true;
            const params = { ...this.search, ...this.getParams(), ...filter };

            const queryString = new URLSearchParams(params).toString();
            const newUrl = `${window.location.pathname}?${queryString}`;
            window.history.pushState({}, "", newUrl);

            try {
                const res = await this.$server.get(
                    this.$page.props.routes.search_api,
                    { params }
                );

                if (res.status === 200) {
                    this.products = res.data.data;

                    this.pages = res.data.meta.pagination;
                    this.search.page = res.data.meta.pagination.current_page;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        searcher() {
            const params = { ...this.getParams(), ...this.search };
            const queryString = new URLSearchParams(params).toString();
            const url = `${this.$page.props.routes.search}?${queryString}`;

            window.location.href = url;
        },
    },
};
</script>
