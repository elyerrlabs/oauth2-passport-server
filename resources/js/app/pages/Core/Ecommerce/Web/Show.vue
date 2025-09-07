<template>
    <v-ecommerce>
        <div class="q-pa-sm q-pa-md-lg">
            <!-- Breadcrumbs - Stack on mobile -->
            <q-breadcrumbs class="q-mb-sm q-mb-md-md" active-color="primary">
                <q-breadcrumbs-el :label="__('Home')" icon="home" to="/" />
                <q-breadcrumbs-el
                    :label="product?.category?.name"
                    :to="product?.category?.links?.index"
                    class="ellipsis"
                />
                <q-breadcrumbs-el :label="product?.name" class="ellipsis" />
            </q-breadcrumbs>

            <!-- Product Detail Section - Stack on mobile -->
            <div class="row q-col-gutter-xl">
                <!-- Product Images - Full width on mobile -->
                <div class="col-md-7 col-12">
                    <!-- Main Carousel - Smaller on mobile -->
                    <q-carousel
                        animated
                        v-model="productImageSlide"
                        arrows
                        navigation
                        infinite
                        :height="$q.screen.lt.sm ? '300px' : '500px'"
                        class="rounded-borders q-mb-sm shadow-1"
                    >
                        <q-carousel-slide
                            v-for="(image, index) in product?.images"
                            :key="`product-image-${index}`"
                            :name="index"
                        >
                            <q-img
                                :src="image.url"
                                loading="lazy"
                                style="height: 100%; width: 100%"
                                class="rounded-borders"
                                fit="contain"
                            >
                                <template v-slot:loading>
                                    <div
                                        class="absolute-full flex flex-center bg-grey-1"
                                    >
                                        <q-spinner color="primary" size="3em" />
                                    </div>
                                </template>
                            </q-img>
                        </q-carousel-slide>
                    </q-carousel>

                    <!-- Thumbnail Gallery - Horizontal scroll on mobile -->
                    <div
                        class="row no-wrap scroll q-gutter-sm q-pb-sm"
                        v-if="product?.images?.length > 1"
                        style="overflow-x: auto"
                    >
                        <div
                            class="col-auto"
                            v-for="(image, index) in product.images"
                            :key="`product-thumb-${index}`"
                        >
                            <q-img
                                :src="image.url"
                                width="80px"
                                height="80px"
                                class="cursor-pointer rounded-borders"
                                :class="{
                                    'product-image-thumb-active':
                                        productImageSlide === index,
                                }"
                                @click="productImageSlide = index"
                            />
                        </div>
                    </div>
                </div>

                <!-- Product Info - Full width on mobile -->
                <div class="col-md-5 col-12">
                    <div class="text-h4 text-weight-bold q-mb-xs q-mb-sm-sm">
                        {{ product?.name }}
                    </div>

                    <!-- Rating/Stock - Stack on mobile -->
                    <div class="row items-center q-mb-md">
                        <div class="col-auto">
                            <q-rating
                                v-model="rating"
                                size="1.5em"
                                color="orange"
                                readonly
                            />
                        </div>
                        <div class="col-auto q-ml-sm text-caption text-grey-7">
                            ({{ product?.reviews_count || 0 }}
                            {{ __("reviews") }})
                        </div>
                        <div
                            class="col-auto q-ml-sm-md text-caption"
                            :class="product?.stock ? 'text-green' : 'text-red'"
                        >
                            <q-icon
                                :name="
                                    product?.stock ? 'check_circle' : 'cancel'
                                "
                                size="xs"
                            />
                            {{
                                product?.stock
                                    ? __("In Stock") + `(${product.stock})`
                                    : __("Out of Stock")
                            }}
                        </div>
                    </div>

                    <!-- Price Section - Stack on mobile -->
                    <div class="row items-center q-mb-md">
                        <div class="text-h4 text-primary text-weight-bold">
                            {{ product?.symbol }}
                            {{ product?.format_price }}
                        </div>
                        <div
                            class="text-subtitle2 text-grey-7 q-ml-md text-strike"
                            v-if="product?.original_price"
                        >
                            {{ product?.symbol }}
                            {{ product?.original_price }}
                        </div>
                        <q-badge
                            color="red"
                            class="q-ml-md"
                            v-if="product?.discount"
                        >
                            {{ __("Save") }} {{ product?.discount }}%
                        </q-badge>
                    </div>

                    <!-- Short Description - Hidden on mobile if too long -->
                    <div
                        class="text-subtitle2 text-grey-8 q-mb-md"
                        :class="{ 'ellipsis-3-lines': $q.screen.lt.sm }"
                    >
                        <div v-html="product?.short_description"></div>
                    </div>

                    <!-- Color Options - Wrap on mobile -->
                    <div
                        class="q-mb-lg"
                        v-for="(attribute, index) in product?.attributes"
                        :key="`attribute-${index}`"
                    >
                        <div class="text-subtitle2 q-mb-xs">
                            {{ attribute.name }}:
                            <span class="text-weight-medium">
                                {{ form.attrs[attribute.slug] || __("Select") }}
                            </span>
                        </div>
                        <div class="row q-gutter-xs">
                            <q-btn
                                v-for="(value, valueIndex) in attribute.value"
                                :key="`attribute-value-${valueIndex}`"
                                :label="value"
                                outline
                                :color="
                                    form.attrs[attribute.slug] === value
                                        ? 'primary'
                                        : 'grey-7'
                                "
                                size="sm"
                                @click="form.attrs[attribute.slug] = value"
                                class="text-capitalize"
                            />
                        </div>
                        <v-error :error="errors.attrs" />
                    </div>

                    <!-- Quantity - Stack on mobile -->
                    <div class="row items-center q-mb-lg">
                        <div
                            class="col-12 col-sm-auto text-subtitle2 q-mr-sm-md q-mb-xs-sm"
                        >
                            {{ __("Quantity:") }}
                        </div>
                        <div class="col-auto">
                            <q-input
                                v-model="form.quantity"
                                type="number"
                                outlined
                                dense
                                style="width: 100px"
                                :min="1"
                                :max="product?.stock || 10"
                            />
                        </div>
                        <div
                            class="col-12 col-sm-auto text-caption text-grey q-ml-sm-md q-mt-xs-sm"
                        >
                            {{ __("Max") }} {{ product?.stock || 10 }}
                            {{ __("per customer") }}
                        </div>
                        <div class="col-12">
                            <v-error :error="errors.quantity" />
                        </div>
                    </div>

                    <!-- Action Buttons - Stack on mobile -->
                    <div class="row q-gutter-sm q-mb-lg">
                        <q-btn
                            color="primary"
                            icon="shopping_cart"
                            :label="__('Add to Cart')"
                            class="col-sm col-12"
                            size="lg"
                            @click="addToCart"
                            :disable="!product?.stock"
                        />

                        <q-btn
                            color="orange"
                            icon="flash_on"
                            label="Buy Now"
                            class="col-sm col-12"
                            size="lg"
                            @click="buyNow"
                            :disable="!product?.stock"
                        />
                        <!--
                            <q-btn
                            flat
                            round
                            color="red"
                            :icon="
                            isInWishlist ? 'favorite' : 'favorite_border'
                            "
                            size="lg"
                            @click="toggleWishlist"
                            class="col-auto"
                            />
                            -->
                    </div>

                    <!-- Tags - Wrap on mobile -->
                    <div
                        class="row q-gutter-xs q-mb-md"
                        v-if="product?.tags?.length"
                    >
                        <q-chip
                            v-for="tag in product.tags"
                            :key="`tag-${tag.id}`"
                            color="grey-2"
                            text-color="grey-8"
                            size="sm"
                            class="cursor-pointer"
                            clickable
                            @click="goTo(tag.slug)"
                        >
                            #{{ tag.name }}
                        </q-chip>
                    </div>
                </div>
            </div>

            <!-- Product Tabs - Center on mobile -->
            <q-tabs
                v-model="productDetailTab"
                :align="$q.screen.gt.xs ? 'left' : 'center'"
                active-color="primary"
                indicator-color="primary"
                class="text-grey-8 q-mb-lg"
                dense
            >
                <q-tab
                    name="description"
                    icon="description"
                    :label="__('Description')"
                />
                <q-tab
                    name="specs"
                    icon="list_alt"
                    :label="__('Specification')"
                />
            </q-tabs>

            <q-tab-panels v-model="productDetailTab" animated class="q-mb-xl">
                <!-- Description Tab -->
                <q-tab-panel name="description">
                    <div class="text-body1" v-html="product?.description"></div>
                </q-tab-panel>

                <!-- Specifications Tab -->
                <q-tab-panel name="specs">
                    <div v-html="product?.specification"></div>
                </q-tab-panel>
            </q-tab-panels>

            <!-- Related Products - Smaller cards on mobile -->
            <v-products
                :title="__('Related Products')"
                :products="related_products"
            />
        </div>
    </v-ecommerce>
