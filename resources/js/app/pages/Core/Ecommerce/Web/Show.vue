<template>
    <div class="min-h-screen">
        <v-header />

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <!-- Breadcrumbs -->
            <div class="flex items-center text-sm text-gray-500 mb-6">
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
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                    <!-- Product Images -->
                    <div>
                        <div
                            class="relative bg-gray-100 rounded-2xl p-6 mb-4 overflow-hidden"
                        >
                            <div v-if="product.discount" class="discount-badge">
                                {{ product.discount }}% OFF
                            </div>
                            <img
                                v-if="product?.images?.length"
                                :src="product?.images[selectedImageIndex].url"
                                :alt="product.name"
                                class="w-full h-80 object-contain zoom-effect"
                            />
                            <button
                                @click="toggleZoom"
                                class="absolute top-4 right-4 bg-white p-2 rounded-full shadow-md text-gray-600 hover:text-indigo-600"
                            >
                                <i
                                    :class="[
                                        isZoomed
                                            ? 'fas fa-search-minus'
                                            : 'fas fa-search-plus',
                                    ]"
                                ></i>
                            </button>
                        </div>
                        <div class="flex space-x-3 overflow-x-auto pb-2">
                            <div
                                v-for="(image, index) in product?.images"
                                :key="index"
                                @click="selectedImageIndex = index"
                                :class="[
                                    'cursor-pointer p-1 rounded-lg transition-all duration-200 transform hover:scale-105',
                                    selectedImageIndex === index
                                        ? 'border-2 border-indigo-500'
                                        : 'border border-gray-200',
                                ]"
                            >
                                <img
                                    :src="image.url"
                                    class="h-16 w-16 object-contain rounded-md"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <h1 class="text-3xl font-bold text-gray-900">
                                {{ product.name }}
                            </h1>
                        </div>

                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400 text-sm">
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
                            <span class="ml-2 text-gray-600"
                                >({{ 3 }} {{ __("reviews") }})</span
                            >
                            <span class="mx-2 text-gray-300"
                                >| {{ product.stock }}
                            </span>
                            <span
                                class="text-green-600 font-medium flex items-center"
                            >
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ __("In stock") }}
                            </span>
                        </div>

                        <div
                            class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl"
                        >
                            <div class="flex items-end">
                                <span
                                    class="text-3xl font-bold text-indigo-600"
                                >
                                    {{ product.symbol }}
                                    {{ product.format_price }}
                                </span>
                                <span
                                    v-if="product.originalPrice"
                                    class="ml-2 text-lg text-gray-500 line-through"
                                >
                                    {{ product.originalPrice }}
                                </span>
                            </div>
                        </div>

                        <p
                            class="text-gray-700 mb-6 leading-relaxed"
                            v-html="product.short_description"
                        ></p>

                        <div
                            v-for="(attribute, index) in product.attributes"
                            :key="index"
                            class="mb-6"
                        >
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-3"
                            >
                                {{ __(attribute.name) }}:
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                <button
                                    v-for="(
                                        value, valueIndex
                                    ) in attribute.value"
                                    :key="valueIndex"
                                    @click="form.attrs[attribute.slug] = value"
                                    :class="[
                                        'px-4 py-2 rounded-lg border font-medium transition-all cursor-pointer',
                                        form.attrs[attribute.slug] === value
                                            ? 'bg-indigo-100 text-indigo-700 border-indigo-300 scale-105'
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-300',
                                    ]"
                                >
                                    {{ __(value) }}
                                </button>
                            </div>
                            <v-error :error="errors.attrs" />
                        </div>

                        <!-- Quantity Selector -->
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <span class="mr-4 text-gray-900 font-medium"
                                    >{{ __("Quantity") }}:</span
                                >
                                <div
                                    class="flex items-center border border-gray-300 rounded-lg overflow-hidden"
                                >
                                    <button
                                        @click="decreaseQuantity"
                                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition"
                                    >
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span
                                        class="px-4 py-2 border-l border-r border-gray-300 w-12 text-center"
                                        >{{ form.quantity }}</span
                                    >
                                    <button
                                        @click="increaseQuantity"
                                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition"
                                    >
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <v-error :error="errors.quantity" />

                            <div
                                class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4"
                            >
                                <button
                                    @click="addToCart"
                                    class="add-to-cart-btn flex-1 text-white py-3 px-6 rounded-lg font-medium flex items-center justify-center shadow-md"
                                >
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    {{ __("Add to Cart") }}
                                </button>
                                <button
                                    @click="buyNow"
                                    class="buy-now-btn flex-1 text-white py-3 px-6 rounded-lg font-medium flex items-center justify-center shadow-md"
                                >
                                    <i class="fas fa-bolt mr-2"></i>
                                    {{ __("Buy Now") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details Tabs -->
                <div class="border-t border-gray-200 mt-8">
                    <div class="px-8">
                        <nav class="flex space-x-8">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="activeTab = tab.id"
                                :class="[
                                    'tab-button py-4 px-1 font-medium text-sm transition',
                                    activeTab === tab.id
                                        ? 'active text-indigo-600'
                                        : 'text-gray-500 hover:text-gray-700',
                                ]"
                            >
                                {{ __(tab.name) }}
                            </button>
                        </nav>
                    </div>

                    <div class="px-8 pb-8">
                        <!-- Description -->
                        <div
                            v-if="activeTab === 'description'"
                            class="prose max-w-none"
                        >
                            <p
                                class="text-gray-700 leading-relaxed mb-4"
                                v-html="product.description"
                            ></p>
                        </div>

                        <!-- Specifications -->
                        <div
                            v-if="activeTab === 'specifications'"
                            class="grid grid-cols-1 md:grid-cols-2 gap-4"
                        >
                            <div class="flex border-b border-gray-100 py-3">
                                <span
                                    class="text-gray-600"
                                    v-html="product.specification"
                                >
                                </span>
                            </div>
                        </div>

                        <!-- Reviews 
                        <div v-if="activeTab === 'reviews'">
                            <div
                                class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6"
                            >
                                <div>
                                    <h3
                                        class="text-xl font-semibold text-gray-900"
                                    >
                                        Customer Reviews
                                    </h3>
                                    <div class="flex items-center mt-2">
                                        <div class="flex text-yellow-400 mr-2">
                                            <i
                                                v-for="star in 5"
                                                :key="star"
                                                :class="[
                                                    star <= product.rating
                                                        ? 'fas fa-star'
                                                        : 'far fa-star',
                                                ]"
                                            ></i>
                                        </div>
                                        <span class="text-gray-600"
                                            >{{ product.rating }} out of 5 ({{
                                                product.reviewCount
                                            }}
                                            reviews)</span
                                        >
                                    </div>
                                </div>
                                <button
                                    class="mt-4 md:mt-0 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition"
                                >
                                    Write a Review
                                </button>
                            </div>

                            <div class="space-y-6">
                                <div
                                    v-for="review in product.reviews"
                                    :key="review.id"
                                    class="border-b border-gray-200 pb-6"
                                >
                                    <div
                                        class="flex justify-between items-start mb-2"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                class="flex text-yellow-400 mr-4 text-sm"
                                            >
                                                <i
                                                    v-for="star in 5"
                                                    :key="star"
                                                    :class="[
                                                        star <= review.rating
                                                            ? 'fas fa-star'
                                                            : 'far fa-star',
                                                    ]"
                                                ></i>
                                            </div>
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ review.author }}</span
                                            >
                                        </div>
                                        <span class="text-sm text-gray-500">{{
                                            review.date
                                        }}</span>
                                    </div>
                                    <p class="text-gray-700 mb-2">
                                        {{ review.comment }}
                                    </p>
                                    <div
                                        class="flex items-center text-sm text-gray-500"
                                    >
                                        <span class="flex items-center mr-4">
                                            <i
                                                class="far fa-thumbs-up mr-1"
                                            ></i>
                                            Helpful ({{ review.helpful }})
                                        </span>
                                        <span class="flex items-center">
                                            <i class="far fa-comment mr-1"></i>
                                            Reply
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __("Related Products") }}
                    </h2>
                    <a
                        :href="$page.props.routes.search"
                        class="text-indigo-600 hover:text-indigo-800 flex items-center"
                    >
                        {{ __("View all") }}
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="relatedProduct in relatedProducts"
                        :key="relatedProduct.id"
                        class="product-card bg-white rounded-xl shadow-md overflow-hidden cursor-pointer hover:bg-black/20"
                        @click="goTo(product?.links?.show)"
                    >
                        <div
                            class="h-48 bg-gray-100 flex items-center justify-center p-4 relative"
                        >
                            <img
                                :src="relatedProduct.images[0].url"
                                :alt="relatedProduct.name"
                                class="h-40 object-contain"
                            />
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 mb-2 truncate">
                                {{ relatedProduct.name }}
                            </h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-xs mr-2">
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
                                <span class="text-gray-500 text-sm"
                                    >({{ 3 }})</span
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-indigo-600">
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
            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
            @click="isZoomed = false"
        >
            <div class="max-w-4xl w-full p-4">
                <img
                    :src="product?.images[selectedImageIndex].url"
                    :alt="product.name"
                    class="w-full h-auto rounded-lg"
                />
                <button
                    @click="isZoomed = false"
                    class="absolute top-4 right-4 text-white text-2xl"
                >
                    <i class="fas fa-times"></i>
                </button>
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
                product_id: "",
                attrs: {},
                quantity: 1,
            },
            errors: {},
            status: false,
            selectedImageIndex: 0,
            activeTab: "description",
            isZoomed: false,
            isInWishlist: false,
            showNotification: false,
            selectedAttributes: [],

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

    created() {
        this.getProduct();
    },

    methods: {
        increaseQuantity() {
            if (this.form.quantity <= this.product.stock - 1) {
                this.form.quantity++;
            }
        },

        decreaseQuantity() {
            if (this.form.quantity > 1) {
                this.form.quantity--;
            }
        },

        clean() {
            this.form.product_id = "";
            this.form.attrs = {};
            this.form.quantity = 0;
        },

        goTo(link) {
            window.location.href = link;
        },

        toggleZoom() {
            this.isZoomed = !this.isZoomed;
        },

        async getProduct() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.show_api
                );

                if (res.status === 200) {
                    this.product = res.data.data;

                    this.getRelatedProducts(this.product);
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            }
        },

        async getRelatedProducts(item) {
            try {
                const response = await this.$server.get(
                    this.$page.props.routes.search_api,
                    {
                        params: {
                            random: true,
                            per_page: 30,
                            tags: item.tags.map((tag) => tag.name).join(","),
                        },
                    }
                );

                if (response.status === 200) {
                    this.relatedProducts = response.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            }
        },

        async addToCart() {
            this.form.product_id = this.product.id;
            try {
                const res = await this.$server.post(
                    this.$page.props.routes.orders_api,
                    this.form
                );
                if (res.status == 201) {
                    this.errors = {};
                    this.clean();
                    this.$notify.success(
                        this.__(":name added to cart", {
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
                    this.$notify.error(
                        this.__("To continue the process, Please login first")
                    );
                }

                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
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

<style scoped lang="css">
body {
    font-family: "Inter", sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    min-height: 100vh;
}
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
.discount-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
}
.color-option {
    transition: all 0.2s ease;
}
.color-option.selected {
    transform: scale(1.1);
    box-shadow: 0 0 0 3px white, 0 0 0 5px #4f46e5;
}
.add-to-cart-btn {
    background: linear-gradient(135deg, #4f46e5 0%, #7c73e6 100%);
    transition: all 0.3s ease;
}
.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #4338ca 0%, #6d64e6 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
}
.buy-now-btn {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    transition: all 0.3s ease;
}
.buy-now-btn:hover {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
}
.tab-button {
    transition: all 0.2s ease;
}
.tab-button.active {
    border-bottom: 3px solid #4f46e5;
}
.notification {
    animation: slideIn 0.5s ease, fadeOut 0.5s ease 2.5s forwards;
}
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
        visibility: hidden;
    }
}
.zoom-effect {
    transition: transform 0.3s ease;
}
.zoom-effect:hover {
    transform: scale(1.05);
}
</style>