</template>

<script>
export default {
    data() {
        return {
            form: {
                product_id: "",
                attrs: {},
                quantity: 1,
            },
            errors: {},
            status: false,

            product: {},
            related_products: [],

            // UI State
            productImageSlide: 0,
            productDetailTab: this.__("description"),
            selectedAttributes: {},

            showReviewDialog: false,
            showQuestionDialog: false,
            isInWishlist: false,
            rating: 3,
            tags: [],
            // Form Data
            newReview: {
                rating: 5,
                title: "",
                comment: "",
            },
            newQuestion: {
                text: "",
            },

            // Mock questions (replace with API data)
            productQuestions: [],
        };
    },

    created() {
        this.getProduct();
    },

    methods: {
        clean() {
            this.form.product_id = "";
            this.form.attrs = {};
            this.form.quantity = 0;
        },

        goTo(tag) {
            window.location.href = `${this.product?.links?.search}?q=${tag}`;
        },

        async getProduct() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["show_api"]
                );

                if (res.status === 200) {
                    this.product = res.data.data;

                    this.getRelatedProducts(this.product);
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
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
                    this.related_products = response.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },

        async checkWishlistStatus() {
            try {
                // In a real app, this would check against your API
                // This is just a mock implementation
                this.isInWishlist = false;
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
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
                    this.$q.notify({
                        message: this.__(":name added to cart", {
                            ":name": this.product.name,
                        }),
                        color: "positive",
                        icon: "shopping_cart",
                        position: "top-right",
                    });
                    this.status = true;
                }
            } catch (e) {
                this.status = false;

                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.status == 401) {
                    this.$q.notify({
                        message: this.__(
                            "To continue the process, Please login first"
                        ),
                        color: "warning",
                        icon: "shopping_cart",
                        position: "top-right",
                    });
                }

                if (e?.response?.data?.message) {
                    this.$q.notify({
                        message: e.response.data.message,
                        color: "warning",
                        icon: "shopping_cart",
                        position: "top-right",
                    });
                }
            }
        },

        async buyNow() {
            await this.addToCart();

            if (this.status) {
                window.location.href = this.$page.props.routes.orders;
            }
        },

        toggleWishlist() {
            this.isInWishlist = !this.isInWishlist;

            this.$q.notify({
                message: this.isInWishlist
                    ? "Added to wishlist"
                    : "Removed from wishlist",
                color: this.isInWishlist ? "positive" : "grey",
                icon: this.isInWishlist ? "favorite" : "favorite_border",
                position: "top-right",
            });

            // In a real app, this would call your API
            if (this.isInWishlist) {
                // Add to wishlist API call
            } else {
                // Remove from wishlist API call
            }
        },

        submitReview() {
            // In a real app, this would call your API
            console.log("Submitting review:", this.newReview);

            this.$q.notify({
                message: "Thank you for your review!",
                color: "positive",
                icon: "thumb_up",
            });

            this.showReviewDialog = false;
            this.newReview = { rating: 5, title: "", comment: "" };
        },

        submitQuestion() {
            // In a real app, this would call your API
            console.log("Submitting question:", this.newQuestion);

            this.$q.notify({
                message: "Your question has been submitted!",
                color: "positive",
                icon: "help",
            });

            this.showQuestionDialog = false;
            this.newQuestion = { text: "" };
        },

        addRelatedToCart(product) {
            const item = {
                product: product,
                quantity: 1,
                selectedAttributes: {},
            };

            // In a real app, this would call your API
            console.log("Adding related product to cart:", item);

            this.$q.notify({
                message: `${product.name} added to cart`,
                color: "positive",
                icon: "shopping_cart",
                position: "top-right",
            });
        },
    },
};
</script>

<style scoped>
/* Improved responsive styles */
.hover-zoom {
    transition: transform 0.3s;
}
.hover-zoom:hover {
    transform: scale(1.02);
}

.product-image-thumb-active {
    border: 2px solid var(--q-primary);
}

.ellipsis-2-lines {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ellipsis-3-lines {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Better image loading animation */
.q-img__loading .q-spinner {
    opacity: 0;
    animation: fadeIn 0.5s ease-in-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Responsive adjustments */
@media (max-width: 599px) {
    .q-tab__label {
        font-size: 0.8rem;
    }

    .q-carousel {
        border-radius: 0 !important;
    }

    .q-tab-panel {
        padding: 16px 0;
    }
}

/* Better thumbnail scroll on mobile */
.scroll {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
}
.scroll::-webkit-scrollbar {
    display: none; /* Chrome/Safari */
}
</style>
